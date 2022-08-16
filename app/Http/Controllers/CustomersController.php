<?php

namespace App\Http\Controllers;

use App\User;
use App\Customer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SystemlogController as Systemlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CustomersController extends Controller
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
     * Display a listing of Customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCustomers()
    {
        $customers = DB::table('customers')
                    ->select('id','name', 'buyer', 'country')
                    ->orderBy('name')
                    ->get();
        return $customers;
    }

    /**
     * Display a single of Customers.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function getCustomer($id)
    {
        return Customer::findOrFail($id);
    }

    /**
     * Store a new Customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addCustomer(Request $request)
    {
        $this->validate($request, Customer::validations());

        Customer::create([
            'name' => $request->input(['name']),
            'shipto' => $request->input(['shipto']),
            'billto' => $request->input(['billto']),
            'buyer' => $request->input(['buyer']),
            'country' => $request->input(['country']),
            'disclaimer' => $request->input(['disclaimer']),
            'comments' => $request->input(['comments'])
        ]);
        $stext = Auth::user()->name . ' created the '. $request->name . ' customer.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Customer created successfully!"}';
    }

    /**
     * Update the specified customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCustomer(Request $request, $id)
    {
        $this->validate($request, Customer::validations());
        
        Customer::findOrFail($id)->update([
            'name' => $request->input(['name']),
            'shipto' => $request->input(['shipto']),
            'billto' => $request->input(['billto']),
            'buyer' => $request->input(['buyer']),
            'country' => $request->input(['country']),
            'disclaimer' => $request->input(['disclaimer']),
            'comments' => $request->input(['comments'])
        ]);
        $stext = Auth::user()->name . ' updated the '. $request->name . ' customer.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Customer updated successfully!"}';
    }

    /**
     * Remove the specified customer.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCustomer($id)
    {
        $this->authorize('delete', $id);
        $cust = Customer::findOrFail($id);
        Customer::destroy($id);

        $stext = Auth::user()->name . ' deleted the '. $cust->name . ' customer.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Customer deleted successfully!"}';
    }

    /**
     * import a customers Excel file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        if($request->file('imported-customers')):
            $path = $request->file('imported-customers')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()):
                foreach ($data->toArray() as $row):
                    if(!empty($row)):
                        if(   
                            array_key_exists('id',$row) && 
                            array_key_exists('name',$row) && 
                            array_key_exists('shipto',$row) && 
                            array_key_exists('billto',$row) && 
                            array_key_exists('buyer',$row) && 
                            array_key_exists('country',$row) && 
                            array_key_exists('disclaimer',$row) && 
                            array_key_exists('comments',$row)
                        ):
                            $dataArray[] =
                            [
                                'id' => $row['id'],
                                'name' => $row['name'],
                                'shipto' => $row['shipto'],
                                'billto' => $row['billto'],
                                'buyer' => $row['buyer'],
                                'country' => $row['country'],
                                'disclaimer' => $row['disclaimer'],
                                'comments' => $row['comments'],
                            ];
                        else:
                            return redirect('settings')->with('customer-import-status-error', 'Columns in file do not match database requirements! The following columns are required: id, name, shipto, billto, buyer, country, disclaimer and comments.');        
                        endif;
                    endif;
                endforeach;
                if(!empty($dataArray)):
                    DB::table('customers')->truncate();
                    Customer::insert($dataArray);
                    return redirect('settings')->with('customer-import-status', 'Customers Import was successful!');
                else:
                    return redirect('settings')->with('customer-import-status-error', 'File was empty: Nothing Imported');
                endif;
            else:
                return redirect('settings')->with('customer-import-status-error', 'File was empty: Nothing Imported');
            endif;
        else:
            return redirect('settings')->with('customer-import-status-error', 'No File Chosen: Nothing to import!');
        endif;
    }

    /**
     * Export customers as an excel file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(){
      Excel::create('dataentry-customers', function($excel) {
          $excel->sheet('ExportFile', function($sheet) {
                $items = DB::table('customers')->get();
                $items = json_decode(json_encode($items), true);
                $sheet->fromArray($items);
          });
      })->export('xlsx');
    }
}
