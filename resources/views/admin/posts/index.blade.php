@extends('layouts.admin')

@section('page_title', 'Gestion Articles')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-black text-[#444050] uppercase tracking-tight">Articles & Actualités</h1>
            <p class="text-[13px] text-slate-400 font-medium">Gérez et publiez les actualités de la chorale "Saint Oscar Romero"</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.posts.create') }}" class="btn-primary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvel Article
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-material border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-[#7367F0]/10 rounded-xl flex items-center justify-center text-[#7367F0]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 4v4h4"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Articles</p>
                <p class="text-xl font-black text-[#444050]">{{ $posts->total() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-material border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-[#28C76F]/10 rounded-xl flex items-center justify-center text-[#28C76F]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Publiés</p>
                <p class="text-xl font-black text-[#444050]">{{ \App\Models\Post::whereNotNull('published_at')->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-material border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-[#FF9F43]/10 rounded-xl flex items-center justify-center text-[#FF9F43]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Brouillons</p>
                <p class="text-xl font-black text-[#444050]">{{ \App\Models\Post::whereNull('published_at')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Posts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        @forelse($posts as $post)
        <div class="group bg-white rounded-3xl shadow-material border border-slate-100 overflow-hidden flex flex-col hover:shadow-material-lg transition-all duration-500 hover:-translate-y-1">
            <!-- Image Thumbnail -->
            <div class="relative h-48 bg-slate-100 overflow-hidden">
                <img src="{{ $post->image_path ?: 'https://ui-avatars.com/api/?name='.urlencode($post->title).'&background=f8f7fa&color=7367f0&size=512' }}" 
                     alt="{{ $post->title }}" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                <div class="absolute top-4 left-4">
                    <span class="px-3 py-1 bg-white/90 backdrop-blur-md text-[#7367F0] rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm">
                        {{ $post->type ?: 'ACTUALITÉ' }}
                    </span>
                </div>

                <div class="absolute bottom-4 left-4 right-4 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                    <div class="flex items-center gap-2 text-white text-[10px] font-bold uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 flex flex-1 flex-col">
                <div class="flex-1 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full {{ $post->published_at ? 'bg-[#28C76F] shadow-[0_0_8px_rgba(40,199,111,0.4)]' : 'bg-[#FF9F43] shadow-[0_0_8px_rgba(255,159,67,0.4)]' }}"></div>
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $post->published_at ? 'text-[#28C76F]' : 'text-[#FF9F43]' }}">
                                {{ $post->published_at ? 'Publié' : 'Brouillon' }}
                            </span>
                        </div>
                        <span class="text-[10px] font-bold text-slate-300 uppercase tracking-tighter">#{{ Str::padLeft($post->id, 3, '0') }}</span>
                    </div>

                    <h3 class="font-black text-[#444050] text-lg leading-tight group-hover:text-[#7367F0] transition-colors line-clamp-2 uppercase tracking-tight">
                        {{ $post->title }}
                    </h3>

                    <p class="text-slate-400 text-xs leading-relaxed line-clamp-2 font-medium">
                        {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 100) }}
                    </p>
                </div>

                <!-- Footer Action -->
                <div class="mt-6 pt-6 border-t border-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $post->author->last_name ?? 'ADMIN' }}</span>
                    </div>

                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.posts.edit', $post) }}" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#7367F0] hover:bg-[#7367F0] hover:text-white transition-all bg-[#7367F0]/5" title="Modifier">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </a>
                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Supprimer cet article ?');" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-9 h-9 rounded-xl flex items-center justify-center text-[#EA5455] hover:bg-[#EA5455] hover:text-white transition-all bg-[#EA5455]/5" title="Supprimer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-1 md:col-span-2 xl:col-span-3 py-20 bg-white rounded-3xl border-2 border-dashed border-slate-100 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 4v4h4"/></svg>
            </div>
            <h3 class="font-bold text-[#444050] text-lg uppercase tracking-tight">Aucun article trouvé</h3>
            <p class="text-slate-400 text-sm max-w-xs mt-1">Commencez par rédiger votre première actualité ou témoignage pour dynamiser le site.</p>
            <a href="{{ route('admin.posts.create') }}" class="mt-6 btn-primary">Rédiger un article</a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="mt-8">
        {{ $posts->links() }}
    </div>
    @endif
</div>
@endsection
