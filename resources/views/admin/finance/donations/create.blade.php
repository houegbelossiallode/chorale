@extends('layouts.admin')

@section('title', 'Enregistrer un Don')

@section('content')
<div class="w-full">
    <div class="mb-8">
        <a href="{{ route('admin.finance.donations.index') }}" class="text-sm text-[#7367F0] flex items-center gap-2 mb-2 hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Retour à l'historique
        </a>
        <h1 class="text-2xl font-bold text-[#444050]">Enregistrer une contribution</h1>
        <p class="text-slate-500 text-sm">Attribuez un don à un donateur et un projet spécifique.</p>
    </div>

    <form action="{{ route('admin.finance.donations.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-xl shadow-material overflow-hidden">
            <div class="p-8 space-y-6">
                <!-- Donor Selection -->
                <div x-data="{ newDonor: false }">
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-bold text-slate-700 uppercase tracking-widest">Donateur</label>
                        <button type="button" @click="newDonor = !newDonor" class="text-xs text-[#7367F0] font-bold hover:underline">
                            <span x-show="!newDonor">+ Nouveau Donateur</span>
                            <span x-show="newDonor">Choisir existant</span>
                        </button>
                    </div>

                    <div x-show="!newDonor">
                        <select name="donateur_id" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all">
                            <option value="">Sélectionner un donateur</option>
                            @foreach($donateurs as $d)
                                <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="newDonor" class="space-y-4">
                        <input type="text" name="donateur_name" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] outline-none" placeholder="Nom complet du donateur">
                        <input type="email" name="donateur_email" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] outline-none" placeholder="Adresse Email">
                    </div>
                </div>

                <!-- Project & Amount -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Projet</label>
                        <select name="projet_id" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all" required>
                            @foreach($projets as $p)
                                <option value="{{ $p->id }}">{{ $p->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Montant (FCFA)</label>
                        <input type="number" name="amount" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all" placeholder="0" required>
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Mode de Paiement</label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['Espèces', 'Mobile Money', 'Virement'] as $method)
                        <label class="flex items-center justify-center p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-all">
                            <input type="radio" name="payment_method" value="{{ $method }}" class="hidden peer" {{ $loop->first ? 'checked' : '' }}>
                            <span class="text-xs font-bold text-slate-500 peer-checked:text-[#7367F0]">{{ $method }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="p-8 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.finance.donations.index') }}" class="px-6 py-2.5 rounded-lg text-slate-600 font-medium hover:bg-slate-200 transition-all">Annuler</a>
                <button type="submit" class="btn-primary px-10">Valider le don</button>
            </div>
        </div>
    </form>
</div>
@endsection
