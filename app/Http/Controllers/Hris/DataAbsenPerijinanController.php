<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\RefAbsenIjin;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\DataAbsenPerijinan;
use App\Models\EmployeeAtribut;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Datatables;
use App\Exports\DataAbsenPerijinanExport;
use App\Exports\DataAbsenPerijinanIKSExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class DataAbsenPerijinanController
 * @package App\Http\Controllers\Hris
 */
class DataAbsenPerijinanController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Data Absen Perizinan';
    }

    public function index()
    {
        $this->refabsenijin = $this->ajax_getselectrefabsenijin();
        return View::make('hris/dataabsenperizinan', $this->data);
    }

    private function ajax_getselectrefabsenijin()
    {
        $query =  RefAbsenIjin::selectRaw('kode_absen_ijin,
                                           concat(kode_absen_ijin," - "
                                                  ,nama_absen_ijin) kode_nama_absen_ijin')
                                 ->orderby('nama_absen_ijin', 'asc')
                                 ->get();

        return $query;

    }

    public function ajax_dataabsenperizinan(Request $request)
    {
        if(request()->ajax()) {

            $columns = array(
                0 => 'uuid',
                1 => 'uuid_master',
                2 => 'tanggal_perizinan',
                3 => 'nomor_form_perizinan',
                4 => 'enroll_id',
                5 => 'nik',
                6 => 'employee_name',
                7 => 'created_at'
            );

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $totalData = 0;
            $totalFiltered = 0;

            if(empty($request->input('search.value')))
            {
                $query =  DataAbsenPerijinan::
                                    selectRaw('employee_atribut.employee_name,employee_atribut.nik, data_absen_perijinan.*')
                                    ->where('data_absen_perijinan.is_verifikasi_pengajuan_admin', '=', 1)
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy($order,$dir)
                                    ->leftJoin('employee_atribut','data_absen_perijinan.enroll_id','=','employee_atribut.enroll_id')
                                    ->get();

                $totalData = DataAbsenPerijinan::count();
                $totalFiltered = $totalData;

            } else {
                $search = $request->input('search.value');
                $query = DataAbsenPerijinan::
                selectRaw('employee_atribut.employee_name, data_absen_perijinan.*')
                ->leftJoin('employee_atribut', 'data_absen_perijinan.enroll_id', '=', 'employee_atribut.enroll_id')
                ->where('data_absen_perijinan.uuid_master', 'LIKE', "%{$search}%")
                ->where('data_absen_perijinan.is_verifikasi_pengajuan_admin', '=', 1)
                ->orWhere('data_absen_perijinan.tanggal_perizinan', 'LIKE', "%{$search}%")
                ->orWhere('data_absen_perijinan.nomor_form_perizinan', 'LIKE', "%{$search}%")
                ->orWhere('data_absen_perijinan.enroll_id', 'LIKE', "%{$search}%")
                ->orWhere('employee_atribut.nik', 'LIKE', "%{$search}%")
                ->orWhere('employee_atribut.employee_name', 'LIKE', "%{$search}%")
                ->orWhere('data_absen_perijinan.kode_absen_ijin', 'LIKE', "%{$search}%")
                ->orWhere('data_absen_perijinan.absen_alasan', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalData = DataAbsenPerijinan::
                leftJoin('employee_atribut', 'data_absen_perijinan.enroll_id', '=', 'employee_atribut.enroll_id')
                ->where('data_absen_perijinan.uuid_master', 'LIKE', "%{$search}%")
                ->where('data_absen_perijinan.is_verifikasi_pengajuan_admin', '=', 1)
                ->orWhere('data_absen_perijinan.tanggal_perizinan', 'LIKE', "%{$search}%")
                ->orWhere('data_absen_perijinan.nomor_form_perizinan', 'LIKE', "%{$search}%")
                ->orWhere('data_absen_perijinan.enroll_id', 'LIKE', "%{$search}%")
                ->orWhere('employee_atribut.nik', 'LIKE', "%{$search}%")
                ->orWhere('employee_atribut.employee_name', 'LIKE', "%{$search}%")
                ->orWhere('data_absen_perijinan.kode_absen_ijin', 'LIKE', "%{$search}%")
                ->orWhere('data_absen_perijinan.absen_alasan', 'LIKE', "%{$search}%")
                ->count();
                $totalFiltered = $totalData;

            }

            $data = array();
            if(!empty($query))
            {
                foreach ($query as $q)
                {

                    $nestedData['uuid'] = $q->uuid;
                    $nestedData['uuid_master'] = $q->uuid_master;
                    $nestedData['tanggal_perizinan'] = $q->tanggal_perizinan;
                    $nestedData['nomor_form_perizinan'] = $q->nomor_form_perizinan;
                    $nestedData['enroll_id'] = $q->enroll_id;
                    $nestedData['nik'] = $q->nik;
                    $nestedData['employee_name'] = $q->employee_name;
                    $nestedData['kode_absen_ijin'] = $q->kode_absen_ijin;
                    $nestedData['absen_alasan'] = $q->absen_alasan;
                    $nestedData['tanggal_mulai_ijin'] = $q->tanggal_mulai_ijin;
                    $nestedData['tanggal_akhir_ijin'] = $q->tanggal_akhir_ijin;
                    $nestedData['time_mulai_ijin'] = substr($q->time_mulai_ijin, 0, 5);
                    $nestedData['time_akhir_ijin'] = substr($q->time_akhir_ijin, 0, 5);
                    $nestedData['total_time_ijin'] = $q->total_time_ijin;
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

    public function create_perizinan(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        info('START REPLACE IZIN');
        info('Tambah Permohonan Perizinan by ' . $email);

        $uuid_master = $request->uuid;
        $tanggal_perizinan=substr($request->tanggal_perizinan,6,4).'-'.substr($request->tanggal_perizinan,3,2).'-'.substr($request->tanggal_perizinan,0,2);
        $tanggal_mulai_ijin=substr($request->tanggal_mulai_ijin,6,4).'-'.substr($request->tanggal_mulai_ijin,3,2).'-'.substr($request->tanggal_mulai_ijin,0,2);
        $tanggal_akhir_ijin=substr($request->tanggal_akhir_ijin,6,4).'-'.substr($request->tanggal_akhir_ijin,3,2).'-'.substr($request->tanggal_akhir_ijin,0,2);
        $nomor_form_perizinan = $request->nomor_form_perizinan;
        $enroll_id = $request->enroll_id;
        $nik = $request->nik;
        $employee_name = $request->employee_name;
        $kode_absen_ijin = $request->kode_absen_ijin;
        $absen_alasan = $request->absen_alasan;
        $query = false;

        switch ($kode_absen_ijin) {
            case 'DL':
                $nomor_form_perizinan = 'FPI/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                break;
            case 'I':
                $nomor_form_perizinan = 'FPI/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                break;
            case 'S':
                $nomor_form_perizinan = 'FPI/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                break;
            default:
                if ($kode_absen_ijin <> 'M') {
                    $nomor_form_perizinan = 'FPC/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                } else {
                    $nomor_form_perizinan = 'TIDAK DI KENALI';
                }
                break;
        }


        if ($kode_absen_ijin=='LP') {
            $is_verifikasi=1;
            $verifikasi_by='system';
        }
        else{
            $is_verifikasi=0;
            $verifikasi_by=null;
        }
        $query=DB::select('select nomor_form_perizinan,CAST(substring(nomor_form_perizinan,13) AS int) as nomor_form from data_absen_perijinan where nomor_form_perizinan LIKE "'.$nomor_form_perizinan.'%" order by nomor_form desc limit 1');
        if(count($query)==0) {
            $nomor = "0000";
            $nomor_form_perizinan =  $nomor_form_perizinan . $nomor;
        } else {
            $nomor = $query[0]->nomor_form_perizinan;
            if(strlen($query[0]->nomor_form)<5){
                $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
            }else{
                $nomorform = str_pad(substr($nomor, -5) + 1,4,"0",STR_PAD_LEFT);
            }
            $nomor_form_perizinan =  $nomor_form_perizinan . $nomorform;
        }

        $query = DataAbsenPerijinan::create([
            'uuid' => Str::uuid(),
            'uuid_master' => $uuid_master,
            'tanggal_perizinan' => $tanggal_perizinan,
            'nomor_form_perizinan' => $nomor_form_perizinan,
            'enroll_id' => $enroll_id,
            'kode_absen_ijin' => $kode_absen_ijin,
            'absen_alasan' => $absen_alasan,
            'tanggal_mulai_ijin' => $tanggal_mulai_ijin,
            'tanggal_akhir_ijin' => $tanggal_akhir_ijin,
            'is_verifikasi'=>$is_verifikasi,
            'verifikasi_by'=>$verifikasi_by,
            'operator' => $email,
            'diajukan_oleh' => $email,
            'is_verifikasi_pengajuan_admin' => 0,
        ]);

        if ($query) {
            info('Insert data nomor [' . $nomor_form_perizinan . '] on table data_absen_perijinan is SUCCESS.');
        } else {
            info('Insert data nomor [' . $nomor_form_perizinan . '] on table data_absen_perijinan is FAILED.');
        }

        if($query) {

            // $query1 = MasterDataAbsenKehadiran::whereRaw('
            //     tanggal_berjalan between "' . $tanggal_mulai_ijin . '" and "' . $tanggal_akhir_ijin . '"
            //     and enroll_id = "' . $enroll_id . '"
            // ')
            if($kode_absen_ijin=='DL') {

                $query1 = MasterDataAbsenKehadiran::whereBetween('tanggal_berjalan', [$tanggal_mulai_ijin, $tanggal_akhir_ijin])
                ->where('enroll_id', $enroll_id)
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5]);
                })->where(function ($query){
                    $query->where('status_absen','!=','LN')
                    ->orWhere('status_absen',null);
                })->update([
                    'nomor_absen_ijin' => $nomor_form_perizinan,
                    'status_absen' => $kode_absen_ijin,
                    'operator' => $email,
                    'jumlah_menit_absen_dt'=>0,
                    'jumlah_menit_absen_pc'=>0,
                    'jumlah_menit_absen_dtpc'=>0
                ]);

                if($query1) {
                    info('Update on table master_data_absen_kehadiran after update Permohonan Perizinan [' . $nomor_form_perizinan . '] on table data_absen_perijinan is SUCCESS' );
                } else {
                    info('Update on table master_data_absen_kehadiran after update Permohonan Perizinan [' . $nomor_form_perizinan . '] on table data_absen_perijinan is FAILED' );
                }

            }else{
                if($kode_absen_ijin=='LN'){
                    $query1 = MasterDataAbsenKehadiran::whereBetween('tanggal_berjalan', [$tanggal_mulai_ijin, $tanggal_akhir_ijin])
                    ->where('enroll_id', $enroll_id)
                    ->update([
                        'nomor_absen_ijin' => $nomor_form_perizinan,
                        'status_absen' => $kode_absen_ijin,
                        'jumlah_menit_absen_dt'=>0,
                        'jumlah_menit_absen_pc'=>0,
                        'jumlah_menit_absen_dtpc'=>0,
                        'operator' => $email
                    ]);
                }else{
                    $query1 = MasterDataAbsenKehadiran::whereBetween('tanggal_berjalan', [$tanggal_mulai_ijin, $tanggal_akhir_ijin])
                    ->where('enroll_id', $enroll_id)
                    ->where(function ($query3) {
                        $query3->whereNotIn('kode_hari', [6, 5])
                            ->orWhereNotNull('mulai_jam_kerja');
                    })->where('status_absen','!=','LN')->update([
                        'nomor_absen_ijin' => $nomor_form_perizinan,
                        'status_absen' => $kode_absen_ijin,
                        'operator' => $email,
                        'absen_masuk_kerja'=>null,
                        'absen_pulang_kerja'=>null,
                        'jumlah_menit_absen_dt'=>0,
                        'jumlah_menit_absen_pc'=>0,
                        'jumlah_menit_absen_dtpc'=>0,
                    ]);
                }
            }

        }
        info('END REPLACE IZIN');

        return $query;
    }

    public function create_iks(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        info('START TAMBAH IKS');
        info('Tambah Permohonan IKS by ' . $email);

        $uuid_master = $request->uuid;
        $tanggal_perizinan=substr($request->tanggal_perizinan,6,4).'-'.substr($request->tanggal_perizinan,3,2).'-'.substr($request->tanggal_perizinan    ,0,2);
        $nomor_form_perizinan = $request->nomor_form_perizinan;
        $enroll_id = $request->enroll_id;
        $nik = $request->nik;
        $employee_name = $request->employee_name;
        $kode_absen_ijin = $request->kode_absen_ijin;
        $absen_alasan = $request->absen_alasan;
        $time_mulai_ijin = $request->time_mulai_ijin;
        $time_akhir_ijin = $request->time_akhir_ijin;
        $total_time_ijin = $request->total_time_ijin;
        $query = false;

        $nomor_form_perizinan = 'IKS/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';

        $getlastnomorform =  DataAbsenPerijinan::selectRaw('nomor_form_perizinan')
                                            ->whereRaw('nomor_form_perizinan like "' . $nomor_form_perizinan . '%"')
                                            ->groupby('nomor_form_perizinan')
                                            ->orderby('nomor_form_perizinan', 'desc')
                                            ->first();

        if ($getlastnomorform) {
            info('Get the latest nomor form permohonan IKS from database : ' . $getlastnomorform);
        } else {
            info('FAILED to Get the latest nomor form permohonan IKS from database');
        }

        if($getlastnomorform == "") {
            //info("Count : Kosong");
            $nomor = "0000";
        } else {
            $nomor = $getlastnomorform->nomor_form_perizinan;
        }

        $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
        $nomor_form_perizinan =  $nomor_form_perizinan . $nomorform;
        info('Nomor Form Perizinan : ' . $nomor_form_perizinan);

        $query = DataAbsenPerijinan::create([
            'uuid' => Str::uuid(),
            'uuid_master' => $uuid_master,
            'tanggal_perizinan' => $tanggal_perizinan,
            'nomor_form_perizinan' => $nomor_form_perizinan,
            'enroll_id' => $enroll_id,
            'kode_absen_ijin' => $kode_absen_ijin,
            'absen_alasan' => $absen_alasan,
            'time_mulai_ijin' => $time_mulai_ijin,
            'time_akhir_ijin' => $time_akhir_ijin,
            'total_time_ijin' => $total_time_ijin,
            'operator' => $email,
            'diajukan_oleh' => $email,
            'is_verifikasi_pengajuan_admin' => 1,
        ]);

        if ($query) {
            info('Insert data nomor [' . $nomor_form_perizinan . '] on table data_absen_perijinan is SUCCESS.');
        } else {
            info('Insert data nomor [' . $nomor_form_perizinan . '] on table data_absen_perijinan is FAILED.');
        }

        if($query) {

            $query = MasterDataAbsenKehadiran::whereRaw('
                tanggal_berjalan = "' . $tanggal_perizinan . '"
                and enroll_id = "' . $enroll_id . '"
            ')
            ->update([
                'nomor_absen_ijin' => $nomor_form_perizinan,
                'status_absen' => $kode_absen_ijin,
                'operator' => $email,
                'updated_absen_ijin' => now()
            ]);

            if($query) {
                info('Update on table master_data_absen_kehadiran after update Permohonan IKS [' . $nomor_form_perizinan . '] on table data_absen_perijinan is SUCCESS' );
            } else {
                info('Update on table master_data_absen_kehadiran after update Permohonan IKS [' . $nomor_form_perizinan . '] on table data_absen_perijinan is FAILED' );
            }

        }
        info('END REPLACE IKS');

        return $query;
    }

    public function update_perizinan_menu(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        info('START UPDATE IZIN');
        info('Update Permohonan Perizinan by ' . $email);
        info('Nomor Form Perizinan : ' . request()->nomor_form_perizinan);

        $allowedEmails = ['fadli', 'mega@ptnag.com', 'hrd', 'ersa@ptnag.com', 'rudy@ptnag.com','indri@nag.nirwanaindonesia.com'];

        if (in_array($email, $allowedEmails)) {
            $absen = DataAbsenPerijinan::where('uuid', request()->uuid)->first();
        } else {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk mengupdate data ini.'], 403);
        }

        if ($absen) {
            $query = DataAbsenPerijinan::where('uuid',request()->uuid)->update([
                'kode_absen_ijin' => request()->kode_absen_ijin,
                'absen_alasan' => request()->absen_alasan,
                'tanggal_mulai_ijin' => request()->tanggal_mulai_ijin,
                'tanggal_akhir_ijin' => request()->tanggal_akhir_ijin,
                'operator' => $email,
            ]);
            if(request()->kode_absen_ijin=='DL')
            {
                MasterDataAbsenKehadiran::whereBetween('tanggal_berjalan', [request()->tanggal_mulai_ijin, request()->tanggal_akhir_ijin])
                ->where('enroll_id', request()->enroll_id)
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5]);
                })->where(function ($query){
                    $query->where('status_absen','!=','LN')
                    ->orWhere('status_absen',null);
                })->update([
                    'nomor_absen_ijin' => request()->nomor_form_perizinan,
                    'status_absen' => request()->kode_absen_ijin,
                    'operator' => $email,
                    'jumlah_menit_absen_dt'=>0,
                    'jumlah_menit_absen_pc'=>0,
                    'jumlah_menit_absen_dtpc'=>0,
                    'updated_absen_ijin' => now()
                ]);
                MasterDataAbsenKehadiran::whereNotBetween('tanggal_berjalan', [request()->tanggal_mulai_ijin, request()->tanggal_akhir_ijin])
                ->where('enroll_id', request()->enroll_id)
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5]);
                })
                ->where('nomor_absen_ijin',request()->nomor_form_perizinan)->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja','!=',null)->update([
                    'nomor_absen_ijin'=>null,
                    'status_absen'=>null,
                    'operator'=>$email,
                ]);
                MasterDataAbsenKehadiran::whereNotBetween('tanggal_berjalan', [request()->tanggal_mulai_ijin, request()->tanggal_akhir_ijin])
                ->where('enroll_id', request()->enroll_id)
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5]);
                })
                ->where('nomor_absen_ijin',request()->nomor_form_perizinan)->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja',null)->update([
                    'nomor_absen_ijin'=>null,
                    'status_absen'=>'TL',
                    'operator'=>$email,
                ]);
                MasterDataAbsenKehadiran::whereNotBetween('tanggal_berjalan', [request()->tanggal_mulai_ijin, request()->tanggal_akhir_ijin])
                ->where('enroll_id', request()->enroll_id)
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5]);
                })
                ->where('nomor_absen_ijin',request()->nomor_form_perizinan)->where('absen_masuk_kerja',null)->where('absen_pulang_kerja','!=',null)->update([
                    'nomor_absen_ijin'=>null,
                    'status_absen'=>'TL',
                    'operator'=>$email,
                ]);
                MasterDataAbsenKehadiran::whereNotBetween('tanggal_berjalan', [request()->tanggal_mulai_ijin, request()->tanggal_akhir_ijin])
                ->where('enroll_id', request()->enroll_id)
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5]);
                })
                ->where('nomor_absen_ijin',request()->nomor_form_perizinan)->where('absen_masuk_kerja',null)->where('absen_pulang_kerja',null)->update([
                    'nomor_absen_ijin'=>null,
                    'status_absen'=>'M',
                    'operator'=>$email,
                ]);
            }else{
                MasterDataAbsenKehadiran::whereBetween('tanggal_berjalan', [request()->tanggal_mulai_ijin, request()->tanggal_akhir_ijin])
                ->where('enroll_id', request()->enroll_id)
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5])
                            ->orWhereNotNull('mulai_jam_kerja');
                })->where('status_absen','!=','LN')->update([
                    'nomor_absen_ijin' => request()->nomor_form_perizinan,
                    'status_absen' => request()->kode_absen_ijin,
                    'operator' => $email,
                    'absen_masuk_kerja'=>null,
                    'absen_pulang_kerja'=>null,
                    'jumlah_menit_absen_dt'=>0,
                    'jumlah_menit_absen_pc'=>0,
                    'jumlah_menit_absen_dtpc'=>0,
                    'updated_absen_ijin' => now()
                ]);
                MasterDataAbsenKehadiran::whereNotBetween('tanggal_berjalan', [request()->tanggal_mulai_ijin, request()->tanggal_akhir_ijin])
                ->where('enroll_id', request()->enroll_id)
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5])
                            ->orWhereNotNull('mulai_jam_kerja');
                })->where('status_absen','!=','LN')
                ->where('nomor_absen_ijin',request()->nomor_form_perizinan)->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja','!=',null)->update([
                    'nomor_absen_ijin'=>null,
                    'status_absen'=>null,
                    'operator'=>$email,
                ]);
                MasterDataAbsenKehadiran::whereNotBetween('tanggal_berjalan', [request()->tanggal_mulai_ijin, request()->tanggal_akhir_ijin])
                ->where('enroll_id', request()->enroll_id)
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5])
                            ->orWhereNotNull('mulai_jam_kerja');
                })->where('status_absen','!=','LN')
                ->where('nomor_absen_ijin',request()->nomor_form_perizinan)->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja',null)->update([
                    'nomor_absen_ijin'=>null,
                    'status_absen'=>'TL',
                    'operator'=>$email,
                ]);
                MasterDataAbsenKehadiran::whereNotBetween('tanggal_berjalan', [request()->tanggal_mulai_ijin, request()->tanggal_akhir_ijin])
                ->where('enroll_id', request()->enroll_id)
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5])
                            ->orWhereNotNull('mulai_jam_kerja');
                })->where('status_absen','!=','LN')
                ->where('nomor_absen_ijin',request()->nomor_form_perizinan)->where('absen_masuk_kerja',null)->where('absen_pulang_kerja','!=',null)->update([
                    'nomor_absen_ijin'=>null,
                    'status_absen'=>'TL',
                    'operator'=>$email,
                ]);
                MasterDataAbsenKehadiran::whereNotBetween('tanggal_berjalan', [request()->tanggal_mulai_ijin, request()->tanggal_akhir_ijin])
                ->where('enroll_id', request()->enroll_id)
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5])
                            ->orWhereNotNull('mulai_jam_kerja');
                })->where('status_absen','!=','LN')
                ->where('nomor_absen_ijin',request()->nomor_form_perizinan)->where('absen_masuk_kerja',null)->where('absen_pulang_kerja',null)->update([
                    'nomor_absen_ijin'=>null,
                    'status_absen'=>'M',
                    'operator'=>$email,
                ]);
            }
        } else {
            return response()->json(['message' => 'Pengajuan sudah diverifikasi atau tidak ditemukan.'], 400);
        }
    }
    public function create_perizinan_menu(Request $request)
    {

        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        info('START REPLACE IZIN');
        info('Tambah Permohonan Perizinan by ' . $email);


        $uuid_master = $request->uuid;
        $tanggal_perizinan = $request->tanggal_perizinan;
        $enroll_id = $request->enroll_id;
        $didelegasikan_enroll_id = $request->didelegasikan_enroll_id;
        $nik = $request->nik;
        $employee_name = $request->employee_name;
        $kode_absen_ijin = $request->kode_absen_ijin;
        $absen_alasan = $request->absen_alasan;
        $tanggal_mulai_ijin = $request->tanggal_mulai_ijin;
        $tanggal_akhir_ijin = $request->tanggal_akhir_ijin;
        $query = false;

        switch ($kode_absen_ijin) {
            case 'DL':
                $nomor_form_perizinan = 'FPI/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                break;
            case 'I':
                $nomor_form_perizinan = 'FPI/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                break;
            case 'S':
                $nomor_form_perizinan = 'FPI/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                break;
            default:
                if ($kode_absen_ijin <> 'M') {
                    $nomor_form_perizinan = 'FPC/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                } else {
                    $nomor_form_perizinan = 'TIDAK DI KENALI';
                }
                break;
        }

        if ($kode_absen_ijin=='LP') {
            $is_verifikasi=1;
            $verifikasi_by='system';
        }
        else{
            $is_verifikasi=0;
            $verifikasi_by=null;
        }

        $query=DB::select('select nomor_form_perizinan,CAST(substring(nomor_form_perizinan,13) AS int) as nomor_form from data_absen_perijinan where nomor_form_perizinan LIKE "'.$nomor_form_perizinan.'%" order by nomor_form desc limit 1');
        if($query == "" || !$query) {
            $nomor = "0000";
            $nomor_form_perizinan =  $nomor_form_perizinan . $nomor;
        } else {
            $nomor = $query[0]->nomor_form_perizinan;
            if(strlen($query[0]->nomor_form)<5){
                $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
            }else{
                $nomorform = str_pad(substr($nomor, -5) + 1,4,"0",STR_PAD_LEFT);
            }
            $nomor_form_perizinan =  $nomor_form_perizinan . $nomorform;
        }
        $cek_data = MasterDataAbsenKehadiran::whereBetween('tanggal_berjalan', [$tanggal_mulai_ijin, $tanggal_akhir_ijin])
                ->where('enroll_id', $enroll_id)
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5])
                        ->orWhereNotNull('mulai_jam_kerja');
                    })->whereNotIn('status_absen',['LN','LP'])->get();
        if($cek_data->count() > 0) {
            $query = DataAbsenPerijinan::create([
                'uuid' => Str::uuid(),
                'uuid_master' => $uuid_master,
                'tanggal_perizinan' => $tanggal_perizinan,
                'nomor_form_perizinan' => $nomor_form_perizinan,
                'enroll_id' => $enroll_id,
                // 'didelegasikan_enroll_id' => $didelegasikan_enroll_id,
                'kode_absen_ijin' => $kode_absen_ijin,
                'absen_alasan' => $absen_alasan,
                'tanggal_mulai_ijin' => $tanggal_mulai_ijin,
                'tanggal_akhir_ijin' => $tanggal_akhir_ijin,
                'is_verifikasi'=>$is_verifikasi,
                'verifikasi_by'=>$verifikasi_by,
                'operator' => $email,
                'diajukan_oleh' => $email,
                'is_verifikasi_pengajuan_admin' => 1,
            ]);
        } else {
            return 0;
        }


        if ($query) {
            info('Insert data nomor [' . $nomor_form_perizinan . '] on table data_absen_perijinan is SUCCESS.');
        } else {
            info('Insert data nomor [' . $nomor_form_perizinan . '] on table data_absen_perijinan is FAILED.');
        }
        if($kode_absen_ijin=='DL') {

            $query1 = MasterDataAbsenKehadiran::whereBetween('tanggal_berjalan', [$tanggal_mulai_ijin, $tanggal_akhir_ijin])
            ->where('enroll_id', $enroll_id)
            ->where(function ($query3) {
                $query3->whereNotIn('kode_hari', [6, 5]);
            })->where(function ($query){
                $query->where('status_absen','!=','LN')
                ->orWhere('status_absen',null);
            })->update([
                'nomor_absen_ijin' => $nomor_form_perizinan,
                'status_absen' => $kode_absen_ijin,
                'operator' => $email,
                'jumlah_menit_absen_dt'=>0,
                'jumlah_menit_absen_pc'=>0,
                'jumlah_menit_absen_dtpc'=>0,
                'updated_absen_ijin' => now()
            ]);

            if($query1) {
                info('Update on table master_data_absen_kehadiran after update Permohonan Perizinan [' . $nomor_form_perizinan . '] on table data_absen_perijinan is SUCCESS' );
            } else {
                info('Update on table master_data_absen_kehadiran after update Permohonan Perizinan [' . $nomor_form_perizinan . '] on table data_absen_perijinan is FAILED' );
            }
        }else{
            if($kode_absen_ijin=='LN'){
                $query1 = MasterDataAbsenKehadiran::whereBetween('tanggal_berjalan', [$tanggal_mulai_ijin, $tanggal_akhir_ijin])
                ->where('enroll_id', $enroll_id)
                ->update([
                    'nomor_absen_ijin' => $nomor_form_perizinan,
                    'status_absen' => $kode_absen_ijin,
                    'jumlah_menit_absen_dt'=>0,
                    'jumlah_menit_absen_pc'=>0,
                    'jumlah_menit_absen_dtpc'=>0,
                    'operator' => $email,
                    'updated_absen_ijin' => now()
                ]);
            }else{
                $query1 = MasterDataAbsenKehadiran::whereBetween('tanggal_berjalan', [$tanggal_mulai_ijin, $tanggal_akhir_ijin])
                ->where('enroll_id', $enroll_id)
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5])
                        ->orWhereNotNull('mulai_jam_kerja');
                    })->whereNotIn('status_absen',['LN','LP'])->update([
                    'nomor_absen_ijin' => $nomor_form_perizinan,
                    'status_absen' => $kode_absen_ijin,
                    'operator' => $email,
                    'absen_masuk_kerja'=>null,
                    'absen_pulang_kerja'=>null,
                    'jumlah_menit_absen_dt'=>0,
                    'jumlah_menit_absen_pc'=>0,
                    'jumlah_menit_absen_dtpc'=>0,
                    'updated_absen_ijin' => now()
                ]);
            }
        }

        info('END REPLACE IZIN');

        return $query1;
    }

    public function update_iks_menu(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        $tanggal_mulai_ijin = request()->tanggal_mulai_ijin;
        $tanggal_perizinan = request()->tanggal_perizinan == null ? $tanggal_mulai_ijin : request()->tanggal_perizinan;
        info('START UPDATE IZIN');
        info('Update Permohonan Perizinan by ' . $email);
        info('Nomor Form Perizinan : ' . request()->nomor_form_perizinan);

        $allowedEmails = ['fadli', 'mega@ptnag.com', 'hrd', 'ersa@ptnag.com', 'rudy@ptnag.com','indri@nag.nirwanaindonesia.com'];

        if (in_array($email, $allowedEmails)) {
            $absen = DataAbsenPerijinan::where('uuid', request()->uuid)->first();
        } else {
            // Harus cek is_verifikasi = 0
            $absen = DataAbsenPerijinan::where('uuid', request()->uuid)
                ->where('is_verifikasi_pengajuan_admin', 0)
                ->first();
        }


        if ($absen) {
            DataAbsenPerijinan::where('uuid',request()->uuid)->update([
                'kode_absen_ijin' => request()->kode_absen_ijin,
                'absen_alasan' => request()->absen_alasan,
                'tanggal_mulai_ijin' => $tanggal_mulai_ijin,
                'tanggal_akhir_ijin' => request()->tanggal_akhir_ijin,
                'time_mulai_ijin' => request()->time_mulai_ijin,
                'time_akhir_ijin' => request()->time_akhir_ijin,
                'total_time_ijin' => request()->total_time_ijin,
                'tanggal_perizinan' => $tanggal_perizinan,
                'operator' => $email
            ]);
            MasterDataAbsenKehadiran::whereRaw('
            tanggal_berjalan = "' . $tanggal_perizinan . '"
            and enroll_id = "' . request()->enroll_id . '"
            ')->where('status_absen','!=','LN')->update([
                'nomor_absen_ijin' => request()->nomor_form_perizinan,
                'status_absen' => request()->kode_absen_ijin,
                'operator' => $email,
                'updated_absen_ijin' => now()
            ]);
        }
        else {
            return response()->json(['message' => 'Pengajuan sudah diverifikasi atau tidak ditemukan.'], 400);
        }
    }

    public function create_iks_menu(Request $request)
    {

        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        info('START TAMBAH IKS');
        info('Tambah Permohonan IKS by ' . $email);

        $uuid_master = $request->uuid;
        $tanggal_mulai_ijin = $request->tanggal_mulai_ijin;
        $tanggal_perizinan = $request->tanggal_perizinan == null ? $tanggal_mulai_ijin : $request->tanggal_perizinan;
        $nomor_form_perizinan = $request->nomor_form_perizinan;
        $enroll_id = $request->enroll_id;
        $nik = $request->nik;
        $employee_name = $request->employee_name;
        $kode_absen_ijin = $request->kode_absen_ijin;
        $absen_alasan = $request->absen_alasan;
        $time_mulai_ijin = $request->time_mulai_ijin;
        $time_akhir_ijin = $request->time_akhir_ijin;
        $total_time_ijin = $request->total_time_ijin;
        $query = false;

        $nomor_form_perizinan = 'IKS/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';

        $getlastnomorform =  DataAbsenPerijinan::selectRaw('nomor_form_perizinan')
                                            ->whereRaw('nomor_form_perizinan like "' . $nomor_form_perizinan . '%"')
                                            ->groupby('nomor_form_perizinan')
                                            ->orderby('nomor_form_perizinan', 'desc')
                                            ->first();

        if ($getlastnomorform) {
            info('Get the latest nomor form permohonan IKS from database : ' . $getlastnomorform);
        } else {
            info('FAILED to Get the latest nomor form permohonan IKS from database');
        }

        if($getlastnomorform == "") {
            //info("Count : Kosong");
            $nomor = "0000";
        } else {
            $nomor = $getlastnomorform->nomor_form_perizinan;
        }

        $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
        $nomor_form_perizinan =  $nomor_form_perizinan . $nomorform;
        info('Nomor Form Perizinan : ' . $nomor_form_perizinan);
        $query = DataAbsenPerijinan::create([
            'uuid' => Str::uuid(),
            'uuid_master' => $uuid_master,
            'tanggal_perizinan' => $tanggal_perizinan,
            'nomor_form_perizinan' => $nomor_form_perizinan,
            'enroll_id' => $enroll_id,
            'kode_absen_ijin' => $kode_absen_ijin,
            'absen_alasan' => $absen_alasan,
            'time_mulai_ijin' => $time_mulai_ijin,
            'time_akhir_ijin' => $time_akhir_ijin,
            'total_time_ijin' => $total_time_ijin,
            'is_verifikasi_pengajuan_admin' => 0,
            'operator' => $email,
            'diajukan_oleh' => $email,
            'tanggal_mulai_ijin' => $tanggal_mulai_ijin,
            'tanggal_akhir_ijin' => request()->tanggal_akhir_ijin,
        ]);
        if ($query) {
            info('Insert data nomor [' . $nomor_form_perizinan . '] on table data_absen_perijinan is SUCCESS.');
        } else {
            info('Insert data nomor [' . $nomor_form_perizinan . '] on table data_absen_perijinan is FAILED.');
        }

        if($query) {
            $query = MasterDataAbsenKehadiran::whereRaw('
                tanggal_berjalan = "' . $tanggal_perizinan . '"
                and enroll_id = "' . $enroll_id . '"
            ')->where(function ($q){
                $q->where('status_absen','!=','LN')
                ->orWhere('status_absen',null);
            })
            ->update([
                'nomor_absen_ijin' => $nomor_form_perizinan,
                'status_absen' => $kode_absen_ijin,
                'operator' => $email,
                'updated_absen_ijin' => now()
            ]);
            if($query) {
                info('Update on table master_data_absen_kehadiran after update Permohonan IKS [' . $nomor_form_perizinan . '] on table data_absen_perijinan is SUCCESS' );
            } else {
                info('Update on table master_data_absen_kehadiran after update Permohonan IKS [' . $nomor_form_perizinan . '] on table data_absen_perijinan is FAILED' );
            }

        }
        info('END REPLACE IKS');

        return $query;
    }

    public function ajax_getkehadiran(Request $request)
    {
        $uuid = $request->uuid;

        $query =  MasterDataAbsenKehadiran::whereRaw('uuid = "' . $uuid  .'"')->Join('employee_atribut', 'master_data_absen_kehadiran.enroll_id', '=', 'employee_atribut.enroll_id')->first();

        return $query;

    }

    public function destroy(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        info('START DELETE PERIZINAN');
        info('Delete Perizinan by ' . $email);

        $tanggal_perizinan = $request->tanggal_perizinan;
        $nomor_form_perizinan = $request->nomor_form_perizinan;
        $enroll_id = $request->enroll_id;
        info('Tanggal Perizinan : ' . $tanggal_perizinan);
        info('Nomor Form Perizinan : ' . $nomor_form_perizinan);
        info('Nomor Absen : ' . $enroll_id);

        $query = DataAbsenPerijinan::whereRaw('
                        nomor_form_perizinan = "'. $nomor_form_perizinan . '"
                        and enroll_id = "'. $enroll_id . '"
                    ')
                    ->delete();

        if($query) {
            info('Data di table data_absen_perijinan berhasil di hapus');
            $query = MasterDataAbsenKehadiran::whereRaw('
                nomor_absen_ijin = "' . $nomor_form_perizinan . '"
                and enroll_id = "' . $enroll_id . '"
            ')
            ->update([
                'nomor_absen_ijin' => null,
                'status_absen' =>'M',
                'operator' => 'system',
                'updated_absen_ijin' => null,
                'deleted_at' => now()
            ]);

            if ($query) {
                info('Data di table master_data_absen_kehadiran BERHASIL di hapus');
            } else {
                info('Data di table master_data_absen_kehadiran GAGAL di hapus');
            }
        }
        return $query;
    }

    public function cekperizinan(Request $request)
    {

        $enroll_id = $request->enroll_id;
        $tanggal_mulai_ijin = $request->tanggal_mulai_ijin;
        $tanggal_akhir_ijin = $request->tanggal_akhir_ijin;
        $query =  DataAbsenPerijinan::whereRaw('(
                                    (tanggal_perizinan between "'. $tanggal_mulai_ijin . '" and "'. $tanggal_akhir_ijin . '") OR
                                    (tanggal_mulai_ijin = "'.$tanggal_mulai_ijin.'" and tanggal_akhir_ijin = "'.$tanggal_akhir_ijin.'"))
                                    and enroll_id = "'. $enroll_id . '"
                                 ')
                                 ->get();

        return $query;

    }

    public function cekiks(Request $request)
    {

        $tanggal_perizinan = $request->tanggal_perizinan;
        $enroll_id = $request->enroll_id;

        $query =  DataAbsenPerijinan::whereRaw('
                                    tanggal_perizinan = "'. $tanggal_perizinan . '"
                                    and enroll_id = "'. $enroll_id . '"
                                 ')
                                 ->count();

        return $query;

    }

    public function ajax_exportexcel(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        if($request->daterange1) {
            $daterange1 = $request->daterange1;
        } else {
            $daterange1 = "";
        }

        $fileName = 'DataPerizinanKaryawan_' . time() . '.xlsx';
        return (new DataAbsenPerijinanExport)->exportParams($daterange1)->download($fileName);

    }

    public function ajax_exportexcel2(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        if($request->daterange2) {
            $daterange2 = $request->daterange2;
        } else {
            $daterange2 = "";
        }

        $fileName = 'DataPerizinanIKSKaryawan_' . time() . '.xlsx';
        return (new DataAbsenPerijinanIKSExport)->exportParams($daterange2)->download($fileName);

    }

    // --------------- Andri -----------------
    public function perijinan_verifikasi()
    {
        return View::make('hris/absen/dataperijinan', $this->data);
    }

    public function perijinan_verifikasi_get(Request $request)
    {
        $periode_perijinan = $request->periode_perijinan;
        $array_periode_perijinan = explode(' - ', $periode_perijinan);
        $awal_bulan = date('Y-m-d', strtotime(substr($array_periode_perijinan[0], 0, 10)));
        $akhir_bulan =date('Y-m-d', strtotime(substr($array_periode_perijinan[1], 0, 10)));

        $get_waiting =  DataAbsenPerijinan::
                    whereRaw('tanggal_perizinan BETWEEN "' . $awal_bulan . '" AND "' . $akhir_bulan . '"
                                AND is_verifikasi=0 AND is_verifikasi_pengajuan_admin=1
                            ')
                    ->leftJoin('employee_atribut','data_absen_perijinan.enroll_id','=','employee_atribut.enroll_id')
                    ->get();

        $get_verifikasi =  DataAbsenPerijinan::
        whereRaw('tanggal_perizinan BETWEEN "' . $awal_bulan . '" AND "' . $akhir_bulan . '"
                    AND is_verifikasi=1
                ')
                ->leftJoin('employee_atribut','data_absen_perijinan.enroll_id','=','employee_atribut.enroll_id')
                ->get();

        $waiting=[];
        foreach ($get_waiting as $key => $value) {
            $waiting[]=[
                'uuid'=>$value->uuid,
                'tanggal_perizinan'=>$value->tanggal_perizinan,
                'nomor_form_perizinan'=>$value->nomor_form_perizinan,
                'enroll_id'=>$value->enroll_id,
                'nik'=>$value->nik,
                'employee_name'=>$value->employee_name,
                'kode_absen_ijin'=>$value->kode_absen_ijin,
                'absen_alasan'=>$value->absen_alasan,
                'tanggal_mulai_ijin'=>$value->tanggal_mulai_ijin??$value->tanggal_perizinan,
                'tanggal_akhir_ijin'=>$value->tanggal_akhir_ijin??$value->tanggal_perizinan,
                'time_mulai_ijin'=>$value->time_mulai_ijin??'-',
                'time_akhir_ijin'=>$value->time_akhir_ijin??'-',
            ];
        }
        $verifikasi=[];
        foreach ($get_verifikasi as $key2 => $value2) {
            $verifikasi[]=[
                'uuid'=>$value2->uuid,
                'tanggal_perizinan'=>$value2->tanggal_perizinan,
                'nomor_form_perizinan'=>$value2->nomor_form_perizinan,
                'enroll_id'=>$value2->enroll_id,
                'nik'=>$value2->nik,
                'employee_name'=>$value2->employee_name,
                'kode_absen_ijin'=>$value2->kode_absen_ijin,
                'absen_alasan'=>$value2->absen_alasan,
                'tanggal_mulai_ijin'=>$value2->tanggal_mulai_ijin??$value2->tanggal_perizinan,
                'tanggal_akhir_ijin'=>$value2->tanggal_akhir_ijin??$value2->tanggal_perizinan,
                'time_mulai_ijin'=>$value2->time_mulai_ijin??'-',
                'time_akhir_ijin'=>$value2->time_akhir_ijin??'-',
            ];
        }
        $data=[
            'waiting'=>$waiting,
            'verifikasi'=> $verifikasi,
        ];
        return Response()->json($data);
    }

    public function perijinan_verifikasi_store(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $count=count($request->uuid);
        for ($i=0; $i < $count ; $i++) {
            $data=[
                'is_verifikasi'=>1,
                'verifikasi_by'=>$loggedAdmin->email
            ];
            DataAbsenPerijinan::where('uuid',$request->uuid[$i])->update($data);
        }
        $a=true;
        return $a;
    }

    public function perijinan_export(Request $request)
    {
        $data=Excel::toArray([],$request->file('file_import'));
                foreach ($data[0] as $key => $row) {
                    if($key>0){
                        $tanggal_perizinan =$row[0];
                        $tanggal_perizinan = ($tanggal_perizinan - 25569) * 86400;
                        $tanggal_perizinan = 25569 + ($tanggal_perizinan / 86400);
                        $tanggal_perizinan = ($tanggal_perizinan - 25569) * 86400;
                        $tanggal_perizinan_a=date('Y-m-d', $tanggal_perizinan);

                        $tanggal_akhir_ijin =$row[8];
                        $tanggal_akhir_ijin = ($tanggal_akhir_ijin - 25569) * 86400;
                        $tanggal_akhir_ijin = 25569 + ($tanggal_akhir_ijin / 86400);
                        $tanggal_akhir_ijin = ($tanggal_akhir_ijin - 25569) * 86400;
                        $tanggal_akhir_ijin_b=date('Y-m-d', $tanggal_akhir_ijin);

                        // tabel perijinan
                        $perijinan=[
                            'uuid' => Str::uuid(),
                            // 'uuid_master' => $uuid_master,
                            'tanggal_perizinan' => $tanggal_perizinan_a,
                            'nomor_form_perizinan' => $row[1],
                            'enroll_id' => $row[2],
                            'nik' => $row[3],
                            'employee_name' => $row[4],
                            'kode_absen_ijin' => $row[5],
                            'absen_alasan' => $row[6],
                            'tanggal_mulai_ijin' => $tanggal_perizinan_a,
                            'tanggal_akhir_ijin' => $tanggal_akhir_ijin_b,
                            'operator' => 'system_injek_lebaran',
                            'is_verifikasi'=>1,
                            'verifikasi_by'=>'system',
                            'is_verifikasi_pengajuan_admin' => 0,
                        ];
                        $query = DataAbsenPerijinan::create($perijinan);

                        $update_empoly=[
                            'nomor_absen_ijin' => $row[1],
                            'status_absen' => $row[5],
                            'absen_alasan' => $row[6],
                            'tanggal_mulai_ijin' => $tanggal_perizinan_a,
                            'tanggal_akhir_ijin' => $tanggal_akhir_ijin_b,
                            'operator' => 'system_injek_lebaran',
                            'updated_absen_ijin' => now()
                        ];
                        MasterDataAbsenKehadiran::whereRaw('
                        tanggal_berjalan between "' . $tanggal_perizinan_a . '" and "' . $tanggal_akhir_ijin_b . '"
                        and enroll_id = "' . $row[2] . '"
                        ')->update($update_empoly);
                    }

                }
            return true;
    }

    public function get_last_nomor_form_perizinan(){
        switch (request()->kode_ijin) {
            case 'DL':
                $nomor_form_perizinan = 'FPI/HR/' . substr(request()->tanggal_perizinan, 2, 2) . substr(request()->tanggal_perizinan, 5, 2) . '/';
                break;
            case 'I':
                $nomor_form_perizinan = 'FPI/HR/' . substr(request()->tanggal_perizinan, 2, 2) . substr(request()->tanggal_perizinan, 5, 2) . '/';
                break;
            case 'S':
                $nomor_form_perizinan = 'FPI/HR/' . substr(request()->tanggal_perizinan, 2, 2) . substr(request()->tanggal_perizinan, 5, 2) . '/';
                break;
            default:
                if (request()->kode_absen <> 'M') {
                    $nomor_form_perizinan = 'FPC/HR/' . substr(request()->tanggal_perizinan, 2, 2) . substr(request()->tanggal_perizinan, 5, 2) . '/';
                } else {
                    $nomor_form_perizinan = 'TIDAK DI KENALI';
                }
                break;
        }
        // $getlastnomorform =  DataAbsenPerijinan::selectRaw('nomor_form_perizinan')
        //                                     ->whereRaw('nomor_form_perizinan like "' . $nomor_form_perizinan . '%"')
        //                                     ->groupby('nomor_form_perizinan')
        //                                     ->orderby('nomor_form_perizinan', 'desc')
        //                                     ->first();

        $query=DB::select('select nomor_form_perizinan,CAST(substring(nomor_form_perizinan,13) AS int) as nomor_form from data_absen_perijinan where nomor_form_perizinan LIKE "'.$nomor_form_perizinan.'%" order by nomor_form desc limit 1');

        if($query == "" || !$query) {
            $nomor = "0000";
            $nomor_form_perizinan =  $nomor_form_perizinan . $nomor;
        } else {
            $nomor = $query[0]->nomor_form_perizinan;
            if(strlen($query[0]->nomor_form)<5){
                $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
            }else{
                $nomorform = str_pad(substr($nomor, -5) + 1,4,"0",STR_PAD_LEFT);
            }
            $nomor_form_perizinan =  $nomor_form_perizinan . $nomorform;
        }
        return $nomor_form_perizinan;
    }

    public function get_last_nomor_form_perizinan_iks(){
        $nomor_form_perizinan = 'IKS/HR/' . substr(request()->tanggal_perizinan, 2, 2) . substr(request()->tanggal_perizinan, 5, 2) . '/';

        $getlastnomorform =  DataAbsenPerijinan::selectRaw('nomor_form_perizinan')
                                            ->whereRaw('nomor_form_perizinan like "' . $nomor_form_perizinan . '%"')
                                            ->groupby('nomor_form_perizinan')
                                            ->orderby('nomor_form_perizinan', 'desc')
                                            ->first();
        if($getlastnomorform == "") {
            $nomor = "0000";
        } else {
            $nomor = $getlastnomorform->nomor_form_perizinan;
        }

        $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
        $nomor_form_perizinan =  $nomor_form_perizinan . $nomorform;
        return $nomor_form_perizinan;
    }

    public function import_data_perizinan(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '5120M');
        $data=Excel::toArray([],request()->file('excel_file'));
        return $data;
    }

    public function import_perizinan_to_database(){
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        info('START REPLACE IZIN');
        info('Import Permohonan Perizinan by ' . $email);
        $data=Excel::toArray([],request()->file('excel_file'));
        $arrayPerizinan=[];
        for($i=4;$i<count($data[0]);$i++){
            if(count(EmployeeAtribut::where('enroll_id',$data[0][$i][1])->get())<1){
                continue;
            }
            if($data[0][$i][0]=='' || $data[0][$i][0]=='-'){
                $tanggal_perizinan=null;
            }else{
                $tanggal_perizinan=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][0])->format('Y-m-d');
            }
            switch($data[0][$i][4]){
                case 'DL':
                    $nomor_form_perizinan = 'FPI/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                    break;
                case 'I':
                    $nomor_form_perizinan = 'FPI/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                    break;
                case 'S':
                    $nomor_form_perizinan = 'FPI/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                    break;
                default:
                if ($data[0][$i][4] <> 'M') {
                    $nomor_form_perizinan = 'FPC/HR/' . substr($tanggal_perizinan, 2, 2) . substr($tanggal_perizinan, 5, 2) . '/';
                } else {
                    $nomor_form_perizinan = 'TIDAK DI KENALI';
                }
                break;
            }

            $query = DB::select('
            SELECT nomor_form_perizinan,
                   CAST(SUBSTRING(nomor_form_perizinan, 13) AS INT) AS nomor_form
            FROM data_absen_perijinan
            WHERE nomor_form_perizinan LIKE "' . $nomor_form_perizinan . '%"
            ORDER BY nomor_form DESC
            LIMIT 1
        ');

            if (empty($query)) {
                $nomor = "0000";
                $nomorform = str_pad(1, 4, "0", STR_PAD_LEFT);
            } else {
                $nomor = $query[0]->nomor_form_perizinan;
                if (strlen($query[0]->nomor_form) < 5) {
                    $nomorform = str_pad(substr($nomor, -4) + 1, 4, "0", STR_PAD_LEFT);
                } else {
                    $nomorform = str_pad(substr($nomor, -5) + 1, 4, "0", STR_PAD_LEFT);
                }
            }

            if ($data[0][$i][4]=='LP') {
                $is_verifikasi=1;
                $verifikasi_by='system';
            }
            else{
                $is_verifikasi=0;
                $verifikasi_by=null;
            }
            if($data[0][$i][6]!='' && $data[0][$i][6] == true){
                $is_verifikasi=1;
                $verifikasi_by='system';
            }
            else{
                $is_verifikasi=0;
                $verifikasi_by=null;
            }
            $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
            $nomor_form_perizinan =  $nomor_form_perizinan . $nomorform;
            DataAbsenPerijinan::create([
                'uuid' => Str::uuid(),
                'tanggal_perizinan' => date('Y-m-d'),
                'nomor_form_perizinan' => $nomor_form_perizinan,
                'enroll_id' => $data[0][$i][1],
                'kode_absen_ijin' => $data[0][$i][4],
                'absen_alasan' => $data[0][$i][5],
                'tanggal_mulai_ijin' => $tanggal_perizinan,
                'tanggal_akhir_ijin' => $tanggal_perizinan,
                'is_verifikasi'=>$is_verifikasi,
                'verifikasi_by'=>$verifikasi_by,
                'operator' => $email,
                'diajukan_oleh' => $email,
                'is_verifikasi_pengajuan_admin' => 0,
            ]);
            if($data[0][$i][4]=='DL') {
                $query1 = MasterDataAbsenKehadiran::whereBetween('tanggal_berjalan', [$tanggal_perizinan, $tanggal_perizinan])
                ->where('enroll_id', $data[0][$i][1])
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5]);
                })->whereNotIn('status_absen',['LN','R'])->update([
                    'nomor_absen_ijin' => $nomor_form_perizinan,
                    'status_absen' => $data[0][$i][4],
                    'operator' => $email,
                    'absen_masuk_kerja'=>null,
                    'absen_pulang_kerja'=>null,
                    'jumlah_menit_absen_dt'=>0,
                    'jumlah_menit_absen_pc'=>0,
                    'jumlah_menit_absen_dtpc'=>0,
                    'updated_absen_ijin' => now()
                ]);
            }else{
                $query1 = MasterDataAbsenKehadiran::whereBetween('tanggal_berjalan', [$tanggal_perizinan, $tanggal_perizinan])
                ->where('enroll_id', $data[0][$i][1])
                ->where(function ($query3) {
                    $query3->whereNotIn('kode_hari', [6, 5])
                        ->orWhereNotNull('mulai_jam_kerja');
                })->whereNotIn('status_absen',['LN','R'])->update([
                    'nomor_absen_ijin' => $nomor_form_perizinan,
                    'status_absen' => $data[0][$i][4],
                    'operator' => $email,
                    'absen_masuk_kerja'=>null,
                    'absen_pulang_kerja'=>null,
                    'jumlah_menit_absen_dt'=>0,
                    'jumlah_menit_absen_pc'=>0,
                    'jumlah_menit_absen_dtpc'=>0,
                    'updated_absen_ijin' => now()
                ]);
            }
        }
    }
}
