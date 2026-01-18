<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinRace extends Model
{
    protected $table = 'vik_join_race';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'user_id',
        'race_id',
        'jrace_licence_num',
        'jrace_pps',
        'jrace_presence_valid',
        'jrace_payement_valid'
    ];
}

