<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Systemlog extends Model
{
    protected $fillable = [
        'logs',
        'time',
        'date'
    ];

    public $timestamps = false;
}
