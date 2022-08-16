<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SystemlogController as Systemlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends Controller
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
    public function getUsers()
    {
        return User::all();
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\User  $id
    * @return \Illuminate\Http\Response
    */
    public function getUser($id)
    {
        return User::findOrFail($id);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addUser(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6',
            'permission' => 'required|max:1'
        ]);

        User::create([
            'name' => $request->input(['name']),
            'email' => $request->input(['email']),
            'password' => bcrypt($request->input(['password'])),
            'permission' => $request->input(['permission'])
        ]);

        $stext = Auth::user()->name . ' added '. $request->name . ' as a user with level ' . $request->permission . ' permissions.';
        Systemlog::addLogEntry($stext);
        return '{"message": "User created successfully!"}';
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\User  $id
    * @return \Illuminate\Http\Response
    */
    public function updateUser(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191',
            'permission' => 'required|max:1'
        ]);

        User::findOrFail($id)->update([
            'name' => $request->input(['name']),
            'email' => $request->input(['email']),
            'permission' => $request->input(['permission']),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $stext = Auth::user()->name . ' updated '. $request->name . '(s) user information.';
        Systemlog::addLogEntry($stext);
        return '{"message": "User updated successfully!"}';
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\User  $id
    * @return \Illuminate\Http\Response
    */
    public function deleteUser($id)
    {
        $this->authorize('delete', $id);
        $user = User::findOrFail($id);
        User::destroy($id);

        $stext = Auth::user()->name . ' deleted the '. $user->name . ' user.';
        Systemlog::addLogEntry($stext);
        return '{"message": "User deleted successfully!"}';
    }

    /**
     * import a users excel file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        if($request->file('imported-user')):
            $path = $request->file('imported-user')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
            if(!empty($data) && $data->count()):
                foreach ($data->toArray() as $row):
                    if(!empty($row)):
                        if(   
                            array_key_exists('id',$row) && 
                            array_key_exists('name',$row) && 
                            array_key_exists('email',$row) && 
                            array_key_exists('password',$row) && 
                            array_key_exists('permission',$row) && 
                            array_key_exists('remember_token',$row) && 
                            array_key_exists('created_at',$row) && 
                            array_key_exists('updated_at',$row)
                        ):
                            $dataArray[] =
                            [
                                'id' => $row['id'],
                                'name' => $row['name'],
                                'email' => $row['email'],
                                'password' => $row['password'],
                                'permission' => $row['permission'],
                                'remember_token' => $row['remember_token'],
                                'created_at' => $row['created_at'],
                                'updated_at' => $row['updated_at']
                            ];
                        else:
                            return redirect('settings')->with('user-import-status-error', 'Columns in file do not match database requirements! The following columns are required: id, name, email, password, permission, remember_token, created_at, updated_at.');        
                        endif;
                    endif;
                endforeach;
                if(!empty($dataArray)):
                    DB::table('users')->truncate();
                    User::insert($dataArray);
                    return redirect('settings')->with('user-import-status', 'Users Import was successful!');
                else:
                    return redirect('settings')->with('user-import-status-error', 'File was empty: Nothing Imported');
                endif;
            else:
                return redirect('settings')->with('user-import-status-error', 'File was empty: Nothing Imported');
            endif;
        else:
            return redirect('settings')->with('user-import-status-error', 'No File Chosen: Nothing to import!');
        endif;
    }

    /**
     * Export users as an excel file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
    {
      Excel::create('dataentry-users', function($excel) {
          $excel->sheet('ExportFile', function($sheet) {
                $items = DB::table('users')->get();
                $items = json_decode(json_encode($items), true);
                $sheet->fromArray($items);
          });
      })->export('xlsx');
    }
}
