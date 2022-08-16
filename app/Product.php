<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 
        'description', 
        'material', 
        'rev', 
        'rev_date'
    ];

    public $timestamps = false;
}
