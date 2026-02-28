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

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    @stack('scripts')
</body>

</html>