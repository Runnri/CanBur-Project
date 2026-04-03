<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Password — CanBur</title>

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
                    animation: { 'fade-up': 'fadeUp 0.7s ease forwards' },
                    keyframes: { fadeUp: { from:{ opacity:'0', transform:'translateY(24px)' }, to:{ opacity:'1', transform:'translateY(0)' } } }
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
                radial-gradient(ellipse 50% 50% at 30% 30%, rgba(196,131,46,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 70% 70%, rgba(26,51,40,0.5) 0%, transparent 60%);
        }
        .glass-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%);
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
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
        .input-field::placeholder { color: rgba(255,255,255,0.22); }
        ::-webkit-scrollbar { width:4px; }
        ::-webkit-scrollbar-track { background:#0a160f; }
        ::-webkit-scrollbar-thumb { background:#c4832e; border-radius:2px; }
    </style>
</head>

<body class="page-bg min-h-screen flex items-center justify-center p-4">

    <div class="fixed top-1/3 right-0 w-72 h-72 rounded-full bg-sand-400/5 blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md animate-fade-up">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('welcome') }}" class="inline-flex flex-col items-center gap-3 group">
                <span class="w-12 h-12 rounded-2xl bg-sand-400 flex items-center justify-center text-forest-900 font-display font-black text-xl group-hover:rotate-6 transition-transform duration-300 shadow-lg shadow-sand-400/20">C</span>
                <span class="font-display font-bold text-2xl">CanBur</span>
            </a>

            <div class="mt-6 mb-4 w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mx-auto">
                <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>

            <h1 class="font-display text-3xl font-black mb-1">Konfirmasi Identitas</h1>
            <p class="text-white/40 font-body text-sm max-w-xs mx-auto leading-relaxed">
                Area ini dilindungi. Masukkan passwordmu untuk melanjutkan.
            </p>
        </div>

        <div class="glass-card rounded-3xl p-8" x-data="{ showPass: false }">

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="space-y-1.5 mb-6">
                    <label class="text-xs font-body text-white/50 uppercase tracking-wider">Password</label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" required autofocus
                               placeholder="Masukkan passwordmu"
                               class="input-field w-full px-4 py-3 pr-11 rounded-xl font-body text-sm @error('password') border-red-500/50 @enderror">
                        <button type="button" @click="showPass = !showPass"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-white/30 hover:text-white/60 transition-colors">
                            <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-red-400 text-xs font-body mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between gap-4">
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sand-400 hover:text-sand-300 font-body text-sm transition-colors">
                        Lupa password?
                    </a>
                    @endif

                    <button type="submit"
                            class="flex-1 py-3.5 rounded-full bg-sand-400 text-forest-900 font-body font-medium hover:bg-sand-300 transition-all duration-300 hover:shadow-xl hover:shadow-sand-400/25 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>

    </div>
</body>
</html>
