@extends('layouts.bootstrap')

@section('title', 'Descargar APP')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
@endpush

@section('content')

    <div class="row mb-2 mt-md-auto">
        <div x-data="{ cargando: false }" class="col-6 text-center">
            <span>Android</span>
            <img class="img-fluid" src="{{ $qrAndroid }}" alt="Codigo QR" />
            <a  @click="cargando = true; setTimeout(() => cargando = false, 3000);" class="text-decoration-none" href="{{ route('descargar-app.android') }}">
                <i class="bi bi-cloud-arrow-down-fill"></i> Descargar
            </a>
            <div class="d-flex justify-content-center">
                <div x-show="cargando"  class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        <div x-data class="col-6 text-center">
            <span>IOS</span>
            <img class="img-fluid" src="{{ $qrIos }}" alt="Codigo QR" />
            <a class="text-decoration-none" href="{{ route('web.index') }}" @click="mostrarPreloader">
                <i class="bi bi-arrow-up-right-square"></i> Abrir
            </a>
        </div>
        <div class="mt-3">
            <p class="fs-6 d-flex" style="text-align: justify !important;">
                <small class="text-muted">
                    Instalación directa en <strong>iPhone</strong> desde Safari: Abre el enlace del código QR para <span class="color-active">IOS</span> en Safari. Toca “Compartir” → “Agregar a pantalla de inicio”. Se instala la app y listo.
                </small>
            </p>
        </div>
    </div>

    <div x-data class="text-center pt-1 mb-5 pb-1">
        @auth
            <a class="text-muted" href="{{ route('profile.show') }}" @click="mostrarPreloader">{{ __('Profile') }}</a>
            <a class="text-muted ms-3" href="{{ url('/dashboard') }}" @click="mostrarPreloader">{{ __('Dashboard') }}</a>
        @else
            <a class="text-muted" href="{{ route('login') }}" @click="mostrarPreloader">{{ __('Log in') }}</a>
            @if (Route::has('register'))
                <a class="text-muted ms-3" href="{{ route('register') }}" @click="mostrarPreloader">{{ __('Register') }}</a>
            @endif
        @endauth
    </div>

@endsection
