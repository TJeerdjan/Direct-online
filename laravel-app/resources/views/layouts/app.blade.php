<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Direct-Online Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        do: {
                            dark: '#1c2336',
                            darker: '#0e172c',
                            mid: '#334157',
                            accent: '#129387',
                            cta: '#f59d0e',
                            light: '#fafaf8',
                            surface: '#f8fafc',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        .sidebar-link { transition: all 0.15s ease; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(18, 147, 135, 0.15); color: #129387; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-do-surface text-do-darker min-h-screen">

{{-- Impersonation Banner --}}
@if(session('impersonating'))
<div class="bg-do-cta text-do-dark px-4 py-2 text-center text-sm font-semibold flex items-center justify-center gap-3" data-testid="impersonation-banner">
    <i class="fa-solid fa-eye"></i>
    Je bekijkt het dashboard als: <strong>{{ session('client_naam') }}</strong>
    <form method="POST" action="{{ route('agency.stop-impersonate') }}" class="inline">
        @csrf
        <button type="submit" class="ml-2 bg-do-dark text-white px-3 py-1 rounded text-xs hover:bg-do-darker" data-testid="stop-impersonate-btn">
            Terug naar admin
        </button>
    </form>
</div>
@endif

<div class="flex min-h-screen" x-data="{ sidebarOpen: true }">
    {{-- Sidebar --}}
    <aside class="w-64 bg-do-dark text-white flex-shrink-0 flex flex-col" :class="sidebarOpen ? '' : 'hidden'" data-testid="sidebar">
        <div class="p-5 border-b border-white/10">
            <h1 class="text-lg font-bold tracking-tight text-do-accent" data-testid="sidebar-brand">Direct-Online</h1>
            <p class="text-xs text-white/50 mt-0.5">Dashboard</p>
        </div>

        <nav class="flex-1 p-3 space-y-1" data-testid="sidebar-nav">
            @if(session('user_type') === 'agency')
                <a href="{{ route('agency.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('agency.dashboard') ? 'active' : 'text-white/70' }}" data-testid="nav-dashboard">
                    <i class="fa-solid fa-gauge w-5 text-center"></i> Dashboard
                </a>
                <a href="{{ route('agency.clients.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('agency.clients.*') ? 'active' : 'text-white/70' }}" data-testid="nav-clients">
                    <i class="fa-solid fa-users w-5 text-center"></i> Klanten
                </a>
            @else
                <a href="{{ route('client.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('client.dashboard') ? 'active' : 'text-white/70' }}" data-testid="nav-dashboard">
                    <i class="fa-solid fa-gauge w-5 text-center"></i> Dashboard
                </a>
                <a href="{{ route('client.portfolio.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('client.portfolio.*') ? 'active' : 'text-white/70' }}" data-testid="nav-portfolio">
                    <i class="fa-solid fa-images w-5 text-center"></i> Portfolio
                </a>
                <a href="{{ route('client.testimonials.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('client.testimonials.*') ? 'active' : 'text-white/70' }}" data-testid="nav-testimonials">
                    <i class="fa-solid fa-star w-5 text-center"></i> Testimonials
                </a>
                <a href="{{ route('client.inbox.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('client.inbox.*') ? 'active' : 'text-white/70' }}" data-testid="nav-inbox">
                    <i class="fa-solid fa-inbox w-5 text-center"></i> Inbox
                </a>
                <a href="{{ route('client.settings.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('client.settings.*') ? 'active' : 'text-white/70' }}" data-testid="nav-settings">
                    <i class="fa-solid fa-gear w-5 text-center"></i> Instellingen
                </a>
            @endif
        </nav>

        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-do-accent/20 flex items-center justify-center text-do-accent text-sm font-bold">
                    {{ strtoupper(substr(session('user_naam', 'U'), 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ session('user_naam') }}</p>
                    <p class="text-xs text-white/40 truncate">{{ session('user_type') === 'agency' ? 'Agency Admin' : session('client_naam', '') }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-white/50 hover:text-white rounded-lg hover:bg-white/5 transition" data-testid="logout-btn">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Uitloggen
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 overflow-x-hidden">
        {{-- Top bar --}}
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="text-do-mid hover:text-do-dark" data-testid="toggle-sidebar">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h2 class="text-lg font-semibold text-do-darker">@yield('page_title', 'Dashboard')</h2>
            </div>
            <div class="flex items-center gap-2">
                @yield('header_actions')
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="mx-6 mt-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2" data-testid="flash-success">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2" data-testid="flash-error">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
        @endif

        {{-- Page Content --}}
        <div class="p-6">
            @yield('content')
        </div>
    </main>
</div>

</body>
</html>
