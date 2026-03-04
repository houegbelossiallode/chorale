@extends('layouts.admin')

@section('title', 'Agenda des Répétitions')

@section('content')
    <div class="space-y-6" x-data="{
            selectedRep: null,
            showProgramModal: false,

            openProgram(rep) {
                this.selectedRep = rep;
                this.showProgramModal = true;
            }
        }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-[#444050]">Agenda des Répétitions</h1>
                <p class="text-slate-500 text-xs md:text-sm">Retrouvez toutes les séances de répétition programmées.</p>
            </div>
        </div>

        <!-- Events Grid (Premium Card Design) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($repetitions as $rep)
                    <div
                        class="bg-white rounded-[2rem] shadow-material-sm border border-slate-100 hover:border-[#7367F0]/30 transition-all group overflow-hidden flex flex-col hover:shadow-material-lg">
                        <!-- Top Section with Date & Icon -->
                        <div
                            class="h-28 bg-gradient-to-br from-slate-50 to-slate-100 relative overflow-hidden shrink-0 group-hover:from-[#7367F0]/5 group-hover:to-[#7367F0]/10 transition-colors">
                            <!-- Date Badge Floating -->
                            <div
                                class="absolute top-4 left-6 w-14 h-14 bg-white rounded-2xl shadow-lg flex flex-col items-center justify-center border border-slate-50 group-hover:scale-110 transition-transform">
                                <span
                                    class="text-[10px] font-black text-[#7367F0] uppercase leading-none mb-0.5">{{ \Carbon\Carbon::parse($rep->start_time)->translatedFormat('M') }}</span>
                                <span
                                    class="text-xl font-black text-[#444050] leading-none">{{ \Carbon\Carbon::parse($rep->start_time)->format('d') }}</span>
                            </div>

                            <!-- Icon Placeholder -->
                            <div class="absolute top-0 right-0 p-8 opacity-5">
                                <svg class="w-24 h-24 text-[#7367F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                            </div>

                            <!-- Status Badge -->
                            <div class="absolute top-4 right-6">
                                <span
                                    class="px-3 py-1 bg-white/90 backdrop-blur-md text-[#28C76F] rounded-lg text-[9px] font-black uppercase tracking-wider shadow-sm border border-[#28C76F]/10">
                                    {{ $rep->presences_count }} POINTÉS
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="mb-5">
                                <h3
                                    class="text-lg font-black text-[#444050] line-clamp-1 mb-2 group-hover:text-[#7367F0] transition-colors uppercase tracking-tight">
                                    {{ $rep->titre }}
                                </h3>

                                <div class="space-y-2">
                                    <div class="flex items-center gap-3 text-slate-400">
                                        <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-[12px] font-bold text-slate-600">
                                            {{ \Carbon\Carbon::parse($rep->start_time)->format('H:i') }} —
                                            {{ \Carbon\Carbon::parse($rep->end_time)->format('H:i') }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-3 text-slate-400">
                                        <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-[12px] font-bold text-slate-600 italic truncate">{{ $rep->lieu }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($rep->description)
                                <p class="text-[13px] text-slate-500 line-clamp-2 mb-6 min-h-[40px] italic">
                                    "{{ $rep->description }}"
                                </p>
                            @else
                                <div class="mb-6 min-h-[40px]"></div>
                            @endif

                            <!-- Actions -->
                            <div class="mt-auto pt-6 border-t border-slate-50">
                                <button @click="openProgram({{ json_encode([
                    'titre' => $rep->titre,
                    'start_time' => \Carbon\Carbon::parse($rep->start_time)->translatedFormat('l d F Y'),
                    'event_title' => $rep->event?->title,
                    'event_date' => $rep->event ? \Carbon\Carbon::parse($rep->event->start_at)->format('d/m/Y') : null,
                    'repertoire' => $rep->event ? $rep->event->repertoireEntries->filter(function ($r) use ($rep) {
                        return $rep->chants->pluck('id')->contains($r->chant_id);
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
                    'simple_chants' => $rep->chants->filter(function ($c) use ($rep) {
                        if (!$rep->event)
                            return true;
                        return !$rep->event->repertoireEntries->pluck('chant_id')->contains($c->id);
                    })->map(function ($c) {
                        return ['title' => $c->title, 'composer' => $c->composer, 'file_path' => $c->file_path];
                    })->values()->all()
                ]) }})"
                                    class="w-full py-3 bg-[#7367F0]/5 hover:bg-[#7367F0] text-[#7367F0] hover:text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.1em] transition-all flex items-center justify-center gap-3 shadow-sm active:scale-95">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                    </svg>
                                    Voir le répertoire
                                </button>
                            </div>
                        </div>
                    </div>
            @empty
                <div
                    class="col-span-full py-24 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm">
                        <svg class="w-8 h-8 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold uppercase tracking-widest opacity-60">Aucune répétition programmée</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            <div class="bg-white px-6 py-4 rounded-[2rem] shadow-material-sm border border-slate-100">
                {{ $repetitions->links() }}
            </div>
        </div>

        <!-- Modal du Programme Musical (Commun Admin/Choriste) -->
        <div x-show="showProgramModal"
            class="fixed inset-0 z-[160] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" x-cloak
            x-transition.opacity>

            <div class="bg-white rounded-[2.5rem] w-full max-w-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh] transform transition-all border border-slate-100"
                @click.away="showProgramModal = false">

                <!-- Header -->
                <div
                    class="px-8 py-8 border-b border-slate-50 shrink-0 bg-gradient-to-br from-[#7367F0]/5 to-transparent flex items-start justify-between">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-2 h-2 rounded-full bg-[#7367F0] animate-pulse"></div>
                            <span class="text-[10px] font-black uppercase text-[#7367F0] tracking-[0.2em]">Programme de
                                Répétition</span>
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
                        <div class="mb-8 p-6 rounded-3xl bg-blue-50 border border-blue-100/50 flex items-center gap-5">
                            <div
                                class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/20">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="text-[9px] font-black uppercase text-blue-600 tracking-[0.2em]">Agenda
                                        Associé</span>
                                </div>
                                <h4 class="text-lg font-black text-[#444050] leading-tight"
                                    x-text="selectedRep?.event_title"></h4>
                                <p class="text-xs text-blue-500 font-bold" x-text="selectedRep?.event_date"></p>
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
                                                            x-text="chant.composer || 'CHEF DE CHOEUR'"></p>
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
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                                </svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="font-bold text-sm text-[#444050] truncate" x-text="chant.title">
                                                </p>
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
                                                            stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
    </div>
@endsection