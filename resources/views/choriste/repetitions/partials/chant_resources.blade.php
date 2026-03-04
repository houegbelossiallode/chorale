<!-- Partition Générale -->
@if($chant->file_path)
    <a href="{{ asset('storage/' . $chant->file_path) }}" target="_blank"
        class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-900 text-white hover:bg-gray-800 transition-all shadow-sm"
        title="Partition Générale">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    </a>
@endif

<!-- Paroles -->
@if($chant->parole)
    <button @click="openLyrics(`{{ addslashes($chant->parole) }}`, '{{ addslashes($chant->title) }}')"
        class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#7367F0]/10 text-[#7367F0] hover:bg-[#7367F0] hover:text-white transition-all border border-[#7367F0]/10"
        title="Paroles">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
    </button>
@endif

<!-- Voix/Fichiers -->
@foreach($chant->fichiers as $fichier)
    @if($fichier->type == 'audio' || $fichier->type == 'youtube')
        <button
            @click="$dispatch('open-media', { type: '{{ $fichier->type }}', url: '{{ Str::startsWith($fichier->file_path, ['http://', 'https://']) ? $fichier->file_path : asset('storage/' . $fichier->file_path) }}', title: '{{ addslashes($chant->title) }}' })"
            class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-[#7367F0]/10 hover:text-[#7367F0] border border-slate-100 transition-all uppercase"
            title="{{ $fichier->type }} - {{ $fichier->pupitre->name ?? 'Tous' }}">
            @if($fichier->type == 'audio')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                </svg>
            @elseif($fichier->type == 'youtube')
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 5 .505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                </svg>
            @endif
        </button>
    @else
        <a href="{{ Str::startsWith($fichier->file_path, ['http://', 'https://']) ? $fichier->file_path : asset('storage/' . $fichier->file_path) }}"
            target="_blank"
            class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-[#7367F0]/10 hover:text-[#7367F0] border border-slate-100 transition-all uppercase"
            title="{{ $fichier->type }} - {{ $fichier->pupitre->name ?? 'Tous' }}">
            @if($fichier->type == 'pdf' || $fichier->type == 'partition')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            @else
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
            @endif
        </a>
    @endif
@endforeach

@if(!$chant->file_path && !$chant->parole && $chant->fichiers->count() == 0)
    <span class="text-[10px] text-slate-300 italic">Aucune</span>
@endif