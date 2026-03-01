@extends('layouts.admin')

@section('title', 'Journal de Caisse')

@section('content')
    <div class="space-y-6">
        <!-- Header & KPIs -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <div class="sm:col-span-2 md:col-span-1">
                <h1 class="text-xl md:text-2xl font-bold text-[#444050]">Journal de Caisse</h1>
                <p class="text-slate-500 text-xs md:text-sm">Suivi des flux financiers.</p>
            </div>

            <div class="bg-white rounded-xl shadow-material p-3 md:p-4 border-l-4 border-green-500">
                <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">Total Recettes</p>
                <p class="text-lg md:text-xl font-black text-green-600">{{ number_format($totalRecettes, 0, ',', ' ') }} FCFA</p>
            </div>

            <div class="bg-white rounded-xl shadow-material p-3 md:p-4 border-l-4 border-red-500">
                <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">Total Dépenses</p>
                <p class="text-lg md:text-xl font-black text-red-600">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</p>
            </div>

            <div class="bg-white rounded-xl shadow-material p-3 md:p-4 border-l-4 border-[#7367F0]">
                <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">Solde Actuel</p>
                <p class="text-lg md:text-xl font-black text-[#7367F0]">{{ number_format($solde, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>

        <!-- Filters & Action -->
        <div class="bg-white rounded-xl shadow-material p-4 flex flex-col lg:flex-row justify-between gap-4">
            <form action="{{ route('admin.finance.transactions.index') }}" method="GET"
                class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <select name="type"
                    class="px-4 py-2 rounded-lg border border-slate-200 text-sm outline-none focus:border-[#7367F0] w-full sm:w-auto">
                    <option value="">Tous les types</option>
                    <option value="recette" {{ request('type') == 'recette' ? 'selected' : '' }}>Recettes</option>
                    <option value="depense" {{ request('type') == 'depense' ? 'selected' : '' }}>Dépenses</option>
                </select>

                <select name="categorie_id"
                    class="px-4 py-2 rounded-lg border border-slate-200 text-sm outline-none focus:border-[#7367F0] w-full sm:w-auto">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('categorie_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->libelle }}</option>
                    @endforeach
                </select>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 sm:flex-none p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-all flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
    
                    @if(request()->anyFilled(['type', 'categorie_id']))
                        <a href="{{ route('admin.finance.transactions.index') }}"
                            class="text-xs text-red-500 hover:underline">Réinitialiser</a>
                    @endif
                </div>
            </form>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.finance.export.csv') }}" class="flex-1 md:flex-none btn-secondary flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>CSV</span>
                </a>
                <a href="{{ route('admin.finance.report.pdf') }}" target="_blank"
                    class="flex-1 md:flex-none btn-secondary flex items-center justify-center gap-2 bg-red-50 text-red-600 border-red-100 hover:bg-red-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span>PDF</span>
                </a>
                <button onclick="window.location.href='{{ route('admin.finance.transactions.create') }}'"
                    class="w-full md:w-auto btn-primary flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouvelle Opération
                </button>
            </div>
        </div>

        <!-- Transactions Table (Desktop) -->
        <div class="hidden md:block bg-white rounded-xl shadow-material overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 text-[#444050] text-[12px] uppercase tracking-wider font-bold border-b border-gray-100">
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4">Catégorie</th>
                            <th class="px-6 py-4">Montant</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[14px]">
                        @forelse($transactions as $tx)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-500">{{ $tx->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 font-medium text-[#444050]">
                                    {{ $tx->description }}
                                    @if($tx->reference)
                                        <p class="text-[10px] text-slate-400 font-normal uppercase tracking-tighter">REF:
                                            {{ $tx->reference }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-xs font-semibold text-slate-600">{{ $tx->categorie->libelle ?? 'N/A' }}</span>
                                </td>
                                <td
                                    class="px-6 py-4 font-black {{ $tx->type == 'recette' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $tx->type == 'recette' ? '+' : '-' }} {{ number_format($tx->montant, 0, ',', ' ') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.finance.transactions.edit', $tx->id) }}" class="btn-icon">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.finance.transactions.destroy', $tx->id) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Annuler cette transaction ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-danger">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500 italic">
                                    Aucune transaction enregistrée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Transactions Cards (Mobile) -->
        <div class="md:hidden space-y-4">
            @forelse($transactions as $tx)
                <div class="bg-white rounded-xl shadow-material p-4 border-l-4 {{ $tx->type == 'recette' ? 'border-green-500' : 'border-red-500' }}">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $tx->created_at->format('d/m/Y') }}</span>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.finance.transactions.edit', $tx->id) }}" class="p-1 text-[#7367F0]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.finance.transactions.destroy', $tx->id) }}" method="POST"
                                onsubmit="return confirm('Annuler cette transaction ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-red-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="mb-2">
                        <p class="text-[#444050] font-semibold text-[15px] leading-tight">{{ $tx->description }}</p>
                        @if($tx->reference)
                            <p class="text-[9px] text-slate-400 uppercase tracking-tighter">REF: {{ $tx->reference }}</p>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase">{{ $tx->categorie->libelle ?? 'N/A' }}</span>
                        <p class="font-black text-[16px] {{ $tx->type == 'recette' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $tx->type == 'recette' ? '+' : '-' }} {{ number_format($tx->montant, 0, ',', ' ') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-material p-8 text-center text-slate-400 italic text-sm">
                    Aucune transaction enregistrée.
                </div>
            @endforelse
        </div>
            <div class="px-6 py-4 border-t border-gray-50">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection