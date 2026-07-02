<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BAMARTE') }}</title>

        <!-- Favicons -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        {{-- Google AdSense (anuncios automáticos) - solo en producción --}}
        @production
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2677514599907084" crossorigin="anonymous"></script>
        @endproduction

        <!-- Google Fonts: Lato (Unified) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
        
        <!-- Scripts -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Custom Styles -->
        <style>
            [x-cloak] { display: none !important; }
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
                --color-cine: #E67E22;
                --color-cine-light: #fef3e8;
                --color-literatura: #2E8B57;
                --color-literatura-light: #e8f5ee;

                /* Borders */
                --border-color: #e0e0e0;
                --border-radius: 8px;
            }

            body {
                font-family: 'Lato', sans-serif;
                background-color: #fff;
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
            
            /* Navbar Hover Styles */
            nav a[href*="/arte"]:hover { color: var(--color-visuales); }
            nav a[href*="/musica"]:hover { color: var(--color-musica); }
            nav a[href*="/teatro"]:hover { color: var(--color-teatro); }
            nav a[href*="/cine"]:hover { color: var(--color-cine); }
            nav a[href*="/literatura"]:hover { color: var(--color-literatura); }

            /* Laravel Paginator Styles */
            .pagination {
                display: flex;
                list-style: none;
                padding: 0;
                margin: 0;
                justify-content: center;
            }
            .pagination li {
                margin: 0 4px;
            }
            .pagination li span, .pagination li a {
                display: block;
                padding: 8px 12px;
                border: 1px solid var(--gray-400);
                border-radius: 4px;
                background-color: #fff;
                color: var(--gray-700);
                font-size: 14px;
                font-weight: 700;
                text-decoration: none;
                transition: all 0.2s ease-in-out;
            }
            .pagination li a:hover {
                background-color: var(--gray-100);
                border-color: var(--gray-500);
            }
            .pagination li.active span {
                background-color: var(--gray-900);
                color: #fff;
                border-color: var(--gray-900);
            }
            .pagination li.disabled span {
                opacity: 0.6;
                cursor: not-allowed;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 {{ $theme ?? '' }}">
            <div class="container mx-auto px-4">
                            @if(request()->is('/'))
                @include('layouts.navigation-home')
            @else
                @include('layouts.navigation-internal')
            @endif

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
        <x-footer />
    </body>
</html>
