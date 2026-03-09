@extends('layouts.admin')

@section('title', 'Saisie Transaction')

@section('content')
    <div class="w-full">
        <div class="mb-8">
            <a href="{{ route('admin.finance.transactions.index') }}"
                class="text-sm text-[#7367F0] flex items-center gap-2 mb-2 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour au journal
            </a>
            <h1 class="text-2xl font-bold text-[#444050]">Enregistrer une opération</h1>
            <p class="text-slate-500 text-sm">Saisissez les détails de la recette ou de la dépense.</p>
        </div>

        <form action="{{ route('admin.finance.transactions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="bg-white rounded-xl shadow-material overflow-hidden">
                <div class="p-4 md:p-8 space-y-6">
                    <div>
                        <label
                            class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Description</label>
                        <input type="text" name="description" value="{{ old('description') }}"
                            class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                            placeholder="Ex: Achat de partitions" required>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Type</label>
                            <select name="type"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                                required>
                                <option value="recette">Recette (+)</option>
                                <option value="depense">Dépense (-)</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Catégorie</label>
                            <select name="categorie_id"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                                required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->libelle }} ({{ ucfirst($cat->type) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Montant
                                (€)</label>
                            <input type="number" name="montant" value="{{ old('montant') }}"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                                placeholder="0" required>
                            @error('montant') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Référence /
                                N° Pièce</label>
                            <input type="text" name="reference" value="{{ old('reference') }}"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all"
                                placeholder="Optionnel">
                        </div>
                    </div>

                    <!-- Justificatif -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-widest">Justificatif
                            (PDF, Image)</label>
                        <div class="relative group">
                            <input type="file" name="justificatif" id="justificatif" class="hidden"
                                accept=".pdf,.jpg,.jpeg,.png">
                            <label for="justificatif"
                                class="flex items-center justify-center gap-3 w-full px-4 py-6 border-2 border-dashed border-slate-200 rounded-xl cursor-pointer group-hover:border-[#7367F0] group-hover:bg-slate-50 transition-all">
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-slate-400 mx-auto group-hover:text-[#7367F0] mb-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="text-xs text-slate-500 font-medium">Cliquez ou glissez un fichier
                                        ici</span>
                                    <p class="text-[10px] text-slate-400 mt-1" id="file-name">Maximum 5 Mo</p>
                                </div>
                            </label>
                        </div>
                        @error('justificatif') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                        <!-- Zone de prévisualisation -->
                        <div id="preview-container" class="mt-4 hidden">
                            <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <div id="preview-visual"
                                    class="w-16 h-16 rounded-lg bg-white border border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                                    <!-- Image preview or PDF icon will go here -->
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p id="preview-filename" class="text-sm font-semibold text-slate-700 truncate"></p>
                                    <p id="preview-filesize" class="text-xs text-slate-400"></p>
                                </div>
                                <button type="button" id="remove-file"
                                    class="p-2 text-red-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 md:p-8 bg-gray-50 border-t border-gray-100 flex flex-col md:flex-row justify-end gap-3">
                    <a href="{{ route('admin.finance.transactions.index') }}"
                        class="order-2 md:order-1 px-6 py-2.5 rounded-lg text-slate-600 font-medium hover:bg-slate-200 text-center transition-all">Annuler</a>
                    <button type="submit" class="order-1 md:order-2 btn-primary px-10 w-full md:w-auto">Enregistrer
                        l'opération</button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const input = document.getElementById('justificatif');
            const previewContainer = document.getElementById('preview-container');
            const previewVisual = document.getElementById('preview-visual');
            const previewFilename = document.getElementById('preview-filename');
            const previewFilesize = document.getElementById('preview-filesize');
            const removeBtn = document.getElementById('remove-file');

            input.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    previewContainer.classList.remove('hidden');
                    previewFilename.textContent = file.name;
                    previewFilesize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' Mo';

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            previewVisual.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type === 'application/pdf') {
                        previewVisual.innerHTML = `<svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 10.5h1v3h-1z"/><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2.5 11l-1.25 1.5L14.5 13l-1.25 1.5L12 13l-1.25 1.5L9.5 13V9h8v4zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6z"/></svg>`;
                    } else {
                        previewVisual.innerHTML = `<svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>`;
                    }
                } else {
                    resetPreview();
                }
            });

            removeBtn.addEventListener('click', function () {
                input.value = '';
                resetPreview();
            });

            function resetPreview() {
                previewContainer.classList.add('hidden');
                previewVisual.innerHTML = '';
                previewFilename.textContent = '';
                previewFilesize.textContent = '';
                document.getElementById('file-name').textContent = "Maximum 5 Mo";
            }
        </script>
    @endpush
@endsection