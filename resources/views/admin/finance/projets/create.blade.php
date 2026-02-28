@extends('layouts.admin')

@section('title', 'Nouveau Projet')

@section('content')
<div class="w-full">
    <div class="mb-8">
        <a href="{{ route('admin.finance.projets.index') }}" class="text-sm text-[#7367F0] flex items-center gap-2 mb-2 hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour aux projets
        </a>
        <h1 class="text-2xl font-bold text-[#444050]">Lancer un nouveau projet</h1>
        <p class="text-slate-500 text-sm">Définissez un objectif clair pour mobiliser les donateurs.</p>
    </div>

    <form action="{{ route('admin.finance.projets.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-xl shadow-material overflow-hidden">
            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Titre du Projet</label>
                    <input type="text" name="title" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] outline-none transition-all" placeholder="Ex: Achat d'un nouveau clavier" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Objectif Financier (FCFA)</label>
                    <input type="number" name="objectif" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] outline-none transition-all" placeholder="Ex: 500000" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] outline-none transition-all resize-none" placeholder="Expliquez l'importance de ce projet..."></textarea>
                </div>
            </div>

            <div class="p-8 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.finance.projets.index') }}" class="px-6 py-2.5 rounded-lg text-slate-600 font-medium hover:bg-slate-200 transition-all">Annuler</a>
                <button type="submit" class="btn-primary px-10">Lancer le projet</button>
            </div>
        </div>
    </form>
</div>
@endsection
