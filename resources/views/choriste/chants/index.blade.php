@extends('layouts.admin')

@section('title', 'Bibliothèque Musicale')

@section('content')
    <div class="w-full">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-[#444050]">Bibliothèque Musicale</h1>
            <p class="text-slate-500 text-sm">Consultez le répertoire et accédez à vos ressources (Soprano, Alto, etc.)</p>
        </div>

        {{-- Barre de recherche rapide --}}
        <!-- <div class="mb-8 bg-white p-4 rounded-xl shadow-material flex items-center gap-4">
            <div class="relative flex-1">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="chantSearch" placeholder="Rechercher un chant ou un compositeur..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all text-sm">
            </div>
            <div class="text-sm text-slate-400 font-medium">
                <span id="chantCount">{{ $chants->count() }}</span> chants disponibles
            </div>
        </div> -->

        {{-- Grille de chants --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="chantGrid">
            @forelse($chants as $chant)
                <div
                    class="chant-card bg-white rounded-xl shadow-material border border-transparent hover:border-[#7367F0]/30 hover:shadow-lg transition-all overflow-hidden group">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="w-12 h-12 bg-[#7367F0]/10 rounded-xl flex items-center justify-center text-[#7367F0] group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                            </div>
                            @if($chant->file_path)
                                <span class="px-2 py-1 bg-green-50 text-green-600 rounded text-[10px] font-bold uppercase">Partition
                                    Prête</span>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold text-[#444050] mb-1 truncate chant-title">{{ $chant->title }}</h3>
                        <p class="text-sm text-slate-400 mb-6 flex items-center gap-1.5 chant-composer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ $chant->composer ?? 'Compositeur inconnu' }}
                        </p>

                        <div class="flex items-center gap-3 mb-6">
                            @php
                                $counts = $chant->fichiers->groupBy('type')->map->count();
                            @endphp

                            @if($counts->get('partition'))
                                <div class="px-2 py-1 bg-red-50 text-red-500 rounded text-xs font-bold"
                                    title="Partitions disponibles">
                                    {{ $counts->get('partition') }} PDF
                                </div>
                            @endif

                            @if($counts->get('audio'))
                                <div class="px-2 py-1 bg-blue-50 text-blue-500 rounded text-xs font-bold" title="Audio disponibles">
                                    {{ $counts->get('audio') }} Audio
                                </div>
                            @endif

                            @if($counts->get('youtube'))
                                <div class="px-2 py-1 bg-red-50 text-red-600 rounded text-xs font-bold" title="YouTube disponibles">
                                    YouTube
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('choriste.chants.show', $chant->id) }}"
                            class="w-full py-3 bg-slate-50 text-[#7367F0] font-bold text-sm rounded-lg flex items-center justify-center gap-2 hover:bg-[#7367F0] hover:text-white transition-all">
                            Ouvrir la fiche
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-2xl shadow-material">
                    <div
                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-600">Aucun chant trouvé</h3>
                    <p class="text-slate-400">Le répertoire est vide pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('chantSearch');
            const cards = document.querySelectorAll('.chant-card');
            const countDisplay = document.getElementById('chantCount');

            searchInput.addEventListener('input', function (e) {
                const term = e.target.value.toLowerCase();
                let visibleCount = 0;

                cards.forEach(card => {
                    const title = card.querySelector('.chant-title').textContent.toLowerCase();
                    const composer = card.querySelector('.chant-composer').textContent.toLowerCase();

                    if (title.includes(term) || composer.includes(term)) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                countDisplay.textContent = visibleCount;
            });
        });
    </script>
@endsection