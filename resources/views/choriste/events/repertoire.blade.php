@extends('layouts.admin')

@section('title', 'Répertoire - ' . $event->title)

@section('content')
    <div class="space-y-6" x-data="recordingSystem()">
        <!-- Header Cadre -->
        <div class="bg-gradient-to-br from-[#7367F0] to-[#4834D4] rounded-2xl p-8 text-white shadow-xl shadow-[#7367F0]/20">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="space-y-2">
                    <a href="{{ route('choriste.events.index') }}"
                        class="inline-flex items-center gap-2 text-white/70 hover:text-white text-xs font-bold transition-colors mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Retour à l'agenda
                    </a>
                    <h1 class="text-3xl font-extrabold tracking-tight">{{ $event->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-white/80 text-sm">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $event->start_at->format('d/m/Y') }}
                        </span>
                        <span class="w-1 h-1 rounded-full bg-white/30"></span>
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $event->start_at->format('H:i') }}
                        </span>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-60 mb-1">Votre Progression</p>
                    <div class="flex items-center gap-3">
                        <div class="w-32 h-2 bg-white/20 rounded-full overflow-hidden">
                            <div class="h-full bg-white rounded-full"
                                style="width: {{ $repertoire->count() > 0 ? ($repertoire->filter(fn($r) => $r->enregistrements->count() > 0)->count() / $repertoire->count()) * 100 : 0 }}%">
                            </div>
                        </div>
                        <span
                            class="text-xs font-bold">{{ $repertoire->filter(fn($r) => $r->enregistrements->count() > 0)->count() }}/{{ $repertoire->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Repertoire Table -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-12">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold uppercase tracking-widest text-slate-400">
                            <th class="px-6 py-4">Partie</th>
                            <th class="px-6 py-4">Chant</th>
                            <th class="px-6 py-4">Ressources</th>
                            <th class="px-6 py-4">Mon Enregistrement</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($repertoire as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs font-bold text-[#7367F0] bg-[#7367F0]/10 px-3 py-1 rounded-full">
                                        {{ $item->partieEvent->titre ?? 'Déroulement' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-[#444050]">{{ $item->chant->title }}</span>
                                        <span
                                            class="text-[10px] text-slate-400 font-medium">{{ $item->chant->composer ?? 'Compositeur inconnu' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <!-- Partition Générale -->
                                        @if($item->chant->file_path)
                                            <button onclick="downloadFile('{{ Str::startsWith($item->chant->file_path, ['http://', 'https://']) ? $item->chant->file_path : asset('storage/' . $item->chant->file_path) }}', '{{ basename($item->chant->file_path) }}')"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-900 text-white hover:bg-gray-800 transition-all shadow-sm cursor-pointer"
                                                title="Télécharger la Partition Générale">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </button>
                                        @endif

                                        <!-- Paroles -->
                                        @if($item->chant->parole)
                                            <button @click="openLyrics(`{{ addslashes($item->chant->parole) }}`, '{{ addslashes($item->chant->title) }}')"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#7367F0]/10 text-[#7367F0] hover:bg-[#7367F0] hover:text-white transition-all border border-[#7367F0]/10"
                                                title="Paroles">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        @endif

                                        <!-- Voix/Fichiers -->
                                        @foreach($item->chant->fichiers as $fichier)
                                            @if($fichier->type == 'audio' || $fichier->type == 'youtube')
                                                <button @click="$dispatch('open-media', { type: '{{ $fichier->type }}', url: '{{ Str::startsWith($fichier->file_path, ['http://', 'https://']) ? $fichier->file_path : asset('storage/' . $fichier->file_path) }}', title: '{{ addslashes($item->chant->title) }}' })"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-[#7367F0]/10 hover:text-[#7367F0] border border-slate-100 transition-all uppercase"
                                                    title="{{ $fichier->type }} - {{ $fichier->pupitre->name ?? 'Tous' }}">
                                                    @if($fichier->type == 'audio')
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                                        </svg>
                                                    @elseif($fichier->type == 'youtube')
                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 5 .505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                                    @endif
                                                </button>
                                            @else
                                                <button onclick="downloadFile('{{ Str::startsWith($fichier->file_path, ['http://', 'https://']) ? $fichier->file_path : asset('storage/' . $fichier->file_path) }}', '{{ basename($fichier->file_path) }}')"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-[#7367F0]/10 hover:text-[#7367F0] border border-slate-100 transition-all cursor-pointer"
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
                                                </button>
                                            @endif
                                        @endforeach

                                        @if(!$item->chant->file_path && !$item->chant->parole && $item->chant->fichiers->count() == 0)
                                            <span class="text-[10px] text-slate-300 italic">Aucune</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->enregistrements->count() > 0)
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-[#28C76F]/10 flex items-center justify-center text-[#28C76F]">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-bold text-[#28C76F] uppercase">Enregistré</span>
                                                <button @click="deleteRecording({{ $item->enregistrements->first()->id }})"
                                                    class="text-[10px] text-red-400 hover:text-red-600 font-bold text-left">Supprimer</button>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-300 font-medium">Non enregistré</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button
                                        @click="openRecordingModal({{ $item->chant->id }}, {{ $item->id }}, '{{ addslashes($item->chant->title) }}')"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#7367F0]/5 hover:bg-[#7367F0] text-[#7367F0] hover:text-white rounded-xl text-xs font-bold transition-all border border-[#7367F0]/10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                        </svg>
                                        Enregistrer
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    Aucun chant dans le répertoire pour le moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Composition de la Chorale -->
        <!-- <div class="space-y-6 pb-12">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-bold text-[#444050]">Composition de la Chorale</h2>
                <div class="h-px flex-1 bg-slate-100"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($pupitres as $pupitre)
                    @if($pupitre->users->count() > 0)
                        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow group">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-bold text-slate-700">{{ $pupitre->name }}</h3>
                                <span class="text-xs font-bold text-[#7367F0] bg-[#7367F0]/10 px-2 py-0.5 rounded-md">
                                    {{ $pupitre->users->count() }}
                                </span>
                            </div>
                            <div class="space-y-2">
                                @foreach($pupitre->users as $choriste)
                                    <div class="flex items-center gap-2 text-xs text-slate-500">
                                        <div class="w-1 h-1 rounded-full {{ Auth::id() == $choriste->id ? 'bg-[#7367F0]' : 'bg-slate-200' }}"></div>
                                        <span class="{{ Auth::id() == $choriste->id ? 'font-bold text-[#444050]' : '' }}">
                                            {{ $choriste->name }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div> -->

        <!-- Recording Modal (Reusable from Chant show but simplified) -->
        <div x-show="modalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-cloak>

            <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl" @click.away="closeModal()">
                <div class="p-8 text-center space-y-6">
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-[#444050]" x-text="currentChantTitle"></h3>
                        <p class="text-xs text-slate-400 font-medium">Enregistrement pour cet événement</p>
                    </div>

                    <!-- Recorder UI -->
                    <div class="flex flex-col items-center justify-center space-y-8 py-4">
                        <!-- Visualize -->
                        <div
                            class="w-full h-32 bg-slate-50 rounded-2xl flex items-center justify-center relative overflow-hidden">
                            <div x-show="isRecording" class="flex items-center gap-1">
                                <template x-for="i in 12" :key="i">
                                    <div class="w-1.5 bg-[#7367F0] rounded-full animate-bounce"
                                        :style="{ height: Math.random() * 4 + 1 + 'rem', animationDelay: i * 0.1 + 's' }">
                                    </div>
                                </template>
                            </div>
                            <div x-show="!isRecording" class="text-slate-200">
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z" />
                                    <path
                                        d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z" />
                                </svg>
                            </div>
                            <div x-show="isRecording" class="absolute top-3 right-4 flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                                <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest"
                                    x-text="formatTime(recordingTime)">00:00</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-4">
                            <button x-show="!isRecording && !audioBlob" @click="startRecording()"
                                class="w-20 h-20 bg-[#7367F0] rounded-full flex items-center justify-center text-white shadow-lg shadow-[#7367F0]/40 hover:scale-105 transition-all">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z" />
                                    <path
                                        d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z" />
                                </svg>
                            </button>

                            <button x-show="isRecording" @click="stopRecording()"
                                class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center text-white shadow-lg shadow-red-500/40 hover:scale-105 animate-pulse transition-all">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M6 6h12v12H6z" />
                                </svg>
                            </button>

                            <div x-show="audioBlob && !isRecording" class="flex items-center gap-4">
                                <button @click="startRecording()"
                                    class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 hover:text-[#7367F0] transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                                <button @click="saveRecording()" :disabled="isSaving"
                                    class="h-16 px-10 bg-[#28C76F] rounded-2xl text-white font-bold shadow-lg shadow-[#28C76F]/20 flex items-center gap-2 hover:scale-105 transition-all disabled:opacity-50">
                                    <span x-show="!isSaving">Enregistrer ma voix</span>
                                    <span x-show="isSaving">Enregistrement...</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button @click="closeModal()"
                        class="text-slate-400 text-xs font-bold hover:text-[#444050] transition-colors">Plus tard</button>
                </div>
            </div>
        </div>

        <!-- Lyrics Modal -->
        <div x-show="lyricsOpen"
            class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-cloak>

            <div class="bg-white rounded-[2rem] w-full max-w-lg overflow-hidden shadow-2xl relative" @click.away="lyricsOpen = false">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <span class="text-[10px] font-bold text-[#7367F0] uppercase tracking-widest block mb-1">Paroles</span>
                            <h3 class="text-xl font-bold text-[#444050]" x-text="currentChantTitle"></h3>
                        </div>
                        <button @click="lyricsOpen = false" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <div class="bg-slate-50 rounded-2xl p-6 max-h-[50vh] overflow-y-auto">
                        <div class="text-sm text-slate-600 font-medium leading-relaxed font-sans" x-html="currentLyrics"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function recordingSystem() {
            return {
                modalOpen: false,
                lyricsOpen: false,
                isRecording: false,
                isSaving: false,
                mediaRecorder: null,
                audioChunks: [],
                audioBlob: null,
                recordingTime: 0,
                timer: null,
                currentChantId: null,
                currentRepertoireId: null,
                currentChantTitle: '',
                currentLyrics: '',
                mimeType: 'audio/webm',
                fileExtension: 'webm',

                init() {
                    if (MediaRecorder.isTypeSupported('audio/webm')) {
                        this.mimeType = 'audio/webm';
                        this.fileExtension = 'webm';
                    } else if (MediaRecorder.isTypeSupported('audio/mp4')) {
                        this.mimeType = 'audio/mp4';
                        this.fileExtension = 'mp4';
                    } else if (MediaRecorder.isTypeSupported('audio/mpeg')) {
                        this.mimeType = 'audio/mpeg';
                        this.fileExtension = 'mp3';
                    }
                },

                openLyrics(lyrics, title) {
                    this.currentLyrics = lyrics;
                    this.currentChantTitle = title;
                    this.lyricsOpen = true;
                },

                openRecordingModal(chantId, repertoireId, title) {
                    this.currentChantId = chantId;
                    this.currentRepertoireId = repertoireId;
                    this.currentChantTitle = title;
                    this.modalOpen = true;
                    this.audioBlob = null;
                    this.audioChunks = [];
                },

                closeModal() {
                    if (this.isRecording) this.stopRecording();
                    this.modalOpen = false;
                },

                formatTime(seconds) {
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                },

                async startRecording() {
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        this.mediaRecorder = new MediaRecorder(stream, { mimeType: this.mimeType });
                        this.audioChunks = [];

                        this.mediaRecorder.ondataavailable = (event) => {
                            this.audioChunks.push(event.data);
                        };

                        this.mediaRecorder.onstop = () => {
                            this.audioBlob = new Blob(this.audioChunks, { type: this.mimeType });
                        };

                        this.mediaRecorder.start();
                        this.isRecording = true;
                        this.recordingTime = 0;
                        this.timer = setInterval(() => this.recordingTime++, 1000);
                    } catch (err) {
                        alert("Erreur micro : " + err.message);
                    }
                },

                stopRecording() {
                    this.mediaRecorder.stop();
                    this.isRecording = false;
                    clearInterval(this.timer);
                    this.mediaRecorder.stream.getTracks().forEach(track => track.stop());
                },

                async saveRecording() {
                    this.isSaving = true;
                    const formData = new FormData();
                    formData.append('audio', this.audioBlob, `recording.${this.fileExtension}`);
                    formData.append('chant_id', this.currentChantId);
                    formData.append('repertoire_id', this.currentRepertoireId);
                    formData.append('_token', '{{ csrf_token() }}');

                    try {
                        const response = await fetch('{{ route("choriste.enregistrements.store") }}', {
                            method: 'POST',
                            body: formData
                        });

                        if (response.ok) {
                            window.location.reload();
                        } else {
                            const data = await response.json();
                            alert(data.message || "Erreur lors de l'enregistrement");
                        }
                    } catch (err) {
                        alert("Erreur reseau : " + err.message);
                    } finally {
                        this.isSaving = false;
                    }
                },

                async deleteRecording(id) {
                    if (!confirm('Voulez-vous supprimer cet enregistrement ?')) return;

                    try {
                        const response = await fetch(`/choriste/enregistrements/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (response.ok) {
                            window.location.reload();
                        }
                    } catch (err) {
                        alert("Erreur suppression");
                    }
                }
            }
        }
    </script>
    <script>
        function downloadFile(url, filename) {
            fetch(url)
                .then(res => res.blob())
                .then(blob => {
                    const a = document.createElement('a');
                    a.href = URL.createObjectURL(blob);
                    a.download = filename || 'fichier';
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    setTimeout(() => URL.revokeObjectURL(a.href), 1000);
                })
                .catch(() => window.open(url, '_blank'));
        }
    </script>
@endsection