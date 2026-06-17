<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Services\Admin\AdminPasswordResetService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function __construct(
        protected AdminPasswordResetService $passwordReset
    ) {}

    public function showEmailForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $admin = Admin::where('email', $validated['email'])->first();

        if (! $admin) {
            throw ValidationException::withMessages([
                'email' => 'No admin account is registered with this email address.',
            ]);
        }

        try {
            $this->passwordReset->sendOtp($admin->email);
        } catch (\Throwable $e) {
            report($e);
            return back()
                ->withInput()
                ->withErrors(['email' => 'Unable to send the verification email. Please try again later.']);
        }

        session([
            AdminPasswordResetService::SESSION_EMAIL_KEY => $admin->email,
        ]);
        session()->forget(AdminPasswordResetService::SESSION_VERIFIED_KEY);

        return redirect()
            ->route('admin.password.forgot.verify')
            ->with('success', 'A 6-digit verification code has been sent to your email.');
    }

    public function showVerifyForm(Request $request)
    {
        if (! $request->session()->has(AdminPasswordResetService::SESSION_EMAIL_KEY)) {
            return redirect()->route('admin.password.forgot');
        }

        return view('admin.auth.verify-otp', [
            'email' => $request->session()->get(AdminPasswordResetService::SESSION_EMAIL_KEY),
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $email = $request->session()->get(AdminPasswordResetService::SESSION_EMAIL_KEY);

        if (! $email) {
            return redirect()->route('admin.password.forgot');
        }

        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        if (! $this->passwordReset->verifyOtp($email, $validated['otp'])) {
            throw ValidationException::withMessages([
                'otp' => 'Invalid or expired verification code. Please try again or request a new code.',
            ]);
        }

        $request->session()->put(AdminPasswordResetService::SESSION_VERIFIED_KEY, true);

        return redirect()
            ->route('admin.password.forgot.reset')
            ->with('success', 'Code verified. Create your new password below.');
    }

    public function resendOtp(Request $request)
    {
        $email = $request->session()->get(AdminPasswordResetService::SESSION_EMAIL_KEY);

        if (! $email || ! Admin::where('email', $email)->exists()) {
            return redirect()->route('admin.password.forgot');
        }

        try {
            $this->passwordReset->sendOtp($email);
        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors(['otp' => 'Unable to resend the code. Please try again later.']);
        }

        return back()->with('success', 'A new verification code has been sent to your email.');
    }

    public function showResetForm(Request $request)
    {
        if (! $request->session()->get(AdminPasswordResetService::SESSION_VERIFIED_KEY)) {
            return redirect()->route('admin.password.forgot.verify');
        }

        return view('admin.auth.reset-password', [
            'email' => $request->session()->get(AdminPasswordResetService::SESSION_EMAIL_KEY),
        ]);
    }

    public function resetPassword(Request $request)
    {
        if (! $request->session()->get(AdminPasswordResetService::SESSION_VERIFIED_KEY)) {
            return redirect()->route('admin.password.forgot.verify');
        }

        $email = $request->session()->get(AdminPasswordResetService::SESSION_EMAIL_KEY);

        if (! $email) {
            return redirect()->route('admin.password.forgot');
        }

        $validated = $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $this->passwordReset->resetPassword($email, $validated['password']);
        $this->passwordReset->clearSession();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Your password has been updated. You can sign in with your new password.');
    }
}
