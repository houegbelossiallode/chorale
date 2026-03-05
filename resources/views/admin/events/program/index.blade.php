@extends('layouts.admin')

@section('title', 'Programme de l\'événement')

@section('content')
    <div class="space-y-6" x-data="{
                                    songModal: false,
                                    searchQuery: '',
                                    songData: { chant_id: '', partie_event_id: '' },

                                    openSongModal() {
                                        this.songData.chant_id = '';
                                        this.songData.partie_event_id = '';
                                        this.searchQuery = '';
                                        this.songModal = true;
                                    }
                                }">
        <!-- Header & Info Card -->
        <div class="card-material p-6 sm:p-8 overflow-hidden relative group">
            <div
                class="absolute top-0 right-0 w-32 h-32 -mr-8 -mt-8 bg-[#7367F0]/5 rounded-full blur-3xl group-hover:bg-[#7367F0]/10 transition-colors duration-500">
            </div>

            <div class="flex flex-col lg:flex-row gap-6 lg:items-center justify-between relative">
                <div class="flex items-start gap-4">
                    <a href="{{ route('admin.events.index') }}"
                        class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 hover:text-[#7367F0] shadow-sm border border-slate-100 transition-all shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <span
                                class="px-2 py-0.5 bg-[#7367F0]/10 text-[#7367F0] rounded text-[10px] font-bold uppercase tracking-wider">
                                {{ $event->type->libelle ?? 'Événement' }}
                            </span>
                            <span class="text-[11px] font-medium text-slate-400">
                                • {{ \Carbon\Carbon::parse($event->start_at)->translatedFormat('l d F Y') }}
                            </span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-bold text-[#444050] truncate">{{ $event->title }}</h1>
                        <p class="text-slate-500 text-[13px] mt-1 line-clamp-1">
                            <svg class="w-3.5 h-3.5 inline mr-1 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $event->location }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Visibility Toggle -->
                    <form action="{{ route('admin.events.program.toggle-visibility', $event->id) }}" method="POST"
                        class="flex items-center gap-3 px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl transition-all hover:bg-white hover:shadow-sm">
                        @csrf
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold uppercase tracking-[0.1em] text-slate-400">Visibilité</span>
                            <span
                                class="text-[11px] font-bold {{ $event->is_repertoire_public ? 'text-[#28C76F]' : 'text-slate-500' }}">
                                {{ $event->is_repertoire_public ? 'Public' : 'Privé' }}
                            </span>
                        </div>
                        <button type="submit"
                            class="relative inline-flex h-5 w-10 items-center rounded-full transition-colors focus:outline-none {{ $event->is_repertoire_public ? 'bg-[#28C76F]' : 'bg-slate-300' }}">
                            <span
                                class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform {{ $event->is_repertoire_public ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </form>

                    <div class="h-10 w-[1px] bg-slate-100 hidden sm:block mx-1"></div>

                    <a href="{{ route('admin.events.repertoire.pdf', $event->id) }}"
                        class="btn-secondary h-11 px-4 gap-2 text-[12px] font-bold uppercase tracking-wider text-slate-600 border-slate-200">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        PDF
                    </a>

                    <button @click="openSongModal()"
                        class="btn-primary h-11 px-5 gap-2 text-[12px] font-bold uppercase tracking-wider shadow-[#7367F0]/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter un chant
                    </button>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div
                class="px-6 py-4 bg-[#DFF7E9]/60 border border-[#28C76F]/20 text-[#28C76F] rounded-2xl font-semibold text-sm flex items-center gap-3 backdrop-blur-sm">
                <div class="w-8 h-8 bg-[#28C76F] text-white rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                {{ session('success') }}
            </div>
        @endif

        <!-- Repertoire Section -->
        <div class="space-y-4">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-4 bg-[#7367F0] rounded-full"></div>
                    <h2 class="text-[14px] font-bold text-[#444050] uppercase tracking-wider">Répertoire Musical</h2>
                </div>
                <span
                    class="text-[11px] font-bold text-slate-400 bg-slate-50 px-2.5 py-1 rounded-full border border-slate-100">
                    {{ $repertoire->count() }} Élément{{ $repertoire->count() > 1 ? 's' : '' }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($repertoire as $item)
                    <div
                        class="bg-white rounded-2xl p-5 border border-slate-100 shadow-material-sm hover:shadow-material transition-all group relative overflow-hidden">
                        {{-- Background Decoration --}}
                        <div
                            class="absolute -right-4 -bottom-4 text-slate-50 group-hover:text-[#7367F0]/5 transition-colors duration-500 pointer-events-none">
                            <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                            </svg>
                        </div>

                        <div class="flex flex-col h-full relative">
                            <div class="flex justify-between items-start mb-4">
                                <span
                                    class="px-2.5 py-1 bg-[#7367F0]/5 text-[#7367F0] rounded-lg text-[9px] font-extrabold uppercase tracking-widest border border-[#7367F0]/10">
                                    {{ $item->partie_titre ?? 'Non défini' }}
                                </span>

                                <form action="{{ route('admin.events.repertoire.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Retirer ce chant du répertoire ?');"
                                    class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <h3
                                class="font-bold text-[#444050] text-[15px] mb-1 group-hover:text-[#7367F0] transition-colors line-clamp-2">
                                {{ $item->chant_title }}
                            </h3>

                            @php
                                $chantObj = $allChants->find($allChants->where('title', $item->chant_title)->first()->id ?? 0);
                            @endphp

                            @if($chantObj && $chantObj->composer)
                                <p class="text-[11px] text-slate-400 font-medium">
                                    Par {{ $chantObj->composer }}
                                </p>
                            @endif

                            <div class="mt-auto pt-4 flex items-center gap-2">
                                <div
                                    class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center border border-slate-100">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                    </svg>
                                </div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Musical</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="md:col-span-2 lg:col-span-3 card-material p-12 text-center bg-slate-50/50 border-dashed border-2 border-slate-200">
                        <div
                            class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                        </div>
                        <p class="text-slate-400 font-medium text-sm">Le répertoire est vide pour le moment.</p>
                        <button @click="openSongModal()"
                            class="mt-4 text-[#7367F0] font-bold text-xs uppercase tracking-widest hover:underline">
                            Commencer la composition
                        </button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Modal Selection -->
        <div x-show="songModal"
            class="fixed inset-0 z-[110] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-cloak
            x-transition>
            <div class="bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-material-lg">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-lg text-[#444050]">Composer le Répertoire</h3>
                    <button @click="songModal = false" class="text-slate-400 hover:text-red-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.events.repertoire.store', $event->id) }}" method="POST"
                    class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    @csrf

                    <!-- Left: Part Selection -->
                    <div class="space-y-4">
                        <label class="text-[12px] font-bold uppercase tracking-widest text-slate-500 ml-1">1. Choisir la
                            Partie</label>
                        <div class="space-y-2 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                            @forelse($allParties as $partie)
                                @if(!$repertoire->contains('partie_titre', $partie->titre))
                                    <label
                                        class="flex items-center gap-3 p-3 border border-slate-100 rounded-xl hover:bg-[#7367F0]/5 cursor-pointer transition-all group has-[:checked]:border-[#7367F0] has-[:checked]:bg-[#7367F0]/5">
                                        <input type="radio" name="partie_event_id" value="{{ $partie->id }}" required
                                            class="w-4 h-4 text-[#7367F0] focus:ring-[#7367F0]/20 border-slate-300">
                                        <span
                                            class="text-sm font-semibold text-[#444050] group-hover:text-[#7367F0]">{{ $partie->titre }}</span>
                                    </label>
                                @endif
                            @empty
                                <div class="text-xs text-amber-500 bg-amber-50 p-4 rounded-xl border border-amber-100">
                                    Attention: Aucune partie n'est configurée. Allez dans les réglages pour en ajouter.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Right: Song Selection -->
                    <div class="space-y-4">
                        <label class="text-[12px] font-bold uppercase tracking-widest text-slate-500 ml-1">2. Rechercher &
                            Cocher les Chants</label>
                        <div class="relative">
                            <input type="text" x-model="searchQuery" placeholder="Rechercher un chant..."
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 outline-none transition-all">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <div
                            class="max-h-60 overflow-y-auto border border-slate-100 rounded-xl divide-y divide-slate-50 custom-scrollbar pr-1">
                            @foreach($allChants as $chant)
                                @if(!$repertoire->contains('chant_title', $chant->title))
                                    <label x-show="'{{ strtolower($chant->title) }}'.includes(searchQuery.toLowerCase())"
                                        class="flex items-center gap-3 p-3 hover:bg-[#7367F0]/5 cursor-pointer transition-all group has-[:checked]:bg-[#7367F0]/10 has-[:checked]:border-[#7367F0]/20 border border-transparent rounded-xl">
                                        <input type="radio" name="chant_id" value="{{ $chant->id }}" required
                                            class="w-4 h-4 text-[#7367F0] focus:ring-[#7367F0]/20 border-slate-300">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-semibold text-[#444050] group-hover:text-[#7367F0] transition-colors">{{ $chant->title }}</span>
                                            @if($chant->composer)
                                                <span class="text-[10px] text-slate-400 font-medium">{{ $chant->composer }}</span>
                                            @endif
                                        </div>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="md:col-span-2 flex justify-end gap-3 pt-4 border-t border-slate-50">
                        <button type="button" @click="songModal = false" class="btn-secondary px-8">Annuler</button>
                        <button type="submit" class="btn-primary px-10">Valider le Répertoire</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection