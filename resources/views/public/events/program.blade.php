@extends('layouts.public')

@section('title', 'Programme - ' . $event->title)

@section('content')
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 bg-dark-purple overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-20"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gradient-to-br from-amber-500/10 to-purple-500/10 rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass mb-8 reveal">
                <span class="text-xs font-bold text-amber-400 uppercase tracking-[0.3em]">✦ Programme Musical ✦</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-serif text-white mb-6 leading-tight reveal">
                {{ $event->title }}
            </h1>
            <div class="flex flex-wrap items-center justify-center gap-6 text-white/60 font-medium reveal">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $event->start_at->translatedFormat('d F Y') }}
                </span>
                <span class="w-1.5 h-1.5 rounded-full bg-white/20"></span>
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $event->location ?? 'Lieu non défini' }}
                </span>
            </div>
        </div>
    </section>

    <!-- Repertoire Section -->
    <section class="py-20 bg-warm-white bg-pattern">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-12">
                @php 
                    $grouped = $repertoire->groupBy('partie_titre');
                @endphp

                @forelse($grouped as $titre => $items)
                    <div class="reveal">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="h-px flex-1 bg-gradient-to-r from-transparent to-amber-200"></div>
                            <h2 class="text-2xl font-serif font-bold text-gray-900 px-4">{{ $titre ?? 'Répertoire' }}</h2>
                            <div class="h-px flex-1 bg-gradient-to-l from-transparent to-amber-200"></div>
                        </div>

                        <div class="grid gap-6">
                            @foreach($items as $item)
                                <div class="group bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-amber-900/5 transition-all duration-500 card-premium">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                        <div class="space-y-2">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-amber-700 transition">{{ $item->chant_title }}</h3>
                                            @if($item->composer)
                                                <p class="text-sm text-gray-400 font-medium italic">Par {{ $item->composer }}</p>
                                            @endif
                                        </div>

                                        @if($item->fichiers->count() > 0)
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($item->fichiers as $fichier)
                                                    <a href="{{ Str::startsWith($fichier->file_path, ['http://', 'https://']) ? $fichier->file_path : asset('storage/' . $fichier->file_path) }}" 
                                                       target="_blank"
                                                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-amber-50 text-gray-600 hover:text-amber-700 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all border border-gray-100 hover:border-amber-200 group/link">
                                                        
                                                        @if($fichier->type == 'audio')
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                                                        @elseif($fichier->type == 'pdf' || $fichier->type == 'partition')
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        @elseif($fichier->type == 'youtube')
                                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 5 .505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                                        @else
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                        @endif
                                                        {{ $fichier->type ?? 'Ressource' }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="reveal bg-white rounded-[3rem] p-20 text-center shadow-sm border border-gray-100">
                        <div class="w-24 h-24 bg-amber-50 rounded-3xl flex items-center justify-center mx-auto mb-8 animate-float-slow">
                            <svg class="w-12 h-12 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        </div>
                        <h3 class="text-2xl font-serif font-bold text-gray-900 mb-4">Répertoire en préparation</h3>
                        <p class="text-gray-400 italic">Le programme musical de cet événement sera bientôt disponible.</p>
                    </div>
                @endforelse

                <div class="text-center pt-12 reveal">
                    <a href="{{ route('events') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-gray-900 text-white rounded-2xl font-semibold hover:bg-gray-800 transition-all group">
                        <svg class="w-5 h-5 group-hover:-translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Retour aux événements
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
