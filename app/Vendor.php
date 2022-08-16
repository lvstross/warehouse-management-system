<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'contact',
        'phone',
        'fax',
        'ext',
        'email',
        'website',
        'type',
        'ship_address',
        'purch_address',
        'remit_address',
        'comment_to'
    ];

    public $timestamps = false;
}
