<?php

namespace App\Services;

use App\Models\Club;
use App\Models\Member;

class ProfileService
{
    public function getUserProfile(Member $user)
    {
        return [
            'user' => $user,
            'clubs' => Club::all(),
        ];
    }
}
