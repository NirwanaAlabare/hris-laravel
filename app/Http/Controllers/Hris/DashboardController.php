<?php

namespace App\Http\Controllers\Hris;

use App\Models\DepartmentAll;
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
        return View::make('hris/dashboard', $this->data);
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
