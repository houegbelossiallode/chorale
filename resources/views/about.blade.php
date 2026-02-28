@extends('layouts.public')

@section('title', 'Notre Histoire — Chorale Saint Oscar Romero')

@section('content')
    <!-- Hero -->
    <section class="relative min-h-[70vh] flex items-center overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center bg-fixed"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/60 to-black/90"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-purple-900/30 to-amber-900/20"></div>

        <!-- Floating rings -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] border border-amber-500/10 rounded-full animate-spin-slow"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[350px] h-[350px] border border-white/5 rounded-full animate-spin-slow" style="animation-direction: reverse; animation-duration: 35s;"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center pt-24 md:pt-20">
            <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full glass mb-10">
                <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse shadow-lg shadow-amber-400/50"></div>
                <!-- <span class="text-amber-300/90 text-xs font-bold tracking-[0.4em] uppercase">Depuis 2004</span> -->
            </div>
            <h1 class="text-5xl md:text-8xl font-serif text-white mb-8 leading-[0.9]">Notre<br><span class="animate-shimmer text-transparent">Histoire</span></h1>
            <p class="text-lg text-white/40 max-w-lg mx-auto leading-relaxed">L'histoire d'un groupe de fidèles unis par la passion du chant sacré et l'amour de la liturgie.</p>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20">
            <div class="w-6 h-10 rounded-full border-2 border-white/15 flex justify-center pt-2">
                <div class="w-1 h-3 bg-amber-400/60 rounded-full animate-bounce"></div>
            </div>
        </div>
    </section>

    <!-- Story Section -->
    <section class="py-32 bg-warm-white relative bg-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="reveal-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 border border-amber-200/50 mb-8">
                        <span class="text-xs font-bold text-amber-700 uppercase tracking-[0.3em]">Notre Genèse</span>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-serif text-gray-900 mb-8 leading-[1.1]">Une chorale<br>née de la <span class="text-gradient-gold">foi</span></h2>
                    <div class="space-y-6">
                        <p class="text-gray-500 leading-relaxed text-lg font-elegant">La Chorale Saint Oscar Romero est née de la conviction que le chant sacré est un pont entre l'humanité et le divin. Fondée en 2004 par un petit groupe de paroissiens passionnés, elle est devenue un pilier de la vie liturgique de notre communauté.</p>
                        <p class="text-gray-500 leading-relaxed text-lg font-elegant">Notre patron, Saint Oscar Arnulfo Romero, archevêque de San Salvador, nous inspire par son courage, sa foi indéfectible et son amour pour les plus humbles. Comme lui, nous mettons notre art au service de la communauté.</p>
                    </div>
                    <div class="mt-10 flex items-center gap-6">
                        <div class="w-16 h-px bg-gradient-to-r from-amber-400 to-transparent"></div>
                        <span class="font-elegant text-xl italic text-amber-700">"La voix est le premier instrument de Dieu"</span>
                    </div>
                </div>
                <div class="reveal-right">
                    <div class="relative">
                        <div class="aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-2xl shadow-amber-900/10 ring-1 ring-black/5 bg-gradient-to-br from-amber-100 via-amber-50 to-orange-50 flex items-center justify-center">
                            <div class="text-center px-10">
                                <div class="w-28 h-28 mx-auto bg-gradient-to-br from-amber-300 to-amber-500 rounded-3xl flex items-center justify-center mb-6 shadow-xl shadow-amber-500/20 animate-float-slow">
                                    <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                                </div>
                                <p class="font-elegant text-2xl text-amber-800/60 italic">Depuis 2004<br>au service du chant sacré</p>
                            </div>
                        </div>
                        <div class="absolute -bottom-6 -left-6 w-full h-full rounded-[2.5rem] border-2 border-amber-300/20 -z-10"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission -->
    <section class="py-32 bg-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-amber-400/40 to-transparent"></div>
        <div class="absolute top-20 right-0 w-96 h-96 bg-gradient-to-bl from-amber-100/30 to-transparent rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-10">
                <div class="group card-premium bg-gradient-to-br from-amber-50 to-orange-50 rounded-3xl p-8 md:p-14 border border-amber-100 reveal-left">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-amber-700 rounded-2xl flex items-center justify-center mb-8 shadow-xl shadow-amber-500/20 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="text-3xl font-serif font-bold text-gray-900 mb-6">Notre Vision</h3>
                    <p class="text-gray-500 leading-relaxed text-lg font-elegant">Être un phare de beauté liturgique, où chaque note chantée devient une prière vivante qui touche les cœurs et élève les âmes vers le Créateur.</p>
                    <div class="mt-8 w-12 h-px bg-gradient-to-r from-amber-400 to-transparent"></div>
                </div>

                <div class="group card-premium bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl p-8 md:p-14 border border-gray-700 reveal-right">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-yellow-400 rounded-2xl flex items-center justify-center mb-8 shadow-xl shadow-amber-500/20 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-3xl font-serif font-bold text-white mb-6">Notre Mission</h3>
                    <p class="text-white/60 leading-relaxed text-lg font-elegant">Enrichir la liturgie par un chant sacré de qualité, former nos membres à l'art vocal, et rayonner la foi à travers la musique dans notre communauté et au-delà.</p>
                    <div class="mt-8 w-12 h-px bg-gradient-to-r from-amber-500/50 to-transparent"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-32 bg-dark-purple relative overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-30"></div>
        <div class="absolute top-1/2 left-0 -translate-y-1/2 w-[400px] h-[400px] bg-gradient-to-r from-amber-500/5 to-transparent rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20 reveal">
                <h2 class="text-4xl md:text-6xl font-serif text-white leading-[1.1]">Nos <span class="text-gradient-gold">Valeurs</span></h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach([
                    ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'title' => 'Foi', 'desc' => 'Notre chant est une prière. Chaque note est offerte à Dieu dans un esprit de dévotion profonde et sincère.'],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Fraternité', 'desc' => 'Nous sommes une famille unie par la musique et la foi. L\'entraide et l\'amour fraternel sont notre fondement.'],
                    ['icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'title' => 'Excellence', 'desc' => 'Nous aspirons à l\'excellence dans notre chant pour offrir au Seigneur le meilleur de notre art et de nos talents.']
                ] as $i => $val)
                <div class="glass rounded-3xl p-10 hover:bg-white/10 transition-all duration-500 card-premium reveal text-center" style="transition-delay: {{ $i * 100 }}ms;">
                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-amber-500 to-amber-700 rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-amber-500/20">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $val['icon'] }}"/></svg>
                    </div>
                    <h3 class="text-2xl font-serif font-bold text-white mb-4">{{ $val['title'] }}</h3>
                    <p class="text-white/40 leading-relaxed font-elegant text-lg">{{ $val['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Quote -->
    <section class="py-24 bg-cream relative">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
            <div class="text-amber-300/30 text-8xl font-serif leading-none mb-4">"</div>
            <p class="text-3xl md:text-4xl font-serif text-gray-900 italic leading-relaxed mb-8">Si on m'élimine, je ressusciterai dans le peuple salvadorien.</p>
            <div class="flex items-center justify-center gap-4">
                <div class="w-8 h-px bg-amber-400"></div>
                <span class="text-amber-700 text-xs font-bold uppercase tracking-[0.3em]">Saint Oscar Romero</span>
                <div class="w-8 h-px bg-amber-400"></div>
            </div>
        </div>
    </section>
@endsection
