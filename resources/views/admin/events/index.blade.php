@extends('layouts.admin')

@section('page_title', 'Gestion Agenda')

@section('content')
    <div class="space-y-6" x-data="{ 
                    view: 'grid',
                    calendarInitialized: false,
                    initCalendar() {
                        if (this.calendarInitialized) return;

                        const calendarEl = document.getElementById('calendar');
                        const calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            locale: 'fr',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,listMonth'
                            },
                            buttonText: {
                                today: 'Aujourd\'hui',
                                month: 'Mois',
                                week: 'Semaine',
                                list: 'Planning'
                            },
                            events: '{{ route('admin.events.api') }}',
                            eventClick: function(info) {
                                if (info.event.url) {
                                    info.jsEvent.preventDefault();
                                    window.location.href = info.event.url;
                                }
                            },
                            eventMouseEnter: function(info) {
                                info.el.style.cursor = 'pointer';
                            },
                            themeSystem: 'standard',
                            height: 'auto',
                            firstDay: 1,
                            direction: 'ltr'
                        });

                        calendar.render();
                        this.calendarInitialized = true;
                    }
                }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-xl sm:text-2xl font-semibold text-[#444050]">Agenda de la Chorale</h3>
                <p class="text-[13px] text-slate-400">Planification des répétitions et concerts</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Toggle Views -->
                <div class="flex p-1 bg-slate-100 rounded-xl mr-2">
                    <button @click="view = 'grid'"
                        :class="view === 'grid' ? 'bg-white text-[#7367F0] shadow-sm' : 'text-slate-500'"
                        class="p-2 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </button>
                    <button @click="view = 'calendar'; $nextTick(() => initCalendar())"
                        :class="view === 'calendar' ? 'bg-white text-[#7367F0] shadow-sm' : 'text-slate-500'"
                        class="p-2 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>

                <a href="{{ route('admin.events.create') }}" class="btn-primary gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouveau
                </a>
            </div>
        </div>

        <!-- Grid View -->
        <div x-show="view === 'grid'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($events as $event)
                    <div
                        class="bg-white rounded-2xl shadow-material-sm border border-slate-100 hover:border-[#7367F0]/30 transition-all group overflow-hidden flex flex-col">
                        <!-- Header Image / Placeholder -->
                        <div class="h-32 bg-slate-50 relative overflow-hidden shrink-0">
                            <img src="{{ $event->thumbnail }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-700">

                            <!-- Type Badge -->
                            <div class="absolute top-4 left-4">
                                <span
                                    class="px-3 py-1 bg-white/90 backdrop-blur-md text-[#7367F0] rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm border border-[#7367F0]/10">
                                    {{ $event->type->libelle }}
                                </span>
                            </div>

                            <!-- Date Badge Floating -->
                            <div
                                class="absolute top-4 right-4 w-12 h-12 bg-white rounded-xl shadow-lg flex flex-col items-center justify-center border border-slate-50">
                                <span
                                    class="text-[9px] font-bold text-[#7367F0] uppercase leading-none">{{ $event->start_at->translatedFormat('M') }}</span>
                                <span
                                    class="text-[16px] font-extrabold text-[#444050] leading-none">{{ $event->start_at->format('d') }}</span>
                            </div>
                        </div>

                        <div class="p-6 flex flex-col flex-grow">
                            <div class="mb-4">
                                <h3
                                    class="text-[17px] font-bold text-[#444050] line-clamp-1 mb-1 group-hover:text-[#7367F0] transition-colors">
                                    {{ $event->title }}
                                </h3>
                                <div class="flex items-center gap-2 text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-[11px] font-medium">{{ $event->start_at->format('H:i') }} •
                                        {{ $event->location }}</span>
                                </div>
                            </div>

                            <p class="text-[13px] text-slate-500 line-clamp-2 mb-6 min-h-[40px]">
                                {!! $event->description ?: 'Aucune description fournie.' !!}
                            </p>

                            <!-- Actions -->
                            <div class="mt-auto pt-5 border-t border-slate-50 flex items-center justify-between">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.events.program.index', $event) }}"
                                        class="p-2 text-slate-400 hover:text-[#7367F0] hover:bg-[#7367F0]/5 rounded-lg transition-all"
                                        title="Programme musical">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.events.show', $event) }}"
                                        class="p-2 text-slate-400 hover:text-[#7367F0] hover:bg-[#7367F0]/5 rounded-lg transition-all"
                                        title="Détails">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event) }}"
                                        class="p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all"
                                        title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                </div>

                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                    onsubmit="return confirm('Supprimer cet événement ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
                                        title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full py-16 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                        <svg class="w-12 h-12 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm font-medium">Aucun événement n'a été créé pour le moment.</p>
                    </div>
                @endforelse
            </div>

            @if($events->hasPages())
                <div class="mt-8 px-4">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
        <!-- Calendar View -->
        <div x-show="view === 'calendar'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <!-- Legend -->
            <div class="flex flex-wrap items-center gap-4 mb-4 px-2">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#7367F0]"></span>
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Autres</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#FF9F43]"></span>
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Répétitions</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#00CFE8]"></span>
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Messes</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#28C76F]"></span>
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Concerts / Prest.</span>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-material-sm border border-slate-100 p-6 overflow-hidden">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Custom FullCalendar Styles for Premium Look */
            .fc {
                font-family: 'Public Sans', sans-serif;
                --fc-border-color: #f1f0f2;
                --fc-button-bg-color: #7367f0;
                --fc-button-border-color: #7367f0;
                --fc-button-hover-bg-color: #5e50ee;
                --fc-button-hover-border-color: #5e50ee;
                --fc-button-active-bg-color: #5e50ee;
                --fc-button-active-border-color: #5e50ee;
            }

            .fc .fc-toolbar-title {
                font-size: 1.25rem;
                font-weight: 700;
                color: #444050;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .fc .fc-button-primary {
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.5px;
                padding: 0.5rem 1rem;
                border-radius: 0.5rem !important;
            }

            .fc .fc-button-primary:not(:disabled).fc-button-active,
            .fc .fc-button-primary:not(:disabled):active {
                background-color: #7367f0 !important;
                border-color: #7367f0 !important;
            }

            .fc .fc-daygrid-day-number {
                font-size: 0.85rem;
                font-weight: 600;
                color: #444050;
                padding: 8px;
            }

            .fc .fc-col-header-cell-cushion {
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                color: #a5a3ae;
                padding: 12px;
            }

            .fc .fc-event {
                border-radius: 6px;
                padding: 4px 8px;
                font-weight: 600;
                border: none !important;
                font-size: 0.75rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .fc .fc-day-today {
                background: rgba(115, 103, 240, 0.04) !important;
            }

            .fc-theme-standard td,
            .fc-theme-standard th {
                border: 1px solid #f8f7fa;
            }

            .fc .fc-list-event-title b {
                color: #444050;
            }

            .fc .fc-list-day-cushion {
                background-color: #f8f7fa;
                font-weight: 700;
                font-size: 0.75rem;
                color: #a5a3ae;
                text-transform: uppercase;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    @endpush
@endsection