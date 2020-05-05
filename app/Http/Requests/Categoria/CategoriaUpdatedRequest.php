<?php

namespace App\Http\Requests\Categoria;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaUpdatedRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'required|max:255|unique:categorias,id,'.$this->get('id'),
            'ativo' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'o nome é um campo obrigatório',
            'nome.unique' => 'o nome informado já foi cadastrado',
            'nome.max' => 'o nome deve possuir no máximo :max caracteres',
            'ativo.boolean' => 'o valor deve ser um booleano true/false'
        ];
    }
}
