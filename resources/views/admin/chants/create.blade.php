@extends('layouts.admin')

@section('title', 'Nouveau Chant')

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
            <h1 class="text-2xl font-bold text-[#444050]">Ajouter un nouveau chant</h1>
            <p class="text-slate-500 text-sm">Remplissez les informations et téléchargez les ressources associées.</p>
        </div>

        <form action="{{ route('admin.chants.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-material p-8 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Titre du
                                chant</label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                                placeholder="Ex: Te Deum Laudamus" required>
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label
                                class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Compositeur</label>
                            <input type="text" name="composer" value="{{ old('composer') }}"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                                placeholder="Ex: Wolfgang Amadeus Mozart">
                            @error('composer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Paroles /
                                Texte</label>
                            <textarea name="parole" rows="10"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all resize-none"
                                placeholder="Saisissez ou collez les paroles ici...">{{ old('parole') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-material p-8 space-y-6">
                        <h3
                            class="text-sm font-bold text-slate-700 uppercase tracking-widest border-b border-gray-100 pb-3">
                            Partition principale</h3>

                        <div class="space-y-4">
                            <div class="relative group">
                                <input type="file" name="partition" id="partition" class="hidden" accept=".pdf">
                                <label for="partition" id="partition-label"
                                    class="w-full flex flex-col items-center justify-center gap-3 py-10 px-4 border-2 border-dashed border-slate-200 rounded-xl cursor-pointer group-hover:bg-[#7367F0]/5 group-hover:border-[#7367F0]/30 transition-all">
                                    <div
                                        class="w-12 h-12 bg-[#7367F0]/10 rounded-full flex items-center justify-center text-[#7367F0]">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-[13px] font-bold text-slate-700">Choisir un PDF</p>
                                        <p class="text-[11px] text-slate-400">Max 10Mo</p>
                                    </div>
                                </label>
                                <!-- Preview Container -->
                                <div id="pdf-preview-container"
                                    class="hidden w-full h-[400px] border border-slate-200 rounded-xl overflow-hidden relative">
                                    <iframe id="pdf-preview" src="" class="w-full h-full" frameborder="0"></iframe>
                                    <button type="button" id="remove-pdf"
                                        class="absolute top-2 right-2 bg-white p-2 rounded-lg text-red-500 hover:bg-red-50 transition-all shadow-md z-10"
                                        title="Retirer le PDF">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @error('partition') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="p-2 space-y-3">
                        <button type="submit"
                            class="btn-primary w-full py-3 shadow-lg shadow-[#7367F0]/30">Enregistrer</button>
                        <a href="{{ route('admin.chants.index') }}"
                            class="block text-center w-full px-6 py-3 rounded-lg text-slate-500 font-medium hover:bg-slate-100 transition-all">Annuler</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('partition');
            const previewContainer = document.getElementById('pdf-preview-container');
            const previewFrame = document.getElementById('pdf-preview');
            const uploadLabel = document.getElementById('partition-label');
            const removeBtn = document.getElementById('remove-pdf');

            if (fileInput) {
                fileInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file && file.type === 'application/pdf') {
                        const fileURL = URL.createObjectURL(file);
                        previewFrame.src = fileURL + '#toolbar=0';
                        previewContainer.classList.remove('hidden');
                        uploadLabel.classList.add('hidden');
                    } else {
                        hidePreview();
                    }
                });
            }

            if (removeBtn) {
                removeBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    fileInput.value = '';
                    hidePreview();
                });
            }

            function hidePreview() {
                previewFrame.src = '';
                previewContainer.classList.add('hidden');
                uploadLabel.classList.remove('hidden');
            }
        });
    </script>
@endsection