@extends('layouts.admin')

@section('page_title', 'Modifier Article')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.posts.index') }}"
                    class="w-9 h-9 bg-white rounded-lg flex items-center justify-center text-slate-400 hover:text-[#7367F0] shadow-sm border border-slate-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h3 class="text-xl sm:text-2xl font-semibold text-[#444050]">Modifier l'Article</h3>
                    <p class="text-[13px] text-slate-400">Édition de : {{ $post->title }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Article Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Content Editor Area -->
                    <div class="card-material p-6 sm:p-8 space-y-6">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                            <div class="w-1.5 h-4 bg-[#7367F0] rounded-full"></div>
                            <h4 class="text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Contenu de
                                l'article</h4>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[12px] font-semibold text-slate-500 ml-1">Titre de l'article</label>
                            <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[15px] font-semibold"
                                placeholder="Titre">
                            @error('title') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- <div class="space-y-1.5">
                            <label class="text-[12px] font-semibold text-slate-500 ml-1">Extrait (Court résumé)</label>
                            <textarea name="excerpt" rows="2"
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px]"
                                placeholder="Résumé...">{{ old('excerpt', $post->excerpt) }}</textarea>
                            @error('excerpt') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                        </div> -->

                        <div class="space-y-1.5">
                            <label class="text-[12px] font-semibold text-slate-500 ml-1">Corps de l'article</label>
                            <textarea name="content" rows="12" required
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px] leading-relaxed"
                                placeholder="Rédigez...">{{ old('content', $post->content) }}</textarea>
                            @error('content') <p class="text-xs text-[#EA5455] mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column: Settings & Meta -->
                <div class="space-y-6">
                    <!-- Publication Settings -->
                    <div class="card-material p-6 sm:p-8 space-y-6">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                            <div class="w-1.5 h-4 bg-[#28C76F] rounded-full"></div>
                            <h4 class="text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Publication</h4>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Catégorie</label>
                                <select name="category" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px] appearance-none">
                                    <option value="news" {{ old('category', $post->type) == 'news' ? 'selected' : '' }}>
                                        Actualité</option>
                                    <option value="testimony" {{ old('category', $post->type) == 'testimony' ? 'selected' : '' }}>Témoignage</option>
                                    <option value="priest_word" {{ old('category', $post->type) == 'priest_word' ? 'selected' : '' }}>Parole de Prêtre</option>
                                    <option value="history" {{ old('category', $post->type) == 'history' ? 'selected' : '' }}>
                                        Histoire</option>
                                </select>
                            </div>

                            <!-- <div class="space-y-1.5">
                                <label class="text-[12px] font-semibold text-slate-500 ml-1">Auteur</label>
                                <input type="text" name="author" value="{{ old('author', $post->author) }}" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 transition-all text-[#444050] text-[14px]"
                                    placeholder="Nom de l'auteur">
                            </div> -->

                            <div class="pt-2">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ old('is_published', $post->published_at) ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#28C76F]">
                                        </div>
                                    </div>
                                    <span
                                        class="text-[14px] font-medium text-slate-600 group-hover:text-[#444050] transition-colors">Article
                                        en ligne</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="card-material p-6 sm:p-8 space-y-6" x-data="{ imageUrl: '{{ $post->image_path }}' }">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                            <div class="w-1.5 h-4 bg-[#FF9F43] rounded-full"></div>
                            <h4 class="text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Image à la une
                            </h4>
                        </div>

                        <div class="space-y-4">
                            <div class="relative group">
                                <input type="file" name="image_path" id="image_path" class="hidden" accept="image/*"
                                    @change="const file = $event.target.files[0]; if (file) { imageUrl = URL.createObjectURL(file) }">

                                <template x-if="!imageUrl">
                                    <label for="image_path"
                                        class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl p-8 bg-slate-50/50 hover:bg-slate-50 hover:border-[#7367F0]/30 transition-all cursor-pointer">
                                        <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-[12px] font-medium text-slate-500">Changer l'image</span>
                                    </label>
                                </template>

                                <template x-if="imageUrl">
                                    <div
                                        class="relative rounded-xl overflow-hidden border border-slate-200 shadow-sm aspect-video group">
                                        <img :src="imageUrl" class="w-full h-full object-cover">
                                        <div
                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                                            <label for="image_path"
                                                class="p-2 bg-white/20 hover:bg-white/40 rounded-full text-white cursor-pointer transition-colors backdrop-blur-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </label>
                                            <button type="button"
                                                @click="imageUrl = null; document.getElementById('image_path').value = ''"
                                                class="p-2 bg-red-500/20 hover:bg-red-500/40 rounded-full text-white cursor-pointer transition-colors backdrop-blur-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            @error('image_path') <p class="text-xs text-[#EA5455] text-center mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit"
                            class="w-full py-3 bg-[#7367F0] text-white rounded-lg font-bold text-[14px] shadow-material hover:bg-[#685dd8] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Mettre à jour l'Article
                        </button>
                        <a href="{{ route('admin.posts.index') }}"
                            class="w-full py-3 bg-white text-slate-400 border border-slate-200 rounded-lg font-bold text-[14px] text-center hover:bg-slate-50 transition-all">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection