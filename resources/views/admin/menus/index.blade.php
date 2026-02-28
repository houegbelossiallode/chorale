@extends('layouts.admin')

@section('title', 'Gestion des Menus')

@section('content')
<div class="space-y-6" x-data="{
    menuModal: false,
    editMode: false,
    currentId: null,
    formData: { name: '', module_id: '' },
    actionUrl: '',

    openMenu(id = null, name = '', moduleId = '') {
        this.editMode = !!id;
        this.currentId = id;
        this.formData.name = name;
        this.formData.module_id = moduleId;
        this.actionUrl = id ? `/dashboard/menus/${id}` : '{{ route('admin.config.menus.store') }}';
        this.menuModal = true;
    }
}">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#444050]">Liste des Menus</h1>
            <p class="text-slate-500 text-sm font-medium">Configurez les entrées de navigation de second niveau par module.</p>
        </div>
        <button @click="openMenu()" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau Menu
        </button>
    </div>

    @if(session('success'))
    <div class="p-4 bg-[#DFF7E9] border border-[#28C76F]/20 text-[#28C76F] rounded-xl font-semibold text-sm animate-fade-in">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-100 shadow-material overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Nom du Menu</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Module Parent</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($menus as $menu)
                    <tr class="hover:bg-slate-50/50 transition duration-300">
                        <td class="px-8 py-4">
                            <span class="font-bold text-[#444050] text-sm">{{ $menu->name }}</span>
                        </td>
                        <td class="px-8 py-4">
                            <span class="px-3 py-1 bg-[#7367F0]/10 text-[#7367F0] rounded-full text-[10px] font-bold uppercase tracking-wider">
                                {{ $menu->module->name ?? 'Indépendant' }}
                            </span>
                        </td>
                        <td class="px-8 py-4">
                            <div class="flex items-center justify-end gap-1">
                                <button @click="openMenu({{ $menu->id }}, '{{ $menu->name }}', '{{ $menu->module_id }}')" class="btn-icon">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <form action="{{ route('admin.config.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Attention: Supprimer ce menu supprimera tous ses sous-menus.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-danger">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-12 text-center text-slate-400 italic font-medium">Aucun menu configuré.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-4 border-t border-slate-50">
            {{ $menus->links() }}
        </div>
    </div>

    <!-- Modal Creation / Edition -->
    <div x-show="menuModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-cloak x-transition>
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-material-lg" @click.away="menuModal = false">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-lg text-[#444050]" x-text="editMode ? 'Modifier le Menu' : 'Nouveau Menu'"></h3>
                <button @click="menuModal = false" class="text-slate-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form :action="actionUrl" method="POST" class="p-8 space-y-6">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="space-y-1.5">
                    <label class="text-[12px] font-bold uppercase tracking-widest text-slate-500 ml-1">Module Parent</label>
                    <select name="module_id" x-model="formData.module_id" required 
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 outline-none focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all font-medium text-[#444050] appearance-none">
                        <option value="">Sélectionner un module</option>
                        @foreach($modules as $module)
                        <option value="{{ $module->id }}">{{ $module->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[12px] font-bold uppercase tracking-widest text-slate-500 ml-1">Libellé du Menu</label>
                    <input type="text" name="name" x-model="formData.name" required
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 outline-none focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all font-semibold text-[#444050]"
                           placeholder="Ex: Facturation, Membres...">
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="menuModal = false" class="btn-secondary px-6">Annuler</button>
                    <button type="submit" class="btn-primary min-w-[140px]">
                        <span x-text="editMode ? 'Mettre à jour' : 'Créer le Menu'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
