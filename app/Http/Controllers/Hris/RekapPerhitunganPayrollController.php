<?php

namespace App\Http\Controllers\Hris;

use App\Exports\RekapPerhitunganPayrollExport;
use App\Exports\RekapPerhitunganPayrollExportUMK2023;
use App\Exports\RekapPerhitunganPayrollExportUMK2024;
use App\Exports\RekapPerhitunganPayrollExportJanuari2024;
use App\Http\Controllers\AdminBaseController;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\RekapPerhitunganPayroll;
use App\Models\EmployeeAtribut;
use App\Models\RefAbsenIjin;
use App\Models\GradingSalary;
use App\Models\BpjsSetting;
use App\Models\EmployeeBpjs;
use App\Exports\DailyLaborCosts;
use App\Exports\DailyLaborCost2;
use App\Models\RekapPerhitunganLembur;
use App\Models\DataKoreksiPotongan;
use App\Models\DepartmentAll;
use App\Models\DailyLaborCost;
use App\Exports\summaryDepartmentExport;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Datatables;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use \avadim\FastExcelLaravel\Excel as FastExcel;
use \avadim\FastExcelWriter\Style;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

/**
 * Class RekapPerhitunganPayrollController
 * @package App\Http\Controllers\Hris
 */
class RekapPerhitunganPayrollController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Rekap Perhitungan Payroll';
    }
    public function ajax_data(Request $request){
        $periode_payroll = request()->periode_payroll;
        $periode_tahun_payroll = substr($periode_payroll,0,4);
        $periode_bulan_payroll = substr($periode_payroll,5,6);
        $department_id = request()->department_id;
        $sub_dept_id = request()->sub_dept_id;
        $status_staff = request()->status_staff;
        $periode_umk = request()->periode_umk;
        if(request()->ajax()) {
            $columns = array(
                0 => 'enroll_id',
                1 => 'nik',
                2 => 'employee_name',
                3 => 'total_kehadiran_net',
                4 => 'upah_per_bulan',
                5 => 'tunjangan_karyawan_rupiah',
                6 => 'lembur1_rupiah',
                7 => 'lembur2_rupiah',
                8 => 'lembur3_rupiah',
                9 => 'lembur4_rupiah',
                10 => 'koreksi_upah_rupiah',
                11 => 'koreksi_potongan_rupiah',
                12 => 'potongan_kehadiran_rupiah',
                13 => 'potongan_jam',
                14 => 'upah_bruto_rupiah',
                15 => 'pph21',
                16 => 'upah_neto_rupiah',
                17 => 'total_bpjs_tk',
                18 => 'total_bpjs_ks',
                19 => 'total_potongan',
                20 => 'jumlah'
            );
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $totalData = 0;
            $totalFiltered = 0;
            $inDepartment='';
            $inSubDepartment='';
            $inStatusStaff='';
            $inSearchValue='';
            $search = $request->input('search.value');
            if(isset(DepartmentAll::select('department_name')->where('department_id',$department_id)->groupBy('department_id')->pluck('department_name')[0])){
                $department_name=DepartmentAll::select('department_name')->where('department_id',$department_id)->groupBy('department_id')->pluck('department_name')[0];
                $inDepartment = ' AND nama_department = "' . $department_name . '"';
            }
            if(isset(DepartmentAll::select('sub_dept_name')->where('sub_dept_id',$sub_dept_id)->pluck('sub_dept_name')[0])){
                $department_name=DepartmentAll::select('sub_dept_name')->where('sub_dept_id',$sub_dept_id)->pluck('sub_dept_name')[0];
                $inSubDepartment = ' AND nama_bagian = "' . $department_name . '"';
            }
            if($status_staff){
                $inStatusStaff = ' AND kategori_karyawan = "' . $status_staff . '"';
            }
            if($periode_umk){
                $inPeriodeUMK = ' AND periode_umk = "' . $periode_umk . '"';
            }else{
                $inPeriodeUMK = " AND periode_umk is NULL";
            }
            if($search){
                $inSearchValue = ' AND (employee_name LIKE "'.$search.'%" OR nik LIKE "'.$search.'%" OR enroll_id LIKE "'.$search.'%" OR nik LIKE "'.$search.'%")';
            }
            $query =  RekapPerhitunganPayroll::selectRaw('enroll_id,nik,employee_name,total_kehadiran_net,upah_per_bulan,tunjangan_karyawan_rupiah,lembur1_rupiah,lembur2_rupiah,lembur3_rupiah,lembur4_rupiah,koreksi_upah_rupiah,koreksi_potongan_rupiah,potongan_kehadiran_rupiah,potongan_dtpc_rupiah,potongan_iks_rupiah,upah_bruto_rupiah,pph21,upah_neto_rupiah,total_bpjs_tk,total_bpjs_ks,iuran_serikat_rupiah,iuran_koperasi,total_upah_thp_rupiah')->whereRaw('periode_tahun_payroll = "' . $periode_tahun_payroll . '" and periode_bulan_payroll = "' . $periode_bulan_payroll.'"'.$inDepartment.''.$inSubDepartment.''.$inStatusStaff.''.$inPeriodeUMK.''.$inSearchValue.'')->offset($start)->limit($limit)->orderBy($order,$dir)->get();
            $totalData = RekapPerhitunganPayroll::whereRaw('periode_tahun_payroll = "' . $periode_tahun_payroll . '" and periode_bulan_payroll = "' . $periode_bulan_payroll.'"'.$inDepartment.''.$inSubDepartment.''.$inStatusStaff.''.$inPeriodeUMK.''.$inSearchValue.'')->count();
            $totalFiltered = $totalData;
            $data = array();
            foreach ($query as $q)
            {
                if($q->lembur1_rupiah==0){$lembur1=0;}else{$lembur1=number_format( $q->lembur1_rupiah , 2 , ',' , '.' );}
                if($q->lembur2_rupiah==0){$lembur2=0;}else{$lembur2=number_format( $q->lembur2_rupiah , 2 , ',' , '.' );}
                if($q->lembur3_rupiah==0){$lembur3=0;}else{$lembur3=number_format( $q->lembur3_rupiah , 2 , ',' , '.' );}
                if($q->lembur4_rupiah==0){$lembur4=0;}else{$lembur4=number_format( $q->lembur4_rupiah , 2 , ',' , '.' );}
                if($q->koreksi_upah_rupiah==0){$koreksi_upah_rupiah=0;}else{$koreksi_upah_rupiah=number_format( $q->koreksi_upah_rupiah , 2 , ',' , '.' );}
                if($q->koreksi_potongan_rupiah==0){$koreksi_potongan_rupiah=0;}else{$koreksi_potongan_rupiah=number_format( $q->koreksi_potongan_rupiah , 2 , ',' , '.' );}
                if($q->potongan_kehadiran_rupiah==0){$potongan_kehadiran_rupiah=0;}else{$potongan_kehadiran_rupiah=number_format( $q->potongan_kehadiran_rupiah , 2 , ',' , '.' );}
                if($q->potongan_dtpc_rupiah+$q->potongan_iks_rupiah==0){$potongan_jam=0;}else{$potongan_jam=number_format( $q->potongan_dtpc_rupiah+$q->potongan_iks_rupiah , 2 , ',' , '.' );}
                $nestedData['enroll_id'] = (int)$q->enroll_id;
                $nestedData['nik'] = $q->nik;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['total_kehadiran_net'] = $q->total_kehadiran_net;
                $nestedData['upah_per_bulan'] = number_format( $q->upah_per_bulan , 0 , ',' , '.' );
                $nestedData['tunjangan_karyawan_rupiah'] = $q->tunjangan_karyawan_rupiah;
                $nestedData['lembur1_rupiah'] = $lembur1;
                $nestedData['lembur2_rupiah'] = $lembur2;
                $nestedData['lembur3_rupiah'] = $lembur3;
                $nestedData['lembur4_rupiah'] = $lembur4;
                $nestedData['koreksi_upah_rupiah'] = $koreksi_upah_rupiah;
                $nestedData['koreksi_potongan_rupiah'] = $koreksi_potongan_rupiah;
                $nestedData['potongan_kehadiran_rupiah'] = $potongan_kehadiran_rupiah;
                $nestedData['potongan_jam'] =  $potongan_jam;
                $nestedData['upah_bruto_rupiah'] = number_format( $q->upah_bruto_rupiah , 2 , ',' , '.' );
                $nestedData['pph21'] = $q->pph21;
                $nestedData['upah_neto_rupiah'] = number_format( $q->upah_neto_rupiah , 2 , ',' , '.' );
                $nestedData['total_bpjs_tk'] = number_format( $q->total_bpjs_tk , 2 , ',' , '.' );
                $nestedData['total_bpjs_ks'] = number_format( $q->total_bpjs_ks , 2 , ',' , '.' );
                $nestedData['total_potongan'] = number_format( $q->total_bpjs_tk+$q->total_bpjs_ks+$q->iuran_serikat_rupiah+$q->iuran_koperasi , 2 , ',' , '.' );
                $nestedData['jumlah'] = number_format( ceil($q->total_upah_thp_rupiah / 100) * 100 , 0 , ',' , '.' );
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
    public function get_payroll_department(){
        $bulan_priode = request()->periode_payroll;
        $status_staff = request()->status_staff;
        $bulan_sekarang1 = strtotime(date( $bulan_priode));

        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum2 = strtotime("-2 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);
        $bulan_sebelum2=date('Y-m-', $bulan_sebelum2);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';
        $tanggal_awal2=$bulan_sebelum2.'26';
        $tanggal_akhir2=$tanggal_akhir;
        $tanggal_awal_baru = date('m/d/Y', strtotime($tanggal_awal));
        $tanggal_akhir_baru = date('m/d/Y', strtotime($tanggal_akhir));
        $periode_payroll = $tanggal_awal_baru . ' - ' . $tanggal_akhir_baru;

        $data_potongan = $this->potongan($periode_payroll);

        list($year, $month) = explode('-', $bulan_priode);
        list($year_before, $month_before) = explode('-', $bulan_sebelum);
        $departement=DepartmentAll::where('site_nirwana_id','NAG')->groupBy('department_id')->get();
        $data=[];
        foreach ($departement as $key => $value) {
            if($status_staff){
                $payroll=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)
                ->where('kategori_karyawan',$status_staff)->where('nama_department',$value->department_name)
                ->where('total_upah_thp_rupiah_employee','>',0)->whereHas('employee_atribut',function($query)use($tanggal_awal){
                    $query->where('tanggal_resign',null)
                    ->orWhere('tanggal_resign','>',$tanggal_awal);
                })->get();
                $payroll_before=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year_before)->where('periode_bulan_payroll', $month_before)
                ->where('kategori_karyawan',$status_staff)->where('nama_department',$value->department_name)
                ->where('total_upah_thp_rupiah_employee','>',0)->whereHas('employee_atribut',function($query)use($tanggal_awal2){
                    $query->where('tanggal_resign',null)
                    ->orWhere('tanggal_resign','>',$tanggal_awal2);
                })->get();
            }else{
                $payroll=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('nama_department',$value->department_name)
                ->where('total_upah_thp_rupiah_employee','>',0)->whereHas('employee_atribut',function($query)use($tanggal_awal){
                    $query->where('tanggal_resign',null)
                    ->orWhere('tanggal_resign','>',$tanggal_awal);
                })->get();
                $payroll_before=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year_before)->where('periode_bulan_payroll', $month_before)->where('nama_department',$value->department_name)
                ->where('total_upah_thp_rupiah_employee','>',0)->whereHas('employee_atribut',function($query)use($tanggal_awal2){
                    $query->where('tanggal_resign',null)
                    ->orWhere('tanggal_resign','>',$tanggal_awal2);
                })->get();
            }
            if($payroll->sum('upah_bruto_rupiah')==0){$bruto=0;}else{$bruto=number_format( $payroll->sum('upah_bruto_rupiah') , 2 , ',' , '.');}
            $bruto_int=$payroll->sum('upah_bruto_rupiah');
            if($payroll->sum('pph21')==0){$pph21=0;}else{$pph21=number_format( $payroll->sum('pph21') , 2 , ',' , '.');}
            $pph21_int=$payroll->sum('pph21');
            if($payroll->sum('upah_neto_rupiah')==0){$upah_neto_rupiah=0;}else{$upah_neto_rupiah=number_format( $payroll->sum('upah_neto_rupiah') , 2 , ',' , '.');}
            $upah_neto_rupiah_int=$payroll->sum('upah_neto_rupiah');
            if($payroll->sum('total_bpjs_tk')==0){$total_bpjs_tk=0;}else{$total_bpjs_tk=number_format( $payroll->sum('total_bpjs_tk') , 2 , ',' , '.');}
            $total_bpjs_tk_int=$payroll->sum('total_bpjs_tk');
            if($payroll->sum('total_bpjs_ks')==0){$total_bpjs_ks=0;}else{$total_bpjs_ks=number_format( $payroll->sum('total_bpjs_ks') , 2 , ',' , '.');}
            $total_bpjs_ks_int=$payroll->sum('total_bpjs_ks');
            $potongan_lain=$data_potongan->where('department_id',$value->department_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','5')->sum('jumlah_rp_potongan');
            $rp_cuti_tahuna=0;
            $potongan_kehadiran_rupiah= $payroll->sum('potongan_kehadiran_rupiah');
            $rp_pot_jam=$payroll->sum('potongan_iks_rupiah')+$payroll->sum('potongan_dtpc_rupiah');
            $iuran_serikat_rupiah=$payroll->sum('iuran_serikat_rupiah');
            $iuran_koperasi=$payroll->sum('iuran_koperasi');
            if($potongan_lain + $rp_cuti_tahuna + $potongan_kehadiran_rupiah + $rp_pot_jam + $iuran_serikat_rupiah + $iuran_koperasi==0){$potongan=0;}else{$potongan=number_format( $potongan_lain + $rp_cuti_tahuna + $potongan_kehadiran_rupiah + $rp_pot_jam + $iuran_serikat_rupiah + $iuran_koperasi , 0 , ',' , '.');}
            $potongan_int=$potongan_lain + $rp_cuti_tahuna + $potongan_kehadiran_rupiah + $rp_pot_jam + $iuran_serikat_rupiah + $iuran_koperasi;
            if($payroll->sum('total_upah_thp_rupiah_employee')==0){$jumlah=0;}else{$jumlah=number_format( $payroll->sum('total_upah_thp_rupiah_employee') , 0 , ',' , '.');}
            $total_upah_thp_rupiah_int=ceil($payroll->sum('total_upah_thp_rupiah') / 100) * 100;
            $jumlah_int=$payroll->sum('total_upah_thp_rupiah_employee');
            if($payroll_before->sum('total_upah_thp_rupiah_employee')==0){$jumlah_sebelum=0;}else{$jumlah_sebelum=number_format( $payroll_before->sum('total_upah_thp_rupiah_employee'), 0 , ',' , '.');}
            $jumlah_sebelum_int=$payroll_before->sum('total_upah_thp_rupiah_employee');
            if($payroll->sum('bpjs_tk_jkm_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkm_rupiah')+$payroll->sum('bpjs_tk_jkk_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkk_rupiah')+$payroll->sum('bpjs_tk_jht_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jht_rupiah')+$payroll->sum('bpjs_tk_jpn_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jpn_rupiah')+$payroll->sum('bpjs_tk_jkn_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkn_rupiah')==0){$bpjs_tk_perusahaan=0;}else{$bpjs_tk_perusahaan=$payroll->sum('bpjs_tk_jkm_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkm_rupiah')+$payroll->sum('bpjs_tk_jkk_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkk_rupiah')+$payroll->sum('bpjs_tk_jht_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jht_rupiah')+$payroll->sum('bpjs_tk_jpn_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jpn_rupiah')+$payroll->sum('bpjs_tk_jkn_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkn_rupiah');}
            $bpjs_tk_perusahaan_int=$payroll->sum('bpjs_tk_jkm_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkm_rupiah')+$payroll->sum('bpjs_tk_jkk_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkk_rupiah')+$payroll->sum('bpjs_tk_jht_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jht_rupiah')+$payroll->sum('bpjs_tk_jpn_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jpn_rupiah')+$payroll->sum('bpjs_tk_jkn_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkn_rupiah');
            if($payroll->sum('bpjs_ks_jkm_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkm_rupiah')+$payroll->sum('bpjs_ks_jkk_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkk_rupiah')+$payroll->sum('bpjs_ks_jht_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jht_rupiah')+$payroll->sum('bpjs_ks_jpn_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jpn_rupiah')+$payroll->sum('bpjs_ks_jkn_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkn_rupiah')==0){$bpjs_ks_perusahaan=0;}else{$bpjs_ks_perusahaan=$payroll->sum('bpjs_ks_jkm_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkm_rupiah')+$payroll->sum('bpjs_ks_jkk_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkk_rupiah')+$payroll->sum('bpjs_ks_jht_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jht_rupiah')+$payroll->sum('bpjs_ks_jpn_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jpn_rupiah')+$payroll->sum('bpjs_ks_jkn_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkn_rupiah');}
            $bpjs_ks_perusahaan_int=$payroll->sum('bpjs_ks_jkm_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkm_rupiah')+$payroll->sum('bpjs_ks_jkk_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkk_rupiah')+$payroll->sum('bpjs_ks_jht_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jht_rupiah')+$payroll->sum('bpjs_ks_jpn_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jpn_rupiah')+$payroll->sum('bpjs_ks_jkn_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkn_rupiah');

            $data[$key]=[
                'id_department'=>$value->department_id,
                'nama_department'=>$value->department_name,
                'jumlah_karyawan'=>$payroll->count(),
                'bruto'=>$bruto,
                'bruto_int'=>$bruto_int,
                'pph'=>$pph21,
                'pph_int'=>$pph21_int,
                'upah_neto_rupiah'=>$upah_neto_rupiah,
                'upah_neto_rupiah_int'=>$upah_neto_rupiah_int,
                'total_bpjs_tk'=>$total_bpjs_tk,
                'total_bpjs_tk_int'=>$total_bpjs_tk_int,
                'total_bpjs_ks'=>$total_bpjs_ks,
                'total_bpjs_ks_int'=>$total_bpjs_ks_int,
                'potongan'=>$potongan,
                'potongan_int'=>$potongan_int,
                'jumlah'=>$jumlah,
                'jumlah_int'=>$jumlah_int,
                'jumlah_karyawan_sebelum'=>$payroll_before->count(),
                'jumlah_sebelum'=>$jumlah_sebelum,
                'jumlah_sebelum_int'=>$jumlah_sebelum_int,
                'selisih_karyawan'=>$payroll->count()-$payroll_before->count(),
                'selisih_gaji'=>(int)(ceil($payroll->sum('total_upah_thp_rupiah') / 100) * 100)-(int)(ceil($payroll_before->sum('total_upah_thp_rupiah') / 100) * 100),
                'bpjs_tk_perusahaan'=>$bpjs_tk_perusahaan,
                'total_bpjs_tk_all'=>(double)$payroll->sum('total_bpjs_tk')+$bpjs_tk_perusahaan,
                'bpjs_ks_perusahaan'=>$bpjs_ks_perusahaan,
                'total_bpjs_ks_all'=>(double)$payroll->sum('total_bpjs_ks')+$bpjs_ks_perusahaan,
                'periode'=>$year.'-'.$month
            ];
        }
        $arrayData=[];
        foreach($data as $key=>$value){
            if($value['jumlah_karyawan']=='0' && $value['jumlah_karyawan_sebelum']=='0'){
                continue;
            }
            $arrayData[$key]=[
                'id_department'=>$value['id_department'],
                'nama_department'=>$value['nama_department'],
                'jumlah_karyawan'=>$value['jumlah_karyawan'],
                'bruto'=>$value['bruto'],
                'bruto_int'=>$value['bruto_int'],
                'pph'=>$value['pph'],
                'pph_int'=>$value['pph_int'],
                'upah_neto_rupiah'=>$value['upah_neto_rupiah'],
                'upah_neto_rupiah_int'=>$value['upah_neto_rupiah_int'],
                'total_bpjs_tk'=>$value['total_bpjs_tk'],
                'total_bpjs_tk_int'=>$value['total_bpjs_tk_int'],
                'total_bpjs_ks'=>$value['total_bpjs_ks'],
                'total_bpjs_ks_int'=>$value['total_bpjs_ks_int'],
                'potongan'=>$value['potongan'],
                'potongan_int'=>$value['potongan_int'],
                'jumlah'=>$value['jumlah'],
                'jumlah_int'=>$value['jumlah_int'],
                'jumlah_karyawan_sebelum'=>$value['jumlah_karyawan_sebelum'],
                'jumlah_sebelum'=>$value['jumlah_sebelum'],
                'jumlah_sebelum_int'=>$value['jumlah_sebelum_int'],
                'selisih_karyawan'=>$value['selisih_karyawan'],
                'selisih_gaji'=>number_format( $value['selisih_gaji'] , 0 , ',' , '.'),
                'bpjs_tk_perusahaan'=>$value['bpjs_tk_perusahaan'],
                'total_bpjs_tk_all'=>$value['total_bpjs_tk_all'],
                'bpjs_ks_perusahaan'=>$value['bpjs_ks_perusahaan'],
                'total_bpjs_ks_all'=>$value['total_bpjs_ks_all'],
                'periode'=>$value['periode'],
            ];
        }
        $jumlah_karyawan = array_sum(array_column($arrayData,'jumlah_karyawan'));
        $bruto_total = array_sum(array_column($arrayData,'bruto_int'));
        if($bruto_total==0){$bruto_total=0;}else{$bruto_total=number_format( $bruto_total , 2 , ',' , '.');}
        $pph = array_sum(array_column($arrayData,'pph_int'));
        if($pph==0){$pph=0;}else{$pph=number_format( $pph , 2 , ',' , '.');}
        $upah_neto_rupiah_int_total = array_sum(array_column($arrayData,'upah_neto_rupiah_int')); 
        if($upah_neto_rupiah_int_total==0){$upah_neto_rupiah_int_total=0;}else{$upah_neto_rupiah_int_total=number_format( $upah_neto_rupiah_int_total , 2 , ',' , '.');}
        $total_bpjs_tk_total = array_sum(array_column($arrayData,'total_bpjs_tk_int')); 
        if($total_bpjs_tk_total==0){$total_bpjs_tk_total=0;}else{$total_bpjs_tk_total=number_format( $total_bpjs_tk_total , 2 , ',' , '.');}
        $total_bpjs_ks_total = array_sum(array_column($arrayData,'total_bpjs_ks_int')); 
        if($total_bpjs_ks_total==0){$total_bpjs_ks_total=0;}else{$total_bpjs_ks_total=number_format( $total_bpjs_ks_total , 2 , ',' , '.');}
        $potongan_total = array_sum(array_column($arrayData,'potongan_int')); 
        if($potongan_total==0){$potongan_total=0;}else{$potongan_total=number_format( $potongan_total , 2 , ',' , '.');}
        $jumlah_total = array_sum(array_column($arrayData,'jumlah_int')); 
        if($jumlah_total==0){$jumlah_total=0;}else{$jumlah_total=number_format( $jumlah_total , 2 , ',' , '.');}
        $jumlah_karyawan_sebelum_total = array_sum(array_column($arrayData,'jumlah_karyawan_sebelum'));
        $jumlah_sebelum_total = array_sum(array_column($arrayData,'jumlah_sebelum_int')); 
        if($jumlah_sebelum_total==0){$jumlah_sebelum_total=0;}else{$jumlah_sebelum_total=number_format( $jumlah_sebelum_total , 2 , ',' , '.');}
        $selisih_karyawan_total = array_sum(array_column($arrayData,'selisih_karyawan'));
        $selisih_gaji=array_sum(array_column($arrayData,'jumlah_int'));
        $selisih_gaji_before=array_sum(array_column($arrayData,'jumlah_sebelum_int'));
        $selisih_gaji_total=$selisih_gaji-$selisih_gaji_before;
        if($selisih_gaji_total==0){$selisih_gaji_total=0;}else{$selisih_gaji_total=number_format( $selisih_gaji_total , 2 , ',' , '.');}

        $grand_total=[
            'id_department'=>'',
            'nama_department'=>'GRAND TOTAL',
            'jumlah_karyawan'=>$jumlah_karyawan,
            'bruto'=>$bruto_total,
            'bruto_int'=>'',
            'pph'=>$pph,
            'pph_int'=>'',
            'upah_neto_rupiah'=>$upah_neto_rupiah_int_total,
            'upah_neto_rupiah_int'=>'',
            'total_bpjs_tk'=>$total_bpjs_tk_total,
            'total_bpjs_tk_int'=>'',
            'total_bpjs_ks'=>$total_bpjs_ks_total,
            'total_bpjs_ks_int'=>'',
            'potongan'=>$potongan_total,
            'potongan_int'=>'',
            'jumlah'=>$jumlah_total,
            'jumlah_int'=>'',
            'jumlah_karyawan_sebelum'=>$jumlah_karyawan_sebelum_total,
            'jumlah_sebelum'=>$jumlah_sebelum_total,
            'jumlah_sebelum_int'=>'',
            'selisih_karyawan'=>$selisih_karyawan_total,
            'selisih_gaji'=>$selisih_gaji_total,
            'bpjs_tk_perusahaan'=>'',
            'total_bpjs_tk_all'=>'',
            'bpjs_ks_perusahaan'=>'',
            'total_bpjs_ks_all'=>'',
            'periode'=>'',
        ];
        array_push($arrayData,$grand_total);
        return $arrayData;
    }
    public function export_excel_summary_department(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        $periode_payroll=request()->param1;
        $status_staff = request()->param2;
        list($year, $month) = explode('-', $periode_payroll);
        $first=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->first();
        list($tgl_awal, $tgl_akhir) = explode(' s/d ', $first->periode_kehadiran);
        $periode_tahun_payroll=substr($periode_payroll,0,4);
        $periode_bulan_payroll=substr($periode_payroll,5,7);
        $bulan_priode = $periode_payroll;
        $bulan_sekarang1 = strtotime(date( $bulan_priode));
        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';
        $tanggal_awal_baru = date('m/d/Y', strtotime($tanggal_awal));
        $tanggal_akhir_baru = date('m/d/Y', strtotime($tanggal_akhir));
        $periode_payroll = $tanggal_awal_baru . ' - ' . $tanggal_akhir_baru;

        $data_potongan = $this->potongan($periode_payroll);
        $departement=DepartmentAll::where('site_nirwana_id','NAG')->groupBy('department_id')->get();
        list($year_before, $month_before) = explode('-', $bulan_sebelum);
        $data=[];
        foreach ($departement as $key => $value) {
            if($status_staff){
                $payroll=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)
                ->where('kategori_karyawan',$status_staff)->where('nama_department',$value->department_name)
                ->where('total_kehadiran_net','>',0)->get();
                $payroll_before=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year_before)->where('periode_bulan_payroll', $month_before)
                ->where('kategori_karyawan',$status_staff)->where('nama_department',$value->department_name)
                ->where('total_kehadiran_net','>',0)->get();
                $payroll_bni=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)
                ->where('kategori_karyawan',$status_staff)->where('nama_department',$value->department_name)
                ->where('total_kehadiran_net','>',0)->where('nama_bank','BNI')->where(function ($query) use ($tgl_awal){
                    $query->where('tanggal_resign',null)->orWhere('tanggal_resign','>',$tgl_awal);
                })->get();
                $payroll_cimb=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)
                ->where('kategori_karyawan',$status_staff)->where('nama_department',$value->department_name)
                ->where('total_kehadiran_net','>',0)->where('nama_bank','CIMB NIAGA')->where(function ($query) use ($tgl_awal){
                    $query->where('tanggal_resign',null)->orWhere('tanggal_resign','>',$tgl_awal);
                })->get();
                $payroll_other=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)
                ->where('kategori_karyawan',$status_staff)->where('nama_department',$value->department_name)
                ->where('total_kehadiran_net','>',0)->whereNotIn('nama_bank',['BNI','CIMB NIAGA'])->where(function ($query) use ($tgl_awal){
                    $query->where('tanggal_resign',null)->orWhere('tanggal_resign','>',$tgl_awal);
                })->get();
            }else{
                $payroll=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('nama_department',$value->department_name)
                ->where('total_kehadiran_net','>',0)->get();
                $payroll_before=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year_before)->where('periode_bulan_payroll', $month_before)->where('nama_department',$value->department_name)
                ->where('total_kehadiran_net','>',0)->get();
                $payroll_bni=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('nama_department',$value->department_name)
                ->where('total_kehadiran_net','>',0)->where('nama_bank','BNI')->where(function ($query) use ($tgl_awal){
                    $query->where('tanggal_resign',null)->orWhere('tanggal_resign','>',$tgl_awal);
                })->get();
                $payroll_cimb=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('nama_department',$value->department_name)
                ->where('total_kehadiran_net','>',0)->where('nama_bank','CIMB NIAGA')->where(function ($query) use ($tgl_awal){
                    $query->where('tanggal_resign',null)->orWhere('tanggal_resign','>',$tgl_awal);
                })->get();
                $payroll_other=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('nama_department',$value->department_name)
                ->where('total_kehadiran_net','>',0)->whereNotIn('nama_bank',['BNI','CIMB NIAGA'])->where(function ($query) use ($tgl_awal){
                    $query->where('tanggal_resign',null)->orWhere('tanggal_resign','>',$tgl_awal);
                })->get();
            }
            if($payroll->sum('upah_bruto_rupiah')==0){$bruto=0;}else{$bruto=number_format( $payroll->sum('upah_bruto_rupiah') , 2 , ',' , '.');}
            $bruto_int=$payroll->sum('upah_bruto_rupiah');
            if($payroll->sum('pph21')==0){$pph21=0;}else{$pph21=number_format( $payroll->sum('pph21') , 2 , ',' , '.');}
            $pph21_int=$payroll->sum('pph21');
            if($payroll->sum('upah_neto_rupiah')==0){$upah_neto_rupiah=0;}else{$upah_neto_rupiah=number_format( $payroll->sum('upah_neto_rupiah') , 2 , ',' , '.');}
            $upah_neto_rupiah_int=$payroll->sum('upah_neto_rupiah');
            if($payroll->sum('total_bpjs_tk')==0){$total_bpjs_tk=0;}else{$total_bpjs_tk=number_format( $payroll->sum('total_bpjs_tk') , 2 , ',' , '.');}
            $total_bpjs_tk_int=$payroll->sum('total_bpjs_tk');
            if($payroll->sum('total_bpjs_ks')==0){$total_bpjs_ks=0;}else{$total_bpjs_ks=number_format( $payroll->sum('total_bpjs_ks') , 2 , ',' , '.');}
            $total_bpjs_ks_int=$payroll->sum('total_bpjs_ks');
            $potongan_lain=$data_potongan->where('department_id',$value->department_id)->where('status_jabatan','NON STAFF')->where('jenis_potongan','5')->sum('jumlah_rp_potongan');
            $rp_cuti_tahuna=0;
            $potongan_kehadiran_rupiah= $payroll->sum('potongan_kehadiran_rupiah');
            $rp_pot_jam=$payroll->sum('potongan_iks_rupiah')+$payroll->sum('potongan_dtpc_rupiah');
            $iuran_serikat_rupiah=$payroll->sum('iuran_serikat_rupiah');
            $iuran_koperasi=$payroll->sum('iuran_koperasi');
            if($potongan_lain + $rp_cuti_tahuna + $potongan_kehadiran_rupiah + $rp_pot_jam + $iuran_serikat_rupiah + $iuran_koperasi==0){$potongan=0;}else{$potongan=number_format( $potongan_lain + $rp_cuti_tahuna + $potongan_kehadiran_rupiah + $rp_pot_jam + $iuran_serikat_rupiah + $iuran_koperasi , 0 , ',' , '.');}
            $potongan_int=$potongan_lain + $rp_cuti_tahuna + $potongan_kehadiran_rupiah + $rp_pot_jam + $iuran_serikat_rupiah + $iuran_koperasi;
            if(ceil($payroll->sum('total_upah_thp_rupiah') / 100) * 100==0){$jumlah=0;}else{$jumlah=number_format( ceil($payroll->sum('total_upah_thp_rupiah') / 100) * 100 , 0 , ',' , '.');}
            $total_upah_thp_rupiah_int=ceil($payroll->sum('total_upah_thp_rupiah') / 100) * 100;
            $jumlah_int=ceil($payroll->sum('total_upah_thp_rupiah')/100)*100;
            if(ceil($payroll_before->sum('total_upah_thp_rupiah') / 100) * 100==0){$jumlah_sebelum=0;}else{$jumlah_sebelum=number_format( ceil($payroll_before->sum('total_upah_thp_rupiah') / 100) * 100 , 0 , ',' , '.');}
            $jumlah_sebelum_int=ceil($payroll_before->sum('total_upah_thp_rupiah') / 100) * 100;
            if($payroll->sum('bpjs_tk_jkm_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkm_rupiah')+$payroll->sum('bpjs_tk_jkk_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkk_rupiah')+$payroll->sum('bpjs_tk_jht_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jht_rupiah')+$payroll->sum('bpjs_tk_jpn_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jpn_rupiah')+$payroll->sum('bpjs_tk_jkn_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkn_rupiah')==0){$bpjs_tk_perusahaan=0;}else{$bpjs_tk_perusahaan=$payroll->sum('bpjs_tk_jkm_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkm_rupiah')+$payroll->sum('bpjs_tk_jkk_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkk_rupiah')+$payroll->sum('bpjs_tk_jht_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jht_rupiah')+$payroll->sum('bpjs_tk_jpn_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jpn_rupiah')+$payroll->sum('bpjs_tk_jkn_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkn_rupiah');}
            $bpjs_tk_perusahaan_int=$payroll->sum('bpjs_tk_jkm_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkm_rupiah')+$payroll->sum('bpjs_tk_jkk_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkk_rupiah')+$payroll->sum('bpjs_tk_jht_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jht_rupiah')+$payroll->sum('bpjs_tk_jpn_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jpn_rupiah')+$payroll->sum('bpjs_tk_jkn_perusahaan_rupiah')+$payroll->sum('bpjs_tk_jkn_rupiah');
            if($payroll->sum('bpjs_ks_jkm_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkm_rupiah')+$payroll->sum('bpjs_ks_jkk_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkk_rupiah')+$payroll->sum('bpjs_ks_jht_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jht_rupiah')+$payroll->sum('bpjs_ks_jpn_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jpn_rupiah')+$payroll->sum('bpjs_ks_jkn_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkn_rupiah')==0){$bpjs_ks_perusahaan=0;}else{$bpjs_ks_perusahaan=$payroll->sum('bpjs_ks_jkm_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkm_rupiah')+$payroll->sum('bpjs_ks_jkk_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkk_rupiah')+$payroll->sum('bpjs_ks_jht_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jht_rupiah')+$payroll->sum('bpjs_ks_jpn_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jpn_rupiah')+$payroll->sum('bpjs_ks_jkn_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkn_rupiah');}
            $bpjs_ks_perusahaan_int=$payroll->sum('bpjs_ks_jkm_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkm_rupiah')+$payroll->sum('bpjs_ks_jkk_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkk_rupiah')+$payroll->sum('bpjs_ks_jht_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jht_rupiah')+$payroll->sum('bpjs_ks_jpn_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jpn_rupiah')+$payroll->sum('bpjs_ks_jkn_perusahaan_rupiah')+$payroll->sum('bpjs_ks_jkn_rupiah');
            $gaji_bni=$payroll_bni->sum('total_upah_thp_rupiah');
            $gaji_cimb=$payroll_cimb->sum('total_upah_thp_rupiah');
            $gaji_other=$payroll_other->sum('total_upah_thp_rupiah');
            $gaji_segala_bank=ceil(($gaji_bni+$gaji_cimb+$gaji_other) / 100) * 100;
            $data[$key]=[
                'id_department'=>$value->department_id,
                'nama_department'=>$value->department_name,
                'jumlah_karyawan'=>$payroll->count(),
                'bruto'=>$bruto_int,
                'bruto_int'=>$bruto_int,
                'pph'=>$pph21_int,
                'pph_int'=>$pph21_int,
                'upah_neto_rupiah'=>$upah_neto_rupiah_int,
                'upah_neto_rupiah_int'=>$upah_neto_rupiah_int,
                'total_bpjs_tk'=>$total_bpjs_tk_int,
                'total_bpjs_tk_int'=>$total_bpjs_tk_int,
                'total_bpjs_ks'=>$total_bpjs_ks_int,
                'total_bpjs_ks_int'=>$total_bpjs_ks_int,
                'potongan'=>$potongan_int,
                'potongan_int'=>$potongan_int,
                'jumlah'=>$jumlah_int,
                'jumlah_int'=>$jumlah_int,
                'jumlah_karyawan_sebelum'=>$payroll_before->count(),
                'jumlah_sebelum'=>$jumlah_sebelum_int,
                'jumlah_sebelum_int'=>$jumlah_sebelum_int,
                'selisih_karyawan'=>$payroll->count()-$payroll_before->count(),
                'selisih_gaji'=>(int)($payroll->sum('total_upah_thp_rupiah_employee'))-(int)(ceil($payroll_before->sum('total_upah_thp_rupiah') / 100) * 100),
                'bpjs_tk_perusahaan'=>$bpjs_tk_perusahaan,
                'total_bpjs_tk_all'=>(double)$payroll->sum('total_bpjs_tk')+$bpjs_tk_perusahaan,
                'bpjs_ks_perusahaan'=>$bpjs_ks_perusahaan,
                'total_bpjs_ks_all'=>(double)$payroll->sum('total_bpjs_ks')+$bpjs_ks_perusahaan,
                'periode'=>$year.'-'.$month,
                'payroll_bni'=>$payroll_bni->count(),
                'payroll_cimb'=>$payroll_cimb->count(),
                'payroll_other'=>$payroll_other->count(),
                'gaji_bni'=>$gaji_bni,
                'gaji_cimb'=>$gaji_cimb,
                'gaji_other'=>$gaji_other,
                'gaji_segala_bank'=>$gaji_segala_bank
            ];
        }
        $arrayData=[];
        foreach($data as $key=>$value){
            if($value['jumlah_karyawan']=='0' && $value['jumlah_karyawan_sebelum']=='0'){
                continue;
            }
            $arrayData[$key]=[
                'id_department'=>$value['id_department'],
                'nama_department'=>$value['nama_department'],
                'jumlah_karyawan'=>$value['jumlah_karyawan'],
                'bruto'=>$value['bruto'],
                'bruto_int'=>$value['bruto_int'],
                'pph'=>$value['pph'],
                'pph_int'=>$value['pph_int'],
                'upah_neto_rupiah'=>$value['upah_neto_rupiah'],
                'upah_neto_rupiah_int'=>$value['upah_neto_rupiah_int'],
                'total_bpjs_tk'=>$value['total_bpjs_tk'],
                'total_bpjs_tk_int'=>$value['total_bpjs_tk_int'],
                'total_bpjs_ks'=>$value['total_bpjs_ks'],
                'total_bpjs_ks_int'=>$value['total_bpjs_ks_int'],
                'potongan'=>$value['potongan'],
                'potongan_int'=>$value['potongan_int'],
                'jumlah'=>$value['jumlah'],
                'jumlah_int'=>$value['jumlah_int'],
                'jumlah_karyawan_sebelum'=>$value['jumlah_karyawan_sebelum'],
                'jumlah_sebelum'=>$value['jumlah_sebelum'],
                'jumlah_sebelum_int'=>$value['jumlah_sebelum_int'],
                'selisih_karyawan'=>$value['selisih_karyawan'],
                'selisih_gaji'=>$value['selisih_gaji'],
                'bpjs_tk_perusahaan'=>$value['bpjs_tk_perusahaan'],
                'total_bpjs_tk_all'=>$value['total_bpjs_tk_all'],
                'bpjs_ks_perusahaan'=>$value['bpjs_ks_perusahaan'],
                'total_bpjs_ks_all'=>$value['total_bpjs_ks_all'],
                'periode'=>$value['periode'],
                'payroll_bni'=>$value['payroll_bni'],
                'payroll_cimb'=>$value['payroll_cimb'],
                'payroll_other'=>$value['payroll_other'],
                'gaji_bni'=>$value['gaji_bni'],
                'gaji_cimb'=>$value['gaji_cimb'],
                'gaji_other'=>$value['gaji_other'],
                'gaji_segala_bank'=>$value['gaji_segala_bank']
            ];
        }
        $jumlah_karyawan = array_sum(array_column($arrayData,'jumlah_karyawan'));
        $bruto_total = array_sum(array_column($arrayData,'bruto_int'));
        if($bruto_total==0){$bruto_total=0;}else{$bruto_total=number_format( $bruto_total , 2 , ',' , '.');}
        $bruto_total_int = array_sum(array_column($arrayData,'bruto_int'));
        $pph = array_sum(array_column($arrayData,'pph_int'));
        if($pph==0){$pph=0;}else{$pph=number_format( $pph , 2 , ',' , '.');}
        $pph_int = array_sum(array_column($arrayData,'pph_int'));
        $upah_neto_rupiah_int_total = array_sum(array_column($arrayData,'upah_neto_rupiah_int')); 
        if($upah_neto_rupiah_int_total==0){$upah_neto_rupiah_int_total=0;}else{$upah_neto_rupiah_int_total=number_format( $upah_neto_rupiah_int_total , 2 , ',' , '.');}
        $upah_neto_rupiah_int_total_int = array_sum(array_column($arrayData,'upah_neto_rupiah_int')); 
        $total_bpjs_tk_total = array_sum(array_column($arrayData,'total_bpjs_tk_int')); 
        if($total_bpjs_tk_total==0){$total_bpjs_tk_total=0;}else{$total_bpjs_tk_total=number_format( $total_bpjs_tk_total , 2 , ',' , '.');}
        $total_bpjs_tk_total_int = array_sum(array_column($arrayData,'total_bpjs_tk_int')); 
        $total_bpjs_ks_total = array_sum(array_column($arrayData,'total_bpjs_ks_int')); 
        if($total_bpjs_ks_total==0){$total_bpjs_ks_total=0;}else{$total_bpjs_ks_total=number_format( $total_bpjs_ks_total , 2 , ',' , '.');}
        $total_bpjs_ks_total_int = array_sum(array_column($arrayData,'total_bpjs_ks_int')); 
        $potongan_total = array_sum(array_column($arrayData,'potongan_int')); 
        if($potongan_total==0){$potongan_total=0;}else{$potongan_total=number_format( $potongan_total , 2 , ',' , '.');}
        $potongan_total_int = array_sum(array_column($arrayData,'potongan_int')); 
        $jumlah_total = array_sum(array_column($arrayData,'jumlah_int')); 
        if($jumlah_total==0){$jumlah_total=0;}else{$jumlah_total=number_format( $jumlah_total , 2 , ',' , '.');}
        $jumlah_total_int = array_sum(array_column($arrayData,'jumlah_int')); 
        $jumlah_karyawan_sebelum_total = array_sum(array_column($arrayData,'jumlah_karyawan_sebelum'));
        $jumlah_sebelum_total = array_sum(array_column($arrayData,'jumlah_sebelum_int')); 
        if($jumlah_sebelum_total==0){$jumlah_sebelum_total=0;}else{$jumlah_sebelum_total=number_format( $jumlah_sebelum_total , 2 , ',' , '.');}
        $jumlah_sebelum_total_int = array_sum(array_column($arrayData,'jumlah_sebelum_int')); 
        $selisih_karyawan_total = array_sum(array_column($arrayData,'selisih_karyawan'));
        $selisih_gaji=array_sum(array_column($arrayData,'jumlah_int'));
        $selisih_gaji_before=array_sum(array_column($arrayData,'jumlah_sebelum_int'));
        $selisih_gaji_total=$selisih_gaji-$selisih_gaji_before;
        if($selisih_gaji_total==0){$selisih_gaji_total=0;}else{$selisih_gaji_total=number_format( $selisih_gaji_total , 2 , ',' , '.');}
        $selisih_gaji_total_int=$selisih_gaji-$selisih_gaji_before;
        $payroll_bni_total=array_sum(array_column($arrayData,'payroll_bni'));
        $payroll_cimb_total=array_sum(array_column($arrayData,'payroll_cimb'));
        $payroll_other_total=array_sum(array_column($arrayData,'payroll_other'));
        $all_payroll_total=$payroll_bni_total+$payroll_cimb_total+$payroll_other_total;
        $gaji_bni_total=array_sum(array_column($arrayData,'gaji_bni'));
        $gaji_cimb_total=array_sum(array_column($arrayData,'gaji_cimb'));
        $gaji_other_total=array_sum(array_column($arrayData,'gaji_other'));
        $gaji_segala_bank_total=array_sum(array_column($arrayData,'gaji_segala_bank'));
        $grand_total=[
            'id_department'=>'',
            'nama_department'=>'GRAND TOTAL',
            'jumlah_karyawan'=>$jumlah_karyawan,
            'bruto'=>$bruto_total_int,
            'bruto_int'=>'',
            'pph'=>$pph_int,
            'pph_int'=>'',
            'upah_neto_rupiah'=>$upah_neto_rupiah_int_total_int,
            'upah_neto_rupiah_int'=>'',
            'total_bpjs_tk'=>$total_bpjs_tk_total_int,
            'total_bpjs_tk_int'=>'',
            'total_bpjs_ks'=>$total_bpjs_ks_total_int,
            'total_bpjs_ks_int'=>'',
            'potongan'=>$potongan_total_int,
            'potongan_int'=>'',
            'jumlah'=>$jumlah_total_int,
            'jumlah_int'=>'',
            'jumlah_karyawan_sebelum'=>$jumlah_karyawan_sebelum_total,
            'jumlah_sebelum'=>$jumlah_sebelum_total_int,
            'jumlah_sebelum_int'=>'',
            'selisih_karyawan'=>$selisih_karyawan_total,
            'selisih_gaji'=>$selisih_gaji_total_int,
            'bpjs_tk_perusahaan'=>'',
            'total_bpjs_tk_all'=>'',
            'bpjs_ks_perusahaan'=>'',
            'total_bpjs_ks_all'=>'',
            'periode'=>'',
            'payroll_bni'=>'',
            'payroll_cimb'=>'',
            'payroll_other'=>'',
            'gaji_bni'=>'',
            'gaji_cimb'=>'',
            'gaji_other'=>'',
            'gaji_segala_bank'=>'',
        ];
        array_push($arrayData,$grand_total);
        $fileName = 'payrollSummaryDepartment_'.time() .'.xlsx';
        return Excel::download(new summaryDepartmentExport($status_staff,$arrayData,$payroll_bni_total,$payroll_cimb_total,$payroll_other_total,$all_payroll_total,$gaji_bni_total,$gaji_cimb_total,$gaji_other_total,$gaji_segala_bank_total,$periode_tahun_payroll,$periode_bulan_payroll), $fileName);
        // return view('hris.Laporan.summary_department',compact('arrayData','periode_tahun_payroll','periode_bulan_payroll'));
    }
    public function index()
    {
        $this->periode_payroll = $this->ajax_getperiodepayroll();
        $this->periode_kehadiran = $this->ajax_getperiodekehadiran();
        $this->selectemployee = $this->ajax_getallemployeeatribut();

        $this->month = date('Y-m');
        $this->latestData = RekapPerhitunganPayroll::latest('updated_at')->first();
        //$this->month = '2023-07';
        $this->loggedAdmin = Auth::guard('admin')->user();
        $rekap_payroll=RekapPerhitunganPayroll::where('periode_kehadiran',$this->latestData->periode_kehadiran)->orderBy('enroll_id')->get();
        $department_id=DepartmentAll::orderBy('department_id')->groupBy('department_id')->get();
        return View::make('hris/rekapperhitunganpayroll', compact('rekap_payroll','department_id'), $this->data);
    }
    public function get_last_update_proses_payroll(){
        $last_update=DB::select('select updated_at from rekap_perhitungan_payroll order by updated_at desc limit 1')[0]->updated_at;
        return $last_update;
    }
    public function get_last_update_labor(){
        $last_update=DB::select('select tanggal_berjalan from daily_labor_costs order by tanggal_berjalan desc limit 1')[0]->tanggal_berjalan;
        return $last_update;
    }
    public function recap_labor_cost(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');
        $periode_kehadiran = request()->daterange;
        $arrperiode=explode(" s/d ",$periode_kehadiran);
        $tanggal_awal = $arrperiode[0];
        $tanggal_akhir = $arrperiode[1];
        $inStatusStaff='';
        if(request()->status_staff){
            $status_staff = request()->status_staff;
            $inStatusStaff='AND status_staff = "'.$status_staff.'"';
        }else{
            $status_staff='Semua Karyawan';
        }
        $daily_labor=DB::select("select tanggal_berjalan,sum(if(group_department='PRODUCTION',(gaji_perhari-rp_pot_jam),0)) production,sum(if(group_department='SUPPORTING PRODUCTION',(gaji_perhari-rp_pot_jam),0)) supporting_production,sum(if(group_department='SUPPORTING GENERAL',(gaji_perhari-rp_pot_jam),0)) supporting_general,sum(if(group_department='PRODUCTION',(gaji_perhari-rp_pot_jam),0))+sum(if(group_department='SUPPORTING PRODUCTION',(gaji_perhari-rp_pot_jam),0))+sum(if(group_department='SUPPORTING GENERAL',(gaji_perhari-rp_pot_jam),0)) total_wages,sum(if(group_department='PRODUCTION',total_lembur_rupiah,0)) overtime_production,sum(if(group_department='SUPPORTING PRODUCTION',total_lembur_rupiah,0)) supporting_production_overtime,sum(if(group_department='SUPPORTING GENERAL',total_lembur_rupiah,0)) supporting_general_overtime,sum(if(group_department='PRODUCTION',total_lembur_rupiah,0))+sum(if(group_department='SUPPORTING PRODUCTION',total_lembur_rupiah,0))+sum(if(group_department='SUPPORTING GENERAL',total_lembur_rupiah,0)) total_overtime,sum(if(group_department='PRODUCTION',(insentif_kehadiran+insentif_jabatan),0)) incentive_production,sum(if(group_department='SUPPORTING PRODUCTION',(insentif_kehadiran+insentif_jabatan),0)) incentive_supporting_production,sum(if(group_department='SUPPORTING GENERAL',(insentif_kehadiran+insentif_jabatan),0)) incentive_supporting_general,sum(if(group_department='PRODUCTION',(insentif_kehadiran+insentif_jabatan),0))+sum(if(group_department='SUPPORTING PRODUCTION',(insentif_kehadiran+insentif_jabatan),0))+sum(if(group_department='SUPPORTING GENERAL',(insentif_kehadiran+insentif_jabatan),0)) total_insentif,sum(if(group_department='PRODUCTION',bpjs_ks,0)) bpjs_ks_production,sum(if(group_department='SUPPORTING PRODUCTION',bpjs_ks,0)) bpjs_ks_supporting_production,sum(if(group_department='SUPPORTING GENERAL',bpjs_ks,0)) bpjs_ks_supporting_general,sum(if(group_department='PRODUCTION',bpjs_ks,0))+sum(if(group_department='SUPPORTING PRODUCTION',bpjs_ks,0))+sum(if(group_department='SUPPORTING GENERAL',bpjs_ks,0)) total_bpjs_ks,sum(if(group_department='PRODUCTION',bpjs_tk,0)) bpjs_tk_production,sum(if(group_department='SUPPORTING PRODUCTION',bpjs_tk,0)) bpjs_tk_supporting_production,sum(if(group_department='SUPPORTING GENERAL',bpjs_tk,0)) bpjs_tk_supporting_general,sum(if(group_department='PRODUCTION',bpjs_tk,0))+sum(if(group_department='SUPPORTING PRODUCTION',bpjs_tk,0))+sum(if(group_department='SUPPORTING GENERAL',bpjs_tk,0)) total_bpjs_tk,sum(if(group_department='PRODUCTION',thr,0)) thr_production,sum(if(group_department='SUPPORTING PRODUCTION',thr,0)) thr_supporting_production,sum(if(group_department='SUPPORTING GENERAL',thr,0)) thr_supporting_general,sum(if(group_department='PRODUCTION',thr,0))+sum(if(group_department='SUPPORTING PRODUCTION',thr,0))+sum(if(group_department='SUPPORTING GENERAL',thr,0)) total_thr,sum(if(group_department='PRODUCTION',(gaji_perhari-rp_pot_jam),0))+sum(if(group_department='PRODUCTION',total_lembur_rupiah,0))+sum(if(group_department='PRODUCTION',(insentif_kehadiran+insentif_jabatan),0))+sum(if(group_department='PRODUCTION',bpjs_ks,0))+sum(if(group_department='PRODUCTION',bpjs_tk,0))+sum(if(group_department='PRODUCTION',thr,0)) total_employee_production_cost,sum(if(group_department='SUPPORTING PRODUCTION',(gaji_perhari-rp_pot_jam),0))+sum(if(group_department='SUPPORTING PRODUCTION',total_lembur_rupiah,0))+sum(if(group_department='SUPPORTING PRODUCTION',(insentif_kehadiran+insentif_jabatan),0))+sum(if(group_department='SUPPORTING PRODUCTION',bpjs_ks,0))+sum(if(group_department='SUPPORTING PRODUCTION',bpjs_tk,0))+sum(if(group_department='SUPPORTING PRODUCTION',thr,0)) total_employee_supporting_production_cost,sum(if(group_department='SUPPORTING GENERAL',(gaji_perhari-rp_pot_jam),0))+sum(if(group_department='SUPPORTING GENERAL',total_lembur_rupiah,0))+sum(if(group_department='SUPPORTING GENERAL',(insentif_kehadiran+insentif_jabatan),0))+sum(if(group_department='SUPPORTING GENERAL',bpjs_ks,0))+sum(if(group_department='SUPPORTING GENERAL',bpjs_tk,0))+sum(if(group_department='SUPPORTING GENERAL',thr,0)) total_employee_supporting_general_cost,sum(if(group_department='PRODUCTION',(gaji_perhari-rp_pot_jam),0))+sum(if(group_department='PRODUCTION',total_lembur_rupiah,0))+sum(if(group_department='PRODUCTION',(insentif_kehadiran+insentif_jabatan),0))+sum(if(group_department='PRODUCTION',bpjs_ks,0))+sum(if(group_department='PRODUCTION',bpjs_tk,0))+sum(if(group_department='PRODUCTION',thr,0))+sum(if(group_department='SUPPORTING PRODUCTION',(gaji_perhari-rp_pot_jam),0))+sum(if(group_department='SUPPORTING PRODUCTION',total_lembur_rupiah,0))+sum(if(group_department='SUPPORTING PRODUCTION',(insentif_kehadiran+insentif_jabatan),0))+sum(if(group_department='SUPPORTING PRODUCTION',bpjs_ks,0))+sum(if(group_department='SUPPORTING PRODUCTION',bpjs_tk,0))+sum(if(group_department='SUPPORTING PRODUCTION',thr,0))+sum(if(group_department='SUPPORTING GENERAL',(gaji_perhari-rp_pot_jam),0))+sum(if(group_department='SUPPORTING GENERAL',total_lembur_rupiah,0))+sum(if(group_department='SUPPORTING GENERAL',(insentif_kehadiran+insentif_jabatan),0))+sum(if(group_department='SUPPORTING GENERAL',bpjs_ks,0))+sum(if(group_department='SUPPORTING GENERAL',bpjs_tk,0))+sum(if(group_department='SUPPORTING GENERAL',thr,0)) total_employee_cost from daily_labor_costs where tanggal_berjalan>='$tanggal_awal' and tanggal_berjalan<='$tanggal_akhir' ".$inStatusStaff." group by tanggal_berjalan");
        $fileName = 'Daily Labor Cost';
        $response = Excel::download(new DailyLaborCost2($daily_labor,$tanggal_awal,$tanggal_akhir,$status_staff), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;
    }
    public function export_excel_daily_labor(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '10240000000000000000M');
        $inEnrollId='';
        $status_staff=request()->status_staff;
        $inStatusStaff='';
        $staffnonstaff='SEMUA KARYAWAN';
        if(request()->enroll_id){
            $enroll_id = request()->enroll_id;
            $enroll_id_string = implode(',', $enroll_id);
            $inEnrollId='AND a.enroll_id in ('.$enroll_id_string.')';
        }
        if(request()->status_staff){
            $status_staff = request()->status_staff;
            $inStatusStaff='AND b.status_staff = "'.$status_staff.'"';
            $staffnonstaff=$status_staff;
        }
        $arrperiode=explode(" s/d ",request()->daterange);
        $first_date=$arrperiode[0];
        $last_date=$arrperiode[1];
        $query=DB::select("select a.tanggal_berjalan,a.kode_hari,a.nama_hari,b.nik,a.enroll_id,b.employee_name,b.status_staff,b.status_jabatan,b.sub_dept_name,b.department_name,c.group_department,a.mulai_jam_kerja,a.akhir_jam_kerja,a.absen_masuk_kerja,a.absen_pulang_kerja,a.permits_dari_pukul,a.permits_sampai_pukul,a.total_menit_permits,a.jumlah_menit_absen_dt,a.jumlah_menit_absen_pc,a.jumlah_menit_absen_dtpc,a.status_absen,a.absen_alasan,h.kode_ijin_payroll,d.catatan,d.nomor_form_lembur,d.mulai_jam_lembur,d.akhir_jam_lembur,d.jumlah_jam_istirahat,d.jumlah_jam_lembur,e.final_mulai_jam_lembur,e.final_selesai_jam_lembur,e.final_jam_istirahat_lembur,e.final_total_jam_lembur,bpjs.dasar_pot_bpjs_rupiah upah_umk,c.gaji_perhari,c.gaji_permenit,c.iby,c.itb,c.m,c.dt,c.pc,c.dtpc,c.lby,c.lsm,c.r,c.ok,c.hari_kerja,c.pot_hari_kerja,c.total_absen,e.lembur_1,e.lembur_2,e.lembur_3,e.lembur_4,b.kode_grade,i.salary_bulanan,c.seniority_allowance,c.insentif_kehadiran,c.insentif_jabatan,e.lembur1_rupiah,e.lembur2_rupiah,e.lembur3_rupiah,e.lembur4_rupiah,if(f.jenis_koreksi=1,f.jumlah_rp_potongan,0) koreksi_upah,if(f.jenis_koreksi=3,f.jumlah_rp_potongan,0) koreksi_lembur,if(f.jenis_koreksi=2,f.jumlah_rp_potongan,0) koreksi_insentif,if(g.jenis_potongan=7,g.jumlah_rp_potongan,0) potongan_upah,if(g.jenis_potongan=8,g.jumlah_rp_potongan,0) potongan_lembur,if(g.jenis_potongan=5,g.jumlah_rp_potongan,0) potongan_insentif,if(g.jenis_potongan=6,g.jumlah_rp_potongan,0) potongan_piutang,c.rp_pot_hari_kerja,c.rp_pot_jam,c.bruto,c.bpjs_tk,c.bpjs_ks,c.total_potongan,c.pembulatan,c.jumlah,c.bpjs_tk_company,c.bpjs_ks_company,c.kompensasi,c.thr,c.konsumsi,c.total_pembayaran from master_data_absen_kehadiran a inner join employee_atribut b on a.enroll_id=b.enroll_id left join daily_labor_costs c on a.enroll_id=c.enroll_id and a.tanggal_berjalan=c.tanggal_berjalan left join data_lembur d on a.enroll_id=d.enroll_id and a.tanggal_berjalan=d.tanggal_berjalan left join rekap_perhitungan_lembur e on a.enroll_id=e.enroll_id and a.tanggal_berjalan=e.tanggal_berjalan left join data_koreksi_upah f on a.enroll_id=f.enroll_id and a.tanggal_berjalan=f.tanggal_koreksi left join data_koreksi_potongan g on a.enroll_id=g.enroll_id and a.tanggal_berjalan=g.tanggal_koreksi left join ref_absen_ijin h on a.status_absen=h.kode_absen_ijin left join (select*from grading_salary where periode_umk='2024-01')i on b.kode_grade=i.kode_grade inner join dasar_pot_bpjs bpjs on REGEXP_SUBSTR(bpjs.kode_dasar_pot_bpjs, '[0-9]+')=substring(a.tanggal_berjalan,1,4) where a.tanggal_berjalan>='".$first_date."' and a.tanggal_berjalan<='".$last_date."'".$inEnrollId.$inStatusStaff);
        $excel = FastExcel::create('query');
        $sheet = $excel->getSheet();

        $area = $sheet->beginArea();
        $style1 = [
            Style::FONT_STYLE_BOLD=>'bold',
            Style::VERTICAL_ALIGN=>'top',
            Style::TEXT_ALIGN=>'center',
            Style::TEXT_WRAP=>'text wrap',
            Style::BORDER => [
                Style::BORDER_TOP => [
                    Style::BORDER_STYLE => Style::BORDER_THIN,
                    Style::BORDER_COLOR => '#000000',
                ],
                Style::BORDER_LEFT => [
                    Style::BORDER_STYLE => Style::BORDER_THIN,
                    Style::BORDER_COLOR => '#000000',
                ],
                Style::BORDER_RIGHT => [
                    Style::BORDER_STYLE => Style::BORDER_THIN,
                    Style::BORDER_COLOR => '#000000',
                ],
                Style::BORDER_BOTTOM => [
                    Style::BORDER_STYLE => Style::BORDER_THIN,
                    Style::BORDER_COLOR => '#ffffff',
                ],
            ]
        ];
        $style2 = [
            Style::FONT_STYLE_BOLD=>'bold',
            Style::VERTICAL_ALIGN=>'top',
            Style::TEXT_ALIGN=>'center',
            Style::TEXT_WRAP=>'text wrap',
            Style::BORDER => [
                Style::BORDER_TOP => [
                    Style::BORDER_STYLE => Style::BORDER_THIN,
                    Style::BORDER_COLOR => '#000000',
                ],
                Style::BORDER_LEFT => [
                    Style::BORDER_STYLE => Style::BORDER_THIN,
                    Style::BORDER_COLOR => '#000000',
                ],
                Style::BORDER_RIGHT => [
                    Style::BORDER_STYLE => Style::BORDER_THIN,
                    Style::BORDER_COLOR => '#000000',
                ],
                Style::BORDER_BOTTOM => [
                    Style::BORDER_STYLE => Style::BORDER_THIN,
                    Style::BORDER_COLOR => '#000000',
                ],
            ]
        ];
        $style3 = [
            Style::FONT_STYLE_BOLD=>'bold',
            Style::VERTICAL_ALIGN=>'top',
            Style::TEXT_ALIGN=>'center',
            Style::TEXT_WRAP=>'text wrap',
            Style::BORDER => [
                Style::BORDER_TOP => [
                    Style::BORDER_STYLE => Style::BORDER_THIN,
                    Style::BORDER_COLOR => '#ffffff',
                ],
                Style::BORDER_LEFT => [
                    Style::BORDER_STYLE => Style::BORDER_THIN,
                    Style::BORDER_COLOR => '#000000',
                ],
                Style::BORDER_RIGHT => [
                    Style::BORDER_STYLE => Style::BORDER_THIN,
                    Style::BORDER_COLOR => '#000000',
                ],
                Style::BORDER_BOTTOM => [
                    Style::BORDER_STYLE => Style::BORDER_THIN,
                    Style::BORDER_COLOR => '#000000',
                ],
            ]
        ];
        $sheet->writeTo('A1', 'PT NIRWANA ALABARE GARMENT', ['font-size' => 18])->applyFontStyleBold();
        $sheet->writeTo('A2', 'LAPORAN ABSENSI KARYAWAN', ['font-size' => 16])->applyFontStyleBold();
        $sheet->writeTo('A3', 'TANGGAL ABSENSI : '.Carbon::parse($first_date)->translatedFormat('d F Y').' S/D '.Carbon::parse($last_date)->translatedFormat('d F Y'), ['font-size' => 14])->applyFontStyleBold();
        $sheet->writeTo('A4', 'STAFF / NON STAFF : '.$staffnonstaff, ['font-size' => 14])->applyFontStyleBold();
        $sheet->writeTo('A7', '');
        $sheet->setStyle('A7', $style1);
        $sheet->writeTo('B7', '');
        $sheet->setStyle('B7', $style1);
        $sheet->writeTo('C7', '');
        $sheet->setStyle('C7', $style1);
        $sheet->writeTo('D7', '');
        $sheet->setStyle('D7', $style1);
        $sheet->writeTo('E7', '');
        $sheet->setStyle('E7', $style1);
        $sheet->writeTo('F7', '');
        $sheet->setStyle('F7', $style1);
        $sheet->writeTo('G7', '');
        $sheet->setStyle('G7', $style1);
        $sheet->writeTo('H7', '');
        $sheet->setStyle('H7', $style1);
        $sheet->writeTo('I7', '');
        $sheet->setStyle('I7', $style1);
        $sheet->writeTo('J7', '');
        $sheet->setStyle('J7', $style1);
        $sheet->writeTo('K7', '');
        $sheet->setStyle('K7', $style1);
        $sheet->mergeCells('L7:O7');
        $sheet->writeTo('L7', 'JADWAL KERJA');
        $sheet->setStyle('L7:O7', $style1);
        $sheet->mergeCells('P7:R7');
        $sheet->writeTo('P7', 'PRESENSI');
        $sheet->setStyle('P7:R7', $style1);
        $sheet->mergeCells('S7:U7');
        $sheet->writeTo('S7', 'IJIN KELUAR SEMENTARA (IKS)');
        $sheet->setStyle('S7:U7', $style1);
        $sheet->mergeCells('V7:X7');
        $sheet->writeTo('V7', 'POTONGAN MENIT');
        $sheet->setStyle('V7:X7', $style1);
        $sheet->writeTo('Y7', '');
        $sheet->setStyle('Y7', $style1);
        $sheet->writeTo('Z7', '');
        $sheet->setStyle('Z7', $style1);
        $sheet->writeTo('AA7', '');
        $sheet->setStyle('AA7', $style1);
        $sheet->writeTo('AC7', '');
        $sheet->setStyle('AC7', $style1);
        $sheet->mergeCells('AD7:AH7');
        $sheet->writeTo('AD7', 'DATA LEMBUR (OVERTIME)');
        $sheet->setStyle('AD7:AH7', $style1);
        $sheet->mergeCells('AJ7:AM7');
        $sheet->writeTo('AJ7', 'DATA LEMBUR VERIFIKASI');
        $sheet->setStyle('AJ7:AM7', $style1);
        $sheet->writeTo('AO7', '');
        $sheet->setStyle('AO7', $style1);
        $sheet->writeTo('AP7', '');
        $sheet->setStyle('AP7', $style1);
        $sheet->writeTo('AQ7', '');
        $sheet->setStyle('AQ7', $style1);
        $sheet->writeTo('AS7', '');
        $sheet->setStyle('AS7', $style1);
        $sheet->writeTo('AT7', '');
        $sheet->setStyle('AT7', $style1);
        $sheet->writeTo('AU7', '');
        $sheet->setStyle('AU7', $style1);
        $sheet->writeTo('AV7', '');
        $sheet->setStyle('AV7', $style1);
        $sheet->writeTo('AW7', '');
        $sheet->setStyle('AW7', $style1);
        $sheet->writeTo('AX7', '');
        $sheet->setStyle('AX7', $style1);
        $sheet->writeTo('AY7', '');
        $sheet->setStyle('AY7', $style1);
        $sheet->writeTo('AZ7', '');
        $sheet->setStyle('AZ7', $style1);
        $sheet->writeTo('BA7', '');
        $sheet->setStyle('BA7', $style1);
        $sheet->writeTo('BB7', '');
        $sheet->setStyle('BB7', $style1);
        $sheet->writeTo('BC7', '');
        $sheet->setStyle('BC7', $style1);
        $sheet->writeTo('BD7', '');
        $sheet->setStyle('BD7', $style1);
        $sheet->writeTo('BE7', '');
        $sheet->setStyle('BE7', $style1);
        $sheet->writeTo('BF7', '');
        $sheet->setStyle('BF7', $style1);
        $sheet->writeTo('BG7', '');
        $sheet->setStyle('BG7', $style1);
        $sheet->writeTo('BH7', '');
        $sheet->setStyle('BH7', $style1);
        $sheet->writeTo('BI7', '');
        $sheet->setStyle('BI7', $style1);
        $sheet->writeTo('BJ7', '');
        $sheet->setStyle('BJ7', $style1);
        $sheet->writeTo('BK7', '');
        $sheet->setStyle('BK7', $style1);
        $sheet->writeTo('BL7', '');
        $sheet->setStyle('BL7', $style1);
        $sheet->writeTo('BM7', '');
        $sheet->setStyle('BM7', $style1);
        $sheet->writeTo('BO7', '');
        $sheet->setStyle('BO7', $style1);
        $sheet->writeTo('BP7', '');
        $sheet->setStyle('BP7', $style1);
        $sheet->writeTo('BQ7', '');
        $sheet->setStyle('BQ7', $style1);
        $sheet->writeTo('BR7', '');
        $sheet->setStyle('BR7', $style1);
        $sheet->writeTo('BS7', '');
        $sheet->setStyle('BS7', $style1);
        $sheet->writeTo('BT7', '');
        $sheet->setStyle('BT7', $style1);
        $sheet->writeTo('BU7', '');
        $sheet->setStyle('BU7', $style1);
        $sheet->writeTo('BV7', '');
        $sheet->setStyle('BV7', $style1);
        $sheet->writeTo('BW7', '');
        $sheet->setStyle('BW7', $style1);
        $sheet->mergeCells('BX7:CD7');
        $sheet->writeTo('BX7', 'Lain - Lain (Koreksi +-)');
        $sheet->setStyle('BX7:CD7', $style1);
        $sheet->writeTo('CE7', '');
        $sheet->setStyle('CE7', $style1);
        $sheet->writeTo('CF7', '');
        $sheet->setStyle('CF7', $style1);
        $sheet->writeTo('CG7', '');
        $sheet->setStyle('CG7', $style1);
        $sheet->writeTo('CH7', '');
        $sheet->setStyle('CH7', $style1);
        $sheet->writeTo('CI7', '');
        $sheet->setStyle('CI7', $style1);
        $sheet->writeTo('CJ7', '');
        $sheet->setStyle('CJ7', $style1);
        $sheet->mergeCells('CK7:CL7');
        $sheet->writeTo('CK7', 'Potongan Karyawan');
        $sheet->setStyle('CK7:CL7', $style1);
        $sheet->writeTo('CM7', '');
        $sheet->setStyle('CM7', $style1);
        $sheet->writeTo('CN7', '');
        $sheet->setStyle('CN7', $style1);
        $sheet->writeTo('CO7', '');
        $sheet->setStyle('CO7', $style1);
        $sheet->writeTo('CP7', '');
        $sheet->setStyle('CP7', $style1);
        $sheet->writeTo('CQ7', '');
        $sheet->setStyle('CQ7', $style1);
        $sheet->mergeCells('CS7:CT7');
        $sheet->writeTo('CS7', 'Potongan Perusahaan');
        $sheet->setStyle('CS7:CT7', $style1);
        $sheet->writeTo('CU7', '');
        $sheet->setStyle('CU7', $style1);
        $sheet->writeTo('CV7', '');
        $sheet->setStyle('CV7', $style1);
        $sheet->writeTo('CW7', '');
        $sheet->setStyle('CW7', $style1);
        $sheet->writeTo('CY7', '');
        $sheet->setStyle('CY7', $style1);
        $sheet->writeTo('A8', 'Tanggal', ['font-size' => 11])->applyFontStyleBold()->applyTextCenter();
        $sheet->setStyle('A8', $style3); 
        $sheet->writeTo('B8', 'Hari', ['font-size' => 11]);
        $sheet->setStyle('B8', $style3); 
        $sheet->writeTo('C8', 'NIP', ['font-size' => 11]);
        $sheet->setStyle('C8', $style3); 
        $sheet->writeTo('D8', 'No. Absen');
        $sheet->setStyle('D8', $style3); 
        $sheet->writeTo('E8', 'Nama Karyawan');
        $sheet->setStyle('E8', $style3); 
        $sheet->writeTo('F8', 'Staff / Non Staff');
        $sheet->setStyle('F8', $style3);
        $sheet->writeTo('G8', 'Jabatan');
        $sheet->setStyle('G8', $style3);
        $sheet->writeTo('H8', 'Bagian');
        $sheet->setStyle('H8', $style3);
        $sheet->writeTo('I8', 'Department');
        $sheet->setStyle('I8', $style3);
        $sheet->writeTo('J8', 'Keterangan Biaya');
        $sheet->setStyle('J8', $style3);
        $sheet->writeTo('K8', 'Kerja/Libur');
        $sheet->setStyle('K8', $style3);
        $sheet->writeTo('L8', 'In');
        $sheet->setStyle('L8', $style2);
        $sheet->writeTo('M8', 'Out');
        $sheet->setStyle('M8', $style2);
        $sheet->writeTo('N8', 'Durasi Istirahat');
        $sheet->setStyle('N8', $style2);
        $sheet->writeTo('O8', 'Durasi Kerja');
        $sheet->setStyle('O8', $style2);
        $sheet->writeTo('P8', 'Sc In');
        $sheet->setStyle('P8', $style2);
        $sheet->writeTo('Q8', 'Sc Out');
        $sheet->setStyle('Q8', $style2);
        $sheet->writeTo('R8', 'Efektif Kerja');
        $sheet->setStyle('R8', $style2);
        $sheet->writeTo('S8', 'IKS (Dari)');
        $sheet->setStyle('S8', $style2);
        $sheet->writeTo('T8', 'IKS (Sampai)');
        $sheet->setStyle('T8', $style2);
        $sheet->writeTo('U8', 'IKS (Total)');
        $sheet->setStyle('U8', $style2);
        $sheet->writeTo('V8', 'DT');
        $sheet->setStyle('V8', $style2);
        $sheet->writeTo('W8', 'PC');
        $sheet->setStyle('W8', $style2);
        $sheet->writeTo('X8', 'Potongan Menit Total');
        $sheet->setStyle('X8', $style2);
        $sheet->writeTo('Y8', 'Kode Absen');
        $sheet->setStyle('Y8', $style3);
        $sheet->writeTo('Z8', 'Status Payroll');
        $sheet->setStyle('Z8', $style3);
        $sheet->writeTo('AA8', 'Keterangan Absen');
        $sheet->setStyle('AA8', $style3);
        $sheet->writeTo('AC8', 'Keterangan Lembur');
        $sheet->setStyle('AC8', $style3);
        $sheet->writeTo('AD8', 'No. SPL');
        $sheet->setStyle('AD8', $style2);
        $sheet->writeTo('AE8', 'OT Mulai');
        $sheet->setStyle('AE8', $style2);
        $sheet->writeTo('AF8', 'OT Selesai');
        $sheet->setStyle('AF8', $style2);
        $sheet->writeTo('AG8', 'OT Istirahat');
        $sheet->setStyle('AG8', $style2);
        $sheet->writeTo('AH8', 'OT Total');
        $sheet->setStyle('AH8', $style2);
        $sheet->writeTo('AJ8', 'Mulai Jam Lembur');
        $sheet->setStyle('AJ8', $style2);
        $sheet->writeTo('AK8', 'Akhir Jam Lembur');
        $sheet->setStyle('AK8', $style2);
        $sheet->writeTo('AL8', 'Jumlah Jam Istirahat');
        $sheet->setStyle('AL8', $style2);
        $sheet->writeTo('AM8', 'Jumlah Jam Lembur');
        $sheet->setStyle('AM8', $style2);
        $sheet->writeTo('AM8', 'Jumlah Jam Lembur');
        $sheet->setStyle('AM8', $style2);
        $sheet->writeTo('AO8', 'UPAH UMK');
        $sheet->setStyle('AO8', $style3);
        $sheet->writeTo('AP8', 'Upah / Hari');
        $sheet->setStyle('AP8', $style3);
        $sheet->writeTo('AQ8', 'Upah / Jam');
        $sheet->setStyle('AQ8', $style3);
        $sheet->writeTo('AS8', 'IBY');
        $sheet->setStyle('AS8', $style3);
        $sheet->writeTo('AT8', 'ITB');
        $sheet->setStyle('AT8', $style3);
        $sheet->writeTo('AU8', 'M');
        $sheet->setStyle('AU8', $style3);
        $sheet->writeTo('AV8', 'DT');
        $sheet->setStyle('AV8', $style3);
        $sheet->writeTo('AW8', 'PC');
        $sheet->setStyle('AW8', $style3);
        $sheet->writeTo('AX8', 'DTPC');
        $sheet->setStyle('AX8', $style3);
        $sheet->writeTo('AY8', 'LBY');
        $sheet->setStyle('AY8', $style3);
        $sheet->writeTo('AZ8', 'LSM');
        $sheet->setStyle('AZ8', $style3);
        $sheet->writeTo('BA8', 'R');
        $sheet->setStyle('BA8', $style3);
        $sheet->writeTo('BB8', 'OK');
        $sheet->setStyle('BB8', $style3);
        $sheet->writeTo('BC8', 'Hari Kerja');
        $sheet->setStyle('BC8', $style3);
        $sheet->writeTo('BD8', 'Pot. Hari Kerja');
        $sheet->setStyle('BD8', $style3);
        $sheet->writeTo('BE8', 'Total Absensi');
        $sheet->setStyle('BE8', $style3);
        $sheet->writeTo('BF8', 'L1');
        $sheet->setStyle('BF8', $style3);
        $sheet->writeTo('BG8', 'L2');
        $sheet->setStyle('BG8', $style3);
        $sheet->writeTo('BH8', 'L3');
        $sheet->setStyle('BH8', $style3);
        $sheet->writeTo('BI8', 'L4');
        $sheet->setStyle('BI8', $style3);
        $sheet->writeTo('BJ8', 'Datang Terlambat');
        $sheet->setStyle('BJ8', $style3);
        $sheet->writeTo('BK8', 'Pulang Cepat');
        $sheet->setStyle('BK8', $style3);
        $sheet->writeTo('BL8', 'Izin Keluar Sementara');
        $sheet->setStyle('BL8', $style3);
        $sheet->writeTo('BM8', 'Sisa Cuti Tahunan');
        $sheet->setStyle('BM8', $style3);
        $sheet->writeTo('BO8', 'Kode Grade');
        $sheet->setStyle('BO8', $style3);
        $sheet->writeTo('BP8', 'Upah Grade');
        $sheet->setStyle('BP8', $style3);
        $sheet->writeTo('BQ8', 'Seniority Allowance');
        $sheet->setStyle('BQ8', $style3);
        $sheet->writeTo('BR8', 'Insentif (Kehadiran)');
        $sheet->setStyle('BR8', $style3);
        $sheet->writeTo('BS8', 'Insentif (Jabatan)');
        $sheet->setStyle('BS8', $style3);
        $sheet->writeTo('BT8', 'RP Lembur 1');
        $sheet->setStyle('BT8', $style3);
        $sheet->writeTo('BU8', 'RP Lembur 2');
        $sheet->setStyle('BU8', $style3);
        $sheet->writeTo('BV8', 'RP Lembur 3');
        $sheet->setStyle('BV8', $style3);
        $sheet->writeTo('BW8', 'RP Lembur 4');
        $sheet->setStyle('BW8', $style3);
        $sheet->writeTo('BX8', '+ Upah');
        $sheet->setStyle('BX8', $style2);
        $sheet->writeTo('BY8', '+ Lembur');
        $sheet->setStyle('BY8', $style2);
        $sheet->writeTo('BZ8', '+ Insentif');
        $sheet->setStyle('BZ8', $style2);
        $sheet->writeTo('CA8', '- Upah');
        $sheet->setStyle('CA8', $style2);
        $sheet->writeTo('CB8', '- Lembur');
        $sheet->setStyle('CB8', $style2);
        $sheet->writeTo('CC8', '- Insentif');
        $sheet->setStyle('CC8', $style2);
        $sheet->writeTo('CD8', '- Piutang');
        $sheet->setStyle('CD8', $style2);
        $sheet->writeTo('CE8', 'RP Cuti Tahunan');
        $sheet->setStyle('CE8', $style3);
        $sheet->writeTo('CF8', 'RP Potongan Hari Kerja');
        $sheet->setStyle('CF8', $style3);
        $sheet->writeTo('CG8', 'RP Pot. Jam');
        $sheet->setStyle('CG8', $style3);
        $sheet->writeTo('CH8', 'Bruto');
        $sheet->setStyle('CH8', $style3);
        $sheet->writeTo('CI8', 'PPH');
        $sheet->setStyle('CI8', $style3);
        $sheet->writeTo('CJ8', 'Netto');
        $sheet->setStyle('CJ8', $style3);
        $sheet->writeTo('CK8', 'Bpjamsostek');
        $sheet->setStyle('CK8', $style2);
        $sheet->writeTo('CL8', 'BPJS Kesehatan');
        $sheet->setStyle('CL8', $style2);
        $sheet->writeTo('CM8', 'Serikat');
        $sheet->setStyle('CM8', $style3);
        $sheet->writeTo('CN8', 'Koperasi');
        $sheet->setStyle('CN8', $style3);
        $sheet->writeTo('CO8', 'Total Potongan');
        $sheet->setStyle('CO8', $style3);
        $sheet->writeTo('CP8', 'Pembulatan');
        $sheet->setStyle('CP8', $style3);
        $sheet->writeTo('CQ8', 'Jumlah');
        $sheet->setStyle('CQ8', $style3);
        $sheet->writeTo('CS8', 'PP Bpjamsostek');
        $sheet->setStyle('CS8', $style2);
        $sheet->writeTo('CT8', 'PP BPJS Kesehatan');
        $sheet->setStyle('CT8', $style2);
        $sheet->writeTo('CU8', 'Kompensasi');
        $sheet->setStyle('CU8', $style3);
        $sheet->writeTo('CV8', 'THR');
        $sheet->setStyle('CV8', $style3);
        $sheet->writeTo('CW8', 'Makan Lembur');
        $sheet->setStyle('CW8', $style3);
        $sheet->writeTo('CY8', 'Total Pembayaran Aktual');
        $sheet->setStyle('CY8', $style3);
        $sheet->setColOptions([
            'A' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 12],
            'B' => ['width' => 8],
            'C' => ['width' => 11],
            'D' => ['width' => 11],
            'E' => ['width' => 35],
            'F' => ['width' => 11],
            'G' => ['width' => 11],
            'H' => ['width' => 27],
            'I' => ['width' => 27],
            'J' => ['width' => 26],
            'K' => ['width' => 14],
            'L' => ['format' => NumberFormat::FORMAT_DATE_TIME3,'width' => 7],
            'M' => ['format' => NumberFormat::FORMAT_DATE_TIME3,'width' => 7],
            'N' => ['format' => NumberFormat::FORMAT_DATE_TIME3,'width' => 13],
            'O' => ['width' => 13],
            'P' => ['format' => NumberFormat::FORMAT_DATE_TIME3,'width' => 10],
            'Q' => ['format' => NumberFormat::FORMAT_DATE_TIME3,'width' => 10],
            'R' => ['width' => 10],
            'S' => ['format' => NumberFormat::FORMAT_DATE_TIME3,'width' => 12],
            'T' => ['format' => NumberFormat::FORMAT_DATE_TIME3,'width' => 12],
            'U' => ['width' => 12],
            'V' => ['width' => 11],
            'W' => ['width' => 11],
            'Y' => ['width' => 16],
            'Z' => ['width' => 17],
            'AA' => ['width' => 21],
            'AA' => ['width' => 21],
            'AB' => ['width' => 9],
            'AC' => ['width' => 39],
            'AD' => ['width' => 18],
            'AE' => ['format' => NumberFormat::FORMAT_DATE_TIME3,'width' => 12],
            'AF' => ['format' => NumberFormat::FORMAT_DATE_TIME3,'width' => 12],
            'AG' => ['width' => 12],
            'AH' => ['width' => 12],
            'AI' => ['width' => 1],
            'AJ' => ['format' => NumberFormat::FORMAT_DATE_TIME3,'width' => 13],
            'AK' => ['format' => NumberFormat::FORMAT_DATE_TIME3,'width' => 13],
            'AL' => ['width' => 13],
            'AM' => ['format' => NumberFormat::FORMAT_DATE_TIME3,'width' => 13],
            'AN' => ['width' => 1],
            'AO' => ['width' => 13],
            'AP' => ['width' => 9],
            'AQ' => ['width' => 9],
            'AR' => ['width' => 5],
            'AS' => ['width' => 6],
            'AT' => ['width' => 6],
            'AU' => ['width' => 6],
            'AV' => ['width' => 6],
            'AW' => ['width' => 6],
            'AX' => ['width' => 6],
            'AY' => ['width' => 6],
            'AZ' => ['width' => 6],
            'BA' => ['width' => 6],
            'BB' => ['width' => 6],
            'BC' => ['width' => 6],
            'BD' => ['width' => 6],
            'BE' => ['width' => 8],
            'BF' => ['width' => 6],
            'BG' => ['width' => 6],
            'BH' => ['width' => 6],
            'BI' => ['width' => 6],
            'BJ' => ['width' => 11],
            'BK' => ['width' => 11],
            'BL' => ['width' => 11],
            'BM' => ['width' => 11],
            'BN' => ['width' => 5],
            'BO' => ['width' => 9],
            'BP' => ['width' => 9],
            'BQ' => ['width' => 11],
            'BR' => ['width' => 12],
            'BS' => ['width' => 11],
            'BT' => ['width' => 11],
            'BU' => ['width' => 11],
            'BV' => ['width' => 11],
            'BW' => ['width' => 11],
            'BX' => ['width' => 10],
            'BY' => ['width' => 10],
            'BZ' => ['width' => 10],
            'CA' => ['width' => 10],
            'CB' => ['width' => 10],
            'CC' => ['width' => 10],
            'CD' => ['width' => 10],
            'CE' => ['width' => 12],
            'CF' => ['width' => 12],
            'CG' => ['width' => 12],
            'CH' => ['width' => 12],
            'CI' => ['width' => 5],
            'CJ' => ['width' => 12],
            'CK' => ['width' => 13],
            'CL' => ['width' => 13],
            'CM' => ['width' => 10],
            'CN' => ['width' => 10],
            'CO' => ['width' => 13],
            'CP' => ['width' => 12],
            'CQ' => ['width' => 13],
            'CR' => ['width' => 5],
            'CS' => ['width' => 13],
            'CT' => ['width' => 13],
            'CU' => ['width' => 12],
            'CV' => ['width' => 11],
            'CW' => ['width' => 11],
            'CX' => ['width' => 9],
            'CY' => ['width' => 12],
        ]);
        foreach($query as $key=>$value){
            $jumlah_menit_istirahat = '01:00';
            $jumlah_menit_istirahat_int=60;
            if(($value->mulai_jam_kerja == null) || ($value->status_absen == "LN" || $value->status_absen == "CG" || $value->status_absen == "CM" || $value->status_absen == "CT" ||$value->status_absen == "L") || (($value->status_absen == "LP" ) && ($value->absen_masuk_kerja==null) && ($value->absen_pulang_kerja==null))) {
                $kerjalibur = "LIBUR";
            } else if(($value->kode_hari=='6' || $value->kode_hari=='5' ) && ($value->mulai_jam_kerja!=null)){
                $kerjalibur = "KERJA";
            } else {
                if(($value->absen_masuk_kerja <> null) || ($value->absen_masuk_kerja <> "") || ($value->absen_pulang_kerja <> null) || ($value->absen_pulang_kerja <> "")) {
                    $kerjalibur = "KERJA";
                    switch ($value->kode_hari) {
                        case '5':
                            $kerjalibur = "LIBUR";
                            $jumlah_menit_istirahat = '00:30';
                            $jumlah_menit_istirahat_int=30;
                            break;
                        case '6':
                            $kerjalibur = "LIBUR";
                            $jumlah_menit_istirahat = '00:30';
                            $jumlah_menit_istirahat_int=30;
                            break;
                    }
                }
            }
            $mulai_jam_kerja=strtotime($value->mulai_jam_kerja);
            $akhir_jam_kerja=strtotime($value->akhir_jam_kerja);
            $jumlah_detik_istirahat=strtotime($jumlah_menit_istirahat);
            $time = explode(":",$jumlah_menit_istirahat);
            $minutes = intval($time[0])*60 + intval($time[1]);
            $total_jam_kerja=(($akhir_jam_kerja-$mulai_jam_kerja)/60)-$minutes;
            $kode_ijin_payroll=$value->kode_ijin_payroll;
            if($kode_ijin_payroll==null){
                if($value->mulai_jam_kerja!=null && $value->akhir_jam_kerja!=null){
                    if($value->absen_masuk_kerja!=null && $value->absen_pulang_kerja!=null && $value->status_absen!='R'){
                        if($value->jumlah_menit_absen_dt!=0 && $value->jumlah_menit_absen_pc==0){
                            $kode_ijin_payroll='DT';
                        }else if($value->jumlah_menit_absen_dt==0 && $value->jumlah_menit_absen_pc!=0){
                            $kode_ijin_payroll='PC';
                        }else if($value->jumlah_menit_absen_dt!=0 && $value->jumlah_menit_absen_pc!=0){
                            $kode_ijin_payroll='DTPC';
                        }else{
                            $kode_ijin_payroll='OK';
                        }
                    }else if($value->absen_masuk_kerja!=null && $value->absen_pulang_kerja!=null && $value->status_absen=='R'){
                        $kode_ijin_payroll='R';
                    }else if($value->absen_masuk_kerja!=null && $value->absen_pulang_kerja==null && $value->status_absen!='R'){
                        $kode_ijin_payroll='M';
                    }else if($value->absen_masuk_kerja==null && $value->absen_pulang_kerja!=null && $value->status_absen!='R'){
                        $kode_ijin_payroll='M';
                    }else if($value->absen_masuk_kerja!=null && $value->absen_pulang_kerja==null && $value->status_absen=='R'){
                        $kode_ijin_payroll='R';
                    }else if($value->absen_masuk_kerja==null && $value->absen_pulang_kerja==null && $value->status_absen!='R'){
                        $kode_ijin_payroll='M';
                    }else if($value->absen_masuk_kerja==null && $value->absen_pulang_kerja==null && $value->status_absen=='R'){
                        $kode_ijin_payroll='R';
                    }
                }else{
                    if($value->status_absen=='LN'){
                        $kode_ijin_payroll='LBY';
                    }else if($value->status_absen=='R'){
                        $kode_ijin_payroll='R';
                    }else if($value->status_absen==null){
                        $kode_ijin_payroll='LSM';
                    }else if($value->status_absen=='IKS'){
                        $kode_ijin_payroll='OK';
                    }else if($value->status_absen=='TL'){
                        $kode_ijin_payroll='LSM';
                    }
                }
            }else if($kode_ijin_payroll=='ITB'){
                if($value->status_absen=='M'){
                    $kode_ijin_payroll='M';
                }else if($value->status_absen=='IKS'){
                    $kode_ijin_payroll='OK';
                }else if($value->status_absen=='LP'){
                    $kode_ijin_payroll='LP';
                }else if($value->status_absen=='S'){
                    $kode_ijin_payroll='S';
                }
            }else if($kode_ijin_payroll=='IBY'){
                if($value->status_absen=='DL'){
                    $kode_ijin_payroll='DL';
                }
            }
            $absen_masuk_kerja=strtotime($value->absen_masuk_kerja);
            $absen_pulang_kerja=strtotime($value->absen_pulang_kerja);
            if($absen_masuk_kerja<$absen_pulang_kerja && $absen_masuk_kerja!=null && $absen_pulang_kerja!=null){
            $total_absen_kerja=(($absen_pulang_kerja-$absen_masuk_kerja)/60)-$jumlah_menit_istirahat_int;
            }else if($absen_pulang_kerja<$absen_masuk_kerja && $absen_masuk_kerja!=null && $absen_pulang_kerja!=null){
                $total_absen_kerja=(($absen_masuk_kerja-$absen_pulang_kerja)/60)-$jumlah_menit_istirahat_int;
            }else{
                $total_absen_kerja='';
            }
            $data = [
                Date::stringToExcel($value->tanggal_berjalan),
                $value->nama_hari,
                $value->nik,
                $value->enroll_id,
                $value->employee_name,
                $value->status_staff,
                $value->status_jabatan,
                $value->sub_dept_name,
                $value->department_name,
                $value->group_department,
                $kerjalibur,
                $value->mulai_jam_kerja,
                $value->akhir_jam_kerja,
                $jumlah_menit_istirahat,
                $total_jam_kerja>0?$total_jam_kerja:'',
                $value->absen_masuk_kerja,
                $value->absen_pulang_kerja,
                $total_absen_kerja>0?$total_absen_kerja:'',
                $value->permits_dari_pukul,
                $value->permits_sampai_pukul,
                $value->total_menit_permits,
                $value->jumlah_menit_absen_dt,
                $value->jumlah_menit_absen_pc,
                $value->jumlah_menit_absen_dtpc,
                $value->status_absen,
                $kode_ijin_payroll,
                $value->absen_alasan,
                '',
                $value->catatan,
                $value->nomor_form_lembur,
                substr($value->mulai_jam_lembur,11,8),
                substr($value->akhir_jam_lembur,11,8),
                $value->jumlah_jam_istirahat,
                $value->jumlah_jam_lembur,
                '',
                $value->final_mulai_jam_lembur,
                $value->final_selesai_jam_lembur,
                $value->final_jam_istirahat_lembur,
                $value->final_total_jam_lembur,
                '',
                '',
                $value->gaji_perhari,
                $value->gaji_permenit,
                '',
                $value->iby,
                $value->itb,
                $value->m,
                $value->dt,
                $value->pc,
                $value->dtpc,
                $value->lby,
                $value->lsm,
                $value->r,
                $value->ok,
                $value->hari_kerja,
                $value->pot_hari_kerja,
                $value->total_absen,
                $value->lembur_1,
                $value->lembur_2,
                $value->lembur_3,
                $value->lembur_4,
                $value->jumlah_menit_absen_dt,
                $value->jumlah_menit_absen_pc,
                $value->total_menit_permits,
                '',
                '',
                $value->kode_grade,
                $value->salary_bulanan,
                $value->seniority_allowance,
                $value->insentif_kehadiran,
                $value->insentif_jabatan,
                $value->lembur1_rupiah,
                $value->lembur2_rupiah,
                $value->lembur3_rupiah,
                $value->lembur4_rupiah,
                $value->koreksi_upah,
                $value->koreksi_lembur,
                $value->koreksi_insentif,
                $value->potongan_upah,
                $value->potongan_lembur,
                $value->potongan_insentif,
                $value->potongan_piutang,
                '',
                $value->rp_pot_hari_kerja,
                $value->rp_pot_jam,
                $value->bruto,
                '',
                $value->bruto,
                $value->bpjs_tk,
                $value->bpjs_ks,
                0,
                0,
                $value->total_potongan,
                $value->pembulatan,
                $value->jumlah,
                '',
                $value->bpjs_tk_company,
                $value->bpjs_ks_company,
                $value->kompensasi,
                $value->thr,
                $value->konsumsi,
                '',
                $value->total_pembayaran
            ];
            $sheet->writeRow($data);
        }
        $finename=date('Y-m-d').'_Time and Attendance PT.NAG_'.rand(10,10000000).'xlsx';
        ob_end_clean();
        return $excel->download($finename);
    }
    public function proses_payroll_harian(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '10240000000000000000M');
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $tidak_bayar=RefAbsenIjin::where('kode_ijin_payroll','ITB')->where('kode_absen_ijin','!=','M')->where('kode_absen_ijin','!=','IKS')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');
        $ITB=array_column($tidak_bayar,'kode_absen_ijin');
        $arrperiode=explode(" s/d ",request()->daterange1);
        $first_date=$arrperiode[0];
        $last_date=$arrperiode[1];
        $inEnrollId='';
        if(request()->enroll_id){
            $enroll_id = request()->enroll_id;
            $enroll_id_string = implode(',', $enroll_id);
            $inEnrollId='AND enroll_id in ('.$enroll_id_string.')';
        }
        $master_absen=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$first_date)->where('tanggal_berjalan','<=',$last_date)->whereRaw('enroll_id is not null '.$inEnrollId)
        ->with(['rekap_lembur'=>function($query)use($first_date,$last_date){
            $query->where('tanggal_berjalan','>=',$first_date)
            ->where('tanggal_berjalan','<=',$last_date);
        }])->with(['data_lembur'=>function($query)use($first_date,$last_date){
            $query->where('tanggal_berjalan','>=',$first_date)
            ->where('tanggal_berjalan','<=',$last_date);
        }])->with(['employee_atribut.grading_salary'=>function($query){
            $query->where('periode_umk','2024-01');
        }])->with('employee_atribut','employee_atribut.employee_bpjs','koreksi_upah','koreksi_potongan','employee_atribut.group_department')->get();
        $z=[];
        foreach($master_absen as $key=>$value){
            $enroll_id_karyawan=$value->enroll_id;
            $nomor_form_lembur=$value->nomor_form_lembur;
            if($nomor_form_lembur!=''){
                if(!$value->rekap_lembur->where('tanggal_berjalan',$value->tanggal_berjalan)->first()){
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
                    if ($selisih_menit <= 15) {
                        $konveri_jam = 0;
                    } elseif ($selisih_menit > 15 && $selisih_menit <= 45) {
                        $konveri_jam = 0.5;
                    } else {
                        $konveri_jam = 1;
                    }
                    $total_jam_lembur=$selisih_jam + $konveri_jam;
                    $total_jam_lembur_finis=$total_jam_lembur-$value->data_lembur->where('tanggal_berjalan',$value->tanggal_berjalan)->first()->jumlah_jam_istirahat;
                    $total_jam_lembur_finis=min($value->data_lembur->where('tanggal_berjalan',$value->tanggal_berjalan)->first()->jumlah_jam_lembur,$total_jam_lembur_finis);
                    if($value->kode_hari==5 || $value->kode_hari==6 || $value->status_absen=='LN' || ($value->mulai_jam_kerja==null && $value->akhir_jam_kerja==null)){
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
                    $kode_grade=EmployeeAtribut::select('kode_grade')->where('enroll_id',$value->enroll_id)->pluck('kode_grade')[0];
                    $salary_bulanan=GradingSalary::select('salary_bulanan')->where('kode_grade',$kode_grade)->where('periode_umk','2024-01')->pluck('salary_bulanan')[0];
                    if($value->kode_hari==6 || $value->status_absen=='LN'){
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
                        'final_jam_istirahat_lembur'=>$value->data_lembur->where('tanggal_berjalan',$value->tanggal_berjalan)->first()->jumlah_jam_istirahat??0,
                        'final_total_menit_lembur'=>($selisih_jam*60)+$selisih_menit,
                        'final_jam_lembur_roundown'=> $selisih_jam,
                        'final_menit_lembur_roundown'=>$selisih_menit,
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
                    $count=RekapPerhitunganLembur::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->count();
                    if($count){
                        RekapPerhitunganLembur::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->update($record_lemburan);
                    }
                    else{
                        RekapPerhitunganLembur::create($record_lemburan);
                    }
                }
            }
            $group_department='SUPPORTING PRODUCTION';
            if($value->employee_atribut->group_department!=null){
                $group_department=$value->employee_atribut->group_department->group2;
            }
            $status_staff=$value->employee_atribut->status_staff;
            $tanggal_sekarang=$value->tanggal_berjalan;
            $bulan_sekarang=substr($tanggal_sekarang,0,8).'26';
            $bulan_sebelum=date('Y-m-d',strtotime( "-1 month", strtotime( $bulan_sekarang ) ));
            $bulan_setelah=date('Y-m-d',strtotime( "+1 month", strtotime( $bulan_sekarang ) ));
            if($tanggal_sekarang>=$bulan_sebelum && $tanggal_sekarang<$bulan_sekarang){
                $tanggal_awal=$bulan_sebelum;
            }else if($tanggal_sekarang>=$bulan_sekarang && $tanggal_sekarang<$bulan_setelah){
                $tanggal_awal=$bulan_sekarang;
            }else{
                $tanggal_awal='';
            }
            $tanggal_akhir=date('Y-m-25',strtotime("+1 month",strtotime($tanggal_awal)));
            $tanggal_masuk=$value->employee_atribut->join_date;
            $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_awal))->y;
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
            $timestamp1 = strtotime($tanggal_awal);
            $timestamp2 = strtotime($tanggal_akhir);
            $timestamp3 = strtotime($tanggal_masuk);
            $datediff = $timestamp1 - $timestamp3;
            $selisih_hari=round($datediff / (60 * 60 * 24));
            $jumlah_hari_total=(abs($timestamp2 - $timestamp1) / (60 * 60 * 24)+1);
            $jumlah_hari_sabtu_minggu_total = 0;
            for ($i = strtotime($tanggal_awal); $i <= strtotime($tanggal_akhir); $i += 86400) {
                if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
                    $jumlah_hari_sabtu_minggu_total++;
                }
            }
            $insentif_kehadiran=0;
            $insentif_jabatan=0;
            $koreksi_upah=0;
            $koreksi_potongan=0;
            if($value->employee_atribut->grading_salary->first()->insentif){
                $insentif_kehadiran=($value->employee_atribut->grading_salary->first()->insentif)/21;
            }
            if(count($value->koreksi_upah->where('tanggal_koreksi',$value->tanggal_berjalan)->where('jenis_koreksi',2))>0){
                $insentif_jabatan=$value->koreksi_upah->where('tanggal_koreksi',$value->tanggal_berjalan)->where('jenis_koreksi',2)->sum('jumlah_rp_potongan');
            }
            if(count($value->koreksi_upah->where('tanggal_koreksi',$value->tanggal_berjalan)->where('jenis_koreksi','!=',2))>0){
                $koreksi_upah=$value->koreksi_upah->where('tanggal_koreksi',$value->tanggal_berjalan)->where('jenis_koreksi','!=',2)->sum('jumlah_rp_potongan');
            }
            if(count($value->koreksi_potongan->where('tanggal_koreksi',$value->tanggal_berjalan))>0){
                $koreksi_potongan=$value->koreksi_upah->where('tanggal_koreksi',$value->tanggal_berjalan)->sum('jumlah_rp_potongan');
            }
            $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->get();
            $jumlah_hari_libur_security=count(DB::select("select enroll_id from master_data_absen_kehadiran where tanggal_berjalan>='".$tanggal_awal."' and tanggal_berjalan<='".$tanggal_akhir."' and enroll_id = ".$enroll_id_karyawan." and mulai_jam_kerja is null"));
            if($security->where('enroll_id',$value->enroll_id)->count()){
                $jumlah_hari_kerja=$jumlah_hari_total-$jumlah_hari_libur_security;
            }else{
                $jumlah_hari_kerja=$jumlah_hari_total-$jumlah_hari_sabtu_minggu_total;
            }
            $jumlah_hari_kerja_employee=$jumlah_hari_total-$jumlah_hari_sabtu_minggu_total;
            if($tanggal_masuk>$tanggal_awal){
                $timestamp3 = strtotime($tanggal_masuk);
                $timestamp4 = strtotime($tanggal_akhir);
                $jumlah_hari_total_baru=(abs($timestamp4 - $timestamp3) / (60 * 60 * 24)+1);
                $jumlah_hari_sabtu_minggu_total_baru = 0;
                for ($i = strtotime($tanggal_masuk); $i <= strtotime($tanggal_akhir); $i += 86400) {
                    if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
                        $jumlah_hari_sabtu_minggu_total_baru++;
                    }
                }
                $jumlah_hari_kerja_employee=$jumlah_hari_total_baru-$jumlah_hari_sabtu_minggu_total_baru;
            }
            $insentif_kehadiran_total=(((($value->status_absen==null || $value->status_absen=='IKS') && $value->mulai_jam_kerja!=null))?$insentif_kehadiran:0);
            $total_lembur_rupiah=0;
            $countlembur=(int)count($value->rekap_lembur->where('tanggal_berjalan',$value->tanggal_berjalan));
            if($countlembur!=0){
                $total_lembur_rupiah=$value->rekap_lembur->where('tanggal_berjalan',$value->tanggal_berjalan)->first()->total_lembur_rupiah;
            }
            $periode_kehadiran=$tanggal_awal.' s/d '.$tanggal_akhir;
            $bpjs_tk=0;
            $bpjs_ks=0;
            $bpjs_tk_company=0;
            $bpjs_ks_company=0;
            if(count($value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_kehadiran))>0){
                $bpjs_tk=($value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_kehadiran)->first()->bpjs_tk_jkm_neto_rupiah
                +$value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_kehadiran)->first()->bpjs_tk_jkk_neto_rupiah
                +$value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_kehadiran)->first()->bpjs_tk_jht_neto_rupiah+
                $value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_kehadiran)->first()->bpjs_tk_jpn_neto_rupiah
                )/$jumlah_hari_kerja_employee;
                $bpjs_ks=($value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_kehadiran)->first()->bpjs_ks_jkn_neto_rupiah)/$jumlah_hari_kerja_employee;
                $bpjs_tk_company=($value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_kehadiran)->first()->bpjs_tk_jkm_bruto_rupiah+$value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_kehadiran)->first()->bpjs_tk_jht_bruto_rupiah+$value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_kehadiran)->first()->bpjs_tk_jkk_bruto_rupiah+$value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_kehadiran)->first()->bpjs_tk_jpn_bruto_rupiah)/$jumlah_hari_kerja_employee;
                $bpjs_ks_company=($value->employee_atribut->employee_bpjs->where('periode_kehadiran',$periode_kehadiran)->first()->bpjs_ks_jkn_bruto_rupiah)/$jumlah_hari_kerja_employee;
            }
            $thr=0;
            if($selisih_hari>30){
                $thr=$value->employee_atribut->grading_salary->first()->salary_bulanan/12/$jumlah_hari_kerja;
            }
            $bpjs_tk_total=($value->kode_hari!=5 && $value->kode_hari!=6)?$bpjs_tk:0;
            $bpjs_ks_total=($value->kode_hari!=5 && $value->kode_hari!=6)?$bpjs_ks:0;
            $bpjs_tk_company_total=($value->kode_hari!=5 && $value->kode_hari!=6)?$bpjs_tk_company:0;
            $bpjs_ks_company_total=($value->kode_hari!=5 && $value->kode_hari!=6)?$bpjs_ks_company:0;
            $thr_total=($value->kode_hari!=5 && $value->kode_hari!=6)?$thr:0;
            $id=$value->enroll_id;
            $tanggal_berjalan=$value->tanggal_berjalan;
            $count=count(DB::select('select*from mut_karyawan_input_form_lembur_det where enroll_id='.$id.' and konsumsi!=0 and no_form in (select no_form from mut_karyawan_input_form_lembur where tgl_lembur="'.$tanggal_berjalan.'")'));
            $count2=count(DB::select('select*from mut_karyawan_input_non_sewing_form_lembur_det where enroll_id='.$id.' and konsumsi!=0 and no_form in (select no_form from mut_karyawan_input_non_sewing_form_lembur where tgl_lembur="'.$tanggal_berjalan.'")'));
            if($count==1 && $count2!=1){
                $uang_makan=8000;
            }else if($count2==1 && $count2){
                $uang_makan=10000;
            }else{
                $uang_makan=0;
            }
            if($security->where('enroll_id',$value->enroll_id)->count()){
                $gaji_perhari=($value->employee_atribut->grading_salary->first()->salary_bulanan)/$jumlah_hari_kerja;
                $gaji_perhari_total=($value->mulai_jam_kerja!=null)?$gaji_perhari:0;
                $gaji_permenit=$gaji_perhari_total/420;
                $potongan_permenit=(($value->status_absen!='TL'||$value->status_absen==null)?(($value->jumlah_menit_absen_dt+$value->jumlah_menit_absen_pc+$value->total_menit_permits)*$gaji_permenit):0);
                $potongan_perhari=((in_array($value->status_absen, $ITB)||$value->status_absen=='M'||($value->status_absen=='TL' && $value->mulai_jam_kerja!=null)||$value->status_absen=='R')?$gaji_perhari:0);
                $seniority_allowance=$tunjangan/$jumlah_hari_kerja;
                $seniority_allowance_total=($value->mulai_jam_kerja!=null)?$tunjangan/$jumlah_hari_kerja:0;
                $bruto=($value->mulai_jam_kerja!=null)?(($gaji_perhari_total+$seniority_allowance_total+$insentif_kehadiran_total+$total_lembur_rupiah+$koreksi_upah+$insentif_jabatan)-($koreksi_potongan+$potongan_permenit+$potongan_perhari)):(($total_lembur_rupiah+$insentif_jabatan+$koreksi_upah)-($potongan_permenit+$potongan_perhari));
                $jumlah=$bruto-($bpjs_tk_total+$bpjs_ks_total);
                $pembulatan=(ceil($jumlah/100)*100)-$jumlah;
                $total_pembayaran=$jumlah+$bpjs_tk_company_total+$bpjs_ks_company_total+$thr_total+$thr_total+$uang_makan;
            }else{
                $gaji_perhari=($value->employee_atribut->grading_salary->first()->salary_bulanan)/$jumlah_hari_kerja;
                $gaji_perhari_total=($value->kode_hari!=5 && $value->kode_hari!=6)?$gaji_perhari:0;
                $gaji_permenit=$gaji_perhari_total/480;
                $potongan_permenit=(($value->status_absen!='TL'||$value->status_absen==null)?(($value->jumlah_menit_absen_dt+$value->jumlah_menit_absen_pc+$value->total_menit_permits)*$gaji_permenit):0);
                $potongan_perhari=((in_array($value->status_absen, $ITB)||$value->status_absen=='M'||($value->status_absen=='TL' && $value->mulai_jam_kerja!=null)||$value->status_absen=='R')?$gaji_perhari:0);
                $seniority_allowance=$tunjangan/$jumlah_hari_kerja;
                $seniority_allowance_total=($value->kode_hari!=5 && $value->kode_hari!=6)?$tunjangan/$jumlah_hari_kerja:0;
                $bruto=($value->kode_hari!=5 && $value->kode_hari!=6)?(($gaji_perhari_total+$seniority_allowance_total+$insentif_kehadiran_total+$total_lembur_rupiah+$koreksi_upah+$insentif_jabatan)-($koreksi_potongan+$potongan_permenit+$potongan_perhari)):(($total_lembur_rupiah+$koreksi_upah+$insentif_jabatan)-($potongan_permenit+$potongan_perhari));
                $jumlah=$bruto-($bpjs_tk_total+$bpjs_ks_total);
                $pembulatan=(ceil($jumlah/100)*100)-$jumlah;
                $total_pembayaran=$jumlah+$bpjs_tk_company_total+$bpjs_ks_company_total+$thr_total+$thr_total+$uang_makan;
            }
            $z=[
                'tanggal_berjalan'=>$value->tanggal_berjalan,
                'enroll_id'=>$value->enroll_id,
                'status_staff'=>$status_staff,
                'group_department'=>$group_department,
                'iby'=>in_array($value->status_absen, $IBY)?1:0,
                'itb'=>in_array($value->status_absen, $ITB)?1:0,
                'm'=>($value->status_absen=='M'||($value->status_absen=='TL' && $value->mulai_jam_kerja!=null))?1:0,
                'dt'=>($value->jumlah_menit_absen_dt!=0 && $value->jumlah_menit_absen_pc==0)?1:0,
                'pc'=>($value->jumlah_menit_absen_dt==0 && $value->jumlah_menit_absen_pc!=0)?1:0,
                'dtpc'=>($value->jumlah_menit_absen_dt!=0 && $value->jumlah_menit_absen_pc!=0)?1:0,
                'lby'=>($value->status_absen=='LN' && $value->kode_hari!=5 && $value->kode_hari!=6)?1:0,
                'lsm'=>($value->status_absen=='LN' && ($value->kode_hari==5 || $value->kode_hari==6))?1:0,
                'r'=>$value->status_absen=='R'?1:0,
                'ok'=>((($value->status_absen==null || $value->status_absen=='IKS') && $value->mulai_jam_kerja!=null && $value->jumlah_menit_absen_dtpc==0))?1:0,
                'hari_kerja'=>((($value->status_absen==null || $value->status_absen=='IKS') && $value->mulai_jam_kerja!=null)||(in_array($value->status_absen, $IBY))||($value->status_absen=='LN' && $value->kode_hari!=5 && $value->kode_hari!=6))?1:0,
                'pot_hari_kerja'=>(in_array($value->status_absen, $ITB)||$value->status_absen=='M'||($value->status_absen=='TL' && $value->mulai_jam_kerja!=null)||$value->status_absen=='R')?1:0,
                'total_absen'=>(in_array($value->status_absen, $ITB)||$value->status_absen=='M'||($value->status_absen=='TL' && $value->mulai_jam_kerja!=null)||$value->status_absen=='R'||in_array($value->status_absen, $IBY)||($value->status_absen=='LN' && $value->kode_hari!=5 && $value->kode_hari!=6))?1:0,
                'gaji_perhari'=>$gaji_perhari_total,
                'gaji_permenit'=>$gaji_permenit,
                'total_lembur_rupiah'=>$total_lembur_rupiah,
                'seniority_allowance'=>$seniority_allowance_total,
                'insentif_kehadiran'=>$insentif_kehadiran_total,
                'insentif_jabatan'=>$insentif_jabatan,
                'rp_pot_hari_kerja'=>$potongan_perhari,
                'rp_pot_jam'=>$potongan_permenit,
                'bruto'=>$bruto,
                'bpjs_tk'=>$bpjs_tk_total,
                'bpjs_ks'=>$bpjs_ks_total,
                'total_potongan'=>$bpjs_tk_total+$bpjs_ks_total,
                'pembulatan'=>$pembulatan,
                'jumlah'=>$jumlah,
                'bpjs_tk_company'=>$bpjs_tk_company_total,
                'bpjs_ks_company'=>$bpjs_ks_company_total,
                'kompensasi'=>$thr_total,
                'thr'=>$thr_total,
                'konsumsi'=>$uang_makan,
                'total_pembayaran'=>$total_pembayaran,
            ];
            if(DailyLaborCost::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->first()){
                DailyLaborCost::where('tanggal_berjalan',$value->tanggal_berjalan)->where('enroll_id',$value->enroll_id)->update($z);
            }else{
                DailyLaborCost::create($z);
            }
        }
    }
    public function rekap_bpjs(){
        
        $kode_bpjs =  BpjsSetting::orderBy('kode_periode_bpjs','desc')->limit(1)->first();
        $explodePeriodePayroll = explode(" s/d ", $periode_kehadiran);
        $periodePayroll = substr($explodePeriodePayroll[1], 0, 4) . substr($explodePeriodePayroll[1], 5, 2);
        $explodeKode = explode("-", $kode_bpjs->kode_periode_bpjs);
        $kode_periode_bpjs = $explodeKode[0] . $explodeKode[1];
        $sqlKodePeriodeBPJS = 'concat("' . $periodePayroll . '", lpad(enroll_id, 5, 0))';
        $id_absen=$value->enroll_id;
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
            enroll_id ='.$id_absen.'
            AND (tanggal_resign is null OR tanggal_resign = "0000-00-00" OR
                NOT tanggal_resign < DATE_ADD( LAST_DAY( DATE_SUB( "' . $explodePeriodePayroll[1] . '", INTERVAL 2 MONTH )), INTERVAL 26 DAY ))
            AND join_date <= "' . $explodePeriodePayroll[1] . '"
        ')
        ->groupBy('enroll_id')
        ->groupBy('employee_name')
        ->get();
        foreach ($queryEmpAtr as $key => $value2) {

            $countEmp = EmployeeBpjs::whereRaw('kode_bpjs = ' . $sqlKodePeriodeBPJS . ' AND enroll_id = "' . $value['enroll_id'] . '"')->count();

            if ($countEmp) {
                $queryEmpBpjs = EmployeeBpjs::whereRaw('kode_bpjs = ' . $sqlKodePeriodeBPJS . ' AND enroll_id = "' . $value['enroll_id'] . '"')
                ->update([
                    'status_aktif_bpjs_tk' => $value2['status_aktif_bpjs_tk'],
                    'tanggal_bpjs_ketenagakerjaan' => $value2['tanggal_bpjs_ketenagakerjaan'],
                    'nomor_bpjs_ketenagakerjaan' => $value2['nomor_bpjs_ketenagakerjaan'],
                    'status_aktif_bpjs_ks' => $value2['status_aktif_bpjs_ks'],
                    'tanggal_bpjs_kesehatan' => $value2['tanggal_bpjs_kesehatan'],
                    'nomor_bpjs_kesehatan' => $value2['nomor_bpjs_kesehatan'],
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
                    'kode_bpjs' => $value2['kode_bpjs'],
                    'periode_bpjs' => $value2['periode_bpjs'],
                    'periode_kehadiran' => $value2['periode_kehadiran'],
                    'enroll_id' => $value2['enroll_id'],
                    'nik' => $value2['nik'],
                    'employee_name' => $value2['employee_name'],
                    'status_aktif_bpjs_tk' => $value2['status_aktif_bpjs_tk'],
                    'tanggal_bpjs_ketenagakerjaan' => $value2['tanggal_bpjs_ketenagakerjaan'],
                    'nomor_bpjs_ketenagakerjaan' => $value2['nomor_bpjs_ketenagakerjaan'],
                    'status_aktif_bpjs_ks' => $value2['status_aktif_bpjs_ks'],
                    'tanggal_bpjs_kesehatan' => $value2['tanggal_bpjs_kesehatan'],
                    'nomor_bpjs_kesehatan' => $value2['nomor_bpjs_kesehatan'],
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

        $EmpBpjs = EmployeeBpjs::where('periode_kehadiran',$periode_kehadiran)->get();

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
    }
    public function ajax_getallemployeeatribut()
    {
        $query =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name,
                                           concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                    ->groupby('enroll_id')
                                    ->orderby('employee_name', 'asc')
                                    ->get();
        return $query;

    }
    public function get_sub_dept_name($id){
        $sub_dept_names=DepartmentAll::where('department_id',$id)->get();
        return $sub_dept_names;
    }
    public function ajax_getperiodepayroll()
    {
        $query =  RekapPerhitunganPayroll::selectRaw('CONCAT(periode_tahun_payroll,"-",periode_bulan_payroll) periode_payroll')
                        ->groupby('periode_payroll')
                        ->orderby('periode_payroll', 'desc')
                        ->get();
        return $query;

    }

    public function ajax_getperiodekehadiran()
    {
        $query =  RekapPerhitunganPayroll::selectRaw('periode_kehadiran')
                        ->groupby('periode_kehadiran')
                        ->orderby('periode_kehadiran', 'desc')
                        ->get();
        return $query;

    }

    public function ajax_exportexcel(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        $periode_payroll = $request->input('periode_payroll');
        list($year, $month) = explode('-', $request->periode_payroll);
        $first=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->first();
        list($tgl_awal, $tgl_akhir) = explode(' s/d ', $first->periode_kehadiran);
        $department_id=request()->department_id;
        $searchData='';
        if(request()->selectEmployee){
            $enroll_id=request()->selectEmployee;
            $searchData=implode(',', $enroll_id);
        }
        if($department_id){
            $department_id=request()->department_id;
        }else{
            $department_id='';
        }
        $sub_dept_id=request()->sub_dept_id;
        if($sub_dept_id){
            $sub_dept_id=request()->sub_dept_id;
        }else{
            $sub_dept_id='';
        }
        $status_staff=request()->status_staff;
        if($status_staff){
            $status_staff=request()->status_staff;
        }else{
            $status_staff='';
        }
        $periode_umk=request()->periode_umks;
        if($periode_umk){
            $periode_umk=request()->periode_umks;
        }else{
            $periode_umk='';
        }
        if($periode_payroll=='2024-01'){
            if($periode_umk=='2023-10'){
                $fileName = 'RekapPerhitunganPayroll_2024-01_UMK-2023_' . time() . '.xlsx';
                return (new RekapPerhitunganPayrollExportUMK2023)->exportParams($periode_payroll,$tgl_awal,$department_id,$sub_dept_id,$status_staff,$periode_umk)->download($fileName);
            }
            if($periode_umk=='2024-01'){
                $fileName = 'RekapPerhitunganPayroll_2024-01_UMK-2024_' . time() . '.xlsx';
                return (new RekapPerhitunganPayrollExportUMK2024)->exportParams($periode_payroll,$tgl_awal,$department_id,$sub_dept_id,$status_staff,$periode_umk)->download($fileName);
            }else{
                $fileName = 'RekapPerhitunganPayroll_' . time() . '.xlsx';
                $response=(new RekapPerhitunganPayrollExportJanuari2024)->exportParams($periode_payroll,$tgl_awal,$department_id,$sub_dept_id,$status_staff,$periode_umk)->download($fileName, \Maatwebsite\Excel\Excel::XLSX);
                ob_end_clean();
                return $response;
            }
        }else{
            $fileName = 'RekapPerhitunganPayroll_' . time() . '.xlsx';
            $response=(new RekapPerhitunganPayrollExport)->exportParams($periode_payroll,$tgl_awal,$department_id,$sub_dept_id,$status_staff,$periode_umk,$searchData)->download($fileName, \Maatwebsite\Excel\Excel::XLSX);
            ob_end_clean();
            return $response;
        }
    }

}
