@extends('layouts.public')

@section('title', 'Inscription — Chorale Saint Oscar Romero')
@section('navDark', '1')

@section('content')
    <section class="min-h-screen pt-32 pb-16 bg-warm-white relative overflow-hidden bg-pattern">
        <div class="absolute top-40 -right-20 w-80 h-80 bg-gradient-to-bl from-amber-200/30 to-yellow-200/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 -left-40 w-[500px] h-[500px] bg-gradient-to-tr from-purple-200/15 to-amber-200/10 rounded-full blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center min-h-[calc(100vh-10rem)]">
                <!-- Left — Form -->
                <div class="order-2 lg:order-1 reveal-left">
                    <div class="max-w-md mx-auto">
                        <div class="text-center mb-10">
                            <div class="lg:hidden mb-8">
                                <div class="w-16 h-16 mx-auto bg-gradient-to-br from-amber-500 via-yellow-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-xl shadow-amber-500/20 mb-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                                </div>
                            </div>
                            <h1 class="text-4xl font-serif font-bold text-gray-900 mb-3">Rejoignez-nous ✦</h1>
                            <p class="text-gray-400 text-sm">Créez votre compte et rejoignez la famille</p>
                        </div>

                        <form id="registerForm" onsubmit="handleRegister(event)" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-[0.15em]">Prénom</label>
                                    <input type="text" id="first_name" required placeholder="Jean"
                                           class="w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-amber-400 outline-none transition-all text-sm font-medium placeholder:text-gray-300">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-[0.15em]">Nom</label>
                                    <input type="text" id="last_name" required placeholder="Dupont"
                                           class="w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-amber-400 outline-none transition-all text-sm font-medium placeholder:text-gray-300">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-[0.15em]">Email</label>
                                <input type="email" id="email" required placeholder="votre@email.com"
                                       class="w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-amber-400 outline-none transition-all text-sm font-medium placeholder:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-[0.15em]">Mot de passe</label>
                                <input type="password" id="password" required placeholder="••••••••"
                                       class="w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-amber-400 outline-none transition-all text-sm font-medium placeholder:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-[0.15em]">Confirmer le mot de passe</label>
                                <input type="password" id="password_confirm" required placeholder="••••••••"
                                       class="w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-amber-400 outline-none transition-all text-sm font-medium placeholder:text-gray-300">
                            </div>

                            <div id="error" class="hidden p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-medium"></div>

                            <button type="submit" class="relative w-full py-5 bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-600 text-white rounded-2xl font-bold text-base overflow-hidden hover:shadow-xl hover:shadow-amber-500/25 transition-all duration-500 hover:scale-[1.02] group mt-2">
                                <span class="relative z-10 flex items-center justify-center gap-2">
                                    Créer mon compte
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </span>
                                <div class="absolute inset-0 bg-gradient-to-r from-amber-600 via-yellow-600 to-amber-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            </button>
                        </form>

                        <div class="mt-8 text-center">
                            <p class="text-gray-400 text-sm">Déjà membre ? <a href="/login" class="text-amber-600 font-bold hover:text-amber-700 transition">Se connecter</a></p>
                        </div>
                    </div>
                </div>

                <!-- Right — Visual -->
                <div class="hidden lg:block order-1 lg:order-2 reveal-right">
                    <div class="relative">
                        <div class="aspect-[4/5] rounded-[2.5rem] overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 shadow-2xl shadow-black/20 p-12 flex flex-col justify-between">
                            <div class="absolute top-10 left-10 w-20 h-20 border border-amber-500/20 rounded-full animate-spin-slow"></div>
                            <div class="absolute bottom-20 right-10 w-32 h-32 border border-white/5 rounded-full animate-spin-slow" style="animation-direction: reverse;"></div>
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-40 h-40 bg-gradient-to-br from-amber-500/10 to-transparent rounded-full blur-3xl"></div>

                            <div class="relative z-10">
                                <div class="w-16 h-16 bg-gradient-to-br from-amber-500 via-yellow-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-xl shadow-amber-500/30 mb-8 animate-pulse-glow">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                                </div>
                                <span class="text-white/40 text-xs font-bold uppercase tracking-[0.3em]">Chorale Catholique</span>
                            </div>

                            <div class="relative z-10">
                                <div class="text-6xl font-serif text-amber-500/20 leading-none mb-4">"</div>
                                <p class="text-2xl font-elegant italic text-white/70 leading-relaxed mb-6">Si on m'élimine, je ressusciterai dans le peuple salvadorien.</p>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-px bg-gradient-to-r from-amber-500 to-transparent"></div>
                                    <span class="text-amber-400/60 text-xs font-bold uppercase tracking-[0.25em]">Saint Oscar Romero</span>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -bottom-4 -left-4 w-full h-full rounded-[2.5rem] border-2 border-amber-300/15 -z-10"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    async function handleRegister(e) {
        e.preventDefault();
        const first_name = document.getElementById('first_name').value;
        const last_name = document.getElementById('last_name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const password_confirm = document.getElementById('password_confirm').value;
        const errorDiv = document.getElementById('error');
        errorDiv.classList.add('hidden');

        if (password !== password_confirm) {
            errorDiv.textContent = 'Les mots de passe ne correspondent pas.';
            errorDiv.classList.remove('hidden');
            return;
        }

        try {
            const supabaseUrl = "{{ env('SUPABASE_URL') }}";
            const supabaseKey = "{{ env('SUPABASE_ANON_KEY') }}";
            if (!supabaseUrl || !supabaseKey) throw new Error('Configuration manquante');
            const client = supabase.createClient(supabaseUrl, supabaseKey);
            const { data, error } = await client.auth.signUp({
                email, password,
                options: { data: { first_name, last_name } }
            });
            if (error) throw error;

            const response = await fetch('/api/supabase-register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    access_token: data.session?.access_token,
                    first_name, last_name, email
                })
            });

            if (response.ok) { window.location.href = '/'; }
            else { throw new Error('Erreur lors de la création du profil.'); }
        } catch (err) {
            errorDiv.textContent = err.message || 'Erreur lors de l\'inscription.';
            errorDiv.classList.remove('hidden');
        }
    }
</script>
@endpush
