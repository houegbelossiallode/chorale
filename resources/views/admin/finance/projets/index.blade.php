@extends('layouts.admin')

@section('title', 'Projets de Financement')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#444050]">Campagnes de Dons</h1>
            <p class="text-slate-500 text-sm">Gérez les projets nécessitant un soutien financier.</p>
        </div>
        <button onclick="window.location.href='{{ route('admin.finance.projets.create') }}'" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau Projet
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projets as $projet)
        @php
            $percent = $projet->objectif > 0 ? min(100, round(($projet->atteint / $projet->objectif) * 100)) : 0;
            $color = $percent >= 100 ? 'bg-green-500' : ($percent >= 50 ? 'bg-[#7367F0]' : 'bg-amber-500');
        @endphp
        <div class="bg-white rounded-xl shadow-material p-6 border border-transparent hover:border-[#7367F0]/30 transition-all flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-[#7367F0]/10 rounded-xl flex items-center justify-center text-[#7367F0]">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="text-xs font-black {{ $percent >= 100 ? 'text-green-600 bg-green-50' : 'text-[#7367F0] bg-[#7367F0]/5' }} px-2 py-1 rounded-lg">
                        {{ $percent }}%
                    </span>
                </div>
                
                <h3 class="text-lg font-bold text-[#444050] mb-2">{{ $projet->title }}</h3>
                <p class="text-sm text-slate-500 mb-6 line-clamp-2">{{ $projet->description ?? 'Aucune description fournie.' }}</p>
                
                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-xs font-bold uppercase tracking-widest">
                        <span class="text-slate-400 font-medium lowercase italic">Atteint: {{ number_format($projet->atteint, 0, ',', ' ') }}</span>
                        <span class="text-slate-600 italic font-medium lowercase italic">Objectif: {{ number_format($projet->objectif, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $color }} transition-all duration-1000" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                <div class="text-xs text-slate-400">
                    <span class="font-bold text-[#7367F0]">{{ $projet->donations_count }}</span> dons reçus
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.finance.projets.edit', $projet->id) }}" class="p-2 text-slate-400 hover:text-[#7367F0] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-gray-50 border-2 border-dashed border-slate-200 rounded-xl text-slate-500 italic">
            Aucun projet de financement actif.
        </div>
        @endforelse
    </div>
</div>
@endsection
