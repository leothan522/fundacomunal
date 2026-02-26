<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Ing. Yonathan Castillo">
    <meta name="generator" content="Bootstrap v5.3.7">

    <title>@yield('title', 'Morros Devops') - {{ config('app.name', 'Laravel') }}</title>

    {{-- Favicon y PWA --}}
    <meta name="description"
          content="Plataforma de planificación y organización de Fundacomunal para el estado Guárico">
    <meta name="theme-color" content="#0056b3">

    <meta property="og:title" content="Fundacomunal Guárico">
    <meta property="og:description"
          content="Plataforma de planificación y organización de Fundacomunal para el estado Guárico">
    <meta property="og:image" content="{{ asset('favicons/appicon-128x128.png') }}">

    {{-- Favicon y PWA --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/appicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicons/appicon-128x128.png') }}">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <!--Bootstrap -->
    {{--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">--}}
    @vite(['resources/js/bootstrap5.js', 'resources/js/sweetalert2.js', 'resources/js/web-app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400&display=swap" rel="stylesheet">

    <style>

        * {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .text_title {
            color: rgba(8, 23, 44, 1);
            font-weight: bold;
        }

        .gradient-custom-2 {
            /* fallback for old browsers */
            background: rgb(42, 177, 199);

            /* Chrome 10-25, Safari 5.1-6 */
            background: -webkit-linear-gradient(90deg, rgba(42, 177, 199, 1) 0%, rgba(41, 149, 209, 1) 50%, rgba(41, 94, 228, 1) 100%);

            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background: linear-gradient(90deg, rgba(42, 177, 199, 1) 0%, rgba(41, 149, 209, 1) 50%, rgba(41, 94, 228, 1) 100%);
        }

        @media (min-width: 768px) {
            .gradient-form {
                height: 100vh !important;
            }
        }

        @media (min-width: 769px) {
            .gradient-custom-2 {
                border-top-right-radius: .3rem;
                border-bottom-right-radius: .3rem;
            }
        }

        @media (min-width: 768px) {
            #scale {
                transform: scale(0.8); /* Reduce el tamaño al 80% */
            }
        }

        /*--------------------------------------------------------------
        # Imagenes Extras
        --------------------------------------------------------------*/
        .mpp_comunas {
            display: block;
            position: absolute;
            width: 45%; /* ocupa todo el ancho del contenedor padre */
            height: auto; /* mantiene proporción */
            right: 3%;
            top: 3%;
        }

        .gobernacion {
            display: block;
            position: absolute;
            height: 80px;
            width: 80px;
            right: 5%;
            top: 5%;
        }

        .gobernacion_rigth {
            display: block;
            position: absolute;
            height: 80px;
            width: 80px;
            left: 5%;
            top: 5%;
        }

    </style>

    @stack('css')
    @livewireStyles
</head>
<body style="background-color: #eee;">

<div class="position-relative gradient-form" style="min-height: 100vh; z-index: 2; /* siempre por encima del footer */">
    <div class="position-absolute top-50 start-50 translate-middle container">


        <div id="scale" class="row d-flex justify-content-center align-items-center">
            <div class="col-xl-10">
                <div class="card rounded-3 text-black">
                    <div class="row g-0">
                        <div class="col">
                            <div class="card-body p-md-5 mx-md-4 position-relative" id="card_body">

                                <img class="gobernacion_rigth mt-3" src="{{ asset('img/logo.png') }}"
                                     alt="Logo Fundacomunal Guárico">

                                <img class="gobernacion mt-3" src="{{ asset('img/logo_gobernacion_white.png') }}"
                                     alt="Logo Gobernación Guárico">

                                <div class="text-center mt-5 mb-5">
                                    <h5 class="text_title">
                                        <strong>Comuna</strong>
                                    </h5>
                                </div>

                                @yield('content')

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<!-- Footer Sticky -->
{{--<footer class="text-center py-2 bg-light border-top fixed-bottom" style="z-index: 1;">
    <small class="text-muted">Desarrollado por Ing. Yonathan Castillo</small>
</footer>--}}

@stack('js')
@livewireScripts
@include('sweetalert2::index')

</body>
</html>
