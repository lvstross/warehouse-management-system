<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaseorder extends Model
{
    protected $fillable = [
        'due_date',
        'will_ship',
        'ship_date',
        'rating',
        'sooner',
        'customer',
        'po_num',
        'part_num',
        'qty',
        'stock',
        'sales',
        'need_routers',
        'routers',
        'cust_req',
        'status',
        'invoice',
        'key'
    ];

    public $timestamps = false;

    /**
     * get validation array
     *
     * @param bool
     * @return array
     */
    public static function validations($setting = true)
    {
        $val = [
                'due_date' => 'required|date',
                'will_ship' => 'required|date',
                'rating' => 'nullable|string',
                'sooner' => 'required|string',
                'customer' => 'required|string|max:100',
                'po_num' => 'required|string|max:50',
                'part_num' => 'required|string|max:50',
                'qty' => 'required|alpha_num',
                'stock' => 'nullable|string',
                'sales' => 'nullable|string',
                'need_routers' => 'required|string',
                'routers' => 'nullable|array',
                'cust_req' => "nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\'\%\.\*\#\$\"\s]+(?<!-)$/i|max:1000"
            ];
        if($setting == true){
            return $val;
        }else{
            $val['status'] = 'required|string';
            $val['invoice'] = 'nullable|string';
            return $val;
        }
    }
}
