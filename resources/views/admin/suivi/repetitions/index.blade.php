@extends('layouts.admin')

@section('title', 'Gestion des Répétitions')

@section('content')
    <div class="space-y-6" x-data="{
                            repModal: false,
                            autoModal: false,
                            editMode: false,
                            currentId: null,
                            formData: { titre: '', start_time: '', end_time: '', lieu: '', description: '' },
                            actionUrl: '{{ route('admin.repetitions.store') }}',
                            selectedRep: null,
                            showProgramModal: false,

                            openProgram(rep) {
                                this.selectedRep = rep;
                                this.showProgramModal = true;
                            },

                            openModal(rep = null) {
                                if (rep) {
                                    this.editMode = true;
                                    this.currentId = rep.id;
                                    this.formData.titre = rep.titre;
                                    this.formData.start_time = rep.start_time.substring(0, 16);
                                    this.formData.end_time = rep.end_time.substring(0, 16);
                                    this.formData.lieu = rep.lieu;
                                    this.formData.description = rep.description;
                                    this.actionUrl = `/admin/repetitions/${rep.id}`;
                                } else {
                                    this.editMode = false;
                                    this.formData = { titre: '', start_time: '', end_time: '', lieu: '', description: '' };
                                    this.actionUrl = '{{ route('admin.repetitions.store') }}';
                                }
                                this.repModal = true;
                            }
                        }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-[#444050]">Gestion des Répétitions</h1>
                <p class="text-slate-500 text-xs md:text-sm">Organisez les séances et suivez l'assiduité.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <button @click="autoModal = true"
                    class="btn-primary-outline flex items-center justify-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-12a2 2 0 002-2z" />
                    </svg>
                    Planification Auto
                </button>
                <button @click="openModal()" class="btn-primary flex items-center justify-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Séance Manuelle
                </button>
            </div>
        </div>

        @if(session('success'))
            <div
                class="p-4 bg-[#DFF7E9] border border-[#28C76F]/20 text-[#28C76F] rounded-xl font-semibold text-sm animate-fade-in">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-material border border-slate-100 overflow-hidden">
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 text-[#444050] text-[11px] uppercase tracking-widest font-bold border-b border-gray-100">
                            <th class="px-8 py-5">Titre & Séance</th>
                            <th class="px-8 py-5">Lieu</th>
                            <th class="px-8 py-5">Horaires</th>
                            <th class="px-8 py-5">Assiduité</th>
                            <th class="px-8 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[14px]">
                        @forelse($repetitions as $rep)
                                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                                <td class="px-8 py-5">
                                                    <div class="flex items-center gap-4">
                                                        <div
                                                            class="w-12 h-12 rounded-2xl bg-[#7367F0]/10 flex flex-col items-center justify-center text-[#7367F0]">
                                                            <span
                                                                class="text-[10px] font-black uppercase tracking-tighter">{{ \Carbon\Carbon::parse($rep->start_time)->format('M') }}</span>
                                                            <span
                                                                class="text-lg font-black leading-none">{{ \Carbon\Carbon::parse($rep->start_time)->format('d') }}</span>
                                                        </div>
                                                        <div>
                                                            <p
                                                                class="font-black text-[#444050] group-hover:text-[#7367F0] transition-colors uppercase tracking-tight">
                                                                {{ $rep->titre }}
                                                            </p>
                                                            <p class="text-[11px] text-slate-400 font-medium italic truncate max-w-[200px]">
                                                                {{ $rep->description ?: 'Aucun détail' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-8 py-5 text-slate-600 font-medium italic">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                        {{ $rep->lieu }}
                                                    </div>
                                                </td>
                                                <td class="px-8 py-5">
                                                    <div class="space-y-0.5">
                                                        <p class="text-xs font-bold text-[#444050]">
                                                            {{ \Carbon\Carbon::parse($rep->start_time)->format('H:i') }} —
                                                            {{ \Carbon\Carbon::parse($rep->end_time)->format('H:i') }}
                                                        </p>
                                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                                            {{ \Carbon\Carbon::parse($rep->start_time)->diffInMinutes($rep->end_time) }} minutes
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="px-8 py-5">
                                                    <span
                                                        class="px-3 py-1 bg-[#28C76F]/10 text-[#28C76F] rounded-full text-[10px] font-black uppercase tracking-widest border border-[#28C76F]/20">
                                                        {{ $rep->presences_count }} POINTÉS
                                                    </span>
                                                </td>
                                                <td class="px-8 py-5 text-right">
                                                    <div class="flex justify-end items-center" x-data="{ open: false }">
                                                        <div class="relative">
                                                            <button @click="open = !open" @click.away="open = false"
                                                                class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-[#7367F0] hover:bg-[#7367F0]/10 transition-all flex items-center justify-center border border-transparent hover:border-[#7367F0]/20">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                                </svg>
                                                            </button>

                                                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                                                x-transition:enter-start="transform opacity-0 scale-95"
                                                                x-transition:enter-end="transform opacity-100 scale-100"
                                                                class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-material-lg border border-slate-100 z-[50] overflow-hidden"
                                                                x-cloak>
                                                                <div class="p-2 space-y-1">
                                                                    <!-- Voir Programme -->
                                                                    <button @click="open = false; openProgram({{ json_encode([
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
                            ]) }})" class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all uppercase tracking-widest">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                                                        </svg>
                                                                        Voir Programme
                                                                    </button>

                                                                    @if(Auth::user()->role && (str_contains(strtolower(Auth::user()->role->libelle), 'admin') || str_contains(strtolower(Auth::user()->role->libelle), 'administrateur')))
                                                                        <div class="h-[1px] bg-slate-50 my-1"></div>

                                                                        <!-- Relancer -->
                                                                        <form action="{{ route('admin.repetitions.reminder', $rep->id) }}"
                                                                            method="POST"
                                                                            onsubmit="return confirm('Souhaitez-vous relancer tous les choristes actifs pour cette répétition ?');">
                                                                            @csrf
                                                                            <button type="submit"
                                                                                class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-600 hover:text-green-600 hover:bg-green-50 rounded-xl transition-all uppercase tracking-widest text-left">
                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                                                </svg>
                                                                                Relancer
                                                                            </button>
                                                                        </form>

                                                                        <!-- Faire l'appel -->
                                                                        <a href="{{ route('admin.repetitions.show', $rep->id) }}"
                                                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-600 hover:text-[#7367F0] hover:bg-[#7367F0]/5 rounded-xl transition-all uppercase tracking-widest">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                                            </svg>
                                                                            Faire l'appel
                                                                        </a>

                                                                        <!-- Modifier -->
                                                                        <button @click="open = false; openModal({{ json_encode($rep) }})"
                                                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-600 hover:text-orange-500 hover:bg-orange-50 rounded-xl transition-all uppercase tracking-widest text-left">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                            </svg>
                                                                            Modifier
                                                                        </button>

                                                                        <div class="h-[1px] bg-slate-50 my-1"></div>

                                                                        <!-- Annuler -->
                                                                        <form action="{{ route('admin.repetitions.destroy', $rep->id) }}"
                                                                            method="POST" onsubmit="return confirm('Annuler cette répétition ?');">
                                                                            @csrf @method('DELETE')
                                                                            <button type="submit"
                                                                                class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-red-500 hover:bg-red-50 rounded-xl transition-all uppercase tracking-widest text-left">
                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                </svg>
                                                                                Annuler
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div
                                        class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-slate-400 italic font-medium">Aucune répétition programmée pour le moment.
                                    </p>
                                    <button @click="openModal()"
                                        class="mt-4 text-[#7367F0] font-black text-xs uppercase hover:underline">Programmer la
                                        première séance</button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($repetitions as $rep)
                    <div class="p-5 space-y-4 bg-white hover:bg-slate-50 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-[#7367F0]/10 flex flex-col items-center justify-center text-[#7367F0] shrink-0">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-tighter">{{ \Carbon\Carbon::parse($rep->start_time)->format('M') }}</span>
                                    <span
                                        class="text-lg font-black leading-none">{{ \Carbon\Carbon::parse($rep->start_time)->format('d') }}</span>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-black text-[#444050] text-base uppercase tracking-tight truncate">
                                        {{ $rep->titre }}
                                    </h3>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                        {{ \Carbon\Carbon::parse($rep->start_time)->format('H:i') }} —
                                        {{ \Carbon\Carbon::parse($rep->end_time)->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 shrink-0 bg-slate-50 rounded-xl p-1">
                                <a href="{{ route('admin.repetitions.show', $rep->id) }}"
                                    class="w-9 h-9 rounded-lg flex items-center justify-center text-[#7367F0] hover:bg-white hover:shadow-sm transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </a>
                                <button @click="openModal({{ json_encode($rep) }})"
                                    class="w-9 h-9 rounded-lg flex items-center justify-center text-orange-500 hover:bg-white hover:shadow-sm transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <form action="{{ route('admin.repetitions.destroy', $rep->id) }}" method="POST"
                                    onsubmit="return confirm('Annuler ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-9 h-9 rounded-lg flex items-center justify-center text-red-500 hover:bg-white hover:shadow-sm transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 py-3 border-y border-slate-50">
                            <div class="flex items-center gap-3 text-slate-500 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-widest truncate">{{ $rep->lieu }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <span
                                class="px-3 py-1 bg-[#28C76F]/10 text-[#28C76F] rounded-full text-[10px] font-black uppercase tracking-widest border border-[#28C76F]/20">
                                {{ $rep->presences_count }} POINTÉS
                            </span>
                            @if($rep->description)
                                <p class="text-[10px] text-slate-400 font-medium italic truncate max-w-[150px]">
                                    {{ $rep->description }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-slate-400 italic">Aucune répétition.</div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="px-8 py-4 bg-slate-50 border-t border-gray-100 flex justify-center">
                {{ $repetitions->links() }}
            </div>
        </div>

        <div x-show="repModal"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" x-cloak
            x-transition.opacity>
            <div class="bg-white rounded-2xl w-full max-w-md shadow-material-lg overflow-hidden flex flex-col max-h-[90vh] mx-2"
                @click.away="repModal = false">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                    <h3 class="font-bold text-lg text-[#444050] uppercase tracking-tight"
                        x-text="editMode ? 'Modifier la Répétition' : 'Programmer une Séance'"></h3>
                    <button @click="repModal = false" class="text-slate-400 hover:text-red-500 transition-all p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form :action="actionUrl" method="POST" class="flex flex-col overflow-hidden">
                    @csrf
                    <div class="p-6 space-y-5 overflow-y-auto custom-scrollbar-slim">
                        <template x-if="editMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Titre de la
                                Séance</label>
                            <input type="text" name="titre" x-model="formData.titre" required
                                class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-50 outline-none focus:border-[#7367F0]/20 transition-all font-bold text-[#444050] placeholder:text-slate-300"
                                placeholder="Ex: RÉPÉTITION GÉNÉRALE">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label
                                    class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Début</label>
                                <input type="datetime-local" name="start_time" x-model="formData.start_time" required
                                    class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-50 outline-none focus:border-[#7367F0]/20 transition-all font-medium text-[#444050] text-xs">
                            </div>
                            <div class="space-y-1">
                                <label
                                    class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Fin</label>
                                <input type="datetime-local" name="end_time" x-model="formData.end_time" required
                                    class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-50 outline-none focus:border-[#7367F0]/20 transition-all font-medium text-[#444050] text-xs">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Lieu</label>
                            <input type="text" name="lieu" x-model="formData.lieu" required
                                class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-50 outline-none focus:border-[#7367F0]/20 transition-all font-medium text-[#444050] placeholder:text-slate-300"
                                placeholder="Ex: Salle paroissiale">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Description /
                                Ordre du Jour</label>
                            <textarea name="description" x-model="formData.description" rows="2"
                                class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-50 outline-none focus:border-[#7367F0]/20 transition-all font-medium text-[#444050] resize-none text-sm placeholder:text-slate-300"
                                placeholder="Optionnel"></textarea>
                        </div>
                    </div>

                    <div class="p-6 border-t border-slate-50 flex justify-end gap-3 bg-slate-50/30">
                        <button type="button" @click="repModal = false"
                            class="px-6 py-2.5 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-100 transition-all">
                            Annuler
                        </button>
                        <button type="submit"
                            class="btn-primary px-8 py-2.5 rounded-xl shadow-lg shadow-[#7367F0]/20 text-sm">
                            <span x-text="editMode ? 'Enregistrer' : 'Programmer'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Planification Automatique -->
        <div x-show="autoModal"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" x-cloak
            x-transition.opacity>
            <div class="bg-white rounded-2xl w-full max-w-lg shadow-material-lg overflow-hidden flex flex-col max-h-[90vh]"
                @click.away="autoModal = false">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                    <h3 class="font-bold text-lg text-[#444050] uppercase tracking-tight">Planification Automatique</h3>
                    <button @click="autoModal = false" class="text-slate-400 hover:text-red-500 transition-all p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('admin.repetitions.automate') }}" method="POST"
                    class="flex flex-col overflow-hidden">
                    @csrf
                    <div class="p-6 space-y-5 overflow-y-auto custom-scrollbar-slim">
                        <p class="text-[11px] text-slate-500 italic">Cet outil va générer toutes les répétitions pour le
                            mois et le jour de la semaine choisis.</p>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label
                                    class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Mois</label>
                                <select name="month"
                                    class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-50 outline-none focus:border-[#7367F0]/20 transition-all font-bold text-[#444050]">
                                    @foreach(['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'] as $i => $m)
                                        <option value="{{ $i + 1 }}" {{ date('n') == $i + 1 ? 'selected' : '' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label
                                    class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Année</label>
                                <select name="year"
                                    class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-50 outline-none focus:border-[#7367F0]/20 transition-all font-bold text-[#444050]">
                                    <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                                    <option value="{{ date('Y') + 1 }}">{{ date('Y') + 1 }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Jour de la
                                semaine</label>
                            <select name="day_of_week"
                                class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-50 outline-none focus:border-[#7367F0]/20 transition-all font-bold text-[#444050]">
                                <option value="1">Lundi</option>
                                <option value="2">Mardi</option>
                                <option value="3">Mercredi</option>
                                <option value="4">Jeudi</option>
                                <option value="5">Vendredi</option>
                                <option value="6">Samedi</option>
                                <option value="0">Dimanche</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Heure
                                    Début</label>
                                <input type="time" name="start_time" value="19:00" required
                                    class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-50 outline-none focus:border-[#7367F0]/20 transition-all font-bold text-[#444050]">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Heure
                                    Fin</label>
                                <input type="time" name="end_time" value="21:00" required
                                    class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-50 outline-none focus:border-[#7367F0]/20 transition-all font-bold text-[#444050]">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Titre par
                                défaut</label>
                            <input type="text" name="titre" value="RÉPÉTITION HEBDOMADAIRE" required
                                class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-50 outline-none focus:border-[#7367F0]/20 transition-all font-bold text-[#444050]">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Lieu</label>
                            <input type="text" name="lieu" value="Salle paroissiale" required
                                class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-50 outline-none focus:border-[#7367F0]/20 transition-all font-bold text-[#444050]">
                        </div>

                        <div class="space-y-1">
                            <label
                                class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Description</label>
                            <textarea name="description" rows="2"
                                class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-50 outline-none focus:border-[#7367F0]/20 transition-all font-medium text-[#444050] resize-none text-sm"></textarea>
                        </div>
                    </div>

                    <div class="p-6 border-t border-slate-50 flex justify-end gap-3 bg-slate-50/30">
                        <button type="button" @click="autoModal = false"
                            class="px-6 py-2.5 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-100 transition-all">
                            Annuler
                        </button>
                        <button type="submit"
                            class="btn-primary px-8 py-2.5 rounded-xl shadow-lg shadow-[#7367F0]/20 text-sm">
                            Générer le Mois
                        </button>
                    </div>
                </form>
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