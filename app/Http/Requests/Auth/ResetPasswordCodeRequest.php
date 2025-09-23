<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'code' => 'required|digits:6', // só 6 números
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'O código é obrigatório.',
            'code.digits' => 'O código deve ter exatamente 6 números.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.exists' => 'E-mail não encontrado.',
            'password.required' => 'A senha é obrigatória.',
            'password.confirmed' => 'A confirmação de senha não confere.',
        ];
    }

    // Retorna os erros como session messages para SweetAlert
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        session()->flash('error', $validator->errors()->first());
        parent::failedValidation($validator);
    }
}