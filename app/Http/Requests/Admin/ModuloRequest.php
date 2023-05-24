<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ModuloRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'IdPadre' => 'required',
            'Nombre' => 'required',
            'Tipo' => 'required'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'IdPadre.required' => '¿De qué carpeta o módulo depende?',
            'Nombre.required' => 'Especifique un nombre.',
            'Tipo.required' => 'Especifique el tipo de módulo.'
        ];
    }
}
