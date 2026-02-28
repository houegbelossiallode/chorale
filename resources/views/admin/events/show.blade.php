@extends('layouts.admin')

@section('page_title', $event->title)

@section('content')
<div class="space-y-4 sm:space-y-6">
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col gap-4 mb-2">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.events.index') }}"
                class="w-9 h-9 bg-white rounded-lg flex items-center justify-center text-slate-400 hover:text-[#7367F0] shadow-sm border border-slate-100 transition-all shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="min-w-0">
                <h3 class="text-lg sm:text-xl md:text-2xl font-semibold text-[#444050] truncate">{{ $event->title }}</h3>
                <p class="text-[13px] text-slate-400">Détails de l'événement</p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.events.edit', $event) }}" class="btn-primary gap-2 text-[13px] sm:text-[14px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Modifier
            </a>
            <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                @csrf @method('DELETE')
                <button type="submit"
                    class="btn-secondary text-[#EA5455] border-[#EA5455]/20 hover:bg-[#EA5455]/5 gap-2 text-[13px] sm:text-[14px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Supprimer
                </button>
            </form>
        </div>
    </div>

    {{-- Hero Image --}}
    @if($event->principalImage)
        <div class="relative rounded-xl sm:rounded-2xl overflow-hidden shadow-material h-48 sm:h-64 md:h-80">
            <img src="{{ $event->principalImage->image_path }}" alt="{{ $event->title }}"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 md:p-8">
                <span
                    class="inline-block px-2.5 sm:px-3 py-1 bg-[#7367F0] text-white rounded-full text-[9px] sm:text-[10px] font-bold uppercase tracking-wider mb-2 sm:mb-3 shadow-lg">
                    {{ $event->type->libelle ?? 'Événement' }}
                </span>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-white drop-shadow-lg line-clamp-2">{{ $event->title }}</h2>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        {{-- Left Column: Details --}}
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            {{-- Info Cards Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                {{-- Date --}}
                <div class="card-material p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-[#7367F0]/10 rounded-lg sm:rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#7367F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Date de début</p>
                        <p class="text-[13px] sm:text-[14px] font-bold text-[#444050]">
                            {{ $event->start_at->translatedFormat('d M Y') }}</p>
                        <p class="text-[11px] sm:text-[12px] text-slate-500">{{ $event->start_at->translatedFormat('l, H:i') }}</p>
                    </div>
                </div>

                {{-- Location --}}
                <div class="card-material p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-[#FF9F43]/10 rounded-lg sm:rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#FF9F43]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lieu</p>
                        <p class="text-[13px] sm:text-[14px] font-bold text-[#444050] truncate">{{ $event->location }}</p>
                    </div>
                </div>

                {{-- Type --}}
                <div class="card-material p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-[#28C76F]/10 rounded-lg sm:rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#28C76F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Type</p>
                        <p class="text-[13px] sm:text-[14px] font-bold text-[#444050]">{{ $event->type->libelle ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="card-material p-4 sm:p-6 md:p-8 space-y-3 sm:space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                    <div class="w-1.5 h-4 bg-[#7367F0] rounded-full"></div>
                    <h4 class="text-[14px] sm:text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Description</h4>
                </div>
                @if($event->description)
                    <div class="text-[13px] sm:text-[14px] text-slate-600 leading-relaxed whitespace-pre-line">{{ $event->description }}</div>
                @else
                    <p class="text-[13px] text-slate-400 italic">Aucune description renseignée pour cet événement.</p>
                @endif
            </div>

            {{-- Image Gallery --}}
            @if($event->images->count() > 0)
                <div class="card-material p-4 sm:p-6 md:p-8 space-y-3 sm:space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-50">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-4 bg-[#28C76F] rounded-full"></div>
                            <h4 class="text-[14px] sm:text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Galerie</h4>
                        </div>
                        <span
                            class="text-[10px] sm:text-[11px] font-bold text-slate-400 bg-slate-50 px-2 sm:px-2.5 py-1 rounded-full">{{ $event->images->count() }}
                            photo{{ $event->images->count() > 1 ? 's' : '' }}</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4"
                        x-data="{ lightbox: false, activeImage: '' }">
                        @foreach($event->images as $image)
                            <div class="relative group aspect-square rounded-lg sm:rounded-xl overflow-hidden border-2 shadow-sm cursor-pointer transition-all hover:shadow-lg
                                        {{ $image->is_principal ? 'border-[#7367F0] ring-2 sm:ring-4 ring-[#7367F0]/10' : 'border-slate-100 hover:border-[#7367F0]/30' }}"
                                @click="lightbox = true; activeImage = '{{ $image->image_path }}'">
                                <img src="{{ $image->image_path }}" alt="Image événement"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                                @if($image->is_principal)
                                    <div
                                        class="absolute top-1.5 left-1.5 sm:top-2 sm:left-2 px-1.5 sm:px-2 py-0.5 bg-[#7367F0] text-white text-[8px] sm:text-[9px] font-bold rounded-md uppercase tracking-wider shadow-sm">
                                        Couverture
                                    </div>
                                @endif

                                <div
                                    class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-white/80 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#444050]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Lightbox Overlay --}}
                        <div x-show="lightbox" x-transition.opacity
                            class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-3 sm:p-6 md:p-8"
                            @click.self="lightbox = false" @keydown.escape.window="lightbox = false" style="display: none;">
                            <button @click="lightbox = false"
                                class="absolute top-3 right-3 sm:top-4 sm:right-4 w-9 h-9 sm:w-10 sm:h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors z-10">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <img :src="activeImage" class="max-w-full max-h-[85vh] rounded-lg sm:rounded-xl shadow-2xl object-contain">
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column: Sidebar --}}
        <div class="space-y-4 sm:space-y-6 lg:sticky lg:top-6 self-start">
            {{-- Planning Card --}}
            <div class="card-material p-4 sm:p-6 md:p-8 space-y-4 sm:space-y-5">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                    <div class="w-1.5 h-4 bg-[#FF9F43] rounded-full"></div>
                    <h4 class="text-[14px] sm:text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Planning</h4>
                </div>

                <div class="space-y-3 sm:space-y-4">
                    {{-- Start --}}
                    <div class="flex items-start gap-3">
                        <div
                            class="w-8 h-8 bg-[#28C76F]/10 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-[#28C76F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Début</p>
                            <p class="text-[12px] sm:text-[13px] font-bold text-[#444050]">
                                {{ $event->start_at->translatedFormat('l d F Y') }}</p>
                            <p class="text-[11px] sm:text-[12px] text-slate-500">à {{ $event->start_at->format('H:i') }}</p>
                        </div>
                    </div>

                    {{-- End --}}
                    @if($event->end_at)
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 bg-[#EA5455]/10 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-[#EA5455]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Fin prévue</p>
                                <p class="text-[12px] sm:text-[13px] font-bold text-[#444050]">
                                    {{ $event->end_at->translatedFormat('l d F Y') }}</p>
                                <p class="text-[11px] sm:text-[12px] text-slate-500">à {{ $event->end_at->format('H:i') }}</p>
                            </div>
                        </div>

                        {{-- Duration --}}
                        <div class="flex items-start gap-3">
                            <div
                                class="w-8 h-8 bg-[#7367F0]/10 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-[#7367F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Durée</p>
                                @php
                                    $diff = $event->start_at->diff($event->end_at);
                                    $parts = [];
                                    if ($diff->d > 0)
                                        $parts[] = $diff->d . ' jour' . ($diff->d > 1 ? 's' : '');
                                    if ($diff->h > 0)
                                        $parts[] = $diff->h . 'h';
                                    if ($diff->i > 0)
                                        $parts[] = $diff->i . 'min';
                                @endphp
                                <p class="text-[12px] sm:text-[13px] font-bold text-[#444050]">{{ implode(' ', $parts) ?: '—' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Status Card --}}
            <div class="card-material p-4 sm:p-6 space-y-3 sm:space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-50">
                    <div class="w-1.5 h-4 bg-[#00CFE8] rounded-full"></div>
                    <h4 class="text-[14px] sm:text-[15px] font-semibold text-[#444050] uppercase tracking-wider">Statut</h4>
                </div>

                @php
                    $now = now();
                    if ($event->start_at->isFuture()) {
                        $statusLabel = 'À venir';
                        $statusColor = '#7367F0';
                        $statusBg = '#E7E7FF';
                    } elseif ($event->end_at && $event->end_at->isFuture()) {
                        $statusLabel = 'En cours';
                        $statusColor = '#28C76F';
                        $statusBg = '#DFF7E9';
                    } else {
                        $statusLabel = 'Terminé';
                        $statusColor = '#82868B';
                        $statusBg = '#F8F8F8';
                    }
                @endphp

                <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                    <span class="px-3 sm:px-4 py-1.5 rounded-full text-[10px] sm:text-[11px] font-bold uppercase tracking-wider"
                        style="background: {{ $statusBg }}; color: {{ $statusColor }};">
                        {{ $statusLabel }}
                    </span>
                    @if($event->start_at->isFuture())
                        <span class="text-[10px] sm:text-[11px] text-slate-400">dans
                            {{ $event->start_at->diffForHumans(null, false, false, 2) }}</span>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="flex flex-col gap-2 sm:gap-3">
                <a href="{{ route('admin.events.edit', $event) }}"
                    class="btn-primary w-full py-3 sm:py-3.5 text-[13px] sm:text-[14px] flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Modifier l'événement
                </a>
                <a href="{{ route('admin.events.index') }}" class="btn-secondary w-full py-3 sm:py-3.5 text-[13px] sm:text-[14px] text-center">
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection