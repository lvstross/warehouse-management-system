<?php

namespace App\Http\Controllers;

use App\User;
use App\Vendor;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SystemlogController as Systemlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class VendorController extends Controller
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
     * Display a listing of Vendors.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVendors()
    {
        $vendors = DB::table('vendors')
                    ->select('*')
                    ->orderBy('name')
                    ->get();
        return $vendors;
    }

    /**
     * Display a single of Vendor.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function getVendor($id)
    {
        return Vendor::findOrFail($id);
    }

    /**
     * Store a new Vendor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addVendor(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|regex:/^(?!-)(?!.*--)[A-Z0-9a-z\,\.\&\s]+(?<!-)$/i|max:50',
            'contact' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Z0-9a-z\,\.\s]+(?<!-)$/i|max:50',
            'phone' => 'nullable|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\-\(\)\+\.\s]+(?<!-)$/i',
            'fax' => 'nullable|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\-\(\)\+\.\s]+(?<!-)$/i',
            'ext' => 'nullable|string|max:10',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'type' => 'nullable|string|max:50',
            'ship_address' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\-\#\@\&\(\)\:\.\s]+(?<!-)$/i|max:255',
            'purch_address' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\-\#\@\&\(\)\:\.\s]+(?<!-)$/i|max:255',
            'remit_address' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\-\#\@\&\(\)\:\.\s]+(?<!-)$/i|max:255',
            'comment_to' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\#\s]+(?<!-)$/i|max:1000'
        ]);

        Vendor::create([
            'name' => $request->input(['name']),
            'contact' => $request->input(['contact']),
            'phone' => $request->input(['phone']),
            'fax' => $request->input(['fax']),
            'ext' => $request->input(['ext']),
            'email' => $request->input(['email']),
            'website' => $request->input(['website']),
            'type' => $request->input(['type']),
            'ship_address' => $request->input(['ship_address']),
            'purch_address' => $request->input(['purch_address']),
            'remit_address' => $request->input(['remit_address']),
            'comment_to' => $request->input(['comment_to'])
        ]);

        $stext = Auth::user()->name . ' created the '. $request->name . ' vendor.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Vendor created successfully!"}';
    }

    /**
     * Update the specified vendor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function updateVendor(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|regex:/^(?!-)(?!.*--)[A-Z0-9a-z\,\.\&\s]+(?<!-)$/i|max:50',
            'contact' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Z0-9a-z\,\.\s]+(?<!-)$/i|max:50',
            'phone' => 'nullable|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\-\(\)\+\.\s]+(?<!-)$/i',
            'fax' => 'nullable|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\-\(\)\+\.\s]+(?<!-)$/i',
            'ext' => 'nullable|string|max:10',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'type' => 'nullable|string|max:50',
            'ship_address' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\-\#\@\&\(\)\:\.\s]+(?<!-)$/i|max:255',
            'purch_address' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\-\#\@\&\(\)\:\.\s]+(?<!-)$/i|max:255',
            'remit_address' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\-\#\@\&\(\)\:\.\s]+(?<!-)$/i|max:255',
            'comment_to' => 'nullable|string|regex:/^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\#\s]+(?<!-)$/i|max:1000'
        ]);
        Vendor::findOrFail($id)->update([
            'name' => $request->input(['name']),
            'contact' => $request->input(['contact']),
            'phone' => $request->input(['phone']),
            'fax' => $request->input(['fax']),
            'ext' => $request->input(['ext']),
            'email' => $request->input(['email']),
            'website' => $request->input(['website']),
            'type' => $request->input(['type']),
            'ship_address' => $request->input(['ship_address']),
            'purch_address' => $request->input(['purch_address']),
            'remit_address' => $request->input(['remit_address']),
            'comment_to' => $request->input(['comment_to'])
        ]);

        $stext = Auth::user()->name . ' updated the '. $request->name . ' vendor.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Vendor updated successfully!"}';
    }

    /**
     * Remove the specified vendor.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteVendor($id)
    {
        // $this->authorize('delete', $id);
        $vendor = Vendor::findOrFail($id);
        Vendor::destroy($id);

        $stext = Auth::user()->name . ' deleted the '. $vendor->name . ' vendor.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Vendor deleted successfully!"}';
    }

    /**
     * import a vendors Excel file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        if($request->file('imported-vendors')):
            $path = $request->file('imported-vendors')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()):
                foreach ($data->toArray() as $row):
                    if(!empty($row)):
                        if(   
                            array_key_exists('id',$row) && 
                            array_key_exists('name',$row) && 
                            array_key_exists('contact',$row) && 
                            array_key_exists('phone',$row) && 
                            array_key_exists('fax',$row) && 
                            array_key_exists('ext',$row) && 
                            array_key_exists('email',$row) && 
                            array_key_exists('website',$row) && 
                            array_key_exists('type',$row) && 
                            array_key_exists('ship_address',$row) && 
                            array_key_exists('purch_address',$row) && 
                            array_key_exists('remit_address',$row) && 
                            array_key_exists('comment_to',$row)
                        ):
                            $dataArray[] =
                            [
                                'id' => $row['id'],
                                'name' => $row['name'],
                                'contact' => $row['contact'],
                                'phone' => $row['phone'],
                                'fax' => $row['fax'],
                                'ext' => $row['ext'],
                                'email' => $row['email'],
                                'website' => $row['website'],
                                'type' => $row['type'],
                                'ship_address' => $row['ship_address'],
                                'purch_address' => $row['purch_address'],
                                'remit_address' => $row['remit_address'],
                                'comment_to' => $row['comment_to']
                            ];
                        else:
                            return redirect('settings')->with('vendor-import-status-error', 'Columns in file do not match database requirements! The following columns are required: id, name, contact, phone, fax, ext, email, website, type, ship_address, purch_address, remit_address and comment_to.');
                        endif;
                    endif;
                endforeach;
                if(!empty($dataArray)):
                    DB::table('vendors')->truncate();
                    Vendor::insert($dataArray);
                    return redirect('settings')->with('vendor-import-status', 'Vendors Import was successful!');
                else:
                    return redirect('settings')->with('vendor-import-status-error', 'File was empty: Nothing Imported');
                endif;
            else:
                return redirect('settings')->with('vendor-import-status-error', 'File was empty: Nothing Imported');
            endif;
        else:
            return redirect('settings')->with('vendor-import-status-error', 'No File Chosen: Nothing to import!');
        endif;
    }

    /**
     * Export vendors as an excel file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(){
      Excel::create('dataentry-vendors', function($excel) {
          $excel->sheet('ExportFile', function($sheet) {
                $items = DB::table('vendors')->get();
                $items = json_decode(json_encode($items), true);
                $sheet->fromArray($items);
          });
      })->export('xlsx');
    }
}
