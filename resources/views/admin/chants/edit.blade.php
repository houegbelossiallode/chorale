@extends('layouts.admin')

@section('title', 'Modifier le Chant')

@section('content')
<div class="w-full">
    <div class="mb-8">
        <a href="{{ route('admin.chants.index') }}"
            class="text-sm text-[#7367F0] flex items-center gap-2 mb-2 hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour au répertoire
        </a>
        <h1 class="text-2xl font-bold text-[#444050]">Modifier le chant : {{ $chant->title }}</h1>
        <p class="text-slate-500 text-sm">Mettez à jour les informations et gérez les ressources musicales.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ===================== --}}
        {{-- LEFT: Chant Info Form --}}
        {{-- ===================== --}}
        <div class="lg:col-span-2 space-y-6">
            <form action="{{ route('admin.chants.update', $chant->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-xl shadow-material p-8 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Titre du chant</label>
                        <input type="text" name="title" value="{{ old('title', $chant->title) }}"
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                            placeholder="Ex: Te Deum Laudamus" required>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Compositeur</label>
                        <input type="text" name="composer" value="{{ old('composer', $chant->composer) }}"
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                            placeholder="Ex: Wolfgang Amadeus Mozart">
                        @error('composer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Paroles / Texte</label>
                        <textarea name="parole" rows="10"
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all resize-none"
                            placeholder="Saisissez ou collez les paroles ici...">{{ old('parole', $chant->parole) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 flex gap-3">
                    <button type="submit" class="btn-primary py-3 px-8 shadow-lg shadow-[#7367F0]/30">Mettre à jour</button>
                    <a href="{{ route('admin.chants.index') }}"
                        class="inline-block text-center px-6 py-3 rounded-lg text-slate-500 font-medium hover:bg-slate-100 transition-all">Annuler</a>
                </div>
            </form>
        </div>

        {{-- ======================== --}}
        {{-- RIGHT: Resources Sidebar --}}
        {{-- ======================== --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-material p-6 space-y-4">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest border-b border-gray-100 pb-3">
                    Ressources du chant</h3>

                {{-- Messages de feedback --}}
                @if(session('success'))
                    <div class="px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 font-medium">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 font-medium">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Liste des ressources existantes --}}
                <div class="space-y-2">
                    @forelse($chant->fichiers as $fichier)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="shrink-0 text-[#7367F0]">
                                @switch($fichier->type)
                                    @case('partition')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        @break
                                    @case('audio')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                                        @break
                                    @case('video')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        @break
                                    @case('youtube')
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                                        @break
                                @endswitch
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-sm font-bold text-slate-700 capitalize">{{ $fichier->type }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ $fichier->pupitre ? $fichier->pupitre->name : 'Tous les pupitres' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <a href="{{ $fichier->file_path }}" target="_blank" class="p-2 text-slate-400 hover:text-[#7367F0] transition-colors" title="Voir">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <form action="{{ route('admin.fichier-chants.destroy', $fichier->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce fichier ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic py-2">Aucune ressource associée.</p>
                    @endforelse
                </div>

                {{-- Formulaire d'ajout de ressources (HORS du formulaire principal) --}}
                <div class="pt-4 border-t border-gray-100">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Ajouter une ressource</h4>

                    <form action="{{ route('admin.fichier-chants.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="hidden" name="chant_id" value="{{ $chant->id }}">

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Type de ressource</label>
                            <select name="type" id="res_type" class="w-full text-sm border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-0 px-3 py-2.5">
                                <option value="partition">Partition (PDF)</option>
                                <option value="audio">Fichier Audio (MP3...)</option>
                                <!-- <option value="video">Fichier Vidéo (MP4...)</option> -->
                                <option value="youtube">Lien YouTube</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Pupitre concerné</label>
                            <select name="pupitre_id" class="w-full text-sm border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-0 px-3 py-2.5">
                                <option value="">Tous les pupitres</option>
                                @foreach($pupitres as $pupitre)
                                    <option value="{{ $pupitre->id }}">{{ $pupitre->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="file_input_container">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Fichier</label>
                            <input type="file" name="file" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#7367F0]/10 file:text-[#7367F0] hover:file:bg-[#7367F0]/20">
                        </div>

                        <div id="url_input_container" class="hidden">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">URL YouTube</label>
                            <input type="url" name="url" placeholder="https://www.youtube.com/watch?v=..." class="w-full text-sm border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-0 px-3 py-2.5">
                        </div>

                        <button type="submit" class="w-full py-3 bg-slate-800 text-white rounded-lg text-sm font-bold hover:bg-slate-900 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Ajouter à la liste
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resType = document.getElementById('res_type');
        const fileContainer = document.getElementById('file_input_container');
        const urlContainer = document.getElementById('url_input_container');

        if (resType) {
            resType.addEventListener('change', function() {
                if (this.value === 'youtube') {
                    fileContainer.classList.add('hidden');
                    urlContainer.classList.remove('hidden');
                } else {
                    fileContainer.classList.remove('hidden');
                    urlContainer.classList.add('hidden');
                }
            });
        }
    });
</script>
@endsection