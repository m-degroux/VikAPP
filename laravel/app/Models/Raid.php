<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Model representing a Raid (an event containing multiple races).
 */
class Raid extends Model
{
    /** @var string The table associated with the model */
    protected $table = 'vik_raid';

    /** @var string The primary key associated with the table */
    protected $primaryKey = 'raid_id';

    /** @var bool Indicates if the IDs are auto-incrementing */
    public $incrementing = true;

    /** @var bool Indicates if the model should be timestamped */
    public $timestamps = false;

    /** @var array The attributes that are mass assignable */
    protected $fillable = [
        'raid_name',
        'raid_reg_start_date',
        'raid_reg_end_date',
        'raid_start_date',
        'raid_end_date',
        'raid_contact',
        'raid_website',
        'raid_place',
        'raid_picture',
        'raid_lat',
        'raid_lng',
        'club_id'
    ];

    /* ---------- Relations ---------- */

    /**
     * Get all races associated with this raid.
     * FK: vik_race.raid_id
     */
    public function races()
    {
        return $this->hasMany(Race::class, 'raid_id');
    }

    /**
     * Get the members responsible for managing this raid.
     * Uses the 'vik_manage_raid' pivot table.
     */
    public function managers()
    {
        return $this->belongsToMany(
            Member::class,
            'vik_manage_raid',
            'raid_id',
            'user_id'
        );
    }

    /* ---------- Accessors & Helpers ---------- */

    /**
     * Custom attribute to determine the minimum age allowed for this raid
     * by checking all nested race age categories.
     */
    public function getMinAgeAttribute()
    {
        return $this->races
            ->flatMap->ageCategories
            ->min('age_min');
    }

    /**
     * Check if at least one race within the raid is currently happening.
     */
    public function isOngoing()
    {
        return $this->races->contains(function($race) {
            return $race->race_start_date <= now() && $race->race_end_date >= now();
        });
    }

    /**
     * Retrieve the chronologically next race in this raid.
     */
    public function nextRace()
    {
        return $this->races()
            ->where('race_start_date', '>', now())
            ->orderBy('race_start_date')
            ->first();
    }

    /**
     * Calculate a human-readable duration until the next race starts.
     * Returns a string like "2h 30m" or null if no upcoming race exists.
     */
    public function timeUntilNextRace()
    {
        $nextRace = $this->nextRace();

        if (!$nextRace) {
            return null;
        }

        return Carbon::now()->diffForHumans(
            Carbon::parse($nextRace->race_start_date),
            [
                'parts' => 2,
                'short' => true,
                'syntax' => Carbon::DIFF_ABSOLUTE
            ]
        );
    }

    /**
     * Count the total number of races in this raid.
     */
    public function racesCount()
    {
        return $this->races->count();
    }

    /**
     * Helper method to get the overall minimum age required for the raid.
     */
    public function minAge()
    {
        return $this->races
            ->flatMap->ageCategories
            ->min('age_min');
    }

    public function isPast(): bool
    {
        return $this->races()
            ->where('race_end_date', '>', now())
            ->doesntExist();
    }
}