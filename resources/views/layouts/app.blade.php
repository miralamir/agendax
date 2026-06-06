<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
    </body>
</html>
