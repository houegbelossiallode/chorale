@extends('layouts.admin')

@section('title', 'Résultats Sondage - ' . $event->title)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.events.index') }}" class="text-xs font-bold text-[#7367F0] uppercase tracking-widest hover:underline flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Retour à l'agenda
                </a>
                <h1 class="text-2xl font-black text-[#444050] uppercase tracking-tight">{{ $event->title }}</h1>
                <p class="text-sm text-slate-400 font-medium">Sondage de présence - {{ $event->start_at->translatedFormat('d F Y') }}</p>
            </div>

            <div class="flex gap-4">
                <div class="bg-white px-6 py-4 rounded-2xl shadow-sm border border-slate-100 text-center">
                    <span class="block text-[10px] font-black text-[#28C76F] uppercase tracking-widest mb-1">Oui</span>
                    <span class="text-2xl font-black text-[#444050]">{{ $sondages->where('choix', 'oui')->count() }}</span>
                </div>
                <div class="bg-white px-6 py-4 rounded-2xl shadow-sm border border-slate-100 text-center">
                    <span class="block text-[10px] font-black text-[#EA5455] uppercase tracking-widest mb-1">Non</span>
                    <span class="text-2xl font-black text-[#444050]">{{ $sondages->where('choix', 'non')->count() }}</span>
                </div>
                <div class="bg-white px-6 py-4 rounded-2xl shadow-sm border border-slate-100 text-center">
                    <span class="block text-[10px] font-black text-[#FF9F43] uppercase tracking-widest mb-1">Peut-être</span>
                    <span class="text-2xl font-black text-[#444050]">{{ $sondages->where('choix', 'peut-etre')->count() }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-material border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold uppercase tracking-widest text-slate-400">
                        <th class="px-8 py-5">Choriste</th>
                        <th class="px-8 py-5">Pupitre</th>
                        <th class="px-8 py-5 text-center">Réponse</th>
                        <th class="px-8 py-5 text-right">Date de réponse</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($sondages as $sondage)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-[#7367F0]">
                                        {{ substr($sondage->user->first_name, 0, 1) }}{{ substr($sondage->user->last_name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-[#444050]">{{ $sondage->user->first_name }} {{ $sondage->user->last_name }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $sondage->user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-xs font-bold text-[#7367F0] bg-[#7367F0]/10 px-3 py-1 rounded-full uppercase">
                                    {{ $sondage->user->pupitre->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($sondage->choix === 'oui')
                                    <span class="px-3 py-1 bg-[#28C76F]/10 text-[#28C76F] rounded-full text-[10px] font-black uppercase border border-[#28C76F]/20">Oui</span>
                                @elseif($sondage->choix === 'non')
                                    <span class="px-3 py-1 bg-[#EA5455]/10 text-[#EA5455] rounded-full text-[10px] font-black uppercase border border-[#EA5455]/20">Non</span>
                                @else
                                    <span class="px-3 py-1 bg-[#FF9F43]/10 text-[#FF9F43] rounded-full text-[10px] font-black uppercase border border-[#FF9F43]/20">?</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                <span class="text-xs text-slate-400 font-medium"> {{ $sondage->updated_at->format('d/m/Y H:i') }}</span>
                            </td>
                        </tr>
                    @endforeach
                    @if ($sondages->isEmpty())
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-slate-400 italic">
                                Aucun choriste n'a encore répondu à ce sondage.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
