@extends('layouts.public')

@section('title', 'Connexion — Chorale Saint Oscar Romero')
@section('navDark', '1')

@section('content')
    <section class="min-h-screen pt-32 pb-16 bg-warm-white relative overflow-hidden bg-pattern">
        <!-- Decorative blobs -->
        <div
            class="absolute top-40 -left-20 w-80 h-80 bg-gradient-to-br from-amber-200/30 to-yellow-200/20 rounded-full blur-3xl">
        </div>
        <div
            class="absolute bottom-20 -right-40 w-[500px] h-[500px] bg-gradient-to-bl from-purple-200/15 to-amber-200/10 rounded-full blur-3xl">
        </div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] border border-amber-300/5 rounded-full">
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center min-h-[calc(100vh-10rem)]">
                <!-- Left — Visual Panel -->
                <div class="hidden lg:block reveal-left">
                    <div class="relative">
                        <div
                            class="aspect-[4/5] rounded-[2.5rem] overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 shadow-2xl shadow-black/20 p-12 flex flex-col justify-between">
                            <!-- Floating decorative elements -->
                            <div
                                class="absolute top-10 right-10 w-20 h-20 border border-amber-500/20 rounded-full animate-spin-slow">
                            </div>
                            <div class="absolute bottom-20 left-10 w-32 h-32 border border-white/5 rounded-full animate-spin-slow"
                                style="animation-direction: reverse; animation-duration: 35s;"></div>
                            <div
                                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-40 h-40 bg-gradient-to-br from-amber-500/10 to-transparent rounded-full blur-3xl">
                            </div>

                            <!-- Logo -->
                            <div class="relative z-10">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-amber-500 via-yellow-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-xl shadow-amber-500/30 mb-8 animate-pulse-glow">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                    </svg>
                                </div>
                                <span class="text-white/40 text-xs font-bold uppercase tracking-[0.3em]">Chorale
                                    Catholique</span>
                            </div>

                            <!-- Quote -->
                            <div class="relative z-10">
                                <div class="text-6xl font-serif text-amber-500/20 leading-none mb-4">"</div>
                                <p class="text-2xl font-elegant italic text-white/70 leading-relaxed mb-6">Chanter, c'est
                                    prier deux fois.</p>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-px bg-gradient-to-r from-amber-500 to-transparent"></div>
                                    <span class="text-amber-400/60 text-xs font-bold uppercase tracking-[0.25em]">Saint
                                        Augustin</span>
                                </div>
                            </div>
                        </div>
                        <!-- Frame decoration -->
                        <div
                            class="absolute -bottom-4 -right-4 w-full h-full rounded-[2.5rem] border-2 border-amber-300/15 -z-10">
                        </div>
                    </div>
                </div>

                <!-- Right — Form -->
                <div class="reveal-right">
                    <div class="max-w-md mx-auto">
                        <div class="text-center mb-10">
                            <!-- Mobile logo -->
                            <div class="lg:hidden mb-8">
                                <div
                                    class="w-16 h-16 mx-auto bg-gradient-to-br from-amber-500 via-yellow-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-xl shadow-amber-500/20 mb-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                    </svg>
                                </div>
                            </div>
                            <h1 class="text-4xl font-serif font-bold text-gray-900 mb-3">Bon retour ✦</h1>
                            <p class="text-gray-400 text-sm">Connectez-vous pour accéder à votre espace membre</p>
                        </div>

                        <form id="loginForm" x-data="{ loading: false }" @submit.prevent="handleLogin($el, $data)"
                            class="space-y-5">
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-[0.15em]">Adresse
                                    Email</label>
                                <div class="relative group">
                                    <input type="email" name="email" required placeholder="votre@email.com"
                                        class="w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-amber-400 outline-none transition-all duration-300 text-sm font-medium placeholder:text-gray-300 group-hover:border-gray-200">
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-[0.15em]">Mot de
                                        passe</label>
                                    <a href="/password/reset"
                                        class="text-xs font-bold text-amber-600 hover:text-amber-700 transition">Oublié
                                        ?</a>
                                </div>
                                <div class="relative group">
                                    <input type="password" name="password" required placeholder="••••••••"
                                        class="w-full px-5 py-4 bg-white border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-amber-400 outline-none transition-all duration-300 text-sm font-medium placeholder:text-gray-300 group-hover:border-gray-200">
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div id="error"
                                class="hidden p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-medium">
                            </div>

                            <button type="submit" :disabled="loading"
                                class="relative w-full py-5 bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-600 text-white rounded-2xl font-bold text-base overflow-hidden hover:shadow-xl hover:shadow-amber-500/25 transition-all duration-500 hover:scale-[1.02] group disabled:opacity-70 disabled:cursor-not-allowed">
                                <span class="relative z-10 flex items-center justify-center gap-2" x-show="!loading">
                                    Se connecter
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>
                                <span class="relative z-10 flex items-center justify-center gap-2" x-show="loading" x-cloak>
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Connexion en cours...
                                </span>
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-amber-600 via-yellow-600 to-amber-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>
                            </button>
                        </form>

                        <div class="mt-8 text-center">
                            <p class="text-gray-400 text-sm">Pas encore membre ? <a href="/register"
                                    class="text-amber-600 font-bold hover:text-amber-700 transition">Créer un compte</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        async function handleLogin(form, alpine) {
            alpine.loading = true;
            const formData = new FormData(form);
            const email = formData.get('email');
            const password = formData.get('password');
            const errorDiv = document.getElementById('error');
            errorDiv.classList.add('hidden');

            try {
                const supabaseUrl = "{{ env('SUPABASE_URL') }}";
                const supabaseKey = "{{ env('SUPABASE_ANON_KEY') }}";
                if (!supabaseUrl || !supabaseKey) { throw new Error('Configuration manquante'); }
                const client = supabase.createClient(supabaseUrl, supabaseKey);

                const { data, error } = await client.auth.signInWithPassword({ email, password });
                if (error) throw error;

                const response = await fetch('/api/supabase-login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ access_token: data.session.access_token })
                });

                let result;
                try {
                    result = await response.json();
                } catch (jsonErr) {
                    const text = await response.text();
                    console.error('Erreur de parsing JSON. Réponse reçue :', text);
                    if (text.includes('<section')) {
                        throw new Error('Le serveur a renvoyé une page HTML au lieu de JSON. Une redirection inattendue a probablement eu lieu.');
                    }
                    throw new Error('Erreur de format de réponse serveur.');
                }

                if (response.ok) {
                    window.location.href = result.redirect || '/';
                } else {
                    throw new Error(result.message || 'Erreur serveur.');
                }
            } catch (err) {
                console.error('Login error:', err);
                errorDiv.textContent = err.message || 'Identifiants incorrects.';
                errorDiv.classList.remove('hidden');
                alpine.loading = false;
            }
        }
    </script>
@endpush