<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreRaidRequest extends FormRequest
{
    public function authorize(): bool
    {
        $raidId = $this->route('raid');
        return true;//Gate::allows('raid', $raidId);
    }

    public function rules(): array
    {
        return [
            'raid_name' => 'required|string|max:50',
            'raid_reg_start_date' => 'required|date',
            'raid_reg_end_date' => 'required|date|after_or_equal:raid_reg_start_date',
            'raid_start_date' => 'required|date|after_or_equal:raid_reg_end_date',
            'raid_end_date' => 'required|date|after_or_equal:raid_start_date',
            'raid_contact' => 'required|string|max:50',
            'raid_website' => 'nullable|string|max:50|url',
            'raid_place' => 'nullable|string|max:50',
            'raid_picture' => 'nullable|string|max:128',
            'responsible_id' => 'required|exists:vik_member,user_id',
        ];
    }

    public function messages(): array
    {
        return [
            'responsible_id.required' => 'Vous devez obligatoirement désigner un responsable pour ce raid.',
            'responsible_id.exists' => 'Le membre sélectionné est invalide.',
            'raid_name.required' => 'Le nom du raid est obligatoire.',
            'raid_start_date.after_or_equal' => 'Le raid ne peut pas commencer avant la fin des inscriptions.',
            'raid_end_date.after_or_equal' => 'La date de fin doit être après la date de début.',
        ];
    }
}
