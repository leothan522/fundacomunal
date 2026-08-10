{{--<x-filament-widgets::widget>
    <x-filament::section>
        --}}{{-- Widget content --}}{{--

        <div x-data class="fi-filament-info-widget-main">
            <x-filament::link
                href="{{ route('filament.dashboard.resources.gestion-humana.index') }}"
                :icon="\Filament\Support\Icons\Heroicon::OutlinedBookmark"
                @click="Alpine.store('loader').show()"
            >
                <strong>Categoria Personal</strong>
            </x-filament::link>

            <p class="fi-filament-info-widget-version">
                {{ $totalTrabajadores }} Trabajadores
            </p>
        </div>

        <div class="fi-filament-info-widget-links">
            @foreach($categorias as $categoria)
                <x-filament::link
                    color="gray"
                    href="{{ route('filament.dashboard.resources.gestion-humana.index').'?filters[Categoria][value]='.$categoria->id }}"
                    --}}{{--:icon="\Filament\Support\Icons\Heroicon::BookOpen"
                    :icon-alias="\Filament\View\PanelsIconAlias::WIDGETS_FILAMENT_INFO_OPEN_DOCUMENTATION_BUTTON"--}}{{--
                    --}}{{--rel="noopener noreferrer"
                    target="_blank"--}}{{--
                    @click="Alpine.store('loader').show()"
                >
                    {{ \Illuminate\Support\Str::upper($categoria->nombre) }} - {{ cerosIzquierda($categoria->trabajadores->count()) }}
                </x-filament::link>
            @endforeach
        </div>

    </x-filament::section>
</x-filament-widgets::widget>--}}

<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Widget content --}}

        <div x-data class="fi-filament-info-widget-main" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
            <div>
                <x-filament::link
                    href="{{ route('filament.dashboard.resources.gestion-humana.index') }}"
                    :icon="\Filament\Support\Icons\Heroicon::OutlinedBookmark"
                    @click="Alpine.store('loader').show()"
                >
                    <strong>Categoría Personal</strong>
                </x-filament::link>

                <p class="fi-filament-info-widget-version" style="margin-top: 0.25rem;">
                    {{ $totalTrabajadores }} Trabajadores en Total
                </p>
            </div>

            {{-- Indicador superior unificado --}}
            <div style="text-align: right;">
                <x-filament::link
                    href="{{ route('filament.dashboard.resources.vacaciones.index') }}"
                    :icon="\Filament\Support\Icons\Heroicon::OutlinedSun"
                    color="warning"
                    @click="Alpine.store('loader').show()"
                >
                    <strong>En Vacaciones</strong>
                </x-filament::link>
                <p class="fi-filament-info-widget-version" style="margin-top: 0.25rem; font-weight: bold; color: rgb(234, 179, 8);">
                    {{ cerosIzquierda($totalVacacionesActivas) }} Activas
                </p>
            </div>
        </div>

        <div class="fi-filament-info-widget-links">
            @foreach($categorias as $categoria)
                @php
                    // Comprobamos si la categoría actual es la de Vacaciones
                    $esCategoriaVacaciones = \Illuminate\Support\Str::contains(\Illuminate\Support\Str::upper($categoria->nombre), 'VACACIONES');

                    // Si es la categoría de vacaciones, usamos el total unificado; si no, el conteo normal de la relación
                    $cantidadMostrar = $esCategoriaVacaciones ? $totalVacacionesActivas : $categoria->trabajadores->count();

                    // Link
                    $link = $esCategoriaVacaciones ?
                        route('filament.dashboard.resources.vacaciones.index') :
                        route('filament.dashboard.resources.gestion-humana.index').'?filters[Categoria][value]='.$categoria->id;
                @endphp

                <x-filament::link
                    color="gray"
                    href="{{ route('filament.dashboard.resources.gestion-humana.index').'?filters[Categoria][value]='.$categoria->id }}"
                    @click="Alpine.store('loader').show()"
                >
                    {{ \Illuminate\Support\Str::upper($categoria->nombre) }} - {{ cerosIzquierda($cantidadMostrar) }}
                </x-filament::link>
            @endforeach
        </div>

    </x-filament::section>
</x-filament-widgets::widget>
