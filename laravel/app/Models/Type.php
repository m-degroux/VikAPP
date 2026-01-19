<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'vik_type';

    protected $primaryKey = 'type_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['type_id', 'type_name'];
}
