<?php

namespace App\Http\Controllers\Admin;

use Carbon;
use App\Classes\Reply;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Exports\AdminExport;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\Admin\CreateRequest;
use App\Http\Requests\Admin\Admin\UpdateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Crypt;


class AdminController extends AdminBaseController
{
    /**
     * Constructor for the Employees
     */

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Admin';
        $this->adminActive = 'active';
    }

    public function index()
    {
        return View::make('admin.admin.index', $this->data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajaxAdmin(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        if(request()->ajax()) {

            $columns = array(
                0 => 'name',
                1 => 'email',
                2 => 'role_user',
                3 => 'level',
                4 => 'last_login',
                5 => 'created_at'
            );
    
            $totalData = Admin::count();

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
    
            if(empty($request->input('search.value')))
            {
                $totalFiltered = $totalData;
                
                if ($loggedAdmin->role_user == "superadmin") {
                    $query = Admin::
                    offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

                    $totalFiltered = Admin::
                    whereRaw('email = "' . $loggedAdmin->email . '"')
                    ->count();                          

                } else if ($loggedAdmin->role_user == "admin") {
                    $query = Admin::
                    whereRaw('role_user <> "superadmin"')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

                    $totalFiltered = Admin::
                    whereRaw('role_user <> "superadmin"')
                    ->count();                          
                } else {
                    $query = Admin::
                    whereRaw('role_user <> "superadmin and email = "' . $loggedAdmin->email . '"')
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

                    $totalFiltered = Admin::
                    whereRaw('role_user <> "superadmin and email = "' . $loggedAdmin->email . '"')
                    ->count();                          
                }

            } else {
                $search = $request->input('search.value');
    
                $query =  Admin::selectRaw('id, name, password, role_user, level, email, date_format(last_login, "%Y-%m-%d %H:%i:%s") as last_login, date_format(created_at, "%Y-%m-%d %H:%i:%s") as created_at, date_format(updated_at, "%Y-%m-%d %H:%i:%s") as updated_at')
                                ->where('name','LIKE',"%{$search}%")
                                ->orWhere('email', 'LIKE',"%{$search}%")
                                ->orWhere('last_login', 'LIKE',"%{$search}%")
                                ->orWhere('created_at', 'LIKE',"%{$search}%")
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();
    
                $totalFiltered = Admin::where('name','LIKE',"%{$search}%")
                                ->orWhere('email', 'LIKE',"%{$search}%")
                                ->orWhere('last_login', 'LIKE',"%{$search}%")
                                ->orWhere('created_at', 'LIKE',"%{$search}%")
                                ->count();

            }
        
            $data = array();
            if(!empty($query))
            {
                foreach ($query as $q)
                {
                    $showData = $q->id;
                    $editData = $q->id;
                    $delData = $q->id . "/" . $q->email;
                    
                    $nestedData['name'] = $q->name;
                    $nestedData['email'] = $q->email;
                    $role_user = "";
                    switch ($q->role_user) {
                        case 'guest':
                            $role_user = "Guest";
                            break;
                        case 'absensi':
                            $role_user = "Absensi";
                            break;
                        case 'payroll':
                            $role_user = "Payroll";
                            break;
                        case 'admin':
                            $role_user = "Administrator";
                            break;
                        case 'superadmin':
                            $role_user = "Super Admin";
                            break;
                    }
                    $nestedData['role_user'] = $role_user;
                    $level = "";
                    switch ($q->level) {
                        case 'read':
                            $level = "Lihat";
                            break;
                        case 'cread':
                            $level = "Input dan Lihat";
                            break;
                        case 'updel':
                            $level = "Update dan Delete";
                            break;
                        case 'crud':
                            $level = "CRUD";
                            break;
                    }                    
                    $nestedData['level'] = $level;
                    $nestedData['last_login'] = $q->last_login->format('Y-m-d H:i:s');
                    $nestedData['created_at'] = $q->created_at->format('Y-m-d');
                    $nestedData['option'] = '
                    <a href="" type="button" class="btn btn-icon btn-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" title="Show/Edit/Delete"> <i class="fa fa-navicon"></i></a>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        <li><a href="javascript:void(0)" data-toggle="tooltip" id="showData-link" data-id="' . $showData . '" data-original-title="Show"><i class="fa fa-eye"></i> Show</a></li>
                        <li><a href="javascript:void(0)" data-toggle="tooltip" id="editData-link" data-id="' . $editData . '" data-original-title="Edit"><i class="fa fa-pencil"></i> Edit</a></li>
                        <li><a href="javascript:void(0)" data-toggle="tooltip" id="delData-link" data-id="' . $delData . '" data-original-title="Delete"><i class="fa fa-remove"></i> Delete</li>
                    </ul>';
    
                    $data[] = $nestedData;
    
                }
            }
    
            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data
                );
    
            echo json_encode($json_data);
            }    
    }

    /**
     * Show the form for creating a new admin
     */
    public function create()
    {
        return View::make('admin.admin.create', $this->data);
    }

    public function update(Request $request)
    {  
        $id = $request->id;
        $name = $request->name;
        $email = $request->email;
        $role_user = $request->role_user;
        $level = $request->level;
        $password = $request->password;
        
        if(!is_null($password)) {
            info('Isi Password');
            $query =  Admin::where('id','=',$id)
                        ->update([
                            'name' => $name, 
                            'email' => $email,
                            'role_user' => $role_user,
                            'level' => $level,
                            'password' => Hash::make($password),
                        ]);
        } else {
            info('Null Password');
            $query =  Admin::where('id','=',$id)
                        ->update([
                            'name' => $name, 
                            'email' => $email,
                            'role_user' => $role_user,
                            'level' => $level,
                        ]);
        }
        
        return Response()->json($query);
    }

    public function ajax_resetpwd(Request $request)
    {  
        $name = $request->name;
        $email = $request->email;
        $password_new = $request->password_new;
        $password_new = Hash::make($password_new);

        if(!is_null($password_new)) {

            $countPass = Admin::where('email','=',"{$email}")
                ->count();

            if($countPass > 0) {
                Admin::where('email','=',$email)
                    ->update([
                        'name' => $name, 
                        'password' => $password_new,
                    ]);

                return true;
            } else {
                return false;
            }
        } else {
            $countPass = Admin::where('email','=',"{$email}")
                ->count();

            if($countPass > 0) {
                Admin::where('email','=',$email)
                    ->update([
                        'name' => $name, 
                    ]);

                return true;
            } else {
                return false;
            }
        }

/*         if(!is_null($password)) {
            info('Isi Password');
            $query =  Admin::where('id','=',$id)
                        ->update([
                            'name' => $name, 
                            'email' => $email,
                            'role_user' => $role_user,
                            'level' => $level,
                            'password' => Hash::make($password),
                        ]);
        } else {
            info('Null Password');
            $query =  Admin::where('id','=',$id)
                        ->update([
                            'name' => $name, 
                            'email' => $email,
                            'role_user' => $role_user,
                            'level' => $level,
                        ]);
        } */
        
    }

    public function store_ori(CreateRequest $request)
    {
        try {
            $employee = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_user' => $request->role_user,
                'level' => $request->level,
                'password' => Hash::make($request->password),
                'last_login' => Carbon\Carbon::now('Asia/Kolkata'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        return Reply::redirect(route('admin.admin.index'), '</strong> successfully added to the Database');
    }

    /**
     * Show the form for editing the specified admin
     */
    public function edit($id)
    {
        $this->employeesActive = 'active';
        $this->admin_user = Admin::findOrFail($id);
        return View::make('admin.admin.edit', $this->data);
    }

    public function editprofile()
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $dataArray['name'] = $loggedAdmin->name;
        $dataArray['email'] = $loggedAdmin->email;

        $role_user = "";
        switch ($loggedAdmin->role_user) {
            case 'guest':
                $role_user = "Guest";
                break;
            case 'absensi':
                $role_user = "Absensi";
                break;
            case 'payroll':
                $role_user = "Payroll";
                break;
            case 'admin':
                $role_user = "Administrator";
                break;
            case 'superadmin':
                $role_user = "Super Admin";
                break;
        }
        $dataArray['role_user'] = $role_user;
        $level = "";
        switch ($loggedAdmin->level) {
            case 'read':
                $level = "Lihat";
                break;
            case 'cread':
                $level = "Input dan Lihat";
                break;
            case 'updel':
                $level = "Update dan Delete";
                break;
            case 'crud':
                $level = "CRUD";
                break;
        }                                                                        
        $dataArray['level'] = $level;
        $this->dataArray = $dataArray;
        return View::make('admin.admin.editprofile', $this->data);
    }

    /**
     * Update the specified admin in storage.
     */
    public function update_ori(UpdateRequest $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return Reply::redirect(route('admin.admin.index'), '<strong>Success</strong> Updated Successfully');
    }

    public function destroy(Request $request)
    {
        $user = admin();
        if ($user->id == $request->id) {
            return Response()->json("Admin");
        }
        info("Delete : " . $request->id);
        Admin::destroy($request->id);

        return Response()->json("Delete User berhasil");
    }

    //export Admin List
    public function export()
    {
        $fileName = 'Admin-' . time() . '.xlsx';
        if (request()->filled('s')) {
            return (new AdminExport(request()->input('s')))->download($fileName);
        }
        return (new AdminExport)->download($fileName);
    }

    public function show_data(Request $request)
    {
        $id = $request->showData;

        $query =  Admin::selectRaw('id, name, password, 
                            case when role_user = "guest" then "Guest"
                                 when role_user = "absensi" then "Absensi"
                                 when role_user = "payroll" then "Payroll"
                                 when role_user = "admin" then "Administrator"
                                 when role_user = "superadmin" then "Super Admin"
                                 else "" 
                            end role_user, 
                            case when level = "read" then "Lihat"
                                 when level = "cread" then "Input dan Lihat"
                                 when level = "updel" then "Update dan Delete"
                                 when level = "crud" then "CRUD"
                                 else "" 
                            end level,                                  
                            email, date_format(last_login, "%Y-%m-%d %H:%i:%s") as last_login, date_format(created_at, "%Y-%m-%d %H:%i:%s") as created_at, date_format(updated_at, "%Y-%m-%d %H:%i:%s") as updated_at')
                            ->where('id','=',$id)
                            ->first();
        
        return Response()->json($query);

    }

    public function edit_data(Request $request)
    {
        $id = $request->editData;

        $query =  Admin::where('id','=',$id)->first();
        
        return Response()->json($query);

    }

    public function store(Request $request)
    {  
        try {
            $query = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_user' => $request->role_user,
                'level' => $request->level,
                'password' => Hash::make($request->password),
                'last_login' => Carbon\Carbon::now('Asia/Jakarta'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        return Response()->json($query);
    }
    
    public function keepalive()
    {        
        return true;
    }

    public function screenlock()
    {
        Session::put('lock', '1');
        $this->name = Auth::guard('admin')->user()->name;
        $this->email = Auth::guard('admin')->user()->email;
        
        return View::make('admin/screen_lock', $this->data);
    }

}
