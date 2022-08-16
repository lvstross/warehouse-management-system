<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'desc',
        'invoice_con',
        'shipper_con',
        'router_con',
        'po_con',
        'inv_prefix'
    ];

    public $timestamps = false;    

    public static function validations()
    {
        return [
            'name' => 'required|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\#\s]+(?<!-)$/i|max:50',
            'address' => 'required|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\-\#\:\.\s]+(?<!-)$/i|max:255',
            'phone' => 'required|string|regex:/^(?!-)(?!.*--)[0-9\(\)\-\s]+(?<!-)$/i|max:25',
            'email' => 'required|string|email|max:50',
            'desc' => 'string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\#\s]+(?<!-)$/i',
            'invoice_con' => 'string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\#\s]+(?<!-)$/i|max:50',
            'shipper_con' => 'string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\#\s]+(?<!-)$/i|max:50',
            'router_con' => 'string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\#\s]+(?<!-)$/i|max:50',
            'po_con' => 'string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\#\s]+(?<!-)$/i|max:50',
            'inv_prefix' => 'required|string|alpha_num|max:15'
        ];
    }
}
