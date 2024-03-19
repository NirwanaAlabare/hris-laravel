<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\BpjsSetting;
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
 * Class BpjsSettingController
 * @package App\Http\Controllers\Hris
 */
class BpjsSettingController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'BPJS Setting';
    }

    public function index()
    {
        return View::make('hris/bpjssetting', $this->data);
    }

    public function ajax_data(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'kode_periode_bpjs',
            1 => 'nama_dasar_pot_bpjs',
            2 => 'dasar_pot_bpjs_rupiah',
            3 => 'bpjs_tk_jkm_persen',
            4 => 'bpjs_tk_jkk_persen',
            5 => 'bpjs_tk_jht_persen',
            6 => 'bpjs_tk_jpn_persen',
            7 => 'bpjs_ks_jkn_persen',
            8 => 'operator',
            9 => 'updated_at'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  BpjsSetting::join('dasar_pot_bpjs', 'bpjs_setting.kode_dasar_pot_bpjs', '=', 'dasar_pot_bpjs.kode_dasar_pot_bpjs')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = BpjsSetting::join('dasar_pot_bpjs', 'bpjs_setting.kode_dasar_pot_bpjs', '=', 'dasar_pot_bpjs.kode_dasar_pot_bpjs')
                ->count();
            $totalFiltered = $totalData;  

        } else {
            $search = $request->input('search.value');

            $query =  BpjsSetting::where('kode_periode_bpjs','LIKE',"%{$search}%")
                ->orWhere('kode_dasar_pot_bpjs','LIKE',"%{$search}%")
                ->orWhere('nama_dasar_pot_bpjs','LIKE',"%{$search}%")
                ->join('dasar_pot_bpjs', 'bpjs_setting.kode_dasar_pot_bpjs', '=', 'dasar_pot_bpjs.kode_dasar_pot_bpjs')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = BpjsSetting::where('kode_periode_bpjs','LIKE',"%{$search}%")
                ->orWhere('kode_dasar_pot_bpjs','LIKE',"%{$search}%")
                ->orWhere('dasar_pot_bpjs','LIKE',"%{$search}%")
                ->join('dasar_pot_bpjs', 'bpjs_setting.kode_dasar_pot_bpjs', '=', 'dasar_pot_bpjs.kode_dasar_pot_bpjs')
                ->count();
            $totalFiltered = $totalData;                            

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['kode_periode_bpjs'] = $q->kode_periode_bpjs;
                $nestedData['kode_dasar_pot_bpjs'] = $q->kode_dasar_pot_bpjs;
                $nestedData['nama_dasar_pot_bpjs'] = $q->nama_dasar_pot_bpjs;
                $nestedData['dasar_pot_bpjs_rupiah'] = $q->dasar_pot_bpjs_rupiah;
                $nestedData['bpjs_tk_jkm_persen'] = $q->bpjs_tk_jkm_persen;
                $nestedData['bpjs_tk_jkm_perusahaan_persen'] = $q->bpjs_tk_jkm_perusahaan_persen;
                $nestedData['bpjs_tk_jkm_karyawan_persen'] = $q->bpjs_tk_jkm_karyawan_persen;
                $nestedData['bpjs_tk_jkk_persen'] = $q->bpjs_tk_jkk_persen;
                $nestedData['bpjs_tk_jkk_perusahaan_persen'] = $q->bpjs_tk_jkk_perusahaan_persen;
                $nestedData['bpjs_tk_jkk_karyawan_persen'] = $q->bpjs_tk_jkk_karyawan_persen;
                $nestedData['bpjs_tk_jht_persen'] = $q->bpjs_tk_jht_persen;
                $nestedData['bpjs_tk_jht_perusahaan_persen'] = $q->bpjs_tk_jht_perusahaan_persen;
                $nestedData['bpjs_tk_jht_karyawan_persen'] = $q->bpjs_tk_jht_karyawan_persen;
                $nestedData['bpjs_tk_jpn_persen'] = $q->bpjs_tk_jpn_persen;
                $nestedData['bpjs_tk_jpn_perusahaan_persen'] = $q->bpjs_tk_jpn_perusahaan_persen;
                $nestedData['bpjs_tk_jpn_karyawan_persen'] = $q->bpjs_tk_jpn_karyawan_persen;
                $nestedData['bpjs_ks_jkn_persen'] = $q->bpjs_ks_jkn_persen;
                $nestedData['bpjs_ks_jkn_perusahaan_persen'] = $q->bpjs_ks_jkn_perusahaan_persen;
                $nestedData['bpjs_ks_jkn_karyawan_persen'] = $q->bpjs_ks_jkn_karyawan_persen;
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
        $kode_periode_bpjs = strtoupper($request->kode_periode_bpjs);
        $kode_dasar_pot_bpjs = strtoupper($request->kode_dasar_pot_bpjs);
        $nama_dasar_pot_bpjs = strtoupper($request->nama_dasar_pot_bpjs);
        $dasar_pot_bpjs_rupiah = $request->dasar_pot_bpjs_rupiah;
        $bpjs_tk_jkm_persen = $request->bpjs_tk_jkm_persen;
        $bpjs_tk_jkm_perusahaan_persen = $request->bpjs_tk_jkm_perusahaan_persen;
        $bpjs_tk_jkm_karyawan_persen = $request->bpjs_tk_jkm_karyawan_persen;
        $bpjs_tk_jkk_persen = $request->bpjs_tk_jkk_persen;
        $bpjs_tk_jkk_perusahaan_persen = $request->bpjs_tk_jkk_perusahaan_persen;
        $bpjs_tk_jkk_karyawan_persen = $request->bpjs_tk_jkk_karyawan_persen;
        $bpjs_tk_jht_persen = $request->bpjs_tk_jht_persen;
        $bpjs_tk_jht_perusahaan_persen = $request->bpjs_tk_jht_perusahaan_persen;
        $bpjs_tk_jht_karyawan_persen = $request->bpjs_tk_jht_karyawan_persen;
        $bpjs_tk_jpn_persen = $request->bpjs_tk_jpn_persen;
        $bpjs_tk_jpn_perusahaan_persen = $request->bpjs_tk_jpn_perusahaan_persen;
        $bpjs_tk_jpn_karyawan_persen = $request->bpjs_tk_jpn_karyawan_persen;
        $bpjs_ks_jkn_persen = $request->bpjs_ks_jkn_persen;
        $bpjs_ks_jkn_perusahaan_persen = $request->bpjs_ks_jkn_perusahaan_persen;
        $bpjs_ks_jkn_karyawan_persen = $request->bpjs_ks_jkn_karyawan_persen;

        $loggedAdmin = Auth::guard('admin')->user();
        $operator = $loggedAdmin->email;

        $findDT = BpjsSetting::where('kode_periode_bpjs','=', $kode_periode_bpjs)->count();

        if($findDT > 0) {
            $query = BpjsSetting::where('kode_periode_bpjs','=', $kode_periode_bpjs)
            ->update([
                'kode_periode_bpjs' => $kode_periode_bpjs,
                'kode_dasar_pot_bpjs' => $kode_dasar_pot_bpjs,
                'nama_dasar_pot_bpjs' => $nama_dasar_pot_bpjs,
                'dasar_pot_bpjs_rupiah' => $dasar_pot_bpjs_rupiah,
                'bpjs_tk_jkm_persen' => $bpjs_tk_jkm_persen,
                'bpjs_tk_jkm_perusahaan_persen' => $bpjs_tk_jkm_perusahaan_persen,
                'bpjs_tk_jkm_karyawan_persen' => $bpjs_tk_jkm_karyawan_persen,
                'bpjs_tk_jkk_persen' => $bpjs_tk_jkk_persen,
                'bpjs_tk_jkk_perusahaan_persen' => $bpjs_tk_jkk_perusahaan_persen,
                'bpjs_tk_jkk_karyawan_persen' => $bpjs_tk_jkk_karyawan_persen,
                'bpjs_tk_jht_persen' => $bpjs_tk_jht_persen,
                'bpjs_tk_jht_perusahaan_persen' => $bpjs_tk_jht_perusahaan_persen,
                'bpjs_tk_jht_karyawan_persen' => $bpjs_tk_jht_karyawan_persen,
                'bpjs_tk_jpn_persen' => $bpjs_tk_jpn_persen,
                'bpjs_tk_jpn_perusahaan_persen' => $bpjs_tk_jpn_perusahaan_persen,
                'bpjs_tk_jpn_karyawan_persen' => $bpjs_tk_jpn_karyawan_persen,
                'bpjs_ks_jkn_persen' => $bpjs_ks_jkn_persen,
                'bpjs_ks_jkn_perusahaan_persen' => $bpjs_ks_jkn_perusahaan_persen,
                'bpjs_ks_jkn_karyawan_persen' => $bpjs_ks_jkn_karyawan_persen,
                'operator' => $operator
            ]);
        } else {
            $query = BpjsSetting::create([
                'kode_periode_bpjs' => $kode_periode_bpjs,
                'kode_dasar_pot_bpjs' => $kode_dasar_pot_bpjs,
                'nama_dasar_pot_bpjs' => $nama_dasar_pot_bpjs,
                'dasar_pot_bpjs_rupiah' => $dasar_pot_bpjs_rupiah,
                'bpjs_tk_jkm_persen' => $bpjs_tk_jkm_persen,
                'bpjs_tk_jkm_perusahaan_persen' => $bpjs_tk_jkm_perusahaan_persen,
                'bpjs_tk_jkm_karyawan_persen' => $bpjs_tk_jkm_karyawan_persen,
                'bpjs_tk_jkk_persen' => $bpjs_tk_jkk_persen,
                'bpjs_tk_jkk_perusahaan_persen' => $bpjs_tk_jkk_perusahaan_persen,
                'bpjs_tk_jkk_karyawan_persen' => $bpjs_tk_jkk_karyawan_persen,
                'bpjs_tk_jht_persen' => $bpjs_tk_jht_persen,
                'bpjs_tk_jht_perusahaan_persen' => $bpjs_tk_jht_perusahaan_persen,
                'bpjs_tk_jht_karyawan_persen' => $bpjs_tk_jht_karyawan_persen,
                'bpjs_tk_jpn_persen' => $bpjs_tk_jpn_persen,
                'bpjs_tk_jpn_perusahaan_persen' => $bpjs_tk_jpn_perusahaan_persen,
                'bpjs_tk_jpn_karyawan_persen' => $bpjs_tk_jpn_karyawan_persen,
                'bpjs_ks_jkn_persen' => $bpjs_ks_jkn_persen,
                'bpjs_ks_jkn_perusahaan_persen' => $bpjs_ks_jkn_perusahaan_persen,
                'bpjs_ks_jkn_karyawan_persen' => $bpjs_ks_jkn_karyawan_persen,
                'operator' => $operator
            ]);
        }

        return $query;
    }

    public function destroy(Request $request)
    {
        $kode_periode_bpjs = $request->kode_periode_bpjs;

        $findDT = BpjsSetting::where('kode_periode_bpjs','=', $kode_periode_bpjs)->count();

        if($findDT > 0) {
            $query = BpjsSetting::where('kode_periode_bpjs','=',$kode_periode_bpjs)->delete();
        } else {
            $query = false;
        }

        return $query;
    }

}
