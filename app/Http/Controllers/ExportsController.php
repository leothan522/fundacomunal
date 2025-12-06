<?php

namespace App\Http\Controllers;

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
        switch ($tipoReporte) {
            case 'semana-actual':
                $inicio = Carbon::now()->startOfWeek();
                $fin    = Carbon::now()->endOfWeek();
                break;

            case 'semana-anterior':
                $inicio = Carbon::now()->subWeek()->startOfWeek();
                $fin    = Carbon::now()->subWeek()->endOfWeek();
                break;

            case 'semana-proxima':
                $inicio = Carbon::now()->addWeek()->startOfWeek();
                $fin    = Carbon::now()->addWeek()->endOfWeek();
                break;

            case 'mes-actual':
                $inicio = Carbon::now()->startOfMonth();
                $fin    = Carbon::now()->endOfMonth();
                break;

            case 'mes-anterior':
                $inicio = Carbon::now()->subMonth()->startOfMonth();
                $fin    = Carbon::now()->subMonth()->endOfMonth();
                break;

            default:
                $inicio = null;
                $fin = null;
        }

        $nombre = Str::upper($tipoReporte);

        return Excel::download(new ParticipacionExport($inicio, $fin), "PARTICIPACION_$nombre.xlsx");
    }
}
