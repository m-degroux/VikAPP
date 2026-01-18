<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'vik_team';
    protected $primaryKey = 'team_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'team_id',
        'race_id',
        'user_id',
        'team_name',
        'team_picture',
        'team_time',
        'team_point'
    ];

    /* ---------- Relations ---------- */

    // FK vik_team.race_id → vik_race.race_id
    public function race()
    {
        return $this->belongsTo(Race::class, 'race_id');
    }

    // FK vik_team.user_id → vik_member.user_id
    public function captain()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    // FK pivot vik_join_team.team_id
    public function members()
    {
        return $this->belongsToMany(
            Member::class,
            'vik_join_team',
            'team_id',
            'user_id'
        );
    }
}
