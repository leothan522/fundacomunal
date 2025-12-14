<?php

namespace App\Http\Controllers;

use App\Exports\FormacionExport;
use App\Exports\GestionHumanaExport;
use App\Exports\ObppExport;
use App\Exports\ParticipacionExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;

class ExportsController extends Controller
{
    public mixed $inicio = null;
    public mixed $fin = null;

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportConsejosComunales()
    {
        return Excel::download(new ObppExport(), 'DATA_OBPP_GUARICO.xlsx');
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportGestionHumana()
    {
        return Excel::download(new GestionHumanaExport(), 'GESTION_HUMANA.xlsx');
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportParticipacion($tipoReporte)
    {
        $this->getFechas($tipoReporte);
        $nombre = Str::upper($tipoReporte);
        return Excel::download(new ParticipacionExport($this->inicio, $this->fin), "PARTICIPACION_$nombre.xlsx");
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportFormacion($tipoReporte)
    {
        $this->getFechas($tipoReporte);
        $nombre = Str::upper($tipoReporte);
        return Excel::download(new FormacionExport($this->inicio, $this->fin), "FORMACION_$nombre.xlsx");
    }

    /**
     * @param $tipoReporte
     * @return void
     */
    protected function getFechas($tipoReporte)
    {
        switch ($tipoReporte) {
            case 'semana-actual':
                $this->inicio = Carbon::now()->startOfWeek();
                $this->fin    = Carbon::now()->endOfWeek();
                break;

            case 'semana-anterior':
                $this->inicio = Carbon::now()->subWeek()->startOfWeek();
                $this->fin    = Carbon::now()->subWeek()->endOfWeek();
                break;

            case 'semana-proxima':
                $this->inicio = Carbon::now()->addWeek()->startOfWeek();
                $this->fin    = Carbon::now()->addWeek()->endOfWeek();
                break;

            case 'mes-actual':
                $this->inicio = Carbon::now()->startOfMonth();
                $this->fin    = Carbon::now()->endOfMonth();
                break;

            case 'mes-anterior':
                $this->inicio = Carbon::now()->subMonth()->startOfMonth();
                $this->fin    = Carbon::now()->subMonth()->endOfMonth();
                break;

            default:
                $this->inicio = null;
                $this->fin = null;
        }
    }
}
