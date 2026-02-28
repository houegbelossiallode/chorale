@extends('layouts.admin')

@section('page_title', 'Aperçu Général')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header Greeting -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div class="text-center md:text-left">
            <h1 class="text-lg sm:text-2xl font-semibold text-[#444050]">Dashboard</h1>
            <p class="text-[12px] sm:text-[14px] text-slate-400 font-medium">Gestion de la chorale</p>
        </div>
        <div class="grid grid-cols-2 md:flex items-center gap-2 sm:gap-3">
            <button class="btn-secondary gap-2 border border-slate-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Exporter</span>
            </button>
            <a href="{{ route('admin.members.create') }}" class="btn-primary gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span class="truncate">Ajouter</span>
            </a>
        </div>
    </div>

    <!-- Stats Grid (CRM Style) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Members Stat -->
        <div class="card-material p-4 md:p-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-[#E7E7FF] text-[#7367F0] rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[12px] md:text-[13px] text-slate-500 font-medium truncate">Membres</p>
                    <div class="flex items-center gap-2">
                        <span class="text-lg md:text-xl font-bold text-[#444050]">{{ $stats['total_members'] }}</span>
                        <span class="text-[10px] md:text-[11px] font-semibold text-[#28C76F] bg-[#DFF7E9] px-1 py-0.5 rounded">+12%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Rate Stat -->
        <div class="card-material p-4 md:p-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-[#DFF7E9] text-[#28C76F] rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[12px] md:text-[13px] text-slate-500 font-medium truncate">Présence</p>
                    <div class="flex items-center gap-2">
                        <span class="text-lg md:text-xl font-bold text-[#444050]">{{ $stats['presence_rate'] }}%</span>
                        <span class="text-[10px] md:text-[11px] font-semibold text-[#28C76F] bg-[#DFF7E9] px-1 py-0.5 rounded">Top</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Stat -->
        <div class="card-material p-4 md:p-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-[#FFF1E3] text-[#FF9F43] rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[12px] md:text-[13px] text-slate-500 font-medium truncate">Agenda</p>
                    <div class="flex items-center gap-2">
                        <span class="text-lg md:text-xl font-bold text-[#444050]">{{ $stats['upcoming_events'] }}</span>
                        <span class="text-[10px] md:text-[11px] font-semibold text-slate-400">Prévus</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts Stat -->
        <div class="card-material p-4 md:p-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-[#E5F8FF] text-[#00CFE8] rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 4v4h4"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[12px] md:text-[13px] text-slate-500 font-medium truncate">Actualités</p>
                    <div class="flex items-center gap-2">
                        <span class="text-lg md:text-xl font-bold text-[#444050]">{{ App\Models\Post::count() }}</span>
                        <span class="text-[10px] md:text-[11px] font-semibold text-[#00CFE8] bg-[#E5F8FF] px-1 py-0.5 rounded">Actif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Insights -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Latest Members (Table Style) -->
        <div class="card-material lg:col-span-2 overflow-hidden flex flex-col">
            <div class="px-3 py-4 sm:px-6 border-b border-slate-100 flex items-center justify-between shrink-0">
                <h3 class="text-[14px] sm:text-[18px] font-semibold text-[#444050]">Nouveaux Membres</h3>
                <a href="{{ route('admin.members.index') }}" class="text-[11px] sm:text-[13px] text-[#7367F0] font-medium hover:underline">Voir tout</a>
            </div>
            <div class="overflow-x-auto custom-scrollbar-slim">
                <table class="w-full text-left min-w-[320px]">
                    <thead>
                        <tr class="text-[10px] sm:text-[12px] text-slate-400 uppercase tracking-widest border-b border-slate-50">
                            <th class="px-3 sm:px-6 py-3 font-semibold">Membre</th>
                            <th class="px-3 sm:px-6 py-3 font-semibold hidden sm:table-cell">Adhésion</th>
                            <th class="px-3 sm:px-6 py-3 font-semibold text-right sm:text-left">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($stats['latest_members'] as $member)
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="px-3 sm:px-6 py-3">
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <img src="{{ $member->photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($member->first_name).'&background=f8f7fa&color=7367f0&size=100' }}" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full border border-white shadow-sm shrink-0 object-cover">
                                    <div class="min-w-0">
                                        <p class="text-[12px] sm:text-[14px] font-semibold text-[#444050] truncate">{{ $member->first_name }} {{ $member->last_name }}</p>
                                        <p class="text-[10px] sm:text-[12px] text-slate-400 truncate">@choriste</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 sm:px-6 py-3 text-[12px] sm:text-[14px] text-slate-500 whitespace-nowrap hidden sm:table-cell">
                                {{ $member->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-3 sm:px-6 py-3 text-right sm:text-left">
                                <span class="text-[9px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded bg-[#DFF7E9] text-[#28C76F] uppercase whitespace-nowrap">Actif</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Upcoming Event Card & Performance -->
        <div class="space-y-6">
            <div class="card-material p-5 sm:p-6 bg-gradient-to-br from-[#7367F0] to-[#9E95F5] text-white">
                <div class="flex justify-between items-start mb-6 md:mb-10">
                    <div>
                        <p class="text-white/80 text-[11px] sm:text-[13px] uppercase font-bold tracking-widest mb-1 sm:mb-2">Prochain Temps Fort</p>
                        @php $nextEvent = App\Models\Event::where('start_at', '>=', now())->orderBy('start_at')->first(); @endphp
                        <h2 class="text-xl sm:text-2xl font-bold leading-tight">{{ $nextEvent->title ?? 'Aucun événement' }}</h2>
                    </div>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-md shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>

                @if($nextEvent)
                <div class="space-y-3 sm:space-y-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-white/10 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[13px] sm:text-[14px] font-medium">{{ $nextEvent->start_at->translatedFormat('d M \à H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-white/10 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-[13px] sm:text-[14px] font-medium truncate">{{ $nextEvent->location }}</span>
                    </div>
                </div>
                <button class="w-full py-2 sm:py-2.5 bg-white text-[#7367F0] font-bold text-[12px] sm:text-[13px] rounded-lg shadow-lg hover:shadow-xl transition-all border border-[#7367F0]/20">
                    Vérifier
                </button>
                @endif
            </div>

            <!-- Performance Card -->
            <div class="card-material p-5 sm:p-6">
                <div class="flex items-center justify-between mb-4 md:mb-6">
                    <h4 class="text-[14px] sm:text-[15px] font-semibold text-[#444050]">Statistiques Voix</h4>
                    <span class="text-[11px] sm:text-[12px] font-bold text-[#7367F0] bg-[#F1F0FF] px-2 py-0.5 rounded">Fév.</span>
                </div>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-[12px] sm:text-[13px] text-slate-500">Soprano</span>
                            <span class="text-[12px] sm:text-[13px] font-bold text-[#444050]">85%</span>
                        </div>
                        <div class="w-full h-1.5 sm:h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-[#7367F0]" style="width: 85%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-[12px] sm:text-[13px] text-slate-500">Ténor</span>
                            <span class="text-[12px] sm:text-[13px] font-bold text-[#444050]">72%</span>
                        </div>
                        <div class="w-full h-1.5 sm:h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-[#FF9F43]" style="width: 72%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
