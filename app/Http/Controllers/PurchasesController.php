<?php

namespace App\Http\Controllers;

use App\User;
use App\Purchases;
use App\Vendor;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SystemlogController as Systemlog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PurchasesController extends Controller
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
    * Display a listing of the resource that are open.
    *
    * @return \Illuminate\Http\Response
    */
    public function getPurchasesOpen()
    {
        $purchases = DB::table('purchases')
                ->select('*')
                ->where('status', 'Open')
                ->orderBy('po_num', 'desc')
                ->get();
        for($i=0;$i<count($purchases);$i++){
            $vendor = Vendor::find($purchases[$i]->vendor);
            if($vendor){
                $purchases[$i]->vendor = $vendor->name;
            }else{
                $purchases[$i]->vendor = 'Vendor Not Found';
            }
        }
        return $purchases;
    }

    /**
    * Display a listing of the resource that are closed.
    *
    * @return \Illuminate\Http\Response
    */
    public function getPurchasesClosed()
    {
        $purchases = DB::table('purchases')
                ->select('*')
                ->where('status', 'Closed')
                ->orderBy('recv_date', 'desc')
                ->get();
        for($i=0;$i<count($purchases);$i++){
            $vendor = Vendor::find($purchases[$i]->vendor);
            if($vendor){
                $purchases[$i]->vendor = $vendor->name;
            }else{
                $purchases[$i]->vendor = 'Vendor Not Found';
            }
        }
        return $purchases;
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Invoice  $id
    * @return \Illuminate\Http\Response
    */
    public function getOne($id)
    {
        return Purchases::findOrFail($id);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addPurchase(Request $request)
    {
        // Validate the Request
        $this->validate($request, Purchases::validations());
        $due_date = NULL;
        $recv_date = NULL;
        $vendor_confirm_order = NULL;
        if($request->input(['due_date'])){ $due_date = $this->correctYearFormat($request->input(['due_date'])); }
        if($request->input(['recv_date'])){ $recv_date = $this->correctYearFormat($request->input(['recv_date'])); }
        if($request->input(['vendor_confirm_order'])){ $vendor_confirm_order = $this->correctYearFormat($request->input(['vendor_confirm_order'])); }

        // Create Purchase Entry
        Purchases::create([
            'po_num' => $request->input(['po_num']),
            'recv_num' => $request->input(['recv_num']),
            'vendor' => $request->input(['vendor']),
            'due_date' => $due_date,
            'recv_date' => $recv_date,
            'terms' => $request->input(['terms']),
            'carrier' => $request->input(['carrier']),
            'vendor_confirm_order' => $vendor_confirm_order,
            'items' => json_encode($request->input(['items'])),
            'po_total' => $request->input(['po_total']),
            'entered_by' => Auth::user()->name,
            'enter_date' => date('Y-m-d'),
            'status' => 'Open',
            'comments' => $request->input(['comments'])
        ]);

        $stext = Auth::user()->name . ' made a purchase with a purchase order number of '. $request->po_num . ' with a grand total of $' . $request->po_total . '.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Purchase created successfully!"}';
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Invoice  $purchase
    * @return \Illuminate\Http\Response
    */
    public function updatePurchase(Request $request, $id)
    {
        // Validate the Request
        $this->validate($request, Purchases::validations());
        
        $due_date = NULL;
        $recv_date = NULL;
        $vendor_confirm_order = NULL;
        if($request->input(['due_date'])){ $due_date = $this->correctYearFormat($request->input(['due_date'])); }
        if($request->input(['vendor_confirm_order'])){ $vendor_confirm_order = $this->correctYearFormat($request->input(['vendor_confirm_order'])); }
        if($request->input(['status']) === 'Closed'){
            if($request->input(['recv_date'])){ 
                $recv_date = $this->correctYearFormat($request->input(['recv_date'])); 
            }else{
                $recv_date = date('Y-m-d');
            }
        }

        // Update Purchase Entry
        Purchases::findOrFail($id)->update([
            'po_num' => $request->input(['po_num']),
            'recv_num' => $request->input(['recv_num']),
            'vendor' => $request->input(['vendor']),
            'due_date' => $due_date,
            'recv_date' => $recv_date,
            'terms' => $request->input(['terms']),
            'carrier' => $request->input(['carrier']),
            'vendor_confirm_order' => $vendor_confirm_order,
            'items' => json_encode($request->input(['items'])),
            'po_total' => $request->input(['po_total']),
            'entered_by' => $request->input(['entered_by']),
            'enter_date' => $request->input(['enter_date']),
            'modified_by' => Auth::user()->name,
            'modify_date' => date('Y-m-d'),
            'status' => $request->input(['status']),
            'comments' => $request->input(['comments'])
        ]);

        $stext = Auth::user()->name . ' updated vendor purchase order '. $request->po_num . '.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Purchase updated successfully!"}';
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Invoice  $id
    * @return \Illuminate\Http\Response
    */
    public function deletePurchase($id)
    {
        // $this->authorize('delete', $id);
        $purchase = Purchases::findOrFail($id);
        Purchases::destroy($id);

        $stext = Auth::user()->name . ' deleted po number ' . $purchase->po_num;
        Systemlog::addLogEntry($stext);
        return '{"message": "Purchase deleted successfully!"}';
    }

    /**
    * Seach for a purchase by it's purchaseorder number
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function byPoNum($status, $term)
    {
        if($status == 'Open'):
            $purchases = DB::table('purchases')
                                ->select('*')
                                ->where([
                                    ['status', $status],
                                    ['po_num', 'like', '%'.$term.'%']
                                ])->get();
        endif;
        if($status == 'Closed'):
            $purchases = DB::table('purchases')
                                ->select('*')
                                ->where([
                                    ['status', $status],
                                    ['po_num', 'like', '%'.$term.'%']
                                ])
                                ->orderBy('recv_date', 'desc')
                                ->get();
        endif;
        for($i=0;$i<count($purchases);$i++){
            $vendor = Vendor::find($purchases[$i]->vendor);
            if($vendor){
                $purchases[$i]->vendor = $vendor->name;
            }else{
                $purchases[$i]->vendor = 'Vendor Not Found';
            }
        }
        return $purchases;
    }

    /**
    * Seach for a purchaseorder by vendor
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function byVendor($status, $term)
    {
        if($status == 'Open'):
            $purchases = DB::table('purchases')
                                ->select('*')
                                ->where([
                                    ['status', $status],
                                    ['vendor', $term]
                                ])->get();
        endif;
        if($status == 'Closed'):
            $purchases = DB::table('purchases')
                                ->select('*')
                                ->where([
                                    ['status', $status],
                                    ['vendor', $term]
                                ])
                                ->orderBy('recv_date', 'desc')
                                ->get();
        endif;
        for($i=0;$i<count($purchases);$i++){
            $vendor = Vendor::find($purchases[$i]->vendor);
            if($vendor){
                $purchases[$i]->vendor = $vendor->name;
            }else{
                $purchases[$i]->vendor = 'Vendor Not Found';
            }
        }
        return $purchases;
    }

    /**
     * import an purchases Excel file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        if($request->file('imported-purchases')):
            $path = $request->file('imported-purchases')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()):
                foreach ($data->toArray() as $row):
                    if(!empty($row)):
                        if(   
                            array_key_exists('id',$row) && 
                            array_key_exists('po_num',$row) && 
                            array_key_exists('recv_num',$row) && 
                            array_key_exists('vendor',$row) && 
                            array_key_exists('due_date',$row) && 
                            array_key_exists('recv_date',$row) && 
                            array_key_exists('terms',$row) && 
                            array_key_exists('carrier',$row) && 
                            array_key_exists('vendor_confirm_order',$row) &&
                            array_key_exists('items',$row) && 
                            array_key_exists('po_total',$row) && 
                            array_key_exists('entered_by',$row) && 
                            array_key_exists('enter_date',$row) && 
                            array_key_exists('modified_by',$row) && 
                            array_key_exists('modify_date',$row) && 
                            array_key_exists('status',$row) && 
                            array_key_exists('comments',$row)
                        ):
                            $dataArray[] =
                            [
                                'id' => $row['id'],
                                'po_num' => $row['po_num'],
                                'recv_num' => $row['recv_num'],
                                'vendor' => $row['vendor'],
                                'due_date' => $row['due_date'],
                                'recv_date' => $row['recv_date'],
                                'terms' => $row['terms'],
                                'carrier' => $row['carrier'],
                                'vendor_confirm_order' => $row['vendor_confirm_order'],
                                'items' => json_encode($row['items']),
                                'po_total' => $row['po_total'],
                                'entered_by' => $row['entered_by'],
                                'enter_date' => $row['enter_date'],
                                'modified_by' => $row['modified_by'],
                                'modify_date' => $row['modify_date'],
                                'status' => $row['status'],
                                'comments' => $row['comments']
                            ];
                        else:
                            return redirect('settings')->with('purchase-import-status-error', 'Columns in file do not match database requirements! The following columns are required: id, po_num, recv_num, vendor, due_date, recv_date, terms, carrier, vendor_confirm_order, items, po_total, entered_by, enter_date, modified_by, modify_date, status and comments.');        
                        endif;
                    endif;
                endforeach;
                if(!empty($dataArray)):
                    DB::table('purchases')->truncate();
                    Purchases::insert($dataArray);
                    return redirect('settings')->with('purchase-import-status', 'Purchases Import was successful!');
                else:
                    return redirect('settings')->with('purchase-import-status-error', 'File was empty: Nothing Imported');
                endif;
            else:
                return redirect('settings')->with('purchase-import-status-error', 'File was empty: Nothing Imported');
            endif;
        else:
            return redirect('settings')->with('purchase-import-status-error', 'No File Chosen: Nothing to import!');
        endif;
    }

    /**
     * Export purchases as an excel file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
      Excel::create('dataentry-purchases', function($excel) {
          $excel->sheet('ExportFile', function($sheet) {
                $items = DB::table('purchases')->get();
                $items = json_decode(json_encode($items), true);
                $sheet->fromArray($items);
          });
      })->export('xlsx');
    }
}
