<?php

namespace App\Http\Requests\Categoria;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaCreatedRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'nome' => 'required|max:255|unique:categorias',
            'ativo' => 'boolean'
        ];
    }
}
