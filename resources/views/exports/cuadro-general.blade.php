<table>
    <thead>
    <tr>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">MUNICIPIOS</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">CANTIDAD DE C.C.</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">CIRCUITOS O COMUNAS</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center">C.C. EN CIRCUITOS O COMUNAS</th>
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $data)
        @php
            $countCC = $data->consejos->count();
            $countComunas = $data->comunas->count();
            $countVinculados = \App\Models\ConsejoComunal::where('municipios_id', $data->id)->has('comuna')->count();

            $consejosComunales = $consejosComunales + $countCC;
            $comunas = $comunas + $countComunas;
            $vinculados = $vinculados + $countVinculados;
        @endphp
        <tr>
            <td style="border: 1px solid #404040; text-align: center">{{ \Illuminate\Support\Str::upper($data->nombre) }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ $countCC }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ $countComunas }}</td>
            <td style="border: 1px solid #404040; text-align: center">{{ $countVinculados }}</td>
        </tr>
    @endforeach

    <tr>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center; font-size: 14pt">TOTALES</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center; font-size: 14pt">{{ $consejosComunales }}</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center; font-size: 14pt">{{ $comunas }}</th>
        <th style="background-color: #C00000; color: #ffffff; border: 1px solid #404040; font-weight: bold; text-align: center; font-size: 14pt">{{ $vinculados }}</th>
    </tr>

    </tbody>
</table>
