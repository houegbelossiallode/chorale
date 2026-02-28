<aside id="admin-sidebar"
    class="fixed inset-y-0 left-0 z-[70] w-[260px] bg-white text-[#444050] transform transition-all duration-300 ease-in-out shadow-material lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <div class="h-[76px] flex items-center px-6 mb-2">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-8 h-8 bg-[#7367F0] rounded-lg flex items-center justify-center text-white shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                </svg>
            </div>
            <span class="text-xl font-bold tracking-tight text-[#444050]">Chorale</span>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden ml-auto text-slate-400 hover:text-[#7367F0]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>


    <div class="flex-1 overflow-y-auto px-4 py-2 custom-scrollbar">
        <nav class="space-y-1"
            x-data="{ activeMenu: '{{ request()->routeIs('admin.*') ? explode('.', request()->route()->getName())[2] ?? '' : '' }}' }">

            <p class="text-[11px] font-medium text-slate-400 uppercase tracking-widest mb-4 px-4 mt-4">Main</p>

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('admin.dashboard') ? 'nav-pill-active' : 'text-[#444050] hover:bg-[#F2F2F2]' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-[#7367F0]' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-[15px] font-medium">Dashboard</span>
            </a>
            @foreach($mainmenus as $menu)
                @php
                    $hasActiveSub = $menu->sousMenus->contains(function ($sm) {
                        $smUrl = Route::has($sm->url) ? route($sm->url) : url($sm->url);
                        return request()->url() == $smUrl;
                    });
                    $menuId = Str::slug($menu->name);
                @endphp
                <div class="space-y-1">
                    <button @click="activeMenu = (activeMenu === '{{ $menuId }}' ? '' : '{{ $menuId }}')"
                        class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg transition-all group {{ $hasActiveSub ? 'bg-[#F2F2F2]' : 'text-[#444050] hover:bg-[#F2F2F2]' }}">
                        <div class="flex items-center gap-3">
                            @php $viewName = 'admin.partials.icons.' . Str::slug($menu->name); @endphp
                            @if(view()->exists($viewName))
                                @include($viewName, ['class' => 'w-5 h-5 text-slate-400 group-hover:text-[#7367F0]'])
                            @else
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-[#7367F0]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            @endif
                            <span class="text-[15px] font-medium">{{ $menu->name }}</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-300"
                            :class="activeMenu === '{{ $menuId }}' ? 'rotate-90' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="activeMenu === '{{ $menuId }}' || '{{ $hasActiveSub }}'" x-collapse
                        class="pl-4 space-y-1 mt-1">
                        @foreach($menu->sousMenus as $sm)
                            @php
                                $smUrl = url(ltrim($sm->url, '/'));
                                $isActive = request()->url() == $smUrl;
                            @endphp
                            <a href="{{ $smUrl }}"
                                class="flex items-center gap-3 px-4 py-2 rounded-lg text-[14px] {{ $isActive ? 'text-[#7367F0] font-semibold' : 'text-slate-500 hover:text-[#7367F0]' }}">
                                <div class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-[#7367F0]' : 'bg-slate-300' }}">
                                </div>
                                {{ $sm->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <p class="text-[11px] font-medium text-slate-400 uppercase tracking-widest mb-4 px-4 mt-8">Utilisateur</p>

            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all group {{ request()->routeIs('profile.edit') ? 'nav-pill-active' : 'text-[#444050] hover:bg-[#F2F2F2]' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('profile.edit') ? 'text-white' : 'text-slate-400 group-hover:text-[#7367F0]' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[15px] font-medium">Mon Profil</span>
            </a>
        </nav>
    </div>
</aside>