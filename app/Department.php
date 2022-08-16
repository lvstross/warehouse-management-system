<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'dept_name',
        'dept_bg_color',
        'dept_txt_color',
        'key'
    ];

    public $timestamps = false;

    public static function validations()
    {
        return [
            'dept_name' => 'required|alpha|max:50',
            'dept_bg_color' => 'nullable|alphanum|max:6',
            'dept_txt_color' => 'nullable|alphanum|max:6'
        ];
    }
}