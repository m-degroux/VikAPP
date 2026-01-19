<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Difficulty extends Model
{
    use HasFactory;

    protected $table = 'vik_difficulty';

    protected $primaryKey = 'dif_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['dif_id', 'dif_dist_min', 'dif_dist_max'];
}
