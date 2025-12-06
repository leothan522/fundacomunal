<?php

namespace App\Providers;

use Filament\Auth\Http\Responses\Contracts\LogoutResponse;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponse::class, \App\Http\Responses\LogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Configuring Livewire's update endpoint
        if (config('app.livewire')){
            Livewire::setUpdateRoute(function ($handle) {
                return Route::post('/'.config('app.livewire').'/livewire/update', $handle)->name('assetlivewire.update');
            });
            Livewire::setScriptRoute(function ($handle) {
                return Route::get('/'.config('app.livewire').'/livewire/livewire.js', $handle);
            });
        }

        //Loader en el Dashboard
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_START,
            fn(): string => Blade::render(view('components.loader-css')->render())
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn(): string => Blade::render(view('components.loader-html')->render())
        );

    }
}
