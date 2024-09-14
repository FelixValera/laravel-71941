<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

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
            $marca->mkNombre = $mkNombre;
            $marca->save(); // almacenamos en tabla
            return redirect('/marcas')
                        ->with(
                            [
                                'mensaje'=>'Marca: '.$mkNombre.' agregada correctamente',
                                'css'=>'green'
                            ]
                        );
        }
        catch ( Throwable $th ){
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
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marca $marca)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marca $marca)
    {
        //
    }
}
