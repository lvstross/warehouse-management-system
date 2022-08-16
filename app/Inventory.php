<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class inventory extends Model
{
    protected $fillable = [
        'part_num',
        'po_num',
        'customer',
        'qty',
        'status',
        'cust_req',
        'routers',
        'boxes',
        'log',
        'date',
        'trip_count'
    ];


    public $timestamps = false;
}
