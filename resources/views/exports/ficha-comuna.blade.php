@extends('layouts.export-img')

@section('content')
    <div class="row">
        <div class="mt-3 col-12">
            <h6 class="ms-4 text_title">
                <strong>Datos Básicos</strong>
            </h6>
            <ol class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="me-auto">
                        <small>Nombre:</small>
                        <div class="fw-bold">JUAN VICENTE TORREALBA</div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="me-auto">
                        <small>COD. COM</small>
                        <div class="fw-bold">COM_100801001</div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="me-auto">
                        <small>COD. SITUR</small>
                        <div class="fw-bold">C-URB-2018-11-0002</div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="me-auto">
                        <div class="fw-bold">Consejos Comunales:</div>
                    </div>
                    <span class="badge text-bg-primary rounded-pill">5</span>
                </li>
            </ol>
        </div>
        <div class="mt-4 col-12">
            <h6 class="ms-4 text_title">
                <strong>Ubicación Geográfica</strong>
            </h6>
            <ol class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="me-auto">
                        <small>REDI</small>
                        <div class="fw-bold">LLANOS</div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="me-auto">
                        <small>Estado</small>
                        <div class="fw-bold">CAMAGUAN</div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="me-auto">
                        <small>Municipio</small>
                        <div class="fw-bold">CAMAGUAN</div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="me-auto">
                        <small>Parroquia</small>
                        <div class="fw-bold">CAMAGUAN</div>
                    </div>
                </li>
            </ol>
        </div>
    </div>
@endsection
