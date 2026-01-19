<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mem_name' => 'required|string|max:50',
            'mem_firstname' => 'required|string|max:50',
            'mem_birthdate' => 'required|date|before:today',

            'mem_email' => 'required|email|max:128|unique:vik_member,mem_email',
            'mem_phone' => 'required|string|size:10',
            'mem_adress' => 'required|string|max:128',

            'user_username' => 'required|string|max:50|unique:vik_member,user_username',
            'user_password' => 'required|string|min:8|confirmed',

            'club_id' => 'nullable|exists:vik_club,club_id',
            'mem_default_licence' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'user_username.unique' => 'Ce nom d\'utilisateur est déjà pris.',
            'mem_email.unique' => 'Cet email est déjà utilisé.',
            'user_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'user_password.min' => 'Le mot de passe doit faire au moins 8 caractères.',
        ];
    }
}
