<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Batch;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Support\Str;
use App\Http\Controllers\RazorpayController;

class CheckoutController extends Controller
{
    public function show($uuid)
    {
        $batch = Batch::with('course')->where('uuid', $uuid)->firstOrFail();

        if ($error = $this->batchEnrollmentError($batch)) {
            return redirect()->route('dashboard')->with('error', $error);
        }

        if (Auth::check()) {
            $hasEnrollment = Enrollment::where('user_id', Auth::id())
                ->where('batch_id', $batch->id)
                ->exists();

            if ($hasEnrollment) {
                return redirect()->route('dashboard')->with('info', 'You are already enrolled in this batch.');
            }
        }

        return view('checkout.show', compact('batch'));
    }

    public function process(Request $request, $uuid)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please log in to continue.'], 401);
        }

        $batch = Batch::where('uuid', $uuid)->firstOrFail();

        if ($error = $this->batchEnrollmentError($batch)) {
            return response()->json(['error' => $error], 422);
        }

        if (Enrollment::where('user_id', Auth::id())->where('batch_id', $batch->id)->exists()) {
            return response()->json(['error' => 'You are already enrolled in this batch.'], 422);
        }

        if (!config('services.razorpay.key') || !config('services.razorpay.secret')) {
            return response()->json(['error' => 'Payment gateway is not configured. Contact support.'], 503);
        }

        // Reuse a recent pending order for the same user/batch/amount to avoid duplicate charges
        $existingPayment = Payment::where('user_id', Auth::id())
            ->where('batch_id', $batch->id)
            ->where('status', 'pending')
            ->where('amount', $batch->price)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->latest()
            ->first();

        if ($existingPayment?->razorpay_order_id) {
            return response()->json([
                'order_id' => $existingPayment->razorpay_order_id,
                'amount'   => (int) round($batch->price * 100),
                'currency' => 'INR',
                'key'      => config('services.razorpay.key'),
            ]);
        }

        $razorpay = new RazorpayController();
        $request->merge(['amount' => $batch->price]);
        $response = $razorpay->createOrder($request);

        $data = json_decode($response->getContent(), true);

        if (!isset($data['order_id'])) {
            return response()->json(['error' => $data['error'] ?? 'Unable to create payment order.'], 500);
        }

        Payment::create([
            'user_id'           => Auth::id(),
            'batch_id'          => $batch->id,
            'amount'            => $batch->price,
            'razorpay_order_id' => $data['order_id'],
            'status'            => 'pending',
        ]);

        return response()->json($data);
    }

    public function verify(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Session expired. Please log in again.'], 401);
        }

        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
            'batch_id'            => 'required|exists:batches,id',
        ]);

        $payment = Payment::where('razorpay_order_id', $request->razorpay_order_id)->first();

        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Payment record not found.'], 404);
        }

        if ($payment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized payment verification.'], 403);
        }

        if ((int) $payment->batch_id !== (int) $request->batch_id) {
            return response()->json(['success' => false, 'message' => 'Batch mismatch for this payment.'], 422);
        }

        $batch = Batch::findOrFail($payment->batch_id);

        // Idempotent: already completed payment
        if ($payment->status === 'success') {
            return response()->json(['success' => true, 'redirect' => route('dashboard')]);
        }

        $razorpay = new RazorpayController();
        $response = $razorpay->verifyPayment($request);
        $data = json_decode($response->getContent(), true);

        if (!isset($data['success']) || !$data['success']) {
            $payment->update(['status' => 'failed']);
            return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 400);
        }

        $payment->update([
            'razorpay_payment_id'  => $request->razorpay_payment_id,
            'razorpay_signature'   => $request->razorpay_signature,
            'status'               => 'success',
        ]);

        $enrollment = Enrollment::firstOrCreate(
            ['user_id' => Auth::id(), 'batch_id' => $batch->id],
            ['uuid' => Str::uuid(), 'status' => 'active']
        );

        if ($enrollment->wasRecentlyCreated) {
            $batch->increment('filled_seats');
        }

        return response()->json(['success' => true, 'redirect' => route('dashboard')]);
    }

    /**
     * Shared rules before a student can pay for a batch.
     */
    private function batchEnrollmentError(Batch $batch): ?string
    {
        if ($batch->is_upcoming) {
            return 'This batch is not open for enrollment yet.';
        }

        if ($batch->status === 'closed') {
            return 'This batch is closed for enrollment.';
        }

        if ($batch->total_seats > 0 && $batch->filled_seats >= $batch->total_seats) {
            return 'This batch is full. No seats available.';
        }

        if ($batch->price <= 0) {
            return 'This batch does not have a valid price configured.';
        }

        return null;
    }
}
