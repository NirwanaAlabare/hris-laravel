<?php

namespace App\Http\Controllers\Hris;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Dompdf\Options;
use Dompdf\FontMetrics;
use App\Models\EmployeeAtribut;
use App\Models\VoucherBazzar;
use App\Imports\KontrakKerjaImport;
use App\Imports\KontrakKerjaImportToDatabase;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\MasterDataAbsenKehadiran;
use App\Exports\exportExcelKontrak;
use App\Models\DasarPotBPJS;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;

class PenilaianKinerjaController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
    }

    public function index(){
        $selectEmployee =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')->groupby('enroll_id')->orderby('employee_name', 'asc')->get();
        $selectNoKTP = EmployeeAtribut::selectRaw('nomor_ktp')->groupby('nomor_ktp')->orderby('nomor_ktp', 'asc')->get();
        return;
    }

}
