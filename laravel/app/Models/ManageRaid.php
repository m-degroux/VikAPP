<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageRaid extends Model
{
    use HasFactory;

    protected $table = 'vik_manage_raid';

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = null;

    protected $fillable = ['raid_id', 'user_id'];
}
