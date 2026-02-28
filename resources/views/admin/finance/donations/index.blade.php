@extends('layouts.admin')

@section('title', 'Historique des Dons')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#444050]">Dons & Contributions</h1>
            <p class="text-slate-500 text-sm">Consultez l'historique complet des dons reçus.</p>
        </div>
        <button onclick="window.location.href='{{ route('admin.finance.donations.create') }}'" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Enregistrer un don
        </button>
    </div>

    <!-- Donations Table -->
    <div class="bg-white rounded-xl shadow-material overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[#444050] text-[12px] uppercase tracking-wider font-bold border-b border-gray-100">
                        <th class="px-6 py-4">Donateur</th>
                        <th class="px-6 py-4">Projet</th>
                        <th class="px-6 py-4">Montant</th>
                        <th class="px-6 py-4">Mode</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-[14px]">
                    @forelse($donations as $donation)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 font-bold text-xs">
                                    {{ strtoupper(substr($donation->donateur->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-[#444050]">{{ $donation->donateur->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-slate-600">{{ $donation->projet->title }}</span>
                        </td>
                        <td class="px-6 py-4 font-black text-[#7367F0]">
                            {{ number_format($donation->amount, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-indigo-50 text-[#7367F0] rounded text-[10px] font-bold uppercase tracking-wider">
                                {{ $donation->payment_method }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.finance.donations.destroy', $donation->id) }}" method="POST" class="inline" onsubmit="return confirm('Annuler ce don ? Le montant sera déduit du projet.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500 italic">
                            Aucun don enregistré pour le moment.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-50">
            {{ $donations->links() }}
        </div>
    </div>
</div>
@endsection
