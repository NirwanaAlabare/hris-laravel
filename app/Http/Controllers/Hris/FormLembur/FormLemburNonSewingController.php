<?php

namespace App\Http\Controllers\Hris\FormLembur;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\View;
use DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ExportSplSheet_non_sewing;
use App\Exports\ExportSpl_All;
use App\Exports\ExportSplNonSewing_All;
use App\Exports\ExportInsentifNonSewing_All;
use App\Exports\ExportSpl_Import;
use Illuminate\Support\Str;

class FormLemburNonSewingController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
    }
    public function index(Request $request)
    {
        $tgl_awal = $request->dateFrom;
        $tgl_akhir = $request->dateTo;

        $user = Auth::guard('admin')->user()->name;
        $user_email = Auth::guard('admin')->user()->email;
        // $dept=Auth::user()->department;
        $dept="Non Sewing";
        if ($request->ajax()) {
            $additionalQuery = '';

            if (request("employee_name")) {
                $noForms=DB::select('select mutnonsewingdet.no_form from employee_atribut inner join mut_karyawan_input_non_sewing_form_lembur_det mutnonsewingdet on mutnonsewingdet.enroll_id = employee_atribut.enroll_id inner join mut_karyawan_input_non_sewing_form_lembur mut_form_lembur on mutnonsewingdet.no_form=mut_form_lembur.no_form  where employee_atribut.employee_name LIKE "%'.request("employee_name").'%" or employee_atribut.enroll_id LIKE "%'.request("employee_name").'%" or mut_form_lembur.dept LIKE "%'.request("employee_name").'%" or mutnonsewingdet.keterangan LIKE "%'.request("employee_name").'%" or employee_atribut.status_jabatan LIKE "%'.request("employee_name").'%" group by mutnonsewingdet.no_form');
                $forms=[];
                foreach($noForms as $no){
                    $forms[]=$no->no_form;
                }
                $noFormString = "'" . implode("', '", $forms) ."'";
                $additionalQuery .= 'AND a.no_form in ('.$noFormString.')';
            }

            if($user=='HR' || $user=='IT' || $user_email == 'mega@ptnag.com' || $user_email == 'rudy@ptnag.com' || $user_email == 'fadli' || $user_email == 'indri@nag.nirwanaindonesia.com' || $user_email == 'ersa@ptnag.com'){
                $data_input = DB::select("
                select
                a.id,
                a.no_form,
                a.tgl_lembur,
                DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
                a.tgl_filter,
                DATE_FORMAT(tgl_filter, '%d-%m-%Y') tgl_filter_fix,
                dept,
                b.keterangan ket,
                count(b.enroll_id) jml_org,
                count(IF(b.status='PINJAMAN',1,null)) jml_org_pinjam,
                count(IF(b.status='-',1,null)) jml_org_ttp,
                count(IF(b.uuid_koreksi_upah!='',1,null)) jml_insentif
                from mut_karyawan_input_non_sewing_form_lembur a
                inner join mut_karyawan_input_non_sewing_form_lembur_det b on a.no_form = b.no_form
                where tgl_lembur >= '$tgl_awal' and tgl_lembur <= '$tgl_akhir' ".$additionalQuery."
                group by no_form
                order by tgl_lembur desc,dept asc
                ");
            }else if($user=='sophia' || $user=='Shopie'){
                $data_input = DB::select("
                select
                a.id,
                a.no_form,
                a.tgl_lembur,
                DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
                a.tgl_filter,
                DATE_FORMAT(tgl_filter, '%d-%m-%Y') tgl_filter_fix,
                dept,
                b.keterangan ket,
                count(b.enroll_id) jml_org,
                count(IF(b.status='PINJAMAN',1,null)) jml_org_pinjam,
                count(IF(b.status='-',1,null)) jml_org_ttp,
                count(IF(b.uuid_koreksi_upah!='',1,null)) jml_insentif
                from mut_karyawan_input_non_sewing_form_lembur a
                inner join mut_karyawan_input_non_sewing_form_lembur_det b on a.no_form = b.no_form
                where tgl_lembur >= '$tgl_awal' and tgl_lembur <= '$tgl_akhir' and (a.no_form like '%SEWING%' or a.no_form like '%MANDING%' or a.no_form like '%SPOTCLEANING%' or a.no_form like '%STEAM%' or a.no_form like '%ADMINPRODUKSI%' or a.no_form like '%MECHANIC%' or a.created_by='$user') ".$additionalQuery."
                group by no_form
                order by tgl_lembur desc,dept asc
                ");
            }else if($user=='sewing'){
                $data_input = DB::select("
                select
                a.id,
                a.no_form,
                a.tgl_lembur,
                DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
                a.tgl_filter,
                DATE_FORMAT(tgl_filter, '%d-%m-%Y') tgl_filter_fix,
                dept,
                b.keterangan ket,
                count(b.enroll_id) jml_org,
                count(IF(b.status='PINJAMAN',1,null)) jml_org_pinjam,
                count(IF(b.status='-',1,null)) jml_org_ttp,
                count(IF(b.uuid_koreksi_upah!='',1,null)) jml_insentif
                from mut_karyawan_input_non_sewing_form_lembur a
                inner join mut_karyawan_input_non_sewing_form_lembur_det b on a.no_form = b.no_form
                where tgl_lembur >= '$tgl_awal' and tgl_lembur <= '$tgl_akhir' and (a.no_form like '%SEWING%' or a.no_form like '%MANDING%' or a.created_by='$user') ".$additionalQuery."
                group by no_form
                order by tgl_lembur desc,dept asc
                ");
            }else if($dept=='FINANCE,ACCOUNTING&TAX'){
                $data_input = DB::select("
                select
                a.id,
                a.no_form,
                a.tgl_lembur,
                DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
                a.tgl_filter,
                DATE_FORMAT(tgl_filter, '%d-%m-%Y') tgl_filter_fix,
                dept,
                b.keterangan ket,
                count(b.enroll_id) jml_org,
                count(IF(b.status='PINJAMAN',1,null)) jml_org_pinjam,
                count(IF(b.status='-',1,null)) jml_org_ttp,
                count(IF(b.uuid_koreksi_upah!='',1,null)) jml_insentif
                from mut_karyawan_input_non_sewing_form_lembur a
                inner join mut_karyawan_input_non_sewing_form_lembur_det b on a.no_form = b.no_form
                where tgl_lembur >= '$tgl_awal' and tgl_lembur <= '$tgl_akhir' and (a.no_form like '%FINANCE,ACCOUNTING&TAX%' or a.created_by='$user') ".$additionalQuery."
                group by no_form
                order by tgl_lembur desc,dept asc
                ");
            }else{
                $data_input = DB::select("
                select
                a.id,
                a.no_form,
                a.tgl_lembur,
                DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
                a.tgl_filter,
                DATE_FORMAT(tgl_filter, '%d-%m-%Y') tgl_filter_fix,
                dept,
                b.keterangan ket,
                count(b.enroll_id) jml_org,
                count(IF(b.status='PINJAMAN',1,null)) jml_org_pinjam,
                count(IF(b.status='-',1,null)) jml_org_ttp,
                count(IF(b.uuid_koreksi_upah!='',1,null)) jml_insentif
                from mut_karyawan_input_non_sewing_form_lembur a
                inner join mut_karyawan_input_non_sewing_form_lembur_det b on a.no_form = b.no_form
                where a.created_by='$user' and tgl_lembur >= '$tgl_awal' and tgl_lembur <= '$tgl_akhir' ".$additionalQuery."
                group by no_form
                order by tgl_lembur desc,dept asc
                ");
            }

            return DataTables::of($data_input)->toJson();
        }
        return view('hris/mutasi-karyawan/form-lembur-non-sewing/form_lembur_non_sewing', [
            'page' => 'dashboard-mut-karyawan',
            "subPageGroup" => "proses-karyawan",
            "subPage" => "form-lembur-non-sewing",
            "user" => $user
        ], $this->data);
    }

    public function create(Request $request)
    {
        $tglskrg = date('Y-m-d');
        $user = Auth::guard('admin')->user()->name;
        $data_dept = DB::select("select
        d.sub_dept_id isi,
        concat(department_name,' - ', sub_dept_name) tampil
        from department_all d
        left join
        (select sub_dept_id,count(employee_id) tot from employee_atribut where status_aktif = 'aktif' group by sub_dept_id) e on d.sub_dept_id = e.sub_dept_id
        where site_nirwana_id = 'NAG'
        and sub_dept_name not like 'line%'
        and e.tot != '0'
        group by d.sub_dept_id
        order by department_name asc");

        // $sql_temp = DB::select("select * from mut_karyawan_input_form_lembur_tmp_det where created_by = '$user' group by created_by");
        // $cek_temp = $sql_temp ? $sql_temp[0]->enroll_id : null;

        return view('hris/mutasi-karyawan/form-lembur-non-sewing/create_form_lembur_non_sewing', [
            'page' => 'dashboard-mut-karyawan', "subPageGroup" => "proses-karyawan", "subPage" => "form-lembur-non-sewing",
            "data_dept" => $data_dept, "user" => $user
        ], $this->data);
    }

    public function show_list_karyawan_non_sewing(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $dept = $request->cbodept;
        $tgl_lembur = $request->tgl_lembur;
        if ($request->ajax()) {
            // DB::connection('mysql_hris')->select

            $data_tmp = DB::select("
            select
            a.enroll_id,
            e.employee_name,
            e.nik,
            e.status_jabatan,
            e.department_name,
            e.sub_dept_name,
            if (e.sub_dept_id = '$dept','-','PINJAMAN') status
            from mut_karyawan_input_non_sewing_form_lembur_det a
            inner join employee_atribut e on a.enroll_id = e.enroll_id
            left join
            (
                 select enroll_id from mut_karyawan_input_non_sewing_form_lembur a
                 inner join mut_karyawan_input_non_sewing_form_lembur_det b on a.no_form = b.no_form
                 where tgl_filter = '$tgl_lembur'
            ) cek_data on e.enroll_id = cek_data.enroll_id
            where e.sub_dept_id = '$dept' and cek_data.enroll_id is null
            union
            SELECT
            a.enroll_id,
            e.employee_name,
            e.nik,
            e.status_jabatan,
            e.department_name,
            e.sub_dept_name,
            if (e.sub_dept_id = '$dept','-','PINJAMAN') status
            FROM mut_karyawan_input_non_sewing_form_lembur_tmp_det a
            inner join employee_atribut e on a.enroll_id = e.enroll_id
            where created_by = '$user' and a.sub_dept_id = '$dept'
            ");

            return DataTables::of($data_tmp)->toJson();
        }
    }

    public function cek_data_karyawan_tmp_non_sewing(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $qrs = $request->txtqr;
        $qr = ltrim($qrs, '0');
        $dept = $request->cbodept;
        $tgl_lembur = $request->tgl_lembur;
        $sql_temp = DB::select("
        select
        a.enroll_id,
        b.enroll_id cek_lembur_non_sewing,
        c.enroll_id cek_lembur_sewing
        from
                (select enroll_id from employee_atribut where status_aktif = 'aktif') a
        left join
                (select enroll_id from mut_karyawan_input_non_sewing_form_lembur_det
                where status = '-') b on a.enroll_id = b.enroll_id
        left join
                (select enroll_id from mut_karyawan_input_form_lembur a
                left join mut_karyawan_input_form_lembur_det b on a.no_form = b.no_form
                where a.tgl_lembur = '$tgl_lembur') c on a.enroll_id = c.enroll_id
        left join
                (select enroll_id from mut_karyawan_input_non_sewing_form_lembur a
                left join mut_karyawan_input_non_sewing_form_lembur_det b on a.no_form = b.no_form
                where a.tgl_lembur = '$tgl_lembur') d on a.enroll_id = d.enroll_id
        left join
                (select enroll_id from mut_karyawan_input_non_sewing_form_lembur_tmp_det) e
                on a.enroll_id = e.enroll_id
        where c.enroll_id is null and d.enroll_id is null and e.enroll_id is null	and a.enroll_id = '$qr'
        ");
        $enroll = $sql_temp[0]->enroll_id;
        return [
            'cek' => $enroll,
        ];
    }

    public function store_data_karyawan_tmp_non_sewing(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $qr = $request->txtqr;
        $timestamp = Carbon::now();
        $dept = $request->cbodept;
        $insert_tmp =  DB::insert("
        insert into mut_karyawan_input_non_sewing_form_lembur_tmp_det(enroll_id,sub_dept_id,created_by,created_at,updated_at)
        values('$qr','$dept','$user','$timestamp','$timestamp')");
    }

    public function hapus_data_karyawan_tmp_non_sewing(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $id_tmp = $request->id_tmp;

        $insert_tmp =  DB::delete("
        delete from mut_karyawan_input_non_sewing_form_lembur_tmp_det where id_tmp = '$id_tmp'");
    }

    public function del_tmp_non_sewing(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $del_tmp =  DB::delete("
        delete from mut_karyawan_input_non_sewing_form_lembur_tmp_det where created_by = '$user'");
    }
    public function del_tmp_non_sewing_enroll_id(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $enroll_id = request()->enroll_id;
        $del_tmp =  DB::delete("
        delete from mut_karyawan_input_non_sewing_form_lembur_tmp_det where created_by = '$user' and enroll_id = '$enroll_id'");
    }

    public function del_karyawan_non_sewing(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $timestamp = Carbon::now();
        $enroll_id = $request->id;
        $no_form = $request->no_form;

        $del_tmp =  DB::delete("
        delete from mut_karyawan_input_non_sewing_form_lembur_det where enroll_id = '$enroll_id' and no_form = '$no_form'");

        $ins =  DB::insert("
        insert into mut_karyawan_input_form_lembur_tmp_det_log_del (enroll_id,no_form,created_by,created_at,updated_at)
        VALUES ('$enroll_id','$no_form','$user','$timestamp','$timestamp')");
    }

    public function show_list_karyawan_tmp_non_sewing(Request $request)
    {
        $user = $request->user;
        $dept = $request->dept;
        $tgl = $request->tgl;
        if ($request->ajax()) {
            $data_tmp = DB::select("
            SELECT * FROM mut_karyawan_input_non_sewing_form_lembur_tmp_det a
            inner join employee_atribut e on a.enroll_id = e.enroll_id
            left join master_data_absen_kehadiran m on a.enroll_id = m.enroll_id
            where created_by = '$user' and a.sub_dept_id= '$dept' and m.tanggal_berjalan = '$tgl'
            ");
            return DataTables::of($data_tmp)->toJson();
        }
    }
    public function get_time_from_id_data_lembur(){
        $no_form=request()->id;
        $sql_cek_nomor = DB::select("select * from mut_karyawan_input_non_sewing_form_lembur_det where no_form = '$no_form' group by jam_lembur_awal_rencana order by created_at limit 1");
        return $sql_cek_nomor;
    }
    public function store_tambahan_data_karyawan_lembur(){
        $timestamp = Carbon::now();
        $jam=request()->jam_awal_lembur;
        $no_form=request()->id_c;
        $dept_id=DB::select("select*from mut_karyawan_input_non_sewing_form_lembur where no_form='$no_form'");
        $department_name=$dept_id[0]->dept;
        $x=[];
        foreach($jam as $key=>$value){
            $x[]=[
                'enroll_id'=>request()->karyawan_lembur[$key],
                'jam_lembur_awal_rencana'=>request()->jam_awal_lembur[$key].':00',
                'jam_lembur_akhir_rencana'=>request()->jam_akhir_lembur[$key].':00',
                'jam_lembur_istirahat'=>request()->jam_istirahat_lembur[$key],
            ];
        }
        foreach($x as $key=>$value){
            $user = Auth::guard('admin')->user()->name;
            $enroll_id=$value['enroll_id'];
            $sub_department_name=DB::select("select sub_dept_name from employee_atribut where enroll_id='$enroll_id'");
            $status='-';
            if(!isset($sub_department_name[0]) || $sub_department_name[0]->sub_dept_name!=$department_name){
                $status='PINJAMAN';
            }
            $tidak_ada=DB::select("select*from mut_karyawan_input_non_sewing_form_lembur_det where enroll_id='$enroll_id' and no_form='$no_form'");
            $jam_awal=$value['jam_lembur_awal_rencana'];
            $jam_akhir=$value['jam_lembur_akhir_rencana'];
            $jam_istirahat=$value['jam_lembur_istirahat'];
            $konsumsi=1;
            if($jam_awal=='18:00' && $jam_awal<='17:00' && $jam_awal>='15:00'){
                $konsumsi=0;
            }
            if(!$tidak_ada){
                DB::insert("insert into mut_karyawan_input_non_sewing_form_lembur_det(no_form,enroll_id,jam_lembur_awal_rencana,jam_lembur_akhir_rencana,jam_lembur_istirahat,status,konsumsi,uuid_koreksi_upah,created_by,created_at,updated_at)
                values('$no_form','$enroll_id','$jam_awal','$jam_akhir','$jam_istirahat','$status','$konsumsi','','$user','$timestamp','$timestamp')");
            }
        }
    }
    public function cek_data_lembur(Request $request){
        $id=request()->id;
        $tgl_lembur=request()->tgl_lembur;
        $no_form=DB::select("select no_form from mut_karyawan_input_non_sewing_form_lembur_det where enroll_id='$id' and no_form in (select no_form from mut_karyawan_input_non_sewing_form_lembur where tgl_lembur = '$tgl_lembur')");
        return count($no_form);
    }
    public function export_excel_spl_all_non_sewing(Request $request){
        return Excel::download(new ExportSplNonSewing_All($request->from, $request->to), 'Laporan_Penerimaan FG_Stok.xlsx');
    }
    public function view_tambahan_data_karyawan_lembur(){
        $user = Auth::guard('admin')->user()->name;
        $enroll_id = request()->enroll_id;
        $no_form = request()->no_form;
        $dept=request()->dept;
        $tanggal_lembur=DB::select("select tgl_lembur from mut_karyawan_input_non_sewing_form_lembur where no_form='$no_form'")[0]->tgl_lembur;
        $form_lembur=DB::select("select no_form from mut_karyawan_input_non_sewing_form_lembur where tgl_lembur='$tanggal_lembur'");
        $no_form=[];
        foreach($form_lembur as $key=>$value){
            $no_form[]=$value->no_form;
        }
        $no_form_lembur_det_array=implode("', '",$no_form);

        $form_lembur_det=DB::select("select no_form from mut_karyawan_input_non_sewing_form_lembur_det where no_form in ('$no_form_lembur_det_array') and enroll_id='$enroll_id'");
        $form_lembur_det_tmp=DB::select("select * from mut_karyawan_input_non_sewing_form_lembur_tmp_det where enroll_id='$enroll_id' and created_by='$user'");
        if(!$form_lembur_det && !$form_lembur_det_tmp){
            $employee=DB::select("select a.enroll_id as enroll_id,a.employee_name as employee_name,a.nik as nik,a.status_jabatan as status_jabatan,a.sub_dept_name as sub_dept_name,a.sub_dept_name as sub_dept_name,m.absen_masuk_kerja as absen_in,m.enroll_id as id_absen,m.tanggal_berjalan as tanggal_berjalan,m.absen_masuk_kerja as absen_in,m.absen_pulang_kerja as absen_out from employee_atribut a left join (select*from master_data_absen_kehadiran where tanggal_berjalan='$tanggal_lembur')m on a.enroll_id=m.enroll_id where a.enroll_id = '$enroll_id'");

            if($employee){
                $enroll_id = $employee[0]->enroll_id;
                $timestamp = Carbon::now();
                $insert_tmp =  DB::insert("
                insert into mut_karyawan_input_non_sewing_form_lembur_tmp_det(enroll_id,sub_dept_id,created_by,created_at,updated_at)
                values('$enroll_id','$dept','$user','$timestamp','$timestamp')");
                return $employee;
            }else{
                return 'data tidak ada';
            }
        }else{
            return 'data sudah ada';
        }
    }
    public function store(Request $request)
    {
        $timestamp = Carbon::now();
        $user               = Auth::guard('admin')->user()->name;
        $tgl_lembur         = $request->tgl_lembur;
        $tgl_filter         = $request->tgl_filter;
        $dept               = $request->cbodept_name;
        $dept_fix           = str_replace(' ', '', $dept);
        $ket                = $request->txtket;
        // $ket                = strtoupper($request->txtket);

        $jam_lembur_awal    = $request->from_lembur;
        $jam_lembur_akhir   = $request->to_lembur;
        $istirahat   = $request->txtistirahat;

        $no = date('ymd_H:i');
        $tgl_pelaksana = date('dmY', strtotime($tgl_lembur));
        $kode = '_SPL_';
        $kode_trans = $no . $kode . $dept_fix . '_'  . $tgl_pelaksana;

        $keteranganArray    = $_POST['keterangan'];
        $JmlArray           = $_POST['cek_data'];
        $enroll_idArray     = $_POST['enroll_id'];
        $statusArray     = $_POST['status'];
        if (is_array($keteranganArray)) {
            $keteranganArray = array_map(function ($value) {
                $value = preg_replace("/[\r\n]+/", " ", $value);
                return trim($value);
            }, $keteranganArray);
        } else {
            $keteranganArray = preg_replace("/[\r\n]+/", " ", $keteranganArray);
            $keteranganArray = trim($keteranganArray);
        }

        if ($JmlArray != '') {

            $sql_cek_nomor = DB::select("select * from mut_karyawan_input_non_sewing_form_lembur where no_form = '$kode_trans'");
            $cek_nomor     = $sql_cek_nomor ? $sql_cek_nomor[0]->no_form : null;
            if ($cek_nomor == null) {
                $insert_bppb =  DB::insert("
            insert into mut_karyawan_input_non_sewing_form_lembur(no_form,tgl_filter,tgl_lembur,dept,approve,created_by,created_at,updated_at)
            values('$kode_trans','$tgl_filter','$tgl_lembur','$dept','N','$user','$timestamp','$timestamp')");
            }
            $konsumsi=1;
            if($jam_lembur_akhir=='18:00' && $jam_lembur_awal<='17:00' && $jam_lembur_awal>='15:00'){
                $konsumsi=0;
            }
            foreach ($JmlArray as $key => $value) {
                if ($value != '') {
                    $txtqty         = $JmlArray[$key];
                    $txtenroll      = $enroll_idArray[$key];
                    $txtstat         = $statusArray[$key];
                    $keterangan         = $keteranganArray[$key]; {
                        $insert_det =  DB::insert("
                    insert into mut_karyawan_input_non_sewing_form_lembur_det(no_form,enroll_id,jam_lembur_awal_rencana,jam_lembur_akhir_rencana,jam_lembur_istirahat,status,konsumsi,uuid_koreksi_upah,keterangan,created_by,created_at,updated_at)
                    values('$kode_trans','$txtenroll','$jam_lembur_awal','$jam_lembur_akhir','$istirahat','$txtstat','$konsumsi','','$keterangan','$user','$timestamp','$timestamp')");
                    }
                }
            }

            $delete_tmp =  DB::delete("
            delete from mut_karyawan_input_form_lembur_tmp_det where created_by = '$user'
            ");

            // return array(
            //     "status" => 200,
            //     "message" => 'No Transaksi :
            //      ' . $kode_trans . '
            //      Sudah Terbuat',
            //     "additional" => [],
            //     "redirect" => route('flns.index')
            // );
            return redirect()->route('flns.index')->with('success', 'Data berhasil disimpan.');

        } else {
            return array(
                "status" => 400,
                "message" => 'Tidak ada Data',
                "additional" => [],
            );
        }






        // if ($insert_bppb != '') {
        //     return array(
        //         "status" => 200,
        //         "message" => 'No Transaksi :
        //          ' . $kode_trans . '
        //          Sudah Terbuat',
        //         "additional" => [],
        //         "redirect" => 'reload'
        //     );
        // } else {
        //     return array(
        //         "status" => 400,
        //         "message" => 'Tidak ada Data',
        //         "additional" => [],
        //     );
        // }
    }

    public function getdatakaryawanspl_non_sewing(Request $request)
    {
        $no_form = $request->no_form;
        if ($request->ajax()) {

            $data_det_spl = DB::select("
            select
            tgl_lembur,
            DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
            a.no_form,
            dept,
            b.keterangan,
            b.enroll_id,
            b.id_det,
            e.nik,
            e.employee_name,
            e.status_jabatan,
            e.sub_dept_name,
            b.konsumsi,
            b.uuid_koreksi_upah,
            b.status,
            substr(m.absen_masuk_kerja,1,5) as absen_masuk_kerja,
            substr(m.absen_pulang_kerja,1,5) as absen_pulang_kerja,
            u.jumlah_rp_potongan as amount,
            date_FORMAT(jam_lembur_awal_rencana,'%H:%i')jam_lembur_awal_rencana,
            date_FORMAT(jam_lembur_akhir_rencana,'%H:%i')jam_lembur_akhir_rencana,
            date_FORMAT(SEC_TO_TIME(jam_lembur_istirahat*60),'%H:%i') istirahat,
            jam_lembur_istirahat,
            if (jam_lembur_awal_rencana < jam_lembur_akhir_rencana,
            date_FORMAT(SEC_TO_TIME((((TIMESTAMPDIFF(MINUTE,jam_lembur_awal_rencana,jam_lembur_akhir_rencana)) - jam_lembur_istirahat) * 60)),'%H:%i') ,
            date_format(sec_to_time((TIMESTAMPDIFF(minute,concat(tgl_lembur, ' ', jam_lembur_awal_rencana),concat(DATE_ADD(tgl_lembur, interval 1 day), ' ', jam_lembur_akhir_rencana)) - jam_lembur_istirahat) * 60),'%H:%i')
            ) total_jam
            from mut_karyawan_input_non_sewing_form_lembur a
            inner join mut_karyawan_input_non_sewing_form_lembur_det b on a.no_form = b.no_form
            left join (
            select * from master_data_absen_kehadiran where tanggal_berjalan IN (select tgl_lembur from mut_karyawan_input_non_sewing_form_lembur where no_form = '$no_form')
            ) m on b.enroll_id = m.enroll_id
            inner join employee_atribut e on b.enroll_id = e.enroll_id
            left join (select*from data_koreksi_upah) u on b.uuid_koreksi_upah=u.uuid
            where a.no_form = '$no_form'
            order by  employee_name asc
            ");

            return DataTables::of($data_det_spl)->toJson();
        }
    }
    public function cek_data_koreksi_upah(){
        $enroll_id=request()->id;
        $no_form=request()->no_form;
        $tgl_lembur=DB::select("select tgl_lembur from mut_karyawan_input_non_sewing_form_lembur where no_form='$no_form'")[0]->tgl_lembur;
        $query=DB::select("select * from mut_karyawan_input_non_sewing_form_lembur_det where enroll_id='$enroll_id' and no_form in(select no_form from mut_karyawan_input_non_sewing_form_lembur where tgl_lembur='$tgl_lembur') and uuid_koreksi_upah!=''");
        if($query){
            return 'not oke';
        }else{
            return 'oke';
        }
    }
    public function update_form_lembur_non_sewing(Request $request)
    {
        $timestamp = Carbon::now();
        $user = Auth::guard('admin')->user()->name;
        $JmlArray                                   = $_POST['enroll_id'];
        $jam_lembur_awal_rencanaArray               = $_POST['jam_lembur_awal_rencana'];
        $jam_lembur_akhir_rencanaArray              = $_POST['jam_lembur_akhir_rencana'];
        $istirahatArray                             = $_POST['istirahat'];
        $no_formArray                               = $_POST['no_form'];
        $txt_uuid                                   = $_POST['uuid_koreksi_upah'];
        $KonsumsiValue                              = $_POST['konsumsi_value'];
        $KeteranganValue                            = $_POST['keterangan'];
        if (is_array($KeteranganValue)) {
            $KeteranganValue = array_map(function ($value) {
                $value = preg_replace("/[\r\n]+/", " ", $value);
                return trim($value);
            }, $KeteranganValue);
        } else {
            $KeteranganValue = preg_replace("/[\r\n]+/", " ", $KeteranganValue);
            $KeteranganValue = trim($KeteranganValue);
        }
        $no_form=$_POST['no_form_modal_input'];
        $tgl_lembur = DB::select("select tgl_lembur from mut_karyawan_input_non_sewing_form_lembur where no_form = '$no_form'")[0];
        $tanggal_sekarang=$tgl_lembur->tgl_lembur;
        $bulan_sekarangs=substr($tanggal_sekarang,5,2);
        $tahun_sekarangs=substr($tanggal_sekarang,0,4);
        $bulan_sekarang=$tahun_sekarangs.'-'.$bulan_sekarangs.'-'.'26';
        $bulan_sebelum=date('Y-m-d',strtotime( "-1 month", strtotime( $bulan_sekarang ) ));
        $bulan_setelah=date('Y-m-d',strtotime( "+1 month", strtotime( $bulan_sekarang ) ));
        if($tanggal_sekarang>=$bulan_sekarang && $tanggal_sekarang<$bulan_setelah){
            $tanggal_awal=$bulan_sekarang;
        }else if($tanggal_sekarang>=$bulan_sebelum && $tanggal_sekarang<$bulan_sekarang){
            $tanggal_awal=$bulan_sebelum;
        }
        $tanggal_akhir=date('Y-m-'.'25',strtotime( "+1 month", strtotime( $tanggal_awal ) ));
        $periode_tanggal_koreksi= date('m/d/Y',strtotime($tanggal_awal)).' - '.date('m/d/Y',strtotime($tanggal_akhir));
        $insentif=[];
        $minutes=(int)date('is');
        $today=date('Ym').$minutes;
        $todayDate=date('Y-m-d');
        $no_form_modal                              = $request->no_form_modal_input;
        $txtket_modal_input                         = $request->txtket_modal_input;
        if(isset($_POST['award']) && isset($_POST['amounts'])){
            $award                                      = $_POST['award'];
            $amounts                                    = $_POST['amounts'];
            foreach ($JmlArray as $key => $value) {
                $nik=DB::select("select nik,employee_name,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name from employee_atribut where enroll_id = '$key'")[0];
                $timestamp = Carbon::now();
                $uuid = Str::uuid();
                $kode_koreksi_upah=$today.$nik->nik;
                $tanggal_koreksi=$todayDate;
                $enroll_id=$key;
                $niks=$nik->nik;
                $employee_name=$nik->employee_name;
                $site_nirwana_id=$nik->site_nirwana_id;
                $site_nirwana_name=$nik->site_nirwana_name;
                $department_id=$nik->department_id;
                $department_name=$nik->department_name;
                $sub_dept_id=$nik->sub_dept_id;
                $sub_dept_name=$nik->sub_dept_name;
                $periode_tanggal_koreksi=$periode_tanggal_koreksi;
                $keterangan='Insentif Reward Lembur';
                $operator='system';
                $uuid_koreksi=$txt_uuid[$key];
                $txtenroll                      = $value;
                $txtjam_lembur_awal_rencana     = $jam_lembur_awal_rencanaArray[$key];
                $txtjam_lembur_akhir_rencana    = $jam_lembur_akhir_rencanaArray[$key];
                $txtistirahat                   = $istirahatArray[$key];
                $txtno_form                     = $no_formArray[$key];
                $konsumsi                       = $KonsumsiValue[$key];
                $keterangan                     = $KeteranganValue[$key];
                $cek_koreksi=DB::select("select*from data_koreksi_upah where periode_tanggal_koreksi = '$periode_tanggal_koreksi' and enroll_id = '$txtenroll'");
                $cek_reward=DB::select("select*from mut_karyawan_input_non_sewing_form_lembur_det where id_det = '$uuid_koreksi' and uuid_koreksi_upah =''");
                if(isset($award[$value])){
                    if($amounts[$value]!=''){
                        $jumlah_rp_potongan=$amounts[$key];
                        if(!$cek_reward){
                            // $insert_tmp =  DB::insert("insert into data_koreksi_upah(uuid,kode_koreksi_upah,tanggal_koreksi,enroll_id,nik,employee_name,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,jumlah_rp_potongan,periode_tanggal_koreksi,keterangan,operator,created_at,updated_at) values ('$uuid','$kode_koreksi_upah','$tanggal_koreksi','$enroll_id','$niks','$employee_name','$site_nirwana_id','$site_nirwana_name','$department_id','$department_name','$sub_dept_id','$sub_dept_name','$jumlah_rp_potongan','$periode_tanggal_koreksi','$keterangan','$operator','$timestamp','$timestamp')");
                            DB::update("update mut_karyawan_input_non_sewing_form_lembur_det set
                                        jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                                        jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                                        jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi',
                                        uuid_koreksi_upah = '$jumlah_rp_potongan',keterangan='$keterangan' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                        }else{
                            if($cek_reward){
                                DB::update("update mut_karyawan_input_non_sewing_form_lembur_det set
                                        jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                                        jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                                        jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi',
                                        uuid_koreksi_upah = '$jumlah_rp_potongan',keterangan='$keterangan' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                                // $amount_before=DB::select("select jumlah_rp_potongan from data_koreksi_upah where periode_tanggal_koreksi = '$periode_tanggal_koreksi' and enroll_id = '$txtenroll'")[0]->jumlah_rp_potongan;
                                // $amount_after=$amount_before+$jumlah_rp_potongan;
                                // DB::update("update data_koreksi_upah set jumlah_rp_potongan = '$amount_after' where periode_tanggal_koreksi = '$periode_tanggal_koreksi' and enroll_id = '$txtenroll'");
                            }else{
                                // $amount_before=DB::select("select jumlah_rp_potongan from data_koreksi_upah where periode_tanggal_koreksi = '$periode_tanggal_koreksi' and enroll_id = '$txtenroll'")[0]->jumlah_rp_potongan;
                                // $amount_reduce=DB::select("select uuid_koreksi_upah from mut_karyawan_input_non_sewing_form_lembur_det where no_form = '$txtno_form' and enroll_id = '$txtenroll'")[0]->uuid_koreksi_upah;
                                // $amount_after=$amount_before-$amount_reduce;
                                // $amount_after2=$amount_after+$jumlah_rp_potongan;
                                // DB::update("update data_koreksi_upah set jumlah_rp_potongan = '$amount_after2' where periode_tanggal_koreksi = '$periode_tanggal_koreksi' and enroll_id = '$txtenroll'");
                                DB::update("update mut_karyawan_input_non_sewing_form_lembur_det set
                                        jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                                        jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                                        jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi',
                                        uuid_koreksi_upah = '$jumlah_rp_potongan',keterangan='$keterangan' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                            }
                        }
                    }else{
                        if(!$cek_reward){
                            // DB::delete("delete from data_koreksi_upah where uuid = '$uuid_koreksi'");
                            DB::update("update mut_karyawan_input_non_sewing_form_lembur_det set
                            jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                            jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                            jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi',
                            uuid_koreksi_upah = '',keterangan='$keterangan' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                        }else{
                            DB::update("update mut_karyawan_input_non_sewing_form_lembur_det set
                            jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                            jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                            jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi',keterangan='$keterangan' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                        }
                    }
                }else{
                    if(!$cek_reward){
                        // DB::delete("delete from data_koreksi_upah where uuid = '$uuid_koreksi'");
                        DB::update("update mut_karyawan_input_non_sewing_form_lembur_det set
                        jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                        jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                        jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi',
                        uuid_koreksi_upah = '',keterangan='$keterangan' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                    }else{
                        DB::update("update mut_karyawan_input_non_sewing_form_lembur_det set
                        jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                        jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                        jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi',keterangan='$keterangan' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                    }
                }

            }
        }else{
            foreach ($JmlArray as $key => $value) {
                $nik=DB::select("select nik,employee_name,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name from employee_atribut where enroll_id = '$key'")[0];
                $timestamp = Carbon::now();
                $uuid = Str::uuid();
                $kode_koreksi_upah=$today.$nik->nik;
                $tanggal_koreksi=$todayDate;
                $enroll_id=$key;
                $niks=$nik->nik;
                $employee_name=$nik->employee_name;
                $site_nirwana_id=$nik->site_nirwana_id;
                $site_nirwana_name=$nik->site_nirwana_name;
                $department_id=$nik->department_id;
                $department_name=$nik->department_name;
                $sub_dept_id=$nik->sub_dept_id;
                $sub_dept_name=$nik->sub_dept_name;
                $periode_tanggal_koreksi=$periode_tanggal_koreksi;
                $keterangan='Insentif';
                $operator='system';
                $uuid_koreksi=$txt_uuid[$key];
                $txtenroll                      = $value;
                $txtjam_lembur_awal_rencana     = $jam_lembur_awal_rencanaArray[$key];
                $txtjam_lembur_akhir_rencana    = $jam_lembur_akhir_rencanaArray[$key];
                $txtistirahat                   = $istirahatArray[$key];
                $txtno_form                     = $no_formArray[$key];
                $konsumsi                       = $KonsumsiValue[$key];
                $keterangan                     = $KeteranganValue[$key];
                $cek_koreksi=DB::select("select*from data_koreksi_upah where uuid='$uuid_koreksi'");
                $cek_reward=DB::select("select*from mut_karyawan_input_non_sewing_form_lembur_det where id_det = '$uuid_koreksi' and uuid_koreksi_upah =''");
                if(!$cek_reward){
                    // DB::delete("delete from data_koreksi_upah where uuid = '$uuid_koreksi'");
                    DB::update("update mut_karyawan_input_non_sewing_form_lembur_det set
                    jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                    jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                    jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi',
                    uuid_koreksi_upah = '',keterangan='$keterangan' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                }else{
                    DB::update("update mut_karyawan_input_non_sewing_form_lembur_det set
                    jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                    jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                    jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi',keterangan='$keterangan' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                }

            }
        }
        return array(
            "status" => 202,
            "message" => 'No Form Berhasil Di Update',
            "additional" => [],
            "redirect" => 'reload',
            "callback" => "getdetail(`$no_form_modal`,`$txtket_modal_input`)"

        );
    }
    public function export_pdf_non_sewing_insentif(){
        $no_form=request()->no_form;
        $sub_dept=DB::select("select dept from mut_karyawan_input_non_sewing_form_lembur where no_form = '$no_form'")[0]->dept;
        $dept=DB::select("select department_name from department_all where sub_dept_name = '$sub_dept'")[0]->department_name;
        $tgl_lembur=Carbon::parse(substr($no_form,-4).'-'.substr($no_form,-6,2).'-'.substr($no_form,-8,2))->translatedFormat('d F Y');
        $data = DB::select("select a.enroll_id,b.employee_name,a.uuid_koreksi_upah,a.keterangan ket,a.jam_lembur_awal_rencana,a.jam_lembur_akhir_rencana,m.absen_pulang_kerja from mut_karyawan_input_non_sewing_form_lembur_det a
                            inner join employee_atribut b on a.enroll_id=b.enroll_id
                            inner join mut_karyawan_input_non_sewing_form_lembur d on a.no_form=d.no_form
                            left join master_data_absen_kehadiran m on a.enroll_id=m.enroll_id and d.tgl_lembur=m.tanggal_berjalan
                            where a.no_form in ('$no_form') and a.uuid_koreksi_upah!='' order by employee_name asc");
        $date_now=Carbon::now()->translatedFormat('d F Y');
        $fileName=date('Ym').' Form Insentif '.' - '.substr_replace(substr($no_form,13),"",-9).' '.Carbon::parse(strtotime(substr($no_form,-4).'-'.substr($no_form,-6,2).'-'.substr($no_form,-8,2)))->translatedFormat('dmY');
        $pdf = PDF::loadView('hris.mutasi-karyawan.form-lembur-non-sewing.form_insentif_non_sewing',["data" => $data,"no_form"=>$no_form,"date_now"=>$date_now,"tgl_lembur"=>$tgl_lembur,"dept"=>$dept,"sub_dept"=>$sub_dept])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
        // Pdf::setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        // $pdf = Pdf::loadView('form-lembur-non-sewing.export-spl-pdf', [
        //     'data' => $data,
        //     'id' => $request->id,
        //     'dept' => $dept,
        //     'tgl_lembur' => $tgl_lembur,
        //     'no_form' => $no_form
        // ])->setPaper('f4', 'portrait');
        // return $pdf->download('spl.pdf');
    }
    public function getdept_name(Request $request)
    {
        $ket =  DB::select(
            "
            select concat(department_name, ' - ', sub_dept_name) dept_name, sub_dept_name from department_all where sub_dept_id = '" . $request->sub_dept_id . "'
        ",
        );
        if(isset($ket[0])){
            return json_encode($ket[0]);
        }
    }

    public function export_pdf_non_sewing_spl(Request $request){
        $master_dept = DB::select(
            "SELECT dept, tgl_lembur,no_form from mut_karyawan_input_non_sewing_form_lembur
            where id = '$request->id'"
        );
        $dept = $master_dept[0]->dept;
        $tgl_lembur = $master_dept[0]->tgl_lembur;
        $no_form = $master_dept[0]->no_form;
        $data = DB::select("
        select
        tgl_lembur,
        DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
        a.no_form,
        dept,
        b.keterangan,
        b.enroll_id,
        e.nik,
        e.employee_name,
        e.status_jabatan,
        date_FORMAT(jam_lembur_awal_rencana,'%H:%i')jam_lembur_awal_rencana,
        date_FORMAT(jam_lembur_akhir_rencana,'%H:%i')jam_lembur_akhir_rencana,
        substr(m.absen_masuk_kerja,1,5) as absen_masuk_kerja,
        substr(m.absen_pulang_kerja,1,5) as absen_pulang_kerja,
        b.jam_lembur_istirahat,
        date_FORMAT(SEC_TO_TIME(jam_lembur_istirahat*60),'%H:%i') istirahat,
        if (jam_lembur_awal_rencana < jam_lembur_akhir_rencana,
        date_FORMAT(SEC_TO_TIME((((TIMESTAMPDIFF(MINUTE,jam_lembur_awal_rencana,jam_lembur_akhir_rencana)) - jam_lembur_istirahat) * 60)),'%H:%i') ,
        date_format(sec_to_time((TIMESTAMPDIFF(minute,concat(tgl_lembur, ' ', jam_lembur_awal_rencana),concat(DATE_ADD(tgl_lembur, interval 1 day), ' ', jam_lembur_akhir_rencana)) - jam_lembur_istirahat) * 60),'%H:%i')
        ) total_jam
        from mut_karyawan_input_non_sewing_form_lembur a
        inner join mut_karyawan_input_non_sewing_form_lembur_det b on a.no_form = b.no_form
        inner join employee_atribut e on b.enroll_id = e.enroll_id
        left join (select * from master_data_absen_kehadiran where tanggal_berjalan = (select tgl_lembur from mut_karyawan_input_non_sewing_form_lembur where no_form = '$no_form')) m on b.enroll_id = m.enroll_id
        where a.id = '$request->id' and b.jam_lembur_awal_rencana!=b.jam_lembur_akhir_rencana
        order by  employee_name asc
        ");
        $date_now=Carbon::now()->translatedFormat('d F Y');
        $fileName=$no_form.'_'.date('His');
        $pdf = PDF::loadView('hris.mutasi-karyawan.form-lembur-non-sewing.export-spl-pdf',["data" => $data,"id"=>$request->id,"tgl_lembur"=>$tgl_lembur,"no_form"=>$no_form,"dept"=>$dept])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
    }
    public function export_spl_non_sewing(Request $request)
    {
        $master_dept = DB::select(
            "SELECT dept, tgl_lembur,no_form from mut_karyawan_input_non_sewing_form_lembur
            where id = '$request->id'"
        );
        $dept = $master_dept[0]->dept;
        $tgl_lembur = $master_dept[0]->tgl_lembur;
        $no_form = $master_dept[0]->no_form;
        $data = DB::select("
        select
        tgl_lembur,
        DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
        a.no_form,
        dept,
        b.keterangan,
        b.enroll_id,
        e.nik,
        e.employee_name,
        e.status_jabatan,
        date_FORMAT(jam_lembur_awal_rencana,'%H:%i')jam_lembur_awal_rencana,
        date_FORMAT(jam_lembur_akhir_rencana,'%H:%i')jam_lembur_akhir_rencana,
        substr(m.absen_masuk_kerja,1,5) as absen_masuk_kerja,
        substr(m.absen_pulang_kerja,1,5) as absen_pulang_kerja,
        b.jam_lembur_istirahat,
        date_FORMAT(SEC_TO_TIME(jam_lembur_istirahat*60),'%H:%i') istirahat,
        if (jam_lembur_awal_rencana < jam_lembur_akhir_rencana,
        date_FORMAT(SEC_TO_TIME((((TIMESTAMPDIFF(MINUTE,jam_lembur_awal_rencana,jam_lembur_akhir_rencana)) - jam_lembur_istirahat) * 60)),'%H:%i') ,
        date_format(sec_to_time((TIMESTAMPDIFF(minute,concat(tgl_lembur, ' ', jam_lembur_awal_rencana),concat(DATE_ADD(tgl_lembur, interval 1 day), ' ', jam_lembur_akhir_rencana)) - jam_lembur_istirahat) * 60),'%H:%i')
        ) total_jam
        from mut_karyawan_input_non_sewing_form_lembur a
        inner join mut_karyawan_input_non_sewing_form_lembur_det b on a.no_form = b.no_form
        inner join employee_atribut e on b.enroll_id = e.enroll_id
        left join (select * from master_data_absen_kehadiran where tanggal_berjalan = (select tgl_lembur from mut_karyawan_input_non_sewing_form_lembur where no_form = '$no_form')) m on b.enroll_id = m.enroll_id
        where a.id = '$request->id' and b.jam_lembur_awal_rencana!=b.jam_lembur_akhir_rencana
        order by  employee_name asc
        ");
        Pdf::setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = Pdf::loadView('form-lembur-non-sewing.export-spl-pdf', [
            'data' => $data,
            'id' => $request->id,
            'dept' => $dept,
            'tgl_lembur' => $tgl_lembur,
            'no_form' => $no_form
        ])->setPaper('f4', 'portrait');
        return $pdf->download('spl.pdf');
    }

    public function export_excel_spl_all(Request $request)
    {
        return Excel::download(new ExportSpl_All($request->from, $request->to), 'Laporan_Penerimaan FG_Stok.xlsx');
    }
    public function export_excel_insentif_non_sewing(Request $request)
    {
        return Excel::download(new ExportInsentifNonSewing_All($request->from, $request->to), 'Laporan_Penerimaan FG_Stok.xlsx');
    }
    public function export_excel_non_sewing_spl_all(Request $request)
    {
        return Excel::download(new ExportSplNonSewing_All($request->from, $request->to), 'Laporan_Penerimaan FG_Stok.xlsx');
    }

    public function export_spl_import(Request $request)
    {
        return Excel::download(new ExportSpl_Import($request->id), 'Laporan_Penerimaan FG_Stok.xlsx');
    }
}

