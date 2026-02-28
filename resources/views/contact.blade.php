@extends('layouts.public')

@section('title', 'Contactez-nous — Chorale Saint Oscar Romero')

@section('content')
    <!-- Hero -->
    <section class="relative min-h-[50vh] flex items-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1516280440614-37939bbacd81?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center bg-fixed"></div>
        <div class="absolute inset-0 bg-pattern opacity-15"></div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center pt-24 md:pt-20">
            <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full glass mb-10">
                <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span class="text-amber-300/90 text-xs font-bold tracking-[0.4em] uppercase">Unir nos voix</span>
            </div>
            <h1 class="text-5xl md:text-8xl font-serif text-white mb-6 leading-[0.9]">Parlons<br><span class="animate-shimmer text-transparent">Ensemble</span></h1>
            <p class="text-lg text-white/40 max-w-md mx-auto px-4">Une question, un partage ou une invitation ? Nous sommes à votre écoute.</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-24 bg-warm-white relative bg-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-12 gap-16">
                <!-- Info Column -->
                <div class="lg:col-span-5 reveal">
                    <h2 class="text-4xl md:text-5xl font-serif text-gray-900 mb-8 leading-tight">Nos<br><span class="text-gradient-gold">Coordonnées</span></h2>
                    
                    <div class="space-y-10">
                        <!-- Item -->
                        <div class="flex gap-6 group">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-xl shadow-amber-900/5 border border-gray-100 group-hover:bg-amber-500 group-hover:text-white transition-all duration-500 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-1">Localisation</h4>
                                <p class="text-gray-900 font-medium">Chemins des visiteurs<br>Villeneuve d'Asqc , 59650</p>
                            </div>
                        </div>

                        <!-- Item -->
                        <div class="flex gap-6 group">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-xl shadow-amber-900/5 border border-gray-100 group-hover:bg-amber-500 group-hover:text-white transition-all duration-500 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-1">Email</h4>
                                <p class="text-gray-900 font-medium">contact@choralesaintoscar.com</p>
                            </div>
                        </div>

                        <!-- Item -->
                        <div class="flex gap-6 group">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-xl shadow-amber-900/5 border border-gray-100 group-hover:bg-amber-500 group-hover:text-white transition-all duration-500 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-1">Téléphone</h4>
                                <p class="text-gray-900 font-medium">+33 6 64 73 81 75</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-16 p-8 glass-gold rounded-3xl border border-amber-100">
                        <p class="text-gray-600 italic leading-relaxed">
                            "Si on m'élimine, je ressusciterai dans le peuple salvadorien." — <span class="text-amber-600 font-bold">Saint Oscar Romero</span>
                        </p>
                    </div>
                </div>

                <!-- Form Column -->
                <div class="lg:col-span-7 reveal">
                    <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-2xl shadow-amber-900/5 border border-gray-100">
                        <h3 class="text-3xl font-serif font-bold text-gray-900 mb-8">Envoyez-nous un message</h3>
                        
                        @if(session('success'))
                            <div class="mb-8 p-4 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest">Votre Nom</label>
                                    <input type="text" name="name" required class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-amber-400 focus:ring-0 outline-none transition-all placeholder:text-gray-300" placeholder="Jean Dupont">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest">Votre Email</label>
                                    <input type="email" name="email" required class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-amber-400 focus:ring-0 outline-none transition-all placeholder:text-gray-300" placeholder="jean@exemple.com">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest">Sujet</label>
                                <input type="text" name="subject" class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-amber-400 focus:ring-0 outline-none transition-all placeholder:text-gray-300" placeholder="Comment pouvons-nous vous aider ?">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest">Votre Message</label>
                                <textarea name="message" rows="5" required class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-amber-400 focus:ring-0 outline-none transition-all placeholder:text-gray-300" placeholder="Décrivez votre demande en quelques mots..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-6 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-2xl font-extrabold text-lg shadow-xl shadow-amber-500/20 hover:scale-[1.02] active:scale-95 transition-all duration-300">
                                Envoyer le Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="h-[400px] w-full bg-gray-200 overflow-hidden relative grayscale hover:grayscale-0 transition-all duration-1000">
        <!-- Placeholder for actual map integration -->
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center"></div>
        <div class="absolute inset-0 bg-blue-900/10"></div>
    </section>
@endsection
