@extends('layouts.export-img')

@section('content')
    <div class="row">
        <div class="mt-3 col-12">
            <ol class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="me-auto">
                        <small>Nombre:</small>
                        <div class="fw-bold">{{ $record->nombre }}</div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="me-auto">
                        <small>COD. COM @if($record->consejos_count > 19) / COD. SITUR @endif</small>
                        <div class="fw-bold">{{ $record->cod_com }} @if($record->consejos_count > 19) / {{ $record->cod_situr }}@endif </div>
                    </div>
                </li>
                @if($record->consejos_count < 20)
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="me-auto">
                            <small>COD. SITUR</small>
                            <div class="fw-bold">{{ $record->cod_situr }}</div>
                        </div>
                    </li>
                @endif
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="me-auto">
                        <div class="fw-bold">Consejos Comunales:</div>
                        @if($record->consejos_count)
                            <ol>
                                @foreach($record->consejos as $consejo)
                                    <li class="ps-2">
                                        <small>{{ \Illuminate\Support\Str::upper($consejo->nombre) }}</small></li>
                                @endforeach
                            </ol>
                        @endif
                    </div>
                    <span class="badge text-bg-primary rounded-pill">{{ $record->consejos_count }}</span>
                </li>
            </ol>
        </div>
        <div class="mt-4 col-12">
            <ol class="list-group">
                @if($record->consejos_count < 20)
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="me-auto">
                            <small>Municipio</small>
                            <div class="fw-bold">{{ $record->municipio->nombre }}</div>
                        </div>
                    </li>
                @endif
                @if($record->consejos_count < 17)
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="me-auto">
                            <small>Parroquia</small>
                            <div class="fw-bold">{{ $record->parroquia }}</div>
                        </div>
                    </li>
                @endif
            </ol>
        </div>
    </div>
@endsection
