@extends('layouts.admin')

@section('title', 'Gestion des Sous-menus')

@section('content')
<div class="space-y-6" x-data="{
    smModal: false,
    editMode: false,
    currentId: null,
    formData: { name: '', url: '', menu_id: '' },
    actionUrl: '',

    openSM(id = null, name = '', url = '', menuId = '') {
        this.editMode = !!id;
        this.currentId = id;
        this.formData.name = name;
        this.formData.url = url;
        this.formData.menu_id = menuId;
        this.actionUrl = id ? `/dashboard/sousmenus/${id}` : '{{ route('admin.sousmenus.store') }}';
        this.smModal = true;
    }
}">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#444050]">Liste des Sous-menus</h1>
            <p class="text-slate-500 text-sm font-medium">Gérez les liens finaux et les routes de l'ERP par menu.</p>
        </div>
        <button @click="openSM()" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau Sous-menu
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
                        <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Libellé</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">URL</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Menu Parent</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($sousMenus as $sm)
                    <tr class="hover:bg-slate-50/50 transition duration-300">
                        <td class="px-8 py-4">
                            <span class="font-bold text-[#444050] text-sm">{{ $sm->name }}</span>
                        </td>
                        <td class="px-8 py-4">
                            <code class="text-[11px] bg-slate-100 px-2 py-1 rounded-md text-slate-600 font-medium">{{ $sm->url }}</code>
                        </td>
                        <td class="px-8 py-4">
                            <span class="px-3 py-1 bg-[#E7E7FF] text-[#7367F0] rounded-full text-[10px] font-bold uppercase tracking-wider">
                                {{ $sm->menu->name ?? 'Indépendant' }}
                            </span>
                        </td>
                        <td class="px-8 py-4">
                            <div class="flex items-center justify-end gap-1">
                                <button @click="openSM({{ $sm->id }}, '{{ $sm->name }}', '{{ $sm->url }}', '{{ $sm->menu_id }}')" class="btn-icon">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <form action="{{ route('admin.sousmenus.destroy', $sm->id) }}" method="POST" onsubmit="return confirm('Supprimer ce sous-menu ?');">
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
                        <td colspan="4" class="px-8 py-12 text-center text-slate-400 italic font-medium">Aucun sous-menu configuré.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-4 border-t border-slate-50">
            {{ $sousMenus->links() }}
        </div>
    </div>

    <!-- Modal Creation / Edition -->
    <div x-show="smModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-cloak x-transition>
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-material-lg" @click.away="smModal = false">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-lg text-[#444050]" x-text="editMode ? 'Modifier le Sous-menu' : 'Nouveau Sous-menu'"></h3>
                <button @click="smModal = false" class="text-slate-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form :action="actionUrl" method="POST" class="p-8 space-y-6">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="space-y-1.5">
                    <label class="text-[12px] font-bold uppercase tracking-widest text-slate-500 ml-1">Menu Parent</label>
                    <select name="menu_id" x-model="formData.menu_id" required 
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 outline-none focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all font-medium text-[#444050] appearance-none">
                        <option value="">Sélectionner un menu</option>
                        @foreach($menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[12px] font-bold uppercase tracking-widest text-slate-500 ml-1">Libellé</label>
                    <input type="text" name="name" x-model="formData.name" required
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 outline-none focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all font-semibold text-[#444050]"
                           placeholder="Ex: Factures Attendues">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[12px] font-bold uppercase tracking-widest text-slate-500 ml-1">URL Relative</label>
                    <input type="text" name="url" x-model="formData.url" required
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 outline-none focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all font-medium text-[#444050]"
                           placeholder="/dashboard/...">
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="smModal = false" class="btn-secondary px-6">Annuler</button>
                    <button type="submit" class="btn-primary min-w-[140px]">
                        <span x-text="editMode ? 'Mettre à jour' : 'Créer le Sous-menu'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
