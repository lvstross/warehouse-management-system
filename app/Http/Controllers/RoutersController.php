<?php

namespace App\Http\Controllers;

use App\User;
use App\Router;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SystemlogController as Systemlog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class RoutersController extends Controller
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
    public function getRouters()
    {
        $lastYear = date('Y') - 5;
        $month_day = date('m-d');
        $twelveMonthsAgo = $lastYear . '-' . $month_day;
        $routers = DB::table('routers')
                            ->select(
                                'id',
                                'router_num',
                                'part_num',
                                'qty',
                                'stock_qty',
                                'status',
                                'dept_name',
                                'move_log',
                                'date',
                                'date_in_inv',
                                'key'
                            )
                            ->whereDate('date', '>=', $twelveMonthsAgo)
                            ->get();
        return $routers;
    }

    /**
    * Display a listing of all the database resources.
    *
    * @return \Illuminate\Http\Response
    */
    public function getAllRouters()
    {
        $routers = DB::table('routers')
                    ->select(
                        'id',
                        'router_num',
                        'part_num',
                        'qty',
                        'stock_qty',
                        'status',
                        'dept_name',
                        'move_log',
                        'date',
                        'date_in_inv'
                    )
                    ->get();
        return $routers;
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Router  $id
    * @return \Illuminate\Http\Response
    */
    public function getOne($id)
    {
        return Router::findOrFail($id);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addRouter(Request $request)
    {
        // Validate the Request
        $this->validate($request, [
            'router_num' => 'required|string',
            'part_num' => 'required|string|max:50',
            'qty' => 'required|alpha_num',
            'stock_qty' => 'nullable|alpha_num',
            'dept_name' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\s]+(?<!-)$/i|max:50',
            'move_log' => 'nullable|array',
            'date' => 'required|date',
            'date_in_inv' => 'nullable|date'
        ]);
        // Give stock qty a default value of zero
        $stock_qty = 0;
        // Check to see if a router with the same router number already exists
        $routers = Router::all();
        for($i = 0; $i < count($routers); $i++){
            if($request->input(['router_num']) == $routers[$i]->router_num){
                return '{"message": "Duplicate detected!", "value": true }';
            }
        }
        // Make sure the format of the date is correct
        $date = $this->correctYearFormat($request->input(['date']));
        // Create Router Entry
        Router::create([
            'router_num' => intval($request->input(['router_num'])),
            'part_num' => $request->input(['part_num']),
            'qty' => $request->input(['qty']),
            'stock_qty' => $stock_qty,
            'status' => "NIP",
            'dept_name' => $request->input(['dept_name']),
            'move_log' => json_encode($request->input(['move_log'])),
            'date' => $date,
            'date_in_inv' => $request->input(['date_in_inv']),
            'key' => 0
        ]);

        // Key the id of the latest entry
        $lateEntry = DB::table('routers')->orderBy('id', 'desc')->first();
        // find same entry and update the key to equal the id
        Router::findOrFail($lateEntry->id)->update([
            'key' => $lateEntry->id
        ]);

        $stext = Auth::user()->name . ' created router # '. $request->router_num . ' for a quantity of ' . $request->qty . ' piece(s) of the ' .$request->part_num . '(s).';
        Systemlog::addLogEntry($stext);
        return '{"message": "Router created successfully!", "value": false }';
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Router  $id
    * @return \Illuminate\Http\Response
    */
    public function updateRouter(Request $request, $id)
    {
        // Validate the Request
        $this->validate($request, [
            'router_num' => 'required|string',
            'part_num' => 'required|string|max:50',
            'qty' => 'required|numeric',
            'stock_qty' => 'nullable|numeric',
            'status' => 'required|string',
            'dept_name' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\s]+(?<!-)$/i|max:50',
            'move_log' => 'nullable|array',
            'date' => 'required|date',
            'date_in_inv' => 'nullable|date',
            'key' => 'nullable|numeric'
        ]);
        // Check to see if qty and stock_qty are null
        $stock_qty = $request->input(['stock_qty']);
        $qty = $request->input(['qty']);
        if($request->stock_qty == '' || $request->stock_qty == null){ $stock_qty = 0; }
        if($request->qty == '' || $request->qty == null){ $qty = 0; }
        // Make sure the date formats are correct for submission
        $date = $this->correctYearFormat($request->input(['date']));
        $date_in_inv = $this->correctYearFormat($request->input(['date_in_inv']));
        // Create Update Entry
        Router::findOrFail($id)->update([
            'router_num' => intval($request->input(['router_num'])),
            'part_num' => $request->input(['part_num']),
            'qty' => $qty,
            'stock_qty' => $stock_qty,
            'status' => $request->input(['status']),
            'dept_name' => $request->input(['dept_name']),
            'move_log' => json_encode($request->input(['move_log'])),
            'date' => $date,
            'date_in_inv' => $date_in_inv,
            'key' => $request->input(['key'])
        ]);

        $stext = Auth::user()->name . ' updated the ' . $request->router_num . ' router.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Router updated successfully!"}';
    }

    /**
    * Update the specified resource's department in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Router  $id
    * @return \Illuminate\Http\Response
    */
    public function updateRouterDept(Request $request)
    {
        // Validate the Request
        $this->validate($request, [
            'empl_name' => 'required|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\s]+(?<!-)$/i|max:50',
            'router_id' => 'required|numeric',
            'dept_name' => 'required|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\s]+(?<!-)$/i|max:50'
        ]);

        $router = Router::findOrFail($request->router_id);
        // decode the move log
        $log = json_decode($router->move_log);
        // generate a new move log entry and push it to the move log array
        $logItem = new stdClass;
        $logItem->item = 'At ' . date('g:i A') . ' on ' . date('n/j/Y') . ', ' . $request->input(['empl_name']) . ' moved router ' . $router->router_num . ' to the ' . $request->input(['dept_name']) . ' department through the employee protal.';
        array_push($log, $logItem);
        if($router->status == 'IP'){
            $router->update([
                'dept_name' => $request->input(['dept_name']),
                'move_log' => json_encode($log)
            ]);
            // system log
            $stext = $request->empl_name . ' moved the ' . $router->router_num . ' router to the ' . $request->dept_name . ' department through the employee portal.';
            Systemlog::addLogEntry($stext);
            return '{"message": "Router ' . $router->router_num . ' has successfuly moved to the '. $request->input(['dept_name']) . ' department."}';
        }else{
            return '{"message": "Router does not seems to be in production. Please select a router that is in production."}';
        }
    }

    /**
     * Reset the keys of each router
     *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
     */
    public function sortRouters(Request $request)
    {
        $data = $request->input();
        for($i=0; $i<count($data); $i++){
            $router = Router::findOrFail($data[$i]['id']);

            //Utility Code to reset routers back in partflow (Testing Use Only)
            // $router->update([
            //     'status' => 'NIP',
            //     'dept_name' => NULL,
            //     'move_log' => json_encode([]),
            //     'date_in_inv' => NULL,
            //     'stock_qty' => 0
            // ]);

            // Real Code
            if($router['original']['status'] != $data[$i]['status']){
                $router->update(['status' => $data[$i]['status']]);
            }
            if ($router['original']['dept_name'] != $data[$i]['dept_name']){
                $router->update(['dept_name' => $data[$i]['dept_name']]);
            }
            if ($router['original']['key'] != $data[$i]['key']){
                $router->update(['key' => $data[$i]['key']]);
            }
            $router->update([
                'move_log' => json_encode($data[$i]['move_log'])
            ]);
        }
        return $data;
    }

    /**
     * Log the sorted router
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logSort(Request $request)
    {
        $stext = '';
        if($request->dept_name == null || $request->dept_name == ''){
            $stext = Auth::user()->name . ' has moved the ' . $request->router_num . ' router to ' . $request->status . ' status.';    
        }else{
            $stext = Auth::user()->name . ' moved the ' . $request->router_num . ' router to the ' . $request->dept_name . ' department.';
        }
        Systemlog::addLogEntry($stext);
        return '{"message": "Sorted router has been logged."}';
    }

    /**
     * update all STFI status router to II status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function inventory(Request $request)
    {
        $data = $request->input();
        for($i=0; $i<count($data); $i++){
            $router = Router::findOrFail($data[$i]['id']);
            if($router->status != $data[$i]['status']){
                $router->update(['status' => $data[$i]['status']]);
                $router->update( ['date_in_inv' => $data[$i]['date_in_inv']] );
                $router->update( ['stock_qty' => $data[$i]['stock_qty']] );
                $router->update( ['move_log' => json_encode($data[$i]['move_log'])] );
                // system log
                $stext = Auth::user()->name . ' submitted the ' . $router->router_num . ' router to inventory with a stock quantity of ' . $data[$i]['stock_qty'] . ' piece(s).';
                Systemlog::addLogEntry($stext);
            }
        }
        return $data;
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Router  $id
    * @return \Illuminate\Http\Response
    */
    public function deleteRouter($id)
    {
        $this->authorize('delete', $id);
        $router = Router::findOrFail($id);
        Router::destroy($id);

        $stext = Auth::user()->name . ' deleted the ' . $router->router_num . ' router.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Router deleted successfully!"}';
    }

    /**
    * Seach for an router by it's router number
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function byRouterNum($term)
    {
        $routers = DB::table('routers')
                            ->select(
                                'id',
                                'router_num',
                                'part_num',
                                'qty',
                                'stock_qty',
                                'status',
                                'dept_name',
                                'move_log',
                                'date',
                                'date_in_inv',
                                'key'
                            )
                            ->where('router_num', $term)
                            ->get();
        return $routers;
    }

    /**
    * Seach for an router by it's part number
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function byPartNum($term)
    {
        $routers = DB::table('routers')
                            ->select(
                                'id',
                                'router_num',
                                'part_num',
                                'qty',
                                'stock_qty',
                                'status',
                                'dept_name',
                                'move_log',
                                'date',
                                'date_in_inv',
                                'key'
                            )
                            ->where('part_num', $term)
                            ->get();
        return $routers;
    }

    /**
    * Seach for an router by it's status
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function byStatus($term)
    {
        $routers = DB::table('routers')
                            ->select(
                                'id',
                                'router_num',
                                'part_num',
                                'qty',
                                'stock_qty',
                                'status',
                                'dept_name',
                                'move_log',
                                'date',
                                'date_in_inv',
                                'key'
                            )
                            ->where('status', $term)
                            ->get();
        return $routers;
    }

    /**
     * import an Routers Excel file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        if($request->file('imported-routers')):
            $path = $request->file('imported-routers')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()):
                foreach ($data->toArray() as $row):
                    if(!empty($row)):
                        if(   
                            array_key_exists('id',$row) && 
                            array_key_exists('router_num',$row) && 
                            array_key_exists('part_num',$row) && 
                            array_key_exists('qty',$row) && 
                            array_key_exists('stock_qty',$row) && 
                            array_key_exists('status',$row) && 
                            array_key_exists('dept_name',$row) && 
                            array_key_exists('move_log',$row) && 
                            array_key_exists('date',$row) && 
                            array_key_exists('date_in_inv',$row) && 
                            array_key_exists('key',$row)
                        ):
                            if($row['stock_qty'] == null):
                                $row['stock_qty'] = 0;
                            endif;
                            $dataArray[] =
                            [
                                'id' => $row['id'],
                                'router_num' => $row['router_num'],
                                'part_num' => $row['part_num'],
                                'qty' => $row['qty'],
                                'stock_qty' => $row['stock_qty'],
                                'status' => $row['status'],
                                'dept_name' => $row['dept_name'],
                                'move_log' => $row['move_log'],
                                'date' => $row['date'],
                                'date_in_inv' => $row['date_in_inv'],
                                'key' => $row['key']
                            ];
                        else:
                            return redirect('settings')->with('router-import-status-error', 'Columns in file do not match database requirements! The following columns are required: id, router_num, part_num, qty, stock_qty, status, dept_name, move_log, date, date_in_inv and key.');        
                        endif;
                    endif;
                endforeach;
                if(!empty($dataArray)):
                    DB::table('routers')->truncate();
                    Router::insert($dataArray);
                    return redirect('settings')->with('router-import-status', 'Routers Import was successful!');
                else:
                    return redirect('settings')->with('router-import-status-error', 'File was empty: Nothing Imported');
                endif;
            else:
                return redirect('settings')->with('router-import-status-error', 'File was empty: Nothing Imported');
            endif;
        else:
            return redirect('settings')->with('router-import-status-error', 'No File Chosen: Nothing to import!');
        endif;
    }

    /**
     * Export routers as an excel file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
      Excel::create('dataentry-routers', function($excel) {
          $excel->sheet('ExportFile', function($sheet) {
                $items = DB::table('routers')->get();
                $items = json_decode(json_encode($items), true);
                $sheet->fromArray($items);
          });
      })->export('xlsx');
    }
}
