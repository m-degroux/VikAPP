<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'raid_id' => 'required|exists:vik_raid,raid_id',
            'type_id' => 'required|exists:vik_type,type_id',
            'dif_id' => 'required|exists:vik_difficulty,dif_id',
            'race_name' => 'required|string|max:50',
            'race_duration' => 'required|date_format:H:i:s',
            'race_length' => 'required|numeric|between:0,99.99',
            'race_reduction' => 'nullable|numeric|between:0,9.99',
            'race_start_date' => 'required|date',
            'race_end_date' => 'required|date|after_or_equal:race_start_date',
            'race_min_part' => 'required|integer|min:1',
            'race_max_part' => 'required|integer|gte:race_min_part',
            'race_min_team' => 'required|integer|min:1',
            'race_max_team' => 'required|integer|gte:race_min_team',
            'race_max_part_per_team' => 'required|integer|min:1',
            'race_meal_price' => 'nullable|numeric|between:0,999.99',
        ];
    }
}
