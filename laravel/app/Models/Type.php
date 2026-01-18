<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'vik_type';
    protected $primaryKey = 'type_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['type_id', 'type_name'];
}
