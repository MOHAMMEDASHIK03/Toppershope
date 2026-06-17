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
    'accent' => 'indigo',
])

@php
    $accentMap = [
        'indigo' => ['logo' => 'from-orange-500 to-amber-500', 'btn' => 'bg-orange-500 hover:bg-orange-600', 'ring' => 'focus:ring-orange-500/20 focus:border-orange-500'],
        'orange' => ['logo' => 'from-orange-500 to-amber-500', 'btn' => 'bg-orange-500 hover:bg-orange-600', 'ring' => 'focus:ring-orange-500/20 focus:border-orange-500'],
        'emerald' => ['logo' => 'from-emerald-600 to-teal-600', 'btn' => 'bg-orange-500 hover:bg-orange-600', 'ring' => 'focus:ring-orange-500/20 focus:border-orange-500'],
    ];
    $accentClasses = $accentMap[$accent] ?? $accentMap['indigo'];
@endphp
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
<body class="min-h-screen bg-[#f4f6f8] flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-14 h-14 mx-auto rounded-xl bg-gradient-to-br {{ $accentClasses['logo'] }} flex items-center justify-center text-white font-bold text-lg shadow-lg mb-4">TH</div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $heading }}</h1>
            <p class="text-slate-500 text-sm mt-1">{{ $subtitle }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8">
            {{ $beforeForm ?? '' }}

            <form action="{{ $formAction }}" method="POST" class="space-y-5">
                @csrf
                {{ $fields ?? '' }}

                @unless(isset($fields))
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="{{ $emailField }}">{{ $emailLabel }}</label>
                    <input type="email" name="{{ $emailField }}" id="{{ $emailField }}" value="{{ old($emailField) }}" required autofocus
                        @if($emailPlaceholder) placeholder="{{ $emailPlaceholder }}" @endif
                        class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-lg {{ $accentClasses['ring'] }} outline-none text-slate-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="password">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-lg {{ $accentClasses['ring'] }} outline-none text-slate-900">
                </div>
                @endunless

                @if($showRemember)
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-slate-600">Keep me signed in</span>
                </label>
                @endif

                <button type="submit" class="w-full py-2.5 {{ $accentClasses['btn'] }} text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                    {{ $buttonLabel }}
                    <i class="ph ph-arrow-right"></i>
                </button>
            </form>

            {{ $afterForm ?? '' }}
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">&copy; {{ date('Y') }} Topper's Hope</p>
    </div>

    <x-toast-stack />
    @stack('scripts')
</body>
</html>
