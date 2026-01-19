<?php

namespace App\Http\Requests;

use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mem_email' => [
                'string',
                'email',
                'max:128',
                Rule::unique(Member::class)->ignore($this->user()->user_id, 'user_id'),
            ],
            'mem_name' => ['string', 'max:50'],
            'mem_firstname' => ['required', 'string', 'max:50'],
            'mem_default_licence' => [
                'nullable',
                'string',
                Rule::unique(Member::class)->ignore($this->user()->user_id, 'user_id'),
            ],
            'mem_birthdate' => ['date'],
            'mem_adress' => ['string', 'max:128'],
            'mem_phone' => ['string', 'max:10'],
            'user_username' => [
                'string',
                'max:50',
                Rule::unique(Member::class)->ignore($this->user()->user_id, 'user_id'),
            ],
            'club_id' => [
                'nullable',
                'integer',
                'exists:vik_club,club_id',
            ],
        ];
    }
}
