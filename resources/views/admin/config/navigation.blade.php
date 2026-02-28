@extends('layouts.admin')

@section('title', 'Configuration de la Navigation')

@section('content')
<div class="space-y-6" x-data="{
    // Modal States
    moduleModal: false,
    menuModal: false,
    smModal: false,
    
    // Data States
    editMode: false,
    currentId: null,
    parentId: null,
    formData: { name: '', url: '' },
    actionUrl: '',

    // Helper Methods
    openModule(id = null, name = '') {
        this.editMode = !!id;
        this.currentId = id;
        this.formData.name = name;
        this.actionUrl = id ? `/dashboard/modules/${id}` : '{{ route('admin.config.modules.store') }}';
        this.moduleModal = true;
    },

    openMenu(moduleId, id = null, name = '') {
        this.parentId = moduleId;
        this.editMode = !!id;
        this.currentId = id;
        this.formData.name = name;
        this.actionUrl = id ? `/dashboard/menus/${id}` : '{{ route('admin.config.menus.store') }}';
        this.menuModal = true;
    },

    openSM(menuId, id = null, name = '', url = '') {
        this.parentId = menuId;
        this.editMode = !!id;
        this.currentId = id;
        this.formData.name = name;
        this.formData.url = url;
        this.actionUrl = id ? `/dashboard/sous-menus/${id}` : '{{ route('admin.config.sousmenus.store') }}';
        this.smModal = true;
    }
}">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#444050]">Structure de Navigation</h1>
            <p class="text-slate-500 text-sm">Gérez l'arborescence des modules et menus de l'ERP.</p>
        </div>
        <button @click="openModule()" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau Module
        </button>
    </div>

    <!-- Hierarchy View -->
    <div class="space-y-6">
        @foreach($modules as $module)
        <div class="bg-white rounded-xl shadow-material overflow-hidden border-l-4 border-[#7367F0]">
            <!-- Module Header -->
            <div class="p-4 bg-slate-50 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="p-2 bg-[#7367F0]/10 text-[#7367F0] rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </span>
                    <h2 class="text-lg font-bold text-[#444050]">{{ $module->name }}</h2>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="openMenu({{ $module->id }})" class="text-xs font-bold text-[#7367F0] hover:underline">+ Ajouter Menu</button>
                    <div class="w-px h-4 bg-gray-200 mx-2"></div>
                    <button @click="openModule({{ $module->id }}, '{{ $module->name }}')" class="btn-icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <form action="{{ route('admin.config.modules.destroy', $module->id) }}" method="POST" onsubmit="return confirm('Attention: Supprimer un module supprimera tous ses menus associés.');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-icon btn-icon-danger"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                    </form>
                </div>
            </div>

            <!-- Menus List -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($module->menus as $menu)
                <div class="border border-slate-100 rounded-xl p-4 bg-white hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-50">
                        <h3 class="font-bold text-[#444050]">{{ $menu->name }}</h3>
                        <div class="flex gap-1">
                            <button @click="openMenu({{ $module->id }}, {{ $menu->id }}, '{{ $menu->name }}')" class="text-slate-300 hover:text-[#7367F0]"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                            <form action="{{ route('admin.config.menus.destroy', $menu->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-300 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        @foreach($menu->sousMenus as $sm)
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#7367F0]/40"></div>
                                <span class="text-sm text-slate-600">{{ $sm->name }}</span>
                            </div>
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="openSM({{ $menu->id }}, {{ $sm->id }}, '{{ $sm->name }}', '{{ $sm->url }}')" class="text-slate-300 hover:text-[#7367F0]"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                            </div>
                        </div>
                        @endforeach
                        <button @click="openSM({{ $menu->id }})" class="text-[11px] font-bold text-slate-400 hover:text-[#7367F0] uppercase tracking-wider mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Sous-menu
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <!-- Modals (Module / Menu / SM) -->
    <!-- Module Modal -->
    <div x-show="moduleModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-material-lg" @click.away="moduleModal = false">
            <div class="p-6 border-b border-gray-100 flex justify-between">
                <h3 class="font-bold text-lg" x-text="editMode ? 'Modifier Module' : 'Nouveau Module'"></h3>
                <button @click="moduleModal = false" class="text-slate-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form :action="actionUrl" method="POST" class="p-8 space-y-4">
                @csrf <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
                <div>
                    <label class="text-xs font-bold uppercase tracking-widest text-slate-500 block mb-2">Nom du Module</label>
                    <input type="text" name="name" x-model="formData.name" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 outline-none focus:border-[#7367F0]" required>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="moduleModal = false" class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn-primary min-w-[120px]">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Menu Modal -->
    <div x-show="menuModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-material-lg" @click.away="menuModal = false">
            <div class="p-6 border-b border-gray-100 flex justify-between">
                <h3 class="font-bold text-lg" x-text="editMode ? 'Modifier Menu' : 'Nouveau Menu'"></h3>
                <button @click="menuModal = false" class="text-slate-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form :action="actionUrl" method="POST" class="p-8 space-y-4">
                @csrf <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
                <input type="hidden" name="module_id" x-model="parentId">
                <div>
                    <label class="text-xs font-bold uppercase tracking-widest text-slate-500 block mb-2">Libellé du Menu</label>
                    <input type="text" name="name" x-model="formData.name" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 outline-none focus:border-[#7367F0]" required>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="menuModal = false" class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn-primary min-w-[120px]">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sous-menu Modal -->
    <div x-show="smModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-cloak>
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-material-lg" @click.away="smModal = false">
            <div class="p-6 border-b border-gray-100 flex justify-between">
                <h3 class="font-bold text-lg" x-text="editMode ? 'Modifier Sous-menu' : 'Nouveau Sous-menu'"></h3>
                <button @click="smModal = false" class="text-slate-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form :action="actionUrl" method="POST" class="p-8 space-y-4">
                @csrf <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
                <input type="hidden" name="menu_id" x-model="parentId">
                <div>
                    <label class="text-xs font-bold uppercase tracking-widest text-slate-500 block mb-2">Libellé</label>
                    <input type="text" name="name" x-model="formData.name" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 outline-none focus:border-[#7367F0]" required>
                </div>
                <div>
                    <label class="text-xs font-bold uppercase tracking-widest text-slate-500 block mb-2">URL relative</label>
                    <input type="text" name="url" x-model="formData.url" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 outline-none focus:border-[#7367F0]" placeholder="/dashboard/..." required>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="smModal = false" class="btn-secondary">Annuler</button>
                    <button type="submit" class="btn-primary min-w-[120px]">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
