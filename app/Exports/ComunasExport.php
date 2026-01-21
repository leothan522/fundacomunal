<?php

namespace App\Exports;

use App\Models\Comuna;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ComunasExport implements FromView, WithTitle, ShouldAutoSize
{
    public function view(): View
    {
        $comunas = Comuna::orderBy('municipios_id')->get();
        return view('exports.comunas')
            ->with('rows', $comunas);
    }

    public function title(): string
    {
        return 'CIRCUITOS Y COMUNAS';
    }
}
