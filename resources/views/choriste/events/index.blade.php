@extends('layouts.admin')

@section('title', 'Agenda des Événements')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#444050]">Agenda des Événements</h1>
                <p class="text-slate-500 text-sm font-medium">Consultez les événements à venir et préparez vos chants.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div
                    class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-material transition-all duration-300">
                    <div class="h-40 relative overflow-hidden">
                        <img src="{{ $event->thumbnail ?? 'https://images.unsplash.com/photo-1516280440614-37939bbacd81?q=80&w=2070&auto=format&fit=crop' }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            alt="{{ $event->title }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <span class="px-2 py-1 bg-[#7367F0] rounded text-[10px] font-bold uppercase tracking-wider">
                                {{ $event->type->libelle ?? 'Événement' }}
                            </span>
                            <h3 class="font-bold mt-1">{{ $event->title }}</h3>
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col space-y-4">
                        <div class="grid grid-cols-2 gap-4 text-xs font-medium text-slate-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#7367F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $event->start_at->format('d/m/Y') }}
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#7367F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $event->start_at->format('H:i') }}
                            </div>
                        </div>

                        <div class="flex-1">
                            <p class="text-xs text-slate-400 line-clamp-2">
                                {{ $event->location ?? 'Lieu non défini' }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 mt-2">
                            <a href="{{ route('choriste.events.show', $event->id) }}"
                                class="flex-1 py-2.5 bg-[#7367F0]/10 hover:bg-[#7367F0] text-[#7367F0] hover:text-white rounded-xl text-xs font-bold transition-all text-center">
                                Voir le Répertoire
                            </a>
                            <a href="{{ route('choriste.events.repertoire.pdf', $event->id) }}" target="_blank"
                                title="Télécharger le programme (PDF)"
                                class="px-4 py-2.5 bg-red-50 hover:bg-red-500 text-red-500 hover:text-white rounded-xl transition-all flex items-center justify-center border border-red-100/50 hover:border-red-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection