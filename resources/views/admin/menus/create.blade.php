@extends('layouts.admin')

@section('title', 'Nouveau Menu')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.menus.index') }}" class="w-9 h-9 bg-white rounded-lg flex items-center justify-center text-slate-400 hover:text-[#7367F0] shadow-sm border border-slate-100 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-[#444050]">Créer un Menu</h1>
            <p class="text-slate-500 text-sm">Ajoutez une nouvelle entrée de navigation par module.</p>
        </div>
    </div>

    <form action="{{ route('admin.menus.store') }}" method="POST" class="bg-white rounded-2xl shadow-material border border-slate-100 overflow-hidden">
        @csrf
        <div class="p-8 space-y-6">
            <div class="space-y-1.5">
                <label class="text-[12px] font-bold uppercase tracking-widest text-slate-500 ml-1">Module Parent</label>
                <select name="module_id" required 
                        class="w-full px-4 py-3 rounded-lg border border-slate-200 outline-none focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all font-medium text-[#444050] appearance-none">
                    <option value="">Sélectionner un module</option>
                    @foreach($modules as $module)
                    <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>{{ $module->name }}</option>
                    @endforeach
                </select>
                @error('module_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-[12px] font-bold uppercase tracking-widest text-slate-500 ml-1">Libellé du Menu</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 rounded-lg border border-slate-200 outline-none focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all font-semibold text-[#444050]"
                       placeholder="Ex: Facturation, Membres...">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="p-8 bg-slate-50/50 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.menus.index') }}" class="btn-secondary px-8">Annuler</a>
            <button type="submit" class="btn-primary min-w-[160px]">Créer le Menu</button>
        </div>
    </form>
</div>
@endsection
