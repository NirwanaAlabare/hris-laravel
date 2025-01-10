<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\DataLembur;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\EmployeeAtribut;
use App\Models\DepartmentAll;
use App\Models\WorkTimeTable;
use App\Models\GradingSalary;
use App\Models\MutKaryawanInputFormLembur;
use App\Models\MutKaryawanInputFormLemburDet;
use App\Models\MutKaryawanInputNonSewingFormLemburDet;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Datatables;
use App\Exports\DepartmentAllExport;
use App\Exports\DataLemburExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use App\Models\RekapPerhitunganLembur;
use App\Models\RekapKehadiranKaryawan;
use \avadim\FastExcelLaravel\Excel as FastExcel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;



/**
 * Class MdAbsenHadirController
 * @package App\Http\Controllers\Hris
 */
class DataLemburController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Master Data';
    }

    public function index()
    {
        $this->department = $this->ajax_getselectdepart();
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        $this->allnfl = $this->ajax_getAllNomorFormLembur();
        $this->periode_lembur = $this->ajax_gettanggallembur();

        return View::make('hris/datalembur', $this->data);
    }

    public function ajax_gettanggallembur()
    {
        $query =  RekapKehadiranKaryawan::selectRaw('periode_payroll')
                                    ->groupby('periode_payroll')
                                    ->orderby('periode_payroll', 'desc')
                                    ->get();
        return $query;

    }

    // public function ajax_gettanggallembur()
    // {
    //     $query =  DB::select(DB::raw('
    //                 SELECT
    //                     CONCAT(
    //                         DATE_ADD( LAST_DAY( DATE_SUB( NOW(), INTERVAL 2 MONTH )), INTERVAL 26 DAY ),
    //                         " s/d ",
    //                         DATE_ADD( LAST_DAY( DATE_SUB( NOW(), INTERVAL 1 MONTH )), INTERVAL 25 DAY )
    //                     ) periode_tanggal
    //                 UNION
    //                 SELECT
    //                     CONCAT(
    //                         DATE_ADD( LAST_DAY( DATE_SUB( CONCAT( substr( tanggal_berjalan, 1, 7 ), "-26" ), INTERVAL 2 MONTH )), INTERVAL 26 DAY ),
    //                         " s/d ",
    //                         DATE_ADD( LAST_DAY( DATE_SUB( CONCAT( substr( tanggal_berjalan, 1, 7 ), "-25" ), INTERVAL 1 MONTH )), INTERVAL 25 DAY )
    //                     ) periode_tanggal
    //                 FROM
    //                     data_lembur
    //                 GROUP BY
    //                     periode_tanggal
    //                 ORDER BY
    //                     periode_tanggal DESC
    //                 '));


    //     return $query;
    // }

    public function getnomorform()
    {
        $tanggal_lembur = request()->tanggal_lembur;
        $datalembur=DB::select("select z.no_form,count(z.enroll_id) jumlah,z.dept from(select b.no_form,b.tgl_lembur,a.enroll_id,b.line dept from mut_karyawan_input_form_lembur_det a inner join mut_karyawan_input_form_lembur b on a.no_form=b.no_form where b.tgl_lembur='$tanggal_lembur'
        union
        select b.no_form,b.tgl_lembur,a.enroll_id,b.dept dept from mut_karyawan_input_non_sewing_form_lembur_det a inner join mut_karyawan_input_non_sewing_form_lembur b on a.no_form=b.no_form where b.tgl_lembur='$tanggal_lembur')z group by no_form order by dept");
        return $datalembur;
    }
    public function getkaryawanlembur(){
        $tanggal_lembur=request()->tanggal_lembur;
        $no_form=request()->no_form;
        $count=count(MutKaryawanInputFormLemburDet::where('no_form',$no_form)->get());
        if($count>0){
            $karyawanLembur=DB::select("select a.enroll_id,e.nik,e.employee_name,SUBSTR(m.absen_masuk_kerja,1,5) absen_masuk_kerja,SUBSTR(m.absen_pulang_kerja,1,5) absen_pulang_kerja,m.status_absen,m.nomor_form_lembur,SUBSTR(a.jam_lembur_awal_rencana,1,5) jam_lembur_awal_rencana,SUBSTR(a.jam_lembur_akhir_rencana,1,5) jam_lembur_akhir_rencana,a.jam_lembur_istirahat,GROUP_CONCAT(c.ket SEPARATOR ', ') ket from mut_karyawan_input_form_lembur_det a inner join mut_karyawan_input_form_lembur b on a.no_form=b.no_form inner join (select*from master_data_absen_kehadiran where tanggal_berjalan='$tanggal_lembur')m on a.enroll_id=m.enroll_id inner join employee_atribut e on a.enroll_id=e.enroll_id inner join mut_karyawan_input_form_lembur_det_ket c on b.no_form=c.no_form where a.no_form='$no_form' group by enroll_id order by employee_name");
        }else{
            $karyawanLembur=DB::select("select a.enroll_id,e.nik,e.employee_name,SUBSTR(m.absen_masuk_kerja,1,5) absen_masuk_kerja,SUBSTR(m.absen_pulang_kerja,1,5) absen_pulang_kerja,m.status_absen,m.nomor_form_lembur,SUBSTR(a.jam_lembur_awal_rencana,1,5) jam_lembur_awal_rencana,SUBSTR(a.jam_lembur_akhir_rencana,1,5) jam_lembur_akhir_rencana,a.jam_lembur_istirahat,a.keterangan ket from mut_karyawan_input_non_sewing_form_lembur_det a inner join mut_karyawan_input_non_sewing_form_lembur b on a.no_form=b.no_form inner join (select*from master_data_absen_kehadiran where tanggal_berjalan='$tanggal_lembur')m on a.enroll_id=m.enroll_id inner join employee_atribut e on a.enroll_id=e.enroll_id where a.no_form='$no_form' order by employee_name");
        }
        return $karyawanLembur;
    }
    public function importkaryawanlembur(){
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        $kodelembur = "SPL/HR";
        $thnbln = date("ym");

        $getlastnomorform =  DataLembur::select('nomor_form_lembur')
                                            ->groupby('nomor_form_lembur')
                                            ->orderby('nomor_form_lembur', 'desc')
                                            ->first();
        if($getlastnomorform == "") {
            $nomor = "0000";
        } else {
            $nomor = $getlastnomorform->nomor_form_lembur;
        }

        $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
        $nomor_form_lembur = $kodelembur . "/" . $thnbln . "/" . $nomorform;

        $enroll_id=request()->enroll_id;
        $tanggal_lembur=request()->tanggal_lembur;
        $x=[];
        foreach($enroll_id as $key=>$value){
            if(!isset(MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->pluck('nomor_form_lembur')[0])){
                $mulai_jam_kerja=null;
                if(isset(MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->pluck('mulai_jam_kerja')[0])){
                    $mulai_jam_kerja=MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->pluck('mulai_jam_kerja')[0];
                }
                $akhir_jam_kerja=null;
                if(isset(MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->pluck('akhir_jam_kerja')[0])){
                    $akhir_jam_kerja=MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->pluck('akhir_jam_kerja')[0];
                }
                $absen_masuk_kerja=null;
                if(isset(MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->pluck('absen_masuk_kerja')[0])){
                    $absen_masuk_kerja=MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->pluck('absen_masuk_kerja')[0];
                }
                $absen_pulang_kerja=null;
                if(isset(MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->pluck('absen_pulang_kerja')[0])){
                    $absen_pulang_kerja=MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->pluck('absen_pulang_kerja')[0];
                }
                $nik=null;
                if(isset(EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('nik')[0])){
                    $nik=EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('nik')[0];
                }
                $employee_id=null;
                if(isset(EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('employee_id')[0])){
                    $employee_id=EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('employee_id')[0];
                }
                $employee_name=null;
                if(isset(EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('employee_name')[0])){
                    $employee_name=EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('employee_name')[0];
                }
                $site_nirwana_id=null;
                if(isset(EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('site_nirwana_id')[0])){
                    $site_nirwana_id=EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('site_nirwana_id')[0];
                }
                $site_nirwana_name=null;
                if(isset(EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('site_nirwana_name')[0])){
                    $site_nirwana_name=EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('site_nirwana_name')[0];
                }
                $department_id=null;
                if(isset(EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('department_id')[0])){
                    $department_id=EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('department_id')[0];
                }
                $department_name=null;
                if(isset(EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('department_name')[0])){
                    $department_name=EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('department_name')[0];
                }
                $sub_dept_id=null;
                if(isset(EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('sub_dept_id')[0])){
                    $sub_dept_id=EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('sub_dept_id')[0];
                }
                $sub_dept_name=null;
                if(isset(EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('sub_dept_name')[0])){
                    $sub_dept_name=EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('sub_dept_name')[0];
                }
                $sub_dept_name=null;
                if(isset(EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('sub_dept_name')[0])){
                    $sub_dept_name=EmployeeAtribut::where('enroll_id',request()->enroll_id[$key])->pluck('sub_dept_name')[0];
                }
                $akhir_jam_lembur='';
                if(request()->jam_lembur_awal_rencana[$key]>request()->jam_lembur_akhir_rencana[$key]){
                    $akhir_jam_lembur=date('Y-m-d', strtotime($tanggal_lembur . ' +1 day')).' '.request()->jam_lembur_akhir_rencana[$key];
                }else{
                    $akhir_jam_lembur=$tanggal_lembur.' '.request()->jam_lembur_akhir_rencana[$key];
                }
                DataLembur::create([
                    'uuid'=>Str::uuid(),
                    'uuid_master'=>MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->pluck('uuid')[0],
                    'nomor_form_lembur'=>$nomor_form_lembur,
                    'tanggal_berjalan'=>MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->pluck('tanggal_berjalan')[0],
                    'tanggal_absen'=>MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->pluck('tanggal_berjalan')[0],
                    'kode_hari'=>MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->pluck('kode_hari')[0],
                    'nama_hari'=>MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->pluck('nama_hari')[0],
                    'mulai_jam_kerja'=>$mulai_jam_kerja,
                    'akhir_jam_kerja'=>$akhir_jam_kerja,
                    'absen_masuk_kerja'=>$absen_masuk_kerja,
                    'absen_pulang_kerja'=>$absen_pulang_kerja,
                    'enroll_id'=>request()->enroll_id[$key],
                    'nik'=>$nik,
                    'employee_id' => $employee_id,
                    'employee_name' => $employee_name,
                    'site_nirwana_id' => $site_nirwana_id,
                    'site_nirwana_name' => $site_nirwana_name,
                    'department_id' => $department_id,
                    'department_name' => $department_name,
                    'sub_dept_id' => $sub_dept_id,
                    'sub_dept_name' => $sub_dept_name,
                    'mulai_jam_lembur' => $tanggal_lembur.' '.request()->jam_lembur_awal_rencana[$key],
                    'akhir_jam_lembur' => $akhir_jam_lembur,
                    'jumlah_jam_lembur' => request()->jam_lembur[$key],
                    'jumlah_jam_istirahat' => request()->jam_lembur_istirahat[$key]/60,
                    'catatan' => request()->keterangan[$key],
                    'operator' => $email
                ]);
                MasterDataAbsenKehadiran::where('enroll_id',request()->enroll_id[$key])->where('tanggal_berjalan',$tanggal_lembur)->update([
                    'nomor_form_lembur'=>$nomor_form_lembur,
                    'kelebihan_jam_kerja_l1' => '0',
                    'kelebihan_jam_kerja_l2' => '0',
                    'kelebihan_jam_kerja_l3' => '0',
                    'kelebihan_jam_kerja_l4' => '0',
                    'mulai_jam_lembur' => $tanggal_lembur.' '.request()->jam_lembur_awal_rencana[$key],
                    'akhir_jam_lembur' => $akhir_jam_lembur,
                    'jumlah_jam_lembur' => request()->jam_lembur[$key],
                    'jumlah_jam_lembur_approved' => request()->jam_lembur[$key],
                    'jumlah_jam_istirahat_lembur' => request()->jam_lembur_istirahat[$key]/60,
                    'catatan_hrd' => request()->keterangan[$key],
                    'operator' => $email
                ]);
            }
        }
        // return $x;
        // $loggedAdmin = Auth::guard('admin')->user();
        // $email = $loggedAdmin->email;
        // $no_form=request()->no_form;
        // $tanggal_lembur=request()->tanggal_lembur;
        // $count=count(MutKaryawanInputFormLemburDet::where('no_form',request()->no_form)->get());
        // if($count>0){
        //     $karyawan_lembur=MutKaryawanInputFormLemburDet::where('no_form',request()->no_form)->with('employee','keterangan')->with(['absen' => function ($query) use($tanggal_lembur) {
        //         $query->where('tanggal_berjalan', $tanggal_lembur);
        //     }])->get();
        // }else{
        //     $karyawan_lembur=MutKaryawanInputNonSewingFormLemburDet::where('no_form',request()->no_form)->with('employee','keterangan')->with(['absen' => function ($query) use($tanggal_lembur) {
        //         $query->where('tanggal_berjalan', $tanggal_lembur);
        //     }])->get();
        // }
        // $nomor_form_lembur=[];
        // $kodelembur = "SPL/HR";
        // $thnbln = date("ym");

        // $getlastnomorform =  DataLembur::select('nomor_form_lembur')
        //                                     ->groupby('nomor_form_lembur')
        //                                     ->orderby('nomor_form_lembur', 'desc')
        //                                     ->first();

        // if($getlastnomorform == "") {
        //     //info("Count : Kosong");
        //     $nomor = "0000";
        // } else {
        //     $nomor = $getlastnomorform->nomor_form_lembur;
        // }

        // $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
        // $nomor_form_lembur = $kodelembur . "/" . $thnbln . "/" . $nomorform;
        // foreach($karyawan_lembur as $key=> $value){
        //     $jumlah_jam_istirahat=$value->jam_lembur_istirahat/60;
        //     $starttimestamp = strtotime($value->jam_lembur_awal_rencana);
        //     $endtimestamp = strtotime($value->jam_lembur_akhir_rencana);
        //     $difference = abs($endtimestamp - $starttimestamp)/3600;
        //     $jumlah_jam_lembur=$difference-$jumlah_jam_istirahat;
        //     if($value->absen[0]->nomor_form_lembur==null){
        //         DataLembur::create([
        //             'uuid'=>Str::uuid(),
        //             'uuid_master'=>$value->absen[0]->uuid,
        //             'nomor_form_lembur'=>$nomor_form_lembur,
        //             'tanggal_berjalan'=>$value->absen[0]->tanggal_berjalan,
        //             'tanggal_absen'=>$value->absen[0]->tanggal_berjalan,
        //             'nomor_form_lembur'=>$nomor_form_lembur,
        //             'kode_hari'=>$value->absen[0]->kode_hari,
        //             'nama_hari'=>$value->absen[0]->nama_hari,
        //             'mulai_jam_kerja'=>$value->absen[0]->mulai_jam_kerja,
        //             'akhir_jam_kerja'=>$value->absen[0]->akhir_jam_kerja,
        //             'absen_masuk_kerja'=>$value->absen[0]->absen_masuk_kerja,
        //             'absen_pulang_kerja'=>$value->absen[0]->absen_pulang_kerja,
        //             'enroll_id'=>$value->absen[0]->enroll_id,
        //             'nik'=>$value->absen[0]->nik,
        //             'employee_id' => $value->absen[0]->employee_id,
        //             'employee_name' => $value->absen[0]->employee_name,
        //             'site_nirwana_id' => $value->absen[0]->site_nirwana_id,
        //             'site_nirwana_name' => $value->absen[0]->site_nirwana_name,
        //             'department_id' => $value->absen[0]->department_id,
        //             'department_name' => $value->absen[0]->department_name,
        //             'sub_dept_id' => $value->absen[0]->sub_dept_id,
        //             'sub_dept_name' => $value->absen[0]->sub_dept_name,
        //             'mulai_jam_lembur' => $tanggal_lembur.' '.$value->jam_lembur_awal_rencana,
        //             'akhir_jam_lembur' => $tanggal_lembur.' '.$value->jam_lembur_akhir_rencana,
        //             'jumlah_jam_lembur' => $jumlah_jam_lembur,
        //             'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
        //             'catatan' => strtoupper($value->keterangan->ket),
        //             'operator' => $email
        //         ]);
        //         MasterDataAbsenKehadiran::where('enroll_id',$value->absen[0]->enroll_id)->where('tanggal_berjalan',$tanggal_lembur)->update([
        //             'nomor_form_lembur'=>$nomor_form_lembur,
        //             'kelebihan_jam_kerja_l1' => '0',
        //             'kelebihan_jam_kerja_l2' => '0',
        //             'kelebihan_jam_kerja_l3' => '0',
        //             'kelebihan_jam_kerja_l4' => '0',
        //             'mulai_jam_lembur' => $tanggal_lembur.' '.$value->jam_lembur_awal_rencana,
        //             'akhir_jam_lembur' => $tanggal_lembur.' '.$value->jam_lembur_akhir_rencana,
        //             'jumlah_jam_lembur' => $jumlah_jam_lembur,
        //             'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
        //             'jumlah_jam_istirahat_lembur' => $jumlah_jam_istirahat,
        //             'catatan_hrd' => strtoupper($value->keterangan->ket),
        //             'operator' => $email
        //         ]);
        //     }
        // }
    }
    public function ajax_getnomorspl(Request $request)
    {

        $periode_lembur = $request->periode_lembur;
        $array_periode_lembur = explode(' s/d ', $periode_lembur);
        $awal_bulan = substr($array_periode_lembur[0], 0, 10);
        $akhir_bulan = substr($array_periode_lembur[1], 0, 10);

        $nomor_form_lembur = $request->nomor_form_lembur;
        $arrayNomorSPL = str_replace(',','","',$nomor_form_lembur);

        if(!empty($nomor_form_lembur)) {
            $inNomorSPL = ' AND nomor_form_lembur IN ("' . $arrayNomorSPL . '")';
        } else {
            $inNomorSPL = '';
        }
        $query =  DataLembur::selectRaw('
                        CONCAT(nomor_form_lembur, " [ ", DATE_FORMAT(tanggal_berjalan, "%d %b %Y"), " ] => ", count(enroll_id), " karyawan") tanggal_nomor_spl,
                        nomor_form_lembur,
                        tanggal_berjalan,
                        mulai_jam_lembur,
                        akhir_jam_lembur,
                        jumlah_jam_istirahat,
                        jumlah_jam_lembur,
                        catatan
                        ')
                        ->whereRaw('
                            tanggal_berjalan BETWEEN "' . $awal_bulan . '" AND "' . $akhir_bulan . '"
                            ' . $inNomorSPL . '
                        ')
                        ->groupby('nomor_form_lembur')
                        ->orderby('nomor_form_lembur', 'desc')
                        ->get();
        return $query;

    }

    public function add_datalembur()
    {
        $this->department = $this->ajax_getselectdepart();
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        return View::make('hris/add_datalembur', $this->data);
    }

    public function ajax_datahadir(Request $request)
    {
        $department_id = $request->department_id;
        $selectNomorFormLembur = $request->selectNomorFormLembur;
        $selectEmployeeID = $request->selectEmployeeID;

        $daterange1 = explode(" - ", $request->daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
        $dataNFL = "";
        $dataEmployee = "";
        $inNFL = "";
        $inEmployee = "";
        $inDepartmentID = "";

        $department_id = $request->department_id;
        $dataDepartment = "";

        if($department_id) {
            $dataDepartment = implode('","',$department_id);
            $inDepartmentID = '"' . $dataDepartment . '"';
        }

        if($selectNomorFormLembur) {
            $dataNFL = implode('","',$selectNomorFormLembur);
            $inNFL = '"' . $dataNFL . '"';
        }

        if($selectEmployeeID) {
            $dataEmployee = implode('","',$selectEmployeeID);
            $inEmployee = '"' . $dataEmployee . '"';
        }
        //info("Select Employee : " . $inEmployee);

        if(request()->ajax()) {

            $columns = array(
                0 => 'uuid',
                1 => 'employee_id',
                2 => 'nik',
                3 => 'enroll_id',
                4 => 'employee_name',
                5 => 'tanggal_berjalan',
                6 => 'kode_hari',
                7 => 'nama_hari',
                8 => 'kerjalibur',
                9 => 'jadwal_jam_kerja',
                10 => 'absensi_masuk_kerja',
                11 => 'absensi_pulang_kerja',
                12 => 'jumlah_jam_kerja',
                13 => 'status_absen',
                14 => 'tanggal_absen'
            );


            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if(empty($department_id) && empty($selectNomorFormLembur)) {
                $totalData = MasterDataAbsenKehadiran::
                whereRaw('
                    nomor_form_lembur is not null
                    and (substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '")
                ')
                ->count();
                $totalFiltered = $totalData;

                $query = MasterDataAbsenKehadiran::
                whereRaw('
                    nomor_form_lembur is not null
                    and (substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '")
                ')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            } else if(empty($selectNomorFormLembur)) {
                $totalData = MasterDataAbsenKehadiran::
                whereRaw('
                    nomor_form_lembur is not null
                    and (substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '")
                    and (department_id in (' . $inDepartmentID . '))
                    and (nik in (' . $inEmployee . '))
                ')
                ->count();
                $totalFiltered = $totalData;

                $query =  MasterDataAbsenKehadiran::
                whereRaw('
                    nomor_form_lembur is not null
                    and (substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '")
                    and (department_id in (' . $inDepartmentID . '))
                    and (nik in (' . $inEmployee . '))
                ')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            } else if(empty($department_id)) {
                $totalData = MasterDataAbsenKehadiran::
                whereRaw('
                    nomor_form_lembur is not null
                    and (nomor_form_lembur in (' . $inNFL . '))
                    and (nik in (' . $inEmployee . '))
                ')
                ->count();
                $totalFiltered = $totalData;

                $query =  MasterDataAbsenKehadiran::
                whereRaw('
                    nomor_form_lembur is not null
                    and (nomor_form_lembur in (' . $inNFL . '))
                    and (nik in (' . $inEmployee . '))
                ')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            }

            $data = array();
            if(!empty($query))
            {
                foreach ($query as $q)
                {
                    $tanggal_absen = $q->tanggal_absen;
                    $kode_hari = $q->kode_hari;
                    $kerjalibur = "KERJA";
                    if($tanggal_absen) {
                        $kerjalibur = "KERJA";
                    } else {
                        switch ($kode_hari) {
                            case '5':
                                $kerjalibur = "LIBUR";
                                break;
                            case '6':
                                $kerjalibur = "LIBUR";
                                break;
                        }
                    }

                    $nestedData['uuid'] = $q->uuid;
                    $nestedData['employee_id'] = $q->employee_id;
                    $nestedData['nik'] = $q->nik;
                    $nestedData['enroll_id'] = $q->enroll_id;
                    $nestedData['employee_name'] = $q->employee_name;
                    $nestedData['tanggal_berjalan'] = $q->tanggal_berjalan;
                    $nestedData['kode_hari'] = $q->kode_hari;
                    $nestedData['nama_hari'] = $q->nama_hari;
                    $nestedData['kerjalibur'] = $kerjalibur;
                    $nestedData['jadwal_jam_kerja'] = $q->mulai_jam_kerja . " s/d " . $q->akhir_jam_kerja;
                    $nestedData['mulai_jam_kerja'] = substr($q->mulai_jam_kerja, 0, 5);
                    $nestedData['akhir_jam_kerja'] = substr($q->akhir_jam_kerja, 0, 5);
                    $nestedData['absen_masuk_kerja'] = substr($q->absen_masuk_kerja, 0, 5);
                    $nestedData['absen_pulang_kerja'] = substr($q->absen_pulang_kerja, 0, 5);
                    $nestedData['absen_dt_datang_terlambat'] = $q->absen_dt_datang_terlambat;
                    $nestedData['absen_dtpc_datang_terlambat_pulang_cepat'] = $q->absen_dtpc_datang_terlambat_pulang_cepat;
                    $nestedData['jumlah_jam_kerja'] = $q->jumlah_jam_kerja;
                    $nestedData['status_absen'] = strtoupper($q->status_absen);
                    $nestedData['absen_alasan'] = $q->absen_alasan;
                    $nestedData['nomor_form_lembur'] = $q->nomor_form_lembur;
                    $nestedData['updated_at'] = substr($q->updated_at, 0, 10) . " " . substr($q->updated_at, 11, 8);
                    $nestedData['operator'] = $q->operator;
                    $nestedData['catatan_hrd'] = $q->catatan_hrd;
                    $nestedData['site_nirwana_name'] = $q->site_nirwana_name;
                    $nestedData['department_name'] = $q->department_name;
                    $nestedData['sub_dept_name'] = $q->sub_dept_name;
                    $nestedData['tanggal_absen'] = $q->tanggal_absen;
                    $nestedData['jumlah_jam_lembur'] = $q->jumlah_jam_lembur;
                    $nestedData['jumlah_jam_istirahat_lembur'] = $q->jumlah_jam_istirahat_lembur;
                    $nestedData['mulai_jam_lembur'] = date('Y-m-d H:i:s', strtotime($q->mulai_jam_lembur));
                    $nestedData['akhir_jam_lembur'] = date('Y-m-d H:i:s', strtotime($q->akhir_jam_lembur));
                    $nestedData['status_lembur'] = $q->status_lembur;

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

    private function ajax_getselectdepart()
    {
        $query =  DepartmentAll::selectRaw('department_id,
                                           concat("[",CASE WHEN site_nirwana_id="ADR208"
                                                            THEN "NAG" WHEN site_nirwana_id="ADR210"
                                                            THEN "SGT" ELSE "UNKNOWN SITE" END,"] "
                                                  ,department_name) department_name')
                                 ->groupby('department_name')
                                 ->orderby('department_name', 'asc')
                                 ->get();

        return $query;

    }

    public function ajax_getselectsubdept(Request $request)
    {
        $department_id = $request->department_id;
        $query =  DepartmentAll::where('department_id','=',$department_id)
                                 ->groupby('sub_dept_id')
                                 ->orderby('sub_dept_name', 'asc')
                                 ->pluck('sub_dept_id','sub_dept_name');
        return $query;

    }

    public function ajax_getallemployeeatribut()
    {
        $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                                           concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                    ->groupby('nik')
                                    ->orderby('employee_name', 'asc')
                                    ->get();
        return $query;

    }

    public function ajax_getselectemployee(Request $request)
    {
        $department_id = $request->department_id;

        if($department_id) {
            $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                        ->where('department_id','=',$department_id)
                                        ->groupby('nik')
                                        ->orderby('employee_name', 'asc')
                                        ->get();

            return $query;
        } else {
            $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                        ->groupby('nik')
                                        ->orderby('employee_name', 'asc')
                                        ->get();

            return $query;
        }

        //return $query;

    }

    public function form_datalembur(Request $request)
    {
        $uuid = $request->editData;

        $query =  MasterDataAbsenKehadiran::
                    selectRaw('uuid, employee_id, nik, employee_name, employee_id, kode_hari, nama_hari,
                                tanggal_berjalan, enroll_id, department_id, department_name,
                                site_nirwana_id, site_nirwana_name, catatan_hrd,
                                sub_dept_id, sub_dept_name, shift_work_id, time_table_name,
                                case when absen_ok_hadir IS NOT NULL then "KERJA" ELSE "LIBUR" END kerjalibur,
                                concat(time_table_name, " [", substr(mulai_jam_kerja, 1, 5), " s/d ", substr(akhir_jam_kerja, 1, 5), "]") jadwal_jam_kerja,
                                mulai_jam_kerja, akhir_jam_kerja, department_id, department_name, sub_dept_id, sub_dept_name,
                                substr(absen_masuk_kerja, 1, 5) absen_masuk_kerja, substr(absen_pulang_kerja, 1, 5) absen_pulang_kerja,
                                concat(DATE_FORMAT(mulai_jam_lembur, "%Y/%m/%d %H:%i"), " - ", DATE_FORMAT(akhir_jam_lembur, "%Y/%m/%d %H:%i")) waktu_jam_lembur,
                                nomor_form_lembur, status_lembur, jumlah_jam_lembur, mulai_jam_lembur, akhir_jam_lembur, date_format(updated_at, "%Y-%m-%d %H:%i:%s") updated_at')
                    ->where('uuid','=',$uuid)->first();

        return Response()->json($query);
    }

    public function getEmployeeLembur(Request $request)
    {
        $daterange1 = explode(" - ", $request->tanggal_lembur);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));

        $selectEmployeeID = $request->selectEmployee;
        if ($selectEmployeeID) {
            $inEmp = explode(",", $selectEmployeeID);
        } else {
            $inEmp = '';
        }

        $query =  MasterDataAbsenKehadiran::
                selectRaw('uuid, employee_id, nik, employee_name, employee_id, kode_hari, nama_hari,
                            tanggal_berjalan, tanggal_absen, enroll_id, department_id, department_name,
                            site_nirwana_id, site_nirwana_name, catatan_hrd,
                            sub_dept_id, sub_dept_name, shift_work_id, time_table_name,
                            case when absen_ok_hadir IS NOT NULL then "KERJA" ELSE "LIBUR" END kerjalibur,
                            concat(time_table_name, " [", substr(mulai_jam_kerja, 1, 5), " s/d ", substr(akhir_jam_kerja, 1, 5), "]") jadwal_jam_kerja,
                            mulai_jam_kerja, akhir_jam_kerja, department_id, department_name, sub_dept_id, sub_dept_name,
                            substr(absen_masuk_kerja, 1, 5) absen_masuk_kerja, substr(absen_pulang_kerja, 1, 5) absen_pulang_kerja,
                            nomor_form_lembur, status_lembur, jumlah_jam_lembur, mulai_jam_lembur, akhir_jam_lembur, date_format(updated_at, "%Y-%m-%d %H:%i:%s") updated_at')
                ->whereRaw('
                    DATE_FORMAT(tanggal_berjalan, "%Y-%m-%d") = DATE_FORMAT("' . $tanggalMulai . '", "%Y-%m-%d")
                    and enroll_id in (' . implode(',', $inEmp) . ')
                ')->orderBy('employee_name')
                ->get();

        // $query =  MasterDataAbsenKehadiran::where('tanggal_berjalan',$tanggalMulai)->wherein('enroll_id', $inEmp)->get();
        //info("Query : " . $query);
        return Response()->json($query);
    }

    public function ajax_getemployeselectdeptid(Request $request)
    {
        $department_id = $request->department_id;
        $dataDepartment = "";

        if($department_id) {
            $dataDepartment = implode('","',$department_id);
        }
        $inDepartmentID = '"' . $dataDepartment . '"';

        $query =  EmployeeAtribut::
                        selectRaw('enroll_id no_pin, nik, employee_name,
                                        concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                        ->whereRaw('
                            department_id in (' . $inDepartmentID . ')
                        ')
                        ->groupby('nik')
                        ->orderby('employee_name', 'asc')
                        ->get();
        //info('Query :' . $query);
        return $query;

    }

    public function ajax_getemployeselectnfl(Request $request)
    {
        $nomor_form_lembur = $request->nomor_form_lembur;
        $dataNFL = "";

        if($nomor_form_lembur) {
            $dataNFL = implode('","',$nomor_form_lembur);
        }
        $inNFL = '"' . $dataNFL . '"';

        $query =  MasterDataAbsenKehadiran::
                        selectRaw('enroll_id no_pin, nik, employee_name,
                                        concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                        ->whereRaw('
                            nomor_form_lembur in (' . $inNFL . ')
                        ')
                        ->groupby('nik')
                        ->orderby('employee_name', 'asc')
                        ->get();

        return $query;

    }

    public function store_multi(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $arrayHtml = $request->arrayHtml;

        $kodelembur = "SPL/HR";
        $thnbln = date("ym");

        $getlastnomorform =  DataLembur::select('nomor_form_lembur')
                                            ->groupby('nomor_form_lembur')
                                            ->orderby('nomor_form_lembur', 'desc')
                                            ->first();


        if($getlastnomorform == "") {
            //info("Count : Kosong");
            $nomor = "0000";
         } else {
            $nomor = $getlastnomorform->nomor_form_lembur;
        }

        $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
        $nomor_form_lembur = $kodelembur . "/" . $thnbln . "/" . $nomorform;
        //info("Nomor Form Lembur : " . $nomor_form_lembur);
        foreach ($arrayHtml as $key => $value) {

            try {
                DataLembur::create([
                    'uuid' => Str::uuid(),
                    'uuid_master' => $value['uuid_master'],
                    'tanggal_berjalan' => $value['tanggal_berjalan'],
                    'tanggal_absen' => $value['tanggal_absen'],
                    'nomor_form_lembur' => $nomor_form_lembur,
                    'shift_work_id' => $value['shift_work_id'],
                    'kode_hari' => $value['kode_hari'],
                    'nama_hari' => $value['nama_hari'],
                    'time_table_name' => $value['time_table_name'],
                    'mulai_jam_kerja' => $value['mulai_jam_kerja'],
                    'akhir_jam_kerja' => $value['akhir_jam_kerja'],
                    'absen_masuk_kerja' => $value['absen_masuk_kerja'],
                    'absen_pulang_kerja' => $value['absen_pulang_kerja'],
                    'enroll_id' => $value['enroll_id'],
                    'nik' => $value['nik'],
                    'employee_id' => $value['employee_id'],
                    'employee_name' => $value['employee_name'],
                    'site_nirwana_id' => $value['site_nirwana_id'],
                    'site_nirwana_name' => $value['site_nirwana_name'],
                    'department_id' => $value['department_id'],
                    'department_name' => $value['department_name'],
                    'sub_dept_id' => $value['sub_dept_id'],
                    'sub_dept_name' => $value['sub_dept_name'],
                    'mulai_jam_lembur' => $value['mulai_jam_lembur_edit'],
                    'akhir_jam_lembur' => $value['akhir_jam_lembur_edit'],
                    'jumlah_jam_lembur' => $value['jumlah_jam_lembur_edit'],
                    'jumlah_jam_istirahat' => $value['jumlah_jam_istirahat_edit'],
                    'catatan' => $value['catatan_hrd_edit'],
                    'operator' => $email
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            MasterDataAbsenKehadiran::where('uuid','=',$value['uuid_master'])
            ->update([
                'nomor_form_lembur' => $nomor_form_lembur,
                'kelebihan_jam_kerja_l1' => '0',
                'kelebihan_jam_kerja_l2' => '0',
                'kelebihan_jam_kerja_l3' => '0',
                'kelebihan_jam_kerja_l4' => '0',
                'mulai_jam_lembur' => $value['mulai_jam_lembur_edit'],
                'akhir_jam_lembur' => $value['akhir_jam_lembur_edit'],
                'jumlah_jam_lembur' => $value['jumlah_jam_lembur_edit'],
                'jumlah_jam_lembur_approved' => $value['jumlah_jam_lembur_edit'],
                'jumlah_jam_istirahat_lembur' => $value['jumlah_jam_istirahat_edit'],
                'catatan_hrd' => $value['catatan_hrd_edit'],
                'operator' => $email
            ]);

        }

        return Response()->json($nomor_form_lembur);

    }

    public function update(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $uuid_master = $request->uuid_master;
        $nomor_form_lembur = $request->nomor_form_lembur;
        $jumlah_jam_istirahat = $request->jumlah_jam_istirahat;
        $jumlah_jam_lembur = $request->jumlah_jam_lembur;
        $mulai_jam_lembur = $request->mulai_jam_lembur;
        $akhir_jam_lembur = $request->akhir_jam_lembur;
        $catatan_hrd = $request->catatan_hrd;

        $queryMaster = MasterDataAbsenKehadiran::where('uuid','=',$uuid_master)
        ->update([
            'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
            'jumlah_jam_lembur' => $jumlah_jam_lembur,
            'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
            'mulai_jam_lembur' => $mulai_jam_lembur,
            'akhir_jam_lembur' => $akhir_jam_lembur,
            'catatan_hrd' => $catatan_hrd,
            'operator' => $email
        ]);

        $queryDetail = DataLembur::where('uuid_master','=',$uuid_master)
        ->update([
            'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
            'jumlah_jam_lembur' => $jumlah_jam_lembur,
            'mulai_jam_lembur' => $mulai_jam_lembur,
            'akhir_jam_lembur' => $akhir_jam_lembur,
            'catatan' => $catatan_hrd,
            'operator' => $email
        ]);

        return Response()->json($queryDetail);

    }

    public function delete(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $uuid_master = $request->uuid_master;
        //info("Delete hapus .....");
        MasterDataAbsenKehadiran::where('uuid','=',$uuid_master)
                                            ->update([
                                                'nomor_form_lembur' => null,
                                                'jumlah_jam_istirahat' => null,
                                                'jumlah_jam_lembur' => null,
                                                'mulai_jam_lembur' => null,
                                                'akhir_jam_lembur' => null,
                                                'catatan_hrd' => null,
                                                'operator' => $email
                                            ]);

        $queryDel = DataLembur::where('uuid_master','=',$uuid_master)->delete();

        return Response()->json($queryDel);

    }

    public function ajax_getAllNomorFormLembur()
    {
        $query =  MasterDataAbsenKehadiran::
                selectRaw('uuid, employee_id, nik, employee_name, employee_id, kode_hari, nama_hari,
                            tanggal_berjalan, tanggal_absen, enroll_id, department_id, department_name,
                            site_nirwana_id, site_nirwana_name, catatan_hrd,
                            sub_dept_id, sub_dept_name, shift_work_id, time_table_name,
                            case when absen_ok_hadir IS NOT NULL then "KERJA" ELSE "LIBUR" END kerjalibur,
                            concat(time_table_name, " [", substr(mulai_jam_kerja, 1, 5), " s/d ", substr(akhir_jam_kerja, 1, 5), "]") jadwal_jam_kerja,
                            mulai_jam_kerja, akhir_jam_kerja, department_id, department_name, sub_dept_id, sub_dept_name,
                            substr(absen_masuk_kerja, 1, 5) absen_masuk_kerja, substr(absen_pulang_kerja, 1, 5) absen_pulang_kerja,
                            nomor_form_lembur, status_lembur, jumlah_jam_lembur, mulai_jam_lembur, akhir_jam_lembur, date_format(updated_at, "%Y-%m-%d %H:%i:%s") updated_at')
                ->whereRaw('
                    nomor_form_lembur is not null
                ')
                ->groupby('nomor_form_lembur')
                ->orderby('nomor_form_lembur', 'desc')
                ->get();

        return $query;
    }

    public function ajax_getNomorFormLembur(Request $request)
    {
        $nomor_form_lembur = $request->nomor_form_lembur;
        $dataNomorFormLembur = "";

        if($nomor_form_lembur) {
            $dataNomorFormLembur = implode('","',$nomor_form_lembur);
        }
        $inNomorFormLembur = '"' . $dataNomorFormLembur . '"';


        $query =  MasterDataAbsenKehadiran::
                selectRaw('uuid, employee_id, nik, employee_name, employee_id, kode_hari, nama_hari,
                            tanggal_berjalan, tanggal_absen, enroll_id, department_id, department_name,
                            site_nirwana_id, site_nirwana_name, catatan_hrd,
                            sub_dept_id, sub_dept_name, shift_work_id, time_table_name,
                            case when absen_ok_hadir IS NOT NULL then "KERJA" ELSE "LIBUR" END kerjalibur,
                            concat(time_table_name, " [", substr(mulai_jam_kerja, 1, 5), " s/d ", substr(akhir_jam_kerja, 1, 5), "]") jadwal_jam_kerja,
                            mulai_jam_kerja, akhir_jam_kerja, department_id, department_name, sub_dept_id, sub_dept_name,
                            substr(absen_masuk_kerja, 1, 5) absen_masuk_kerja, substr(absen_pulang_kerja, 1, 5) absen_pulang_kerja,
                            nomor_form_lembur, status_lembur, jumlah_jam_lembur, mulai_jam_lembur, akhir_jam_lembur, date_format(updated_at, "%Y-%m-%d %H:%i:%s") updated_at')
                ->whereRaw('
                    nomor_form_lembur in (' . $inNomorFormLembur . ')
                ')
                ->groupby('nomor_form_lembur')
                ->orderby('nomor_form_lembur', 'desc')
                ->get();

        return Response()->json($query);
    }

    public function ajax_exportexcel(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        $periode_lembur = $request->input('periode_lembur');
        $array_periode_lembur = explode(' s/d ', $periode_lembur);
        $this->awal_bulan = substr($array_periode_lembur[0], 0, 10);
        $this->akhir_bulan = substr($array_periode_lembur[1], 0, 10);
        $tanggal_awal=$array_periode_lembur[0];
        $tanggal_akhir=$array_periode_lembur[1];
        $datePeriode = strtoupper(strftime("%d %b %Y", strtotime($this->awal_bulan)) . ' s/d ' . strftime("%d %b %Y", strtotime($this->akhir_bulan)));

        $dateRange = 'and master_data_absen_kehadiran.tanggal_berjalan between "' . $this->awal_bulan . '" and "' . $this->akhir_bulan . '"';

        if($request->input('selectPosisiName')) {
            $posisi = $request->input('selectPosisiName');
        } else {
            $posisi = '';
        }

       /*  $fileName = 'DepartmentAll.xlsx';
        return (new DepartmentAllExport)->download($fileName); */
        $nomor_form_lembur_rekap=RekapPerhitunganLembur::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->pluck('nomor_form_lembur');
        $dataLembur = MasterDataAbsenKehadiran::whereNotNull('nomor_form_lembur')->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->with('data_lembur','employee_atribut')->with(['rekap_lembur' => function ($query)use($tanggal_awal,$tanggal_akhir) {
            $query->where('tanggal_berjalan', '>=', $tanggal_awal)
            ->where('tanggal_berjalan','<=',$tanggal_akhir);
        }])->orderBy('tanggal_berjalan')->orderBy('nomor_form_lembur')->orderBy('employee_name')->get();
        $excel = FastExcel::create('dataLembur');
        $sheet = $excel->getSheet();
        $sheet->writeTo('A1', 'PT NIRWANA ALABARE GARMENT',['font-size' => 14]);
        $sheet->writeTo('A2', 'LAPORAN DATA LEMBUR KARYAWAN',['font-size' => 11]);
        $sheet->writeTo('A3', 'TANGGAL LEMBUR : ' . $datePeriode,['font-size' => 11]);
        $sheet->writeTo('A4', 'STAFF/NON STAFF : SEMUA KARYAWAN',['font-size' => 11]);

        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');
        $sheet->mergeCells('A4:D4');

        $sheet->writeTo('A6', 'TANGGAL');
        $sheet->mergeCells('A6:A7')->applyTextCenter();
        $sheet->writeTo('B6', 'HARI');
        $sheet->mergeCells('B6:B7')->applyTextCenter();
        $sheet->writeTo('C6', 'NO. SPL');
        $sheet->mergeCells('C6:C7')->applyTextCenter();
        $sheet->writeTo('D6', 'NIK');
        $sheet->mergeCells('D6:D7')->applyTextCenter();
        $sheet->writeTo('E6', 'NO. ABSEN');
        $sheet->mergeCells('E6:E7')->applyTextCenter();
        $sheet->writeTo('F6', 'NAMA KARYAWAN');
        $sheet->mergeCells('F6:F7')->applyTextCenter();
        $sheet->writeTo('G6', 'KERJA/LIBUR');
        $sheet->mergeCells('G6:G7')->applyTextCenter();
        $sheet->writeTo('H6', 'STATUS');
        $sheet->mergeCells('H6:H7')->applyTextCenter();
        $sheet->writeTo('I6', 'DEPARTMENT');
        $sheet->mergeCells('I6:I7')->applyTextCenter();
        $sheet->writeTo('J6', 'BAGIAN');
        $sheet->mergeCells('J6:J7')->applyTextCenter();

        $sheet->writeTo('K6', 'JADWAL KERJA');
        $sheet->mergeCells('K6:L6')->applyTextCenter();
        $sheet->writeTo('K7', 'IN')->applyTextCenter();
        $sheet->writeTo('L7', 'OUT')->applyTextCenter();

        $sheet->writeTo('M6', 'ABSEN');
        $sheet->mergeCells('M6:N6')->applyTextCenter();
        $sheet->writeTo('M7', 'IN')->applyTextCenter();
        $sheet->writeTo('N7', 'OUT')->applyTextCenter();
        $sheet->writeTo('O6', 'JAM LEMBUR');
        $sheet->mergeCells('O6:P6')->applyTextCenter();
        $sheet->writeTo('O7', 'MULAI')->applyTextCenter();
        $sheet->writeTo('P7', 'SELESAI');
        $sheet->writeTo('Q6', 'RINCIAN PENGAJUAN LEMBUR');
        $sheet->mergeCells('Q6:U6')->applyTextCenter();
        $sheet->writeTo('Q7', 'LEMBUR 1')->applyTextCenter();
        $sheet->writeTo('R7', 'LEMBUR 2')->applyTextCenter();
        $sheet->writeTo('S7', 'LEMBUR 3')->applyTextCenter();
        $sheet->writeTo('T7', 'LEMBUR 4')->applyTextCenter();
        $sheet->writeTo('U7', 'TOTAL LEMBUR')->applyTextCenter();
        $sheet->writeTo('V6', 'CATATAN')->applyTextCenter();
        $sheet->mergeCells('V6:V7')->applyTextCenter();
        $sheet->writeTo('X6', 'TOTAL LEMBUR VERIFIKASI');
        $sheet->mergeCells('X6:X7');

        $sheet->writeTo('Z6', 'GRADE');
        $sheet->mergeCells('Z6:Z7')->applyTextCenter();
        $sheet->writeTo('AA6', 'BIAYA LEMBUR');
        $sheet->mergeCells('AA6:AF6')->applyTextCenter();
        $sheet->writeTo('AA7', 'RP. LEMBUR 1')->applyTextCenter();
        $sheet->writeTo('AB7', 'RP. LEMBUR 2')->applyTextCenter();
        $sheet->writeTo('AC7', 'RP. LEMBUR 3')->applyTextCenter();
        $sheet->writeTo('AD7', 'RP. LEMBUR 4')->applyTextCenter();
        $sheet->writeTo('AE7', 'TOTAL LEMBUR')->applyTextCenter();
        $sheet->writeTo('AF7', 'VERIFY STATUS')->applyTextCenter();
        $sheet->writeAreas();

        if(Auth::guard('admin')->user()->role_user!='payroll'){
            if(Auth::guard('admin')->user()->email=='alex.herdian@ptnag.com'){
                $sheet->setColOptions([
                    'A' => ['width' => 11],
                    'B' => ['width' => 7],
                    'C' => ['width' => 18],
                    'D' => ['width' => 12],
                    'E' => ['width' => 10],
                    'F' => ['width' => 32],
                    'G' => ['width' => 12],
                    'H' => ['width' => 13],
                    'I' => ['width' => 27],
                    'J' => ['width' => 27],
                    'J' => ['width' => 27],
                    'K' => ['width' => 7],
                    'L' => ['width' => 7],
                    'M' => ['width' => 7],
                    'N' => ['width' => 7],
                    'O' => ['width' => 7],
                    'P' => ['width' => 7],
                    'V' => ['width' => 34],
                    'W' => ['width' => 6],
                    'X' => ['width' => 23],
                    'X' => ['width' => 23],
                    'AA7' => ['width' => 15],
                    'AB7' => ['width' => 15],
                    'AC7' => ['width' => 15],
                    'AD7' => ['width' => 15],
                    'AE7' => ['width' => 15],
                    'AF7' => ['width' => 15],
                    'K' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                    'L' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                    'M' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                    'N' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                    'O' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                    'P' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                    'Z' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
                    'AA' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
                    'AB' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
                    'AC' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
                    'AD' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
                ]);
            }else{
                $sheet->setColOptions([
                    'A' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 11],
                    'B' => ['width' => 7],
                    'C' => ['width' => 18],
                    'D' => ['width' => 12],
                    'E' => ['width' => 10],
                    'F' => ['width' => 32],
                    'G' => ['width' => 12],
                    'H' => ['width' => 13],
                    'I' => ['width' => 27],
                    'J' => ['width' => 27],
                    'J' => ['width' => 27],
                    'K' => ['width' => 7],
                    'L' => ['width' => 7],
                    'M' => ['width' => 7],
                    'N' => ['width' => 7],
                    'O' => ['width' => 7],
                    'P' => ['width' => 7],
                    'V' => ['width' => 34],
                    'W' => ['width' => 6],
                    'X' => ['width' => 23],
                    'X' => ['width' => 23],
                    'AA7' => ['width' => 15],
                    'AB7' => ['width' => 15],
                    'AC7' => ['width' => 15],
                    'AD7' => ['width' => 15],
                    'AE7' => ['width' => 15],
                    'AF7' => ['width' => 15],
                    'K' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                    'L' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                    'M' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                    'N' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                    'O' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                    'P' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                    'Z' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
                    'AA' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
                    'AB' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
                    'AC' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
                    'AD' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
                ]);
            }
        }else{
            $sheet->setColOptions([
                'A' => ['width' => 11],
                'B' => ['width' => 7],
                'C' => ['width' => 18],
                'D' => ['width' => 12],
                'E' => ['width' => 10],
                'F' => ['width' => 32],
                'G' => ['width' => 12],
                'H' => ['width' => 13],
                'I' => ['width' => 27],
                'J' => ['width' => 27],
                'J' => ['width' => 27],
                'K' => ['width' => 7],
                'L' => ['width' => 7],
                'M' => ['width' => 7],
                'N' => ['width' => 7],
                'O' => ['width' => 7],
                'P' => ['width' => 7],
                'V' => ['width' => 34],
                'W' => ['width' => 6],
                'X' => ['width' => 23],
                'X' => ['width' => 23],
                'AA7' => ['width' => 15],
                'AB7' => ['width' => 15],
                'AC7' => ['width' => 15],
                'AD7' => ['width' => 15],
                'AE7' => ['width' => 15],
                'AF7' => ['width' => 15],
                'K' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                'L' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                'M' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                'N' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                'O' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                'P' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
                'Z' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
                'AA' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
                'AB' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
                'AC' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
                'AD' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
            ]);
        }
        foreach($dataLembur as $lembur){
            $kode_hari = $lembur->kode_hari;
            $liburnasional = $lembur->holiday_name;

            $absenIN = $lembur->absen_masuk_kerja;
            $absenOUT = $lembur->absen_pulang_kerja;

            $kerjalibur = "KERJA";
            switch ($kode_hari) {
                case '5':
                    $kerjalibur = "LIBUR";
                    break;
                case '6':
                    $kerjalibur = "LIBUR";
                    break;
            }

            if(($absenIN <> null) || ($absenIN <> "") || ($absenOUT <> null) || ($absenOUT <> "")) {
                $kerjalibur = "KERJA";
                switch ($kode_hari) {
                    case '5':
                        $kerjalibur = "LIBUR";
                        break;
                    case '6':
                        $kerjalibur = "LIBUR";
                        break;
                }
            }
            if($lembur->data_lembur->is_verifikasi==0){
                $is_verifikasi='UNVERIFIED';
            }else if($lembur->data_lembur->is_verifikasi==1){
                $is_verifikasi='VERIFIED';
            }
            $lembur_1='';
            if(isset($lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur_1')[0])){
                $lembur_1=$lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur_1')[0];
            }
            $lembur_2='';
            if(isset($lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur_2')[0])){
                $lembur_2=$lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur_2')[0];
            }
            $lembur_3='';
            if(isset($lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur_3')[0])){
                $lembur_3=$lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur_3')[0];
            }
            $lembur_4='';
            if(isset($lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur_4')[0])){
                $lembur_4=$lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur_4')[0];
            }
            $total_lembur_1234='';
            if(isset($lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('total_lembur_1234')[0])){
                $total_lembur_1234=$lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('total_lembur_1234')[0];
            }
            $lembur1_rupiah='';
            if(isset($lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur1_rupiah')[0])){
                $lembur1_rupiah=$lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur1_rupiah')[0];
            }
            $lembur2_rupiah='';
            if(isset($lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur2_rupiah')[0])){
                $lembur2_rupiah=$lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur2_rupiah')[0];
            }
            $lembur3_rupiah='';
            if(isset($lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur3_rupiah')[0])){
                $lembur3_rupiah=$lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur3_rupiah')[0];
            }
            $lembur4_rupiah='';
            if(isset($lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur4_rupiah')[0])){
                $lembur4_rupiah=$lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('lembur4_rupiah')[0];
            }
            $total_lembur_rupiah='';
            if(isset($lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('total_lembur_rupiah')[0])){
                $total_lembur_rupiah=$lembur['rekap_lembur']->where('tanggal_berjalan',$lembur->tanggal_berjalan)->pluck('total_lembur_rupiah')[0];
            }
            $tahun_berjalan = substr($lembur->tanggal_berjalan, 0, 4);
            $kode_grade=EmployeeAtribut::where('enroll_id',$lembur->enroll_id)->pluck('kode_grade')[0];
            $salary=GradingSalary::where('kode_grade',$kode_grade)->whereRaw("SUBSTRING(periode_umk, 1, 4) = ?", [$tahun_berjalan])->pluck('salary_bulanan')[0];
            if(Auth::guard('admin')->user()->role_user!='payroll'){
                if(Auth::guard('admin')->user()->email=='alex.herdian@ptnag.com'){
                    $data = [
                        $lembur->tanggal_berjalan,
                        $lembur->nama_hari,
                        $lembur->nomor_form_lembur,
                        $lembur->nik,
                        $lembur->enroll_id,
                        $lembur->employee_name,
                        $kerjalibur,
                        $lembur->employee_atribut->status_staff,
                        $lembur->employee_atribut->department_name,
                        $lembur->employee_atribut->sub_dept_name,
                        substr($lembur->mulai_jam_kerja, 0, 5),
                        substr($lembur->akhir_jam_kerja, 0, 5),
                        substr($lembur->absen_masuk_kerja, 0, 5),
                        substr($lembur->absen_pulang_kerja, 0, 5),
                        substr($lembur->mulai_jam_lembur, 11, 5),
                        substr($lembur->akhir_jam_lembur, 11, 5),
                        $lembur_1,
                        $lembur_2,
                        $lembur_3,
                        $lembur_4,
                        $total_lembur_1234,
                        $lembur['data_lembur']->catatan,
                        '',
                        $total_lembur_1234,
                        '',
                        $salary,
                        $lembur1_rupiah,
                        $lembur2_rupiah,
                        $lembur3_rupiah,
                        $lembur4_rupiah,
                        $total_lembur_rupiah,
                        $is_verifikasi
                    ];
                }else{
                    $data = [
                        Date::stringToExcel($lembur->tanggal_berjalan),
                        $lembur->nama_hari,
                        $lembur->nomor_form_lembur,
                        $lembur->nik,
                        $lembur->enroll_id,
                        $lembur->employee_name,
                        $kerjalibur,
                        $lembur->employee_atribut->status_staff,
                        $lembur->employee_atribut->department_name,
                        $lembur->employee_atribut->sub_dept_name,
                        substr($lembur->mulai_jam_kerja, 0, 5),
                        substr($lembur->akhir_jam_kerja, 0, 5),
                        substr($lembur->absen_masuk_kerja, 0, 5),
                        substr($lembur->absen_pulang_kerja, 0, 5),
                        substr($lembur->mulai_jam_lembur, 11, 5),
                        substr($lembur->akhir_jam_lembur, 11, 5),
                        $lembur_1,
                        $lembur_2,
                        $lembur_3,
                        $lembur_4,
                        $total_lembur_1234,
                        $lembur['data_lembur']->catatan,
                        '',
                        $total_lembur_1234,
                        '',
                        $salary,
                        $lembur1_rupiah,
                        $lembur2_rupiah,
                        $lembur3_rupiah,
                        $lembur4_rupiah,
                        $total_lembur_rupiah,
                        $is_verifikasi
                    ];
                }
            }else{
                $data = [
                    $lembur->tanggal_berjalan,
                    $lembur->nama_hari,
                    $lembur->nomor_form_lembur,
                    $lembur->nik,
                    $lembur->enroll_id,
                    $lembur->employee_name,
                    $kerjalibur,
                    $lembur->employee_atribut->status_staff,
                    $lembur->employee_atribut->department_name,
                    $lembur->employee_atribut->sub_dept_name,
                    substr($lembur->mulai_jam_kerja, 0, 5),
                    substr($lembur->akhir_jam_kerja, 0, 5),
                    substr($lembur->absen_masuk_kerja, 0, 5),
                    substr($lembur->absen_pulang_kerja, 0, 5),
                    substr($lembur->mulai_jam_lembur, 11, 5),
                    substr($lembur->akhir_jam_lembur, 11, 5),
                    $lembur_1,
                    $lembur_2,
                    $lembur_3,
                    $lembur_4,
                    $total_lembur_1234,
                    $lembur['data_lembur']->catatan,
                    '',
                    $total_lembur_1234,
                    '',
                    $salary,
                    $lembur1_rupiah,
                    $lembur2_rupiah,
                    $lembur3_rupiah,
                    $lembur4_rupiah,
                    $total_lembur_rupiah,
                    $is_verifikasi
                ];
            }
            $sheet->writeRow($data);
        }
        $finename=substr($request->periode_lembur,14,8).' - PT.NAG OVERTIME DATA '.rand(10,10000000);
        ob_end_clean();
        $excel->download($finename);
    }

    public function ajax_gettanggalnfl(Request $request)
    {
        $daterange1 = explode(" - ", $request->daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));

        $query =  MasterDataAbsenKehadiran::
                selectRaw('uuid, employee_id, nik, employee_name, employee_id, kode_hari, nama_hari,
                            tanggal_berjalan, tanggal_absen, enroll_id, department_id, department_name,
                            site_nirwana_id, site_nirwana_name, catatan_hrd,
                            sub_dept_id, sub_dept_name, shift_work_id, time_table_name,
                            case when absen_ok_hadir IS NOT NULL then "KERJA" ELSE "LIBUR" END kerjalibur,
                            concat(time_table_name, " [", substr(mulai_jam_kerja, 1, 5), " s/d ", substr(akhir_jam_kerja, 1, 5), "]") jadwal_jam_kerja,
                            mulai_jam_kerja, akhir_jam_kerja, department_id, department_name, sub_dept_id, sub_dept_name,
                            substr(absen_masuk_kerja, 1, 5) absen_masuk_kerja, substr(absen_pulang_kerja, 1, 5) absen_pulang_kerja,
                            nomor_form_lembur, status_lembur, jumlah_jam_lembur, mulai_jam_lembur, akhir_jam_lembur, date_format(updated_at, "%Y-%m-%d %H:%i:%s") updated_at')
                ->whereRaw('
                    substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                    and nomor_form_lembur is not null
                ')
                ->groupby('nomor_form_lembur')
                ->orderby('nomor_form_lembur', 'desc')
                ->get();

        return Response()->json($query);
    }

    /*    Screen lock controller.When screen lock button from menu is cliked this controller is called.
    *     lock variable is set to 1 when screen is locked.SET to 0  if you dont want screen variable
    */

    public function screenlock()
    {
        Session::put('lock', '1');
        return View::make('admin/screen_lock', $this->data);
    }

    public function ajax_getemployee(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        if(request()->ajax()) {

        $columns = array(
            0 => 'enroll_id',
            1 => 'nik',
            2 => 'employee_name',
            3 => 'sub_dept_name',
            4 => 'status_staff',
            5 => 'status_aktif',
            6 => 'join_date',
            7 => 'tanggal_resign'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  EmployeeAtribut::whereRaw('enroll_id is not null')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = EmployeeAtribut::whereRaw('enroll_id is not null')
                ->count();
            $totalFiltered = $totalData;

        } else {
            $search = $request->input('search.value');

            $query =  EmployeeAtribut::whereRaw('enroll_id is not null')
                ->where('nik','LIKE',"%{$search}%")
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->orWhere('status_staff','LIKE',"%{$search}%")
                ->orWhere('status_aktif','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = EmployeeAtribut::whereRaw('enroll_id is not null')
                ->where('nik','LIKE',"%{$search}%")
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->orWhere('status_staff','LIKE',"%{$search}%")
                ->orWhere('status_aktif','LIKE',"%{$search}%")
                ->count();
            $totalFiltered = $totalData;

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['nik'] = $q->nik;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['status_aktif'] = $q->status_aktif;
                $nestedData['status_staff'] = $q->status_staff;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;
                $nestedData['join_date'] = $q->employee_name;
                $nestedData['operator'] = $q->operator;
                $nestedData['join_date'] = substr($q->join_date, 0, 10) . " " . substr($q->join_date, 11, 5);
                $nestedData['tanggal_resign'] = substr($q->tanggal_resign, 0, 10) . " " . substr($q->tanggal_resign, 11, 5);
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

    public function ajax_getsubdept(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'site_nirwana_name',
            1 => 'department_name',
            2 => 'sub_dept_name'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  DepartmentAll::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = DepartmentAll::count();
            $totalFiltered = $totalData;

        } else {
            $search = $request->input('search.value');

            $query =  DepartmentAll::where('site_nirwana_name','LIKE',"%{$search}%")
                ->orWhere('department_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = DepartmentAll::where('site_nirwana_name','LIKE',"%{$search}%")
                ->orWhere('department_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->count();
            $totalFiltered = $totalData;

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['site_nirwana_id'] = $q->site_nirwana_id;
                $nestedData['site_nirwana_name'] = $q->site_nirwana_name;
                $nestedData['department_id'] = $q->department_id;
                $nestedData['department_name'] = $q->department_name;
                $nestedData['sub_dept_id'] = $q->sub_dept_id;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;

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
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $enroll_id = $request->enroll_id;
        $tanggal_berjalan = $request->tanggal_berjalan;
        $nomor_form_lembur = $request->nomor_form_lembur;
        $mulai_jam_lembur = $request->mulai_jam_lembur;
        $explodeMulaiLembur = explode(" ", $mulai_jam_lembur);
        $tanggal_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2);
        $mulai_jam_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2) . " " . $explodeMulaiLembur[1];

        $akhir_jam_lembur = $request->akhir_jam_lembur;
        $explodeAkhirLembur = explode(" ", $akhir_jam_lembur);
        $akhir_jam_lembur = substr($explodeAkhirLembur[0], 6, 4) . '-' . substr($explodeAkhirLembur[0], 3, 2) . '-' . substr($explodeAkhirLembur[0], 0, 2) . " " . $explodeAkhirLembur[1];

        $jumlah_jam_lembur = $request->jumlah_jam_lembur;
        $jumlah_jam_istirahat = $request->jumlah_jam_istirahat;
        $selectEmployee = $request->selectEmployee;
        $enroll_id_array = explode(",", $selectEmployee);
        $selectSubDept = $request->selectSubDept;
        $sub_dept_id_array = explode(",", $selectSubDept);
        $catatan = $request->catatan;

        if (empty($request->nomor_form_lembur)) {
            $kodelembur = "SPL/HR";
            $thnbln = date("ym");

            $getlastnomorform =  DataLembur::select('nomor_form_lembur')
                                                ->groupby('nomor_form_lembur')
                                                ->orderby('nomor_form_lembur', 'desc')
                                                ->first();

            if($getlastnomorform == "") {
                //info("Count : Kosong");
                $nomor = "0000";
            } else {
                $nomor = $getlastnomorform->nomor_form_lembur;
            }

            $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
            $nomor_form_lembur = $kodelembur . "/" . $thnbln . "/" . $nomorform;

            //info("Nomor Form Lembur : " . $nomor_form_lembur);

            if (!empty($enroll_id_array[0])) {

                foreach ($enroll_id_array as $key => $value) {

                    $queryMaster =  MasterDataAbsenKehadiran::selectRaw('
                                    master_data_absen_kehadiran.uuid,
                                    master_data_absen_kehadiran.nomor_form_lembur,
                                    master_data_absen_kehadiran.tanggal_berjalan,
                                    master_data_absen_kehadiran.kode_hari,
                                    master_data_absen_kehadiran.nama_hari,
                                    master_data_absen_kehadiran.mulai_jam_kerja,
                                    master_data_absen_kehadiran.akhir_jam_kerja,
                                    master_data_absen_kehadiran.absen_masuk_kerja,
                                    master_data_absen_kehadiran.absen_pulang_kerja,
                                    master_data_absen_kehadiran.enroll_id,
                                    employee_atribut.nik,
                                    employee_atribut.employee_id,
                                    employee_atribut.employee_name,
                                    employee_atribut.site_nirwana_id,
                                    department_all.site_nirwana_name,
                                    employee_atribut.department_id,
                                    department_all.department_name,
                                    employee_atribut.sub_dept_id,
                                    department_all.sub_dept_name
                                ')
                                ->whereRaw('
                                    master_data_absen_kehadiran.enroll_id = "' . $value . '"
                                    AND master_data_absen_kehadiran.tanggal_berjalan = "' . $tanggal_lembur . '"
                                ')
                                ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'master_data_absen_kehadiran.enroll_id')
                                ->leftJoin('department_all', 'department_all.sub_dept_id', '=', 'master_data_absen_kehadiran.sub_dept_id')
                                ->orderBy('employee_atribut.employee_name','asc')
                                ->get();

                    foreach ($queryMaster as $key1 => $value1) {

                        if(empty($value1['nomor_form_lembur'])) {
                            $queryDataLembur = DataLembur::create([
                                'uuid' => Str::uuid(),
                                'uuid_master' => $value1['uuid'],
                                'tanggal_berjalan' => $value1['tanggal_berjalan'],
                                'tanggal_absen' => $value1['tanggal_berjalan'],
                                'nomor_form_lembur' => $nomor_form_lembur,
                                'kode_hari' => $value1['kode_hari'],
                                'nama_hari' => $value1['nama_hari'],
                                'mulai_jam_kerja' => $value1['mulai_jam_kerja'],
                                'akhir_jam_kerja' => $value1['akhir_jam_kerja'],
                                'absen_masuk_kerja' => $value1['absen_masuk_kerja'],
                                'absen_pulang_kerja' => $value1['absen_pulang_kerja'],
                                'enroll_id' => $value1['enroll_id'],
                                'nik' => $value1['nik'],
                                'employee_id' => $value1['employee_id'],
                                'employee_name' => $value1['employee_name'],
                                'site_nirwana_id' => $value1['site_nirwana_id'],
                                'site_nirwana_name' => $value1['site_nirwana_name'],
                                'department_id' => $value1['department_id'],
                                'department_name' => $value1['department_name'],
                                'sub_dept_id' => $value1['sub_dept_id'],
                                'sub_dept_name' => $value1['sub_dept_name'],
                                'mulai_jam_lembur' => $mulai_jam_lembur,
                                'akhir_jam_lembur' => $akhir_jam_lembur,
                                'jumlah_jam_lembur' => $jumlah_jam_lembur,
                                'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                                'catatan' => strtoupper($catatan),
                                'operator' => $email
                            ]);
                        } else {
                            $queryDataLembur = DataLembur::whereRaw('
                                tanggal_berjalan = "' . $value1['tanggal_berjalan'] . '"
                                AND enroll_id = "' . $value . '"
                            ')
                            ->update([
                                'nomor_form_lembur' => $nomor_form_lembur,
                                'mulai_jam_kerja' => $value1['mulai_jam_kerja'],
                                'akhir_jam_kerja' => $value1['akhir_jam_kerja'],
                                'absen_masuk_kerja' => $value1['absen_masuk_kerja'],
                                'absen_pulang_kerja' => $value1['absen_pulang_kerja'],
                                'mulai_jam_lembur' => $mulai_jam_lembur,
                                'akhir_jam_lembur' => $akhir_jam_lembur,
                                'jumlah_jam_lembur' => $jumlah_jam_lembur,
                                'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                                'catatan' => strtoupper($catatan),
                                'operator' => $email
                            ]);
                        }

                        if($queryDataLembur) {
                            $query = MasterDataAbsenKehadiran::whereRaw('
                                tanggal_berjalan = "' . $value1['tanggal_berjalan'] . '"
                                AND enroll_id = "' . $value . '"
                            ')
                            ->update([
                                'nomor_form_lembur' => $nomor_form_lembur,
                                'kelebihan_jam_kerja_l1' => '0',
                                'kelebihan_jam_kerja_l2' => '0',
                                'kelebihan_jam_kerja_l3' => '0',
                                'kelebihan_jam_kerja_l4' => '0',
                                'mulai_jam_lembur' => $mulai_jam_lembur,
                                'akhir_jam_lembur' => $akhir_jam_lembur,
                                'jumlah_jam_lembur' => $jumlah_jam_lembur,
                                'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
                                'jumlah_jam_istirahat_lembur' => $jumlah_jam_istirahat,
                                'catatan_hrd' => strtoupper($catatan),
                                'operator' => $email
                            ]);
                        }
                    }
                }
            } else {
                if (!empty($sub_dept_id_array[0])) {

                    foreach ($sub_dept_id_array as $key => $value) {

                        $queryMaster =  MasterDataAbsenKehadiran::selectRaw('
                                        master_data_absen_kehadiran.uuid,
                                        master_data_absen_kehadiran.nomor_form_lembur,
                                        master_data_absen_kehadiran.tanggal_berjalan,
                                        master_data_absen_kehadiran.kode_hari,
                                        master_data_absen_kehadiran.nama_hari,
                                        master_data_absen_kehadiran.mulai_jam_kerja,
                                        master_data_absen_kehadiran.akhir_jam_kerja,
                                        master_data_absen_kehadiran.absen_masuk_kerja,
                                        master_data_absen_kehadiran.absen_pulang_kerja,
                                        master_data_absen_kehadiran.enroll_id,
                                        employee_atribut.nik,
                                        employee_atribut.employee_id,
                                        employee_atribut.employee_name,
                                        employee_atribut.site_nirwana_id,
                                        department_all.site_nirwana_name,
                                        employee_atribut.department_id,
                                        department_all.department_name,
                                        employee_atribut.sub_dept_id,
                                        department_all.sub_dept_name
                                    ')
                                    ->whereRaw('
                                        master_data_absen_kehadiran.enroll_id is not null
                                        AND employee_atribut.sub_dept_id = "' . $value . '"
                                        AND master_data_absen_kehadiran.tanggal_berjalan = "' . $tanggal_lembur . '"
                                    ')
                                    ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'master_data_absen_kehadiran.enroll_id')
                                    ->leftJoin('department_all', 'department_all.sub_dept_id', '=', 'master_data_absen_kehadiran.sub_dept_id')
                                    ->orderBy('employee_name','asc')
                                    ->get();

                        foreach ($queryMaster as $key1 => $value1) {

                            if(empty($value1['nomor_form_lembur'])) {
                                $queryDataLembur = DataLembur::create([
                                    'uuid' => Str::uuid(),
                                    'uuid_master' => $value1['uuid'],
                                    'tanggal_berjalan' => $value1['tanggal_berjalan'],
                                    'tanggal_absen' => $value1['tanggal_berjalan'],
                                    'nomor_form_lembur' => $nomor_form_lembur,
                                    'kode_hari' => $value1['kode_hari'],
                                    'nama_hari' => $value1['nama_hari'],
                                    'mulai_jam_kerja' => $value1['mulai_jam_kerja'],
                                    'akhir_jam_kerja' => $value1['akhir_jam_kerja'],
                                    'absen_masuk_kerja' => $value1['absen_masuk_kerja'],
                                    'absen_pulang_kerja' => $value1['absen_pulang_kerja'],
                                    'enroll_id' => $value1['enroll_id'],
                                    'nik' => $value1['nik'],
                                    'employee_id' => $value1['employee_id'],
                                    'employee_name' => $value1['employee_name'],
                                    'site_nirwana_id' => $value1['site_nirwana_id'],
                                    'site_nirwana_name' => $value1['site_nirwana_name'],
                                    'department_id' => $value1['department_id'],
                                    'department_name' => $value1['department_name'],
                                    'sub_dept_id' => $value1['sub_dept_id'],
                                    'sub_dept_name' => $value1['sub_dept_name'],
                                    'mulai_jam_lembur' => $mulai_jam_lembur,
                                    'akhir_jam_lembur' => $akhir_jam_lembur,
                                    'jumlah_jam_lembur' => $jumlah_jam_lembur,
                                    'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                                    'catatan' => strtoupper($catatan),
                                    'operator' => $email
                                ]);
                            } else {
                                $queryDataLembur = DataLembur::whereRaw('
                                    enroll_id = "' . $value1['enroll_id'] . '"
                                    AND tanggal_berjalan = "' . $tanggal_lembur . '"
                                ')
                                ->update([
                                    'nomor_form_lembur' => $nomor_form_lembur,
                                    'mulai_jam_kerja' => $value1['mulai_jam_kerja'],
                                    'akhir_jam_kerja' => $value1['akhir_jam_kerja'],
                                    'absen_masuk_kerja' => $value1['absen_masuk_kerja'],
                                    'absen_pulang_kerja' => $value1['absen_pulang_kerja'],
                                    'mulai_jam_lembur' => $mulai_jam_lembur,
                                    'akhir_jam_lembur' => $akhir_jam_lembur,
                                    'jumlah_jam_lembur' => $jumlah_jam_lembur,
                                    'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                                    'catatan' => strtoupper($catatan),
                                    'operator' => $email
                                ]);
                            }

                            if($queryDataLembur) {
                                MasterDataAbsenKehadiran::whereRaw('
                                    enroll_id = "' . $value1['enroll_id'] . '"
                                    AND tanggal_berjalan = "' . $tanggal_lembur . '"
                                ')
                                ->update([
                                    'nomor_form_lembur' => $nomor_form_lembur,
                                    'kelebihan_jam_kerja_l1' => '0',
                                    'kelebihan_jam_kerja_l2' => '0',
                                    'kelebihan_jam_kerja_l3' => '0',
                                    'kelebihan_jam_kerja_l4' => '0',
                                    'mulai_jam_lembur' => $mulai_jam_lembur,
                                    'akhir_jam_lembur' => $akhir_jam_lembur,
                                    'jumlah_jam_lembur' => $jumlah_jam_lembur,
                                    'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
                                    'jumlah_jam_istirahat_lembur' => $jumlah_jam_istirahat,
                                    'catatan_hrd' => strtoupper($catatan),
                                    'operator' => $email
                                ]);
                            }
                        }
                    }
                }
            }
        } else {

            $queryMaster =  MasterDataAbsenKehadiran::selectRaw('
                            master_data_absen_kehadiran.uuid,
                            master_data_absen_kehadiran.nomor_form_lembur,
                            master_data_absen_kehadiran.tanggal_berjalan,
                            master_data_absen_kehadiran.kode_hari,
                            master_data_absen_kehadiran.nama_hari,
                            master_data_absen_kehadiran.mulai_jam_kerja,
                            master_data_absen_kehadiran.akhir_jam_kerja,
                            master_data_absen_kehadiran.absen_masuk_kerja,
                            master_data_absen_kehadiran.absen_pulang_kerja,
                            master_data_absen_kehadiran.enroll_id,
                            employee_atribut.nik,
                            employee_atribut.employee_id,
                            employee_atribut.employee_name,
                            employee_atribut.site_nirwana_id,
                            department_all.site_nirwana_name,
                            employee_atribut.department_id,
                            department_all.department_name,
                            employee_atribut.sub_dept_id,
                            department_all.sub_dept_name
                        ')
                        ->whereRaw('
                            master_data_absen_kehadiran.enroll_id = "' . $enroll_id . '"
                            AND master_data_absen_kehadiran.tanggal_berjalan = "' . $tanggal_berjalan .'"
                            AND master_data_absen_kehadiran.nomor_form_lembur = "' . $nomor_form_lembur .'"
                        ')
                        ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'master_data_absen_kehadiran.enroll_id')
                        ->leftJoin('department_all', 'department_all.sub_dept_id', '=', 'master_data_absen_kehadiran.sub_dept_id')
                        ->orderBy('employee_name','asc')
                        ->get();

            foreach ($queryMaster as $key1 => $value1) {
                $queryDataLembur = DataLembur::whereRaw('
                            nomor_form_lembur = "' . $nomor_form_lembur . '"
                            AND tanggal_berjalan = "' . $tanggal_berjalan . '"
                            AND enroll_id = "' . $enroll_id . '"
                        ')
                    ->update([
                        'nik' => $value1['nik'],
                        'employee_id' => $value1['employee_id'],
                        'employee_name' => $value1['employee_name'],
                        'site_nirwana_id' => $value1['site_nirwana_id'],
                        'site_nirwana_name' => $value1['site_nirwana_name'],
                        'department_id' => $value1['department_id'],
                        'department_name' => $value1['department_name'],
                        'sub_dept_id' => $value1['sub_dept_id'],
                        'sub_dept_name' => $value1['sub_dept_name'],
                        'mulai_jam_kerja' => $value1['mulai_jam_kerja'],
                        'akhir_jam_kerja' => $value1['akhir_jam_kerja'],
                        'absen_masuk_kerja' => $value1['absen_masuk_kerja'],
                        'absen_pulang_kerja' => $value1['absen_pulang_kerja'],
                        'mulai_jam_lembur' => $mulai_jam_lembur,
                        'akhir_jam_lembur' => $akhir_jam_lembur,
                        'jumlah_jam_lembur' => $jumlah_jam_lembur,
                        'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                        'catatan' => strtoupper($catatan),
                        'operator' => $email
                    ]);

                if($queryDataLembur) {
                    MasterDataAbsenKehadiran::whereRaw('
                            nomor_form_lembur = "' . $nomor_form_lembur . '"
                            AND tanggal_berjalan = "' . $tanggal_berjalan . '"
                            AND enroll_id = "' . $enroll_id . '"
                        ')
                        ->update([
                            'nomor_form_lembur' => $nomor_form_lembur,
                            'kelebihan_jam_kerja_l1' => '0',
                            'kelebihan_jam_kerja_l2' => '0',
                            'kelebihan_jam_kerja_l3' => '0',
                            'kelebihan_jam_kerja_l4' => '0',
                            'mulai_jam_lembur' => $mulai_jam_lembur,
                            'akhir_jam_lembur' => $akhir_jam_lembur,
                            'jumlah_jam_lembur' => $jumlah_jam_lembur,
                            'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
                            'jumlah_jam_istirahat_lembur' => $jumlah_jam_istirahat,
                            'catatan_hrd' => strtoupper($catatan),
                            'operator' => $email
                        ]);
                }
            }
        }

        return Response()->json($nomor_form_lembur);

    }
    public function ajax_datalembur2(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        $periode_lembur = $request->periode_lembur;
        $array_periode_lembur = explode(' s/d ', $periode_lembur);
        $awal_bulan = substr($array_periode_lembur[0], 0, 10);
        $akhir_bulan = substr($array_periode_lembur[1], 0, 10);

        $inNoSPL = "";
        if($request->selectNoSPL) {
            $selectNoSPL = $request->selectNoSPL;
            $dataNoSPL = implode('","',$selectNoSPL);
            $inNoSPL = ' AND data_lembur.nomor_form_lembur in ("' . $dataNoSPL . '")';

        }
        $query =  DataLembur::where('nomor_form_lembur',$request->selectNoSPL)->get();
        // whereRaw('
        //         master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $awal_bulan . '" AND "' . $akhir_bulan . '"
        //         AND master_data_absen_kehadiran.tanggal_berjalan= data_lembur.tanggal_berjalan
        //         AND master_data_absen_kehadiran.enroll_id = data_lembur.enroll_id
        //         AND master_data_absen_kehadiran.enroll_id = employee_atribut.enroll_id
        //         ' . $inNoSPL . '
        //     ')
        //     ->get();

        return Response()->json($query);

    }

    // public function ajax_datalembur(Request $request)
    // {
    //     $periode_lembur = $request->periode_lembur;
    //     // $periode_lembur="2023-01-26 s/d 2023-02-25";
    //     $array_periode_lembur = explode(' s/d ', $periode_lembur);
    //     $awal_bulan = substr($array_periode_lembur[0], 0, 10);
    //     $akhir_bulan = substr($array_periode_lembur[1], 0, 10);

    //     // if($request->selectNoSPL) {
    //     //     $inNoSPL = ' AND data_lembur.nomor_form_lembur in ("' . $dataNoSPL . '")';
    //     // }

    //     //$explodeNoSPL = explode(',', $selectNoSPL);
    //     if($request->selectNoSPL) {
    //         $selectNoSPL = $request->selectNoSPL;
    //         $query =  MasterDataAbsenKehadiran::with('employee_atribut','employee_atribut.dept','data_lembur')->where('tanggal_berjalan','>=',$awal_bulan)->where('tanggal_berjalan','<=',$akhir_bulan)->where('nomor_form_lembur',$selectNoSPL)->orderBy('employee_name')->get();
    //     }else{
    //         $query =  MasterDataAbsenKehadiran::with('employee_atribut','employee_atribut.dept','data_lembur')->where('tanggal_berjalan','>=',$awal_bulan)->where('tanggal_berjalan','<=',$akhir_bulan)->where('nomor_form_lembur','!=',null)->orderBy('employee_name')->get();
    //     }
    //     return $query;

    //     // $query =  MasterDataAbsenKehadiran::selectRaw('
    //     //         data_lembur.uuid,
    //     //         data_lembur.nomor_form_lembur,
    //     //         data_lembur.is_verifikasi,
    //     //         DATE_FORMAT(master_data_absen_kehadiran.tanggal_berjalan, "%d %b %Y") format_tanggal_berjalan,
    //     //         master_data_absen_kehadiran.tanggal_berjalan,
    //     //         master_data_absen_kehadiran.kode_hari,
    //     //         master_data_absen_kehadiran.nama_hari,
    //     //         IF(master_data_absen_kehadiran.kode_hari IN (5,6), "LIBUR",
    //     //             IF(master_data_absen_kehadiran.holiday_name is not null, "LIBUR", "KERJA")
    //     //         ) status_kerja,
    //     //         master_data_absen_kehadiran.enroll_id,
    //     //         employee_atribut.nik,
    //     //         employee_atribut.employee_name,
    //     //         substr(master_data_absen_kehadiran.mulai_jam_kerja, 1, 5) mulai_jam_kerja,
    //     //         substr(master_data_absen_kehadiran.akhir_jam_kerja, 1, 5) akhir_jam_kerja,
    //     //         substr(master_data_absen_kehadiran.absen_masuk_kerja, 1, 5) absen_masuk_kerja,
    //     //         substr(master_data_absen_kehadiran.absen_pulang_kerja, 1, 5) absen_pulang_kerja,
    //     //         master_data_absen_kehadiran.mulai_jam_lembur,
    //     //         master_data_absen_kehadiran.akhir_jam_lembur,
    //     //         master_data_absen_kehadiran.jumlah_jam_lembur,
    //     //         master_data_absen_kehadiran.jumlah_jam_istirahat_lembur,
    //     //         employee_atribut.status_aktif,
    //     //         employee_atribut.status_staff,
    //     //         employee_atribut.tanggal_resign,
    //     //         DATE_FORMAT(employee_atribut.tanggal_resign, "%d %b %Y") format_tanggal_resign,
    //     //         department_all.site_nirwana_name,
    //     //         department_all.department_name,
    //     //         department_all.sub_dept_name,
    //     //         data_lembur.catatan
    //     //     ')
    //     //     ->whereRaw('
    //     //         master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $awal_bulan . '" AND "' . $akhir_bulan . '"
    //     //         AND master_data_absen_kehadiran.tanggal_berjalan= data_lembur.tanggal_berjalan
    //     //         AND master_data_absen_kehadiran.enroll_id = data_lembur.enroll_id
    //     //         AND master_data_absen_kehadiran.enroll_id = employee_atribut.enroll_id
    //     //         ' . $inNoSPL . '
    //     //     ')
    //     //     ->join('data_lembur','data_lembur.enroll_id','=','master_data_absen_kehadiran.enroll_id')
    //     //     ->join('employee_atribut','master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
    //     //     ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
    //     //     ->orderBy('data_lembur.nomor_form_lembur','desc')
    //     //     ->orderBy('master_data_absen_kehadiran.tanggal_berjalan','asc')
    //     //     ->orderBy('employee_atribut.employee_name','asc')
    //     //     ->get();

    //     return Response()->json($query);

    // }

    public function ajax_datalembur(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '40000M');
        $periode_lembur = $request->periode_lembur;
        // $periode_lembur="2023-01-26 s/d 2023-02-25";
        $array_periode_lembur = explode(' s/d ', $periode_lembur);
        $awal_bulan = substr($array_periode_lembur[0], 0, 10);
        $akhir_bulan = substr($array_periode_lembur[1], 0, 10);

        // if($request->selectNoSPL) {
        //     $inNoSPL = ' AND data_lembur.nomor_form_lembur in ("' . $dataNoSPL . '")';
        // }

        //$explodeNoSPL = explode(',', $selectNoSPL);

        if($request->selectNoSPL=='' && $request->verificationStatus!=''){
            $verification_status=$request->verificationStatus;
            $query =  MasterDataAbsenKehadiran::with('employee_atribut','employee_atribut.dept','data_lembur')->where('nomor_form_lembur','!=',null)->where('tanggal_berjalan','>=',$awal_bulan)->where('tanggal_berjalan','<=',$akhir_bulan)->whereHas('data_lembur',function($query) use ($verification_status){
                $query->where('is_verifikasi',$verification_status);
            })->orderBy('employee_name')->get();
        }else if($request->selectNoSPL!='' && $request->verificationStatus==''){
            $selectNoSPL = $request->selectNoSPL;
            $query =  MasterDataAbsenKehadiran::with('employee_atribut','employee_atribut.dept','data_lembur')->where('tanggal_berjalan','>=',$awal_bulan)->where('tanggal_berjalan','<=',$akhir_bulan)->where('nomor_form_lembur',$selectNoSPL)->orderBy('employee_name')->get();
        }else if($request->selectNoSPL!='' && $request->verificationStatus!=''){
            $selectNoSPL = $request->selectNoSPL;
            $verification_status=$request->verificationStatus;
            $query =  MasterDataAbsenKehadiran::with('employee_atribut','employee_atribut.dept','data_lembur')->where('tanggal_berjalan','>=',$awal_bulan)->where('tanggal_berjalan','<=',$akhir_bulan)->where('nomor_form_lembur',$selectNoSPL)->whereHas('data_lembur',function($query) use ($verification_status){
                $query->where('is_verifikasi',$verification_status);
            })->orderBy('employee_name')->get();
        }else{
            $query =  MasterDataAbsenKehadiran::with('employee_atribut','employee_atribut.dept','data_lembur')->where('nomor_form_lembur','!=',null)->where('tanggal_berjalan','>=',$awal_bulan)->where('tanggal_berjalan','<=',$akhir_bulan)->orderBy('employee_name')->get();
        }

        return Response()->json($query);

        // $query =  MasterDataAbsenKehadiran::selectRaw('
        //         data_lembur.uuid,
        //         data_lembur.nomor_form_lembur,
        //         data_lembur.is_verifikasi,
        //         DATE_FORMAT(master_data_absen_kehadiran.tanggal_berjalan, "%d %b %Y") format_tanggal_berjalan,
        //         master_data_absen_kehadiran.tanggal_berjalan,
        //         master_data_absen_kehadiran.kode_hari,
        //         master_data_absen_kehadiran.nama_hari,
        //         IF(master_data_absen_kehadiran.kode_hari IN (5,6), "LIBUR",
        //             IF(master_data_absen_kehadiran.holiday_name is not null, "LIBUR", "KERJA")
        //         ) status_kerja,
        //         master_data_absen_kehadiran.enroll_id,
        //         employee_atribut.nik,
        //         employee_atribut.employee_name,
        //         substr(master_data_absen_kehadiran.mulai_jam_kerja, 1, 5) mulai_jam_kerja,
        //         substr(master_data_absen_kehadiran.akhir_jam_kerja, 1, 5) akhir_jam_kerja,
        //         substr(master_data_absen_kehadiran.absen_masuk_kerja, 1, 5) absen_masuk_kerja,
        //         substr(master_data_absen_kehadiran.absen_pulang_kerja, 1, 5) absen_pulang_kerja,
        //         master_data_absen_kehadiran.mulai_jam_lembur,
        //         master_data_absen_kehadiran.akhir_jam_lembur,
        //         master_data_absen_kehadiran.jumlah_jam_lembur,
        //         master_data_absen_kehadiran.jumlah_jam_istirahat_lembur,
        //         employee_atribut.status_aktif,
        //         employee_atribut.status_staff,
        //         employee_atribut.tanggal_resign,
        //         DATE_FORMAT(employee_atribut.tanggal_resign, "%d %b %Y") format_tanggal_resign,
        //         department_all.site_nirwana_name,
        //         department_all.department_name,
        //         department_all.sub_dept_name,
        //         data_lembur.catatan
        //     ')
        //     ->whereRaw('
        //         master_data_absen_kehadiran.tanggal_berjalan BETWEEN "' . $awal_bulan . '" AND "' . $akhir_bulan . '"
        //         AND master_data_absen_kehadiran.tanggal_berjalan= data_lembur.tanggal_berjalan
        //         AND master_data_absen_kehadiran.enroll_id = data_lembur.enroll_id
        //         AND master_data_absen_kehadiran.enroll_id = employee_atribut.enroll_id
        //         ' . $inNoSPL . '
        //     ')
        //     ->join('data_lembur','data_lembur.enroll_id','=','master_data_absen_kehadiran.enroll_id')
        //     ->join('employee_atribut','master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
        //     ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
        //     ->orderBy('data_lembur.nomor_form_lembur','desc')
        //     ->orderBy('master_data_absen_kehadiran.tanggal_berjalan','asc')
        //     ->orderBy('employee_atribut.employee_name','asc')
        //     ->get();

        // return Response()->json($query);

    }

    public function remove(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $tanggal_berjalan = $request->tanggal_berjalan;
        $enroll_id = $request->enroll_id;
        $nomor_form_lembur = $request->nomor_form_lembur;

        $queryMaster = MasterDataAbsenKehadiran::whereRaw('
                tanggal_berjalan = "' . $tanggal_berjalan . '"
                AND enroll_id = "' . $enroll_id . '"
            ')
            ->update([
                'nomor_form_lembur' => null,
                'jumlah_jam_istirahat_lembur' => null,
                'jumlah_jam_lembur' => null,
                'jumlah_jam_lembur_approved' => null,
                'mulai_jam_lembur' => null,
                'akhir_jam_lembur' => null,
                'catatan_hrd' => null,
                'operator' => 'system',
            ]);

        if($queryMaster) {
            $query = DataLembur::whereRaw('
                        tanggal_berjalan = "' . $tanggal_berjalan . '"
                        AND enroll_id = "' . $enroll_id . '"
                        AND nomor_form_lembur = "' . $nomor_form_lembur . '"
                    ')->delete();
            $lembur =RekapPerhitunganLembur:: where('nomor_form_lembur',$nomor_form_lembur)->where('enroll_id',$enroll_id)->delete();

        }

        return Response()->json($query);

    }

    public function updatelembur(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $enroll_id = $request->enroll_id;
        $nomor_form_lembur = $request->nomor_form_lembur;

        $mulai_jam_lembur = $request->mulai_jam_lembur;
        $explodeMulaiLembur = explode(" ", $mulai_jam_lembur);
        $tanggal_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2);
        $mulai_jam_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2) . " " . $explodeMulaiLembur[1];

        $tanggal_berjalan = $request->tanggal_berjalan;

        $akhir_jam_lembur = $request->akhir_jam_lembur;
        $explodeAkhirLembur = explode(" ", $akhir_jam_lembur);
        $akhir_jam_lembur = substr($explodeAkhirLembur[0], 6, 4) . '-' . substr($explodeAkhirLembur[0], 3, 2) . '-' . substr($explodeAkhirLembur[0], 0, 2) . " " . $explodeAkhirLembur[1];

        $jumlah_jam_lembur = $request->jumlah_jam_lembur;
        $jumlah_jam_istirahat = $request->jumlah_jam_istirahat;
        $catatan = $request->catatan;

        MasterDataAbsenKehadiran::whereRaw('
                    tanggal_berjalan = "' . $tanggal_berjalan . '"
                    AND enroll_id = "' . $enroll_id . '"
        ')
        ->update([
            'nomor_form_lembur' => null,
            'jumlah_jam_istirahat_lembur' => null,
            'jumlah_jam_lembur' => null,
            'jumlah_jam_lembur_approved' => null,
            'mulai_jam_lembur' => null,
            'akhir_jam_lembur' => null,
            'catatan_hrd' => null,
            'operator' => $email
        ]);

        $queryDataLembur = DataLembur::whereRaw('
                    tanggal_berjalan = "' . $tanggal_berjalan . '"
                    AND enroll_id = "' . $enroll_id . '"
                ')
            ->update([
                'tanggal_berjalan' => $tanggal_lembur,
                'tanggal_absen' => $tanggal_lembur,
                'mulai_jam_lembur' => $mulai_jam_lembur,
                'akhir_jam_lembur' => $akhir_jam_lembur,
                'jumlah_jam_lembur' => $jumlah_jam_lembur,
                'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                'catatan' => strtoupper($catatan),
                'operator' => $email
            ]);

        $queryMaster = 0;

        if($queryDataLembur) {

            $queryMaster = MasterDataAbsenKehadiran::whereRaw('
                    tanggal_berjalan = "' . $tanggal_lembur . '"
                    AND enroll_id = "' . $enroll_id . '"
                ')
            ->update([
                'nomor_form_lembur' => $nomor_form_lembur,
                'mulai_jam_lembur' => $mulai_jam_lembur,
                'akhir_jam_lembur' => $akhir_jam_lembur,
                'jumlah_jam_lembur' => $jumlah_jam_lembur,
                'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
                'jumlah_jam_istirahat_lembur' => $jumlah_jam_istirahat,
                'catatan_hrd' => strtoupper($catatan),
                'operator' => $email
            ]);

        }

        return Response()->json($queryMaster);

    }

    public function updatelemburall(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $nomor_form_lembur = $request->nomor_form_lembur;

        $mulai_jam_lembur = $request->mulai_jam_lembur;
        $explodeMulaiLembur = explode(" ", $mulai_jam_lembur);
        $tanggal_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2);
        $mulai_jam_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2) . " " . $explodeMulaiLembur[1];

        $akhir_jam_lembur = $request->akhir_jam_lembur;
        $explodeAkhirLembur = explode(" ", $akhir_jam_lembur);
        $akhir_jam_lembur = substr($explodeAkhirLembur[0], 6, 4) . '-' . substr($explodeAkhirLembur[0], 3, 2) . '-' . substr($explodeAkhirLembur[0], 0, 2) . " " . $explodeAkhirLembur[1];

        $jumlah_jam_lembur = $request->jumlah_jam_lembur;
        $jumlah_jam_istirahat = $request->jumlah_jam_istirahat;
        $catatan = $request->catatan;

        MasterDataAbsenKehadiran::whereRaw('
            nomor_form_lembur = "' . $nomor_form_lembur . '"
        ')
        ->update([
            'nomor_form_lembur' => null,
            'jumlah_jam_istirahat_lembur' => null,
            'jumlah_jam_lembur' => null,
            'jumlah_jam_lembur_approved' => null,
            'mulai_jam_lembur' => null,
            'akhir_jam_lembur' => null,
            'catatan_hrd' => null,
            'operator' => $email
        ]);


        $queryAll = DataLembur::selectRaw('
                    tanggal_berjalan,
                    enroll_id,
                    nomor_form_lembur
                ')
                ->whereRaw('
                    nomor_form_lembur = "' . $nomor_form_lembur . '"
                ')
                ->groupBy('tanggal_berjalan')
                ->groupBy('enroll_id')
                ->groupBy('nomor_form_lembur')
                ->get();

        foreach ($queryAll as $key => $value) {

            $queryDataLembur = DataLembur::whereRaw('
                        nomor_form_lembur = "' . $nomor_form_lembur . '"
                        AND enroll_id = "' . $value['enroll_id'] . '"
                ')
                ->update([
                    'tanggal_berjalan' => $tanggal_lembur,
                    'tanggal_absen' => $tanggal_lembur,
                    'mulai_jam_lembur' => $mulai_jam_lembur,
                    'akhir_jam_lembur' => $akhir_jam_lembur,
                    'jumlah_jam_lembur' => $jumlah_jam_lembur,
                    'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                    'catatan' => strtoupper($catatan),
                    'operator' => $email
                ]);

            $queryMaster = 0;

            if($queryDataLembur) {

                $queryMaster = MasterDataAbsenKehadiran::whereRaw('
                        tanggal_berjalan = "' . $tanggal_lembur . '"
                        AND enroll_id = "' . $value['enroll_id'] . '"
                    ')
                ->update([
                    'nomor_form_lembur' => $nomor_form_lembur,
                    'mulai_jam_lembur' => $mulai_jam_lembur,
                    'akhir_jam_lembur' => $akhir_jam_lembur,
                    'jumlah_jam_lembur' => $jumlah_jam_lembur,
                    'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
                    'jumlah_jam_istirahat_lembur' => $jumlah_jam_istirahat,
                    'catatan_hrd' => strtoupper($catatan),
                    'operator' => $email
                ]);

            }

        }
        return Response()->json($queryMaster);

    }

    public function tambahkaryawan(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $nomor_form_lembur = $request->nomor_form_lembur;
        $enroll_id_array = $request->selectEmp;

        $mulai_jam_lembur = $request->mulai_jam_lembur;
        $explodeMulaiLembur = explode(" ", $mulai_jam_lembur);
        $tanggal_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2);
        $mulai_jam_lembur = substr($explodeMulaiLembur[0], 6, 4) . '-' . substr($explodeMulaiLembur[0], 3, 2) . '-' . substr($explodeMulaiLembur[0], 0, 2) . " " . $explodeMulaiLembur[1];

        $akhir_jam_lembur = $request->akhir_jam_lembur;
        $explodeAkhirLembur = explode(" ", $akhir_jam_lembur);
        $akhir_jam_lembur = substr($explodeAkhirLembur[0], 6, 4) . '-' . substr($explodeAkhirLembur[0], 3, 2) . '-' . substr($explodeAkhirLembur[0], 0, 2) . " " . $explodeAkhirLembur[1];

        $jumlah_jam_lembur = $request->jumlah_jam_lembur;
        $jumlah_jam_istirahat = $request->jumlah_jam_istirahat;
        $catatan = $request->catatan;

        $queryMaster = 0;
        if (!empty($enroll_id_array[0])) {

            foreach ($enroll_id_array as $key => $value) {

                $queryMaster =  MasterDataAbsenKehadiran::selectRaw('
                                master_data_absen_kehadiran.uuid,
                                master_data_absen_kehadiran.nomor_form_lembur,
                                master_data_absen_kehadiran.tanggal_berjalan,
                                master_data_absen_kehadiran.kode_hari,
                                master_data_absen_kehadiran.nama_hari,
                                master_data_absen_kehadiran.mulai_jam_kerja,
                                master_data_absen_kehadiran.akhir_jam_kerja,
                                master_data_absen_kehadiran.absen_masuk_kerja,
                                master_data_absen_kehadiran.absen_pulang_kerja,
                                master_data_absen_kehadiran.enroll_id,
                                employee_atribut.nik,
                                employee_atribut.employee_id,
                                employee_atribut.employee_name,
                                employee_atribut.site_nirwana_id,
                                department_all.site_nirwana_name,
                                employee_atribut.department_id,
                                department_all.department_name,
                                employee_atribut.sub_dept_id,
                                department_all.sub_dept_name
                            ')
                            ->whereRaw('
                                master_data_absen_kehadiran.enroll_id = "' . $value . '"
                                AND master_data_absen_kehadiran.tanggal_berjalan = "' . $tanggal_lembur . '"
                            ')
                            ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'master_data_absen_kehadiran.enroll_id')
                            ->leftJoin('department_all', 'department_all.sub_dept_id', '=', 'master_data_absen_kehadiran.sub_dept_id')
                            ->orderBy('employee_atribut.employee_name','asc')
                            ->get();
                            info($value . " " . $tanggal_lembur);
                foreach ($queryMaster as $key1 => $value1) {

                    $queryDataLembur = DataLembur::create([
                        'uuid' => Str::uuid(),
                        'uuid_master' => $value1['uuid'],
                        'tanggal_berjalan' => $value1['tanggal_berjalan'],
                        'tanggal_absen' => $value1['tanggal_berjalan'],
                        'nomor_form_lembur' => $nomor_form_lembur,
                        'kode_hari' => $value1['kode_hari'],
                        'nama_hari' => $value1['nama_hari'],
                        'mulai_jam_kerja' => $value1['mulai_jam_kerja'],
                        'akhir_jam_kerja' => $value1['akhir_jam_kerja'],
                        'absen_masuk_kerja' => $value1['absen_masuk_kerja'],
                        'absen_pulang_kerja' => $value1['absen_pulang_kerja'],
                        'enroll_id' => $value1['enroll_id'],
                        'nik' => $value1['nik'],
                        'employee_id' => $value1['employee_id'],
                        'employee_name' => $value1['employee_name'],
                        'site_nirwana_id' => $value1['site_nirwana_id'],
                        'site_nirwana_name' => $value1['site_nirwana_name'],
                        'department_id' => $value1['department_id'],
                        'department_name' => $value1['department_name'],
                        'sub_dept_id' => $value1['sub_dept_id'],
                        'sub_dept_name' => $value1['sub_dept_name'],
                        'mulai_jam_lembur' => $mulai_jam_lembur,
                        'akhir_jam_lembur' => $akhir_jam_lembur,
                        'jumlah_jam_lembur' => $jumlah_jam_lembur,
                        'jumlah_jam_istirahat' => $jumlah_jam_istirahat,
                        'catatan' => strtoupper($catatan),
                        'operator' => $email
                    ]);

                    $queryMaster = MasterDataAbsenKehadiran::whereRaw('
                            tanggal_berjalan = "' . $tanggal_lembur . '"
                            AND enroll_id = "' . $value . '"
                        ')
                    ->update([
                        'nomor_form_lembur' => $nomor_form_lembur,
                        'mulai_jam_lembur' => $mulai_jam_lembur,
                        'akhir_jam_lembur' => $akhir_jam_lembur,
                        'jumlah_jam_lembur' => $jumlah_jam_lembur,
                        'jumlah_jam_lembur_approved' => $jumlah_jam_lembur,
                        'jumlah_jam_istirahat_lembur' => $jumlah_jam_istirahat,
                        'catatan_hrd' => strtoupper($catatan),
                        'operator' => $email
                    ]);
                }
            }
        }

        return Response()->json($queryMaster);

    }

    public function removenospl(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $selectNoSPL = $request->selectNoSPL;

        $query = 0;
        foreach ($selectNoSPL as $key1 => $nomor_form_lembur) {

            $query = DataLembur::whereRaw('
                nomor_form_lembur = "' . $nomor_form_lembur . '"
                        ')->delete();

            if($query) {
                $query = MasterDataAbsenKehadiran::whereRaw('
                nomor_form_lembur = "' . $nomor_form_lembur . '"
                ')
                ->update([
                    'nomor_form_lembur' => null,
                    'jumlah_jam_istirahat_lembur' => null,
                    'jumlah_jam_lembur' => null,
                    'jumlah_jam_lembur_approved' => null,
                    'mulai_jam_lembur' => null,
                    'akhir_jam_lembur' => null,
                    'catatan_hrd' => null,
                    'operator' => 'system',
                ]);
            }
        }
        $lembur =RekapPerhitunganLembur:: where('nomor_form_lembur',$nomor_form_lembur)->delete();

        return Response()->json($query);

    }

    // ============= Andri ========
    public function verifikasi(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $count=count($request->uuid);
        for ($i=0; $i < $count ; $i++) {
            $data=[
                'is_verifikasi'=>1,
                'verifikasi_by'=>$loggedAdmin->email
            ];
                DataLembur::where('uuid',$request->uuid[$i])->update($data);
        }
        $a=true;
        return $a;
    }
    public function verificating(Request $request)
    {
        $all_uuid=explode(",",$request->all_uuid);
        $loggedAdmin = Auth::guard('admin')->user();
        $count=count(explode(",",$request->all_uuid));
        $all_uuid=explode(',',$request->all_uuid);
        for ($i=0; $i < $count ; $i++) {
            DataLembur::where('uuid_master',$all_uuid[$i])->update([
                'is_verifikasi'=>1,
                'verifikasi_by'=>$loggedAdmin->email
            ]);
        }
    }
    public function unverifikasi($uuid){
        $loggedAdmin = Auth::guard('admin')->user();
        $data=[
            'is_verifikasi'=>0,
            'verifikasi_by'=>$loggedAdmin->email
        ];
        DataLembur::where('uuid',$uuid)->update($data);

        return true;
    }
    public function CreateSpl(Request $request) {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $kodelembur = "SPL/HR";
        $thnbln = date("ym");

        $getlastnomorform =  DataLembur::select('nomor_form_lembur')
                                        ->groupby('nomor_form_lembur')
                                        ->orderby('nomor_form_lembur', 'desc')
                                        ->first();

        if($getlastnomorform == "") {
            $nomor = "0000";
        } else {
            $nomor = $getlastnomorform->nomor_form_lembur;
        }

        $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
        $nomor_form_lembur = $kodelembur . "/" . $thnbln . "/" . $nomorform;

        $data_lembur=$request->arrayHtml;
        $spl=[];
        $master_absen=[];
        $sudah_ada=[];
        foreach ($data_lembur as $key => $value) {
            $string = $value['waktu_jam_lembur_edit'];
            $parts = explode(" - ", $string);

            $oldDate = $parts[0];
            $mulai_jam_lembur = date('Y-m-d H:i', strtotime($oldDate));
            $oldDate1 = $parts[1];
            $akhir_jam_lembur = date('Y-m-d H:i', strtotime($oldDate1));
            $cek=DataLembur::where('tanggal_berjalan',$value['tanggal_berjalan'])->where( 'enroll_id', $value['enroll_id'])->count();
            if($cek==0){
                $spl=[
                    'uuid' => Str::uuid(),
                    'uuid_master' => $value['uuid_master'],
                    'tanggal_berjalan' => $value['tanggal_berjalan'],
                    'tanggal_absen' => $value['tanggal_berjalan'],
                    'nomor_form_lembur' => $nomor_form_lembur,
                    'kode_hari' => $value['kode_hari'],
                    'nama_hari' => $value['nama_hari'],
                    'mulai_jam_kerja' => $value['mulai_jam_kerja'],
                    'akhir_jam_kerja' => $value['akhir_jam_kerja'],
                    'absen_masuk_kerja' => $value['absen_masuk_kerja'],
                    'absen_pulang_kerja' => $value['absen_pulang_kerja'],
                    'enroll_id' => $value['enroll_id'],
                    'nik' => $value['nik'],
                    'employee_id' => $value['employee_id'],
                    'employee_name' => $value['employee_name'],
                    'site_nirwana_id' => $value['site_nirwana_id'],
                    'site_nirwana_name' => $value['site_nirwana_name'],
                    'department_id' => $value['department_id'],
                    'department_name' => $value['department_name'],
                    'sub_dept_id' => $value['sub_dept_id'],
                    'sub_dept_name' => $value['sub_dept_name'],
                    'mulai_jam_lembur' =>  $mulai_jam_lembur,
                    'akhir_jam_lembur' =>  $akhir_jam_lembur,
                    'jumlah_jam_lembur' =>  $value['jumlah_jam_lembur_edit'],
                    'jumlah_jam_istirahat' => $value['jumlah_jam_istirahat_edit'],
                    'catatan' =>  $value['catatan_hrd_edit'],
                    'operator' => $email
                ];
                DataLembur::create( $spl);
                $master_absen=[
                    'nomor_form_lembur' => $nomor_form_lembur,
                    'kelebihan_jam_kerja_l1' => '0',
                    'kelebihan_jam_kerja_l2' => '0',
                    'kelebihan_jam_kerja_l3' => '0',
                    'kelebihan_jam_kerja_l4' => '0',
                    'mulai_jam_lembur' => $mulai_jam_lembur,
                    'akhir_jam_lembur' => $akhir_jam_lembur,
                    'jumlah_jam_lembur' => $value['jumlah_jam_lembur_edit'],
                    'jumlah_jam_lembur_approved' => $value['jumlah_jam_lembur_edit'],
                    'jumlah_jam_istirahat_lembur' => $value['jumlah_jam_istirahat_edit'],
                    'catatan_hrd' => $value['catatan_hrd_edit'],
                    'operator' => $email
                ];
                MasterDataAbsenKehadiran::where('uuid',$value['uuid_master'])->update($master_absen);
            }
            else{
                $sudah_ada[]=[
                    'nik' => $value['nik'],
                    'employee_name' => $value['employee_name'],
                ];
            }

       }
       return Response()->json($sudah_ada);
    }
    public function import_data_lembur(Request $request){
        $employee=EmployeeAtribut::where('site_nirwana_id','NAG')->pluck('enroll_id')->toArray();
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        $data=Excel::toArray([],$request->file('excel_file'));
        $enroll_id=[];
        $nik=[];
        $tanggal=[];
        $employee_name=[];
        $dari=[];
        $sampai=[];
        $act_in=[];
        $act_out=[];
        $jumlah_lembur=[];
        $jumlah_jam_istirahat=[];
        $keterangan_lembur=[];
        $status_absen=[];
        $actual=[];
        $count=[];
        $arrayEmployee=[];
        for($i=4;$i<count($data[0]);$i++){
            $count=count(MasterDataAbsenKehadiran::where('enroll_id',(int)substr($data[0][$i][3],-4))->where('tanggal_berjalan',gmdate("Y-m-d", ($data[0][$i][1] - 25569) * 86400))->get());
            if($count>0){
                array_push($enroll_id,(int)substr($data[0][$i][3],-4));
                array_push($nik,$data[0][$i][3]);
                $excel_date = $data[0][$i][1];
                $unix_date = ($excel_date - 25569) * 86400;
                $excel_date = 25569 + ($unix_date / 86400);
                $unix_date = ($excel_date - 25569) * 86400;
                array_push($tanggal,gmdate("Y-m-d", $unix_date));
                array_push($employee_name,$data[0][$i][2]);
                $the_value = $data[0][$i][6];
                $total = ($the_value * 24)+0.0001;
                $hours = floor($total);
                $hours_display =sprintf("%02d", $hours);
                $minute_fraction = $total - $hours;
                $minutes = $minute_fraction * 60;
                $minutes_display =sprintf("%02d", $minutes);
                $minutes_whole = floor( $minutes );
                $seconds_fraction = $minutes - $minutes_whole;
                $seconds = $seconds_fraction * 60;
                $seconds_display =sprintf("%02d", $seconds);
                $display = $hours_display . ":" . $minutes_display. ":" . $seconds_display;
                array_push($dari,$display);
                $the_values = $data[0][$i][7];
                $totals = ($the_values * 24)+0.0001;
                $hourss = floor($totals);
                $hours_displays =sprintf("%02d", $hourss);
                $minute_fractions = $totals - $hourss;
                $minutess = $minute_fractions * 60;
                $minutes_displays =sprintf("%02d", $minutess);
                $minutes_wholes = floor( $minutess );
                $seconds_fractions = $minutess - $minutes_wholes;
                $secondss = $seconds_fractions * 60;
                $seconds_displays =sprintf("%02d", $secondss);
                $displays = $hours_displays . ":" . $minutes_displays. ":" . $seconds_displays;
                array_push($sampai,$displays);
                $actual=MasterDataAbsenKehadiran::where('enroll_id',(int)substr($data[0][$i][3],-4))->where('tanggal_berjalan',gmdate("Y-m-d", $unix_date))->first();
                if($actual->absen_masuk_kerja=='' || $actual->absen_masuk_kerja==null){
                    array_push($act_in,'');
                }else{
                    array_push($act_in,$actual->absen_masuk_kerja);
                }
                if($actual->absen_pulang_kerja==''){
                    array_push($act_out,'');
                }else{
                    array_push($act_out,$actual->absen_pulang_kerja);
                }
                $starttimestamp = strtotime($display);
                $endtimestamp = strtotime($displays);
                $difference = abs($endtimestamp - $starttimestamp)/3600;
                $timeDifference=$difference-$data[0][$i][7];

                $the_valuess = $data[0][$i][8];
                $totalss = ($the_valuess * 24)+0.0001;
                $hoursss = floor($totalss);
                $hours_displayss =sprintf("%02d", $hoursss);
                $minute_fractionss = $totalss - $hoursss;
                $minutesss = $minute_fractionss * 60;
                $minutes_displayss =sprintf("%02d", $minutesss);
                $minutes_wholess = floor( $minutesss );
                $seconds_fractionss = $minutesss - $minutes_wholess;
                $secondsss = $seconds_fractionss * 60;
                $seconds_displayss =sprintf("%02d", $secondsss);
                $displayss = $hours_displayss . ":" . $minutes_displayss. ":" . $seconds_displayss;
                $hour_istirahat=(((int)$hours_displayss)*60+(int)$minutes_displayss)/60;
                $hour_lembur=$difference-$hour_istirahat;
                array_push($jumlah_lembur,$hour_lembur);
                array_push($jumlah_jam_istirahat,$hour_istirahat);
                array_push($keterangan_lembur,$data[0][$i][5]);
                if($actual->status_absen==''){
                    array_push($status_absen,'');
                }else{
                    array_push($status_absen,$actual->status_absen);
                }
                foreach($enroll_id as $key=>$value){
                    if(isset($act_in[$key])){
                        $actual_in=$act_in[$key];
                    }else{
                        $actual_in=0;
                    }
                    if(isset($act_out[$key])){
                        $actual_out=$act_out[$key];
                    }else{
                        $actual_out=0;
                    }
                    $arrayEmployee[$key]=[
                        'enroll_id'=>$enroll_id[$key],
                        'nik'=>$nik[$key],
                        'tanggal'=>$tanggal[$key],
                        'employee_name'=>$employee_name[$key],
                        'dari'=>$dari[$key],
                        'sampai'=>$sampai[$key],
                        'act_in'=>$actual_in,
                        'act_out'=>$actual_out,
                        'jumlah_lembur'=>$jumlah_lembur[$key],
                        'jumlah_jam_istirahat'=>$jumlah_jam_istirahat[$key],
                        'keterangan_lembur'=>$keterangan_lembur[$key],
                        'status_absen'=>$status_absen[$key]
                    ];
                }
            }
            $kodelembur = "SPL/HR";
            $thnbln = date("ym");
            $getlastnomorform =  DataLembur::select('nomor_form_lembur')
                                                    ->groupby('nomor_form_lembur')
                                                    ->orderby('nomor_form_lembur', 'desc')
                                                    ->first();
            if($getlastnomorform == "") {
                //info("Count : Kosong");
                $nomor = "0000";
            } else {
                $nomor = $getlastnomorform->nomor_form_lembur;
            }
            $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
            $nomor_form_lembur = $kodelembur . "/" . $thnbln . "/" . $nomorform;
            $arrayOvertime=[];
            foreach($arrayEmployee as $key=>$value){
                if(!in_array($value['enroll_id'],$employee)){
                    continue;
                }
                if($value['enroll_id']==null||$value['nik']==null||$value['tanggal']==null||$value['employee_name']==null||$value['dari']==null||$value['sampai']==null||$value['jumlah_lembur']==null){
                    continue;
                }
                $arrayOvertime[$key]=[
                    'nomor_form_lembur'=>$nomor_form_lembur,
                    'enroll_id'=>$value['enroll_id'],
                    'nik'=>$value['nik'],
                    'tanggal'=>$value['tanggal'],
                    'employee_name'=>$value['employee_name'],
                    'dari'=>$value['dari'],
                    'sampai'=>$value['sampai'],
                    'act_in'=>$value['act_in'],
                    'act_out'=>$value['act_out'],
                    'jumlah_lembur'=>$value['jumlah_lembur'],
                    'jumlah_jam_istirahat'=>$value['jumlah_jam_istirahat'],
                    'keterangan_lembur'=>$value['keterangan_lembur'],
                    'status_absen'=>$value['status_absen'],
                ];
            }
            $arrayOvertimeEnrollId=[];
            $overtime=[];
            $no=-1;
            foreach($arrayOvertime as $key=>$value){
                $no++;
                $arrayOvertimeEnrollId[$key]=$value['enroll_id'];
                $overtime[$no]=[
                    'nomor_form_lembur'=>$nomor_form_lembur,
                    'enroll_id'=>$value['enroll_id'],
                    'nik'=>$value['nik'],
                    'tanggal'=>$value['tanggal'],
                    'employee_name'=>$value['employee_name'],
                    'dari'=>$value['dari'],
                    'sampai'=>$value['sampai'],
                    'act_in'=>$value['act_in'],
                    'act_out'=>$value['act_out'],
                    'jumlah_lembur'=>$value['jumlah_lembur'],
                    'jumlah_jam_istirahat'=>$value['jumlah_jam_istirahat'],
                    'keterangan_lembur'=>$value['keterangan_lembur'],
                    'status_absen'=>$value['status_absen'],
                ];
            }
            $ArrayOvertimeEnrollId=array_unique($arrayOvertimeEnrollId);
            $jumlah_data=count($ArrayOvertimeEnrollId);
            $overtimeResult=[];
            $no2=0;
            $absen_lembur=[];
            foreach($ArrayOvertimeEnrollId as $key=>$value){
                $no2++;
                $lembur_absen=MasterDataAbsenKehadiran::where('enroll_id',$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['enroll_id'])->where('tanggal_berjalan',$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['tanggal'])->where('nomor_form_lembur','!=','')->count();
                if($lembur_absen==1){
                    $absen_lembur='red';
                }else{
                    $absen_lembur='black';
                }
                $overtimeResult[$no2]=[
                    'nomor_form_lembur'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['nomor_form_lembur'],
                    'nik'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['nik'],
                    'enroll_id'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['enroll_id'],
                    'nik'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['nik'],
                    'employee_name'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['employee_name'],
                    'tanggal'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['tanggal'],
                    'employee_name'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['employee_name'],
                    'dari'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['dari'],
                    'sampai'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['sampai'],
                    'act_in'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['act_in'],
                    'act_out'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['act_out'],
                    'jumlah_lembur'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['jumlah_lembur'],
                    'jumlah_jam_istirahat'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['jumlah_jam_istirahat'],
                    'jumlah_data'=>$jumlah_data,
                    'keterangan_lembur'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['keterangan_lembur'],
                    'status_absen'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['status_absen'],
                    'absen_lembur'=>$absen_lembur,
                ];
            }
        }
        return $overtimeResult;
    }
    public function importing_data_lembur(Request $request){
        $employee=EmployeeAtribut::where('site_nirwana_id','NAG')->pluck('enroll_id')->toArray();
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        $data=Excel::toArray([],$request->file('excel_file'));
        $enroll_id=[];
        $nik=[];
        $tanggal=[];
        $employee_name=[];
        $dari=[];
        $sampai=[];
        $jumlah_lembur=[];
        $jumlah_jam_istirahat=[];
        $keterangan_lembur=[];
        for($i=4;$i<count($data[0]);$i++){
            array_push($enroll_id,(int)substr($data[0][$i][3],-4));
            array_push($nik,$data[0][$i][3]);
            $excel_date = $data[0][$i][1];
            $unix_date = ($excel_date - 25569) * 86400;
            $excel_date = 25569 + ($unix_date / 86400);
            $unix_date = ($excel_date - 25569) * 86400;
            array_push($tanggal,gmdate("Y-m-d", $unix_date));
            array_push($employee_name,$data[0][$i][2]);
            $the_value = $data[0][$i][6];
            $total = ($the_value * 24)+0.0001;
            $hours = floor($total);
            $hours_display =sprintf("%02d", $hours);
            $minute_fraction = $total - $hours;
            $minutes = $minute_fraction * 60;
            $minutes_display =sprintf("%02d", $minutes);
            $minutes_whole = floor( $minutes );
            $seconds_fraction = $minutes - $minutes_whole;
            $seconds = $seconds_fraction * 60;
            $seconds_display =sprintf("%02d", $seconds);
            $display = $hours_display . ":" . $minutes_display. ":" . $seconds_display;
            array_push($dari,$display);
            $the_values = $data[0][$i][7];
            $totals = ($the_values * 24)+0.0001;
            $hourss = floor($totals);
            $hours_displays =sprintf("%02d", $hourss);
            $minute_fractions = $totals - $hourss;
            $minutess = $minute_fractions * 60;
            $minutes_displays =sprintf("%02d", $minutess);
            $minutes_wholes = floor( $minutess );
            $seconds_fractions = $minutess - $minutes_wholes;
            $secondss = $seconds_fractions * 60;
            $seconds_displays =sprintf("%02d", $secondss);
            $displays = $hours_displays . ":" . $minutes_displays. ":" . $seconds_displays;
            array_push($sampai,$displays);
            $starttimestamp = strtotime($display);
            $endtimestamp = strtotime($displays);
            $difference = abs($endtimestamp - $starttimestamp)/3600;
            $timeDifference=$difference-$data[0][$i][7];

            $the_valuess = $data[0][$i][8];
            $totalss = ($the_valuess * 24)+0.0001;
            $hoursss = floor($totalss);
            $hours_displayss =sprintf("%02d", $hoursss);
            $minute_fractionss = $totalss - $hoursss;
            $minutesss = $minute_fractionss * 60;
            $minutes_displayss =sprintf("%02d", $minutesss);
            $minutes_wholess = floor( $minutesss );
            $seconds_fractionss = $minutesss - $minutes_wholess;
            $secondsss = $seconds_fractionss * 60;
            $seconds_displayss =sprintf("%02d", $secondsss);
            $displayss = $hours_displayss . ":" . $minutes_displayss. ":" . $seconds_displayss;
            $hour_istirahat=(((int)$hours_displayss)*60+(int)$minutes_displayss)/60;
            $hour_lembur=$difference-$hour_istirahat;
            array_push($jumlah_lembur,$hour_lembur);
            array_push($jumlah_jam_istirahat,$hour_istirahat);
            array_push($keterangan_lembur,$data[0][$i][5]);
        }
        $arrayEmployee=[];
        foreach($enroll_id as $key=>$value){
            $arrayEmployee[$key]=[
                'enroll_id'=>$enroll_id[$key],
                'nik'=>$nik[$key],
                'tanggal'=>$tanggal[$key],
                'employee_name'=>$employee_name[$key],
                'dari'=>$dari[$key],
                'sampai'=>$sampai[$key],
                'jumlah_lembur'=>$jumlah_lembur[$key],
                'jumlah_jam_istirahat'=>$jumlah_jam_istirahat[$key],
                'keterangan_lembur'=>$keterangan_lembur[$key],
            ];
        }
        $kodelembur = "SPL/HR";
        $thnbln = date("ym");
        $getlastnomorform =  DataLembur::select('nomor_form_lembur')
                                                ->groupby('nomor_form_lembur')
                                                ->orderby('nomor_form_lembur', 'desc')
                                                ->first();
        if($getlastnomorform == "") {
            //info("Count : Kosong");
            $nomor = "0000";
        } else {
            $nomor = $getlastnomorform->nomor_form_lembur;
        }
        $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
        $nomor_form_lembur = $kodelembur . "/" . $thnbln . "/" . $nomorform;
        $arrayOvertime=[];
        foreach($arrayEmployee as $key=>$value){
            if(!in_array($value['enroll_id'],$employee)){
                continue;
            }
            if($value['enroll_id']==null||$value['nik']==null||$value['tanggal']==null||$value['employee_name']==null||$value['employee_name']==null||$value['dari']==null||$value['sampai']==null||$value['jumlah_lembur']==null){
                continue;
            }
            $arrayOvertime[$key]=[
                'nomor_form_lembur'=>$nomor_form_lembur,
                'enroll_id'=>$value['enroll_id'],
                'nik'=>$value['nik'],
                'tanggal'=>$value['tanggal'],
                'employee_name'=>$value['employee_name'],
                'dari'=>$value['dari'],
                'sampai'=>$value['sampai'],
                'jumlah_lembur'=>$value['jumlah_lembur'],
                'jumlah_jam_istirahat'=>$value['jumlah_jam_istirahat'],
                'keterangan_lembur'=>$value['keterangan_lembur']
            ];
        }
        $arrayOvertimeEnrollId=[];
        $overtime=[];
        $no=-1;
        foreach($arrayOvertime as $key=>$value){
            $no++;
            $arrayOvertimeEnrollId[$key]=$value['enroll_id'];
            $overtime[$no]=[
                'nomor_form_lembur'=>$nomor_form_lembur,
                'enroll_id'=>$value['enroll_id'],
                'nik'=>$value['nik'],
                'tanggal'=>$value['tanggal'],
                'employee_name'=>$value['employee_name'],
                'dari'=>$value['dari'],
                'sampai'=>$value['sampai'],
                'jumlah_lembur'=>$value['jumlah_lembur'],
                'jumlah_jam_istirahat'=>$value['jumlah_jam_istirahat'],
                'keterangan_lembur'=>$value['keterangan_lembur']
            ];
        }
        $ArrayOvertimeEnrollId=array_unique($arrayOvertimeEnrollId);
        $overtimeResultS=[];
        $no2=0;
        foreach($ArrayOvertimeEnrollId as $key=>$value){
            $no2++;
            $overtimeResultS[$no2]=[
                'nomor_form_lembur'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['nomor_form_lembur'],
                'nik'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['nik'],
                'enroll_id'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['enroll_id'],
                'nik'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['nik'],
                'employee_name'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['employee_name'],
                'tanggal'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['tanggal'],
                'employee_name'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['employee_name'],
                'dari'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['dari'],
                'sampai'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['sampai'],
                'jumlah_lembur'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['jumlah_lembur'],
                'jumlah_jam_istirahat'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['jumlah_jam_istirahat'],
                'keterangan_lembur'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['keterangan_lembur'],
            ];
        }
        $overtimeResult=[];
        $no3=0;
        foreach($overtimeResultS as $key=>$value){
            if(count(MasterDataAbsenKehadiran::where('enroll_id',$value['enroll_id'])->where('tanggal_berjalan',$value['tanggal'])->where('nomor_form_lembur','!=','')->get())==1){
                continue;
            }
            $no3++;
            $overtimeResult[$no3]=[
                'nomor_form_lembur'=>$value['nomor_form_lembur'],
                'nik'=>$value['nik'],
                'enroll_id'=>$value['enroll_id'],
                'nik'=>$value['nik'],
                'employee_name'=>$value['employee_name'],
                'tanggal'=>$value['tanggal'],
                'employee_name'=>$value['employee_name'],
                'dari'=>$value['dari'],
                'sampai'=>$value['sampai'],
                'jumlah_lembur'=>$value['jumlah_lembur'],
                'jumlah_jam_istirahat'=>$value['jumlah_jam_istirahat'],
                'keterangan_lembur'=>$value['keterangan_lembur'],
            ];
        }
        foreach($overtimeResult as $key=>$value){
            $queryMaster[$key] =  MasterDataAbsenKehadiran::selectRaw('
                master_data_absen_kehadiran.uuid,
                master_data_absen_kehadiran.nomor_form_lembur,
                master_data_absen_kehadiran.tanggal_berjalan,
                master_data_absen_kehadiran.kode_hari,
                master_data_absen_kehadiran.nama_hari,
                master_data_absen_kehadiran.mulai_jam_kerja,
                master_data_absen_kehadiran.akhir_jam_kerja,
                master_data_absen_kehadiran.absen_masuk_kerja,
                master_data_absen_kehadiran.absen_pulang_kerja,
                master_data_absen_kehadiran.enroll_id,
                employee_atribut.nik,
                employee_atribut.employee_id,
                employee_atribut.employee_name,
                employee_atribut.site_nirwana_id,
                department_all.site_nirwana_name,
                employee_atribut.department_id,
                department_all.department_name,
                employee_atribut.sub_dept_id,
                department_all.sub_dept_name
            ')
            ->whereRaw('
                master_data_absen_kehadiran.enroll_id = "' . $value['enroll_id'] . '"
                AND master_data_absen_kehadiran.tanggal_berjalan = "' . $value['tanggal'] . '"
            ')
            ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'master_data_absen_kehadiran.enroll_id')
            ->leftJoin('department_all', 'department_all.sub_dept_id', '=', 'master_data_absen_kehadiran.sub_dept_id')
            ->orderBy('employee_atribut.employee_name','asc')
            ->get();
        }
        $x=[];
        foreach($queryMaster as $key=>$value){
            if(isset($value[0])){
                if(empty($value[0]['nomor_form_lembur'])){
                    $queryDataLembur = DataLembur::create([
                        'uuid' => Str::uuid(),
                        'uuid_master' => $value[0]['uuid'],
                        'tanggal_berjalan' => $value[0]['tanggal_berjalan'],
                        'tanggal_absen' => $value[0]['tanggal_berjalan'],
                        'nomor_form_lembur' => $nomor_form_lembur,
                        'kode_hari' => $value[0]['kode_hari'],
                        'nama_hari' => $value[0]['nama_hari'],
                        'mulai_jam_kerja' => $value[0]['mulai_jam_kerja'],
                        'akhir_jam_kerja' => $value[0]['akhir_jam_kerja'],
                        'absen_masuk_kerja' => $value[0]['absen_masuk_kerja'],
                        'absen_pulang_kerja' => $value[0]['absen_pulang_kerja'],
                        'enroll_id' => $value[0]['enroll_id'],
                        'nik' => $value[0]['nik'],
                        'employee_id' => $value[0]['employee_id'],
                        'employee_name' => $value[0]['employee_name'],
                        'site_nirwana_id' => $value[0]['site_nirwana_id'],
                        'site_nirwana_name' => $value[0]['site_nirwana_name'],
                        'department_id' => $value[0]['department_id'],
                        'department_name' => $value[0]['department_name'],
                        'sub_dept_id' => $value[0]['sub_dept_id'],
                        'sub_dept_name' => $value[0]['sub_dept_name'],
                        'mulai_jam_lembur' => $overtimeResult[$key]['tanggal'].' '.$overtimeResult[$key]['dari'],
                        'akhir_jam_lembur' => $overtimeResult[$key]['tanggal'].' '.$overtimeResult[$key]['sampai'],
                        'jumlah_jam_lembur' => $overtimeResult[$key]['jumlah_lembur'],
                        'jumlah_jam_istirahat' => $overtimeResult[$key]['jumlah_jam_istirahat'],
                        'catatan' => $overtimeResult[$key]['keterangan_lembur'],
                        'operator' => $email
                    ]);
                }else{
                    $queryDataLembur=DataLembur::where('tanggal_berjalan',$value[0]['tanggal_berjalan'])->where('enroll_id',$value[0]['enroll_id'])->update([
                        'nomor_form_lembur' => $nomor_form_lembur,
                        'mulai_jam_kerja' => $value[0]['mulai_jam_kerja'],
                        'akhir_jam_kerja' => $value[0]['akhir_jam_kerja'],
                        'absen_masuk_kerja' => $value[0]['absen_masuk_kerja'],
                        'absen_pulang_kerja' => $value[0]['absen_pulang_kerja'],
                        'mulai_jam_lembur' => $overtimeResult[$key]['tanggal'].' '.$overtimeResult[$key]['dari'],
                        'akhir_jam_lembur' => $overtimeResult[$key]['tanggal'].' '.$overtimeResult[$key]['sampai'],
                        'jumlah_jam_lembur' => $overtimeResult[$key]['jumlah_lembur'],
                        'jumlah_jam_istirahat' => $overtimeResult[$key]['jumlah_jam_istirahat'],
                        'operator' => $email,
                        'catatan' => $overtimeResult[$key]['keterangan_lembur'],
                    ]);
                }
                if($queryDataLembur){
                    MasterDataAbsenKehadiran::whereRaw('
                    tanggal_berjalan = "' . $value[0]['tanggal_berjalan'] . '"
                    AND enroll_id = "' . $value[0]['enroll_id'] . '"')
                    ->update([
                        'nomor_form_lembur' => $nomor_form_lembur,
                        'kelebihan_jam_kerja_l1' => '0',
                        'kelebihan_jam_kerja_l2' => '0',
                        'kelebihan_jam_kerja_l3' => '0',
                        'kelebihan_jam_kerja_l4' => '0',
                        'mulai_jam_lembur' => $overtimeResult[$key]['tanggal'].' '.$overtimeResult[$key]['dari'],
                        'akhir_jam_lembur' => $overtimeResult[$key]['tanggal'].' '.$overtimeResult[$key]['sampai'],
                        'jumlah_jam_lembur' => $overtimeResult[$key]['jumlah_lembur'],
                        'jumlah_jam_istirahat_lembur' => $overtimeResult[$key]['jumlah_jam_istirahat'],
                        'jumlah_jam_lembur_approved' => $overtimeResult[$key]['jumlah_lembur'],
                        'operator' => $email,
                        'catatan_hrd' => $overtimeResult[$key]['keterangan_lembur'],
                    ]);
                }
            }
        }
    }

    // EDITAN RIZKY :
    // public function importing_data_lembur(Request $request){
    //     $employee=EmployeeAtribut::where('site_nirwana_id','NAG')->pluck('enroll_id')->toArray();
    //     $loggedAdmin = Auth::guard('admin')->user();
    //     $email = $loggedAdmin->email;
    //     $data=Excel::toArray([],$request->file('excel_file'));
    //     $enroll_id=[];
    //     $nik=[];
    //     $tanggal=[];
    //     $employee_name=[];
    //     $dari=[];
    //     $sampai=[];
    //     $jumlah_lembur=[];
    //     $jumlah_jam_istirahat=[];
    //     $keterangan_lembur=[];
    //     for($i=4;$i<count($data[0]);$i++){
    //         array_push($enroll_id,(int)substr($data[0][$i][3],5,4));
    //         array_push($nik,$data[0][$i][3]);
    //         $excel_date = $data[0][$i][1];
    //         $unix_date = ($excel_date - 25569) * 86400;
    //         $excel_date = 25569 + ($unix_date / 86400);
    //         $unix_date = ($excel_date - 25569) * 86400;
    //         array_push($tanggal,gmdate("Y-m-d", $unix_date));
    //         array_push($employee_name,$data[0][$i][2]);
    //         $the_value = $data[0][$i][6];
    //         $total = ($the_value * 24)+0.0001;
    //         $hours = floor($total);
    //         $hours_display =sprintf("%02d", $hours);
    //         $minute_fraction = $total - $hours;
    //         $minutes = $minute_fraction * 60;
    //         $minutes_display =sprintf("%02d", $minutes);
    //         $minutes_whole = floor( $minutes );
    //         $seconds_fraction = $minutes - $minutes_whole;
    //         $seconds = $seconds_fraction * 60;
    //         $seconds_display =sprintf("%02d", $seconds);
    //         $display = $hours_display . ":" . $minutes_display. ":" . $seconds_display;
    //         array_push($dari,$display);
    //         $the_values = $data[0][$i][7];
    //         $totals = ($the_values * 24)+0.0001;
    //         $hourss = floor($totals);
    //         $hours_displays =sprintf("%02d", $hourss);
    //         $minute_fractions = $totals - $hourss;
    //         $minutess = $minute_fractions * 60;
    //         $minutes_displays =sprintf("%02d", $minutess);
    //         $minutes_wholes = floor( $minutess );
    //         $seconds_fractions = $minutess - $minutes_wholes;
    //         $secondss = $seconds_fractions * 60;
    //         $seconds_displays =sprintf("%02d", $secondss);
    //         $displays = $hours_displays . ":" . $minutes_displays. ":" . $seconds_displays;
    //         array_push($sampai,$displays);
    //         $starttimestamp = strtotime($display);
    //         $endtimestamp = strtotime($displays);
    //         $difference = abs($endtimestamp - $starttimestamp)/3600;
    //         $timeDifference=$difference-$data[0][$i][7];

    //         $the_valuess = $data[0][$i][8];
    //         $totalss = ($the_valuess * 24)+0.0001;
    //         $hoursss = floor($totalss);
    //         $hours_displayss =sprintf("%02d", $hoursss);
    //         $minute_fractionss = $totalss - $hoursss;
    //         $minutesss = $minute_fractionss * 60;
    //         $minutes_displayss =sprintf("%02d", $minutesss);
    //         $minutes_wholess = floor( $minutesss );
    //         $seconds_fractionss = $minutesss - $minutes_wholess;
    //         $secondsss = $seconds_fractionss * 60;
    //         $seconds_displayss =sprintf("%02d", $secondsss);
    //         $displayss = $hours_displayss . ":" . $minutes_displayss. ":" . $seconds_displayss;
    //         $hour_istirahat=(((int)$hours_displayss)*60+(int)$minutes_displayss)/60;
    //         $hour_lembur=$difference-$hour_istirahat;
    //         array_push($jumlah_lembur,$hour_lembur);
    //         array_push($jumlah_jam_istirahat,$hour_istirahat);
    //         array_push($keterangan_lembur,$data[0][$i][5]);
    //     }
    //     $arrayEmployee=[];
    //     foreach($enroll_id as $key=>$value){
    //         $arrayEmployee[$key]=[
    //             'enroll_id'=>$enroll_id[$key],
    //             'nik'=>$nik[$key],
    //             'tanggal'=>$tanggal[$key],
    //             'employee_name'=>$employee_name[$key],
    //             'dari'=>$dari[$key],
    //             'sampai'=>$sampai[$key],
    //             'jumlah_lembur'=>$jumlah_lembur[$key],
    //             'jumlah_jam_istirahat'=>$jumlah_jam_istirahat[$key],
    //             'keterangan_lembur'=>$keterangan_lembur[$key],
    //         ];
    //     }
    //     $kodelembur = "SPL/HR";
    //     $thnbln = date("ym");
    //     $getlastnomorform =  DataLembur::select('nomor_form_lembur')
    //                                             ->groupby('nomor_form_lembur')
    //                                             ->orderby('nomor_form_lembur', 'desc')
    //                                             ->first();
    //     if($getlastnomorform == "") {
    //         //info("Count : Kosong");
    //         $nomor = "0000";
    //     } else {
    //         $nomor = $getlastnomorform->nomor_form_lembur;
    //     }
    //     $nomorform = str_pad(substr($nomor, -4) + 1,4,"0",STR_PAD_LEFT);
    //     $nomor_form_lembur = $kodelembur . "/" . $thnbln . "/" . $nomorform;
    //     $arrayOvertime=[];
    //     foreach($arrayEmployee as $key=>$value){
    //         if(!in_array($value['enroll_id'],$employee)){
    //             continue;
    //         }
    //         if($value['enroll_id']==null||$value['nik']==null||$value['tanggal']==null||$value['employee_name']==null||$value['employee_name']==null||$value['dari']==null||$value['sampai']==null||$value['jumlah_lembur']==null){
    //             continue;
    //         }
    //         $arrayOvertime[$key]=[
    //             'nomor_form_lembur'=>$nomor_form_lembur,
    //             'enroll_id'=>$value['enroll_id'],
    //             'nik'=>$value['nik'],
    //             'tanggal'=>$value['tanggal'],
    //             'employee_name'=>$value['employee_name'],
    //             'dari'=>$value['dari'],
    //             'sampai'=>$value['sampai'],
    //             'jumlah_lembur'=>$value['jumlah_lembur'],
    //             'jumlah_jam_istirahat'=>$value['jumlah_jam_istirahat'],
    //             'keterangan_lembur'=>$value['keterangan_lembur']
    //         ];
    //     }
    //     $arrayOvertimeEnrollId=[];
    //     $overtime=[];
    //     $no=-1;
    //     foreach($arrayOvertime as $key=>$value){
    //         $no++;
    //         $arrayOvertimeEnrollId[$key]=$value['enroll_id'];
    //         $overtime[$no]=[
    //             'nomor_form_lembur'=>$nomor_form_lembur,
    //             'enroll_id'=>$value['enroll_id'],
    //             'nik'=>$value['nik'],
    //             'tanggal'=>$value['tanggal'],
    //             'employee_name'=>$value['employee_name'],
    //             'dari'=>$value['dari'],
    //             'sampai'=>$value['sampai'],
    //             'jumlah_lembur'=>$value['jumlah_lembur'],
    //             'jumlah_jam_istirahat'=>$value['jumlah_jam_istirahat'],
    //             'keterangan_lembur'=>$value['keterangan_lembur']
    //         ];
    //     }
    //     $ArrayOvertimeEnrollId=array_unique($arrayOvertimeEnrollId);
    //     $overtimeResult=[];
    //     $no2=0;
    //     foreach($ArrayOvertimeEnrollId as $key=>$value){
    //         $no2++;
    //         $overtimeResult[$no2]=[
    //             'nomor_form_lembur'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['nomor_form_lembur'],
    //             'nik'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['nik'],
    //             'enroll_id'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['enroll_id'],
    //             'nik'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['nik'],
    //             'employee_name'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['employee_name'],
    //             'tanggal'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['tanggal'],
    //             'employee_name'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['employee_name'],
    //             'dari'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['dari'],
    //             'sampai'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['sampai'],
    //             'jumlah_lembur'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['jumlah_lembur'],
    //             'jumlah_jam_istirahat'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['jumlah_jam_istirahat'],
    //             'keterangan_lembur'=>$overtime[array_search($value, array_column($overtime, 'enroll_id'))]['keterangan_lembur'],
    //         ];
    //     }
    //     foreach($overtimeResult as $key=>$value){
    //         $queryMaster[$key] =  MasterDataAbsenKehadiran::selectRaw('
    //             master_data_absen_kehadiran.uuid,
    //             master_data_absen_kehadiran.nomor_form_lembur,
    //             master_data_absen_kehadiran.tanggal_berjalan,
    //             master_data_absen_kehadiran.kode_hari,
    //             master_data_absen_kehadiran.nama_hari,
    //             master_data_absen_kehadiran.mulai_jam_kerja,
    //             master_data_absen_kehadiran.akhir_jam_kerja,
    //             master_data_absen_kehadiran.absen_masuk_kerja,
    //             master_data_absen_kehadiran.absen_pulang_kerja,
    //             master_data_absen_kehadiran.enroll_id,
    //             employee_atribut.nik,
    //             employee_atribut.employee_id,
    //             employee_atribut.employee_name,
    //             employee_atribut.site_nirwana_id,
    //             department_all.site_nirwana_name,
    //             employee_atribut.department_id,
    //             department_all.department_name,
    //             employee_atribut.sub_dept_id,
    //             department_all.sub_dept_name
    //         ')
    //         ->whereRaw('
    //             master_data_absen_kehadiran.enroll_id = "' . $value['enroll_id'] . '"
    //             AND master_data_absen_kehadiran.tanggal_berjalan = "' . $value['tanggal'] . '"
    //         ')
    //         ->join('employee_atribut', 'employee_atribut.enroll_id', '=', 'master_data_absen_kehadiran.enroll_id')
    //         ->leftJoin('department_all', 'department_all.sub_dept_id', '=', 'master_data_absen_kehadiran.sub_dept_id')
    //         ->orderBy('employee_atribut.employee_name','asc')
    //         ->get();
    //     }
    //     $x=[];
    //     foreach($queryMaster as $key=>$value){
    //         if(isset($value[0])){
    //             if(empty($value[0]['nomor_form_lembur'])){
    //                 $queryDataLembur = DataLembur::create([
    //                     'uuid' => Str::uuid(),
    //                     'uuid_master' => $value[0]['uuid'],
    //                     'tanggal_berjalan' => $value[0]['tanggal_berjalan'],
    //                     'tanggal_absen' => $value[0]['tanggal_berjalan'],
    //                     'nomor_form_lembur' => $nomor_form_lembur,
    //                     'kode_hari' => $value[0]['kode_hari'],
    //                     'nama_hari' => $value[0]['nama_hari'],
    //                     'mulai_jam_kerja' => $value[0]['mulai_jam_kerja'],
    //                     'akhir_jam_kerja' => $value[0]['akhir_jam_kerja'],
    //                     'absen_masuk_kerja' => $value[0]['absen_masuk_kerja'],
    //                     'absen_pulang_kerja' => $value[0]['absen_pulang_kerja'],
    //                     'enroll_id' => $value[0]['enroll_id'],
    //                     'nik' => $value[0]['nik'],
    //                     'employee_id' => $value[0]['employee_id'],
    //                     'employee_name' => $value[0]['employee_name'],
    //                     'site_nirwana_id' => $value[0]['site_nirwana_id'],
    //                     'site_nirwana_name' => $value[0]['site_nirwana_name'],
    //                     'department_id' => $value[0]['department_id'],
    //                     'department_name' => $value[0]['department_name'],
    //                     'sub_dept_id' => $value[0]['sub_dept_id'],
    //                     'sub_dept_name' => $value[0]['sub_dept_name'],
    //                     'mulai_jam_lembur' => $overtimeResult[$key]['tanggal'].' '.$overtimeResult[$key]['dari'],
    //                     'akhir_jam_lembur' => $overtimeResult[$key]['tanggal'].' '.$overtimeResult[$key]['sampai'],
    //                     'jumlah_jam_lembur' => $overtimeResult[$key]['jumlah_lembur'],
    //                     'jumlah_jam_istirahat' => $overtimeResult[$key]['jumlah_jam_istirahat'],
    //                     'catatan' => $overtimeResult[$key]['keterangan_lembur'],
    //                     'operator' => $email
    //                 ]);
    //             }else{
    //                 $queryDataLembur=DataLembur::where('tanggal_berjalan',$value[0]['tanggal_berjalan'])->where('enroll_id',$value[0]['enroll_id'])->update([
    //                     'nomor_form_lembur' => $nomor_form_lembur,
    //                     'mulai_jam_kerja' => $value[0]['mulai_jam_kerja'],
    //                     'akhir_jam_kerja' => $value[0]['akhir_jam_kerja'],
    //                     'absen_masuk_kerja' => $value[0]['absen_masuk_kerja'],
    //                     'absen_pulang_kerja' => $value[0]['absen_pulang_kerja'],
    //                     'mulai_jam_lembur' => $overtimeResult[$key]['tanggal'].' '.$overtimeResult[$key]['dari'],
    //                     'akhir_jam_lembur' => $overtimeResult[$key]['tanggal'].' '.$overtimeResult[$key]['sampai'],
    //                     'jumlah_jam_lembur' => $overtimeResult[$key]['jumlah_lembur'],
    //                     'jumlah_jam_istirahat' => $overtimeResult[$key]['jumlah_jam_istirahat'],
    //                     'operator' => $email,
    //                     'catatan' => $overtimeResult[$key]['keterangan_lembur'],
    //                 ]);
    //             }
    //             if($queryDataLembur){
    //                 MasterDataAbsenKehadiran::whereRaw('
    //                 tanggal_berjalan = "' . $value[0]['tanggal_berjalan'] . '"
    //                 AND enroll_id = "' . $value[0]['enroll_id'] . '"')
    //                 ->update([
    //                     'nomor_form_lembur' => $nomor_form_lembur,
    //                     'kelebihan_jam_kerja_l1' => '0',
    //                     'kelebihan_jam_kerja_l2' => '0',
    //                     'kelebihan_jam_kerja_l3' => '0',
    //                     'kelebihan_jam_kerja_l4' => '0',
    //                     'mulai_jam_lembur' => $overtimeResult[$key]['tanggal'].' '.$overtimeResult[$key]['dari'],
    //                     'akhir_jam_lembur' => $overtimeResult[$key]['tanggal'].' '.$overtimeResult[$key]['sampai'],
    //                     'jumlah_jam_lembur' => $overtimeResult[$key]['jumlah_lembur'],
    //                     'jumlah_jam_istirahat' => $overtimeResult[$key]['jumlah_jam_istirahat'],
    //                     'jumlah_jam_lembur_approved' => $overtimeResult[$key]['jumlah_lembur'],
    //                     'operator' => $email,
    //                     'catatan_hrd' => $overtimeResult[$key]['keterangan_lembur'],
    //                 ]);
    //             }
    //         }
    //     }
    // }

    public function get_last_nomor_form_lembur(){
        $nomor_form_lembur = DataLembur::select('nomor_form_lembur')->orderBy('created_at','desc')->limit(1)->pluck('nomor_form_lembur')[0];
        $last_nomor =substr($nomor_form_lembur,12);
        $ldate = date('Ym');
        return 'SPL/HR/'.substr($ldate,2).'/'.sprintf("%04d", (int)$last_nomor+1);
    }
}
