<x-panel.auth-page
    page-title="Verify Code"
    heading="Enter verification code"
    :subtitle="'We sent a 6-digit code to ' . $email"
    :form-action="route('admin.password.forgot.verify.submit')"
    button-label="Verify code"
    :show-remember="false"
    accent="orange"
>
    <x-slot:fields>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5" for="otp">6-digit OTP</label>
            <input type="text" name="otp" id="otp" value="{{ old('otp') }}" required autofocus
                inputmode="numeric" pattern="[0-9]{6}" maxlength="6" minlength="6"
                placeholder="000000"
                class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-lg tracking-[0.4em] text-center font-bold focus:ring-orange-500/20 focus:border-orange-500 outline-none text-slate-900">
            <p class="text-xs text-slate-500 mt-2">Code expires in {{ \App\Services\Admin\AdminPasswordResetService::OTP_TTL_MINUTES }} minutes.</p>
        </div>
    </x-slot:fields>

    <x-slot:afterForm>
        <form action="{{ route('admin.password.forgot.resend') }}" method="POST" class="mt-4 text-center">
            @csrf
            <button type="submit" class="text-sm font-semibold text-orange-600 hover:text-orange-700">
                Resend code
            </button>
        </form>
        <p class="text-center mt-4 text-sm text-slate-500">
            <a href="{{ route('admin.password.forgot') }}" class="font-semibold text-orange-600 hover:text-orange-700">Use a different email</a>
        </p>
    </x-slot:afterForm>
</x-panel.auth-page>

@push('scripts')
<script>
    document.getElementById('otp')?.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 6);
    });
</script>
@endpush
