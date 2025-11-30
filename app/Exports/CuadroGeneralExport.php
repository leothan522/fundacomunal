<?php

namespace App\Exports;

use App\Models\Municipio;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CuadroGeneralExport implements FromView, WithTitle, ShouldAutoSize, WithColumnFormatting
{
    public function view(): View
    {
        $municipios = Municipio::whereRelation('estado', 'nombre', 'GUARICO')->orderBy('nombre')->get();
        return \view('exports.cuadro-general')
            ->with('rows', $municipios)
            ->with('consejosComunales', 0)
            ->with('comunas', 0)
            ->with('vinculados', 0);
    }

    public function title(): string
    {
        return 'CUADRO GENERAL';
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
