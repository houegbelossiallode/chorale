@extends('layouts.app')

@section('title', 'Programme - ' . $event->title)

@section('content')
    <div class="bg-[#F8F9FA] min-h-screen py-12 px-4">
        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Header -->
            <div class="text-center space-y-4">
                <h1 class="text-4xl font-extrabold text-[#444050] tracking-tight">{{ $event->title }}</h1>
                <div class="flex items-center justify-center gap-4 text-slate-500 font-medium">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $event->start_at->format('d/m/Y') }}
                    </span>
                    <span class="w-1.5 h-1.5 rounded-full bg-[#7367F0]/30"></span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $event->location ?? 'Lieu non défini' }}
                    </span>
                </div>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Découvrez le déroulement musical de notre événement.</p>
            </div>

            <!-- Répertoire -->
            <div class="space-y-6">
                @php 
                    $grouped = $repertoire->groupBy('partie_titre');
                @endphp


                               @forelse($grouped as $titre => $items)
                                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden transform transition-all hover:shadow-md">
                                    <div class="px-8 py-5 bg-gradient-to-r from-[#7367F0]/5 to-transparent border-b border-slate-50 flex items-center justify-between">
                                        <h2 class="text-xl font-bold text-[#444050]">{{ $titre ?? 'Répertoire' }}</h2>
                                        <span class="px-4 py-1 bg-[#7367F0] text-white rounded-full text-[12px] font-bold">
                                            {{ $items->count() }} {{ Str::plural('Chant', $items->count()) }}
                                        </span>
                                    </div>
                                    <div class="divide-y divide-slate-50">
                                        @foreach($items as $item)
                                            <div class="p-8 space-y-4">
                                                <div class="flex justify-between items-start gap-4">
                                                    <div>
                                                        <h3 class="text-lg font-bold text-[#444050]">{{ $item->chant_title }}</h3>
                                                        @if($item->composer)
                                                            <p class="text-sm text-slate-400 font-medium">Compositeur : {{ $item->composer }}</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Ressources -->
                                                @if($item->fichiers->count() > 0)
                                                    <div class="pt-4 flex flex-wrap gap-3">
                                                        @foreach($item->fichiers as $fichier)
                                                                                        <a href="{{ asset('storage/' . $fichier->url) }}" target="_blank"
                                                                                           class="flex items-center gap-2 px-4 py-2 bg-slate-50 hover:bg-[#7367F0]/10 t
                                                                                                ext-slate-600 hover:text-[#7367F0] rounded-xl text-sm font-bold trans
                                                            i                                       tion-all border border-slate-100 group">

                                                                                                                            @if($fichier->type == 'audio')
                                                                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                                                                        d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>

                                                                                                                            @elseif($fichier->type == 'pdf')
                                                                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                                                                                </svg>
                                                                                                                            @else
                                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                                                                            @endif
                                                                                            {{ $fichier->nom ?? 'Ressource' }}
                                                                                        </a>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-xs text-slate-400 italic">Aucune ressource disponible pour ce chant.</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                    <div class="bg-white rounded-3xl p-16 text-center shadow-sm border border-slate-100">



                                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#444050]">Répertoire vide</h3>
                        <p class="text-slate-400">Le répertoire musical de cet événement n'est pas encore disponible.</p>
                    </div>
                @endforelse
            </div>


                           <div class="text-center pt-8">


                                <a href="{{ route('events.index') }}" class="text-[#7367F0] font-bold flex items-center justify-center gap-2 hover:gap-3 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Retour aux événements
                </a>
            </div>
        </div>    </div>
@endsection
