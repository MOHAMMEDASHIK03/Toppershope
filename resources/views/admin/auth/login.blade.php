<x-panel.auth-page
    page-title="Admin Sign In"
    heading="Admin Console"
    subtitle="Sign in with your super admin credentials."
    :form-action="route('admin.login')"
    accent="orange"
>
    <x-slot:afterForm>
        <p class="text-center mt-5 text-sm text-slate-500">
            <a href="{{ route('admin.password.forgot') }}" class="font-semibold text-orange-600 hover:text-orange-700">Forgot password?</a>
        </p>
    </x-slot:afterForm>
</x-panel.auth-page>
