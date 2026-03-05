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
                            $totalChants = $repertoire->count() + $extraChants->count();
                            $recordedChants = $repertoire->filter(fn($r) => $r->enregistrements->count() > 0)->count() +
                                $extraChants->filter(fn($c) => $c->enregistrements->count() > 0)->count();
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

        @if($repetition->event)
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-center gap-4 reveal">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase text-blue-600 tracking-widest">Programme de l'événement</span>
                    <h4 class="text-sm font-bold text-slate-700">{{ $repetition->event->title }}
                        ({{ \Carbon\Carbon::parse($repetition->event->start_at)->format('d/m/Y') }})</h4>
                </div>
            </div>
        @endif

        <!-- Repertoire Table -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-12">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold uppercase tracking-widest text-slate-400">
                            <th class="px-6 py-4">Partie / Type</th>
                            <th class="px-6 py-4">Chant</th>
                            <th class="px-6 py-4">Ressources</th>
                            <th class="px-6 py-4">Mon Enregistrement</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <!-- Chants de l'agenda -->
                        @foreach($repertoire as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="text-[10px] font-bold text-[#7367F0] bg-[#7367F0]/10 px-3 py-1 rounded-full uppercase tracking-tighter">
                                        {{ $item->partieEvent->titre ?? 'Agenda' }}
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
                                        @include('choriste.repetitions.partials.chant_resources', ['chant' => $item->chant])
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->enregistrements->count() > 0)
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-7 h-7 rounded-full bg-[#28C76F]/10 flex items-center justify-center text-[#28C76F]">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[9px] font-bold text-[#28C76F] uppercase">Déposé</span>
                                                <button @click="deleteRecording({{ $item->enregistrements->first()->id }})"
                                                    class="text-[9px] text-red-400 hover:text-red-600 font-bold text-left">Supprimer</button>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-300 font-medium italic">En attente</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button
                                        @click="openRecordingModal({{ $item->chant->id }}, {{ $item->id }}, '{{ addslashes($item->chant->title) }}')"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#7367F0]/5 hover:bg-[#7367F0] text-[#7367F0] hover:text-white rounded-xl text-[10px] font-bold transition-all border border-[#7367F0]/10 uppercase tracking-widest shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                        </svg>
                                        Enregistrer
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                        <!-- Chants supplémentaires -->
                        @foreach($extraChants as $chant)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="text-[10px] font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-full uppercase tracking-tighter">
                                        Libre
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-[#444050]">{{ $chant->title }}</span>
                                        <span
                                            class="text-[10px] text-slate-400 font-medium">{{ $chant->composer ?? 'Compositeur inconnu' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @include('choriste.repetitions.partials.chant_resources', ['chant' => $chant])
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($chant->enregistrements->count() > 0)
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-7 h-7 rounded-full bg-[#28C76F]/10 flex items-center justify-center text-[#28C76F]">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[9px] font-bold text-[#28C76F] uppercase">Déposé</span>
                                                <button @click="deleteRecording({{ $chant->enregistrements->first()->id }})"
                                                    class="text-[9px] text-red-400 hover:text-red-600 font-bold text-left">Supprimer</button>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-300 font-medium italic">En attente</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button
                                        @click="openRecordingModal({{ $chant->id }}, null, '{{ addslashes($chant->title) }}')"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#7367F0]/5 hover:bg-[#7367F0] text-[#7367F0] hover:text-white rounded-xl text-[10px] font-bold transition-all border border-[#7367F0]/10 uppercase tracking-widest shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                        </svg>
                                        Enregistrer
                                    </button>
                                </td>
                            </tr>
                        @endforeach
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
                                    <div class="flex items-center gap-2 text-xs text-slate-500">
                                        <div
                                            class="w-1 h-1 rounded-full {{ Auth::id() == $choriste->id ? 'bg-[#7367F0]' : 'bg-slate-200' }}">
                                        </div>
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

        <!-- Modals Identiques à la page événement -->
        @include('choriste.repetitions.partials.modals')

    </div>

    @include('choriste.repetitions.partials.scripts')
@endsection