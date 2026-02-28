@extends('layouts.admin')

@section('title', 'Nouveau Rôle')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('admin.roles.index') }}" class="text-sm text-[#7367F0] flex items-center gap-2 mb-2 hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour à la liste
        </a>
        <h1 class="text-2xl font-bold text-[#444050]">Créer un nouveau rôle</h1>
        <p class="text-slate-500 text-sm">Initialisez un rôle pour ensuite lui assigner des permissions.</p>
    </div>

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-xl shadow-material overflow-hidden p-8">
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Nom du rôle</label>
                <input type="text" name="libelle" value="{{ old('libelle') }}" 
                       class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                       placeholder="Ex: Archiviste">
                @error('libelle') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.roles.index') }}" class="px-6 py-2.5 rounded-lg text-slate-600 font-medium hover:bg-slate-200 transition-all">Annuler</a>
                <button type="submit" class="btn-primary px-10">Créer le rôle</button>
            </div>
        </div>
    </form>
</div>
@endsection
