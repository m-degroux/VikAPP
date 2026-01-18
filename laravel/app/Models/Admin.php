<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'vik_admin';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['user_id', 'user_username', 'user_password'];

    protected $hidden = ['user_password'];

    //To show Laravel where is the user's password
    public function getAuthPassword()
    {
        return $this->user_password;
    }
}
