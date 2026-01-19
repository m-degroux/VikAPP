<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaceAgeCategory extends Model
{
    use HasFactory;

    protected $table = 'vik_race_age_cat';

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = null;

    protected $fillable = ['race_id', 'age_id', 'bel_price'];
}
