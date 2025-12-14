<?php

namespace App\Exports;

use App\Models\Formacion;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FormacionExport implements FromView, WithTitle, WithColumnFormatting, WithStyles
{
    protected mixed $inicio;
    protected mixed $final;

    public function __construct($inicio, $final)
    {
        $this->inicio = $inicio;
        $this->final = $final;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $query = Formacion::query();
        if (!isAdmin()) {
            $query->where(function (Builder $subQuery) {
                $subQuery->whereRelation('promotor', 'users_id', auth()->id())
                    ->orWhere('users_id', auth()->id());
            });

        }
        $actividades = $query->whereBetween('fecha', [$this->inicio, $this->final])->orderBy('fecha')->get();
        return \view('exports.formacion')
            ->with('rows', $actividades)
            ->with('i', 0);
    }

    public function columnFormats(): array
    {
        return  [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Fila 2 con altura de 50 puntos
        $sheet->getRowDimension(2)->setRowHeight(50);

        // Ajustar texto y ancho
        $sheet->getStyle('A2:AB2')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);



        $sheet->getColumnDimension('A')->setWidth(7);
        $sheet->getColumnDimension('B')->setWidth(16);
        foreach (range('C', 'G') as $col){
            $sheet->getColumnDimension($col)->setWidth(25);
        }
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(25);
        $sheet->getColumnDimension('J')->setWidth(30);
        $sheet->getColumnDimension('K')->setWidth(30);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(50);
        $sheet->getColumnDimension('N')->setWidth(40);
        $sheet->getColumnDimension('O')->setWidth(26);
        $sheet->getColumnDimension('P')->setWidth(26);
        $sheet->getColumnDimension('Q')->setWidth(17);
        $sheet->getColumnDimension('R')->setWidth(17);
        $sheet->getColumnDimension('S')->setWidth(26);
        foreach (range('T', 'Y') as $col){
            $sheet->getColumnDimension($col)->setWidth(25);
        }
        $sheet->getColumnDimension('Z')->setWidth(40);
        $sheet->getColumnDimension('AA')->setWidth(30);
        $sheet->getColumnDimension('AB')->setWidth(30);
    }

    public function title(): string
    {
        return  'FORMACIÓN';
    }
}
