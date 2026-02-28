<header
    class="h-[64px] bg-white/80 backdrop-blur-md rounded-xl shadow-material flex items-center justify-between px-3 md:px-8 transition-all duration-300">
    <!-- Header Left: Search & Mobile Trigger -->
    <div class="flex items-center gap-1 md:gap-4 flex-1 min-w-0">
        <!-- Mobile Trigger -->
        <button @click="sidebarOpen = true"
            class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Material Search (Hidden on smaller screens, visible from 'sm') -->
        <div class="hidden sm:flex items-center gap-3 text-slate-400 group flex-1 max-w-[200px] md:max-w-md">
            <svg class="w-5 h-5 group-focus-within:text-[#7367F0] transition-colors shrink-0" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" placeholder="Rechercher..."
                class="bg-transparent border-none focus:ring-0 text-[14px] placeholder-slate-400 w-full text-[#444050] truncate">
        </div>
    </div>

    <!-- Header Right: Actions -->
    <div class="flex items-center gap-1 sm:gap-2 ml-auto shrink-0">
        <!-- Notifications -->
        <div class="relative" x-data="{ notificationsOpen: false }">
            <button @click="notificationsOpen = !notificationsOpen" @click.away="notificationsOpen = false"
                class="p-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span
                    class="absolute top-2 right-2 w-3.5 h-3.5 bg-[#EA5455] text-white text-[9px] font-bold rounded-full flex items-center justify-center border-2 border-white">2</span>
            </button>

            <!-- Notifications Dropdown (Absolute right for mobile) -->
            <div x-show="notificationsOpen" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="fixed sm:absolute right-4 sm:right-0 mt-2 w-[calc(100vw-32px)] sm:w-96 bg-white rounded-lg shadow-material-lg border border-slate-100 z-50 overflow-hidden"
                style="display: none;">
                <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h4 class="text-[14px] font-semibold text-[#444050]">Notifications</h4>
                    <span class="text-[9px] font-bold bg-[#E7E7FF] text-[#7367F0] px-2 py-0.5 rounded uppercase">2
                        Nouvelles</span>
                </div>
                <div class="max-h-[300px] overflow-y-auto custom-scrollbar-slim">
                    <!-- Notification Item 1 -->
                    <a href="#"
                        class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition-colors border-b border-slate-50">
                        <div
                            class="w-9 h-9 rounded-full bg-[#DFF7E9] text-[#28C76F] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[13px] font-semibold text-[#444050]">Nouveau membre</p>
                            <p class="text-[11px] text-slate-400 line-clamp-1">Jean-Baptiste vient de s'inscrire.</p>
                        </div>
                    </a>
                    <!-- Notification Item 2 -->
                    <a href="#"
                        class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition-colors border-b border-slate-50">
                        <div
                            class="w-9 h-9 rounded-full bg-[#FFF1E3] text-[#FF9F43] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[13px] font-semibold text-[#444050]">Répétition</p>
                            <p class="text-[11px] text-slate-400 line-clamp-1">Début dans 2 heures.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="relative ml-1 sm:ml-2" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false"
                class="flex items-center gap-2 p-1 hover:bg-slate-100 rounded-lg transition-colors">
                <div class="relative shrink-0">
                    <div
                        class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-[#7367F0] font-bold border-2 border-white shadow-sm overflow-hidden text-[13px]">
                        {{ substr(auth()->user()->first_name ?? 'A', 0, 1) }}
                    </div>
                    <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-[#28C76F] border-2 border-white rounded-full">
                    </div>
                </div>
                <div class="hidden lg:block text-left">
                    <p class="text-[13px] font-semibold text-[#444050] leading-none">
                        {{ auth()->user()->first_name ?? 'Admin' }}
                    </p>
                    <p class="text-[11px] text-slate-400 mt-1 leading-none">
                        {{ auth()->user()->role->libelle ?? 'Membre' }}
                    </p>
                </div>
            </button>

            <!-- Material Dropdown -->
            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 mt-2 w-48 sm:w-56 bg-white rounded-lg shadow-material-lg border border-slate-100 py-2 z-50 overflow-hidden"
                style="display: none;">
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 px-4 py-2 text-[14px] text-[#444050] hover:bg-slate-50 transition-colors">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil
                </a>
                <div class="h-px bg-slate-100 my-1"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-2 text-[14px] text-[#EA5455] hover:bg-rose-50 transition-colors text-left font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Sortir
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>