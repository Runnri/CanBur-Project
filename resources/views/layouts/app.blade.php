<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — CanBur</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'display': ['"Playfair Display"', 'serif'],
                        'body': ['"DM Sans"', 'sans-serif'],
                    },
                    colors: {
                        sand: { 50:'#fdf8f0',100:'#f9edd8',200:'#f0d9b0',300:'#e4be80',400:'#d49e52',500:'#c4832e' },
                        forest: { 700:'#1a3328', 800:'#102318', 900:'#0a160f' },
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'fade-in': 'fadeIn 0.5s ease forwards',
                        'slide-up': 'slideUp 0.5s ease forwards',
                    },
                    keyframes: {
                        float: { '0%,100%':{ transform:'translateY(0)' }, '50%':{ transform:'translateY(-10px)' } },
                        fadeIn: { from:{ opacity:'0' }, to:{ opacity:'1' } },
                        slideUp: { from:{ opacity:'0', transform:'translateY(16px)' }, to:{ opacity:'1', transform:'translateY(0)' } },
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'DM Sans', sans-serif; }
        body::before {
            content:''; position:fixed; inset:0;
            background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events:none; z-index:9999; opacity:0.4;
        }
        .card {
            background: linear-gradient(135deg, rgba(255,255,255,0.04) 0%, rgba(255,255,255,0.01) 100%);
            border: 1px solid rgba(255,255,255,0.07);
            backdrop-filter: blur(10px);
            transition: all 0.35s cubic-bezier(0.16,1,0.3,1);
        }
        .card:hover { border-color: rgba(212,158,82,0.3); }
        .dest-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.04) 0%, rgba(255,255,255,0.01) 100%);
            border: 1px solid rgba(255,255,255,0.07);
            transition: all 0.4s cubic-bezier(0.16,1,0.3,1);
        }
        .dest-card:hover {
            transform: translateY(-6px);
            border-color: rgba(212,158,82,0.4);
            box-shadow: 0 20px 60px rgba(212,158,82,0.08);
        }
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover, .sidebar-link.active {
            background: linear-gradient(90deg, rgba(212,158,82,0.12) 0%, transparent 100%);
            border-left-color: #d49e52;
            color: #d49e52;
        }
        .input-field {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            transition: all 0.2s ease;
        }
        .input-field:focus {
            outline: none;
            border-color: rgba(212,158,82,0.5);
            background: rgba(212,158,82,0.05);
            box-shadow: 0 0 0 3px rgba(212,158,82,0.08);
        }
        .input-field::placeholder { color: rgba(255,255,255,0.25); }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #0a160f; }
        ::-webkit-scrollbar-thumb { background: #c4832e; border-radius: 2px; }
        [x-cloak] { display:none !important; }
    </style>
    @stack('styles')
</head>

<body class="bg-forest-900 text-white min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- Sidebar --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="fixed top-0 left-0 h-full w-64 z-40 bg-forest-800 border-r border-white/5 flex flex-col transition-transform duration-300"
    >
        {{-- Logo --}}
        <div class="p-6 border-b border-white/5">
            <a href="{{ route('welcome') }}" class="flex items-center gap-2.5 group">
                <span class="w-8 h-8 rounded-lg bg-sand-400 flex items-center justify-center text-forest-900 font-display font-bold text-sm group-hover:rotate-12 transition-transform duration-300">C</span>
                <span class="font-display font-bold text-lg">CanBur</span>
            </a>
        </div>

        {{-- User info --}}
        <div class="px-4 py-4 border-b border-white/5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sand-400/20 border border-sand-400/30 flex items-center justify-center text-sand-400 font-display font-bold text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-body font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs font-body text-white/40 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>

        {{-- Nav links --}}
        <nav class="flex-1 p-4 space-y-1">
            <p class="text-[10px] font-body tracking-widest uppercase text-white/25 px-3 pb-2">Menu</p>

            <a href="{{ route('dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl border-l-2 border-transparent text-white/60 font-body text-sm {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('destinations.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl border-l-2 border-transparent text-white/60 font-body text-sm {{ request()->routeIs('destinations.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Destinasi Liburan
            </a>

            <a href="{{ route('destinations.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl border-l-2 border-transparent text-white/60 font-body text-sm {{ request()->routeIs('plans.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Rencana Perjalanan
            </a>

            <a href="{{ route('profile.edit') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl border-l-2 border-transparent text-white/60 font-body text-sm {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profil
            </a>
        </nav>

        {{-- Logout --}}
        <div class="p-4 border-t border-white/5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-400/70 hover:text-red-400 hover:bg-red-400/8 font-body text-sm transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Sidebar overlay (mobile) --}}
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 md:hidden"
        x-transition:enter="transition duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    {{-- Main content --}}
    <div class="md:pl-64 min-h-screen flex flex-col">

        {{-- Top bar --}}
        <header class="sticky top-0 z-20 bg-forest-900/80 backdrop-blur-xl border-b border-white/5 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                {{-- Mobile menu toggle --}}
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden w-8 h-8 flex flex-col justify-center items-center gap-1.5">
                    <span class="w-4 h-0.5 bg-white block"></span>
                    <span class="w-4 h-0.5 bg-white block"></span>
                    <span class="w-4 h-0.5 bg-white block"></span>
                </button>
                <div>
                    <h1 class="font-display text-lg font-bold">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs font-body text-white/30">@yield('page-subtitle', 'Selamat datang kembali')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @yield('header-actions')
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 p-6">
            {{-- Flash messages --}}
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                 class="mb-6 flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-green-500/10 border border-green-500/20 text-green-400 font-body text-sm animate-slide-up">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
                <button @click="show = false" class="ml-auto text-green-400/50 hover:text-green-400">✕</button>
            </div>
            @endif

            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition
                 class="mb-6 flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400 font-body text-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
                <button @click="show = false" class="ml-auto text-red-400/50 hover:text-red-400">✕</button>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
