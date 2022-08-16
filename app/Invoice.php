<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'inv_num',
        'date',
        'customer',
        'po_num',
        'line_items',
        'misc_char',
        'ship_fee',
        'total',
        'carrier',
        'memo',
        'create_date'
    ];
    protected $casts = [
        'customer' => 'object',
        'line_items' => 'object'
    ];

    public $timestamps = false;
}
