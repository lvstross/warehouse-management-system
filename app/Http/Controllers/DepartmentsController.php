<?php

namespace App\Http\Controllers;

use App\User;
use App\Department;
use App\Router;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SystemlogController as Systemlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DepartmentsController extends Controller
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
    public function getDepartments()
    {
        return Department::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $id
     * @return \Illuminate\Http\Response
     */
    public function getOne($id)
    {
        return Department::findOrFail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addDepartment(Request $request)
    {
        $this->validate($request, Department::validations());
        Department::create([
            'dept_name' => $request->input(['dept_name']),
            'dept_bg_color' => $request->input(['dept_bg_color']),
            'dept_txt_color' => $request->input(['dept_txt_color']),
            'key' => 0
        ]);

        // Key the id of the latest entry
        $lateEntry = DB::table('departments')->orderBy('id', 'desc')->first();
        // find same entry and update the key to equal the id
        Department::findOrFail($lateEntry->id)->update([
            'key' => $lateEntry->id
        ]);

        $stext = Auth::user()->name . ' created the '. $request->dept_name . ' department.';
        Systemlog::addLogEntry($stext);

        return '{ 
            "message": "Department successfully created.",
            "dept_name": "'.$request->input(['dept_name']).'",
            "dept_bg_color": "'.$request->input(['dept_bg_color']).'", 
            "dept_txt_color": "'.$request->input(['dept_txt_color']).'", 
            "key": '.$lateEntry->id.'
        }';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDepartment(Request $request, $id)
    {
        $this->validate($request, Department::validations());

        $department = Department::findOrFail($id);
        if($request->input(['dept_name']) != $department->dept_name){
            $routers = DB::table('routers')
                    ->select('id', 'dept_name')
                    ->where('dept_name', $department->dept_name)
                    ->where('status', 'IP')
                    ->get();
            for($i=0;$i<count($routers);$i++){
                Router::findOrFail($routers[$i]->id)->update([
                    'dept_name' => $request->input(['dept_name'])
                ]);
            }
        }

        $department->update([
            'dept_name' => $request->input(['dept_name']),
            'dept_bg_color' => $request->input(['dept_bg_color']),
            'dept_txt_color' => $request->input(['dept_txt_color']),
            'key' => $request->input(['key'])
        ]);

        $stext = Auth::user()->name . ' updated the '. $request->dept_name . ' department.';
        Systemlog::addLogEntry($stext);

        return '{ 
            "message": "Department successfully updated.",
            "dept_name": "'.$request->input(['dept_name']).'",
            "dept_bg_color": "'.$request->input(['dept_bg_color']).'", 
            "dept_txt_color": "'.$request->input(['dept_txt_color']).'", 
            "key": '.$request->input(['key']).'
        }';
    }

    /**
     * Sort and update all the keys of every department
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sortDepartments(Request $request)
    {
        $data = $request->input();
        for($i=0; $i<count($data); $i++){
            Department::findOrFail($data[$i]['id'])->update([
                'key' => $data[$i]['key']
            ]);
        }

        $stext = Auth::user()->name . ' sorted the departments.';
        Systemlog::addLogEntry($stext);

        return '{ "message": "Department keys successfully updated."}';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteDepartment($id)
    {
        $this->authorize('delete', $id);
        $department = Department::findOrFail($id);
        $routers = DB::table('routers')
                ->select('id', 'dept_name')
                ->where('dept_name', $department->dept_name)
                ->where('status', 'IP')
                ->get();
        for($i=0;$i<count($routers);$i++){
            Router::findOrFail($routers[$i]->id)->update([
                'dept_name' => NULL
            ]);
        }
        Department::destroy($id);
        $stext = Auth::user()->name . ' deleted the ' . $department->dept_name . ' departments.';
        Systemlog::addLogEntry($stext);
        
        return '{ 
            "message": "Department successfully deleted.",
            "id": '.$id.'
        }';
    }

    /**
     * import a departments Excel file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        if($request->file('imported-departments')):
            $path = $request->file('imported-departments')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()):
                foreach ($data->toArray() as $row):
                    if(!empty($row)):
                        if(   
                            array_key_exists('id',$row) && 
                            array_key_exists('dept_name',$row) && 
                            array_key_exists('dept_bg_color',$row) && 
                            array_key_exists('dept_txt_color',$row) && 
                            array_key_exists('key',$row)
                        ):
                            $dataArray[] =
                            [
                                'id' => $row['id'],
                                'dept_name' => $row['dept_name'],
                                'dept_bg_color' => $row['dept_bg_color'],
                                'dept_txt_color' => $row['dept_txt_color'],
                                'key' => $row['key']
                            ];
                        else:
                            return redirect('settings')->with('department-import-status-error', 'Columns in file do not match database requirements! The following columns are required: id, dept_name, dept_bg_color, dept_txt_color, key.');        
                        endif;
                    endif;
                endforeach;
                if(!empty($dataArray)):
                    DB::table('departments')->truncate();
                    Department::insert($dataArray);
                    return redirect('settings')->with('department-import-status', 'Departments Import was successful!');
                else:
                    return redirect('settings')->with('department-import-status-error', 'File was empty: Nothing Imported');
                endif;
            else:
                return redirect('settings')->with('department-import-status-error', 'File was empty: Nothing Imported');
            endif;
        else:
            return redirect('settings')->with('department-import-status-error', 'No File Chosen: Nothing to import!');
        endif;
    }

    /**
     * Export departments as an excel file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
      Excel::create('dataentry-departments', function($excel) {
          $excel->sheet('ExportFile', function($sheet) {
                $items = DB::table('departments')->get();
                $items = json_decode(json_encode($items), true);
                $sheet->fromArray($items);
          });
      })->export('xlsx');
    }
}
