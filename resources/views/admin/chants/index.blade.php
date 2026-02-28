@extends('layouts.admin')

@section('title', 'Répertoire Musical')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#444050]">Répertoire Musical</h1>
                <p class="text-slate-500 text-sm">Gérez les partitions et les fichiers audio pour les choristes.</p>
            </div>
            <button onclick="window.location.href='{{ route('admin.chants.create') }}'"
                class="btn-primary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Ajouter un chant
            </button>
        </div>

        <!-- Chants Table Card -->
        <div class="bg-white rounded-xl shadow-material overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 text-[#444050] text-[13px] uppercase tracking-wider font-bold border-b border-gray-100">
                            <th class="px-6 py-4">Titre & Compositeur</th>
                            <th class="px-6 py-4">Ressources</th>
                            <th class="px-6 py-4">Date d'ajout</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[14px]">
                        @forelse($chants as $chant)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-[#7367F0]/10 rounded-lg flex items-center justify-center text-[#7367F0]">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#444050] group-hover:text-[#7367F0] transition-colors">
                                                {{ $chant->title }}</p>
                                            <p class="text-xs text-slate-400">{{ $chant->composer ?? 'Compositeur inconnu' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        {{-- Lien de téléchargement du fichier principal du chant --}}
                                        @if($chant->file_path)
                                            <a href="{{ $chant->file_path }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#7367F0]/10 text-[#7367F0] hover:bg-[#7367F0]/20 rounded-lg text-xs font-bold transition-colors"
                                               title="Télécharger la partition principale">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                Partition
                                            </a>
                                        @endif

                                        {{-- Autres ressources (fichier_chants) --}}
                                        @foreach($chant->fichiers as $fichier)
                                            <a href="{{ $fichier->file_path }}" target="_blank"
                                               class="w-8 h-8 rounded-lg flex items-center justify-center transition-all hover:scale-110"
                                               title="{{ ucfirst($fichier->type) }} ({{ $fichier->pupitre ? $fichier->pupitre->name : 'Tous' }})">
                                                @switch($fichier->type)
                                                    @case('partition')
                                                        <div class="bg-red-50 text-red-500 p-1.5 rounded">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        </div>
                                                        @break
                                                    @case('audio')
                                                        <div class="bg-blue-50 text-blue-500 p-1.5 rounded">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                                                        </div>
                                                        @break
                                                    @case('video')
                                                        <div class="bg-purple-50 text-purple-500 p-1.5 rounded">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                        </div>
                                                        @break
                                                    @case('youtube')
                                                        <div class="bg-red-50 text-red-600 p-1.5 rounded">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                                                        </div>
                                                        @break
                                                @endswitch
                                            </a>
                                        @endforeach

                                        @if(!$chant->file_path && $chant->fichiers->isEmpty())
                                            <span class="text-xs text-slate-400 italic">Aucune ressource</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $chant->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.chants.show', $chant->id) }}" class="btn-icon" title="Voir le détail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.chants.edit', $chant->id) }}" class="btn-icon">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.chants.destroy', $chant->id) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Supprimer ce chant ?');">
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
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500 italic">
                                    Aucun chant dans le répertoire pour le moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection