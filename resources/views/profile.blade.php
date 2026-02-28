@extends('layouts.public')

@section('title', $user->first_name . ' ' . $user->last_name . ' — Trombinoscope Chorale')

@section('navDark', true)

@section('content')
    @push('meta')
        <meta property="og:title" content="{{ $user->first_name }} {{ $user->last_name }} — Chorale Saint Oscar Romero" />
        <meta property="og:description" content="{{ $user->citation ?? 'Découvrez mon profil au sein de la chorale.' }}" />
        <meta property="og:image" content="{{ asset($user->photo_url ?? 'images/default-avatar.jpg') }}" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:type" content="profile" />
        <meta name="twitter:card" content="summary_large_image">
    @endpush

    <section class="pt-32 pb-32 bg-warm-white relative bg-pattern overflow-hidden">
        <!-- Floating shapes -->
        <div
            class="absolute top-0 right-0 w-[600px] h-[600px] bg-gradient-to-bl from-amber-100/40 to-transparent rounded-full blur-3xl -z-10 animate-pulse">
        </div>
        <div
            class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-gradient-to-tr from-purple-100/20 to-transparent rounded-full blur-3xl -z-10">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumb / Back -->
            <nav class="mb-16 reveal">
                <a href="{{ route('members') }}"
                    class="inline-flex items-center gap-4 px-6 py-3.5 bg-white/80 backdrop-blur-md rounded-2xl border border-gray-100 text-amber-700 font-bold hover:bg-amber-50 hover:border-amber-200 transition-all text-sm group shadow-sm">
                    <svg class="w-5 h-5 group-hover:-translate-x-1.5 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                    Trombinoscope
                </a>
            </nav>

            <div class="grid lg:grid-cols-12 gap-16 items-start">
                <!-- Profile Media (4 cols) -->
                <div class="lg:col-span-4 reveal-left">
                    <div class="sticky top-32">
                        <div class="relative group">
                            <div
                                class="aspect-[3/4] rounded-[3.5rem] overflow-hidden shadow-2xl shadow-amber-900/10 ring-1 ring-black/5 relative z-10">
                                @if($user->photo_url)
                                    <img src="{{ $user->photo_url }}" alt="{{ $user->first_name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-1000">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-amber-100 via-amber-50 to-orange-50 flex items-center justify-center">
                                        <div
                                            class="w-32 h-32 bg-gradient-to-br from-amber-400 to-amber-600 rounded-[2.5rem] flex items-center justify-center text-white text-6xl font-serif font-bold shadow-2xl shadow-amber-500/30">
                                            {{ substr($user->first_name, 0, 1) }}
                                        </div>
                                    </div>
                                @endif
                                <!-- Glass Overlay in bottom -->
                                <div
                                    class="absolute inset-x-0 bottom-0 p-8 pt-20 bg-gradient-to-t from-black/60 to-transparent flex flex-col items-center">
                                    <div class="flex items-center gap-6">
                                        <!-- Like Button -->
                                        <button onclick="toggleLike('{{ $user->slug }}')" id="like-btn"
                                            class="group/like flex flex-col items-center gap-1 transition-transform active:scale-95 cursor-pointer">
                                            <div id="heart-container"
                                                class="w-16 h-16 rounded-full flex items-center justify-center backdrop-blur-md border border-white/30 transition-all duration-300 {{ $hasLiked ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/40' : 'bg-white/20 text-white hover:bg-rose-500/20' }}">
                                                <svg class="w-8 h-8 {{ $hasLiked ? 'fill-current' : 'fill-none' }} stroke-current"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                                </svg>
                                            </div>
                                            <span id="likes-count-label"
                                                class="text-white font-bold text-sm tracking-widest drop-shadow-md">{{ $user->likes_received_count }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Decorative floating element -->
                            <div
                                class="absolute -bottom-6 -right-6 w-full h-full rounded-[3.5rem] border-2 border-amber-300/30 -z-0">
                            </div>

                            <!-- Voice Badge -->
                            @if($pupitre)
                                <div
                                    class="absolute -top-4 -right-4 z-20 px-8 py-4 bg-gradient-to-br from-amber-500 to-yellow-500 text-white rounded-2xl shadow-2xl shadow-amber-500/40 font-bold text-sm tracking-[0.2em] uppercase animate-float-slow">
                                    {{ $pupitre->name }}
                                </div>
                            @endif
                        </div>

                        <!-- Share Section -->
                        <div class="mt-12 bg-white/40 backdrop-blur-md rounded-3xl p-8 border border-white/60">
                            <p class="text-[10px] font-black text-amber-900/40 uppercase tracking-[0.4em] mb-6 text-center">
                                Partager le profil</p>
                            <div class="grid grid-cols-3 gap-4">
                                @php $shareUrl = urlencode(url()->current()); @endphp
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank"
                                    class="aspect-square bg-white rounded-2xl border border-gray-100 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 hover:scale-105 transition-all shadow-sm">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                                    </svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}" target="_blank"
                                    class="aspect-square bg-white rounded-2xl border border-gray-100 flex items-center justify-center text-gray-400 hover:text-sky-500 hover:bg-sky-50 hover:scale-105 transition-all shadow-sm">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                                    </svg>
                                </a>
                                <a href="https://wa.me/?text=Découvrez+le+profil+de+{{ $user->first_name }}+sur+le+trombinoscope+de+la+chorale+:+{{ $shareUrl }}"
                                    target="_blank"
                                    class="aspect-square bg-white rounded-2xl border border-gray-100 flex items-center justify-center text-gray-400 hover:text-green-600 hover:bg-green-50 hover:scale-105 transition-all shadow-sm">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Column (8 cols) -->
                <div class="lg:col-span-8 reveal-right">
                    <div class="mb-14">
                        <div class="flex items-center gap-4 mb-6">
                            <h1 class="text-3xl md:text-5xl font-serif text-gray-900 leading-[0.85] tracking-tighter">
                                {{ $user->first_name }}<br>
                                <span class="text-amber-500">{{ $user->last_name }}</span>
                            </h1>
                        </div>
                        <div class="flex flex-wrap items-center gap-6">
                            @if($pupitre)
                                <div
                                    class="flex items-center gap-3 px-6 py-2.5 bg-amber-50 border border-amber-100 rounded-full text-amber-700 font-black text-xs uppercase tracking-[0.2em]">
                                    <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                                    {{ $pupitre->name }}
                                </div>
                            @endif
                            <span class="text-gray-400 font-elegant italic tracking-wide">Membre de la Chorale Saint Oscar
                                Romero</span>
                        </div>
                    </div>

                    <!-- Personal Quote -->
                    @if($user->citation)
                        <div class="mb-20 relative px-10">
                            <svg class="absolute -top-6 -left-2 w-20 h-20 text-amber-100/60 transition-transform hover:scale-110"
                                fill="currentColor" viewBox="0 0 32 32">
                                <path d="M10 8v8H6v-8h4zm12 0v8h-4v-8h4z" />
                            </svg>
                            <blockquote class="relative z-10">
                                <p class="text-3xl md:text-4xl font-elegant italic text-gray-600 leading-snug">
                                    {{ $user->citation }}
                                </p>
                            </blockquote>
                        </div>
                    @endif

                    <!-- Detailed Cards -->
                    <div class="space-y-12">
                        <!-- What I love about the choir -->
                        @if($user->love_choir)
                            <div
                                class="bg-white rounded-[2.5rem] p-12 border border-blue-50 shadow-xl shadow-blue-900/5 relative overflow-hidden group/love">
                                <div
                                    class="absolute -right-8 -bottom-8 w-40 h-40 bg-rose-50 rounded-full group-hover/love:scale-110 transition duration-1000">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center gap-5 mb-8">
                                        <div
                                            class="w-14 h-14 bg-gradient-to-br from-rose-400 to-rose-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-rose-200">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-serif font-bold text-gray-900">Ce que j'aime dans la chorale
                                        </h3>
                                    </div>
                                    <div class="prose prose-amber max-w-none">
                                        <p class="text-xl text-gray-500 font-elegant leading-relaxed whitespace-pre-wrap">
                                            {{ $user->love_choir }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid md:grid-cols-2 gap-12">
                            <!-- Activities -->
                            @if($user->activities)
                                <div
                                    class="bg-amber-50/50 rounded-[2.5rem] p-10 border border-amber-100 relative overflow-hidden h-full group/act">
                                    <div class="flex items-center gap-4 mb-6">
                                        <div
                                            class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-700 transition-colors group-hover/act:bg-amber-500 group-hover/act:text-white">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                            </svg>
                                        </div>
                                        <h4 class="font-bold text-gray-900 text-lg">Dans la chorale</h4>
                                    </div>
                                    <p class="text-gray-500 leading-relaxed font-elegant text-lg">{{ $user->activities }}</p>
                                </div>
                            @endif

                            <!-- Hobbies -->
                            @if($user->hobbie || $user->activite)
                                <div
                                    class="bg-purple-50/30 rounded-[2.5rem] p-10 border border-purple-100 relative overflow-hidden h-full group/hob">
                                    <div class="flex items-center gap-4 mb-6">
                                        <div
                                            class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-700 transition-colors group-hover/hob:bg-purple-500 group-hover/hob:text-white">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h4 class="font-bold text-gray-900 text-lg">Hobbies & Vie</h4>
                                    </div>
                                    <div class="space-y-4">
                                        @if($user->activite)
                                            <div class="flex items-start gap-4">
                                                <div class="w-1.5 h-1.5 rounded-full bg-purple-400 mt-2.5"></div>
                                                <p class="text-gray-500 font-elegant text-lg"><span
                                                        class="font-bold text-gray-700">Activité :</span> {{ $user->activite }}</p>
                                            </div>
                                        @endif
                                        @if($user->hobbie)
                                            <div class="flex items-start gap-4">
                                                <div class="w-1.5 h-1.5 rounded-full bg-purple-400 mt-2.5"></div>
                                                <p class="text-gray-500 font-elegant text-lg"><span
                                                        class="font-bold text-gray-700">Loisirs :</span> {{ $user->hobbie }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AJAX Scripts -->
    <script>
        async function toggleLike(slug) {
            const btn = document.getElementById('heart-container');
            const label = document.getElementById('likes-count-label');
            const svg = btn.querySelector('svg');

            try {
                const response = await fetch(`/membres/${slug}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Erreur réseau');

                const data = await response.json();

                // Update UI
                if (data.status === 'liked') {
                    btn.classList.add('bg-rose-500', 'text-white', 'shadow-lg', 'shadow-rose-500/40');
                    btn.classList.remove('bg-white/20', 'hover:bg-rose-500/20');
                    svg.classList.add('fill-current');
                } else {
                    btn.classList.remove('bg-rose-500', 'text-white', 'shadow-lg', 'shadow-rose-500/40');
                    btn.classList.add('bg-white/20', 'hover:bg-rose-500/20');
                    svg.classList.remove('fill-current');
                }

                label.innerText = data.likes_count;

                // Little bounce effect
                btn.style.transform = 'scale(1.2)';
                setTimeout(() => btn.style.transform = 'scale(1)', 200);

            } catch (error) {
                console.error(error);
                // Notification via Toast si le système est dispo
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { message: 'Impossible de mettre à jour le like.', type: 'error' }
                }));
            }
        }
    </script>
@endsection