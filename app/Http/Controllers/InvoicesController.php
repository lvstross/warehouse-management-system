<?php

namespace App\Http\Controllers;

use App\User;
use App\Invoice;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SystemlogController as Systemlog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class InvoicesController extends Controller
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
    public function getInvoices()
    {
        $lastYear = date('Y') - 1;
        $month_day = date('m-d');
        $twelveMonthsAgo = $lastYear . '-' . $month_day;
        $invoices = DB::table('invoices')
                            ->select('id','inv_num', 'date', 'customer', 'total', 'create_date')
                            ->orderBy('date', 'desc')
                            ->whereDate('date', '>=', $twelveMonthsAgo)
                            ->get();
        $newArr = [];
        foreach($invoices as $key => $value){
            if($key === 'customer'){
                $d_decoded;
                $counter;
                while(gettype($value) == 'string' && $counter <= 5){
                     $d_decoded = json_decode($value, true);
                     $counter++;
                }
                 array_push($newArr, $d_decoded);
            }else {
                array_push($newArr, $value);
            }
        }
        return $newArr;
    }

    /**
    * Display a listing of all the database resources.
    *
    * @return \Illuminate\Http\Response
    */
    public function getAllInvoices()
    {
        $invoices = DB::table('invoices')
                            ->select('id','inv_num', 'date', 'customer', 'total', 'create_date')
                            ->orderBy('date', 'desc')
                            ->get();
        $newArr = [];
        foreach($invoices as $key => $value){
            if($key === 'customer'){
                $d_decoded;
                $counter;
                while(gettype($value) == 'string' && $counter <= 5){
                     $d_decoded = json_decode($value, true);
                     $counter++;
                }
                 array_push($newArr, $d_decoded);
            }else {
                array_push($newArr, $value);
            }
        }
        return $newArr;
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Invoice  $id
    * @return \Illuminate\Http\Response
    */
    public function getOne($id)
    {
        return Invoice::findOrFail($id);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addInvoice(Request $request)
    {
        // Validate the Request
        $this->validate($request, [
            'inv_num' => 'required|numeric',
            'date' => 'required|date',
            'customer' => 'required|array', // array of customer info3
            'po_num' => 'required|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\s]+(?<!-)$/i|max:30',
            'line_items' => 'required|array', // array of line items
            'misc_char' => 'nullable|regex:/^(?!-)(?!.*--)[0-9\.]+(?<!-)$/i',
            'ship_fee' => 'nullable|regex:/^(?!-)(?!.*--)[0-9\.]+(?<!-)$/i',
            'total' => 'required|regex:/^(?!-)(?!.*--)[0-9\.]+(?<!-)$/i',
            'carrier' => 'nullable|string|max:50',
            'memo' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\#\$\s]+(?<!-)$/i|max:255',
        ]);

        $date = $this->correctYearFormat($request->input(['date']));

        // Create Invoice Entry
        Invoice::create([
            'inv_num' => $request->input(['inv_num']),
            'date' => $date,
            'customer' => json_encode($request->input(['customer'])), // array of customer info
            'po_num' => $request->input(['po_num']),
            'line_items' => json_encode($request->input(['line_items'])), // arry of line items
            'misc_char' => $request->input(['misc_char']),
            'ship_fee' => $request->input(['ship_fee']),
            'total' => $request->input(['total']),
            'carrier' => $request->input(['carrier']),
            'memo' => $request->input(['memo']),
            'create_date' => date('F j, Y')
        ]);

        $stext = Auth::user()->name . ' created an invoice with an invoice number of '. $request->inv_num . ', for PO # '. $request->po_num .' with a grand total of $' . $request->total . '.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Invoice created successfully!"}';
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Invoice  $invoice
    * @return \Illuminate\Http\Response
    */
    public function updateInvoice(Request $request, $id)
    {
        // Validate the Request
        $this->validate($request, [
            'inv_num' => 'required|numeric',
            'date' => 'required|date',
            'customer' => 'required|array', // array of customer info
            'po_num' => 'required|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\s]+(?<!-)$/i|max:30',
            'line_items' => 'required|array', // array of line items
            'misc_char' => 'nullable|regex:/^(?!-)(?!.*--)[0-9\.]+(?<!-)$/i',
            'ship_fee' => 'nullable|regex:/^(?!-)(?!.*--)[0-9\.]+(?<!-)$/i',
            'total' => 'required|regex:/^(?!-)(?!.*--)[0-9\.]+(?<!-)$/i',
            'carrier' => 'nullable|string|max:50',
            'memo' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\#\$\s]+(?<!-)$/i|max:255',
        ]);

        $date = $this->correctYearFormat($request->input(['date']));

        // Create Invoice Entry
        Invoice::findOrFail($id)->update([
            'inv_num' => $request->input(['inv_num']),
            'date' => $date,
            'customer' => json_encode($request->input(['customer'])), // array of customer info
            'po_num' => $request->input(['po_num']),
            'line_items' => json_encode($request->input(['line_items'])), // arry of line items
            'misc_char' => $request->input(['misc_char']),
            'ship_fee' => $request->input(['ship_fee']),
            'total' => $request->input(['total']),
            'carrier' => $request->input(['carrier']),
            'memo' => $request->input(['memo']),
        ]);

        $stext = Auth::user()->name . ' updated invoice '. $request->inv_num . '.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Invoice updated successfully!"}';
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Invoice  $id
    * @return \Illuminate\Http\Response
    */
    public function deleteInvoice($id)
    {
        $this->authorize('delete', $id);
        $inv = Invoice::findOrFail($id);
        Invoice::destroy($id);

        $stext = Auth::user()->name . ' deleted invoice # ' . $inv->inv_num;
        Systemlog::addLogEntry($stext);
        return '{"message": "Invoice deleted successfully!"}';
    }

    /**
    * Get invoice report data between two dates
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function betweenDates($start, $end)
    {
        $invoices = DB::table('invoices')
                    ->select('id','inv_num', 'date', 'customer', 'total')
                    ->whereBetween('date', [$start, $end])
                    ->get();
        $newArr = [];
        foreach($invoices as $key => $value){
            if($key === 'customer'){
                 $d_decoded = $this->doubleDecode($value);
                 array_push($newArr, $d_decoded);
            }else {
                array_push($newArr, $value);
            }
        }
        return $newArr;
    }

    /**
    * Seach for an invoice by it's invoice number
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function byInvoiceNum($term)
    {
        $searchResult = DB::table('invoices')
                            ->select('id','inv_num', 'date', 'customer', 'total')
                            ->where('inv_num', $term)
                            ->get();
        $newArr = [];
        foreach($searchResult as $key => $value){
            if($key === 'customer'){
                 $d_decoded = $this->doubleDecode($value);
                 array_push($newArr, $d_decoded);
            }else {
                array_push($newArr, $value);
            }
        }
        return $newArr;
    }

    /**
     * import an invoices Excel file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        if($request->file('imported-invoices')):
            $path = $request->file('imported-invoices')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()):
                foreach ($data->toArray() as $row):
                    if(!empty($row)):
                        if(   
                            array_key_exists('id',$row) && 
                            array_key_exists('inv_num',$row) && 
                            array_key_exists('date',$row) && 
                            array_key_exists('customer',$row) && 
                            array_key_exists('po_num',$row) && 
                            array_key_exists('line_items',$row) && 
                            array_key_exists('misc_char',$row) && 
                            array_key_exists('ship_fee',$row) &&
                            array_key_exists('total',$row) && 
                            array_key_exists('carrier',$row) && 
                            array_key_exists('memo',$row) && 
                            array_key_exists('create_date',$row)
                        ):
                            $dataArray[] =
                            [
                                'id' => $row['id'],
                                'inv_num' => $row['inv_num'],
                                'date' => $row['date'],
                                'customer' => json_encode($row['customer']),
                                'po_num' => $row['po_num'],
                                'line_items' => json_encode($row['line_items']),
                                'misc_char' => $row['misc_char'],
                                'ship_fee' => $row['ship_fee'],
                                'total' => $row['total'],
                                'carrier' => $row['carrier'],
                                'memo' => $row['memo'],
                                'create_date' => $row['create_date']
                            ];
                        else:
                            return redirect('settings')->with('invoice-import-status-error', 'Columns in file do not match database requirements! The following columns are required: id, inv_num, date, customer, po_num, line_items, misc_char, ship_fee, total, carrier, memo and create_date.');        
                        endif;
                    endif;
                endforeach;
                if(!empty($dataArray)):
                    DB::table('invoices')->truncate();
                    Invoice::insert($dataArray);
                    return redirect('settings')->with('invoice-import-status', 'Invoices Import was successful!');
                else:
                    return redirect('settings')->with('invoice-import-status-error', 'File was empty: Nothing Imported');
                endif;
            else:
                return redirect('settings')->with('invoice-import-status-error', 'File was empty: Nothing Imported');
            endif;
        else:
            return redirect('settings')->with('invoice-import-status-error', 'No File Chosen: Nothing to import!');
        endif;
    }

    /**
     * Export invoices as an excel file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
      Excel::create('dataentry-invoices', function($excel) {
          $excel->sheet('ExportFile', function($sheet) {
                $items = DB::table('invoices')->get();
                $items = json_decode(json_encode($items), true);
                $sheet->fromArray($items);
          });
      })->export('xlsx');
    }
}