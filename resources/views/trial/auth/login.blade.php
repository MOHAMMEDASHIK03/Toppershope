<x-panel.auth-page
    page-title="Trial Sign In"
    heading="Trial Portal"
    subtitle="Log in to your 5-day preview access."
    :form-action="route('trial.login.submit')"
    button-label="Start trial preview"
    email-field="trial_email"
    email-label="Trial login ID (email)"
    email-placeholder="trial_1234@toppershope.com"
    :show-remember="false"
    accent="emerald"
>
    <x-slot:beforeForm>
        @if(session('expired'))
            <div class="mb-5 p-4 rounded-lg text-sm font-medium bg-amber-50 border border-amber-200 text-amber-800">
                Your trial access has expired. Please contact the admission team to enrol in the full batch.
            </div>
        @endif
    </x-slot:beforeForm>
</x-panel.auth-page>
