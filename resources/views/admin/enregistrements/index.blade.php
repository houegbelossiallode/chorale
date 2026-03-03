@extends('layouts.admin')

@section('title', 'Gestion des Enregistrements')

@section('content')
    <div class="w-full space-y-8" x-data="{ activeEvent: null }">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#444050]">Suivi des Enregistrements</h1>
                <p class="text-slate-500 text-sm">Organisé par événement pour un meilleur suivi des prestations.</p>
            </div>
        </div>

        <!-- Liste des Événements -->
        <div class="space-y-4">
            @forelse($events as $event)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden transition-all duration-300">
                    <!-- Header de l'Événement -->
                    <button @click="activeEvent = (activeEvent === {{ $event->id }} ? null : {{ $event->id }})"
                        class="w-full px-8 py-6 flex items-center justify-between hover:bg-slate-50 transition-colors text-left">
                        <div class="flex items-center gap-6">
                            <div
                                class="w-14 h-14 rounded-2xl bg-[#7367F0]/10 flex flex-col items-center justify-center text-[#7367F0]">
                                <span class="text-lg font-bold">{{ $event->start_at->format('d') }}</span>
                                <span
                                    class="text-[10px] uppercase font-bold tracking-tighter">{{ $event->start_at->translatedFormat('M') }}</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-[#444050]">{{ $event->title }}</h3>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-xs font-medium text-slate-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $event->location ?? 'Lieu non défini' }}
                                    </span>
                                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                    <span class="text-xs font-bold text-[#7367F0]">
                                        {{ $event->repertoireEntries->sum(fn($r) => $r->enregistrements->count()) }}
                                        enregistrements
                                    </span>
                                </div>
                            </div>
                        </div>
                        <svg class="w-6 h-6 text-slate-300 transition-transform duration-300"
                            :class="activeEvent === {{ $event->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Liste des Enregistrements pour cet Événement -->
                    <div x-show="activeEvent === {{ $event->id }}" x-collapse x-cloak class="border-t border-slate-50">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50/50">
                                    <tr>
                                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            Choriste</th>
                                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            Chant / Partie</th>
                                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            Audio</th>
                                        <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                            Appréciation</th>
                                        <th
                                            class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">
                                            Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($event->repertoireEntries as $item)
                                        @foreach($item->enregistrements as $rec)
                                            <tr class="hover:bg-slate-50/30 transition-colors group">
                                                <td class="px-8 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div
                                                            class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-[10px]">
                                                            {{ substr($rec->user->first_name, 0, 1) }}{{ substr($rec->user->last_name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-bold text-slate-700 leading-tight">
                                                                {{ $rec->user->name }}
                                                            </p>
                                                            <p class="text-[10px] font-bold text-[#7367F0] uppercase tracking-tight">
                                                                {{ $rec->user->pupitre ? $rec->user->pupitre->name : 'Sans pupitre' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-8 py-4">
                                                    <p class="text-sm font-bold text-slate-700 leading-tight">{{ $item->chant->title }}
                                                    </p>
                                                    <p class="text-[10px] font-medium text-slate-400">{{ $item->partieEvent->titre }}
                                                    </p>
                                                </td>
                                                <td class="px-8 py-4">
                                                    <audio controls class="h-8 w-40 opacity-70 hover:opacity-100 transition-opacity">
                                                        <source src="{{ asset('storage/' . $rec->file_path) }}" type="audio/mpeg">
                                                    </audio>
                                                </td>
                                                <td class="px-8 py-4">
                                                    @if($rec->chef_comment)
                                                        <div class="max-w-[200px]">
                                                            <p class="text-xs text-slate-500 italic line-clamp-1 group-hover:line-clamp-none transition-all cursor-help"
                                                                title="{{ $rec->chef_comment }}">
                                                                "{{ $rec->chef_comment }}"
                                                            </p>
                                                            <button
                                                                onclick="openFeedbackModal({{ $rec->id }}, '{{ addslashes($rec->chef_comment) }}')"
                                                                class="text-[10px] text-[#7367F0] font-bold uppercase mt-1 opacity-0 group-hover:opacity-100 transition-opacity hover:underline">
                                                                Modifier
                                                            </button>
                                                        </div>
                                                    @else
                                                        <button onclick="openFeedbackModal({{ $rec->id }}, '')"
                                                            class="px-3 py-1.5 bg-[#7367F0]/5 text-[#7367F0] border border-[#7367F0]/10 rounded-lg text-xs font-bold hover:bg-[#7367F0] hover:text-white transition-all">
                                                            Apprécier
                                                        </button>
                                                    @endif
                                                </td>
                                                <td class="px-8 py-4 text-right">
                                                    <span class="text-[10px] font-medium text-slate-400 italic">
                                                        {{ $rec->created_at->diffForHumans() }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[2rem] p-20 text-center border border-dashed border-slate-200">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">Aucun enregistrement par événement</h3>
                    <p class="text-slate-400 text-sm">Les nouveaux enregistrements apparaîtront ici triés par événement.</p>
                </div>
            @endforelse
        </div>

        @if($orphanEnregistrements->count() > 0)
            <div class="pt-8 mb-12">
                <div class="flex items-center gap-4 mb-6">
                    <h2 class="text-lg font-bold text-slate-700">Enregistrements sans Événement</h2>
                    <div class="h-px flex-1 bg-slate-100"></div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50/50">
                                <tr>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Choriste</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Chant</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Audio</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Appréciation</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($orphanEnregistrements as $rec)
                                    <tr class="hover:bg-slate-50/30 transition-colors group">
                                        <td class="px-8 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-[10px]">
                                                    {{ substr($rec->user->first_name ?? 'U', 0, 1) }}{{ substr($rec->user->last_name ?? 'S', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-700 leading-tight">{{ $rec->user->name }}</p>
                                                    <p class="text-[10px] font-bold text-[#7367F0] uppercase tracking-tight">
                                                        {{ $rec->user->pupitre ? $rec->user->pupitre->name : 'Sans pupitre' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4 text-sm font-bold text-slate-700">{{ $rec->chant->title }}</td>
                                        <td class="px-8 py-4">
                                            <audio controls class="h-8 w-40 opacity-70 hover:opacity-100 transition-opacity">
                                                <source src="{{ asset('storage/' . $rec->file_path) }}" type="audio/mpeg">
                                            </audio>
                                        </td>
                                        <td class="px-8 py-4">
                                            @if($rec->chef_comment)
                                                <div class="max-w-[200px]">
                                                    <p class="text-xs text-slate-500 italic line-clamp-1 group-hover:line-clamp-none transition-all cursor-help" title="{{ $rec->chef_comment }}">
                                                        "{{ $rec->chef_comment }}"
                                                    </p>
                                                    <button onclick="openFeedbackModal({{ $rec->id }}, '{{ addslashes($rec->chef_comment) }}')"
                                                            class="text-[10px] text-[#7367F0] font-bold uppercase mt-1 opacity-0 group-hover:opacity-100 transition-opacity hover:underline">
                                                        Modifier
                                                    </button>
                                                </div>
                                            @else
                                                <button onclick="openFeedbackModal({{ $rec->id }}, '')"
                                                        class="px-3 py-1.5 bg-[#7367F0]/5 text-[#7367F0] border border-[#7367F0]/10 rounded-lg text-xs font-bold hover:bg-[#7367F0] hover:text-white transition-all">
                                                    Apprécier
                                                </button>
                                            @endif
                                        </td>
                                        <td class="px-8 py-4 text-right">
                                            <span class="text-[10px] font-medium text-slate-400 italic">
                                                {{ $rec->created_at->diffForHumans() }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- MODAL FEEDBACK (Identique mais avec style un peu plus fin) --}}
    <div id="feedbackModal"
        class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm px-4">
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden animate-fade-in">
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-700">Appréciation du Chef</h3>
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mt-0.5">Feedback Personnel</p>
                </div>
                <button onclick="closeFeedbackModal()"
                    class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="feedbackForm" action="" method="POST" class="p-8 space-y-6">
                @csrf
                <div>
                    <label
                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-3 text-center">Votre
                        Commentaire</label>
                    <textarea name="chef_comment" id="chefCommentField" rows="4" required
                        class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-[#7367F0] focus:ring-4 focus:ring-[#7367F0]/10 outline-none transition-all text-sm font-medium placeholder:text-slate-300"
                        placeholder="Qu'avez-vous pensé de cette interprétation ?"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 py-4 bg-[#7367F0] text-white font-bold rounded-2xl hover:bg-[#685dd8] shadow-lg shadow-[#7367F0]/20 transition-all active:scale-95">
                        Publier l'appréciation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openFeedbackModal(recId, currentComment) {
            const modal = document.getElementById('feedbackModal');
            const form = document.getElementById('feedbackForm');
            const field = document.getElementById('chefCommentField');

            form.action = `/admin/enregistrements/${recId}/feedback`;
            field.value = currentComment;
            modal.classList.remove('hidden');
        }

        function closeFeedbackModal() {
            document.getElementById('feedbackModal').classList.add('hidden');
        }

        window.onclick = function (event) {
            const modal = document.getElementById('feedbackModal');
            if (event.target == modal) closeFeedbackModal();
        }
    </script>
@endsection