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
                
                /* Basic Tailwind CSS for app layout when Vite is not built */
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body { font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif; background-color: rgb(243 244 246); }
                .font-sans { font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif; }
                .antialiased { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
                .flex { display: flex; }
                .flex-1 { flex: 1 1 0%; }
                .flex-col { flex-direction: column; }
                .flex-shrink-0 { flex-shrink: 0; }
                .h-screen { height: 100vh; }
                .bg-gray-100 { background-color: rgb(243 244 246); }
                .overflow-hidden { overflow: hidden; }
                .bg-white { background-color: rgb(255 255 255); }
                .shadow-sm { box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); }
                .shadow-2xl { box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25); }
                .border-b { border-bottom-width: 1px; }
                .border-gray-200 { border-color: rgb(229 231 235); }
                .z-10 { z-index: 10; }
                .items-center { align-items: center; }
                .justify-between { justify-content: space-between; }
                .justify-center { justify-content: center; }
                .h-16 { height: 4rem; }
                .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
                .py-2\.5 { padding-top: 0.625rem; padding-bottom: 0.625rem; }
                .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
                .space-x-4 > :not(:last-child) { margin-right: 1rem; }
                .relative { position: relative; }
                .text-gray-600 { color: rgb(75 85 99); }
                .text-gray-300 { color: rgb(209 213 219); }
                .h-6 { height: 1.5rem; }
                .w-6 { width: 1.5rem; }
                .h-5 { height: 1.25rem; }
                .w-5 { width: 1.25rem; }
                .h-4 { height: 1rem; }
                .w-4 { width: 1rem; }
                .h-9 { height: 2.25rem; }
                .w-9 { width: 2.25rem; }
                .w-64 { width: 16rem; }
                .mr-3 { margin-right: 0.75rem; }
                .absolute { position: absolute; }
                .-top-1 { top: -0.25rem; }
                .-right-1 { right: -0.25rem; }
                .inline-flex { display: inline-flex; }
                .text-xs { font-size: 0.75rem; line-height: 1rem; }
                .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
                .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
                .font-bold { font-weight: 700; }
                .font-medium { font-weight: 500; }
                .text-white { color: rgb(255 255 255); }
                .bg-red-500 { background-color: rgb(239 68 68); }
                .rounded-full { border-radius: 9999px; }
                .rounded-xl { border-radius: 0.75rem; }
                .text-gray-700 { color: rgb(55 65 81); }
                .ml-2 { margin-left: 0.5rem; }
                .overflow-x-hidden { overflow-x: hidden; }
                .overflow-y-auto { overflow-y: auto; }
                .transition-colors { transition-property: color, background-color, border-color, text-decoration-color, fill, stroke; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }
                .duration-150 { transition-duration: 150ms; }
                .hidden { display: none; }
                
                /* Color Classes for Professional Blue Theme */
                .hover\:text-navy-600:hover { color: var(--color-navy-600); }
                .text-cobalt-400 { color: var(--color-cobalt-400); }
                .bg-primary-400 { background-color: var(--color-primary-400); }
                
                /* Gradient Classes */
                .gradient-navy { background: linear-gradient(135deg, var(--color-navy-800) 0%, var(--color-navy-900) 100%); }
                .from-primary-500 { --tw-gradient-from: var(--color-primary-500); }
                .to-cobalt-600 { --tw-gradient-to: var(--color-cobalt-600); }
                .bg-gradient-to-r { background-image: linear-gradient(to right, var(--tw-gradient-from, transparent), var(--tw-gradient-to, transparent)); }
                
                /* Media queries for responsive design */
                @media (min-width: 768px) {
                    .md\:flex { display: flex; }
                    .md\:flex-col { flex-direction: column; }
                    .md\:hidden { display: none; }
                }
            </style>
        @endif
    </head>
    <body class="font-sans antialiased">
        <div class="flex h-screen bg-gray-100 overflow-hidden">
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header Bar -->
                <header class="bg-white shadow-sm border-b border-gray-200 z-10">
                    <div class="flex items-center justify-between h-16 px-6">
                        <!-- Page Title -->
                        <div>
                            @isset($header)
                                {{ $header }}
                            @endisset
                        </div>

                        <!-- Right Side: Notifications & User Menu -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <a href="{{ route('notifications.index') }}" class="relative text-gray-600 hover:text-navy-600 transition-colors duration-150">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if(Auth::user()->notifications()->where('is_read', false)->count() > 0)
                                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                                    {{ Auth::user()->notifications()->where('is_read', false)->count() }}
                                </span>
                                @endif
                            </a>

                            <!-- User Dropdown -->
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center text-sm font-medium text-gray-700 hover:text-navy-600 focus:outline-none transition duration-150 ease-in-out">
                                        <span>{{ Auth::user()->name }}</span>
                                        <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100">
                                        {{ Auth::user()->getRoleNames()->first() ?? 'User' }}
                                    </div>
                                    
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
