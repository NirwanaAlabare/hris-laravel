<?php

namespace App\Http\Controllers\Hris\MutasiKaryawan;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use App\Models\EmployeeAtributHistory;
use App\Models\MutKaryawan;
use Carbon\Carbon;
use Dompdf\Options;
use Dompdf\FontMetrics;
use App\Models\EmployeeAtribut;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Exports\ExportLineSheet;
use App\Exports\ExportLaporanMutasi;
use App\Exports\ExportLaporanMutasiKaryawan;

class MutasiKaryawanController extends AdminBaseController
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
        return View::make('hris/mutasi-karyawan/dashboard',compact('selectEmployee','selectNoKTP'), $this->data);
    }
    public function mutasi_karyawan(Request $request){
        $tgl_skrg = Carbon::now()->isoFormat('D MMMM Y hh:mm:ss');
        $tglskrg = date('Y-m-d');
        $type = Auth::guard('admin')->user()->type;
        $username=Auth::guard('admin')->user()->name;
        if ($request->ajax()) {
            $additionalQuery = '';

            if (request("employee_name")) {
                $line_skrg=DB::select('select line from mut_karyawan_input where tgl_pindah ="'.$tglskrg.'" and nm_karyawan like "%'.request("employee_name").'%" or enroll_id like "%'.request("employee_name").'%" or line like "%'.request("employee_name").'%" or enroll_id like "%'.request("employee_name").'%" or nik like "%'.request("employee_name").'%"');
                $lines=[];
                foreach($line_skrg as $no){
                    $lines[]=$no->line;
                }
                $lineString = "'" . implode("', '", $lines) ."'";
                $additionalQuery .= ' AND b.line in ('.$lineString.')';
            }
            $data_line = DB::select("
            SELECT
            b.tgl_pindah,
            line,
            count(b.id) tot_orang,
            cast(right(line,2) as UNSIGNED) urutan,
            count(IF(mk.absen_masuk_kerja is null,1,null)) tot_absen,
            count(b.id)  - count(IF(mk.absen_masuk_kerja is null,1,null)) selisih
            from
                (
                select max(id) id from mut_karyawan_input a where tgl_pindah = '" . $tglskrg . "'
                group by nik
                )a
            inner join mut_karyawan_input b on a.id = b.id
            left join
                (
                select enroll_id, absen_masuk_kerja,status_absen from master_data_absen_kehadiran where tanggal_berjalan = '" . $tglskrg . "'
                ) mk on b.enroll_id = mk.enroll_id
            where b.id is not null".$additionalQuery."
            group by line
            order by cast(right(line,2) as UNSIGNED) asc
            ");

            return DataTables::of($data_line)->toJson();
        }

        $data_line = DB::select("select line isi, line tampil from mut_karyawan_input group by line
        order by line asc");

        $data_karyawan = DB::select("select enroll_id isi, concat(enroll_id,' - ', employee_name) tampil from
        employee_atribut
        where status_aktif = 'aktif' and department_name = 'sewing'
        order by employee_name asc");

        return view('hris/mutasi-karyawan/mutasi_karyawan', [
            'page' => 'dashboard-mut-karyawan',
            'subPageGroup' => 'proses-karyawan',
            'subPage' => 'mut-karyawan',
            'data_line' => $data_line,
            'data_karyawan' => $data_karyawan,
            'type' => $type,
            'username'=>$username
        ], $this->data);
    }
    public function line_dashboard()
    {
        $tglskrg = date('Y-m-d');
        $data_line =  DB::select("
        SELECT
        line,
        count(b.id) tot_orang,
        cast(right(line,2) as UNSIGNED) urutan,
        COUNT(IF(ea.sewing_nonsewing = 'SEWING',1,NULL)) jumlah_sewing,
		COUNT(IF(ea.sewing_nonsewing = 'NON SEWING',1,NULL)) jumlah_non_sewing
        from
        (
        select max(id) id from mut_karyawan_input a where tgl_pindah = '" . $tglskrg . "'
        group by nik
        )a
        inner join mut_karyawan_input b on a.id = b.id
        left join
        (
        select enroll_id, absen_masuk_kerja,status_absen from master_data_absen_kehadiran where tanggal_berjalan = '" . $tglskrg . "'
        ) mk on b.enroll_id = mk.enroll_id
        inner join employee_atribut ea on b.enroll_id = ea.enroll_id
        group by line
        order by cast(right(line,2) as UNSIGNED) asc
            ");


        return json_encode($data_line);
    }

    public function get_mutasi_list(Request $request){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '10240000000000000M');

        $tgl_skrg = Carbon::now()->isoFormat('D MMMM Y hh:mm:ss');
        $tglskrg = date('Y-m-d');
            $additionalQuery = '';

            if (request("employee_name")) {
                $line_skrg=DB::select('select line from mut_karyawan_input where tgl_pindah ="'.$tglskrg.'" and nm_karyawan like "%'.request("employee_name").'%" or enroll_id like "%'.request("employee_name").'%" or line like "%'.request("employee_name").'%" or enroll_id like "%'.request("employee_name").'%" or nik like "%'.request("employee_name").'%"');
                $lines=[];
                foreach($line_skrg as $no){
                    $lines[]=$no->line;
                }
                $lineString = "'" . implode("', '", $lines) ."'";
                $additionalQuery .= ' AND b.line in ('.$lineString.')';
            }
            $data_line = DB::select("
                SELECT
                b.tgl_pindah,
                line,
                count(b.id) tot_orang,
                cast(right(line,2) as UNSIGNED) urutan,
                count(IF(mk.absen_masuk_kerja is null,1,null)) tot_absen,
                count(b.id)  - count(IF(mk.absen_masuk_kerja is null,1,null)) selisih
                from
                    (
                    select max(id) id from mut_karyawan_input a where tgl_pindah = '" . $tglskrg . "'
                    group by nik
                    )a
                inner join mut_karyawan_input b on a.id = b.id
                left join
                    (
                    select enroll_id, absen_masuk_kerja,status_absen from master_data_absen_kehadiran where tanggal_berjalan = '" . $tglskrg . "'
                    ) mk on b.enroll_id = mk.enroll_id
                where b.id is not null".$additionalQuery."
                group by line
                order by cast(right(line,2) as UNSIGNED) asc
            ");

        return DataTables::of($data_line)->toJson();
    }

    public function export_line(Request $request, $line = 0)
    {
        return Excel::download(new ExportLineSheet($line), 'Laporan_Line.xlsx');
    }

    public function export_excel()
    {

        $from = request()->param1;
        $to = request()->param2;
        return Excel::download(new ExportLaporanMutasi($from, $to), 'Laporan_Mutasi_Karyawan.xlsx');
    }

    public function export_excel_mut_karyawan(Request $request)
    {
        $from = request()->param1;
        $to = request()->param2;
        return Excel::download(new ExportLaporanMutasiKaryawan($from, $to), 'Laporan_Mutasi_Karyawan.xlsx');
    }

    public function getdatalinekaryawan(Request $request)
    {
        $tglskrg = date('Y-m-d');

        $det_karyawan_line =  DB::select("
        SELECT
        a.id,
        line,
				b.enroll_id,
				e.nama_karyawan,
				e.nik,
                b.line_asal,
				e.status_jabatan,
				absen_masuk_kerja,
				status_absen,
				DATE_FORMAT(tgl_pindah, '%d-%m-%Y') tgl_pindah_fix,
				DATE_FORMAT(b.updated_at, '%d-%m-%Y %H:%i:%s') tgl_update_fix
        from
        (
        select max(id) id from mut_karyawan_input a where tgl_pindah = '$tglskrg'
        group by nik
        )a
        inner join mut_karyawan_input b on a.id = b.id
        left join
        (
        select enroll_id,absen_masuk_kerja,status_absen from master_data_absen_kehadiran where tanggal_berjalan = '$tglskrg'
        ) mk on b.enroll_id = mk.enroll_id
        left join (select enroll_id id_karyawan,employee_name nama_karyawan,status_jabatan,nik from employee_atribut) e on b.enroll_id=e.id_karyawan
				where b.line = '" . $request->nm_line . "'
				order by e.nama_karyawan asc
        ");
        return DataTables::of($det_karyawan_line)->toJson();
    }


    public function store_add_non_qr(Request $request)
    {
        $user = Auth::user()->name;
        $timestamp = Carbon::now();
        $tgl_pindah = date('Y-m-d');
        $validatedRequest = $request->validate([
            "cboline" => "required",
            "cboqr" => "required",
        ]);

        $master_karyawan = DB::select(
            "select enroll_id,ifnull(nik,nik_new) nik, employee_name from employee_atribut
            where enroll_id ='" . $validatedRequest['cboqr'] . "' and status_aktif = 'AKTIF'",
        );

        $nik = $master_karyawan[0]->nik;
        $nm_karyawan = $master_karyawan[0]->employee_name;

        $line_asal =  DB::select("
        select line,nik,enroll_id, nm_karyawan from (
            select a.id, b.tgl_pindah,b.enroll_id,b.nik,b.nm_karyawan,b.line from
            (select max(id) id from mut_karyawan_input a
            group by enroll_id)a
            inner join mut_karyawan_input b on a.id = b.id
            ) master_karyawan
        where enroll_id ='" . $validatedRequest['cboqr'] . "'
        ");

        $line_asal_data = $line_asal ? $line_asal[0]->line : null;

        // $cek_data = DB::select("
        // select sd.color from so_det sd
        // where id = '" . $validatedRequest['cboproduct'] . "'
        // ");

        // $color = $cek_data[0]->color;

        $insert_tmp = DB::insert("
            insert into mut_karyawan_input
            (tgl_pindah,enroll_id,nik,nm_karyawan,line,line_asal,created_at,updated_at,status)
            values
            (
                '$tgl_pindah',
                '" . $validatedRequest['cboqr'] . "',
                '$nik',
                '$nm_karyawan',
                '" . $validatedRequest['cboline'] . "',
                '$line_asal_data',
                '$timestamp',
                '$timestamp',
                'NON QR'
            )
            ");

        $enroll_id=$validatedRequest['cboqr'];
        $nm_line=$validatedRequest['cboline'];
        $sub_dept_id=DB::select("select sub_dept_id from department_all where sub_dept_name='$nm_line' and site_nirwana_id='NAG'")[0]->sub_dept_id;
        $department_id=DB::select("select department_id from department_all where sub_dept_id='".$sub_dept_id."' and site_nirwana_id='NAG'")[0]->department_id;
        $department_name=DB::select("select department_name from department_all where sub_dept_id='".$sub_dept_id."' and site_nirwana_id='NAG'")[0]->department_name;
        if($department_name=='SEWING'){
            $master_karyawan = DB::select(
                "select enroll_id,ifnull(nik,nik_new) nik, employee_name, sub_dept_name, department_id from employee_atribut
                where enroll_id ='" . $enroll_id . "' and status_aktif = 'AKTIF'",
            );
            if($master_karyawan[0]->department_id =='DEP20'){
                DB::update("update employee_atribut set sub_dept_name='$nm_line',sub_dept_id='$sub_dept_id' where enroll_id='$enroll_id'");
                $employeeUpdate=DB::select("select*from employee_atribut where enroll_id ='$enroll_id'");
                foreach($employeeUpdate as $value){
                    $tanggal_hari_ini=date('Y-m-d');
                    $bulan_hari_ini=substr($tanggal_hari_ini,0,8).'26';
                    $bulan_sebelum_hari_ini=date('Y-m-d',strtotime( "-1 month", strtotime( $bulan_hari_ini ) ));
                    $bulan_setelah_hari_ini=date('Y-m-d',strtotime( "+1 month", strtotime( $bulan_hari_ini ) ));
                    if($tanggal_hari_ini>=$bulan_sebelum_hari_ini && $tanggal_hari_ini<$bulan_hari_ini){
                        $tanggal_awal_hari_ini=$bulan_sebelum_hari_ini;
                    }else if($tanggal_hari_ini>=$bulan_hari_ini && $tanggal_hari_ini<$bulan_setelah_hari_ini){
                        $tanggal_awal_hari_ini=$bulan_hari_ini;
                    }else{
                        $tanggal_awal_hari_ini='';
                    }
                    $tanggal_akhir_hari_ini=date('Y-m-25',strtotime("+1 month",strtotime($tanggal_awal_hari_ini)));
                    $periode_payroll_hari_ini=$tanggal_awal_hari_ini.' s/d '.$tanggal_akhir_hari_ini;
                    $countEmpHistory=EmployeeAtributHistory::where('enroll_id',$enroll_id)->where('periode_payroll',$periode_payroll_hari_ini)->count();
                    if($countEmpHistory>0){
                        EmployeeAtributHistory::where('enroll_id',$enroll_id)->where('periode_payroll',$periode_payroll_hari_ini)->update([
                            'tanggal_dirubah'=>$tanggal_hari_ini,
                            'sub_dept_id'=>$sub_dept_id,
                            'sub_dept_name'=>$nm_line
                        ]);
                    }else{
                        EmployeeAtributHistory::create([
                            'enroll_id'=>$value->enroll_id,
                            'tanggal_dirubah'=>$tanggal_hari_ini,
                            'periode_payroll'=>$periode_payroll_hari_ini,
                            'employee_id' => $value->employee_id,
                            'employee_name' => $value->employee_name,
                            'jenis_kelamin' => $value->jenis_kelamin,
                            'tempat_lahir' => $value->tempat_lahir,
                            'tanggal_lahir' => $value->tanggal_lahir,
                            'golongan_darah' => $value->golongan_darah,
                            'email' => $value->email,
                            'nomor_tlpn' => $value->nomor_tlpn,
                            'agama' => $value->agama,
                            'status_kawin' => $value->status_kawin,
                            'npwp' => $value->npwp,
                            'nomor_ktp' => $value->nomor_ktp,
                            'nomor_kk' => $value->nomor_kk,
                            'pendidikan_terakhir' => $value->pendidikan_terakhir,
                            'jurusan_pendidikan' => $value->jurusan_pendidikan,
                            'nama_bank' => $value->nama_bank,
                            'nomor_rekening_bank' => $value->nomor_rekening_bank,
                            'ibu_kandung' => $value->ibu_kandung,
                            'propinsi' => $value->propinsi,
                            'kota_kab' => $value->kota_kab,
                            'kecamatan' => $value->kecamatan,
                            'kelurahan_desa' => $value->kelurahan_desa,
                            'alamat_rumah' => $value->alamat_rumah,
                            'alamat_sementara' => $value->alamat_sementara,
                            'site_nirwana_id' => $value->site_nirwana_id,
                            'site_nirwana_name' => $value->site_nirwana_name,
                            'department_id' => $value->department_id,
                            'department_name' => $value->department_name,
                            'sub_dept_id' => $value->sub_dept_id,
                            'sub_dept_name' => $value->sub_dept_name,
                            'sewing_nonsewing'=>$value->sewing_nonsewing,
                            'direct_indirect'=>$value->direct_indirect,
                            'enroll_id' => $value->enroll_id,
                            'join_date' => $value->join_date,
                            'nik' => $value->nik,
                            'status_aktif' => $value->status_aktif,
                            'status_jabatan' => $value->status_jabatan,
                            'status_kontrak_tetap' => $value->status_kontrak_tetap,
                            'status_staff' => $value->status_staff,
                            'tanggal_resign' => $value->tanggal_resign,
                            'sebab_resign' => $value->sebab_resign,
                            'tunjangan' => $value->tunjangan,
                            'kode_grade' => $value->kode_grade,
                            'referensi' => $value->referensi,
                            'employee_name_atasan' => $value->employee_name_atasan,
                            'status_aktif_bpjs_tk' => $value->status_aktif_bpjs_tk,
                            'tanggal_bpjs_ketenagakerjaan' => $value->tanggal_bpjs_ketenagakerjaan,
                            'nomor_bpjs_ketenagakerjaan' => $value->nomor_bpjs_ketenagakerjaan,
                            'status_aktif_bpjs_ks' => $value->status_aktif_bpjs_ks,
                            'tanggal_bpjs_kesehatan' => $value->tanggal_bpjs_kesehatan,
                            'nomor_bpjs_kesehatan' => $value->nomor_bpjs_kesehatan,
                            'premi' => $value->premi,
                            'pengalaman_bekerja' => $value->pengalaman_bekerja,
                            'nama_kerabat' => $value->nama_kerabat,
                            'nomor_tlpn_kerabat' => $value->nomor_tlpn_kerabat,
                            'hubungan_kerabat' => $value->hubungan_kerabat,
                            'alamat_kerabat' => $value->alamat_kerabat,
                            'tanggal_vaccine1' => $value->tanggal_vaccine1,
                            'nama_vaksin1' => $value->nama_vaksin1,
                            'tanggal_vaccine2' => $value->tanggal_vaccine2,
                            'nama_vaksin2' => $value->nama_vaksin2,
                            'golongan_sim' => $value->golongan_sim,
                            'nomor_sim' => $value->nomor_sim,
                            'tanggal_expire_sim' => $value->tanggal_expire_sim,
                            'catatan' => $value->catatan,
                            'no_surat'=>$value->no_surat,
                            'lokasi_foto' => $value->lokasi_foto,
                            'operator' => $value->operator,
                            'tanggal_mulai_kontrak' => $value->tanggal_mulai_kontrak,
                            'tanggal_akhir_kontrak' => $value->tanggal_akhir_kontrak,
                            'catatan_kontrak' => $value->catatan_kontrak
                        ]);
                    }
                }
            }
        }

        if ($insert_tmp) {
            return array(
                'icon' => 'benar',
                'msg' => 'Data Berhasil Ditambahkan',
            );
        } else {
            return array(
                'icon' => 'salah',
                'msg' => 'Tidak ada yang ditambahkan',
            );
        }
    }

    public function getdatakaryawan_nonqr(Request $request)
    {
        $tglskrg = date('Y-m-d');
        $data_karyawan_nonqr =  DB::select("
        SELECT
        m.*,
        DATE_FORMAT(tgl_pindah, '%d-%m-%Y') tgl_pindah_fix,
				absen_masuk_kerja,
				DATE_FORMAT(a.updated_at, '%d-%m-%Y %H:%i:%s') tgl_update_fix
        from
        mut_karyawan_input m
				inner join
				(select * from master_data_absen_kehadiran where tanggal_berjalan = '$tglskrg') a on m.enroll_id = a.enroll_id
				where status = 'NON QR' and tgl_pindah = '$tglskrg'
        ");
        return DataTables::of($data_karyawan_nonqr)->toJson();
    }

    public function delete_mutasi(){
        MutKaryawan::where('id',request()->id)->delete();
    }

    public function create_mut_karyawan()
    {
        $bagian_sewing=DB::select('select*from department_all where department_id="DEP20" and site_nirwana_id="NAG"');
        $departments=DB::select('select * from department_all where site_nirwana_id="NAG" and status="AKTIF" order by sub_dept_name');
        $username=Auth::guard('admin')->user()->name;
        return view('hris/mutasi-karyawan/create_employee',[
            'bagian_sewing'=>$bagian_sewing,
            'departments'=>$departments,
            'username'=>$username
        ],$this->data);
    }

    public function getdataline(Request $request)
    {
        $master_line = DB::select(
            "SELECT cast(right(sub_dept_name,2) as unsigned) urutan,
            sub_dept_name nm_line
            from department_all
            where sub_dept_name = '".$request->txtline ."'
            group by sub_dept_name
            order by cast(right(sub_dept_name,2) as unsigned) asc",
        );

        // '%" . $request->txtline . "%'
        // $data_marker = DB::select("select a.* from marker_input a
        // where a.id = '" . $request->cri_item . "'");

        return json_encode($master_line[0]);
    }

    public function gettotal(Request $request)
    {
        $tglskrg = date('Y-m-d');
        $total =  DB::select(
            "
        select count(nik) total from
        (select max(id) id from mut_karyawan_input a where a.tgl_pindah = '$tglskrg'
        group by nik)a
        inner join mut_karyawan_input b on a.id = b.id
        where line ='" .
                $request->nm_line .
                "'
        ",
        );
        return json_encode($total[0]);
    }

    public function getdatanik(Request $request)
    {
        $tanggal_sekarang=date('Y-m-d');
        $master_karyawan = DB::select(
            "select enroll_id,ifnull(nik,nik_new) nik, employee_name from employee_atribut
            where enroll_id ='" . $request->txtenroll_id . "' and (status_aktif = 'AKTIF' or tanggal_resign>='$tanggal_sekarang')",
        );
        return json_encode($master_karyawan[0]);
    }

    public function store(Request $request)
    {
        $tglpindah = date('Y-m-d');
        $timestamp = Carbon::now();
        $enroll_id = $request->txtenroll_id;

        $line_asal =  DB::select("
        select line,nik,enroll_id, nm_karyawan from (
            select a.id, b.tgl_pindah,b.enroll_id,b.nik,b.nm_karyawan,b.line from
            (select max(id) id from mut_karyawan_input a
            group by enroll_id)a
            inner join mut_karyawan_input b on a.id = b.id
            ) master_karyawan
        where enroll_id ='$enroll_id'
        ");

        $line_asal_data = $line_asal ? $line_asal[0]->line : null;

        $line_skrg =  DB::select("
        select line,nik,enroll_id, nm_karyawan from (
            select a.id, b.tgl_pindah,b.enroll_id,b.nik,b.nm_karyawan,b.line from
            (select max(id) id from mut_karyawan_input a
            group by enroll_id)a
            inner join mut_karyawan_input b on a.id = b.id
            ) master_karyawan
        where enroll_id ='$enroll_id' and tgl_pindah = '$tglpindah'
        ");

        $line_skrg_data = $line_skrg ? $line_skrg[0]->line : null;

        if ($line_skrg_data == $request->nm_line) {
            return [
                'icon' => 'error',
                'msg' => 'Data Sudah Ada',
                'timer' => false,
                'prog' => true,
            ];
        } else {
            $savemutasi = MutKaryawan::create([
                'tgl_pindah' => $tglpindah,
                'enroll_id' => $request['txtenroll_id'],
                'nik' => $request['nik'],
                'nm_karyawan' => $request['nm_karyawan'],
                'line' => $request['nm_line'],
                'line_asal' => $line_asal_data,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
            $nm_line=$request['nm_line'];
            $sub_dept_id=DB::select("select sub_dept_id from department_all where sub_dept_name='$nm_line' and site_nirwana_id='NAG'")[0]->sub_dept_id;

            $department_name=DB::select("select department_name from department_all where sub_dept_id='".$sub_dept_id."' and site_nirwana_id='NAG'")[0]->department_name;
            if($department_name=='SEWING'){

                $master_karyawan = DB::select(
                    "select enroll_id,ifnull(nik,nik_new) nik, employee_name, sub_dept_name, department_id from employee_atribut
                    where enroll_id ='" . $enroll_id . "' and status_aktif = 'AKTIF'",
                );
                if($master_karyawan[0]->department_id =='DEP20'){
                    DB::update("update employee_atribut set sub_dept_name='$nm_line',sub_dept_id='$sub_dept_id' where enroll_id='$enroll_id'");
                    $employeeUpdate=DB::select("select*from employee_atribut where enroll_id ='$enroll_id'");
                    foreach($employeeUpdate as $value){
                        $tanggal_hari_ini=date('Y-m-d');
                        $bulan_hari_ini=substr($tanggal_hari_ini,0,8).'26';
                        $bulan_sebelum_hari_ini=date('Y-m-d',strtotime( "-1 month", strtotime( $bulan_hari_ini ) ));
                        $bulan_setelah_hari_ini=date('Y-m-d',strtotime( "+1 month", strtotime( $bulan_hari_ini ) ));
                        if($tanggal_hari_ini>=$bulan_sebelum_hari_ini && $tanggal_hari_ini<$bulan_hari_ini){
                            $tanggal_awal_hari_ini=$bulan_sebelum_hari_ini;
                        }else if($tanggal_hari_ini>=$bulan_hari_ini && $tanggal_hari_ini<$bulan_setelah_hari_ini){
                            $tanggal_awal_hari_ini=$bulan_hari_ini;
                        }else{
                            $tanggal_awal_hari_ini='';
                        }
                        $tanggal_akhir_hari_ini=date('Y-m-25',strtotime("+1 month",strtotime($tanggal_awal_hari_ini)));
                        $periode_payroll_hari_ini=$tanggal_awal_hari_ini.' s/d '.$tanggal_akhir_hari_ini;
                        $countEmpHistory=EmployeeAtributHistory::where('enroll_id',$enroll_id)->where('periode_payroll',$periode_payroll_hari_ini)->count();
                        if($countEmpHistory>0){
                            EmployeeAtributHistory::where('enroll_id',$enroll_id)->where('periode_payroll',$periode_payroll_hari_ini)->update([
                                'tanggal_dirubah'=>$tanggal_hari_ini,
                                'sub_dept_id'=>$sub_dept_id,
                                'sub_dept_name'=>$nm_line
                            ]);
                        }else{
                            EmployeeAtributHistory::create([
                                'enroll_id'=>$value->enroll_id,
                                'tanggal_dirubah'=>$tanggal_hari_ini,
                                'periode_payroll'=>$periode_payroll_hari_ini,
                                'employee_id' => $value->employee_id,
                                'employee_name' => $value->employee_name,
                                'jenis_kelamin' => $value->jenis_kelamin,
                                'tempat_lahir' => $value->tempat_lahir,
                                'tanggal_lahir' => $value->tanggal_lahir,
                                'golongan_darah' => $value->golongan_darah,
                                'email' => $value->email,
                                'nomor_tlpn' => $value->nomor_tlpn,
                                'agama' => $value->agama,
                                'status_kawin' => $value->status_kawin,
                                'npwp' => $value->npwp,
                                'nomor_ktp' => $value->nomor_ktp,
                                'nomor_kk' => $value->nomor_kk,
                                'pendidikan_terakhir' => $value->pendidikan_terakhir,
                                'jurusan_pendidikan' => $value->jurusan_pendidikan,
                                'nama_bank' => $value->nama_bank,
                                'nomor_rekening_bank' => $value->nomor_rekening_bank,
                                'ibu_kandung' => $value->ibu_kandung,
                                'propinsi' => $value->propinsi,
                                'kota_kab' => $value->kota_kab,
                                'kecamatan' => $value->kecamatan,
                                'kelurahan_desa' => $value->kelurahan_desa,
                                'alamat_rumah' => $value->alamat_rumah,
                                'alamat_sementara' => $value->alamat_sementara,
                                'site_nirwana_id' => $value->site_nirwana_id,
                                'site_nirwana_name' => $value->site_nirwana_name,
                                'department_id' => $value->department_id,
                                'department_name' => $value->department_name,
                                'sub_dept_id' => $value->sub_dept_id,
                                'sub_dept_name' => $value->sub_dept_name,
                                'sewing_nonsewing'=>$value->sewing_nonsewing,
                                'direct_indirect'=>$value->direct_indirect,
                                'enroll_id' => $value->enroll_id,
                                'join_date' => $value->join_date,
                                'nik' => $value->nik,
                                'status_aktif' => $value->status_aktif,
                                'status_jabatan' => $value->status_jabatan,
                                'status_kontrak_tetap' => $value->status_kontrak_tetap,
                                'status_staff' => $value->status_staff,
                                'tanggal_resign' => $value->tanggal_resign,
                                'sebab_resign' => $value->sebab_resign,
                                'tunjangan' => $value->tunjangan,
                                'kode_grade' => $value->kode_grade,
                                'referensi' => $value->referensi,
                                'employee_name_atasan' => $value->employee_name_atasan,
                                'status_aktif_bpjs_tk' => $value->status_aktif_bpjs_tk,
                                'tanggal_bpjs_ketenagakerjaan' => $value->tanggal_bpjs_ketenagakerjaan,
                                'nomor_bpjs_ketenagakerjaan' => $value->nomor_bpjs_ketenagakerjaan,
                                'status_aktif_bpjs_ks' => $value->status_aktif_bpjs_ks,
                                'tanggal_bpjs_kesehatan' => $value->tanggal_bpjs_kesehatan,
                                'nomor_bpjs_kesehatan' => $value->nomor_bpjs_kesehatan,
                                'premi' => $value->premi,
                                'pengalaman_bekerja' => $value->pengalaman_bekerja,
                                'nama_kerabat' => $value->nama_kerabat,
                                'nomor_tlpn_kerabat' => $value->nomor_tlpn_kerabat,
                                'hubungan_kerabat' => $value->hubungan_kerabat,
                                'alamat_kerabat' => $value->alamat_kerabat,
                                'tanggal_vaccine1' => $value->tanggal_vaccine1,
                                'nama_vaksin1' => $value->nama_vaksin1,
                                'tanggal_vaccine2' => $value->tanggal_vaccine2,
                                'nama_vaksin2' => $value->nama_vaksin2,
                                'golongan_sim' => $value->golongan_sim,
                                'nomor_sim' => $value->nomor_sim,
                                'tanggal_expire_sim' => $value->tanggal_expire_sim,
                                'catatan' => $value->catatan,
                                'no_surat'=>$value->no_surat,
                                'lokasi_foto' => $value->lokasi_foto,
                                'operator' => $value->operator,
                                'tanggal_mulai_kontrak' => $value->tanggal_mulai_kontrak,
                                'tanggal_akhir_kontrak' => $value->tanggal_akhir_kontrak,
                                'catatan_kontrak' => $value->catatan_kontrak
                            ]);
                        }
                    }
                }
            }

        }

        return [
            'icon' => 'success',
            'msg' => 'Data Sudah Tersimpan',
            'timer' => 1500,
            'prog' => false,
        ];
    }


}
