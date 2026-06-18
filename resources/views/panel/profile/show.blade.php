@extends($config['layout'])

@section('title', 'My Profile')
@section('page_title', 'My Profile')

@section('content')
@php
    $canUpdate = $config['can_update'] ?? false;
    $canChangePassword = $config['can_change_password'] ?? false;
    $updateRoute = route($config['profile_route'] . '.update');
@endphp

<div class="max-w-2xl">
    <div class="bg-white dark:bg-[#1E1E24] border border-gray-200 dark:border-[#2D2D35] rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-primary-50 to-primary-100/60 dark:from-primary-900/20 dark:to-primary-900/10 border-b border-gray-200 dark:border-[#2D2D35]">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-600 to-primary-700 flex items-center justify-center text-2xl font-bold text-white shadow-md shrink-0">
                    {{ \App\Support\PanelProfile::initial($user) }}
                </div>
                <div class="min-w-0">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $displayEmail }}</p>
                    <p class="text-xs font-semibold text-primary-600 dark:text-primary-400 mt-1">{{ $roleLabel }}</p>
                </div>
            </div>
        </div>

        @if($canUpdate)
            <form action="{{ $updateRoute }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                @foreach($editableFields as $field)
                    @php
                        $value = old($field['name'], $user->{$field['name']} ?? '');
                        if ($field['name'] === 'target_exam' && $value) {
                            $value = strtolower((string) $value);
                        }
                        if ($field['name'] === 'dob' && $value) {
                            $value = \Illuminate\Support\Carbon::parse($value)->format('Y-m-d');
                        }
                    @endphp

                    @if(($field['type'] ?? 'text') === 'select')
                        <x-form.field :label="$field['label']" :name="$field['name']" type="select" :required="$field['required'] ?? false">
                            @foreach($field['options'] as $optionValue => $optionLabel)
                                <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                            @endforeach
                        </x-form.field>
                    @else
                        <x-form.field
                            :label="$field['label']"
                            :name="$field['name']"
                            :type="$field['type'] ?? 'text'"
                            :value="$value"
                            :required="$field['required'] ?? false"
                        />
                    @endif
                @endforeach

                @foreach($readOnlyFields as $field)
                    @if(in_array($field['name'], ['email', 'trial_email'], true))
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ $field['label'] }}</label>
                            <input type="email" value="{{ $field['value'] }}" disabled
                                class="w-full rounded-xl border border-gray-200 dark:border-[#2D2D35] bg-gray-100 dark:bg-[#17171C] px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400 cursor-not-allowed">
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Email cannot be changed.</p>
                        </div>
                    @endif
                @endforeach

                @if($canChangePassword)
                    <div class="pt-2 border-t border-gray-100 dark:border-[#2D2D35]">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4">Change Password</h3>
                        <div class="space-y-4">
                            <x-form.field label="Current Password" name="current_password" type="password" autocomplete="current-password" />
                            <x-form.field label="New Password" name="password" type="password" autocomplete="new-password" hint="Minimum 8 characters." />
                            <x-form.field label="Confirm New Password" name="password_confirmation" type="password" autocomplete="new-password" />
                        </div>
                    </div>
                @endif

                <div class="pt-2">
                    <button type="submit" class="px-6 py-3 rounded-xl btn-primary text-white font-bold text-sm transition-all shadow-lg shadow-primary-500/10">
                        Save Changes
                    </button>
                </div>
            </form>
        @else
            <div class="p-6 space-y-4">
                @foreach($readOnlyFields as $field)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ $field['label'] }}</label>
                        <input type="text" value="{{ $field['value'] }}" disabled
                            class="w-full rounded-xl border border-gray-200 dark:border-[#2D2D35] bg-gray-100 dark:bg-[#17171C] px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 cursor-not-allowed">
                    </div>
                @endforeach
                <p class="text-sm text-gray-500 dark:text-gray-400 pt-2">
                    Trial credentials are managed by the admissions team. Contact them to update your access.
                </p>
            </div>
        @endif
    </div>

    @if($canUpdate)
        <div class="bg-white dark:bg-[#1E1E24] border border-gray-200 dark:border-[#2D2D35] rounded-xl shadow-sm p-6 mt-6">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Account Information</h3>
            <div class="space-y-2 text-sm">
                @foreach($readOnlyFields as $field)
                    @if(!in_array($field['name'], ['email', 'trial_email'], true))
                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500 dark:text-gray-400">{{ $field['label'] }}</span>
                            <span class="text-gray-800 dark:text-gray-200 font-medium text-right">{{ $field['value'] }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
