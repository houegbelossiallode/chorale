@extends('layouts.admin')

@section('title', 'Agenda des Événements')

@section('content')
    <div class="space-y-6" x-data="{ 
                            view: 'grid',
                            searchQuery: '',
                            init() {
                                const searchInput = document.getElementById('global-search');
                                if (searchInput) {
                                    searchInput.placeholder = 'Rechercher un événement...';
                                    searchInput.addEventListener('input', (e) => {
                                        this.searchQuery = e.target.value;
                                    });
                                }
                            },
                            calendarInitialized: false,
                            initCalendar() {
                                if (this.calendarInitialized) return;

                                const calendarEl = document.getElementById('calendar');
                                const calendar = new FullCalendar.Calendar(calendarEl, {
                                    initialView: window.innerWidth < 768 ? 'listMonth' : 'dayGridMonth',
                                    locale: 'fr',
                                    handleWindowResize: true,
                                    windowResizeDelay: 100,
                                    headerToolbar: window.innerWidth < 768 ? {
                                        left: 'prev,next',
                                        center: 'title',
                                        right: 'listMonth'
                                    } : {
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
                                            const eventId = info.event.id;
                                            window.location.href = `/choriste/agenda/${eventId}`;
                                        }
                                    },
                                    eventMouseEnter: function(info) {
                                        info.el.style.cursor = 'pointer';
                                    },
                                    themeSystem: 'standard',
                                    height: 'auto',
                                    firstDay: 1,
                                    windowResize: function(arg) {
                                        if (window.innerWidth < 768) {
                                            calendar.setOption('headerToolbar', {
                                                left: 'prev,next',
                                                center: 'title',
                                                right: 'listMonth'
                                            });
                                            if (calendar.view.type !== 'listMonth' && calendar.view.type !== 'timeGridWeek') {
                                                calendar.changeView('listMonth');
                                            }
                                        } else {
                                            calendar.setOption('headerToolbar', {
                                                left: 'prev,next today',
                                                center: 'title',
                                                right: 'dayGridMonth,timeGridWeek,listMonth'
                                            });
                                        }
                                    }
                                });

                                calendar.render();
                                this.calendarInitialized = true;
                            }
                        }">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#444050]">Agenda des Événements</h1>
                <p class="text-slate-500 text-sm font-medium">Consultez les événements à venir et préparez vos chants.</p>
            </div>

            <div class="flex p-1 bg-slate-100 rounded-xl">
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
        </div>


        <div x-show="view === 'grid'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    @php
                        $isUpcoming = $event->start_at->isFuture() || $event->start_at->isToday();
                        $matchesSearch = "searchQuery === '' || 
                            '".strtolower(addslashes($event->title))."'.includes(searchQuery.toLowerCase()) || 
                            '".strtolower(addslashes($event->type->libelle ?? ''))."'.includes(searchQuery.toLowerCase()) || 
                            '".strtolower(addslashes($event->location ?? ''))."'.includes(searchQuery.toLowerCase())";
                        
                        // Logic: Show if it matches search AND (either we are searching OR it's upcoming)
                        $xShowLogic = "($matchesSearch) && (searchQuery !== '' || ".($isUpcoming ? 'true' : 'false').")";
                    @endphp
                    <div x-show="{{ $xShowLogic }}"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-material transition-all duration-300">
                        <div class="h-40 relative overflow-hidden">
                            <img src="{{ $event->thumbnail ?? 'https://images.unsplash.com/photo-1516280440614-37939bbacd81?q=80&w=2070&auto=format&fit=crop' }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                alt="{{ $event->title }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 text-white">
                                <span class="px-2 py-1 bg-[#7367F0] rounded text-[10px] font-bold uppercase tracking-wider">
                                    {{ $event->type->libelle ?? 'Événement' }}
                                </span>
                                <h3 class="font-bold mt-1">{{ $event->title }}</h3>
                            </div>
                        </div>

                        <div class="p-5 flex-1 flex flex-col space-y-4">
                            <div class="grid grid-cols-2 gap-4 text-xs font-medium text-slate-500">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#7367F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $event->start_at->format('d/m/Y') }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#7367F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $event->start_at->format('H:i') }}
                                </div>
                            </div>

                            <div class="flex-1">
                                <p class="text-xs text-slate-400 line-clamp-2">
                                    {{ $event->location ?? 'Lieu non défini' }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2 mt-2">
                                <a href="{{ route('choriste.events.show', $event->id) }}"
                                    class="flex-1 py-2.5 bg-[#7367F0]/10 hover:bg-[#7367F0] text-[#7367F0] hover:text-white rounded-xl text-xs font-bold transition-all text-center">
                                    Voir le Répertoire
                                </a>
                                <a href="{{ route('choriste.events.repertoire.pdf', $event->id) }}" target="_blank"
                                    title="Télécharger le programme (PDF)"
                                    class="px-4 py-2.5 bg-red-50 hover:bg-red-500 text-red-500 hover:text-white rounded-xl transition-all flex items-center justify-center border border-red-100/50 hover:border-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- No Results Message -->
            <!-- <div x-show="searchQuery !== '' && [...$el.parentElement.querySelectorAll('.repetition-card, [x-show*=matchesSearch]')].filter(el => el.style.display !== 'none').length === 0"
                class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="text-slate-400 font-medium">Aucun événement ne correspond à votre recherche.</p>
            </div> -->
        </div>
        <!-- Calendar View -->
        <div x-show="view === 'calendar'" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
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
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Concerts</span>
                </div>
            </div>
            <div
                class="bg-white rounded-2xl shadow-material-sm border border-slate-100 p-6 overflow-hidden calendar-container">
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

            /* Mobile Adjustments */
            @media (max-width: 767.98px) {
                .fc .fc-toolbar {
                    flex-direction: column;
                    gap: 1rem;
                }

                .fc .fc-toolbar-title {
                    font-size: 1.1rem;
                }

                .fc .fc-button-group {
                    width: 100%;
                }

                .fc .fc-button {
                    flex: 1;
                    padding: 0.4rem 0.5rem !important;
                    font-size: 0.7rem !important;
                }

                .fc .fc-daygrid-body,
                .fc .fc-scrollgrid-sync-table {
                    width: 100% !important;
                }

                .calendar-container {
                    padding: 1rem !important;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    @endpush
@endsection