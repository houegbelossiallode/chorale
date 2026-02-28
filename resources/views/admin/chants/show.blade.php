@extends('layouts.admin')

@section('title', $chant->title)

@section('content')
<div class="w-full space-y-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.chants.index') }}"
                class="text-sm text-[#7367F0] flex items-center gap-2 mb-2 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour au répertoire
            </a>
            <h1 class="text-3xl font-bold text-[#444050]">{{ $chant->title }}</h1>
            @if($chant->composer)
                <p class="text-slate-500 mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ $chant->composer }}
                </p>
            @endif
        </div>
        <a href="{{ route('admin.chants.edit', $chant->id) }}"
            class="btn-primary flex items-center gap-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Modifier ce chant
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT: Paroles + Partition principale --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Partition principale (PDF Viewer) --}}
            @if($chant->file_path)
            <div class="bg-white rounded-xl shadow-material overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-700 uppercase tracking-widest flex items-center gap-2">
                        <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center text-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        Partition principale
                    </h2>
                    <a href="{{ $chant->file_path }}" target="_blank"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-[#7367F0] hover:underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Télécharger
                    </a>
                </div>
                <iframe src="{{ $chant->file_path }}#toolbar=0" class="w-full h-[600px]" frameborder="0"></iframe>
            </div>
            @endif

            {{-- Paroles --}}
            @if($chant->parole)
            <div class="bg-white rounded-xl shadow-material p-8">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-widest border-b border-gray-100 pb-3 mb-6 flex items-center gap-2">
                    <div class="w-8 h-8 bg-[#7367F0]/10 rounded-lg flex items-center justify-center text-[#7367F0]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                        </svg>
                    </div>
                    Paroles
                </h2>
                <div class="prose max-w-none text-slate-600 leading-relaxed whitespace-pre-wrap text-sm font-medium">{{ $chant->parole }}</div>
            </div>
            @endif

        </div>

        {{-- RIGHT: Ressources --}}
        <div class="space-y-6">

            {{-- Infos rapides --}}
            <div class="bg-white rounded-xl shadow-material p-6">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest border-b border-gray-100 pb-3 mb-4">Informations</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Ajouté le</span>
                        <span class="font-bold text-slate-700">{{ $chant->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Ressources</span>
                        <span class="font-bold text-slate-700">{{ $chant->fichiers->count() }}</span>
                    </div>
                    @if($chant->file_path)
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Partition</span>
                        <span class="px-2 py-0.5 bg-green-50 text-green-600 rounded text-xs font-bold">Disponible</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Ressources associées --}}
            <div class="bg-white rounded-xl shadow-material p-6">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest border-b border-gray-100 pb-3 mb-4">Ressources associées</h3>

                @if($chant->fichiers->isEmpty())
                    <p class="text-xs text-slate-400 italic text-center py-4">Aucune ressource associée.</p>
                @else
                    {{-- Grouper par type --}}
                    @php
                        $grouped = $chant->fichiers->groupBy('type');
                    @endphp

                    <div class="space-y-5">
                        @foreach($grouped as $type => $fichiers)
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                @switch($type)
                                    @case('partition')
                                        <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        @break
                                    @case('audio')
                                        <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                                        @break
                                    @case('video')
                                        <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        @break
                                    @case('youtube')
                                        <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                                        @break
                                @endswitch
                                {{ ucfirst($type) }}s
                            </p>
                            <div class="space-y-2">
                                @foreach($fichiers as $fichier)
                                <a href="{{ $fichier->file_path }}" target="_blank"
                                    class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 hover:bg-[#7367F0]/5 hover:border-[#7367F0]/20 border border-transparent transition-all group">
                                    @switch($type)
                                        @case('partition')
                                            <div class="w-8 h-8 bg-red-50 text-red-400 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-red-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            @break
                                        @case('audio')
                                            <div class="w-8 h-8 bg-blue-50 text-blue-400 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-blue-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                                            </div>
                                            @break
                                        @case('video')
                                            <div class="w-8 h-8 bg-purple-50 text-purple-400 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-purple-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            </div>
                                            @break
                                        @case('youtube')
                                            <div class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-red-100">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                                            </div>
                                            @break
                                    @endswitch

                                    <div class="overflow-hidden flex-1">
                                        <p class="text-sm font-bold text-slate-700 truncate group-hover:text-[#7367F0] transition-colors">
                                            {{ $fichier->pupitre ? $fichier->pupitre->name : 'Tous les pupitres' }}
                                        </p>
                                        @if($type === 'youtube')
                                            <p class="text-xs text-slate-400 truncate">{{ $fichier->file_path }}</p>
                                        @else
                                            <p class="text-xs text-slate-400">Cliquer pour ouvrir</p>
                                        @endif
                                    </div>

                                    <svg class="w-4 h-4 text-slate-300 group-hover:text-[#7367F0] transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-6 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.chants.edit', $chant->id) }}"
                        class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg border border-slate-200 text-sm text-slate-600 font-medium hover:bg-slate-50 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Gérer les ressources
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
