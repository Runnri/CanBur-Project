<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email — CanBur</title>

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
                        'fade-up': 'fadeUp 0.7s ease forwards',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'float': 'float 4s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeUp: { from:{ opacity:'0', transform:'translateY(24px)' }, to:{ opacity:'1', transform:'translateY(0)' } },
                        float: { '0%,100%':{ transform:'translateY(0)' }, '50%':{ transform:'translateY(-8px)' } },
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
        .page-bg {
            background-color: #0a160f;
            background-image:
                radial-gradient(ellipse 60% 50% at 50% 20%, rgba(196,131,46,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 80% 80%, rgba(26,51,40,0.5) 0%, transparent 60%);
        }
        .glass-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%);
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
        }
        ::-webkit-scrollbar { width:4px; }
        ::-webkit-scrollbar-track { background:#0a160f; }
        ::-webkit-scrollbar-thumb { background:#c4832e; border-radius:2px; }
    </style>
</head>

<body class="page-bg min-h-screen flex items-center justify-center p-4">

    <div class="fixed top-1/3 right-0 w-72 h-72 rounded-full bg-sand-400/5 blur-[100px] pointer-events-none"></div>
    <div class="fixed bottom-1/4 left-0 w-64 h-64 rounded-full bg-forest-700/25 blur-[80px] pointer-events-none"></div>

    <div class="w-full max-w-md animate-fade-up">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('welcome') }}" class="inline-flex flex-col items-center gap-3 group">
                <span class="w-12 h-12 rounded-2xl bg-sand-400 flex items-center justify-center text-forest-900 font-display font-black text-xl group-hover:rotate-6 transition-transform duration-300 shadow-lg shadow-sand-400/20">C</span>
                <span class="font-display font-bold text-2xl">CanBur</span>
            </a>
        </div>

        <div class="glass-card rounded-3xl p-10 text-center space-y-6">

            {{-- Animated envelope icon --}}
            <div class="relative inline-flex">
                <div class="w-20 h-20 rounded-3xl bg-sand-400/10 border border-sand-400/20 flex items-center justify-center animate-float mx-auto">
                    <svg class="w-9 h-9 text-sand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                {{-- Pulse ring --}}
                <span class="absolute inset-0 rounded-3xl border border-sand-400/20 animate-ping"></span>
            </div>

            <div>
                <h1 class="font-display text-2xl font-black mb-2">Cek Email Kamu</h1>
                <p class="text-white/40 font-body text-sm leading-relaxed max-w-xs mx-auto">
                    Kami sudah mengirimkan link verifikasi ke emailmu. Silakan cek dan klik link tersebut untuk mengaktifkan akun.
                </p>
            </div>

            {{-- Success status --}}
            @if (session('status') == 'verification-link-sent')
            <div class="flex items-center gap-2.5 px-4 py-3 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 font-body text-sm text-left">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Link verifikasi baru sudah dikirim ke emailmu.
            </div>
            @endif

            {{-- Resend form --}}
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                        class="w-full py-3.5 rounded-full bg-sand-400 text-forest-900 font-body font-medium hover:bg-sand-300 transition-all duration-300 hover:shadow-xl hover:shadow-sand-400/25 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3">
                <div class="flex-1 h-px bg-white/5"></div>
                <span class="text-white/20 text-xs font-body">atau</span>
                <div class="flex-1 h-px bg-white/5"></div>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-white/40 font-body text-sm hover:text-white transition-colors flex items-center gap-1.5 mx-auto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar dari akun
                </button>
            </form>
        </div>

        {{-- Tip --}}
        <div class="mt-5 px-4 py-3.5 rounded-2xl border border-white/5 bg-white/2">
            <p class="text-white/30 font-body text-xs text-center leading-relaxed">
                💡 Tidak menemukan email? Periksa folder spam atau folder promosi kamu.
            </p>
        </div>

    </div>
</body>
</html>
