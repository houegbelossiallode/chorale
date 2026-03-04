@extends('layouts.admin')

@section('page_title', 'Tableau de bord Choriste')

@section('content')
    <div class="space-y-6">
        <!-- Header Greeting -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 shrink-0">
                    <img src="{{ asset('images/logo chorale st oscar romero noir fond blanc.png') }}" alt="Logo"
                        class="w-full h-full object-contain">
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-semibold text-[#444050]">Bienvenue, {{ Auth::user()->first_name }}</h1>
                    <p class="text-[14px] text-slate-400 font-medium">Membre du pupitre : <span
                            class="text-[#7367F0]">{{ $choristeStats['pupitre']?->nom ?? 'Non défini' }}</span></p>
                </div>
            </div>
            <div class="flex items-center justify-center md:justify-end gap-3">
                <div class="px-4 py-2 bg-white border border-slate-100 rounded-lg shadow-sm flex items-center gap-3">
                    <img src="{{ Auth::user()->photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->first_name) . '&background=f8f7fa&color=7367f0&size=100' }}"
                        class="w-8 h-8 rounded-full object-cover">
                    <span class="text-sm font-bold text-[#444050]">Choriste</span>
                </div>
            </div>
        </div>

        <!-- Stats Grid (Admin Style) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Presence Stat -->
            <div class="card-material p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#E7E7FF] text-[#7367F0] rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[13px] text-slate-500 font-medium truncate">Présence</p>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold text-[#444050]">{{ $choristeStats['my_presence_rate'] }}%</span>
                            <span
                                class="text-[11px] font-semibold text-[#28C76F] bg-[#DFF7E9] px-1 py-0.5 rounded">Fidèle</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Streak Stat -->
            <div class="card-material p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#DFF7E9] text-[#28C76F] rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[13px] text-slate-500 font-medium truncate">Série</p>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold text-[#444050]">{{ $choristeStats['attendance_streak'] }}</span>
                            <span class="text-[11px] font-semibold text-slate-400">🔥</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Repertoire Stat -->
            <div class="card-material p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#FFF1E3] text-[#FF9F43] rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[13px] text-slate-500 font-medium truncate">Répertoire</p>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold text-[#444050]">{{ $choristeStats['total_chants_count'] }}</span>
                            <span class="text-[11px] font-semibold text-slate-400">Chants</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Updates Stat -->
            <div class="card-material p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#E5F8FF] text-[#00CFE8] rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[13px] text-slate-500 font-medium truncate">Annonces</p>
                        <div class="flex items-center gap-2">
                            <span
                                class="text-xl font-bold text-[#444050]">{{ $choristeStats['notifications']->count() }}</span>
                            <span
                                class="text-[11px] font-semibold text-[#00CFE8] bg-[#E5F8FF] px-1 py-0.5 rounded">New</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Rehearsals Timeline (Admin Table Style) -->
            <div class="card-material lg:col-span-2 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                    <h3 class="text-lg font-semibold text-[#444050]">Prochaines Répétitions</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4" x-data="{ 
                        selectedRep: null,
                        showProgramModal: false,
                        openProgram(rep) {
                            this.selectedRep = rep;
                            this.showProgramModal = true;
                        }
                    }">
                        @forelse($choristeStats['next_rehearsals'] as $repetition)
                                            <div
                                                class="p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-[#7367F0]/30 hover:bg-white transition-all group">
                                                <div class="flex flex-col md:flex-row justify-between gap-4">
                                                    <div class="space-y-1">
                                                        <div class="flex items-center gap-2">
                                                            <span
                                                                class="text-[10px] font-black uppercase text-[#7367F0] bg-[#E7E7FF] px-1.5 py-0.5 rounded tracking-widest">Session</span>
                                                            <span
                                                                class="text-xs text-slate-400 font-bold tracking-tighter">{{ \Carbon\Carbon::parse($repetition->start_time)->translatedFormat('l d F • H:i') }}</span>
                                                        </div>
                                                        <h4 class="text-md font-bold text-[#444050]">{{ $repetition->titre }}</h4>
                                                        <div class="flex items-center gap-4 text-xs text-slate-500">
                                                            <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                </svg>{{ $repetition->lieu }}</span>
                                                            <span class="text-[#7367F0] font-bold">{{ $repetition->chants->count() }}
                                                                Chants</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <button @click="openProgram({{ json_encode([
                                'titre' => $repetition->titre,
                                'start_time' => \Carbon\Carbon::parse($repetition->start_time)->translatedFormat('l d F Y'),
                                'event_title' => $repetition->event?->title,
                                'event_date' => $repetition->event ? \Carbon\Carbon::parse($repetition->event->start_at)->format('d/m/Y') : null,
                                'repertoire' => $repetition->event ? $repetition->event->repertoireEntries->filter(function ($r) use ($repetition) {
                                    return $repetition->chants->pluck('id')->contains($r->chant_id);
                                })->groupBy(function ($r) {
                                    return $r->partieEvent->titre ?? 'Autre';
                                })->map(function ($items) {
                                    return $items->map(function ($r) {
                                        return [
                                            'title' => $r->chant->title,
                                            'composer' => $r->chant->composer,
                                            'file_path' => $r->chant->file_path
                                        ];
                                    });
                                }) : null,
                                'simple_chants' => $repetition->chants->filter(function ($c) use ($repetition) {
                                    if (!$repetition->event)
                                        return true;
                                    return !$repetition->event->repertoireEntries->pluck('chant_id')->contains($c->id);
                                })->map(function ($c) {
                                    return ['title' => $c->title, 'composer' => $c->composer, 'file_path' => $c->file_path];
                                })->values()->all()
                            ]) }})"
                                                            class="btn-primary-outline text-[12px] py-1.5 px-4 flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                                            </svg>
                                                            Voir le répertoire
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal du Programme Musical (Choriste) -->
                                            <div x-show="showProgramModal"
                                                class="fixed inset-0 z-[160] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md"
                                                x-cloak x-transition.opacity>

                                                <div class="bg-white rounded-[2.5rem] w-full max-w-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh] transform transition-all border border-slate-100"
                                                    @click.away="showProgramModal = false">

                                                    <!-- Header -->
                                                    <div
                                                        class="px-8 py-8 border-b border-slate-50 shrink-0 bg-gradient-to-br from-[#7367F0]/5 to-transparent flex items-start justify-between">
                                                        <div class="space-y-1">
                                                            <div class="flex items-center gap-2 mb-1">
                                                                <div class="w-2 h-2 rounded-full bg-[#7367F0] animate-pulse"></div>
                                                                <span
                                                                    class="text-[10px] font-black uppercase text-[#7367F0] tracking-[0.2em]">Programme
                                                                    de Répétition</span>
                                                            </div>
                                                            <h3 class="text-2xl font-black text-[#444050] tracking-tight leading-none"
                                                                x-text="selectedRep?.titre"></h3>
                                                            <p class="text-sm text-slate-400 font-medium" x-text="selectedRep?.start_time"></p>
                                                        </div>
                                                        <button @click="showProgramModal = false"
                                                            class="w-10 h-10 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-100 hover:bg-red-50 transition-all shadow-sm">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Content -->
                                                    <div class="flex-1 overflow-y-auto p-8 custom-scrollbar-slim">
                                                        <!-- Event Banner if linked -->
                                                        <template x-if="selectedRep?.event_title">
                                                            <div
                                                                class="mb-8 p-6 rounded-3xl bg-blue-50 border border-blue-100/50 flex items-center gap-5">
                                                                <div
                                                                    class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/20">
                                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <div class="flex items-center gap-2 mb-0.5">
                                                                        <span
                                                                            class="text-[9px] font-black uppercase text-blue-600 tracking-[0.2em]">Agenda
                                                                            Associé</span>
                                                                    </div>
                                                                    <h4 class="text-lg font-black text-[#444050] leading-tight"
                                                                        x-text="selectedRep?.event_title"></h4>
                                                                    <p class="text-xs text-blue-500 font-bold" x-text="selectedRep?.event_date">
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </template>

                                                        <!-- Repertoire Groups -->
                                                        <div class="space-y-10">
                                                            <!-- If Repertoire from Agenda -->
                                                            <template x-if="selectedRep?.repertoire">
                                                                <div>
                                                                    <template x-for="(chants, partie) in selectedRep.repertoire" :key="partie">
                                                                        <div class="mb-8 last:mb-0">
                                                                            <h5
                                                                                class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 pl-1 flex items-center gap-3">
                                                                                <span x-text="partie"></span>
                                                                                <div class="h-[1px] flex-1 bg-slate-100"></div>
                                                                            </h5>
                                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                                <template x-for="chant in chants" :key="chant.title">
                                                                                    <div
                                                                                        class="p-4 rounded-2xl border border-slate-100 bg-white hover:border-[#7367F0]/30 transition-all flex items-center gap-4 group">
                                                                                        <div
                                                                                            class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-[#7367F0] group-hover:text-white transition-all shadow-sm">
                                                                                            <svg class="w-5 h-5" fill="none"
                                                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                                                <path stroke-linecap="round"
                                                                                                    stroke-linejoin="round" stroke-width="2"
                                                                                                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                                                                            </svg>
                                                                                        </div>
                                                                                        <div class="min-w-0 flex-1">
                                                                                            <p class="font-bold text-sm text-[#444050] truncate"
                                                                                                x-text="chant.title"></p>
                                                                                            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-tighter truncate"
                                                                                                x-text="chant.composer || 'CHEF DE CHOEUR'"></p>
                                                                                        </div>
                                                                                        <template x-if="chant.file_path">
                                                                                            <button
                                                                                                @click="$dispatch('open-media', { type: 'audio', url: chant.file_path, title: chant.title })"
                                                                                                class="w-8 h-8 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center hover:bg-orange-500 hover:text-white transition-all">
                                                                                                <svg class="w-4 h-4" fill="none"
                                                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                                                    <path stroke-linecap="round"
                                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                                                                    <path stroke-linecap="round"
                                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                                </svg>
                                                                                            </button>
                                                                                        </template>
                                                                                    </div>
                                                                                </template>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </template>

                                                            <!-- Simple Chants List (if no linked Event or extra chants) -->
                                                            <template x-if="selectedRep?.simple_chants && selectedRep.simple_chants.length > 0">
                                                                <div>
                                                                    <template x-if="selectedRep?.event_title">
                                                                        <h5
                                                                            class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 pl-1 flex items-center gap-3">
                                                                            <span>Autres chants (Hors programme)</span>
                                                                            <div class="h-[1px] flex-1 bg-slate-100"></div>
                                                                        </h5>
                                                                    </template>
                                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                        <template x-for="chant in selectedRep.simple_chants" :key="chant.title">
                                                                            <div
                                                                                class="p-4 rounded-2xl border border-slate-100 bg-white hover:border-[#7367F0]/30 transition-all flex items-center gap-4 group">
                                                                                <div
                                                                                    class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-[#7367F0] group-hover:text-white transition-all shadow-sm">
                                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                                                                    </svg>
                                                                                </div>
                                                                                <div class="min-w-0 flex-1">
                                                                                    <p class="font-bold text-sm text-[#444050] truncate"
                                                                                        x-text="chant.title"></p>
                                                                                    <p class="text-[10px] uppercase font-bold text-slate-400 tracking-tighter truncate"
                                                                                        x-text="chant.composer || 'Chef de Choeur'"></p>
                                                                                </div>
                                                                                <template x-if="chant.file_path">
                                                                                    <button
                                                                                        @click="$dispatch('open-media', { type: 'audio', url: chant.file_path, title: chant.title })"
                                                                                        class="w-8 h-8 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center hover:bg-orange-500 hover:text-white transition-all">
                                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                                            viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                stroke-width="2"
                                                                                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                stroke-width="2"
                                                                                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                        </svg>
                                                                                    </button>
                                                                                </template>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                        @empty
                            <div class="text-center py-10 text-slate-400 italic">Aucune répétition prévue.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar Widgets -->
            <div class="space-y-6">
                <!-- Notifications Widget (Admin Style) -->
                <div class="card-material overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                        <h3 class="text-md font-semibold text-[#444050]">Dernières Annonces</h3>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @forelse($choristeStats['notifications'] as $notification)
                            <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                                <div class="flex gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-semibold text-[#444050] leading-tight mb-1">
                                            {{ $notification->title }}</p>
                                        <p class="text-[11px] text-slate-400">{{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-slate-400 text-xs italic">Aucune annonce.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Pupitre Stats (Performance Style) -->
                <div class="card-material p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-[15px] font-semibold text-[#444050]">Mon Pupitre</h4>
                        <span
                            class="text-[12px] font-bold text-[#7367F0] bg-[#F1F0FF] px-2 py-0.5 rounded">{{ $choristeStats['pupitre']?->nom ?? 'Solo' }}</span>
                    </div>
                    <div class="space-y-4">
                        @foreach($choristeStats['pupitre_members']->take(5) as $member)
                            <div class="flex items-center gap-3">
                                <img src="{{ $member->photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->first_name) . '&background=f8f7fa&color=7367f0&size=100' }}"
                                    class="w-8 h-8 rounded-full border border-slate-100 shadow-sm shrink-0 object-cover">
                                <div class="min-w-0 flex-1">
                                    <p class="text-[13px] font-semibold text-[#444050] truncate">{{ $member->first_name }}
                                        {{ $member->last_name }}</p>
                                    <p class="text-[11px] text-slate-400">Connecté</p>
                                </div>
                                <span class="w-2 h-2 rounded-full bg-[#28C76F]"></span>
                            </div>
                        @endforeach
                        @if($choristeStats['pupitre_members']->count() > 5)
                            <p class="text-center text-[11px] text-[#7367F0] font-medium pt-2">
                                +{{ $choristeStats['pupitre_members']->count() - 5 }} autres membres</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Repertoire Section (Admin Table Style) -->
        <div class="card-material overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                <h3 class="text-lg font-semibold text-[#444050]">Nouveaux Chants au Répertoire</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[12px] text-slate-400 uppercase tracking-widest border-b border-slate-50">
                            <th class="px-6 py-3 font-semibold">Titre</th>
                            <th class="px-6 py-3 font-semibold whitespace-nowrap">Compositeur</th>
                            <th class="px-6 py-3 font-semibold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-[14px]">
                        @foreach($choristeStats['latest_chants'] as $chant)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-[#444050]">{{ $chant->title }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $chant->composer ?? 'Classic' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-[#7367F0] hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection