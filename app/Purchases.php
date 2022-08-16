<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchases extends Model
{
    protected $fillable = [
        'po_num',
        'recv_num',
        'vendor',
        'due_date',
        'recv_date',
        'terms',
        'carrier',
        'vendor_confirm_order',
        'items',
        'po_total',
        'entered_by',
        'enter_date',
        'modified_by',
        'modify_date',
        'status',
        'comments'
    ];

    public $timestamps = false;

    /**
     * Static function to return validation array
     *
     * @return array
     */
    public static function validations()
    {
        return [
            'po_num' => 'required|string',
            'recv_num' => 'nullable|string',
            'vendor' => 'required|numeric',
            'due_date' => 'nullable|date',
            'recv_date' => 'nullable|date',
            'terms' => 'nullable|string',
            'carrier' => 'nullable|string',
            'vendor_confirm_order' => 'nullable|date',
            'items' => 'required|array',
            'po_total' => 'nullable|regex:/^(?!-)(?!.*--)[0-9\.]+(?<!-)$/i',
            'comments' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\%\#\$\s]+(?<!-)$/i|max:255'
        ];
    }
}
