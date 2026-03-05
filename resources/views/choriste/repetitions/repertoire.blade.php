@extends('layouts.admin')

@section('title', 'Répertoire - ' . $repetition->titre)

@section('content')
    <div class="space-y-6" x-data="recordingSystem()">
        <!-- Header Cadre -->
        <div class="bg-gradient-to-br from-[#7367F0] to-[#4834D4] rounded-2xl p-8 text-white shadow-xl shadow-[#7367F0]/20">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="space-y-2">
                    <a href="{{ route('choriste.repetitions.index') }}"
                        class="inline-flex items-center gap-2 text-white/70 hover:text-white text-xs font-bold transition-colors mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Retour aux répétitions
                    </a>
                    <h1 class="text-3xl font-extrabold tracking-tight">{{ $repetition->titre }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-white/80 text-sm">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($repetition->start_time)->translatedFormat('d F Y') }}
                        </span>
                        <span class="w-1 h-1 rounded-full bg-white/30"></span>
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($repetition->start_time)->format('H:i') }}
                        </span>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20">
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-60 mb-1">Votre Progression</p>
                    <div class="flex items-center gap-3">
                        @php
                            $totalChants = $repertoire->count();
                            $recordedChants = $repertoire->filter(fn($r) => $r->enregistrements->count() > 0)->count();
                            $percent = $totalChants > 0 ? ($recordedChants / $totalChants) * 100 : 0;
                        @endphp
                        <div class="w-32 h-2 bg-white/20 rounded-full overflow-hidden">
                            <div class="h-full bg-white rounded-full transition-all duration-1000"
                                style="width: {{ $percent }}%">
                            </div>
                        </div>
                        <span class="text-xs font-bold">{{ $recordedChants }}/{{ $totalChants }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($repertoire->groupBy('event_id')->count() > 0)
            <div class="space-y-12">
                @foreach($repertoire->groupBy('event_id') as $eventId => $items)
                    @php $event = $items->first()->event; @endphp
                    <div class="space-y-4">
                        <div
                            class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-center justify-between gap-4 reveal">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase text-blue-600 tracking-widest">Programme de
                                        l'événement</span>
                                    <h4 class="text-sm font-bold text-slate-700">{{ $event->title }}
                                        ({{ \Carbon\Carbon::parse($event->start_at)->format('d/m/Y') }})</h4>
                                </div>
                            </div>
                            <span
                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold uppercase">{{ $items->count() }}
                                Chants</span>
                        </div>

                        <!-- Repertoire Table for Event -->
                        <!-- Card Grid for Event Repertoire -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($items as $item)
                                <div
                                    class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all flex flex-col group relative overflow-hidden">
                                    <!-- Header: Part Label -->
                                    <div class="flex items-center justify-between mb-4 flex-shrink-0">
                                        <span
                                            class="text-[10px] font-black text-[#7367F0] bg-[#7367F0]/10 px-3 py-1 rounded-full uppercase tracking-tighter">
                                            {{ $item->partieEvent->titre ?? 'Agenda' }}
                                        </span>
                                        @if($item->enregistrements->count() > 0)
                                            <div
                                                class="w-6 h-6 rounded-full bg-[#28C76F]/10 flex items-center justify-center text-[#28C76F]">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content: Chant Title & Composer -->
                                    <div class="flex-1 space-y-1 mb-6">
                                        <h5
                                            class="font-black text-[#444050] text-base leading-tight group-hover:text-[#7367F0] transition-colors line-clamp-2">
                                            {{ $item->chant->title }}
                                        </h5>
                                        <p class="text-xs text-slate-400 font-medium italic">
                                            {{ $item->chant->composer ?? 'Compositeur inconnu' }}
                                        </p>
                                    </div>

                                    <!-- Resources -->
                                    <div class="mb-6 pt-4 border-t border-slate-50">
                                        <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest mb-3">Ressources
                                            disponibles</p>
                                        <div class="flex flex-wrap gap-2">
                                            @include('choriste.repetitions.partials.chant_resources', ['chant' => $item->chant])
                                        </div>
                                    </div>

                                    <!-- Footer: Recording Status & Action -->
                                    <div class="mt-auto space-y-3">
                                        @if($item->enregistrements->count() > 0)
                                            <div
                                                class="flex items-center justify-between bg-emerald-50/50 p-3 rounded-2xl border border-emerald-100">
                                                <div class="flex flex-col">
                                                    <span class="text-[9px] font-black text-emerald-600 uppercase">Mon travail déposé</span>
                                                    <span class="text-[8px] text-emerald-400">Prêt pour révision</span>
                                                </div>
                                                <button @click="deleteRecording({{ $item->enregistrements->first()->id }})"
                                                    class="p-2 text-red-300 hover:text-red-500 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif

                                        <button
                                            @click="openRecordingModal({{ $item->chant->id }}, {{ $item->id }}, '{{ addslashes($item->chant->title) }}')"
                                            class="w-full flex items-center justify-center gap-2 py-3 bg-[#7367F0] hover:bg-[#4834D4] text-white rounded-2xl text-[11px] font-black transition-all shadow-lg shadow-[#7367F0]/20 uppercase tracking-widest">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                            </svg>
                                            {{ $item->enregistrements->count() > 0 ? 'Ré-enregistrer' : 'Enregistrer ma voix' }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="text-slate-400 text-sm italic font-medium">Aucun chant n'est encore programmé pour cette séance.</p>
            </div>
        @endif

        <!-- Composition de la Chorale -->
        <!-- <div class="space-y-6 pb-12 mt-12">
                <div class="flex items-center gap-4">
                    <h2 class="text-xl font-bold text-[#444050]">Composition de la Chorale</h2>
                    <div class="h-px flex-1 bg-slate-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($pupitres as $pupitre)
                        @if($pupitre->users->count() > 0)
                            <div
                                class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow group">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-bold text-slate-700">{{ $pupitre->name }}</h3>
                                    <span class="text-xs font-bold text-[#7367F0] bg-[#7367F0]/10 px-2 py-0.5 rounded-md">
                                        {{ $pupitre->users->count() }}
                                    </span>
                                </div>
                                <div class="space-y-2">
                                    @foreach($pupitre->users as $choriste)
                                        <div class="flex items-center gap-2 text-[10px] text-slate-500">
                                            <div
                                                class="w-1 h-1 rounded-full {{ Auth::id() == $choriste->id ? 'bg-[#7367F0]' : 'bg-slate-200' }}">
                                            </div>
                                            <span class="{{ Auth::id() == $choriste->id ? 'font-bold text-[#444050]' : '' }}">
                                                {{ $choriste->first_name }} {{ $choriste->last_name }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div> -->



        <!-- Modals Identiques à la page événement -->
        @include('choriste.repetitions.partials.modals')

    </div>

    @include('choriste.repetitions.partials.scripts')
@endsection