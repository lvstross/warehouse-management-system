<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    protected $fillable = [
        'router_num',
        'part_num',
        'qty',
        'stock_qty',
        'status',
        'dept_name',
        'move_log',
        'date',
        'date_in_inv',
        'key'
    ];

    public $timestamps = false;
}
