<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JoinTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'team_id' => 'required|integer|exists:vik_team,team_id',
            'user_id' => 'required|integer|exists:vik_member,user_id',
        ];
    }
}
