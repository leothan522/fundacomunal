<?php

namespace App\Exports;

use App\Models\ConsejoComunal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ConsejosComunalesExport implements FromView, WithTitle, ShouldAutoSize
{
    public function view(): View
    {
        return \view('exports.consejos-comunales');
    }

    public function title(): string
    {
        return 'CONSOLIDADO';
    }
}
