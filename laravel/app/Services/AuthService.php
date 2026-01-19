<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(string $username, string $password): array
    {
        $member = Member::where('user_username', $username)->first();

        if (! $member || ! Hash::check($password, $member->user_password)) {
            throw ValidationException::withMessages([
                'username' => ['Identifiants incorrects.'],
            ]);
        }

        $token = $member->createToken('api-token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $member,
        ];
    }

    public function signup(array $data): array
    {
        $data['user_password'] = Hash::make($data['user_password']);

        $member = Member::create($data);
        $token = $member->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $member,
        ];
    }

    public function updateProfile(Member $member, array $data): Member
    {
        if (! empty($data['user_password'])) {
            $data['user_password'] = Hash::make($data['user_password']);
        } else {
            unset($data['user_password']);
        }

        $member->update($data);

        return $member;
    }
}
