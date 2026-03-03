@extends('layouts.admin')

@section('title', 'Gestion Newsletter')

@section('content')
    <div class="p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Newsletter</h1>
                <p class="text-sm text-gray-500 mt-1">Gérez vos abonnés et envoyez des communications par email.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Compose Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Envoyer un message
                        </h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.newsletter.send') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Sujet</label>
                                <input type="text" name="subject" required
                                    class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition"
                                    placeholder="Ex: Concert de Pâques 2026">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Contenu
                                    du message</label>
                                <div class="space-y-2">
                                    <div id="editor-container" class="h-64 bg-white rounded-xl border border-gray-200"></div>
                                    <textarea name="content" id="content-textarea" class="hidden">{{ old('content') }}</textarea>
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full py-3 bg-gray-900 text-white rounded-xl font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-900/10 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Diffuser à {{ $subscribers->where('is_active', true)->count() }} abonnés
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Subscriber List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Liste des abonnés
                        </h2>
                        <span
                            class="px-3 py-1 bg-amber-50 text-amber-600 text-xs font-bold rounded-full border border-amber-100">
                            {{ $subscribers->total() }} au total
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Email
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Inscrit
                                        le</th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">
                                        Statut</th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($subscribers as $sub)
                                    <tr class="hover:bg-gray-50/30 transition">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-semibold text-gray-900">{{ $sub->email }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="text-xs text-gray-500">{{ $sub->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('admin.newsletter.toggle', $sub->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $sub->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                    {{ $sub->is_active ? 'Actif' : 'Inactif' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('admin.newsletter.destroy', $sub->id) }}" method="POST"
                                                onsubmit="return confirm('Supprimer cet abonné ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $subscribers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Quill Editor Initialization
            var quill = new Quill('#editor-container', {
                theme: 'snow',
                placeholder: 'Écrivez votre message ici...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'color': [] }],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['clean']
                    ]
                }
            });

            var textarea = document.getElementById('content-textarea');

            if (textarea.value) {
                quill.root.innerHTML = textarea.value;
            }

            quill.on('text-change', function () {
                textarea.value = quill.root.innerHTML;
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Quill Editor Initialization
            var quill = new Quill('#editor-container', {
                theme: 'snow',
                placeholder: 'Écrivez votre message ici...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'color': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['clean']
                    ]
                }
            });

            var textarea = document.getElementById('content-textarea');
            
            if (textarea.value) {
                quill.root.innerHTML = textarea.value;
            }

            quill.on('text-change', function() {
                textarea.value = quill.root.innerHTML;
            });
        });
    </script>
@endsection