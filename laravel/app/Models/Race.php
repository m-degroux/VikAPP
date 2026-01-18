<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    protected $table = 'vik_race';
    protected $primaryKey = 'race_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'race_id',
        'raid_id',
        'type_id',
        'dif_id',
        'race_name',
        'race_duration',
        'race_length',
        'race_reduction',
        'race_start_date',
        'race_end_date',
        'race_min_part',
        'race_max_part',
        'race_min_team',
        'race_max_team',
        'race_max_part_per_team',
        'race_meal_price'
    ];

    /* ---------- Relations ---------- */

    // FK vik_race.raid_id → vik_raid.raid_id
    public function raid()
    {
        return $this->belongsTo(Raid::class, 'raid_id');
    }

    // FK vik_race.type_id → vik_type.type_id
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    // FK vik_race.dif_id → vik_difficulty.dif_id
    public function difficulty()
    {
        return $this->belongsTo(Difficulty::class, 'dif_id');
    }

    // FK vik_team.race_id
    public function teams()
    {
        return $this->hasMany(Team::class, 'race_id');
    }

    // FK pivot vik_race_manager.race_id
    public function managers()
    {
        return $this->belongsToMany(
            Member::class,
            'vik_race_manager',
            'race_id',
            'user_id'
        );
    }

    // FK pivot vik_race_age_cat.race_id
    public function ageCategories()
    {
        return $this->belongsToMany(
            AgeCategory::class,
            'vik_race_age_cat',
            'race_id',
            'age_id'
        )->withPivot('bel_price');
    }
}
