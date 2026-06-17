<?php

namespace App\Services\Admin;

use App\Mail\AdminPasswordResetOtpMail;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminPasswordResetOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminPasswordResetService
{
    public const OTP_LENGTH = 6;
    public const OTP_TTL_MINUTES = 10;
    public const MAX_ATTEMPTS = 5;
    public const SESSION_EMAIL_KEY = 'admin_password_reset.email';
    public const SESSION_VERIFIED_KEY = 'admin_password_reset.verified';

    public function sendOtp(string $email): void
    {
        $otp = str_pad((string) random_int(0, 999999), self::OTP_LENGTH, '0', STR_PAD_LEFT);

        AdminPasswordResetOtp::where('email', $email)->delete();

        AdminPasswordResetOtp::create([
            'email'      => $email,
            'otp_hash'   => Hash::make($otp),
            'attempts'   => 0,
            'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES),
        ]);

        Mail::to($email)->send(new AdminPasswordResetOtpMail($otp));
    }

    public function verifyOtp(string $email, string $otp): bool
    {
        $record = AdminPasswordResetOtp::where('email', $email)->latest()->first();

        if (! $record || $record->expires_at->isPast()) {
            return false;
        }

        if ($record->attempts >= self::MAX_ATTEMPTS) {
            return false;
        }

        if (! Hash::check($otp, $record->otp_hash)) {
            $record->increment('attempts');
            return false;
        }

        AdminPasswordResetOtp::where('email', $email)->delete();

        return true;
    }

    public function resetPassword(string $email, string $password): void
    {
        $admin = Admin::where('email', $email)->firstOrFail();
        $admin->update(['password' => Hash::make($password)]);
    }

    public function clearSession(): void
    {
        session()->forget([self::SESSION_EMAIL_KEY, self::SESSION_VERIFIED_KEY]);
    }
}
