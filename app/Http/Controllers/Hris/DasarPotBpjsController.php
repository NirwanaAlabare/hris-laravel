<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\DasarPotBpjs;
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
 * Class DasarPotBpjsController
 * @package App\Http\Controllers\Hris
 */
class DasarPotBpjsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dasar Pot BPJS';
    }

    public function index()
    {
        return View::make('hris/dasarpotbpjs', $this->data);
    }

    public function ajax_data(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'kode_dasar_pot_bpjs',
            1 => 'nama_dasar_pot_bpjs',
            2 => 'dasar_pot_bpjs_rupiah',
            3 => 'updated_at'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  DasarPotBpjs::offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalData = DasarPotBpjs::count();
            $totalFiltered = $totalData;  

        } else {
            $search = $request->input('search.value');

            $query =  DasarPotBpjs::where('kode_dasar_pot_bpjs','LIKE',"%{$search}%")
                            ->orWhere('nama_dasar_pot_bpjs','LIKE',"%{$search}%")
                            ->orWhere('dasar_pot_bpjs_rupiah','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalData = DasarPotBpjs::where('kode_dasar_pot_bpjs','LIKE',"%{$search}%")
                            ->orWhere('nama_dasar_pot_bpjs','LIKE',"%{$search}%")
                            ->orWhere('dasar_pot_bpjs_rupiah','LIKE',"%{$search}%")
                            ->count();
            $totalFiltered = $totalData;                            

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['kode_dasar_pot_bpjs'] = $q->kode_dasar_pot_bpjs;
                $nestedData['nama_dasar_pot_bpjs'] = $q->nama_dasar_pot_bpjs;
                $nestedData['dasar_pot_bpjs_rupiah'] = $q->dasar_pot_bpjs_rupiah;
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

    public function replace(Request $request)
    {
        $kode_dasar_pot_bpjs = strtoupper($request->kode_dasar_pot_bpjs);
        $nama_dasar_pot_bpjs = strtoupper($request->nama_dasar_pot_bpjs);
        $dasar_pot_bpjs_rupiah = $request->dasar_pot_bpjs_rupiah;

        $loggedAdmin = Auth::guard('admin')->user();
        $operator = $loggedAdmin->email;

        $findDT = DasarPotBpjs::where('kode_dasar_pot_bpjs','=', $kode_dasar_pot_bpjs)->count();

        if($findDT > 0) {
            $query = DasarPotBpjs::where('kode_dasar_pot_bpjs','=', $kode_dasar_pot_bpjs)
            ->update([
                'kode_dasar_pot_bpjs' => $kode_dasar_pot_bpjs,
                'nama_dasar_pot_bpjs' => $nama_dasar_pot_bpjs,
                'dasar_pot_bpjs_rupiah' => $dasar_pot_bpjs_rupiah,
                'operator' => $operator
            ]);
        } else {
            $query = DasarPotBpjs::create([
                'kode_dasar_pot_bpjs' => $kode_dasar_pot_bpjs,
                'nama_dasar_pot_bpjs' => $nama_dasar_pot_bpjs,
                'dasar_pot_bpjs_rupiah' => $dasar_pot_bpjs_rupiah,
                'operator' => $operator
            ]);
        }

        return $query;
    }

    public function destroy(Request $request)
    {
        $kode_dasar_pot_bpjs = $request->kode_dasar_pot_bpjs;

        $findDT = DasarPotBpjs::where('kode_dasar_pot_bpjs','=', $kode_dasar_pot_bpjs)->count();

        if($findDT > 0) {
            $query = DasarPotBpjs::where('kode_dasar_pot_bpjs','=',$kode_dasar_pot_bpjs)->delete();
        } else {
            $query = false;
        }

        return $query;
    }

}
