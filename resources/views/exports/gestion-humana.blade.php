<table>
    <thead>
    <tr>
        <th colspan="2">&nbsp;</th>
        <th colspan="4" style="background-color: #F2F2F2; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">{{ \Illuminate\Support\Str::upper('Ubicación Geográfica') }}</th>
        <td colspan="2">&nbsp;</td>
        <td colspan="5" style="background-color: #757171; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">DATOS DEL TRABAJADOR</td>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <th style="background-color: #F2F2F2; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">NRO.</th>
        <th style="background-color: #F2F2F2; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">FECHA</th>
        <th style="background-color: #F2F2F2; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">REDI</th>
        <th style="background-color: #F2F2F2; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">ESTADO</th>
        <th style="background-color: #F2F2F2; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">MUNICIPIO</th>
        <th style="background-color: #F2F2F2; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">PARROQUIA</th>
        <th style="background-color: #AEAAAA; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">LABOR QUE EJERCE</th>
        <th style="background-color: #AEAAAA; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">CATEGORIA</th>
        <th style="background-color: #757171; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">NOMBRE</th>
        <th style="background-color: #757171; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">APELLIDO</th>
        <th style="background-color: #757171; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">CÉDULA</th>
        <th style="background-color: #757171; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">TELÉFONO</th>
        <th style="background-color: #757171; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">CORREO</th>
        <th style="background-color: #757171; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">ÓRGANO O ENTE ADSCRITO</th>
        <th style="background-color: #757171; color: #000000; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">OBSERVACIÓN</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">FECHA DE NACIMIENTO</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #000000; font-weight: bold; text-align: center; font-size: 12pt">FECHA DE INGRESO</th>
    </tr>
    </thead>
    <tbody>

    @foreach($rows as $data)
        <tr>
            <td style="border: 1px solid #404040; text-align: center">{{ ++$i }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ date('d/m/Y') }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->redi->nombre) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->estado->nombre) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->municipio->nombre) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->parroquia) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->tipoPersonal->nombre) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->categoria->nombre) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->nombre) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->apellido) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->cedula) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->telefono) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::lower($data->email) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->ente) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->observacion) }}</td>
            <td style="border: 1px solid #404040; text-align: center">
                {{ $data->fecha_nacimiento ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel(\Carbon\Carbon::parse($data->fecha_nacimiento)) : null }}
            </td>
            <td style="border: 1px solid #404040; text-align: center">
                {{ $data->fecha_ingreso ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel(\Carbon\Carbon::parse($data->fecha_ingreso)) : null }}
            </td>
        </tr>
    @endforeach

    </tbody>
</table>
