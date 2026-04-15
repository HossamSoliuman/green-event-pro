<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') – GreenEventPro</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        green: { 50:'#f0fdf4',100:'#dcfce7',200:'#bbf7d0',500:'#22c55e',600:'#16a34a',700:'#15803d',800:'#166534',900:'#14532d' },
                        brand: { DEFAULT:'#16a34a', light:'#22c55e', dark:'#14532d' }
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style type="text/tailwindcss">
        [x-cloak] { display: none !important; }
        .sidebar-link { @apply flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-700 transition-colors; }
        .sidebar-link.active { @apply bg-green-100 text-green-800; }
        .badge-gray { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800; }
        .badge-blue { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800; }
        .badge-yellow { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800; }
        .badge-green { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800; }
        .badge-red { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800; }
        .card { @apply bg-white rounded-xl shadow-sm border border-gray-100 p-6; }
        .btn-primary { @apply inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors; }
        .btn-secondary { @apply inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors; }
        .btn-danger { @apply inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors; }
        .form-label { @apply block text-sm font-medium text-gray-700 mb-1; }
        .form-input { @apply w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent; }
        .form-select { @apply w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white; }
        .form-checkbox { @apply h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500; }
        .section-title { @apply text-base font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100; }
        .muss-badge { @apply inline-flex items-center px-1.5 py-0.5 text-xs font-bold rounded bg-red-100 text-red-700 ml-1; }
        .soll-badge { @apply inline-flex items-center px-1.5 py-0.5 text-xs font-bold rounded bg-blue-100 text-blue-700 ml-1; }
    </style>
    @stack('styles')
</head>
<body class="h-full font-sans" x-data="{ sidebarOpen: false }">

<div class="flex h-full">
    <!-- Sidebar -->
    <aside class="hidden lg:flex flex-col w-64 bg-white border-r border-gray-200 fixed inset-y-0 z-50">
        <!-- Logo -->
        <div class="flex items-center gap-2 px-6 py-5 border-b border-gray-100">
            <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
            <span class="font-bold text-gray-900 text-sm">GreenEventPro</span>
        </div>

        <!-- Org name -->
        <div class="px-4 py-3 border-b border-gray-100">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Organisation</p>
            <p class="text-sm font-semibold text-gray-700 truncate mt-0.5">{{ $currentOrganization->name ?? '' }}</p>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 mt-1">
                {{ ucfirst($currentOrganization->subscription_plan ?? 'Free') }}
            </span>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('events.index') }}" class="sidebar-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Veranstaltungen
            </a>
            <a href="{{ route('analytics') }}" class="sidebar-link {{ request()->routeIs('analytics*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Analytics
            </a>

            <div class="pt-4 pb-1">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Organisation</p>
            </div>
            <a href="{{ route('organization.settings') }}" class="sidebar-link {{ request()->routeIs('organization.settings') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Einstellungen
            </a>
            <a href="{{ route('organization.users') }}" class="sidebar-link {{ request()->routeIs('organization.users*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Benutzer
            </a>
            <a href="{{ route('organization.billing') }}" class="sidebar-link {{ request()->routeIs('organization.billing') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Abonnement
            </a>
        </nav>

        <!-- User -->
        <div class="px-4 py-4 border-t border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-semibold text-sm">
                    {{ strtoupper(substr($currentUser->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $currentUser->name ?? '' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ ucfirst($currentUser->role ?? '') }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">
        <!-- Top bar -->
        <header class="bg-white border-b border-gray-200 px-4 lg:px-8 py-4 flex items-center justify-between sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                @yield('header-actions')
            </div>
        </header>

        <!-- Flash messages -->
        @if(session('success'))
        <div class="mx-4 lg:mx-8 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3 text-green-800 text-sm" x-data x-init="setTimeout(() => $el.remove(), 5000)">
            <svg class="w-5 h-5 flex-shrink-0 text-green-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mx-4 lg:mx-8 mt-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">
            {{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div class="mx-4 lg:mx-8 mt-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Content -->
        <main class="flex-1 px-4 lg:px-8 py-6">
            @yield('content')
        </main>

        <footer class="px-8 py-4 text-center text-xs text-gray-400 border-t border-gray-100">
            GreenEventPro &copy; {{ date('Y') }} — Nachhaltige Veranstaltungen nach UZ 62
        </footer>
    </div>
</div>

@stack('scripts')
</body>
</html>
