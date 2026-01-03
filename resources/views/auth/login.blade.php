<x-guest-layout>
    <!-- Page Title -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-cobalt-600 text-center">Welcome Back</h2>
        <p class="mt-2 text-center text-sm text-gray-600">Sign in to your account to continue</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-semibold" />
            <div class="relative mt-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <x-text-input id="email" class="block w-full pl-10 input-modern" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
            <div class="relative mt-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input id="password" class="block w-full pl-10 input-modern"
                                type="password"
                                name="password"
                                required autocomplete="current-password"
                                placeholder="Enter your password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500" name="remember">
                <span class="ms-2 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-primary-600 hover:text-primary-800 font-semibold underline underline-offset-2 hover:underline-offset-4 transition-all" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div>
            <x-primary-button class="w-full justify-center bg-gradient-to-r from-primary-600 to-cobalt-600 hover:from-primary-700 hover:to-cobalt-700 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                {{ __('Sign In') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Demo Credentials Info -->
    <div class="mt-8 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border-2 border-blue-100 shadow-md">
        <div class="flex items-center mb-3">
            <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm font-bold text-blue-900">Demo Credentials</p>
        </div>
        <div class="text-xs text-blue-800 space-y-2">
            <div class="bg-white/50 p-2 rounded-lg">
                <p><strong>Super Admin:</strong> admin@hrmanagement.com / password</p>
            </div>
            <div class="bg-white/50 p-2 rounded-lg">
                <p><strong>HR Admin:</strong> hr@hrmanagement.com / password</p>
            </div>
            <div class="bg-white/50 p-2 rounded-lg">
                <p><strong>IT User:</strong> it@hrmanagement.com / password</p>
            </div>
        </div>
    </div>
</x-guest-layout>
