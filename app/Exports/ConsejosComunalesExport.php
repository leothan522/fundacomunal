<?php

namespace App\Exports;

use App\Models\ConsejoComunal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ConsejosComunalesExport implements FromView, WithTitle, ShouldAutoSize, WithColumnFormatting
{
    public function view(): View
    {
        $consejosComunales = ConsejoComunal::get();
        return \view('exports.consejos-comunales')
            ->with('consejos', $consejosComunales);
    }

    public function title(): string
    {
        return 'CONSOLIDADO';
    }

    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'K' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}
