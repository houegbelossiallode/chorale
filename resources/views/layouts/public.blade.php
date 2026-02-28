<!DOCTYPE html>
<html lang="fr" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Chorale Saint Oscar Romero')</title>
    <meta name="description" content="Chorale catholique Saint Oscar Romero — Chant, Foi & Fraternité au service de la liturgie.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700;1,800;1,900&family=Inter:wght@300;400;500;600;700;800&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('meta')

    <style>
        :root {
            --gold: #c9a84c;
            --gold-light: #e8d48b;
            --gold-deep: #8b6914;
            --dark-purple: #0c0118;
            --rich-purple: #1a0a2e;
            --wine: #2d1b3d;
            --cream: #faf7f0;
            --warm-white: #fefdfb;
        }

        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-elegant { font-family: 'Cormorant Garamond', serif; }

        /* Custom utility backgrounds */
        .text-gold { color: var(--gold); }
        .text-gold-light { color: var(--gold-light); }
        .bg-dark-purple { background-color: var(--dark-purple); }
        .bg-rich-purple { background-color: var(--rich-purple); }
        .bg-cream { background-color: var(--cream); }
        .bg-warm-white { background-color: var(--warm-white); }
        .border-gold { border-color: var(--gold); }

        /* === PREMIUM ANIMATIONS === */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(3deg); }
        }
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes shimmer {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(201, 168, 76, 0.3); }
            50% { box-shadow: 0 0 40px rgba(201, 168, 76, 0.6); }
        }
        @keyframes gradient-flow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes spin-slow { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        @keyframes draw-line { 0% { width: 0; } 100% { width: 100%; } }
        @keyframes count-up { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-slow { animation: float-slow 8s ease-in-out infinite; }
        .animate-shimmer {
            background: linear-gradient(90deg, var(--gold-deep), var(--gold-light), var(--gold-deep));
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 3s linear infinite;
        }
        .animate-pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }
        .animate-gradient-flow {
            background-size: 300% 300%;
            animation: gradient-flow 8s ease infinite;
        }
        .animate-spin-slow { animation: spin-slow 30s linear infinite; }

        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
        .glass-white {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        .glass-gold {
            background: rgba(201, 168, 76, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(201, 168, 76, 0.2);
        }

        /* Gold gradient text */
        .text-gradient-gold {
            background: linear-gradient(135deg, #c9a84c, #e8d48b, #c9a84c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Ornamental separator */
        .ornament::after {
            content: '✦';
            display: block;
            text-align: center;
            color: var(--gold);
            font-size: 1.2rem;
            margin-top: 1.5rem;
            letter-spacing: 1em;
        }

        /* Card hover lift */
        .card-premium {
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .card-premium:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15), 0 0 30px rgba(201, 168, 76, 0.1);
        }

        /* Scroll-reveal */
        .reveal {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 1s cubic-bezier(0.23, 1, 0.32, 1), transform 1s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .js-loaded .reveal {
            opacity: 0;
            transform: translateY(40px);
        }
        .js-loaded .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .js-loaded .reveal-left {
            opacity: 0;
            transform: translateX(-40px);
        }
        .js-loaded .reveal-left.visible {
            opacity: 1;
            transform: translateX(0);
        }
        .js-loaded .reveal-right {
            opacity: 0;
            transform: translateX(40px);
        }
        .js-loaded .reveal-right.visible {
            opacity: 1;
            transform: translateX(0);
        }
        .js-loaded .reveal-scale {
            opacity: 0;
            transform: scale(0.9);
        }
        .js-loaded .reveal-scale.visible {
            opacity: 1;
            transform: scale(1);
        }

        /* Background pattern */
        .bg-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(201, 168, 76, 0.07) 1px, transparent 0);
            background-size: 40px 40px;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--dark-purple); }
        ::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 10px; }

        /* Number counter animation */
        .counter { font-variant-numeric: tabular-nums; }
    </style>
    @stack('styles')
</head>
<body class="antialiased pt-0 bg-warm-white">
    <!-- Navigation -->
    @php $navDark = View::hasSection('navDark'); @endphp
    <nav class="fixed top-0 w-full z-50 transition-all duration-700" id="mainNav"
         x-data="{ scrolled: {{ $navDark ? 'true' : 'false' }}, mobileOpen: false }"
         x-init="window.addEventListener('scroll', () => { scrolled = (window.scrollY > 50) || {{ $navDark ? 'true' : 'false' }} })"
         :class="scrolled ? 'bg-white/90 backdrop-blur-2xl shadow-[0_4px_30px_rgba(0,0,0,0.08)] border-b border-gray-100/50' : 'bg-transparent'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 sm:gap-3 group">
                    <div class="relative">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl flex items-center justify-center transition-all duration-500 overflow-hidden"
                             :class="scrolled ? 'bg-gradient-to-br from-amber-500 via-yellow-500 to-amber-700 shadow-lg shadow-amber-500/25' : 'glass'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-white drop-shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                        </div>
                        <div class="absolute -inset-1 bg-gradient-to-r from-amber-400 to-yellow-300 rounded-2xl blur-lg opacity-0 group-hover:opacity-40 transition-opacity duration-500" :class="scrolled ? '' : 'hidden'"></div>
                    </div>
                    <div>
                        <span class="text-[11px] sm:text-sm font-extrabold tracking-wider uppercase block leading-tight transition-colors duration-500"
                              :class="scrolled ? 'text-gray-900' : 'text-white'">Chorale Saint Oscar</span>
                        <span class="text-[8px] sm:text-[10px] font-semibold tracking-[0.25em] uppercase block transition-colors duration-500"
                              :class="scrolled ? 'text-amber-600' : 'text-amber-300/90'">Romero</span>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-1">
                    @foreach([
                        ['/', 'Accueil'],
                        ['/a-propos', 'Notre Histoire'],
                        ['/membres', 'Nos Voix'],
                        ['/evenements', 'Événements'],
                        ['/contact', 'Contact']
                    ] as [$url, $label])
                    <a href="{{ $url }}" class="relative px-4 py-2 rounded-xl text-sm font-medium transition-all duration-500 group {{ request()->is(ltrim($url, '/') ?: '/') ? 'font-bold' : '' }}"
                       :class="scrolled ? '{{ request()->is(ltrim($url, '/') ?: '/') ? 'text-amber-700' : 'text-gray-600 hover:text-gray-900' }}' : '{{ request()->is(ltrim($url, '/') ?: '/') ? 'text-amber-300' : 'text-white/80 hover:text-white' }}'">
                        {{ $label }}
                        @if(request()->is(ltrim($url, '/') ?: '/'))
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full" :class="scrolled ? 'bg-amber-500' : 'bg-amber-300'"></span>
                        @endif
                    </a>
                    @endforeach

                    <div class="w-px h-6 mx-3" :class="scrolled ? 'bg-gray-200' : 'bg-white/20'"></div>

                    <div class="flex items-center gap-3">
                        @guest
                            <a href="/login" class="px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-500"
                               :class="scrolled ? 'text-gray-600 hover:text-amber-700 hover:bg-amber-50' : 'text-white/80 hover:text-white hover:bg-white/10'">Connexion</a>
                        @endguest

                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="/admin" class="px-4 py-2 text-[10px] font-bold text-amber-500 uppercase tracking-widest hover:text-amber-400 transition"
                                   :class="scrolled ? 'text-amber-600' : 'text-amber-300'">Admin</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" id="logout-form-desktop" class="hidden">
                                @csrf
                            </form>
                            <button onclick="document.getElementById('logout-form-desktop').submit()" 
                                    class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-xl text-sm font-bold hover:shadow-lg transition">
                                Déconnexion
                            </button>
                        @endauth

                        <a href="/don" class="relative px-6 py-2.5 bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-600 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-amber-500/25 transition-all duration-500 hover:scale-105 overflow-hidden group">
                            <span class="relative z-10">✦ Faire un Don</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-amber-600 via-yellow-600 to-amber-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </a>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileOpen = !mobileOpen" class="md:hidden relative w-10 h-10 rounded-xl transition-all duration-300 flex items-center justify-center"
                        :class="scrolled ? 'text-gray-700 hover:bg-gray-100' : 'text-white hover:bg-white/10'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileOpen" 
                 x-anchor.bottom-start="$el.previousElementSibling"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-4 scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                 x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
                 class="md:hidden absolute left-4 right-4 mt-2 py-6 px-4 rounded-[2rem] shadow-2xl border transition-all z-50"
                 :class="scrolled ? 'bg-white/95 backdrop-blur-xl border-gray-100' : 'bg-gray-900/95 backdrop-blur-xl border-white/10'">
                
                <div class="space-y-2">
                    @foreach([
                        ['/', 'Accueil'],
                        ['/a-propos', 'Notre Histoire'],
                        ['/membres', 'Nos Voix'],
                        ['/evenements', 'Événements']
                    ] as [$url, $label])
                    <a href="{{ $url }}" class="block px-6 py-4 rounded-2xl font-bold transition-all active:scale-95 flex items-center justify-between group"
                       :class="scrolled 
                            ? '{{ request()->is(ltrim($url, '/') ?: '/') ? 'bg-amber-50 text-amber-700' : 'text-gray-700 hover:bg-gray-50' }}' 
                            : '{{ request()->is(ltrim($url, '/') ?: '/') ? 'bg-white/10 text-amber-300' : 'text-white/80 hover:bg-white/5' }}'">
                        {{ $label }}
                        <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @endforeach
                </div>

                <div class="mt-6 pt-6 border-t space-y-3" :class="scrolled ? 'border-gray-100' : 'border-white/10'">
                    <!-- @guest -->
                        <a href="/login" class="block px-6 py-4 rounded-2xl font-semibold text-center transition active:scale-95" 
                           :class="scrolled ? 'bg-gray-50 text-gray-900' : 'bg-white/5 text-white'">Connexion</a>
                    <!-- @endguest -->
                    
                    <!-- @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="/admin" class="block px-6 py-4 rounded-2xl font-semibold text-center transition active:scale-95"
                               :class="scrolled ? 'bg-amber-50 text-amber-700' : 'bg-white/10 text-amber-300'">Administration</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile" class="hidden">
                            @csrf
                        </form>
                        <button onclick="document.getElementById('logout-form-mobile').submit()" 
                                class="w-full px-6 py-4 rounded-2xl font-semibold text-center transition active:scale-95"
                                :class="scrolled ? 'bg-gray-50 text-gray-900' : 'bg-white/5 text-white'">Déconnexion</button>
                    @endauth -->

                    <a href="/don" class="block px-6 py-4 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-2xl font-bold text-center shadow-lg shadow-amber-500/20 active:scale-95 transition-transform">
                        ✦ Faire un Don
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Premium Footer -->
    <footer class="bg-dark-purple text-white relative overflow-hidden">
        <!-- Decorative top border -->
        <div class="h-1 bg-gradient-to-r from-transparent via-amber-500 to-transparent"></div>

        <!-- Floating ornaments -->
        <div class="absolute top-20 left-10 w-40 h-40 border border-white/5 rounded-full animate-spin-slow"></div>
        <div class="absolute bottom-20 right-10 w-60 h-60 border border-amber-500/5 rounded-full animate-spin-slow" style="animation-direction: reverse;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gradient-to-br from-amber-500/5 to-purple-500/5 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 relative z-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-16">
                <!-- Brand Column -->
                <div class="sm:col-span-2 md:col-span-5">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-14 h-14 bg-gradient-to-br from-amber-500 via-yellow-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-2xl shadow-amber-500/20 animate-pulse-glow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-lg font-extrabold tracking-wider uppercase block leading-tight">Saint Oscar Romero</span>
                            <span class="text-[10px] text-amber-400 font-semibold tracking-[0.3em] uppercase">Chorale Catholique</span>
                        </div>
                    </div>
                    <p class="text-white/40 leading-relaxed text-sm max-w-md mb-8">
                        Depuis nos débuts, nous portons la beauté du chant liturgique au cœur de notre paroisse. Notre mission : unir nos voix pour élever les âmes.
                    </p>
                    <!-- Social Icons -->
                    <div class="flex gap-3">
                        @foreach(['M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z', 'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z', 'M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01M6.5 4.5h11A2 2 0 0119.5 6.5v11a2 2 0 01-2 2h-11a2 2 0 01-2-2v-11a2 2 0 012-2z'] as $icon)
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white/40 hover:text-amber-400 hover:bg-amber-500/10 hover:border-amber-500/30 transition-all duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $icon }}"/></svg>
                        </a>
                        @endforeach
                    </div>
                    <!-- Newsletter Form -->
                    <div class="mt-12">
                        <h4 class="text-xs font-bold text-amber-400 uppercase tracking-[0.3em] mb-4">Newsletter</h4>
                        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                            @csrf
                            <input type="email" name="email" required placeholder="Votre email..." class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-amber-500/50 transition-all placeholder:text-white/20 min-w-0">
                            <button type="submit" class="w-full sm:w-12 h-12 bg-amber-500 rounded-xl flex items-center justify-center hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/20 active:scale-95 shrink-0">
                                <span class="sm:hidden text-xs font-bold uppercase mr-2">S'abonner</span>
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"/></svg>
                            </button>
                        </form>
                        @if(session('success') && !request()->is('contact'))
                            <p class="text-[10px] text-emerald-400 mt-3 font-medium">{{ session('success') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Links Columns -->
                <div class="md:col-span-3">
                    <h4 class="text-xs font-bold text-amber-400 uppercase tracking-[0.3em] mb-6">Navigation</h4>
                    <ul class="space-y-3">
                        @foreach(['/' => 'Accueil', '/a-propos' => 'Notre Histoire', '/membres' => 'Nos Voix', '/evenements' => 'Événements', '/don' => 'Faire un Don', '/contact' => 'Contact'] as $url => $label)
                        <li><a href="{{ $url }}" class="text-white/40 hover:text-amber-400 transition-all duration-300 text-sm font-medium hover:pl-2">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Quote Column -->
                <div class="md:col-span-4">
                    <div class="glass rounded-3xl p-8">
                        <svg class="w-8 h-8 text-amber-500/30 mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.896 14.913 16 16.017 16H19.017C19.569 16 20.017 15.552 20.017 15V9C20.017 8.448 19.569 8 19.017 8H16.017C14.913 8 14.017 7.104 14.017 6V3H17.017C18.121 3 19.017 3.896 19.017 5V6C20.121 6 21.017 6.896 21.017 8V15C21.017 17.209 19.226 19 17.017 19H14.017V21Z"/></svg>
                        <p class="text-white/60 font-elegant italic text-xl leading-relaxed mb-4">
                            « Si on m'élimine, je ressusciterai dans le peuple salvadorien. »
                        </p>
                        <p class="text-amber-400/60 text-xs font-bold uppercase tracking-[0.2em]">— Saint Oscar Romero</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="mt-20 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-white/20 text-xs">&copy; 2026 Chorale Saint Oscar Romero. Tous droits réservés.</p>
                <p class="text-white/20 text-xs">Conçu avec <span class="text-amber-500">♥</span> et foi</p>
            </div>
        </div>
    </footer>

    <!-- Scroll Animations — Independent -->
    <script>
        document.documentElement.classList.add('js-loaded');

        try {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('visible');
                        }, index * 80);
                    }
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

            document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale').forEach(el => observer.observe(el));
        } catch(e) {
            document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale').forEach(el => el.classList.add('visible'));
        }

        // Counter animation
        function animateCounters() {
            document.querySelectorAll('[data-count]').forEach(el => {
                const target = parseInt(el.dataset.count);
                const duration = 2000;
                const start = performance.now();
                function update(now) {
                    const elapsed = now - start;
                    const progress = Math.min(elapsed / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.floor(eased * target);
                    if (progress < 1) requestAnimationFrame(update);
                }
                requestAnimationFrame(update);
            });
        }
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) { animateCounters(); counterObserver.disconnect(); } });
        });
        const counterSection = document.getElementById('stats-section');
        if (counterSection) counterObserver.observe(counterSection);
    </script>

    <!-- Supabase Auth - Script retained for general Supabase features if needed, but UI injection removed -->
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script>
        try {
            const supabaseUrl = "{{ env('SUPABASE_URL') }}";
            const supabaseKey = "{{ env('SUPABASE_ANON_KEY') }}";
            if (supabaseUrl && supabaseKey) {
                const supabaseClient = supabase.createClient(supabaseUrl, supabaseKey);
                // Auth UI is now handled via Blade auth/guest for better reliability
            }
        } catch(e) { console.warn('Supabase init skipped:', e); }
    </script>
    @stack('scripts')
</body>
</html>
