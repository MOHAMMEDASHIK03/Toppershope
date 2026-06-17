<x-panel.auth-page
    page-title="Student Sign In"
    heading="Student learning"
    subtitle="Sign in to access your courses, test series, and doubt forum."
    :form-action="route('login')"
    button-label="Sign in"
    accent="orange"
>
    <x-slot:afterForm>
        <p class="text-center text-sm text-slate-500 mt-6 pt-6 border-t border-slate-100">
            New to Topper&rsquo;s Hope?
            <a href="{{ route('register') }}" class="font-semibold text-orange-600 hover:text-orange-700">Create an account</a>
        </p>
    </x-slot:afterForm>
</x-panel.auth-page>
