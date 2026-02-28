@extends('layouts.public')

@section('title', 'Événements — Chorale Saint Oscar Romero')

@section('content')
    <!-- Hero -->
    <section class="relative min-h-[60vh] flex items-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-800 via-amber-900 to-gray-900"></div>
        <div
            class="absolute inset-0 opacity-8 bg-[url('https://images.unsplash.com/photo-1507838153414-b4b713384a76?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center bg-fixed">
        </div>
        <div class="absolute inset-0 bg-pattern opacity-15"></div>

        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] border border-amber-500/10 rounded-full animate-spin-slow">
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] border border-white/5 rounded-full animate-spin-slow"
            style="animation-direction: reverse;"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center pt-24 md:pt-20">
            <!-- <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full glass mb-10">
                        <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse shadow-lg shadow-amber-400/50"></div>
                        <span class="text-amber-300/90 text-xs font-bold tracking-[0.4em] uppercase">Calendrier Sacré</span>
                    </div> -->
            <h1
                class="text-3xl sm:text-5xl md:text-6xl font-serif text-white leading-[0.95] mb-6 max-w-4xl mx-auto break-words">
                Agenda<br><span class="text-amber-400/80 italic font-light">des Liturgies</span></h1>
            <p class="text-base sm:text-lg text-white/40 max-w-2xl mx-auto px-4">Concerts, messes chantées et temps forts de
                notre calendrier
                liturgique</p>
        </div>
    </section>

    <!-- Events Grid -->
    <section class="py-32 bg-warm-white relative bg-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($events as $i => $event)
                    <div class="group card-premium bg-white rounded-3xl overflow-hidden border border-gray-100 reveal"
                        style="transition-delay: {{ $i * 80 }}ms;">
                        <div class="h-2 bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500"></div>
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-br from-amber-500 via-yellow-500 to-amber-600 rounded-2xl flex flex-col items-center justify-center shadow-lg shadow-amber-500/20 group-hover:scale-110 transition-transform duration-500">
                                        <span
                                            class="text-xl font-extrabold text-white leading-none">{{ $event->start_at->format('d') }}</span>
                                        <span
                                            class="text-[9px] font-bold text-white/80 uppercase tracking-wider">{{ $event->start_at->translatedFormat('M') }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 capitalize">
                                            {{ $event->start_at->translatedFormat('l') }}
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $event->start_at->format('H:i') }}</p>
                                    </div>
                                </div>
                                <span
                                    class="px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 text-[10px] font-bold uppercase tracking-wider border border-amber-100">
                                    {{ $event->type->libelle ?? 'Événement' }}
                                </span>
                            </div>

                            <h3
                                class="text-xl font-serif font-bold text-gray-900 mb-3 group-hover:text-amber-700 transition leading-tight">
                                {{ Str::limit($event->title, 45) }}
                            </h3>
                            <p class="text-gray-400 text-sm mb-6 line-clamp-2 leading-relaxed h-[40px]">
                                {{ Str::limit($event->description, 80) }}
                            </p>

                            @if($event->location)
                                <div class="flex items-center gap-2 text-gray-400 mb-6">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-xs font-medium">{{ $event->location }}</span>
                                </div>
                            @endif

                            <a href="{{ route('evenements.show', $event->id) }}"
                                class="flex items-center gap-2 text-amber-600 font-bold text-sm group-hover:gap-3 transition-all">
                                En savoir plus
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <p class="text-gray-400 italic">Aucun événement public n'est prévu pour le moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection