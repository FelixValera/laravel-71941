<?php

use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/

// Route::método('peticion', acción)
Route::view('/saludo.html', 'vista');
Route::get('/datos', function()
{
    //return 'hola mundo';
    return [
                'curso' => 'Desarrollo con Laravel',
                'codigo' => 71941,
                'inicio' => '20-07-2024',
                'fin' => '28-09-2024'
           ];
});
Route::get('/vista', function()
{
    //proceso que vamos a escribir luego
    $curso = 'Desarrollo con Laravel';
    //Retorno de una vista + pasaje de datos
    return view('vista',
                 [
                     'curso' => $curso,
                     'numero' => 7,
                     'zeppelin' => [
                                    'Jimmy Page',
                                    'Robert Palnt',
                                    'John Paul Jones',
                                    'Bonzo Bonham'
                                   ]
                 ]);
});

Route::view('/', 'dashboard');
/*#########################*/
##### CRUD de regiones
Route::get('/regiones', function ()
{
    //obtenemos listado de regiones
    /* $regiones = DB::select('SELECT * FROM regiones
                                ORDER BY idRegion DESC'); */
    $regiones = DB::table('regiones')
                        ->orderBy('idRegion', 'DESC')
                        ->get();
    return view('regiones', [ 'regiones'=>$regiones ]);
});
Route::get('/region/create', function ()
{
    return view('regionCreate');
});
Route::post('/region/store', function ()
{
    //capturamos datos enviados por el form
    $nombre = request()->nombre;
    try {
        //insertamos datos en tabla
        /* DB::insert('INSERT INTO regiones
                            ( nombre )
                        VALUE
                            ( :nombre )',
                            [ $nombre ]); */
        DB::table('regiones')
                ->insert([ 'nombre'=>$nombre ]);
        return redirect('/regiones')
                    ->with(
                        [
                            'css'=>'green',
                            'mensaje'=>'Region: '.$nombre.' agregada correctamente'
                        ]
                    );
    }catch ( Throwable $th){
        return redirect('/regiones')
            ->with(
                [
                    'css'=>'red',
                    'mensaje'=>'No se pudo agregar la región: '.$nombre
                ]
            );
    }
});
Route::get('/region/edit/{id}', function ( string $id )
{
    //Obtenemos datos de la región filtrada por su ID
    /* $region = DB::select('SELECT * FROM regiones
                            WHERE idRegion = :id',
                                    [ $id ]); */
    $region = DB::table('regiones')
                        ->where('idRegion', $id)
                        ->first();
    //Retornamos vista del formulario que va mostrar la región a modificar
    return view('regionEdit', [ 'region'=>$region ]);
});
Route::post('/region/update', function ()
{
    $nombre = request()->nombre;
    $idRegion = request()->idRegion;
    try {
        DB::table('regiones')
                ->where('idRegion', $idRegion)
                ->update(
                    [ 'nombre'=>$nombre ]
                );
        return redirect('/regiones')
            ->with(
                [
                    'css'=>'green',
                    'mensaje'=>'Region: '.$nombre.' modificada correctamente'
                ]
            );
    }catch ( Throwable $th){
        // app de log
        return redirect('/regiones')
            ->with(
                [
                    'css'=>'red',
                    'mensaje'=>'No se pudo modificar la región: '.$nombre
                ]
            );
    }
});

/*#########################*/
##### CRUD de destinos
Route::get('/destinos', function ()
{
    //obtenemos listado de retinos
    // raw SQL
    /* $destinos = DB::select(
                    'SELECT *
                        FROM destinos AS d
                        JOIN regiones r
                          ON d.idRegion = r.idRegion'
                    ); */
    // Query Builder
    $destinos = DB::table('destinos as d')
                        ->join('regiones as r', 'd.idRegion', '=', 'r.idRegion')
                        ->orderBy('d.idDestino', 'desc')
                        ->get();
    return view('/destinos', [ 'destinos'=>$destinos ]);
});
Route::get('/destino/create', function ()
{
    //obtenemos listado de regiones
    $regiones = DB::table('regiones')->get();
    return view('destinoCreate', [ 'regiones'=>$regiones ]);
});
Route::post('/destino/store', function ()
{
    $aeropuerto = request()->aeropuerto;
    $precio = request()->precio;
    $idRegion = request()->idRegion;
    try {
        DB::table('destinos')
                ->insert(
                    [
                        'aeropuerto'=>$aeropuerto,
                        'precio'=>$precio,
                        'idRegion'=>$idRegion
                    ]
                );
        return redirect('/destinos')
                    ->with(
                            [
                                'mensaje'=>'Destino: '.$aeropuerto.' agregado correctamente',
                                'css'=>'green'
                            ]
                    );
    }
    catch ( Throwable $th ){
        return redirect('/destinos')
            ->with(
                [
                    'mensaje'=>'No se pudo agregar el destino '.$aeropuerto,
                    'css'=>'red'
                ]
            );
    }
});


