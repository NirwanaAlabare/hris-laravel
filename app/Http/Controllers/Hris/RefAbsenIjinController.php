<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\RefAbsenIjin;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Datatables;
use App\Exports\MasterDataAbsenKehadiranExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class RefAbsenIjinController
 * @package App\Http\Controllers\Hris
 */
class RefAbsenIjinController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Referensi Absen Perijinan';
    }

    public function index()
    {
        return View::make('hris/refabsenijin', $this->data);
    }

    public function ajax_refabsenijin(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'kode_absen_ijin',
            1 => 'nama_absen_ijin',
            2 => 'nama_ijin_payroll',
            3 => 'kode_ijin_payroll'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  RefAbsenIjin::offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalData = RefAbsenIjin::count();
            $totalFiltered = $totalData;  

        } else {
            $search = $request->input('search.value');

            $query =  RefAbsenIjin::where('kode_absen_ijin','LIKE',"%{$search}%")
                            ->orWhere('nama_absen_ijin','LIKE',"%{$search}%")
                            ->orWhere('nama_ijin_payroll','LIKE',"%{$search}%")
                            ->orWhere('kode_ijin_payroll','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalData = RefAbsenIjin::where('kode_absen_ijin','LIKE',"%{$search}%")
                            ->orWhere('nama_absen_ijin','LIKE',"%{$search}%")
                            ->orWhere('nama_ijin_payroll','LIKE',"%{$search}%")
                            ->orWhere('kode_ijin_payroll','LIKE',"%{$search}%")
                            ->count();
            $totalFiltered = $totalData;                            

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['kode_absen_ijin'] = $q->kode_absen_ijin;
                $nestedData['nama_absen_ijin'] = $q->nama_absen_ijin;
                $nestedData['nama_ijin_payroll'] = $q->nama_ijin_payroll;
                $nestedData['kode_ijin_payroll'] = $q->kode_ijin_payroll;
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

    public function replace(Request $request)
    {
        $kode_absen_ijin = $request->kode_absen_ijin;
        $nama_absen_ijin = $request->nama_absen_ijin;
        $nama_ijin_payroll = $request->nama_ijin_payroll;
        $kode_ijin_payroll = $request->kode_ijin_payroll;

        $findDT = RefAbsenIjin::where('kode_absen_ijin','=', $kode_absen_ijin)->count();

        if($findDT > 0) {
            $query = RefAbsenIjin::where('kode_absen_ijin','=', $kode_absen_ijin)
            ->update([
                'kode_absen_ijin' => $kode_absen_ijin,
                'nama_absen_ijin' => $nama_absen_ijin,
                'nama_ijin_payroll' => $nama_ijin_payroll,
                'kode_ijin_payroll' => $kode_ijin_payroll
            ]);
        } else {
            $query = RefAbsenIjin::create([
                'kode_absen_ijin' => $kode_absen_ijin,
                'nama_absen_ijin' => $nama_absen_ijin,
                'nama_ijin_payroll' => $nama_ijin_payroll,
                'kode_ijin_payroll' => $kode_ijin_payroll
            ]);
        }

        return $query;
    }

    public function destroy(Request $request)
    {
        $kode_absen_ijin = $request->kode_absen_ijin;

        $findDT = RefAbsenIjin::where('kode_absen_ijin','=', $kode_absen_ijin)->count();

        if($findDT > 0) {
            $query = RefAbsenIjin::where('kode_absen_ijin','=',$kode_absen_ijin)->delete();
        } else {
            $query = false;
        }

        return $query;
    }

}
