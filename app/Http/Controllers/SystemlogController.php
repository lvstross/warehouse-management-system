<?php

namespace App\Http\Controllers;

use App\User;
use App\Systemlog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SystemlogController extends Controller
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
    * Display a listing of the resource in the last three months.
    *
    * @return \Illuminate\Http\Response
    */
    public function getSystemLog()
    {
        $date = date('Y-m-j');
        $threeMonthsAgo = strtotime('-3 month', strtotime($date));
        $threeMonthsAgo = date('Y-m-j', $threeMonthsAgo);
        $logs = DB::table('systemlogs')
                            ->select('id','logs','time','date')
                            ->orderBy('id', 'desc')
                            ->whereDate('date', '>=', $threeMonthsAgo)
                            ->get();
        return $logs;
    }

    /**
    * Display a listing of resources from the users desired length
    *
    * @return \Illuminate\Http\Response
    */
    public function getSystemLogByMonth($month)
    {
        $date = date('Y-m-j');
        $term = '-'.$month.' month';
        $xMonthsAgo = strtotime($term, strtotime($date));
        $xMonthsAgo = date('Y-m-j', $xMonthsAgo);
        $logs = DB::table('systemlogs')
                            ->select('id','logs','time','date')
                            ->orderBy('id', 'desc')
                            ->whereDate('date', '>=', $xMonthsAgo)
                            ->get();
        return $logs;
    }

    /**
    * Display a listing of resources by a given date
    *
    * @return \Illuminate\Http\Response
    */
    public function getSystemLogByDate($date)
    {
        $logs = DB::table('systemlogs')
                            ->select('id','logs','time','date')
                            ->orderBy('id', 'desc')
                            ->whereDate('date', '=', $date)
                            ->get();
        return $logs;
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function getSystemLogAll()
    {
        $logs = DB::table('systemlogs')
                            ->select(
                                'id',
                                'logs',
                                'time',
                                'date'
                            )
                            ->orderBy('date', 'desc')
                            ->get();
        return $logs;
    }

    /**
    * Store a newly created resource in storage.
    *
    * @return \Illuminate\Http\Response
    */
    public static function addLogEntry($text)
    {
        // Create Router Entry
        Systemlog::create([
            'logs' => $text,
            'time' => date('g:i A'),
            'date' => date('Y-m-d')
        ]);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Router  $id
    * @return \Illuminate\Http\Response
    */
    public function deleteSystemLogItem($id)
    {
        $this->authorize('delete', $id);
        return Systemlog::destroy($id);
    }

    /**
     * import a system log excel file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        if($request->file('imported-systemlog')):
            $path = $request->file('imported-systemlog')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()):
                foreach ($data->toArray() as $row):
                    if(!empty($row)):
                        if(   
                            array_key_exists('id',$row) && 
                            array_key_exists('logs',$row) && 
                            array_key_exists('time',$row) && 
                            array_key_exists('date',$row)
                        ):
                            $dataArray[] =
                            [
                                'id' => $row['id'],
                                'logs' => $row['logs'],
                                'time' => $row['time'],
                                'date' => $row['date']
                            ];
                        else:
                            return redirect('settings')->with('systemlog-import-status-error', 'Columns in file do not match database requirements! The following columns are required: id, time, logs, date.');        
                        endif;
                    endif;
                endforeach;
                if(!empty($dataArray)):
                    DB::table('systemlogs')->truncate();
                    Systemlog::insert($dataArray);
                    return redirect('settings')->with('systemlog-import-status', 'Systemlog Import was successful!');
                else:
                    return redirect('settings')->with('systemlog-import-status-error', 'File was empty: Nothing Imported');
                endif;
            else:
                return redirect('settings')->with('systemlog-import-status-error', 'File was empty: Nothing Imported');
            endif;
        else:
            return redirect('settings')->with('systemlog-import-status-error', 'No File Chosen: Nothing to import!');
        endif;
    }

    /**
     * Export system log as an excel file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
      Excel::create('dataentry-systemlog', function($excel) {
          $excel->sheet('ExportFile', function($sheet) {
                $items = DB::table('systemlogs')->get();
                $items = json_decode(json_encode($items), true);
                $sheet->fromArray($items);
          });
      })->export('xlsx');
    }
}