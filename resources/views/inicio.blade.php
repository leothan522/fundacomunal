@extends('layouts.bootstrap')

@section('title', 'Inicio')

@section('content')
    <div x-data class="text-center mb-5 mb-sm-auto">
        @auth
            <a class="text-muted" href="{{ route('profile.show') }}" @click="mostrarPreloader">{{ __('Profile') }}</a>
            <a class="text-muted ms-3" href="{{ url('/dashboard') }}" @click="mostrarPreloader">{{ __('Dashboard') }}</a>
        @else
            <a class="text-muted" href="{{ route('login') }}" @click="mostrarPreloader">{{ __('Log in') }}</a>
            @if (Route::has('register'))
                <a class="text-muted ms-3" href="{{ route('register') }}" @click="mostrarPreloader">{{ __('Register') }}</a>
            @endif
            <a class="text-muted ms-3 text-nowrap" href="{{ route('instalar-app') }}" @click="mostrarPreloader">Instalar App</a>
        @endauth
    </div>
@endsection
