<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use function Termwind\render;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtenemos el listado de las marcas
        $marcas = Marca::orderBy('idMarca', 'desc')
                          ->paginate(6);
        return view('marcas', [ 'marcas'=>$marcas ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('marcaCreate');
    }

    private function validarForm( Request $request )
    {
        $request->validate(
            //[ 'campo' => 'regla1|regla2' ]
            [
                'mkNombre'=>'required|unique:marcas,mkNombre|min:2|max:20'
            ],
            [
                'mkNombre.required'=>'Complete el campo "Nombre de la marca"',
                'mkNombre.unique'=>'Ya existe una marca con ese nombre',
                'mkNombre.min'=>'El campo "Nombre de la marca" debe tener al menos dos caracteres',
                'mkNombre.max'=>'El campo "Nombre de la marca" debe tener 20 caracteres como máximo'
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mkNombre = $request->mkNombre;
        //validación
        $this->validarForm( $request );
        try {
            $marca = new Marca; //instanciamos
            $marca->mkNombre = $mkNombre; // asignamos atributos
            $marca->save(); // almacenamos en tabla
            return redirect('/marcas')
                        ->with(
                            [
                                'mensaje'=>'Marca: '.$mkNombre.' agregada correctamente',
                                'css'=>'green'
                            ]
                        );
        }
        //catch ( Throwable $th ){
        catch ( QueryException $th ){
            return redirect('/marcas')
                ->with(
                    [
                        'mensaje'=>'No se pudo agregar la marca: '.$mkNombre,
                        'css'=>'red'
                    ]
                );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Marca $marca)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( string $id )
    {
        //Obtenemos los datos de una marca filtrada por su ID
        //$marca = Marca::where('idMarca', $id)->first();
        $marca = Marca::find($id);
        return view('marcaEdit', [ 'marca'=>$marca ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $mkNombre = $request->mkNombre;
        $idMarca = $request->idMarca;

        $this->validarForm( $request );
        try {
            $marca = Marca::find($idMarca);
            $marca->mkNombre = $mkNombre; // asignamos atributos
            $marca->save(); // almacenamos en tabla
            return redirect('/marcas')
                ->with(
                    [
                        'mensaje'=>'Marca: '.$mkNombre.' modificada correctamente',
                        'css'=>'green'
                    ]
                );
        }
        //catch ( Throwable $th ){
        catch ( QueryException $th ){
            return redirect('/marcas')
                ->with(
                    [
                        'mensaje'=>'No se pudo modificar la marca: '.$mkNombre,
                        'css'=>'red'
                    ]
                );
        }
    }

    private function chekProductoPorMarca( int $idMarca)
    {
        // objeto | null
        /* $check = DB::table('productos')
                        ->where('idMarca', $idMarca)
                        ->first(); */
        // int
        $check = DB::table('productos')
                        ->where('idMarca', $idMarca)
                        ->count();
        return $check;
    }

    public function delete( string $id )
    {
        //Obtenemos datos de la marca filtrada por su ID
        $marca = Marca::find($id);
        if( Producto::chekProductoPorMarca($id) ){
            return redirect('/marcas')
                        ->with(
                            [
                                'mensaje'=>'No se puede eliminar la marca: '.$marca->mkNombre. ' porque tienen productos relacionados',
                                'css'=>'yellow'
                            ]
                        );
        }
        return view('marcaDelete', [ 'marca'=>$marca ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca)
    {
        //
    }
}
