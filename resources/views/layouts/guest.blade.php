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
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Enhanced Tailwind CSS for guest layout */
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body { 
                    font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                }
                .font-sans { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
                .text-gray-900 { color: rgb(17 24 39); }
                .text-gray-600 { color: rgb(75 85 99); }
                .text-gray-500 { color: rgb(107 114 128); }
                .text-blue-600 { color: rgb(37 99 235); }
                .text-white { color: white; }
                .antialiased { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
                .min-h-screen { min-height: 100vh; }
                .flex { display: flex; }
                .flex-col { flex-direction: column; }
                .items-center { align-items: center; }
                .justify-center { justify-content: center; }
                .pt-6 { padding-top: 1.5rem; }
                .pb-6 { padding-bottom: 1.5rem; }
                .w-full { width: 100%; }
                .max-w-md { max-width: 28rem; }
                .mt-6 { margin-top: 1.5rem; }
                .mt-4 { margin-top: 1rem; }
                .mb-8 { margin-bottom: 2rem; }
                .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
                .px-8 { padding-left: 2rem; padding-right: 2rem; }
                .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
                .py-8 { padding-top: 2rem; padding-bottom: 2rem; }
                .py-10 { padding-top: 2.5rem; padding-bottom: 2.5rem; }
                .bg-white { background-color: rgb(255 255 255); }
                .shadow-2xl { box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25); }
                .overflow-hidden { overflow: hidden; }
                .rounded-2xl { border-radius: 1rem; }
                .text-center { text-align: center; }
                .text-3xl { font-size: 1.875rem; line-height: 2.25rem; }
                .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
                .font-bold { font-weight: 700; }
                .font-semibold { font-weight: 600; }
                @media (min-width: 640px) {
                    .sm\:max-w-md { max-width: 28rem; }
                    .sm\:px-10 { padding-left: 2.5rem; padding-right: 2.5rem; }
                    .sm\:py-12 { padding-top: 3rem; padding-bottom: 3rem; }
                }
            </style>
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center pt-6 pb-6 px-6">
            <div class="w-full max-w-md">
                <!-- Logo/Brand Section -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-4">
                        <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">HR Management</h1>
                    <p class="text-white text-sm">Employee Lifecycle Management System</p>
                </div>

                <!-- Card -->
                <div class="bg-white shadow-2xl overflow-hidden rounded-2xl">
                    <div class="px-8 py-10 sm:px-10 sm:py-12">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 text-center">
                    <p class="text-white text-sm">
                        © {{ date('Y') }} HR Management System. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
