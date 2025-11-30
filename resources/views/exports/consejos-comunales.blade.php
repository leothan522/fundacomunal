<table>
    <thead>
    <tr>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">MUNICIPIO</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">PARROQUIA</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">TIPO CONSEJO COMUNAL</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">CODIGO COM</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">CODIGO CIRCUITO</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">CIRCUITO O COMUNA</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">SITUR VIEJO</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">SITUR NUEVO OBPP</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">CONSEJOS COMUNALES</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">FECHA DE ASAMBLEA</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">FECHA DE VENCIMIENTO</th>
    </tr>
    </thead>
    <tbody>

    @foreach($consejos as $data)
        <tr>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->municipio->nombre) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->parroquia) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->tipo) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ $data->comuna ? \Illuminate\Support\Str::upper($data->comuna->cod_com) : null }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ $data->comuna ? \Illuminate\Support\Str::upper($data->comuna->cod_situr) : null }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ $data->comuna ? \Illuminate\Support\Str::upper($data->comuna->nombre) : null }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ $data->situr_viejo ? \Illuminate\Support\Str::upper($data->situr_viejo) : null }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ $data->situr_nuevo ? \Illuminate\Support\Str::upper($data->situr_nuevo) : null }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->nombre) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ $data->fecha_asamblea ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel(\Carbon\Carbon::parse($data->fecha_asamblea)) : null }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ $data->fecha_vencimiento ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel(\Carbon\Carbon::parse($data->fecha_vencimiento)) : null }}</td>
        </tr>
    @endforeach

    </tbody>
</table>
