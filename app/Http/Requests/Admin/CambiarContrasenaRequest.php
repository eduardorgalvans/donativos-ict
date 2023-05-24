<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CambiarContrasenaRequest extends FormRequest
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
            'ContrasenaActual' => 'required',
            'ContrasenaNueva' => 'required',
            'ConfirmarContrasena' => 'required'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $contrasenia = request()->ContrasenaNueva;
            $confirmacion = request()->ConfirmarContrasena;

            if ($contrasenia != $confirmacion) {
                $validator->errors()->add('Confirmacion',
                    'La contraseña y su confirmación no coinciden.'
                );
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'ContrasenaActual.required' => 'Debe indicar su contraseña actual.',
            'ContrasenaNueva.required' => 'Debe indicar una contraseña.',
            'ConfirmarContrasena.required' => 'Debe confirma la contraseña nueva.'
        ];
    }
}
