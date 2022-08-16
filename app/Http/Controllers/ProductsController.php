<?php

namespace App\Http\Controllers;

use App\User;
use App\Product;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SystemlogController as Systemlog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductsController extends Controller
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
    public function getProducts() 
    {
        $products = DB::table('products')
                    ->select('id','name', 'description', 'material', 'rev', 'rev_date')
                    ->orderBy('name', 'asc')
                    ->get();
        return $products;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $id
     * @return \Illuminate\Http\Response
     */
    public function getOne($id)
    {
        return Product::findOrFail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addProduct(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:products|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\s]+(?<!-)$/i|max:25',
            'description' => 'nullable|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\(\)\/\s]+(?<!-)$/i|max:255',
            'material' => 'nullable|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\(\)\.\/\,\s]+(?<!-)$/i|max:255',
            'rev' => 'nullable|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\(\)\.\/\,\s]+(?<!-)$/i|max:20',
            'rev_date' => 'nullable|date'
        ]);

        $rev_date = $this->correctYearFormat($request->input(['rev_date']));

        Product::create([
            'name' => $request->input(['name']),
            'description' => $request->input(['description']),
            'material' => $request->input(['material']),
            'rev' => $request->input(['rev']),
            'rev_date' => $rev_date
        ]);

        $stext = Auth::user()->name . ' created the '. $request->name . ' product.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Product created successfully!"}';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProduct(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\s]+(?<!-)$/i|max:25',
            'description' => 'nullable|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\(\)\/\s]+(?<!-)$/i|max:255',
            'material' => 'nullable|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\(\)\.\/\,\s]+(?<!-)$/i|max:255',
            'rev' => 'nullable|regex:/^(?!-)(?!.*--)[A-Za-z0-9\-\(\)\.\/\,\s]+(?<!-)$/i|max:20',
            'rev_date' => 'nullable|date'
        ]);
        
        $rev_date = $this->correctYearFormat($request->input(['rev_date']));

        Product::findOrFail($id)->update([
            'name' => $request->input(['name']),
            'description' => $request->input(['description']),
            'material' => $request->input(['material']),
            'rev' => $request->input(['rev']),
            'rev_date' => $rev_date
        ]);

        $stext = Auth::user()->name . ' updated the '. $request->name . ' product.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Product updated successfully!"}';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProduct($id)
    {
        $this->authorize('delete', $id);
        $prod = Product::findOrFail($id);
        Product::destroy($id);

        $stext = Auth::user()->name . ' deleted the '. $prod->name . ' product.';
        Systemlog::addLogEntry($stext);
        return '{"message": "Product deleted successfully!"}';
    }

    /**
     * import a products Excel file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        if($request->file('imported-products')):
            $path = $request->file('imported-products')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()):
                foreach ($data->toArray() as $row):
                    if(!empty($row)):
                        if(   
                            array_key_exists('id',$row) && 
                            array_key_exists('name',$row) && 
                            array_key_exists('description',$row) && 
                            array_key_exists('material',$row) && 
                            array_key_exists('rev',$row) && 
                            array_key_exists('rev_date',$row)
                        ):
                            $dataArray[] =
                            [
                                'id' => $row['id'],
                                'name' => $row['name'],
                                'description' => $row['description'],
                                'material' => $row['material'],
                                'rev' => $row['rev'],
                                'rev_date' => $row['rev_date'],
                            ];
                        else:
                            return redirect('settings')->with('product-import-status-error', 'Columns in file do not match database requirements! The following columns are required: id, name, description, material, rev and rev_date.');        
                        endif;
                    endif;
                endforeach;
                if(!empty($dataArray)):
                    DB::table('products')->truncate();
                    Product::insert($dataArray);
                    return redirect('settings')->with('product-import-status', 'Products Import was successful!');
                else:
                    return redirect('settings')->with('product-import-status-error', 'File was empty: Nothing Imported');
                endif;
            else:
                return redirect('settings')->with('product-import-status-error', 'File was empty: Nothing Imported');
            endif;
        else:
            return redirect('settings')->with('product-import-status-error', 'No File Chosen: Nothing to import!');
        endif;
    }

    /**
     * Export products as an excel file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(){
      Excel::create('dataentry-products', function($excel) {
          $excel->sheet('ExportFile', function($sheet) {
                $items = DB::table('products')->get();
                $items = json_decode(json_encode($items), true);
                $sheet->fromArray($items);
          });
      })->export('xlsx');
    }
}
