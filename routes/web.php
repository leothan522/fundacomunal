<?php

use App\Http\Controllers\ExportsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('inicio');
})->name('web.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/home', function () {
        return redirect()->route('web.index');
        //return view('dashboard');
    })->name('home');
});

Route::get('/instalar-app', function () {
    //$qrAndroid = qrCodeGenerate(\route('descargar-app.android'), null, null, 'qr-android-download');
    $qrIos = qrCodeGenerate(\route('web.index'), null, null, 'qr-ios-download');
    return view('descargar-app')
       //->with('qrAndroid', $qrAndroid)
        ->with('qrIos', $qrIos);
})->name('instalar-app');

/*Route::get('/apk', function (){
    $path = 'descargas/Fundacomunal.apk';
    if (Storage::disk('public')->exists($path)){
        $fullPath = Storage::disk('public')->path($path);
        return response()->download($fullPath, 'Fundacomunal.apk', [
            'Content-Type' => 'application/vnd.android.package-archive',
        ]);
    }
    return redirect()->route('web.index');
})->name('descargar-app.android');*/

Route::get('descargar/data-obpp', [ExportsController::class, 'exportConsejosComunales'])->name('descargar.data-obpp');
Route::get('descargar/gestion-humana', [ExportsController::class, 'exportGestionHumana'])->name('descargar.gestion-humana');
Route::get('descargar/participacion/{tipoReporte}', [ExportsController::class, 'exportParticipacion'])->name('descargar.participacion');
Route::get('descargar/formacion/{tipoReporte}', [ExportsController::class, 'exportFormacion'])->name('descargar.formacion');
