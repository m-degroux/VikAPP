<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRaidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'raid_name' => 'sometimes|string|max:50',
            'raid_place' => 'nullable|string|max:50',
            'raid_contact' => 'nullable|string|max:50',
            'raid_website' => 'nullable|url|max:100',
            'raid_reg_start_date' => 'nullable|date',
            'raid_reg_end_date' => 'nullable|date|after_or_equal:raid_reg_start_date',
            'raid_start_date' => 'nullable|date|after_or_equal:raid_reg_end_date',
            'raid_end_date' => 'nullable|date|after_or_equal:raid_start_date',
            'responsible_id' => 'nullable|integer|exists:vik_member,user_id',
            'club_id' => 'nullable|exists:vik_club,club_id',
        ];
    }
}
