<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-200">
        <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-clinic-50 via-white to-teal-50 relative overflow-hidden">
            {{-- Background decorative elements --}}
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-clinic-200/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-teal-200/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="w-full sm:max-w-md relative z-10">
                <div class="flex flex-col items-center mb-8">
                    <a href="/" class="group flex flex-col items-center gap-3">
                        <x-application-logo class="h-28 w-auto object-contain transition-transform duration-300 group-hover:scale-105" />
                    </a>
                </div>


                <div class="w-full bg-white rounded-2xl border border-gray-200/60 shadow-2xl shadow-slate-200/80 overflow-hidden p-6 sm:p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>

</html>
