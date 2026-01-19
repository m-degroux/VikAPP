<?php

namespace App\Contracts;

use App\Models\Member;

interface AuthServiceInterface
{
    public function login(array $credentials): ?Member;

    public function signup(array $data): Member;

    public function updateProfile(Member $member, array $data): Member;
}
