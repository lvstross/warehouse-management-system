<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'shipto',
        'billto',
        'buyer',
        'country',
        'disclaimer',
        'comments'
    ];

    public $timestamps = false;

    /**
    * Validation Array
    *
    * 
    */
    public static function validations()
    {
        return [
            'name' => 'required|string|regex:/^(?!-)(?!.*--)[A-Z0-9a-z\,\.\s]+(?<!-)$/i|max:50',
            'shipto' => 'required|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\-\#\@\(\)\:\.\s]+(?<!-)$/i|max:255',
            'billto' => 'required|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\-\#\@\(\)\:\.\s]+(?<!-)$/i|max:255',
            'buyer' => 'required|string|regex:/^(?!-)(?!.*--)[A-Za-z\,\.\s]+(?<!-)$/i|max:50',
            'country' => 'required|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\#\s]+(?<!-)$/i|max:15',
            'disclaimer' => "required|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\'\.\*\#\s]+(?<!-)$/i|max:500",
            'comments' => 'required|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\#\s]+(?<!-)$/i|max:255'
        ];
    }
}
