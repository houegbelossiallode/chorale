@extends('layouts.public')

@section('title', 'Chorale Saint Oscar Romero — Chant, Foi & Fraternité')

@section('content')
    <!-- ═══════════════════════════════════════════════ -->
    <!-- HERO — Cinematic Full Viewport                 -->
    <!-- ═══════════════════════════════════════════════ -->
    <header class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Multi-layer background -->
        <div class="absolute inset-0 z-0">
            <div class="w-full h-full bg-[url('https://images.unsplash.com/photo-1507838153414-b4b713384a76?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center bg-fixed scale-110"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/60 to-black/90"></div>
            <div class="absolute inset-0 bg-gradient-to-tr from-purple-900/30 via-transparent to-amber-900/20"></div>
        </div>

        <!-- Animated decorative rings -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] border border-amber-500/10 rounded-full animate-spin-slow z-10"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] border border-white/5 rounded-full animate-spin-slow z-10" style="animation-direction: reverse; animation-duration: 40s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] border border-amber-400/5 rounded-full animate-spin-slow z-10" style="animation-duration: 50s;"></div>

        <!-- Floating particles -->
        <div class="absolute top-20 left-[15%] w-2 h-2 bg-amber-400/30 rounded-full animate-float" style="animation-delay: 0s;"></div>
        <div class="absolute top-40 right-[20%] w-1.5 h-1.5 bg-amber-300/20 rounded-full animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-40 left-[30%] w-1 h-1 bg-white/20 rounded-full animate-float-slow" style="animation-delay: 1s;"></div>
        <div class="absolute top-60 right-[35%] w-2.5 h-2.5 bg-amber-500/15 rounded-full animate-float-slow" style="animation-delay: 3s;"></div>

        <!-- Content -->
        <div class="relative z-20 text-center px-4 max-w-5xl mx-auto pt-24 md:pt-20">
            <!-- <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full glass mb-12">
                <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse shadow-lg shadow-amber-400/50"></div>
                <span class="text-amber-300/90 text-xs font-bold tracking-[0.4em] uppercase">Chorale Catholique • Depuis 2004</span>
                <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse shadow-lg shadow-amber-400/50"></div>
            </div> -->

            <h1 class="text-5xl sm:text-7xl md:text-[8rem] lg:text-[10rem] font-serif text-white mb-4 leading-[0.85] tracking-tight">
                <span class="text-white/50 italic font-light text-4xl sm:text-6xl md:text-8xl">Chorale</span> </br> Saint Oscar<br>
                <span class="animate-shimmer text-transparent">Romero</span><br>
                
            </h1>

            <div class="w-20 h-px bg-gradient-to-r from-transparent via-amber-500 to-transparent mx-auto my-10"></div>

            <!-- <p class="text-lg md:text-xl text-white/40 mb-14 max-w-lg mx-auto leading-relaxed font-light tracking-wide">
                Unir nos voix pour élever les cœurs<br>
                <span class="text-amber-400/60 font-medium">Chant • Foi • Fraternité</span>
            </p> -->

            <!-- <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="group relative px-10 py-5 bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-600 text-white rounded-2xl font-bold text-base overflow-hidden hover:shadow-2xl hover:shadow-amber-500/30 transition-all duration-500 hover:scale-105">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Rejoindre la Chorale
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-600 via-yellow-600 to-amber-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </a>
                <a href="{{ route('events') }}" class="px-10 py-5 glass text-white rounded-2xl font-semibold text-base hover:bg-white/15 transition-all duration-500 text-center">
                    Voir l'Agenda ✦
                </a>
            </div> -->
        </div>

        <!-- Scroll Indicator -->
        <!-- <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center gap-4">
            <span class="text-white/20 text-[10px] font-bold tracking-[0.4em] uppercase">Découvrir</span>
            <div class="w-6 h-10 rounded-full border-2 border-white/15 flex justify-center pt-2">
                <div class="w-1 h-3 bg-amber-400/60 rounded-full animate-bounce"></div>
            </div>
        </div> -->
    </header>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- STATS COUNTER BAR                              -->
    <!-- ═══════════════════════════════════════════════ -->
    <section id="stats-section" class="relative -mt-16 z-30 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-3xl shadow-2xl shadow-black/10 border border-gray-100 p-2">
                <div class="grid grid-cols-2 md:grid-cols-4">
                    @foreach([
                        ['count' => 45, 'suffix' => '+', 'label' => 'Choristes', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['count' => 20, 'suffix' => '', 'label' => 'Années', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['count' => 150, 'suffix' => '+', 'label' => 'Concerts', 'icon' => 'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3'],
                        ['count' => 4, 'suffix' => '', 'label' => 'Pupitres', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10']
                    ] as $stat)
                    <div class="text-center py-8 px-4 {{ !$loop->last ? 'md:border-r border-gray-100' : '' }} group hover:bg-amber-50/50 rounded-2xl transition-all duration-300">
                        <div class="w-10 h-10 mx-auto bg-gradient-to-br from-amber-100 to-amber-200 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $stat['icon'] }}"/></svg>
                        </div>
                        <span class="text-3xl md:text-4xl font-extrabold text-gray-900 block counter" data-count="{{ $stat['count'] }}">0</span>
                        <span class="text-gray-900 font-bold">{{ $stat['suffix'] }}</span>
                        <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block mt-1">{{ $stat['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- PAROLE AUX PRÊTRES — Immersive Split           -->
    <!-- ═══════════════════════════════════════════════ -->
    <section class="py-32 bg-warm-white relative bg-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-5 gap-20 items-center">
                <!-- Image Column -->
                <div class="lg:col-span-2 reveal-left">
                    <div class="relative">
                        <div class="aspect-[3/4] rounded-[2.5rem] overflow-hidden shadow-2xl shadow-amber-900/10 ring-1 ring-black/5">
                            @if($priest_word && $priest_word->image_path)
                                <img src="{{ $priest_word->image_path }}" class="w-full h-full object-cover" alt="Parole spirituelle">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-amber-100 via-amber-50 to-orange-50 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-amber-200 to-amber-300 rounded-3xl flex items-center justify-center mb-4 animate-float-slow">
                                            <svg class="w-12 h-12 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <p class="text-amber-600/60 font-elegant text-lg italic">Parole d'inspiration</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- Decorative frame -->
                        <div class="absolute -bottom-6 -right-6 w-full h-full rounded-[2.5rem] border-2 border-amber-300/20 -z-10"></div>
                        <!-- Floating badge -->
                        <div class="absolute -top-4 -right-4 bg-gradient-to-br from-amber-500 to-yellow-500 text-white px-5 py-3 rounded-2xl shadow-xl shadow-amber-500/30 animate-float-slow font-bold text-sm">
                            ✦ Méditation
                        </div>
                    </div>
                </div>

                <!-- Text Column -->
                <div class="lg:col-span-3 reveal-right">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 border border-amber-200/50 mb-8">
                        <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                        <span class="text-xs font-bold text-amber-700 uppercase tracking-[0.3em]">Mot d'Inspiration</span>
                    </div>

                    <h2 class="text-4xl md:text-6xl font-serif text-gray-900 mb-8 md:mb-12 leading-[1.1]">
                        Parole<br><span class="text-gradient-gold">aux Prêtres</span>
                    </h2>

                    <blockquote class="relative">
                        <div class="absolute -top-3 -left-1 text-8xl font-serif text-amber-200/50 leading-none select-none">"</div>
                        <p class="text-xl md:text-2xl text-gray-600 leading-relaxed font-elegant italic relative z-10 pl-8 border-l-4 border-gradient-gold" style="border-image: linear-gradient(to bottom, #c9a84c, #e8d48b) 1;">
                            @if($priest_word)
                                {{ $priest_word->content }}
                            @else
                                La musique sacrée est le pont entre le visible et l'invisible. Elle élève nos prières jusqu'au trône de Dieu et fait descendre Sa grâce dans nos cœurs. Notre chorale est cet instrument divin.
                            @endif
                        </p>
                        @if($priest_word)
                            <footer class="mt-8 pl-8 flex items-center gap-4">
                                <div class="w-12 h-px bg-gradient-to-r from-amber-400 to-transparent"></div>
                                <p class="text-gray-900 font-bold text-sm">{{ $priest_word->title }}</p>
                            </footer>
                        @endif
                    </blockquote>

                    <div class="mt-14">
                        <a href="{{ route('about') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-gray-900 text-white rounded-2xl font-semibold hover:bg-gray-800 transition-all duration-300 hover:shadow-xl hover:shadow-gray-900/20 group">
                            Découvrir notre histoire
                            <svg class="w-4 h-4 group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- EVENTS — Immersive Cards                       -->
    <!-- ═══════════════════════════════════════════════ -->
    <section class="py-32 bg-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-amber-400/40 to-transparent"></div>
        <!-- Background decoration -->
        <div class="absolute top-20 right-0 w-96 h-96 bg-gradient-to-bl from-amber-100/30 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-0 w-96 h-96 bg-gradient-to-tr from-purple-100/20 to-transparent rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-20 gap-6 reveal">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 border border-amber-200/50 mb-6">
                        <span class="text-xs font-bold text-amber-700 uppercase tracking-[0.3em]">✦ Prochaines Dates</span>
                    </div>
                    <h2 class="text-4xl md:text-6xl font-serif text-gray-900 leading-[1.1]">Agenda<br><span class="text-gradient-gold">Liturgique</span></h2>
                </div>
                <a href="{{ route('events') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-xl font-semibold text-sm hover:bg-gray-800 transition-all group shrink-0">
                    Tout l'agenda
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @forelse($upcoming_events as $i => $event)
                <div class="group card-premium bg-white rounded-3xl overflow-hidden border border-gray-100 reveal flex flex-col h-full" style="transition-delay: {{ $i * 80 }}ms;">
                    <!-- Image Header -->
                    <div class="aspect-[16/9] overflow-hidden relative">
                        <img src="{{ $event->principalImage ? $event->principalImage->image_path : 'https://images.unsplash.com/photo-1507838153414-b4b713384a76?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80' }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="{{ $event->title }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>
                        
                        <!-- Type Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1.5 rounded-xl bg-white/90 backdrop-blur-sm text-[#7367F0] text-[10px] font-bold uppercase tracking-wider shadow-sm border border-white/20">
                                {{ $event->type->libelle }}
                            </span>
                        </div>
                    </div>

                    <div class="p-8 flex-1 flex flex-col">
                        <div class="flex items-center gap-4 mb-6">
                            <!-- Premium Date Badge -->
                            <div class="w-14 h-14 bg-gradient-to-br from-amber-500 via-yellow-500 to-amber-600 rounded-2xl flex flex-col items-center justify-center shadow-lg shadow-amber-500/20 group-hover:scale-110 transition-transform duration-500 shrink-0">
                                <span class="text-xl font-extrabold text-white leading-none">{{ $event->start_at->format('d') }}</span>
                                <span class="text-[9px] font-bold text-white/80 uppercase tracking-wider">{{ $event->start_at->translatedFormat('M') }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 capitalize">{{ $event->start_at->translatedFormat('l') }}</p>
                                <p class="text-xs text-gray-400 font-medium">{{ $event->start_at->format('H:i') }}</p>
                            </div>
                        </div>

                        <h3 class="text-xl font-serif font-bold text-gray-900 mb-3 group-hover:text-amber-700 transition leading-tight">{{ $event->title }}</h3>
                        <p class="text-gray-400 text-sm mb-8 line-clamp-2 leading-relaxed">{{ $event->description }}</p>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                            <div class="flex items-center gap-2 text-gray-400">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="text-xs font-medium">{{ $event->location }}</span>
                            </div>
                            <a href="{{ route('events.show', $event->slug) }}" class="w-10 h-10 rounded-xl flex items-center justify-center text-gray-400 group-hover:bg-gradient-to-br group-hover:from-amber-500 group-hover:to-amber-600 group-hover:text-white transition-all duration-300 group-hover:shadow-lg group-hover:shadow-amber-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center text-gray-400 italic">Aucun événement à venir pour le moment.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- TESTIMONIALS — Dark Immersive                  -->
    <!-- ═══════════════════════════════════════════════ -->
    <section class="py-20 md:py-32 bg-dark-purple relative overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-30"></div>
        <div class="absolute top-1/2 right-0 translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full bg-gradient-to-br from-amber-500/5 to-purple-500/5 blur-3xl"></div>
        <div class="absolute top-20 left-20 w-32 h-32 border border-amber-500/10 rounded-full animate-spin-slow"></div>
        <div class="absolute bottom-20 right-20 w-48 h-48 border border-white/5 rounded-full animate-spin-slow" style="animation-direction: reverse;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20 reveal">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass mb-8">
                    <span class="text-xs font-bold text-amber-400 uppercase tracking-[0.3em]">✦ Ils témoignent ✦</span>
                </div>
                <h2 class="text-4xl md:text-6xl font-serif text-white leading-[1.1]">Témoignages<br><span class="text-gradient-gold">de Foi</span></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @forelse($testimonies as $i => $t)
                <div class="glass rounded-3xl p-6 sm:p-8 hover:bg-white/10 transition-all duration-500 card-premium reveal" style="transition-delay: {{ $i * 100 }}ms;">
                    <div class="flex items-center gap-4 mb-6">
                        @if($t->image_path)
                            <img src="{{ $t->image_path }}" class="w-12 h-12 rounded-2xl object-cover shadow-lg shadow-amber-500/20" alt="{{ $t->author }}">
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-700 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-amber-500/20">{{ substr($t->author ?? 'T', 0, 1) }}</div>
                        @endif
                        <div>
                            <p class="font-bold text-white text-sm">{{ $t->author ?? 'Anonyme' }}</p>
                            <p class="text-amber-400/60 text-xs">{{ $t->title }}</p>
                        </div>
                    </div>
                    <div class="w-8 h-px bg-gradient-to-r from-amber-500/5 to-transparent mb-6"></div>
                    <p class="text-white/50 leading-relaxed font-elegant italic text-lg line-clamp-4">"{{ Str::limit($t->content, 150) }}"</p>
                </div>
                @empty
                <div class="col-span-full py-12 text-center text-white/30 italic">Aucun témoignage partagé pour le moment.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- NEWS — Magazine Style                          -->
    <!-- ═══════════════════════════════════════════════ -->
    <section class="py-32 bg-cream relative bg-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 reveal">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 border border-amber-200/50 mb-8">
                    <span class="text-xs font-bold text-amber-700 uppercase tracking-[0.3em]">✦ Actualités</span>
                </div>
                <h2 class="text-5xl md:text-6xl font-serif text-gray-900 leading-[1.1]">Dernières<br><span class="text-gradient-gold">Nouvelles</span></h2>
            </div>

            <div class="grid md:grid-cols-3 gap-10">
                @forelse($latest_news as $i => $p)
                <article class="group card-premium bg-white rounded-3xl overflow-hidden border border-gray-100 reveal" style="transition-delay: {{ $i * 100 }}ms;">
                    <div class="aspect-[4/3] overflow-hidden">
                        @if($p->image_path)
                            <img src="{{ $p->image_path }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="{{ $p->title }}">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-amber-100 via-amber-50 to-orange-50 flex items-center justify-center"><svg class="w-16 h-16 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"/></svg></div>
                        @endif
                    </div>
                    <div class="p-8">
                        <span class="text-amber-600 font-bold text-xs uppercase tracking-widest block mb-4">{{ $p->published_at ? $p->published_at->diffForHumans() : $p->created_at->diffForHumans() }}</span>
                        <h3 class="text-xl font-serif font-bold text-gray-900 group-hover:text-amber-700 transition mb-4 leading-tight">{{ $p->title }}</h3>
                        <p class="text-gray-400 text-sm line-clamp-3 leading-relaxed">{{ Str::limit(strip_tags($p->content), 120) }}</p>
                    </div>
                </article>
                @empty
                <div class="col-span-full py-12 text-center text-gray-400 italic">Aucune actualité récente.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- NOS MEMBRES — Showcase                         -->
    <!-- ═══════════════════════════════════════════════ -->
    <section class="py-32 bg-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-amber-400/40 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 border border-amber-200/50 mb-8">
                    <span class="text-xs font-bold text-amber-700 uppercase tracking-[0.3em]">✦ Notre Communauté</span>
                </div>
                <h2 class="text-5xl md:text-6xl font-serif text-gray-900 leading-[1.1]">Nos<br><span class="text-gradient-gold">Voix</span></h2>
                <p class="text-gray-400 mt-6 max-w-lg mx-auto text-sm leading-relaxed">Les visages et les voix qui font battre le cœur de notre paroisse</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-5 mb-14 reveal">
                @foreach($members_preview as $i => $member)
                <a href="{{ route('profile.show', $member->slug) }}" class="group cursor-pointer" style="transition-delay: {{ $i * 80 }}ms;">
                    <div class="aspect-square rounded-3xl overflow-hidden bg-gradient-to-br from-amber-50 to-amber-100 flex items-center justify-center group-hover:shadow-xl group-hover:shadow-amber-500/10 transition-all duration-500 group-hover:-translate-y-2 border border-amber-100 group-hover:border-amber-300">
                        @if($member->photo_url)
                            <img src="{{ $member->photo_url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="{{ $member->first_name }}">
                        @else
                            <div class="w-14 h-14 bg-gradient-to-br from-amber-200 to-amber-300 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                                <span class="text-amber-700 font-bold text-xl">{{ substr($member->first_name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>

            <div class="text-center reveal">
                <a href="{{ route('members') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-600 text-white rounded-2xl font-bold hover:shadow-2xl hover:shadow-amber-500/30 transition-all duration-500 hover:scale-105 group">
                    Découvrir tous nos membres
                    <svg class="w-5 h-5 group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════ -->
    <!-- DONATION CTA — Cinematic                       -->
    <!-- ═══════════════════════════════════════════════ -->
    <section class="relative py-32 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-600 via-amber-700 to-amber-900 animate-gradient-flow"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1542621334-a25bb769435b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center bg-fixed"></div>
        <div class="absolute inset-0 bg-pattern opacity-20"></div>

        <!-- Floating elements -->
        <div class="absolute top-10 left-10 w-40 h-40 border border-white/10 rounded-full animate-float"></div>
        <div class="absolute bottom-10 right-10 w-60 h-60 border border-white/5 rounded-full animate-float-slow"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center reveal">
            <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full glass mb-12">
                <svg class="w-4 h-4 text-amber-200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                <span class="text-white/80 text-xs font-bold tracking-[0.3em] uppercase">Soutenir Notre Mission</span>
            </div>

            <h2 class="text-4xl md:text-7xl font-serif text-white mb-8 leading-[1.05]">
                Votre générosité<br><span class="italic font-light text-white/70">porte nos voix</span>
            </h2>
            <p class="text-lg text-white/50 mb-14 max-w-xl mx-auto leading-relaxed">
                Votre soutien nous permet d'acquérir de nouveaux instruments, de former nos membres et d'assurer le rayonnement de notre ministère musical.
            </p>
            <a href="{{ route('donation') }}" class="inline-flex items-center gap-3 px-12 py-6 bg-white text-amber-800 rounded-2xl font-extrabold text-lg hover:bg-amber-50 transition-all duration-500 shadow-2xl shadow-black/20 hover:scale-105 hover:shadow-3xl group">
                ✦ Faire un don maintenant
                <svg class="w-5 h-5 group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </section>
@endsection
