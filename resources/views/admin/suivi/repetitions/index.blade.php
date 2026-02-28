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

        openModal(rep = null) {
            if (rep) {
                this.editMode = true;
                this.currentId = rep.id;
                this.formData.titre = rep.titre;
                this.formData.start_time = rep.start_time.substring(0, 16);
                this.formData.end_time = rep.end_time.substring(0, 16);
                this.formData.lieu = rep.lieu;
                this.formData.description = rep.description;
                this.actionUrl = `/dashboard/repetitions/${rep.id}`;
            } else {
                this.editMode = false;
                this.formData = { titre: '', start_time: '', end_time: '', lieu: '', description: '' };
                this.actionUrl = '{{ route('admin.repetitions.store') }}';
            }
            this.repModal = true;
        }
    }">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#444050]">Gestion des Répétitions</h1>
                <p class="text-slate-500 text-sm">Organisez les séances de travail et suivez l'assiduité du chœur.</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="autoModal = true" class="btn-primary-outline flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-12a2 2 0 002-2z" />
                    </svg>
                    Planification Automatique
                </button>
                <button @click="openModal()" class="btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Programmer une Séance
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
            <div class="overflow-x-auto">
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
                                                {{ $rep->titre }}</p>
                                            <p class="text-[11px] text-slate-400 font-medium italic truncate max-w-[200px]">
                                                {{ $rep->description ?: 'Aucun détail' }}</p>
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
                                            {{ \Carbon\Carbon::parse($rep->end_time)->format('H:i') }}</p>
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
                                <td class="px-8 py-5">
                                    <div class="flex justify-end items-center gap-2 text-right">
                                        <a href="{{ route('admin.repetitions.show', $rep->id) }}" title="Faire l'appel"
                                            class="w-8 h-8 rounded-lg bg-[#E7E7FF] text-[#7367F0] flex items-center justify-center hover:bg-[#7367F0] hover:text-white transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </a>
                                        <button @click="openModal({{ json_encode($rep) }})" title="Modifier"
                                            class="w-8 h-8 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center hover:bg-orange-500 hover:text-white transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('admin.repetitions.destroy', $rep->id) }}" method="POST"
                                            onsubmit="return confirm('Annuler cette répétition ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
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
        </div>

        <div x-show="repModal"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" x-cloak
            x-transition.opacity>
            <div class="bg-white rounded-2xl w-full max-w-md shadow-material-lg overflow-hidden flex flex-col max-h-[90vh]"
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
    </div>
@endsection