<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = auth()->user()->user_id;

        return [
            'mem_name' => 'sometimes|required|string|max:50',
            'mem_firstname' => 'sometimes|required|string|max:50',
            'mem_email' => 'sometimes|required|email|unique:vik_member,mem_email,'.$userId.',user_id',
            'mem_phone' => 'sometimes|required|string|size:10',
            'mem_adress' => 'sometimes|required|string|max:128',
            'user_password' => 'sometimes|nullable|string|min:8|confirmed',
        ];
    }
}
