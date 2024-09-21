<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarcaController;

Route::get('/', function () {
    return view('plantilla');
});

/*################
    CRUD de marcas
 * */
Route::get('/marcas', [ MarcaController::class, 'index' ] );
Route::get('/marca/create', [ MarcaController::class, 'create' ]);
Route::post('/marca/store', [ MarcaController::class, 'store' ]);
Route::get('/marca/edit/{id}', [ MarcaController::class, 'edit' ]);
Route::patch('/marca/update', [ MarcaController::class, 'update' ]);
Route::get('/marca/delete/{id}', [ MarcaController::class, 'delete' ]);
