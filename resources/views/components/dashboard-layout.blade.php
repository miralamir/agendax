<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BAMARTE') }} - Dashboard</title>
        <!-- Favicons -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <!-- Google Fonts: Lato (Unified) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
        
        <!-- Scripts -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Custom Styles -->
        <style>
            :root {
                /* Gray Scale */
                --gray-900: #111;
                --gray-800: #222;
                --gray-700: #444;
                --gray-600: #555;
                --gray-500: #888;
                --gray-400: #ccc;
                --gray-300: #ddd;
                --gray-200: #ebebeb;
                --gray-100: #f0f0f0;

                /* Disciplines */
                --color-visuales: #7B2D8B;
                --color-visuales-light: #f3eafc;
                --color-musica: #1A3A7C;
                --color-musica-light: #e6edf9;
                --color-teatro: #8B1A2D;
                --color-teatro-light: #faeaed;
                --color-cine: #1a1a2e;
                --color-cine-light: #e8e8f0;
                --color-literatura: #1A5C3A;
                --color-literatura-light: #eafaf0;

                /* Borders */
                --border-color: #e0e0e0;
                --border-radius: 8px;
            }

            body {
                font-family: 'Lato', sans-serif;
                background-color: var(--gray-100);
                color: var(--gray-800);
            }

            .section-title {
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 2px;
                font-weight: 700;
                color: var(--gray-600);
                padding-left: 1rem;
                border-left: 3px solid var(--gray-300);
                margin-bottom: 2rem;
            }
            
            /* Dashboard specific styles */
            .dashboard-navbar {
                background-color: #fff;
                border-bottom: 1px solid var(--gray-400);
            }
            .dashboard-sidebar {
                background-color: #fff;
                border-right: 1px solid var(--gray-300);
            }
            .dashboard-badge-admin {
                background-color: var(--gray-200);
                color: var(--gray-700);
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 700;
            }
            .dashboard-button-primary {
                background-color: var(--gray-900);
                color: #fff;
                font-weight: 700;
                border-radius: 20px;
                padding: 0.5rem 1.5rem;
            }
            .dashboard-button-outline {
                border: 1px solid var(--gray-600);
                color: var(--gray-600);
                font-weight: 700;
                border-radius: 20px;
                padding: 0.5rem 1.5rem;
            }
            .dashboard-section-title {
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 2px;
                font-weight: 700;
                color: var(--gray-900);
                padding-left: 1rem;
                border-left: 3px solid var(--gray-600);
                margin-bottom: 1.5rem;
            }
            .dashboard-input {
                border: 1px solid var(--gray-400);
                border-radius: 6px;
                font-family: 'Lato', sans-serif;
                padding: 0.5rem 0.75rem;
            }
            .dashboard-input:focus {
                border-color: var(--gray-600);
                outline: none;
                box-shadow: 0 0 0 1px var(--gray-600);
            }
            .dashboard-label {
                font-family: 'Lato', sans-serif;
                font-weight: 700;
                font-size: 12px;
                color: var(--gray-700);
            }
            .dashboard-table-header {
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 2px;
                color: var(--gray-500);
            }
            .dashboard-table-row:hover {
                background-color: var(--gray-100);
            }

            /* Category Badges */
            .badge-visuales { background-color: var(--color-visuales-light); color: var(--color-visuales); }
            .badge-musica { background-color: var(--color-musica-light); color: var(--color-musica); }
            .badge-teatro { background-color: var(--color-teatro-light); color: var(--color-teatro); }
            .badge-cine { background-color: var(--color-cine-light); color: var(--color-cine); }
            .badge-literatura { background-color: var(--color-literatura-light); color: var(--color-literatura); }

        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <div class="dashboard-sidebar w-64 flex-shrink-0 p-6">
                <a href="{{ route('home') }}" class="text-2xl font-black mb-10" style="font-family: 'Lato', sans-serif; font-weight: 900;">
                    BAM<span style="color: var(--gray-600);">ARTE</span>
                </a>
                <nav class="mt-8">
                    <ul>
                        <li class="mb-3"><a href="{{ route('dashboard.eventos.index') }}" class="font-bold text-gray-800 hover:text-gray-900">Eventos</a></li>
                        <li class="mb-3"><a href="{{ route('dashboard.novedades.index') }}" class="font-bold text-gray-800 hover:text-gray-900">Novedades</a></li>
                        <li class="mb-3"><a href="{{ route('dashboard.creadores.index') }}" class="font-bold text-gray-800 hover:text-gray-900">Creadores</a></li>
                        <li class="mb-3"><a href="{{ route('dashboard.usuarios.index') }}" class="font-bold text-gray-800 hover:text-gray-900">Usuarios</a></li>
                        <li class="mb-3"><a href="{{ route('dashboard.banners.index') }}" class="font-bold text-gray-800 hover:text-gray-900">Publicidad</a></li>
                        <li class="mb-3"><a href="#" class="font-bold text-gray-400 cursor-not-allowed">Configuración (próximamente)</a></li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1">
                <!-- Navbar del Dashboard -->
                <header class="dashboard-navbar py-4 px-6 flex justify-between items-center">
                    <div class="flex items-center">
                        <h1 class="text-xl font-black">Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="dashboard-badge-admin">Admin</span>
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Cerrar Sesión') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <main style="max-width: 1200px; margin: 0 auto; padding: 24px 32px;">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
