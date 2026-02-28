@extends('layouts.public')

@section('title', 'Nos Voix — Chorale Saint Oscar Romero')

@section('content')
    <!-- Hero Section -->
    <section class="relative min-h-[60vh] flex items-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900"></div>
        <div class="absolute inset-0 bg-pattern opacity-20"></div>
        <div class="absolute top-1/2 right-0 translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-l from-amber-500/10 to-transparent rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-24">
            <div class="reveal">
                <span class="inline-block px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold tracking-[0.3em] uppercase mb-6">Communauté</span>
                <h1 class="text-5xl md:text-8xl font-serif text-white leading-[0.9] mb-8">
                    Nos <span class="text-amber-400 font-light italic">Voix Sacrées</span>
                </h1>
                <p class="text-xl text-gray-400 max-w-2xl font-elegant leading-relaxed">
                    Découvrez les visages, les talents et les histoires qui animent notre chorale et portent nos prières en musique.
                </p>
            </div>
        </div>
    </section>

    <!-- Trombinoscope -->
    <section class="py-24 bg-warm-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @forelse($pupitres as $pupitre)
                @php
                    $colors = [
                        'Soprano' => 'from-rose-500 to-pink-600',
                        'Alto' => 'from-violet-500 to-purple-600',
                        'Ténor' => 'from-sky-500 to-blue-600',
                        'Basse' => 'from-emerald-500 to-green-600',
                    ];
                    $currentColor = $colors[$pupitre->name] ?? 'from-amber-500 to-amber-600';
                    $lightColor = str_replace(['from-', 'to-'], ['bg-', ''], $currentColor);
                @endphp

                <div class="mb-20 reveal">
                    <div class="flex items-end gap-6 mb-12">
                        <div class="w-16 h-16 bg-gradient-to-br {{ $currentColor }} rounded-2xl flex items-center justify-center shadow-lg shadow-black/5">
                            <span class="text-white text-2xl font-serif font-bold">{{ substr($pupitre->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-4xl font-serif font-bold text-gray-900">{{ $pupitre->name }}s</h2>
                            <div class="flex items-center gap-4 mt-2">
                                <span class="text-sm font-bold text-amber-600 uppercase tracking-widest">{{ $pupitre->users->count() }} membres</span>
                                <div class="h-px bg-gradient-to-r from-gray-200 to-transparent flex-1"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach($pupitre->users as $member)
                            <div class="group relative reveal">
                                <a href="{{ route('profile.show', $member->slug) }}" class="block relative z-10">
                                    <div class="bg-white rounded-[2rem] overflow-hidden border border-gray-100 shadow-sm transition-all duration-500 hover:shadow-2xl hover:shadow-amber-900/10 hover:-translate-y-2 card-premium">
                                        <!-- Photo Container -->
                                        <div class="aspect-[4/5] relative overflow-hidden bg-gray-50">
                                            @if($member->photo_url)
                                                <img src="{{ $member->photo_url }}" alt="{{ $member->first_name }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-amber-50 to-orange-50 flex items-center justify-center">
                                                    <div class="w-24 h-24 bg-gradient-to-br {{ $currentColor }} rounded-3xl flex items-center justify-center text-white text-4xl font-serif font-bold opacity-30 group-hover:scale-110 transition-transform duration-500">
                                                        {{ substr($member->first_name, 0, 1) }}
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <!-- Stats Overlay -->
                                            <div class="absolute bottom-4 left-4 right-4 flex justify-between items-center z-20">
                                                <div class="flex items-center gap-2 px-4 py-2 bg-white/90 backdrop-blur-md rounded-xl shadow-sm border border-white/20">
                                                    <svg class="w-4 h-4 text-rose-500 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                                    <span class="text-xs font-bold text-gray-900">{{ $member->likes_received_count }}</span>
                                                </div>
                                            </div>

                                            <!-- Link Indicator -->
                                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent h-1/2 opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-end justify-center pb-8 p-4">
                                                <span class="text-white text-xs font-bold uppercase tracking-[0.2em] flex items-center gap-3 bg-amber-500/20 backdrop-blur-md px-6 py-3 rounded-full border border-white/20">
                                                    Voir profil 
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- content -->
                                        <div class="p-8 text-center">
                                            <h3 class="text-xl font-serif font-bold text-gray-900 group-hover:text-amber-700 transition-colors uppercase tracking-tight">
                                                {{ $member->first_name }} {{ $member->last_name }}
                                            </h3>
                                            <p class="text-xs font-bold text-amber-600/60 uppercase tracking-[0.2em] mt-2 mb-4">{{ $pupitre->name }}</p>
                                            
                                            @if($member->citation)
                                                <p class="text-sm text-gray-500 italic font-elegant line-clamp-2 leading-relaxed">&ldquo;{{ Str::limit($member->citation, 60) }}&rdquo;</p>
                                            @else
                                                <p class="text-sm text-gray-400 font-elegant italic">Chanteur dévoué à la liturgie...</p>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                                <!-- Decorative element -->
                                <div class="absolute -inset-2 rounded-[2.5rem] bg-gradient-to-br {{ $currentColor }} opacity-0 group-hover:opacity-5 blur-xl transition-all duration-500 -z-10"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="py-32 text-center reveal">
                    <div class="w-24 h-24 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-8 border border-amber-100">
                        <svg class="w-10 h-10 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <p class="text-gray-400 font-elegant text-xl">Notre communauté se prépare. Revenez bientôt.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Join CTA -->
    <section class="py-32 bg-dark-purple relative overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-purple-500/5 rounded-full blur-3xl"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center reveal">
            <h2 class="text-5xl md:text-7xl font-serif text-white mb-8 leading-tight">Portez <span class="text-gradient-gold italic">votre voix</span><br>plus haut</h2>
            <p class="text-xl text-white/50 max-w-xl mx-auto mb-12 font-elegant">Que vous soyez ténor, soprano ou simple passionné, il y a une place pour vous dans notre harmonie.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-4 px-12 py-6 bg-gradient-to-r from-amber-500 to-yellow-500 text-white rounded-2xl font-bold shadow-2xl shadow-amber-500/20 hover:scale-105 transition-all duration-500 group">
                Rejoindre la Chorale
                <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </section>
@endsection
