<?php

namespace App\Http\Controllers;

use App\Exports\ConsejosComunalesExport;
use Illuminate\Http\Request;
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
        return Excel::download(new ConsejosComunalesExport(), 'prueba.xlsx');
    }
}
