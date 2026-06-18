<x-panel.auth-page
    page-title="Forgot Password"
    heading="Reset admin password"
    subtitle="Enter the email address registered on your admin account."
    :form-action="route('admin.password.forgot.send')"
    button-label="Send verification code"
    :show-remember="false"
    accent="orange"
>
    <x-slot:fields>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5" for="email">Email address</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                placeholder="admin@toppershope.com"
                class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-lg focus:ring-primary-600/15 focus:border-primary-500 outline-none text-slate-900">
        </div>
    </x-slot:fields>

    <x-slot:afterForm>
        <p class="text-center mt-5 text-sm text-slate-500">
            Remember your password?
            <a href="{{ route('admin.login') }}" class="font-semibold text-primary-700 hover:text-primary-700">Back to sign in</a>
        </p>
    </x-slot:afterForm>
</x-panel.auth-page>
