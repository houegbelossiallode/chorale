@extends('layouts.admin')

@section('title', 'Modifier les Permissions')

@section('content')
<div class="w-full" x-data="{
    permissions: {{ json_encode($role->permissions()->where('is_granted', true)->pluck('sous_menu_id')->toArray()) }},
    toggleModule(moduleId, event) {
        const checkboxes = document.querySelectorAll(`.module-${moduleId}-checkbox`);
        const checked = event.target.checked;
        checkboxes.forEach(cb => {
            const val = parseInt(cb.value);
            if (checked) {
                if (!this.permissions.includes(val)) this.permissions.push(val);
            } else {
                this.permissions = this.permissions.filter(p => p !== val);
            }
        });
    }
}">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.roles.index') }}" class="text-sm text-[#7367F0] flex items-center gap-2 mb-2 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour à la liste
            </a>
            <h1 class="text-2xl font-bold text-[#444050]">Matrice des Permissions : {{ $role->libelle }}</h1>
            <p class="text-slate-500 text-sm font-medium">Configurez les accès granulaires pour ce rôle au sein de l'ERP.</p>
        </div>
        <div class="flex items-center gap-3">
             <a href="{{ route('admin.roles.index') }}" class="btn-secondary">Annuler</a>
             <button @click="$refs.permForm.submit()" class="btn-primary min-w-[150px]">Enregistrer</button>
        </div>
    </div>

    <form x-ref="permForm" action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-material overflow-hidden mb-8 border border-slate-100">
            <div class="p-6 md:p-8 border-b border-gray-100 bg-slate-50/30">
                <label class="block text-[11px] font-bold text-slate-400 mb-2 uppercase tracking-widest">Identification du rôle</label>
                <div class="max-w-xl">
                    <input type="text" name="libelle" value="{{ old('libelle', $role->libelle) }}" 
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 outline-none transition-all font-semibold text-[#444050]"
                           placeholder="Nom du rôle...">
                    @error('libelle') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @foreach($modules as $module)
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-[#7367F0]/10 rounded-lg flex items-center justify-center text-[#7367F0]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <h4 class="text-sm font-bold text-[#444050] uppercase tracking-wider">{{ $module->name }}</h4>
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Tout cocher</span>
                                <input type="checkbox" @change="toggleModule({{ $module->id }}, $event)" class="w-4 h-4 rounded border-slate-300 text-[#7367F0] focus:ring-[#7367F0]/20">
                            </label>
                        </div>
                        
                        <div class="p-6 space-y-8 flex-1">
                            @foreach($module->menus as $menu)
                            <div>
                                <h5 class="text-[12px] font-bold text-slate-400 uppercase tracking-[0.1em] mb-4 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#7367F0]"></span>
                                    {{ $menu->name }}
                                </h5>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($menu->sousMenus as $sm)
                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-transparent hover:border-[#7367F0]/20 hover:bg-[#7367F0]/5 transition-all cursor-pointer group">
                                        <input type="checkbox" name="permissions[]" value="{{ $sm->id }}" 
                                               x-model="permissions"
                                               class="module-{{ $module->id }}-checkbox w-5 h-5 rounded border-slate-300 text-[#7367F0] focus:ring-[#7367F0]/20 transition-all cursor-pointer">
                                        <div class="flex flex-col">
                                            <span class="text-[13px] font-bold text-[#444050] group-hover:text-[#7367F0] transition-colors">
                                                {{ $sm->name }}
                                            </span>
                                            <span class="text-[10px] text-slate-400 font-medium italic">{{ $sm->url }}</span>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="p-8 bg-slate-50/50 border-t border-gray-100 flex justify-end items-center gap-4">
                <p class="text-[12px] text-slate-400 italic mr-auto">Les modifications s'appliquent immédiatement après l'enregistrement.</p>
                <a href="{{ route('admin.roles.index') }}" class="btn-secondary">Annuler</a>
                <button type="submit" class="btn-primary px-12 h-[48px]">Enregistrer les permissions</button>
            </div>
        </div>
    </form>
</div>
@endsection
