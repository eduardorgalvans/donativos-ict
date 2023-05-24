<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PermanentesRequest extends FormRequest
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
            'Variable'=>'required',
            'Valor'=>'required',
            'Tipo'=>'required',
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
            'Variable.required' => 'El campo nombre de variable es obligatorio.',
            'Valor.required' => 'El campo valor es obligatorio.',
            'Tipo.required' => 'El campo tipo del variable es obligatorio.',
        ];
    }

}
