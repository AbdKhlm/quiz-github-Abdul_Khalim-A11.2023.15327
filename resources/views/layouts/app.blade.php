<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Poliklinik') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daisyui@5/daisyui.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <div class="min-h-screen flex flex-col">

        <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-hospital text-red-500 text-2xl"></i>
                    <span class="text-xl font-bold">{{ config('app.name', 'Poliklinik') }}</span>
                </div>

                <div class="hidden sm:flex items-center gap-4 text-sm font-medium">
                    <a href="{{ url('/') }}" class="hover:text-red-500 transition">Beranda</a>
                </div>
            </nav>
        </header>

        <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </main>

        <footer class="border-t border-gray-200 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-500 flex flex-col sm:flex-row justify-between items-center gap-2">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Poliklinik') }}. All rights reserved.</p>
                <p>Built with <i class="fa-solid fa-heart text-red-500"></i> using Laravel & Tailwind CSS</p>
            </div>
        </footer>

    </div>

    @stack('scripts')
</body>
</html>
