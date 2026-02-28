@extends('layouts.public')

@section('title', 'Merci pour votre don !')

@section('content')
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-warm-white">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="max-w-2xl mx-auto px-4 text-center relative z-10">
            <div
                class="w-24 h-24 mx-auto bg-green-100 rounded-3xl flex items-center justify-center mb-8 shadow-xl shadow-green-900/5 animate-bounce">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-4xl md:text-6xl font-serif text-gray-900 mb-6">Merci infiniment !</h1>
            <p class="text-xl text-gray-500 mb-10 leading-relaxed max-w-lg mx-auto">
                Votre don de <span class="text-amber-600 font-extrabold text-3xl">{{ number_format($amount, 0, ',', ' ') }}
                    FCFA</span> a été reçu avec succès.
            </p>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-xl shadow-black/5 mb-12 inline-block">
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Référence Transaction</p>
                <code
                    class="text-sm font-mono text-slate-700 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">{{ $transactionId }}</code>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('home') }}"
                    class="px-8 py-4 bg-gray-900 text-white rounded-2xl font-bold hover:bg-gray-800 transition-all shadow-xl shadow-black/10">
                    Retour à l'accueil </a>
                <p class="text-xs text-slate-400 max-w-[200px]">Votre soutien est précieux pour notre mission.</p>
            </div>
        </div>
    </section>
@endsection