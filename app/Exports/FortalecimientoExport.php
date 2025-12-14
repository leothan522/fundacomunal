<?php

namespace App\Exports;

use App\Models\Fortalecimiento;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FortalecimientoExport implements FromView, WithTitle, WithColumnFormatting, WithStyles
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
        $query = Fortalecimiento::query();
        if (!isAdmin()) {
            $query->where(function (Builder $subQuery) {
                $subQuery->whereRelation('promotor', 'users_id', auth()->id())
                    ->orWhere('users_id', auth()->id());
            });

        }
        $actividades = $query->whereBetween('fecha', [$this->inicio, $this->final])->orderBy('fecha')->get();
        return \view('exports.fortalecimiento')
            ->with('rows', $actividades)
            ->with('i', 0);
    }

    public function columnFormats(): array
    {
        return  [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function styles(Worksheet $sheet): void
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
        $sheet->getColumnDimension('H')->setWidth(40);
        $sheet->getColumnDimension('I')->setWidth(25);
        $sheet->getColumnDimension('J')->setWidth(40);
        $sheet->getColumnDimension('K')->setWidth(40);
        $sheet->getColumnDimension('L')->setWidth(50);
        $sheet->getColumnDimension('M')->setWidth(40);
        $sheet->getColumnDimension('N')->setWidth(40);
        $sheet->getColumnDimension('O')->setWidth(25);
        $sheet->getColumnDimension('P')->setWidth(20);
        $sheet->getColumnDimension('Q')->setWidth(20);
        $sheet->getColumnDimension('R')->setWidth(50);
        foreach (range('S', 'Y') as $col){
            $sheet->getColumnDimension($col)->setWidth(25);
        }
        $sheet->getColumnDimension('Z')->setWidth(40);
        $sheet->getColumnDimension('AA')->setWidth(30);
        $sheet->getColumnDimension('AB')->setWidth(30);
    }

    public function title(): string
    {
        return  'FORTALECIMIENTO';
    }
}
