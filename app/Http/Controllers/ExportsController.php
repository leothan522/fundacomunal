<?php

namespace App\Http\Controllers;

use App\Exports\ConsejosComunalesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportsController extends Controller
{
    public function exportConsejosComunales()
    {
        return Excel::download(new ConsejosComunalesExport(), 'prueba.xlsx');
    }
}
