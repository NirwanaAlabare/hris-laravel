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
use App\Exports\ExportSplSheet;
use App\Exports\ExportSpl_All;
use App\Exports\ExportInsentifSewing_All;
use App\Exports\ExportSpl_Import;
use App\Exports\BiayaMakanKaryawan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class FormLemburSewingController extends AdminBaseController
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
        $dept= 'sewing';

        if ($request->ajax()) {
            $additionalQuery = '';

            if (request("employee_name")) {
                $noForms=DB::select('select mutsewingdet.no_form from employee_atribut inner join mut_karyawan_input_form_lembur_det mutsewingdet on mutsewingdet.enroll_id = employee_atribut.enroll_id inner join mut_karyawan_input_form_lembur mut_form_lembur on mutsewingdet.no_form=mut_form_lembur.no_form inner join mut_karyawan_input_form_lembur_det_ket mut_form_lembur_ket on mutsewingdet.no_form=mut_form_lembur_ket.no_form where employee_atribut.employee_name LIKE "%'.request("employee_name").'%" or employee_atribut.enroll_id LIKE "%'.request("employee_name").'%" or mut_form_lembur.line LIKE "%'.request("employee_name").'%" or mut_form_lembur_ket.ket LIKE "%'.request("employee_name").'%" or employee_atribut.status_jabatan LIKE "%'.request("employee_name").'%" group by mutsewingdet.no_form');
                $forms=[];
                foreach($noForms as $no){
                    $forms[]=$no->no_form;
                }
                $noFormString = "'" . implode("', '", $forms) ."'";
                $additionalQuery .= 'AND a.no_form in ('.$noFormString.')';
            }
            if($user=='HR' || $user=='IT' || $user=='sophia' || $user=='Shopie' || $user=='sewing' || $dept=='FINANCE,ACCOUNTING&TAX' || $user_email == 'mega@ptnag.com' || $user_email == 'rudy@ptnag.com' || $user_email == 'fadli'){
                $data_input = DB::select("
                SELECT
                a.id,
                a.no_form,
                a.tgl_lembur,
                DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
                a.tgl_filter,
                DATE_FORMAT(tgl_filter, '%d-%m-%Y') tgl_filter_fix,
                line,
                k.ket,
                count(b.enroll_id) jml_org,
                count(IF(b.status='PINJAMAN',1,null)) jml_org_pinjam,
                count(IF(b.status!='PINJAMAN',1,null)) jml_org_line,
                count(IF(b.uuid_koreksi_upah!='',1,null)) jml_insentif
                from mut_karyawan_input_form_lembur a
                inner join mut_karyawan_input_form_lembur_det b on a.no_form = b.no_form
                left join
                (
                    select no_form,group_concat(ket order by ket asc SEPARATOR ', ') ket from mut_karyawan_input_form_lembur_det_ket group by no_form
                ) k on a.no_form = k.no_form
                where tgl_lembur >= '$tgl_awal' and tgl_lembur <= '$tgl_akhir' ".$additionalQuery."
                group by no_form
                order by tgl_lembur desc,line asc
                ");
            }else{
                $data_input = DB::select("
                SELECT
                a.id,
                a.no_form,
                a.tgl_lembur,
                DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
                a.tgl_filter,
                DATE_FORMAT(tgl_filter, '%d-%m-%Y') tgl_filter_fix,
                line,
                k.ket,
                count(b.enroll_id) jml_org,
                count(IF(b.status='PINJAMAN',1,null)) jml_org_pinjam,
                count(IF(b.status!='PINJAMAN',1,null)) jml_org_line,
                count(IF(b.uuid_koreksi_upah!='',1,null)) jml_insentif
                from mut_karyawan_input_form_lembur a
                inner join mut_karyawan_input_form_lembur_det b on a.no_form = b.no_form
                left join
                (
                    select no_form,group_concat(ket order by ket asc SEPARATOR ', ') ket from mut_karyawan_input_form_lembur_det_ket group by no_form
                ) k on a.no_form = k.no_form
                where a.created_by='$user' and tgl_lembur >= '$tgl_awal' and tgl_lembur <= '$tgl_akhir' ".$additionalQuery."
                group by no_form
                order by tgl_lembur desc,line asc
                ");
            }

            return DataTables::of($data_input)->toJson();
        }


        return view('hris/mutasi-karyawan/form-lembur-sewing/form_lembur', [
            'page' => 'dashboard-mut-karyawan',
            'subPageGroup' => 'proses-karyawan',
            'subPage' => 'mut-karyawan',
            'user' => $user,
        ], $this->data);

    }
    public function export_excel_insentif(Request $request){
        return Excel::download(new ExportInsentifSewing_All($request->from, $request->to), 'Laporan_Penerimaan FG_Stok.xlsx');
    }
    public function create(Request $request)
    {
        $tglskrg = date('Y-m-d');
        $user = Auth::guard('admin')->user()->name;
        $data_line = DB::select("select line isi, line tampil from mut_karyawan_input
        group by line");
        $data_ket = DB::connection('mysql_sb')->select("select ac.kpno isi, ac.kpno tampil from master_plan mp
        inner join act_costing ac on mp.id_ws = ac.id
        where tgl_plan >= DATE('$tglskrg' - INTERVAL 7 DAY)
        group by id_ws
        order by ac.kpno asc");

        // $sql_temp = DB::select("select * from mut_karyawan_input_form_lembur_tmp_det where created_by = '$user' group by created_by");
        // $cek_temp = $sql_temp ? $sql_temp[0]->enroll_id : null;

        return view('hris/mutasi-karyawan/form-lembur-sewing/create_form_lembur', [
            'page' => 'dashboard-mut-karyawan', "subPageGroup" => "proses-karyawan", "subPage" => "form-lembur",
            "data_line" => $data_line, "data_ket" => $data_ket, "user" => $user
        ], $this->data);
    }


    public function cek_data_lembur(Request $request){
        $id=request()->id;
        $tgl_lembur=request()->tgl_lembur;
        $no_form=DB::select("select no_form from mut_karyawan_input_non_sewing_form_lembur_det where enroll_id='$id' and no_form in (select no_form from mut_karyawan_input_non_sewing_form_lembur where tgl_lembur = '$tgl_lembur')");
        return count($no_form);
    }
    public function show_list_karyawan(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $line = $request->cboline;
        $tgl_filter = $request->tgl_filter;
        $tgl_lembur = $request->tgl_lembur;
        if ($request->ajax()) {
            // DB::connection('mysql_hris')->select

            $data_tmp = DB::select("
            SELECT m.enroll_id, nm_karyawan,m.nik, e.status_jabatan, c.enroll_id cek_stat, line stat
						FROM
            (
            select max(id) id from mut_karyawan_input a
            where tgl_pindah = '$tgl_filter' and line = '$line'
            group by nik
            ) a
            inner join mut_karyawan_input m on a.id = m.id
            inner join employee_atribut e on m.enroll_id=e.enroll_id
            left join (
                select enroll_id, absen_masuk_kerja, absen_pulang_kerja
                from master_data_absen_kehadiran where tanggal_berjalan = '$tgl_filter'
            ) b on m.enroll_id = b.enroll_id
            left join
            (
                select enroll_id from mut_karyawan_input_form_lembur a
                inner join mut_karyawan_input_form_lembur_det b on a.no_form = b.no_form
                where a.tgl_lembur = '$tgl_lembur' and a.tgl_filter = '$tgl_filter'
            ) c on b.enroll_id = c.enroll_id
            left join
            (
                select enroll_id from mut_karyawan_input_non_sewing_form_lembur a
                left join mut_karyawan_input_non_sewing_form_lembur_det b on a.no_form = b.no_form
                where a.tgl_lembur = '$tgl_lembur'
            ) d on b.enroll_id = d.enroll_id
            where c.enroll_id is null and d.enroll_id is null
						UNION
						select tmp.enroll_id ,emp.employee_name, emp.nik, emp.status_jabatan,null cek_stat,'PINJAMAN' stat
						from mut_karyawan_input_form_lembur_tmp_det tmp
						inner join (
						select enroll_id, absen_masuk_kerja, absen_pulang_kerja
                from master_data_absen_kehadiran where tanggal_berjalan = '$tgl_filter'
						)m on tmp.enroll_id = m.enroll_id
                        inner join employee_atribut emp on emp.enroll_id=m.enroll_id
						where tgl_lembur = '$tgl_lembur' and line = '$line'
						order by CASE
               WHEN stat  = 'PINJAMAN'
                  THEN 1
               ELSE 0 END ASC, nm_karyawan asc
            ");

            return DataTables::of($data_tmp)->toJson();
        }
    }

    public function cek_data_karyawan_tmp(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $qrs = $request->txtqr;
        $qr = ltrim($qrs, '0');
        $line = $request->cboline;
        $tgl_lembur = $request->tgl_lembur;
        $tgl_filter = $request->tgl_filter;

        $sql_temp = DB::select("
        select
        a.enroll_id,
        a.enroll_id cek_absen,
        b.enroll_id cek_mutasi_karyawan,
        c.enroll_id cek_lembur_sewing,
        f.enroll_id cek_lembur_non_sewing
        from
        (select enroll_id,absen_masuk_kerja from master_data_absen_kehadiran where tanggal_berjalan = '$tgl_filter' and absen_masuk_kerja is not null) a
        left join
            (
						select enroll_id from (
        select max(id) id from mut_karyawan_input a
        where tgl_pindah = '$tgl_filter' and line = '$line'
        group by nik) mut
				left join mut_karyawan_input m on mut.id = m.id
            ) b on a.enroll_id = b.enroll_id
        left join (
        select enroll_id from mut_karyawan_input_form_lembur a
        inner join mut_karyawan_input_form_lembur_det b on a.no_form = b.no_form
        where a.tgl_lembur = '$tgl_lembur' and a.tgl_filter = '$tgl_filter'
        ) c on a.enroll_id = c.enroll_id
        left join (
        select enroll_id from mut_karyawan_input_non_sewing_form_lembur a
        inner join mut_karyawan_input_non_sewing_form_lembur_det b on a.no_form = b.no_form
        where tgl_lembur = '$tgl_lembur'
        ) f  on a.enroll_id = f.enroll_id
        where a.enroll_id is not null and b.enroll_id is null and c.enroll_id is null and f.enroll_id is null and a.enroll_id = '$qr'
        ");

        // SELECT m.enroll_id, nm_karyawan,m.nik, b.status_jabatan, c.enroll_id cek_stat, d.enroll_id cek_stat_tmp FROM
        // (
        // select max(id) id from mut_karyawan_input a
        // where tgl_pindah = '$tgl_filter' and line != '$line'
        // group by nik
        // ) a
        // inner join mut_karyawan_input m on a.id = m.id
        // left join (
        //     select enroll_id, nik, employee_id, employee_name, status_jabatan, absen_masuk_kerja,
        //     absen_masuk_kerja_real, absen_pulang_kerja, absen_pulang_kerja_real
        //     from master_data_absen_kehadiran where tanggal_berjalan = '$tgl_filter'
        // ) b on m.enroll_id = b.enroll_id
        //             left join
        //             (
        //             select enroll_id from mut_karyawan_input_form_lembur a
        //             inner join mut_karyawan_input_form_lembur_det b on a.no_form = b.no_form
        //             where a.tgl_lembur = '$tgl_lembur' and a.tgl_filter = '$tgl_filter'
        //             ) c on b.enroll_id = c.enroll_id
        //             left join
        //             (
        //             select * from mut_karyawan_input_form_lembur_tmp_det
        //             ) d on b.enroll_id = d.enroll_id
        //             where c.enroll_id is null and d.enroll_id is null and m.enroll_id = '$qr'

        $enroll = $sql_temp[0]->enroll_id;
        return [
            'cek' => $enroll,
        ];
    }

    public function store_data_karyawan_tmp(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $qr = $request->txtqr;
        $timestamp = Carbon::now();
        $line = $request->cboline;
        $tgl_lembur = $request->tgl_lembur;
        $tgl_filter = $request->tgl_filter;

        $insert_tmp =  DB::insert("
        insert into mut_karyawan_input_form_lembur_tmp_det(enroll_id,line,tgl_filter,tgl_lembur,created_by,created_at,updated_at)
        values('$qr','$line','$tgl_filter','$tgl_lembur','$user','$timestamp','$timestamp')");
    }

    public function hapus_data_karyawan_tmp(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $id_tmp = $request->id_tmp;

        $insert_tmp =  DB::delete("
        delete from mut_karyawan_input_form_lembur_tmp_det where id_tmp = '$id_tmp'");
    }

    public function del_tmp(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $del_tmp =  DB::delete("
        delete from mut_karyawan_input_form_lembur_tmp_det where created_by = '$user'");
    }

    public function del_karyawan(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $timestamp = Carbon::now();
        $enroll_id = $request->id;
        $no_form = $request->no_form;

        $del_tmp =  DB::delete("
        delete from mut_karyawan_input_form_lembur_det where enroll_id = '$enroll_id' and no_form = '$no_form'");

        $ins =  DB::insert("
        insert into mut_karyawan_input_form_lembur_tmp_det_log_del (enroll_id,no_form,created_by,created_at,updated_at)
        VALUES ('$enroll_id','$no_form','$user','$timestamp','$timestamp')");
    }

    public function show_list_karyawan_tmp(Request $request)
    {
        $user = $request->user;
        $line = $request->line;
        $tgl_lembur = $request->tgl_lembur;
        $tgl_filter = $request->tgl_filter;
        if ($request->ajax()) {

            $data_tmp = DB::select("
            select
            tmp.id_tmp,
            tmp.enroll_id,
            e.employee_name nm_karyawan,
            e.nik,
            e.status_jabatan,
            m.absen_masuk_kerja,
            m.absen_pulang_kerja,
            ifnull(mut.line,e.sub_dept_name) stat
            from
            (select * from mut_karyawan_input_form_lembur_tmp_det where created_by = '$user' and line = '$line') tmp
            left join (
            select * from employee_atribut e where status_aktif = 'aktif'
            ) e on tmp.enroll_id = e.enroll_id
            left join (
            select enroll_id, line from (
                            select max(id) id from mut_karyawan_input a
                    where tgl_pindah = '$tgl_filter'
                    group by nik) mut
                            left join mut_karyawan_input m on mut.id = m.id
            ) mut on tmp.enroll_id = mut.enroll_id
             left join (select * from master_data_absen_kehadiran)m on tmp.enroll_id=m.enroll_id where m.tanggal_berjalan='$tgl_lembur'
            ");
            return DataTables::of($data_tmp)->toJson();
        }

        // select mut.*,tmp.id_tmp from mut_karyawan_input_form_lembur_tmp_det tmp
        // inner join (
        //             SELECT m.enroll_id, nm_karyawan,m.nik, b.status_jabatan, line FROM
        //             (
        //             select max(id) id from mut_karyawan_input a
        //             where tgl_pindah = '$tgl_filter'
        //             group by nik
        //             ) a
        //             inner join mut_karyawan_input m on a.id = m.id
        //             left join (
        //                 select enroll_id, nik, employee_id, employee_name, status_jabatan, absen_masuk_kerja,
        //                 absen_masuk_kerja_real, absen_pulang_kerja, absen_pulang_kerja_real
        //                 from master_data_absen_kehadiran where tanggal_berjalan = '$tgl_filter'
        //             ) b on m.enroll_id = b.enroll_id
        // )	mut on tmp.enroll_id = mut.enroll_id
        // where tmp.created_by = '$user' and tmp.line = '$line'

    }
    public function view_tambahan_data_karyawan_lembur_sewing(){
        $user = Auth::guard('admin')->user()->name;
        $enroll_id = request()->enroll_id;
        $no_form = request()->no_form;
        $dept=request()->dept;
        $tanggal_lembur=DB::select("select tgl_lembur from mut_karyawan_input_form_lembur where no_form='$no_form'")[0]->tgl_lembur;
        $tanggal_filter=DB::select("select tgl_filter from mut_karyawan_input_form_lembur where no_form='$no_form'")[0]->tgl_filter;
        $form_lembur=DB::select("select no_form from mut_karyawan_input_form_lembur where tgl_lembur='$tanggal_lembur'");
        $no_form=[];
        foreach($form_lembur as $key=>$value){
            $no_form[]=$value->no_form;
        }
        $no_form_lembur_det_array=implode("', '",$no_form);

        $form_lembur_det=DB::select("select no_form from mut_karyawan_input_form_lembur_det where no_form in ('$no_form_lembur_det_array') and enroll_id='$enroll_id'");
        $form_lembur_det_tmp=DB::select("select * from mut_karyawan_input_form_lembur_tmp_det where enroll_id='$enroll_id' and created_by='$user'");
        if(!$form_lembur_det && !$form_lembur_det_tmp){
            $employee=DB::select("select a.enroll_id as enroll_id,a.employee_name as employee_name,a.nik as nik,a.status_jabatan as status_jabatan,a.sub_dept_name as sub_dept_name,a.sub_dept_name as sub_dept_name,m.absen_masuk_kerja as absen_in,m.enroll_id as id_absen,m.tanggal_berjalan as tanggal_berjalan,m.absen_masuk_kerja as absen_in,m.absen_pulang_kerja as absen_out from employee_atribut a left join (select*from master_data_absen_kehadiran where tanggal_berjalan='$tanggal_lembur')m on a.enroll_id=m.enroll_id where a.enroll_id = '$enroll_id'");

            if($employee){
                $enroll_id = $employee[0]->enroll_id;
                $timestamp = Carbon::now();
                $insert_tmp =  DB::insert("
                insert into mut_karyawan_input_form_lembur_tmp_det(enroll_id,line,tgl_filter,tgl_lembur,created_by,created_at,updated_at)
                values('$enroll_id','$dept','$tanggal_filter','$tanggal_lembur','$user','$timestamp','$timestamp')");
                return $employee;
            }else{
                return 'data tidak ada';
            }
        }else{
            return 'data sudah ada';
        }
    }
    public function del_tmp_non_sewing_enroll_id_sewing(){
        $user = Auth::guard('admin')->user()->name;
        $enroll_id = request()->enroll_id;
        $del_tmp =  DB::delete("
        delete from mut_karyawan_input_form_lembur_tmp_det where created_by = '$user' and enroll_id = '$enroll_id'");
    }
    public function store_tambahan_data_karyawan_lembur_sewing(){
        $timestamp = Carbon::now();
        $jam=request()->jam_awal_lembur;
        $no_form=request()->id_c;
        $dept_id=DB::select("select*from mut_karyawan_input_form_lembur where no_form='$no_form'");
        $department_name=$dept_id[0]->line;
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
            $tidak_ada=DB::select("select*from mut_karyawan_input_form_lembur_det where enroll_id='$enroll_id' and no_form='$no_form'");
            $jam_awal=$value['jam_lembur_awal_rencana'];
            $jam_akhir=$value['jam_lembur_akhir_rencana'];
            $jam_istirahat=$value['jam_lembur_istirahat'];
            $konsumsi=1;
            if($jam_awal=='18:00' && $jam_awal<='17:00' && $jam_awal>='15:00'){
                $konsumsi=0;
            }
            if(!$tidak_ada){
                DB::insert("insert into mut_karyawan_input_form_lembur_det(no_form,enroll_id,jam_lembur_awal_rencana,jam_lembur_akhir_rencana,jam_lembur_istirahat,status,konsumsi,uuid_koreksi_upah,created_by,created_at,updated_at)
                values('$no_form','$enroll_id','$jam_awal','$jam_akhir','$jam_istirahat','$status','$konsumsi','','$user','$timestamp','$timestamp')");
            }
        }
    }
    public function get_time_from_id_data_lembur_sewing(){
        $no_form=request()->id;
        $sql_cek_nomor = DB::select("select * from mut_karyawan_input_form_lembur_det where no_form = '$no_form' group by jam_lembur_awal_rencana order by created_at limit 1");
        return $sql_cek_nomor;
    }
    public function store(Request $request)
    {
        $timestamp = Carbon::now();
        $user               = Auth::guard('admin')->user()->name;
        $tgl_lembur         = $request->tgl_lembur;
        $tgl_filter         = $request->tgl_filter;
        $line               = $request->cboline;
        $line_fix           = str_replace(' ', '', $line);

        $jam_lembur_awal    = $request->from_lembur;
        $jam_lembur_akhir   = $request->to_lembur;
        $istirahat   = $request->txtistirahat;

        $no = date('ymd_H:i');
        $tgl_pelaksana = date('dmY', strtotime($tgl_lembur));
        $kode = '_SPL_';
        $kode_trans = $no . $kode . $line_fix . '_'  . $tgl_pelaksana;

        $JmlArray           = $_POST['cek_data'];
        $enroll_idArray     = $_POST['enroll_id'];
        $statArray          = $_POST['stat'];
        $JmlArrayKet        = $_POST['cboket'];

        if ($JmlArray != '') {

            $sql_cek_nomor = DB::select("select * from mut_karyawan_input_form_lembur where no_form = '$kode_trans'");
            $cek_nomor     = $sql_cek_nomor ? $sql_cek_nomor[0]->no_form : null;
            if ($cek_nomor == null) {
                $insert_bppb =  DB::insert("
            insert into mut_karyawan_input_form_lembur(no_form,tgl_filter,tgl_lembur,line,approve,created_by,created_at,updated_at)
            values('$kode_trans','$tgl_filter','$tgl_lembur','$line','N','$user','$timestamp','$timestamp')");
            }
            $konsumsi=1;
            if($jam_lembur_akhir=='18:00' && $jam_lembur_awal<='17:00' && $jam_lembur_awal>='15:00'){
                $konsumsi=0;
            }
            foreach ($JmlArray as $key => $value) {
                if ($value != '') {
                    $txtqty         = $JmlArray[$key];
                    $txtenroll      = $enroll_idArray[$key];
                    $txtstat        = $statArray[$key]; {
                        $insert_det =  DB::insert("
                    insert into mut_karyawan_input_form_lembur_det(no_form,enroll_id,jam_lembur_awal_rencana,jam_lembur_akhir_rencana,jam_lembur_istirahat,status,konsumsi,uuid_koreksi_upah,created_by,created_at,updated_at)
                    values('$kode_trans','$txtenroll','$jam_lembur_awal','$jam_lembur_akhir','$istirahat','$txtstat','$konsumsi','','$user','$timestamp','$timestamp')");
                    }
                }
            }

            if ($JmlArrayKet != '') {
                foreach ($JmlArrayKet as $key_ => $value_) {
                    if ($value_ != '') {

                        $txtket         = $JmlArrayKet[$key_]; {
                            $sql_cek_data = DB::select("select * from mut_karyawan_input_form_lembur_det_ket where no_form = '$kode_trans' and ket = '$txtket'");
                            $cek_data     = $sql_cek_data ? $sql_cek_data[0]->ket : null;
                            if ($cek_data == null) {
                                $insert_ket =  DB::insert("
                                insert into mut_karyawan_input_form_lembur_det_ket(no_form,ket)
                                values('$kode_trans','$txtket')");
                            }
                        }
                    }
                }
            }

            $delete_tmp =  DB::delete("
                            delete from mut_karyawan_input_form_lembur_tmp_det where created_by = '$user'
                            ");

            return array(
                "status" => 200,
                "message" => 'No Transaksi :
                 ' . $kode_trans . '
                 Sudah Terbuat',
                "additional" => [],
                "redirect" => 'reload'
            );
        } else {
            return array(
                "status" => 400,
                "message" => 'Tidak ada Data',
                "additional" => [],
            );
        }
    }

    public function getdatakaryawanspl(Request $request)
    {
        $no_form = $request->no_form;
        if ($request->ajax()) {

            $data_det_spl = DB::select("
            select
            tgl_lembur,
            DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
            a.no_form,
            line,
            k.ket,
            b.enroll_id,
            b.id_det,
            b.uuid_koreksi_upah,
            b.konsumsi,
            e.nik,
            e.employee_name,
            e.status_jabatan,
            substr(m.absen_masuk_kerja,1,5) as absen_masuk_kerja,
            substr(m.absen_pulang_kerja,1,5) as absen_pulang_kerja,
            u.jumlah_rp_potongan as amount,
            date_FORMAT(jam_lembur_awal_rencana,'%H:%i')jam_lembur_awal_rencana,
            date_FORMAT(jam_lembur_akhir_rencana,'%H:%i')jam_lembur_akhir_rencana,
            b.status,
            date_FORMAT(SEC_TO_TIME(jam_lembur_istirahat*60),'%H:%i') istirahat,
            jam_lembur_istirahat,
            if (jam_lembur_awal_rencana < jam_lembur_akhir_rencana,
            date_FORMAT(SEC_TO_TIME((((TIMESTAMPDIFF(MINUTE,jam_lembur_awal_rencana,jam_lembur_akhir_rencana)) - jam_lembur_istirahat) * 60)),'%H:%i') ,
            date_format(sec_to_time((TIMESTAMPDIFF(minute,concat(tgl_lembur, ' ', jam_lembur_awal_rencana),concat(DATE_ADD(tgl_lembur, interval 1 day), ' ', jam_lembur_akhir_rencana)) - jam_lembur_istirahat) * 60),'%H:%i')
            ) total_jam
            from mut_karyawan_input_form_lembur a
            inner join mut_karyawan_input_form_lembur_det b on a.no_form = b.no_form
            left join (
            select * from master_data_absen_kehadiran where tanggal_berjalan = (select tgl_lembur from mut_karyawan_input_form_lembur where no_form = '$no_form')
            ) m on b.enroll_id = m.enroll_id
            left join
            (
            select no_form,group_concat(ket order by ket asc SEPARATOR ', ') ket from mut_karyawan_input_form_lembur_det_ket group by no_form
            ) k on a.no_form = k.no_form
            inner join employee_atribut e on b.enroll_id = e.enroll_id
            left join (select*from data_koreksi_upah) u on b.uuid_koreksi_upah=u.uuid
            where a.no_form = '$no_form'
            order by  employee_name asc
            ");
            // date_FORMAT(SEC_TO_TIME((((TIMESTAMPDIFF(MINUTE,jam_lembur_awal_rencana,jam_lembur_akhir_rencana)) - jam_lembur_istirahat) * 60)),'%H:%i') total_jam

            return DataTables::of($data_det_spl)->toJson();
        }
    }
    public function cek_data_koreksi_upah_sewing(){
        $enroll_id=request()->id;
        $no_form=request()->no_form;
        $tgl_lembur=DB::select("select tgl_lembur from mut_karyawan_input_form_lembur where no_form='$no_form'")[0]->tgl_lembur;
        $query=DB::select("select * from mut_karyawan_input_form_lembur_det where enroll_id='$enroll_id' and no_form in(select no_form from mut_karyawan_input_form_lembur where tgl_lembur='$tgl_lembur') and uuid_koreksi_upah!=''");
        if($query){
            return 'not oke';
        }else{
            return 'oke';
        }
    }

    public function getket(Request $request)
    {
        $ket =  DB::select(
            "
            select no_form,group_concat(ket order by ket asc SEPARATOR ', ') ket
            from mut_karyawan_input_form_lembur_det_ket
            where no_form = '" . $request->no_form . "'
            group by no_form
        ",
        );
        return json_encode($ket[0]);
    }

    public function update_form_lembur(Request $request)
    {
        $timestamp = Carbon::now();
        $user = Auth::guard('admin')->user()->name;
        $JmlArray                                   = $_POST['enroll_id'];
        $jam_lembur_awal_rencanaArray               = $_POST['jam_lembur_awal_rencana'];
        $jam_lembur_akhir_rencanaArray              = $_POST['jam_lembur_akhir_rencana'];
        $jam_lembur_istirahatArray                  = $_POST['jam_lembur_istirahat'];
        $no_formArray                               = $_POST['no_form'];
        $txt_uuid                                   = $_POST['uuid_koreksi_upah'];
        $KonsumsiValue                              = $_POST['konsumsi_value'];
        $no_form_modal                              = $request->no_form_modal_input;
        $tgl_lembur = DB::select("select tgl_lembur from mut_karyawan_input_form_lembur where no_form = '$no_form_modal'")[0];
        $tanggal_sekarang=$tgl_lembur->tgl_lembur;
        $bulan_sekarangs=substr($tanggal_sekarang,5,2);
        $tahun_sekarangs=substr($tanggal_sekarang,0,4);
        $bulan_sekarang=$tahun_sekarangs.'-'.$bulan_sekarangs.'-'.'26';
        $bulan_sekarang=date('Y-'.$bulan_sekarangs.'-'.'26');
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
                $txtistirahat                   = $jam_lembur_istirahatArray[$key];
                $txtno_form                     = $no_formArray[$key];
                $konsumsi                       = $KonsumsiValue[$key];
                $cek_koreksi=DB::select("select*from data_koreksi_upah where uuid='$uuid_koreksi'");
                $cek_reward=DB::select("select*from mut_karyawan_input_form_lembur_det where id_det = '$uuid_koreksi' and uuid_koreksi_upah =''");
                if(isset($award[$value])){
                    if($amounts[$value]!=''){
                        $jumlah_rp_potongan=$amounts[$key];
                        if(!$cek_reward){
                            // $insert_tmp =  DB::update("update data_koreksi_upah set tanggal_koreksi = '$tanggal_koreksi', jumlah_rp_potongan = '$jumlah_rp_potongan' where uuid = '$uuid_koreksi'");
                            DB::update("update mut_karyawan_input_form_lembur_det set
                            jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                            jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                            jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi',
                            uuid_koreksi_upah = '$jumlah_rp_potongan' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                        }else{
                            // $insert_tmp =  DB::insert("insert into data_koreksi_upah(uuid,kode_koreksi_upah,tanggal_koreksi,enroll_id,nik,employee_name,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,jumlah_rp_potongan,periode_tanggal_koreksi,keterangan,operator,created_at,updated_at) values ('$uuid','$kode_koreksi_upah','$tanggal_koreksi','$enroll_id','$niks','$employee_name','$site_nirwana_id','$site_nirwana_name','$department_id','$department_name','$sub_dept_id','$sub_dept_name','$jumlah_rp_potongan','$periode_tanggal_koreksi','$keterangan','$operator','$timestamp','$timestamp')");
                            DB::update("update mut_karyawan_input_form_lembur_det set
                            jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                            jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                            jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi',
                            uuid_koreksi_upah = '$jumlah_rp_potongan' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                        }
                    }else{
                        if(!$cek_reward){
                            // DB::delete("delete from data_koreksi_upah where uuid = '$uuid_koreksi'");
                            DB::update("update mut_karyawan_input_form_lembur_det set
                            jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                            jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                            jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi',
                            uuid_koreksi_upah = '' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                        }else{
                            DB::update("update mut_karyawan_input_form_lembur_det set
                            jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                            jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                            jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi',
                            uuid_koreksi_upah = '' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                        }
                    }
                }else{
                    if(!$cek_reward){
                        // DB::delete("delete from data_koreksi_upah where uuid = '$uuid_koreksi'");
                        DB::update("update mut_karyawan_input_form_lembur_det set
                        jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                        jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                        jam_lembur_istirahat = '$txtistirahat',
                        uuid_koreksi_upah = '', konsumsi = '$konsumsi' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                    }else{
                        DB::update("update mut_karyawan_input_form_lembur_det set
                        jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                        jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                        jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
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
                $txtistirahat                   = $jam_lembur_istirahatArray[$key];
                $txtno_form                     = $no_formArray[$key];
                $txtno_form                     = $no_formArray[$key];
                $konsumsi                       = $KonsumsiValue[$key];
                $cek_koreksi=DB::select("select*from data_koreksi_upah where uuid='$uuid_koreksi'");
                $cek_reward=DB::select("select*from mut_karyawan_input_form_lembur_det where id_det = '$uuid_koreksi' and uuid_koreksi_upah =''");
                if(!$cek_reward){
                    // DB::delete("delete from data_koreksi_upah where uuid = '$uuid_koreksi'");
                    DB::update("update mut_karyawan_input_form_lembur_det set
                    jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                    jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                    jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi',
                    uuid_koreksi_upah = '' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                }else{
                    DB::update("update mut_karyawan_input_form_lembur_det set
                    jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                    jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                    jam_lembur_istirahat = '$txtistirahat', konsumsi = '$konsumsi' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                }
            }
        }

        return array(
            "status" => 202,
            "message" => 'No Form Berhasil Di Update',
            "additional" => [],
            "redirect" => 'reload',
            "callback" => "getdetail(`$no_form_modal`)"

        );
        // "callback" => "getdetail(`$no_form_modal`,`$txtket_modal`)"
    }
    public function export_pdf_insentif(){
        $no_form=request()->no_form;
        $sub_dept=DB::select("select line from mut_karyawan_input_form_lembur where no_form = '$no_form'")[0]->line;
        $dept=DB::select("select department_name from department_all where site_nirwana_id='NAG' and sub_dept_name = '$sub_dept'")[0]->department_name;
        $tgl_lembur=Carbon::parse(substr($no_form,-4).'-'.substr($no_form,-6,2).'-'.substr($no_form,-8,2))->translatedFormat('d F Y');
        $data = DB::select("select a.enroll_id,b.employee_name,a.uuid_koreksi_upah,d.ket,a.jam_lembur_awal_rencana,m.absen_pulang_kerja from mut_karyawan_input_form_lembur_det a
        inner join employee_atribut b on a.enroll_id=b.enroll_id
        inner join (select*from mut_karyawan_input_form_lembur_det_ket where no_form='$no_form' group by no_form)d on a.no_form=d.no_form
        inner join mut_karyawan_input_form_lembur e on a.no_form=e.no_form
        left join master_data_absen_kehadiran m on a.enroll_id=m.enroll_id and e.tgl_lembur=m.tanggal_berjalan
        where a.no_form='$no_form' and a.uuid_koreksi_upah!=''");
        $date_now=Carbon::now()->translatedFormat('d F Y');
        $fileName=date('Ym').' Form Insentif '.' - '.substr_replace(substr($no_form,13),"",-9).' '.Carbon::parse(strtotime(substr($no_form,-4).'-'.substr($no_form,-6,2).'-'.substr($no_form,-8,2)))->translatedFormat('dmY');
        $pdf = PDF::loadView('hris.mutasi-karyawan.form-lembur-sewing.form_insentif_sewing',["data" => $data,"no_form"=>$no_form,"date_now"=>$date_now,"tgl_lembur"=>$tgl_lembur,"dept"=>$dept,"sub_dept"=>$sub_dept])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
    }
    public function export_pdf_sewing_spl(Request $request){
        $master_line = DB::select("SELECT line, tgl_lembur, no_form FROM mut_karyawan_input_form_lembur WHERE id = '$request->id'");

        $line = $master_line[0]->line;
        $tgl_lembur = $master_line[0]->tgl_lembur;
        $no_form = $master_line[0]->no_form;
        $data = DB::select("
            select
            tgl_lembur,
            DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
            a.no_form,
            line,
            k.ket,
            b.enroll_id,
            e.nik,
            e.employee_name,
            e.status_jabatan,
            date_FORMAT(jam_lembur_awal_rencana,'%H:%i')jam_lembur_awal_rencana,
            date_FORMAT(jam_lembur_akhir_rencana,'%H:%i')jam_lembur_akhir_rencana,
            b.jam_lembur_istirahat,
            b.status,
            date_FORMAT(SEC_TO_TIME(jam_lembur_istirahat*60),'%H:%i') istirahat,
            date_FORMAT(SEC_TO_TIME((((TIMESTAMPDIFF(MINUTE,jam_lembur_awal_rencana,jam_lembur_akhir_rencana)) - jam_lembur_istirahat) * 60)),'%H:%i') total_jam,
            date_FORMAT(m.absen_masuk_kerja,'%H:%i')absen_masuk_kerja,
            date_FORMAT(m.absen_pulang_kerja,'%H:%i')absen_pulang_kerja,
            date_format(timediff(m.absen_pulang_kerja,m.absen_masuk_kerja),'%H:%i') realisasi_lembur
            from mut_karyawan_input_form_lembur a
            inner join mut_karyawan_input_form_lembur_det b on a.no_form = b.no_form
            left join (select * from master_data_absen_kehadiran where tanggal_berjalan = (select tgl_lembur from mut_karyawan_input_form_lembur where no_form = '$no_form')) m on b.enroll_id = m.enroll_id
            left join (select no_form,group_concat(ket order by ket asc SEPARATOR ', ') ket from mut_karyawan_input_form_lembur_det_ket group by no_form) k on a.no_form = k.no_form
            inner join employee_atribut e on b.enroll_id = e.enroll_id
            where a.id = '$request->id' and b.jam_lembur_awal_rencana!=b.jam_lembur_akhir_rencana
            order by  employee_name asc
        ");
        $fileName=$no_form.'_'.date('His');
        $pdf = PDF::loadView('hris.mutasi-karyawan.form-lembur-sewing.export-spl-pdf',["data" => $data,"no_form"=>$no_form,"tgl_lembur"=>$tgl_lembur,"line"=>$line])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
    }
    public function update_form_lembur_2(Request $request)
    {
        $timestamp = Carbon::now();
        $user = Auth::guard('admin')->user()->name;
        $JmlArray                                   = $_POST['enroll_id'];
        $jam_lembur_awal_rencanaArray               = $_POST['jam_lembur_awal_rencana'];
        $jam_lembur_akhir_rencanaArray              = $_POST['jam_lembur_akhir_rencana'];
        $jam_lembur_istirahatArray                  = $_POST['jam_lembur_istirahat'];
        $no_formArray                               = $_POST['no_form'];
        $txt_uuid                                   = $_POST['uuid_koreksi_upah'];
        $no_form_modal                              = $request->no_form_modal_input;

        $tgl_lembur = DB::select("select tgl_lembur from mut_karyawan_input_form_lembur where no_form = '$no_form_modal'")[0];
        $tanggal_sekarang=$tgl_lembur->tgl_lembur;
        $bulan_sekarang=date('Y-m-'.'26');
        $bulan_sebelum=date('Y-m-d',strtotime( "-1 month", strtotime( $bulan_sekarang ) ));
        $bulan_setelah=date('Y-m-d',strtotime( "+1 month", strtotime( $bulan_sekarang ) ));
        if($tanggal_sekarang>$bulan_sebelum && $tanggal_sekarang<=$bulan_sekarang){
            $tanggal_awal=$bulan_sebelum;
        }else if($tanggal_sekarang>$bulan_sekarang && $tanggal_sekarang<=$bulan_setelah){
            $tanggal_awal=$bulan_sekarang;
        }
        $tanggal_akhir=date('Y-m-'.'25',strtotime( "+1 month", strtotime( $tanggal_awal ) ));
        $periode_tanggal_koreksi= date('m/d/Y',strtotime($tanggal_awal)).' - '.date('m/d/Y',strtotime($tanggal_akhir));
        $insentif=[];
        $minutes=(int)date('is');
        $today=date('Ym').$minutes;
        $todayDate=date('Y-m-d');

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
                $keterangan='Insentif';
                $operator='system';
                $uuid_koreksi=$txt_uuid[$key];
                $txtenroll                      = $value;
                $txtjam_lembur_awal_rencana     = $jam_lembur_awal_rencanaArray[$key];
                $txtjam_lembur_akhir_rencana    = $jam_lembur_akhir_rencanaArray[$key];
                $txtistirahat                   = $jam_lembur_istirahatArray[$key];
                $txtno_form                     = $no_formArray[$key];
                if(isset($award[$value])){
                    if($amounts[$value]!=''){
                        $jumlah_rp_potongan=$amounts[$key];
                        DB::update("update mut_karyawan_input_form_lembur_det set
                                    jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                                    jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                                    jam_lembur_istirahat = '$txtistirahat',uuid_koreksi_upah='$jumlah_rp_potongan',amount='$jumlah_rp_potongan where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                    }else{
                        DB::update("update mut_karyawan_input_non_sewing_form_lembur_det set
                        jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                        jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                        jam_lembur_istirahat = '$txtistirahat',
                        uuid_koreksi_upah = '',amount=0 where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                    }
                }else{
                    // DB::delete("delete from data_koreksi_upah where uuid = '$uuid_koreksi'");
                    // DB::update("update mut_karyawan_input_non_sewing_form_lembur_det set
                    // jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                    // jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                    // jam_lembur_istirahat = '$txtistirahat',
                    // uuid_koreksi_upah = '',amount=0 where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
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
                $txtistirahat                   = $jam_lembur_istirahatArray[$key];
                $txtno_form                     = $no_formArray[$key];
                DB::update("update mut_karyawan_input_form_lembur_det set
                jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                jam_lembur_istirahat = '$txtistirahat',
                uuid_koreksi_upah = '',amount=0 where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
            }
        }

        return array(
            "status" => 202,
            "message" => 'No Form Berhasil Di Update',
            "additional" => [],
            "redirect" => 'reload',
            "callback" => "getdetail(`$no_form_modal`)"

        );
        // "callback" => "getdetail(`$no_form_modal`,`$txtket_modal`)"
    }

    public function update_form_lembur_3(Request $request)
    {
        $timestamp = Carbon::now();
        $user = Auth::guard('admin')->user()->name;
        $JmlArray                                   = $_POST['enroll_id'];
        $jam_lembur_awal_rencanaArray               = $_POST['jam_lembur_awal_rencana'];
        $jam_lembur_akhir_rencanaArray              = $_POST['jam_lembur_akhir_rencana'];
        $jam_lembur_istirahatArray                  = $_POST['jam_lembur_istirahat'];
        $no_formArray                               = $_POST['no_form'];
        $txt_uuid                                   = $_POST['uuid_koreksi_upah'];
        $no_form_modal                              = $request->no_form_modal_input;

        $tgl_lembur = DB::select("select tgl_lembur from mut_karyawan_input_form_lembur where no_form = '$no_form_modal'")[0];
        $tanggal_sekarang=$tgl_lembur->tgl_lembur;
        $bulan_sekarang=date('Y-m-'.'26');
        $bulan_sebelum=date('Y-m-d',strtotime( "-1 month", strtotime( $bulan_sekarang ) ));
        $bulan_setelah=date('Y-m-d',strtotime( "+1 month", strtotime( $bulan_sekarang ) ));
        if($tanggal_sekarang>$bulan_sebelum && $tanggal_sekarang<=$bulan_sekarang){
            $tanggal_awal=$bulan_sebelum;
        }else if($tanggal_sekarang>$bulan_sekarang && $tanggal_sekarang<=$bulan_setelah){
            $tanggal_awal=$bulan_sekarang;
        }
        $tanggal_akhir=date('Y-m-'.'25',strtotime( "+1 month", strtotime( $tanggal_awal ) ));
        $periode_tanggal_koreksi= date('m/d/Y',strtotime($tanggal_awal)).' - '.date('m/d/Y',strtotime($tanggal_akhir));
        $insentif=[];
        $minutes=(int)date('is');
        $today=date('Ym').$minutes;
        $todayDate=date('Y-m-d');

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
                $keterangan='Insentif';
                $operator='system';
                $uuid_koreksi=$txt_uuid[$key];
                $txtenroll                      = $value;
                $txtjam_lembur_awal_rencana     = $jam_lembur_awal_rencanaArray[$key];
                $txtjam_lembur_akhir_rencana    = $jam_lembur_akhir_rencanaArray[$key];
                $txtistirahat                   = $jam_lembur_istirahatArray[$key];
                $txtno_form                     = $no_formArray[$key];
                $cek_koreksi=DB::select("select*from data_koreksi_upah where uuid='$uuid_koreksi'");
                if(isset($award[$value])){
                    if($amounts[$value]!=''){
                        $jumlah_rp_potongan=$amounts[$key];
                        if($cek_koreksi){
                            // $insert_tmp =  DB::update("update data_koreksi_upah set tanggal_koreksi = '$tanggal_koreksi', jumlah_rp_potongan = '$jumlah_rp_potongan' where uuid = '$uuid_koreksi'");
                            DB::update("update mut_karyawan_input_form_lembur_det set
                                        jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                                        jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                                        jam_lembur_istirahat = '$txtistirahat' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                        }else{
                            // $insert_tmp =  DB::insert("insert into data_koreksi_upah(uuid,kode_koreksi_upah,tanggal_koreksi,enroll_id,nik,employee_name,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,jumlah_rp_potongan,periode_tanggal_koreksi,keterangan,operator,created_at,updated_at) values ('$uuid','$kode_koreksi_upah','$tanggal_koreksi','$enroll_id','$niks','$employee_name','$site_nirwana_id','$site_nirwana_name','$department_id','$department_name','$sub_dept_id','$sub_dept_name','$jumlah_rp_potongan','$periode_tanggal_koreksi','$keterangan','$operator','$timestamp','$timestamp')");
                            DB::update("update mut_karyawan_input_form_lembur_det set
                                        jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                                        jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                                        jam_lembur_istirahat = '$txtistirahat',
                                        uuid_koreksi_upah = '$uuid' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                        }
                    }else{
                        if($cek_koreksi){
                            DB::delete("delete from data_koreksi_upah where uuid = '$uuid_koreksi'");
                            DB::update("update mut_karyawan_input_non_sewing_form_lembur_det set
                            jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                            jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                            jam_lembur_istirahat = '$txtistirahat',
                            uuid_koreksi_upah = '' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                        }else{
                            DB::update("update mut_karyawan_input_non_sewing_form_lembur_det set
                            jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                            jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                            jam_lembur_istirahat = '$txtistirahat' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                        }
                    }
                }else{
                    if($cek_koreksi){
                        DB::delete("delete from data_koreksi_upah where uuid = '$uuid_koreksi'");
                        DB::update("update mut_karyawan_input_form_lembur_det set
                        jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                        jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                        jam_lembur_istirahat = '$txtistirahat',
                        uuid_koreksi_upah = '' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                    }else{
                        DB::update("update mut_karyawan_input_form_lembur_det set
                        jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                        jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                        jam_lembur_istirahat = '$txtistirahat' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
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
                $txtistirahat                   = $jam_lembur_istirahatArray[$key];
                $txtno_form                     = $no_formArray[$key];
                $cek_koreksi=DB::select("select*from data_koreksi_upah where uuid='$uuid_koreksi'");
                if($cek_koreksi){
                    DB::delete("delete from data_koreksi_upah where uuid = '$uuid_koreksi'");
                    DB::update("update mut_karyawan_input_form_lembur_det set
                    jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                    jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                    jam_lembur_istirahat = '$txtistirahat',
                    uuid_koreksi_upah = '' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                }else{
                    DB::update("update mut_karyawan_input_form_lembur_det set
                    jam_lembur_awal_rencana = '$txtjam_lembur_awal_rencana',
                    jam_lembur_akhir_rencana = '$txtjam_lembur_akhir_rencana',
                    jam_lembur_istirahat = '$txtistirahat' where no_form = '$txtno_form' and enroll_id = '$txtenroll'");
                }
            }
        }

        return array(
            "status" => 202,
            "message" => 'No Form Berhasil Di Update',
            "additional" => [],
            "redirect" => 'reload',
            "callback" => "getdetail(`$no_form_modal`)"

        );
        // "callback" => "getdetail(`$no_form_modal`,`$txtket_modal`)"
    }
    public function export_spl(Request $request)
    {
        $master_line = DB::select("SELECT line, tgl_lembur, no_form FROM mut_karyawan_input_form_lembur WHERE id = '$request->id'");

        $line = $master_line[0]->line;
        $tgl_lembur = $master_line[0]->tgl_lembur;
        $no_form = $master_line[0]->no_form;
        $data = DB::select("
            select
            tgl_lembur,
            DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
            a.no_form,
            line,
            k.ket,
            b.enroll_id,
            e.nik,
            e.employee_name,
            e.status_jabatan,
            date_FORMAT(jam_lembur_awal_rencana,'%H:%i')jam_lembur_awal_rencana,
            date_FORMAT(jam_lembur_akhir_rencana,'%H:%i')jam_lembur_akhir_rencana,
            b.jam_lembur_istirahat,
            b.status,
            date_FORMAT(SEC_TO_TIME(jam_lembur_istirahat*60),'%H:%i') istirahat,
            date_FORMAT(SEC_TO_TIME((((TIMESTAMPDIFF(MINUTE,jam_lembur_awal_rencana,jam_lembur_akhir_rencana)) - jam_lembur_istirahat) * 60)),'%H:%i') total_jam,
            date_FORMAT(m.absen_masuk_kerja,'%H:%i')absen_masuk_kerja,
            date_FORMAT(m.absen_pulang_kerja,'%H:%i')absen_pulang_kerja,
            date_format(timediff(m.absen_pulang_kerja,m.absen_masuk_kerja),'%H:%i') realisasi_lembur
            from mut_karyawan_input_form_lembur a
            inner join mut_karyawan_input_form_lembur_det b on a.no_form = b.no_form
            left join (select * from master_data_absen_kehadiran where tanggal_berjalan = (select tgl_lembur from mut_karyawan_input_form_lembur where no_form = '$no_form')) m on b.enroll_id = m.enroll_id
            left join (select no_form,group_concat(ket order by ket asc SEPARATOR ', ') ket from mut_karyawan_input_form_lembur_det_ket group by no_form) k on a.no_form = k.no_form
            inner join employee_atribut e on b.enroll_id = e.enroll_id
            where a.id = '$request->id' and b.jam_lembur_awal_rencana!=b.jam_lembur_akhir_rencana
            order by  employee_name asc
        ");

        Pdf::setOption(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isPhpEnabled' => true]);
        $pdf = Pdf::loadView('form-lembur.export-spl-pdf', [
            'data' => $data,
            'id' => $request->id,
            'line' => $line,
            'tgl_lembur' => $tgl_lembur,
            'no_form' => $no_form
        ])->setPaper('f4', 'portrait');
        return $pdf->download('spl.pdf');
        // return Excel::download(new ExportSpl($data,$request->id), 'Laporan_SPL.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    public function export_excel_spl_all(Request $request)
    {
        return Excel::download(new ExportSpl_All($request->from, $request->to), 'Laporan_Penerimaan FG_Stok.xlsx');
    }
    public function export_excel_konsumsi(Request $request){
        return Excel::download(new BiayaMakanKaryawan($request->from,$request->to), 'Laporan_Penerimaan FG_Stok.xlsx');
    }
    public function export_spl_import(Request $request)
    {
        return Excel::download(new ExportSpl_Import($request->id), 'Laporan_Penerimaan FG_Stok.xlsx');
    }
}
