<?php

namespace App\Exports;

use App\Models\GestionHumana;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class GestionHumanaExport implements FromView, WithTitle, ShouldAutoSize, WithColumnFormatting
{
    public function view(): View
    {
        $gestionHumana = GestionHumana::orderBy('cedula')->get();
        return \view('exports.gestion-humana')
            ->with('rows', $gestionHumana)
            ->with('i', 0);
    }

    public function columnFormats(): array
    {
        return  [
            'P' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'Q' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function title(): string
    {
        return 'GESTION HUMANA';
    }
}
