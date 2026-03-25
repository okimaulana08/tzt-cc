<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0
                bg-gradient-to-br from-slate-100 via-violet-50 to-indigo-100">

        <!-- Logo -->
        <div class="mb-8">
            <a href="/" class="flex flex-col items-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-600 to-purple-700
                            flex items-center justify-center shadow-lg">
                    <x-application-logo class="w-8 h-8 fill-current text-white" />
                </div>
                <span class="text-xl font-bold text-gray-800">{{ config('app.name') }}</span>
            </a>
        </div>

        <!-- Card -->
        <div class="w-full sm:max-w-md px-6 py-8
                    bg-white/80 backdrop-blur-sm
                    shadow-xl shadow-violet-100 rounded-2xl border border-white">
            {{ $slot }}
        </div>

    </div>

</body>
</html>
