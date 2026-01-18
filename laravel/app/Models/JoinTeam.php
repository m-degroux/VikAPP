<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinTeam extends Model
{
    protected $table = 'vik_join_team';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = ['team_id', 'user_id'];
}


