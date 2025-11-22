@extends('layouts.bootstrap')

@section('title', 'Inicio')

@section('content')
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
