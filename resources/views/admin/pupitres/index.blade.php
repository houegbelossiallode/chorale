@extends('layouts.admin')

@section('page_title', 'Gestion des Pupitres')

@section('content')
<div class="space-y-6" x-data="{ 
    showCreateModal: false, 
    showEditModal: false,
    currentPupitre: { id: '', name: '', description: '', responsable_id: '' },
    openEditModal(pupitre) {
        this.currentPupitre = { ...pupitre };
        this.showEditModal = true;
    }
}">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h3 class="text-xl sm:text-2xl font-semibold text-[#444050]">Répertoire des Pupitres</h3>
        <button @click="showCreateModal = true" class="btn-primary gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau Pupitre
        </button>
    </div>

    @if(session('success'))
    <div class="p-4 bg-[#DFF7E9] border border-[#28C76F]/20 text-[#28C76F] rounded-xl font-semibold text-sm animate-fade-in">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        @forelse($pupitres as $pupitre)
        <div class="bg-white rounded-2xl shadow-material border border-slate-100 p-6 hover:shadow-material-lg transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-[#E7E7FF] flex items-center justify-center text-[#7367F0]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                </div>
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button @click="openEditModal({ 
                        id: '{{ $pupitre->id }}', 
                        name: '{{ addslashes($pupitre->name) }}', 
                        description: '{{ addslashes($pupitre->description) }}', 
                        responsable_id: '{{ $pupitre->responsable_id }}' 
                    })" class="p-1.5 text-slate-400 hover:text-[#7367F0] transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                    <form action="{{ route('admin.pupitres.destroy', $pupitre) }}" method="POST" onsubmit="return confirm('Supprimer ce pupitre ?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            <h4 class="text-lg font-bold text-[#444050] mb-1 capitalize">{{ $pupitre->name }}</h4>
            <p class="text-xs text-slate-400 font-medium mb-4 line-clamp-2 min-h-[2rem]">{{ $pupitre->description ?? 'Aucune description disponible.' }}</p>
            
            <div class="flex flex-col gap-3 pt-4 border-t border-slate-50">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Effectif</span>
                    <span class="px-2 py-0.5 bg-slate-50 text-slate-600 rounded-md text-xs font-bold">{{ $pupitre->users_count }} membres</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Responsable</span>
                    @if($pupitre->responsable)
                        <div class="flex items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($pupitre->responsable->first_name) }}&background=f8f7fa&color=7367f0&size=32" class="w-5 h-5 rounded-full" alt="">
                            <span class="text-xs font-bold text-[#7367F0]">{{ $pupitre->responsable->first_name }}</span>
                        </div>
                    @else
                        <span class="text-xs font-medium text-slate-300 italic">Non défini</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-dashed border-slate-300">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
            </div>
            <h5 class="text-slate-600 font-bold">Aucun pupitre trouvé</h5>
            <p class="text-slate-400 text-sm mb-6">Commencez par ajouter votre premier pupitre.</p>
            <button @click="showCreateModal = true" class="btn-primary mx-auto w-fit">Ajouter un pupitre</button>
        </div>
        @endforelse
    </div>

    <!-- Modale de Création -->
    <div x-show="showCreateModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-cloak>
        <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-material-lg animate-fade-in" @click.away="showCreateModal = false">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-[#444050]">Nouveau Pupitre</h3>
                <button @click="showCreateModal = false" class="text-slate-400 hover:text-[#7367F0]"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form action="{{ route('admin.pupitres.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[12px] font-black text-slate-400 uppercase tracking-widest ml-1">Nom du Pupitre</label>
                    <input type="text" name="name" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#7367F0] outline-none transition-all font-bold text-[#444050]">
                </div>
                <div class="space-y-2">
                    <label class="text-[12px] font-black text-slate-400 uppercase tracking-widest ml-1">Chef de Pupitre</label>
                    <select name="responsable_id" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#7367F0] outline-none transition-all font-bold text-[#444050]">
                        <option value="">Sélectionner un responsable</option>
                        @foreach(App\Models\User::all() as $user)
                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[12px] font-black text-slate-400 uppercase tracking-widest ml-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#7367F0] outline-none transition-all font-medium text-[#444050] resize-none"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-50">
                    <button type="button" @click="showCreateModal = false" class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn-primary min-w-[140px]">Créer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modale d'Édition -->
    <div x-show="showEditModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-cloak>
        <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-material-lg animate-fade-in" @click.away="showEditModal = false">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-[#444050]">Modifier le Pupitre</h3>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-[#7367F0]"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form :action="`/dashboard/pupitres/${currentPupitre.id}`" method="POST" class="p-8 space-y-6">
                @csrf @method('PUT')
                <div class="space-y-2">
                    <label class="text-[12px] font-black text-slate-400 uppercase tracking-widest ml-1">Nom du Pupitre</label>
                    <input type="text" name="name" x-model="currentPupitre.name" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#7367F0] outline-none transition-all font-bold text-[#444050]">
                </div>
                <div class="space-y-2">
                    <label class="text-[12px] font-black text-slate-400 uppercase tracking-widest ml-1">Chef de Pupitre</label>
                    <select name="responsable_id" x-model="currentPupitre.responsable_id" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#7367F0] outline-none transition-all font-bold text-[#444050]">
                        <option value="">Sélectionner un responsable</option>
                        @foreach(App\Models\User::all() as $user)
                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[12px] font-black text-slate-400 uppercase tracking-widest ml-1">Description</label>
                    <textarea name="description" rows="3" x-model="currentPupitre.description" class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#7367F0] outline-none transition-all font-medium text-[#444050] resize-none"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-50">
                    <button type="button" @click="showEditModal = false" class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn-primary min-w-[140px]">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
