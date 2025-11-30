<?php

namespace App\Http\Controllers;

use App\Exports\ObppExport;
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
        return Excel::download(new ObppExport(), 'DATA_OBPP_GUARICO.xlsx');
    }
}
