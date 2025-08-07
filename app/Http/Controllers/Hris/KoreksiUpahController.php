<?php

namespace App\Http\Controllers\Hris;

use App\Models\EmployeeAtribut;
use App\Http\Controllers\AdminBaseController;
use App\Models\DataKoreksiUpah;
use App\Models\RekapKehadiranKaryawan;
use App\Models\DepartmentAll;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Datatables;
use App\Exports\MasterDataAbsenKehadiranExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

use  App\Exports\KoreksiUpahExport;

/**
 * Class KoreksiUpahController
 * @package App\Http\Controllers\Hris
 */
class KoreksiUpahController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Koreksi Upah';
    }

    public function index()
    {
        $this->periode_payroll = $this->ajax_getperiode();
        $departments=DepartmentAll::where('site_nirwana_id','NAG')->where('status','AKTIF')->groupBy('department_name')->get();
        $data_priode=DataKoreksiUpah::groupBy('periode_tanggal_koreksi')->orderBy('tanggal_koreksi', 'desc')->get();
        $x=[];
        foreach ($data_priode as $key => $value) {
            $x[]=['periode'=>$value->periode_tanggal_koreksi];
        }

        $this->priode_koreksi=$x;
        return View::make('hris/koreksiupah', $this->data,compact('departments'));
    }
    public function ajax_getperiode()
    {
        $query =  RekapKehadiranKaryawan::selectRaw('periode_payroll')
                                    ->groupby('periode_payroll')
                                    ->orderby('periode_payroll', 'desc')
                                    ->get();
        return $query;

    }
    public function ajax_datainsjabatan(Request $request){
        if(request()->ajax()) {

            $limit = $request->input('length');
            $start = $request->input('start');
            $totalData = 0;
            $totalFiltered = 0;

            $periode_tanggal_koreksi = substr($request->periode_tanggal_koreksi,5,2).'/'.substr($request->periode_tanggal_koreksi,8,2).'/'.substr($request->periode_tanggal_koreksi,0,4).' - '.substr($request->periode_tanggal_koreksi,20,2).'/'.substr($request->periode_tanggal_koreksi,23,2).'/'.substr($request->periode_tanggal_koreksi,15,4);

            // $query =  DB::select("select
            // a.enroll_id id,
            // a.employee_name as nama_karyawan,
            // a.sub_dept_name as nama_department,
            // a.jumlah_rp_potongan as insentif,
            // b.jumlah_rp_potongan as koreksi_upah
            // from (select x.enroll_id, x.jumlah_rp_potongan from data_koreksi_upah x inner join (select max(y.periode) periode, y.enroll_id enroll_id
            // from (SELECT enroll_id, uuid,
            // CONCAT(substr(periode_tanggal_koreksi,20,4),'-',substr(periode_tanggal_koreksi,14,2),'-',substr(periode_tanggal_koreksi,17,2)) as periode, jumlah_rp_potongan
            // FROM data_koreksi_upah where jenis_koreksi=2 order by CONCAT(substr(periode_tanggal_koreksi,20,4),'-',substr(periode_tanggal_koreksi,14,2),'-',substr(periode_tanggal_koreksi,17,2)) desc)y
            // group by enroll_id)z on x.enroll_id=z.enroll_id and CONCAT(substr(x.periode_tanggal_koreksi,20,4),'-',substr(x.periode_tanggal_koreksi,14,2),'-',substr(x.periode_tanggal_koreksi,17,2))=z.periode)a
            // left join (select*from data_koreksi_upah where periode_tanggal_koreksi='$periode_tanggal_koreksi' and jenis_koreksi=2 group by enroll_id) b on a.enroll_id=b.enroll_id");

            $query = DB::select("SELECT
                        a.enroll_id AS id,
                        e.employee_name AS nama_karyawan,
                        e.sub_dept_name AS nama_department,
                        a.jumlah_rp_potongan AS insentif,
                        b.jumlah_rp_potongan AS koreksi_upah
                    FROM
                        (
                            SELECT
                                x.enroll_id,
                                x.jumlah_rp_potongan
                            FROM
                                data_koreksi_upah x
                            INNER JOIN
                                (
                                    SELECT
                                        enroll_id,
                                        MAX(CONCAT(SUBSTR(periode_tanggal_koreksi, 20, 4), '-', SUBSTR(periode_tanggal_koreksi, 14, 2), '-', SUBSTR(periode_tanggal_koreksi, 17, 2))) AS periode
                                    FROM
                                        data_koreksi_upah
                                    WHERE
                                        jenis_koreksi = 2
                                    GROUP BY
                                        enroll_id
                                ) z
                            ON
                                x.enroll_id = z.enroll_id
                                AND CONCAT(SUBSTR(x.periode_tanggal_koreksi, 20, 4), '-', SUBSTR(x.periode_tanggal_koreksi, 14, 2), '-', SUBSTR(x.periode_tanggal_koreksi, 17, 2)) = z.periode
                        ) a
                    LEFT JOIN
                        (
                            SELECT
                                enroll_id,
                                jumlah_rp_potongan
                            FROM
                                data_koreksi_upah
                            WHERE
                                periode_tanggal_koreksi = '$periode_tanggal_koreksi'
                                AND jenis_koreksi = 2
                            GROUP BY
                                enroll_id
                        ) b
                    ON
                        a.enroll_id = b.enroll_id
                    LEFT JOIN
                        employee_atribut e
                    ON
                        a.enroll_id = e.enroll_id;
                    ");

            $totalData = DataKoreksiUpah::where('jenis_koreksi',2)->groupBy('enroll_id')->count();
            $totalFiltered = count($query);
            $data = array();
            if(!empty($query))
            {
                foreach ($query as $q)
                {
                    $nestedData['enroll_id'] = $q->id;
                    $nestedData['employee_name'] = $q->nama_karyawan;
                    $nestedData['department_name'] = $q->nama_department;
                    $nestedData['insentif'] = $q->insentif;
                    $nestedData['koreksi_upah'] = $q->koreksi_upah;
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
    public function ajax_datakoreksiupah(Request $request)
    {

        if(request()->ajax()) {

        $limit = $request->input('length');
        $start = $request->input('start');
        $totalData = 0;
        $totalFiltered = 0;

        $periode_payroll = $request->periode_payroll;

        if(empty($request->input('search.value')))
        {
            $query =  DataKoreksiUpah::selectRaw('
                        data_koreksi_upah.uuid,
                        data_koreksi_upah.kode_koreksi_upah,
                        data_koreksi_upah.tanggal_koreksi,
                        employee_atribut.enroll_id,
                        employee_atribut.nik,
                        employee_atribut.employee_name,
                        department_all.sub_dept_name,
                        department_all.department_name,
                        data_koreksi_upah.jumlah_rp_potongan,
                        data_koreksi_upah.periode_tanggal_koreksi,
                        data_koreksi_upah.operator,
                        data_koreksi_upah.keterangan,
                        data_koreksi_upah.created_at,
                        data_koreksi_upah.updated_at,
                        data_koreksi_upah.jenis_koreksi
                    ')
                    ->leftJoin('employee_atribut','data_koreksi_upah.enroll_id','=','employee_atribut.enroll_id')
                    ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
                    ->orderBy('data_koreksi_upah.updated_at','desc')
                    ->offset($start)
                    ->limit($limit)
                    ->get();

            $totalData = DataKoreksiUpah::count();
            $totalFiltered = $totalData;

        } else {
            $search = $request->input('search.value');

            $query =  DataKoreksiUpah::selectRaw('
                        data_koreksi_upah.uuid,
                        data_koreksi_upah.kode_koreksi_upah,
                        data_koreksi_upah.tanggal_koreksi,
                        employee_atribut.enroll_id,
                        employee_atribut.nik,
                        employee_atribut.employee_name,
                        department_all.department_name,
                        department_all.sub_dept_name,
                        data_koreksi_upah.jumlah_rp_potongan,
                        data_koreksi_upah.periode_tanggal_koreksi,
                        data_koreksi_upah.operator,
                        data_koreksi_upah.keterangan,
                        data_koreksi_upah.created_at,
                        data_koreksi_upah.updated_at,
                        data_koreksi_upah.jenis_koreksi
                    ')
                    ->whereRaw('
                        data_koreksi_upah.kode_koreksi_upah LIKE "%' . $search . '%"
                        OR data_koreksi_upah.tanggal_koreksi = "' . $search . '%"
                        OR employee_atribut.enroll_id LIKE "%' . $search . '%"
                        OR employee_atribut.nik LIKE "%' . $search . '%"
                        OR employee_atribut.employee_name LIKE "%' . $search . '%"
                        OR department_all.sub_dept_name LIKE "%' . $search . '%"
                        OR data_koreksi_upah.keterangan LIKE "%' . $search . '%"
                    ')
                    ->leftJoin('employee_atribut','data_koreksi_upah.enroll_id','=','employee_atribut.enroll_id')
                    ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
                    ->orderBy('data_koreksi_upah.updated_at','desc')
                    ->offset($start)
                    ->limit($limit)
                    ->get();

            $totalData = DataKoreksiUpah::whereRaw('
                                data_koreksi_upah.kode_koreksi_upah LIKE "%' . $search . '%"
                                OR data_koreksi_upah.tanggal_koreksi = "' . $search . '%"
                                OR employee_atribut.enroll_id LIKE "%' . $search . '%"
                                OR employee_atribut.nik LIKE "%' . $search . '%"
                                OR employee_atribut.employee_name LIKE "%' . $search . '%"
                                OR department_all.site_nirwana_name LIKE "%' . $search . '%"
                                OR data_koreksi_upah.keterangan LIKE "%' . $search . '%"
                            ')
                            ->leftJoin('employee_atribut','data_koreksi_upah.enroll_id','=','employee_atribut.enroll_id')
                            ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
                            ->count();
            $totalFiltered = $totalData;

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['uuid'] = $q->uuid;
                $nestedData['kode_koreksi_upah'] = $q->kode_koreksi_upah;
                $nestedData['tanggal_koreksi'] = $q->tanggal_koreksi;
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['nik'] = $q->nik;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['department_name'] = $q->department_name;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;
                $nestedData['jumlah_rp_potongan'] = $q->jumlah_rp_potongan;
                $nestedData['jumlah_rp_potongan_format'] = number_format($q->jumlah_rp_potongan);
                $nestedData['periode_tanggal_koreksi'] = $q->periode_tanggal_koreksi;

                setlocale(LC_ALL, 'id-ID', 'id_ID');
                $explodePeriode = explode(" - ", $q->periode_tanggal_koreksi);
                $periodeStartKoreksi = strtoupper(strftime("%d %b %Y", strtotime(substr($explodePeriode[0], 6, 4) . "-" . substr($explodePeriode[0], 0, 2) . "-" . substr($explodePeriode[0], 3, 2))));
                $periodeEndKoreksi = strtoupper(strftime("%d %b %Y", strtotime(substr($explodePeriode[1], 6, 4) . "-" . substr($explodePeriode[1], 0, 2) . "-" . substr($explodePeriode[1], 3, 2))));
                $periode_tanggal_koreksi_format = $periodeStartKoreksi . " - " . $periodeEndKoreksi;

                $nestedData['periode_tanggal_koreksi_format'] = $periode_tanggal_koreksi_format;

                $nestedData['operator'] = $q->operator;
                $nestedData['keterangan'] = $q->keterangan;
                $nestedData['created_at'] = substr($q->created_at, 0, 10) . " " . substr($q->created_at, 11, 5);
                $nestedData['updated_at'] = substr($q->updated_at, 0, 10) . " " . substr($q->updated_at, 11, 5);

                $nestedData['jenis_koreksi'] = $q->jenis_koreksi;


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

    public function create(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        session(['loggedAdmin' => $loggedAdmin]);

        $kode_koreksi_upah = $request->kode_koreksi_upah;
        $tanggal_koreksi = $request->tanggal_koreksi;
        $enroll_id = $request->enroll_id;
        $nik = $request->nik;
        $employee_name = $request->employee_name;
        $site_nirwana_id = $request->site_nirwana_id;
        $site_nirwana_name = $request->site_nirwana_name;
        $department_id = $request->department_id;
        $department_name = $request->department_name;
        $sub_dept_id = $request->sub_dept_id;
        $sub_dept_name = $request->sub_dept_name;
        $jumlah_rp_potongan = $request->jumlah_rp_potongan;
        $periode_tanggal_koreksi = $request->periode_tanggal_koreksi;
        $keterangan = $request->keterangan;
        $jenis_koreksi = $request->jenis_koreksi;


        $tanggal_awal = explode(' - ', $periode_tanggal_koreksi)[0];

        $format_tanggal = \Carbon\Carbon::createFromFormat('m/d/Y', $tanggal_awal)->format('Y-m-d');

         $findDT = DataKoreksiUpah::where('enroll_id',$request->enroll_id)->where('periode_tanggal_koreksi',$request->periode_tanggal_koreksi)
            ->where('jenis_koreksi',$request->jenis_koreksi)->count();
            if($findDT > 0) {
            $query = false;
        } else {
            $query = DataKoreksiUpah::create([
                'uuid' => Str::uuid(),
                'kode_koreksi_upah' => $kode_koreksi_upah,
                'tanggal_koreksi' => $format_tanggal,
                'enroll_id' => $enroll_id,
                'jumlah_rp_potongan' => $jumlah_rp_potongan,
                'periode_tanggal_koreksi' => $periode_tanggal_koreksi,
                'keterangan' => $keterangan,
                'operator' => $email,
                'jenis_koreksi' => $jenis_koreksi,
                'is_verifikasi_acc' => 0,

            ]);
        }

        return $query;
    }
    public function cek_koreksi_upah(){
        $periode_tanggal_koreksi = request()->periode_tanggal_kehadiran;

        $arr_periode_tgl_koreksi=explode(" s/d ",$periode_tanggal_koreksi);
        $periode_tanggal_kehadiran = date("m/d/Y", strtotime($arr_periode_tgl_koreksi[0])).' - '.date("m/d/Y", strtotime($arr_periode_tgl_koreksi[1]));
        $countData=DataKoreksiUpah::where('periode_tanggal_koreksi',$periode_tanggal_kehadiran)->where('enroll_id',request()->id)->count();
        return $countData;
    }
    public function add_position_insentif(){
        $loggedAdmin=Auth::guard('admin')->user()->email;
        $tanggal_koreksi=request()->tanggal_koreksi;
        $arr_tgl_koreksi=explode("-",$tanggal_koreksi);
        $enroll_id = request()->id;
        $employee=EmployeeAtribut::where('enroll_id',$enroll_id)->get();
        foreach($employee as $value){
            $nik=$value->nik;
            $employee_name=$value->employee_name;
            $site_nirwana_id = $value->site_nirwana_id;
            $site_nirwana_name = $value->site_nirwana_name;
            $department_id = $value->department_id;
            $department_name = $value->department_name;
            $sub_dept_id = $value->sub_dept_id;
            $sub_dept_name = $value->sub_dept_name;
        }
        $kode_koreksi_upah=$arr_tgl_koreksi[0].$arr_tgl_koreksi[1].ltrim(date('is'),'0').$nik;
        $jumlah_rp_potongan = request()->jumlah_rp_potongan;
        $periode_tanggal_koreksi = request()->periode_tanggal_koreksi;

        $arr_periode_tgl_koreksi=explode(" s/d ",$periode_tanggal_koreksi);
        $periode_tanggal_kehadiran = date("m/d/Y", strtotime($arr_periode_tgl_koreksi[0])).' - '.date("m/d/Y", strtotime($arr_periode_tgl_koreksi[1]));
        $keterangan='Insentif Jabatan';
        $jenis_koreksi=2;
        $data_koreksi=[
            'uuid' => Str::uuid(),
            'kode_koreksi_upah' => $kode_koreksi_upah,
            'tanggal_koreksi' => $tanggal_koreksi,
            'enroll_id' => $enroll_id,
            'jumlah_rp_potongan' => $jumlah_rp_potongan,
            'periode_tanggal_koreksi' => $periode_tanggal_kehadiran,
            'keterangan' => $keterangan,
            'operator' => $loggedAdmin,
            'jenis_koreksi' => $jenis_koreksi,
            'is_verifikasi_acc' => 0,
        ];
        $data_koreksi_db=DataKoreksiUpah::where('enroll_id',$enroll_id)->where('periode_tanggal_koreksi',$periode_tanggal_kehadiran)->count();
        if($data_koreksi_db==0){
            DataKoreksiUpah::create($data_koreksi);
        }else{
            DataKoreksiUpah::where('periode_tanggal_koreksi',$periode_tanggal_kehadiran)->where('enroll_id',$enroll_id)->update($data_koreksi);
        }
    }
    public function delete_position_insentif(){
        $periode_tanggal_koreksi = request()->periode_tanggal_koreksi;

        $arr_periode_tgl_koreksi=explode(" s/d ",$periode_tanggal_koreksi);
        $periode_tanggal_kehadiran = date("m/d/Y", strtotime($arr_periode_tgl_koreksi[0])).' - '.date("m/d/Y", strtotime($arr_periode_tgl_koreksi[1]));
        DataKoreksiUpah::where('periode_tanggal_koreksi',$periode_tanggal_kehadiran)->where('enroll_id',request()->id)->delete();
    }
    public function get_active_employee(){
        $tanggal_sekarang = request()->tanggal_koreksi;
        $bulan_sekarang=date('Y-m-'.'26');
        $bulan_sebelum=date('Y-m-d',strtotime( "-1 month", strtotime( $bulan_sekarang ) ));
        $bulan_setelah=date('Y-m-d',strtotime( "+1 month", strtotime( $bulan_sekarang ) ));

        if($tanggal_sekarang>$bulan_sebelum && $tanggal_sekarang<=$bulan_sekarang){
            $tanggal_awal=$bulan_sebelum;
        }else if($tanggal_sekarang>$bulan_sekarang && $tanggal_sekarang<=$bulan_setelah){
            $tanggal_awal=$bulan_sekarang;
        }
        $employee=EmployeeAtribut::where('status_aktif','AKTIF')->orWhere('tanggal_resign','>',$tanggal_awal)->orderBy('employee_name')->get();
        return $employee;
    }
    public function update(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        session(['loggedAdmin' => $loggedAdmin]);
        $email = $loggedAdmin->email;

        $uuid = $request->uuid;
        $kode_koreksi_upah = $request->kode_koreksi_upah;
        $tanggal_koreksi = $request->tanggal_koreksi;
        $enroll_id = $request->enroll_id;
        $nik = $request->nik;
        $employee_name = $request->employee_name;
        $site_nirwana_id = $request->site_nirwana_id;
        $site_nirwana_name = $request->site_nirwana_name;
        $department_id = $request->department_id;
        $department_name = $request->department_name;
        $sub_dept_id = $request->sub_dept_id;
        $sub_dept_name = $request->sub_dept_name;
        $jumlah_rp_potongan = $request->jumlah_rp_potongan;
        $periode_tanggal_koreksi = $request->periode_tanggal_koreksi;
        $keterangan = $request->keterangan;
        $jenis_koreksi = $request->jenis_koreksi;


        $query = DataKoreksiUpah::where('uuid','=', $uuid)
                    ->update([
                        'kode_koreksi_upah' => $kode_koreksi_upah,
                        'tanggal_koreksi' => $tanggal_koreksi,
                        'enroll_id' => $enroll_id,
                        'jumlah_rp_potongan' => $jumlah_rp_potongan,
                        'periode_tanggal_koreksi' => $periode_tanggal_koreksi,
                        'keterangan' => $keterangan,
                        'operator' => $email,
                        'jenis_koreksi' => $jenis_koreksi

                    ]);

        return $query;
    }

    public function destroy(Request $request)
    {
        $uuid = $request->uuid;
        $loggedAdmin = Auth::guard('admin')->user();
        session(['loggedAdmin' => $loggedAdmin]);

        $findDT = DataKoreksiUpah::where('uuid','=', $uuid)->count();

        if($findDT > 0) {
            $query = DataKoreksiUpah::where('uuid','=',$uuid)->delete();
        } else {
            $query = false;
        }

        return $query;
    }

    // =============Andri====================
     public function format_import_koreksiupah()
     {
         $filepath = public_path('format_import/format_import_koreksi_upah.xlsx');
         return Response()->download($filepath);
     }

     public function import_koreksiupah(Request $request)
     {
        try{
            $data=Excel::toArray([],$request->file('file_import'));
            $loggedAdmin = Auth::guard('admin')->user();
            $loggedAdmin->email;

            $data_import=[];

            $jenisKoreksiMapping = [
                'UPAH' => 1,
                'INSENTIF JABATAN' => 2,
                'LEMBUR' => 3,
                'INSENTIF LAINNYA' => 4,
            ];


            $head=$data[0][0];
            if($head[0]=='enroll_id' && $head[3]=='jumlah_rp_koreksi' ){
                foreach ($data[0] as $key2 => $row) {
                    if($key2>0){
                        $error=$key2;
                        $employee=EmployeeAtribut::where('enroll_id',$row[0])->first();
                        $nik=$employee->nik??null;

                        $tgl_awal =$row[4];
                        $tgl_awal = ($tgl_awal - 25569) * 86400;
                        $tgl_awal = 25569 + ($tgl_awal / 86400);
                        $tgl_awal = ($tgl_awal - 25569) * 86400;
                        $tgl_priode_awal=date('m/d/Y', $tgl_awal);

                        // $tgl_koreksi_plus1 = date('Y-m-d', strtotime('+1 day', $tgl_awal));

                        $tgl_akhir =$row[5];
                        $tgl_akhir = ($tgl_akhir - 25569) * 86400;
                        $tgl_akhir = 25569 + ($tgl_akhir / 86400);
                        $tgl_akhir = ($tgl_akhir - 25569) * 86400;
                        $tgl_priode_akhir=date('m/d/Y', $tgl_akhir);

                        $tgl_koreksi_awal =$row[8];
                        $tgl_koreksi_awal = ($tgl_koreksi_awal - 25569) * 86400;
                        $tgl_koreksi_awal = 25569 + ($tgl_koreksi_awal / 86400);
                        $tgl_koreksi_awal = ($tgl_koreksi_awal - 25569) * 86400;
                        $tgl_koreksi = date('Y-m-d', $tgl_koreksi_awal);

                        $jenis_koreksi_string = strtoupper(trim($row[7]));

                        $data_import[]=[
                            'kode_koreksi_upah'=>date('Y').date('m').date('i').date('s').$nik,
                            'tanggal_koreksi'=> $tgl_koreksi,
                            'enroll_id'=>$row[0],
                            'nik'=>$employee->nik??null,
                            'employee_name'=>$employee->employee_name??null,
                            'site_nirwana_id'=>$employee->site_nirwana_id??null,
                            'site_nirwana_name'=>$employee->site_nirwana_name??null,
                            'department_id'=>$employee->department_id??null,
                            'department_name'=>$employee->department_name??null,
                            'sub_dept_id'=>$employee->sub_dept_id??null,
                            'sub_dept_name'=>$employee->sub_dept_name??null,
                            'jumlah_rp_potongan'=>$row[3],
                            'periode_tanggal_koreksi'=>$tgl_priode_awal.' - '.$tgl_priode_akhir,
                            'keterangan'=>$row[6],
                            'operator'=> $loggedAdmin->email,
                            'jenis_koreksi' => $jenisKoreksiMapping[$jenis_koreksi_string] ?? null,

                        ];
                    }
                }
                $collect=collect($data_import)->where('nik','!=',null);

                foreach ($collect as $key => $value) {
                    $findDT = DataKoreksiUpah::where('enroll_id',$value['enroll_id'])->where('periode_tanggal_koreksi',$value['periode_tanggal_koreksi'])
                            ->where('jenis_koreksi',$value['jenis_koreksi'])->count();
                    if($findDT){
                        $data_update=[
                            'kode_koreksi_upah'=>$value['kode_koreksi_upah'],
                            'tanggal_koreksi'=>$value['tanggal_koreksi'],
                            'enroll_id'=>$value['enroll_id'],
                            'jumlah_rp_potongan'=>$value['jumlah_rp_potongan'],
                            'periode_tanggal_koreksi'=>$value['periode_tanggal_koreksi'],
                            'keterangan'=>$value['keterangan'],
                            'operator'=>$value['operator'],
                            'jenis_koreksi' => $value['jenis_koreksi'],

                        ];
                        DataKoreksiUpah::where('enroll_id',$value['enroll_id'])->where('periode_tanggal_koreksi',$value['periode_tanggal_koreksi'])->where('jenis_koreksi',$value['jenis_koreksi'])->update($data_update);
                    }
                    else{
                        $data_insert=[
                            'uuid' => Str::uuid(),
                            'kode_koreksi_upah'=>$value['kode_koreksi_upah'],
                            'tanggal_koreksi'=>$value['tanggal_koreksi'],
                            'enroll_id'=>$value['enroll_id'],
                            'jumlah_rp_potongan'=>$value['jumlah_rp_potongan'],
                            'periode_tanggal_koreksi'=>$value['periode_tanggal_koreksi'],
                            'keterangan'=>$value['keterangan'],
                            'operator'=>$value['operator'],
                            'jenis_koreksi' => $value['jenis_koreksi'],
                            'is_verifikasi_acc' => 0,

                        ];
                        DataKoreksiUpah::create($data_insert);
                    }
                }

                return back()->with("success",'berhasil disimpan');
            }
            else{
                return back()->with("error",'gagal tersimpan format salah');
            }
        }catch(\Exception $e){
            $error=$error+1;
            return back()->with("error",'gagal tersimpan terdapat kesalah di row '.$error);
        }
     }

     public function export_koreksiupah(Request $request)
     {
        $DataKoreksiUpah =  DataKoreksiUpah::selectRaw('
                    data_koreksi_upah.uuid,
                    data_koreksi_upah.kode_koreksi_upah,
                    data_koreksi_upah.tanggal_koreksi,
                    employee_atribut.enroll_id,
                    employee_atribut.nik,
                    employee_atribut.employee_name,
                    department_all.sub_dept_name,
                    department_all.department_name,
                    data_koreksi_upah.jumlah_rp_potongan,
                    data_koreksi_upah.periode_tanggal_koreksi,
                    data_koreksi_upah.operator,
                    data_koreksi_upah.keterangan,
                    data_koreksi_upah.created_at,
                    data_koreksi_upah.updated_at,
                    data_koreksi_upah.jenis_koreksi
                ')
                ->leftJoin('employee_atribut','data_koreksi_upah.enroll_id','=','employee_atribut.enroll_id')
                ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
                ->where('periode_tanggal_koreksi',$request->priode_payroll)
                ->where('jenis_koreksi',$request->jenis_koreksi_export)
                ->orderBy('data_koreksi_upah.updated_at','asc')
                ->get();


        $time=time() ;
        return Excel::download(new KoreksiUpahExport($DataKoreksiUpah),'KoreksiPotongan'.$time.'.xlsx');
     }

}
