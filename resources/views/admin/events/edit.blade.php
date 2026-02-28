@extends('layouts.admin')

@section('page_title', 'Modifier Événement')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.events.index') }}"
                    class="w-9 h-9 bg-white rounded-lg flex items-center justify-center text-slate-400 hover:text-[#7367F0] shadow-sm border border-slate-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h3 class="text-xl sm:text-2xl font-semibold text-[#444050]">Modifier l'Événement</h3>
                    <p class="text-[13px] text-slate-400">Édition de : {{ $event->title }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6" x-data="{ 
                        previews: [],
                        principalId: {{ $event->principalImage->id ?? 'null' }},
                        principalIndex: null,
                        dataTransfer: new DataTransfer(),
                        handleFiles(event) {
                            const files = Array.from(event.target.files);
                            files.forEach(file => {
                                this.dataTransfer.items.add(file);
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    this.previews.push({
                                        id: Math.random().toString(36).substr(2, 9),
                                        url: e.target.result,
                                        name: file.name
                                    });
                                };
                                reader.readAsDataURL(file);
                            });
                            this.$refs.fileInput.files = this.dataTransfer.files;
                        },
                        removePreview(id) {
                            const index = this.previews.findIndex(p => p.id === id);
                            if (index !== -1) {
                                this.previews.splice(index, 1);
                                const newDt = new DataTransfer();
                                const files = Array.from(this.dataTransfer.files);
                                files.splice(index, 1);
                                files.forEach(file => newDt.items.add(file));
                                this.dataTransfer = newDt;
                                this.$refs.fileInput.files = this.dataTransfer.files;

                                // Reset principal index if deleted
                                if (this.principalIndex === index) this.principalIndex = null;
                                else if (this.principalIndex > index) this.principalIndex--;
                            }
                        },
                        setPrincipalExisting(id) {
                            this.principalId = id;
                            this.principalIndex = null;
                        },
                        setPrincipalNew(index) {
                            this.principalIndex = index;
                            this.principalId = null;
                        }
                      }">
            @csrf
            @method('PUT')

            <input type="hidden" name="principal_image_id" x-model="principalId">
            <input type="hidden" name="principal_image_index" x-model="principalIndex">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Event details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- General Information -->
                    <div class="card-material p-6 sm:p-8 space-y-6">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                            <div class="w-1.5 h-4 bg-[#7367F0] rounded-full"></div>
                            <h4 class="text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Informations
                                Générales</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="sm:col-span-2 space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Titre de l'événement</label>
                                <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px]"
                                    placeholder="Titre">
                                @error('title') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Type d'événement</label>
                                <select name="type_id" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px] appearance-none">
                                    <option value="">Sélectionner un type</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('type_id', $event->type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_id') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Lieu / Localisation</label>
                                <input type="text" name="location" value="{{ old('location', $event->location) }}" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px]"
                                    placeholder="Lieu">
                                @error('location') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[12px] font-semibold text-slate-500 ml-1">Description / Notes</label>
                            <textarea name="description" rows="5"
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px]"
                                placeholder="Contenu...">{{ old('description', $event->description) }}</textarea>
                            @error('description') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Multi-Images Gallery -->
                    <div class="card-material p-6 sm:p-8 space-y-6">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                            <div class="w-1.5 h-4 bg-[#28C76F] rounded-full"></div>
                            <h4 class="text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Galerie d'images
                            </h4>
                        </div>

                        <!-- Existing Images -->
                        @if($event->images->count() > 0)
                            <div class="space-y-3">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Images Actuelles</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    @foreach($event->images as $image)
                                        <div class="relative group aspect-square rounded-xl overflow-hidden border-2 shadow-sm transition-all"
                                            :class="principalId == {{ $image->id }} ? 'border-[#7367F0] ring-4 ring-[#7367F0]/10 shadow-lg' : 'border-slate-100 hover:border-[#7367F0]/30'">
                                            <img src="{{ $image->image_path }}" class="w-full h-full object-cover">

                                            <!-- Actions Overlay -->
                                            <div
                                                class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                                <!-- Set Principal -->
                                                <button type="button" @click="setPrincipalExisting({{ $image->id }})"
                                                    class="w-8 h-8 rounded-full flex items-center justify-center transition-all shadow-lg"
                                                    :class="principalId == {{ $image->id }} ? 'bg-[#7367F0] text-white' : 'bg-white text-slate-400 hover:text-[#7367F0]'">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M10 2a1 1 0 01.832.445l2.333 3.5a1 1 0 01.168.555v6.5a1 1 0 01-2 0v-5.228l-1.332-1.998-1.332 1.998V13a1 1 0 01-2 0V6.5a1 1 0 01.168-.555l2.333-3.5A1 1 0 0110 2z" />
                                                    </svg>
                                                </button>

                                                <!-- Delete -->
                                                <button type="button"
                                                    onclick="if(confirm('Supprimer cette image ?')) { document.getElementById('delete-image-{{ $image->id }}').submit(); }"
                                                    class="w-8 h-8 bg-white text-red-500 rounded-full flex items-center justify-center hover:bg-red-50 transition-all shadow-lg">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Label Principal -->
                                            <div x-show="principalId == {{ $image->id }}"
                                                class="absolute top-2 left-2 px-2 py-0.5 bg-[#7367F0] text-white text-[9px] font-bold rounded-md uppercase tracking-wider shadow-sm">
                                                Couverte
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <label class="text-[12px] font-semibold text-slate-500 ml-1">Ajouter de nouvelles photos</label>
                            <!-- Preview Grid for New Files -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" x-show="previews.length > 0">
                                <template x-for="(image, index) in previews" :key="image.id">
                                    <div class="relative group aspect-square rounded-xl overflow-hidden border-2 shadow-sm transition-all"
                                        :class="principalIndex === index ? 'border-[#7367F0] ring-4 ring-[#7367F0]/10 shadow-lg' : 'border-slate-100 hover:border-[#7367F0]/30'">

                                        <img :src="image.url" class="w-full h-full object-cover">

                                        <!-- Actions Overlay -->
                                        <div
                                            class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                            <!-- Principal Selection -->
                                            <button type="button" @click="setPrincipalNew(index)"
                                                class="w-10 h-10 rounded-full flex items-center justify-center transition-all shadow-lg"
                                                :class="principalIndex === index ? 'bg-[#7367F0] text-white' : 'bg-white text-slate-400 hover:text-[#7367F0]'">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 2a1 1 0 01.832.445l2.333 3.5a1 1 0 01.168.555v6.5a1 1 0 01-2 0v-5.228l-1.332-1.998-1.332 1.998V13a1 1 0 01-2 0V6.5a1 1 0 01.168-.555l2.333-3.5A1 1 0 0110 2z" />
                                                </svg>
                                            </button>

                                            <!-- Remove -->
                                            <button type="button" @click="removePreview(image.id)"
                                                class="w-10 h-10 bg-white text-red-500 rounded-full flex items-center justify-center hover:bg-red-50 transition-all shadow-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Label Principal -->
                                        <div x-show="principalIndex === index"
                                            class="absolute top-2 left-2 px-2 py-0.5 bg-[#7367F0] text-white text-[9px] font-bold rounded-md uppercase tracking-wider shadow-sm">
                                            Couverte
                                        </div>

                                        <div
                                            class="absolute bottom-0 inset-x-0 p-1.5 bg-gradient-to-t from-black/50 to-transparent">
                                            <p class="text-[8px] text-white truncate" x-text="image.name"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Upload Trigger -->
                            <label
                                class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-8 bg-slate-50/50 hover:bg-slate-50 hover:border-[#7367F0]/30 transition-all cursor-pointer group">
                                <input type="file" name="images[]" multiple class="hidden" accept="image/*"
                                    x-ref="fileInput" @change="handleFiles">
                                <div
                                    class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-100 mb-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-7 h-7 text-[#7367F0]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h5 class="text-[14px] font-semibold text-[#444050]">Ajouter des photos à la galerie</h5>
                            </label>
                            @error('images.*') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column: Schedule & Actions -->
                <div class="space-y-6 lg:sticky lg:top-6 self-start">
                    <!-- Planning -->
                    <div class="card-material p-6 sm:p-8 space-y-6">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                            <div class="w-1.5 h-4 bg-[#FF9F43] rounded-full"></div>
                            <h4 class="text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Planning</h4>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Début</label>
                                <input type="datetime-local" name="start_at"
                                    value="{{ old('start_at', $event->start_at->format('Y-m-d\TH:i')) }}" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px]">
                                @error('start_at') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Fin prévue (Optionnel)</label>
                                <input type="datetime-local" name="end_at"
                                    value="{{ old('end_at', $event->end_at ? $event->end_at->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px]">
                                @error('end_at') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-3 pt-2">
                        <button type="submit"
                            class="btn-primary w-full py-4 text-[14px] flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.events.index') }}"
                            class="btn-secondary w-full py-4 text-[14px] text-center">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Hidden Delete Forms -->
    @foreach($event->images as $image)
        <form id="delete-image-{{ $image->id }}" action="{{ route('admin.events.delete-image', $image) }}" method="POST"
            class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endsection