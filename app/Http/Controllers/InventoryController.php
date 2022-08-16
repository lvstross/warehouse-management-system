<?php

namespace App\Http\Controllers;

use App\User;
use App\Inventory;
use App\Router;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SystemlogController as Systemlog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class InventoryController extends Controller
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
    * @var inventory status on every patch request
    */
    public $inv_status;

    /**
    * Display a listing of the resource in the last 12 months in descending order
    *
    * @return \Illuminate\Http\Response
    */
    public function getInventory()
    {
        $lastYear = date('Y') - 1;
        $month_day = date('m-d');
        $twelveMonthsAgo = $lastYear . '-' . $month_day;
        return DB::table('inventories')
                ->select('id', 'part_num', 'customer', 'po_num', 'qty', 'status', 'date')
                ->orderBy('id', 'desc')
                ->whereDate('date', '>=', $twelveMonthsAgo)
                ->get();
    }

    /**
    * Display a listing of all the database resources.
    *
    * @return \Illuminate\Http\Response
    */
    public function getAllInventory()
    {
        return DB::table('inventories')
                ->select('id', 'part_num', 'customer', 'po_num', 'qty', 'status', 'date')
                ->orderBy('date', 'desc')
                ->get();
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Invoice  $id
    * @return \Illuminate\Http\Response
    */
    public function getOne($id)
    {
        return Inventory::findOrFail($id);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addInventory(Request $request)
    {
        // Validate the Request
        $this->validate($request, [
            'part_num' => 'required|string|max:50',
            'po_num' => 'required|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\s]+(?<!-)$/i|max:30',
            'customer' => 'required|string|regex:/^(?!-)(?!.*--)[A-Z0-9a-z\,\.\s]+(?<!-)$/i|max:50',
            'qty' => 'required|alpha_num',
            'status' => 'required|string|max:25',
            'cust_req' => "nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\'\.\*\#\$\"\s]+(?<!-)$/i|max:500",
            'routers' => 'nullable|array',
            'boxes' => 'nullable|array',
            'log' => 'nullable|array',
            'date' => 'required|date',
            'trip_count' => 'required|string|max:5'
        ]);

        $date = $this->correctYearFormat($request->input(['date']));
        $qty = intval($request->input(['qty']));
        if( gettype($qty) != 'integer' ){
            return '{"message": "qty needs to be a number.", "value": false }';
        }

        // Create Inventory Entry
        Inventory::create([
            'part_num' => $request->input(['part_num']),
            'po_num' => $request->input(['po_num']),
            'customer' => $request->input(['customer']),
            'qty' => $qty,
            'status' => $request->input(['status']),
            'cust_req' => $request->input(['cust_req']),
            'routers' => json_encode($request->input(['routers'])),
            'boxes' => json_encode($request->input(['boxes'])),
            'log' => json_encode($request->input(['log'])),
            'date' => $date,
            'trip_count' => $request->input(['trip_count'])
        ]);

        $stext = Auth::user()->name . ' created an inventory ship ticket.';
        Systemlog::addLogEntry($stext);

        $this->updateRouterStQty();
        return '{"message": "Inventory ship ticket created and applied routers updated.", "value": true}';
    }

    /**
    * Update the stock quanity of a given router
    *
    * @param  \App\Router  $id
    * @param Integer $newtotal
    * @return \Illuminate\Http\Response
    */
    public function updateRouterStQty()
    {
        // get the most resent entry
        $lateEntry = DB::table('inventories')->orderBy('id', 'desc')->first();
        // decode the routers array
        $inv_routers = json_decode($lateEntry->routers);
        // loop through the routers array and update each routers stock qty
        for($i = 0; $i < count($inv_routers); $i++){
            // Grab the router by id
            $router = Router::findOrFail($inv_routers[$i]->id);
            // decode the move log
            $log = json_decode($router->move_log);
            // generate a new move log entry and push it to the move log array
            $logItem = new stdClass;
            $logItem->item = 'At ' . date('g:i A') . ' on ' . date('n/j/Y') . ', ' . $inv_routers[$i]->apply_qty . ' piece(s) were taken for a ship ticket with a PO # of ' . $lateEntry->po_num . ', leaving a total of ' . $inv_routers[$i]->new_router_total . ' from the original total of ' . $inv_routers[$i]->old_qty . ' piece(s).';
            array_push($log, $logItem);
            // Add to system log
            $stext = Auth::user()->name . ' applied '. $inv_routers[$i]->apply_qty .' piece(s) of router '. $router->router_num .' to a ship ticket.';
            Systemlog::addLogEntry($stext);
            // update the resource: if new qty is 0, change status to archive
            if($inv_routers[$i]->new_router_total == 0){
                $logItem2 = new stdClass;
                $logItem2->item = 'At ' . date('g:i A') . ' on ' . date('n/j/Y') . " router was archived.";
                array_push($log, $logItem2);
                $log = json_encode($log);
                // system log entry
                $s2text = $router->router_num ." router was archived because it's qty reached 0.";
                Systemlog::addLogEntry($s2text);
                $router->update([
                    'stock_qty' => $inv_routers[$i]->new_router_total,
                    'status' => 'A',
                    'move_log' => $log
                ]);                
            }else{
                $log = json_encode($log);
                $router->update([
                    'stock_qty' => $inv_routers[$i]->new_router_total,
                    'move_log' => $log
                ]);
            }
        }
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Invoice  $id
    * @return \Illuminate\Http\Response
    */
    public function updateInventory(Request $request, $id)
    {
        // Validate the Request
        $this->validate($request, [
            'part_num' => 'required|string|max:50',
            'po_num' => 'required|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\s]+(?<!-)$/i|max:30',
            'customer' => 'required|string|regex:/^(?!-)(?!.*--)[A-Z0-9a-z\,\.\s]+(?<!-)$/i|max:50',
            'qty' => 'required|numeric',
            'status' => 'required|string|max:25',
            'cust_req' => "nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\'\.\*\#\$\"\s]+(?<!-)$/i|max:500",
            'routers' => 'nullable|array',
            'boxes' => 'nullable|array',
            'log' => 'nullable|array',
            'date' => 'required|date',
            'trip_count' => 'required|string|max:5'
        ]);
        $date = $this->correctYearFormat($request->input(['date']));
        // Set the current status to check if status has changed to canceled
        $inventory = Inventory::findOrFail($id);
        $this->inv_status = $inventory->status;
        // Create Inventory Entry
        $inventory->update([
            'part_num' => $request->input(['part_num']),
            'po_num' => $request->input(['po_num']),
            'customer' => $request->input(['customer']),
            'qty' => $request->input(['qty']),
            'status' => $request->input(['status']),
            'cust_req' => $request->input(['cust_req']),
            'routers' => json_encode($request->input(['routers'])),
            'boxes' => json_encode($request->input(['boxes'])),
            'log' => json_encode($request->input(['log'])),
            'date' => $date,
            'trip_count' => $request->input(['trip_count'])
        ]);
        if($request->input(['status']) == 'Canceled' && $this->inv_status != 'Canceled'){
            $stext = Auth::user()->name ." canceled an inventory ship ticket that was created on " . $inventory->date . " with P.O.# " . $inventory->po_num . ".";
            Systemlog::addLogEntry($stext);
            $this->canceleShipTicket($id);
            return '{"message": "Inventory ship ticket was cancelled, router quantities have been put back."}';
        }else if($request->input(['status']) == 'Approved' && $this->inv_status != 'Approved'){
            $stext = Auth::user()->name ." approved inventory ship ticket created on " . $inventory->date . " with P.O.# " . $inventory->po_num . ".";
            Systemlog::addLogEntry($stext);
            return '{"message": "Inventory ship ticket has been approved."}';
        }
        $stext = Auth::user()->name ." updated an inventory ship ticket that was created on " . $inventory->date . " with P.O.# " . $inventory->po_num . ".";
        Systemlog::addLogEntry($stext);
        return '{"message": "Inventory ship ticket has successfully updated."}';
    }

    /**
    * Cancle the ship ticket and put the router quantities back
    *
    * @param  \App\Router  $id
    * @param Integer $newtotal
    * @return \Illuminate\Http\Response
    */
    public function canceleShipTicket($id)
    {
        // Get the inventory ship ticket
        $lateEntry = Inventory::findOrFail($id);
        // decode the routers array
        $inv_routers = json_decode($lateEntry->routers);
        // loop through the routers array and update each routers stock qty
        for($i = 0; $i < count($inv_routers); $i++){
            // Grab the router by id
            $router = Router::findOrFail($inv_routers[$i]->id);
            // decode the move log
            $log = json_decode($router->move_log);
            // generate a new move log entry and push it to the move log array
            $logItem = new stdClass;
            $logItem->item = 'At ' . date('g:i A') . ' on ' . date('n/j/Y') . ', ' . $inv_routers[$i]->apply_qty . ' piece(s) re-applied to this router because of a ship ticket cancelation.';
            array_push($log, $logItem);
            // Add up to new qty
            $new_qty = $router->stock_qty + $inv_routers[$i]->apply_qty;
            // update the resource: if new qty is 0, change status to archive
            // Add to system log
            $stext = $inv_routers[$i]->apply_qty .' piece(s) were re-applied to router '. $router->router_num .' because of a ship ticket cancelation.';
            Systemlog::addLogEntry($stext);
            if($router->status == 'A'){
                $logItem2 = new stdClass;
                $logItem2->item = 'At ' . date('g:i A') . ' on ' . date('n/j/Y') . " router status went from 'A' to 'II' status because of a ship ticket cancelation.";
                array_push($log, $logItem2);
                $log = json_encode($log);
                // Add to system log
                $s2text = $router->router_num .' router has been moved back into inventory with a new qty of ' . $new_qty . ' peice(s).';
                Systemlog::addLogEntry($s2text);
                $router->update([
                    'stock_qty' => $new_qty,
                    'status' => 'II',
                    'move_log' => $log
                ]);                
            }else{
                $log = json_encode($log);
                $router->update([
                    'stock_qty' => $new_qty,
                    'move_log' => $log
                ]);
            }
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Invoice  $id
    * @return \Illuminate\Http\Response
    */
    public function deleteInventory($id)
    {
        $this->authorize('delete', $id);
        $inv = Inventory::findOrFail($id);
        Inventory::destroy($id);

        $stext = Auth::user()->name . ' deleted a ship ticket created on ' . $inv->date;
        Systemlog::addLogEntry($stext);
        return '{"message": "Ship ticket successfully deleted!"}';
    }

    /**
    * Seach for an inventory by partnumber
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function byPartNum($term)
    {
        return DB::table('inventories')
            ->select('id', 'part_num', 'po_num', 'customer', 'qty', 'status', 'date')
            ->where('part_num', $term)
            ->get();
    }

    /**
    * Seach for an inventory by date
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function byDate($term)
    {
        return DB::table('inventories')
            ->select('id', 'part_num', 'po_num', 'customer', 'qty', 'status', 'date')
            ->where('date', $term)
            ->get();
    }

    /**
    * Seach for an inventory by status
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function byStatus($term)
    {
        return DB::table('inventories')
            ->select('id', 'part_num', 'po_num', 'customer', 'qty', 'status', 'date')
            ->where('status', $term)
            ->get();
    }

    /**
     * import an inventory Excel file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        if($request->file('imported-inventory')):
            $path = $request->file('imported-inventory')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()):
                foreach ($data->toArray() as $row):
                    if(!empty($row)):
                        if(   
                            array_key_exists('id',$row) && 
                            array_key_exists('part_num',$row) && 
                            array_key_exists('po_num',$row) && 
                            array_key_exists('customer',$row) && 
                            array_key_exists('qty',$row) && 
                            array_key_exists('status',$row) && 
                            array_key_exists('cust_req',$row) && 
                            array_key_exists('routers',$row) && 
                            array_key_exists('boxes',$row) && 
                            array_key_exists('log',$row) && 
                            array_key_exists('date',$row) && 
                            array_key_exists('trip_count',$row)
                        ):
                            $dataArray[] =
                            [
                                'id' => $row['id'],
                                'part_num' => $row['part_num'],
                                'po_num' => $row['po_num'],
                                'customer' => $row['customer'],
                                'qty' => $row['qty'],
                                'status' => $row['status'],
                                'cust_req' => $row['cust_req'],
                                'routers' => $row['routers'],
                                'boxes' => $row['boxes'],
                                'log' => $row['log'],
                                'date' => $row['date'],
                                'trip_count' => $row['trip_count']
                            ];
                        else:
                            return redirect('settings')->with('inventory-import-status-error', 'Columns in file do not match database requirements! The following columns are required: id, part_num, po_num, customer, qty, status, cust_req, routers, boxes, log, date, trip_count.');        
                        endif;
                    endif;
                endforeach;
                if(!empty($dataArray)):
                    DB::table('inventories')->truncate();
                    Inventory::insert($dataArray);
                    return redirect('settings')->with('inventory-import-status', 'Inventory Import was successful!');
                else:
                    return redirect('settings')->with('inventory-import-status-error', 'File was empty: Nothing Imported');
                endif;
            else:
                return redirect('settings')->with('inventory-import-status-error', 'File was empty: Nothing Imported');
            endif;
        else:
            return redirect('settings')->with('inventory-import-status-error', 'No File Chosen: Nothing to import!');
        endif;
    }

    /**
     * Export inventory as an excel file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
      Excel::create('dataentry-inventory', function($excel) {
          $excel->sheet('ExportFile', function($sheet) {
                $items = DB::table('inventories')->get();
                $items = json_decode(json_encode($items), true);
                $sheet->fromArray($items);
          });
      })->export('xlsx');
    }
}
