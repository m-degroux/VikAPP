<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $table = 'vik_club';

    protected $primaryKey = 'club_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'club_id',
        'user_id',
        'club_name',
        'club_address',
        'club_active',
    ];

    protected function casts(): array
    {
        return [
            'club_active' => 'boolean',
        ];
    }

    /* ---------- Relations ---------- */

    public function manager()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    public function members()
    {
        return $this->hasMany(Member::class, 'club_id');
    }

    public function raids()
    {
        return $this->hasMany(Raid::class, 'club_id');
    }
}
