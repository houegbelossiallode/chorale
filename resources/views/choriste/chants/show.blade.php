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
                            <div class="text-base md:text-lg text-slate-700 leading-relaxed font-serif italic text-center max-w-2xl mx-auto px-4 break-words">
                                {!! $chant->parole !!}
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
                                        <!-- <a href="{{ Str::startsWith($audio->file_path, ['http://', 'https://']) ? $audio->file_path : asset('storage/' . $audio->file_path) }}" download
                                            class="w-6 h-6 flex items-center justify-center bg-white rounded-lg border border-gray-100 text-[#7367F0] hover:scale-110 transition-transform shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                        </a> -->
                                    </div>
                                    <button @click="$dispatch('open-media', { type: 'audio', url: '{{ Str::startsWith($audio->file_path, ['http://', 'https://']) ? $audio->file_path : asset('storage/' . $audio->file_path) }}', title: '{{ addslashes($chant->title) }}' })" class="w-full py-2 bg-white rounded-xl border border-gray-200 text-[#7367F0] text-xs font-bold hover:bg-[#7367F0]/5 flex items-center justify-center gap-2 transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Écouter l'audio
                                    </button>
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
                                    <button @click="$dispatch('open-media', { type: '{{ $visual->type }}', url: '{{ Str::startsWith($visual->file_path, ['http://', 'https://']) ? $visual->file_path : asset('storage/' . $visual->file_path) }}', title: '{{ addslashes($chant->title) }}' })"
                                        class="w-full text-left flex items-center gap-4 p-4 rounded-2xl bg-slate-50/50 hover:bg-purple-50/30 border border-transparent hover:border-purple-200 transition-all group">
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
                                    </button>
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
