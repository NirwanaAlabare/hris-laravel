<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\DataClosingPayroll;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Datatables;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class DataClosingPayrollController
 * @package App\Http\Controllers\Hris
 */
class DataClosingPayrollController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'DATA CLOSING PAYROLL';
    }

    public function index()
    {
        return View::make('hris/dataclosingpayroll', $this->data);
    }

    public function ajax_data(Request $request)
    {

        if(request()->ajax()) {

        $limit = $request->input('length');
        $start = $request->input('start');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  DataClosingPayroll::offset($start)
                ->limit($limit)
                ->orderBy('updated_at','desc')
                ->get();

            $totalData = DataClosingPayroll::count();
            $totalFiltered = $totalData;  

        } else {
            $search = $request->input('search.value');

            $query =  DataClosingPayroll::where('nama_closing','LIKE',"%{$search}%")
                ->orWhere('periode_payroll','LIKE',"%{$search}%")
                ->orWhere('catatan','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy('updated_at','desc')
                ->get();

            $totalData = DataClosingPayroll::where('nama_closing','LIKE',"%{$search}%")
                ->orWhere('periode_payroll','LIKE',"%{$search}%")
                ->orWhere('catatan','LIKE',"%{$search}%")
                ->count();
            $totalFiltered = $totalData;                            

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['kode_closing'] = $q->kode_closing;
                $nestedData['nama_closing'] = $q->nama_closing;

                $nestedData['periode_payroll'] = $q->periode_payroll;

                $daterange1 = explode(" s/d ", $q->periode_payroll);
                $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
                $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
        
                setlocale(LC_ALL, 'id-ID', 'id_ID');
                $datePeriode = strtoupper(strftime("%d %b %Y", strtotime($tanggalMulai)) . ' s/d ' . strftime("%d %b %Y", strtotime($tanggalSampai)));

                $nestedData['periode_payroll_format'] = $datePeriode;
                if($q->istemp) {
                    $istemp = '<div class="badge badge-primary badge-md">TRUE</div>';
                } else {
                    $istemp = '<div class="badge badge-danger badge-md">FALSE</div>';
                }
                $nestedData['istemp_format'] = $istemp;
                $nestedData['istemp'] = $q->istemp;
                if($q->ispermanent) {
                    $ispermanent = '<div class="badge badge-primary badge-md">TRUE</div>';
                } else {
                    $ispermanent = '<div class="badge badge-danger badge-md">FALSE</div>';
                }
                $nestedData['ispermanent_format'] = $ispermanent;
                $nestedData['ispermanent'] = $q->ispermanent;
                $nestedData['start_periode'] = $q->start_periode;
                $nestedData['end_periode'] = $q->end_periode;
                $nestedData['catatan'] = $q->catatan;
                $nestedData['operator'] = $q->operator;
                $nestedData['created_at'] = substr($q->created_at, 0, 10) . " " . substr($q->created_at, 11, 5);
                $nestedData['updated_at'] = substr($q->updated_at, 0, 10) . " " . substr($q->updated_at, 11, 5);
        
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

    public function ajax_getclosing(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $operator = $loggedAdmin->email;

        $tanggal = $request->tanggal;
     
        $query = DataClosingPayroll::where('istemp', '=', 1)->get();

        $json_data = array(
            "status" => "info",
            "message" => "<b>INFO:</b> CEK CLOSING SELESAI."
        );

        foreach($query as $value) {
            $query1 = DataClosingPayroll::whereRaw('
                            "' . $tanggal . '" BETWEEN "' . $value->start_periode . '" AND "' . $value->end_periode . '" 
                        ')
                        ->count();

            setlocale(LC_ALL, 'id-ID', 'id_ID');
            $datePeriode = explode(" s/d ", $value->periode_payroll);
            $infoPeriodePayroll = strtoupper(strftime("%A, %d %b %Y", strtotime($datePeriode[0])) . ' s/d ' . strftime("%A, %d %b %Y", strtotime($datePeriode[1])));

            if($query1) {
                $json_data = array(
                    "status" => "error",
                    "message" => "<b>ERROR :</b> DATA PERIODE " . $infoPeriodePayroll . " <b>SUDAH CLOSING SEMENTARA</b>.",
                    "ada" => true
                );
                break;
            } else {
                $json_data = array(
                    "status" => "info",
                    "message" => "<b>INFO:</b> CEK CLOSING SELESAI.",
                    "ada" => false
                );    
            }                
        }

        return json_encode($json_data);
    }

    public function create(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $operator = $loggedAdmin->email;

        $nama_closing = $request->nama_closing;
        $periode_payroll = $request->periode_payroll;
        $istemp = $request->istemp;
        $ispermanent = $request->ispermanent;
        $start_periode = $request->start_periode;
        $end_periode = $request->end_periode;
        $catatan = $request->catatan;

        $replacekode1 = str_replace(' s/d ', '', $periode_payroll);
        $replacekode2 = str_replace('-', '', $replacekode1);
        
        $kode_closing = $replacekode2;

        $findDT = DataClosingPayroll::where('kode_closing','=', $kode_closing)->count();
        $query = "";
        if($findDT > 0) {
            return false;
        } else {
             $query = DataClosingPayroll::create([
                'kode_closing' => $kode_closing,
                'nama_closing' => $nama_closing,
                'periode_payroll' => $periode_payroll,
                'istemp' => $istemp,
                'ispermanent' => $ispermanent,
                'start_periode' => $start_periode,
                'end_periode' => $end_periode,
                'catatan' => $catatan,
                'operator' => $operator
            ]);
        }

        return $query;
    }

    public function update(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $operator = $loggedAdmin->email;

        $kode_closing_lama = $request->kode_closing;

        $periode_payroll = $request->periode_payroll;
        $replacekode1 = str_replace(' s/d ', '', $periode_payroll);
        $replacekode2 = str_replace('-', '', $replacekode1);
        
        $kode_closing = $replacekode2;

        $nama_closing = $request->nama_closing;
        $istemp = $request->istemp;
        $ispermanent = $request->ispermanent;
        $start_periode = $request->start_periode;
        $end_periode = $request->end_periode;
        $catatan = $request->catatan;

        $findDT = DataClosingPayroll::where('kode_closing','=', $kode_closing_lama)->count();

        if($findDT > 0) {
            $query = DataClosingPayroll::where('kode_closing','=', $kode_closing_lama)
            ->update([
                'kode_closing' => $kode_closing,
                'nama_closing' => $nama_closing,
                'periode_payroll' => $periode_payroll,
                'istemp' => $istemp,
                'ispermanent' => $ispermanent,
                'start_periode' => $start_periode,
                'end_periode' => $end_periode,
                'catatan' => $catatan,
                'operator' => $operator
            ]);
        } else {
            return false;
        }

        return $query;
    }

    public function destroy(Request $request)
    {
        $kode_closing = $request->kode_closing;

        $findDT = DataClosingPayroll::where('kode_closing','=', $kode_closing)->count();

        if($findDT > 0) {
            $query = DataClosingPayroll::where('kode_closing','=',$kode_closing)->delete();
        } else {
            $query = false;
        }

        return $query;
    }

}
