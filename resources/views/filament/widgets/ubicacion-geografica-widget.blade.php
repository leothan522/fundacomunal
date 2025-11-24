<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Widget content --}}


        <div x-data class="fi-filament-info-widget-main">
            <x-filament::link
                href="{{ route('filament.dashboard.resources.gestion-humana.index') }}"
                :icon="\Filament\Support\Icons\Heroicon::OutlinedMapPin"
                @click="Alpine.store('loader').show()"
            >
                <strong>Ubicación Geográfica </strong>
            </x-filament::link>

            <p class="fi-filament-info-widget-version">
                {{ $totalTrabajadores }} Trabajadores
            </p>
        </div>

        <div class="fi-filament-info-widget-links">
            @foreach($municipios as $municipio)
                <x-filament::link
                    color="gray"
                    href="{{ route('filament.dashboard.resources.gestion-humana.index').'?filters[Municipio][value]='.$municipio->id }}"
                    {{--:icon="\Filament\Support\Icons\Heroicon::BookOpen"
                    :icon-alias="\Filament\View\PanelsIconAlias::WIDGETS_FILAMENT_INFO_OPEN_DOCUMENTATION_BUTTON"--}}
                    {{--rel="noopener noreferrer"
                    target="_blank"--}}
                    @click="Alpine.store('loader').show()"
                >
                    {{ \Illuminate\Support\Str::upper($municipio->nombre) }} - {{ cerosIzquierda($municipio->trabajadores->count()) }}
                </x-filament::link>
            @endforeach
        </div>

    </x-filament::section>
</x-filament-widgets::widget>
