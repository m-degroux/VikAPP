<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgeCategory extends Model
{
    protected $table = 'vik_age_category';
    protected $primaryKey = 'age_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['age_id', 'age_min', 'age_max'];
}


