@extends('layouts.admin')

@section('title', 'Modifier Transaction')

@section('content')
    <div class="w-full">
        <div class="mb-8">
            <a href="{{ route('admin.finance.transactions.index') }}"
                class="text-sm text-[#7367F0] flex items-center gap-2 mb-2 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour au journal
            </a>
            <h1 class="text-2xl font-bold text-[#444050]">Modifier l'opération</h1>
            <p class="text-slate-500 text-sm">Ajustez les détails de la transaction.</p>
        </div>

        <form action="{{ route('admin.finance.transactions.update', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-white rounded-xl shadow-material overflow-hidden">
                <div class="p-4 md:p-8 space-y-6">
                    <div>
                        <label
                            class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Description</label>
                        <input type="text" name="description" value="{{ old('description', $transaction->description) }}"
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                            placeholder="Ex: Achat de partitions" required>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Type</label>
                            <select name="type"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                                required>
                                <option value="recette" {{ $transaction->type == 'recette' ? 'selected' : '' }}>Recette (+)
                                </option>
                                <option value="depense" {{ $transaction->type == 'depense' ? 'selected' : '' }}>Dépense (-)
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Catégorie</label>
                            <select name="categorie_id"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                                required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $transaction->categorie_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->libelle }} ({{ ucfirst($cat->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Montant
                                (FCFA)</label>
                            <input type="number" name="montant" value="{{ old('montant', $transaction->montant) }}"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                                placeholder="0" required>
                            @error('montant') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Référence /
                                N° Pièce</label>
                            <input type="text" name="reference" value="{{ old('reference', $transaction->reference) }}"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                                placeholder="Optionnel">
                        </div>
                    </div>
                </div>

                <div class="p-4 md:p-8 bg-gray-50 border-t border-gray-100 flex flex-col md:flex-row justify-end gap-3">
                    <a href="{{ route('admin.finance.transactions.index') }}"
                        class="order-2 md:order-1 px-6 py-2.5 rounded-lg text-slate-600 font-medium hover:bg-slate-200 text-center transition-all">Annuler</a>
                    <button type="submit" class="order-1 md:order-2 btn-primary px-10">Mettre à jour l'opération</button>
                </div>
            </div>
        </form>
    </div>
@endsection