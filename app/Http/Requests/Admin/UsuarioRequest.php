<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
        $reglas = [
            'username' => 'required',
            'email' => 'required',
            'TblDGP_id' => 'required'
        ];

        if ($this->input('password')) {
            $reglas['password'] = 'required';
        }

        return $reglas;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'username.required' => 'El nombre de usuario es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'TblDGP_id.required' => 'La persona asociada es obligatoria.',
            'password.required' => 'La contraseña es obligatoria.'
        ];
    }
}
