@extends('layouts.admin')

@section('title', 'Configuration des Parties d\'Événement')

@section('content')
    <div class="space-y-6" x-data="{
        partModal: false,
        editMode: false,
        partData: { titre: '', ordre: 1 },
        actionUrl: '',

        openModal(id = null, titre = '', ordre = 1) {
            this.editMode = !!id;
            this.partData.titre = titre;
            this.partData.ordre = ordre;
            this.actionUrl = id ? `/admin/partie-events/${id}` : '{{ route('admin.partie-events.store') }}';
            this.partModal = true;
        }
    }">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#444050]">Configuration des Parties</h1>
                <p class="text-slate-500 text-sm font-medium">Définissez les parties standards pour vos programmes
                    d'événements.</p>
            </div>
            <button @click="openModal()" class="btn-primary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle Partie
            </button>
        </div>

        @if(session('success'))
            <div class="p-4 bg-[#DFF7E9] border border-[#28C76F]/20 text-[#28C76F] rounded-xl font-semibold text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-material overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Ordre</th>
                            <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Titre</th>
                            <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($parties as $partie)
                            <tr class="hover:bg-slate-50/50 transition duration-300">
                                <td class="px-8 py-4">
                                    <span
                                        class="font-bold text-[#7367F0] bg-[#7367F0]/10 w-8 h-8 rounded-lg flex items-center justify-center">{{ $partie->ordre }}</span>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="font-bold text-[#444050] text-sm">{{ $partie->titre }}</span>
                                </td>
                                <td class="px-8 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            @click="openModal({{ $partie->id }}, '{{ $partie->titre }}', {{ $partie->ordre }})"
                                            class="btn-icon">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('admin.partie-events.destroy', $partie->id) }}" method="POST"
                                            onsubmit="return confirm('Supprimer cette partie ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-danger">
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
                                <td colspan="3" class="px-8 py-12 text-center text-slate-400 italic">Aucune partie configurée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="partModal"
            class="fixed inset-0 z-[110] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-cloak
            x-transition>
            <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-material-lg"
                @click.away="partModal = false">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-lg text-[#444050]"
                        x-text="editMode ? 'Modifier la Partie' : 'Nouvelle Partie'"></h3>
                    <button @click="partModal = false" class="text-slate-400 hover:text-red-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form :action="actionUrl" method="POST" class="p-8 space-y-6">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="space-y-1.5">
                        <label class="text-[12px] font-bold uppercase tracking-widest text-slate-500 ml-1">Titre de la
                            Partie</label>
                        <input type="text" name="titre" x-model="partData.titre" required
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 outline-none focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all font-semibold text-[#444050]"
                            placeholder="Ex: Intro, Louange, Communion...">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[12px] font-bold uppercase tracking-widest text-slate-500 ml-1">Ordre</label>
                        <input type="number" name="ordre" x-model="partData.ordre" required
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 outline-none focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all font-semibold text-[#444050]">
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="partModal = false" class="btn-secondary px-6">Annuler</button>
                        <button type="submit" class="btn-primary min-w-[140px]">
                            <span x-text="editMode ? 'Mettre à jour' : 'Ajouter'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection