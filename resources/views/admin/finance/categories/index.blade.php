@extends('layouts.admin')

@section('title', 'Catégories Financières')

@section('content')
    <div class="space-y-6" x-data="{ 
        catModalOpen: false, 
        editMode: false,
        catId: null,
        catLibelle: '',
        catType: 'recette',
        actionUrl: '{{ route('admin.finance.finance-categories.store') }}',

        openCreateModal() {
            this.editMode = false;
            this.catId = null;
            this.catLibelle = '';
            this.catType = 'recette';
            this.actionUrl = '{{ route('admin.finance.finance-categories.store') }}';
            this.catModalOpen = true;
        },

        openEditModal(id, libelle, type) {
            this.editMode = true;
            this.catId = id;
            this.catLibelle = libelle;
            this.catType = type;
            this.actionUrl = '{{ route('admin.finance.finance-categories.index') }}/' + id;
            this.catModalOpen = true;
        }
    }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-[#444050]">Catégories Financières</h1>
                <p class="text-slate-500 text-xs md:text-sm">Définissez les types de revenus et de dépenses.</p>
            </div>
            <button @click="openCreateModal()" class="w-full sm:w-auto btn-primary flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle Catégorie
            </button>
        </div>

        <!-- List Table (Desktop) -->
        <div class="hidden md:block bg-white rounded-xl shadow-material overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50 text-[#444050] text-[12px] uppercase tracking-wider font-bold border-b border-gray-100">
                        <th class="px-6 py-4">Catégorie</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-[14px]">
                    @foreach($categories as $category)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-[#444050]">{{ $category->libelle }}</td>
                            <td class="px-6 py-4">
                                @if($category->type == 'recette')
                                    <span
                                        class="px-2 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-bold uppercase tracking-wider">Recette</span>
                                @else
                                    <span
                                        class="px-2 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-bold uppercase tracking-wider">Dépense</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button
                                        @click="openEditModal({{ $category->id }}, '{{ addslashes($category->libelle) }}', '{{ $category->type }}')"
                                        class="btn-icon">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.finance.finance-categories.destroy', $category->id) }}"
                                        method="POST" class="inline" onsubmit="return confirm('Supprimer cette catégorie ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- List Cards (Mobile) -->
        <div class="md:hidden space-y-4">
            @foreach($categories as $category)
                <div class="bg-white rounded-xl shadow-material p-4 flex justify-between items-center">
                    <div>
                        <p class="text-[#444050] font-bold text-[15px]">{{ $category->libelle }}</p>
                        @if($category->type == 'recette')
                            <span class="text-[9px] text-green-600 font-bold uppercase tracking-wider">Recette</span>
                        @else
                            <span class="text-[9px] text-red-600 font-bold uppercase tracking-wider">Dépense</span>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <button
                            @click="openEditModal({{ $category->id }}, '{{ addslashes($category->libelle) }}', '{{ $category->type }}')"
                            class="p-2 text-[#7367F0]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <form action="{{ route('admin.finance.finance-categories.destroy', $category->id) }}" method="POST"
                            onsubmit="return confirm('Supprimer cette catégorie ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal Category -->
        <div x-show="catModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-cloak>
            <div class="bg-white rounded-2xl shadow-material-lg w-full max-w-md overflow-hidden"
                @click.away="catModalOpen = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-[#444050]"
                        x-text="editMode ? 'Modifier la catégorie' : 'Nouvelle catégorie'"></h3>
                    <button @click="catModalOpen = false" class="text-slate-400 hover:text-slate-600">
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
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest">Libellé</label>
                        <input type="text" name="libelle" x-model="catLibelle"
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                            placeholder="Ex: Cotisation" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest">Type</label>
                        <select name="type" x-model="catType"
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                            required>
                            <option value="recette">Recette (Entrée)</option>
                            <option value="depense">Dépense (Sortie)</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="catModalOpen = false" class="btn-secondary">Annuler</button>
                        <button type="submit" class="btn-primary min-w-[120px]"
                            x-text="editMode ? 'Enregistrer' : 'Créer'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection