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
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased bg-gray-50">
        <div class="min-h-screen flex">
            <!-- Left Side - Branding -->
            {{-- <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 to-indigo-700 p-12 flex-col justify-between">
                <div>
                    <h1 class="text-white text-4xl font-bold mb-4">CalorieMaps</h1>
                    <p class="text-blue-100 text-lg">Temukan dan jelajahi tempat makan dengan informasi nutrisi</p>
                </div>
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="bg-white/10 rounded-lg p-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold mb-1">Berbasis Lokasi</h3>
                            <p class="text-blue-100 text-sm">Temukan restoran terdekat dengan peta interaktif</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="bg-white/10 rounded-lg p-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold mb-1">Informasi Kalori</h3>
                            <p class="text-blue-100 text-sm">Dapatkan data nutrisi lengkap untuk setiap menu</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="bg-white/10 rounded-lg p-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold mb-1">Ulasan & Rating</h3>
                            <p class="text-blue-100 text-sm">Bagikan dan baca pengalaman komunitas</p>
                        </div>
                    </div>
                </div>
                <div class="text-blue-100 text-sm">
                    © 2025 CalorieMaps. Hak cipta dilindungi.
                </div>
            </div> --}}

            <!-- Right Side - Form -->
            <div class="flex-1 flex items-center justify-center p-8">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
