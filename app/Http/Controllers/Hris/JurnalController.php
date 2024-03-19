<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\Jurnal;
use Illuminate\Support\Facades\View;
use App\Models\RekapPerhitunganPayroll;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Datatables;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Exports\JurnalExport;

/**
 * Class RekapPerhitunganPayrollController
 * @package App\Http\Controllers\Hris
 */
class JurnalController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Rekap Perhitungan Payroll';
    }

    public function index()
    {
        $this->periode_payroll = $this->ajax_getperiodepayroll();

        $this->latestData = Jurnal::latest('updated_at')->first();
        
        $this->loggedAdmin = Auth::guard('admin')->user();
        return View::make('hris/jurnal', $this->data);
    }


    public function ajax_getperiodepayroll()
    {
        $query =  Jurnal::selectRaw('periode_payroll')
                        ->groupby('periode_payroll')
                        ->orderby('periode_payroll', 'desc')
                        ->get();
        return $query;

    }
    public function ajax_exportexcel(Request $request)
    {
        $data=Jurnal::where('periode_payroll',$request->periode_payroll)->get();

        return Excel::download(new JurnalExport($data),'Data_jurnal'.time().'.xlsx');
    }

}
