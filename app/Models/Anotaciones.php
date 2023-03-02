<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anotaciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'maker',
    ];
}