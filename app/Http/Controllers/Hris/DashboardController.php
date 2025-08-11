<?php

namespace App\Http\Controllers\Hris;

use App\Models\DepartmentAll;
use App\Models\EmployeeAtribut;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Datatables;


/**
 * Class DashboardController
 * @package App\Http\Controllers\Hris
 */
class DashboardController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
    }

    public function index(Request $request)
    {
        $data_jabatan_aktif = EmployeeAtribut::select(DB::raw('COUNT(*) as total'), 'status_jabatan', 'status_aktif')->where('status_aktif','AKTIF')->where('site_nirwana_id','NAG')->groupBy('status_jabatan')->get();
        $total_semua_aktif = $data_jabatan_aktif->sum('total');
        $data_jabatan_non_aktif = EmployeeAtribut::select(DB::raw('COUNT(*) as total'), 'status_jabatan', 'status_aktif')->where('status_aktif','TIDAK AKTIF')->where('site_nirwana_id','NAG')->groupBy('status_jabatan')->get();
        $total_semua_non_aktif = $data_jabatan_non_aktif->sum('total');
        return View::make('hris/dashboard', $this->data, compact('data_jabatan_aktif', 'data_jabatan_non_aktif', 'total_semua_aktif', 'total_semua_non_aktif'));
    }
    public function tes(){
        $loggedAdmin = Auth::guard('admin')->user();
        $role=$loggedAdmin->role_user;
        $type=$loggedAdmin->type;
        $email=$loggedAdmin->email;
        $enroll_id=$loggedAdmin->enroll_id;
        $modul=$loggedAdmin->modul;
        return View::make('hris/dashboard_page',compact('role','type','email','enroll_id','modul'), $this->data);
    }

}
