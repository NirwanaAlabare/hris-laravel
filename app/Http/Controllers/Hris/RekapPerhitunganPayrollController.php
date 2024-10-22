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
use App\Models\DataKoreksiPotongan;
use App\Models\DepartmentAll;
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
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';
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
                ->where('total_kehadiran_net','>',0)->get();
                $payroll_before=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year_before)->where('periode_bulan_payroll', $month_before)
                ->where('kategori_karyawan',$status_staff)->where('nama_department',$value->department_name)
                ->where('total_kehadiran_net','>',0)->get();
            }else{
                $payroll=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('nama_department',$value->department_name)
                ->where('total_kehadiran_net','>',0)->get();
                $payroll_before=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year_before)->where('periode_bulan_payroll', $month_before)->where('nama_department',$value->department_name)
                ->where('total_kehadiran_net','>',0)->get();
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
            if(ceil($payroll_before->sum('total_upah_thp_rupiah') / 100) * 100==0){$jumlah_sebelum=0;}else{$jumlah_sebelum=number_format( ceil($payroll_before->sum('total_upah_thp_rupiah') / 100) * 100 , 0 , ',' , '.');}
            $jumlah_sebelum_int=ceil($payroll_before->sum('total_upah_thp_rupiah') / 100) * 100;
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
            $jumlah_int=$payroll->sum('total_upah_thp_rupiah_employee');
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
