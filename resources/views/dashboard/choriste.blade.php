@extends('layouts.admin')

@section('page_title', 'Tableau de bord Choriste')

@section('content')
<div class="space-y-6">
    <!-- Header Greeting -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="text-center md:text-left">
            <h1 class="text-2xl font-semibold text-[#444050]">Bienvenue, {{ Auth::user()->first_name }}</h1>
            <p class="text-[14px] text-slate-400 font-medium">Membre du pupitre : <span class="text-[#7367F0]">{{ $choristeStats['pupitre']?->nom ?? 'Non défini' }}</span></p>
        </div>
        <div class="flex items-center justify-center md:justify-end gap-3">
            <div class="px-4 py-2 bg-white border border-slate-100 rounded-lg shadow-sm flex items-center gap-3">
                <img src="{{ Auth::user()->photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->first_name).'&background=f8f7fa&color=7367f0&size=100' }}" class="w-8 h-8 rounded-full object-cover">
                <span class="text-sm font-bold text-[#444050]">Choriste</span>
            </div>
        </div>
    </div>

    <!-- Stats Grid (Admin Style) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Presence Stat -->
        <div class="card-material p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-[#E7E7FF] text-[#7367F0] rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[13px] text-slate-500 font-medium truncate">Présence</p>
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-bold text-[#444050]">{{ $choristeStats['my_presence_rate'] }}%</span>
                        <span class="text-[11px] font-semibold text-[#28C76F] bg-[#DFF7E9] px-1 py-0.5 rounded">Fidèle</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Streak Stat -->
        <div class="card-material p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-[#DFF7E9] text-[#28C76F] rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[13px] text-slate-500 font-medium truncate">Série</p>
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-bold text-[#444050]">{{ $choristeStats['attendance_streak'] }}</span>
                        <span class="text-[11px] font-semibold text-slate-400">🔥</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Repertoire Stat -->
        <div class="card-material p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-[#FFF1E3] text-[#FF9F43] rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[13px] text-slate-500 font-medium truncate">Répertoire</p>
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-bold text-[#444050]">{{ $choristeStats['total_chants_count'] }}</span>
                        <span class="text-[11px] font-semibold text-slate-400">Chants</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Updates Stat -->
        <div class="card-material p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-[#E5F8FF] text-[#00CFE8] rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[13px] text-slate-500 font-medium truncate">Annonces</p>
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-bold text-[#444050]">{{ $choristeStats['notifications']->count() }}</span>
                        <span class="text-[11px] font-semibold text-[#00CFE8] bg-[#E5F8FF] px-1 py-0.5 rounded">New</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Rehearsals Timeline (Admin Table Style) -->
        <div class="card-material lg:col-span-2 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                <h3 class="text-lg font-semibold text-[#444050]">Prochaines Répétitions</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($choristeStats['next_rehearsals'] as $repetition)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-[#7367F0]/30 hover:bg-white transition-all group">
                            <div class="flex flex-col md:flex-row justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black uppercase text-[#7367F0] bg-[#E7E7FF] px-1.5 py-0.5 rounded tracking-widest">Session</span>
                                        <span class="text-xs text-slate-400 font-bold tracking-tighter">{{ \Carbon\Carbon::parse($repetition->start_time)->translatedFormat('l d F • H:i') }}</span>
                                    </div>
                                    <h4 class="text-md font-bold text-[#444050]">{{ $repetition->titre }}</h4>
                                    <div class="flex items-center gap-4 text-xs text-slate-500">
                                        <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>{{ $repetition->lieu }}</span>
                                        <span class="text-[#7367F0] font-bold">{{ $repetition->chants()->count() }} Chants</span>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <button class="btn-primary-outline text-[12px] py-1.5 px-4">Détails</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400 italic">Aucune répétition prévue.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Widgets -->
        <div class="space-y-6">
            <!-- Notifications Widget (Admin Style) -->
            <div class="card-material overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                    <h3 class="text-md font-semibold text-[#444050]">Dernières Annonces</h3>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($choristeStats['notifications'] as $notification)
                        <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[13px] font-semibold text-[#444050] leading-tight mb-1">{{ $notification->title }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400 text-xs italic">Aucune annonce.</div>
                    @endforelse
                </div>
            </div>

            <!-- Pupitre Stats (Performance Style) -->
            <div class="card-material p-6">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-[15px] font-semibold text-[#444050]">Mon Pupitre</h4>
                    <span class="text-[12px] font-bold text-[#7367F0] bg-[#F1F0FF] px-2 py-0.5 rounded">{{ $choristeStats['pupitre']?->nom ?? 'Solo' }}</span>
                </div>
                <div class="space-y-4">
                    @foreach($choristeStats['pupitre_members']->take(5) as $member)
                        <div class="flex items-center gap-3">
                            <img src="{{ $member->photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($member->first_name).'&background=f8f7fa&color=7367f0&size=100' }}" class="w-8 h-8 rounded-full border border-slate-100 shadow-sm shrink-0 object-cover">
                            <div class="min-w-0 flex-1">
                                <p class="text-[13px] font-semibold text-[#444050] truncate">{{ $member->first_name }} {{ $member->last_name }}</p>
                                <p class="text-[11px] text-slate-400">Connecté</p>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-[#28C76F]"></span>
                        </div>
                    @endforeach
                    @if($choristeStats['pupitre_members']->count() > 5)
                        <p class="text-center text-[11px] text-[#7367F0] font-medium pt-2">+{{ $choristeStats['pupitre_members']->count() - 5 }} autres membres</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Repertoire Section (Admin Table Style) -->
    <div class="card-material overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
            <h3 class="text-lg font-semibold text-[#444050]">Nouveaux Chants au Répertoire</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[12px] text-slate-400 uppercase tracking-widest border-b border-slate-50">
                        <th class="px-6 py-3 font-semibold">Titre</th>
                        <th class="px-6 py-3 font-semibold whitespace-nowrap">Compositeur</th>
                        <th class="px-6 py-3 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-[14px]">
                    @foreach($choristeStats['latest_chants'] as $chant)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-[#444050]">{{ $chant->title }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $chant->composer ?? 'Classic' }}</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-[#7367F0] hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
