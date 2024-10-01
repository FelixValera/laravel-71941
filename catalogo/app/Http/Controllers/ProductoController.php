<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $productos = Producto::orderBy('idProducto', 'desc')->paginate(6);
        return view('productos', [ 'productos'=>$productos ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        // Obtenemos listados de marcas y de categorías
        $marcas = Marca::all();
        $categorias = Categoria::all();
        return view('productoCreate',
                        [
                            'marcas'=>$marcas,
                            'categorias'=>$categorias
                        ]
                );
    }

    private function validarForm( Request $request )
    {
        //[ rules ],[messages]
        $request->validate(
            [
                'prdNombre'=>'required|'.Rule::unique('productos').'|min:2|max:75',
                'prdPrecio'=>'required|decimal:2|min:0',
                'idMarca'=>'required|exists:marcas,idMarca',
                'idCategoria'=>'required|exists:categorias,idCategoria',
                'prdDescripcion'=>'max:1000',
                'prdImagen'=>'mimes:jpg,jpeg,png,gif,svg,webp|max:5120'
            ],
            [
                'prdNombre.required'=>'El campo "Nombre de producto" es obligatorio.',
                'prdNombre.unique'=>'El "Nombre de producto" ya existe.',
                'prdNombre.min'=>'El campo "Nombre de producto" debe tener como mínimo 2 caractéres.',
                'prdNombre.max'=>'El campo "Nombre de producto" debe tener 75 caractéres como máximo.',
                'prdPrecio.required'=>'Complete el campo Precio.',
                'prdPrecio.decimal'=>'Complete el campo Precio con un número que tenga como máximo dos decimales.',
                'prdPrecio.min'=>'Complete el campo Precio con un número mayor a 0.',
                'idMarca.required'=>'Seleccione una marca.',
                'idMarca.exists'=>'Seleccione una marca existente',
                'idCategoria.required'=>'Seleccione una categoría.',
                'idCategoria.exists'=>'Seleccione una categoría existente',
                'prdDescripcion.max'=>'Complete el campo Descripción con 1000 caractéres como máximo.',
                'prdImagen.mimes'=>'Debe ser una imagen.',
                'prdImagen.max'=>'Debe ser una imagen de 5MB como máximo.'
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( ProductoRequest $request )
    {
        $prdNombre = $request->prdNombre;
        // validación
        //$this->validarForm($request);
        return 'Pasó la validación';
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
