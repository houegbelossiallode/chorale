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
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.events.index') }}"
                    class="w-9 h-9 bg-white rounded-lg flex items-center justify-center text-slate-400 hover:text-[#7367F0] shadow-sm border border-slate-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-[#444050]">Programme : {{ $event->title }}</h1>
                    <p class="text-slate-500 text-sm font-medium">Gérez le répertoire de cet événement en choisissant les
                        parties.</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <!-- Visibility Toggle -->
                <form action="{{ route('admin.events.program.toggle-visibility', $event->id) }}" method="POST"
                    class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg border border-slate-100 shadow-sm">
                    @csrf
                    <span
                        class="text-[12px] font-bold uppercase tracking-wider {{ $event->is_repertoire_public ? 'text-[#28C76F]' : 'text-slate-400' }}">
                        {{ $event->is_repertoire_public ? 'Public' : 'Privé' }}
                    </span>
                    <button type="submit"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none {{ $event->is_repertoire_public ? 'bg-[#28C76F]' : 'bg-slate-200' }}">
                        <span
                            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $event->is_repertoire_public ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </form>

                <a href="{{ route('admin.events.repertoire.pdf', $event->id) }}"
                    class="flex items-center gap-2 px-4 py-2.5 bg-white text-slate-600 hover:text-red-600 border border-slate-200 rounded-xl font-bold text-xs uppercase tracking-widest shadow-sm transition-all">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Télécharger (PDF)
                </a>

                <button @click="openSongModal()" class="btn-primary flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Ajouter au Répertoire
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-[#DFF7E9] border border-[#28C76F]/20 text-[#28C76F] rounded-xl font-semibold text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Repertoire Table -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-material overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Partie</th>
                            <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Chant</th>
                            <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($repertoire as $item)
                            <tr class="hover:bg-slate-50/50 transition duration-300">
                                <td class="px-8 py-4">
                                    <span
                                        class="px-3 py-1 bg-[#7367F0]/10 text-[#7367F0] rounded-full text-[10px] font-bold uppercase tracking-wider">
                                        {{ $item->partie_titre ?? 'Non défini' }}
                                    </span>
                                </td>
                                <td class="px-8 py-4">
                                    <span class="font-bold text-[#444050] text-sm">{{ $item->chant_title }}</span>
                                </td>
                                <td class="px-8 py-4">
                                    <div class="flex items-center justify-end">
                                        <form action="{{ route('admin.events.repertoire.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Retirer ce chant du répertoire ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-danger">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <td colspan="3" class="px-8 py-16 text-center text-slate-400 italic text-sm">
                                    Le répertoire est vide. Cliquez sur "Ajouter au Répertoire" pour commencer.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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