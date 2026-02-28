@extends('layouts.admin')

@section('page_title', 'Gestion des Types d\'Événement')

@section('content')
    <div class="space-y-6" x-data="{ 
                showModal: false, 
                editMode: false, 
                typeId: null, 
                libelle: '',
                openCreate() {
                    this.editMode = false;
                    this.typeId = null;
                    this.libelle = '';
                    this.showModal = true;
                },
                openEdit(type) {
                    this.editMode = true;
                    this.typeId = type.id;
                    this.libelle = type.libelle;
                    this.showModal = true;
                }
            }">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#444050]">Types d'Événements</h1>
                <p class="text-slate-500 text-sm">Gérez les catégories d'événements de la chorale.</p>
            </div>
            <button @click="openCreate()" class="btn-primary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau Type
            </button>
        </div>

        {{-- Types Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($types as $type)
                <div
                    class="bg-white rounded-xl shadow-material p-6 border border-transparent hover:border-[#7367F0]/30 transition-all group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 bg-[#7367F0]/10 rounded-xl flex items-center justify-center text-[#7367F0]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-[#444050] mb-1">{{ $type->libelle }}</h3>
                    <p class="text-xs text-[#7367F0] font-medium uppercase tracking-wider mb-4">{{ $type->events_count }}
                        événement{{ $type->events_count > 1 ? 's' : '' }} associé{{ $type->events_count > 1 ? 's' : '' }}</p>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                        <button @click="openEdit({{ json_encode($type) }})"
                            class="text-[14px] font-medium text-slate-500 hover:text-[#7367F0] transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Renommer
                        </button>
                        <form action="{{ route('admin.events.types.destroy', $type) }}" method="POST"
                            onsubmit="return confirm('Supprimer ce type ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-[11px] text-red-400 hover:text-red-600 font-bold uppercase tracking-wider flex items-center gap-1 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

            {{-- Add New Type Card --}}
            <button @click="openCreate()"
                class="bg-gray-50 border-2 border-dashed border-slate-200 rounded-xl p-6 flex flex-col items-center justify-center gap-3 hover:bg-[#7367F0]/5 hover:border-[#7367F0]/30 transition-all group min-h-[180px]">
                <div
                    class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-slate-400 group-hover:text-[#7367F0] shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <span class="text-slate-500 font-medium group-hover:text-[#7367F0]">Ajouter un type</span>
            </button>
        </div>

        {{-- Modal --}}
        <div x-show="showModal"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-cloak>
            <div class="bg-white rounded-2xl shadow-material-lg w-full max-w-md overflow-hidden"
                @click.away="showModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-[#444050]" x-text="editMode ? 'Modifier le Type' : 'Nouveau Type'">
                    </h3>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form
                    :action="editMode ? '{{ route('admin.events.types.index') }}/' + typeId : '{{ route('admin.events.types.store') }}'"
                    method="POST" class="p-8 space-y-6">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-widest">Libellé du
                            type</label>
                        <input type="text" name="libelle" x-model="libelle"
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                            placeholder="Ex: Concert, Répétition, ..." required>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="showModal = false" class="btn-secondary">Annuler</button>
                        <button type="submit" class="btn-primary min-w-[120px]"
                            x-text="editMode ? 'Enregistrer' : 'Créer le type'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection