@extends('layouts.admin')

@section('title', 'Gestion des Styles / Catégories')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#444050]">Styles & Catégories</h1>
                <p class="text-slate-500 text-sm">Organisez votre répertoire en définissant des styles musicaux (Latin, Gospel, etc.).</p>
            </div>
            
            <button @click="$dispatch('open-modal', 'create-category-modal')"
                class="btn-primary flex items-center gap-2 whitespace-nowrap w-full sm:w-auto justify-center shadow-lg shadow-[#7367F0]/20 px-4 py-2.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau Style
            </button>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-xl shadow-material overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[#444050] text-[13px] uppercase tracking-wider font-bold border-b border-gray-100">
                            <th class="px-6 py-4">Nom du Style</th>
                            <th class="px-6 py-4">Chants Associés</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[14px]">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-[#7367F0]/10 rounded-lg flex items-center justify-center text-[#7367F0]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                        </div>
                                        <span class="font-bold text-[#444050]">{{ $cat->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-[#7367F0]/10 text-[#7367F0] rounded-full text-xs font-bold">
                                        {{ $cat->chants_count }} chants
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="$dispatch('open-modal', 'edit-category-modal-{{ $cat->id }}')"
                                            class="p-2 text-slate-400 hover:text-[#7367F0] hover:bg-slate-100 rounded-lg transition-all"
                                            title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="{{ route('admin.categories-chants.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Supprimer ce style ?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Supprimer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>

                                    {{-- Edit Modal --}}
                                    <div x-data="{ open: false }" 
                                         @open-modal.window="if($event.detail === 'edit-category-modal-{{ $cat->id }}') open = true"
                                         @close-modal.window="if($event.detail === 'edit-category-modal-{{ $cat->id }}') open = false"
                                         x-show="open" 
                                         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
                                         x-cloak>
                                        <div @click.away="open = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
                                            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                                                <h3 class="text-lg font-bold text-[#444050]">Modifier le style</h3>
                                                <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.categories-chants.update', $cat->id) }}" method="POST" class="p-6 space-y-4">
                                                @csrf
                                                @method('PUT')
                                                <div class="text-left">
                                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Nom du style</label>
                                                    <input type="text" name="name" value="{{ $cat->name }}" required
                                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all outline-none">
                                                </div>
                                                <div class="flex gap-3 pt-2">
                                                    <button type="button" @click="open = false" class="flex-1 px-6 py-3 rounded-xl text-slate-500 font-bold hover:bg-slate-100 transition-all">Annuler</button>
                                                    <button type="submit" class="flex-1 btn-primary py-3 shadow-lg shadow-[#7367F0]/30">Enregistrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-slate-400 italic">
                                    Aucun style défini pour le moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Create Modal --}}
        <div x-data="{ open: false }" 
             @open-modal.window="if($event.detail === 'create-category-modal') open = true"
             @close-modal.window="if($event.detail === 'create-category-modal') open = false"
             x-show="open" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
             x-cloak translate="no">
            <div @click.away="open = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-lg font-bold text-[#444050]">Nouveau Style</h3>
                    <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form action="{{ route('admin.categories-chants.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Nom du style</label>
                        <input type="text" name="name" placeholder="Ex: Latin, Gospel, Pop..." required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all outline-none">
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="open = false" class="flex-1 px-6 py-3 rounded-xl text-slate-500 font-bold hover:bg-slate-100 transition-all">Annuler</button>
                        <button type="submit" class="flex-1 btn-primary py-3 shadow-lg shadow-[#7367F0]/30">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
@endsection
