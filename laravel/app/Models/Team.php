<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $table = 'vik_team';

    protected $primaryKey = 'team_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($team) {
            if (empty($team->team_id)) {
                $team->team_id = \Illuminate\Support\Str::uuid()->toString();
            }
        });
    }

    protected $fillable = [
        'team_id',
        'race_id',
        'user_id',
        'team_name',
        'team_picture',
        'team_time',
        'team_point',
    ];

    protected function casts(): array
    {
        return [
            'team_point' => 'integer',
        ];
    }

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
