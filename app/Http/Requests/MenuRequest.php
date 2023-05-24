<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
            'id_Padre'=>'required',
            'Icono'=>'required',
            'Nombre'=>'required',
            'Ruta'=>'required',
            # 'Permiso'=>'required',
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
            'id_Padre.required' => 'El campo Nodo Padre es obligatorio.',
            'Icono.required' => 'El campo Icono es obligatorio.',
            'Nombre.required' => 'El campo Nombre del articulo es obligatorio.',
            'Ruta.required' => 'El campo Ruta es obligatorio.',
            'Permiso.required' => 'El campo Permiso es obligatorio.',
        ];
    }

}
