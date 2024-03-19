<?php

namespace App\Http\Controllers\Hris;

use App\Exports\RekapPerhitunganPayrollCutOffExport;
use App\Http\Controllers\AdminBaseController;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\RekapPerhitunganPayrollCutOff;
use App\Models\EmployeeAtribut;
use App\Models\DepartmentAll;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Datatables;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class RekapPerhitunganPayrollCutOffController
 * @package App\Http\Controllers\Hris
 */
class RekapPerhitunganPayrollCutOffController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'CutOff Payroll';
    }

    public function index()
    {
        $this->periode_payroll = $this->ajax_getperiodepayroll();
        return View::make('hris/rekapperhitunganpayrollcutoff', $this->data);
    }

    public function ajax_getperiodepayroll()
    {
        $query =  RekapPerhitunganPayrollCutoff::selectRaw('CONCAT(periode_tahun_payroll,"-",periode_bulan_payroll) periode_payroll')
                        ->groupby('periode_payroll')
                        ->orderby('periode_payroll', 'desc')
                        ->get();
        return $query;

    }

    public function ajax_exportexcel(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        $periode_payroll = $request->input('periode_payroll');

        $fileName = 'RekapPerhitunganPayrollCutOff_' . time() . '.xlsx';
        return (new RekapPerhitunganPayrollCutOffExport)->exportParams($periode_payroll)->download($fileName);

    }

}
