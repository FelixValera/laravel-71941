<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Requests\CategoriaRequest;
use PDOException;
use Throwable;

class CategoriaController extends Controller
{   
    /* La funcion de validar se la pasamos a 'CategoriaRequest'

    public function validarForm($request)
    {

        $request->validate(
            [
                'catNombre'=>'required|alpha|unique:categorias,catNombre|min:5|max:25'
            ],
            [
                'catNombre.required'=>'Complete el campo "Nombre de la categoria"',
                'catNombre.alpha'=>'El campo "Nombre de la categoria" solo debe tener letras',
                'catNombre.unique'=>'Ya existe una categoria con ese nombre',
                'catNombre.min'=>'El campo "Nombre de la categoria" debe tener al menos 5 caracteres',
                'catNombre.max'=>'El campo "Nombre de la categoria" debe tener 20 caracteres como máximo'
            ]
        );
    }
    */
    /**
     * Display a listing of the resource.
    */

    public function index()
    {
        $categorias = Categoria::orderBy('idCategoria',)->simplePaginate(4);

        return view('categorias',['categorias'=>$categorias]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categoriaCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriaRequest $request)
    {
        $catNombre = $request->catNombre;

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
    public function edit(string $id)
    {
        $categoria = Categoria::find($id);
        return view('categoriaEdit',['categoria'=>$categoria]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriaRequest $request)
    {
        $catNombre = $request->catNombre;
        $id = $request->idCategoria;
        
        try{

            $categoria = Categoria::find($id);
            $categoria->catNombre = $catNombre;

            $categoria->update();

            return redirect('/categorias')->with([
                'mensaje'=>'Categoria: '.$catNombre.' Actualizado correctamente :)',
                'css'=>'green'
            ]);

        }
        catch(QueryException $q){

            return redirect('/categorias')->with([
                'mensaje'=>'Categoria: '.$catNombre.' No se pudo actualizar :(',
                'css'=>'red'
            ]);
        }
    }

    /**
     * show message confirm 
     */
    public function delete(string $id)
    {
        $categoria = Categoria::find($id);

        if(Producto::chekProductoPorCategoria($categoria->idCategoria))
        {
            return redirect('/categorias')->with(
                [
                    'mensaje'=>'No se puede eliminar la categoria: '.$categoria->catNombre. ' porque tienen productos relacionados',
                    'css'=>'yellow'
                ]
            );
        }

        return view('categoriaDelete',['categoria'=>$categoria]);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $idCategoria = $request->idCategoria;
        $catNombre = $request->catNombre;

        try{

            $categoria = Categoria::find($idCategoria);
            $categoria->delete();
            
            return redirect('/categorias')->with(
                [
                    'mensaje'=>'Se elimino la categoria: '.$categoria->catNombre.' correctamente :)',
                    'css'=>'green'
                ]
            );
        }
        catch(Throwable $th){

            return redirect('/categorias')->with(
                [
                    'mensaje'=>'No se pudo eliminar la categoria: '.$catNombre.' :(',
                    'css'=>'red'
                ]
            );
        
        }
        catch(QueryException $q){

            return redirect('/categorias')->with(
                [
                    'mensaje'=>'No se pudo eliminar la categoria: '.$catNombre.' :(',
                    'css'=>'red'
                ]
            );
        }
    }
}
