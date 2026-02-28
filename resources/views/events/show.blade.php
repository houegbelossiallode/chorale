@extends('layouts.public')

@section('title', $event->title . ' — Chorale Saint Oscar Romero')

@section('content')
    <!-- Hero Header -->
    <section class="relative min-h-[40vh] flex items-end overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-800 via-amber-900 to-gray-900"></div>
        <div class="absolute inset-0 bg-pattern opacity-15"></div>

        <div class="absolute top-1/2 right-10 w-60 h-60 border border-amber-500/10 rounded-full animate-spin-slow"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-24 w-full">
            <nav class="mb-10">
                <a href="{{ route('events') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl glass text-white/60 hover:text-amber-300 transition text-sm font-medium group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour à l'agenda
                </a>
            </nav>
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl glass text-amber-300 text-[11px] font-bold uppercase tracking-wider mb-6">
                <div class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></div>
                {{ $event->type->libelle ?? 'Événement' }}
            </div>
            <h1 class="text-3xl sm:text-5xl md:text-6xl font-serif text-white leading-[1.1] mb-8 break-words max-w-5xl">
                {{ $event->title }}</h1>
            <div class="flex flex-wrap items-center gap-6 text-white/40 text-sm">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $event->start_at->translatedFormat('l d F Y') }}
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $event->start_at->format('H:i') }}
                </span>
                @if($event->location)
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $event->location }}
                    </span>
                @endif
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="py-24 bg-warm-white relative bg-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-16">
                <!-- Main Content -->
                <div class="lg:col-span-2 reveal-left">
                    @if($event->description)
                        <div class="mb-14">
                            <h2 class="text-2xl font-serif font-bold text-gray-900 mb-8">À propos</h2>
                            <div class="prose prose-lg max-w-none text-gray-500 font-elegant">
                                {!! nl2br(e($event->description)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Gallery -->
                    @if($event->images->count() > 0)
                        <div x-data="{ 
                                                    showLightbox: false, 
                                                    currentIndex: 0, 
                                                    images: [
                                                        @foreach($event->images as $img)
                                                            '{{ $img->image_path }}',
                                                        @endforeach
                                                    ],
                                                    openLightbox(index) {
                                                        this.currentIndex = index;
                                                        this.showLightbox = true;
                                                        document.body.style.overflow = 'hidden';
                                                    },
                                                    closeLightbox() {
                                                        this.showLightbox = false;
                                                        document.body.style.overflow = 'auto';
                                                    },
                                                    next() {
                                                        this.currentIndex = (this.currentIndex + 1) % this.images.length;
                                                    },
                                                    prev() {
                                                        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                                                    }
                                                }" @keydown.escape.window="closeLightbox()"
                            @keydown.arrow-right.window="next()" @keydown.arrow-left.window="prev()" class="reveal">
                            <h2 class="text-2xl font-serif font-bold text-gray-900 mb-8 flex items-center gap-3">
                                <span class="w-8 h-px bg-amber-500/30"></span>
                                Galerie Photos
                            </h2>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($event->images->take(6) as $index => $image)
                                    <div class="group relative aspect-square rounded-2xl overflow-hidden card-premium border border-gray-100 cursor-pointer"
                                        @click="openLightbox({{ $index }})">
                                        <img src="{{ $image->image_path }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                            alt="{{ $event->title }}">

                                        <div
                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white scale-75 group-hover:scale-100 transition-transform duration-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0h-3" />
                                            </svg>
                                        </div>

                                        @if($loop->index == 5 && $event->images->count() > 6)
                                            <div
                                                class="absolute inset-0 bg-amber-900/60 backdrop-blur-[2px] flex flex-col items-center justify-center text-white">
                                                <span class="text-3xl font-serif font-bold">+{{ $event->images->count() - 5 }}</span>
                                                <span class="text-[10px] font-bold uppercase tracking-widest mt-1">Photos</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Lightbox Modal -->
                            <div x-show="showLightbox" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/95 backdrop-blur-xl p-4 sm:p-8"
                                style="display: none;">

                                <!-- Close Button -->
                                <button @click="closeLightbox()"
                                    class="absolute top-6 right-6 text-white/60 hover:text-white transition-colors p-2 z-[110]">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <!-- Navigation -->
                                <div class="absolute inset-0 flex items-center justify-between px-4 sm:px-10">
                                    <button @click="prev()"
                                        class="w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all backdrop-blur-md z-[110]"
                                        @click.stop>
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button @click="next()"
                                        class="w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all backdrop-blur-md z-[110]"
                                        @click.stop>
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Image Container -->
                                <div class="relative max-w-5xl w-full h-full flex flex-col items-center justify-center gap-6"
                                    @click.away="closeLightbox()">
                                    <img :src="images[currentIndex]"
                                        class="max-w-full max-h-[80vh] object-contain rounded-xl shadow-2xl shadow-black/50"
                                        x-transition:enter="transition transform duration-500"
                                        x-transition:enter-start="scale-95 opacity-0 text-amber-500"
                                        x-transition:enter-end="scale-100 opacity-100">

                                    <div class="text-center">
                                        <p class="text-white/40 text-xs font-bold uppercase tracking-[0.4em]">Image <span
                                                x-text="currentIndex + 1"></span> sur <span x-text="images.length"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 reveal-right">
                    <div class="sticky top-28 space-y-8">
                        <!-- Event Details Card -->
                        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-amber-900/5">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-6">Informations</h3>
                            <div class="space-y-5">
                                <div class="flex items-start gap-4 p-4 bg-amber-50/50 rounded-2xl">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-700 rounded-xl flex items-center justify-center shrink-0 shadow-lg shadow-amber-500/20">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Date</p>
                                        <p class="text-gray-900 font-semibold text-sm">
                                            {{ $event->start_at->translatedFormat('l d F Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4 p-4 bg-amber-50/50 rounded-2xl">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-700 rounded-xl flex items-center justify-center shrink-0 shadow-lg shadow-amber-500/20">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Horaire</p>
                                        <p class="text-gray-900 font-semibold text-sm">
                                            {{ $event->start_at->format('H:i') }}@if($event->end_at) —
                                            {{ $event->end_at->format('H:i') }}@endif
                                        </p>
                                    </div>
                                </div>
                                @if($event->location)
                                    <div class="flex items-start gap-4 p-4 bg-amber-50/50 rounded-2xl">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-700 rounded-xl flex items-center justify-center shrink-0 shadow-lg shadow-amber-500/20">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Lieu</p>
                                            <p class="text-gray-900 font-semibold text-sm">{{ $event->location }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Share Card -->
                        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-6">Partager</h3>
                            <div class="flex gap-3">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                    target="_blank"
                                    class="flex-1 py-3 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all border border-gray-100 hover:border-blue-100 card-premium">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                                    </svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($event->title) }}"
                                    target="_blank"
                                    class="flex-1 py-3 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 hover:text-sky-500 hover:bg-sky-50 transition-all border border-gray-100 hover:border-sky-100 card-premium">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                                    </svg>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($event->title . ' — ' . url()->current()) }}"
                                    target="_blank"
                                    class="flex-1 py-3 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 hover:text-green-600 hover:bg-green-50 transition-all border border-gray-100 hover:border-green-100 card-premium">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection