<?php

namespace App\Http\Requests;

use App\Models\Race;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateRaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $race = $this->route('race');
        if (!$race instanceof Race) {
            $race = Race::find($race);
        }
        if (!$race) return false;
        return Gate::allows('update-race', $race);
    }

    public function rules(): array
    {
        $rules = (new StoreRaceRequest())->rules();
        return array_map(fn($rule) => "sometimes|$rule", $rules);
    }
}
