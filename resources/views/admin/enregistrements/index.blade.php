@extends('layouts.admin')

@section('title', 'Gestion des Enregistrements')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#444050]">Enregistrements des Choristes</h1>
                <p class="text-slate-500 text-sm">Écoutez les prestations et donnez votre feedback aux choristes.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-material overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Choriste</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Chant</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Audio</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Feedback</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($enregistrements as $rec)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">
                                    {{ $rec->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-[#7367F0]/10 flex items-center justify-center text-[#7367F0] font-bold text-xs">
                                            {{ $rec->user->first_name }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-700">{{ $rec->user->name }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">
                                                {{ $rec->user->pupitre ? $rec->user->pupitre->name : 'Sans pupitre' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-slate-600">{{ $rec->chant->title }}</p>
                                </td>
                                <td class="px-6 py-4 min-w-[200px]">
                                    <audio controls class="w-48 h-8 rounded-lg">
                                        <source src="{{ $rec->file_path }}" type="audio/mpeg">
                                    </audio>
                                </td>
                                <td class="px-6 py-4">
                                    @if($rec->chef_comment)
                                        <div class="group relative">
                                            <p class="text-xs text-slate-600 font-medium italic line-clamp-2 italic">
                                                {{ $rec->chef_comment }}</p>
                                            <button
                                                onclick="openFeedbackModal({{ $rec->id }}, '{{ addslashes($rec->chef_comment) }}')"
                                                class="text-[10px] text-[#7367F0] font-bold uppercase mt-1 hover:underline">Modifier</button>
                                        </div>
                                    @else
                                        <button onclick="openFeedbackModal({{ $rec->id }}, '')"
                                            class="px-3 py-1.5 bg-[#7367F0]/10 text-[#7367F0] rounded-lg text-xs font-bold hover:bg-[#7367F0]/20 transition-all">
                                            Ajouter Feedback
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic">
                                    Aucun enregistrement trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL FEEDBACK --}}
    <div id="feedbackModal"
        class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm px-4">
        <div class="bg-white rounded-2xl shadow-material-lg w-full max-w-md overflow-hidden animate-fade-in">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-700">Feedback du Chef</h3>
                <button onclick="closeFeedbackModal()" class="text-slate-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="feedbackForm" action="" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Votre
                        Commentaire</label>
                    <textarea name="chef_comment" id="chefCommentField" rows="5" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#7367F0] focus:ring-2 focus:ring-[#7367F0]/20 outline-none transition-all text-sm font-medium"
                        placeholder="Conseils, corrections, encouragements..."></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeFeedbackModal()"
                        class="flex-1 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all">Annuler</button>
                    <button type="submit"
                        class="flex-1 py-3 bg-[#7367F0] text-white font-bold rounded-xl hover:bg-[#685dd8] shadow-md transition-all">Enregistrer</button>
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

        // Fermer au clic extérieur
        window.onclick = function (event) {
            const modal = document.getElementById('feedbackModal');
            if (event.target == modal) {
                closeFeedbackModal();
            }
        }
    </script>
@endsection