<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *unique:categorias,catNombre
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'catNombre'=>'required|alpha|'.Rule::unique('categorias')->ignore($request->idCategoria,'idCategoria').'|min:5|max:25'
        ];
    }

    public function messages(): array
    {
        return [
            'catNombre.required'=>'Complete el campo "Nombre de la categoria"',
            'catNombre.alpha'=>'El campo "Nombre de la categoria" solo debe tener letras',
            'catNombre.unique'=>'Ya existe una categoria con ese nombre',
            'catNombre.min'=>'El campo "Nombre de la categoria" debe tener al menos 5 caracteres',
            'catNombre.max'=>'El campo "Nombre de la categoria" debe tener 20 caracteres como máximo'
        ];
    }
}
