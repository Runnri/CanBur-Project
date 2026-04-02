<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CanBur — Rencanakan Liburanmu</title>

    {{-- Tailwind CSS CDN (ganti dengan vite mix jika sudah setup) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
                        sand: {
                            50:  '#fdf8f0',
                            100: '#f9edd8',
                            200: '#f0d9b0',
                            300: '#e4be80',
                            400: '#d49e52',
                            500: '#c4832e',
                        },
                        forest: {
                            700: '#1a3328',
                            800: '#102318',
                            900: '#0a160f',
                        },
                        sky: {
                            soft: '#d6eaf5',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delay': 'float 6s ease-in-out 2s infinite',
                        'slide-up': 'slideUp 0.8s ease forwards',
                        'fade-in': 'fadeIn 1s ease forwards',
                        'marquee': 'marquee 25s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-18px)' },
                        },
                        slideUp: {
                            from: { opacity: '0', transform: 'translateY(40px)' },
                            to: { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            from: { opacity: '0' },
                            to: { opacity: '1' },
                        },
                        marquee: {
                            '0%': { transform: 'translateX(0%)' },
                            '100%': { transform: 'translateX(-50%)' },
                        },
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'DM Sans', sans-serif; }

        /* Noise grain overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 9999;
            opacity: 0.5;
        }

        /* Hero gradient mesh */
        .hero-bg {
            background-color: #0a160f;
            background-image:
                radial-gradient(ellipse 80% 60% at 20% 20%, rgba(196,131,46,0.18) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 80% 80%, rgba(26,51,40,0.8) 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 70% 20%, rgba(214,234,245,0.07) 0%, transparent 50%);
        }

        /* Card hover glow */
        .feature-card:hover {
            box-shadow: 0 0 0 1px rgba(196,131,46,0.3), 0 20px 60px rgba(196,131,46,0.1);
        }

        /* Text reveal animation helpers */
        [x-cloak] { display: none !important; }

        .clip-reveal {
            clip-path: polygon(0 100%, 100% 100%, 100% 100%, 0 100%);
            transition: clip-path 1s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .clip-reveal.revealed {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #0a160f; }
        ::-webkit-scrollbar-thumb { background: #c4832e; border-radius: 2px; }

        /* Destination card */
        .dest-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.04) 0%, rgba(255,255,255,0.01) 100%);
            border: 1px solid rgba(255,255,255,0.07);
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dest-card:hover {
            transform: translateY(-8px);
            border-color: rgba(196,131,46,0.4);
            background: linear-gradient(135deg, rgba(196,131,46,0.08) 0%, rgba(255,255,255,0.02) 100%);
        }

        /* Divider line animation */
        .line-grow {
            width: 0;
            transition: width 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .line-grow.active { width: 100%; }
    </style>
</head>

<body class="bg-forest-900 text-white overflow-x-hidden" x-data="app()" x-init="init()">
    {{--  NAVBAR                                --}}
    <nav
        x-data="{ scrolled: false, mobileOpen: false }"
        x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 40)"
        :class="scrolled ? 'bg-forest-900/90 backdrop-blur-xl border-b border-white/5 py-3' : 'bg-transparent py-5'"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-500"
    >
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
            {{-- Logo --}}
            <a href="#" class="flex items-center">
    <img 
        src="{{ asset('images/LogoCanbur.png') }}" 
        alt="CanBur Logo"
        class="h-14 w-auto object-contain"
    >
</a>

            {{-- Desktop nav --}}
            <div class="hidden md:flex items-center gap-8 text-sm font-body text-white/60">
                <a href="#fitur" class="hover:text-sand-400 transition-colors">Fitur</a>
                <a href="#cara-kerja" class="hover:text-sand-400 transition-colors">Cara Kerja</a>
                <a href="#destinasi" class="hover:text-sand-400 transition-colors">Destinasi</a>
            </div>

            {{-- profil --}}
            <div class="hidden md:flex items-center gap-3">
    @auth
        <div class="relative" x-data="{ open: false }">
            
        
            <!-- Trigger (nama user) -->
            <button @click="open = !open"
                class="flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 hover:bg-white/10 text-sm text-white/80 transition">
                
                {{ Auth::user()->name }}
                
                <svg class="w-4 h-4" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown -->
            <div x-show="open"
                 @click.outside="open = false"
                 x-transition
                 class="absolute right-0 mt-3 w-48 rounded-xl bg-forest-800 border border-white/10 shadow-lg overflow-hidden z-50">

                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2 text-sm text-white/70 hover:bg-white/5">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-white/5">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    @else
        <a href="{{ route('login') }}" class="px-4 py-2 text-sm text-white/70">
            Masuk
        </a>
        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-sand-400 text-forest-900 text-sm">
            Mulai Gratis
        </a>
    @endauth
</div>

            {{-- Mobile hamburger --}}
            <button @click="mobileOpen = !mobileOpen" class="md:hidden w-9 h-9 flex flex-col justify-center items-center gap-1.5">
                <span :class="mobileOpen ? 'rotate-45 translate-y-2' : ''" class="w-5 h-0.5 bg-white block transition-all duration-300"></span>
                <span :class="mobileOpen ? 'opacity-0' : ''" class="w-5 h-0.5 bg-white block transition-all duration-300"></span>
                <span :class="mobileOpen ? '-rotate-45 -translate-y-2' : ''" class="w-5 h-0.5 bg-white block transition-all duration-300"></span>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div
            x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="md:hidden border-t border-white/5 bg-forest-900/95 backdrop-blur-xl px-6 py-4 space-y-3"
        >
            <a href="#fitur" class="block py-2 text-white/70 hover:text-sand-400 transition-colors font-body text-sm" @click="mobileOpen = false">Fitur</a>
            <a href="#cara-kerja" class="block py-2 text-white/70 hover:text-sand-400 transition-colors font-body text-sm" @click="mobileOpen = false">Cara Kerja</a>
            <a href="#destinasi" class="block py-2 text-white/70 hover:text-sand-400 transition-colors font-body text-sm" @click="mobileOpen = false">Destinasi</a>
            <div class="pt-3 flex flex-col gap-2 border-t border-white/5">
                @auth
                    <a href="{{ route('dashboard') }}" class="block text-center px-5 py-2.5 rounded-full bg-sand-400 text-forest-900 font-body font-medium text-sm">Dashboard →</a>
                @else
                    <a href="{{ route('login') }}" class="block text-center py-2 text-white/70 font-body text-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="block text-center px-5 py-2.5 rounded-full bg-sand-400 text-forest-900 font-body font-medium text-sm">Mulai Gratis</a>
                @endauth
            </div>
        </div>
    </nav>


    {{-- ═══════════════════════════════════════ --}}
    {{--  HERO SECTION                          --}}
    {{-- ═══════════════════════════════════════ --}}
    <section class="hero-bg relative min-h-screen flex flex-col justify-center overflow-hidden pt-24">

        {{-- Decorative blobs --}}
        <div class="absolute top-1/4 right-0 w-[500px] h-[500px] rounded-full bg-sand-400/5 blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] rounded-full bg-forest-700/40 blur-[100px] pointer-events-none"></div>
        

        {{-- Main hero content --}}
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center py-16">

            <div class="space-y-8">

               

                {{-- Headline --}}
                <div
                    x-show="heroVisible"
                    x-transition:enter="transition ease-out duration-700 delay-200"
                    x-transition:enter-start="opacity-0 translate-y-6"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >
                    <h1 class="font-display text-5xl md:text-6xl lg:text-7xl font-black leading-[1.05] tracking-tight">
                        Liburan

                        <span class="block">Terencana.</span>
                    </h1>
                </div>

                {{-- Subtext --}}
                <p
                    x-show="heroVisible"
                    x-transition:enter="transition ease-out duration-700 delay-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="text-white/50 font-body text-lg leading-relaxed max-w-md"
                >
                    CanBur membantu kamu mencatat destinasi, menyusun jadwal perjalanan, dan melacak budget — semua dalam satu tempat.
                </p>

                {{-- CTA group --}}
                <div
                    x-show="heroVisible"
                    x-transition:enter="transition ease-out duration-700 delay-400"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex flex-wrap items-center gap-4"
                >
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="group px-7 py-3.5 rounded-full bg-sand-400 text-forest-900 font-body font-medium hover:bg-sand-300 transition-all duration-300 hover:shadow-2xl hover:shadow-sand-400/30 hover:-translate-y-0.5 flex items-center gap-2">
                            Ke Dashboard
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="group px-7 py-3.5 rounded-full bg-sand-400 text-forest-900 font-body font-medium hover:bg-sand-300 transition-all duration-300 hover:shadow-2xl hover:shadow-sand-400/30 hover:-translate-y-0.5 flex items-center gap-2">
                            Mulai Sekarang
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('login') }}" class="text-white/50 font-body text-sm hover:text-sand-400 transition-colors flex items-center gap-1">
                            Sudah punya akun?
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endauth
                </div>

                {{-- Social proof --}}
                <div
                    x-show="heroVisible"
                    x-transition:enter="transition ease-out duration-700 delay-500"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="flex items-center gap-4 pt-2"
                >
                    <div class="flex -space-x-2">
                        @foreach(['🧳','🏝️','⛺','🗺️'] as $emoji)
                        <span class="w-8 h-8 rounded-full bg-forest-700 border-2 border-forest-900 flex items-center justify-center text-xs">{{ $emoji }}</span>
                        @endforeach
                    </div>
                    <p class="text-white/40 text-xs font-body">Digunakan oleh para traveler Indonesia</p>
                </div>
            </div>

            {{-- Hero visual / mock card --}}
            <div
                x-show="heroVisible"
                x-transition:enter="transition ease-out duration-1000 delay-300"
                x-transition:enter-start="opacity-0 translate-x-8"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="relative hidden md:block"
            >
                {{-- Big destination preview card --}}
                <div class="dest-card rounded-3xl overflow-hidden p-1 max-w-md ml-auto">
                    {{-- Image placeholder --}}
                    <div class="relative rounded-2xl overflow-hidden h-56 bg-gradient-to-br from-forest-700 to-forest-800">
                        <div class="absolute inset-0 bg-gradient-to-br from-sand-400/20 via-transparent to-forest-900/60"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-7xl">🏔️</span>
                        </div>
                        {{-- Status badge --}}
                        <div class="absolute top-3 right-3 px-3 py-1 rounded-full bg-green-500/20 border border-green-500/30 text-green-400 text-xs font-body">
                            ✓ Tersedia
                        </div>
                    </div>
                    </div>
                </div>

                {{-- Decorative circle --}}
                <div class="absolute -bottom-8 -left-8 w-48 h-48 rounded-full border border-sand-400/10 pointer-events-none"></div>
                <div class="absolute -bottom-4 -left-4 w-32 h-32 rounded-full border border-sand-400/5 pointer-events-none"></div>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-bounce">
            <span class="text-white/20 text-xs font-body tracking-widest uppercase">Scroll</span>
            <div class="w-px h-8 bg-gradient-to-b from-white/20 to-transparent"></div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════ --}}
    {{--  MARQUEE TICKER                        --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="bg-sand-400 py-3 overflow-hidden">
        <div class="flex animate-marquee whitespace-nowrap">
            @php
                $items = ['🏔️ Trip Bromo', '🏖️ Pantai Kuta', '⛺ Gn. Cikuray', '🌋 Kawah Ijen', '🏯 Borobudur', '🌴 Raja Ampat', '🎿 Dieng Plateau', '🏄 Lombok', '🌊 Bunaken'];
                $repeated = array_merge($items, $items, $items, $items);
            @endphp
            @foreach($repeated as $item)
                <span class="text-forest-900 font-display font-bold text-sm mx-8">{{ $item }}</span>
            @endforeach
        </div>
    </div>


    {{-- ═══════════════════════════════════════ --}}
    {{--  FITUR SECTION                         --}}
    {{-- ═══════════════════════════════════════ --}}
    <section id="fitur" class="py-28 px-6 relative" x-intersect="featuresVisible = true">
        <div class="max-w-7xl mx-auto">

            <div class="text-center mb-20 space-y-4">
                <span class="text-sand-400 text-xs font-body tracking-widest uppercase">Fitur Utama</span>
                <h2 class="font-display text-4xl md:text-5xl font-black">Semua yang kamu butuhkan</h2>
                <p class="text-white/40 font-body max-w-md mx-auto">Dari destinasi hingga jadwal harian, CanBur mencakup seluruh perjalananmu.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                @php
                    $features = [
                        ['icon' => '🗺️', 'title' => 'Destinasi Liburan', 'desc' => 'Tambahkan destinasi impianmu lengkap dengan foto, budget, durasi, dan tanggal keberangkatan.', 'tag' => 'CRUD Lengkap'],
                        ['icon' => '📅', 'title' => 'Rencana Perjalanan', 'desc' => 'Susun jadwal harian secara terperinci — jam berapa, kegiatan apa, lokasi mana.', 'tag' => 'Per Hari & Jam'],
                        ['icon' => '💰', 'title' => 'Manajemen Budget', 'desc' => 'Catat berapa budget yang dibutuhkan dan pantau apakah sudah terpenuhi atau belum.', 'tag' => 'Tracking'],
                        ['icon' => '✅', 'title' => 'Status Pencapaian', 'desc' => 'Tandai destinasi yang sudah tercapai dan rayakan setiap perjalanan yang berhasil kamu lakukan.', 'tag' => 'Progress'],
                        ['icon' => '🖼️', 'title' => 'Galeri Foto', 'desc' => 'Tambahkan foto untuk setiap destinasi agar rencana liburanmu terasa lebih nyata dan bersemangat.', 'tag' => 'Visual'],
                        ['icon' => '🔐', 'title' => 'Akun Aman', 'desc' => 'Register dan login dengan enkripsi password. Data liburanmu hanya milikmu.', 'tag' => 'Privasi'],
                    ];
                @endphp

                @foreach($features as $i => $f)
                <div
                    x-show="featuresVisible"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-6"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    :style="`transition-delay: ${{{ $i }}} * 80 + 'ms'`"
                    class="feature-card dest-card rounded-2xl p-6 space-y-4 group cursor-default transition-all duration-300"
                >
                    <div class="flex items-start justify-between">
                        <span class="text-3xl group-hover:scale-110 transition-transform duration-300 block">{{ $f['icon'] }}</span>
                        <span class="text-[10px] font-body tracking-widest uppercase text-sand-400/60 border border-sand-400/20 px-2 py-0.5 rounded-full">{{ $f['tag'] }}</span>
                    </div>
                    <h3 class="font-display text-lg font-bold">{{ $f['title'] }}</h3>
                    <p class="text-white/40 font-body text-sm leading-relaxed">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════ --}}
    {{--  CARA KERJA                            --}}
    {{-- ═══════════════════════════════════════ --}}
    <section id="cara-kerja" class="py-28 px-6 bg-forest-800/30 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(196,131,46,0.04)_0%,transparent_70%)] pointer-events-none"></div>

        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-20 space-y-4">
                <span class="text-sand-400 text-xs font-body tracking-widest uppercase">Cara Kerja</span>
                <h2 class="font-display text-4xl md:text-5xl font-black">Mulai dalam 3 langkah</h2>
            </div>

            <div class="space-y-0">
                @php
                    $steps = [
                        ['num' => '01', 'title' => 'Buat Akun', 'desc' => 'Daftar dengan nama, email, dan password. Proses register cepat dan mudah.', 'icon' => '👤'],
                        ['num' => '02', 'title' => 'Tambah Destinasi', 'desc' => 'Buat destinasi liburan impianmu — lengkap dengan foto, budget, dan durasi perjalanan.', 'icon' => '📍'],
                        ['num' => '03', 'title' => 'Susun Rencana', 'desc' => 'Isi rencana perjalanan per hari secara terperinci. Jadikan setiap momen terencana.', 'icon' => '✏️'],
                    ];
                @endphp

                @foreach($steps as $i => $step)
                <div class="flex gap-8 items-start group" x-intersect="$el.classList.add('opacity-100', 'translate-y-0')" style="opacity:0; transform:translateY(20px); transition: all 0.7s ease {{ $i * 150 }}ms;">
                    <div class="flex flex-col items-center shrink-0">
                        <div class="w-14 h-14 rounded-2xl bg-forest-700 border border-white/10 flex items-center justify-center font-display font-black text-sand-400 text-lg group-hover:border-sand-400/40 transition-colors">
                            {{ $step['num'] }}
                        </div>
                        @if(!$loop->last)
                        <div class="w-px flex-1 bg-white/5 my-2 min-h-[40px]"></div>
                        @endif
                    </div>
                    <div class="pb-12 pt-2 space-y-2">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">{{ $step['icon'] }}</span>
                            <h3 class="font-display text-xl font-bold">{{ $step['title'] }}</h3>
                        </div>
                        <p class="text-white/40 font-body leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-sand-400 text-forest-900 font-body font-medium hover:bg-sand-300 transition-all duration-300 hover:shadow-2xl hover:shadow-sand-400/30">
                        Ke Dashboard →
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-sand-400 text-forest-900 font-body font-medium hover:bg-sand-300 transition-all duration-300 hover:shadow-2xl hover:shadow-sand-400/30">
                        Mulai Sekarang — Gratis →
                    </a>
                @endauth
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════ --}}
    {{--  DESTINASI SAMPLE                      --}}
    {{-- ═══════════════════════════════════════ --}}
    <section id="destinasi" class="py-28 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div class="space-y-3">
                    <span class="text-sand-400 text-xs font-body tracking-widest uppercase">Contoh Destinasi</span>
                    <h2 class="font-display text-4xl md:text-5xl font-black">Inspirasi<br><span class="text-sand-400 italic">Liburanmu</span></h2>
                </div>
                <p class="text-white/40 font-body max-w-xs leading-relaxed">Dari gunung ke pantai, susun rencana perjalanan terbaikmu bersama CanBur.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $destinations = [
                        ['emoji' => '🏔️', 'title' => 'Trip Bromo', 'location' => 'Jawa Timur', 'date' => '15 Jun 2025', 'days' => 3, 'budget' => '2.500.000', 'done' => true, 'color' => 'from-orange-900/40 to-forest-900'],
                        ['emoji' => '🏖️', 'title' => 'Pantai Kuta', 'location' => 'Bali', 'date' => '20 Jul 2025', 'days' => 5, 'budget' => '4.000.000', 'done' => false, 'color' => 'from-blue-900/40 to-forest-900'],
                        ['emoji' => '⛺', 'title' => 'Camping Gn. Cikuray', 'location' => 'Garut, Jawa Barat', 'date' => '10 Agu 2025', 'days' => 2, 'budget' => '800.000', 'done' => false, 'color' => 'from-green-900/40 to-forest-900'],
                    ];
                @endphp

                @foreach($destinations as $dest)
                <div class="dest-card rounded-3xl overflow-hidden group">
                    {{-- Image area --}}
                    <div class="relative h-48 bg-gradient-to-br {{ $dest['color'] }} flex items-center justify-center overflow-hidden">
                        <span class="text-6xl group-hover:scale-110 transition-transform duration-500">{{ $dest['emoji'] }}</span>
                        <div class="absolute top-4 right-4">
                            @if($dest['done'])
                                <span class="px-3 py-1 rounded-full bg-green-500/20 border border-green-500/30 text-green-400 text-xs font-body">✓ Tercapai</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-white/50 text-xs font-body">○ Belum</span>
                            @endif
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-forest-900/80 to-transparent"></div>
                    </div>

                    {{-- Card content --}}
                    <div class="p-5 space-y-4">
                        <div>
                            <h3 class="font-display text-lg font-bold">{{ $dest['title'] }}</h3>
                            <p class="text-white/40 text-sm font-body flex items-center gap-1 mt-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                {{ $dest['location'] }}
                            </p>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="p-2 rounded-xl bg-white/4">
                                <p class="text-[9px] text-white/30 font-body uppercase tracking-wide">Tanggal</p>
                                <p class="text-xs font-body font-medium mt-1 text-white/80">{{ $dest['date'] }}</p>
                            </div>
                            <div class="p-2 rounded-xl bg-white/4">
                                <p class="text-[9px] text-white/30 font-body uppercase tracking-wide">Durasi</p>
                                <p class="text-xs font-body font-medium mt-1 text-white/80">{{ $dest['days'] }} Hari</p>
                            </div>
                            <div class="p-2 rounded-xl bg-sand-400/10">
                                <p class="text-[9px] text-sand-400/50 font-body uppercase tracking-wide">Budget</p>
                                <p class="text-xs font-body font-medium mt-1 text-sand-400">{{ number_format((int)str_replace('.', '', $dest['budget']), 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════ --}}
    {{--  CTA SECTION                           --}}
    {{-- ═══════════════════════════════════════ --}}
    <section class="py-28 px-6">
        <div class="max-w-4xl mx-auto">
            <div class="relative rounded-3xl overflow-hidden p-12 md:p-16 text-center" style="background: linear-gradient(135deg, #1a3328 0%, #0a160f 100%); border: 1px solid rgba(196,131,46,0.2);">
                {{-- Glow --}}
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-64 h-32 bg-sand-400/10 blur-3xl rounded-full pointer-events-none"></div>

                <span class="text-5xl mb-6 block">✈️</span>
                <h2 class="font-display text-4xl md:text-5xl font-black mb-4">
                    Siap merencanakan<br>
                    <span class="text-sand-400 italic">liburan terbaik?</span>
                </h2>
                <p class="text-white/40 font-body mb-10 max-w-md mx-auto leading-relaxed">
                    Bergabunglah sekarang dan mulai catat destinasi impianmu. Gratis, mudah, dan menyenangkan.
                </p>

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 px-10 py-4 rounded-full bg-sand-400 text-forest-900 font-body font-medium text-lg hover:bg-sand-300 transition-all duration-300 hover:shadow-2xl hover:shadow-sand-400/30 hover:-translate-y-1">
                        Buka Dashboard
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @else
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-2 px-10 py-4 rounded-full bg-sand-400 text-forest-900 font-body font-medium text-lg hover:bg-sand-300 transition-all duration-300 hover:shadow-2xl hover:shadow-sand-400/30 hover:-translate-y-1">
                            Daftar Sekarang
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('login') }}" class="text-white/50 font-body hover:text-sand-400 transition-colors">
                            atau Masuk →
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════ --}}
    {{--  FOOTER                                --}}
    {{-- ═══════════════════════════════════════ --}}
    <footer class="border-t border-white/5 py-10 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-sand-400 flex items-center justify-center text-forest-900 font-display font-bold text-xs">C</span>
                <span class="font-display font-bold">CanBur</span>
                <span class="text-white/20 font-body text-sm ml-2">Rencanakan Liburanmu</span>
            </div>
            <p class="text-white/20 font-body text-sm">
                © {{ date('Y') }} CanBur. Dibuat dengan ❤️ untuk para traveler Indonesia.
            </p>
        </div>
    </footer>


    <script>
        function app() {
            return {
                heroVisible: false,
                featuresVisible: false,
                init() {
                    // Trigger hero animation on load
                    setTimeout(() => { this.heroVisible = true; }, 100);
                }
            }
        }
    </script>

</body>
</html>