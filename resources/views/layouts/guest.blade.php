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
                /* Color Scheme Variables - Professional Blue Theme */
                :root {
                    /* Primary Blue - Vibrant modern blues */
                    --color-primary-50: #f0f9ff;
                    --color-primary-100: #e0f2fe;
                    --color-primary-200: #bae6fd;
                    --color-primary-300: #7dd3fc;
                    --color-primary-400: #38bdf8;
                    --color-primary-500: #0ea5e9;
                    --color-primary-600: #0284c7;
                    --color-primary-700: #0369a1;
                    --color-primary-800: #075985;
                    --color-primary-900: #0c4a6e;
                    --color-primary-950: #082f49;
                    
                    /* Navy - Modern deep blues for navigation */
                    --color-navy-50: #f8fafc;
                    --color-navy-100: #f1f5f9;
                    --color-navy-200: #e2e8f0;
                    --color-navy-300: #cbd5e1;
                    --color-navy-400: #94a3b8;
                    --color-navy-500: #64748b;
                    --color-navy-600: #475569;
                    --color-navy-700: #334155;
                    --color-navy-800: #1e293b;
                    --color-navy-900: #0f172a;
                    --color-navy-950: #020617;
                    
                    /* Cobalt - Bright accent blues */
                    --color-cobalt-50: #eff6ff;
                    --color-cobalt-100: #dbeafe;
                    --color-cobalt-200: #bfdbfe;
                    --color-cobalt-300: #93c5fd;
                    --color-cobalt-400: #60a5fa;
                    --color-cobalt-500: #3b82f6;
                    --color-cobalt-600: #2563eb;
                    --color-cobalt-700: #1d4ed8;
                    --color-cobalt-800: #1e40af;
                    --color-cobalt-900: #1e3a8a;
                    --color-cobalt-950: #172554;
                }
                
                /* Enhanced Tailwind CSS for guest layout */
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body { 
                    font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
                    background: linear-gradient(135deg, var(--color-primary-500) 0%, var(--color-cobalt-600) 50%, var(--color-navy-800) 100%);
                }
                .font-sans { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
                .text-gray-900 { color: rgb(17 24 39); }
                .text-gray-600 { color: rgb(75 85 99); }
                .text-gray-500 { color: rgb(107 114 128); }
                .text-blue-600 { color: rgb(37 99 235); }
                .text-white { color: white; }
                .text-primary-600 { color: var(--color-primary-600); }
                .antialiased { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
                .min-h-screen { min-height: 100vh; }
                .flex { display: flex; }
                .flex-col { flex-direction: column; }
                .items-center { align-items: center; }
                .justify-center { justify-content: center; }
                .inline-flex { display: inline-flex; }
                .pt-6 { padding-top: 1.5rem; }
                .pb-6 { padding-bottom: 1.5rem; }
                .w-full { width: 100%; }
                .w-12 { width: 3rem; }
                .w-20 { width: 5rem; }
                .h-12 { height: 3rem; }
                .h-20 { height: 5rem; }
                .max-w-md { max-width: 28rem; }
                .mt-6 { margin-top: 1.5rem; }
                .mt-4 { margin-top: 1rem; }
                .mb-2 { margin-bottom: 0.5rem; }
                .mb-4 { margin-bottom: 1rem; }
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
                .rounded-3xl { border-radius: 1.5rem; }
                .text-center { text-align: center; }
                .text-3xl { font-size: 1.875rem; line-height: 2.25rem; }
                .text-4xl { font-size: 2.25rem; line-height: 2.5rem; }
                .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
                .font-bold { font-weight: 700; }
                .font-semibold { font-weight: 600; }
                .font-medium { font-weight: 500; }
                .relative { position: relative; }
                .z-10 { z-index: 10; }
                .from-primary-500.via-cobalt-600.to-navy-800.bg-gradient-to-br { 
                    background-image: linear-gradient(to bottom right, var(--color-primary-500), var(--color-cobalt-600), var(--color-navy-800)); 
                }
                @media (min-width: 640px) {
                    .sm\:max-w-md { max-width: 28rem; }
                    .sm\:px-10 { padding-left: 2.5rem; padding-right: 2.5rem; }
                    .sm\:py-12 { padding-top: 3rem; padding-bottom: 3rem; }
                }
            </style>
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center pt-6 pb-6 px-6 bg-gradient-to-br from-primary-500 via-cobalt-600 to-navy-800 relative overflow-hidden">
            <!-- Animated background circles -->
            <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full translate-x-1/2 translate-y-1/2 blur-3xl"></div>
            
            <div class="w-full max-w-md relative z-10">
                <!-- Logo/Brand Section -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-2xl mb-4 transform hover:scale-110 transition-transform duration-300">
                        <svg class="w-12 h-12 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-white mb-2 drop-shadow-lg">HR Management</h1>
                    <p class="text-white/90 text-sm font-medium">Employee Lifecycle Management System</p>
                </div>

                <!-- Card -->
                <div class="bg-white/95 backdrop-blur-xl shadow-2xl overflow-hidden rounded-3xl border border-white/20">
                    <div class="px-8 py-10 sm:px-10 sm:py-12">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 text-center">
                    <p class="text-white/80 text-sm drop-shadow">
                        © {{ date('Y') }} HR Management System. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
