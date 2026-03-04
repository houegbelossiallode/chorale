<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap"
        rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Quill.js Rich Text Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #F8F7FA;
            color: #444050;
        }

        /* Material Excellence Shadows */
        .shadow-material {
            box-shadow: 0 0.125rem 0.25rem 0 rgba(165, 163, 174, 0.3);
        }

        .shadow-material-lg {
            box-shadow: 0 0.5rem 1.125rem 0 rgba(165, 163, 174, 0.45);
        }

        /* Card Master */
        .card-material {
            background-color: #ffffff;
            border-radius: 0.75rem;
            border: none;
            transition: all 0.3s ease-in-out;
        }

        .card-material:hover {
            box-shadow: 0 0.25rem 0.75rem 0 rgba(165, 163, 174, 0.45);
        }

        /* Pill Navigation */
        .nav-pill-active {
            background: linear-gradient(72.47deg, #7367f0 22.16%, rgba(115, 103, 240, 0.7) 76.47%);
            color: #ffffff !important;
            box-shadow: 0 0.125rem 0.25rem 0 rgba(115, 103, 240, 0.4);
        }

        /* Animations */
        .animate-fade-in {
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #dbdade;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #c9c8ce;
        }

        .custom-scrollbar-slim::-webkit-scrollbar {
            height: 4px;
        }

        .custom-scrollbar-slim::-webkit-scrollbar-thumb {
            background: #dbdade;
        }

        /* Premium Buttons */
        .btn-primary {
            background: linear-gradient(72.47deg, #7367f0 22.16%, rgba(115, 103, 240, 0.7) 76.47%);
            color: #ffffff;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 0.125rem 0.25rem 0 rgba(115, 103, 240, 0.4);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 0.25rem 0.75rem 0 rgba(115, 103, 240, 0.5);
            opacity: 0.9;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary-outline {
            background-color: transparent;
            border: 1.5px solid #7367f0;
            color: #7367f0;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary-outline:hover {
            background-color: rgba(115, 103, 240, 0.08);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #f1f0f2;
            color: #8e8593;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-secondary:hover {
            background-color: #e2e1e4;
            color: #7367f0;
        }

        .btn-danger {
            background-color: rgba(255, 76, 81, 0.1);
            color: #ff4c51;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-danger:hover {
            background-color: #ff4c51;
            color: #ffffff;
            box-shadow: 0 0.125rem 0.25rem 0 rgba(255, 76, 81, 0.4);
        }

        /* Icon Buttons */
        .btn-icon {
            width: 2.25rem;
            height: 2.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            color: #a5a3ae;
            transition: all 0.2s;
        }

        .btn-icon:hover {
            background-color: rgba(165, 163, 174, 0.1);
            color: #7367f0;
        }

        .btn-icon-danger:hover {
            background-color: rgba(255, 76, 81, 0.1);
            color: #ff4c51;
        }

        /* Tabler Icons Fix */
        .ti {
            font-size: 1.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="antialiased" x-data="{ sidebarOpen: false }">

    <!-- Unified Mobile Overlay -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[60] lg:hidden"></div>

    <!-- Sidebar Wrapper -->
    @include('admin.partials.sidebar')

    <!-- Stage Wrapper -->
    <div class="lg:ml-[260px] min-h-screen flex flex-col transition-all duration-300 ease-in-out">

        <!-- Header (Floating-ish) -->
        <div class="px-4 md:px-6 py-4 sticky top-0 z-40">
            @include('admin.partials.header')
        </div>

        <!-- Main Spotlight -->
        <main class="flex-1 p-4 md:p-6 lg:p-6">
            <div class="animate-fade-in">
                @yield('content')
            </div>
        </main>

        <!-- Notification Center -->
        @include('admin.partials.toasts')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <!-- Universal Media Player Modal -->
    <div x-data="universalMediaPlayer()" @open-media.window="open($event.detail)" x-show="isOpen"
        class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/95 backdrop-blur-xl"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

        <div class="relative w-full max-w-5xl aspect-video bg-black rounded-[2rem] overflow-hidden shadow-2xl border border-white/10"
            @click.away="close()">

            <!-- Close Button -->
            <button @click="close()"
                class="absolute top-6 right-6 z-50 w-12 h-12 rounded-full bg-black/50 text-white flex items-center justify-center hover:bg-amber-500 transition-all border border-white/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Player Content -->
            <div class="w-full h-full flex items-center justify-center">
                <!-- YouTube -->
                <template x-if="type === 'youtube'">
                    <iframe :src="'https://www.youtube.com/embed/' + youtubeId + '?autoplay=1'" class="w-full h-full"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </template>

                <!-- Audio -->
                <template x-if="type === 'audio'">
                    <div class="flex flex-col items-center gap-8 w-full p-12">
                        <div
                            class="w-32 h-32 rounded-3xl bg-gradient-to-br from-amber-500 to-amber-700 flex items-center justify-center shadow-2xl shadow-amber-500/20 animate-pulse-glow">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-serif text-white text-center" x-text="title"></h3>
                        <audio controls autoplay class="w-full max-w-xl" :src="url" ref="audioPlayer"></audio>
                    </div>
                </template>

                <!-- Video (Local) -->
                <template x-if="type === 'video'">
                    <video controls autoplay class="w-full h-full" :src="url"></video>
                </template>
            </div>
        </div>
    </div>

    <script>
        function universalMediaPlayer() {
            return {
                isOpen: false,
                type: '',
                url: '',
                title: '',
                youtubeId: '',

                open(detail) {
                    this.type = detail.type;
                    this.url = detail.url;
                    this.title = detail.title || 'Média';

                    if (this.type === 'youtube') {
                        this.youtubeId = this.extractYoutubeId(this.url);
                        if (!this.youtubeId) {
                            alert('Lien YouTube invalide');
                            return;
                        }
                    }

                    this.isOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                close() {
                    this.isOpen = false;
                    this.type = '';
                    this.url = '';
                    this.youtubeId = '';
                    document.body.style.overflow = 'auto';
                },

                extractYoutubeId(url) {
                    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                    const match = url.match(regExp);
                    return (match && match[2].length === 11) ? match[2] : null;
                }
            }
        }
    </script>
    @stack('scripts')
</body>

</html>