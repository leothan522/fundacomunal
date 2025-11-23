<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <meta name="description" content="Plataforma de planificación y organización de Fundacomunal para el estado Guárico">
        <meta name="theme-color" content="#0056b3">

        <meta property="og:title" content="Fundacomunal Guárico">
        <meta property="og:description" content="Plataforma de planificación y organización de Fundacomunal para el estado Guárico">
        <meta property="og:image" content="{{ asset('favicons/appicon-128x128.png') }}">

        {{-- Favicon y PWA --}}
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/appicon-32x32.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('favicons/appicon-128x128.png') }}">

        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/sweetalert2.js'])

        <style>

            /*--------------------------------------------------------------
            # Preloader
            --------------------------------------------------------------*/
            #preloader {
                position: fixed;
                inset: 0;
                z-index: 999999;
                overflow: hidden;
                background: #ffffff;
                transition: all 0.6s ease-out;
            }

            #preloader:before {
                content: "";
                position: fixed;
                top: calc(50% - 30px);
                left: calc(50% - 30px);
                border: 6px solid #ffffff;
                border-color: #1977cc transparent #1977cc transparent;
                border-radius: 50%;
                width: 60px;
                height: 60px;
                animation: animate-preloader 1.5s linear infinite;
            }

            @keyframes animate-preloader {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

        </style>

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Preloader -->
        <div id="preloader"></div>

        @stack('modals')

        @livewireScripts
        @include('sweetalert2::index')

        <script>
            //Script para ejecurar el preloader
            window.addEventListener('load', function () {
                document.querySelector('#preloader').classList.add('hidden');
            });
        </script>

        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register("{{ asset('service-worker.js') }}")
                        .then(reg => console.log('✅ Service Worker registrado en:', reg.scope))
                        .catch(err => console.error('⚠️ Error al registrar el Service Worker:', err));
                });
            }
        </script>
    </body>
</html>
