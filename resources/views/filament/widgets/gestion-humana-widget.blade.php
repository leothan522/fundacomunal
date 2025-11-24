<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Widget content --}}

        <div x-data class="fi-filament-info-widget-main">
            <x-filament::link
                href="{{ route('filament.dashboard.resources.gestion-humana.index') }}"
                :icon="\Filament\Support\Icons\Heroicon::OutlinedUserGroup"
                @click="Alpine.store('loader').show()"
            >
                <strong>Gestión Humana</strong>
            </x-filament::link>

            <p class="fi-filament-info-widget-version">
                {{ $totalTrabajadores }} Tranajadores
            </p>
        </div>

        <div class="fi-filament-info-widget-links">
            @foreach($tipoPersonal as $tipo)
                <x-filament::link
                    color="gray"
                    href="{{ route('filament.dashboard.resources.gestion-humana.index').'?filters[Tipo%20Personal][value]='.$tipo->id }}"
                    {{--:icon="\Filament\Support\Icons\Heroicon::BookOpen"
                    :icon-alias="\Filament\View\PanelsIconAlias::WIDGETS_FILAMENT_INFO_OPEN_DOCUMENTATION_BUTTON"--}}
                    {{--rel="noopener noreferrer"
                    target="_blank"--}}
                    @click="Alpine.store('loader').show()"
                >
                    {{ \Illuminate\Support\Str::upper($tipo->nombre) }} - {{ cerosIzquierda($tipo->trabajadores->count()) }}
                </x-filament::link>
            @endforeach
        </div>

    </x-filament::section>
</x-filament-widgets::widget>
