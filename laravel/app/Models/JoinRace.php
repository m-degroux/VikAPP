<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoinRace extends Model
{
    use HasFactory;

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
        'jrace_payement_valid',
    ];

    /**
     * Get the member associated with this race registration.
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id', 'user_id');
    }

    /**
     * Get the team associated with this race registration.
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'team_id');
    }

    /**
     * Get the race associated with this registration.
     */
    public function race()
    {
        return $this->belongsTo(Race::class, 'race_id', 'race_id');
    }
}
