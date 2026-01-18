<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $table = 'vik_club';
    protected $primaryKey = 'club_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'club_id',
        'user_id',
        'club_name',
        'club_address',
        'club_active'
    ];

    /* ---------- Relations ---------- */

    // FK vik_club.user_id → vik_member.user_id
    public function manager()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    // FK vik_member.club_id
    public function members()
    {
        return $this->hasMany(Member::class, 'club_id');
    }
}
