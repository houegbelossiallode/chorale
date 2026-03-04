@extends('layouts.admin')

@section('title', 'Agenda des Répétitions')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-[#444050]">Agenda des Répétitions</h1>
                <p class="text-slate-500 text-xs md:text-sm">Retrouvez toutes les séances de répétition programmées.</p>
            </div>
        </div>

        <!-- Events Grid (Premium Card Design) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($repetitions as $rep)
                <div
                    class="bg-white rounded-[2rem] shadow-material-sm border border-slate-100 hover:border-[#7367F0]/30 transition-all group overflow-hidden flex flex-col hover:shadow-material-lg">
                    <!-- Top Section with Date & Icon -->
                    <div
                        class="h-28 bg-gradient-to-br from-slate-50 to-slate-100 relative overflow-hidden shrink-0 group-hover:from-[#7367F0]/5 group-hover:to-[#7367F0]/10 transition-colors">
                        <!-- Date Badge Floating -->
                        <div
                            class="absolute top-4 left-6 w-14 h-14 bg-white rounded-2xl shadow-lg flex flex-col items-center justify-center border border-slate-50 group-hover:scale-110 transition-transform">
                            <span
                                class="text-[10px] font-black text-[#7367F0] uppercase leading-none mb-0.5">{{ \Carbon\Carbon::parse($rep->start_time)->translatedFormat('M') }}</span>
                            <span
                                class="text-xl font-black text-[#444050] leading-none">{{ \Carbon\Carbon::parse($rep->start_time)->format('d') }}</span>
                        </div>

                        <!-- Icon Placeholder -->
                        <div class="absolute top-0 right-0 p-8 opacity-5">
                            <svg class="w-24 h-24 text-[#7367F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                        </div>

                        <!-- Status Badge -->
                        <div class="absolute top-4 right-6">
                            <span
                                class="px-3 py-1 bg-white/90 backdrop-blur-md text-[#28C76F] rounded-lg text-[9px] font-black uppercase tracking-wider shadow-sm border border-[#28C76F]/10">
                                {{ $rep->presences_count }} POINTÉS
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="mb-5">
                            <h3
                                class="text-lg font-black text-[#444050] line-clamp-1 mb-2 group-hover:text-[#7367F0] transition-colors uppercase tracking-tight">
                                {{ $rep->titre }}
                            </h3>

                            <div class="space-y-2">
                                <div class="flex items-center gap-3 text-slate-400">
                                    <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-[12px] font-bold text-slate-600">
                                        {{ \Carbon\Carbon::parse($rep->start_time)->format('H:i') }} —
                                        {{ \Carbon\Carbon::parse($rep->end_time)->format('H:i') }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-3 text-slate-400">
                                    <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-[12px] font-bold text-slate-600 italic truncate">{{ $rep->lieu }}</span>
                                </div>
                            </div>
                        </div>

                        @if($rep->description)
                            <p class="text-[13px] text-slate-500 line-clamp-2 mb-6 min-h-[40px] italic">
                                "{{ $rep->description }}"
                            </p>
                        @else
                            <div class="mb-6 min-h-[40px]"></div>
                        @endif

                        <!-- Actions -->
                        <div class="mt-auto pt-6 border-t border-slate-50">
                            <a href="{{ route('choriste.repetitions.repertoire', $rep->id) }}"
                                class="w-full py-3 bg-[#7367F0]/5 hover:bg-[#7367F0] text-[#7367F0] hover:text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.1em] transition-all flex items-center justify-center gap-3 shadow-sm active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                                Voir le répertoire
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full py-24 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm">
                        <svg class="w-8 h-8 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold uppercase tracking-widest opacity-60">Aucune répétition programmée</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            <div class="bg-white px-6 py-4 rounded-[2rem] shadow-material-sm border border-slate-100">
                {{ $repetitions->links() }}
            </div>
        </div>

    </div>
@endsection