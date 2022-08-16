<?php

namespace App\Http\Controllers;

use App\Company;
use App\Invoice;
use App\Http\Controllers\SystemlogController as Systemlog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CompanyController extends Controller
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
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function getOne($id)
    {
        return Company::findOrFail($id);
    }

    /**
    * Get the one company for displaying
    *
    * @return mixed
    */
    public function getName(){
        return Company::where('id', 1)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addCompany(Request $request)
    {
        $this->validate($request, Company::validations());
        return Company::create([
            'name' => $request->input(['name']),
            'address' => $request->input(['address']),
            'phone' => $request->input(['phone']),
            'email' => $request->input(['email']),
            'desc' => $request->input(['desc']),
            'invoice_con' => $request->input(['invoice_con']),
            'shipper_con' => $request->input(['shipper_con']),
            'router_con' => $request->input(['router_con']),
            'po_con' => $request->input(['po_con']),
            'inv_prefix' => $request->input(['inv_prefix'])
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function updateCompany(Request $request, $id)
    {
        $this->validate($request, Company::validations());

        Company::findOrFail($id)->update([
            'name' => $request->input(['name']),
            'address' => $request->input(['address']),
            'phone' => $request->input(['phone']),
            'email' => $request->input(['email']),
            'desc' => $request->input(['desc']),
            'invoice_con' => $request->input(['invoice_con']),
            'shipper_con' => $request->input(['shipper_con']),
            'router_con' => $request->input(['router_con']),
            'po_con' => $request->input(['po_con']),
            'inv_prefix' => $request->input(['inv_prefix'])
        ]);
        $stext = Auth::user()->name . ' updated the company information.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Company updated successfully!"}';
    }

    /**
    * Get the invoice prefix number
    *
    * @return string
    */
    public function getInvPrefix()
    {
        // Get the values in the Databse
        $prefix = DB::table('companies')
                    ->select('inv_prefix')
                    ->get();
        $inv_num = DB::table('invoices')
                    ->select('inv_num')
                    ->orderBy('inv_num', 'desc')
                    ->limit(1)
                    ->get();
        // Check if inv_num returned a value
        if(count($inv_num) > 0){
            // Set the values to Strings
            $str_prefix = strval($prefix[0]->inv_prefix);
            $str_inv_num = strval($inv_num[0]->inv_num);
            // Check if the given prefix matches the last inv_num prefix
            // if there are matches, return the value of the inv_num with the
            // same prefix plus one.
            preg_match('/'.$str_prefix.'/', $str_inv_num, $matches);
            if(count($matches) > 0){
                return $inv_num[0]->inv_num + 1;
            }else{
                return $prefix[0]->inv_prefix . '0000';
            }
        }
        return $prefix[0]->inv_prefix . '0000';
    }

    /**
     * import a company excel file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        if($request->file('imported-company')):
            $path = $request->file('imported-company')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()):
                foreach ($data->toArray() as $row):
                    if(!empty($row)):
                        if(   
                            array_key_exists('id',$row) && 
                            array_key_exists('name',$row) && 
                            array_key_exists('address',$row) && 
                            array_key_exists('phone',$row) && 
                            array_key_exists('email',$row) && 
                            array_key_exists('desc',$row) && 
                            array_key_exists('invoice_con',$row) && 
                            array_key_exists('shipper_con',$row) && 
                            array_key_exists('po_con',$row) && 
                            array_key_exists('inv_prefix',$row)
                        ):
                            $dataArray[] =
                            [
                                'id' => $row['id'],
                                'name' => $row['name'],
                                'address' => $row['address'],
                                'phone' => $row['phone'],
                                'email' => $row['email'],
                                'desc' => $row['desc'],
                                'invoice_con' => $row['invoice_con'],
                                'shipper_con' => $row['shipper_con'],
                                'po_con' => $row['po_con'],
                                'inv_prefix' => $row['inv_prefix']
                            ];
                        else:
                            return redirect('settings')->with('company-import-status-error', 'Columns in file do not match database requirements! The following columns are required: id, name, address, phone, email, desc, invoice_con, shipper_con, po_con, inv_prefix.');        
                        endif;
                    endif;
                endforeach;
                if(!empty($dataArray)):
                    DB::table('companies')->truncate();
                    Company::insert($dataArray);
                    return redirect('settings')->with('company-import-status', 'Company Import was successful!');
                else:
                    return redirect('settings')->with('company-import-status-error', 'File was empty: Nothing Imported');
                endif;
            else:
                return redirect('settings')->with('company-import-status-error', 'File was empty: Nothing Imported');
            endif;
        else:
            return redirect('settings')->with('company-import-status-error', 'No File Chosen: Nothing to import!');
        endif;
    }

    /**
     * Export company as an excel file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
      Excel::create('dataentry-company', function($excel) {
          $excel->sheet('ExportFile', function($sheet) {
                $items = DB::table('companies')->get();
                $items = json_decode(json_encode($items), true);
                $sheet->fromArray($items);
          });
      })->export('xlsx');
    }
}
