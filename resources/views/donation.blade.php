@extends('layouts.public')

@push('scripts')
    <script src="https://cdn.kkiapay.me/k.js"></script>
@endpush

@section('title', 'Faire un Don — Chorale Saint Oscar Romero')

@section('content')
    <!-- Hero -->
    <section class="relative min-h-[70vh] flex items-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-700 via-amber-800 to-gray-900"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1542621334-a25bb769435b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center bg-fixed"></div>
        <div class="absolute inset-0 bg-pattern opacity-15"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[280px] h-[280px] sm:w-[400px] sm:h-[400px] border border-amber-400/10 rounded-full animate-spin-slow"></div>

        <!-- Floating particles -->
        <div class="absolute top-20 left-[20%] w-2 h-2 bg-amber-400/30 rounded-full animate-float"></div>
        <div class="absolute bottom-40 right-[25%] w-1.5 h-1.5 bg-white/20 rounded-full animate-float-slow" style="animation-delay: 1s;"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center pt-32 sm:pt-40 pb-24 sm:pb-32">
            <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full glass mb-10">
                <svg class="w-4 h-4 text-amber-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                <span class="text-amber-300/90 text-xs font-bold tracking-[0.4em] uppercase">Soutenez-nous</span>
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-8xl font-serif text-white mb-6 leading-[0.9]">Votre<br><span class="animate-shimmer text-transparent">Générosité</span></h1>
            <!-- <p class="text-lg text-white/40 max-w-md mx-auto px-4">Chaque don contribue à faire vivre notre mission sacrée</p> -->
        </div>
    </section>

    <!-- Impact Section -->
    <section class="relative -mt-12 sm:-mt-16 z-30 px-4 pb-8">
        <div class="max-w-5xl mx-auto">
            <div class="grid md:grid-cols-3 gap-6">
                @foreach([
                    ['icon' => 'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3', 'title' => 'Partitions & Instruments', 'desc' => 'Acquisition de nouvelles partitions liturgiques et entretien de nos instruments pour enrichir notre répertoire.'],
                    ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'title' => 'Formation Vocale', 'desc' => 'Sessions de formation et retraites pour développer les talents de nos choristes.'],
                    ['icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Concerts & Events', 'desc' => 'Organisation de concerts paroissiaux et déplacements pour les événements diocésains.']
                ] as $i => $item)
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-black/5 card-premium reveal" style="transition-delay: {{ $i * 100 }}ms;">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-700 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-amber-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/></svg>
                    </div>
                    <h3 class="text-xl font-serif font-bold text-gray-900 mb-3">{{ $item['title'] }}</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">{{ $item['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Donation Form -->
    <section class="py-24 sm:py-32 bg-warm-white relative bg-pattern">
        <div class="absolute top-20 right-0 w-96 h-96 bg-gradient-to-bl from-amber-100/30 to-transparent rounded-full blur-3xl"></div>

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 reveal">
            <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] p-6 sm:p-10 md:p-14 border border-gray-100 shadow-2xl shadow-amber-900/5">
                <div class="text-center mb-12">
                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-amber-500 to-amber-700 rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-amber-500/20 animate-float-slow">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </div>
                    <h2 class="text-3xl font-serif font-bold text-gray-900 mb-3">Choisissez votre don</h2>
                    <p class="text-gray-400 text-sm">Chaque euro compte pour notre mission sacrée</p>
                </div>

                <div x-data="{ 
                    amount: 5000, 
                    custom: false, 
                    recurring: false,
                    firstName: '',
                    lastName: '',
                    email: '',
                    phone: '',
                    
                    pay() {
                        const finalAmount = parseInt(this.amount);
                        console.log('Tentative de paiement:', finalAmount, this.email);
                        
                        if (isNaN(finalAmount) || finalAmount <= 0) {
                            alert('Veuillez choisir un montant valide.');
                            return;
                        }
                        if (!this.email || !this.lastName) {
                            alert('Veuillez remplir votre nom et email.');
                            return;
                        }

                        if (typeof openKkiapayWidget !== 'function') {
                            alert('Le système de paiement n\'est pas encore chargé. Veuillez patienter ou rafraîchir la page.');
                            console.error('openKkiapayWidget is not defined');
                            return;
                        }

                        openKkiapayWidget({
                            amount: finalAmount,
                            position: 'center',
                            callback: '{{ route('donation.success') }}',
                            data: JSON.stringify({
                                first_name: this.firstName,
                                last_name: this.lastName,
                                email: this.email,
                                phone: this.phone,
                                amount: finalAmount
                            }),
                            key: '{{ config('services.kkiapay.public_key') }}',
                            sandbox: true
                        });
                    }
                }">
                    <!-- Amount Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-8">
                        @foreach([10, 25, 50, 100, 200] as $val)
                        <button @click="amount = {{ $val * 500 }}; custom = false" :class="amount === {{ $val * 500 }} && !custom ? 'bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-600 text-white shadow-lg shadow-amber-500/20 border-transparent scale-105' : 'bg-gray-50 text-gray-700 border-gray-100 hover:border-amber-300 hover:bg-amber-50'" class="relative py-4 sm:py-5 rounded-2xl font-extrabold text-sm sm:text-lg border-2 transition-all duration-300">
                            {{ number_format($val, 0, ',', ' ') }}
                        </button>
                        @endforeach
                        <button @click="custom = true; amount = 0" :class="custom ? 'bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-600 text-white shadow-lg shadow-amber-500/20 border-transparent scale-105' : 'bg-gray-50 text-gray-700 border-gray-100 hover:border-amber-300'" class="py-4 sm:py-5 rounded-2xl font-bold text-sm sm:text-lg border-2 transition-all duration-300">Autre</button>
                    </div>

                    <!-- Currency Label (FCFA) -->
                    <!-- <div class="text-center mb-4">
                        <span class="text-xs font-black text-amber-600 uppercase tracking-widest">Paiement en FCFA</span>
                    </div> -->

                    <!-- Custom Input -->
                    <div x-show="custom" x-transition class="mb-8">
                        <input type="number" x-model.number="amount" placeholder="Montant" class="w-full px-6 py-5 bg-gray-50 border-2 border-gray-100 rounded-2xl text-center text-3xl font-extrabold text-gray-900 focus:ring-0 focus:border-amber-400 outline-none transition-all">
                    </div>

                    <!-- Frequency -->
                    <!-- <div class="flex items-center justify-center gap-2 sm:gap-4 p-2 sm:p-3 bg-gray-50 rounded-2xl mb-10">
                        <button @click="recurring = false" :class="!recurring ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-400'" class="flex-1 px-3 sm:px-6 py-3 rounded-xl text-xs sm:text-sm transition-all text-center">Don Unique</button>
                        <button @click="recurring = true" :class="recurring ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-400'" class="flex-1 px-3 sm:px-6 py-3 rounded-xl text-xs sm:text-sm transition-all text-center">Don Mensuel</button>
                    </div> -->

                    <!-- Donor Info -->
                    <div class="space-y-4 mb-10">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-[0.15em]">Prénom</label>
                                <input type="text" x-model="firstName" placeholder="Jean" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-amber-400 outline-none transition text-sm font-medium placeholder:text-gray-300">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-[0.15em]">Nom</label>
                                <input type="text" x-model="lastName" placeholder="Dupont" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-amber-400 outline-none transition text-sm font-medium placeholder:text-gray-300">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-[0.15em]">Email</label>
                                <input type="email" x-model="email" placeholder="jean@exemple.com" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-amber-400 outline-none transition text-sm font-medium placeholder:text-gray-300">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-[0.15em]">Téléphone</label>
                                <input type="tel" x-model="phone" placeholder="67000000" class="w-full px-5 py-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-amber-400 outline-none transition text-sm font-medium placeholder:text-gray-300">
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button @click="pay()" class="relative w-full py-6 bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-600 text-white rounded-2xl font-extrabold text-xl overflow-hidden hover:shadow-2xl hover:shadow-amber-500/30 transition-all duration-500 hover:scale-[1.02] group">
                        <span class="relative z-10 flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            Donner <span x-text="amount"></span> FCFA
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-600 via-yellow-600 to-amber-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </button>
                    <p class="text-center text-gray-300 text-xs mt-4 font-medium">🔒 Paiement sécurisé • Reçu fiscal sur demande</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Section -->
    <section class="py-24 bg-white relative">
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-amber-400/40 to-transparent"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 reveal">
                @foreach([
                    ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Sécurisé', 'desc' => 'Paiement 100% sécurisé avec les meilleurs standards'],
                    ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Reçu fiscal', 'desc' => 'Reçu fiscal disponible sur demande pour votre déclaration'],
                    ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'title' => '100% reversé', 'desc' => 'L\'intégralité de vos dons finance directement la chorale']
                ] as $item)
                <div class="text-center p-8">
                    <div class="w-14 h-14 mx-auto bg-amber-50 rounded-2xl flex items-center justify-center mb-4 border border-amber-100">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/></svg>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">{{ $item['title'] }}</h4>
                    <p class="text-gray-400 text-sm">{{ $item['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Quote -->
    <section class="py-24 bg-dark-purple relative overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-20"></div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 reveal">
            <div class="text-amber-500/20 text-8xl font-serif leading-none mb-4">"</div>
            <p class="text-2xl md:text-3xl font-elegant italic text-white/60 leading-relaxed mb-8">Nous ne pouvons pas tous faire de grandes choses, mais nous pouvons faire de petites choses avec un grand amour.</p>
            <div class="flex items-center justify-center gap-4">
                <div class="w-8 h-px bg-amber-500/50"></div>
                <span class="text-amber-400/60 text-xs font-bold uppercase tracking-[0.3em]">Sainte Thérèse de Calcutta</span>
                <div class="w-8 h-px bg-amber-500/50"></div>
            </div>
        </div>
    </section>
@endsection
