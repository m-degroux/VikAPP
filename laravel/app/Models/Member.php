<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Model representing a Member/User within the orientation race system.
 */
class Member extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /* ------------------------------------------------------
     * ELOQUENT CONFIGURATION
     * ------------------------------------------------------ */
    
    /** @var string The table associated with the model */
    protected $table = 'vik_member';

    /** @var string The primary key associated with the table */
    protected $primaryKey = 'user_id';

    /** @var bool Indicates if the IDs are auto-incrementing */
    public $incrementing = true;

    /** @var bool Indicates if the model should be timestamped (created_at/updated_at) */
    public $timestamps = false;

    /** @var array The attributes that are mass assignable */
    protected $fillable = [
        'user_id',
        'club_id',
        'mem_name',
        'mem_firstname',
        'mem_birthdate',
        'mem_adress',
        'mem_phone',
        'mem_email',
        'mem_default_licence',
        'user_username',
        'user_password'
    ];

    /** @var array The attributes that should be hidden for serialization */
    protected $hidden = [
        'user_password',
        'remember_token',
    ];

    /* ------------------------------------------------------
     * 1. MEMBERSHIP RELATIONS (Entities I belong to)
     * ------------------------------------------------------ */

    /**
     * Get the club the member belongs to.
     * Inverse of Club::members()
     */
    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    /**
     * Get the races the member is registered for.
     * Uses the 'vik_join_race' pivot table with additional registration data.
     */
    public function races()
    {
        return $this->belongsToMany(
            Race::class,
            'vik_join_race',
            'user_id',
            'race_id'
        )->withPivot([
                    'jrace_licence_num',
                    'jrace_pps',
                    'jrace_presence_valid',
                    'jrace_payement_valid'
                ]);
    }

    /**
     * Get the teams the member is a part of.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'vik_join_team', 'user_id', 'team_id');
    }

    /* ------------------------------------------------------
     * 2. MANAGEMENT RELATIONS (Entities I organize/manage)
     * ------------------------------------------------------ */

    /**
     * Get the club managed by this member (President/Manager role).
     */
    public function managedClub()
    {
        return $this->hasOne(Club::class, 'user_id', 'user_id')
            ->where('club_active', 1);
    }

    /**
     * Get the teams created/managed by this member.
     */
    public function managedTeams()
    {
        return $this->hasMany(Team::class, 'user_id', 'user_id');
    }

    /**
     * Get the raids organized by this member.
     * Only returns upcoming raids (end date in the future).
     */
    public function managedRaids()
    {
        return $this->belongsToMany(Raid::class, 'vik_manage_raid', 'user_id', 'raid_id')
            ->where('raid_end_date', '>', now());
    }

    /**
     * Get the races supervised by this member via the manager pivot table.
     */
    public function managedRaces()
    {
        return $this->belongsToMany(Race::class, 'vik_race_manager', 'user_id', 'race_id');
    }

    /* ------------------------------------------------------
     * 3. HELPERS (Boolean checks for Navbar/Blade logic)
     * ------------------------------------------------------ */

    /**
     * Check if the member manages at least one active club.
     */
    public function isClubManager(): bool
    {
        return $this->managedClub()->exists();
    }

    /**
     * Check if the member has created/managed at least one team.
     */
    public function isTeamManager(): bool
    {
        return $this->managedTeams()->exists();
    }

    /**
     * Check if the member is an organizer for any upcoming raids.
     */
    public function isRaidOrganizer(): bool
    {
        return $this->managedRaids()->exists();
    }

    /**
     * Check if the member is assigned as a manager for any races.
     */
    public function isRaceManager(): bool
    {
        return $this->managedRaces()->exists();
    }

    /**
     * Retrieve the specific club ID managed by a user.
     */
    public function clubId(int $user_id): int
    {
        $club = Club::select('club_id')->where('user_id', $user_id)->first();

        return $club->club_id;
    }

    /* ------------------------------------------------------
     * AUTHENTICATION OVERRIDES
     * ------------------------------------------------------ */

    /**
     * Tells Laravel to use 'user_password' instead of the default 'password' field.
     */
    public function getAuthPassword()
    {
        return $this->user_password;
    }
}