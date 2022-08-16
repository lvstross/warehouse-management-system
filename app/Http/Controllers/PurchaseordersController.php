<?php

namespace App\Http\Controllers;

use App\User;
use App\Purchaseorder;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SystemlogController as Systemlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseordersController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getPurchaseordersOpen()
    {
        return DB::table('purchaseorders')
                ->select('*')
                ->where('status', 'Open')
                ->get();
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getPurchaseordersClosed()
    {
        return DB::table('purchaseorders')
                ->select('*')
                ->where('status', 'Closed')
                ->orderBy('ship_date', 'desc')
                ->get();
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Purchaseorder  $id
    * @return \Illuminate\Http\Response
    */
    public function getOne($id)
    {
        return Purchaseorder::findOrFail($id);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addPurchaseorder(Request $request)
    {
        // Validate the Request
        $this->validate($request, Purchaseorder::validations());

        $due_date = $this->correctYearFormat($request->input(['due_date']));
        $will_ship = $this->correctYearFormat($request->input(['will_ship']));
        $qty = intval($request->input(['qty']));
        $sales = floatval(number_format($request->input(['sales']), 2, '.', ''));

        // Create Purchaseorder Entry
        Purchaseorder::create([
            'due_date' => $due_date,
            'will_ship' => $will_ship,
            'ship_date' => $request->input(['ship_date']),
            'rating' => $request->input(['rating']),
            'sooner' => $request->input(['sooner']),
            'customer' => $request->input(['customer']),
            'po_num' => $request->input(['po_num']),
            'part_num' => $request->input(['part_num']),
            'qty' => $qty,
            'stock' => $request->input(['stock']),
            'sales' => $sales,
            'need_routers' => $request->input(['need_routers']),
            'routers' => json_encode($request->input(['routers'])),
            'cust_req' => $request->input(['cust_req']),
            'status' => 'Open',
            'invoice' => null,
            'key' => 0
        ]);

        // Key the id of the latest entry
        // $lateEntry = DB::table('purchaseorders')->orderBy('id', 'desc')->first();
        // find same entry and update the key to equal the id
        // Purchaseorder::findOrFail($lateEntry->id)->update([
        //     'key' => $lateEntry->id
        // ]);

        $stext = Auth::user()->name . ' created the '. $request->po_num . ' purchase order for '. $request->qty . ' piece(s) of the ' . $request->part_num . 's, due to ship on ' . $request->will_ship . ' for a due date of ' . $request->due_date . ' to ' . $request->customer;
        Systemlog::addLogEntry($stext);

        return '{ "message": "purchase order successfully created." }';
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Purchaseorder  $id
    * @return \Illuminate\Http\Response
    */
    public function updatePurchaseorder(Request $request, $id)
    {
        // Validate the Request
        $this->validate($request, Purchaseorder::validations(false));

        $due_date = $this->correctYearFormat($request->input(['due_date']));
        $will_ship = $this->correctYearFormat($request->input(['will_ship']));
        $ship_date = $request->input(['ship_date']);
        if($request->input(['ship_date'])){
            $ship_date = $this->correctYearFormat($request->input(['ship_date']));
        }
        $qty = intval($request->input(['qty']));
        $sales = floatval(number_format($request->input(['sales']), 2, '.', ''));

        // Create Update Entry
        Purchaseorder::findOrFail($id)->update([
            'due_date' => $due_date,
            'will_ship' => $will_ship,
            'ship_date' => $ship_date,
            'rating' => $request->input(['rating']),
            'sooner' => $request->input(['sooner']),
            'customer' => $request->input(['customer']),
            'po_num' => $request->input(['po_num']),
            'part_num' => $request->input(['part_num']),
            'qty' => $qty,
            'stock' => $request->input(['stock']),
            'sales' => $sales,
            'need_routers' => $request->input(['need_routers']),
            'routers' => json_encode($request->input(['routers'])),
            'cust_req' => $request->input(['cust_req']),
            'status' => $request->input(['status']),
            'invoice' => $request->input(['invoice'])
        ]);
        return '{"message": "Purchase order successfully updated."}';
    }

    /**
    * Clear All Rating Numbers
    *
    * @return \Illuminate\Http\Response
    */
    public function clearAllRating()
    {
        $purchaseorders = DB::table('purchaseorders')
                ->select('id', 'rating')
                ->where('status', 'Open')
                ->get();
        for($i=0;$i<count($purchaseorders);$i++){
            Purchaseorder::findOrFail($purchaseorders[$i]->id)->update([
                'rating' => NULL,
            ]);
        }
        return '{"message": "Purchase order ratings successfully updated."}';
    }

    /**
     * Sort and update all the keys of every purchase order
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sortPurchaseorders(Request $request)
    {
        $data = $request->input();
        for($i=0; $i<count($data); $i++){
            Purchaseorder::findOrFail($data[$i]['id'])->update([
                'key' => $data[$i]['key']
            ]);
        }

        $stext = Auth::user()->name . ' sorted the purchase orders.';
        Systemlog::addLogEntry($stext);

        return '{ "message": "Purchaseorder keys successfully updated."}';
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Purchaseorder  $id
    * @return \Illuminate\Http\Response
    */
    public function deletePurchaseorder($id)
    {
        // $this->authorize('delete', $id);
        $stext = Auth::user()->name . ' deleted a purchase order.';
        Systemlog::addLogEntry($stext);
        return Purchaseorder::destroy($id);
    }

    /**
    * Seach for a purchaseorder by it's purchaseorder number
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function byPoNum($status, $term)
    {
        if($status == 'Open'):
            $purchaseorders = DB::table('purchaseorders')
                                ->select('*')
                                ->where([
                                    ['status', $status],
                                    ['po_num', 'like', '%'.$term.'%']
                                ])->get();
        endif;
        if($status == 'Closed'):
            $purchaseorders = DB::table('purchaseorders')
                                ->select('*')
                                ->where([
                                    ['status', $status],
                                    ['po_num', 'like', '%'.$term.'%']
                                ])
                                ->orderBy('will_ship', 'desc')
                                ->get();
        endif;
        // for($i=0;$i<count($purchaseorders);$i++){
        //     $purchaseorders[$i]->routers = $this->doubleDecode($purchaseorders[$i]->routers);
        // }
        return $purchaseorders;
    }

    /**
    * Seach for a purchaseorder by customer
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function byCust($status, $term)
    {
        if($status == 'Open'):
            $purchaseorders = DB::table('purchaseorders')
                                ->select('*')
                                ->where([
                                    ['status', $status],
                                    ['customer', 'like', '%'.$term.'%']
                                ])->get();
        endif;
        if($status == 'Closed'):
            $purchaseorders = DB::table('purchaseorders')
                                ->select('*')
                                ->where([
                                    ['status', $status],
                                    ['customer', 'like', '%'.$term.'%']
                                ])
                                ->orderBy('will_ship', 'desc')
                                ->get();
        endif;
        // for($i=0;$i<count($purchaseorders);$i++){
        //     $purchaseorders[$i]->routers = $this->doubleDecode($purchaseorders[$i]->routers);
        // }
        return $purchaseorders;
    }

    /**
    * Seach for a purchaseorder by part number
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function byPartNum($status, $term)
    {
        if($status == 'Open'):
            $purchaseorders = DB::table('purchaseorders')
                                ->select('*')
                                ->where([
                                    ['status', $status],
                                    ['part_num', 'like', '%'.$term.'%']
                                ])->get();
        endif;
        if($status == 'Closed'):
            $purchaseorders = DB::table('purchaseorders')
                                ->select('*')
                                ->where([
                                    ['status', $status],
                                    ['part_num', 'like', '%'.$term.'%']
                                ])
                                ->orderBy('will_ship', 'desc')
                                ->get();
        endif;
        // for($i=0;$i<count($purchaseorders);$i++){
        //     $purchaseorders[$i]->routers = $this->doubleDecode($purchaseorders[$i]->routers);
        // }
        return $purchaseorders;
    }

    /**
     * import an Purchaseorders Excel file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        if($request->file('imported-purchaseorders')):
            $path = $request->file('imported-purchaseorders')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()):
                foreach ($data->toArray() as $row):
                    if(!empty($row)):
                        if(   
                            array_key_exists('id',$row) && 
                            array_key_exists('due_date',$row) && 
                            array_key_exists('will_ship',$row) && 
                            array_key_exists('ship_date',$row) && 
                            array_key_exists('rating',$row) && 
                            array_key_exists('sooner',$row) && 
                            array_key_exists('customer',$row) && 
                            array_key_exists('po_num',$row) && 
                            array_key_exists('part_num',$row) && 
                            array_key_exists('qty',$row) && 
                            array_key_exists('stock',$row) && 
                            array_key_exists('sales',$row) && 
                            array_key_exists('need_routers',$row) && 
                            array_key_exists('routers',$row) && 
                            array_key_exists('cust_req', $row) && 
                            array_key_exists('status', $row) && 
                            array_key_exists('invoice', $row) && 
                            array_key_exists('key', $row)
                        ):
                            $dataArray[] =
                            [
                                'id' => $row['id'],
                                'due_date' => $row['due_date'],
                                'will_ship' => $row['will_ship'],
                                'ship_date' => $row['ship_date'],
                                'rating' => $row['rating'],
                                'sooner' => $row['sooner'],
                                'customer' => $row['customer'],
                                'po_num' => $row['po_num'],
                                'part_num' => $row['part_num'],
                                'qty' => $row['qty'],
                                'stock' => $row['stock'],
                                'sales' => $row['sales'],
                                'need_routers' => $row['need_routers'],
                                'routers' => $row['routers'],
                                'cust_req' => $row['cust_req'],
                                'status' => $row['status'],
                                'invoice' => $row['invoice'],
                                'key' => $row['key']
                            ];
                        else:
                            return redirect('settings')->with('purchaseorders-import-status-error', 'Columns in file do not match database requirements! The following columns are required: id, due_date, will_ship, ship_date, rating, sooner, customer, po_num, part_num, qty, stock, sales, need_routers, routers, cust_req, status, invoice, key.');        
                        endif;
                    endif;
                endforeach;
                if(!empty($dataArray)):
                    DB::table('purchaseorders')->truncate();
                    Purchaseorder::insert($dataArray);
                    return redirect('settings')->with('purchaseorders-import-status', 'Purchase orders import was successful!');
                else:
                    return redirect('settings')->with('purchaseorders-import-status-error', 'File was empty: Nothing Imported');
                endif;
            else:
                return redirect('settings')->with('purchaseorders-import-status-error', 'File was empty: Nothing Imported');
            endif;
        else:
            return redirect('settings')->with('purchaseorders-import-status-error', 'No File Chosen: Nothing to import!');
        endif;
    }

    /**
     * Export purchaseorders as an excel file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
      Excel::create('dataentry-purchaseorders', function($excel) {
          $excel->sheet('ExportFile', function($sheet) {
                $items = DB::table('purchaseorders')->get();
                $items = json_decode(json_encode($items), true);
                $sheet->fromArray($items);
          });
      })->export('xlsx');
    }
}
