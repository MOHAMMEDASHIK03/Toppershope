<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RazorpayController extends Controller
{
    private $keyId;
    private $keySecret;

    public function __construct()
    {
        // Typically stored in .env config
        $this->keyId = config('services.razorpay.key');
        $this->keySecret = config('services.razorpay.secret');
    }

    /**
     * Create an order directly using Razorpay REST API without the heavy SDK.
     */
    public function createOrder(Request $request)
    {
        if (!$this->keyId || !$this->keySecret) {
            return response()->json(['error' => 'Razorpay credentials are not configured.'], 503);
        }

        $amount = (int) round($request->amount * 100); // rupees → paise
        $receiptId = 'rcptid_' . uniqid();

        $response = Http::withBasicAuth($this->keyId, $this->keySecret)
            ->post('https://api.razorpay.com/v1/orders', [
                'amount' => $amount,
                'currency' => 'INR',
                'receipt' => $receiptId,
                'payment_capture' => 1
            ]);

        if ($response->successful()) {
            return response()->json([
                'order_id' => $response['id'],
                'amount' => $response['amount'],
                'currency' => $response['currency'],
                'key' => $this->keyId,
            ]);
        }

        \Illuminate\Support\Facades\Log::error('Razorpay Order Creation Failed: ' . $response->body());
        return response()->json(['error' => 'Unable to create order'], 500);
    }

    /**
     * Verify payment signature synchronously (shared hosting safe, no queue required)
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required'
        ]);

        $attributes = [
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature
        ];

        // Perform hashing logic natively
        $expectedSignature = hash_hmac('sha256', $attributes['razorpay_order_id'] . '|' . $attributes['razorpay_payment_id'], $this->keySecret);

        if (hash_equals($expectedSignature, $attributes['razorpay_signature'])) {
            // Verification successful
            // Find order in database, update status to paid, grant course access, etc.
            
            // e.g. Order::where('order_id', $attributes['razorpay_order_id'])->update(['status' => 'paid']);
            
            return response()->json(['success' => true, 'message' => 'Payment verified successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Payment signature verification failed.'], 400);
    }
}
