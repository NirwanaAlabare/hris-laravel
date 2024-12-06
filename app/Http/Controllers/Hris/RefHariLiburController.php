<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\RefHariLibur;
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
use App\Models\MasterDataAbsenKehadiran;
use App\Models\RefAbsenIjin;
use App\Models\DataAbsenPerijinan;



/**
 * Class RefHariLiburController
 * @package App\Http\Controllers\Hris
 */
class RefHariLiburController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Referensi Hari Libur';
    }

    public function index()
    {
        $this->RefAbsenIjin= RefAbsenIjin::all();
        return View::make('hris/refharilibur', $this->data);
    }

    public function ajax_refharilibur(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'id',
            1 => 'nama_hari_libur',
            2 => 'tanggal_libur',
            3 => 'status_absen'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  RefHariLibur::offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalData = RefHariLibur::count();
            $totalFiltered = $totalData;  

        } else {
            $search = $request->input('search.value');

            $query =  RefHariLibur::where('id','LIKE',"%{$search}%")
                            ->orWhere('nama_hari_libur','LIKE',"%{$search}%")
                            ->orWhere('tanggal_libur','LIKE',"%{$search}%")
                            ->orWhere('status_absen','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalData = RefHariLibur::where('id','LIKE',"%{$search}%")
                            ->orWhere('nama_hari_libur','LIKE',"%{$search}%")
                            ->orWhere('tanggal_libur','LIKE',"%{$search}%")
                            ->orWhere('status_absen','LIKE',"%{$search}%")
                            ->count();
            $totalFiltered = $totalData;                            

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['id'] = $q->id;
                $nestedData['nama_hari_libur'] = $q->nama_hari_libur;
                $nestedData['tanggal_libur'] = $q->tanggal_libur!=null?date('d-m-Y', strtotime($q->tanggal_libur)):$q->tanggal_libur;
                $nestedData['status_absen'] = $q->status_absen;
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
        $id = $request->id;
        $nama_hari_libur = $request->nama_hari_libur;
        $tanggal_libur =date('Y-m-d', strtotime($request->tanggal_libur));
        $status_absen = $request->status_absen;

        $findDT = RefHariLibur::where('id','=', $id)->count();
        $update_libur=[
            'status_absen'=>$status_absen
        ];
        $jadwal_kerjaLN=[
            'mulai_jam_kerja'=>null,
            'akhir_jam_kerja'=>null,
        ];
        $update_LP=[
            'status_absen'=>null,
        ];
        if($status_absen=='LP'){
            MasterDataAbsenKehadiran::where('tanggal_berjalan',$tanggal_libur)
            ->whereNotIn('status_absen',['DL','KM','R'])
            ->where('absen_masuk_kerja',null)
            ->where('absen_pulang_kerja',null)
            ->update($update_libur);

            MasterDataAbsenKehadiran::where('tanggal_berjalan',$tanggal_libur)
            ->whereNotIn('status_absen',['DL','KM','R'])
            ->where('absen_masuk_kerja','!=',null)
            ->where('absen_pulang_kerja',null)
            ->update($update_libur);

            MasterDataAbsenKehadiran::where('tanggal_berjalan',$tanggal_libur)
            ->whereNotIn('status_absen',['DL','KM','R'])
            ->where('absen_masuk_kerja',null)
            ->where('absen_pulang_kerja','!=',null)
            ->update($update_libur);

            MasterDataAbsenKehadiran::where('tanggal_berjalan',$tanggal_libur)
            ->where('absen_masuk_kerja','!=',null)
            ->where('absen_pulang_kerja','!=',null)
            ->update($update_LP);
        }
        elseif ($status_absen=='LN'){
            MasterDataAbsenKehadiran::where('tanggal_berjalan',$tanggal_libur)
            ->where(function ($query) {
                $query ->whereNotIn('status_absen',['R'])
                        ->orWhere('status_absen',null);
            })
        //     // ->where('absen_masuk_kerja',null)
        //     // ->where('absen_pulang_kerja',null)
            ->update($update_libur);

            MasterDataAbsenKehadiran::where('tanggal_berjalan',$tanggal_libur)->update($jadwal_kerjaLN);
        }

        elseif ($status_absen=='L'){
            MasterDataAbsenKehadiran::where('tanggal_berjalan',$tanggal_libur)
            ->where('status_absen','M')
            ->update($update_libur);
        }

        if($findDT > 0) {
            $query = RefHariLibur::where('id','=', $id)
            ->update([
                'nama_hari_libur' => $nama_hari_libur,
                'tanggal_libur' => $tanggal_libur,
                'status_absen' => $status_absen
            ]);
        } else {
            $query = RefHariLibur::create([
                'nama_hari_libur' => $nama_hari_libur,
                'tanggal_libur' => $tanggal_libur,
                'status_absen' => $status_absen
            ]);
        }

        return $query;
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        $findDT = RefHariLibur::where('id','=', $id)->first();

        if($findDT) {
            $update_libur=[
                'status_absen'=>null
            ];
            MasterDataAbsenKehadiran::where('tanggal_berjalan',$findDT->tanggal_libur)
                ->where('status_absen',$findDT->status_absen)
                ->where('absen_masuk_kerja','!=',null)
                ->where('absen_pulang_kerja','!=',null)
                // ->where('nomor_absen_ijin',null)
                ->update($update_libur);


            $absen_izin=MasterDataAbsenKehadiran::where('tanggal_berjalan',$findDT->tanggal_libur)
                ->where('status_absen',$findDT->status_absen)
                ->where('absen_masuk_kerja',null)
                ->where('absen_pulang_kerja',null)
                // ->where('nomor_absen_ijin','!=',null)
                ->get();

                foreach ($absen_izin as $key => $value) {
                    $izin=DataAbsenPerijinan::where('nomor_form_perizinan',$value->nomor_absen_ijin)->where('enroll_id',$value->enroll_id)->first();
                    $update_izin=[
                        'status_absen'=>$izin->kode_absen_ijin??'M'
                    ];
                    MasterDataAbsenKehadiran::where('uuid',$value->uuid)->update($update_izin);
                }
            $query = RefHariLibur::where('id','=',$id)->delete();
        } else {
            $query = false;
        }

        return $query;
    }

}
