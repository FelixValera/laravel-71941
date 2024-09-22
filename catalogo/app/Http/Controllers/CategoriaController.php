<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use PDOException;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
    */

    public function index()
    {
        $categorias = Categoria::orderBy('idCategoria',)->simplePaginate(3);

        return view('categorias',['categorias'=>$categorias]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categoriaCreate');
    }

    public function validarForm($request)
    {

        $request->validate(
            [
                'catNombre'=>'required|unique:categorias,catNombre|min:5|max:25'
            ],
            [
                'catNombre.required'=>'Complete el campo "Nombre de la categoria"',
                'catNombre.unique'=>'Ya existe una categoria con ese nombre',
                'catNombre.min'=>'El campo "Nombre de la categoria" debe tener al menos 5 caracteres',
                'catNombre.max'=>'El campo "Nombre de la categoria" debe tener 20 caracteres como máximo'
            ]
        );
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $catNombre = $request->catNombre;

        //$this->validarForm($request);
    
        try{

            $categoria = new Categoria;
            $categoria->catNombre = $catNombre;
            $categoria->save();

            return redirect('/categorias')
            ->with([
                'mensaje'=>'Categoria: '.$catNombre.' agregada correctamente',
                'css'=>'green'
            ]);
        }
        catch(QueryException $q){

            return redirect('/categorias')
            ->with([
                'mensaje'=>'No se pudo agregar la categoria: '.$catNombre,
                'css'=>'red'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        //
    }
}
