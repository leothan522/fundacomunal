<table>
    <thead>
    <tr>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">MUNICIPIO</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">PARROQUIA</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">COD. COM</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">COd. SITUR</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">CIRCUITO O COMUNA</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">CANTIDAD C.C.</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">TIPO</th>
    </tr>
    </thead>
    <tbody>

    @foreach($rows as $data)
        <tr>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->municipio->nombre) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->parroquia) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->cod_com) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->cod_situr) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->nombre) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ $data->consejos->count() }}</td>
            @php
                $tipos = $data->consejos->map(fn($c) => $c->tipo->nombre)->unique();

                $clasificacion = $tipos->count() === 1
                    ? $tipos->first()
                    : 'MIXTA';
            @endphp

            <td style="border: 1px solid #404040; text-align: center">
                {{ \Illuminate\Support\Str::upper($clasificacion) }}
            </td>



        </tr>
    @endforeach

    </tbody>
</table>
