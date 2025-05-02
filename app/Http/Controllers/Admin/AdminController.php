<?php

namespace App\Http\Controllers\Admin;

use Carbon;
use App\Classes\Reply;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Notification;
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


    public function get_employee_contract_for_reminder_non_staff(){

        $thirty_day_more = date('Y-m-d',strtotime('+30 days',strtotime(date("Y-m-d")))) . PHP_EOL;
        $inStatusKontrak='AND y.contract_end BETWEEN curdate() AND "'.$thirty_day_more.'"';

        $inStatusAktif='AND z.status_aktif = "aktif" AND z.status_staff = "NON STAFF"';

        $data_kontrak_non_staff = DB::select("select z.enroll_id,z.nik,z.employee_name,z.department_name,z.sub_dept_name,z.status_aktif,z.tanggal_resign,z.ibu_kandung,z.nomor_ktp,z.status_staff,y.id,y.contract,y.contract_end from (select a.enroll_id,a.id,e.contract,e.contract_end from (select enroll_id,max(contract) contract,max(contract_end) contract_end from employee_contract group by enroll_id)e inner join (select id,enroll_id,contract,contract_end from employee_contract)a on e.enroll_id=a.enroll_id and e.contract_end=a.contract_end)y right join (select enroll_id,nik,employee_name,tanggal_resign,tempat_lahir,nomor_tlpn,agama,status_kawin,nomor_kk,pendidikan_terakhir,jurusan_pendidikan,alamat_rumah,department_name,sub_dept_name,status_aktif,ibu_kandung,nomor_ktp,status_staff from employee_atribut)z on y.enroll_id=z.enroll_id where z.enroll_id is not null ".$inStatusKontrak." ".$inStatusAktif." order by enroll_id");

        $enrollIdsToday = collect($data_kontrak_non_staff)->pluck('enroll_id')->unique()->toArray();

        $alreadyNotifiedIds = Notification::where('type', 'KONTRAK')
            ->whereDate('created_at', today())
            ->get()
            ->pluck('enroll_ids')
            ->flatten()
            ->unique()
            ->toArray();

        $newEnrollIds = array_diff($enrollIdsToday, $alreadyNotifiedIds);

        $newEnrollIds = array_values($newEnrollIds);
        $receiverEmails = ['fadli', 'mega@ptnag.com', 'ersa@ptnag.com', 'kiki@ptnag.com', 'rudy@ptnag.com', 'hrd','hadiyoso@nag.nirwanaindonesia.com','ramon'];

        if (count($newEnrollIds) > 0) {
            foreach ($receiverEmails as $receiverEmail) {
                Notification::create([
                    'sender_email' => 'system',
                    'receiver_email' => $receiverEmail,
                    'type' => 'KONTRAK',
                    'href_menu' => route('hris.hrd.kontrak_kerja'),
                    'message' => 'Ada ' . count($newEnrollIds) . ' karyawan baru yang kontraknya akan berakhir.',
                    'enroll_ids' => $newEnrollIds,
                    'is_read' => false,
                    'is_delete' => false,
                    'status_staff' => 'NON STAFF',
                ]);
            }
        }
    }
    public function get_employee_contract_for_reminder_staff(){


        $inStatusAktif='AND z.status_aktif = "aktif" AND z.status_staff = "STAFF"';
        $thirty_day_later = date('Y-m-d', strtotime('+30 days'));

        $inStatusKontrak = 'AND y.contract_end = "'.$thirty_day_later.'"';

        $data_kontrak_staff = DB::select("select z.enroll_id,z.nik,z.employee_name,z.department_name,z.sub_dept_name,z.status_aktif,z.tanggal_resign,z.ibu_kandung,z.nomor_ktp,z.status_staff,y.id,y.contract,y.contract_end from (select a.enroll_id,a.id,e.contract,e.contract_end from (select enroll_id,max(contract) contract,max(contract_end) contract_end from employee_contract group by enroll_id)e inner join (select id,enroll_id,contract,contract_end from employee_contract)a on e.enroll_id=a.enroll_id and e.contract_end=a.contract_end)y right join (select enroll_id,nik,employee_name,tanggal_resign,tempat_lahir,nomor_tlpn,agama,status_kawin,nomor_kk,pendidikan_terakhir,jurusan_pendidikan,alamat_rumah,department_name,sub_dept_name,status_aktif,ibu_kandung,nomor_ktp,status_staff from employee_atribut)z on y.enroll_id=z.enroll_id where z.enroll_id is not null ".$inStatusKontrak." ".$inStatusAktif." order by enroll_id");

        $enrollIdsToday = collect($data_kontrak_staff)->pluck('enroll_id')->unique()->toArray();

        $receiverEmails = ['fadli', 'mega@ptnag.com', 'ersa@ptnag.com', 'rudy@ptnag.com', 'hrd','hadiyoso@nag.nirwanaindonesia.com','ramon'];

        $alreadyNotifiedIds = Notification::where('type', 'KONTRAK')
        ->whereDate('created_at', today())
        ->get()
        ->pluck('enroll_ids')
        ->flatten()
        ->unique()
        ->toArray();

        $newEnrollIds = array_diff($enrollIdsToday, $alreadyNotifiedIds);

        $newEnrollIds = array_values($newEnrollIds);

        foreach ($data_kontrak_staff as $staff) {
            if (in_array($staff->enroll_id, $newEnrollIds)) {
                foreach ($receiverEmails as $receiverEmail) {
                    Notification::create([
                        'sender_email' => 'system',
                        'receiver_email' => $receiverEmail,
                        'type' => 'KONTRAK',
                        'href_menu' => route('hris.hrd.kontrak_kerja'),
                        'message' => 'Kontrak karyawan ' . $staff->employee_name . ' akan berakhir pada ' . Carbon\Carbon::parse($staff->contract_end)->translatedFormat('d F Y') . '.',
                        'enroll_ids' => [$staff->enroll_id], // Tetap array
                        'is_read' => false,
                        'is_delete' => false,
                        'status_staff' => 'STAFF',
                    ]);
                }
            }
        }
    }

    public function get_notifications(Request $request)
    {
        $this->get_employee_contract_for_reminder_staff();
        $this->get_employee_contract_for_reminder_non_staff();
          // Ambil data notifikasi dari database
          $notifications = Notification::with(['sender', 'receiver'])->where('receiver_email', Auth::guard('admin')->user()->email)->where('is_delete', 0)
          ->orderBy('created_at', 'desc')
          ->get();
          $unreadCount = Notification::where('is_read', 0)->where('is_delete', 0)->where('receiver_email', Auth::guard('admin')->user()->email)->count();
      // Kembalikan data dalam format JSON
      return response()->json([
          'notifications' => $notifications,
          'unread_count' => $unreadCount,
      ]);
    }


    public function get_employee(Request $request)
    {
          $search = $request->q;

          $employees = EmployeeAtribut::selectRaw('enroll_id, concat(enroll_id, " - ", nik, " - ", employee_name) as select_employee')
              ->where(function($query) use ($search) {
                  $query->where('enroll_id', 'like', "%$search%")
                      ->orWhere('nik', 'like', "%$search%")
                      ->orWhere('employee_name', 'like', "%$search%");
              })
              ->where('status_aktif', 'aktif')
              ->orderBy('employee_name', 'asc')
              ->limit(30) // batasi hasil
              ->get();

          return response()->json($employees);
    }



    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->update(['is_read' => 1]);
            $unreadCount = Notification::where('is_read', 0)->where('is_delete', 0)->where('receiver_email', Auth::guard('admin')->user()->email)->count();
            return response()->json(['success' => true,'unread_count' => $unreadCount,]);
        }


        return response()->json(['success' => false], 404);
    }
    public function markAsDelete(Request $request, $id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->update(['is_delete' => 1]);
            $unreadCount = Notification::where('is_read', 0)->where('is_delete', 0)->where('receiver_email', Auth::guard('admin')->user()->email)->count();
            return response()->json(['success' => true,'unread_count' => $unreadCount,]);
        }


        return response()->json(['success' => false], 404);
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
