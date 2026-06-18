<x-panel.auth-page
    page-title="New Password"
    heading="Create new password"
    :subtitle="'Set a new password for ' . $email"
    :form-action="route('admin.password.forgot.reset.submit')"
    button-label="Update password"
    :show-remember="false"
    accent="orange"
>
    <x-slot:fields>
        <div class="mb-4 p-3 rounded-lg bg-slate-50 border border-slate-200 text-xs text-slate-600 leading-relaxed">
            Password must be at least <strong>8 characters</strong> and include at least one
            <strong>uppercase</strong> letter, one <strong>number</strong>, and one <strong>symbol</strong>.
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5" for="password">New password</label>
            <input type="password" name="password" id="password" required autofocus
                class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-lg focus:ring-primary-600/15 focus:border-primary-500 outline-none text-slate-900">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5" for="password_confirmation">Confirm new password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-lg focus:ring-primary-600/15 focus:border-primary-500 outline-none text-slate-900">
        </div>
    </x-slot:fields>

    <x-slot:afterForm>
        <p class="text-center mt-5 text-sm text-slate-500">
            <a href="{{ route('admin.login') }}" class="font-semibold text-primary-700 hover:text-primary-700">Back to sign in</a>
        </p>
    </x-slot:afterForm>
</x-panel.auth-page>
