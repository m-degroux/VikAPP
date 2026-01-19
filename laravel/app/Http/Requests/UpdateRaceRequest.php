<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = (new StoreRaceRequest)->rules();

        return array_map(fn ($rule) => "sometimes|$rule", $rules);
    }
}
