@extends('layouts.admin')

@section('title', $chant->title)

@section('content')
    <div class="w-full min-h-screen bg-slate-50/50 pt-4 md:pt-6">
        {{-- Header Premium --}}
        <div class="mb-8 relative">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div class="space-y-2">
                    <a href="{{ route('choriste.chants.index') }}"
                        class="inline-flex items-center gap-2 text-xs font-bold text-[#7367F0] hover:text-[#685dd8] transition-colors uppercase tracking-widest group">
                        <div class="p-1.5 rounded-lg bg-[#7367F0]/10 group-hover:bg-[#7367F0]/20 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </div>
                        Retour à la bibliothèque
                    </a>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl md:text-4xl font-extrabold text-[#444050] tracking-tight break-words">
                            {{ $chant->title }}
                        </h1>
                        @if($chant->composer)
                            <span
                                class="px-3 py-1 bg-white border border-[#7367F0]/20 text-[#7367F0] text-[10px] md:text-xs font-bold rounded-full shadow-sm whitespace-nowrap">
                                {{ $chant->composer }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div
                        class="px-4 py-2 bg-white rounded-xl shadow-material border border-gray-100 flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">Mode Apprentissage
                            Actif</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- LEFT COLUMN: Partition & Lyrics (8 cols) --}}
            <div class="lg:col-span-8 space-y-8">

                @php
                    $partitions = collect();
                    if ($chant->file_path) {
                        $partitions->push((object) [
                            'id' => 'main',
                            'file_path' => $chant->file_path,
                            'pupitre' => null,
                            'label' => 'Partition Principale',
                            'is_main' => true
                        ]);
                    }
                    foreach ($chant->fichiers->where('type', 'partition') as $f) {
                        $partitions->push((object) [
                            'id' => $f->id,
                            'file_path' => $f->file_path,
                            'pupitre' => $f->pupitre,
                            'label' => 'Partition ' . ($f->pupitre ? $f->pupitre->name : 'Générale'),
                            'is_main' => false
                        ]);
                    }
                @endphp


                @if($chant->parole)
                    <div class="bg-white rounded-3xl shadow-material-lg p-6 md:p-10 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-[#7367F0]/5 rounded-bl-full -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-500"></div>
                        <div class="relative z-10">
                            <h3 class="text-xs font-black text-[#7367F0] uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                                <div class="w-8 h-[2px] bg-[#7367F0]"></div>
                                Paroles & Texte
                            </h3>
                            <div class="whitespace-pre-wrap text-base md:text-lg text-slate-700 leading-relaxed font-serif italic text-center max-w-2xl mx-auto px-4 break-words">
                                {{ $chant->parole }}
                            </div>
                        </div>
                    </div>
                @endif


                    @if($partitions->isNotEmpty())
                        <div class="bg-white rounded-3xl shadow-material-lg overflow-hidden border border-gray-100"
                            x-data="{ activeTab: '{{ $partitions->first()->id }}' }">
                            {{-- Tab Header --}}
                            <div
                                class="px-4 md:px-8 py-5 border-b border-gray-100 bg-slate-50/50 flex flex-wrap items-center justify-between gap-4">
                                <div
                                    class="flex items-center gap-2 overflow-x-auto custom-scrollbar-slim pb-2 md:pb-0 scrollbar-hide">
                                    @foreach($partitions as $p)
                                        <button @click="activeTab = '{{ $p->id }}'"
                                            :class="activeTab === '{{ $p->id }}' ? 'bg-[#7367F0] text-white shadow-md' : 'bg-white text-slate-500 hover:bg-slate-100 border border-gray-200'"
                                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap">
                                            {{ $p->label }}
                                        </button>
                                    @endforeach
                                </div>

                                <div class="flex items-center gap-3">
                                    @foreach($partitions as $p)
                                        <a x-show="activeTab === '{{ $p->id }}'" href="{{ $p->file_path }}" target="_blank"
                                            class="p-2.5 bg-white border border-gray-200 text-red-500 rounded-xl hover:bg-red-50 transition-all shadow-sm"
                                            title="Ouvrir plein écran">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Tab Content --}}
                            <div class="p-2 bg-slate-100">
                                @foreach($partitions as $p)
                                    <div x-show="activeTab === '{{ $p->id }}'" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                        class="min-h-[500px] md:aspect-[1/1.4] w-full rounded-2xl overflow-hidden shadow-inner">
                                        <iframe src="{{ $p->file_path }}#toolbar=0" class="w-full h-full min-h-[500px]"
                                            frameborder="0"></iframe>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div
                            class="bg-white rounded-3xl p-10 md:p-20 text-center shadow-material border border-dashed border-gray-300">
                            <div
                                class="w-16 md:w-20 h-16 md:h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                <svg class="w-8 md:w-10 h-8 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-600">Aucune partition</h3>
                            <p class="text-slate-400">La partition n'a pas encore été partagée par le chef.</p>
                        </div>
                    @endif


                </div>

                {{-- RIGHT COLUMN: Interactive Widgets (4 cols) --}}
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24 self-start">

                    {{-- RECORDER WIDGET (Glassmorphism) --}}
                    <div class="backdrop-blur-md bg-white/60 border border-white/50 rounded-3xl p-6 shadow-material-lg">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-sm font-extrabold text-[#7367F0] uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                </svg>
                                S'enregistrer
                            </h3>
                        </div>

                        <div class="space-y-6" id="recorder-container">
                            <div
                                class="flex items-center justify-center py-4 bg-slate-50/50 rounded-2xl relative overflow-hidden group">
                                <div id="visualizer-bg"
                                    class="absolute inset-0 opacity-10 pointer-events-none flex items-center justify-center gap-0.5 px-2">
                                    @for ($i = 0; $i < 40; $i++)
                                        <div class="v-bar w-1 bg-[#7367F0] rounded-full h-4 transition-all duration-75"></div>
                                    @endfor
                                </div>

                                <button id="startBtn"
                                    class="relative z-10 w-20 h-20 bg-red-500 text-white rounded-full flex items-center justify-center shadow-xl hover:bg-red-600 transition-all hover:scale-110 group active:scale-95">
                                    <div class="w-6 h-6 bg-white rounded-full"></div>
                                </button>
                                <button id="stopBtn"
                                    class="relative z-10 w-20 h-20 bg-slate-900 text-white rounded-full flex items-center justify-center shadow-xl hover:bg-black transition-all hover:scale-110 active:scale-95 hidden">
                                    <div class="w-6 h-6 bg-white rounded-sm"></div>
                                </button>
                            </div>

                            <div id="recorderStatus" class="text-center">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                                <p id="statusLabel" class="text-xs font-bold text-slate-600 mb-2 uppercase">Prêt à enregistrer
                                </p>
                                <p id="timer" class="text-4xl font-mono text-slate-800 font-extrabold hidden">00:00</p>
                            </div>

                            <div id="previewContainer" class="hidden animate-fade-in space-y-4 pt-6 border-t border-gray-100">
                                <div class="p-4 bg-[#7367F0]/5 rounded-2xl border border-[#7367F0]/10">
                                    <p class="text-[10px] font-bold text-[#7367F0] uppercase tracking-widest mb-3 text-center">
                                        Écouter ma prise</p>
                                    <audio id="audioPreview" controls class="w-full h-8 custom-audio"></audio>
                                </div>
                                <div class="grid grid-cols-2 gap-3 md:gap-4">
                                    <button id="discardBtn"
                                        class="py-3 md:py-4 bg-slate-100 text-slate-600 text-xs md:text-sm font-bold rounded-2xl hover:bg-slate-200 transition-all">
                                        Recommencer
                                    </button>
                                    <button id="uploadBtn"
                                        class="w-full py-3 md:py-4 bg-[#7367F0] text-white text-xs md:text-sm font-bold rounded-2xl flex items-center justify-center gap-2 md:gap-3 hover:bg-[#685dd8] transition-all shadow-lg hover:shadow-[#7367F0]/30 group">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 group-hover:-translate-y-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Envoyer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MY RECORDINGS --}}
                    @if($enregistrements->isNotEmpty())
                        <div class="bg-white rounded-3xl shadow-material-lg p-5 md:p-6 border border-gray-100">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Historique Prises
                            </h3>

                            <div class="space-y-4 max-h-[400px] overflow-y-auto custom-scrollbar-slim pr-1">
                                @foreach($enregistrements as $rec)
                                    <div class="p-4 rounded-2xl border border-slate-50 bg-slate-50/30 hover:bg-slate-50 transition-colors">
                                        <div class="flex justify-between items-center mb-3">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span
                                                    class="text-[10px] font-black text-slate-400 font-mono">{{ $rec->created_at->format('d/m/Y H:i') }}</span>
                                                @if($rec->chef_comment)
                                                    <span
                                                        class="px-2 py-0.5 bg-green-50 text-green-600 text-[8px] font-black uppercase rounded">Avis
                                                        Reçu</span>
                                                @else
                                                    <span
                                                        class="px-2 py-0.5 bg-yellow-50 text-yellow-600 text-[8px] font-black uppercase rounded">En
                                                        attente</span>
                                                @endif
                                            </div>
                                            <button onclick="deleteRecording({{ $rec->id }})"
                                                class="p-1.5 text-slate-300 hover:text-red-500 transition-colors" title="Supprimer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        <audio id="rec-{{ $rec->id }}" src="{{ $rec->file_path }}" controls class="w-full h-6 mb-3 custom-audio-mini"></audio>

                                        @if($rec->chef_comment)
                                            <div class="p-3 bg-white border border-[#7367F0]/20 rounded-xl relative mt-2 shadow-sm">
                                                <div
                                                    class="absolute -top-2 left-3 px-1.5 bg-[#7367F0] text-white text-[8px] font-black rounded uppercase">
                                                    Feedback Chef</div>
                                                <p class="text-[11px] text-slate-600 font-medium italic leading-relaxed">
                                                    {{ $rec->chef_comment }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- AUDIO RESOURCES --}}
                    @php $audioFiles = $chant->fichiers->where('type', 'audio'); @endphp
                    @if($audioFiles->isNotEmpty())
                        <div class="bg-white rounded-3xl shadow-material-lg p-5 md:p-6 border border-gray-100">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                </svg>
                                Voix de Travail
                            </h3>

                            @foreach($audioFiles as $audio)
                                <div class="mb-5 last:mb-0 p-4 bg-slate-50/50 rounded-2xl group transition-all hover:bg-blue-50/30">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">
                                            {{ $audio->pupitre ? $audio->pupitre->name : 'Tutti' }}
                                        </span>
                                        <a href="{{ $audio->file_path }}" download
                                            class="w-6 h-6 flex items-center justify-center bg-white rounded-lg border border-gray-100 text-[#7367F0] hover:scale-110 transition-transform shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                        </a>
                                    </div>
                                    <audio src="{{ $audio->file_path }}" controls class="w-full h-8 custom-audio"></audio>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- VIDEOS --}}
                    @php $visualFiles = $chant->fichiers->whereIn('type', ['video', 'youtube']); @endphp
                    @if($visualFiles->isNotEmpty())
                        <div class="bg-white rounded-3xl shadow-material-lg p-5 md:p-6 border border-gray-100">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Contenus Vidéo
                            </h3>

                            <div class="space-y-3">
                                @foreach($visualFiles as $visual)
                                    <a href="{{ $visual->file_path }}" target="_blank"
                                        class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50/50 hover:bg-purple-50/30 border border-transparent hover:border-purple-200 transition-all group">
                                        @if($visual->type === 'youtube')
                                            <div
                                                class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm group-hover:scale-110 transition-transform">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
                                                </svg>
                                            </div>
                                        @else
                                            <div
                                                class="w-10 h-10 bg-purple-50 text-purple-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm group-hover:scale-110 transition-transform">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif

                                        <div class="overflow-hidden">
                                            <p
                                                class="text-sm font-bold text-slate-700 truncate group-hover:text-purple-600 transition-colors">
                                                {{ $visual->pupitre ? $visual->pupitre->name : 'Général' }}</p>
                                            <p class="text-[10px] text-slate-400 font-black uppercase">
                                                {{ $visual->type === 'youtube' ? 'YouTube link' : 'Vidéo locale' }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <style>
            /* Modern Scrollbar Slim */
            .custom-scrollbar-slim::-webkit-scrollbar {
                width: 3px;
                height: 3px;
            }

            .custom-scrollbar-slim::-webkit-scrollbar-thumb {
                background: #dbdade;
                border-radius: 10px;
            }

            /* Premium Audio Styling */
            .custom-audio::-webkit-media-controls-panel {
                background-color: #f1f5f9;
            }

            .custom-audio::-webkit-media-controls-play-button {
                background-color: #7367F0;
                border-radius: 50%;
                color: white;
            }

            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }

            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>
@endsection

@push('scripts')
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script>
        let mediaRecorder;
        let audioChunks = [];
        let timerInterval;
        let startTime;
        let audioBlob;

        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const timerElem = document.getElementById('timer');
        const statusLabel = document.getElementById('statusLabel');
        const visualizerBg = document.getElementById('visualizer-bg');
        const previewContainer = document.getElementById('previewContainer');
        const audioPreview = document.getElementById('audioPreview');
        const uploadBtn = document.getElementById('uploadBtn');
        const discardBtn = document.getElementById('discardBtn');

        startBtn.onclick = async () => {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

                startBtn.classList.add('hidden');
                stopBtn.classList.remove('hidden');
                timerElem.classList.remove('hidden');
                previewContainer.classList.add('hidden');
                statusLabel.textContent = "ENREGISTREMENT...";
                statusLabel.classList.add('text-red-500', 'animate-pulse');

                startTime = Date.now();
                timerInterval = setInterval(() => {
                    const diff = Math.floor((Date.now() - startTime) / 1000);
                    timerElem.textContent = `${Math.floor(diff / 60).toString().padStart(2, '0')}:${(diff % 60).toString().padStart(2, '0')}`;
                }, 1000);

                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.ondataavailable = (e) => audioChunks.push(e.data);

                mediaRecorder.onstop = () => {
                    audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                    audioPreview.src = URL.createObjectURL(audioBlob);
                    previewContainer.classList.remove('hidden');
                    statusLabel.textContent = "ENREGISTREMENT TERMINÉ";
                    statusLabel.classList.remove('text-red-500', 'animate-pulse');
                    stream.getTracks().forEach(t => t.stop());
                };

                audioChunks = [];
                mediaRecorder.start();
                animateBars(stream);

            } catch (err) { alert("Micro inaccessible !"); }
        };

        stopBtn.onclick = () => {
            mediaRecorder.stop();
            clearInterval(timerInterval);
            startBtn.classList.remove('hidden');
            stopBtn.classList.add('hidden');
        };

        discardBtn.onclick = () => {
            audioChunks = [];
            audioBlob = null;
            audioPreview.src = '';
            previewContainer.classList.add('hidden');
            statusLabel.textContent = "PRÊT À ENREGISTRER";
            statusLabel.classList.remove('text-red-500', 'animate-pulse');
            timerElem.classList.add('hidden');
            timerElem.textContent = "00:00";
        };

        function animateBars(stream) {
            const ctx = new AudioContext();
            const src = ctx.createMediaStreamSource(stream);
            const anlyz = ctx.createAnalyser();
            src.connect(anlyz);
            const data = new Uint8Array(anlyz.frequencyBinCount);
            const bars = document.querySelectorAll('.v-bar');
            function draw() {
                if (mediaRecorder.state !== 'recording') return;
                requestAnimationFrame(draw);
                anlyz.getByteFrequencyData(data);
                bars.forEach((b, i) => b.style.height = `${data[i % 50] / 2 + 10}px`);
            }
            draw();
        }

        uploadBtn.onclick = async () => {
            const originalText = uploadBtn.innerHTML;
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<span class="animate-spin mr-2">◌</span> Envoi...';
            const formData = new FormData();
            formData.append('audio', audioBlob, 'record.webm');
            formData.append('chant_id', '{{ $chant->id }}');
            try {
                const res = await fetch('{{ route("choriste.enregistrements.store") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const result = await res.json();
                if (result.success) window.location.reload();
                else alert("Erreur: " + result.message);
            } catch (err) { alert("Erreur lors de l'envoi."); }
            finally { uploadBtn.disabled = false; uploadBtn.innerHTML = originalText; }
        };

        async function deleteRecording(id) {
            if (!confirm('Supprimer cet enregistrement définitivement ?')) return;
            try {
                const res = await fetch(`/choriste/enregistrements/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const result = await res.json();
                if (result.success) window.location.reload();
                else alert("Erreur: " + result.message);
            } catch (err) { alert("Erreur lors de la suppression."); }
        }
    </script>
@endpush