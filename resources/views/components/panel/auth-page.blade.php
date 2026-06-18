@props([
    'pageTitle' => 'Sign In',
    'heading',
    'subtitle',
    'formAction',
    'buttonLabel' => 'Sign in',
    'emailField' => 'email',
    'emailLabel' => 'Email',
    'emailPlaceholder' => null,
    'showRemember' => true,
])
<!DOCTYPE html>
<html lang="en" class="h-full antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} | Topper's Hope</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1/src/index.js" type="module"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-white dark:bg-[#0F0F12] flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <x-brand.logo variant="full" context="auth" class="mb-4" />
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $heading }}</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ $subtitle }}</p>
        </div>

        <div class="bg-white dark:bg-[#1E1E24] border border-gray-200 dark:border-[#2D2D35] rounded-2xl shadow-sm p-8">
            {{ $beforeForm ?? '' }}

            <form action="{{ $formAction }}" method="POST" class="space-y-5">
                @csrf
                {{ $fields ?? '' }}

                @unless(isset($fields))
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="{{ $emailField }}">{{ $emailLabel }}</label>
                    <input type="email" name="{{ $emailField }}" id="{{ $emailField }}" value="{{ old($emailField) }}" required autofocus
                        @if($emailPlaceholder) placeholder="{{ $emailPlaceholder }}" @endif
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-[#2D2D35] rounded-lg focus:ring-4 focus:ring-primary-600/15 focus:border-primary-600 outline-none text-gray-900 dark:text-white dark:bg-[#17171C]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="password">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-[#2D2D35] rounded-lg focus:ring-4 focus:ring-primary-600/15 focus:border-primary-600 outline-none text-gray-900 dark:text-white dark:bg-[#17171C]">
                </div>
                @endunless

                @if($showRemember)
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600/20">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Keep me signed in</span>
                </label>
                @endif

                <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                    {{ $buttonLabel }}
                    <i class="ph ph-arrow-right"></i>
                </button>
            </form>

            {{ $afterForm ?? '' }}
        </div>

        <div class="flex items-center justify-center gap-3 mt-6">
            <x-panel.theme-toggle />
            <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Topper's Hope</p>
        </div>
    </div>

    <x-toast-stack />
    @stack('scripts')
</body>
</html>
