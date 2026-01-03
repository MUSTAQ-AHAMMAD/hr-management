<!-- Sidebar Navigation -->
<aside x-data="{ open: false }" class="w-64 gradient-navy text-white flex-shrink-0 hidden md:flex md:flex-col shadow-2xl">
    <!-- Logo / Brand -->
    <div class="h-16 flex items-center justify-center border-b border-white/10">
        <a href="{{ route('dashboard') }}" class="flex items-center group">
            <div class="relative">
                <svg class="h-9 w-9 text-cobalt-400 mr-3 transform group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <div class="absolute -top-1 -right-1 w-2 h-2 bg-primary-400 rounded-full animate-pulse"></div>
            </div>
            <span class="text-xl font-bold bg-gradient-to-r from-white to-primary-200 bg-clip-text text-transparent">HR System</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-primary-500 to-cobalt-600 text-white shadow-lg scale-105' : 'text-gray-300 hover:bg-white/10 hover:text-white hover:scale-105' }}">
            <svg class="h-5 w-5 mr-3 {{ request()->routeIs('dashboard') ? 'animate-pulse' : 'group-hover:scale-110' }} transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>

        <!-- Employees -->
        @can('view employees')
        <a href="{{ route('employees.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('employees.*') ? 'bg-gradient-to-r from-primary-500 to-cobalt-600 text-white shadow-lg scale-105' : 'text-gray-300 hover:bg-white/10 hover:text-white hover:scale-105' }}">
            <svg class="h-5 w-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Employees
        </a>
        @endcan

        <!-- Onboarding -->
        @can('view onboarding')
        <a href="{{ route('onboarding-requests.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('onboarding-requests.*') ? 'bg-gradient-to-r from-primary-500 to-cobalt-600 text-white shadow-lg scale-105' : 'text-gray-300 hover:bg-white/10 hover:text-white hover:scale-105' }}">
            <svg class="h-5 w-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Onboarding
        </a>
        @endcan

        <!-- Exit Clearance -->
        @can('view exit-clearance')
        <a href="{{ route('exit-clearance-requests.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('exit-clearance-requests.*') ? 'bg-gradient-to-r from-primary-500 to-cobalt-600 text-white shadow-lg scale-105' : 'text-gray-300 hover:bg-white/10 hover:text-white hover:scale-105' }}">
            <svg class="h-5 w-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Exit Clearance
        </a>
        @endcan

        <!-- My Tasks -->
        <a href="{{ route('my-tasks') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('my-tasks') ? 'bg-gradient-to-r from-primary-500 to-cobalt-600 text-white shadow-lg scale-105' : 'text-gray-300 hover:bg-white/10 hover:text-white hover:scale-105' }}">
            <svg class="h-5 w-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            My Tasks
        </a>

        <!-- Section Divider -->
        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Management
            </p>
        </div>

        <!-- Departments -->
        @can('view departments')
        <a href="{{ route('departments.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('departments.*') ? 'bg-gradient-to-r from-primary-500 to-cobalt-600 text-white shadow-lg scale-105' : 'text-gray-300 hover:bg-white/10 hover:text-white hover:scale-105' }}">
            <svg class="h-5 w-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Departments
        </a>
        @endcan

        <!-- Users -->
        @can('view users')
        <a href="{{ route('users.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-gradient-to-r from-primary-500 to-cobalt-600 text-white shadow-lg scale-105' : 'text-gray-300 hover:bg-white/10 hover:text-white hover:scale-105' }}">
            <svg class="h-5 w-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Users
        </a>
        @endcan

        <!-- Tasks -->
        @can('view tasks')
        <a href="{{ route('tasks.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('tasks.*') ? 'bg-gradient-to-r from-primary-500 to-cobalt-600 text-white shadow-lg scale-105' : 'text-gray-300 hover:bg-white/10 hover:text-white hover:scale-105' }}">
            <svg class="h-5 w-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            Tasks
        </a>
        @endcan

        <!-- Assets -->
        <a href="{{ route('assets.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('assets.*') ? 'bg-gradient-to-r from-primary-500 to-cobalt-600 text-white shadow-lg scale-105' : 'text-gray-300 hover:bg-white/10 hover:text-white hover:scale-105' }}">
            <svg class="h-5 w-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
            </svg>
            Assets
        </a>

        <!-- Custom Fields (Super Admin Only) -->
        @role('Super Admin')
        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                System
            </p>
        </div>
        <a href="{{ route('custom-fields.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('custom-fields.*') ? 'bg-gradient-to-r from-primary-500 to-cobalt-600 text-white shadow-lg scale-105' : 'text-gray-300 hover:bg-white/10 hover:text-white hover:scale-105' }}">
            <svg class="h-5 w-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Custom Fields
        </a>
        @endrole
    </nav>

    <!-- User Info at Bottom -->
    <div class="border-t border-white/10 p-4">
        <div class="flex items-center group cursor-pointer hover:bg-white/10 rounded-lg p-2 transition-all duration-200">
            <div class="flex-shrink-0">
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-400 to-cobalt-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
            <div class="ml-3 overflow-hidden flex-1">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</p>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Overlay -->
<div x-data="{ mobileOpen: false }" class="md:hidden">
    <!-- Mobile Menu Button -->
    <div class="fixed top-0 left-0 right-0 h-16 gradient-navy z-50 flex items-center justify-between px-4 shadow-lg">
        <button @click="mobileOpen = !mobileOpen" class="text-white p-2 hover:bg-white/10 rounded-lg transition-all duration-200">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <span class="text-white text-lg font-bold">HR System</span>
        <div class="w-10"></div> <!-- Spacer for centering -->
    </div>

    <!-- Mobile Sidebar -->
    <div x-show="mobileOpen" 
         @click.away="mobileOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform -translate-x-full"
         class="fixed inset-0 z-40 flex">
        <div class="w-64 gradient-navy text-white overflow-y-auto">
            <div class="p-4">
                <!-- Same navigation content as desktop -->
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-navy-800 text-white' : 'text-gray-300 hover:bg-navy-800' }}">
                        Dashboard
                    </a>
                    @can('view employees')
                    <a href="{{ route('employees.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('employees.*') ? 'bg-navy-800 text-white' : 'text-gray-300 hover:bg-navy-800' }}">
                        Employees
                    </a>
                    @endcan
                    <!-- Add other mobile links -->
                </nav>
            </div>
        </div>
    </div>
</div>
