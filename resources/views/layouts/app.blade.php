<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('app.app_name')) - 7expres</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe',
                            300: '#93c5fd', 400: '#60a5fa', 500: '#3b82f6',
                            600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af',
                            900: '#1e3a8a', 950: '#172554',
                        }
                    },
                    fontFamily: {
                        arabic: ['Cairo', 'Tajawal', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        [dir="rtl"] { font-family: 'Cairo', sans-serif; }
        [dir="ltr"] { font-family: 'Inter', sans-serif; }

        /* Sidebar transition */
        .sidebar-transition { transition: transform 0.3s ease, width 0.3s ease; }

        /* Status badges */
        .status-badge { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }

        /* Print styles */
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
        }
        .print-only { display: none; }

        /* Table styles */
        .data-table th { @apply text-xs font-semibold uppercase tracking-wider text-gray-500 bg-gray-50 px-4 py-3; }
        .data-table td { @apply px-4 py-3 text-sm text-gray-900 border-b border-gray-100; }
        .data-table tr:hover td { @apply bg-blue-50; }

        /* Form styles */
        .form-label { @apply block text-sm font-medium text-gray-700 mb-1; }
        .form-input { @apply block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 outline-none transition; }
        .form-select { @apply block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 outline-none transition bg-white; }
        .form-textarea { @apply block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 outline-none transition; }
        .form-error { @apply mt-1 text-xs text-red-600; }

        /* Card styles */
        .card { @apply bg-white rounded-xl shadow-sm border border-gray-100; }
        .card-header { @apply px-6 py-4 border-b border-gray-100 flex items-center justify-between; }
        .card-body { @apply p-6; }

        /* Button styles */
        .btn { @apply inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2; }
        .btn-primary { @apply btn bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500; }
        .btn-secondary { @apply btn bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-400; }
        .btn-danger { @apply btn bg-red-600 text-white hover:bg-red-700 focus:ring-red-500; }
        .btn-success { @apply btn bg-green-600 text-white hover:bg-green-700 focus:ring-green-500; }
        .btn-warning { @apply btn bg-amber-500 text-white hover:bg-amber-600 focus:ring-amber-400; }
        .btn-sm { @apply px-3 py-1.5 text-xs; }
        .btn-lg { @apply px-6 py-3 text-base; }
        .btn-icon { @apply inline-flex items-center justify-center w-9 h-9 rounded-lg; }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-gray-50" x-data="{ sidebarOpen: true, mobileMenu: false }">

    <div class="flex h-full">
        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'w-64' : 'w-16'"
               class="sidebar-transition hidden lg:flex flex-col bg-gradient-to-b from-blue-900 to-blue-950 text-white overflow-hidden flex-shrink-0 h-screen sticky top-0">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-5 py-5 border-b border-blue-800">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-blue-900 font-black text-sm">7X</span>
                </div>
                <div x-show="sidebarOpen" x-transition class="min-w-0">
                    <div class="font-bold text-sm leading-tight">7expres</div>
                    <div class="text-blue-300 text-xs">{{ __('app.tagline') }}</div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 py-4 overflow-y-auto">
                @php
                    $navItems = [
                        ['route' => 'dashboard', 'icon' => 'fa-gauge-high', 'label' => __('app.dashboard')],
                        ['route' => 'shipments.index', 'icon' => 'fa-box', 'label' => __('app.shipments')],
                        ['route' => 'customers.index', 'icon' => 'fa-users', 'label' => __('app.customers')],
                        ['route' => 'branches.index', 'icon' => 'fa-building', 'label' => __('app.branches')],
                        ['route' => 'drivers.index', 'icon' => 'fa-truck', 'label' => __('app.drivers')],
                    ];
                @endphp

                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-4 py-3 mx-2 rounded-lg transition-all
                              {{ request()->routeIs(explode('.', $item['route'])[0].'*')
                                 ? 'bg-blue-700 text-white shadow-sm'
                                 : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                        <i class="fa-solid {{ $item['icon'] }} w-5 text-center flex-shrink-0"></i>
                        <span x-show="sidebarOpen" x-transition class="text-sm font-medium">{{ $item['label'] }}</span>
                    </a>
                @endforeach

                @if(auth()->user()?->hasRole('admin'))
                    <div class="mt-4 mx-4 mb-2" x-show="sidebarOpen">
                        <span class="text-xs font-semibold text-blue-400 uppercase tracking-wider">{{ __('app.settings') }}</span>
                    </div>
                    <a href="{{ route('users.index') }}"
                       class="flex items-center gap-3 px-4 py-3 mx-2 rounded-lg transition-all
                              {{ request()->routeIs('users*') ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                        <i class="fa-solid fa-user-gear w-5 text-center flex-shrink-0"></i>
                        <span x-show="sidebarOpen" x-transition class="text-sm font-medium">{{ __('app.users') }}</span>
                    </a>
                @endif
            </nav>

            {{-- Collapse toggle --}}
            <div class="p-4 border-t border-blue-800">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="flex items-center justify-center w-full py-2 rounded-lg text-blue-300 hover:text-white hover:bg-blue-800 transition">
                    <i class="fa-solid" :class="sidebarOpen ? '{{ app()->getLocale() === 'ar' ? 'fa-chevron-right' : 'fa-chevron-left' }}' : '{{ app()->getLocale() === 'ar' ? 'fa-chevron-left' : 'fa-chevron-right' }}'"></i>
                </button>
            </div>
        </aside>

        {{-- Main content --}}
        <div class="flex-1 flex flex-col min-h-screen overflow-hidden">

            {{-- Top navbar --}}
            <header class="bg-white border-b border-gray-200 px-4 lg:px-6 py-3 flex items-center justify-between no-print sticky top-0 z-30">
                {{-- Mobile menu --}}
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                    <i class="fa-solid fa-bars"></i>
                </button>

                {{-- Page title --}}
                <h1 class="text-lg font-semibold text-gray-900">@yield('page-title', __('app.dashboard'))</h1>

                {{-- Right side --}}
                <div class="flex items-center gap-3">
                    {{-- Language toggle --}}
                    <a href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}"
                       class="flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                        <i class="fa-solid fa-globe text-xs"></i>
                        <span>{{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}</span>
                    </a>

                    {{-- Notifications --}}
                    <button class="relative p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                        <i class="fa-solid fa-bell"></i>
                    </button>

                    {{-- User menu --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                                {{ substr(auth()->user()?->name ?? 'A', 0, 1) }}
                            </div>
                            <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()?->name }}</span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                             class="{{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} absolute top-full mt-1 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    {{ __('app.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Mobile sidebar overlay --}}
            <div x-show="mobileMenu" @click="mobileMenu = false"
                 class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:leave="transition-opacity ease-linear duration-300">
            </div>
            <div x-show="mobileMenu" x-transition
                 class="fixed inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} z-50 w-72 bg-gradient-to-b from-blue-900 to-blue-950 lg:hidden flex flex-col">
                <div class="flex items-center justify-between px-5 py-5 border-b border-blue-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center">
                            <span class="text-blue-900 font-black text-sm">7X</span>
                        </div>
                        <div>
                            <div class="font-bold text-white text-sm">7expres</div>
                            <div class="text-blue-300 text-xs">{{ __('app.tagline') }}</div>
                        </div>
                    </div>
                    <button @click="mobileMenu = false" class="text-blue-300 hover:text-white">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <nav class="flex-1 py-4 overflow-y-auto">
                    @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}" @click="mobileMenu = false"
                           class="flex items-center gap-3 px-4 py-3 mx-2 rounded-lg transition-all
                                  {{ request()->routeIs(explode('.', $item['route'])[0].'*')
                                     ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800 hover:text-white' }}">
                            <i class="fa-solid {{ $item['icon'] }} w-5 text-center"></i>
                            <span class="text-sm font-medium">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- Page content --}}
            <main class="flex-1 p-4 lg:p-6 overflow-auto">
                {{-- Flash messages --}}
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                         class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                        <i class="fa-solid fa-circle-check text-green-500"></i>
                        <span class="text-sm">{{ session('success') }}</span>
                        <button @click="show = false" class="{{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }} text-green-500 hover:text-green-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show"
                         class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                        <i class="fa-solid fa-circle-xmark text-red-500"></i>
                        <span class="text-sm">{{ session('error') }}</span>
                        <button @click="show = false" class="{{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }} text-red-500">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    <script>
        // Global CSRF token for AJAX
        window.Laravel = { csrfToken: '{{ csrf_token() }}' };

        // Status color map
        window.statusColors = {
            created: 'blue', collected: 'indigo', in_transit: 'yellow',
            transferred: 'purple', arrived_at_branch: 'cyan', out_for_delivery: 'orange',
            delivered: 'green', received_closed: 'emerald', failed_delivery: 'red', returned: 'gray'
        };
    </script>
</body>
</html>
