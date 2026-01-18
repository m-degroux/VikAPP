<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Gate;

class StoreClubRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('admin');
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:vik_member,user_id',
            'club_name' => 'required|string|max:50',
            'club_adress' => 'required|string|max:50',
            'club_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => "Le membre spécifié n'existe pas.",
            'club_name.required' => "Le nom du club est obligatoire.",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
