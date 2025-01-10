<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Models\MasterDataAbsenKehadiran;
use App\Models\RefAbsenIjin;
use App\Models\DataKoreksiPotongan;
use App\Models\DataKoreksiUpah;

use App\Models\RekapPerhitunganLembur;
use App\Models\RekapKehadiranKaryawan;
use App\Models\DataLembur;
use App\Models\EmployeeGrading;
use App\Models\DataAbsenPerijinan;
use App\Models\RekapPerhitunganIKS;
use App\Models\RekapPerhitunganDTPC;
use App\Models\EmployeeBpjs;
use App\Models\EmployeeAtribut;
use App\Models\TunjanganKaryawan;
use App\Models\RekapPerhitunganPayroll;
use App\Models\BpjsSetting;
use App\Models\GradingSalary;
use App\Models\DepartmentAll;
use App\Models\Jurnal;

use Illuminate\Support\Str;
use App\Models\RekapPerhitunganKehadiranKaryawan;
use App\Exports\payrollTransferExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

/**
 * Class MdAbsenHadirController
 * @package App\Http\Controllers\Hris
 */
class ProsesPayrollController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Data Kehadiran Karyawan';
    }

    // public function index()
    // {
    //     # code...
    // }

    // rekap_absen
    public function export_excel_transfer(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        $periode_payroll=request()->param1;
        $status_staff=request()->param2;
        $department_id=request()->param3;
        $sub_dept_id=request()->param4;

        $inStatusStaff = "";
        if($status_staff) {
            $inStatusStaff = ' AND kategori_karyawan = "' . $status_staff . '"';
        }

        $inDepartment = "";
        if($department_id) {
            $department_name=DepartmentAll::select('department_name')->where('department_id',$department_id)->pluck('department_name')[0];
            $inDepartment = ' AND nama_department = "' . $department_name . '"';
        }

        $inSubDepartmentName = "";
        if($sub_dept_id) {
            $sub_department_name=DepartmentAll::select('sub_dept_name')->where('sub_dept_id',$sub_dept_id)->pluck('sub_dept_name')[0];
            $inSubDepartmentName = ' AND nama_bagian = "' . $sub_department_name . '"';
        }

        list($year, $month) = explode('-', $periode_payroll);
        $first=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->first();
        list($tgl_awal, $tgl_akhir) = explode(' s/d ', $first->periode_kehadiran);
        $fileName = 'payrollTransferExport_' . time() . '.xlsx';
        $periode_tahun_payroll=substr($periode_payroll,0,4);
        $periode_bulan_payroll=substr($periode_payroll,5,7);
        $department=DepartmentAll::select('department_name')->where('site_nirwana_id','NAG')->groupBy('department_id')->pluck('department_name');
        $departments=[];
        foreach($department as $dept){
            array_push($departments,$dept);
        }
        $department_names='("'.implode('","', $departments).'")';
        $karyawan_bank_bni = RekapPerhitunganPayroll::selectRaw('enroll_id,nik,employee_name,total_kehadiran_net,upah_per_bulan,tunjangan_karyawan_rupiah,lembur1_rupiah,lembur2_rupiah,lembur3_rupiah,lembur4_rupiah,koreksi_upah_rupiah,koreksi_potongan_rupiah,potongan_kehadiran_rupiah,potongan_dtpc_rupiah,potongan_iks_rupiah,upah_bruto_rupiah,pph21,upah_neto_rupiah,total_bpjs_tk,total_bpjs_ks,iuran_serikat_rupiah,iuran_koperasi,total_upah_thp_rupiah,nama_bank,nomor_rekening_bank,tanggal_resign')->whereRaw('periode_tahun_payroll = "' . $periode_tahun_payroll . '" and periode_bulan_payroll = "' . $periode_bulan_payroll.'" and nama_bank="BNI" and total_kehadiran_net>0 and nama_department in '.$department_names.''.$inDepartment.''.$inSubDepartmentName.''.$inStatusStaff.'')->where(function ($query)use($tgl_awal){
            $query->where('tanggal_resign',null)->orWhere('tanggal_resign','>',$tgl_awal);
        })->get();
        $jumlah_karyawan_bank_bni=count($karyawan_bank_bni);
        $karyawan_bank_cimb = RekapPerhitunganPayroll::selectRaw('enroll_id,nik,employee_name,total_kehadiran_net,upah_per_bulan,tunjangan_karyawan_rupiah,lembur1_rupiah,lembur2_rupiah,lembur3_rupiah,lembur4_rupiah,koreksi_upah_rupiah,koreksi_potongan_rupiah,potongan_kehadiran_rupiah,potongan_dtpc_rupiah,potongan_iks_rupiah,upah_bruto_rupiah,pph21,upah_neto_rupiah,total_bpjs_tk,total_bpjs_ks,iuran_serikat_rupiah,iuran_koperasi,total_upah_thp_rupiah,nama_bank,nomor_rekening_bank,tanggal_resign')->whereRaw('periode_tahun_payroll = "' . $periode_tahun_payroll . '" and periode_bulan_payroll = "' . $periode_bulan_payroll.'" and nama_bank="CIMB NIAGA" and total_kehadiran_net>0 and nama_department in '.$department_names.''.$inDepartment.''.$inSubDepartmentName.''.$inStatusStaff.'')->where(function ($query)use($tgl_awal){
            $query->where('tanggal_resign',null)->orWhere('tanggal_resign','>',$tgl_awal);
        })->get();
        $jumlah_karyawan_bank_cimb=count($karyawan_bank_cimb);
        $payroll=[];
        $no=-1;
        foreach($karyawan_bank_bni as $p){
            $no+=2;
            $payroll[$no]=[
                'nik'=>$p->nik,
                'rekening'=>$p->nomor_rekening_bank,
                'nama_karyawan'=>$p->employee_name,
                'jumlah'=>$p->total_upah_thp_rupiah,
                'nama_bank'=>$p->nama_bank
            ];
        }
        $no1=0;
        foreach($karyawan_bank_cimb as $p){
            $no1+=2;
            $payroll[$no1]=[
                'nik'=>$p->nik,
                'rekening'=>$p->nomor_rekening_bank,
                'nama_karyawan'=>$p->employee_name,
                'jumlah'=>$p->total_upah_thp_rupiah,
                'nama_bank'=>$p->nama_bank
            ];
        }
        $collectionPayroll=collect();
        foreach($payroll as $key=>$value){
            $collectionPayroll[$key]=$payroll[$key];
        }
        $response= Excel::download(new payrollTransferExport($jumlah_karyawan_bank_bni,$jumlah_karyawan_bank_cimb,$collectionPayroll,$periode_tahun_payroll,$periode_bulan_payroll,$tgl_awal), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;
        // return view('hris.Laporan.payroll_transfer',compact('payroll','periode_tahun_payroll','periode_bulan_payroll'));
    }
    public function get_rekap(){
        $selectedPayroll=RekapPerhitunganPayroll::where('kode_rekap_payroll',request()->kode_rekap_payroll)->get();
        return $selectedPayroll;
    }
    public function rekap_absen()
    {
        $bulan_priode='2023-12';
        $bulan_sekarang1 = strtotime(date( $bulan_priode));
				$tanggal_sekarang=date('Y-m-d');
        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';

        $bulan=date('m', $bulan_sekarang1);
        $tahun=date('Y', $bulan_sekarang1);
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');

        $tidak_bayar=RefAbsenIjin::where('kode_ijin_payroll','ITB')->where('kode_absen_ijin','!=','M')->where('kode_absen_ijin','!=','IKS')->get()->toArray();
        $ITB=array_column($tidak_bayar,'kode_absen_ijin');

        $timestamp1 = strtotime($tanggal_awal);
        $timestamp2 = strtotime($tanggal_akhir);
        $jumlah_hari =(abs($timestamp2 - $timestamp1) / (60 * 60 * 24)+1);

        $jumlah_hari_sabtu_minggu = 0;

        $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->where('enroll_id','!=',7445)->get();



        for ($i = strtotime($tanggal_awal); $i <= strtotime($tanggal_akhir); $i += 86400) {
            if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
                $jumlah_hari_sabtu_minggu++;
            }
        }
        $dsjfoai=['6150'];
        // $x=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('department_id','DEP10')->get()->groupby('enroll_id');
        // $x=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('enroll_id','1755')->get()->groupby('enroll_id');
        $x=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->whereIn('enroll_id',$dsjfoai)->get()->groupby('enroll_id');
        $y=[];
        foreach ($x as $key => $value) {
            $IBY_employe=$value->wherein('status_absen', $IBY)->count();
            $LBY_employe=$value->where('status_absen','LN')->whereNotin('kode_hari', ['5','6'])->count();
            $ITB_employe=$value->wherein('status_absen', $ITB)->count();
            $lsm_employe=$value->wherein('kode_hari', ['5','6'])->count();
           // $dt_employe=$value->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','0')->count();
           // $pc_employe=$value->where('jumlah_menit_absen_pc','>','0')->where('jumlah_menit_absen_dt','0')->count();

           // $dtpc_employe=$value->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','>','0')->count();
 						$dt_employe=$value->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','0')->where('status_absen',null)->count();
            $pc_employe=$value->where('jumlah_menit_absen_pc','>','0')->where('jumlah_menit_absen_dt','0')->where('status_absen',null)->count();

            $dtpc_employe=$value->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','>','0')->where('status_absen',null)->count();

            $absen_M=$value->where('status_absen','M')->count();
            $absen_R=$value->where('status_absen','R')->count();
            $absen_TL=$value->where('status_absen','TL')->where('mulai_jam_kerja','!=',null)->count();
            $absen_IKS=$value->where('status_absen','IKS')->where('jumlah_menit_absen_dtpc','0')->count();
            // $absen_ok=$value->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja','!=',null)
            //                 ->where('mulai_jam_kerja','!=',null)->where('akhir_jam_kerja','!=',null)
            //                 ->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();
            $absen_ok=$value->whereNotin('kode_hari', ['5','6'])->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();
            $absen_ok=$absen_ok+$absen_IKS;
            $unik=$angka_string =sprintf("%04d", $key);
            $kode_rekap_kehadiran=$bulan_sekarang.$unik;

            $total_kehadiran=$IBY_employe+$ITB_employe+$lsm_employe+$dtpc_employe+$absen_M+$absen_R+$absen_ok+$LBY_employe+$dt_employe+$pc_employe+$absen_TL;
            // $kehadiran_tk=$jumlah_hari-($total_kehadiran+$absen_TL);

            $total_kehadiran_net=$absen_ok+$dt_employe+$pc_employe+$dtpc_employe;
            $aa=$total_kehadiran_net+$ITB_employe+$LBY_employe+$IBY_employe+$lsm_employe+$absen_M+$absen_TL+$absen_R;
            $kehadiran_tk=$jumlah_hari-$aa;

            if($security->where('enroll_id',$value->first()->enroll_id)->count()){
                $jumlah_hari_kerja=25;

            }else{
                $jumlah_hari_kerja=$jumlah_hari-$jumlah_hari_sabtu_minggu;

            }
 							$karyawan=EmployeeAtribut::where('enroll_id',$value->first()->enroll_id)->first();

						$kehadiran_dl=$value->where('status_absen','DL')->count();
            $kehadiran_cb=$value->where('status_absen','CB')->count();
            $kehadiran_cbd=$value->where('status_absen','CBD')->count();
            $kehadiran_cg=$value->where('status_absen','CG')->count();
            $kehadiran_ch=$value->where('status_absen','CH')->count();
            $kehadiran_cm=$value->where('status_absen','CM')->count();
            $kehadiran_cn=$value->where('status_absen','CN')->count();
            $kehadiran_ct=$value->where('status_absen','CT')->count();
            $kehadiran_ig=$value->where('status_absen','IG')->count();
            $kehadiran_im=$value->where('status_absen','IM')->count();
            $kehadiran_ka=$value->where('status_absen','KA')->count();
            $kehadiran_km=$value->where('status_absen','KM')->count();
            $kehadiran_kr=$value->where('status_absen','KR')->count();
            $kehadiran_na=$value->where('status_absen','NA')->count();
            $kehadiran_pp=$value->where('status_absen','PP')->count();
            $kehadiran_i=$value->where('status_absen','I')->count();
            $kehadiran_lp=$value->where('status_absen','LP')->count();
            $kehadiran_l=$value->where('status_absen','L')->count();
            $kehadiran_tl=$value->where('status_absen','TL')->count();
            $kehadiran_iks=$value->where('status_absen','IKS')->count();
            $kehadiran_s=$value->where('status_absen','S')->count();

            $M_estimasi=$value->where('status_absen','M')->where('tanggal_berjalan','>=',$tanggal_sekarang)->where('tanggal_berjalan','<=',$tanggal_akhir)->count();
            $TL_estimasi=$value->where('status_absen','TL')->where('mulai_jam_kerja','!=',null)->where('tanggal_berjalan','>=',$tanggal_sekarang)->where('tanggal_berjalan','<=',$tanggal_akhir)->count();


            $y=[
                'uuid'=>Str::uuid('uuid'),
                'kode_rekap_kehadiran'=> $kode_rekap_kehadiran,
                'periode_payroll'=>$tanggal_awal.' s/d '.$tanggal_akhir,
                'periode_tahun'=>$tahun,
                'periode_bulan'=>$bulan,
                'enroll_id'=>$value->first()->enroll_id,
                'nik'=>$karyawan->nik,
                'employee_name'=>$karyawan->employee_name,
                'site_nirwana_id'=>$karyawan->site_nirwana_id,
                'site_nirwana_name'=>$karyawan->site_nirwana_name,
                'department_id'=>$karyawan->department_id,
                'department_name'=>$karyawan->department_name,
                'sub_dept_id'=>$karyawan->sub_dept_id,
                'sub_dept_name'=>$karyawan->sub_dept_name,
                'join_date'=>$karyawan->join_date,
                'tanggal_resign'=>$karyawan->tanggal_resign,
                'status_aktif'=> $karyawan->status_aktif,
                'status_staff'=>  $karyawan->status_staff,
                'kehadiran_iby'=>$IBY_employe,
                'kehadiran_itb'=>$ITB_employe,
                'kehadiran_lby'=>$LBY_employe,
                'kehadiran_lsm'=>$lsm_employe,
                'kehadiran_dt'=> $dt_employe,
                'kehadiran_pc'=> $pc_employe,
                'kehadiran_dtpc'=>$dtpc_employe,
                'kehadiran_m'=>$absen_M+$absen_TL,
                'kehadiran_r'=>$absen_R,
                'kehadiran_tk'=>$kehadiran_tk,
                'kehadiran_ok'=>$absen_ok,
                'total_kehadiran'=> $total_kehadiran,
                // 'total_kehadiran'=> $total_kehadiran+$kehadiran_tk,
                'total_kehadiran_net'=>$absen_ok+$dt_employe+$pc_employe+$dtpc_employe+$LBY_employe+$IBY_employe,
                'jumlah_hari'=>$jumlah_hari,
                'jumlah_hari_kerja'=> $jumlah_hari_kerja,


                'kehadiran_dl'=>$kehadiran_dl,
                'kehadiran_cb'=>$kehadiran_cb,
                'kehadiran_cbd'=>$kehadiran_cbd,
                'kehadiran_cg'=>$kehadiran_cg,
                'kehadiran_ch'=>$kehadiran_ch,
                'kehadiran_cm'=>$kehadiran_cm,
                'kehadiran_cn'=>$kehadiran_cn,
                'kehadiran_ct'=>$kehadiran_ct,
                'kehadiran_ig'=>$kehadiran_ig,
                'kehadiran_im'=>$kehadiran_im,
                'kehadiran_ka'=>$kehadiran_ka,
                'kehadiran_km'=>$kehadiran_km,
                'kehadiran_kr'=>$kehadiran_kr,
                'kehadiran_na'=>$kehadiran_na,
                'kehadiran_pp'=>$kehadiran_pp,
                'kehadiran_i'=>$kehadiran_i,
                'kehadiran_lp'=>$kehadiran_lp,
                'kehadiran_l'=>$kehadiran_l,
                'kehadiran_tl'=>$kehadiran_tl,
                'kehadiran_iks'=>$kehadiran_iks,
                'kehadiran_s'=>$kehadiran_s,
                'kehadiran_m_estimasi'=> $TL_estimasi+$M_estimasi,
            ];
            // dd($y);
            // $count=RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->count();
            // if($count){
            //     RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->update($y);
            // }
            // else{
            //     RekapKehadiranKaryawan::create($y);

            // }
        }
        dd($y);
        // return true;

    }
    // rekap_absen perhitungan
    public function  rekap_absen_perhitungan()
    {
        // $periode_tahun='2023';
        // $periode_bulan='03';
        $bulan_priode='2023-12';
        $bulan_sekarang1 = strtotime(date( $bulan_priode));

        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';

        $periode_bulan=date('m', $bulan_sekarang1);
        $periode_tahun=date('Y', $bulan_sekarang1);
        $dsjfoai=['6067','5809','6050','6062','6067','6069','6074','6075','6079','6080'];

        $data=RekapKehadiranKaryawan::where('periode_tahun',$periode_tahun)->where('periode_bulan',$periode_bulan)->whereIn('enroll_id',$dsjfoai)->get();
        $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->get();
        foreach ($data as $key => $value) {
            $periode = $value->periode_payroll ;
            $enroll_id = $value->enroll_id;
            $kode = str_replace(array('-', ' '), '', $periode) . str_pad($enroll_id, 5, '0', STR_PAD_LEFT);;
            $kode_rekap = date('Ymd', strtotime(substr($kode, 0, 8))) . date('Ymd', strtotime(substr($kode, 11, 8))) . substr($kode, 19);

            $salary=EmployeeGrading::where('enroll_id',$value->enroll_id)->latest()->first();
            // $salary_bulanan=$salary->salary_bulanan??0;
            $salary_bulanan=$salary->salary_bulanan??GradingSalary::where('kode_grade','C')->first()->salary_bulanan;

            $hari_potongan=max($value->jumlah_hari_kerja-$value->total_kehadiran_net, 0);

            if($security->where('enroll_id',$value->enroll_id)->count()){
                $jumlah_menit_kerja=420;

            }else{
                $jumlah_menit_kerja=480;

            }

            $perhitungan=[
                'kode_rekap'=>$kode_rekap,
                'periode_payroll'=>$value->periode_payroll,
                'periode_tahun_bulan'=>$value->periode_tahun.'-'.$value->periode_bulan,
                'enroll_id'=>$value->enroll_id,
                'employee_name'=>$value->employee_name,
                'kehadiran_iby'=>$value->kehadiran_iby,
                'kehadiran_itb'=>$value->kehadiran_itb,
                'kehadiran_lby'=>$value->kehadiran_lby,
                'kehadiran_lsm'=>$value->kehadiran_lsm,
                'kehadiran_dt'=>$value->kehadiran_dt,
                'kehadiran_pc'=>$value->kehadiran_pc,
                'kehadiran_dtpc'=>$value->kehadiran_dtpc,
                'kehadiran_m'=>$value->kehadiran_m,
                'kehadiran_r'=>$value->kehadiran_r,
                'kehadiran_tk'=>$value->kehadiran_tk,
                'kehadiran_ok'=>$value->kehadiran_ok,
                'total_kehadiran'=>$value->total_kehadiran,
                'total_kehadiran_net'=>$value->total_kehadiran_net,
                'jumlah_hari'=>$value->jumlah_hari,
                'jumlah_hari_kerja'=>$value->jumlah_hari_kerja,
                'gaji_pokok'=>$salary_bulanan,
                'gaji_harian'=>$salary_bulanan/$value->jumlah_hari_kerja,
                'gaji_menit'=>($salary_bulanan/$value->jumlah_hari_kerja)/$jumlah_menit_kerja,
                'potongan_kehadiran_rupiah'=>($salary_bulanan/$value->jumlah_hari_kerja)*$hari_potongan,
               'kehadiran_m_estimasi'=>$value->kehadiran_m_estimasi,
            ];
            $count=RekapPerhitunganKehadiranKaryawan::where( 'kode_rekap',$kode_rekap)->count();
            if($count){
                RekapPerhitunganKehadiranKaryawan::where( 'kode_rekap',$kode_rekap)->update($perhitungan);
            }
            else{
                RekapPerhitunganKehadiranKaryawan::create($perhitungan);

            }
        }
        dd('oooo');
        // return true;
    }

    //rekap lembur
    public function rekap_lembur()
    {
        $bulan_priode='2023-12';
        $bulan_sekarang1 = strtotime(date( $bulan_priode));

        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';

        $dsjfoai=['1757'];
        $a=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->whereIn('enroll_id',$dsjfoai)->where('nomor_form_lembur','!=',null)->get();


        // $a=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('nomor_form_lembur','=','SPL/HR/2304/0679')->where('enroll_id','1763')->get();
        // dd($a);
        $data=[];
        foreach ($a as $key => $value) {
            $y=DataLembur::where('enroll_id',$value->enroll_id)->where('nomor_form_lembur',$value->nomor_form_lembur)->first();
            // dd($y);
            $jumlah_jam_kerja=date_diff(date_create($value->mulai_jam_kerja),date_create($value->akhir_jam_kerja));
            $jam_efektif_kerja=date_diff(date_create($value->absen_masuk_kerja),date_create($value->absen_pulang_kerja));

            $jam_in=$value->absen_masuk_kerja;
            $jam_out=$value->absen_pulang_kerja;

            $spl_in=date('H:i:s', strtotime($value->mulai_jam_lembur));
            $spl_out=date('H:i:s', strtotime($value->akhir_jam_lembur));

            $jadwal_in=$value->mulai_jam_kerja;
            $jadwal_out=$value->akhir_jam_kerja;

            if($jadwal_in==null || $value->status_absen=='LN'){
                $finish_in=max([$spl_in,$jam_in]);
                $finish_out=min([$spl_out,$jam_out]);
            }
            elseif ( $spl_in<$jadwal_in) {
                $finish_in=max([$spl_in,$jam_in]);
                $finish_out=min([$spl_out,$jam_out]);
            }
            else{
                $finish_in=max([$jadwal_out,$spl_in,$jam_in]);
                $finish_out=min([$spl_out,$jam_out]);
            }


            $jam1 = strtotime($finish_in);
            $jam2 = strtotime($finish_out);

            // Jika $jam2 lebih kecil dari $jam1, tambahkan 1 hari (86400 detik)
            if ($jam2 < $jam1) {
                $jam2 += 86400;
            }

            $selisih_detik = max($jam2 - $jam1, 0);

            $selisih_jam = floor($selisih_detik / 3600);
            $selisih_detik %= 3600;

            $selisih_menit = floor($selisih_detik / 60);
            $selisih_detik %= 60;

            $final_total_jam_lembur = sprintf("%02d:%02d:%02d", $selisih_jam, $selisih_menit, $selisih_detik);

            if($value->kode_hari==5 || $value->kode_hari==6 || $value->status_absen=='LN'){
                $kerjalibur='LIBUR';
            }
            else{
                $kerjalibur='KERJA';
            }

            $data[]=[
                // 'uuid'=>Str::uuid('uuid'),
                'tanggal_berjalan'=>$value->tanggal_berjalan,
                'kode_hari'=>$value->kode_hari,
                'nama_hari'=>$value->nama_hari,
                'kerjalibur'=>$kerjalibur,
                'holiday_name'=>$value->holiday_name,
                'nomor_form_lembur'=>$value->nomor_form_lembur,
                'enroll_id'=>$value->enroll_id,
                'nik'=>$value->nik,
                'employee_name'=>$value->employee_name,
                'site_nirwana_id'=>$value->site_nirwana_id,
                'site_nirwana_name'=>$value->site_nirwana_name,
                'department_id'=>$value->department_id,
                'department_name'=>$value->department_name,
                'sub_dept_id'=>$value->sub_dept_id,
                'sub_dept_name'=>$value->sub_dept_name,
                'posisi_name'=>$value->posisi_name,
                'mulai_jam_kerja'=>$value->mulai_jam_kerja,
                'akhir_jam_kerja'=>$value->akhir_jam_kerja,
                'jumlah_jam_kerja'=>sprintf('%02d:%02d:%02d', $jumlah_jam_kerja->h, $jumlah_jam_kerja->i, $jumlah_jam_kerja->s),
                'absen_masuk_kerja'=>$value->absen_masuk_kerja,
                'absen_pulang_kerja'=>$value->absen_pulang_kerja,
                'jam_efektif_kerja'=>sprintf('%02d:%02d:%02d', $jam_efektif_kerja->h, $jam_efektif_kerja->i, $jam_efektif_kerja->s),
                'mulai_jam_lembur'=>$value->mulai_jam_lembur,
                'akhir_jam_lembur'=>$value->akhir_jam_lembur,
                'final_mulai_jam_lembur'=>$finish_in,
                'final_selesai_jam_lembur'=>$value->absen_pulang_kerja,
                'final_total_jam_lembur'=>$final_total_jam_lembur,
                'final_jam_istirahat_lembur'=>0,
                'final_total_menit_lembur'=>($selisih_jam*60)+$selisih_menit,
                'final_jam_lembur_roundown'=> $selisih_jam,
                'final_menit_lembur_roundown'=>$selisih_menit,
                'lembur_1'=>0,
                'lembur_2'=>0,
                'lembur_3'=>0,
                'lembur_4'=>0,
                'total_lembur_1234'=>0,
                'salary'=>0,
                'lembur1_rupiah'=>0,
                'lembur2_rupiah'=> 0,
                'lembur3_rupiah'=> 0,
                'lembur4_rupiah'=> 0,
                'total_lembur_rupiah'=> 0,
                'operator'=>'system',
                'jumlah_jam_istirahat_form'=>$y->jumlah_jam_istirahat??0,
                'jumlah_jam_lembur_form'=>$y->jumlah_jam_lembur??0,
                'status_absen'=>$value->status_absen

            ];
        }
        $record=[];
        foreach ($data as $key2 => $value2) {
            if ($value2['final_menit_lembur_roundown'] <= 15) {
                $konveri_jam = 0;
            } elseif ($value2['final_menit_lembur_roundown'] > 15 && $value2['final_menit_lembur_roundown'] <= 45) {
                $konveri_jam = 0.5;
            } else {
                $konveri_jam = 1;
            }

            $total_jam_lembur=$value2['final_jam_lembur_roundown'] + $konveri_jam;

            $total_jam_lembur_finis=$total_jam_lembur-$value2['jumlah_jam_istirahat_form'];


            $total_jam_lembur_finis=min($value2['jumlah_jam_lembur_form'],$total_jam_lembur_finis);

                if($value2['kode_hari']==5 || $value2['kode_hari']==6 || $value2['status_absen']=='LN'){
                    $kerjalibur='LIBUR';
                    $l1=0;
                    $l2=($total_jam_lembur_finis <= 8) ? $total_jam_lembur_finis : 8;
                    if($total_jam_lembur_finis > 9){
                        $l3=1;
                        $l4=max($total_jam_lembur_finis -9, 0);
                    }
                    else if($total_jam_lembur_finis > 8 && $total_jam_lembur_finis <=9 ){
                        $l3=max($total_jam_lembur_finis -8, 0);
                        $l4=0;
                    }
                    else{
                        $l3=0;
                        $l4=0;
                    }
                }
                else{
                    $kerjalibur='KERJA';
                    $l1 = ($total_jam_lembur_finis <= 1) ? $total_jam_lembur_finis : 1;
                    $l2 = max($total_jam_lembur_finis - 1, 0);
                    $l3=0;
                    $l4=0;

                }


            $salary=EmployeeGrading::where('enroll_id',$value2['enroll_id'])->latest()->first();
            //$salary_bulanan=$salary->salary_bulanan??0;
            $salary_bulanan=$salary->salary_bulanan??GradingSalary::where('kode_grade','C')->first()->salary_bulanan;

                if($value2['kode_hari']==6 || $value2['status_absen']=='LN'){
                    $l1_rupiah=$l1*($salary_bulanan/173*1);
                    $l2_rupiah=$l2*($salary_bulanan/173*2);
                    $l3_rupiah=$l3*($salary_bulanan/173*2);
                    $l4_rupiah=$l4*($salary_bulanan/173*2);
                }
                else{
                    $l1_rupiah=$l1*($salary_bulanan/173*1);
                    $l2_rupiah=$l2*($salary_bulanan/173*1);
                    $l3_rupiah=$l3*($salary_bulanan/173*1);
                    $l4_rupiah=$l4*($salary_bulanan/173*1);
                }

            $record[$key2]=[
                'uuid'=>Str::uuid('uuid'),
                'tanggal_berjalan'=>$value2['tanggal_berjalan'],
                'kode_hari'=>$value2['kode_hari'],
                'nama_hari'=>$value2['nama_hari'],
                'kerjalibur'=>$value2['kerjalibur'],
                'holiday_name'=>$value2['holiday_name'],
                'nomor_form_lembur'=>$value2['nomor_form_lembur'],
                'enroll_id'=>$value2['enroll_id'],
                'nik'=>$value2['nik'],
                'employee_name'=>$value2['employee_name'],
                'site_nirwana_id'=>$value2['site_nirwana_id'],
                'site_nirwana_name'=>$value2['site_nirwana_name'],
                'department_id'=>$value2['department_id'],
                'department_name'=>$value2['department_name'],
                'sub_dept_id'=>$value2['sub_dept_id'],
                'sub_dept_name'=>$value2['sub_dept_name'],
                'posisi_name'=>$value2['posisi_name'],
                'mulai_jam_kerja'=>$value2['mulai_jam_kerja'],
                'akhir_jam_kerja'=>$value2['akhir_jam_kerja'],
                'jumlah_jam_kerja'=>$value2['jumlah_jam_kerja'],
                'absen_masuk_kerja'=>$value2['absen_masuk_kerja'],
                'absen_pulang_kerja'=>$value2['absen_pulang_kerja'],
                'jam_efektif_kerja'=>$value2['jam_efektif_kerja'],
                'mulai_jam_lembur'=>$value2['mulai_jam_lembur'],
                'akhir_jam_lembur'=>$value2['akhir_jam_lembur'],
                'final_mulai_jam_lembur'=>$value2['final_mulai_jam_lembur'],
                'final_selesai_jam_lembur'=>$value2['final_selesai_jam_lembur'],
                'final_total_jam_lembur'=>$value2['final_total_jam_lembur'],
                'final_jam_istirahat_lembur'=>$value2['jumlah_jam_istirahat_form'],
                'final_total_menit_lembur'=>$value2['final_total_menit_lembur'],
                'final_jam_lembur_roundown'=> $value2['final_jam_lembur_roundown'],
                'final_menit_lembur_roundown'=>$value2['final_menit_lembur_roundown'],
                'lembur_1'=>$l1,
                'lembur_2'=>$l2,
                'lembur_3'=>$l3,
                'lembur_4'=>$l4,
                'total_lembur_1234'=>$total_jam_lembur_finis,
                'salary'=>$salary_bulanan,
                'lembur1_rupiah'=>$l1_rupiah,
                'lembur2_rupiah'=> $l2_rupiah,
                'lembur3_rupiah'=> $l3_rupiah,
                'lembur4_rupiah'=> $l4_rupiah,
                'total_lembur_rupiah'=> $l1_rupiah+$l2_rupiah+$l3_rupiah+$l4_rupiah,
                'operator'=>'system',
            ];

            // $count=RekapPerhitunganLembur::where('tanggal_berjalan',$value2['tanggal_berjalan'])->where('enroll_id',$value2['enroll_id'])->count();
            // if($count){
            //     RekapPerhitunganLembur::where('tanggal_berjalan',$value2['tanggal_berjalan'])->where('enroll_id',$value2['enroll_id'])->update($record);
            // }
            // else{
            //     RekapPerhitunganLembur::create($record);

            // }


        }
            dd($record);

        // return true;
    }
    //rekap IKS
    public function rekap_iks()
    {
        // $tanggal_awal='2023-02-26';
        // $tanggal_akhir='2023-03-25';
        $bulan_priode='2023-12';
        $bulan_sekarang1 = strtotime(date( $bulan_priode));

        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';

        $dsjfoai=['6067','5809','6050','6062','6067','6069','6074','6075','6079','6080'];
        $a=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->whereIn('enroll_id',$dsjfoai)->where('total_menit_permits','>',0)->get();
        $priode=$tanggal_awal.' s/d '. $tanggal_akhir;

        foreach ($a as $key => $value) {

            if($value->kode_hari = 4){
                $jam_mulai_istirahat='11:30';
                $jam_selesai_istirahat='12:30';
            }
            else{
                if($value->mulai_jam_kerja = '06:00' AND $value->akhir_jam_kerja = '15:00'){
                    $jam_mulai_istirahat='10:00';
                    $jam_selesai_istirahat='11:00';
                }
                elseif($value->mulai_jam_kerja = '16:00' AND $value->akhir_jam_kerja = '23:00'){
                    $jam_mulai_istirahat='18:00';
                    $jam_selesai_istirahat='19:00';
                }
                else{
                    $jam_mulai_istirahat='12:00';
                    $jam_selesai_istirahat='13:00';
                }
            }
            $minutes = $value->total_menit_permits;
            $seconds = $minutes * 60;
            $time = gmdate("H:i:s", $seconds);


            $salary=RekapPerhitunganKehadiranKaryawan::where('periode_payroll',$priode)->where('enroll_id',$value->enroll_id)->first();
            $salary->gaji_pokok;

            $data=[
                'uuid'=>Str::uuid('uuid'),
                'nomor_form_perizinan'=>$value->nomor_absen_ijin,
                'tanggal_berjalan'=>$value->tanggal_berjalan,
                'enroll_id'=>$value->enroll_id,
                'employee_name'=>$value->employee_name,
                'sub_dept_name'=>$value->sub_dept_name,
                'time_mulai_ijin'=>$value->permits_dari_pukul,
                'time_akhir_ijin'=>$value->permits_sampai_pukul,
                'jam_mulai_istirahat'=>$jam_mulai_istirahat,
                'jam_selesai_istirahat'=> $jam_selesai_istirahat,
                'lama_istirahat_menit'=>60,
                'lama_ijin_menit'=>$value->total_menit_permits,
                'lama_ijin_jam'=>$time,
                'absen_alasan'=>$value->absen_alasan,
                'gaji_pokok'=> $salary->gaji_pokok,
                'gaji_harian'=> $salary->gaji_harian,
                'gaji_menit'=> $salary->gaji_menit,
                'potongan_iks_rupiah'=>$salary->gaji_menit*$value->total_menit_permits,
            ];
            $count=RekapPerhitunganIKS::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->count();
            if($count){
                RekapPerhitunganIKS::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->update($data);
            }
            else{
                RekapPerhitunganIKS::create($data);

            }
        }
        dd('sudsido');
        // return true;
    }
    //dt pc
    public function dt_pc()
    {
        // $tanggal_awal='2023-02-26';
        // $tanggal_akhir='2023-03-25';
        $bulan_priode='2023-12';
        $bulan_sekarang1 = strtotime(date( $bulan_priode));

        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';

        $priode=$tanggal_awal.' s/d '. $tanggal_akhir;

        $dsjfoai=['6067','5809','6050','6062','6067','6069','6074','6075','6079','6080'];
        $a=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('enroll_id','>=','6110')->whereIn('enroll_id',$dsjfoai)->get();

        // $a=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('jumlah_menit_absen_dtpc','>',0)->where('enroll_id','3942')->get();
        $data=[];
        foreach ($a as $key => $value) {
            $salary=RekapPerhitunganKehadiranKaryawan::where('periode_payroll',$priode)->where('enroll_id',$value->enroll_id)->first();

            $data=[
                'uuid'=>Str::uuid('uuid'),
                'tanggal_berjalan'=>$value->tanggal_berjalan,
                'employee_id'=>$value->employee_id,
                'enroll_id'=>$value->enroll_id,
                'employee_name'=>$value->employee_name,
                'mulai_jam_kerja'=>$value->mulai_jam_kerja,
                'akhir_jam_kerja'=>$value->akhir_jam_kerja,
                'absen_masuk_kerja'=>$value->absen_masuk_kerja,
                'absen_pulang_kerja'=>$value->absen_pulang_kerja,
                'status_absen'=>$value->status_absen,
                'gaji_pokok'=> $salary->gaji_pokok,
                'gaji_menit'=> $salary->gaji_menit,
                'jumlah_menit_absen_dt'=>$value->jumlah_menit_absen_dt,
                'jumlah_menit_absen_pc'=>$value->jumlah_menit_absen_pc,
                'jumlah_menit_absen_dtpc'=>$value->jumlah_menit_absen_dtpc,
                'potongan_dt_rupiah'=>$salary->gaji_menit * $value->jumlah_menit_absen_dt,
                'potongan_pc_rupiah'=>$salary->gaji_menit * $value->jumlah_menit_absen_pc,
                'potongan_dtpc_rupiah'=>$salary->gaji_menit * $value->jumlah_menit_absen_dtpc,
                'jumlah_absen_menit_kerja'=>$value->jumlah_absen_menit_kerja,
            ];
            $count=RekapPerhitunganDTPC::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->count();
            if($count){
                RekapPerhitunganDTPC::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->update($data);
            }
            else{
                RekapPerhitunganDTPC::create($data);
            }
        }
        // RekapPerhitunganDTPC::where('jumlah_menit_absen_dtpc',0)->delete();
        dd('dadooiaoa');
        // return true;

    }
    public function dt_pc2()
    {
        // $tanggal_awal='2023-02-26';
        // $tanggal_akhir='2023-03-25';
        $bulan_priode='2023-11';
        $bulan_sekarang1 = strtotime(date( $bulan_priode));

        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';

        $priode=$tanggal_awal.' s/d '. $tanggal_akhir;
        $a=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->get();

        // $a=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('jumlah_menit_absen_dtpc','>',0)->where('enroll_id','3942')->get();
        $data=[];
        foreach ($a as $key => $value) {
            $salary=RekapPerhitunganKehadiranKaryawan::where('periode_payroll',$priode)->where('enroll_id','1757')->first();

            $data[$key]=[
                'uuid'=>Str::uuid('uuid'),
                'tanggal_berjalan'=>$value->tanggal_berjalan,
                'employee_id'=>$value->employee_id,
                'enroll_id'=>$value->enroll_id,
                'employee_name'=>$value->employee_name,
                'mulai_jam_kerja'=>$value->mulai_jam_kerja,
                'akhir_jam_kerja'=>$value->akhir_jam_kerja,
                'absen_masuk_kerja'=>$value->absen_masuk_kerja,
                'absen_pulang_kerja'=>$value->absen_pulang_kerja,
                'status_absen'=>$value->status_absen,
                'gaji_pokok'=> $salary->gaji_pokok,
                'gaji_menit'=> $salary->gaji_menit,
                'jumlah_menit_absen_dt'=>$value->jumlah_menit_absen_dt,
                'jumlah_menit_absen_pc'=>$value->jumlah_menit_absen_pc,
                'jumlah_menit_absen_dtpc'=>$value->jumlah_menit_absen_dtpc,
                'potongan_dt_rupiah'=>$salary->gaji_menit * $value->jumlah_menit_absen_dt,
                'potongan_pc_rupiah'=>$salary->gaji_menit * $value->jumlah_menit_absen_pc,
                'potongan_dtpc_rupiah'=>$salary->gaji_menit * $value->jumlah_menit_absen_dtpc,
                'jumlah_absen_menit_kerja'=>$value->jumlah_absen_menit_kerja,
            ];
            // $count=RekapPerhitunganDTPC::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->count();
            // if($count){
            //     RekapPerhitunganDTPC::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->update($data);
            // }
            // else{
            //     RekapPerhitunganDTPC::create($data);
            // }
        }
        // RekapPerhitunganDTPC::where('jumlah_menit_absen_dtpc',0)->delete();
        dd($data);
        // return true;

    }

    // BPJS
    public function update_bpjs($bulan_priode)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;



        $bulan_sekarang1 = strtotime(date( $bulan_priode));

        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';

        $kode_bpjs =  BpjsSetting::orderBy('kode_periode_bpjs','desc')->limit(1)->first();

        $periode_payroll=$tanggal_awal.' s/d '. $tanggal_akhir;

        $explodePeriodePayroll = explode(" s/d ", $periode_payroll);
        $periodePayroll = substr($explodePeriodePayroll[1], 0, 4) . substr($explodePeriodePayroll[1], 5, 2);
        $explodeKode = explode("-", $kode_bpjs->kode_periode_bpjs);
        $kode_periode_bpjs = $explodeKode[0] . $explodeKode[1];
        $sqlKodePeriodeBPJS = 'concat("' . $periodePayroll . '", lpad(enroll_id, 5, 0))';
        // $status_staff = $request->status_staff;
        // $searchData = $request->searchData;

        $queryEmpAtr =  EmployeeAtribut::selectRaw('uuid() uuid,
                    concat(SUBSTR(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ), 1, 4),
                    SUBSTR(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ), 6, 2), lpad(enroll_id, 5, 0)) kode_bpjs,
                    substr("' . $explodePeriodePayroll[1] . '", 1, 4) periode_bpjs,
                    CONCAT(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 2 MONTH )), INTERVAL 26 DAY ), " s/d ",
                	DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ))  periode_kehadiran,
                    enroll_id, nik, employee_name, site_nirwana_id, department_id, sub_dept_id,
                    status_aktif_bpjs_tk, tanggal_bpjs_ketenagakerjaan, nomor_bpjs_ketenagakerjaan,
                    status_aktif_bpjs_ks, tanggal_bpjs_kesehatan, nomor_bpjs_kesehatan, join_date
                    ')
                    ->whereRaw('
                    enroll_id is not null
                    AND (tanggal_resign is null OR tanggal_resign = "0000-00-00" OR
                        NOT tanggal_resign < DATE_ADD( LAST_DAY( DATE_SUB( "' . $explodePeriodePayroll[1] . '", INTERVAL 2 MONTH )), INTERVAL 26 DAY ))
                    AND join_date <= "' . $explodePeriodePayroll[1] . '"
                    ')
                    ->groupBy('enroll_id')
                    ->groupBy('employee_name')
                    ->get();

        foreach ($queryEmpAtr as $key => $value) {

            $tanggal_masuk = $value['join_date'];

            // hitung selisih tahun antara tanggal masuk dan sekarang
         $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_awal))->y;

            // tentukan besaran tunjangan berdasarkan masa kerja
            if ($selisih_tahun < 1) {
                $tunjangan = 0;
            } elseif ($selisih_tahun < 3) {
                $tunjangan = 2500;
            } elseif ($selisih_tahun < 6) {
                $tunjangan = 5000;
            }elseif ($selisih_tahun < 9) {
                $tunjangan = 7500;
            }elseif ($selisih_tahun < 12) {
                $tunjangan = 10000;
            }else{
                $tunjangan = 12500;
            }

            $countEmp = EmployeeBpjs::whereRaw('kode_bpjs = ' . $sqlKodePeriodeBPJS . ' AND enroll_id = "' . $value['enroll_id'] . '"')->count();

            if ($countEmp) {
                $queryEmpBpjs = EmployeeBpjs::whereRaw('kode_bpjs = ' . $sqlKodePeriodeBPJS . ' AND enroll_id = "' . $value['enroll_id'] . '"')
                ->update([
                    'status_aktif_bpjs_tk' => $value['status_aktif_bpjs_tk'],
                    'tanggal_bpjs_ketenagakerjaan' => $value['tanggal_bpjs_ketenagakerjaan'],
                    'nomor_bpjs_ketenagakerjaan' => $value['nomor_bpjs_ketenagakerjaan'],
                    'status_aktif_bpjs_ks' => $value['status_aktif_bpjs_ks'],
                    'tanggal_bpjs_kesehatan' => $value['tanggal_bpjs_kesehatan'],
                    'nomor_bpjs_kesehatan' => $value['nomor_bpjs_kesehatan'],
                    'kode_periode_bpjs' => null,
                    'kode_dasar_pot_bpjs' => null,
                    'dasar_pot_bpjs_rupiah' => 0,
                    'bpjs_tk_jkm_bruto_rupiah' => 0,
                    'bpjs_tk_jkk_bruto_rupiah' => 0,
                    'bpjs_ks_jkn_bruto_rupiah' => 0,
                    'bpjs_tk_jkm_neto_rupiah' => 0,
                    'bpjs_tk_jkk_neto_rupiah' => 0,
                    'bpjs_tk_jht_neto_rupiah' => 0,
                    'bpjs_tk_jpn_neto_rupiah' => 0,
                    'bpjs_ks_jkn_neto_rupiah' => 0,
                    'bpjs_tk_jkm_persen' => 0,
                    'bpjs_tk_jkk_persen' => 0,
                    'bpjs_tk_jht_persen' => 0,
                    'bpjs_tk_jpn_persen' => 0,
                    'bpjs_ks_jkn_persen' => 0,
                    'bpjs_tk_jkm_bruto_persen' => 0,
                    'bpjs_tk_jkk_bruto_persen' => 0,
                    'bpjs_tk_jht_bruto_persen' => 0,
                    'bpjs_tk_jpn_bruto_persen' => 0,
                    'bpjs_ks_jkn_bruto_persen' => 0,
                    'bpjs_tk_jkm_neto_persen' => 0,
                    'bpjs_tk_jkk_neto_persen' => 0,
                    'bpjs_tk_jht_neto_persen' => 0,
                    'bpjs_tk_jpn_neto_persen' => 0,
                    'bpjs_ks_jkn_neto_persen' => 0,
                    'tmk'=>$tunjangan,
                ]);
            } else {
                EmployeeBpjs::create([
                    'uuid' => Str::uuid(),
                    'kode_bpjs' => $value['kode_bpjs'],
                    'periode_bpjs' => $value['periode_bpjs'],
                    'periode_kehadiran' => $value['periode_kehadiran'],
                    'enroll_id' => $value['enroll_id'],
                    'nik' => $value['nik'],
                    'employee_name' => $value['employee_name'],
                    'status_aktif_bpjs_tk' => $value['status_aktif_bpjs_tk'],
                    'tanggal_bpjs_ketenagakerjaan' => $value['tanggal_bpjs_ketenagakerjaan'],
                    'nomor_bpjs_ketenagakerjaan' => $value['nomor_bpjs_ketenagakerjaan'],
                    'status_aktif_bpjs_ks' => $value['status_aktif_bpjs_ks'],
                    'tanggal_bpjs_kesehatan' => $value['tanggal_bpjs_kesehatan'],
                    'nomor_bpjs_kesehatan' => $value['nomor_bpjs_kesehatan'],
                    'kode_periode_bpjs' => null,
                    'kode_dasar_pot_bpjs' => null,
                    'dasar_pot_bpjs_rupiah' => 0,
                    'bpjs_tk_jkm_bruto_rupiah' => 0,
                    'bpjs_tk_jkk_bruto_rupiah' => 0,
                    'bpjs_ks_jkn_bruto_rupiah' => 0,
                    'bpjs_tk_jkm_neto_rupiah' => 0,
                    'bpjs_tk_jkk_neto_rupiah' => 0,
                    'bpjs_tk_jht_neto_rupiah' => 0,
                    'bpjs_tk_jpn_neto_rupiah' => 0,
                    'bpjs_ks_jkn_neto_rupiah' => 0,
                    'bpjs_tk_jkm_persen' => 0,
                    'bpjs_tk_jkk_persen' => 0,
                    'bpjs_tk_jht_persen' => 0,
                    'bpjs_tk_jpn_persen' => 0,
                    'bpjs_ks_jkn_persen' => 0,
                    'bpjs_tk_jkm_bruto_persen' => 0,
                    'bpjs_tk_jkk_bruto_persen' => 0,
                    'bpjs_tk_jht_bruto_persen' => 0,
                    'bpjs_tk_jpn_bruto_persen' => 0,
                    'bpjs_ks_jkn_bruto_persen' => 0,
                    'bpjs_tk_jkm_neto_persen' => 0,
                    'bpjs_tk_jkk_neto_persen' => 0,
                    'bpjs_tk_jht_neto_persen' => 0,
                    'bpjs_tk_jpn_neto_persen' => 0,
                    'bpjs_ks_jkn_neto_persen' => 0,
                    'tmk'=>$tunjangan,
                ]);
            }

        }
        $query =  BpjsSetting::whereRaw(' substr(kode_periode_bpjs, 1, 4) = substr("' . $kode_bpjs->kode_periode_bpjs . '", 1, 4)')
                ->orderBy('kode_periode_bpjs','desc')
                ->limit(1)
                ->get();

        $kode_periode_bpjs = $query[0]->kode_periode_bpjs;
        $kode_dasar_pot_bpjs = $query[0]->kode_dasar_pot_bpjs;
        $dasar_pot_bpjs_rupiah_gapok = $query[0]->dasar_pot_bpjs_rupiah;
        $bpjs_tk_jkm_persen = $query[0]->bpjs_tk_jkm_persen;
        $bpjs_tk_jkm_perusahaan_persen = $query[0]->bpjs_tk_jkm_perusahaan_persen;
        $bpjs_tk_jkm_karyawan_persen = $query[0]->bpjs_tk_jkm_karyawan_persen;
        $bpjs_tk_jkk_persen = $query[0]->bpjs_tk_jkk_persen;
        $bpjs_tk_jkk_perusahaan_persen = $query[0]->bpjs_tk_jkk_perusahaan_persen;
        $bpjs_tk_jkk_karyawan_persen = $query[0]->bpjs_tk_jkk_karyawan_persen;
        $bpjs_tk_jht_persen = $query[0]->bpjs_tk_jht_persen;
        $bpjs_tk_jht_perusahaan_persen = $query[0]->bpjs_tk_jht_perusahaan_persen;
        $bpjs_tk_jht_karyawan_persen = $query[0]->bpjs_tk_jht_karyawan_persen;
        $bpjs_tk_jpn_persen = $query[0]->bpjs_tk_jpn_persen;
        $bpjs_tk_jpn_perusahaan_persen = $query[0]->bpjs_tk_jpn_perusahaan_persen;
        $bpjs_tk_jpn_karyawan_persen = $query[0]->bpjs_tk_jpn_karyawan_persen;
        $bpjs_ks_jkn_persen = $query[0]->bpjs_ks_jkn_persen;
        $bpjs_ks_jkn_perusahaan_persen = $query[0]->bpjs_ks_jkn_perusahaan_persen;
        $bpjs_ks_jkn_karyawan_persen = $query[0]->bpjs_ks_jkn_karyawan_persen;

        $EmpBpjs = EmployeeBpjs::where('periode_kehadiran',$periode_payroll)->get();

        foreach ($EmpBpjs as $key3 => $value3) {
            $dasar_pot_bpjs_rupiah=$dasar_pot_bpjs_rupiah_gapok+$value3->tmk;
            // dd($dasar_pot_bpjs_rupiah);
        $queryEmpBpjs = DB::update('update employee_bpjs set
                kode_periode_bpjs = "' . $kode_periode_bpjs . '",
                kode_dasar_pot_bpjs = "' . $kode_dasar_pot_bpjs . '",
                dasar_pot_bpjs_rupiah = "' . $dasar_pot_bpjs_rupiah . '",
                bpjs_tk_jkm_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkm_perusahaan_persen . '/100)), 0),
                bpjs_tk_jkk_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkk_perusahaan_persen . '/100)), 0),
                bpjs_tk_jht_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jht_perusahaan_persen . '/100)), 0),
                bpjs_tk_jpn_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jpn_perusahaan_persen . '/100)), 0),
                bpjs_ks_jkn_bruto_rupiah = IF(status_aktif_bpjs_ks = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_ks_jkn_perusahaan_persen . '/100)), 0),
                bpjs_tk_jkm_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkm_karyawan_persen . '/100)), 0),
                bpjs_tk_jkk_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkk_karyawan_persen . '/100)), 0),
                bpjs_tk_jht_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jht_karyawan_persen . '/100)), 0),
                bpjs_tk_jpn_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jpn_karyawan_persen . '/100)), 0),
                bpjs_ks_jkn_neto_rupiah = IF(status_aktif_bpjs_ks = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_ks_jkn_karyawan_persen . '/100)), 0),
                bpjs_tk_jkm_persen = "' . $bpjs_tk_jkm_persen . '",
                bpjs_tk_jkk_persen = "' . $bpjs_tk_jkk_persen . '",
                bpjs_tk_jht_persen = "' . $bpjs_tk_jht_persen . '",
                bpjs_tk_jpn_persen = "' . $bpjs_tk_jpn_persen . '",
                bpjs_ks_jkn_persen = "' . $bpjs_ks_jkn_persen . '",
                bpjs_tk_jkm_bruto_persen = "' . $bpjs_tk_jkm_perusahaan_persen . '",
                bpjs_tk_jkk_bruto_persen = "' . $bpjs_tk_jkk_perusahaan_persen . '",
                bpjs_tk_jht_bruto_persen = "' . $bpjs_tk_jht_perusahaan_persen . '",
                bpjs_tk_jpn_bruto_persen = "' . $bpjs_tk_jpn_perusahaan_persen . '",
                bpjs_ks_jkn_bruto_persen = "' . $bpjs_ks_jkn_perusahaan_persen . '",
                bpjs_tk_jkm_neto_persen = "' . $bpjs_tk_jkm_karyawan_persen . '",
                bpjs_tk_jkk_neto_persen = "' . $bpjs_tk_jkk_karyawan_persen . '",
                bpjs_tk_jht_neto_persen = "' . $bpjs_tk_jht_karyawan_persen . '",
                bpjs_tk_jpn_neto_persen = "' . $bpjs_tk_jpn_karyawan_persen . '",
                bpjs_ks_jkn_neto_persen = "' . $bpjs_ks_jkn_karyawan_persen . '",
                operator = "' . $email . '"
            where enroll_id = "'. $value3->enroll_id .'"');
        }


        return Response()->json($queryEmpBpjs);
    }

    // rekap payroll
    public function rekap_payroll()
    {
        $bulan_priode='2023-12';
        // $tanggal_awal='2023-02-26';
        // $tanggal_akhir='2023-03-25';

        $bulan_sekarang1 = strtotime(date( $bulan_priode));

        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';

        $osisdoag=['1757'];

        // $karyawan=EmployeeAtribut::where('status_aktif','AKTIF')->where('join_date','<=', $tanggal_akhir)->ORwhere('tanggal_resign','>',$tanggal_awal)->get();
        $karyawan=EmployeeAtribut::where('status_aktif','AKTIF')->whereIn('enroll_id',$osisdoag)->get();

        $periode_payroll=$tanggal_awal.' s/d '.$tanggal_akhir;// menggunakan s/d

        $tanggal_awal_baru = date('m/d/Y', strtotime($tanggal_awal));
        $tanggal_akhir_baru = date('m/d/Y', strtotime($tanggal_akhir));
        $periode_payroll2 = $tanggal_awal_baru . ' - ' . $tanggal_akhir_baru;// menggunakan -

        $data=[];
        foreach ($karyawan as $key => $value) {

            $rekap_kehadiran=RekapPerhitunganKehadiranKaryawan::where('enroll_id',$value->enroll_id)->where('periode_payroll',$periode_payroll)->first();

            $rekap_lembur=RekapPerhitunganLembur::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->get();

            $rekap_iks=RekapPerhitunganIKS::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->get();

            $rekap_dtpc=RekapPerhitunganDTPC::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->get();

            $Bpjs=EmployeeBpjs::where('enroll_id',$value->enroll_id)->where('periode_kehadiran',$periode_payroll)->first();


            $koreksi_upah=DataKoreksiUpah::where('enroll_id',$value->enroll_id)->where('periode_tanggal_koreksi',$periode_payroll2)->get();

            $koreksi_potongan=DataKoreksiPotongan::where('enroll_id',$value->enroll_id)->where('periode_tanggal_koreksi',$periode_payroll2)->get();

            $tunjangan=TunjanganKaryawan::where('enroll_id',$value->enroll_id)->where('periode_payroll',$periode_payroll)->first();

            if($rekap_kehadiran!=null){
                list($periode_tahun_payroll, $periode_bulan_payroll) = explode("-", $rekap_kehadiran->periode_tahun_bulan);
                //tunjangan
                $tanggal_masuk = $value->join_date;

                // hitung selisih tahun antara tanggal masuk dan sekarang
                $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_awal))->y;

                // tentukan besaran tunjangan berdasarkan masa kerja
                if ($selisih_tahun < 1) {
                    $tunjangan = 0;
                } elseif ($selisih_tahun < 3) {
                    $tunjangan = 2500;
                } elseif ($selisih_tahun < 6) {
                    $tunjangan = 5000;
                }elseif ($selisih_tahun < 9) {
                    $tunjangan = 7500;
                }elseif ($selisih_tahun < 12) {
                    $tunjangan = 10000;
                }else{
                    $tunjangan = 12500;
                }


                $bpjs_tk_jkm_bruto_rupiah=$Bpjs->bpjs_tk_jkm_bruto_rupiah??0;
                $bpjs_tk_jkm_neto_rupiah=$Bpjs->bpjs_tk_jkm_neto_rupiah??0;
                $bpjs_tk_jkk_bruto_rupiah=$Bpjs->bpjs_tk_jkk_bruto_rupiah??0;
                $bpjs_tk_jkk_neto_rupiah=$Bpjs->bpjs_tk_jkk_neto_rupiah??0;
                $bpjs_tk_jht_bruto_rupiah=$Bpjs->bpjs_tk_jht_bruto_rupiah??0;
                $bpjs_tk_jht_neto_rupiah=$Bpjs->bpjs_tk_jht_neto_rupiah??0;
                $bpjs_tk_jpn_bruto_rupiah=$Bpjs->bpjs_tk_jpn_bruto_rupiah??0;
                $bpjs_tk_jpn_neto_rupiah=$Bpjs->bpjs_tk_jpn_neto_rupiah??0;
                $bpjs_ks_jkn_bruto_rupiah=$Bpjs->bpjs_ks_jkn_bruto_rupiah??0;
                $bpjs_ks_jkn_neto_rupiah=$Bpjs->bpjs_ks_jkn_neto_rupiah??0;



                $data[]=[
                    'kode_rekap_payroll'=>$rekap_kehadiran->kode_rekap,
                    'periode_kehadiran'=>$rekap_kehadiran->periode_payroll,
                    'periode_tahun_payroll'=>$periode_tahun_payroll,
                    'periode_bulan_payroll'=>$periode_bulan_payroll,
                    'enroll_id'=>$value->enroll_id,
                    'nik'=>$value->nik,
                    'kode_grade'=>$value->kode_grade,
                    'join_date'=>$value->join_date,
                    'employee_name'=>$value->employee_name,
                    'tanggal_resign'=>$value->tanggal_resign,
                    'ptkp'=>$value->ptkp,
                    'jabatan_karyawan'=>$value->status_jabatan,
                    'nama_bagian'=>$value->sub_dept_name??'',
                    'nama_department'=>$value->department_name??'',
                    'kategori_karyawan'=>$value->status_staff,
                    'aktif_karyawan'=>$value->status_aktif,
                    'jenis_kelamin'=>$value->jenis_kelamin,
                    'status_kawin'=>$value->status_kawin,
                    'site_nirwana_name'=>$value->site_nirwana_name,
                    'nama_bank'=>$value->nama_bank==null||$value->nama_bank==""||$value->nama_bank=="-"?'TUNAI':$value->nama_bank,
                    'nomor_rekening_bank'=>$value->nomor_rekening_bank==null||$value->nomor_rekening_bank==""||$value->nomor_rekening_bank=="-"?'-':$value->nomor_rekening_bank,
                    'npwp'=>$value->npwp,
                    'operator'=>'sistem',
                    'sub_dept_id'=>$value->sub_dept_id,
                    'tunjangan_karyawan_rupiah'=>$tunjangan,
                    'premi_karyawan'=>0,
                    'kehadiran_iby'=>$rekap_kehadiran->kehadiran_iby,
                    'kehadiran_itb'=>$rekap_kehadiran->kehadiran_itb,
                    'kehadiran_m'=>$rekap_kehadiran->kehadiran_m,
                    'kehadiran_dt'=>$rekap_kehadiran->kehadiran_dt,
                    'kehadiran_pc'=>$rekap_kehadiran->kehadiran_pc,
                    'kehadiran_dtpc'=>$rekap_kehadiran->kehadiran_dtpc,
                    'kehadiran_lby'=>$rekap_kehadiran->kehadiran_lby,
                    'kehadiran_lsm'=>$rekap_kehadiran->kehadiran_lsm,
                    'kehadiran_r'=>$rekap_kehadiran->kehadiran_r,
                    'kehadiran_ok'=>$rekap_kehadiran->kehadiran_ok,
                    'kehadiran_tk'=>$rekap_kehadiran->kehadiran_tk,
                    'total_kehadiran'=>$rekap_kehadiran->total_kehadiran,
                    'total_kehadiran_net'=>$rekap_kehadiran->total_kehadiran_net,
                    'upah_per_bulan'=>$rekap_kehadiran->gaji_pokok,
                    'upah_per_hari'=>$rekap_kehadiran->gaji_harian,
                    'upah_per_menit'=>$rekap_kehadiran->gaji_menit,
                    'potongan_kehadiran_rupiah'=>$rekap_kehadiran->potongan_kehadiran_rupiah,
                    'kehadiran_m_estimasi'=>$rekap_kehadiran->kehadiran_m_estimasi,

                    'lembur_1'=>$rekap_lembur->sum('lembur_1')??0,
                    'lembur_2'=>$rekap_lembur->sum('lembur_2')??0,
                    'lembur_3'=>$rekap_lembur->sum('lembur_3')??0,
                    'lembur_4'=>$rekap_lembur->sum('lembur_4')??0,
                    'total_lembur_1234'=>$rekap_lembur->sum('total_lembur_1234')??0,
                    'lembur1_rupiah'=>$rekap_lembur->sum('lembur1_rupiah')??0,
                    'lembur2_rupiah'=>$rekap_lembur->sum('lembur2_rupiah')??0,
                    'lembur3_rupiah'=>$rekap_lembur->sum('lembur3_rupiah')??0,
                    'lembur4_rupiah'=>$rekap_lembur->sum('lembur4_rupiah')??0,
                    'total_lembur_rupiah'=>$rekap_lembur->sum('total_lembur_rupiah')??0,

                    'pendapatan_lainnya_rupiah'=>0,

                    'koreksi_upah_rupiah'=>$koreksi_upah->sum('jumlah_rp_potongan')??0,
                    'koreksi_potongan_rupiah'=>$koreksi_potongan->sum('jumlah_rp_potongan')??0,

                    'potongan_iks_menit'=>$rekap_iks->sum('lama_ijin_menit')??0,
                    'potongan_iks_rupiah'=>$rekap_iks->sum('potongan_iks_rupiah')??0,
                    'potongan_dt_menit'=>$rekap_dtpc->sum('jumlah_menit_absen_dt')??0,
                    'potongan_pc_menit'=>$rekap_dtpc->sum('jumlah_menit_absen_pc')??0,
                    'potongan_dtpc_menit'=>$rekap_dtpc->sum('jumlah_menit_absen_dtpc')??0,
                    'potongan_dt_rupiah'=>$rekap_dtpc->sum('potongan_dt_rupiah')??0,
                    'potongan_pc_rupiah'=>$rekap_dtpc->sum('potongan_pc_rupiah')??0,
                    'potongan_dtpc_rupiah'=>$rekap_dtpc->sum('potongan_dtpc_rupiah')??0,

                    // 'upah_bruto_rupiah'=>$value,
                    // 'upah_neto_rupiah'=>$value,

                    // 'upah_bersih_rupiah'=>$value,
                    // 'total_upah_thp_rupiah'=>$value,
                    // 'jumlah_potongan_rupiah'=>$value,


                    'pph21'=>0, // dari mana?
                    'iuran_serikat_rupiah'=>0, // dari mana?
                    'iuran_koperasi'=>0, // dari mana?
                    'potongan_kasbon_rupiah'=>0, // dari mana?

                    'bpjs_tk_jkm_rupiah'=> $bpjs_tk_jkm_bruto_rupiah + $bpjs_tk_jkm_neto_rupiah,
                    'bpjs_tk_jkm_perusahaan_rupiah'=> $bpjs_tk_jkm_bruto_rupiah,
                    'bpjs_tk_jkm_karyawan_rupiah'=> $bpjs_tk_jkm_neto_rupiah,
                    'bpjs_tk_jkk_rupiah'=> $bpjs_tk_jkk_bruto_rupiah + $bpjs_tk_jkk_neto_rupiah,
                    'bpjs_tk_jkk_perusahaan_rupiah'=> $bpjs_tk_jkk_bruto_rupiah,
                    'bpjs_tk_jkk_karyawan_rupiah'=> $bpjs_tk_jkk_neto_rupiah,
                    'bpjs_tk_jht_rupiah'=> $bpjs_tk_jht_bruto_rupiah + $bpjs_tk_jht_neto_rupiah,
                    'bpjs_tk_jht_perusahaan_rupiah'=> $bpjs_tk_jht_bruto_rupiah,
                    'bpjs_tk_jht_karyawan_rupiah'=> $bpjs_tk_jht_neto_rupiah,
                    'bpjs_tk_jpn_rupiah'=> $bpjs_tk_jpn_bruto_rupiah + $bpjs_tk_jpn_neto_rupiah,
                    'bpjs_tk_jpn_perusahaan_rupiah'=> $bpjs_tk_jpn_bruto_rupiah,
                    'bpjs_tk_jpn_karyawan_rupiah'=> $bpjs_tk_jpn_neto_rupiah,
                    'bpjs_ks_jkn_rupiah'=> $bpjs_ks_jkn_bruto_rupiah + $bpjs_ks_jkn_neto_rupiah,
                    'bpjs_ks_jkn_perusahaan_rupiah'=> $bpjs_ks_jkn_bruto_rupiah,
                    'bpjs_ks_jkn_karyawan_rupiah'=> $bpjs_ks_jkn_neto_rupiah,
                    'total_bpjs_tk'=>$bpjs_tk_jkm_neto_rupiah + $bpjs_tk_jkk_neto_rupiah + $bpjs_tk_jht_neto_rupiah + $bpjs_tk_jpn_neto_rupiah,
                    'total_bpjs_ks'=>$bpjs_ks_jkn_neto_rupiah,

                ];
            }
        }
       // $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->get();

        $records=[];
        foreach ($data as $k => $v) {
           // $count=$security->where('enroll_id',$v['enroll_id'])->count();
           // if($count==0){
                $upah_bruto_rupiah=($v['upah_per_bulan']+$v['tunjangan_karyawan_rupiah']+$v['premi_karyawan']+$v['total_lembur_rupiah']+$v['pendapatan_lainnya_rupiah']+$v['koreksi_upah_rupiah'])-
                    ($v['koreksi_potongan_rupiah']+$v['potongan_iks_rupiah']+$v['potongan_dtpc_rupiah']+$v['potongan_kehadiran_rupiah']);

                $upah_neto_rupiah=($v['upah_per_bulan']+$v['tunjangan_karyawan_rupiah']+$v['premi_karyawan']+$v['total_lembur_rupiah']+$v['pendapatan_lainnya_rupiah']+$v['koreksi_upah_rupiah'])-
                    ($v['koreksi_potongan_rupiah']+$v['potongan_iks_rupiah']+$v['potongan_dtpc_rupiah']+$v['potongan_kehadiran_rupiah'])-$v['pph21'];

                $upah_bersih_rupiah=$upah_neto_rupiah-($v['total_bpjs_tk']+$v['total_bpjs_ks']+$v['iuran_koperasi']+$v['iuran_serikat_rupiah']);

                $total_upah_thp_rupiah=$upah_neto_rupiah-($v['total_bpjs_tk']+$v['total_bpjs_ks']+$v['iuran_koperasi']+$v['iuran_serikat_rupiah'])-$v['potongan_kasbon_rupiah'];

                $jumlah_potongan_rupiah=($v['total_bpjs_tk']+$v['total_bpjs_ks']+$v['iuran_koperasi']+$v['iuran_serikat_rupiah']);

                $records[$k]=[
                    'kode_rekap_payroll'=>$v['kode_rekap_payroll'],
                    'periode_kehadiran'=>$v['periode_kehadiran'],
                    'periode_tahun_payroll'=>$v['periode_tahun_payroll'],
                    'periode_bulan_payroll'=>$v['periode_bulan_payroll'],
                    'enroll_id'=>$v['enroll_id'],
                    'nik'=>$v['nik'],
                    'kode_grade'=>$v['kode_grade'],
                    'join_date'=>$v['join_date'],

                    'site_nirwana_name'=>$v['site_nirwana_name'],
                    'employee_name'=>$v['employee_name'],
                    'tanggal_resign'=>$v['tanggal_resign'],
                    'kehadiran_iby'=>$v['kehadiran_iby'],
                    'kehadiran_itb'=>$v['kehadiran_itb'],
                    'kehadiran_m'=>$v['kehadiran_m'],
                    'kehadiran_dt'=>$v['kehadiran_dt'],
                    'kehadiran_pc'=>$v['kehadiran_pc'],
                    'kehadiran_dtpc'=>$v['kehadiran_dtpc'],
                    'kehadiran_lby'=>$v['kehadiran_lby'],
                    'kehadiran_lsm'=>$v['kehadiran_lsm'],
                    'kehadiran_r'=>$v['kehadiran_r'],
                    'kehadiran_ok'=>$v['kehadiran_ok'],
                    'kehadiran_tk'=>$v['kehadiran_tk'],
                    'total_kehadiran'=>$v['total_kehadiran'],
                    'total_kehadiran_net'=>$v['total_kehadiran_net'],
                    'ptkp'=>$v['ptkp'],
                    'upah_per_bulan'=>$v['upah_per_bulan'],
                    'upah_per_hari'=>$v['upah_per_hari'],
                    'upah_per_menit'=>$v['upah_per_menit'],
                                    'kehadiran_m_estimasi'=>$v['kehadiran_m_estimasi'],

                    'tunjangan_karyawan_rupiah'=>$v['tunjangan_karyawan_rupiah'],
                    'premi_karyawan'=>$v['premi_karyawan'],

                    'lembur_1'=>$v['lembur_1'],
                    'lembur_2'=>$v['lembur_2'],
                    'lembur_3'=>$v['lembur_3'],
                    'lembur_4'=>$v['lembur_4'],
                    'total_lembur_1234'=>$v['total_lembur_1234'],
                    'lembur1_rupiah'=>$v['lembur1_rupiah'],
                    'lembur2_rupiah'=>$v['lembur2_rupiah'],
                    'lembur3_rupiah'=>$v['lembur3_rupiah'],
                    'lembur4_rupiah'=>$v['lembur4_rupiah'],
                    'total_lembur_rupiah'=>$v['total_lembur_rupiah'],

                    'pendapatan_lainnya_rupiah'=>$v['pendapatan_lainnya_rupiah'],

                    'koreksi_upah_rupiah'=>$v['koreksi_upah_rupiah'],
                    'koreksi_potongan_rupiah'=>$v['koreksi_potongan_rupiah'],

                    'potongan_iks_menit'=>$v['potongan_iks_menit'],
                    'potongan_dt_menit'=>$v['potongan_dt_menit'],
                    'potongan_pc_menit'=>$v['potongan_pc_menit'],
                    'potongan_dtpc_menit'=>$v['potongan_dtpc_menit'],
                    'potongan_iks_rupiah'=>$v['potongan_iks_rupiah'],
                    'potongan_dt_rupiah'=>$v['potongan_dt_rupiah'],
                    'potongan_pc_rupiah'=>$v['potongan_pc_rupiah'],
                    'potongan_dtpc_rupiah'=>$v['potongan_dtpc_rupiah'],
                    'potongan_kehadiran_rupiah'=>$v['potongan_kehadiran_rupiah'],


                    'upah_bruto_rupiah'=>$upah_bruto_rupiah??0,
                    'upah_neto_rupiah'=>$upah_neto_rupiah??0,

                    'upah_bersih_rupiah'=>$upah_bersih_rupiah??0,
                    'total_upah_thp_rupiah'=>$total_upah_thp_rupiah??0,
                    'jumlah_potongan_rupiah'=>$jumlah_potongan_rupiah??0,


                    'pph21'=>$v['pph21'], // dari mana?
                    'iuran_serikat_rupiah'=>$v['iuran_serikat_rupiah'], // dari mana?
                    'iuran_koperasi'=>$v['iuran_koperasi'], // dari mana?
                    'potongan_kasbon_rupiah'=>$v['potongan_kasbon_rupiah'], // dari mana?

                    'bpjs_tk_jkm_rupiah'=> $v['bpjs_tk_jkm_rupiah'],
                    'bpjs_tk_jkm_perusahaan_rupiah'=> $v['bpjs_tk_jkm_perusahaan_rupiah'],
                    'bpjs_tk_jkm_karyawan_rupiah'=> $v['bpjs_tk_jkm_karyawan_rupiah'],
                    'bpjs_tk_jkk_rupiah'=> $v['bpjs_tk_jkk_rupiah'],
                    'bpjs_tk_jkk_perusahaan_rupiah'=> $v['bpjs_tk_jkk_perusahaan_rupiah'],
                    'bpjs_tk_jkk_karyawan_rupiah'=> $v['bpjs_tk_jkk_karyawan_rupiah'],
                    'bpjs_tk_jht_rupiah'=> $v['bpjs_tk_jht_rupiah'],
                    'bpjs_tk_jht_perusahaan_rupiah'=> $v['bpjs_tk_jht_perusahaan_rupiah'],
                    'bpjs_tk_jht_karyawan_rupiah'=> $v['bpjs_tk_jht_karyawan_rupiah'],
                    'bpjs_tk_jpn_rupiah'=> $v['bpjs_tk_jpn_rupiah'],
                    'bpjs_tk_jpn_perusahaan_rupiah'=> $v['bpjs_tk_jpn_perusahaan_rupiah'],
                    'bpjs_tk_jpn_karyawan_rupiah'=> $v['bpjs_tk_jpn_karyawan_rupiah'],
                    'bpjs_ks_jkn_rupiah'=> $v['bpjs_ks_jkn_rupiah'],
                    'bpjs_ks_jkn_perusahaan_rupiah'=> $v['bpjs_ks_jkn_perusahaan_rupiah'],
                    'bpjs_ks_jkn_karyawan_rupiah'=> $v['bpjs_ks_jkn_karyawan_rupiah'],
                    'total_bpjs_tk'=>$v['total_bpjs_tk'],
                    'total_bpjs_ks'=>$v['total_bpjs_ks'],

                    'status_kawin'=>$v['status_kawin'],
                    'jabatan_karyawan'=>$v['jabatan_karyawan'],
                    'nama_bagian'=>$v['nama_bagian'],
                    'nama_department'=>$v['nama_department'],
                    'kategori_karyawan'=>$v['kategori_karyawan'],
                    'aktif_karyawan'=>$v['aktif_karyawan'],
                    'jenis_kelamin'=>$v['jenis_kelamin'],
                    'nama_bank'=>$v['nama_bank'],
                    'nomor_rekening_bank'=>$v['nomor_rekening_bank'],
                    'npwp'=>$v['npwp'],
                    'operator'=>$v['operator'],
                    'sub_dept_id'=>$v['sub_dept_id'],
                    'total_upah_thp_rupiah_employee'=>ceil($total_upah_thp_rupiah / 100) * 100
                ];

                // $count=RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$v['kode_rekap_payroll'])->count();
                // if($count){
                //     RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$v['kode_rekap_payroll'])->update($records);
                // }
                // else{
                //     RekapPerhitunganPayroll::create($records);
                // }
          //  }
        }

        dd($records);
    }
    public function rekap_payroll_2()
    {
        // $tanggal_awal='2023-02-26';
        // $tanggal_akhir='2023-03-25';
        $bulan_priode='2023-11';
        $bulan_sekarang1 = strtotime(date( $bulan_priode));

        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';

        $karyawan=EmployeeAtribut::where('enroll_id','6054')
        ->orWhere('enroll_id','3072')
        ->get();
        // $karyawan=EmployeeAtribut::where('status_aktif','AKTIF')->where('enroll_id','4966')->get();

        $periode_payroll=$tanggal_awal.' s/d '.$tanggal_akhir;// menggunakan s/d

        $tanggal_awal_baru = date('m/d/Y', strtotime($tanggal_awal));
        $tanggal_akhir_baru = date('m/d/Y', strtotime($tanggal_akhir));
        $periode_payroll2 = $tanggal_awal_baru . ' - ' . $tanggal_akhir_baru;// menggunakan -

        $data=[];
        foreach ($karyawan as $key => $value) {

            $rekap_kehadiran=RekapPerhitunganKehadiranKaryawan::where('enroll_id',$value->enroll_id)->where('periode_payroll',$periode_payroll)->first();

            $rekap_lembur=RekapPerhitunganLembur::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->get();

            $rekap_iks=RekapPerhitunganIKS::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->get();

            $rekap_dtpc=RekapPerhitunganDTPC::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->get();

            $Bpjs=EmployeeBpjs::where('enroll_id',$value->enroll_id)->where('periode_kehadiran',$periode_payroll)->first();


            $koreksi_upah=DataKoreksiUpah::where('enroll_id',$value->enroll_id)->where('periode_tanggal_koreksi',$periode_payroll2)->get();

            $koreksi_potongan=DataKoreksiPotongan::where('enroll_id',$value->enroll_id)->where('periode_tanggal_koreksi',$periode_payroll2)->get();

            $tunjangan=TunjanganKaryawan::where('enroll_id',$value->enroll_id)->where('periode_payroll',$periode_payroll)->first();

            if($rekap_kehadiran!=null){
                list($periode_tahun_payroll, $periode_bulan_payroll) = explode("-", $rekap_kehadiran->periode_tahun_bulan);
                //tunjangan
                $tanggal_masuk = $value->join_date;

                // hitung selisih tahun antara tanggal masuk dan sekarang
                $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_awal))->y;

                // tentukan besaran tunjangan berdasarkan masa kerja
                if ($selisih_tahun < 1) {
                    $tunjangan = 0;
                } elseif ($selisih_tahun < 3) {
                    $tunjangan = 2500;
                } elseif ($selisih_tahun < 6) {
                    $tunjangan = 5000;
                }elseif ($selisih_tahun < 9) {
                    $tunjangan = 7500;
                }elseif ($selisih_tahun < 12) {
                    $tunjangan = 10000;
                }else{
                    $tunjangan = 12500;
                }


                $bpjs_tk_jkm_bruto_rupiah=$Bpjs->bpjs_tk_jkm_bruto_rupiah??0;
                $bpjs_tk_jkm_neto_rupiah=$Bpjs->bpjs_tk_jkm_neto_rupiah??0;
                $bpjs_tk_jkk_bruto_rupiah=$Bpjs->bpjs_tk_jkk_bruto_rupiah??0;
                $bpjs_tk_jkk_neto_rupiah=$Bpjs->bpjs_tk_jkk_neto_rupiah??0;
                $bpjs_tk_jht_bruto_rupiah=$Bpjs->bpjs_tk_jht_bruto_rupiah??0;
                $bpjs_tk_jht_neto_rupiah=$Bpjs->bpjs_tk_jht_neto_rupiah??0;
                $bpjs_tk_jpn_bruto_rupiah=$Bpjs->bpjs_tk_jpn_bruto_rupiah??0;
                $bpjs_tk_jpn_neto_rupiah=$Bpjs->bpjs_tk_jpn_neto_rupiah??0;
                $bpjs_ks_jkn_bruto_rupiah=$Bpjs->bpjs_ks_jkn_bruto_rupiah??0;
                $bpjs_ks_jkn_neto_rupiah=$Bpjs->bpjs_ks_jkn_neto_rupiah??0;



                $data[]=[
                    'kode_rekap_payroll'=>$rekap_kehadiran->kode_rekap,
                    'periode_kehadiran'=>$rekap_kehadiran->periode_payroll,
                    'periode_tahun_payroll'=>$periode_tahun_payroll,
                    'periode_bulan_payroll'=>$periode_bulan_payroll,
                    'enroll_id'=>$value->enroll_id,
                    'nik'=>$value->nik,
                    'kode_grade'=>$value->kode_grade,
                    'join_date'=>$value->join_date,
                    'employee_name'=>$value->employee_name,
                    'tanggal_resign'=>$value->tanggal_resign,
                    'kehadiran_iby'=>$rekap_kehadiran->kehadiran_iby,
                    'kehadiran_itb'=>$rekap_kehadiran->kehadiran_itb,
                    'kehadiran_m'=>$rekap_kehadiran->kehadiran_m,
                    'kehadiran_dt'=>$rekap_kehadiran->kehadiran_dt,
                    'kehadiran_pc'=>$rekap_kehadiran->kehadiran_pc,
                    'kehadiran_dtpc'=>$rekap_kehadiran->kehadiran_dtpc,
                    'kehadiran_lby'=>$rekap_kehadiran->kehadiran_lby,
                    'kehadiran_lsm'=>$rekap_kehadiran->kehadiran_lsm,
                    'kehadiran_r'=>$rekap_kehadiran->kehadiran_r,
                    'kehadiran_ok'=>$rekap_kehadiran->kehadiran_ok,
                    'kehadiran_tk'=>$rekap_kehadiran->kehadiran_tk,
                    'total_kehadiran'=>$rekap_kehadiran->total_kehadiran,
                    'total_kehadiran_net'=>$rekap_kehadiran->total_kehadiran_net,
                    'ptkp'=>$value->ptkp,
                    'upah_per_bulan'=>$rekap_kehadiran->gaji_pokok,
                    'upah_per_hari'=>$rekap_kehadiran->gaji_harian,
                    'upah_per_menit'=>$rekap_kehadiran->gaji_menit,

                    'tunjangan_karyawan_rupiah'=>$tunjangan,
                    'premi_karyawan'=>0,

                    'lembur_1'=>$rekap_lembur->sum('lembur_1')??0,
                    'lembur_2'=>$rekap_lembur->sum('lembur_2')??0,
                    'lembur_3'=>$rekap_lembur->sum('lembur_3')??0,
                    'lembur_4'=>$rekap_lembur->sum('lembur_4')??0,
                    'total_lembur_1234'=>$rekap_lembur->sum('total_lembur_1234')??0,
                    'lembur1_rupiah'=>$rekap_lembur->sum('lembur1_rupiah')??0,
                    'lembur2_rupiah'=>$rekap_lembur->sum('lembur2_rupiah')??0,
                    'lembur3_rupiah'=>$rekap_lembur->sum('lembur3_rupiah')??0,
                    'lembur4_rupiah'=>$rekap_lembur->sum('lembur4_rupiah')??0,
                    'total_lembur_rupiah'=>$rekap_lembur->sum('total_lembur_rupiah')??0,

                    'pendapatan_lainnya_rupiah'=>0,

                    'koreksi_upah_rupiah'=>$koreksi_upah->sum('jumlah_rp_potongan')??0,
                    'koreksi_potongan_rupiah'=>$koreksi_potongan->sum('jumlah_rp_potongan')??0,

                    'potongan_iks_menit'=>$rekap_iks->sum('lama_ijin_menit')??0,
                    'potongan_dt_menit'=>$rekap_dtpc->sum('jumlah_menit_absen_dt')??0,
                    'potongan_pc_menit'=>$rekap_dtpc->sum('jumlah_menit_absen_pc')??0,
                    'potongan_dtpc_menit'=>$rekap_dtpc->sum('jumlah_menit_absen_dtpc')??0,
                    'potongan_iks_rupiah'=>$rekap_iks->sum('potongan_iks_rupiah')??0,
                    'potongan_dt_rupiah'=>$rekap_dtpc->sum('potongan_dt_rupiah')??0,
                    'potongan_pc_rupiah'=>$rekap_dtpc->sum('potongan_pc_rupiah')??0,
                    'potongan_dtpc_rupiah'=>$rekap_dtpc->sum('potongan_dtpc_rupiah')??0,
                    'potongan_kehadiran_rupiah'=>$rekap_kehadiran->potongan_kehadiran_rupiah,
 										'kehadiran_m_estimasi'=>$rekap_kehadiran->kehadiran_m_estimasi,

                    // 'upah_bruto_rupiah'=>$value,
                    // 'upah_neto_rupiah'=>$value,

                    // 'upah_bersih_rupiah'=>$value,
                    // 'total_upah_thp_rupiah'=>$value,
                    // 'jumlah_potongan_rupiah'=>$value,


                    'pph21'=>0, // dari mana?
                    'iuran_serikat_rupiah'=>0, // dari mana?
                    'iuran_koperasi'=>0, // dari mana?
                    'potongan_kasbon_rupiah'=>0, // dari mana?

                    'bpjs_tk_jkm_rupiah'=> $bpjs_tk_jkm_bruto_rupiah + $bpjs_tk_jkm_neto_rupiah,
                    'bpjs_tk_jkm_perusahaan_rupiah'=> $bpjs_tk_jkm_bruto_rupiah,
                    'bpjs_tk_jkm_karyawan_rupiah'=> $bpjs_tk_jkm_neto_rupiah,
                    'bpjs_tk_jkk_rupiah'=> $bpjs_tk_jkk_bruto_rupiah + $bpjs_tk_jkk_neto_rupiah,
                    'bpjs_tk_jkk_perusahaan_rupiah'=> $bpjs_tk_jkk_bruto_rupiah,
                    'bpjs_tk_jkk_karyawan_rupiah'=> $bpjs_tk_jkk_neto_rupiah,
                    'bpjs_tk_jht_rupiah'=> $bpjs_tk_jht_bruto_rupiah + $bpjs_tk_jht_neto_rupiah,
                    'bpjs_tk_jht_perusahaan_rupiah'=> $bpjs_tk_jht_bruto_rupiah,
                    'bpjs_tk_jht_karyawan_rupiah'=> $bpjs_tk_jht_neto_rupiah,
                    'bpjs_tk_jpn_rupiah'=> $bpjs_tk_jpn_bruto_rupiah + $bpjs_tk_jpn_neto_rupiah,
                    'bpjs_tk_jpn_perusahaan_rupiah'=> $bpjs_tk_jpn_bruto_rupiah,
                    'bpjs_tk_jpn_karyawan_rupiah'=> $bpjs_tk_jpn_neto_rupiah,
                    'bpjs_ks_jkn_rupiah'=> $bpjs_ks_jkn_bruto_rupiah + $bpjs_ks_jkn_neto_rupiah,
                    'bpjs_ks_jkn_perusahaan_rupiah'=> $bpjs_ks_jkn_bruto_rupiah,
                    'bpjs_ks_jkn_karyawan_rupiah'=> $bpjs_ks_jkn_neto_rupiah,
                    'total_bpjs_tk'=>$bpjs_tk_jkm_neto_rupiah + $bpjs_tk_jkk_neto_rupiah + $bpjs_tk_jht_neto_rupiah + $bpjs_tk_jpn_neto_rupiah,
                    'total_bpjs_ks'=>$bpjs_ks_jkn_neto_rupiah,

                    'jabatan_karyawan'=>$value->status_jabatan,
                    'nama_bagian'=>$value->sub_dept_name??'',
                    'nama_department'=>$value->department_name??'',
                    'kategori_karyawan'=>$value->status_staff,
                    'aktif_karyawan'=>$value->status_aktif,
                    'jenis_kelamin'=>$value->jenis_kelamin,
                    'status_kawin'=>$value->status_kawin,
                    'site_nirwana_name'=>$value->site_nirwana_name,
                    'nama_bank'=>$value->nama_bank==null||$value->nama_bank==""||$value->nama_bank=="-"?'TUNAI':$value->nama_bank,
                    'nomor_rekening_bank'=>$value->nomor_rekening_bank==null||$value->nomor_rekening_bank==""||$value->nomor_rekening_bank=="-"?'-':$value->nomor_rekening_bank,
                    //'nomor_rekening_bank'=>$value->nomor_rekening_bank==null||$value->nomor_rekening_bank==""||$value->nomor_rekening_bank=="-"?'TRANSFER':$value->nomor_rekening_bank,
                    'npwp'=>$value->npwp,
                    'operator'=>'sistem',
                    'sub_dept_id'=>$value->sub_dept_id,
                ];
            }
        }
       // $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->get();

        $records=[];
        foreach ($data as $k => $v) {
           // $count=$security->where('enroll_id',$v['enroll_id'])->count();
           // if($count==0){
            $upah_bruto_rupiah=($v['upah_per_bulan']+$v['tunjangan_karyawan_rupiah']+$v['premi_karyawan']+$v['total_lembur_rupiah']+$v['pendapatan_lainnya_rupiah']+$v['koreksi_upah_rupiah'])-
                ($v['koreksi_potongan_rupiah']+$v['potongan_iks_rupiah']+$v['potongan_dtpc_rupiah']+$v['potongan_kehadiran_rupiah']);

            $upah_neto_rupiah=($v['upah_per_bulan']+$v['tunjangan_karyawan_rupiah']+$v['premi_karyawan']+$v['total_lembur_rupiah']+$v['pendapatan_lainnya_rupiah']+$v['koreksi_upah_rupiah'])-
                ($v['koreksi_potongan_rupiah']+$v['potongan_iks_rupiah']+$v['potongan_dtpc_rupiah']+$v['potongan_kehadiran_rupiah'])-$v['pph21'];

            $upah_bersih_rupiah=$upah_neto_rupiah-($v['total_bpjs_tk']+$v['total_bpjs_ks']+$v['iuran_koperasi']+$v['iuran_serikat_rupiah']);

            $total_upah_thp_rupiah=$upah_neto_rupiah-($v['total_bpjs_tk']+$v['total_bpjs_ks']+$v['iuran_koperasi']+$v['iuran_serikat_rupiah'])-$v['potongan_kasbon_rupiah'];

            $jumlah_potongan_rupiah=($v['total_bpjs_tk']+$v['total_bpjs_ks']+$v['iuran_koperasi']+$v['iuran_serikat_rupiah']);

            $records=[
                'kode_rekap_payroll'=>$v['kode_rekap_payroll'],
                'nama_bagian'=>$v['nama_bagian'],
                'nama_department'=>$v['nama_department'],
            ];

            $count=RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$v['kode_rekap_payroll'])->count();
            if($count){
                RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$v['kode_rekap_payroll'])->update($records);
            }
            else{
                RekapPerhitunganPayroll::create($records);
            }
        }

        // return true;
    }
    public function tes_2(){
        $enroll_id='';
        $absen_perijinan=DataAbsenPerijinan::where('tanggal_mulai_ijin','<=','2023-12-25')->where('tanggal_akhir_ijin','>=','2023-11-26')->get();
        $x=[];
        foreach($absen_perijinan as $key=>$value){
            $x[$key]=([
                'enroll_id'=>$value->enroll_id,
                'nomor_absen_ijin'=>$value->nomor_form_perizinan,
                'tanggal_mulai_ijin'=>$value->tanggal_mulai_ijin,
                'tanggal_akhir_ijin'=>$value->tanggal_akhir_ijin,
                'absen_alasan'=>$value->absen_alasan
            ]);
        }
        dd($x);
        // foreach($absen_perijinan as $absen){
        //     MasterDataAbsenKehadiran::where('enroll_id',$absen->enroll_id)->where('tanggal_berjalan','>=',date('Y-m-d'))->where('tanggal_berjalan','<=',$absen->tanggal_akhir_ijin)->where('kode_hari','!=','5')->where('kode_hari','!=','6')->update([
        //         'status_absen'=>$absen->kode_absen_ijin,
        //         'nomor_absen_ijin'=>$absen->nomor_form_perizinan,
        //         'tanggal_mulai_ijin'=>$absen->tanggal_mulai_ijin,
        //         'tanggal_akhir_ijin'=>$absen->tanggal_akhir_ijin,
        //         'absen_alasan'=>$absen->absen_alasan
        //     ]);
        // }
    }

 	public function update_tgl_resign($bulan_priode){
        list($year, $month) = explode('-', $bulan_priode);

        $rekap=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('total_kehadiran_net','0')->get();
        foreach ($rekap as $key => $value) {
            $karyawan=EmployeeAtribut::where('enroll_id',$value->enroll_id)->first();
            $data=['tanggal_resign'=>$karyawan->tanggal_resign];
            RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$value->kode_rekap_payroll)->update($data);
        }
        return true;

    }

     public function pembulatan($bulan_priode){
        list($year, $month) = explode('-', $bulan_priode);

        $rekap=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->get();
        foreach ($rekap as $key => $value) {

                $total_upah_thp_rupiah_pembulatan= ceil($value->total_upah_thp_rupiah / 100) * 100;
                $pembulatan=$total_upah_thp_rupiah_pembulatan-$value->total_upah_thp_rupiah;

            $data=['pembulatan'=>$pembulatan,];
            RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$value->kode_rekap_payroll)->update($data);
        }
        return true;

    }

    public function index(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '3000000000000M');
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        $selectedEnrollId=$request->selectEmployeeID;
        $periode_umk=$request->periode_umk;
        if(request()->periode_payrols){
            $periode_payroll=request()->periode_payrols;
        }else{
            $periode_payroll = '2025-01';
        }

        $bulan_sekarang1 = strtotime(date($periode_payroll));
        $tanggal_sekarang=date('Y-m-d');
        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';
        $tahun=date('Y', $bulan_sekarang1);
        $bulan=date('m', $bulan_sekarang1);

        $timestamp1 = strtotime($tanggal_awal);
        $timestamp2 = strtotime($tanggal_akhir);
        $jumlah_hari_total=(abs($timestamp2 - $timestamp1) / (60 * 60 * 24)+1);
        $jumlah_hari_sabtu_minggu_total = 0;
        $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->where('enroll_id','!=',7445)->get();
        for ($i = strtotime($tanggal_awal); $i <= strtotime($tanggal_akhir); $i += 86400) {
            if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
                $jumlah_hari_sabtu_minggu_total++;
            }
        }

        $priode=$tanggal_awal.' s/d '. $tanggal_akhir;

        $inEnrollId='';
        if($selectedEnrollId){
            $enroll_id = implode(", ", $selectedEnrollId);
            $allEnroll_id= '('.$enroll_id.')';
            $inEnrollId = ' AND enroll_id IN '.$allEnroll_id.'';
        }
        // return $inEnrollId;
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');
        $tidak_bayar=RefAbsenIjin::where('kode_ijin_payroll','ITB')->where('kode_absen_ijin','!=','M')->where('kode_absen_ijin','!=','IKS')->get()->toArray();
        $ITB=array_column($tidak_bayar,'kode_absen_ijin');
        $ITB_all = '"'.implode('","', $ITB).'"';
        $allITB= '('.$ITB_all.')';
        $inAllITB = ' AND status_absen IN  '.$allITB.'';
        //update bpjs variable
        $kode_bpjs =  BpjsSetting::orderBy('kode_periode_bpjs','desc')->limit(1)->first();
        $periode_payroll_bpjs=$tanggal_awal.' s/d '. $tanggal_akhir;
        $explodePeriodePayroll = explode(" s/d ", $periode_payroll_bpjs);
        $periodePayroll = substr($explodePeriodePayroll[1], 0, 4) . substr($explodePeriodePayroll[1], 5, 2);
        $explodeKode = explode("-", $kode_bpjs->kode_periode_bpjs);
        $kode_periode_bpjs = $explodeKode[0] . $explodeKode[1];
        $sqlKodePeriodeBPJS = 'concat("' . $periodePayroll . '", lpad(enroll_id, 5, 0))';

        //rekap payroll variable
        $tanggal_awal_baru = date('m/d/Y', strtotime($tanggal_awal));
        $tanggal_akhir_baru = date('m/d/Y', strtotime($tanggal_akhir));
        $periode_payroll2 = $tanggal_awal_baru . ' - ' . $tanggal_akhir_baru;// menggunakan -

        //update tanggal resign variable
        list($year, $month) = explode('-', $periode_payroll);

        //rekap kehadiran
        $jumlah_hari='';
        $jumlah_hari_sabtu_minggu = 0;
        $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->get();
        if(request()->periode_payrols){
            // rekap kehadiran
            if($selectedEnrollId){
                $employees=EmployeeAtribut::whereIn('enroll_id',$selectedEnrollId)->where(function($query)use($tanggal_awal){
                    $query->where('status_aktif','AKTIF')
                    ->orWhere('tanggal_resign','>=',$tanggal_awal)
                    ->orWhere('tanggal_resign',null);
                })->with(['absensi' => function ($query) use ($tanggal_awal,$tanggal_akhir) {
                    $query->where('tanggal_berjalan', '>=', $tanggal_awal)
                    ->where('tanggal_berjalan','<=',$tanggal_akhir);
                }])->orderBy('enroll_id', 'asc')->get();
                foreach ($employees as $key => $value) {
                    $enroll_id=$value->enroll_id;
                    $unik=sprintf("%04d", $enroll_id);
                    $kode_rekap_kehadiran=$bulan_sekarang.$unik;
                    $libur_sabtu_minggu=$value['absensi']->whereIn('status_absen',$IBY)->count();
                    $libur=$value['absensi']->where('status_absen','M')->where('mulai_jam_kerja',null)->count();
                    $lsm_security=$libur_sabtu_minggu+$libur;
                    $lsm_employe=$value['absensi']->whereIn('kode_hari', ['5','6'])->count();
                    $absen_M_security=$value['absensi']->where('status_absen','M')->where('mulai_jam_kerja','!=',null)->count();
                    $absen_M=$value['absensi']->where('status_absen','M')->count();

                    $absen_TL=$value['absensi']->where('status_absen','TL')->where('mulai_jam_kerja','!=',null)->count();
                    $absen_R=$value['absensi']->where('status_absen','R')->count();

                    $absen_ok=$value['absensi']->whereNotin('kode_hari', ['5','6'])->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();
                    $absen_IKS=$value['absensi']->where('status_absen','IKS')->whereNotNull('mulai_jam_kerja')->count();
                    $absen_ok_employee=$absen_ok+$absen_IKS;

                    $absen_ok_security=$value['absensi']->where('mulai_jam_kerja','!=',null)->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();

                    $absen_ok_security_total=$absen_ok_security+$absen_IKS;

                    $dt_employe=$value['absensi']->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','0')->where('status_absen',null)->count();
                    $pc_employe=$value['absensi']->where('jumlah_menit_absen_pc','>','0')->where('jumlah_menit_absen_dt','0')->where('status_absen',null)->count();
                    $dtpc_employe=$value['absensi']->where('enroll_id',$value->enroll_id)->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','>','0')->where('status_absen',null)->count();

                    $LBY_employe=$value['absensi']->where('status_absen','LN')->whereNotin('kode_hari', ['5','6'])->count();
                    $IBY_employe=$value['absensi']->whereIn('status_absen',$IBY)->count();
                    $ITB_employe=$value['absensi']->wherein('status_absen', $ITB)->count();

                    $total_kehadiran_net_security=$absen_ok_security+$dt_employe+$pc_employe+$dtpc_employe;
                    $aa_security=$total_kehadiran_net_security+$ITB_employe+$LBY_employe+$IBY_employe+$lsm_security+$absen_M_security+$absen_TL+$absen_R;

                    $total_kehadiran_net=$absen_ok+$dt_employe+$pc_employe+$dtpc_employe;
                    $aa=$total_kehadiran_net+$ITB_employe+$LBY_employe+$IBY_employe+$lsm_employe+$absen_M+$absen_TL+$absen_R;

                    $kehadiran_tk_security=$jumlah_hari_total-$aa_security;
                    $kehadiran_tk=$jumlah_hari_total-$aa;

                    $total_kehadiran_security=$IBY_employe+$ITB_employe+$lsm_security+$dtpc_employe+$absen_M_security+$absen_R+$absen_ok_security+$LBY_employe+$dt_employe+$pc_employe+$absen_TL;
                    $total_kehadiran=$IBY_employe+$ITB_employe+$lsm_employe+$dtpc_employe+$absen_M+$absen_R+$absen_ok+$LBY_employe+$dt_employe+$pc_employe+$absen_TL;
                    $M_estimasi=$value['absensi']->where('status_absen','M')->where('tanggal_berjalan','>=',date('Y-m-d'))->count();
                    $TL_estimasi=$value['absensi']->where('status_absen','TL')->where('tanggal_berjalan','>=',date('Y-m-d'))->where('mulai_jam_kerja','!=',null)->count();

                    if($security->where('enroll_id',$value->enroll_id)->count()){
                        $lsm=$lsm_security;
                        $m=$absen_M_security;
                        $ok=$absen_ok_security_total;
                        $tk=$kehadiran_tk_security;
                        $total_kh=$total_kehadiran_security;
                        $jumlah_hari_kerja=25;
                    }else{
                        $lsm=$lsm_employe;
                        $m=$absen_M;
                        $ok=$absen_ok_employee;
                        $tk=$kehadiran_tk;
                        $total_kh=$total_kehadiran;
                        $jumlah_hari_kerja=$jumlah_hari_total-$jumlah_hari_sabtu_minggu_total;
                    }
                    $row=[
                        'uuid'=>Str::uuid('uuid'),
                        'kode_rekap_kehadiran'=> $kode_rekap_kehadiran,
                        'periode_umk'=>null,
                        'periode_payroll'=>$priode,
                        'periode_tahun'=>$tahun,
                        'periode_bulan'=>$bulan,
                        'enroll_id'=>$value['enroll_id'],
                        'nik'=>$value['nik'],
                        'employee_name'=>$value['employee_name'],
                        'site_nirwana_id'=>$value['site_nirwana_id'],
                        'site_nirwana_name'=>$value['site_nirwana_name'],
                        'department_id'=>$value['department_id'],
                        'department_name'=>$value['department_name'],
                        'sub_dept_id'=>$value['sub_dept_id'],
                        'sub_dept_name'=>$value['sub_dept_name'],
                        'join_date'=>$value['join_date'],
                        'tanggal_resign'=>$value['tanggal_resign'],
                        'status_aktif'=>$value['status_aktif'],
                        'status_staff'=>$value['status_staff'],
                        'kehadiran_iby'=>$value['absensi']->whereIn('status_absen',$IBY)->count(),
                        'kehadiran_itb'=>$value['absensi']->whereIn('status_absen',$ITB)->count(),
                        'kehadiran_lby'=>$value['absensi']->where('status_absen','LN')->whereNotin('kode_hari', ['5','6'])->count(),
                        'kehadiran_lsm'=>$lsm,
                        'kehadiran_dt'=> $value['absensi']->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','0')->where('status_absen',null)->count(),
                        'kehadiran_pc'=> $value['absensi']->where('jumlah_menit_absen_pc','>','0')->where('jumlah_menit_absen_dt','0')->where('status_absen',null)->count(),
                        'kehadiran_dtpc'=>$value['absensi']->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','>','0')->where('status_absen',null)->count(),
                        'kehadiran_m'=>$m+$absen_TL,
                        'kehadiran_r'=>$absen_R,
                        'kehadiran_ok'=>$ok,
                        'total_kehadiran_net'=>$ok+$dt_employe+$pc_employe+$dtpc_employe+$LBY_employe+$IBY_employe,
                        'kehadiran_tk'=>$tk,
                        'total_kehadiran'=> $total_kh,
                        'jumlah_hari'=>$jumlah_hari_total,
                        'jumlah_hari_kerja'=>$jumlah_hari_kerja,
                        'kehadiran_dl'=>$value['absensi']->where('status_absen','DL')->count(),
                        'kehadiran_cb'=>$value['absensi']->where('status_absen','CB')->count(),
                        'kehadiran_cbd'=>$value['absensi']->where('status_absen','CBD')->count(),
                        'kehadiran_cg'=>$value['absensi']->where('status_absen','CG')->count(),
                        'kehadiran_ch'=>$value['absensi']->where('status_absen','CH')->count(),
                        'kehadiran_cm'=>$value['absensi']->where('status_absen','CM')->count(),
                        'kehadiran_cn'=>$value['absensi']->where('status_absen','CN')->count(),
                        'kehadiran_ct'=>$value['absensi']->where('status_absen','CT')->count(),
                        'kehadiran_ig'=>$value['absensi']->where('status_absen','IG')->count(),
                        'kehadiran_im'=>$value['absensi']->where('status_absen','IM')->count(),
                        'kehadiran_ka'=>$value['absensi']->where('status_absen','KA')->count(),
                        'kehadiran_km'=>$value['absensi']->where('status_absen','KM')->count(),
                        'kehadiran_kr'=>$value['absensi']->where('status_absen','KR')->count(),
                        'kehadiran_na'=>$value['absensi']->where('status_absen','NA')->count(),
                        'kehadiran_pp'=>$value['absensi']->where('status_absen','PP')->count(),
                        'kehadiran_i'=>$value['absensi']->where('status_absen','I')->count(),
                        'kehadiran_lp'=>$value['absensi']->where('status_absen','LP')->count(),
                        'kehadiran_l'=>$value['absensi']->where('status_absen','L')->count(),
                        'kehadiran_tl'=>$value['absensi']->where('status_absen','TL')->count(),
                        'kehadiran_iks'=>$value['absensi']->where('status_absen','IKS')->count(),
                        'kehadiran_s'=>$value['absensi']->where('status_absen','S')->count(),
                        'kehadiran_m_estimasi'=> $TL_estimasi+$M_estimasi,
                    ];
                    $count=RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->count();
                    if($count){
                        RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->update($row);
                    }
                    else{
                        RekapKehadiranKaryawan::create($row);
                    }
                }
            }else{
                $employees=EmployeeAtribut::where(function($query)use($tanggal_awal){
                    $query->where('status_aktif','AKTIF')
                    ->orWhere('tanggal_resign','>=',$tanggal_awal)
                    ->orWhere('tanggal_resign',null);
                })->with(['absensi' => function ($query) use ($tanggal_awal,$tanggal_akhir) {
                    $query->where('tanggal_berjalan', '>=', $tanggal_awal)
                    ->where('tanggal_berjalan','<=',$tanggal_akhir);
                }])->orderBy('enroll_id', 'asc')->get();
                $data_absensi=[];
                foreach ($employees as $key => $value) {
                    $enroll_id=$value->enroll_id;
                    $unik=sprintf("%04d", $enroll_id);
                    $kode_rekap_kehadiran=$bulan_sekarang.$unik;
                    $libur_sabtu_minggu=$value['absensi']->whereIn('status_absen',$IBY)->count();
                    $libur=$value['absensi']->where('status_absen','M')->where('mulai_jam_kerja',null)->count();
                    $lsm_security=$libur_sabtu_minggu+$libur;
                    $lsm_employe=$value['absensi']->whereIn('kode_hari', ['5','6'])->count();
                    $absen_M_security=$value['absensi']->where('status_absen','M')->where('mulai_jam_kerja','!=',null)->count();
                    $absen_M=$value['absensi']->where('status_absen','M')->count();

                    $absen_TL=$value['absensi']->where('status_absen','TL')->where('mulai_jam_kerja','!=',null)->count();
                    $absen_R=$value['absensi']->where('status_absen','R')->count();

                    $absen_ok=$value['absensi']->whereNotin('kode_hari', ['5','6'])->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();
                    $absen_IKS=$value['absensi']->where('status_absen','IKS')->whereNotIn('kode_hari',[5,6])->count();
                    $absen_ok_employee=$absen_ok+$absen_IKS;

                    $absen_ok_security=$value['absensi']->where('mulai_jam_kerja','!=',null)->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();

                    $absen_ok_security_total=$absen_ok_security+$absen_IKS;

                    $dt_employe=$value['absensi']->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','0')->where('status_absen',null)->count();
                    $pc_employe=$value['absensi']->where('jumlah_menit_absen_pc','>','0')->where('jumlah_menit_absen_dt','0')->where('status_absen',null)->count();
                    $dtpc_employe=$value['absensi']->where('enroll_id',$value->enroll_id)->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','>','0')->where('status_absen',null)->count();

                    $LBY_employe=$value['absensi']->where('status_absen','LN')->whereNotin('kode_hari', ['5','6'])->count();
                    $IBY_employe=$value['absensi']->whereIn('status_absen',$IBY)->count();
                    $ITB_employe=$value['absensi']->wherein('status_absen', $ITB)->count();

                    $total_kehadiran_net_security=$absen_ok_security+$dt_employe+$pc_employe+$dtpc_employe;
                    $aa_security=$total_kehadiran_net_security+$ITB_employe+$LBY_employe+$IBY_employe+$lsm_security+$absen_M_security+$absen_TL+$absen_R;

                    $total_kehadiran_net=$absen_ok+$dt_employe+$pc_employe+$dtpc_employe;
                    $aa=$total_kehadiran_net+$ITB_employe+$LBY_employe+$IBY_employe+$lsm_employe+$absen_M+$absen_TL+$absen_R;

                    $kehadiran_tk_security=$jumlah_hari_total-$aa_security;
                    $kehadiran_tk=$jumlah_hari_total-$aa;

                    $total_kehadiran_security=$IBY_employe+$ITB_employe+$lsm_security+$dtpc_employe+$absen_M_security+$absen_R+$absen_ok_security+$LBY_employe+$dt_employe+$pc_employe+$absen_TL;
                    $total_kehadiran=$IBY_employe+$ITB_employe+$lsm_employe+$dtpc_employe+$absen_M+$absen_R+$absen_ok+$LBY_employe+$dt_employe+$pc_employe+$absen_TL;
                    $M_estimasi=$value['absensi']->where('status_absen','M')->count();
                    $TL_estimasi=$value['absensi']->where('status_absen','TL')->where('mulai_jam_kerja','!=',null)->count();

                    if($security->where('enroll_id',$value->enroll_id)->count()){
                        $lsm=$lsm_security;
                        $m=$absen_M_security;
                        $ok=$absen_ok_security_total;
                        $tk=$kehadiran_tk_security;
                        $total_kh=$total_kehadiran_security;
                        $jumlah_hari_kerja=25;
                    }else{
                        $lsm=$lsm_employe;
                        $m=$absen_M;
                        $ok=$absen_ok_employee;
                        $tk=$kehadiran_tk;
                        $total_kh=$total_kehadiran;
                        $jumlah_hari_kerja=$jumlah_hari_total-$jumlah_hari_sabtu_minggu_total;
                    }
                    $row=[
                        'uuid'=>Str::uuid('uuid'),
                        'kode_rekap_kehadiran'=> $kode_rekap_kehadiran,
                        'periode_umk'=>null,
                        'periode_payroll'=>$priode,
                        'periode_tahun'=>$tahun,
                        'periode_bulan'=>$bulan,
                        'enroll_id'=>$value['enroll_id'],
                        'nik'=>$value['nik'],
                        'employee_name'=>$value['employee_name'],
                        'site_nirwana_id'=>$value['site_nirwana_id'],
                        'site_nirwana_name'=>$value['site_nirwana_name'],
                        'join_date'=>$value['join_date'],
                        'status_staff'=>$value['status_staff'],
                        'kehadiran_iby'=>$value['absensi']->whereIn('status_absen',$IBY)->count(),
                        'kehadiran_lby'=>$value['absensi']->where('status_absen','LN')->whereNotin('kode_hari', ['5','6'])->count(),
                        'kehadiran_lsm'=>$lsm,
                        'kehadiran_dt'=> $value['absensi']->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','0')->where('status_absen',null)->count(),
                        'kehadiran_pc'=> $value['absensi']->where('jumlah_menit_absen_pc','>','0')->where('jumlah_menit_absen_dt','0')->where('status_absen',null)->count(),
                        'kehadiran_dtpc'=>$value['absensi']->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','>','0')->where('status_absen',null)->count(),
                        'kehadiran_tk'=>$tk,
                        'total_kehadiran'=> $total_kh,
                        'jumlah_hari'=>$jumlah_hari_total,
                        'jumlah_hari_kerja'=>$jumlah_hari_kerja,
                        'kehadiran_dl'=>$value['absensi']->where('status_absen','DL')->count(),
                        'kehadiran_cb'=>$value['absensi']->where('status_absen','CB')->count(),
                        'kehadiran_cbd'=>$value['absensi']->where('status_absen','CBD')->count(),
                        'kehadiran_cg'=>$value['absensi']->where('status_absen','CG')->count(),
                        'kehadiran_ch'=>$value['absensi']->where('status_absen','CH')->count(),
                        'kehadiran_cm'=>$value['absensi']->where('status_absen','CM')->count(),
                        'kehadiran_cn'=>$value['absensi']->where('status_absen','CN')->count(),
                        'kehadiran_ct'=>$value['absensi']->where('status_absen','CT')->count(),
                        'kehadiran_ig'=>$value['absensi']->where('status_absen','IG')->count(),
                        'kehadiran_im'=>$value['absensi']->where('status_absen','IM')->count(),
                        'kehadiran_ka'=>$value['absensi']->where('status_absen','KA')->count(),
                        'kehadiran_km'=>$value['absensi']->where('status_absen','KM')->count(),
                        'kehadiran_kr'=>$value['absensi']->where('status_absen','KR')->count(),
                        'kehadiran_na'=>$value['absensi']->where('status_absen','NA')->count(),
                        'kehadiran_pp'=>$value['absensi']->where('status_absen','PP')->count(),
                        'kehadiran_lp'=>$value['absensi']->where('status_absen','LP')->count(),
                        'kehadiran_l'=>$value['absensi']->where('status_absen','L')->count(),
                        'kehadiran_iks'=>$value['absensi']->where('status_absen','IKS')->count(),
                        'kehadiran_itb'=>$value['absensi']->whereIn('status_absen',$ITB)->count(),
                        'tanggal_resign'=>$value['tanggal_resign'],
                        'status_aktif'=>$value['status_aktif'],
                        'kehadiran_m'=>$m+$absen_TL,
                        'kehadiran_r'=>$absen_R,
                        'kehadiran_ok'=>$ok,
                        'total_kehadiran_net'=>$ok+$dt_employe+$pc_employe+$dtpc_employe+$LBY_employe+$IBY_employe,
                        'kehadiran_i'=>$value['absensi']->where('status_absen','I')->count(),
                        'kehadiran_tl'=>$value['absensi']->where('status_absen','TL')->count(),
                        'department_id'=>$value['department_id'],
                        'department_name'=>$value['department_name'],
                        'sub_dept_id'=>$value['sub_dept_id'],
                        'sub_dept_name'=>$value['sub_dept_name'],
                        'kehadiran_s'=>$value['absensi']->where('status_absen','S')->count(),
                        'kehadiran_m_estimasi'=> $TL_estimasi+$M_estimasi,
                    ];
                    $count=RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->count();
                    if($count){
                        RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->update($row);
                    }
                    else{
                        RekapKehadiranKaryawan::create($row);
                    }
                }
            }

            // rekap perhitungan kehadiran karyawan
            $kehadiran=RekapKehadiranKaryawan::selectRaw('uuid,kode_rekap_kehadiran,periode_payroll,periode_tahun,periode_bulan,enroll_id,nik,employee_name,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,join_date,tanggal_resign,status_aktif,status_staff,kehadiran_iby,kehadiran_itb,kehadiran_lby,kehadiran_lsm,kehadiran_dt,kehadiran_pc,kehadiran_dtpc,kehadiran_m,kehadiran_r,kehadiran_tk,kehadiran_ok,total_kehadiran,total_kehadiran_net,jumlah_hari,jumlah_hari_kerja,kehadiran_dl,kehadiran_cb,kehadiran_cbd,kehadiran_cg,kehadiran_ch,kehadiran_cm,kehadiran_cn,kehadiran_ct,kehadiran_ig,kehadiran_im,kehadiran_ka,kehadiran_km,kehadiran_kr,kehadiran_na,kehadiran_pp,kehadiran_i,kehadiran_lp,kehadiran_l,kehadiran_tl,kehadiran_iks,kehadiran_s,kehadiran_m_estimasi,operator,created_at,updated_at,deleted_at')->whereRaw('periode_bulan = "'.$bulan.'" and periode_tahun = "'.$tahun.'"'.$inEnrollId.'')->groupby('enroll_id')->get();
            foreach ($kehadiran as $key => $value) {
                $periode = $value->periode_payroll;
                $enroll_id = $value->enroll_id;
                $kode = str_replace(array('-', ' '), '', $periode) . str_pad($enroll_id, 5, '0', STR_PAD_LEFT);
                $kode_rekap = date('Ymd', strtotime(substr($kode, 0, 8))) . date('Ymd', strtotime(substr($kode, 11, 8))) . substr($kode, 19);
                $kode_grade=EmployeeAtribut::select('kode_grade')->where('enroll_id',$value->enroll_id)->first()->kode_grade;
                $salary_bulanan=2942088;
                $tahun_berjalan = $value->periode_tahun;
                $check_salary_bulanan = GradingSalary::select('salary_bulanan')
                                ->where('kode_grade', $kode_grade)
                                ->whereRaw("SUBSTRING(periode_umk, 1, 4) = ?", [$tahun_berjalan])
                                ->first();
                if($check_salary_bulanan){
                    $salary_bulanan=GradingSalary::select('salary_bulanan')
                    ->where('kode_grade', $kode_grade)
                    ->whereRaw("SUBSTRING(periode_umk, 1, 4) = ?", [$tahun_berjalan])
                    ->first()
                    ->salary_bulanan;
                }
                $hari_potongan=max($value->jumlah_hari_kerja-$value->total_kehadiran_net, 0);
                $hari_potongan_security=max(25-$value->total_kehadiran_net, 0);
                $hp='';
                if($security->where('enroll_id',$value->enroll_id)->count()){
                    $hp=$hari_potongan_security;
                    $potongan_kehadiran_rupiah=($salary_bulanan/25)*$hp;
                    $jumlah_menit_kerja=420;
                }else{
                    $hp=$hari_potongan;
                    $potongan_kehadiran_rupiah=($salary_bulanan/$value->jumlah_hari_kerja)*$hp;
                    $jumlah_menit_kerja=480;
                }
                $perhitungan_gaji=[
                    'uuid'=>Str::uuid('uuid'),
                    'periode_umk'=>null,
                    'kode_rekap'=>$kode_rekap,
                    'periode_payroll'=>$value->periode_payroll,
                    'periode_tahun_bulan'=>$value->periode_tahun.'-'.$value->periode_bulan,
                    'enroll_id'=>$value->enroll_id,
                    'employee_name'=>$value->employee_name,
                    'kehadiran_iby'=>$value->kehadiran_iby,
                    'kehadiran_itb'=>$value->kehadiran_itb,
                    'kehadiran_lby'=>$value->kehadiran_lby,
                    'kehadiran_lsm'=>$value->kehadiran_lsm,
                    'kehadiran_dt'=>$value->kehadiran_dt,
                    'kehadiran_pc'=>$value->kehadiran_pc,
                    'kehadiran_dtpc'=>$value->kehadiran_dtpc,
                    'kehadiran_m'=>$value->kehadiran_m,
                    'kehadiran_m_estimasi'=> $value->kehadiran_m_estimasi,
                    'kehadiran_r'=>$value->kehadiran_r,
                    'kehadiran_tk'=>$value->kehadiran_tk,
                    'kehadiran_ok'=>$value->kehadiran_ok,
                    'total_kehadiran'=>$value->total_kehadiran,
                    'total_kehadiran_net'=>$value->total_kehadiran_net,
                    'jumlah_hari'=>$value->jumlah_hari,
                    'jumlah_hari_kerja'=>$value->jumlah_hari_kerja,
                    'gaji_pokok'=>$salary_bulanan,
                    'gaji_harian'=>$salary_bulanan/$value->jumlah_hari_kerja,
                    'gaji_menit'=>($salary_bulanan/$value->jumlah_hari_kerja)/$jumlah_menit_kerja,
                    'potongan_kehadiran_rupiah'=>$potongan_kehadiran_rupiah,
                ];
                $count=RekapPerhitunganKehadiranKaryawan::where( 'kode_rekap',$kode_rekap)->count();
                if($count){
                    RekapPerhitunganKehadiranKaryawan::where('kode_rekap',$kode_rekap)->update($perhitungan_gaji);
                }
                else{
                    RekapPerhitunganKehadiranKaryawan::create($perhitungan_gaji);
                }
            }

            // rekap lembur
            if($selectedEnrollId){
                $data_lemburan=[];
                $lemburan=MasterDataAbsenKehadiran::where('nomor_form_lembur','!=',null)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->whereIn('enroll_id',$selectedEnrollId)->with('data_lembur')->get();
                foreach($lemburan as $key=>$value){
                    if($value->kode_hari==5 || $value->kode_hari==6 || $value->status_absen=='LN'){
                        $kerjalibur='LIBUR';
                    }else{
                        $kerjalibur='KERJA';
                    }
                    $jumlah_jam_kerja=date_diff(date_create($value->mulai_jam_kerja),date_create($value->akhir_jam_kerja));
                    $jam_efektif_kerja=date_diff(date_create($value->absen_masuk_kerja),date_create($value->absen_pulang_kerja));
                    $spl_in=date('H:i:s', strtotime($value->mulai_jam_lembur));
                    $jam_in=$value->absen_masuk_kerja;
                    $spl_out=date('H:i:s', strtotime($value->akhir_jam_lembur));
                    $jam_out=$value->absen_pulang_kerja;
                    $jadwal_in=$value->mulai_jam_kerja;
                    $jadwal_out=$value->akhir_jam_kerja;
                    if($jadwal_in==null || $value->status_absen=='LN'){
                        $finish_in=max([$spl_in,$jam_in]);
                        $finish_out=min([$spl_out,$jam_out]);
                    }
                    elseif ( $spl_in<=$jadwal_in) {
                        $finish_in=max([$spl_in,$jam_in]);
                        $finish_out=min([$spl_out,$jam_out]);
                    }
                    else{
                        $finish_in=max([$jadwal_out,$spl_in,$jam_in]);
                        $finish_out=min([$spl_out,$jam_out]);
                    }
                    $jam1 = strtotime($finish_in);
                    $jam2 = strtotime($finish_out);

                    // Jika $jam2 lebih kecil dari $jam1, tambahkan 1 hari (86400 detik)
                    if ($jam2 < $jam1) {
                        $jam2 += 86400;
                    }
                    if($value->jumlah_menit_absen_pc!=0 || $value->absen_masuk_kerja==null || $value->absen_pulang_kerja==null){
                        $selisih_detik=0;
                    }else{
                        $selisih_detik = max($jam2 - $jam1, 0);
                    }

                    $selisih_jam = floor($selisih_detik / 3600);
                    $selisih_detik %= 3600;

                    $selisih_menit = floor($selisih_detik / 60);
                    $selisih_detik %= 60;

                    $final_total=sprintf("%02d:%02d:%02d", $selisih_jam, $selisih_menit, $selisih_detik);
                    if($final_total>='20:00:00'){
                        $final_total_jam_lembur ='00:00:00';
                    }else{
                        $final_total_jam_lembur = sprintf("%02d:%02d:%02d", $selisih_jam, $selisih_menit, $selisih_detik);
                    }
                    $data_lemburan[$key]=[
                        'tanggal_berjalan'=>$value->tanggal_berjalan,
                        'kode_hari'=>$value->kode_hari,
                        'nama_hari'=>$value->nama_hari,
                        'kerjalibur'=>$kerjalibur,
                        'holiday_name'=>$value->holiday_name,
                        'nomor_form_lembur'=>$value->nomor_form_lembur,
                        'enroll_id'=>$value->enroll_id,
                        'nik'=>$value->nik,
                        'employee_name'=>$value->employee_name,
                        'site_nirwana_id'=>$value->site_nirwana_id,
                        'site_nirwana_name'=>$value->site_nirwana_name,
                        'department_id'=>$value->department_id,
                        'department_name'=>$value->department_name,
                        'sub_dept_id'=>$value->sub_dept_id,
                        'sub_dept_name'=>$value->sub_dept_name,
                        'posisi_name'=>$value->posisi_name,
                        'mulai_jam_kerja'=>$value->mulai_jam_kerja,
                        'akhir_jam_kerja'=>$value->akhir_jam_kerja,
                        'jumlah_jam_kerja'=>sprintf('%02d:%02d:%02d', $jumlah_jam_kerja->h, $jumlah_jam_kerja->i, $jumlah_jam_kerja->s),
                        'absen_masuk_kerja'=>$value->absen_masuk_kerja,
                        'absen_pulang_kerja'=>$value->absen_pulang_kerja,
                        'jam_efektif_kerja'=>sprintf('%02d:%02d:%02d', $jam_efektif_kerja->h, $jam_efektif_kerja->i, $jam_efektif_kerja->s),
                        'mulai_jam_lembur'=>$value->mulai_jam_lembur,
                        'akhir_jam_lembur'=>$value->akhir_jam_lembur,
                        'final_mulai_jam_lembur'=>$finish_in,
                        'final_selesai_jam_lembur'=>$value->absen_pulang_kerja,
                        'final_total_jam_lembur'=>$final_total_jam_lembur,
                        'final_jam_istirahat_lembur'=>0,
                        'final_total_menit_lembur'=>($selisih_jam*60)+$selisih_menit,
                        'final_jam_lembur_roundown'=> $selisih_jam,
                        'final_menit_lembur_roundown'=>$selisih_menit,
                        'lembur_1'=>0,
                        'lembur_2'=>0,
                        'lembur_3'=>0,
                        'lembur_4'=>0,
                        'total_lembur_1234'=>0,
                        'salary'=>0,
                        'lembur1_rupiah'=>0,
                        'lembur2_rupiah'=> 0,
                        'lembur3_rupiah'=> 0,
                        'lembur4_rupiah'=> 0,
                        'total_lembur_rupiah'=> 0,
                        'operator'=>'system',
                        'jumlah_jam_istirahat_form'=>$value->data_lembur->jumlah_jam_istirahat??0,
                        'jumlah_jam_lembur_form'=>$value->data_lembur->jumlah_jam_lembur??0,
                        'status_absen'=>$value->status_absen
                    ];
                }
                foreach ($data_lemburan as $key2 => $value2) {
                    if ($value2['final_menit_lembur_roundown'] <= 15) {
                        $konveri_jam = 0;
                    } elseif ($value2['final_menit_lembur_roundown'] > 15 && $value2['final_menit_lembur_roundown'] <= 45) {
                        $konveri_jam = 0.5;
                    } else {
                        $konveri_jam = 1;
                    }
                    $total_jam_lembur=$value2['final_jam_lembur_roundown'] + $konveri_jam;
                    $total_jam_lembur_finis=$total_jam_lembur-$value2['jumlah_jam_istirahat_form'];
                    $total_jam_lembur_finis=min($value2['jumlah_jam_lembur_form'],$total_jam_lembur_finis);
                    if($value2['kode_hari']==5 || $value2['kode_hari']==6 || $value2['status_absen']=='LN' || ($value2['mulai_jam_kerja']==null && $value2['akhir_jam_kerja']==null)){
                        $kerjalibur='LIBUR';
                        $l1=0;
                        $le2=($total_jam_lembur_finis <= 8) ? $total_jam_lembur_finis : 8;
                        $l2=$le2<0?0:$le2;
                        if($total_jam_lembur_finis > 9){
                            $le3=1;
                            $le4=max($total_jam_lembur_finis -9, 0);
                        }
                        else if($total_jam_lembur_finis > 8 && $total_jam_lembur_finis <=9 ){
                            $le3=max($total_jam_lembur_finis -8, 0);
                            $le4=0;
                        }
                        else{
                            $le3=0;
                            $le4=0;
                        }
                        $l3=$le3<0?0:$le3;
                        $l4=$le4<0?0:$le4;
                    }
                    else{
                        $kerjalibur='KERJA';
                        $le1 = ($total_jam_lembur_finis <= 1) ? $total_jam_lembur_finis : 1;
                        $le2 = max($total_jam_lembur_finis - 1, 0);
                        $l3=0;
                        $l4=0;
                        $l1=$le1<0?0:$le1;
                        $l2=$le2<0?0:$le2;
                    }
                    
                    $kode_grade=EmployeeAtribut::select('kode_grade')->where('enroll_id',$value->enroll_id)->first()->kode_grade;
                    $salary_bulanan=2942088;
                    $tahun_berjalan = $value->periode_tahun;
                    $check_salary_bulanan = GradingSalary::select('salary_bulanan')
                                    ->where('kode_grade', $kode_grade)
                                    ->whereRaw("SUBSTRING(periode_umk, 1, 4) = ?", [$tahun_berjalan])
                                    ->first();
                    if($check_salary_bulanan){
                        $salary_bulanan=GradingSalary::select('salary_bulanan')
                        ->where('kode_grade', $kode_grade)
                        ->whereRaw("SUBSTRING(periode_umk, 1, 4) = ?", [$tahun_berjalan])
                        ->first()
                        ->salary_bulanan;
                    }
                    if($value2['kode_hari']==6 || $value2['status_absen']=='LN'){
                        $l1_rupiah=$l1*($salary_bulanan/173*1);
                        $l2_rupiah=$l2*($salary_bulanan/173*2);
                        $l3_rupiah=$l3*($salary_bulanan/173*2);
                        $l4_rupiah=$l4*($salary_bulanan/173*2);
                    }
                    else{
                        $l1_rupiah=$l1*($salary_bulanan/173*1);
                        $l2_rupiah=$l2*($salary_bulanan/173*1);
                        $l3_rupiah=$l3*($salary_bulanan/173*1);
                        $l4_rupiah=$l4*($salary_bulanan/173*1);
                    }
                    $record_lemburan=[
                        'uuid'=>Str::uuid('uuid'),
                        'periode_umk'=>null,
                        'tanggal_berjalan'=>$value2['tanggal_berjalan'],
                        'kode_hari'=>$value2['kode_hari'],
                        'nama_hari'=>$value2['nama_hari'],
                        'kerjalibur'=>$value2['kerjalibur'],
                        'holiday_name'=>$value2['holiday_name'],
                        'nomor_form_lembur'=>$value2['nomor_form_lembur'],
                        'enroll_id'=>$value2['enroll_id'],
                        'nik'=>$value2['nik'],
                        'employee_name'=>$value2['employee_name'],
                        'site_nirwana_id'=>$value2['site_nirwana_id'],
                        'site_nirwana_name'=>$value2['site_nirwana_name'],
                        'department_id'=>$value2['department_id'],
                        'department_name'=>$value2['department_name'],
                        'sub_dept_id'=>$value2['sub_dept_id'],
                        'sub_dept_name'=>$value2['sub_dept_name'],
                        'posisi_name'=>$value2['posisi_name'],
                        'mulai_jam_kerja'=>$value2['mulai_jam_kerja'],
                        'akhir_jam_kerja'=>$value2['akhir_jam_kerja'],
                        'jumlah_jam_kerja'=>$value2['jumlah_jam_kerja'],
                        'absen_masuk_kerja'=>$value2['absen_masuk_kerja'],
                        'absen_pulang_kerja'=>$value2['absen_pulang_kerja'],
                        'jam_efektif_kerja'=>$value2['jam_efektif_kerja'],
                        'mulai_jam_lembur'=>$value2['mulai_jam_lembur'],
                        'akhir_jam_lembur'=>$value2['akhir_jam_lembur'],
                        'final_mulai_jam_lembur'=>$value2['final_mulai_jam_lembur'],
                        'final_selesai_jam_lembur'=>$value2['final_selesai_jam_lembur'],
                        'final_total_jam_lembur'=>$value2['final_total_jam_lembur'],
                        'final_jam_istirahat_lembur'=>$value2['jumlah_jam_istirahat_form'],
                        'final_total_menit_lembur'=>$value2['final_total_menit_lembur'],
                        'final_jam_lembur_roundown'=> $value2['final_jam_lembur_roundown'],
                        'final_menit_lembur_roundown'=>$value2['final_menit_lembur_roundown'],
                        'lembur_1'=>$l1,
                        'lembur_2'=>$l2,
                        'lembur_3'=>$l3,
                        'lembur_4'=>$l4,
                        'total_lembur_1234'=>$l1+$l2+$l3+$l4,
                        'salary'=>$salary_bulanan,
                        'lembur1_rupiah'=>$l1_rupiah,
                        'lembur2_rupiah'=> $l2_rupiah,
                        'lembur3_rupiah'=> $l3_rupiah,
                        'lembur4_rupiah'=> $l4_rupiah,
                        'total_lembur_rupiah'=> $l1_rupiah+$l2_rupiah+$l3_rupiah+$l4_rupiah,
                        'operator'=>'system',
                    ];
                    $count=RekapPerhitunganLembur::where('tanggal_berjalan',$value2['tanggal_berjalan'])->where('enroll_id',$value2['enroll_id'])->count();
                    if($count){
                        RekapPerhitunganLembur::where('tanggal_berjalan',$value2['tanggal_berjalan'])->where('enroll_id',$value2['enroll_id'])->update($record_lemburan);
                    }
                    else{
                        RekapPerhitunganLembur::create($record_lemburan);
                    }
                }
            }else{
                $data_lemburan=[];
                $lemburan=MasterDataAbsenKehadiran::where('nomor_form_lembur','!=',null)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->with('data_lembur')->get();
                foreach($lemburan as $key=>$value){
                    if($value->kode_hari==5 || $value->kode_hari==6 || $value->status_absen=='LN' || ($value->mulai_jam_kerja==null && $value->akhir_jam_kerja==null)){
                        $kerjalibur='LIBUR';
                    }else{
                        $kerjalibur='KERJA';
                    }
                    $jumlah_jam_kerja=date_diff(date_create($value->mulai_jam_kerja),date_create($value->akhir_jam_kerja));
                    $jam_efektif_kerja=date_diff(date_create($value->absen_masuk_kerja),date_create($value->absen_pulang_kerja));
                    $spl_in=date('H:i:s', strtotime($value->mulai_jam_lembur));
                    $jam_in=$value->absen_masuk_kerja;
                    $spl_out=date('H:i:s', strtotime($value->akhir_jam_lembur));
                    $jam_out=$value->absen_pulang_kerja;
                    $jadwal_in=$value->mulai_jam_kerja;
                    $jadwal_out=$value->akhir_jam_kerja;
                    if($jadwal_in==null || $value->status_absen=='LN'){
                        $finish_in=max([$spl_in,$jam_in]);
                        $finish_out=min([$spl_out,$jam_out]);
                    }
                    elseif ( $spl_in<=$jadwal_in) {
                        $finish_in=max([$spl_in,$jam_in]);
                        $finish_out=min([$spl_out,$jam_out]);
                    }
                    else{
                        $finish_in=max([$jadwal_out,$spl_in,$jam_in]);
                        $finish_out=min([$spl_out,$jam_out]);
                    }
                    $jam1 = strtotime($finish_in);
                    $jam2 = strtotime($finish_out);

                    // Jika $jam2 lebih kecil dari $jam1, tambahkan 1 hari (86400 detik)
                    if ($jam2 < $jam1) {
                        $jam2 += 86400;
                    }
                    if($value->jumlah_menit_absen_pc!=0 || $value->absen_masuk_kerja==null || $value->absen_pulang_kerja==null){
                        $selisih_detik=0;
                    }else{
                        $selisih_detik = max($jam2 - $jam1, 0);
                    }

                    $selisih_jam = floor($selisih_detik / 3600);
                    $selisih_detik %= 3600;

                    $selisih_menit = floor($selisih_detik / 60);
                    $selisih_detik %= 60;

                    $final_total=sprintf("%02d:%02d:%02d", $selisih_jam, $selisih_menit, $selisih_detik);
                    if($final_total>='20:00:00'){
                        $final_total_jam_lembur ='00:00:00';
                    }else{
                        $final_total_jam_lembur = sprintf("%02d:%02d:%02d", $selisih_jam, $selisih_menit, $selisih_detik);
                    }
                    $data_lemburan[]=[
                        'tanggal_berjalan'=>$value->tanggal_berjalan,
                        'kode_hari'=>$value->kode_hari,
                        'nama_hari'=>$value->nama_hari,
                        'kerjalibur'=>$kerjalibur,
                        'holiday_name'=>$value->holiday_name,
                        'nomor_form_lembur'=>$value->nomor_form_lembur,
                        'enroll_id'=>$value->enroll_id,
                        'nik'=>$value->nik,
                        'employee_name'=>$value->employee_name,
                        'site_nirwana_id'=>$value->site_nirwana_id,
                        'site_nirwana_name'=>$value->site_nirwana_name,
                        'department_id'=>$value->department_id,
                        'department_name'=>$value->department_name,
                        'sub_dept_id'=>$value->sub_dept_id,
                        'sub_dept_name'=>$value->sub_dept_name,
                        'posisi_name'=>$value->posisi_name,
                        'mulai_jam_kerja'=>$value->mulai_jam_kerja,
                        'akhir_jam_kerja'=>$value->akhir_jam_kerja,
                        'jumlah_jam_kerja'=>sprintf('%02d:%02d:%02d', $jumlah_jam_kerja->h, $jumlah_jam_kerja->i, $jumlah_jam_kerja->s),
                        'absen_masuk_kerja'=>$value->absen_masuk_kerja,
                        'absen_pulang_kerja'=>$value->absen_pulang_kerja,
                        'jam_efektif_kerja'=>sprintf('%02d:%02d:%02d', $jam_efektif_kerja->h, $jam_efektif_kerja->i, $jam_efektif_kerja->s),
                        'mulai_jam_lembur'=>$value->mulai_jam_lembur,
                        'akhir_jam_lembur'=>$value->akhir_jam_lembur,
                        'final_mulai_jam_lembur'=>$finish_in,
                        'final_selesai_jam_lembur'=>$value->absen_pulang_kerja,
                        'final_total_jam_lembur'=>$final_total_jam_lembur,
                        'final_jam_istirahat_lembur'=>0,
                        'final_total_menit_lembur'=>($selisih_jam*60)+$selisih_menit,
                        'final_jam_lembur_roundown'=> $selisih_jam,
                        'final_menit_lembur_roundown'=>$selisih_menit,
                        'lembur_1'=>0,
                        'lembur_2'=>0,
                        'lembur_3'=>0,
                        'lembur_4'=>0,
                        'total_lembur_1234'=>0,
                        'salary'=>0,
                        'lembur1_rupiah'=>0,
                        'lembur2_rupiah'=> 0,
                        'lembur3_rupiah'=> 0,
                        'lembur4_rupiah'=> 0,
                        'total_lembur_rupiah'=> 0,
                        'operator'=>'system',
                        'jumlah_jam_istirahat_form'=>$value->data_lembur->jumlah_jam_istirahat??0,
                        'jumlah_jam_lembur_form'=>$value->data_lembur->jumlah_jam_lembur??0,
                        'status_absen'=>$value->status_absen
                    ];
                }
                // $array_lemburan=[];
                foreach ($data_lemburan as $key2 => $value2) {
                    if ($value2['final_menit_lembur_roundown'] <= 15) {
                        $konveri_jam = 0;
                    } elseif ($value2['final_menit_lembur_roundown'] > 15 && $value2['final_menit_lembur_roundown'] <= 45) {
                        $konveri_jam = 0.5;
                    } else {
                        $konveri_jam = 1;
                    }
                    $total_jam_lembur=$value2['final_jam_lembur_roundown'] + $konveri_jam;
                    $total_jam_lembur_finis=$total_jam_lembur-$value2['jumlah_jam_istirahat_form'];
                    $total_jam_lembur_finis=min($value2['jumlah_jam_lembur_form'],$total_jam_lembur_finis);
                    if($value2['kode_hari']==5 || $value2['kode_hari']==6 || $value2['status_absen']=='LN' || ($value2['mulai_jam_kerja']==null && $value2['akhir_jam_kerja']==null)){
                        $kerjalibur='LIBUR';
                        $l1=0;
                        $le2=($total_jam_lembur_finis <= 8) ? $total_jam_lembur_finis : 8;
                        $l2=$le2<0?0:$le2;
                        if($total_jam_lembur_finis > 9){
                            $le3=1;
                            $le4=max($total_jam_lembur_finis -9, 0);
                        }
                        else if($total_jam_lembur_finis > 8 && $total_jam_lembur_finis <=9 ){
                            $le3=max($total_jam_lembur_finis -8, 0);
                            $le4=0;
                        }
                        else{
                            $le3=0;
                            $le4=0;
                        }
                        $l3=$le3<0?0:$le3;
                        $l4=$le4<0?0:$le4;
                    }
                    else{
                        if($value['kode_hari']==5){
                            $kerjalibur='LIBUR';
                        }else{
                            $kerjalibur='KERJA';
                        }
                        $le1 = ($total_jam_lembur_finis <= 1) ? $total_jam_lembur_finis : 1;
                        $le2 = max($total_jam_lembur_finis - 1, 0);
                        $l3=0;
                        $l4=0;
                        $l1=$le1<0?0:$le1;
                        $l2=$le2<0?0:$le2;
                    }
                    
                    $kode_grade=EmployeeAtribut::select('kode_grade')->where('enroll_id',$value->enroll_id)->first()->kode_grade;
                    $salary_bulanan=2942088;
                    $tahun_berjalan = $value->periode_tahun;
                    $check_salary_bulanan = GradingSalary::select('salary_bulanan')
                                    ->where('kode_grade', $kode_grade)
                                    ->whereRaw("SUBSTRING(periode_umk, 1, 4) = ?", [$tahun_berjalan])
                                    ->first();
                    if($check_salary_bulanan){
                        $salary_bulanan=GradingSalary::select('salary_bulanan')
                        ->where('kode_grade', $kode_grade)
                        ->whereRaw("SUBSTRING(periode_umk, 1, 4) = ?", [$tahun_berjalan])
                        ->first()
                        ->salary_bulanan;
                    }
                    if($value2['kode_hari']==6 || $value2['status_absen']=='LN'){
                        $l1_rupiah=$l1*($salary_bulanan/173*1);
                        $l2_rupiah=$l2*($salary_bulanan/173*2);
                        $l3_rupiah=$l3*($salary_bulanan/173*2);
                        $l4_rupiah=$l4*($salary_bulanan/173*2);
                    }
                    else{
                        $l1_rupiah=$l1*($salary_bulanan/173*1);
                        $l2_rupiah=$l2*($salary_bulanan/173*1);
                        $l3_rupiah=$l3*($salary_bulanan/173*1);
                        $l4_rupiah=$l4*($salary_bulanan/173*1);
                    }
                    $record_lemburan=[
                        'uuid'=>Str::uuid('uuid'),
                        'periode_umk'=>null,
                        'tanggal_berjalan'=>$value2['tanggal_berjalan'],
                        'kode_hari'=>$value2['kode_hari'],
                        'nama_hari'=>$value2['nama_hari'],
                        'kerjalibur'=>$value2['kerjalibur'],
                        'holiday_name'=>$value2['holiday_name'],
                        'nomor_form_lembur'=>$value2['nomor_form_lembur'],
                        'enroll_id'=>$value2['enroll_id'],
                        'nik'=>$value2['nik'],
                        'employee_name'=>$value2['employee_name'],
                        'site_nirwana_id'=>$value2['site_nirwana_id'],
                        'site_nirwana_name'=>$value2['site_nirwana_name'],
                        'department_id'=>$value2['department_id'],
                        'department_name'=>$value2['department_name'],
                        'sub_dept_id'=>$value2['sub_dept_id'],
                        'sub_dept_name'=>$value2['sub_dept_name'],
                        'posisi_name'=>$value2['posisi_name'],
                        'mulai_jam_kerja'=>$value2['mulai_jam_kerja'],
                        'akhir_jam_kerja'=>$value2['akhir_jam_kerja'],
                        'jumlah_jam_kerja'=>$value2['jumlah_jam_kerja'],
                        'absen_masuk_kerja'=>$value2['absen_masuk_kerja'],
                        'absen_pulang_kerja'=>$value2['absen_pulang_kerja'],
                        'jam_efektif_kerja'=>$value2['jam_efektif_kerja'],
                        'mulai_jam_lembur'=>$value2['mulai_jam_lembur'],
                        'akhir_jam_lembur'=>$value2['akhir_jam_lembur'],
                        'final_mulai_jam_lembur'=>$value2['final_mulai_jam_lembur'],
                        'final_selesai_jam_lembur'=>$value2['final_selesai_jam_lembur'],
                        'final_total_jam_lembur'=>$value2['final_total_jam_lembur'],
                        'final_jam_istirahat_lembur'=>$value2['jumlah_jam_istirahat_form'],
                        'final_total_menit_lembur'=>$value2['final_total_menit_lembur'],
                        'final_jam_lembur_roundown'=> $value2['final_jam_lembur_roundown'],
                        'final_menit_lembur_roundown'=>$value2['final_menit_lembur_roundown'],
                        'lembur_1'=>$l1,
                        'lembur_2'=>$l2,
                        'lembur_3'=>$l3,
                        'lembur_4'=>$l4,
                        'total_lembur_1234'=>$l1+$l2+$l3+$l4,
                        'salary'=>$salary_bulanan,
                        'lembur1_rupiah'=>$l1_rupiah,
                        'lembur2_rupiah'=> $l2_rupiah,
                        'lembur3_rupiah'=> $l3_rupiah,
                        'lembur4_rupiah'=> $l4_rupiah,
                        'total_lembur_rupiah'=> $l1_rupiah+$l2_rupiah+$l3_rupiah+$l4_rupiah,
                        'operator'=>'system'
                    ];
                    // array_push($array_lemburan, $record_lemburan);
                    $count=RekapPerhitunganLembur::where('tanggal_berjalan',$value2['tanggal_berjalan'])->where('enroll_id',$value2['enroll_id'])->count();
                    if($count){
                        RekapPerhitunganLembur::where('tanggal_berjalan',$value2['tanggal_berjalan'])->where('enroll_id',$value2['enroll_id'])
                        ->update($record_lemburan);
                    }
                    else{
                        RekapPerhitunganLembur::create($record_lemburan);
                    }
                }
            }
            // $rekapLemburArrChunk = array_chunk($array_lemburan, 1000);
            // foreach ($rekapLemburArrChunk as $data) {
            //     RekapPerhitunganLembur::upsert(
            //         $data,
            //         ["tanggal_berjalan", "enroll_id","uuid"]
            //     );
            // }
            // return $rekapLemburArrChunk;

            //rekap iks
            $iks=MasterDataAbsenKehadiran::selectRaw('uuid,tanggal_berjalan,tanggal_absen,shift_work_id,kode_hari,nama_hari,time_table_name,mulai_jam_kerja,akhir_jam_kerja,jam_kerja,jumlah_jam_kerja,jumlah_menit_kerja,mulai_jam_istirahat,akhir_jam_istirahat,jumlah_jam_istirahat,jumlah_menit_istirahat,absen_masuk_kerja,absen_pulang_kerja,enroll_id,nik,employee_id,employee_name,join_date,work_status,employee_status,posisi_name,kode_grade,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,status_absen,nama_absen_ijin,kode_ijin_payroll,absen_ijin_id,nomor_absen_ijin,tanggal_mulai_ijin,tanggal_akhir_ijin,permits_dari_pukul,permits_sampai_pukul,total_menit_permits,jumlah_menit_absen_dt,jumlah_menit_absen_pc,jumlah_menit_absen_dtpc,jumlah_absen_menit_kerja,absen_s_sakit,absen_iby_ijin_dibayar,absen_itb_ijin_tidak_dibayar,absen_m_mangkir,absen_pc_pulang_cepat,absen_dt_datang_terlambat,absen_dtpc_datang_terlambat_pulang_cepat,absen_lby_libur_dibayar,absen_lsm_libur_sabtu_minggu,absen_ltb_libur_tidak_dibayar,absen_r_resign,absen_dl_dinas_luar,absen_ok_hadir,absen_tk_tidak_kerja,absen_cuti_umum,absen_cuti_khusus,absen_hari_resmi,absen_alasan,holiday_id,holiday_name,kelebihan_jam_kerja_l1,kelebihan_jam_kerja_l2,kelebihan_jam_kerja_l3,kelebihan_jam_kerja_l4,jumlah_selisih_menit_kerja,operator,catatan_hrd,nomor_form_perubahan_absen,nomor_form_lembur,jumlah_jam_lembur,jumlah_jam_istirahat_lembur,mulai_jam_lembur,akhir_jam_lembur,status_lembur,jumlah_jam_lembur_approved,updated_absen_cekinout,updated_absen_ijin,updated_absen_permits,updated_absen_dtpc,created_at,updated_at,deleted_at')->whereRaw('tanggal_berjalan >= "'.$tanggal_awal.'" and tanggal_berjalan <= "'.$tanggal_akhir.'" and total_menit_permits>0'.$inEnrollId.'')->get();
            foreach ($iks as $key => $value){
                if($value->kode_hari = 4){
                    $jam_mulai_istirahat='11:30';
                    $jam_selesai_istirahat='12:30';
                }
                else{
                    if($value->mulai_jam_kerja = '06:00' AND $value->akhir_jam_kerja = '15:00'){
                        $jam_mulai_istirahat='10:00';
                        $jam_selesai_istirahat='11:00';
                    }
                    elseif($value->mulai_jam_kerja = '16:00' AND $value->akhir_jam_kerja = '23:00'){
                        $jam_mulai_istirahat='18:00';
                        $jam_selesai_istirahat='19:00';
                    }
                    else{
                        $jam_mulai_istirahat='12:00';
                        $jam_selesai_istirahat='13:00';
                    }
                }
                $minutes = $value->total_menit_permits;
                $seconds = $minutes * 60;
                $time = gmdate("H:i:s", $seconds);
                $salary=RekapPerhitunganKehadiranKaryawan::where('periode_payroll',$priode)->where('enroll_id',$value->enroll_id)->first();
                $salary->gaji_pokok;

                $data_iks=[
                    'uuid'=>Str::uuid('uuid'),
                    'periode_umk'=>$periode_umk,
                    'nomor_form_perizinan'=>$value->nomor_absen_ijin,
                    'tanggal_berjalan'=>$value->tanggal_berjalan,
                    'enroll_id'=>$value->enroll_id,
                    'employee_name'=>$value->employee_name,
                    'sub_dept_name'=>$value->sub_dept_name,
                    'time_mulai_ijin'=>$value->permits_dari_pukul,
                    'time_akhir_ijin'=>$value->permits_sampai_pukul,
                    'jam_mulai_istirahat'=>$jam_mulai_istirahat,
                    'jam_selesai_istirahat'=> $jam_selesai_istirahat,
                    'lama_istirahat_menit'=>60,
                    'lama_ijin_menit'=>$value->total_menit_permits,
                    'lama_ijin_jam'=>$time,
                    'absen_alasan'=>$value->absen_alasan,
                    'gaji_pokok'=> $salary->gaji_pokok,
                    'gaji_harian'=> $salary->gaji_harian,
                    'gaji_menit'=> $salary->gaji_menit,
                    'potongan_iks_rupiah'=>$salary->gaji_menit*$value->total_menit_permits,
                ];
                $count=RekapPerhitunganIKS::where('tanggal_berjalan',$value->tanggal_berjalan)->where('periode_umk',$periode_umk)->where('enroll_id',$value->enroll_id)->count();
                if($count){
                    RekapPerhitunganIKS::where('tanggal_berjalan',$value->tanggal_berjalan)->where('periode_umk',$periode_umk)->where('enroll_id',$value->enroll_id)->update($data_iks);
                }
                else{
                    RekapPerhitunganIKS::create($data_iks);
                }
            }

            //rekap dtpc
            RekapPerhitunganDTPC::whereRaw('tanggal_berjalan >= "'.$tanggal_awal.'" and tanggal_berjalan <= "'.$tanggal_akhir.'" and jumlah_menit_absen_dtpc != 0'.$inEnrollId.'')->delete();
            $data_dtpcx=MasterDataAbsenKehadiran::selectRaw('uuid,tanggal_berjalan,tanggal_absen,shift_work_id,kode_hari,nama_hari,time_table_name,mulai_jam_kerja,akhir_jam_kerja,jam_kerja,jumlah_jam_kerja,jumlah_menit_kerja,mulai_jam_istirahat,akhir_jam_istirahat,jumlah_jam_istirahat,jumlah_menit_istirahat,absen_masuk_kerja,absen_pulang_kerja,enroll_id,nik,employee_id,employee_name,join_date,work_status,employee_status,posisi_name,kode_grade,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,status_absen,nama_absen_ijin,kode_ijin_payroll,absen_ijin_id,nomor_absen_ijin,tanggal_mulai_ijin,tanggal_akhir_ijin,permits_dari_pukul,permits_sampai_pukul,total_menit_permits,jumlah_menit_absen_dt,jumlah_menit_absen_pc,jumlah_menit_absen_dtpc,jumlah_absen_menit_kerja,absen_s_sakit,absen_iby_ijin_dibayar,absen_itb_ijin_tidak_dibayar,absen_m_mangkir,absen_pc_pulang_cepat,absen_dt_datang_terlambat,absen_dtpc_datang_terlambat_pulang_cepat,absen_lby_libur_dibayar,absen_lsm_libur_sabtu_minggu,absen_ltb_libur_tidak_dibayar,absen_r_resign,absen_dl_dinas_luar,absen_ok_hadir,absen_tk_tidak_kerja,absen_cuti_umum,absen_cuti_khusus,absen_hari_resmi,absen_alasan,holiday_id,holiday_name,kelebihan_jam_kerja_l1,kelebihan_jam_kerja_l2,kelebihan_jam_kerja_l3,kelebihan_jam_kerja_l4,jumlah_selisih_menit_kerja,operator,catatan_hrd,nomor_form_perubahan_absen,nomor_form_lembur,jumlah_jam_lembur,jumlah_jam_istirahat_lembur,mulai_jam_lembur,akhir_jam_lembur,status_lembur,jumlah_jam_lembur_approved,updated_absen_cekinout,updated_absen_ijin,updated_absen_permits,updated_absen_dtpc,created_at,updated_at,deleted_at')->whereRaw('tanggal_berjalan >= "'.$tanggal_awal.'" and tanggal_berjalan <= "'.$tanggal_akhir.'" and jumlah_menit_absen_dtpc != 0'.$inEnrollId.'')->get();
            foreach ($data_dtpcx as $key => $value) {
                $salary=RekapPerhitunganKehadiranKaryawan::where('periode_payroll',$priode)->where('enroll_id',$value->enroll_id)->first();
                $data_dtpcw=[
                    'uuid'=>Str::uuid('uuid'),
                    'periode_umk'=>$periode_umk,
                    'tanggal_berjalan'=>$value->tanggal_berjalan,
                    'employee_id'=>$value->employee_id,
                    'enroll_id'=>$value->enroll_id,
                    'employee_name'=>$value->employee_name,
                    'mulai_jam_kerja'=>$value->mulai_jam_kerja,
                    'akhir_jam_kerja'=>$value->akhir_jam_kerja,
                    'absen_masuk_kerja'=>$value->absen_masuk_kerja,
                    'absen_pulang_kerja'=>$value->absen_pulang_kerja,
                    'status_absen'=>$value->status_absen,
                    'gaji_pokok'=> $salary->gaji_pokok,
                    'gaji_menit'=> $salary->gaji_menit,
                    'jumlah_menit_absen_dt'=>$value->jumlah_menit_absen_dt,
                    'jumlah_menit_absen_pc'=>$value->jumlah_menit_absen_pc,
                    'jumlah_menit_absen_dtpc'=>$value->jumlah_menit_absen_dtpc,
                    'potongan_dt_rupiah'=>$salary->gaji_menit * $value->jumlah_menit_absen_dt,
                    'potongan_pc_rupiah'=>$salary->gaji_menit * $value->jumlah_menit_absen_pc,
                    'potongan_dtpc_rupiah'=>$salary->gaji_menit * $value->jumlah_menit_absen_dtpc,
                    'jumlah_absen_menit_kerja'=>$value->jumlah_absen_menit_kerja,
                ];
                $count=RekapPerhitunganDTPC::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->count();
                if($count){
                    RekapPerhitunganDTPC::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->update($data_dtpcw);
                }
                else{
                    RekapPerhitunganDTPC::create($data_dtpcw);
                }
            }

            //update bpjs
            $queryEmpAtr = EmployeeAtribut::selectRaw('uuid() uuid,
                concat(SUBSTR(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ), 1, 4),
                SUBSTR(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ), 6, 2), lpad(enroll_id, 5, 0)) kode_bpjs,
                substr("' . $explodePeriodePayroll[1] . '", 1, 4) periode_bpjs,
                CONCAT(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 2 MONTH )), INTERVAL 26 DAY ), " s/d ",
                DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ))  periode_kehadiran,
                enroll_id, nik, employee_name, site_nirwana_id, department_id, sub_dept_id,
                status_aktif_bpjs_tk, tanggal_bpjs_ketenagakerjaan, nomor_bpjs_ketenagakerjaan,
                status_aktif_bpjs_ks, tanggal_bpjs_kesehatan, nomor_bpjs_kesehatan, join_date
            ')
            ->where(function ($query) use ($selectedEnrollId, $explodePeriodePayroll) {
                $query->whereNotNull('enroll_id');
                // Tambahkan filter hanya jika $selectedEnrollId tidak kosong
                if (!empty($selectedEnrollId) && is_array($selectedEnrollId)) {
                    $query->whereIn('enroll_id', $selectedEnrollId);
                }
            })
            ->where(function ($query) use ($explodePeriodePayroll) {
                $query->whereNull('tanggal_resign')
                    ->orWhere('tanggal_resign', '=', '0000-00-00')
                    ->orWhere('tanggal_resign', '>=', \DB::raw('DATE_ADD(LAST_DAY(DATE_SUB("' . $explodePeriodePayroll[1] . '", INTERVAL 2 MONTH)), INTERVAL 26 DAY)'));
            })
            ->where('join_date', '<=', $explodePeriodePayroll[1])
            ->groupBy('enroll_id', 'employee_name')
            ->get();
            // return $queryEmpAtr;
            foreach ($queryEmpAtr as $key => $value) {

                $tanggal_masuk = $value['join_date'];

                // hitung selisih tahun antara tanggal masuk dan sekarang
                 $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_awal))->y;

                // tentukan besaran tunjangan berdasarkan masa kerja
                if ($selisih_tahun < 1) {
                    $tunjangan = 0;
                } elseif ($selisih_tahun < 3) {
                    $tunjangan = 2500;
                } elseif ($selisih_tahun < 6) {
                    $tunjangan = 5000;
                }elseif ($selisih_tahun < 9) {
                    $tunjangan = 7500;
                }elseif ($selisih_tahun < 12) {
                    $tunjangan = 10000;
                }else{
                    $tunjangan = 12500;
                }

                $countEmp = EmployeeBpjs::whereRaw('kode_bpjs = ' . $sqlKodePeriodeBPJS . ' AND enroll_id = "' . $value['enroll_id'] . '"')->count();

                if ($countEmp) {
                    $queryEmpBpjs = EmployeeBpjs::whereRaw('kode_bpjs = ' . $sqlKodePeriodeBPJS . ' AND enroll_id = "' . $value['enroll_id'] . '"')
                    ->update([
                        'status_aktif_bpjs_tk' => $value['status_aktif_bpjs_tk'],
                        'tanggal_bpjs_ketenagakerjaan' => $value['tanggal_bpjs_ketenagakerjaan'],
                        'nomor_bpjs_ketenagakerjaan' => $value['nomor_bpjs_ketenagakerjaan'],
                        'status_aktif_bpjs_ks' => $value['status_aktif_bpjs_ks'],
                        'tanggal_bpjs_kesehatan' => $value['tanggal_bpjs_kesehatan'],
                        'nomor_bpjs_kesehatan' => $value['nomor_bpjs_kesehatan'],
                        'kode_periode_bpjs' => null,
                        'kode_dasar_pot_bpjs' => null,
                        'dasar_pot_bpjs_rupiah' => 0,
                        'bpjs_tk_jkm_bruto_rupiah' => 0,
                        'bpjs_tk_jkk_bruto_rupiah' => 0,
                        'bpjs_ks_jkn_bruto_rupiah' => 0,
                        'bpjs_tk_jkm_neto_rupiah' => 0,
                        'bpjs_tk_jkk_neto_rupiah' => 0,
                        'bpjs_tk_jht_neto_rupiah' => 0,
                        'bpjs_tk_jpn_neto_rupiah' => 0,
                        'bpjs_ks_jkn_neto_rupiah' => 0,
                        'bpjs_tk_jkm_persen' => 0,
                        'bpjs_tk_jkk_persen' => 0,
                        'bpjs_tk_jht_persen' => 0,
                        'bpjs_tk_jpn_persen' => 0,
                        'bpjs_ks_jkn_persen' => 0,
                        'bpjs_tk_jkm_bruto_persen' => 0,
                        'bpjs_tk_jkk_bruto_persen' => 0,
                        'bpjs_tk_jht_bruto_persen' => 0,
                        'bpjs_tk_jpn_bruto_persen' => 0,
                        'bpjs_ks_jkn_bruto_persen' => 0,
                        'bpjs_tk_jkm_neto_persen' => 0,
                        'bpjs_tk_jkk_neto_persen' => 0,
                        'bpjs_tk_jht_neto_persen' => 0,
                        'bpjs_tk_jpn_neto_persen' => 0,
                        'bpjs_ks_jkn_neto_persen' => 0,
                        'tmk'=>$tunjangan,
                    ]);
                } else {
                    EmployeeBpjs::create([
                        'uuid' => Str::uuid(),
                        'kode_bpjs' => $value['kode_bpjs'],
                        'periode_bpjs' => $value['periode_bpjs'],
                        'periode_kehadiran' => $value['periode_kehadiran'],
                        'enroll_id' => $value['enroll_id'],
                        'nik' => $value['nik'],
                        'employee_name' => $value['employee_name'],
                        'status_aktif_bpjs_tk' => $value['status_aktif_bpjs_tk'],
                        'tanggal_bpjs_ketenagakerjaan' => $value['tanggal_bpjs_ketenagakerjaan'],
                        'nomor_bpjs_ketenagakerjaan' => $value['nomor_bpjs_ketenagakerjaan'],
                        'status_aktif_bpjs_ks' => $value['status_aktif_bpjs_ks'],
                        'tanggal_bpjs_kesehatan' => $value['tanggal_bpjs_kesehatan'],
                        'nomor_bpjs_kesehatan' => $value['nomor_bpjs_kesehatan'],
                        'kode_periode_bpjs' => null,
                        'kode_dasar_pot_bpjs' => null,
                        'dasar_pot_bpjs_rupiah' => 0,
                        'bpjs_tk_jkm_bruto_rupiah' => 0,
                        'bpjs_tk_jkk_bruto_rupiah' => 0,
                        'bpjs_ks_jkn_bruto_rupiah' => 0,
                        'bpjs_tk_jkm_neto_rupiah' => 0,
                        'bpjs_tk_jkk_neto_rupiah' => 0,
                        'bpjs_tk_jht_neto_rupiah' => 0,
                        'bpjs_tk_jpn_neto_rupiah' => 0,
                        'bpjs_ks_jkn_neto_rupiah' => 0,
                        'bpjs_tk_jkm_persen' => 0,
                        'bpjs_tk_jkk_persen' => 0,
                        'bpjs_tk_jht_persen' => 0,
                        'bpjs_tk_jpn_persen' => 0,
                        'bpjs_ks_jkn_persen' => 0,
                        'bpjs_tk_jkm_bruto_persen' => 0,
                        'bpjs_tk_jkk_bruto_persen' => 0,
                        'bpjs_tk_jht_bruto_persen' => 0,
                        'bpjs_tk_jpn_bruto_persen' => 0,
                        'bpjs_ks_jkn_bruto_persen' => 0,
                        'bpjs_tk_jkm_neto_persen' => 0,
                        'bpjs_tk_jkk_neto_persen' => 0,
                        'bpjs_tk_jht_neto_persen' => 0,
                        'bpjs_tk_jpn_neto_persen' => 0,
                        'bpjs_ks_jkn_neto_persen' => 0,
                        'tmk'=>$tunjangan,
                    ]);
                }
            }
            $query =  BpjsSetting::whereRaw(' substr(kode_periode_bpjs, 1, 4) = substr("' . $kode_bpjs->kode_periode_bpjs . '", 1, 4)')
                    ->orderBy('kode_periode_bpjs','desc')
                    ->limit(1)
                    ->get();

            $kode_periode_bpjs = $query[0]->kode_periode_bpjs;
            $kode_dasar_pot_bpjs = $query[0]->kode_dasar_pot_bpjs;
            $dasar_pot_bpjs_rupiah_gapok = $query[0]->dasar_pot_bpjs_rupiah;
            $bpjs_tk_jkm_persen = $query[0]->bpjs_tk_jkm_persen;
            $bpjs_tk_jkm_perusahaan_persen = $query[0]->bpjs_tk_jkm_perusahaan_persen;
            $bpjs_tk_jkm_karyawan_persen = $query[0]->bpjs_tk_jkm_karyawan_persen;
            $bpjs_tk_jkk_persen = $query[0]->bpjs_tk_jkk_persen;
            $bpjs_tk_jkk_perusahaan_persen = $query[0]->bpjs_tk_jkk_perusahaan_persen;
            $bpjs_tk_jkk_karyawan_persen = $query[0]->bpjs_tk_jkk_karyawan_persen;
            $bpjs_tk_jht_persen = $query[0]->bpjs_tk_jht_persen;
            $bpjs_tk_jht_perusahaan_persen = $query[0]->bpjs_tk_jht_perusahaan_persen;
            $bpjs_tk_jht_karyawan_persen = $query[0]->bpjs_tk_jht_karyawan_persen;
            $bpjs_tk_jpn_persen = $query[0]->bpjs_tk_jpn_persen;
            $bpjs_tk_jpn_perusahaan_persen = $query[0]->bpjs_tk_jpn_perusahaan_persen;
            $bpjs_tk_jpn_karyawan_persen = $query[0]->bpjs_tk_jpn_karyawan_persen;
            $bpjs_ks_jkn_persen = $query[0]->bpjs_ks_jkn_persen;
            $bpjs_ks_jkn_perusahaan_persen = $query[0]->bpjs_ks_jkn_perusahaan_persen;
            $bpjs_ks_jkn_karyawan_persen = $query[0]->bpjs_ks_jkn_karyawan_persen;

            $EmpBpjs = EmployeeBpjs::where('periode_kehadiran',$priode)->get();

            foreach ($EmpBpjs as $key3 => $value3) {
                $dasar_pot_bpjs_rupiah=$dasar_pot_bpjs_rupiah_gapok+$value3->tmk;
                // dd($dasar_pot_bpjs_rupiah);
                $queryEmpBpjs = DB::update('update employee_bpjs set
                    kode_periode_bpjs = "' . $kode_periode_bpjs . '",
                    kode_dasar_pot_bpjs = "' . $kode_dasar_pot_bpjs . '",
                    dasar_pot_bpjs_rupiah = "' . $dasar_pot_bpjs_rupiah . '",
                    bpjs_tk_jkm_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkm_perusahaan_persen . '/100)), 0),
                    bpjs_tk_jkk_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkk_perusahaan_persen . '/100)), 0),
                    bpjs_tk_jht_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jht_perusahaan_persen . '/100)), 0),
                    bpjs_tk_jpn_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jpn_perusahaan_persen . '/100)), 0),
                    bpjs_ks_jkn_bruto_rupiah = IF(status_aktif_bpjs_ks = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_ks_jkn_perusahaan_persen . '/100)), 0),
                    bpjs_tk_jkm_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkm_karyawan_persen . '/100)), 0),
                    bpjs_tk_jkk_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkk_karyawan_persen . '/100)), 0),
                    bpjs_tk_jht_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jht_karyawan_persen . '/100)), 0),
                    bpjs_tk_jpn_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jpn_karyawan_persen . '/100)), 0),
                    bpjs_ks_jkn_neto_rupiah = IF(status_aktif_bpjs_ks = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_ks_jkn_karyawan_persen . '/100)), 0),
                    bpjs_tk_jkm_persen = "' . $bpjs_tk_jkm_persen . '",
                    bpjs_tk_jkk_persen = "' . $bpjs_tk_jkk_persen . '",
                    bpjs_tk_jht_persen = "' . $bpjs_tk_jht_persen . '",
                    bpjs_tk_jpn_persen = "' . $bpjs_tk_jpn_persen . '",
                    bpjs_ks_jkn_persen = "' . $bpjs_ks_jkn_persen . '",
                    bpjs_tk_jkm_bruto_persen = "' . $bpjs_tk_jkm_perusahaan_persen . '",
                    bpjs_tk_jkk_bruto_persen = "' . $bpjs_tk_jkk_perusahaan_persen . '",
                    bpjs_tk_jht_bruto_persen = "' . $bpjs_tk_jht_perusahaan_persen . '",
                    bpjs_tk_jpn_bruto_persen = "' . $bpjs_tk_jpn_perusahaan_persen . '",
                    bpjs_ks_jkn_bruto_persen = "' . $bpjs_ks_jkn_perusahaan_persen . '",
                    bpjs_tk_jkm_neto_persen = "' . $bpjs_tk_jkm_karyawan_persen . '",
                    bpjs_tk_jkk_neto_persen = "' . $bpjs_tk_jkk_karyawan_persen . '",
                    bpjs_tk_jht_neto_persen = "' . $bpjs_tk_jht_karyawan_persen . '",
                    bpjs_tk_jpn_neto_persen = "' . $bpjs_tk_jpn_karyawan_persen . '",
                    bpjs_ks_jkn_neto_persen = "' . $bpjs_ks_jkn_karyawan_persen . '",
                    operator = "' . $email . '"
                where enroll_id = "'. $value3->enroll_id .'"');
            }

            //rekap payroll
            if($selectedEnrollId){
                $all_karyawan=EmployeeAtribut::whereIn('enroll_id',$selectedEnrollId)->where(function ($query)use($tanggal_awal,$tanggal_akhir){
                    $query->where(function ($querys)use($tanggal_akhir){
                        $querys->where('status_aktif','AKTIF')
                        ->where('join_date','<=',$tanggal_akhir);
                    })->orWhere('tanggal_resign','>=',$tanggal_awal);
                })
                ->with(['grading_salary' => function ($query) use ($tahun) {
                    $query->whereRaw("SUBSTRING(periode_umk, 1, 4) = ?", [$tahun]);
                }])->with(['rekap_kehadiran' => function ($query) use ($priode) {
                    $query->where('periode_payroll', $priode);
                }])->with(['rekap_lembur' => function ($query) use ($tanggal_awal,$tanggal_akhir) {
                    $query->where('tanggal_berjalan','>=', $tanggal_awal)
                    ->where('tanggal_berjalan','<=',$tanggal_akhir);
                }])->with(['rekap_iks' => function ($query) use ($tanggal_awal,$tanggal_akhir) {
                    $query->where('tanggal_berjalan','>=', $tanggal_awal)
                    ->where('tanggal_berjalan','<=',$tanggal_akhir);
                }])->with(['rekap_dtpc' => function ($query) use ($tanggal_awal,$tanggal_akhir) {
                    $query->where('tanggal_berjalan','>=', $tanggal_awal)
                    ->where('tanggal_berjalan','<=',$tanggal_akhir);
                }])->with(['bpjs' => function ($query) use ($priode) {
                    $query->where('periode_kehadiran', $priode);
                }])->with(['koreksi_upah' => function ($query) use ($periode_payroll2) {
                    $query->where('periode_tanggal_koreksi', $periode_payroll2);
                }])->with(['koreksi_potongan' => function ($query) use ($periode_payroll2) {
                    $query->where('periode_tanggal_koreksi', $periode_payroll2);
                }])->with(['tunjangan' => function ($query) use ($priode) {
                    $query->where('periode_payroll', $priode);
                }])->get();
            }else{
                $all_karyawan=EmployeeAtribut::where(function ($query)use($tanggal_akhir){
                    $query->where('status_aktif','AKTIF')
                    ->where('join_date','<=',$tanggal_akhir);
                })->orWhere('tanggal_resign','>=',$tanggal_awal)
                ->with(['grading_salary' => function ($query) use ($tahun) {
                    $query->whereRaw("SUBSTRING(periode_umk, 1, 4) = ?", [$tahun]);
                }])->with(['rekap_kehadiran' => function ($query) use ($priode) {
                    $query->where('periode_payroll', $priode);
                }])->with(['rekap_lembur' => function ($query) use ($tanggal_awal,$tanggal_akhir) {
                    $query->where('tanggal_berjalan','>=', $tanggal_awal)
                    ->where('tanggal_berjalan','<=',$tanggal_akhir);
                }])->with(['rekap_iks' => function ($query) use ($tanggal_awal,$tanggal_akhir) {
                    $query->where('tanggal_berjalan','>=', $tanggal_awal)
                    ->where('tanggal_berjalan','<=',$tanggal_akhir);
                }])->with(['rekap_dtpc' => function ($query) use ($tanggal_awal,$tanggal_akhir) {
                    $query->where('tanggal_berjalan','>=', $tanggal_awal)
                    ->where('tanggal_berjalan','<=',$tanggal_akhir);
                }])->with(['bpjs' => function ($query) use ($priode) {
                    $query->where('periode_kehadiran', $priode);
                }])->with(['koreksi_upah' => function ($query) use ($periode_payroll2) {
                    $query->where('periode_tanggal_koreksi', $periode_payroll2);
                }])->with(['koreksi_potongan' => function ($query) use ($periode_payroll2) {
                    $query->where('periode_tanggal_koreksi', $periode_payroll2);
                }])->with(['tunjangan' => function ($query) use ($priode) {
                    $query->where('periode_payroll', $priode);
                }])->get();
            }
            $data_payroll=[];
            $karyawan_yang_belum_terekap=[];
            foreach ($all_karyawan as $key => $value) {
                $kode_grade2=$value->kode_grade;
                $premi_karyawan=0;
                if(isset($value->grading_salary[0]->insentif)){
                    $premis=$value->grading_salary[0]->insentif;
                    $premi_karyawan=($premis/21)*($value->rekap_kehadiran[0]->kehadiran_ok+$value->rekap_kehadiran[0]->kehadiran_dt+$value->rekap_kehadiran[0]->kehadiran_pc+$value->rekap_kehadiran[0]->kehadiran_dtpc);
                }
                if(!isset($value->rekap_kehadiran[0])){
                    $karyawan_yang_belum_terekap[]=[
                        'enroll_id'=>$value->enroll_id,
                        'employee_name'=>$value->employee_name,
                    ];
                }
                if(isset($value->rekap_kehadiran[0])){
                    list($periode_tahun_payroll, $periode_bulan_payroll) = explode("-", $value->rekap_kehadiran[0]->periode_tahun_bulan);
                    //tunjangan
                    $tanggal_masuk = $value->join_date;

                    // hitung selisih tahun antara tanggal masuk dan sekarang
                    $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_awal))->y;

                    // tentukan besaran tunjangan berdasarkan masa kerja
                    if ($selisih_tahun < 1) {
                        $tunjangan = 0;
                    } elseif ($selisih_tahun < 3) {
                        $tunjangan = 2500;
                    } elseif ($selisih_tahun < 6) {
                        $tunjangan = 5000;
                    }elseif ($selisih_tahun < 9) {
                        $tunjangan = 7500;
                    }elseif ($selisih_tahun < 12) {
                        $tunjangan = 10000;
                    }else{
                        $tunjangan = 12500;
                    }

                    $bpjs_tk_jkm_bruto_rupiah=0;
                    $bpjs_tk_jkm_neto_rupiah=0;
                    $bpjs_tk_jkk_bruto_rupiah=0;
                    $bpjs_tk_jkk_neto_rupiah=0;
                    $bpjs_tk_jht_bruto_rupiah=0;
                    $bpjs_tk_jht_neto_rupiah=0;
                    $bpjs_tk_jpn_bruto_rupiah=0;
                    $bpjs_tk_jpn_neto_rupiah=0;
                    $bpjs_ks_jkn_bruto_rupiah=0;
                    $bpjs_ks_jkn_neto_rupiah=0;
                    if($value->bpjs[0]){
                        $bpjs_tk_jkm_bruto_rupiah=$value->bpjs[0]->bpjs_tk_jkm_bruto_rupiah;
                        $bpjs_tk_jkm_neto_rupiah=$value->bpjs[0]->bpjs_tk_jkm_neto_rupiah;
                        $bpjs_tk_jkk_bruto_rupiah=$value->bpjs[0]->bpjs_tk_jkk_bruto_rupiah;
                        $bpjs_tk_jkk_neto_rupiah=$value->bpjs[0]->bpjs_tk_jkk_neto_rupiah;
                        $bpjs_tk_jht_bruto_rupiah=$value->bpjs[0]->bpjs_tk_jht_bruto_rupiah;
                        $bpjs_tk_jht_neto_rupiah=$value->bpjs[0]->bpjs_tk_jht_neto_rupiah;
                        $bpjs_tk_jpn_bruto_rupiah=$value->bpjs[0]->bpjs_tk_jpn_bruto_rupiah;
                        $bpjs_tk_jpn_neto_rupiah=$value->bpjs[0]->bpjs_tk_jpn_neto_rupiah;
                        $bpjs_ks_jkn_bruto_rupiah=$value->bpjs[0]->bpjs_ks_jkn_bruto_rupiah;
                        $bpjs_ks_jkn_neto_rupiah=$value->bpjs[0]->bpjs_ks_jkn_neto_rupiah;
                    }

                    $data_payroll[]=[
                        'kode_rekap_payroll'=>$value->rekap_kehadiran[0]->kode_rekap,
                        'periode_kehadiran'=>$value->rekap_kehadiran[0]->periode_payroll,
                        'periode_tahun_payroll'=>$periode_tahun_payroll,
                        'periode_bulan_payroll'=>$periode_bulan_payroll,
                        'enroll_id'=>$value->enroll_id,
                        'nik'=>$value->nik,
                        'kode_grade'=>$value->kode_grade,
                        'join_date'=>$value->join_date,
                        'employee_name'=>$value->employee_name,
                        'tanggal_resign'=>$value->tanggal_resign,
                        'ptkp'=>$value->ptkp,
                        'jabatan_karyawan'=>$value->status_jabatan,
                        'nama_bagian'=>$value->sub_dept_name??'',
                        'nama_department'=>$value->department_name??'',
                        'kategori_karyawan'=>$value->status_staff,
                        'aktif_karyawan'=>$value->status_aktif,
                        'jenis_kelamin'=>$value->jenis_kelamin,
                        'status_kawin'=>$value->status_kawin,
                        'site_nirwana_name'=>$value->site_nirwana_name,
                        'nama_bank'=>$value->nama_bank==null||$value->nama_bank==""||$value->nama_bank=="-"?'TUNAI':$value->nama_bank,
                        'nomor_rekening_bank'=>$value->nomor_rekening_bank==null||$value->nomor_rekening_bank==""||$value->nomor_rekening_bank=="-"?'-':$value->nomor_rekening_bank,
                        'npwp'=>$value->npwp,
                        'operator'=>'sistem',
                        'sub_dept_id'=>$value->sub_dept_id,
                        'tunjangan_karyawan_rupiah'=>$tunjangan,
                        'premi_karyawan'=>$premi_karyawan,
                        'kehadiran_iby'=>$value->rekap_kehadiran[0]->kehadiran_iby,
                        'kehadiran_itb'=>$value->rekap_kehadiran[0]->kehadiran_itb,
                        'kehadiran_m'=>$value->rekap_kehadiran[0]->kehadiran_m,
                        'kehadiran_dt'=>$value->rekap_kehadiran[0]->kehadiran_dt,
                        'kehadiran_pc'=>$value->rekap_kehadiran[0]->kehadiran_pc,
                        'kehadiran_dtpc'=>$value->rekap_kehadiran[0]->kehadiran_dtpc,
                        'kehadiran_lby'=>$value->rekap_kehadiran[0]->kehadiran_lby,
                        'kehadiran_lsm'=>$value->rekap_kehadiran[0]->kehadiran_lsm,
                        'kehadiran_r'=>$value->rekap_kehadiran[0]->kehadiran_r,
                        'kehadiran_ok'=>$value->rekap_kehadiran[0]->kehadiran_ok,
                        'kehadiran_tk'=>$value->rekap_kehadiran[0]->kehadiran_tk,
                        'total_kehadiran'=>$value->rekap_kehadiran[0]->total_kehadiran,
                        'total_kehadiran_net'=>$value->rekap_kehadiran[0]->total_kehadiran_net,
                        'upah_per_bulan'=>$value->rekap_kehadiran[0]->gaji_pokok,
                        'upah_per_hari'=>$value->rekap_kehadiran[0]->gaji_harian,
                        'upah_per_menit'=>$value->rekap_kehadiran[0]->gaji_menit,
                        'potongan_kehadiran_rupiah'=>$value->rekap_kehadiran[0]->potongan_kehadiran_rupiah,
                        'kehadiran_m_estimasi'=>$value->rekap_kehadiran[0]->kehadiran_m_estimasi,
                        'lembur_1'=>$value->rekap_lembur->sum('lembur_1'),
                        'lembur_2'=>$value->rekap_lembur->sum('lembur_2'),
                        'lembur_3'=>$value->rekap_lembur->sum('lembur_3'),
                        'lembur_4'=>$value->rekap_lembur->sum('lembur_4'),
                        'total_lembur_1234'=>$value->rekap_lembur->sum('total_lembur_1234'),
                        'lembur1_rupiah'=>$value->rekap_lembur->sum('lembur1_rupiah'),
                        'lembur2_rupiah'=>$value->rekap_lembur->sum('lembur2_rupiah'),
                        'lembur3_rupiah'=>$value->rekap_lembur->sum('lembur3_rupiah'),
                        'lembur4_rupiah'=>$value->rekap_lembur->sum('lembur4_rupiah'),
                        'total_lembur_rupiah'=>$value->rekap_lembur->sum('total_lembur_rupiah'),

                        'pendapatan_lainnya_rupiah'=>0,

                        'koreksi_upah_rupiah'=>$value->koreksi_upah->whereIn('jenis_koreksi',[1,null])->sum('jumlah_rp_potongan'),
                        'insentif_jabatan'=>$value->koreksi_upah->where('jenis_koreksi',2)->sum('jumlah_rp_potongan'),
                        'koreksi_potongan_rupiah'=>$value->koreksi_potongan->sum('jumlah_rp_potongan'),

                        'potongan_iks_menit'=>$value->rekap_iks->sum('lama_ijin_menit'),
                        'potongan_iks_rupiah'=>$value->rekap_iks->sum('potongan_iks_rupiah'),
                        'potongan_dt_menit'=>$value->rekap_dtpc->sum('jumlah_menit_absen_dt'),
                        'potongan_pc_menit'=>$value->rekap_dtpc->sum('jumlah_menit_absen_pc'),
                        'potongan_dtpc_menit'=>$value->rekap_dtpc->sum('jumlah_menit_absen_dtpc'),
                        'potongan_dt_rupiah'=>$value->rekap_dtpc->sum('potongan_dt_rupiah'),
                        'potongan_pc_rupiah'=>$value->rekap_dtpc->sum('potongan_pc_rupiah'),
                        'potongan_dtpc_rupiah'=>$value->rekap_dtpc->sum('potongan_dtpc_rupiah'),

                        'pph21'=>0, // dari mana?
                        'iuran_serikat_rupiah'=>0, // dari mana?
                        'iuran_koperasi'=>0, // dari mana?
                        'potongan_kasbon_rupiah'=>0, // dari mana?

                        'bpjs_tk_jkm_rupiah'=> $bpjs_tk_jkm_bruto_rupiah + $bpjs_tk_jkm_neto_rupiah,
                        'bpjs_tk_jkm_perusahaan_rupiah'=> $bpjs_tk_jkm_bruto_rupiah,
                        'bpjs_tk_jkm_karyawan_rupiah'=> $bpjs_tk_jkm_neto_rupiah,
                        'bpjs_tk_jkk_rupiah'=> $bpjs_tk_jkk_bruto_rupiah + $bpjs_tk_jkk_neto_rupiah,
                        'bpjs_tk_jkk_perusahaan_rupiah'=> $bpjs_tk_jkk_bruto_rupiah,
                        'bpjs_tk_jkk_karyawan_rupiah'=> $bpjs_tk_jkk_neto_rupiah,
                        'bpjs_tk_jht_rupiah'=> $bpjs_tk_jht_bruto_rupiah + $bpjs_tk_jht_neto_rupiah,
                        'bpjs_tk_jht_perusahaan_rupiah'=> $bpjs_tk_jht_bruto_rupiah,
                        'bpjs_tk_jht_karyawan_rupiah'=> $bpjs_tk_jht_neto_rupiah,
                        'bpjs_tk_jpn_rupiah'=> $bpjs_tk_jpn_bruto_rupiah + $bpjs_tk_jpn_neto_rupiah,
                        'bpjs_tk_jpn_perusahaan_rupiah'=> $bpjs_tk_jpn_bruto_rupiah,
                        'bpjs_tk_jpn_karyawan_rupiah'=> $bpjs_tk_jpn_neto_rupiah,
                        'bpjs_ks_jkn_rupiah'=> $bpjs_ks_jkn_bruto_rupiah + $bpjs_ks_jkn_neto_rupiah,
                        'bpjs_ks_jkn_perusahaan_rupiah'=> $bpjs_ks_jkn_bruto_rupiah,
                        'bpjs_ks_jkn_karyawan_rupiah'=> $bpjs_ks_jkn_neto_rupiah,
                        'total_bpjs_tk'=>$bpjs_tk_jkm_neto_rupiah + $bpjs_tk_jkk_neto_rupiah + $bpjs_tk_jht_neto_rupiah + $bpjs_tk_jpn_neto_rupiah,
                        'total_bpjs_ks'=>$bpjs_ks_jkn_neto_rupiah,

                    ];
                }
            }
           // $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->get();

            $records_payroll=[];
            foreach ($data_payroll as $k => $v) {
               // $count=$security->where('enroll_id',$v['enroll_id'])->count();
               // if($count==0){
                $upah_bruto_rupiah=($v['upah_per_bulan']+$v['tunjangan_karyawan_rupiah']+$v['premi_karyawan']+$v['total_lembur_rupiah']+$v['pendapatan_lainnya_rupiah']+$v['koreksi_upah_rupiah']+$v['insentif_jabatan'])-
                    ($v['koreksi_potongan_rupiah']+$v['potongan_iks_rupiah']+$v['potongan_dtpc_rupiah']+$v['potongan_kehadiran_rupiah']);

                $upah_neto_rupiah=($v['upah_per_bulan']+$v['tunjangan_karyawan_rupiah']+$v['premi_karyawan']+$v['total_lembur_rupiah']+$v['pendapatan_lainnya_rupiah']+$v['koreksi_upah_rupiah']+$v['insentif_jabatan'])-
                    ($v['koreksi_potongan_rupiah']+$v['potongan_iks_rupiah']+$v['potongan_dtpc_rupiah']+$v['potongan_kehadiran_rupiah'])-$v['pph21'];

                $upah_bersih_rupiah=$upah_neto_rupiah-($v['total_bpjs_tk']+$v['total_bpjs_ks']+$v['iuran_koperasi']+$v['iuran_serikat_rupiah']);

                $total_upah_thp_rupiah=$upah_neto_rupiah-($v['total_bpjs_tk']+$v['total_bpjs_ks']+$v['iuran_koperasi']+$v['iuran_serikat_rupiah'])-$v['potongan_kasbon_rupiah'];

                $jumlah_potongan_rupiah=($v['total_bpjs_tk']+$v['total_bpjs_ks']+$v['iuran_koperasi']+$v['iuran_serikat_rupiah']);
                if($v['nama_bank']=='TUNAI'){
                    $total_upah_thp=(int)$total_upah_thp_rupiah;
                    $last_three_number=substr($total_upah_thp,-3);
                    if($last_three_number<=500){
                        $last_number=500;
                        $total_upah_thp_rupiah=substr_replace((int)$total_upah_thp_rupiah, '500', -3);
                    }else{
                        $total_upah_thp_rupiah=ceil($total_upah_thp_rupiah / 1000) * 1000;
                    }
                }
                $records_payroll=[
                    'kode_rekap_payroll'=>$v['kode_rekap_payroll'],
                    'periode_kehadiran'=>$v['periode_kehadiran'],
                    'periode_tahun_payroll'=>$v['periode_tahun_payroll'],
                    'periode_bulan_payroll'=>$v['periode_bulan_payroll'],
                    'enroll_id'=>$v['enroll_id'],
                    'nik'=>$v['nik'],
                    'kode_grade'=>$v['kode_grade'],
                    'join_date'=>$v['join_date'],

                    'site_nirwana_name'=>$v['site_nirwana_name'],
                    'employee_name'=>$v['employee_name'],
                    'tanggal_resign'=>$v['tanggal_resign'],
                    'kehadiran_iby'=>$v['kehadiran_iby'],
                    'kehadiran_itb'=>$v['kehadiran_itb'],
                    'kehadiran_m'=>$v['kehadiran_m'],
                    'kehadiran_dt'=>$v['kehadiran_dt'],
                    'kehadiran_pc'=>$v['kehadiran_pc'],
                    'kehadiran_dtpc'=>$v['kehadiran_dtpc'],
                    'kehadiran_lby'=>$v['kehadiran_lby'],
                    'kehadiran_lsm'=>$v['kehadiran_lsm'],
                    'kehadiran_r'=>$v['kehadiran_r'],
                    'kehadiran_ok'=>$v['kehadiran_ok'],
                    'kehadiran_tk'=>$v['kehadiran_tk'],
                    'total_kehadiran'=>$v['total_kehadiran'],
                    'total_kehadiran_net'=>$v['total_kehadiran_net'],
                    'ptkp'=>$v['ptkp'],
                    'upah_per_bulan'=>$v['upah_per_bulan'],
                    'upah_per_hari'=>$v['upah_per_hari'],
                    'upah_per_menit'=>$v['upah_per_menit'],
                                    'kehadiran_m_estimasi'=>$v['kehadiran_m_estimasi'],

                    'tunjangan_karyawan_rupiah'=>$v['tunjangan_karyawan_rupiah'],
                    'premi_karyawan'=>$v['premi_karyawan'],

                    'lembur_1'=>$v['lembur_1'],
                    'lembur_2'=>$v['lembur_2'],
                    'lembur_3'=>$v['lembur_3'],
                    'lembur_4'=>$v['lembur_4'],
                    'total_lembur_1234'=>$v['total_lembur_1234'],
                    'lembur1_rupiah'=>$v['lembur1_rupiah'],
                    'lembur2_rupiah'=>$v['lembur2_rupiah'],
                    'lembur3_rupiah'=>$v['lembur3_rupiah'],
                    'lembur4_rupiah'=>$v['lembur4_rupiah'],
                    'total_lembur_rupiah'=>$v['total_lembur_rupiah'],

                    'pendapatan_lainnya_rupiah'=>$v['pendapatan_lainnya_rupiah'],

                    'koreksi_upah_rupiah'=>$v['koreksi_upah_rupiah'],
                    'insentif_jabatan'=>$v['insentif_jabatan'],
                    'koreksi_potongan_rupiah'=>$v['koreksi_potongan_rupiah'],

                    'potongan_iks_menit'=>$v['potongan_iks_menit'],
                    'potongan_dt_menit'=>$v['potongan_dt_menit'],
                    'potongan_pc_menit'=>$v['potongan_pc_menit'],
                    'potongan_dtpc_menit'=>$v['potongan_dtpc_menit'],
                    'potongan_iks_rupiah'=>$v['potongan_iks_rupiah'],
                    'potongan_dt_rupiah'=>$v['potongan_dt_rupiah'],
                    'potongan_pc_rupiah'=>$v['potongan_pc_rupiah'],
                    'potongan_dtpc_rupiah'=>$v['potongan_dtpc_rupiah'],
                    'potongan_kehadiran_rupiah'=>$v['potongan_kehadiran_rupiah'],


                    'upah_bruto_rupiah'=>$upah_bruto_rupiah??0,
                    'upah_neto_rupiah'=>$upah_neto_rupiah??0,

                    'upah_bersih_rupiah'=>$upah_bersih_rupiah??0,
                    'total_upah_thp_rupiah'=>$total_upah_thp_rupiah??0,
                    'jumlah_potongan_rupiah'=>$jumlah_potongan_rupiah??0,


                    'pph21'=>$v['pph21'], // dari mana?
                    'iuran_serikat_rupiah'=>$v['iuran_serikat_rupiah'], // dari mana?
                    'iuran_koperasi'=>$v['iuran_koperasi'], // dari mana?
                    'potongan_kasbon_rupiah'=>$v['potongan_kasbon_rupiah'], // dari mana?

                    'bpjs_tk_jkm_rupiah'=> $v['bpjs_tk_jkm_rupiah'],
                    'bpjs_tk_jkm_perusahaan_rupiah'=> $v['bpjs_tk_jkm_perusahaan_rupiah'],
                    'bpjs_tk_jkm_karyawan_rupiah'=> $v['bpjs_tk_jkm_karyawan_rupiah'],
                    'bpjs_tk_jkk_rupiah'=> $v['bpjs_tk_jkk_rupiah'],
                    'bpjs_tk_jkk_perusahaan_rupiah'=> $v['bpjs_tk_jkk_perusahaan_rupiah'],
                    'bpjs_tk_jkk_karyawan_rupiah'=> $v['bpjs_tk_jkk_karyawan_rupiah'],
                    'bpjs_tk_jht_rupiah'=> $v['bpjs_tk_jht_rupiah'],
                    'bpjs_tk_jht_perusahaan_rupiah'=> $v['bpjs_tk_jht_perusahaan_rupiah'],
                    'bpjs_tk_jht_karyawan_rupiah'=> $v['bpjs_tk_jht_karyawan_rupiah'],
                    'bpjs_tk_jpn_rupiah'=> $v['bpjs_tk_jpn_rupiah'],
                    'bpjs_tk_jpn_perusahaan_rupiah'=> $v['bpjs_tk_jpn_perusahaan_rupiah'],
                    'bpjs_tk_jpn_karyawan_rupiah'=> $v['bpjs_tk_jpn_karyawan_rupiah'],
                    'bpjs_ks_jkn_rupiah'=> $v['bpjs_ks_jkn_rupiah'],
                    'bpjs_ks_jkn_perusahaan_rupiah'=> $v['bpjs_ks_jkn_perusahaan_rupiah'],
                    'bpjs_ks_jkn_karyawan_rupiah'=> $v['bpjs_ks_jkn_karyawan_rupiah'],
                    'total_bpjs_tk'=>$v['total_bpjs_tk'],
                    'total_bpjs_ks'=>$v['total_bpjs_ks'],

                    'status_kawin'=>$v['status_kawin'],
                    'jabatan_karyawan'=>$v['jabatan_karyawan'],
                    'nama_bagian'=>$v['nama_bagian'],
                    'nama_department'=>$v['nama_department'],
                    'kategori_karyawan'=>$v['kategori_karyawan'],
                    'aktif_karyawan'=>$v['aktif_karyawan'],
                    'jenis_kelamin'=>$v['jenis_kelamin'],
                    'nama_bank'=>$v['nama_bank'],
                    'nomor_rekening_bank'=>$v['nomor_rekening_bank'],
                    'npwp'=>$v['npwp'],
                    'operator'=>$v['operator'],
                    'sub_dept_id'=>$v['sub_dept_id'],
                    'total_upah_thp_rupiah_employee'=>ceil($total_upah_thp_rupiah / 100) * 100
                ];

                $count=RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$v['kode_rekap_payroll'])->count();
                if($count){
                    RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$v['kode_rekap_payroll'])->update($records_payroll);
                }
                else{
                    RekapPerhitunganPayroll::create($records_payroll);
                }
            }

            //update tanggal resign
            $rekap=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('total_kehadiran_net','0')->get();
            foreach ($rekap as $key => $value) {
                $karyawan=EmployeeAtribut::where('enroll_id',$value->enroll_id)->first();
                $data=['tanggal_resign'=>$karyawan->tanggal_resign];
                RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$value->kode_rekap_payroll)->update($data);
            }

            //pembulatan
            $rekap2=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->get();
            foreach ($rekap2 as $key => $value) {
                $total_upah_thp_rupiah_pembulatan= ceil($value->total_upah_thp_rupiah / 100) * 100;
                $pembulatan=$total_upah_thp_rupiah_pembulatan-$value->total_upah_thp_rupiah;

                $data=['pembulatan'=>$pembulatan,];
                RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$value->kode_rekap_payroll)->update($data);
            }

            //rekap jurnal
            $departement=DepartmentAll::where('site_nirwana_id','NAG')->get();
            $data_potongan = $this->potongan($periode_payroll);
            $data_koreksi = $this->koreksi($periode_payroll);
            foreach ($departement as $key => $value) {
                $potongan_bpjs_tk=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','1')->sum('jumlah_rp_potongan');
                $potongan_bpjs_ks=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','2')->sum('jumlah_rp_potongan');
                $potongan_bazzar=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','3')->sum('jumlah_rp_potongan');
                $potongan_kasbon=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','4')->sum('jumlah_rp_potongan');
                $potongan_lain=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','5')->sum('jumlah_rp_potongan');
                $koreksi_upah=$data_koreksi->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_koreksi','1')->sum('jumlah_rp_potongan');
                $koreksi_insentif=$data_koreksi->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_koreksi','2')->sum('jumlah_rp_potongan');
                $payroll=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('periode_umk',null)
                    ->where('kategori_karyawan','NON STAFF')->where('sub_dept_id',$value->sub_dept_id)
                    ->whereHas('employee_atribut',function($query)use($tanggal_awal){
                        $query->where('tanggal_resign',null)
                        ->orWhere('tanggal_resign','>',$tanggal_awal);
                    })->get();
                $rp_cuti_tahuna=0;
                $potongan_kehadiran_rupiah= $payroll->sum('potongan_kehadiran_rupiah');
                $rp_pot_jam=$payroll->sum('potongan_iks_rupiah')+$payroll->sum('potongan_dtpc_rupiah');
                $iuran_serikat_rupiah=$payroll->sum('iuran_serikat_rupiah');
                $iuran_koperasi=$payroll->sum('iuran_koperasi');

                $gaji_umk=$payroll->sum('upah_per_bulan');
                $pembulatan=$payroll->sum('pembulatan');
                $gaji=$gaji_umk+ $pembulatan + $koreksi_upah;
                $tunjangan_karyawan_rupiah=$payroll->sum('tunjangan_karyawan_rupiah')+$koreksi_insentif;
                $total_lembur_rupiah=$payroll->sum('total_lembur_rupiah');
                $bonus=0;
                $piutang_karyawan=$potongan_kasbon;
                $piutang_bazzar=$potongan_bazzar;
                $bpjs_tk=$payroll->sum('total_bpjs_tk');
                $bpjs_ks=$payroll->sum('total_bpjs_ks');
                $potongan= $potongan_lain + $rp_cuti_tahuna + $potongan_kehadiran_rupiah + $rp_pot_jam + $iuran_serikat_rupiah + $iuran_koperasi;
                $gaji_neto=$payroll->sum('total_upah_thp_rupiah')+$payroll->sum('pembulatan');

                $data=[
                    'kode_bagian'=>$value->sub_dept_id,
                    'nama_bagian'=>$value->sub_dept_name,
                    'gaji'=>$gaji ,
                    'tunjangan_karyawan_rupiah'=> $tunjangan_karyawan_rupiah,
                    'total_lembur_rupiah'=> $total_lembur_rupiah,
                    'bonus'=> $bonus,
                    'piutang_karyawan'=>$piutang_karyawan,
                    'piutang_bazzar'=>$piutang_bazzar,
                    'bpjs_tk'=>$bpjs_tk,
                    'bpjs_ks'=>$bpjs_ks,
                    'potongan'=>$potongan,
                    'gaji_neto'=>$gaji_neto,
                    'jumlah_karyawn'=>$payroll->count(),
                    'periode_payroll'=>$periode_payroll,
                ];
                $count=Jurnal::where('kode_bagian',$value->sub_dept_id)->where('periode_payroll',$periode_payroll)->count();

                if($count){
                    Jurnal::where('kode_bagian',$value->sub_dept_id)->where('periode_payroll',$periode_payroll)->update($data);
                }
                else{
                    Jurnal::create($data);
                }
            }
            return $karyawan_yang_belum_terekap;
        }
        else{
            if($periode_umk!='2023-2024'){
                if($periode_umk=='2023-10'){
                    $year_umk=substr($periode_umk,0,4);
                    $month_umk_first=$year_umk.'-12-26';
                    $month_umk_last=$year_umk.'-12-31';
                    $timestamp3 = strtotime($month_umk_first);
                    $timestamp4 = strtotime($month_umk_last);
                    $jumlah_hari = (abs($timestamp3 - $timestamp4) / (60 * 60 * 24)+1);
                    for ($i = strtotime($month_umk_first); $i <= strtotime($month_umk_last); $i += 86400) {
                        if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
                            $jumlah_hari_sabtu_minggu++;
                        }
                    }
                    $x=MasterDataAbsenKehadiran::selectRaw('uuid,tanggal_berjalan,tanggal_absen,shift_work_id,kode_hari,nama_hari,time_table_name,mulai_jam_kerja,akhir_jam_kerja,jam_kerja,jumlah_jam_kerja,jumlah_menit_kerja,mulai_jam_istirahat,akhir_jam_istirahat,jumlah_jam_istirahat,jumlah_menit_istirahat,absen_masuk_kerja,absen_pulang_kerja,enroll_id,nik,employee_id,employee_name,join_date,work_status,employee_status,posisi_name,kode_grade,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,status_absen,nama_absen_ijin,kode_ijin_payroll,absen_ijin_id,nomor_absen_ijin,tanggal_mulai_ijin,tanggal_akhir_ijin,permits_dari_pukul,permits_sampai_pukul,total_menit_permits,jumlah_menit_absen_dt,jumlah_menit_absen_pc,jumlah_menit_absen_dtpc,jumlah_absen_menit_kerja,absen_s_sakit,absen_iby_ijin_dibayar,absen_itb_ijin_tidak_dibayar,absen_m_mangkir,absen_pc_pulang_cepat,absen_dt_datang_terlambat,absen_dtpc_datang_terlambat_pulang_cepat,absen_lby_libur_dibayar,absen_lsm_libur_sabtu_minggu,absen_ltb_libur_tidak_dibayar,absen_r_resign,absen_dl_dinas_luar,absen_ok_hadir,absen_tk_tidak_kerja,absen_cuti_umum,absen_cuti_khusus,absen_hari_resmi,absen_alasan,holiday_id,holiday_name,kelebihan_jam_kerja_l1,kelebihan_jam_kerja_l2,kelebihan_jam_kerja_l3,kelebihan_jam_kerja_l4,jumlah_selisih_menit_kerja,operator,catatan_hrd,nomor_form_perubahan_absen,nomor_form_lembur,jumlah_jam_lembur,jumlah_jam_istirahat_lembur,mulai_jam_lembur,akhir_jam_lembur,status_lembur,jumlah_jam_lembur_approved,updated_absen_cekinout,updated_absen_ijin,updated_absen_permits,updated_absen_dtpc,created_at,updated_at,deleted_at')->whereRaw('tanggal_berjalan >= "'.$month_umk_first.'" and tanggal_berjalan <= "'.$month_umk_last.'"'.$inEnrollId.'')->groupBy('enroll_id')->get();
                    $a=MasterDataAbsenKehadiran::selectRaw('uuid,tanggal_berjalan,tanggal_absen,shift_work_id,kode_hari,nama_hari,time_table_name,mulai_jam_kerja,akhir_jam_kerja,jam_kerja,jumlah_jam_kerja,jumlah_menit_kerja,mulai_jam_istirahat,akhir_jam_istirahat,jumlah_jam_istirahat,jumlah_menit_istirahat,absen_masuk_kerja,absen_pulang_kerja,enroll_id,nik,employee_id,employee_name,join_date,work_status,employee_status,posisi_name,kode_grade,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,status_absen,nama_absen_ijin,kode_ijin_payroll,absen_ijin_id,nomor_absen_ijin,tanggal_mulai_ijin,tanggal_akhir_ijin,permits_dari_pukul,permits_sampai_pukul,total_menit_permits,jumlah_menit_absen_dt,jumlah_menit_absen_pc,jumlah_menit_absen_dtpc,jumlah_absen_menit_kerja,absen_s_sakit,absen_iby_ijin_dibayar,absen_itb_ijin_tidak_dibayar,absen_m_mangkir,absen_pc_pulang_cepat,absen_dt_datang_terlambat,absen_dtpc_datang_terlambat_pulang_cepat,absen_lby_libur_dibayar,absen_lsm_libur_sabtu_minggu,absen_ltb_libur_tidak_dibayar,absen_r_resign,absen_dl_dinas_luar,absen_ok_hadir,absen_tk_tidak_kerja,absen_cuti_umum,absen_cuti_khusus,absen_hari_resmi,absen_alasan,holiday_id,holiday_name,kelebihan_jam_kerja_l1,kelebihan_jam_kerja_l2,kelebihan_jam_kerja_l3,kelebihan_jam_kerja_l4,jumlah_selisih_menit_kerja,operator,catatan_hrd,nomor_form_perubahan_absen,nomor_form_lembur,jumlah_jam_lembur,jumlah_jam_istirahat_lembur,mulai_jam_lembur,akhir_jam_lembur,status_lembur,jumlah_jam_lembur_approved,updated_absen_cekinout,updated_absen_ijin,updated_absen_permits,updated_absen_dtpc,created_at,updated_at,deleted_at')->whereRaw('tanggal_berjalan >= "'.$month_umk_first.'" and tanggal_berjalan <= "'.$month_umk_last.'" and nomor_form_lembur != "'.null.'"'.$inEnrollId.'')->get();
                    $b=MasterDataAbsenKehadiran::selectRaw('uuid,tanggal_berjalan,tanggal_absen,shift_work_id,kode_hari,nama_hari,time_table_name,mulai_jam_kerja,akhir_jam_kerja,jam_kerja,jumlah_jam_kerja,jumlah_menit_kerja,mulai_jam_istirahat,akhir_jam_istirahat,jumlah_jam_istirahat,jumlah_menit_istirahat,absen_masuk_kerja,absen_pulang_kerja,enroll_id,nik,employee_id,employee_name,join_date,work_status,employee_status,posisi_name,kode_grade,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,status_absen,nama_absen_ijin,kode_ijin_payroll,absen_ijin_id,nomor_absen_ijin,tanggal_mulai_ijin,tanggal_akhir_ijin,permits_dari_pukul,permits_sampai_pukul,total_menit_permits,jumlah_menit_absen_dt,jumlah_menit_absen_pc,jumlah_menit_absen_dtpc,jumlah_absen_menit_kerja,absen_s_sakit,absen_iby_ijin_dibayar,absen_itb_ijin_tidak_dibayar,absen_m_mangkir,absen_pc_pulang_cepat,absen_dt_datang_terlambat,absen_dtpc_datang_terlambat_pulang_cepat,absen_lby_libur_dibayar,absen_lsm_libur_sabtu_minggu,absen_ltb_libur_tidak_dibayar,absen_r_resign,absen_dl_dinas_luar,absen_ok_hadir,absen_tk_tidak_kerja,absen_cuti_umum,absen_cuti_khusus,absen_hari_resmi,absen_alasan,holiday_id,holiday_name,kelebihan_jam_kerja_l1,kelebihan_jam_kerja_l2,kelebihan_jam_kerja_l3,kelebihan_jam_kerja_l4,jumlah_selisih_menit_kerja,operator,catatan_hrd,nomor_form_perubahan_absen,nomor_form_lembur,jumlah_jam_lembur,jumlah_jam_istirahat_lembur,mulai_jam_lembur,akhir_jam_lembur,status_lembur,jumlah_jam_lembur_approved,updated_absen_cekinout,updated_absen_ijin,updated_absen_permits,updated_absen_dtpc,created_at,updated_at,deleted_at')->whereRaw('tanggal_berjalan >= "'.$month_umk_first.'" and tanggal_berjalan <= "'.$month_umk_last.'" and total_menit_permits>0'.$inEnrollId.'')->groupby('enroll_id')->get();
                    $c=MasterDataAbsenKehadiran::selectRaw('uuid,tanggal_berjalan,tanggal_absen,shift_work_id,kode_hari,nama_hari,time_table_name,mulai_jam_kerja,akhir_jam_kerja,jam_kerja,jumlah_jam_kerja,jumlah_menit_kerja,mulai_jam_istirahat,akhir_jam_istirahat,jumlah_jam_istirahat,jumlah_menit_istirahat,absen_masuk_kerja,absen_pulang_kerja,enroll_id,nik,employee_id,employee_name,join_date,work_status,employee_status,posisi_name,kode_grade,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,status_absen,nama_absen_ijin,kode_ijin_payroll,absen_ijin_id,nomor_absen_ijin,tanggal_mulai_ijin,tanggal_akhir_ijin,permits_dari_pukul,permits_sampai_pukul,total_menit_permits,jumlah_menit_absen_dt,jumlah_menit_absen_pc,jumlah_menit_absen_dtpc,jumlah_absen_menit_kerja,absen_s_sakit,absen_iby_ijin_dibayar,absen_itb_ijin_tidak_dibayar,absen_m_mangkir,absen_pc_pulang_cepat,absen_dt_datang_terlambat,absen_dtpc_datang_terlambat_pulang_cepat,absen_lby_libur_dibayar,absen_lsm_libur_sabtu_minggu,absen_ltb_libur_tidak_dibayar,absen_r_resign,absen_dl_dinas_luar,absen_ok_hadir,absen_tk_tidak_kerja,absen_cuti_umum,absen_cuti_khusus,absen_hari_resmi,absen_alasan,holiday_id,holiday_name,kelebihan_jam_kerja_l1,kelebihan_jam_kerja_l2,kelebihan_jam_kerja_l3,kelebihan_jam_kerja_l4,jumlah_selisih_menit_kerja,operator,catatan_hrd,nomor_form_perubahan_absen,nomor_form_lembur,jumlah_jam_lembur,jumlah_jam_istirahat_lembur,mulai_jam_lembur,akhir_jam_lembur,status_lembur,jumlah_jam_lembur_approved,updated_absen_cekinout,updated_absen_ijin,updated_absen_permits,updated_absen_dtpc,created_at,updated_at,deleted_at')->whereRaw('tanggal_berjalan >= "'.$month_umk_first.'" and tanggal_berjalan <= "'.$month_umk_last.'" and jumlah_menit_absen_dtpc != 0'.$inEnrollId.'')->get();
                }else if($periode_umk=='2024-01'){
                    $year_umk=substr($periode_umk,0,4);
                    $month_umk_first='2024-01-01';
                    $month_umk_last='2024-01-25';
                    $timestamp3 = strtotime($month_umk_first);
                    $timestamp4 = strtotime($month_umk_last);
                    $jumlah_hari = (abs($timestamp3 - $timestamp4) / (60 * 60 * 24)+1);
                    for ($i = strtotime($month_umk_first); $i <= strtotime($month_umk_last); $i += 86400) {
                        if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
                            $jumlah_hari_sabtu_minggu++;
                        }
                    }
                    $x=MasterDataAbsenKehadiran::selectRaw('uuid,tanggal_berjalan,tanggal_absen,shift_work_id,kode_hari,nama_hari,time_table_name,mulai_jam_kerja,akhir_jam_kerja,jam_kerja,jumlah_jam_kerja,jumlah_menit_kerja,mulai_jam_istirahat,akhir_jam_istirahat,jumlah_jam_istirahat,jumlah_menit_istirahat,absen_masuk_kerja,absen_pulang_kerja,enroll_id,nik,employee_id,employee_name,join_date,work_status,employee_status,posisi_name,kode_grade,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,status_absen,nama_absen_ijin,kode_ijin_payroll,absen_ijin_id,nomor_absen_ijin,tanggal_mulai_ijin,tanggal_akhir_ijin,permits_dari_pukul,permits_sampai_pukul,total_menit_permits,jumlah_menit_absen_dt,jumlah_menit_absen_pc,jumlah_menit_absen_dtpc,jumlah_absen_menit_kerja,absen_s_sakit,absen_iby_ijin_dibayar,absen_itb_ijin_tidak_dibayar,absen_m_mangkir,absen_pc_pulang_cepat,absen_dt_datang_terlambat,absen_dtpc_datang_terlambat_pulang_cepat,absen_lby_libur_dibayar,absen_lsm_libur_sabtu_minggu,absen_ltb_libur_tidak_dibayar,absen_r_resign,absen_dl_dinas_luar,absen_ok_hadir,absen_tk_tidak_kerja,absen_cuti_umum,absen_cuti_khusus,absen_hari_resmi,absen_alasan,holiday_id,holiday_name,kelebihan_jam_kerja_l1,kelebihan_jam_kerja_l2,kelebihan_jam_kerja_l3,kelebihan_jam_kerja_l4,jumlah_selisih_menit_kerja,operator,catatan_hrd,nomor_form_perubahan_absen,nomor_form_lembur,jumlah_jam_lembur,jumlah_jam_istirahat_lembur,mulai_jam_lembur,akhir_jam_lembur,status_lembur,jumlah_jam_lembur_approved,updated_absen_cekinout,updated_absen_ijin,updated_absen_permits,updated_absen_dtpc,created_at,updated_at,deleted_at')->whereRaw('tanggal_berjalan >= "'.$month_umk_first.'" and tanggal_berjalan <= "'.$month_umk_last.'"'.$inEnrollId.'')->groupBy('enroll_id')->get();
                    $a=MasterDataAbsenKehadiran::selectRaw('uuid,tanggal_berjalan,tanggal_absen,shift_work_id,kode_hari,nama_hari,time_table_name,mulai_jam_kerja,akhir_jam_kerja,jam_kerja,jumlah_jam_kerja,jumlah_menit_kerja,mulai_jam_istirahat,akhir_jam_istirahat,jumlah_jam_istirahat,jumlah_menit_istirahat,absen_masuk_kerja,absen_pulang_kerja,enroll_id,nik,employee_id,employee_name,join_date,work_status,employee_status,posisi_name,kode_grade,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,status_absen,nama_absen_ijin,kode_ijin_payroll,absen_ijin_id,nomor_absen_ijin,tanggal_mulai_ijin,tanggal_akhir_ijin,permits_dari_pukul,permits_sampai_pukul,total_menit_permits,jumlah_menit_absen_dt,jumlah_menit_absen_pc,jumlah_menit_absen_dtpc,jumlah_absen_menit_kerja,absen_s_sakit,absen_iby_ijin_dibayar,absen_itb_ijin_tidak_dibayar,absen_m_mangkir,absen_pc_pulang_cepat,absen_dt_datang_terlambat,absen_dtpc_datang_terlambat_pulang_cepat,absen_lby_libur_dibayar,absen_lsm_libur_sabtu_minggu,absen_ltb_libur_tidak_dibayar,absen_r_resign,absen_dl_dinas_luar,absen_ok_hadir,absen_tk_tidak_kerja,absen_cuti_umum,absen_cuti_khusus,absen_hari_resmi,absen_alasan,holiday_id,holiday_name,kelebihan_jam_kerja_l1,kelebihan_jam_kerja_l2,kelebihan_jam_kerja_l3,kelebihan_jam_kerja_l4,jumlah_selisih_menit_kerja,operator,catatan_hrd,nomor_form_perubahan_absen,nomor_form_lembur,jumlah_jam_lembur,jumlah_jam_istirahat_lembur,mulai_jam_lembur,akhir_jam_lembur,status_lembur,jumlah_jam_lembur_approved,updated_absen_cekinout,updated_absen_ijin,updated_absen_permits,updated_absen_dtpc,created_at,updated_at,deleted_at')->whereRaw('tanggal_berjalan >= "'.$month_umk_first.'" and tanggal_berjalan <= "'.$month_umk_last.'" and nomor_form_lembur != "'.null.'"'.$inEnrollId.'')->get();
                    $b=MasterDataAbsenKehadiran::selectRaw('uuid,tanggal_berjalan,tanggal_absen,shift_work_id,kode_hari,nama_hari,time_table_name,mulai_jam_kerja,akhir_jam_kerja,jam_kerja,jumlah_jam_kerja,jumlah_menit_kerja,mulai_jam_istirahat,akhir_jam_istirahat,jumlah_jam_istirahat,jumlah_menit_istirahat,absen_masuk_kerja,absen_pulang_kerja,enroll_id,nik,employee_id,employee_name,join_date,work_status,employee_status,posisi_name,kode_grade,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,status_absen,nama_absen_ijin,kode_ijin_payroll,absen_ijin_id,nomor_absen_ijin,tanggal_mulai_ijin,tanggal_akhir_ijin,permits_dari_pukul,permits_sampai_pukul,total_menit_permits,jumlah_menit_absen_dt,jumlah_menit_absen_pc,jumlah_menit_absen_dtpc,jumlah_absen_menit_kerja,absen_s_sakit,absen_iby_ijin_dibayar,absen_itb_ijin_tidak_dibayar,absen_m_mangkir,absen_pc_pulang_cepat,absen_dt_datang_terlambat,absen_dtpc_datang_terlambat_pulang_cepat,absen_lby_libur_dibayar,absen_lsm_libur_sabtu_minggu,absen_ltb_libur_tidak_dibayar,absen_r_resign,absen_dl_dinas_luar,absen_ok_hadir,absen_tk_tidak_kerja,absen_cuti_umum,absen_cuti_khusus,absen_hari_resmi,absen_alasan,holiday_id,holiday_name,kelebihan_jam_kerja_l1,kelebihan_jam_kerja_l2,kelebihan_jam_kerja_l3,kelebihan_jam_kerja_l4,jumlah_selisih_menit_kerja,operator,catatan_hrd,nomor_form_perubahan_absen,nomor_form_lembur,jumlah_jam_lembur,jumlah_jam_istirahat_lembur,mulai_jam_lembur,akhir_jam_lembur,status_lembur,jumlah_jam_lembur_approved,updated_absen_cekinout,updated_absen_ijin,updated_absen_permits,updated_absen_dtpc,created_at,updated_at,deleted_at')->whereRaw('tanggal_berjalan >= "'.$month_umk_first.'" and tanggal_berjalan <= "'.$month_umk_last.'" and total_menit_permits>0'.$inEnrollId.'')->groupby('enroll_id')->get();
                    $c=MasterDataAbsenKehadiran::selectRaw('uuid,tanggal_berjalan,tanggal_absen,shift_work_id,kode_hari,nama_hari,time_table_name,mulai_jam_kerja,akhir_jam_kerja,jam_kerja,jumlah_jam_kerja,jumlah_menit_kerja,mulai_jam_istirahat,akhir_jam_istirahat,jumlah_jam_istirahat,jumlah_menit_istirahat,absen_masuk_kerja,absen_pulang_kerja,enroll_id,nik,employee_id,employee_name,join_date,work_status,employee_status,posisi_name,kode_grade,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,status_absen,nama_absen_ijin,kode_ijin_payroll,absen_ijin_id,nomor_absen_ijin,tanggal_mulai_ijin,tanggal_akhir_ijin,permits_dari_pukul,permits_sampai_pukul,total_menit_permits,jumlah_menit_absen_dt,jumlah_menit_absen_pc,jumlah_menit_absen_dtpc,jumlah_absen_menit_kerja,absen_s_sakit,absen_iby_ijin_dibayar,absen_itb_ijin_tidak_dibayar,absen_m_mangkir,absen_pc_pulang_cepat,absen_dt_datang_terlambat,absen_dtpc_datang_terlambat_pulang_cepat,absen_lby_libur_dibayar,absen_lsm_libur_sabtu_minggu,absen_ltb_libur_tidak_dibayar,absen_r_resign,absen_dl_dinas_luar,absen_ok_hadir,absen_tk_tidak_kerja,absen_cuti_umum,absen_cuti_khusus,absen_hari_resmi,absen_alasan,holiday_id,holiday_name,kelebihan_jam_kerja_l1,kelebihan_jam_kerja_l2,kelebihan_jam_kerja_l3,kelebihan_jam_kerja_l4,jumlah_selisih_menit_kerja,operator,catatan_hrd,nomor_form_perubahan_absen,nomor_form_lembur,jumlah_jam_lembur,jumlah_jam_istirahat_lembur,mulai_jam_lembur,akhir_jam_lembur,status_lembur,jumlah_jam_lembur_approved,updated_absen_cekinout,updated_absen_ijin,updated_absen_permits,updated_absen_dtpc,created_at,updated_at,deleted_at')->whereRaw('tanggal_berjalan >= "'.$month_umk_first.'" and tanggal_berjalan <= "'.$month_umk_last.'" and jumlah_menit_absen_dtpc != 0'.$inEnrollId.'')->get();
                }

                // rekap kehadiran
                $y=[];
                foreach($x as $key=>$value){
                    $enroll_id=$value->enroll_id;
                    $unik=sprintf("%04d", $enroll_id);
                    $kode_rekap_kehadiran=$bulan_sekarang.$unik;

                    $employee=EmployeeAtribut::where('enroll_id',$value->enroll_id)->first();
                    $IBY_employe=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->whereIn('status_absen',$IBY)->count();
                    $ITB_employe=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->wherein('status_absen', $ITB)->count();
                    $LBY_employe=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','LN')->whereNotin('kode_hari', ['5','6'])->count();
                    $lsm_employe=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->whereIn('kode_hari', ['5','6'])->count();
                    $dt_employe=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','0')->where('status_absen',null)->count();
                    $pc_employe=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('jumlah_menit_absen_pc','>','0')->where('jumlah_menit_absen_dt','0')->where('status_absen',null)->count();
                    $dtpc_employe=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','>','0')->where('status_absen',null)->count();
                    $absen_M=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','M')->count();
                    $absen_TL=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','TL')->where('mulai_jam_kerja','!=',null)->count();
                    $absen_R=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','R')->count();
                    $absen_ok=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->whereNotin('kode_hari', ['5','6'])->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();
                    $absen_IKS=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','IKS')->where('jumlah_menit_absen_dtpc','0')->count();
                    $jumlah_hari_kerja_karyawan=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->count();
                    $jumlah_hari_sabtu_minggu_karyawan=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->whereIn('kode_hari',[5,6])->count();
                    $absen_ok=$absen_ok+$absen_IKS;
                    $total_kehadiran_net=$absen_ok+$dt_employe+$pc_employe+$dtpc_employe;
                    $aa=$total_kehadiran_net+$ITB_employe+$LBY_employe+$IBY_employe+$lsm_employe+$absen_M+$absen_TL+$absen_R;
                    $kehadiran_tk=$jumlah_hari-$aa;
                    $total_kehadiran=$IBY_employe+$ITB_employe+$lsm_employe+$dtpc_employe+$absen_M+$absen_R+$absen_ok+$LBY_employe+$dt_employe+$pc_employe+$absen_TL;

                    //security variable
                    $libur_sabtu_minggu=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen',null)->where('mulai_jam_kerja',null)->count();
                    $libur=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','M')->where('mulai_jam_kerja',null)->count();
                    $lsm_security=$libur_sabtu_minggu+$libur;
                    $absen_M_security=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','M')->where('mulai_jam_kerja','!=',null)->count();
                    $absen_ok_security=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('mulai_jam_kerja','!=',null)->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();
                    $absen_ok_security_total=$absen_ok_security+$absen_IKS;
                    $total_kehadiran_net_security=$absen_ok_security+$dt_employe+$pc_employe+$dtpc_employe;
                    $aa_security=$total_kehadiran_net_security+$ITB_employe+$LBY_employe+$IBY_employe+$lsm_security+$absen_M_security+$absen_TL+$absen_R;
                    $kehadiran_tk_security=$jumlah_hari-$aa_security;
                    $total_kehadiran_security=$IBY_employe+$ITB_employe+$lsm_security+$dtpc_employe+$absen_M_security+$absen_R+$absen_ok_security+$LBY_employe+$dt_employe+$pc_employe+$absen_TL;

                    if($security->where('enroll_id',$value->enroll_id)->count()){
                        $jumlah_hari_kerja=$jumlah_hari-$lsm_security;
                        $lsm=$lsm_security;
                        $m=$absen_M_security;
                        $ok=$absen_ok_security_total;
                        $tk=$kehadiran_tk_security;
                        $total_kh=$total_kehadiran_security;
                    }else{
                        $jumlah_hari_kerja=$jumlah_hari_kerja_karyawan-$jumlah_hari_sabtu_minggu_karyawan;
                        $lsm=$lsm_employe;
                        $m=$absen_M;
                        $ok=$absen_ok;
                        $tk=$kehadiran_tk;
                        $total_kh=$total_kehadiran;
                    }
                    $kehadiran_dl=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','DL')->count();
                    $kehadiran_cb=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','CB')->count();
                    $kehadiran_cbd=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','CBD')->count();
                    $kehadiran_cg=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','CG')->count();
                    $kehadiran_ch=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','CH')->count();
                    $kehadiran_cm=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','CM')->count();
                    $kehadiran_cn=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','CN')->count();
                    $kehadiran_ct=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','CT')->count();
                    $kehadiran_ig=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','IG')->count();
                    $kehadiran_im=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','IM')->count();
                    $kehadiran_ka=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','KA')->count();
                    $kehadiran_km=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','KM')->count();
                    $kehadiran_kr=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','KR')->count();
                    $kehadiran_na=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','NA')->count();
                    $kehadiran_pp=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','PP')->count();
                    $kehadiran_i=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','I')->count();
                    $kehadiran_lp=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','LP')->count();
                    $kehadiran_l=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','L')->count();
                    $kehadiran_tl=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','TL')->count();
                    $kehadiran_iks=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','IKS')->count();
                    $kehadiran_s=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','S')->count();
                    $M_estimasi=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','M')->count();
                    $TL_estimasi=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->where('enroll_id',$value->enroll_id)->where('status_absen','TL')->where('mulai_jam_kerja','!=',null)->count();
                    $y=[
                        'uuid'=>Str::uuid('uuid'),
                        'periode_umk'=>$periode_umk,
                        'periode_payroll'=>$priode,
                        'periode_tahun'=>$tahun,
                        'periode_bulan'=>$bulan,

                        'kode_rekap_kehadiran'=> $kode_rekap_kehadiran,

                        'enroll_id'=>$employee->enroll_id,
                        'nik'=>$employee->nik,
                        'employee_name'=>$employee->employee_name,
                        'site_nirwana_id'=>$employee->site_nirwana_id,
                        'site_nirwana_name'=>$employee->site_nirwana_name,
                        'department_id'=>$employee->department_id,
                        'department_name'=>$employee->department_name,
                        'sub_dept_id'=>$employee->sub_dept_id,
                        'sub_dept_name'=>$employee->sub_dept_name,
                        'join_date'=>$employee->join_date,
                        'tanggal_resign'=>$employee->tanggal_resign,
                        'status_aktif'=>$employee->status_aktif,
                        'status_staff'=>$employee->status_staff,
                        'kehadiran_iby'=>$IBY_employe,
                        'kehadiran_itb'=>$ITB_employe,
                        'kehadiran_lby'=>$LBY_employe,
                        'kehadiran_lsm'=>$lsm,
                        'kehadiran_dt'=> $dt_employe,
                        'kehadiran_pc'=> $pc_employe,
                        'kehadiran_dtpc'=>$dtpc_employe,
                        'kehadiran_m'=>$m+$absen_TL,
                        'kehadiran_r'=>$absen_R,
                        'kehadiran_ok'=>$ok,
                        'total_kehadiran_net'=>$ok+$dt_employe+$pc_employe+$dtpc_employe+$LBY_employe+$IBY_employe,
                        'kehadiran_tk'=>$tk,
                        'total_kehadiran'=> $total_kh,
                        'jumlah_hari'=>$jumlah_hari,
                        'jumlah_hari_kerja'=>$jumlah_hari_kerja,
                        'kehadiran_dl'=>$kehadiran_dl,
                        'kehadiran_cb'=>$kehadiran_cb,
                        'kehadiran_cbd'=>$kehadiran_cbd,
                        'kehadiran_cg'=>$kehadiran_cg,
                        'kehadiran_ch'=>$kehadiran_ch,
                        'kehadiran_cm'=>$kehadiran_cm,
                        'kehadiran_cn'=>$kehadiran_cn,
                        'kehadiran_ct'=>$kehadiran_ct,
                        'kehadiran_ig'=>$kehadiran_ig,
                        'kehadiran_im'=>$kehadiran_im,
                        'kehadiran_ka'=>$kehadiran_ka,
                        'kehadiran_km'=>$kehadiran_km,
                        'kehadiran_kr'=>$kehadiran_kr,
                        'kehadiran_na'=>$kehadiran_na,
                        'kehadiran_pp'=>$kehadiran_pp,
                        'kehadiran_i'=>$kehadiran_i,
                        'kehadiran_lp'=>$kehadiran_lp,
                        'kehadiran_l'=>$kehadiran_l,
                        'kehadiran_tl'=>$kehadiran_tl,
                        'kehadiran_iks'=>$kehadiran_iks,
                        'kehadiran_s'=>$kehadiran_s,
                        'kehadiran_m_estimasi'=> $TL_estimasi+$M_estimasi,
                    ];
                    $count=RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->where('periode_umk',$periode_umk)->count();
                    if($count){
                        RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->where('periode_umk',$periode_umk)->update($y);
                    }
                    else{
                        RekapKehadiranKaryawan::create($y);
                    }
                }

                // rekap perhitungan kehadiran karyawan
                $data_kehadiran=RekapKehadiranKaryawan::selectRaw('uuid,kode_rekap_kehadiran,periode_payroll,periode_tahun,periode_bulan,enroll_id,nik,employee_name,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,join_date,tanggal_resign,status_aktif,status_staff,kehadiran_iby,kehadiran_itb,kehadiran_lby,kehadiran_lsm,kehadiran_dt,kehadiran_pc,kehadiran_dtpc,kehadiran_m,kehadiran_r,kehadiran_tk,kehadiran_ok,total_kehadiran,total_kehadiran_net,jumlah_hari,jumlah_hari_kerja,kehadiran_dl,kehadiran_cb,kehadiran_cbd,kehadiran_cg,kehadiran_ch,kehadiran_cm,kehadiran_cn,kehadiran_ct,kehadiran_ig,kehadiran_im,kehadiran_ka,kehadiran_km,kehadiran_kr,kehadiran_na,kehadiran_pp,kehadiran_i,kehadiran_lp,kehadiran_l,kehadiran_tl,kehadiran_iks,kehadiran_s,kehadiran_m_estimasi,operator,created_at,updated_at,deleted_at')->whereRaw('periode_bulan = "'.$bulan.'" and periode_tahun = "'.$tahun.'" and periode_umk = "'.$periode_umk.'"'.$inEnrollId.'')->groupby('enroll_id')->get();
                foreach ($data_kehadiran as $key => $value) {
                    $periode = $value->periode_payroll;
                    $enroll_id = $value->enroll_id;
                    $kode = str_replace(array('-', ' '), '', $periode) . str_pad($enroll_id, 5, '0', STR_PAD_LEFT);
                    $kode_rekap = date('Ymd', strtotime(substr($kode, 0, 8))) . date('Ymd', strtotime(substr($kode, 11, 8))) . substr($kode, 19);
                    $kode_grade=EmployeeAtribut::select('kode_grade')->where('enroll_id',$value->enroll_id)->pluck('kode_grade')[0];

                    if(isset(GradingSalary::select('salary_bulanan')->where('kode_grade',$kode_grade)->where('periode_umk',$periode_umk)->pluck('salary_bulanan')[0])){
                        $salary_bulanan=GradingSalary::select('salary_bulanan')->where('kode_grade',$kode_grade)->where('periode_umk',$periode_umk)->pluck('salary_bulanan')[0];
                    }else{
                        if($kode_grade=='A'){
                            $salary_bulanan=GradingSalary::select('salary_bulanan')->where('kode_grade','A')->orderBy('created_at','desc')->limit(1)->pluck('salary_bulanan')[0];
                        }
                        if($kode_grade=='B'){
                            $salary_bulanan=GradingSalary::select('salary_bulanan')->where('kode_grade','B')->orderBy('created_at','desc')->limit(1)->pluck('salary_bulanan')[0];
                        }
                        if($kode_grade=='C'){
                            $salary_bulanan=GradingSalary::select('salary_bulanan')->where('kode_grade','C')->orderBy('created_at','desc')->limit(1)->pluck('salary_bulanan')[0];
                        }
                        if($kode_grade=='G'){
                            $salary_bulanan=GradingSalary::select('salary_bulanan')->where('kode_grade','G')->orderBy('created_at','desc')->limit(1)->pluck('salary_bulanan')[0];
                        }
                    }
                    $hari_potongan=max($value->jumlah_hari_kerja-$value->total_kehadiran_net, 0);
                    $hari_potongan_security=max($value->kehadiran_m+$value->kehadiran_r+$value->kehadiran_itb, 0);
                    $hp='';
                    if($security->where('enroll_id',$value->enroll_id)->count()){
                        $jh=25;
                        $hp=$hari_potongan_security;
                        $jumlah_menit_kerja=420;
                    }else{
                        $jh=$jumlah_hari_total-$jumlah_hari_sabtu_minggu_total;
                        $hp=$hari_potongan;
                        $jumlah_menit_kerja=480;
                    }
                    $perhitungan=[
                        'uuid'=>Str::uuid('uuid'),
                        'periode_umk'=>$periode_umk,
                        'kode_rekap'=>$kode_rekap,
                        'periode_payroll'=>$value->periode_payroll,
                        'periode_tahun_bulan'=>$value->periode_tahun.'-'.$value->periode_bulan,
                        'enroll_id'=>$value->enroll_id,
                        'employee_name'=>$value->employee_name,
                        'kehadiran_iby'=>$value->kehadiran_iby,
                        'kehadiran_itb'=>$value->kehadiran_itb,
                        'kehadiran_lby'=>$value->kehadiran_lby,
                        'kehadiran_lsm'=>$value->kehadiran_lsm,
                        'kehadiran_dt'=>$value->kehadiran_dt,
                        'kehadiran_pc'=>$value->kehadiran_pc,
                        'kehadiran_dtpc'=>$value->kehadiran_dtpc,
                        'kehadiran_m'=>$value->kehadiran_m,
                        'kehadiran_r'=>$value->kehadiran_r,
                        'kehadiran_tk'=>$value->kehadiran_tk,
                        'kehadiran_ok'=>$value->kehadiran_ok,
                        'total_kehadiran'=>$value->total_kehadiran,
                        'total_kehadiran_net'=>$value->total_kehadiran_net,
                        'jumlah_hari'=>$value->jumlah_hari,
                        'jumlah_hari_kerja'=>$value->jumlah_hari_kerja,
                        'gaji_pokok'=>$salary_bulanan,
                        'gaji_harian'=>$salary_bulanan/$jh,
                        'gaji_menit'=>($salary_bulanan/$jh)/$jumlah_menit_kerja,
                        'potongan_kehadiran_rupiah'=>($salary_bulanan/$jh)*$hp,
                    ];
                    $count=RekapPerhitunganKehadiranKaryawan::where( 'kode_rekap',$kode_rekap)->where('periode_umk',$periode_umk)->count();
                    if($count){
                        RekapPerhitunganKehadiranKaryawan::where( 'kode_rekap',$kode_rekap)->where('periode_umk',$periode_umk)->update($perhitungan);
                    }
                    else{
                        RekapPerhitunganKehadiranKaryawan::create($perhitungan);
                    }
                }

                // rekap lembur
                $data_lembur=[];
                foreach ($a as $key => $value) {
                    $y=DataLembur::where('enroll_id',$value->enroll_id)->where('nomor_form_lembur',$value->nomor_form_lembur)->first();
                    if($value->kode_hari==5 || $value->kode_hari==6 || $value->status_absen=='LN'){
                        $kerjalibur='LIBUR';
                    }else{
                        $kerjalibur='KERJA';
                    }
                    $jumlah_jam_kerja=date_diff(date_create($value->mulai_jam_kerja),date_create($value->akhir_jam_kerja));
                    $jam_efektif_kerja=date_diff(date_create($value->absen_masuk_kerja),date_create($value->absen_pulang_kerja));
                    $spl_in=date('H:i:s', strtotime($value->mulai_jam_lembur));
                    $jam_in=$value->absen_masuk_kerja;
                    $spl_out=date('H:i:s', strtotime($value->akhir_jam_lembur));
                    $jam_out=$value->absen_pulang_kerja;
                    $jadwal_in=$value->mulai_jam_kerja;
                    $jadwal_out=$value->akhir_jam_kerja;
                    if($jadwal_in==null || $value->status_absen=='LN'){
                        $finish_in=max([$spl_in,$jam_in]);
                        $finish_out=min([$spl_out,$jam_out]);
                    }
                    elseif ( $spl_in<$jadwal_in) {
                        $finish_in=max([$spl_in,$jam_in]);
                        $finish_out=min([$spl_out,$jam_out]);
                    }
                    else{
                        $finish_in=max([$jadwal_out,$spl_in,$jam_in]);
                        $finish_out=min([$spl_out,$jam_out]);
                    }
                    $jam1 = strtotime($finish_in);
                    $jam2 = strtotime($finish_out);

                    // Jika $jam2 lebih kecil dari $jam1, tambahkan 1 hari (86400 detik)
                    if ($jam2 < $jam1) {
                        $jam2 += 86400;
                    }

                    $selisih_detik = max($jam2 - $jam1, 0);

                    $selisih_jam = floor($selisih_detik / 3600);
                    $selisih_detik %= 3600;

                    $selisih_menit = floor($selisih_detik / 60);
                    $selisih_detik %= 60;

                    $final_total_jam_lembur = sprintf("%02d:%02d:%02d", $selisih_jam, $selisih_menit, $selisih_detik);
                    $data_lembur[$key]=[
                        'tanggal_berjalan'=>$value->tanggal_berjalan,
                        'kode_hari'=>$value->kode_hari,
                        'nama_hari'=>$value->nama_hari,
                        'kerjalibur'=>$kerjalibur,
                        'holiday_name'=>$value->holiday_name,
                        'nomor_form_lembur'=>$value->nomor_form_lembur,
                        'enroll_id'=>$value->enroll_id,
                        'nik'=>$value->nik,
                        'employee_name'=>$value->employee_name,
                        'site_nirwana_id'=>$value->site_nirwana_id,
                        'site_nirwana_name'=>$value->site_nirwana_name,
                        'department_id'=>$value->department_id,
                        'department_name'=>$value->department_name,
                        'sub_dept_id'=>$value->sub_dept_id,
                        'sub_dept_name'=>$value->sub_dept_name,
                        'posisi_name'=>$value->posisi_name,
                        'mulai_jam_kerja'=>$value->mulai_jam_kerja,
                        'akhir_jam_kerja'=>$value->akhir_jam_kerja,
                        'jumlah_jam_kerja'=>sprintf('%02d:%02d:%02d', $jumlah_jam_kerja->h, $jumlah_jam_kerja->i, $jumlah_jam_kerja->s),
                        'absen_masuk_kerja'=>$value->absen_masuk_kerja,
                        'absen_pulang_kerja'=>$value->absen_pulang_kerja,
                        'jam_efektif_kerja'=>sprintf('%02d:%02d:%02d', $jam_efektif_kerja->h, $jam_efektif_kerja->i, $jam_efektif_kerja->s),
                        'mulai_jam_lembur'=>$value->mulai_jam_lembur,
                        'akhir_jam_lembur'=>$value->akhir_jam_lembur,
                        'final_mulai_jam_lembur'=>$finish_in,
                        'final_selesai_jam_lembur'=>$value->absen_pulang_kerja,
                        'final_total_jam_lembur'=>$final_total_jam_lembur,
                        'final_jam_istirahat_lembur'=>0,
                        'final_total_menit_lembur'=>($selisih_jam*60)+$selisih_menit,
                        'final_jam_lembur_roundown'=> $selisih_jam,
                        'final_menit_lembur_roundown'=>$selisih_menit,
                        'lembur_1'=>0,
                        'lembur_2'=>0,
                        'lembur_3'=>0,
                        'lembur_4'=>0,
                        'total_lembur_1234'=>0,
                        'salary'=>0,
                        'lembur1_rupiah'=>0,
                        'lembur2_rupiah'=> 0,
                        'lembur3_rupiah'=> 0,
                        'lembur4_rupiah'=> 0,
                        'total_lembur_rupiah'=> 0,
                        'operator'=>'system',
                        'jumlah_jam_istirahat_form'=>$y->jumlah_jam_istirahat??0,
                        'jumlah_jam_lembur_form'=>$y->jumlah_jam_lembur??0,
                        'status_absen'=>$value->status_absen
                    ];
                }
                foreach ($data_lembur as $key2 => $value2) {
                    if ($value2['final_menit_lembur_roundown'] <= 15) {
                        $konveri_jam = 0;
                    } elseif ($value2['final_menit_lembur_roundown'] > 15 && $value2['final_menit_lembur_roundown'] <= 45) {
                        $konveri_jam = 0.5;
                    } else {
                        $konveri_jam = 1;
                    }
                    $total_jam_lembur=$value2['final_jam_lembur_roundown'] + $konveri_jam;
                    $total_jam_lembur_finis=$total_jam_lembur-$value2['jumlah_jam_istirahat_form'];
                    $total_jam_lembur_finis=min($value2['jumlah_jam_lembur_form'],$total_jam_lembur_finis);
                    if($value2['kode_hari']==5 || $value2['kode_hari']==6 || $value2['status_absen']=='LN'){
                        $kerjalibur='LIBUR';
                        $l1=0;
                        $l2=($total_jam_lembur_finis <= 8) ? $total_jam_lembur_finis : 8;
                        if($total_jam_lembur_finis > 9){
                            $l3=1;
                            $l4=max($total_jam_lembur_finis -9, 0);
                        }
                        else if($total_jam_lembur_finis > 8 && $total_jam_lembur_finis <=9 ){
                            $l3=max($total_jam_lembur_finis -8, 0);
                            $l4=0;
                        }
                        else{
                            $l3=0;
                            $l4=0;
                        }
                    }
                    else{
                        $kerjalibur='KERJA';
                        $l1 = ($total_jam_lembur_finis <= 1) ? $total_jam_lembur_finis : 1;
                        $l2 = max($total_jam_lembur_finis - 1, 0);
                        $l3=0;
                        $l4=0;
                    }

                    $kode_grade=EmployeeAtribut::select('kode_grade')->where('enroll_id',$value2['enroll_id'])->pluck('kode_grade')[0];

                    if(isset(GradingSalary::select('salary_bulanan')->where('kode_grade',$kode_grade)->where('periode_umk',$periode_umk)->pluck('salary_bulanan')[0])){
                        $salary_bulanan=GradingSalary::select('salary_bulanan')->where('kode_grade',$kode_grade)->where('periode_umk',$periode_umk)->pluck('salary_bulanan')[0];
                    }else{
                        if($kode_grade=='A'){
                            $salary_bulanan=GradingSalary::select('salary_bulanan')->where('kode_grade','A')->orderBy('created_at','desc')->limit(1)->pluck('salary_bulanan')[0];
                        }
                        if($kode_grade=='B'){
                            $salary_bulanan=GradingSalary::select('salary_bulanan')->where('kode_grade','B')->orderBy('created_at','desc')->limit(1)->pluck('salary_bulanan')[0];
                        }
                        if($kode_grade=='C'){
                            $salary_bulanan=GradingSalary::select('salary_bulanan')->where('kode_grade','C')->orderBy('created_at','desc')->limit(1)->pluck('salary_bulanan')[0];
                        }
                        if($kode_grade=='G'){
                            $salary_bulanan=GradingSalary::select('salary_bulanan')->where('kode_grade','G')->orderBy('created_at','desc')->limit(1)->pluck('salary_bulanan')[0];
                        }
                    }
                    if($value2['kode_hari']==6 || $value2['status_absen']=='LN'){
                        $l1_rupiah=$l1*($salary_bulanan/173*1);
                        $l2_rupiah=$l2*($salary_bulanan/173*2);
                        $l3_rupiah=$l3*($salary_bulanan/173*2);
                        $l4_rupiah=$l4*($salary_bulanan/173*2);
                    }
                    else{
                        $l1_rupiah=$l1*($salary_bulanan/173*1);
                        $l2_rupiah=$l2*($salary_bulanan/173*1);
                        $l3_rupiah=$l3*($salary_bulanan/173*1);
                        $l4_rupiah=$l4*($salary_bulanan/173*1);
                    }
                    $record=[
                        'uuid'=>Str::uuid('uuid'),
                        'periode_umk'=>$periode_umk,
                        'tanggal_berjalan'=>$value2['tanggal_berjalan'],
                        'kode_hari'=>$value2['kode_hari'],
                        'nama_hari'=>$value2['nama_hari'],
                        'kerjalibur'=>$value2['kerjalibur'],
                        'holiday_name'=>$value2['holiday_name'],
                        'nomor_form_lembur'=>$value2['nomor_form_lembur'],
                        'enroll_id'=>$value2['enroll_id'],
                        'nik'=>$value2['nik'],
                        'employee_name'=>$value2['employee_name'],
                        'site_nirwana_id'=>$value2['site_nirwana_id'],
                        'site_nirwana_name'=>$value2['site_nirwana_name'],
                        'department_id'=>$value2['department_id'],
                        'department_name'=>$value2['department_name'],
                        'sub_dept_id'=>$value2['sub_dept_id'],
                        'sub_dept_name'=>$value2['sub_dept_name'],
                        'posisi_name'=>$value2['posisi_name'],
                        'mulai_jam_kerja'=>$value2['mulai_jam_kerja'],
                        'akhir_jam_kerja'=>$value2['akhir_jam_kerja'],
                        'jumlah_jam_kerja'=>$value2['jumlah_jam_kerja'],
                        'absen_masuk_kerja'=>$value2['absen_masuk_kerja'],
                        'absen_pulang_kerja'=>$value2['absen_pulang_kerja'],
                        'jam_efektif_kerja'=>$value2['jam_efektif_kerja'],
                        'mulai_jam_lembur'=>$value2['mulai_jam_lembur'],
                        'akhir_jam_lembur'=>$value2['akhir_jam_lembur'],
                        'final_mulai_jam_lembur'=>$value2['final_mulai_jam_lembur'],
                        'final_selesai_jam_lembur'=>$value2['final_selesai_jam_lembur'],
                        'final_total_jam_lembur'=>$value2['final_total_jam_lembur'],
                        'final_jam_istirahat_lembur'=>$value2['jumlah_jam_istirahat_form'],
                        'final_total_menit_lembur'=>$value2['final_total_menit_lembur'],
                        'final_jam_lembur_roundown'=> $value2['final_jam_lembur_roundown'],
                        'final_menit_lembur_roundown'=>$value2['final_menit_lembur_roundown'],
                        'lembur_1'=>$l1,
                        'lembur_2'=>$l2,
                        'lembur_3'=>$l3,
                        'lembur_4'=>$l4,
                        'total_lembur_1234'=>$total_jam_lembur_finis,
                        'salary'=>$salary_bulanan,
                        'lembur1_rupiah'=>$l1_rupiah,
                        'lembur2_rupiah'=> $l2_rupiah,
                        'lembur3_rupiah'=> $l3_rupiah,
                        'lembur4_rupiah'=> $l4_rupiah,
                        'total_lembur_rupiah'=> $l1_rupiah+$l2_rupiah+$l3_rupiah+$l4_rupiah,
                        'operator'=>'system',
                    ];
                    $count=RekapPerhitunganLembur::where('tanggal_berjalan',$value2['tanggal_berjalan'])->where('enroll_id',$value2['enroll_id'])->where('periode_umk',$periode_umk)->count();
                    if($count){
                        RekapPerhitunganLembur::where('tanggal_berjalan',$value2['tanggal_berjalan'])->where('enroll_id',$value2['enroll_id'])->where('periode_umk',$periode_umk)->update($record);
                    }
                    else{
                        RekapPerhitunganLembur::create($record);
                    }
                }

                // rekap iks
                foreach ($b as $key => $value){
                    if($value->kode_hari = 4){
                        $jam_mulai_istirahat='11:30';
                        $jam_selesai_istirahat='12:30';
                    }
                    else{
                        if($value->mulai_jam_kerja = '06:00' AND $value->akhir_jam_kerja = '15:00'){
                            $jam_mulai_istirahat='10:00';
                            $jam_selesai_istirahat='11:00';
                        }
                        elseif($value->mulai_jam_kerja = '16:00' AND $value->akhir_jam_kerja = '23:00'){
                            $jam_mulai_istirahat='18:00';
                            $jam_selesai_istirahat='19:00';
                        }
                        else{
                            $jam_mulai_istirahat='12:00';
                            $jam_selesai_istirahat='13:00';
                        }
                    }
                    $minutes = $value->total_menit_permits;
                    $seconds = $minutes * 60;
                    $time = gmdate("H:i:s", $seconds);
                    $salary=RekapPerhitunganKehadiranKaryawan::where('periode_payroll',$priode)->where('enroll_id',$value->enroll_id)->where('periode_umk',$periode_umk)->first();
                    $salary->gaji_pokok;

                    $data_iks=[
                        'uuid'=>Str::uuid('uuid'),
                        'periode_umk'=>$periode_umk,
                        'nomor_form_perizinan'=>$value->nomor_absen_ijin,
                        'tanggal_berjalan'=>$value->tanggal_berjalan,
                        'enroll_id'=>$value->enroll_id,
                        'employee_name'=>$value->employee_name,
                        'sub_dept_name'=>$value->sub_dept_name,
                        'time_mulai_ijin'=>$value->permits_dari_pukul,
                        'time_akhir_ijin'=>$value->permits_sampai_pukul,
                        'jam_mulai_istirahat'=>$jam_mulai_istirahat,
                        'jam_selesai_istirahat'=> $jam_selesai_istirahat,
                        'lama_istirahat_menit'=>60,
                        'lama_ijin_menit'=>$value->total_menit_permits,
                        'lama_ijin_jam'=>$time,
                        'absen_alasan'=>$value->absen_alasan,
                        'gaji_pokok'=> $salary->gaji_pokok,
                        'gaji_harian'=> $salary->gaji_harian,
                        'gaji_menit'=> $salary->gaji_menit,
                        'potongan_iks_rupiah'=>$salary->gaji_menit*$value->total_menit_permits,
                    ];
                    $count=RekapPerhitunganIKS::where('tanggal_berjalan',$value->tanggal_berjalan)->where('periode_umk',$periode_umk)->where('enroll_id',$value->enroll_id)->count();
                    if($count){
                        RekapPerhitunganIKS::where('tanggal_berjalan',$value->tanggal_berjalan)->where('periode_umk',$periode_umk)->where('enroll_id',$value->enroll_id)->update($data_iks);
                    }
                    else{
                        RekapPerhitunganIKS::create($data_iks);
                    }
                }

                // rekap dtpc
                foreach ($c as $key => $value) {
                    $salary=RekapPerhitunganKehadiranKaryawan::where('periode_payroll',$priode)->where('enroll_id',$value->enroll_id)->where('periode_umk',$periode_umk)->first();
                    $data_dtpc=[
                        'uuid'=>Str::uuid('uuid'),
                        'periode_umk'=>$periode_umk,
                        'tanggal_berjalan'=>$value->tanggal_berjalan,
                        'employee_id'=>$value->employee_id,
                        'enroll_id'=>$value->enroll_id,
                        'employee_name'=>$value->employee_name,
                        'mulai_jam_kerja'=>$value->mulai_jam_kerja,
                        'akhir_jam_kerja'=>$value->akhir_jam_kerja,
                        'absen_masuk_kerja'=>$value->absen_masuk_kerja,
                        'absen_pulang_kerja'=>$value->absen_pulang_kerja,
                        'status_absen'=>$value->status_absen,
                        'gaji_pokok'=> $salary->gaji_pokok,
                        'gaji_menit'=> $salary->gaji_menit,
                        'jumlah_menit_absen_dt'=>$value->jumlah_menit_absen_dt,
                        'jumlah_menit_absen_pc'=>$value->jumlah_menit_absen_pc,
                        'jumlah_menit_absen_dtpc'=>$value->jumlah_menit_absen_dtpc,
                        'potongan_dt_rupiah'=>$salary->gaji_menit * $value->jumlah_menit_absen_dt,
                        'potongan_pc_rupiah'=>$salary->gaji_menit * $value->jumlah_menit_absen_pc,
                        'potongan_dtpc_rupiah'=>$salary->gaji_menit * $value->jumlah_menit_absen_dtpc,
                        'jumlah_absen_menit_kerja'=>$value->jumlah_absen_menit_kerja,
                    ];
                    $count=RekapPerhitunganDTPC::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->count();
                    if($count){
                        RekapPerhitunganDTPC::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->update($data_dtpc);
                    }
                    else{
                        RekapPerhitunganDTPC::create($data_dtpc);
                    }
                }

                //rekap payroll
                $karyawan=EmployeeAtribut::selectRaw('employee_id,employee_name,jenis_kelamin,tempat_lahir,tanggal_lahir,golongan_darah,email,nomor_tlpn,agama,status_kawin,npwp,nomor_ktp,nomor_kk,ptkp,nama_sekolah_terakhir,pendidikan_terakhir,jurusan_pendidikan,nama_bank,nomor_rekening_bank,ibu_kandung,propinsi,kota_kab,kecamatan,kelurahan_desa,alamat_rumah,alamat_sementara,site_nirwana_id,site_nirwana_name,department_id,department_name,sub_dept_id,sub_dept_name,enroll_id,join_date,nik,status_aktif,status_jabatan,status_kontrak_tetap,status_staff,tanggal_resign,tunjangan,kode_grade,referensi,employee_name_atasan,status_aktif_bpjs_tk,tanggal_bpjs_ketenagakerjaan,nomor_bpjs_ketenagakerjaan,status_aktif_bpjs_ks,tanggal_bpjs_kesehatan,nomor_bpjs_kesehatan,pengalaman_bekerja,lokasi_file_cv,nama_kerabat,nomor_tlpn_kerabat,hubungan_kerabat,alamat_kerabat,tanggal_vaccine1,nama_vaksin1,tanggal_vaccine2,nama_vaksin2,tanggal_vaccine3,nama_vaksin3,golongan_sim,nomor_sim,tanggal_expire_sim,catatan,lokasi_foto,operator,tanggal_mulai_kontrak,tanggal_akhir_kontrak,catatan_kontrak,created_at,updated_at,deleted_at,shift_work_id,work_status,employee_status,posisi_name,hamlet,kode_pos,saudara_yang_bisa_dihubungi,allowance,pola_kerja')->whereRaw('((status_aktif="AKTIF" and join_date <= "'.$tanggal_akhir.'") or tanggal_resign>"'.$tanggal_awal.'")'.$inEnrollId.'')->get();
                $data=[];
                foreach ($karyawan as $key => $value) {
                    $rekap_kehadiran=RekapPerhitunganKehadiranKaryawan::where('enroll_id',$value->enroll_id)->where('periode_payroll',$priode)->where('periode_umk',$periode_umk)->first();
                    $rekap_lembur=RekapPerhitunganLembur::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->get();
                    $rekap_iks=RekapPerhitunganIKS::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->get();
                    $rekap_dtpc=RekapPerhitunganDTPC::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan','>=',$month_umk_first)->where('tanggal_berjalan','<=',$month_umk_last)->get();
                    $Bpjs=EmployeeBpjs::where('enroll_id',$value->enroll_id)->where('periode_kehadiran',$priode)->first();
                    $tunjangan=0;

                    if($rekap_kehadiran!=null){
                        list($periode_tahun_payroll, $periode_bulan_payroll) = explode("-", $rekap_kehadiran->periode_tahun_bulan);
                        $tanggal_masuk = $value->join_date;
                        // hitung selisih tahun antara tanggal masuk dan sekarang
                        $bpjs_tk_jkm_bruto_rupiah=$Bpjs->bpjs_tk_jkm_bruto_rupiah??0;
                        $bpjs_tk_jkm_neto_rupiah=$Bpjs->bpjs_tk_jkm_neto_rupiah??0;
                        $bpjs_tk_jkk_bruto_rupiah=$Bpjs->bpjs_tk_jkk_bruto_rupiah??0;
                        $bpjs_tk_jkk_neto_rupiah=$Bpjs->bpjs_tk_jkk_neto_rupiah??0;
                        $bpjs_tk_jht_bruto_rupiah=$Bpjs->bpjs_tk_jht_bruto_rupiah??0;
                        $bpjs_tk_jht_neto_rupiah=$Bpjs->bpjs_tk_jht_neto_rupiah??0;
                        $bpjs_tk_jpn_bruto_rupiah=$Bpjs->bpjs_tk_jpn_bruto_rupiah??0;
                        $bpjs_tk_jpn_neto_rupiah=$Bpjs->bpjs_tk_jpn_neto_rupiah??0;
                        $bpjs_ks_jkn_bruto_rupiah=$Bpjs->bpjs_ks_jkn_bruto_rupiah??0;
                        $bpjs_ks_jkn_neto_rupiah=$Bpjs->bpjs_ks_jkn_neto_rupiah??0;
                        $data[$key]=[
                            'kode_rekap_payroll'=>$rekap_kehadiran->kode_rekap.$rekap_kehadiran->periode_umk,
                            'periode_umk'=>$rekap_kehadiran->periode_umk,
                            'periode_kehadiran'=>$rekap_kehadiran->periode_payroll,
                            'periode_tahun_payroll'=>$periode_tahun_payroll,
                            'periode_bulan_payroll'=>$periode_bulan_payroll,
                            'enroll_id'=>$value->enroll_id,
                            'nik'=>$value->nik,
                            'kode_grade'=>$value->kode_grade,
                            'join_date'=>$value->join_date,
                            'employee_name'=>$value->employee_name,
                            'tanggal_resign'=>$value->tanggal_resign,
                            'ptkp'=>$value->ptkp,
                            'jabatan_karyawan'=>$value->status_jabatan,
                            'nama_bagian'=>$value->sub_dept_name??'',
                            'nama_department'=>$value->department_name??'',
                            'kategori_karyawan'=>$value->status_staff,
                            'aktif_karyawan'=>$value->status_aktif,
                            'jenis_kelamin'=>$value->jenis_kelamin,
                            'status_kawin'=>$value->status_kawin,
                            'site_nirwana_name'=>$value->site_nirwana_name,
                            'nama_bank'=>$value->nama_bank==null||$value->nama_bank==""||$value->nama_bank=="-"?'TUNAI':$value->nama_bank,
                            'nomor_rekening_bank'=>$value->nomor_rekening_bank==null||$value->nomor_rekening_bank==""||$value->nomor_rekening_bank=="-"?'-':$value->nomor_rekening_bank,
                            'npwp'=>$value->npwp,
                            'operator'=>'sistem',
                            'sub_dept_id'=>$value->sub_dept_id,
                            'tunjangan_karyawan_rupiah'=>$tunjangan,
                            'premi_karyawan'=>0,
                            'kehadiran_iby'=>$rekap_kehadiran->kehadiran_iby,
                            'kehadiran_itb'=>$rekap_kehadiran->kehadiran_itb,
                            'kehadiran_m'=>$rekap_kehadiran->kehadiran_m,
                            'kehadiran_dt'=>$rekap_kehadiran->kehadiran_dt,
                            'kehadiran_pc'=>$rekap_kehadiran->kehadiran_pc,
                            'kehadiran_dtpc'=>$rekap_kehadiran->kehadiran_dtpc,
                            'kehadiran_lby'=>$rekap_kehadiran->kehadiran_lby,
                            'kehadiran_lsm'=>$rekap_kehadiran->kehadiran_lsm,
                            'kehadiran_r'=>$rekap_kehadiran->kehadiran_r,
                            'kehadiran_ok'=>$rekap_kehadiran->kehadiran_ok,
                            'kehadiran_tk'=>$rekap_kehadiran->kehadiran_tk,
                            'total_kehadiran'=>$rekap_kehadiran->total_kehadiran,
                            'total_kehadiran_net'=>$rekap_kehadiran->total_kehadiran_net,
                            'upah_per_bulan'=>$rekap_kehadiran->gaji_pokok,
                            'upah_per_hari'=>$rekap_kehadiran->gaji_harian,
                            'jumlah_hari_kerja'=>$rekap_kehadiran->jumlah_hari_kerja,
                            'upah_per_menit'=>$rekap_kehadiran->gaji_menit,
                            'potongan_kehadiran_rupiah'=>$rekap_kehadiran->potongan_kehadiran_rupiah,
                            'kehadiran_m_estimasi'=>$rekap_kehadiran->kehadiran_m_estimasi,
                            'lembur_1'=>$rekap_lembur->sum('lembur_1')??0,
                            'lembur_2'=>$rekap_lembur->sum('lembur_2')??0,
                            'lembur_3'=>$rekap_lembur->sum('lembur_3')??0,
                            'lembur_4'=>$rekap_lembur->sum('lembur_4')??0,
                            'total_lembur_1234'=>$rekap_lembur->sum('total_lembur_1234')??0,
                            'lembur1_rupiah'=>$rekap_lembur->sum('lembur1_rupiah')??0,
                            'lembur2_rupiah'=>$rekap_lembur->sum('lembur2_rupiah')??0,
                            'lembur3_rupiah'=>$rekap_lembur->sum('lembur3_rupiah')??0,
                            'lembur4_rupiah'=>$rekap_lembur->sum('lembur4_rupiah')??0,
                            'total_lembur_rupiah'=>$rekap_lembur->sum('total_lembur_rupiah')??0,
                            'pendapatan_lainnya_rupiah'=>0,
                            'koreksi_upah_rupiah'=>0,
                            'koreksi_potongan_rupiah'=>0,
                            'potongan_iks_menit'=>$rekap_iks->sum('lama_ijin_menit')??0,
                            'potongan_iks_rupiah'=>$rekap_iks->sum('potongan_iks_rupiah')??0,
                            'potongan_dt_menit'=>$rekap_dtpc->sum('jumlah_menit_absen_dt')??0,
                            'potongan_pc_menit'=>$rekap_dtpc->sum('jumlah_menit_absen_pc')??0,
                            'potongan_dtpc_menit'=>$rekap_dtpc->sum('jumlah_menit_absen_dtpc')??0,
                            'potongan_dt_rupiah'=>$rekap_dtpc->sum('potongan_dt_rupiah')??0,
                            'potongan_pc_rupiah'=>$rekap_dtpc->sum('potongan_pc_rupiah')??0,
                            'potongan_dtpc_rupiah'=>$rekap_dtpc->sum('potongan_dtpc_rupiah')??0,
                            'pph21'=>0, // dari mana?
                            'iuran_serikat_rupiah'=>0, // dari mana?
                            'iuran_koperasi'=>0, // dari mana?
                            'potongan_kasbon_rupiah'=>0, // dari mana?
                            'bpjs_tk_jkm_rupiah'=> $bpjs_tk_jkm_bruto_rupiah + $bpjs_tk_jkm_neto_rupiah,
                            'bpjs_tk_jkm_perusahaan_rupiah'=> $bpjs_tk_jkm_bruto_rupiah,
                            'bpjs_tk_jkm_karyawan_rupiah'=> $bpjs_tk_jkm_neto_rupiah,
                            'bpjs_tk_jkk_rupiah'=> $bpjs_tk_jkk_bruto_rupiah + $bpjs_tk_jkk_neto_rupiah,
                            'bpjs_tk_jkk_perusahaan_rupiah'=> $bpjs_tk_jkk_bruto_rupiah,
                            'bpjs_tk_jkk_karyawan_rupiah'=> $bpjs_tk_jkk_neto_rupiah,
                            'bpjs_tk_jht_rupiah'=> $bpjs_tk_jht_bruto_rupiah + $bpjs_tk_jht_neto_rupiah,
                            'bpjs_tk_jht_perusahaan_rupiah'=> $bpjs_tk_jht_bruto_rupiah,
                            'bpjs_tk_jht_karyawan_rupiah'=> $bpjs_tk_jht_neto_rupiah,
                            'bpjs_tk_jpn_rupiah'=> $bpjs_tk_jpn_bruto_rupiah + $bpjs_tk_jpn_neto_rupiah,
                            'bpjs_tk_jpn_perusahaan_rupiah'=> $bpjs_tk_jpn_bruto_rupiah,
                            'bpjs_tk_jpn_karyawan_rupiah'=> $bpjs_tk_jpn_neto_rupiah,
                            'bpjs_ks_jkn_rupiah'=> $bpjs_ks_jkn_bruto_rupiah + $bpjs_ks_jkn_neto_rupiah,
                            'bpjs_ks_jkn_perusahaan_rupiah'=> $bpjs_ks_jkn_bruto_rupiah,
                            'bpjs_ks_jkn_karyawan_rupiah'=> $bpjs_ks_jkn_neto_rupiah,
                            'total_bpjs_tk'=>$bpjs_tk_jkm_neto_rupiah + $bpjs_tk_jkk_neto_rupiah + $bpjs_tk_jht_neto_rupiah + $bpjs_tk_jpn_neto_rupiah,
                            'total_bpjs_ks'=>$bpjs_ks_jkn_neto_rupiah,
                        ];
                    }
                }
                foreach ($data as $k => $v) {
                    $upah_per_bulan=$v['upah_per_hari']*$v['jumlah_hari_kerja'];
                    $upah_bruto_rupiah=($upah_per_bulan+$v['tunjangan_karyawan_rupiah']+$v['premi_karyawan']+$v['total_lembur_rupiah']+$v['pendapatan_lainnya_rupiah']+$v['koreksi_upah_rupiah'])-($v['koreksi_potongan_rupiah']+$v['potongan_iks_rupiah']+$v['potongan_dtpc_rupiah']+$v['potongan_kehadiran_rupiah']);
                    $upah_neto_rupiah=($upah_per_bulan+$v['tunjangan_karyawan_rupiah']+$v['premi_karyawan']+$v['total_lembur_rupiah']+$v['pendapatan_lainnya_rupiah']+$v['koreksi_upah_rupiah'])-($v['koreksi_potongan_rupiah']+$v['potongan_iks_rupiah']+$v['potongan_dtpc_rupiah']+$v['potongan_kehadiran_rupiah'])-$v['pph21'];
                    $upah_bersih_rupiah=$upah_neto_rupiah;
                    $total_upah_thp_rupiah=$upah_neto_rupiah;
                    $jumlah_potongan_rupiah=0;

                    $records=[
                        'kode_rekap_payroll'=>$v['kode_rekap_payroll'],
                        'periode_umk'=>$v['periode_umk'],
                        'periode_kehadiran'=>$v['periode_kehadiran'],
                        'periode_tahun_payroll'=>$v['periode_tahun_payroll'],
                        'periode_bulan_payroll'=>$v['periode_bulan_payroll'],
                        'enroll_id'=>$v['enroll_id'],
                        'nik'=>$v['nik'],
                        'kode_grade'=>$v['kode_grade'],
                        'join_date'=>$v['join_date'],

                        'site_nirwana_name'=>$v['site_nirwana_name'],
                        'employee_name'=>$v['employee_name'],
                        'tanggal_resign'=>$v['tanggal_resign'],
                        'kehadiran_iby'=>$v['kehadiran_iby'],
                        'kehadiran_itb'=>$v['kehadiran_itb'],
                        'kehadiran_m'=>$v['kehadiran_m'],
                        'kehadiran_dt'=>$v['kehadiran_dt'],
                        'kehadiran_pc'=>$v['kehadiran_pc'],
                        'kehadiran_dtpc'=>$v['kehadiran_dtpc'],
                        'kehadiran_lby'=>$v['kehadiran_lby'],
                        'kehadiran_lsm'=>$v['kehadiran_lsm'],
                        'kehadiran_r'=>$v['kehadiran_r'],
                        'kehadiran_ok'=>$v['kehadiran_ok'],
                        'kehadiran_tk'=>$v['kehadiran_tk'],
                        'total_kehadiran'=>$v['total_kehadiran'],
                        'total_kehadiran_net'=>$v['total_kehadiran_net'],
                        'ptkp'=>$v['ptkp'],
                        'upah_per_bulan'=>$v['upah_per_bulan'],
                        'upah_per_hari'=>$v['upah_per_hari'],
                        'upah_per_menit'=>$v['upah_per_menit'],
                        'kehadiran_m_estimasi'=>$v['kehadiran_m_estimasi'],

                        'tunjangan_karyawan_rupiah'=>$v['tunjangan_karyawan_rupiah'],
                        'premi_karyawan'=>$v['premi_karyawan'],

                        'lembur_1'=>$v['lembur_1'],
                        'lembur_2'=>$v['lembur_2'],
                        'lembur_3'=>$v['lembur_3'],
                        'lembur_4'=>$v['lembur_4'],
                        'total_lembur_1234'=>$v['total_lembur_1234'],
                        'lembur1_rupiah'=>$v['lembur1_rupiah'],
                        'lembur2_rupiah'=>$v['lembur2_rupiah'],
                        'lembur3_rupiah'=>$v['lembur3_rupiah'],
                        'lembur4_rupiah'=>$v['lembur4_rupiah'],
                        'total_lembur_rupiah'=>$v['total_lembur_rupiah'],

                        'pendapatan_lainnya_rupiah'=>$v['pendapatan_lainnya_rupiah'],

                        'koreksi_upah_rupiah'=>$v['koreksi_upah_rupiah'],
                        'koreksi_potongan_rupiah'=>$v['koreksi_potongan_rupiah'],

                        'potongan_iks_menit'=>$v['potongan_iks_menit'],
                        'potongan_dt_menit'=>$v['potongan_dt_menit'],
                        'potongan_pc_menit'=>$v['potongan_pc_menit'],
                        'potongan_dtpc_menit'=>$v['potongan_dtpc_menit'],
                        'potongan_iks_rupiah'=>$v['potongan_iks_rupiah'],
                        'potongan_dt_rupiah'=>$v['potongan_dt_rupiah'],
                        'potongan_pc_rupiah'=>$v['potongan_pc_rupiah'],
                        'potongan_dtpc_rupiah'=>$v['potongan_dtpc_rupiah'],
                        'potongan_kehadiran_rupiah'=>$v['potongan_kehadiran_rupiah'],

                        'upah_bruto_rupiah'=>$upah_bruto_rupiah??0,
                        'upah_neto_rupiah'=>$upah_neto_rupiah??0,
                        'upah_bersih_rupiah'=>$upah_bersih_rupiah??0,
                        'total_upah_thp_rupiah'=>$total_upah_thp_rupiah??0,
                        'jumlah_potongan_rupiah'=>$jumlah_potongan_rupiah??0,

                        'pph21'=>$v['pph21'], // dari mana?
                        'iuran_serikat_rupiah'=>$v['iuran_serikat_rupiah'], // dari mana?
                        'iuran_koperasi'=>$v['iuran_koperasi'], // dari mana?
                        'potongan_kasbon_rupiah'=>$v['potongan_kasbon_rupiah'], // dari mana?
                        'bpjs_tk_jkm_rupiah'=> 0,
                        'bpjs_tk_jkm_perusahaan_rupiah'=> 0,
                        'bpjs_tk_jkm_karyawan_rupiah'=> 0,
                        'bpjs_tk_jkk_rupiah'=> 0,
                        'bpjs_tk_jkk_perusahaan_rupiah'=> 0,
                        'bpjs_tk_jkk_karyawan_rupiah'=> 0,
                        'bpjs_tk_jht_rupiah'=> 0,
                        'bpjs_tk_jht_perusahaan_rupiah'=> 0,
                        'bpjs_tk_jht_karyawan_rupiah'=> 0,
                        'bpjs_tk_jpn_rupiah'=> 0,
                        'bpjs_tk_jpn_perusahaan_rupiah'=> 0,
                        'bpjs_tk_jpn_karyawan_rupiah'=> 0,
                        'bpjs_ks_jkn_rupiah'=> 0,
                        'bpjs_ks_jkn_perusahaan_rupiah'=> 0,
                        'bpjs_ks_jkn_karyawan_rupiah'=> 0,
                        'total_bpjs_tk'=>0,
                        'total_bpjs_ks'=>0,
                        'status_kawin'=>$v['status_kawin'],
                        'jabatan_karyawan'=>$v['jabatan_karyawan'],
                        'nama_bagian'=>$v['nama_bagian'],
                        'nama_department'=>$v['nama_department'],
                        'kategori_karyawan'=>$v['kategori_karyawan'],
                        'aktif_karyawan'=>$v['aktif_karyawan'],
                        'jenis_kelamin'=>$v['jenis_kelamin'],
                        'nama_bank'=>$v['nama_bank'],
                        'nomor_rekening_bank'=>$v['nomor_rekening_bank'],
                        'npwp'=>$v['npwp'],
                        'operator'=>$v['operator'],
                        'sub_dept_id'=>$v['sub_dept_id'],
                        'total_upah_thp_rupiah_employee'=>ceil($total_upah_thp_rupiah / 100) * 100
                    ];
                    $count=RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$v['kode_rekap_payroll'])->first();
                    if($count){
                        RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$v['kode_rekap_payroll'])->update($records);
                    }
                    else{
                        RekapPerhitunganPayroll::create($records);
                    }
                }

                //update tanggal resign
                $rekap=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('periode_umk',$periode_umk)->where('total_kehadiran_net','0')->get();
                foreach ($rekap as $key => $value) {
                    $karyawan=EmployeeAtribut::where('enroll_id',$value->enroll_id)->first();
                    $data=['tanggal_resign'=>$karyawan->tanggal_resign];
                    RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$value->kode_rekap_payroll)->update($data);
                }

                //pembulatan
                $rekap=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('periode_umk',$periode_umk)->get();
                foreach ($rekap as $key => $value) {
                    $total_upah_thp_rupiah_pembulatan= ceil($value->total_upah_thp_rupiah / 100) * 100;
                    $pembulatan=$total_upah_thp_rupiah_pembulatan-$value->total_upah_thp_rupiah;
                    $data=['pembulatan'=>$pembulatan,];
                    RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$value->kode_rekap_payroll)->update($data);
                }
            }else{
                $queryEmpAtr =  EmployeeAtribut::selectRaw('uuid() uuid,
                        concat(SUBSTR(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ), 1, 4),
                        SUBSTR(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ), 6, 2), lpad(enroll_id, 5, 0)) kode_bpjs,
                        substr("' . $explodePeriodePayroll[1] . '", 1, 4) periode_bpjs,
                        CONCAT(DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 2 MONTH )), INTERVAL 26 DAY ), " s/d ",
                        DATE_ADD( LAST_DAY( DATE_SUB( MAX("' . $explodePeriodePayroll[1] . '"), INTERVAL 1 MONTH )), INTERVAL 25 DAY ))  periode_kehadiran,
                        enroll_id, nik, employee_name, site_nirwana_id, department_id, sub_dept_id,
                        status_aktif_bpjs_tk, tanggal_bpjs_ketenagakerjaan, nomor_bpjs_ketenagakerjaan,
                        status_aktif_bpjs_ks, tanggal_bpjs_kesehatan, nomor_bpjs_kesehatan, join_date
                        ')
                        ->whereRaw('
                        enroll_id is not null
                        AND (tanggal_resign is null OR tanggal_resign = "0000-00-00" OR
                            NOT tanggal_resign < DATE_ADD( LAST_DAY( DATE_SUB( "' . $explodePeriodePayroll[1] . '", INTERVAL 2 MONTH )), INTERVAL 26 DAY ))
                        AND join_date <= "' . $explodePeriodePayroll[1] . '"
                        ')
                        ->groupBy('enroll_id')
                        ->groupBy('employee_name')
                        ->get();

                foreach ($queryEmpAtr as $key => $value) {

                    $tanggal_masuk = $value['join_date'];

                    // hitung selisih tahun antara tanggal masuk dan sekarang
                    $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_awal))->y;

                    // tentukan besaran tunjangan berdasarkan masa kerja
                    if ($selisih_tahun < 1) {
                        $tunjangan = 0;
                    } elseif ($selisih_tahun < 3) {
                        $tunjangan = 2500;
                    } elseif ($selisih_tahun < 6) {
                        $tunjangan = 5000;
                    }elseif ($selisih_tahun < 9) {
                        $tunjangan = 7500;
                    }elseif ($selisih_tahun < 12) {
                        $tunjangan = 10000;
                    }else{
                        $tunjangan = 12500;
                    }

                    $countEmp = EmployeeBpjs::whereRaw('kode_bpjs = ' . $sqlKodePeriodeBPJS . ' AND enroll_id = "' . $value['enroll_id'] . '"')->count();

                    if ($countEmp) {
                        $queryEmpBpjs = EmployeeBpjs::whereRaw('kode_bpjs = ' . $sqlKodePeriodeBPJS . ' AND enroll_id = "' . $value['enroll_id'] . '"')
                        ->update([
                            'status_aktif_bpjs_tk' => $value['status_aktif_bpjs_tk'],
                            'tanggal_bpjs_ketenagakerjaan' => $value['tanggal_bpjs_ketenagakerjaan'],
                            'nomor_bpjs_ketenagakerjaan' => $value['nomor_bpjs_ketenagakerjaan'],
                            'status_aktif_bpjs_ks' => $value['status_aktif_bpjs_ks'],
                            'tanggal_bpjs_kesehatan' => $value['tanggal_bpjs_kesehatan'],
                            'nomor_bpjs_kesehatan' => $value['nomor_bpjs_kesehatan'],
                            'kode_periode_bpjs' => null,
                            'kode_dasar_pot_bpjs' => null,
                            'dasar_pot_bpjs_rupiah' => 0,
                            'bpjs_tk_jkm_bruto_rupiah' => 0,
                            'bpjs_tk_jkk_bruto_rupiah' => 0,
                            'bpjs_ks_jkn_bruto_rupiah' => 0,
                            'bpjs_tk_jkm_neto_rupiah' => 0,
                            'bpjs_tk_jkk_neto_rupiah' => 0,
                            'bpjs_tk_jht_neto_rupiah' => 0,
                            'bpjs_tk_jpn_neto_rupiah' => 0,
                            'bpjs_ks_jkn_neto_rupiah' => 0,
                            'bpjs_tk_jkm_persen' => 0,
                            'bpjs_tk_jkk_persen' => 0,
                            'bpjs_tk_jht_persen' => 0,
                            'bpjs_tk_jpn_persen' => 0,
                            'bpjs_ks_jkn_persen' => 0,
                            'bpjs_tk_jkm_bruto_persen' => 0,
                            'bpjs_tk_jkk_bruto_persen' => 0,
                            'bpjs_tk_jht_bruto_persen' => 0,
                            'bpjs_tk_jpn_bruto_persen' => 0,
                            'bpjs_ks_jkn_bruto_persen' => 0,
                            'bpjs_tk_jkm_neto_persen' => 0,
                            'bpjs_tk_jkk_neto_persen' => 0,
                            'bpjs_tk_jht_neto_persen' => 0,
                            'bpjs_tk_jpn_neto_persen' => 0,
                            'bpjs_ks_jkn_neto_persen' => 0,
                            'tmk'=>$tunjangan,
                        ]);
                    } else {
                        EmployeeBpjs::create([
                            'uuid' => Str::uuid(),
                            'kode_bpjs' => $value['kode_bpjs'],
                            'periode_bpjs' => $value['periode_bpjs'],
                            'periode_kehadiran' => $value['periode_kehadiran'],
                            'enroll_id' => $value['enroll_id'],
                            'nik' => $value['nik'],
                            'employee_name' => $value['employee_name'],
                            'status_aktif_bpjs_tk' => $value['status_aktif_bpjs_tk'],
                            'tanggal_bpjs_ketenagakerjaan' => $value['tanggal_bpjs_ketenagakerjaan'],
                            'nomor_bpjs_ketenagakerjaan' => $value['nomor_bpjs_ketenagakerjaan'],
                            'status_aktif_bpjs_ks' => $value['status_aktif_bpjs_ks'],
                            'tanggal_bpjs_kesehatan' => $value['tanggal_bpjs_kesehatan'],
                            'nomor_bpjs_kesehatan' => $value['nomor_bpjs_kesehatan'],
                            'kode_periode_bpjs' => null,
                            'kode_dasar_pot_bpjs' => null,
                            'dasar_pot_bpjs_rupiah' => 0,
                            'bpjs_tk_jkm_bruto_rupiah' => 0,
                            'bpjs_tk_jkk_bruto_rupiah' => 0,
                            'bpjs_ks_jkn_bruto_rupiah' => 0,
                            'bpjs_tk_jkm_neto_rupiah' => 0,
                            'bpjs_tk_jkk_neto_rupiah' => 0,
                            'bpjs_tk_jht_neto_rupiah' => 0,
                            'bpjs_tk_jpn_neto_rupiah' => 0,
                            'bpjs_ks_jkn_neto_rupiah' => 0,
                            'bpjs_tk_jkm_persen' => 0,
                            'bpjs_tk_jkk_persen' => 0,
                            'bpjs_tk_jht_persen' => 0,
                            'bpjs_tk_jpn_persen' => 0,
                            'bpjs_ks_jkn_persen' => 0,
                            'bpjs_tk_jkm_bruto_persen' => 0,
                            'bpjs_tk_jkk_bruto_persen' => 0,
                            'bpjs_tk_jht_bruto_persen' => 0,
                            'bpjs_tk_jpn_bruto_persen' => 0,
                            'bpjs_ks_jkn_bruto_persen' => 0,
                            'bpjs_tk_jkm_neto_persen' => 0,
                            'bpjs_tk_jkk_neto_persen' => 0,
                            'bpjs_tk_jht_neto_persen' => 0,
                            'bpjs_tk_jpn_neto_persen' => 0,
                            'bpjs_ks_jkn_neto_persen' => 0,
                            'tmk'=>$tunjangan,
                        ]);
                    }

                }
                $query =  BpjsSetting::whereRaw(' substr(kode_periode_bpjs, 1, 4) = substr("' . $kode_bpjs->kode_periode_bpjs . '", 1, 4)')
                        ->orderBy('kode_periode_bpjs','desc')
                        ->limit(1)
                        ->get();

                $kode_periode_bpjs = $query[0]->kode_periode_bpjs;
                $kode_dasar_pot_bpjs = $query[0]->kode_dasar_pot_bpjs;
                $dasar_pot_bpjs_rupiah_gapok = $query[0]->dasar_pot_bpjs_rupiah;
                $bpjs_tk_jkm_persen = $query[0]->bpjs_tk_jkm_persen;
                $bpjs_tk_jkm_perusahaan_persen = $query[0]->bpjs_tk_jkm_perusahaan_persen;
                $bpjs_tk_jkm_karyawan_persen = $query[0]->bpjs_tk_jkm_karyawan_persen;
                $bpjs_tk_jkk_persen = $query[0]->bpjs_tk_jkk_persen;
                $bpjs_tk_jkk_perusahaan_persen = $query[0]->bpjs_tk_jkk_perusahaan_persen;
                $bpjs_tk_jkk_karyawan_persen = $query[0]->bpjs_tk_jkk_karyawan_persen;
                $bpjs_tk_jht_persen = $query[0]->bpjs_tk_jht_persen;
                $bpjs_tk_jht_perusahaan_persen = $query[0]->bpjs_tk_jht_perusahaan_persen;
                $bpjs_tk_jht_karyawan_persen = $query[0]->bpjs_tk_jht_karyawan_persen;
                $bpjs_tk_jpn_persen = $query[0]->bpjs_tk_jpn_persen;
                $bpjs_tk_jpn_perusahaan_persen = $query[0]->bpjs_tk_jpn_perusahaan_persen;
                $bpjs_tk_jpn_karyawan_persen = $query[0]->bpjs_tk_jpn_karyawan_persen;
                $bpjs_ks_jkn_persen = $query[0]->bpjs_ks_jkn_persen;
                $bpjs_ks_jkn_perusahaan_persen = $query[0]->bpjs_ks_jkn_perusahaan_persen;
                $bpjs_ks_jkn_karyawan_persen = $query[0]->bpjs_ks_jkn_karyawan_persen;

                $EmpBpjs = EmployeeBpjs::where('periode_kehadiran',$periode_payroll_bpjs)->get();

                foreach ($EmpBpjs as $key3 => $value3) {
                    $dasar_pot_bpjs_rupiah=$dasar_pot_bpjs_rupiah_gapok+$value3->tmk;
                    // dd($dasar_pot_bpjs_rupiah);
                    $queryEmpBpjs = DB::update('update employee_bpjs set
                        kode_periode_bpjs = "' . $kode_periode_bpjs . '",
                        kode_dasar_pot_bpjs = "' . $kode_dasar_pot_bpjs . '",
                        dasar_pot_bpjs_rupiah = "' . $dasar_pot_bpjs_rupiah . '",
                        bpjs_tk_jkm_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkm_perusahaan_persen . '/100)), 0),
                        bpjs_tk_jkk_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkk_perusahaan_persen . '/100)), 0),
                        bpjs_tk_jht_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jht_perusahaan_persen . '/100)), 0),
                        bpjs_tk_jpn_bruto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jpn_perusahaan_persen . '/100)), 0),
                        bpjs_ks_jkn_bruto_rupiah = IF(status_aktif_bpjs_ks = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_ks_jkn_perusahaan_persen . '/100)), 0),
                        bpjs_tk_jkm_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkm_karyawan_persen . '/100)), 0),
                        bpjs_tk_jkk_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jkk_karyawan_persen . '/100)), 0),
                        bpjs_tk_jht_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jht_karyawan_persen . '/100)), 0),
                        bpjs_tk_jpn_neto_rupiah = IF(status_aktif_bpjs_tk = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_tk_jpn_karyawan_persen . '/100)), 0),
                        bpjs_ks_jkn_neto_rupiah = IF(status_aktif_bpjs_ks = "AKTIF", (' . $dasar_pot_bpjs_rupiah . ' * (' . $bpjs_ks_jkn_karyawan_persen . '/100)), 0),
                        bpjs_tk_jkm_persen = "' . $bpjs_tk_jkm_persen . '",
                        bpjs_tk_jkk_persen = "' . $bpjs_tk_jkk_persen . '",
                        bpjs_tk_jht_persen = "' . $bpjs_tk_jht_persen . '",
                        bpjs_tk_jpn_persen = "' . $bpjs_tk_jpn_persen . '",
                        bpjs_ks_jkn_persen = "' . $bpjs_ks_jkn_persen . '",
                        bpjs_tk_jkm_bruto_persen = "' . $bpjs_tk_jkm_perusahaan_persen . '",
                        bpjs_tk_jkk_bruto_persen = "' . $bpjs_tk_jkk_perusahaan_persen . '",
                        bpjs_tk_jht_bruto_persen = "' . $bpjs_tk_jht_perusahaan_persen . '",
                        bpjs_tk_jpn_bruto_persen = "' . $bpjs_tk_jpn_perusahaan_persen . '",
                        bpjs_ks_jkn_bruto_persen = "' . $bpjs_ks_jkn_perusahaan_persen . '",
                        bpjs_tk_jkm_neto_persen = "' . $bpjs_tk_jkm_karyawan_persen . '",
                        bpjs_tk_jkk_neto_persen = "' . $bpjs_tk_jkk_karyawan_persen . '",
                        bpjs_tk_jht_neto_persen = "' . $bpjs_tk_jht_karyawan_persen . '",
                        bpjs_tk_jpn_neto_persen = "' . $bpjs_tk_jpn_karyawan_persen . '",
                        bpjs_ks_jkn_neto_persen = "' . $bpjs_ks_jkn_karyawan_persen . '",
                        operator = "' . $email . '"
                    where enroll_id = "'. $value3->enroll_id .'"');
                }

                $rekap_payroll=RekapPerhitunganPayroll::selectRaw('kode_rekap_payroll,periode_umk,periode_kehadiran,periode_tahun_payroll,periode_bulan_payroll,enroll_id,nik,kode_grade,employee_name,tanggal_resign,kehadiran_iby,kehadiran_itb,kehadiran_m,kehadiran_dt,kehadiran_pc,kehadiran_dtpc,kehadiran_lby,kehadiran_lsm,kehadiran_r,kehadiran_ok,kehadiran_tk,total_kehadiran,total_kehadiran_net,ptkp,upah_per_bulan,upah_per_hari,upah_per_menit,tunjangan_karyawan_rupiah,premi_karyawan,lembur_1,lembur_2,lembur_3,lembur_4,total_lembur_1234,lembur1_rupiah,lembur2_rupiah,lembur3_rupiah,lembur4_rupiah,total_lembur_rupiah,pendapatan_lainnya_rupiah,koreksi_upah_rupiah,koreksi_potongan_rupiah,potongan_iks_menit,potongan_dt_menit,potongan_pc_menit,potongan_dtpc_menit,potongan_iks_rupiah,potongan_dt_rupiah,potongan_pc_rupiah,potongan_dtpc_rupiah,potongan_kehadiran_rupiah,upah_bruto_rupiah,pph21,upah_neto_rupiah,total_bpjs_tk,total_bpjs_ks,iuran_serikat_rupiah,iuran_koperasi,jumlah_potongan_rupiah,upah_bersih_rupiah,potongan_kasbon_rupiah,total_upah_thp_rupiah,bpjs_tk_jkm_rupiah,bpjs_tk_jkm_perusahaan_rupiah,bpjs_tk_jkm_karyawan_rupiah,bpjs_tk_jkk_rupiah,bpjs_tk_jkk_perusahaan_rupiah,bpjs_tk_jkk_karyawan_rupiah,bpjs_tk_jht_rupiah,bpjs_tk_jht_perusahaan_rupiah,bpjs_tk_jht_karyawan_rupiah,bpjs_tk_jpn_rupiah,bpjs_tk_jpn_perusahaan_rupiah,bpjs_tk_jpn_karyawan_rupiah,bpjs_ks_jkn_rupiah,bpjs_ks_jkn_perusahaan_rupiah,bpjs_ks_jkn_karyawan_rupiah,jabatan_karyawan,nama_bagian,nama_department,kategori_karyawan,aktif_karyawan,jenis_kelamin,nama_bank,nomor_rekening_bank,npwp,operator,created_at,updated_at,deleted_at,site_nirwana_name,join_date,status_kawin,kehadiran_m_estimasi,sub_dept_id,pembulatan,total_upah_thp_rupiah_employee')->whereRaw('periode_tahun_payroll = "2024" and periode_bulan_payroll = "01"'.$inEnrollId.' and periode_umk = "2024-01"')->get();
                foreach($rekap_payroll as $key=>$value){
                    $rekap_payrolls=RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->get();
                    $Bpjs=EmployeeBpjs::where('enroll_id',$value->enroll_id)->where('periode_kehadiran',$priode)->first();
                    $koreksi_upah_mandiri=DataKoreksiUpah::where('enroll_id',$value->enroll_id)->where('periode_tanggal_koreksi',$periode_payroll2)->get();
                    $koreksi_potongan_mandiri=DataKoreksiPotongan::where('enroll_id',$value->enroll_id)->where('periode_tanggal_koreksi',$periode_payroll2)->get();
                    $upah_per_hari_mandiri=RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->pluck('upah_per_hari');
                    $jumlah_hari_mandiri=RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->pluck('total_kehadiran');
                    $jumlah_hari_libur_mandiri=RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->pluck('kehadiran_lsm');
                    if(count($upah_per_hari_mandiri)>1){
                        $upah_hari_kerja_mandiri_1=$upah_per_hari_mandiri[0]*($jumlah_hari_mandiri[0]-$jumlah_hari_libur_mandiri[0]);
                        $upah_hari_kerja_mandiri_2=$upah_per_hari_mandiri[1]*($jumlah_hari_mandiri[1]-$jumlah_hari_libur_mandiri[1]);
                    }else{
                        $upah_hari_kerja_mandiri_1=$upah_per_hari_mandiri[0]*($jumlah_hari_mandiri[0]-$jumlah_hari_libur_mandiri[0]);
                        $upah_hari_kerja_mandiri_2=0;
                    }
                    $total_lembur_rupiah_mandiri=RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('total_lembur_rupiah');
                    $pendapatan_lainnya_rupiah_mandiri=RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('pendapatan_lainnya_rupiah');
                    $koreksi_upah_rupiah_mandiri=$koreksi_upah_mandiri->sum('jumlah_rp_potongan')??0;
                    $koreksi_potongan_rupiah_mandiri=$koreksi_potongan_mandiri->sum('jumlah_rp_potongan')??0;
                    $potongan_iks_rupiah_mandiri=RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('potongan_iks_rupiah');
                    $potongan_dtpc_rupiah_mandiri=RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('potongan_dtpc_rupiah');
                    $potongan_kehadiran_rupiah_mandiri=RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('potongan_kehadiran_rupiah');

                    $upah_hari_kerja_total=$upah_hari_kerja_mandiri_1+$upah_hari_kerja_mandiri_2;
                    $tunjangan_mandiri=TunjanganKaryawan::where('enroll_id',$value->join_date)->where('periode_payroll',$priode)->first();
                    $selisih_tahun = date_diff(date_create($value->join_date), date_create($tanggal_awal))->y;
                    // tentukan besaran tunjangan berdasarkan masa kerja
                    if ($selisih_tahun < 1) {
                        $tunjangan_mandiri = 0;
                    } elseif ($selisih_tahun < 3) {
                        $tunjangan_mandiri = 2500;
                    } elseif ($selisih_tahun < 6) {
                        $tunjangan_mandiri = 5000;
                    }elseif ($selisih_tahun < 9) {
                        $tunjangan_mandiri = 7500;
                    }elseif ($selisih_tahun < 12) {
                        $tunjangan_mandiri = 10000;
                    }else{
                        $tunjangan_mandiri = 12500;
                    }

                    $upah_bruto_rupiah_mandiri=($upah_hari_kerja_total+$tunjangan_mandiri+$value->premi_karyawan+$total_lembur_rupiah_mandiri+$pendapatan_lainnya_rupiah_mandiri+$koreksi_upah_rupiah_mandiri)-($koreksi_potongan_rupiah_mandiri+$potongan_iks_rupiah_mandiri+$potongan_dtpc_rupiah_mandiri+$potongan_kehadiran_rupiah_mandiri);
                    $upah_neto_rupiah_mandiri=($upah_hari_kerja_total+$tunjangan_mandiri+$value->premi_karyawan+$total_lembur_rupiah_mandiri+$pendapatan_lainnya_rupiah_mandiri+$koreksi_upah_rupiah_mandiri)-($koreksi_potongan_rupiah_mandiri+$potongan_iks_rupiah_mandiri+$potongan_dtpc_rupiah_mandiri+$potongan_kehadiran_rupiah_mandiri)-$value->pph21;

                    $bpjs_tk_jkm_bruto_rupiah_mandiri=$Bpjs->bpjs_tk_jkm_bruto_rupiah??0;
                    $bpjs_tk_jkm_neto_rupiah_mandiri=$Bpjs->bpjs_tk_jkm_neto_rupiah??0;
                    $bpjs_tk_jkk_bruto_rupiah_mandiri=$Bpjs->bpjs_tk_jkk_bruto_rupiah??0;
                    $bpjs_tk_jkk_neto_rupiah_mandiri=$Bpjs->bpjs_tk_jkk_neto_rupiah??0;
                    $bpjs_tk_jht_bruto_rupiah_mandiri=$Bpjs->bpjs_tk_jht_bruto_rupiah??0;
                    $bpjs_tk_jht_neto_rupiah_mandiri=$Bpjs->bpjs_tk_jht_neto_rupiah??0;
                    $bpjs_tk_jpn_bruto_rupiah_mandiri=$Bpjs->bpjs_tk_jpn_bruto_rupiah??0;
                    $bpjs_tk_jpn_neto_rupiah_mandiri=$Bpjs->bpjs_tk_jpn_neto_rupiah??0;
                    $bpjs_ks_jkn_bruto_rupiah_mandiri=$Bpjs->bpjs_ks_jkn_bruto_rupiah??0;
                    $bpjs_ks_jkn_neto_rupiah_mandiri=$Bpjs->bpjs_ks_jkn_neto_rupiah??0;

                    $total_bpjs_tk_mandiri=$bpjs_tk_jkm_neto_rupiah_mandiri + $bpjs_tk_jkk_neto_rupiah_mandiri + $bpjs_tk_jht_neto_rupiah_mandiri + $bpjs_tk_jpn_neto_rupiah_mandiri;

                    $total_bpjs_ks_mandiri=$bpjs_ks_jkn_neto_rupiah_mandiri;

                    $upah_bersih_rupiah_mandiri=$upah_neto_rupiah_mandiri-($total_bpjs_tk_mandiri+$total_bpjs_ks_mandiri+$value->iuran_koperasi+$value->iuran_serikat_rupiah);

                    $total_upah_thp_rupiah_mandiri=$upah_neto_rupiah_mandiri-($total_bpjs_tk_mandiri+$total_bpjs_ks_mandiri+$value->iuran_koperasi+$value->iuran_serikat_rupiah)-$value->potongan_kasbon_rupiah;

                    $jumlah_potongan_rupiah_mandiri=($total_bpjs_tk_mandiri+$total_bpjs_ks_mandiri+$value->iuran_koperasi+$value->iuran_serikat_rupiah);

                    $rekap_payroll_mandiri=[
                        'kode_rekap_payroll'=>substr($value->kode_rekap_payroll, 0, -7),
                        'periode_umk'=>null,
                        'periode_kehadiran'=>$value->periode_kehadiran,
                        'periode_tahun_payroll'=>$value->periode_tahun_payroll,
                        'periode_bulan_payroll'=>$value->periode_bulan_payroll,
                        'enroll_id'=>$value->enroll_id,
                        'nik'=>$value->nik,
                        'kode_grade'=>$value->kode_grade,
                        'join_date'=>$value->join_date,
                        'site_nirwana_name'=>$value->site_nirwana_name,
                        'employee_name'=>$value->employee_name,
                        'tanggal_resign'=>$value->tanggal_resign,
                        'ptkp'=>$value->ptkp,
                        'status_kawin'=>$value->status_kawin,
                        'jabatan_karyawan'=>$value->jabatan_karyawan,
                        'nama_bagian'=>$value->nama_bagian,
                        'nama_department'=>$value->nama_department,
                        'kategori_karyawan'=>$value->kategori_karyawan,
                        'aktif_karyawan'=>$value->aktif_karyawan,
                        'jenis_kelamin'=>$value->jenis_kelamin,
                        'nama_bank'=>$value->nama_bank,
                        'nomor_rekening_bank'=>$value->nomor_rekening_bank,
                        'npwp'=>$value->npwp,
                        'operator'=>$value->operator,
                        'sub_dept_id'=>$value->sub_dept_id,
                        'tunjangan_karyawan_rupiah'=>$tunjangan_mandiri,
                        'premi_karyawan'=>$value->premi_karyawan,
                        'upah_per_bulan'=>$upah_hari_kerja_total,
                        'upah_per_hari'=>$value->upah_per_hari,
                        'upah_per_menit'=>$value->upah_per_menit,

                        'kehadiran_iby'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('kehadiran_iby'),
                        'kehadiran_itb'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('kehadiran_itb'),
                        'kehadiran_m'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('kehadiran_m'),
                        'kehadiran_dt'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('kehadiran_dt'),
                        'kehadiran_pc'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('kehadiran_pc'),
                        'kehadiran_dtpc'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('kehadiran_dtpc'),
                        'kehadiran_lby'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('kehadiran_lby'),
                        'kehadiran_lsm'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('kehadiran_lsm'),
                        'kehadiran_r'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('kehadiran_r'),
                        'kehadiran_ok'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('kehadiran_ok'),
                        'kehadiran_tk'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('kehadiran_tk'),
                        'total_kehadiran'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('total_kehadiran'),
                        'total_kehadiran_net'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('total_kehadiran_net'),
                        'potongan_kehadiran_rupiah'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('potongan_kehadiran_rupiah'),
                        'kehadiran_m_estimasi'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('kehadiran_m_estimasi'),

                        'lembur_1'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('lembur_1'),
                        'lembur_2'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('lembur_2'),
                        'lembur_3'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('lembur_3'),
                        'lembur_4'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('lembur_4'),
                        'total_lembur_1234'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('total_lembur_1234'),
                        'lembur1_rupiah'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('lembur1_rupiah'),
                        'lembur2_rupiah'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('lembur2_rupiah'),
                        'lembur3_rupiah'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('lembur3_rupiah'),
                        'lembur4_rupiah'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('lembur4_rupiah'),
                        'total_lembur_rupiah'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('total_lembur_rupiah'),

                        'pendapatan_lainnya_rupiah'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('pendapatan_lainnya_rupiah'),

                        'koreksi_upah_rupiah'=>$koreksi_upah_rupiah_mandiri,
                        'koreksi_potongan_rupiah'=>$koreksi_potongan_rupiah_mandiri,

                        'potongan_iks_menit'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('potongan_iks_menit'),
                        'potongan_iks_rupiah'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('potongan_iks_rupiah'),
                        'potongan_dt_menit'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('potongan_dt_menit'),
                        'potongan_dt_rupiah'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('potongan_dt_rupiah'),
                        'potongan_pc_menit'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('potongan_pc_rupiah'),
                        'potongan_pc_rupiah'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('potongan_pc_rupiah'),
                        'potongan_dtpc_menit'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('potongan_dtpc_menit'),
                        'potongan_dtpc_rupiah'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('potongan_dtpc_rupiah'),
                        'potongan_kehadiran_rupiah'=>RekapPerhitunganPayroll::where('enroll_id',$value->enroll_id)->where('periode_tahun_payroll','2024')->where('periode_umk','!=','')->where('periode_bulan_payroll','01')->sum('potongan_kehadiran_rupiah'),
                        'pph21'=>$value->pph21, // dari mana?
                        'iuran_serikat_rupiah'=>$value->iuran_serikat_rupiah, // dari mana?
                        'iuran_koperasi'=>$value->iuran_koperasi, // dari mana?
                        'potongan_kasbon_rupiah'=>$value->potongan_kasbon_rupiah, // dari mana?
                        'bpjs_tk_jkm_rupiah'=> $bpjs_tk_jkm_bruto_rupiah_mandiri + $bpjs_tk_jkm_neto_rupiah_mandiri,
                        'bpjs_tk_jkm_perusahaan_rupiah'=> $bpjs_tk_jkm_bruto_rupiah_mandiri,
                        'bpjs_tk_jkm_karyawan_rupiah'=> $bpjs_tk_jkm_neto_rupiah_mandiri,
                        'bpjs_tk_jkk_rupiah'=> $bpjs_tk_jkk_bruto_rupiah_mandiri + $bpjs_tk_jkk_neto_rupiah_mandiri,
                        'bpjs_tk_jkk_perusahaan_rupiah'=> $bpjs_tk_jkk_bruto_rupiah_mandiri,
                        'bpjs_tk_jkk_karyawan_rupiah'=> $bpjs_tk_jkk_neto_rupiah_mandiri,
                        'bpjs_tk_jht_rupiah'=> $bpjs_tk_jht_bruto_rupiah_mandiri + $bpjs_tk_jht_neto_rupiah_mandiri,
                        'bpjs_tk_jht_perusahaan_rupiah'=> $bpjs_tk_jht_bruto_rupiah_mandiri,
                        'bpjs_tk_jht_karyawan_rupiah'=> $bpjs_tk_jht_neto_rupiah_mandiri,
                        'bpjs_tk_jpn_rupiah'=> $bpjs_tk_jpn_bruto_rupiah_mandiri + $bpjs_tk_jpn_neto_rupiah_mandiri,
                        'bpjs_tk_jpn_perusahaan_rupiah'=> $bpjs_tk_jpn_bruto_rupiah_mandiri,
                        'bpjs_tk_jpn_karyawan_rupiah'=> $bpjs_tk_jpn_neto_rupiah_mandiri,
                        'bpjs_ks_jkn_rupiah'=> $bpjs_ks_jkn_bruto_rupiah_mandiri + $bpjs_ks_jkn_neto_rupiah_mandiri,
                        'bpjs_ks_jkn_perusahaan_rupiah'=> $bpjs_ks_jkn_bruto_rupiah_mandiri,
                        'bpjs_ks_jkn_karyawan_rupiah'=> $bpjs_ks_jkn_neto_rupiah_mandiri,
                        'total_bpjs_tk'=>$bpjs_tk_jkm_neto_rupiah_mandiri + $bpjs_tk_jkk_neto_rupiah_mandiri + $bpjs_tk_jht_neto_rupiah_mandiri + $bpjs_tk_jpn_neto_rupiah_mandiri,
                        'total_bpjs_ks'=>$bpjs_ks_jkn_neto_rupiah_mandiri,

                        'upah_bruto_rupiah'=>$upah_bruto_rupiah_mandiri??0,
                        'upah_neto_rupiah'=>$upah_neto_rupiah_mandiri??0,

                        'upah_bersih_rupiah'=>$upah_bersih_rupiah_mandiri??0,
                        'total_upah_thp_rupiah'=>$total_upah_thp_rupiah_mandiri??0,
                        'jumlah_potongan_rupiah'=>$jumlah_potongan_rupiah_mandiri??0,
                        'pembulatan'=>(ceil($total_upah_thp_rupiah_mandiri / 100) * 100)-$total_upah_thp_rupiah_mandiri,
                        'total_upah_thp_rupiah_employee'=>ceil($total_upah_thp_rupiah_mandiri / 100) * 100
                    ];
                    $count_mandiri=RekapPerhitunganPayroll::where('kode_rekap_payroll',substr($value->kode_rekap_payroll, 0, -7))->count();
                    if($count_mandiri){
                        RekapPerhitunganPayroll::where('kode_rekap_payroll',substr($value->kode_rekap_payroll, 0, -7))->update($rekap_payroll_mandiri);
                    }else{
                        RekapPerhitunganPayroll::create($rekap_payroll_mandiri);
                    }
                }

                //rekap jurnal
                $departement=DepartmentAll::where('site_nirwana_id','NAG')->get();
                $data_potongan = $this->potongan($periode_payroll);
                $data_koreksi = $this->koreksi($periode_payroll);
                foreach ($departement as $key => $value) {
                    $potongan_bpjs_tk=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','1')->sum('jumlah_rp_potongan');
                    $potongan_bpjs_ks=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','2')->sum('jumlah_rp_potongan');
                    $potongan_bazzar=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','3')->sum('jumlah_rp_potongan');
                    $potongan_kasbon=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','4')->sum('jumlah_rp_potongan');
                    $potongan_lain=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','5')->sum('jumlah_rp_potongan');
                    $koreksi_upah=$data_koreksi->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_koreksi','1')->sum('jumlah_rp_potongan');
                    $koreksi_insentif=$data_koreksi->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_koreksi','2')->sum('jumlah_rp_potongan');
                    $payroll=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('periode_umk',null)
                        ->where('kategori_karyawan','NON STAFF')->where('sub_dept_id',$value->sub_dept_id)
                        ->where('total_kehadiran_net','>',0)->get();

                    $rp_cuti_tahuna=0;
                    $potongan_kehadiran_rupiah= $payroll->sum('potongan_kehadiran_rupiah');
                    $rp_pot_jam=$payroll->sum('potongan_iks_rupiah')+$payroll->sum('potongan_dtpc_rupiah');
                    $iuran_serikat_rupiah=$payroll->sum('iuran_serikat_rupiah');
                    $iuran_koperasi=$payroll->sum('iuran_koperasi');

                    $gaji_umk=$payroll->sum('upah_per_bulan');
                    $pembulatan=$payroll->sum('pembulatan');
                    $gaji=$gaji_umk+ $pembulatan + $koreksi_upah;
                    $tunjangan_karyawan_rupiah=$payroll->sum('tunjangan_karyawan_rupiah')+$koreksi_insentif;
                    $total_lembur_rupiah=$payroll->sum('total_lembur_rupiah');
                    $bonus=0;
                    $piutang_karyawan=$potongan_kasbon;
                    $piutang_bazzar=$potongan_bazzar;
                    $bpjs_tk=$payroll->sum('total_bpjs_tk');
                    $bpjs_ks=$payroll->sum('total_bpjs_ks');
                    $potongan= $potongan_lain + $rp_cuti_tahuna + $potongan_kehadiran_rupiah + $rp_pot_jam + $iuran_serikat_rupiah + $iuran_koperasi;
                    // $gaji_note=($gaji+$tunjangan_karyawan_rupiah+$total_lembur_rupiah+$bonus)-
                    //             ($piutang_karyawan+$piutang_bazzar+$bpjs_tk+$bpjs_ks+$potongan);
                    $gaji_neto=$payroll->sum('total_upah_thp_rupiah')+$payroll->sum('pembulatan');

                    $data=[
                        'kode_bagian'=>$value->sub_dept_id,
                        'nama_bagian'=>$value->sub_dept_name,
                        'gaji'=>$gaji ,
                        'tunjangan_karyawan_rupiah'=> $tunjangan_karyawan_rupiah,
                        'total_lembur_rupiah'=> $total_lembur_rupiah,
                        'bonus'=> $bonus,
                        'piutang_karyawan'=>$piutang_karyawan,
                        'piutang_bazzar'=>$piutang_bazzar,
                        'bpjs_tk'=>$bpjs_tk,
                        'bpjs_ks'=>$bpjs_ks,
                        'potongan'=>$potongan,
                        'gaji_neto'=>$gaji_neto,
                        'jumlah_karyawn'=>$payroll->count(),
                        'periode_payroll'=>$periode_payroll,
                    ];
                    $count=Jurnal::where('kode_bagian',$value->sub_dept_id)->where('periode_payroll',$periode_payroll)->count();

                    if($count){
                        Jurnal::where('kode_bagian',$value->sub_dept_id)->where('periode_payroll',$periode_payroll)->update($data);
                    }
                    else{
                        Jurnal::create($data);
                    }
                }
            }
        }

    }


    public function proses_rekap_lembur(Request $request){
        $bulan=$request->periode_payrol;

        $rekap_lembur = $this->rekap_lembur($bulan);
        return true;
    }

		 // rekap_absen_security
    public function absen_security($bulan_priode)
    {
        // $bulan_priode='2023-04';
        $bulan_sekarang1 = strtotime(date( $bulan_priode));
				$tanggal_sekarang=date('Y-m-d');

        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';

        $bulan=date('m', $bulan_sekarang1);
        $tahun=date('Y', $bulan_sekarang1);
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');

        $tidak_bayar=RefAbsenIjin::where('kode_ijin_payroll','ITB')->where('kode_absen_ijin','!=','M')->where('kode_absen_ijin','!=','IKS')->get()->toArray();
        $ITB=array_column($tidak_bayar,'kode_absen_ijin');

        $timestamp1 = strtotime($tanggal_awal);
        $timestamp2 = strtotime($tanggal_akhir);
        $jumlah_hari =(abs($timestamp2 - $timestamp1) / (60 * 60 * 24)+1);

        $jumlah_hari_sabtu_minggu = 0;

        $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->where('enroll_id','!=',7445)->get()->toArray();
        $nik_security=array_map(function ($value){
            return ($value['enroll_id']);
        },$security);

        for ($i = strtotime($tanggal_awal); $i <= strtotime($tanggal_akhir); $i += 86400) {
            if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
                $jumlah_hari_sabtu_minggu++;
            }
        }

        $x=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->wherein('enroll_id',$nik_security)->get()->groupby('enroll_id');
        foreach ($x as $key => $value) {
            $IBY_employe=$value->wherein('status_absen', $IBY)->count();
            $LBY_employe=$value->where('status_absen','LN')->whereNotin('kode_hari', ['5','6'])->count();
            $ITB_employe=$value->wherein('status_absen', $ITB)->count();

            // $libur_sabtu_minggu=$value->wherein('kode_hari', ['5','6'])->where('mulai_jam_kerja',null)->count();
            $dt_employe=$value->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','0')->where('status_absen',null)->count();
            $pc_employe=$value->where('jumlah_menit_absen_pc','>','0')->where('jumlah_menit_absen_dt','0')->where('status_absen',null)->count();

            $dtpc_employe=$value->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','>','0')->count();

            $absen_M=$value->where('status_absen','M')->where('mulai_jam_kerja','!=',null)->count();

            $libur_sabtu_minggu=$value->where('status_absen',null)->where('mulai_jam_kerja',null)->count();

            // $libur=$value->where('status_absen','M')->where('mulai_jam_kerja',null)->count();
            $libur=$value->where('status_absen','M')->where('mulai_jam_kerja',null)->count();


            $lsm_employe=$libur_sabtu_minggu+$libur;
            $absen_R=$value->where('status_absen','R')->count();
            $absen_TL=$value->where('status_absen','TL')->where('mulai_jam_kerja','!=',null)->count();
            $absen_IKS=$value->where('status_absen','IKS')->where('jumlah_menit_absen_dtpc','0')->count();
            $absen_ok=$value->where('mulai_jam_kerja','!=',null)->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();
            $absen_ok=$absen_ok+$absen_IKS;
            $unik=$angka_string =sprintf("%04d", $key);
            $kode_rekap_kehadiran=$bulan_sekarang.$unik;

            $total_kehadiran=$IBY_employe+$ITB_employe+$lsm_employe+$dtpc_employe+$absen_M+$absen_R+$absen_ok+$LBY_employe+$dt_employe+$pc_employe+$absen_TL;

            $total_kehadiran_net=$absen_ok+$dt_employe+$pc_employe+$dtpc_employe;
            $aa=$total_kehadiran_net+$ITB_employe+$LBY_employe+$IBY_employe+$lsm_employe+$absen_M+$absen_TL+$absen_R;
            $kehadiran_tk=$jumlah_hari-$aa;

 						$M_estimasi=$value->where('status_absen','M')->where('tanggal_berjalan','>=',$tanggal_sekarang)->where('tanggal_berjalan','<=',$tanggal_akhir)->count();
            $TL_estimasi=$value->where('status_absen','TL')->where('mulai_jam_kerja','!=',null)->where('tanggal_berjalan','>=',$tanggal_sekarang)->where('tanggal_berjalan','<=',$tanggal_akhir)->count();

                $jumlah_hari_kerja=25;

            $y=[
                'uuid'=>Str::uuid('uuid'),
                'kode_rekap_kehadiran'=> $kode_rekap_kehadiran,
                'periode_payroll'=>$tanggal_awal.' s/d '.$tanggal_akhir,
                'periode_tahun'=>$tahun,
                'periode_bulan'=>$bulan,
                'enroll_id'=>$value->first()->enroll_id,
                'nik'=>$value->first()->nik,
                'employee_name'=>$value->first()->employee_name,
                'site_nirwana_id'=>$value->first()->site_nirwana_id,
                'site_nirwana_name'=>$value->first()->site_nirwana_name,
                'department_id'=>$value->first()->department_id,
                'department_name'=>$value->first()->department_name,
                'sub_dept_id'=>$value->first()->sub_dept_id,
                'sub_dept_name'=>$value->first()->sub_dept_name,
                'join_date'=>$value->first()->join_date,
                'tanggal_resign'=>$value->first()->tanggal_resign,
                'status_aktif'=>$value->first()->status_aktif,
                'status_staff'=>$value->first()->status_staff,
                'kehadiran_iby'=>$IBY_employe,
                'kehadiran_itb'=>$ITB_employe,
                'kehadiran_lby'=>$LBY_employe,
                'kehadiran_lsm'=>$lsm_employe,
                'kehadiran_dt'=> $dt_employe,
                'kehadiran_pc'=> $pc_employe,
                'kehadiran_dtpc'=>$dtpc_employe,
                'kehadiran_m'=>$absen_M+$absen_TL,
                'kehadiran_r'=>$absen_R,
                'kehadiran_tk'=>$kehadiran_tk,
                'kehadiran_ok'=>$absen_ok,
                'total_kehadiran'=> $total_kehadiran,
                'total_kehadiran_net'=>$absen_ok+$dt_employe+$pc_employe+$dtpc_employe+$LBY_employe+$IBY_employe,
                'jumlah_hari'=>$jumlah_hari,
                'jumlah_hari_kerja'=> $jumlah_hari_kerja,
                'kehadiran_m_estimasi'=> $TL_estimasi+$M_estimasi,

            ];
            $count=RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->count();
            if($count){
                RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->update($y);
            }
            else{
                RekapKehadiranKaryawan::create($y);

            }
        }
        // dd($y);
        return true;

    }
     // rekap_absen_perhitungan_security
    public function  rekap_absen_perhitungan_security($bulan_priode)
    {
        $bulan_sekarang1 = strtotime(date( $bulan_priode));

        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';

        $periode_bulan=date('m', $bulan_sekarang1);
        $periode_tahun=date('Y', $bulan_sekarang1);


        $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->where('enroll_id','!=',7445)->get()->toArray();
        $nik_security=array_map(function ($value){
            return ($value['enroll_id']);
        },$security);

        $data=RekapKehadiranKaryawan::where('periode_tahun',$periode_tahun)->where('periode_bulan',$periode_bulan)->wherein('enroll_id',$nik_security)->get();

        foreach ($data as $key => $value) {
            $periode = $value->periode_payroll;
            $enroll_id = $value->enroll_id;
            $kode = str_replace(array('-', ' '), '', $periode) . str_pad($enroll_id, 5, '0', STR_PAD_LEFT);;
            $kode_rekap = date('Ymd', strtotime(substr($kode, 0, 8))) . date('Ymd', strtotime(substr($kode, 11, 8))) . substr($kode, 19);

            $salary=EmployeeGrading::where('enroll_id',$value->enroll_id)->latest()->first();
            // $salary=EmployeeGrading::where('enroll_id',$value->enroll_id)->where('periode_payroll',$value->periode_payroll)->first();

           // $salary_bulanan=$salary->salary_bulanan??0;
            $salary_bulanan=$salary->salary_bulanan??GradingSalary::where('kode_grade','C')->first()->salary_bulanan;

            $hari_potongan=max($value->kehadiran_m+$value->kehadiran_r+$value->kehadiran_itb, 0);

                $jumlah_menit_kerja=420;

            $perhitungan=[
                'kode_rekap'=>$kode_rekap,
                'periode_payroll'=>$value->periode_payroll,
                'periode_tahun_bulan'=>$value->periode_tahun.'-'.$value->periode_bulan,
                'enroll_id'=>$value->enroll_id,
                'employee_name'=>$value->employee_name,
                'kehadiran_iby'=>$value->kehadiran_iby,
                'kehadiran_itb'=>$value->kehadiran_itb,
                'kehadiran_lby'=>$value->kehadiran_lby,
                'kehadiran_lsm'=>$value->kehadiran_lsm,
                'kehadiran_dt'=>$value->kehadiran_dt,
                'kehadiran_pc'=>$value->kehadiran_pc,
                'kehadiran_dtpc'=>$value->kehadiran_dtpc,
                'kehadiran_m'=>$value->kehadiran_m,
                'kehadiran_r'=>$value->kehadiran_r,
                'kehadiran_tk'=>$value->kehadiran_tk,
                'kehadiran_ok'=>$value->kehadiran_ok,
                'total_kehadiran'=>$value->total_kehadiran,
                'total_kehadiran_net'=>$value->total_kehadiran_net,
                'jumlah_hari'=>$value->jumlah_hari,
                'jumlah_hari_kerja'=>$value->jumlah_hari_kerja,
                'gaji_pokok'=>$salary_bulanan,
                'gaji_harian'=>$salary_bulanan/$value->jumlah_hari_kerja,
                'gaji_menit'=>($salary_bulanan/$value->jumlah_hari_kerja)/$jumlah_menit_kerja,
                'potongan_kehadiran_rupiah'=>($salary_bulanan/$value->jumlah_hari_kerja)*$hari_potongan,
               	'kehadiran_m_estimasi'=>$value->kehadiran_m_estimasi,
            ];
            $count=RekapPerhitunganKehadiranKaryawan::where( 'kode_rekap',$kode_rekap)->count();
            if($count){
                RekapPerhitunganKehadiranKaryawan::where( 'kode_rekap',$kode_rekap)->update($perhitungan);
            }
            else{
                RekapPerhitunganKehadiranKaryawan::create($perhitungan);
            }
        }
        return true;
    }

     public function jurnal()
    {
        // $bulan_priode=date('Y-m');
        // $bulan_priode=date('2023-07');
        $bulan_priode='2023-12';

        $bulan_sekarang1 = strtotime(date( $bulan_priode));

        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';
        $tanggal_awal_baru = date('m/d/Y', strtotime($tanggal_awal));
        $tanggal_akhir_baru = date('m/d/Y', strtotime($tanggal_akhir));
        $periode_payroll = $tanggal_awal_baru . ' - ' . $tanggal_akhir_baru;// menggunakan -

        $data_potongan = $this->potongan($periode_payroll);

        $data_koreksi = $this->koreksi($periode_payroll);

        list($year, $month) = explode('-', $bulan_priode);
        // $departement=DepartmentAll::where('site_nirwana_id','NAG')->where('sub_dept_id','DEP08SUB006')->get();
        $departement=DepartmentAll::where('site_nirwana_id','NAG')->get();

        $data=[];
        foreach ($departement as $key => $value) {

            // 1. BPJS TK
            // 2. BPJS KS
            // 3. Bazzar
            // 4. Kas Bon
            // 5. Lain-lain
            $potongan_bpjs_tk=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','1')->sum('jumlah_rp_potongan');
            $potongan_bpjs_ks=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','2')->sum('jumlah_rp_potongan');
            $potongan_bazzar=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','3')->sum('jumlah_rp_potongan');
            $potongan_kasbon=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','4')->sum('jumlah_rp_potongan');
            $potongan_lain=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','5')->sum('jumlah_rp_potongan');

            // 1. Upah
            // 2. Insentif
            $koreksi_upah=$data_koreksi->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_koreksi','1')->sum('jumlah_rp_potongan');
            $koreksi_insentif=$data_koreksi->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_koreksi','2')->sum('jumlah_rp_potongan');
            $payroll=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)
                ->where('kategori_karyawan','NON STAFF')->where('sub_dept_id',$value->sub_dept_id)
                ->where('total_kehadiran_net','>',0)->get();

            $rp_cuti_tahuna=0;
            $potongan_kehadiran_rupiah= $payroll->sum('potongan_kehadiran_rupiah');
            $rp_pot_jam=$payroll->sum('potongan_iks_rupiah')+$payroll->sum('potongan_dtpc_rupiah');
            $iuran_serikat_rupiah=$payroll->sum('iuran_serikat_rupiah');
            $iuran_koperasi=$payroll->sum('iuran_koperasi');

            $gaji_umk=$payroll->sum('upah_per_bulan');
            $pembulatan=$payroll->sum('pembulatan');
            $gaji=$gaji_umk+ $pembulatan + $koreksi_upah;
            $tunjangan_karyawan_rupiah=$payroll->sum('tunjangan_karyawan_rupiah')+$koreksi_insentif;
            $total_lembur_rupiah=$payroll->sum('total_lembur_rupiah');
            $bonus=0;
            $piutang_karyawan=$potongan_kasbon;
            $piutang_bazzar=$potongan_bazzar;
            $bpjs_tk=$payroll->sum('total_bpjs_tk');
            $bpjs_ks=$payroll->sum('total_bpjs_ks');
            $potongan= $potongan_lain + $rp_cuti_tahuna + $potongan_kehadiran_rupiah + $rp_pot_jam + $iuran_serikat_rupiah + $iuran_koperasi;
            // $gaji_note=($gaji+$tunjangan_karyawan_rupiah+$total_lembur_rupiah+$bonus)-
            //             ($piutang_karyawan+$piutang_bazzar+$bpjs_tk+$bpjs_ks+$potongan);
            $gaji_neto=$payroll->sum('total_upah_thp_rupiah')+$payroll->sum('pembulatan');

            $data=[
                'kode_bagian'=>$value->sub_dept_id,
                'nama_bagian'=>$value->sub_dept_name,
                'gaji'=>$gaji ,
                'tunjangan_karyawan_rupiah'=> $tunjangan_karyawan_rupiah,
                'total_lembur_rupiah'=> $total_lembur_rupiah,
                'bonus'=> $bonus,
                'piutang_karyawan'=>$piutang_karyawan,
                'piutang_bazzar'=>$piutang_bazzar,
                'bpjs_tk'=>$bpjs_tk,
                'bpjs_ks'=>$bpjs_ks,
                'potongan'=>$potongan,
                'gaji_neto'=>$gaji_neto,
                'jumlah_karyawn'=>$payroll->count(),
                'periode_payroll'=>$bulan_priode,

            ];
            $count=Jurnal::where('kode_bagian',$value->sub_dept_id)->where('periode_payroll',$bulan_priode)->count();

            if($count){
                Jurnal::where('kode_bagian',$value->sub_dept_id)->where('periode_payroll',$bulan_priode)->update($data);
            }
            else{
                Jurnal::create($data);

            }
        }
        dd($data);
        // return true;

    }
    public function jurnal2()
   {
       // $bulan_priode=date('Y-m');
       // $bulan_priode=date('2023-07');

       $bulan_priode='2023-11';
       $bulan_sekarang1 = strtotime(date( $bulan_priode));

       $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
       $bulan_sebelum=date('Y-m-', $bulan_sebelum);
       $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

       $tanggal_awal=$bulan_sebelum.'26';
       $tanggal_akhir=$bulan_sekarang.'25';
       $tanggal_awal_baru = date('m/d/Y', strtotime($tanggal_awal));
       $tanggal_akhir_baru = date('m/d/Y', strtotime($tanggal_akhir));
       $periode_payroll = $tanggal_awal_baru . ' - ' . $tanggal_akhir_baru;// menggunakan -

       $data_potongan = $this->potongan($periode_payroll);

       $data_koreksi = $this->koreksi($periode_payroll);

       list($year, $month) = explode('-', $bulan_priode);
       // $departement=DepartmentAll::where('site_nirwana_id','NAG')->where('sub_dept_id','DEP08SUB006')->get();
       $departement=DepartmentAll::where('site_nirwana_id','NAG')->get();

       $data=[];
       $payroll=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('kategori_karyawan','NON STAFF')->where('sub_dept_id','DEP03SUB003')->where('total_kehadiran_net','>',0)->get();
        $gaji_umk=$payroll->sum('upah_per_bulan');
        $pembulatan=$payroll->sum('pembulatan');
        $koreksi_upah=$data_koreksi->where('sub_dept_id','DEP03SUB003')->where('status_jabatan','NON STAFF')->where('jenis_koreksi','1')->sum('jumlah_rp_potongan');
        $gaji=$gaji_umk+ $pembulatan + $koreksi_upah;
        dd($gaji);
    //    foreach ($departement as $key => $value) {

    //        // 1. BPJS TK
    //        // 2. BPJS KS
    //        // 3. Bazzar
    //        // 4. Kas Bon
    //        // 5. Lain-lain
    //        $potongan_bpjs_tk=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','1')->sum('jumlah_rp_potongan');
    //        $potongan_bpjs_ks=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','2')->sum('jumlah_rp_potongan');
    //        $potongan_bazzar=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','3')->sum('jumlah_rp_potongan');
    //        $potongan_kasbon=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','4')->sum('jumlah_rp_potongan');
    //        $potongan_lain=$data_potongan->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','5')->sum('jumlah_rp_potongan');

    //        // 1. Upah
    //        // 2. Insentif
    //        $koreksi_upah=$data_koreksi->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_koreksi','1')->sum('jumlah_rp_potongan');
    //        $koreksi_insentif=$data_koreksi->where('sub_dept_id',$value->sub_dept_id)->where('status_jabatan','NON STAFF')->where('jenis_koreksi','2')->sum('jumlah_rp_potongan');
        //    $payroll=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)
        //        ->where('kategori_karyawan','NON STAFF')->where('sub_dept_id',$value->sub_dept_id)
        //        ->where('total_kehadiran_net','>',0)->get();

        //    $rp_cuti_tahuna=0;
        //    $potongan_kehadiran_rupiah= $payroll->sum('potongan_kehadiran_rupiah');
        //    $rp_pot_jam=$payroll->sum('potongan_iks_rupiah')+$payroll->sum('potongan_dtpc_rupiah');
        //    $iuran_serikat_rupiah=$payroll->sum('iuran_serikat_rupiah');
        //    $iuran_koperasi=$payroll->sum('iuran_koperasi');

        //    $gaji_umk=$payroll->sum('upah_per_bulan');
        //    $pembulatan=$payroll->sum('pembulatan');
        //    $gaji=$gaji_umk+ $pembulatan + $koreksi_upah;
        //    $tunjangan_karyawan_rupiah=$payroll->sum('tunjangan_karyawan_rupiah')+$koreksi_insentif;
        //    $total_lembur_rupiah=$payroll->sum('total_lembur_rupiah');
        //    $bonus=0;
        //    $piutang_karyawan=$potongan_kasbon;
        //    $piutang_bazzar=$potongan_bazzar;
        //    $bpjs_tk=$payroll->sum('total_bpjs_tk');
        //    $bpjs_ks=$payroll->sum('total_bpjs_ks');
        //    $potongan= $potongan_lain + $rp_cuti_tahuna + $potongan_kehadiran_rupiah + $rp_pot_jam + $iuran_serikat_rupiah + $iuran_koperasi;
        //    // $gaji_note=($gaji+$tunjangan_karyawan_rupiah+$total_lembur_rupiah+$bonus)-
        //    //             ($piutang_karyawan+$piutang_bazzar+$bpjs_tk+$bpjs_ks+$potongan);
        //    $gaji_neto=$payroll->sum('total_upah_thp_rupiah')+$payroll->sum('pembulatan');

        //    $data=[
        //        'kode_bagian'=>$value->sub_dept_id,
        //        'nama_bagian'=>$value->sub_dept_name,
        //        'gaji'=>$gaji ,
        //        'tunjangan_karyawan_rupiah'=> $tunjangan_karyawan_rupiah,
        //        'total_lembur_rupiah'=> $total_lembur_rupiah,
        //        'bonus'=> $bonus,
        //        'piutang_karyawan'=>$piutang_karyawan,
        //        'piutang_bazzar'=>$piutang_bazzar,
        //        'bpjs_tk'=>$bpjs_tk,
        //        'bpjs_ks'=>$bpjs_ks,
        //        'potongan'=>$potongan,
        //        'gaji_neto'=>$gaji_neto,
        //        'jumlah_karyawn'=>$payroll->count(),
        //        'periode_payroll'=>$bulan_priode,


        //    ];
        //    $count=Jurnal::where('kode_bagian',$value->sub_dept_id)->where('periode_payroll',$bulan_priode)->count();

        //    if($count){
        //        Jurnal::where('kode_bagian',$value->sub_dept_id)->where('periode_payroll',$bulan_priode)->update($data);
        //    }
        //    else{
        //        Jurnal::create($data);

        //    }
    //    }
    //    dd()
    //    return true;

   }


    public function potongan($periode_payroll)  {
        $DataKoreksiPotongan=DataKoreksiPotongan::where('periode_tanggal_koreksi',$periode_payroll)->get();
            $data=[];
            foreach ($DataKoreksiPotongan as $key => $value) {
                $data[]=[
                    'enroll_id'=>$value->enroll_id,
                    'nik'=>$value->nik,
                    'employee_name'=>$value->employee_name,
                    'site_nirwana_id'=>$value->site_nirwana_id,
                    'site_nirwana_name'=>$value->site_nirwana_name,
                    'department_id'=>$value->department_id,
                    'department_name'=>$value->department_name,
                    'sub_dept_id'=>$value->sub_dept_id,
                    'sub_dept_name'=>$value->sub_dept_name,
                    'jumlah_rp_potongan'=>$value->jumlah_rp_potongan,
                    'periode_tanggal_koreksi'=>$value->periode_tanggal_koreksi,
                    'jenis_potongan'=>$value->jenis_potongan,
                    'keterangan'=>$value->keterangan,
                    'status_jabatan'=>$value->atribut->status_staff,
                ];
            }

            $koreksi_potongan=collect($data);

            return  $koreksi_potongan;

    }
    public function koreksi($periode_payroll)  {
        $DataKoreksiUpah=DataKoreksiUpah::where('periode_tanggal_koreksi',$periode_payroll)->get();
            $data=[];
            foreach ($DataKoreksiUpah as $key => $value) {
                $data[]=[
                    'enroll_id'=>$value->enroll_id,
                    'nik'=>$value->nik,
                    'employee_name'=>$value->employee_name,
                    'site_nirwana_id'=>$value->site_nirwana_id,
                    'site_nirwana_name'=>$value->site_nirwana_name,
                    'department_id'=>$value->department_id,
                    'department_name'=>$value->department_name,
                    'sub_dept_id'=>$value->sub_dept_id,
                    'sub_dept_name'=>$value->sub_dept_name,
                    'jumlah_rp_potongan'=>$value->jumlah_rp_potongan,
                    'periode_tanggal_koreksi'=>$value->periode_tanggal_koreksi,
                    'jenis_koreksi'=>$value->jenis_koreksi,
                    'keterangan'=>$value->keterangan,
                    'status_jabatan'=>$value->atribut->status_staff,
                ];

            }

        $data_koreksi=collect($data);

        return $data_koreksi;
    }
    public function index2(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        $selectedEnrollId=$request->selectEmployeeID;
        $periode_umk=$request->periode_umk;
        if(request()->periode_payrols){
            $periode_payroll=request()->periode_payrols;
        }else{
            $periode_payroll = '2024-01';
        }
        $bulan_sekarang1 = strtotime(date($periode_payroll));
        $tanggal_sekarang=date('Y-m-d');
        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';
        $tahun=date('Y', $bulan_sekarang1);
        $bulan=date('m', $bulan_sekarang1);

        $timestamp1 = strtotime($tanggal_awal);
        $timestamp2 = strtotime($tanggal_akhir);
        $jumlah_hari_total=(abs($timestamp2 - $timestamp1) / (60 * 60 * 24)+1);
        $jumlah_hari_sabtu_minggu_total = 0;
        $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->where('enroll_id','!=',7445)->get();
        for ($i = strtotime($tanggal_awal); $i <= strtotime($tanggal_akhir); $i += 86400) {
            if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
                $jumlah_hari_sabtu_minggu_total++;
            }
        }

        $priode=$tanggal_awal.' s/d '. $tanggal_akhir;

        $inEnrollId='';
        if($selectedEnrollId){
            $enroll_id = implode(", ", $selectedEnrollId);
            $allEnroll_id= '('.$enroll_id.')';
            $inEnrollId = ' AND enroll_id IN '.$allEnroll_id.'';
        }
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');
        $tidak_bayar=RefAbsenIjin::where('kode_ijin_payroll','ITB')->where('kode_absen_ijin','!=','M')->where('kode_absen_ijin','!=','IKS')->get()->toArray();
        $ITB=array_column($tidak_bayar,'kode_absen_ijin');

        //update bpjs variable
        $kode_bpjs =  BpjsSetting::orderBy('kode_periode_bpjs','desc')->limit(1)->first();
        $periode_payroll_bpjs=$tanggal_awal.' s/d '. $tanggal_akhir;
        $explodePeriodePayroll = explode(" s/d ", $periode_payroll_bpjs);
        $periodePayroll = substr($explodePeriodePayroll[1], 0, 4) . substr($explodePeriodePayroll[1], 5, 2);
        $explodeKode = explode("-", $kode_bpjs->kode_periode_bpjs);
        $kode_periode_bpjs = $explodeKode[0] . $explodeKode[1];
        $sqlKodePeriodeBPJS = 'concat("' . $periodePayroll . '", lpad(enroll_id, 5, 0))';

        //rekap payroll variable
        $tanggal_awal_baru = date('m/d/Y', strtotime($tanggal_awal));
        $tanggal_akhir_baru = date('m/d/Y', strtotime($tanggal_akhir));
        $periode_payroll2 = $tanggal_awal_baru . ' - ' . $tanggal_akhir_baru;// menggunakan -

        //update tanggal resign variable
        list($year, $month) = explode('-', $periode_payroll);

        //rekap kehadiran
        $jumlah_hari='';
        $jumlah_hari_sabtu_minggu = 0;
        $data_nomor_form_lembur=RekapPerhitunganLembur::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->get()->toArray();
        $dnfl=array_column($data_nomor_form_lembur,'nomor_form_lembur');
        if(request()->periode_payrols){
            if($selectedEnrollId){
                $lemburan=MasterDataAbsenKehadiran::where('nomor_form_lembur','!=',null)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->whereIn('enroll_id',$selectedEnrollId)->whereIn('nomor_form_lembur',$dnfl)->with('data_lembur')->get();
            }else{
                $lemburan=MasterDataAbsenKehadiran::where('nomor_form_lembur','!=',null)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->whereIn('nomor_form_lembur',$dnfl)->with('data_lembur')->get();
            }
            foreach ($lemburan as $key => $value) {
                // $y=DataLembur::where('enroll_id',$value->enroll_id)->where('nomor_form_lembur',$value->nomor_form_lembur)->first();
                if($value->kode_hari==5 || $value->kode_hari==6 || $value->status_absen=='LN'){
                    $kerjalibur='LIBUR';
                }else{
                    $kerjalibur='KERJA';
                }
                $jumlah_jam_kerja=date_diff(date_create($value->mulai_jam_kerja),date_create($value->akhir_jam_kerja));
                $jam_efektif_kerja=date_diff(date_create($value->absen_masuk_kerja),date_create($value->absen_pulang_kerja));
                $spl_in=date('H:i:s', strtotime($value->mulai_jam_lembur));
                $jam_in=$value->absen_masuk_kerja;
                $spl_out=date('H:i:s', strtotime($value->akhir_jam_lembur));
                $jam_out=$value->absen_pulang_kerja;
                $jadwal_in=$value->mulai_jam_kerja;
                $jadwal_out=$value->akhir_jam_kerja;
                if($jadwal_in==null || $value->status_absen=='LN'){
                    $finish_in=max([$spl_in,$jam_in]);
                    $finish_out=min([$spl_out,$jam_out]);
                }
                elseif ( $spl_in<=$jadwal_in) {
                    $finish_in=max([$spl_in,$jam_in]);
                    $finish_out=min([$spl_out,$jam_out]);
                }
                else{
                    $finish_in=max([$jadwal_out,$spl_in,$jam_in]);
                    $finish_out=min([$spl_out,$jam_out]);
                }
                $jam1 = strtotime($finish_in);
                $jam2 = strtotime($finish_out);

                // Jika $jam2 lebih kecil dari $jam1, tambahkan 1 hari (86400 detik)
                if ($jam2 < $jam1) {
                    $jam2 += 86400;
                }

                if($value->jumlah_menit_absen_pc!=0 || $value->absen_masuk_kerja==null || $value->absen_pulang_kerja==null){
                    $selisih_detik=0;
                }else{
                    $selisih_detik = max($jam2 - $jam1, 0);
                }

                $selisih_jam = floor($selisih_detik / 3600);
                $selisih_detik %= 3600;

                $selisih_menit = floor($selisih_detik / 60);
                $selisih_detik %= 60;

                $final_total=sprintf("%02d:%02d:%02d", $selisih_jam, $selisih_menit, $selisih_detik);
                if($final_total>='20:00:00'){
                    $final_total_jam_lembur ='00:00:00';
                }else{
                    $final_total_jam_lembur = sprintf("%02d:%02d:%02d", $selisih_jam, $selisih_menit, $selisih_detik);
                }
                $data_lemburan[$key]=[
                    'tanggal_berjalan'=>$value->tanggal_berjalan,
                    'kode_hari'=>$value->kode_hari,
                    'nama_hari'=>$value->nama_hari,
                    'kerjalibur'=>$kerjalibur,
                    'holiday_name'=>$value->holiday_name,
                    'nomor_form_lembur'=>$value->nomor_form_lembur,
                    'enroll_id'=>$value->enroll_id,
                    'nik'=>$value->nik,
                    'employee_name'=>$value->employee_name,
                    'site_nirwana_id'=>$value->site_nirwana_id,
                    'site_nirwana_name'=>$value->site_nirwana_name,
                    'department_id'=>$value->department_id,
                    'department_name'=>$value->department_name,
                    'sub_dept_id'=>$value->sub_dept_id,
                    'sub_dept_name'=>$value->sub_dept_name,
                    'posisi_name'=>$value->posisi_name,
                    'mulai_jam_kerja'=>$value->mulai_jam_kerja,
                    'akhir_jam_kerja'=>$value->akhir_jam_kerja,
                    'jumlah_jam_kerja'=>sprintf('%02d:%02d:%02d', $jumlah_jam_kerja->h, $jumlah_jam_kerja->i, $jumlah_jam_kerja->s),
                    'absen_masuk_kerja'=>$value->absen_masuk_kerja,
                    'absen_pulang_kerja'=>$value->absen_pulang_kerja,
                    'jam_efektif_kerja'=>sprintf('%02d:%02d:%02d', $jam_efektif_kerja->h, $jam_efektif_kerja->i, $jam_efektif_kerja->s),
                    'mulai_jam_lembur'=>$value->mulai_jam_lembur,
                    'akhir_jam_lembur'=>$value->akhir_jam_lembur,
                    'final_mulai_jam_lembur'=>$finish_in,
                    'final_selesai_jam_lembur'=>$value->absen_pulang_kerja,
                    'final_total_jam_lembur'=>$final_total_jam_lembur,
                    'final_jam_istirahat_lembur'=>0,
                    'final_total_menit_lembur'=>($selisih_jam*60)+$selisih_menit,
                    'final_jam_lembur_roundown'=> $selisih_jam,
                    'final_menit_lembur_roundown'=>$selisih_menit,
                    'lembur_1'=>0,
                    'lembur_2'=>0,
                    'lembur_3'=>0,
                    'lembur_4'=>0,
                    'total_lembur_1234'=>0,
                    'salary'=>0,
                    'lembur1_rupiah'=>0,
                    'lembur2_rupiah'=> 0,
                    'lembur3_rupiah'=> 0,
                    'lembur4_rupiah'=> 0,
                    'total_lembur_rupiah'=> 0,
                    'operator'=>'system',
                    'jumlah_jam_istirahat_form'=>$value->data_lembur->jumlah_jam_istirahat??0,
                    'jumlah_jam_lembur_form'=>$value->data_lembur->jumlah_jam_lembur??0,
                    'status_absen'=>$value->status_absen
                ];
            }
            foreach ($data_lemburan as $key2 => $value2) {
                if ($value2['final_menit_lembur_roundown'] <= 15) {
                    $konveri_jam = 0;
                } elseif ($value2['final_menit_lembur_roundown'] > 15 && $value2['final_menit_lembur_roundown'] <= 45) {
                    $konveri_jam = 0.5;
                } else {
                    $konveri_jam = 1;
                }
                $total_jam_lembur=$value2['final_jam_lembur_roundown'] + $konveri_jam;
                $total_jam_lembur_finis=$total_jam_lembur-$value2['jumlah_jam_istirahat_form'];
                $total_jam_lembur_finis=min($value2['jumlah_jam_lembur_form'],$total_jam_lembur_finis);
                if($value2['kode_hari']==5 || $value2['kode_hari']==6 || $value2['status_absen']=='LN' || ($value2['mulai_jam_kerja']==null && $value2['akhir_jam_kerja']==null)){
                    $kerjalibur='LIBUR';
                    $l1=0;
                    $le2=($total_jam_lembur_finis <= 8) ? $total_jam_lembur_finis : 8;
                    $l2=$le2<0?0:$le2;
                    if($total_jam_lembur_finis > 9){
                        $le3=1;
                        $le4=max($total_jam_lembur_finis -9, 0);
                    }
                    else if($total_jam_lembur_finis > 8 && $total_jam_lembur_finis <=9 ){
                        $le3=max($total_jam_lembur_finis -8, 0);
                        $le4=0;
                    }
                    else{
                        $le3=0;
                        $le4=0;
                    }
                    $l3=$le3<0?0:$le3;
                    $l4=$le4<0?0:$le4;
                }
                else{
                    if($value['kode_hari']==5){
                        $kerjalibur='LIBUR';
                    }else{
                        $kerjalibur='KERJA';
                    }
                    $le1 = ($total_jam_lembur_finis <= 1) ? $total_jam_lembur_finis : 1;
                    $le2 = max($total_jam_lembur_finis - 1, 0);
                    $l3=0;
                    $l4=0;
                    $l1=$le1<0?0:$le1;
                    $l2=$le2<0?0:$le2;
                }

                $kode_grade=EmployeeAtribut::select('kode_grade')->where('enroll_id',$value2['enroll_id'])->pluck('kode_grade')[0];
                $salary_bulanan=GradingSalary::select('salary_bulanan')->where('kode_grade',$kode_grade)->where('periode_umk','2025-01')->pluck('salary_bulanan')[0];
                if($value2['kode_hari']==6 || $value2['status_absen']=='LN'){
                    $l1_rupiah=$l1*($salary_bulanan/173*1);
                    $l2_rupiah=$l2*($salary_bulanan/173*2);
                    $l3_rupiah=$l3*($salary_bulanan/173*2);
                    $l4_rupiah=$l4*($salary_bulanan/173*2);
                }
                else{
                    $l1_rupiah=$l1*($salary_bulanan/173*1);
                    $l2_rupiah=$l2*($salary_bulanan/173*1);
                    $l3_rupiah=$l3*($salary_bulanan/173*1);
                    $l4_rupiah=$l4*($salary_bulanan/173*1);
                }
                $record_lemburan=[
                    'uuid'=>Str::uuid('uuid'),
                    'periode_umk'=>null,
                    'tanggal_berjalan'=>$value2['tanggal_berjalan'],
                    'kode_hari'=>$value2['kode_hari'],
                    'nama_hari'=>$value2['nama_hari'],
                    'kerjalibur'=>$value2['kerjalibur'],
                    'holiday_name'=>$value2['holiday_name'],
                    'nomor_form_lembur'=>$value2['nomor_form_lembur'],
                    'enroll_id'=>$value2['enroll_id'],
                    'nik'=>$value2['nik'],
                    'employee_name'=>$value2['employee_name'],
                    'site_nirwana_id'=>$value2['site_nirwana_id'],
                    'site_nirwana_name'=>$value2['site_nirwana_name'],
                    'department_id'=>$value2['department_id'],
                    'department_name'=>$value2['department_name'],
                    'sub_dept_id'=>$value2['sub_dept_id'],
                    'sub_dept_name'=>$value2['sub_dept_name'],
                    'posisi_name'=>$value2['posisi_name'],
                    'mulai_jam_kerja'=>$value2['mulai_jam_kerja'],
                    'akhir_jam_kerja'=>$value2['akhir_jam_kerja'],
                    'jumlah_jam_kerja'=>$value2['jumlah_jam_kerja'],
                    'absen_masuk_kerja'=>$value2['absen_masuk_kerja'],
                    'absen_pulang_kerja'=>$value2['absen_pulang_kerja'],
                    'jam_efektif_kerja'=>$value2['jam_efektif_kerja'],
                    'mulai_jam_lembur'=>$value2['mulai_jam_lembur'],
                    'akhir_jam_lembur'=>$value2['akhir_jam_lembur'],
                    'final_mulai_jam_lembur'=>$value2['final_mulai_jam_lembur'],
                    'final_selesai_jam_lembur'=>$value2['final_selesai_jam_lembur'],
                    'final_total_jam_lembur'=>$value2['final_total_jam_lembur'],
                    'final_jam_istirahat_lembur'=>$value2['jumlah_jam_istirahat_form'],
                    'final_total_menit_lembur'=>$value2['final_total_menit_lembur'],
                    'final_jam_lembur_roundown'=> $value2['final_jam_lembur_roundown'],
                    'final_menit_lembur_roundown'=>$value2['final_menit_lembur_roundown'],
                    'lembur_1'=>$l1,
                    'lembur_2'=>$l2,
                    'lembur_3'=>$l3,
                    'lembur_4'=>$l4,
                    'total_lembur_1234'=>$l1+$l2+$l3+$l4,
                    'salary'=>$salary_bulanan,
                    'lembur1_rupiah'=>$l1_rupiah,
                    'lembur2_rupiah'=> $l2_rupiah,
                    'lembur3_rupiah'=> $l3_rupiah,
                    'lembur4_rupiah'=> $l4_rupiah,
                    'total_lembur_rupiah'=> $l1_rupiah+$l2_rupiah+$l3_rupiah+$l4_rupiah,
                    'operator'=>'system',
                ];
                $count=RekapPerhitunganLembur::where('tanggal_berjalan',$value2['tanggal_berjalan'])->where('enroll_id',$value2['enroll_id'])->count();
                if($count){
                    RekapPerhitunganLembur::where('tanggal_berjalan',$value2['tanggal_berjalan'])->where('enroll_id',$value2['enroll_id'])->update($record_lemburan);
                }
                else{
                    RekapPerhitunganLembur::create($record_lemburan);
                }
            }
        }
    }
    public function index3(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        $selectedEnrollId=$request->selectEmployeeID;
        $periode_umk=$request->periode_umk;
        if(request()->periode_payrols){
            $periode_payroll=request()->periode_payrols;
        }else{
            $periode_payroll = '2024-01';
        }
        $bulan_sekarang1 = strtotime(date($periode_payroll));
        $tanggal_sekarang=date('Y-m-d');
        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';
        $tahun=date('Y', $bulan_sekarang1);
        $bulan=date('m', $bulan_sekarang1);

        $timestamp1 = strtotime($tanggal_awal);
        $timestamp2 = strtotime($tanggal_akhir);
        $jumlah_hari_total=(abs($timestamp2 - $timestamp1) / (60 * 60 * 24)+1);
        $jumlah_hari_sabtu_minggu_total = 0;
        $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->where('enroll_id','!=',7445)->get();
        for ($i = strtotime($tanggal_awal); $i <= strtotime($tanggal_akhir); $i += 86400) {
            if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
                $jumlah_hari_sabtu_minggu_total++;
            }
        }

        $priode=$tanggal_awal.' s/d '. $tanggal_akhir;

        $inEnrollId='';
        if($selectedEnrollId){
            $enroll_id = implode(", ", $selectedEnrollId);
            $allEnroll_id= '('.$enroll_id.')';
            $inEnrollId = ' AND enroll_id IN '.$allEnroll_id.'';
        }
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');
        $tidak_bayar=RefAbsenIjin::where('kode_ijin_payroll','ITB')->where('kode_absen_ijin','!=','M')->where('kode_absen_ijin','!=','IKS')->get()->toArray();
        $ITB=array_column($tidak_bayar,'kode_absen_ijin');

        //update bpjs variable
        $kode_bpjs =  BpjsSetting::orderBy('kode_periode_bpjs','desc')->limit(1)->first();
        $periode_payroll_bpjs=$tanggal_awal.' s/d '. $tanggal_akhir;
        $explodePeriodePayroll = explode(" s/d ", $periode_payroll_bpjs);
        $periodePayroll = substr($explodePeriodePayroll[1], 0, 4) . substr($explodePeriodePayroll[1], 5, 2);
        $explodeKode = explode("-", $kode_bpjs->kode_periode_bpjs);
        $kode_periode_bpjs = $explodeKode[0] . $explodeKode[1];
        $sqlKodePeriodeBPJS = 'concat("' . $periodePayroll . '", lpad(enroll_id, 5, 0))';

        //rekap payroll variable
        $tanggal_awal_baru = date('m/d/Y', strtotime($tanggal_awal));
        $tanggal_akhir_baru = date('m/d/Y', strtotime($tanggal_akhir));
        $periode_payroll2 = $tanggal_awal_baru . ' - ' . $tanggal_akhir_baru;// menggunakan -

        //update tanggal resign variable
        list($year, $month) = explode('-', $periode_payroll);

        //rekap kehadiran
        $jumlah_hari='';
        $jumlah_hari_sabtu_minggu = 0;
        $data_nomor_form_lembur=RekapPerhitunganLembur::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->get()->toArray();
        $dnfl=array_column($data_nomor_form_lembur,'nomor_form_lembur');
        if(request()->periode_payrols){
            $data_lemburan=[];
            if($selectedEnrollId){
                $lemburan=MasterDataAbsenKehadiran::where('nomor_form_lembur','!=',null)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->whereIn('enroll_id',$selectedEnrollId)->whereNotIn('nomor_form_lembur',$dnfl)->with('data_lembur')->get();
            }else{
                $lemburan=MasterDataAbsenKehadiran::where('nomor_form_lembur','!=',null)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->whereNotIn('nomor_form_lembur',$dnfl)->with('data_lembur')->get();
            }
            foreach ($lemburan as $key => $value) {
                // $y=DataLembur::where('enroll_id',$value->enroll_id)->where('nomor_form_lembur',$value->nomor_form_lembur)->first();
                if($value->kode_hari==5 || $value->kode_hari==6 || $value->status_absen=='LN'){
                    $kerjalibur='LIBUR';
                }else{
                    $kerjalibur='KERJA';
                }
                $jumlah_jam_kerja=date_diff(date_create($value->mulai_jam_kerja),date_create($value->akhir_jam_kerja));
                $jam_efektif_kerja=date_diff(date_create($value->absen_masuk_kerja),date_create($value->absen_pulang_kerja));
                $spl_in=date('H:i:s', strtotime($value->mulai_jam_lembur));
                $jam_in=$value->absen_masuk_kerja;
                $spl_out=date('H:i:s', strtotime($value->akhir_jam_lembur));
                $jam_out=$value->absen_pulang_kerja;
                $jadwal_in=$value->mulai_jam_kerja;
                $jadwal_out=$value->akhir_jam_kerja;
                if($jadwal_in==null || $value->status_absen=='LN'){
                    $finish_in=max([$spl_in,$jam_in]);
                    $finish_out=min([$spl_out,$jam_out]);
                }
                elseif ( $spl_in<=$jadwal_in) {
                    $finish_in=max([$spl_in,$jam_in]);
                    $finish_out=min([$spl_out,$jam_out]);
                }
                else{
                    $finish_in=max([$jadwal_out,$spl_in,$jam_in]);
                    $finish_out=min([$spl_out,$jam_out]);
                }
                $jam1 = strtotime($finish_in);
                $jam2 = strtotime($finish_out);

                // Jika $jam2 lebih kecil dari $jam1, tambahkan 1 hari (86400 detik)
                if ($jam2 < $jam1) {
                    $jam2 += 86400;
                }
                if($value->jumlah_menit_absen_pc!=0 || $value->absen_masuk_kerja==null || $value->absen_pulang_kerja==null){
                    $selisih_detik=0;
                }else{
                    $selisih_detik = max($jam2 - $jam1, 0);
                }

                $selisih_jam = floor($selisih_detik / 3600);
                $selisih_detik %= 3600;

                $selisih_menit = floor($selisih_detik / 60);
                $selisih_detik %= 60;

                $final_total=sprintf("%02d:%02d:%02d", $selisih_jam, $selisih_menit, $selisih_detik);
                if($final_total>='20:00:00'){
                    $final_total_jam_lembur ='00:00:00';
                }else{
                    $final_total_jam_lembur = sprintf("%02d:%02d:%02d", $selisih_jam, $selisih_menit, $selisih_detik);
                }
                $data_lemburan[$key]=[
                    'tanggal_berjalan'=>$value->tanggal_berjalan,
                    'kode_hari'=>$value->kode_hari,
                    'nama_hari'=>$value->nama_hari,
                    'kerjalibur'=>$kerjalibur,
                    'holiday_name'=>$value->holiday_name,
                    'nomor_form_lembur'=>$value->nomor_form_lembur,
                    'enroll_id'=>$value->enroll_id,
                    'nik'=>$value->nik,
                    'employee_name'=>$value->employee_name,
                    'site_nirwana_id'=>$value->site_nirwana_id,
                    'site_nirwana_name'=>$value->site_nirwana_name,
                    'department_id'=>$value->department_id,
                    'department_name'=>$value->department_name,
                    'sub_dept_id'=>$value->sub_dept_id,
                    'sub_dept_name'=>$value->sub_dept_name,
                    'posisi_name'=>$value->posisi_name,
                    'mulai_jam_kerja'=>$value->mulai_jam_kerja,
                    'akhir_jam_kerja'=>$value->akhir_jam_kerja,
                    'jumlah_jam_kerja'=>sprintf('%02d:%02d:%02d', $jumlah_jam_kerja->h, $jumlah_jam_kerja->i, $jumlah_jam_kerja->s),
                    'absen_masuk_kerja'=>$value->absen_masuk_kerja,
                    'absen_pulang_kerja'=>$value->absen_pulang_kerja,
                    'jam_efektif_kerja'=>sprintf('%02d:%02d:%02d', $jam_efektif_kerja->h, $jam_efektif_kerja->i, $jam_efektif_kerja->s),
                    'mulai_jam_lembur'=>$value->mulai_jam_lembur,
                    'akhir_jam_lembur'=>$value->akhir_jam_lembur,
                    'final_mulai_jam_lembur'=>$finish_in,
                    'final_selesai_jam_lembur'=>$value->absen_pulang_kerja,
                    'final_total_jam_lembur'=>$final_total_jam_lembur,
                    'final_jam_istirahat_lembur'=>0,
                    'final_total_menit_lembur'=>($selisih_jam*60)+$selisih_menit,
                    'final_jam_lembur_roundown'=> $selisih_jam,
                    'final_menit_lembur_roundown'=>$selisih_menit,
                    'lembur_1'=>0,
                    'lembur_2'=>0,
                    'lembur_3'=>0,
                    'lembur_4'=>0,
                    'total_lembur_1234'=>0,
                    'salary'=>0,
                    'lembur1_rupiah'=>0,
                    'lembur2_rupiah'=> 0,
                    'lembur3_rupiah'=> 0,
                    'lembur4_rupiah'=> 0,
                    'total_lembur_rupiah'=> 0,
                    'operator'=>'system',
                    'jumlah_jam_istirahat_form'=>$value->data_lembur->jumlah_jam_istirahat??0,
                    'jumlah_jam_lembur_form'=>$value->data_lembur->jumlah_jam_lembur??0,
                    'status_absen'=>$value->status_absen
                ];
            }
            foreach ($data_lemburan as $key2 => $value2) {
                if ($value2['final_menit_lembur_roundown'] <= 15) {
                    $konveri_jam = 0;
                } elseif ($value2['final_menit_lembur_roundown'] > 15 && $value2['final_menit_lembur_roundown'] <= 45) {
                    $konveri_jam = 0.5;
                } else {
                    $konveri_jam = 1;
                }
                $total_jam_lembur=$value2['final_jam_lembur_roundown'] + $konveri_jam;
                $total_jam_lembur_finis=$total_jam_lembur-$value2['jumlah_jam_istirahat_form'];
                $total_jam_lembur_finis=min($value2['jumlah_jam_lembur_form'],$total_jam_lembur_finis);
                if($value2['kode_hari']==5 || $value2['kode_hari']==6 || $value2['status_absen']=='LN' || ($value2['mulai_jam_kerja']==null && $value2['akhir_jam_kerja']==null)){
                    $kerjalibur='LIBUR';
                    $l1=0;
                    $le2=($total_jam_lembur_finis <= 8) ? $total_jam_lembur_finis : 8;
                    $l2=$le2<0?0:$le2;
                    if($total_jam_lembur_finis > 9){
                        $le3=1;
                        $le4=max($total_jam_lembur_finis -9, 0);
                    }
                    else if($total_jam_lembur_finis > 8 && $total_jam_lembur_finis <=9 ){
                        $le3=max($total_jam_lembur_finis -8, 0);
                        $le4=0;
                    }
                    else{
                        $le3=0;
                        $le4=0;
                    }
                    $l3=$le3<0?0:$le3;
                    $l4=$le4<0?0:$le4;
                }
                else{
                    if($value['kode_hari']==5){
                        $kerjalibur='LIBUR';
                    }else{
                        $kerjalibur='KERJA';
                    }
                    $le1 = ($total_jam_lembur_finis <= 1) ? $total_jam_lembur_finis : 1;
                    $le2 = max($total_jam_lembur_finis - 1, 0);
                    $l3=0;
                    $l4=0;
                    $l1=$le1<0?0:$le1;
                    $l2=$le2<0?0:$le2;
                }

                $kode_grade=EmployeeAtribut::select('kode_grade')->where('enroll_id',$value2['enroll_id'])->pluck('kode_grade')[0];
                $salary_bulanan=GradingSalary::select('salary_bulanan')->where('kode_grade',$kode_grade)->where('periode_umk','2025-01')->pluck('salary_bulanan')[0];
                if($value2['kode_hari']==6 || $value2['status_absen']=='LN'){
                    $l1_rupiah=$l1*($salary_bulanan/173*1);
                    $l2_rupiah=$l2*($salary_bulanan/173*2);
                    $l3_rupiah=$l3*($salary_bulanan/173*2);
                    $l4_rupiah=$l4*($salary_bulanan/173*2);
                }
                else{
                    $l1_rupiah=$l1*($salary_bulanan/173*1);
                    $l2_rupiah=$l2*($salary_bulanan/173*1);
                    $l3_rupiah=$l3*($salary_bulanan/173*1);
                    $l4_rupiah=$l4*($salary_bulanan/173*1);
                }
                $record_lemburan=[
                    'uuid'=>Str::uuid('uuid'),
                    'periode_umk'=>null,
                    'tanggal_berjalan'=>$value2['tanggal_berjalan'],
                    'kode_hari'=>$value2['kode_hari'],
                    'nama_hari'=>$value2['nama_hari'],
                    'kerjalibur'=>$value2['kerjalibur'],
                    'holiday_name'=>$value2['holiday_name'],
                    'nomor_form_lembur'=>$value2['nomor_form_lembur'],
                    'enroll_id'=>$value2['enroll_id'],
                    'nik'=>$value2['nik'],
                    'employee_name'=>$value2['employee_name'],
                    'site_nirwana_id'=>$value2['site_nirwana_id'],
                    'site_nirwana_name'=>$value2['site_nirwana_name'],
                    'department_id'=>$value2['department_id'],
                    'department_name'=>$value2['department_name'],
                    'sub_dept_id'=>$value2['sub_dept_id'],
                    'sub_dept_name'=>$value2['sub_dept_name'],
                    'posisi_name'=>$value2['posisi_name'],
                    'mulai_jam_kerja'=>$value2['mulai_jam_kerja'],
                    'akhir_jam_kerja'=>$value2['akhir_jam_kerja'],
                    'jumlah_jam_kerja'=>$value2['jumlah_jam_kerja'],
                    'absen_masuk_kerja'=>$value2['absen_masuk_kerja'],
                    'absen_pulang_kerja'=>$value2['absen_pulang_kerja'],
                    'jam_efektif_kerja'=>$value2['jam_efektif_kerja'],
                    'mulai_jam_lembur'=>$value2['mulai_jam_lembur'],
                    'akhir_jam_lembur'=>$value2['akhir_jam_lembur'],
                    'final_mulai_jam_lembur'=>$value2['final_mulai_jam_lembur'],
                    'final_selesai_jam_lembur'=>$value2['final_selesai_jam_lembur'],
                    'final_total_jam_lembur'=>$value2['final_total_jam_lembur'],
                    'final_jam_istirahat_lembur'=>$value2['jumlah_jam_istirahat_form'],
                    'final_total_menit_lembur'=>$value2['final_total_menit_lembur'],
                    'final_jam_lembur_roundown'=> $value2['final_jam_lembur_roundown'],
                    'final_menit_lembur_roundown'=>$value2['final_menit_lembur_roundown'],
                    'lembur_1'=>$l1,
                    'lembur_2'=>$l2,
                    'lembur_3'=>$l3,
                    'lembur_4'=>$l4,
                    'total_lembur_1234'=>$l1+$l2+$l3+$l4,
                    'salary'=>$salary_bulanan,
                    'lembur1_rupiah'=>$l1_rupiah,
                    'lembur2_rupiah'=> $l2_rupiah,
                    'lembur3_rupiah'=> $l3_rupiah,
                    'lembur4_rupiah'=> $l4_rupiah,
                    'total_lembur_rupiah'=> $l1_rupiah+$l2_rupiah+$l3_rupiah+$l4_rupiah,
                    'operator'=>'system',
                ];
                $count=RekapPerhitunganLembur::where('tanggal_berjalan',$value2['tanggal_berjalan'])->where('enroll_id',$value2['enroll_id'])->count();
                if($count){
                    RekapPerhitunganLembur::where('tanggal_berjalan',$value2['tanggal_berjalan'])->where('enroll_id',$value2['enroll_id'])->update($record_lemburan);
                }
                else{
                    RekapPerhitunganLembur::create($record_lemburan);
                }
            }
        }
    }



    // // rekap_absen
    // public function index1()
    // {
    //     $bulan_priode='2023-04';
    //     $bulan_sekarang1 = strtotime(date( $bulan_priode));

    //     $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
    //     $bulan_sebelum=date('Y-m-', $bulan_sebelum);
    //     $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

    //     $tanggal_awal=$bulan_sebelum.'26';
    //     $tanggal_akhir=$bulan_sekarang.'25';

    //     $bulan=date('m', $bulan_sekarang1);
    //     $tahun=date('Y', $bulan_sekarang1);
    //     $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
    //     $IBY=array_column($ijin_bayar,'kode_absen_ijin');

    //     $tidak_bayar=RefAbsenIjin::where('kode_ijin_payroll','ITB')->where('kode_absen_ijin','!=','M')->where('kode_absen_ijin','!=','IKS')->get()->toArray();
    //     $ITB=array_column($tidak_bayar,'kode_absen_ijin');

    //     $timestamp1 = strtotime($tanggal_awal);
    //     $timestamp2 = strtotime($tanggal_akhir);
    //     $jumlah_hari =(abs($timestamp2 - $timestamp1) / (60 * 60 * 24)+1);

    //     $jumlah_hari_sabtu_minggu = 0;

    //     $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->get();



    //     for ($i = strtotime($tanggal_awal); $i <= strtotime($tanggal_akhir); $i += 86400) {
    //         if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
    //             $jumlah_hari_sabtu_minggu++;
    //         }
    //     }

    //     // $x=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('department_id','DEP10')->get()->groupby('enroll_id');
    //     $x=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('enroll_id','1757')->get()->groupby('enroll_id');
    //     // $x=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->get()->groupby('enroll_id');
    //     foreach ($x as $key => $value) {
    //         $IBY_employe=$value->wherein('status_absen', $IBY)->count();
    //         $LBY_employe=$value->where('status_absen','LN')->whereNotin('kode_hari', ['5','6'])->count();
    //         $ITB_employe=$value->wherein('status_absen', $ITB)->count();

    //         $lsm_employe=$value->wherein('kode_hari', ['5','6'])->where('mulai_jam_kerja',null)->count();
    //         $dt_employe=$value->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','0')->count();
    //         $pc_employe=$value->where('jumlah_menit_absen_pc','>','0')->where('jumlah_menit_absen_dt','0')->count();
    //         $dtpc_employe=$value->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','>','0')->count();

    //         $absen_M=$value->where('status_absen','M')->count();
    //         $absen_R=$value->where('status_absen','R')->count();
    //         $absen_TL=$value->where('status_absen','TL')->count();
    //         $absen_IKS=$value->where('status_absen','IKS')->where('jumlah_menit_absen_dtpc','0')->count();
    //         // $absen_ok=$value->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja','!=',null)
    //         //                 ->where('mulai_jam_kerja','!=',null)->where('akhir_jam_kerja','!=',null)
    //         //                 ->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();
    //         $absen_ok=$value->where('mulai_jam_kerja','!=',null)->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();
    //         $absen_ok=$absen_ok+$absen_IKS;
    //         $unik=$angka_string =sprintf("%04d", $key);
    //         $kode_rekap_kehadiran=$bulan_sebelum.$unik;

    //         $total_kehadiran=$IBY_employe+$ITB_employe+$lsm_employe+$dtpc_employe+$absen_M+$absen_R+$absen_ok+$LBY_employe+$dt_employe+$pc_employe+$absen_TL;
    //         // $kehadiran_tk=$jumlah_hari-($total_kehadiran+$absen_TL);

    //         $total_kehadiran_net=$absen_ok+$dt_employe+$pc_employe+$dtpc_employe;
    //         $aa=$total_kehadiran_net+$ITB_employe+$LBY_employe+$IBY_employe+$lsm_employe+$absen_M+$absen_TL+$absen_R;
    //         $kehadiran_tk=$jumlah_hari-$aa;

    //         if($security->where('enroll_id',$value->first()->enroll_id)->count()){
    //             $jumlah_hari_kerja=25;

    //         }else{
    //             $jumlah_hari_kerja=$jumlah_hari-$jumlah_hari_sabtu_minggu;

    //         }


    //         $y=[
    //             'uuid'=>Str::uuid('uuid'),
    //             'kode_rekap_kehadiran'=> $kode_rekap_kehadiran,
    //             'periode_payroll'=>$tanggal_awal.' s/d '.$tanggal_akhir,
    //             'periode_tahun'=>$tahun,
    //             'periode_bulan'=>$bulan,
    //             'enroll_id'=>$value->first()->enroll_id,
    //             'nik'=>$value->first()->nik,
    //             'employee_name'=>$value->first()->employee_name,
    //             'site_nirwana_id'=>$value->first()->site_nirwana_id,
    //             'site_nirwana_name'=>$value->first()->site_nirwana_name,
    //             'department_id'=>$value->first()->department_id,
    //             'department_name'=>$value->first()->department_name,
    //             'sub_dept_id'=>$value->first()->sub_dept_id,
    //             'sub_dept_name'=>$value->first()->sub_dept_name,
    //             'join_date'=>$value->first()->join_date,
    //             'tanggal_resign'=>$value->first()->tanggal_resign,
    //             'status_aktif'=>$value->first()->status_aktif,
    //             'status_staff'=>$value->first()->status_staff,
    //             'kehadiran_iby'=>$IBY_employe,
    //             'kehadiran_itb'=>$ITB_employe,
    //             'kehadiran_lby'=>$LBY_employe,
    //             'kehadiran_lsm'=>$lsm_employe,
    //             'kehadiran_dt'=> $dt_employe,
    //             'kehadiran_pc'=> $pc_employe,
    //             'kehadiran_dtpc'=>$dtpc_employe,
    //             'kehadiran_m'=>$absen_M+$absen_TL,
    //             'kehadiran_r'=>$absen_R,
    //             'kehadiran_tk'=>$kehadiran_tk,
    //             'kehadiran_ok'=>$absen_ok,
    //             'total_kehadiran'=> $total_kehadiran,
    //             // 'total_kehadiran'=> $total_kehadiran+$kehadiran_tk,
    //             'total_kehadiran_net'=>$absen_ok+$dt_employe+$pc_employe+$dtpc_employe+$LBY_employe+$IBY_employe,
    //             'jumlah_hari'=>$jumlah_hari,
    //             'jumlah_hari_kerja'=> $jumlah_hari_kerja,
    //         ];
    //         dd($y);
    //         // $count=RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->count();
    //         // if($count){
    //         //     RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->update($y);
    //         // }
    //         // else{
    //         //     RekapKehadiranKaryawan::create($y);

    //         // }
    //     }
    //     return true;

    // }


    // rekap payrol
    // public function rekap_payroll($bulan_priode)
    // {
    //     // $tanggal_awal='2023-02-26';
    //     // $tanggal_akhir='2023-03-25';

    //     $bulan_sekarang1 = strtotime(date( $bulan_priode));

    //     $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
    //     $bulan_sebelum=date('Y-m-', $bulan_sebelum);
    //     $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

    //     $tanggal_awal=$bulan_sebelum.'26';
    //     $tanggal_akhir=$bulan_sekarang.'25';

    //     $karyawan=EmployeeAtribut::where('status_aktif','AKTIF')->where('join_date','<=', $tanggal_akhir)->ORwhere('tanggal_resign','>=',$tanggal_awal)->get();
    //     // $karyawan=EmployeeAtribut::where('status_aktif','AKTIF')->where('enroll_id','4954')->get();

    //     $periode_payroll=$tanggal_awal.' s/d '.$tanggal_akhir;// menggunakan s/d

    //     $tanggal_awal_baru = date('m/d/Y', strtotime($tanggal_awal));
    //     $tanggal_akhir_baru = date('m/d/Y', strtotime($tanggal_akhir));
    //     $periode_payroll2 = $tanggal_awal_baru . ' - ' . $tanggal_akhir_baru;// menggunakan -

    //     $data=[];
    //     foreach ($karyawan as $key => $value) {

    //         // 'lembur_1'=>$rekap_lembur->sum('lembur_1')??0,
    //         // 'lembur_2'=>$rekap_lembur->sum('lembur_2')??0,
    //         // 'lembur_3'=>$rekap_lembur->sum('lembur_3')??0,
    //         // 'lembur_4'=>$rekap_lembur->sum('lembur_4')??0,
    //         // 'total_lembur_1234'=>$rekap_lembur->sum('total_lembur_1234')??0,
    //         // 'lembur1_rupiah'=>$rekap_lembur->sum('lembur1_rupiah')??0,
    //         // 'lembur2_rupiah'=>$rekap_lembur->sum('lembur2_rupiah')??0,
    //         // 'lembur3_rupiah'=>$rekap_lembur->sum('lembur3_rupiah')??0,
    //         // 'lembur4_rupiah'=>$rekap_lembur->sum('lembur4_rupiah')??0,
    //         // 'total_lembur_rupiah'=>$rekap_lembur->sum('total_lembur_rupiah')??0,

    //         $rekap_kehadiran=RekapPerhitunganKehadiranKaryawan::where('enroll_id',$value->enroll_id)->where('periode_payroll',$periode_payroll)->first();

    //         $rekap_lembur=RekapPerhitunganLembur::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->get();
    //         $lembur_1=collect( $rekap_lembur)->sum('lembur_1');
    //         $lembur_2=collect( $rekap_lembur)->sum('lembur_2');
    //         $lembur_3=collect( $rekap_lembur)->sum('lembur_3');
    //         $lembur_4=collect( $rekap_lembur)->sum('lembur_4');
    //         $total_lembur_1234=collect( $rekap_lembur)->sum('total_lembur_1234');
    //         $lembur1_rupiah=collect( $rekap_lembur)->sum('lembur1_rupiah');
    //         $lembur2_rupiah=collect( $rekap_lembur)->sum('lembur2_rupiah');
    //         $lembur3_rupiah=collect( $rekap_lembur)->sum('lembur3_rupiah');
    //         $lembur4_rupiah=collect( $rekap_lembur)->sum('lembur4_rupiah');
    //         $total_lembur_rupiah=collect( $rekap_lembur)->sum('total_lembur_rupiah');

    //         $rekap_iks=RekapPerhitunganIKS::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->get();
    //         $rekap_dtpc=RekapPerhitunganDTPC::where('enroll_id',$value->enroll_id)->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->get();


    //         $potongan_iks_menit=collect($rekap_iks)->sum('lama_ijin_menit');
    //         $potongan_dt_menit=collect($rekap_dtpc)->sum('jumlah_menit_absen_dt');
    //         $potongan_pc_menit=collect($rekap_dtpc)->sum('jumlah_menit_absen_pc');
    //         $potongan_dtpc_menit=collect($rekap_dtpc)->sum('jumlah_menit_absen_dtpc');

    //         $potongan_iks_rupiah=collect($rekap_iks)->sum('potongan_iks_rupiah');
    //         $potongan_dt_rupiah=collect($rekap_dtpc)->sum('potongan_dt_rupiah');
    //         $potongan_pc_rupiah=collect($rekap_dtpc)->sum('potongan_pc_rupiah');
    //         $potongan_dtpc_rupiah=collect($rekap_dtpc)->sum('potongan_dtpc_rupiah');

    //         $Bpjs=EmployeeBpjs::where('enroll_id',$value->enroll_id)->where('periode_kehadiran',$periode_payroll)->first();

    //         // 'koreksi_upah_rupiah'=>$koreksi_upah->sum('jumlah_rp_potongan')??0,
    //         // 'koreksi_potongan_rupiah'=>$koreksi_potongan->sum('jumlah_rp_potongan')??0,
    //         $koreksi_upah_rupiah=DataKoreksiUpah::where('enroll_id',$value->enroll_id)->where('periode_tanggal_koreksi',$periode_payroll2)->sum('jumlah_rp_potongan');

    //         $koreksi_potongan_rupiah=DataKoreksiPotongan::where('enroll_id',$value->enroll_id)->where('periode_tanggal_koreksi',$periode_payroll2)->sum('jumlah_rp_potongan');

    //         $tunjangan=TunjanganKaryawan::where('enroll_id',$value->enroll_id)->where('periode_payroll',$periode_payroll)->first();

    //         list($periode_tahun_payroll, $periode_bulan_payroll) = explode("-", $rekap_kehadiran->periode_tahun_bulan??null);

    //         //tunjangan
    //         $tanggal_masuk = $value->join_date;

    //         // hitung selisih tahun antara tanggal masuk dan sekarang
    //         $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_akhir))->y;

    //         // tentukan besaran tunjangan berdasarkan masa kerja
    //         if ($selisih_tahun < 1) {
    //             $tunjangan = 0;
    //         } elseif ($selisih_tahun < 3) {
    //             $tunjangan = 2500;
    //         } elseif ($selisih_tahun < 6) {
    //             $tunjangan = 5000;
    //         }elseif ($selisih_tahun < 9) {
    //             $tunjangan = 7500;
    //         }elseif ($selisih_tahun < 12) {
    //             $tunjangan = 10000;
    //         }else{
    //             $tunjangan = 12500;
    //         }

    //         $data[]=[
    //             'kode_rekap_payroll'=>$rekap_kehadiran->kode_rekap,
    //             'periode_kehadiran'=>$rekap_kehadiran->periode_payroll,
    //             'periode_tahun_payroll'=>$periode_tahun_payroll,
    //             'periode_bulan_payroll'=>$periode_bulan_payroll,
    //             'enroll_id'=>$value->enroll_id,
    //             'nik'=>$value->nik,
    //             'kode_grade'=>$value->kode_grade,
    //             'employee_name'=>$value->employee_name,
    //             'tanggal_resign'=>$value->tanggal_resign,
    //             'kehadiran_iby'=>$rekap_kehadiran->kehadiran_iby,
    //             'kehadiran_itb'=>$rekap_kehadiran->kehadiran_itb,
    //             'kehadiran_m'=>$rekap_kehadiran->kehadiran_m,
    //             'kehadiran_dt'=>$rekap_kehadiran->kehadiran_dt,
    //             'kehadiran_pc'=>$rekap_kehadiran->kehadiran_pc,
    //             'kehadiran_dtpc'=>$rekap_kehadiran->kehadiran_dtpc,
    //             'kehadiran_lby'=>$rekap_kehadiran->kehadiran_lby,
    //             'kehadiran_lsm'=>$rekap_kehadiran->kehadiran_lsm,
    //             'kehadiran_r'=>$rekap_kehadiran->kehadiran_r,
    //             'kehadiran_ok'=>$rekap_kehadiran->kehadiran_ok,
    //             'kehadiran_tk'=>$rekap_kehadiran->kehadiran_tk,
    //             'total_kehadiran'=>$rekap_kehadiran->total_kehadiran,
    //             'total_kehadiran_net'=>$rekap_kehadiran->total_kehadiran_net,
    //             'ptkp'=>$value->ptkp,
    //             'upah_per_bulan'=>$rekap_kehadiran->gaji_pokok,
    //             'upah_per_hari'=>$rekap_kehadiran->gaji_harian,
    //             'upah_per_menit'=>$rekap_kehadiran->gaji_menit,

    //             'tunjangan_karyawan_rupiah'=>$tunjangan,
    //             'premi_karyawan'=>0,

    //             'lembur_1'=>$lembur_1,
    //             'lembur_2'=>$lembur_2,
    //             'lembur_3'=>$lembur_3,
    //             'lembur_4'=>$lembur_4,
    //             'total_lembur_1234'=>$total_lembur_1234,
    //             'lembur1_rupiah'=>$lembur1_rupiah,
    //             'lembur2_rupiah'=>$lembur2_rupiah,
    //             'lembur3_rupiah'=>$lembur3_rupiah,
    //             'lembur4_rupiah'=>$lembur4_rupiah,
    //             'total_lembur_rupiah'=>$total_lembur_rupiah,

    //             'pendapatan_lainnya_rupiah'=>0,

    //             'koreksi_upah_rupiah'=>$koreksi_upah_rupiah,
    //             'koreksi_potongan_rupiah'=>$koreksi_potongan_rupiah,

    //             'potongan_iks_menit'=>$potongan_iks_menit,
    //             'potongan_dt_menit'=>$potongan_dt_menit,
    //             'potongan_pc_menit'=>$potongan_pc_menit,
    //             'potongan_dtpc_menit'=>$potongan_dtpc_menit,

    //             'potongan_iks_rupiah'=>$potongan_iks_rupiah,
    //             'potongan_dt_rupiah'=>$potongan_dt_rupiah,
    //             'potongan_pc_rupiah'=>$potongan_pc_rupiah,
    //             'potongan_dtpc_rupiah'=>$potongan_dtpc_rupiah,
    //             'potongan_kehadiran_rupiah'=>$rekap_kehadiran->potongan_kehadiran_rupiah,


    //             // 'upah_bruto_rupiah'=>$value,
    //             // 'upah_neto_rupiah'=>$value,

    //             // 'upah_bersih_rupiah'=>$value,
    //             // 'total_upah_thp_rupiah'=>$value,
    //             // 'jumlah_potongan_rupiah'=>$value,


    //             'pph21'=>0, // dari mana?
    //             'iuran_serikat_rupiah'=>0, // dari mana?
    //             'iuran_koperasi'=>0, // dari mana?
    //             'potongan_kasbon_rupiah'=>0, // dari mana?

    //             'bpjs_tk_jkm_rupiah'=> $Bpjs->bpjs_tk_jkm_bruto_rupiah + $Bpjs->bpjs_tk_jkm_neto_rupiah??0,
    //             'bpjs_tk_jkm_perusahaan_rupiah'=> $Bpjs->bpjs_tk_jkm_bruto_rupiah??0,
    //             'bpjs_tk_jkm_karyawan_rupiah'=> $Bpjs->bpjs_tk_jkm_neto_rupiah??0,
    //             'bpjs_tk_jkk_rupiah'=> $Bpjs->bpjs_tk_jkk_bruto_rupiah + $Bpjs->bpjs_tk_jkk_neto_rupiah??0,
    //             'bpjs_tk_jkk_perusahaan_rupiah'=> $Bpjs->bpjs_tk_jkk_bruto_rupiah??0,
    //             'bpjs_tk_jkk_karyawan_rupiah'=> $Bpjs->bpjs_tk_jkk_neto_rupiah??0,
    //             'bpjs_tk_jht_rupiah'=> $Bpjs->bpjs_tk_jht_bruto_rupiah + $Bpjs->bpjs_tk_jht_neto_rupiah??0,
    //             'bpjs_tk_jht_perusahaan_rupiah'=> $Bpjs->bpjs_tk_jht_bruto_rupiah??0,
    //             'bpjs_tk_jht_karyawan_rupiah'=> $Bpjs->bpjs_tk_jht_neto_rupiah??0,
    //             'bpjs_tk_jpn_rupiah'=> $Bpjs->bpjs_tk_jpn_bruto_rupiah + $Bpjs->bpjs_tk_jpn_neto_rupiah??0,
    //             'bpjs_tk_jpn_perusahaan_rupiah'=> $Bpjs->bpjs_tk_jpn_bruto_rupiah??0,
    //             'bpjs_tk_jpn_karyawan_rupiah'=> $Bpjs->bpjs_tk_jpn_neto_rupiah??0,
    //             'bpjs_ks_jkn_rupiah'=> $Bpjs->bpjs_ks_jkn_bruto_rupiah + $Bpjs->bpjs_ks_jkn_neto_rupiah??0,
    //             'bpjs_ks_jkn_perusahaan_rupiah'=> $Bpjs->bpjs_ks_jkn_bruto_rupiah??0,
    //             'bpjs_ks_jkn_karyawan_rupiah'=> $Bpjs->bpjs_ks_jkn_neto_rupiah??0,
    //             'total_bpjs_tk'=>$Bpjs->bpjs_tk_jkm_neto_rupiah + $Bpjs->bpjs_tk_jkk_neto_rupiah + $Bpjs->bpjs_tk_jht_neto_rupiah + $Bpjs->bpjs_tk_jpn_neto_rupiah??0,
    //             'total_bpjs_ks'=>$Bpjs->bpjs_ks_jkn_neto_rupiah??0,

    //             'jabatan_karyawan'=>$value->status_jabatan,
    //             'nama_bagian'=>$value->dept()->first()->sub_dept_name??'',
    //             'nama_department'=>$value->dept()->first()->department_name??'',
    //             'kategori_karyawan'=>$value->status_staff,
    //             'aktif_karyawan'=>$value->status_aktif,
    //             'jenis_kelamin'=>$value->jenis_kelamin,
    //             'site_nirwana_name'=>$value->site_nirwana_name,
    //             'nama_bank'=>$value->nama_bank==null||$value->nama_bank==""||$value->nama_bank=="-"?'TUNAI':$value->nama_bank,
    //             'nomor_rekening_bank'=>$value->nomor_rekening_bank==null||$value->nomor_rekening_bank==""||$value->nomor_rekening_bank=="-"?'TRANSFER':$value->nomor_rekening_bank,
    //             'npwp'=>$value->npwp,
    //             'operator'=>'sistem',
    //         ];
    //     }
    //     // dd($data);

    //     $records=[];
    //     foreach ($data as $k => $v) {

    //         $upah_bruto_rupiah=($v['upah_per_bulan']+$v['tunjangan_karyawan_rupiah']+$v['premi_karyawan']+$v['total_lembur_rupiah']+$v['pendapatan_lainnya_rupiah']+$v['koreksi_upah_rupiah'])-
    //             ($v['koreksi_potongan_rupiah']+$v['potongan_iks_rupiah']+$v['potongan_dtpc_rupiah']+$v['potongan_kehadiran_rupiah']);

    //         $upah_neto_rupiah=($v['upah_per_bulan']+$v['tunjangan_karyawan_rupiah']+$v['premi_karyawan']+$v['total_lembur_rupiah']+$v['pendapatan_lainnya_rupiah']+$v['koreksi_upah_rupiah'])-
    //             ($v['koreksi_potongan_rupiah']+$v['potongan_iks_rupiah']+$v['potongan_dtpc_rupiah']+$v['potongan_kehadiran_rupiah'])-$v['pph21'];

    //         $upah_bersih_rupiah=$upah_neto_rupiah-($v['total_bpjs_tk']+$v['total_bpjs_ks']+$v['iuran_koperasi']+$v['iuran_serikat_rupiah']);

    //         $total_upah_thp_rupiah=$upah_neto_rupiah-($v['total_bpjs_tk']+$v['total_bpjs_ks']+$v['iuran_koperasi']+$v['iuran_serikat_rupiah'])-$v['potongan_kasbon_rupiah'];

    //         $jumlah_potongan_rupiah=($v['total_bpjs_tk']+$v['total_bpjs_ks']+$v['iuran_koperasi']+$v['iuran_serikat_rupiah']);

    //         $records=[
    //             'kode_rekap_payroll'=>$v['kode_rekap_payroll'],
    //             'periode_kehadiran'=>$v['periode_kehadiran'],
    //             'periode_tahun_payroll'=>$v['periode_tahun_payroll'],
    //             'periode_bulan_payroll'=>$v['periode_bulan_payroll'],
    //             'enroll_id'=>$v['enroll_id'],
    //             'nik'=>$v['nik'],
    //             'kode_grade'=>$v['kode_grade'],
    //             'site_nirwana_name'=>$v['site_nirwana_name'],
    //             'employee_name'=>$v['employee_name'],
    //             'tanggal_resign'=>$v['tanggal_resign'],
    //             'kehadiran_iby'=>$v['kehadiran_iby'],
    //             'kehadiran_itb'=>$v['kehadiran_itb'],
    //             'kehadiran_m'=>$v['kehadiran_m'],
    //             'kehadiran_dt'=>$v['kehadiran_dt'],
    //             'kehadiran_pc'=>$v['kehadiran_pc'],
    //             'kehadiran_dtpc'=>$v['kehadiran_dtpc'],
    //             'kehadiran_lby'=>$v['kehadiran_lby'],
    //             'kehadiran_lsm'=>$v['kehadiran_lsm'],
    //             'kehadiran_r'=>$v['kehadiran_r'],
    //             'kehadiran_ok'=>$v['kehadiran_ok'],
    //             'kehadiran_tk'=>$v['kehadiran_tk'],
    //             'total_kehadiran'=>$v['total_kehadiran'],
    //             'total_kehadiran_net'=>$v['total_kehadiran_net'],
    //             'ptkp'=>$v['ptkp'],
    //             'upah_per_bulan'=>$v['upah_per_bulan'],
    //             'upah_per_hari'=>$v['upah_per_hari'],
    //             'upah_per_menit'=>$v['upah_per_menit'],

    //             'tunjangan_karyawan_rupiah'=>$v['tunjangan_karyawan_rupiah'],
    //             'premi_karyawan'=>$v['premi_karyawan'],

    //             'lembur_1'=>$v['lembur_1'],
    //             'lembur_2'=>$v['lembur_2'],
    //             'lembur_3'=>$v['lembur_3'],
    //             'lembur_4'=>$v['lembur_4'],
    //             'total_lembur_1234'=>$v['total_lembur_1234'],
    //             'lembur1_rupiah'=>$v['lembur1_rupiah'],
    //             'lembur2_rupiah'=>$v['lembur2_rupiah'],
    //             'lembur3_rupiah'=>$v['lembur3_rupiah'],
    //             'lembur4_rupiah'=>$v['lembur4_rupiah'],
    //             'total_lembur_rupiah'=>$v['total_lembur_rupiah'],

    //             'pendapatan_lainnya_rupiah'=>$v['pendapatan_lainnya_rupiah'],

    //             'koreksi_upah_rupiah'=>$v['koreksi_upah_rupiah'],
    //             'koreksi_potongan_rupiah'=>$v['koreksi_potongan_rupiah'],

    //             'potongan_iks_menit'=>$v['potongan_iks_menit'],
    //             'potongan_dt_menit'=>$v['potongan_dt_menit'],
    //             'potongan_pc_menit'=>$v['potongan_pc_menit'],
    //             'potongan_dtpc_menit'=>$v['potongan_dtpc_menit'],
    //             'potongan_iks_rupiah'=>$v['potongan_iks_rupiah'],
    //             'potongan_dt_rupiah'=>$v['potongan_dt_rupiah'],
    //             'potongan_pc_rupiah'=>$v['potongan_pc_rupiah'],
    //             'potongan_dtpc_rupiah'=>$v['potongan_dtpc_rupiah'],
    //             'potongan_kehadiran_rupiah'=>$v['potongan_kehadiran_rupiah'],


    //             'upah_bruto_rupiah'=>$upah_bruto_rupiah??0,
    //             'upah_neto_rupiah'=>$upah_neto_rupiah??0,

    //             'upah_bersih_rupiah'=>$upah_bersih_rupiah??0,
    //             'total_upah_thp_rupiah'=>$total_upah_thp_rupiah??0,
    //             'jumlah_potongan_rupiah'=>$jumlah_potongan_rupiah??0,


    //             'pph21'=>$v['pph21'], // dari mana?
    //             'iuran_serikat_rupiah'=>$v['iuran_serikat_rupiah'], // dari mana?
    //             'iuran_koperasi'=>$v['iuran_koperasi'], // dari mana?
    //             'potongan_kasbon_rupiah'=>$v['potongan_kasbon_rupiah'], // dari mana?

    //             'bpjs_tk_jkm_rupiah'=> $v['bpjs_tk_jkm_rupiah'],
    //             'bpjs_tk_jkm_perusahaan_rupiah'=> $v['bpjs_tk_jkm_perusahaan_rupiah'],
    //             'bpjs_tk_jkm_karyawan_rupiah'=> $v['bpjs_tk_jkm_karyawan_rupiah'],
    //             'bpjs_tk_jkk_rupiah'=> $v['bpjs_tk_jkk_rupiah'],
    //             'bpjs_tk_jkk_perusahaan_rupiah'=> $v['bpjs_tk_jkk_perusahaan_rupiah'],
    //             'bpjs_tk_jkk_karyawan_rupiah'=> $v['bpjs_tk_jkk_karyawan_rupiah'],
    //             'bpjs_tk_jht_rupiah'=> $v['bpjs_tk_jht_rupiah'],
    //             'bpjs_tk_jht_perusahaan_rupiah'=> $v['bpjs_tk_jht_perusahaan_rupiah'],
    //             'bpjs_tk_jht_karyawan_rupiah'=> $v['bpjs_tk_jht_karyawan_rupiah'],
    //             'bpjs_tk_jpn_rupiah'=> $v['bpjs_tk_jpn_rupiah'],
    //             'bpjs_tk_jpn_perusahaan_rupiah'=> $v['bpjs_tk_jpn_perusahaan_rupiah'],
    //             'bpjs_tk_jpn_karyawan_rupiah'=> $v['bpjs_tk_jpn_karyawan_rupiah'],
    //             'bpjs_ks_jkn_rupiah'=> $v['bpjs_ks_jkn_rupiah'],
    //             'bpjs_ks_jkn_perusahaan_rupiah'=> $v['bpjs_ks_jkn_perusahaan_rupiah'],
    //             'bpjs_ks_jkn_karyawan_rupiah'=> $v['bpjs_ks_jkn_karyawan_rupiah'],
    //             'total_bpjs_tk'=>$v['total_bpjs_tk'],
    //             'total_bpjs_ks'=>$v['total_bpjs_ks'],

    //             'jabatan_karyawan'=>$v['jabatan_karyawan'],
    //             'nama_bagian'=>$v['nama_bagian'],
    //             'nama_department'=>$v['nama_department'],
    //             'kategori_karyawan'=>$v['kategori_karyawan'],
    //             'aktif_karyawan'=>$v['aktif_karyawan'],
    //             'jenis_kelamin'=>$v['jenis_kelamin'],
    //             'nama_bank'=>$v['nama_bank'],
    //             'nomor_rekening_bank'=>$v['nomor_rekening_bank'],
    //             'npwp'=>$v['npwp'],
    //             'operator'=>$v['operator'],
    //         ];

    //         $count=RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$v['kode_rekap_payroll'])->count();
    //         if($count){
    //             RekapPerhitunganPayroll::where( 'kode_rekap_payroll',$v['kode_rekap_payroll'])->update($records);
    //         }
    //         else{
    //             RekapPerhitunganPayroll::create($records);

    //         }
    //     }

    //     return true;
    // }


 // // rekap lembur
    // public function rekap_lembur($bulan_priode)
    // {
    //     // $tanggal_awal='2023-02-26';
    //     // $tanggal_akhir='2023-03-25';

    //     $bulan_sekarang1 = strtotime(date( $bulan_priode));

    //     $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
    //     $bulan_sebelum=date('Y-m-', $bulan_sebelum);
    //     $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

    //     $tanggal_awal=$bulan_sebelum.'26';
    //     $tanggal_akhir=$bulan_sekarang.'25';

    //     $a=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('nomor_form_lembur','!=',null)->get();

    //     // $a=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('nomor_form_lembur','!=',null)->where('enroll_id','1755')->get();

    //     foreach ($a as $key => $value) {
    //         $y=DataLembur::where('enroll_id',$value->enroll_id)->where('nomor_form_lembur',$value->nomor_form_lembur)->first();
    //         // dd($y);
    //         $jumlah_jam_kerja=date_diff(date_create($value->mulai_jam_kerja),date_create($value->akhir_jam_kerja));
    //         $jam_efektif_kerja=date_diff(date_create($value->absen_masuk_kerja),date_create($value->absen_pulang_kerja));
    //         $final_mulai_jam_lembur=date('H:i:s', strtotime($value->mulai_jam_lembur));
    //         $final_total_jam_lembur=date_diff(date_create($final_mulai_jam_lembur),date_create($value->absen_pulang_kerja));

    //         $salary=EmployeeGrading::where('enroll_id',$value->enroll_id)->latest()->first();
    //         $salary_bulanan=$salary->salary_bulanan??0;

    //         if($value->kode_hari==5 || $value->kode_hari==6 || $value->status_absen=='LN'){
    //             $kerjalibur='LIBUR';
    //             $l1=0;
    //             $l2=($y->jumlah_jam_lembur <= 8) ? $y->jumlah_jam_lembur : 8;
    //             if($y->jumlah_jam_lembur > 9){
    //                 $l3=1;
    //                 $l4=max($y->jumlah_jam_lembur -9, 0);
    //             }
    //             else if($y->jumlah_jam_lembur > 8 && $y->jumlah_jam_lembur <= 9 ){
    //                 $l3=max($y->jumlah_jam_lembur -8, 0);
    //                 $l4=0;
    //             }
    //             else{
    //                 $l3=0;
    //                 $l4=0;
    //             }
    //         }
    //         else{
    //             $kerjalibur='KERJA';
    //             $l1 = ($y->jumlah_jam_lembur <= 1) ? $y->jumlah_jam_lembur : 1;
    //             $l2 = max($y->jumlah_jam_lembur - 1, 0);
    //             $l3=0;
    //             $l4=0;

    //         }
    //         if($value->kode_hari==6 || $value->status_absen=='LN'){
    //             $l1_rupiah=$l1*($salary_bulanan/173*1);
    //             $l2_rupiah=$l2*($salary_bulanan/173*1);
    //             $l3_rupiah=$l3*($salary_bulanan/173*2);
    //             $l4_rupiah=$l4*($salary_bulanan/173*2);
    //         }
    //         else{
    //             $l1_rupiah=$l1*($salary_bulanan/173*1);
    //             $l2_rupiah=$l2*($salary_bulanan/173*1);
    //             $l3_rupiah=$l3*($salary_bulanan/173*1);
    //             $l4_rupiah=$l4*($salary_bulanan/173*1);
    //         }

    //         $data=[
    //             'uuid'=>Str::uuid('uuid'),
    //             'tanggal_berjalan'=>$value->tanggal_berjalan,
    //             'kode_hari'=>$value->kode_hari,
    //             'nama_hari'=>$value->nama_hari,
    //             'kerjalibur'=>$kerjalibur,
    //             'holiday_name'=>$value->holiday_name,
    //             'nomor_form_lembur'=>$value->nomor_form_lembur,
    //             'enroll_id'=>$value->enroll_id,
    //             'nik'=>$value->nik,
    //             'employee_name'=>$value->employee_name,
    //             'site_nirwana_id'=>$value->site_nirwana_id,
    //             'site_nirwana_name'=>$value->site_nirwana_name,
    //             'department_id'=>$value->department_id,
    //             'department_name'=>$value->department_name,
    //             'sub_dept_id'=>$value->sub_dept_id,
    //             'sub_dept_name'=>$value->sub_dept_name,
    //             'posisi_name'=>$value->posisi_name,
    //             'mulai_jam_kerja'=>$value->mulai_jam_kerja,
    //             'akhir_jam_kerja'=>$value->akhir_jam_kerja,
    //             'jumlah_jam_kerja'=>sprintf('%02d:%02d:%02d', $jumlah_jam_kerja->h, $jumlah_jam_kerja->i, $jumlah_jam_kerja->s),
    //             'absen_masuk_kerja'=>$value->absen_masuk_kerja,
    //             'absen_pulang_kerja'=>$value->absen_pulang_kerja,
    //             'jam_efektif_kerja'=>sprintf('%02d:%02d:%02d', $jam_efektif_kerja->h, $jam_efektif_kerja->i, $jam_efektif_kerja->s),
    //             'mulai_jam_lembur'=>$value->mulai_jam_lembur,
    //             'akhir_jam_lembur'=>$value->akhir_jam_lembur,
    //             'final_mulai_jam_lembur'=>$final_mulai_jam_lembur,
    //             'final_selesai_jam_lembur'=>$value->absen_pulang_kerja,
    //             'final_total_jam_lembur'=>sprintf('%02d:%02d:%02d', $final_total_jam_lembur->h, $final_total_jam_lembur->i, $final_total_jam_lembur->s),
    //             'final_jam_istirahat_lembur'=>$y->jumlah_jam_istirahat,
    //             'final_total_menit_lembur'=>($final_total_jam_lembur->h*60)+$final_total_jam_lembur->i,
    //             'final_jam_lembur_roundown'=> $final_total_jam_lembur->h,
    //             'final_menit_lembur_roundown'=>$final_total_jam_lembur->i,
    //             'lembur_1'=>$l1,
    //             'lembur_2'=>$l2,
    //             'lembur_3'=>$l3,
    //             'lembur_4'=>$l4,
    //             'total_lembur_1234'=>$l1+$l2+$l3+$l4,
    //             'salary'=>$salary_bulanan,
    //             'lembur1_rupiah'=> $l1_rupiah,
    //             'lembur2_rupiah'=> $l2_rupiah,
    //             'lembur3_rupiah'=> $l3_rupiah,
    //             'lembur4_rupiah'=> $l4_rupiah,
    //             'total_lembur_rupiah'=> $l1_rupiah + $l2_rupiah +  $l3_rupiah +  $l4_rupiah,
    //             'operator'=>'system',
    //         ];
    //         $count=RekapPerhitunganLembur::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->count();
    //         if($count){
    //             RekapPerhitunganLembur::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->update($data);
    //         }
    //         else{
    //             RekapPerhitunganLembur::create($data);

    //         }
    //     }
    //     return true;
    // }
}
