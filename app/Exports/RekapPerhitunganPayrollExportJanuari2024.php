<?php

namespace App\Exports;

use App\Models\RekapPerhitunganPayroll;
use App\Models\DataKoreksiUpah;
use App\Http\Controllers\Hris\RekapPayrollController;

use App\User;
use App\Models\DepartmentAll;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;

use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Illuminate\Support\Facades\DB;

use Auth;

class RekapPerhitunganPayrollExportJanuari2024 implements FromQuery, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle,  WithColumnFormatting
{
    use Exportable;

    public function exportParams(string $periode_payroll, string $tgl_awal, $department_id, $sub_dept_id, $status_staff, $periode_umk,$enroll_id)
    {
        $this->periode_payroll = $periode_payroll;
        $this->tgl_awal = $tgl_awal;
        $this->department_id=$department_id;
        $this->sub_dept_id=$sub_dept_id;
        $this->status_staff=$status_staff;
        $this->periode_umk=$periode_umk;
        $this->enroll_id=$enroll_id;

        return $this;
    }

    public function query()
    {
        $tgl_awal=$this->tgl_awal;
        $department_id=$this->department_id;
        $sub_dept_id=$this->sub_dept_id;
        $status_staff=$this->status_staff;
        $periode_umk=$this->periode_umk;
        $enroll_ids=$this->enroll_id;
        $inEnrollId='';
        $inDepartmentId='';
        $inSubDepartment='';
        $inStatusStaff='';
        $inPeriodeUMK='';
        if($this->enroll_id!=''){
            $inEnrollId=' AND enroll_id in ('.$enroll_ids.')';
        }
        if($department_id){
        $nama_department=DepartmentAll::select('department_name')->where('department_id',$this->department_id)->pluck('department_name')[0];
        $inDepartmentId=' AND nama_department = "'.$nama_department.'"';
        }
        if($sub_dept_id){
        $sub_department_name=DepartmentAll::select('sub_dept_name')->where('sub_dept_id',$sub_dept_id)->pluck('sub_dept_name')[0];
        $inSubDepartment = ' AND nama_bagian = "' . $sub_department_name . '"';
        }
        if($status_staff){
        $inStatusStaff='AND kategori_karyawan = "'.$status_staff.'"';
        }
        $periode_umk_title='';
        if($periode_umk){
            $inPeriodeUMK='AND periode_umk = "'.$periode_umk.'"';
        }else{
            $inPeriodeUMK='AND periode_umk IS NULL';
        }
        $q =  RekapPerhitunganPayroll::query()
                ->selectRaw('
                    kode_rekap_payroll,
                    periode_kehadiran,
                    CONCAT(periode_tahun_payroll,"-",periode_bulan_payroll) periode_payroll,
                    periode_tahun_payroll,
                    periode_bulan_payroll,
                    enroll_id,
                    nik,
                    periode_kehadiran,
                    kode_grade,
                    site_nirwana_name,
                    join_date,
                    employee_name,
                    tanggal_resign,
                    kehadiran_iby,
                    kehadiran_itb,
                    kehadiran_m,
                    kehadiran_dt,
                    kehadiran_pc,
                    kehadiran_dtpc,
                    sub_dept_id,
                    kehadiran_lby,
                    kehadiran_lsm,
                    kehadiran_r,
                    kehadiran_ok,
                    kehadiran_tk,
                    total_kehadiran,
                    total_kehadiran_net,
                    ptkp,
                    upah_per_bulan,
                    upah_per_hari,
                    upah_per_menit,
                    tunjangan_karyawan_rupiah,
                    premi_karyawan,
                    lembur_1,
                    lembur_2,
                    lembur_3,
                    lembur_4,
                    total_lembur_1234,
                    lembur1_rupiah,
                    lembur2_rupiah,
                    lembur3_rupiah,
                    lembur4_rupiah,
                    total_lembur_rupiah,
                    pendapatan_lainnya_rupiah,
                    koreksi_upah_rupiah,
                    koreksi_potongan_rupiah,
                    potongan_iks_menit,
                    potongan_dt_menit,
                    potongan_pc_menit,
                    potongan_dtpc_menit,
                    potongan_iks_rupiah,
                    potongan_dt_rupiah,
                    potongan_pc_rupiah,
                    potongan_iks_rupiah + potongan_dtpc_rupiah total_potongan_jam_rupiah,
                    potongan_kehadiran_rupiah,
                    upah_bruto_rupiah,
                    pph21,
                    upah_neto_rupiah,
                    total_bpjs_tk,
                    total_bpjs_ks,
                    iuran_serikat_rupiah,
                    iuran_koperasi,
                    jumlah_potongan_rupiah,
                    upah_bersih_rupiah,
                    potongan_kasbon_rupiah,
                    total_upah_thp_rupiah,
                    total_upah_thp_rupiah_employee,
                    bpjs_tk_jkm_rupiah,
                    bpjs_tk_jkm_perusahaan_rupiah,
                    bpjs_tk_jkm_karyawan_rupiah,
                    bpjs_tk_jkk_rupiah,
                    bpjs_tk_jkk_perusahaan_rupiah,
                    bpjs_tk_jkk_karyawan_rupiah,
                    bpjs_tk_jht_rupiah,
                    bpjs_tk_jht_perusahaan_rupiah,
                    bpjs_tk_jht_karyawan_rupiah,
                    bpjs_tk_jpn_rupiah,
                    bpjs_tk_jpn_perusahaan_rupiah,
                    bpjs_tk_jpn_karyawan_rupiah,
                    bpjs_ks_jkn_rupiah,
                    bpjs_ks_jkn_perusahaan_rupiah,
                    bpjs_ks_jkn_karyawan_rupiah,
                    jabatan_karyawan,
                    nama_bagian,
                    nama_department,
                    kategori_karyawan,
                    aktif_karyawan,
                    jenis_kelamin,
                    nama_bank,
                    CONCAT(nomor_rekening_bank," ") nomor_rekening_bank,
                    npwp,
                    operator,
                    status_kawin,
                    potongan_dtpc_rupiah,
                    created_at,
                    updated_at

                ')
                ->whereRaw('
                    CONCAT(periode_tahun_payroll, "-", periode_bulan_payroll) = "' . $this->periode_payroll . '"'.$inEnrollId.''.$inDepartmentId.''.$inSubDepartment.''.$inStatusStaff.''.$inPeriodeUMK.'
                ')
                 ->where(function ($query) use ($tgl_awal) {
                    $query->orWhereNull('tanggal_resign')
                        ->orWhere('tanggal_resign', '>', $tgl_awal);
                })
                ->orderBy('employee_name','asc')
                ->orderBy('periode_payroll','desc')
                ->limit(1);

        return $q;
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function map($Data): array
    {
        $enroll_id = $Data->enroll_id;
        $nik = $Data->nik;
        $employee_name = $Data->employee_name;
        $kode_grade= $Data->kode_grade;


        $join_date=$Data->join_date!=null?date('d-m-Y', strtotime($Data->join_date)):$Data->join_date;
        $site_nirwana_name = $Data->site_nirwana_name;

        if($Data->kehadiran_iby == 0){ $kehadiran_iby = '0';} else { $kehadiran_iby = $Data->kehadiran_iby; }
        if($Data->kehadiran_itb == 0){ $kehadiran_itb = '0';} else { $kehadiran_itb = $Data->kehadiran_itb; }
        if($Data->kehadiran_m == 0){ $kehadiran_m = '0';} else { $kehadiran_m = $Data->kehadiran_m; }
        if($Data->kehadiran_dt == 0){ $kehadiran_dt = '0';} else { $kehadiran_dt = $Data->kehadiran_dt; }
        if($Data->kehadiran_pc == 0){ $kehadiran_pc = '0';} else { $kehadiran_pc = $Data->kehadiran_pc; }
        if($Data->kehadiran_dtpc == 0){ $kehadiran_dtpc = '0';} else { $kehadiran_dtpc = $Data->kehadiran_dtpc; }
        if($Data->kehadiran_lby == 0){ $kehadiran_lby = '0';} else { $kehadiran_lby = $Data->kehadiran_lby; }
        if($Data->kehadiran_lsm == 0){ $kehadiran_lsm = '0';} else { $kehadiran_lsm = $Data->kehadiran_lsm; }
        if($Data->kehadiran_r == 0){ $kehadiran_r = '0';} else { $kehadiran_r = $Data->kehadiran_r; }
        if($Data->kehadiran_ok == 0){ $kehadiran_ok = '0';} else { $kehadiran_ok = $Data->kehadiran_ok; }
        if($Data->kehadiran_tk == 0){ $kehadiran_tk = '0';} else { $kehadiran_tk = $Data->kehadiran_tk; }
        if($Data->total_kehadiran == 0){ $total_kehadiran = '0';} else { $total_kehadiran = $Data->total_kehadiran; }
        if($Data->total_kehadiran_net == 0){ $total_kehadiran_net = '0';} else { $total_kehadiran_net = $Data->total_kehadiran_net; }
        $ptkp = $Data->ptkp;
        $st = $Data->status_kawin;

        if($Data->upah_per_bulan == 0){ $upah_per_bulan = '0';} else { $upah_per_bulan = $Data->upah_per_bulan; }
        if($Data->upah_per_hari == 0){ $upah_per_hari = '0';} else { $upah_per_hari = $Data->upah_per_hari; }
        if($Data->tunjangan_karyawan_rupiah == 0){ $tunjangan_karyawan_rupiah = '0'; $readOnlyTunjanganKaryawanRupiah = '0'; } else { $tunjangan_karyawan_rupiah = $Data->tunjangan_karyawan_rupiah; $readOnlyTunjanganKaryawanRupiah = $Data->tunjangan_karyawan_rupiah; }
        if($Data->premi_karyawan == 0){ $premi_karyawan = '0';} else { $premi_karyawan = $Data->premi_karyawan; }
        if($Data->lembur_1 == 0){ $lembur_1 = '0';} else { $lembur_1 = $Data->lembur_1; }
        if($Data->lembur_2 == 0){ $lembur_2 = '0';} else { $lembur_2 = $Data->lembur_2; }
        if($Data->lembur_3 == 0){ $lembur_3 = '0';} else { $lembur_3 = $Data->lembur_3; }
        if($Data->lembur_4 == 0){ $lembur_4 = '0';} else { $lembur_4 = $Data->lembur_4; }
        if($Data->total_lembur_1234 == 0){ $total_lembur_1234 = '0';} else { $total_lembur_1234 = $Data->total_lembur_1234; }
        if($Data->lembur1_rupiah == 0){ $lembur1_rupiah = '0';} else { $lembur1_rupiah = $Data->lembur1_rupiah; }
        if($Data->lembur2_rupiah == 0){ $lembur2_rupiah = '0';} else { $lembur2_rupiah = $Data->lembur2_rupiah; }
        if($Data->lembur3_rupiah == 0){ $lembur3_rupiah = '0';} else { $lembur3_rupiah = $Data->lembur3_rupiah; }
        if($Data->lembur4_rupiah == 0){ $lembur4_rupiah = '0';} else { $lembur4_rupiah = $Data->lembur4_rupiah; }
        if($Data->total_lembur_rupiah == 0){ $total_lembur_rupiah = '0';} else { $total_lembur_rupiah = $Data->total_lembur_rupiah; }
        if($Data->koreksi_upah_rupiah == 0){ $koreksi_upah_rupiah = '0';} else { $koreksi_upah_rupiah = $Data->koreksi_upah_rupiah; }
        if($Data->pendapatan_lainnya_rupiah == 0){ $pendapatan_lainnya_rupiah = '0';} else { $pendapatan_lainnya_rupiah = $Data->pendapatan_lainnya_rupiah; }
        if($Data->koreksi_potongan_rupiah == 0){ $koreksi_potongan_rupiah = '0';} else { $koreksi_potongan_rupiah = $Data->koreksi_potongan_rupiah; }
        if($Data->potongan_iks_menit == 0){ $potongan_iks_menit = '0';} else { $potongan_iks_menit = $Data->potongan_iks_menit; }
        if($Data->potongan_dt_menit == 0){ $potongan_dt_menit = '0';} else { $potongan_dt_menit = $Data->potongan_dt_menit; }
        if($Data->potongan_pc_menit == 0){ $potongan_pc_menit = '0';} else { $potongan_pc_menit = $Data->potongan_pc_menit; }
        if($Data->potongan_iks_rupiah == 0){ $potongan_iks_rupiah = '0';} else { $potongan_iks_rupiah = $Data->potongan_iks_rupiah; }
        if($Data->potongan_dt_rupiah == 0){ $potongan_dt_rupiah = '0';} else { $potongan_dt_rupiah = $Data->potongan_dt_rupiah; }
        if($Data->potongan_pc_rupiah == 0){ $potongan_pc_rupiah = '0';} else { $potongan_pc_rupiah = $Data->potongan_pc_rupiah; }
        if($Data->total_potongan_jam_rupiah == 0){ $total_potongan_jam_rupiah = '0';} else { $total_potongan_jam_rupiah = $Data->total_potongan_jam_rupiah; }
        if($Data->potongan_kehadiran_rupiah == 0){ $potongan_kehadiran_rupiah = '0';} else { $potongan_kehadiran_rupiah = $Data->potongan_kehadiran_rupiah; }
        if($Data->upah_bruto_rupiah == 0){ $upah_bruto_rupiah = '0';} else { $upah_bruto_rupiah = $Data->upah_bruto_rupiah; }
        $pph21 = $Data->pph21;
        if($Data->upah_neto_rupiah == 0){ $upah_neto_rupiah = '0';} else { $upah_neto_rupiah = $Data->upah_neto_rupiah; }
        if($Data->total_bpjs_tk == 0){ $total_bpjs_tk = '0';} else { $total_bpjs_tk = $Data->total_bpjs_tk; }
        if($Data->total_bpjs_ks == 0){ $total_bpjs_ks = '0';} else { $total_bpjs_ks = $Data->total_bpjs_ks; }
        if($Data->iuran_serikat_rupiah == 0){ $iuran_serikat_rupiah = '0';} else { $iuran_serikat_rupiah = $Data->iuran_serikat_rupiah; }
        if($Data->iuran_koperasi == 0){ $iuran_koperasi = '0';} else { $iuran_koperasi = $Data->iuran_koperasi; }
        if($Data->jumlah_potongan_rupiah == 0){ $jumlah_potongan_rupiah = '0';} else { $jumlah_potongan_rupiah = $Data->jumlah_potongan_rupiah; }
        if($Data->upah_bersih_rupiah == 0){ $upah_bersih_rupiah = '0';} else { $upah_bersih_rupiah = $Data->upah_bersih_rupiah; }
        if($Data->potongan_kasbon_rupiah == 0){ $potongan_kasbon_rupiah = '0';} else { $potongan_kasbon_rupiah = $Data->potongan_kasbon_rupiah; }
        if($Data->total_upah_thp_rupiah == 0){ $total_upah_thp_rupiah = '0';} else { $total_upah_thp_rupiah = $Data->total_upah_thp_rupiah; }
        if($Data->total_upah_thp_rupiah_employee == 0){ $total_upah_thp_rupiah_employee = '0';} else { $total_upah_thp_rupiah_employee = $Data->total_upah_thp_rupiah_employee; }
        if($Data->total_upah_thp_rupiah == 0){ $total_upah_thp_rupiah_pecahan = '0';} else { $total_upah_thp_rupiah_pecahan = $Data->total_upah_thp_rupiah; }
        if($Data->bpjs_tk_jkm_perusahaan_rupiah == 0){ $bpjs_tk_jkm_perusahaan_rupiah = '0';} else { $bpjs_tk_jkm_perusahaan_rupiah = $Data->bpjs_tk_jkm_perusahaan_rupiah; }
        if($Data->bpjs_tk_jkm_karyawan_rupiah == 0){ $bpjs_tk_jkm_karyawan_rupiah = '0';} else { $bpjs_tk_jkm_karyawan_rupiah = $Data->bpjs_tk_jkm_karyawan_rupiah; }
        if($Data->bpjs_tk_jkm_rupiah == 0){ $bpjs_tk_jkm_rupiah = '0';} else { $bpjs_tk_jkm_rupiah = $Data->bpjs_tk_jkm_rupiah; }
        if($Data->bpjs_tk_jkk_perusahaan_rupiah == 0){ $bpjs_tk_jkk_perusahaan_rupiah = '0';} else { $bpjs_tk_jkk_perusahaan_rupiah = $Data->bpjs_tk_jkk_perusahaan_rupiah; }
        if($Data->bpjs_tk_jkk_karyawan_rupiah == 0){ $bpjs_tk_jkk_karyawan_rupiah = '0';} else { $bpjs_tk_jkk_karyawan_rupiah = $Data->bpjs_tk_jkk_karyawan_rupiah; }
        if($Data->bpjs_tk_jkk_rupiah == 0){ $bpjs_tk_jkk_rupiah = '0';} else { $bpjs_tk_jkk_rupiah = $Data->bpjs_tk_jkk_rupiah; }
        if($Data->bpjs_tk_jht_perusahaan_rupiah == 0){ $bpjs_tk_jht_perusahaan_rupiah = '0';} else { $bpjs_tk_jht_perusahaan_rupiah = $Data->bpjs_tk_jht_perusahaan_rupiah; }
        if($Data->bpjs_tk_jht_karyawan_rupiah == 0){ $bpjs_tk_jht_karyawan_rupiah = '0';} else { $bpjs_tk_jht_karyawan_rupiah = $Data->bpjs_tk_jht_karyawan_rupiah; }
        if($Data->bpjs_tk_jht_rupiah == 0){ $bpjs_tk_jht_rupiah = '0';} else { $bpjs_tk_jht_rupiah = $Data->bpjs_tk_jht_rupiah; }
        if($Data->bpjs_tk_jpn_perusahaan_rupiah == 0){ $bpjs_tk_jpn_perusahaan_rupiah = '0';} else { $bpjs_tk_jpn_perusahaan_rupiah = $Data->bpjs_tk_jpn_perusahaan_rupiah; }
        if($Data->bpjs_tk_jpn_karyawan_rupiah == 0){ $bpjs_tk_jpn_karyawan_rupiah = '0';} else { $bpjs_tk_jpn_karyawan_rupiah = $Data->bpjs_tk_jpn_karyawan_rupiah; }
        if($Data->bpjs_tk_jpn_rupiah == 0){ $bpjs_tk_jpn_rupiah = '0';} else { $bpjs_tk_jpn_rupiah = $Data->bpjs_tk_jpn_rupiah; }
        if($Data->bpjs_ks_jkn_perusahaan_rupiah == 0){ $bpjs_ks_jkn_perusahaan_rupiah = '0';} else { $bpjs_ks_jkn_perusahaan_rupiah = $Data->bpjs_ks_jkn_perusahaan_rupiah; }
        if($Data->bpjs_ks_jkn_karyawan_rupiah == 0){ $bpjs_ks_jkn_karyawan_rupiah = '0';} else { $bpjs_ks_jkn_karyawan_rupiah = $Data->bpjs_ks_jkn_karyawan_rupiah; }
        if($Data->bpjs_ks_jkn_rupiah == 0){ $bpjs_ks_jkn_rupiah = '0';} else { $bpjs_ks_jkn_rupiah = $Data->bpjs_ks_jkn_rupiah; }
        $jabatan_karyawan = $Data->jabatan_karyawan;
        $nama_bagian = $Data->nama_bagian;
        $department_id='';
        if(isset(DepartmentAll::select('department_id')->where('department_name',$Data->nama_department)->groupBy('department_id')->pluck('department_id')[0])){
            $department_id = DepartmentAll::select('department_id')->where('department_name',$Data->nama_department)->groupBy('department_id')->pluck('department_id')[0];
        }
        $sub_dept_id='';
        if(isset(DepartmentAll::select('sub_dept_id')->where('department_name',$Data->nama_department)->where('sub_dept_name',$Data->nama_bagian)->pluck('sub_dept_id')[0])){
            $sub_dept_id = DepartmentAll::select('sub_dept_id')->where('department_name',$Data->nama_department)->where('sub_dept_name',$Data->nama_bagian)->pluck('sub_dept_id')[0];
        }
        $nama_department = $Data->nama_department;
        $kategori_karyawan = $Data->kategori_karyawan;
        $aktif_karyawan = $Data->aktif_karyawan;
        $jenis_kelamin = $Data->jenis_kelamin;
        if($Data->nama_bank==null){ $nama_bank = '-';} else { $nama_bank = $Data->nama_bank; }
        //if($Data->nomor_rekening_bank == 0){ $nomor_rekening_bank = '-';} else { $nomor_rekening_bank = $Data->nomor_rekening_bank; }
        if($Data->nomor_rekening_bank == 0|| $Data->nomor_rekening_bank == 'TRANSFER' ){ $nomor_rekening_bank = '-';} else { $nomor_rekening_bank = $Data->nomor_rekening_bank; }
        if($Data->npwp == 0){ $npwp = '-';} else { $npwp = $Data->npwp; }

        $total_upah_thp_rupiah_pembulatan= ceil($total_upah_thp_rupiah / 100) * 100;
        $pembulatan=$total_upah_thp_rupiah_pembulatan-$total_upah_thp_rupiah;
        $upah_per_jam=$upah_per_bulan/173;
        $periode_kehadiran= $Data->periode_kehadiran;
        $explodePeriodePayroll = explode(" s/d ", $periode_kehadiran);
        $periodePayroll =$explodePeriodePayroll[0];

        $startDate = Carbon::parse($join_date);
        $endDate = Carbon::parse($periodePayroll);
        $diff = $startDate->diff($endDate);
        $years = $diff->y;
        $months = $diff->m;
        $days = $diff->d;
        $kosong=" ";
        $nol='0';
        $pot_hari_kerja=$kehadiran_itb+$kehadiran_m+$kehadiran_r;
        $total_absen=$kehadiran_itb+$kehadiran_m+$kehadiran_r+$kehadiran_iby+$kehadiran_lby;
        $desember=RekapPerhitunganPayroll::where('enroll_id',$Data->enroll_id)->where('periode_tahun_payroll',$Data->periode_tahun_payroll)->where('periode_bulan_payroll',$Data->periode_bulan_payroll)->where('periode_umk','2024-01')->count();
        if($desember==1){
            $bruto_desember=RekapPerhitunganPayroll::select('upah_bruto_rupiah')->where('enroll_id',$Data->enroll_id)->where('periode_tahun_payroll',$Data->periode_tahun_payroll)->where('periode_bulan_payroll',$Data->periode_bulan_payroll)->where('periode_umk','2024-01')->pluck('upah_bruto_rupiah')[0];
        }else{
            $bruto_desember=0;
        }
        $januari=RekapPerhitunganPayroll::where('enroll_id',$Data->enroll_id)->where('periode_tahun_payroll',$Data->periode_tahun_payroll)->where('periode_bulan_payroll',$Data->periode_bulan_payroll)->where('periode_umk','2025-01')->count();
        if($januari==1){
            $insentif_jabatan_mandiri=DataKoreksiUpah::where('enroll_id',$Data->enroll_id)->where('tanggal_koreksi','>=','2024-12-26')->where('tanggal_koreksi', '<=','2025-01-25')->where('jenis_koreksi',2)->get();
            $data_1 =  RekapPerhitunganPayroll::select('upah_bruto_rupiah')->where('enroll_id',$Data->enroll_id)->where('periode_tahun_payroll',$Data->periode_tahun_payroll)->where('periode_bulan_payroll',$Data->periode_bulan_payroll)->where('periode_umk','2025-01')->pluck('upah_bruto_rupiah')[0];
            if($Data->sub_dept_id == 'DEP08SUB005' && $Data->jenis_kelamin == 'LAKI-LAKI' && $Data->enroll_id != '7445'){
                $bruto_januari=$data_1 + $insentif_jabatan_mandiri->sum('jumlah_rp_potongan')??0;
            }else{
                $bruto_januari=$data_1;
            }
        }else{
            $bruto_januari=0;
        }
        $total_bruto=$bruto_desember+$bruto_januari;

        $potongan_dtpc_rupiah=$Data->potongan_dtpc_rupiah;
        $rp_pot_jam=$potongan_iks_rupiah+$potongan_dtpc_rupiah;

        $total_potongan= $total_bpjs_tk+$total_bpjs_ks+$iuran_serikat_rupiah+$iuran_koperasi;
        if($total_potongan==0){
            $total_potongan='0';
        }
        if($total_upah_thp_rupiah_pembulatan==0){$total_upah_thp_rupiah_pembulatan='0';}
        if($total_upah_thp_rupiah_employee==0){$total_upah_thp_rupiah_employee='0';}
        if($total_upah_thp_rupiah_pecahan==0){$total_upah_thp_rupiah_pecahan='0';}
        if($pembulatan==0){$pembulatan='0';}
        if($years==0){$years='0';}
        if($months==0){$months='0';}
        if($days==0){$days='0';}
        if($rp_pot_jam==0){$rp_pot_jam='0';}
        if($pph21==0){$pph21='0';}
        if($pot_hari_kerja==0){$pot_hari_kerja='0';}
        $gapok=$upah_per_bulan+$tunjangan_karyawan_rupiah;

        if( $total_kehadiran_net<=0){
            $tunjangan_karyawan_rupiah=0;
            $gapok=$upah_per_bulan;
            $upah_neto_rupiah=0;
            $upah_bruto_rupiah=0;
            $pembulatan='0';
            $total_upah_thp_rupiah_pembulatan='0';
            $total_upah_thp_rupiah_employee='0';
            $total_upah_thp_rupiah_pecahan='0';
        }

        return [
            $kosong,
            $enroll_id,
            $nik,
            $employee_name,
            $department_id,
            $nama_department,
            $sub_dept_id,
            $nama_bagian,
            Date::stringToExcel( $join_date),
            // $join_date,
            $years,
            $months,
            $days,
            $kategori_karyawan,
            $aktif_karyawan,
            $nama_bank,
            $nomor_rekening_bank,
            $jenis_kelamin,
            // $ptkp,
            $st,
            $kosong,
            $kehadiran_iby,
            $kehadiran_itb,
            $kehadiran_m,
            $kehadiran_dt,
            $kehadiran_pc,
            $kehadiran_dtpc,
            $kehadiran_lby,
            $kehadiran_lsm,
            $kehadiran_r,
            $kehadiran_ok,
            $total_kehadiran_net,
            $pot_hari_kerja,
            $total_absen,
            $bruto_desember,
            $bruto_januari,
            $total_bruto,
            $pph21,
            $total_bruto-$pph21,
            $total_bpjs_tk,
            $total_bpjs_ks,
            $iuran_serikat_rupiah,
            $iuran_koperasi,
            $total_potongan,
            $pembulatan,
            $total_upah_thp_rupiah_pembulatan,

        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'P' => NumberFormat::FORMAT_TEXT,
            'AG' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'AH' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'AI' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'AJ' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'AK' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'AL' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'AM' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'AN' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'AO' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'AP' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'AQ' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'AR' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
        ];
    }

    public function title(): string
    {
        return 'REKAPPERHITUNGANPAYROLL';
    }
    public function registerEvents(): array
    {
        $counter = 1;

        return [
            AfterSheet::class => function (AfterSheet $event) use (&$counter) {
                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18);
                $sheet->setCellValue('A2', 'Rekap Perhitungan Payroll Karyawan');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(16);

                if($this->periode_umk){
                    if($this->periode_umk=='2024-01'){
                        $tanggal='26 - 31 desember 2024';
                    }else if($this->periode_umk=='2025-01'){
                        $tanggal='01 - 25 januari 2025';
                    }
                }else{
                    setlocale(LC_ALL, 'id-ID', 'id_ID');
                    $datePeriode = explode("-", $this->periode_payroll);
                    $tanggal = strtoupper(date("F", mktime(0, 0, 0, $datePeriode[1], 10))) . ' ' . $datePeriode[0];
                }
                $sheet->setCellValue('A3', 'Periode  : ' . $tanggal);
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');


                $highestRow = $event->sheet->getHighestRow();

                $event->sheet->getStyle('A7:A' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('0');

                for ($row = 7; $row <= $highestRow; $row++) {
                    $event->sheet->setCellValue('A' . $row, $counter);
                    $counter++;
                }

                $sheet->mergeCells('A5:A6');
                $sheet->setCellValue('A5', 'NO');

                $sheet->mergeCells('B5:B6');
                $sheet->setCellValue('B5', 'ID');

                $sheet->mergeCells('C5:C6');
                $sheet->setCellValue('C5', 'NIP');

                $sheet->mergeCells('D5:D6');
                $sheet->setCellValue('D5', 'Nama Karyawan');

                $sheet->mergeCells('E5:E6');
                $sheet->setCellValue('E5', 'ID Department');

                $sheet->mergeCells('F5:F6');
                $sheet->setCellValue('F5', 'Department');

                $sheet->mergeCells('G5:G6');
                $sheet->setCellValue('G5', 'ID Bagian');

                $sheet->mergeCells('H5:H6');
                $sheet->setCellValue('H5', 'Bagian');

                $sheet->mergeCells('I5:I6');
                $sheet->setCellValue('I5', 'Join Date');


                $sheet->mergeCells('J5:L5');
                $sheet->setCellValue('J5', 'Masa Kerja');
                $sheet->setCellValue('J6', 'T');
                $sheet->setCellValue('K6', 'B');
                $sheet->setCellValue('L6', 'H');

                $sheet->mergeCells('M5:M6');
                $sheet->setCellValue('M5', 'Staff /non Staff');

                $sheet->mergeCells('N5:N6');
                $sheet->setCellValue('N5', 'Aktif / Tidak Aktif');

                $sheet->mergeCells('O5:O6');
                $sheet->setCellValue('O5', 'Bank');

                $sheet->mergeCells('P5:P6');
                $sheet->setCellValue('P5', 'Rekening');

                $sheet->mergeCells('Q5:Q6');
                $sheet->setCellValue('Q5', 'JK');

                $sheet->mergeCells('R5:R6');
                $sheet->setCellValue('R5', 'ST');

                //kosong

                $sheet->mergeCells('S5:S6');
                $sheet->setCellValue('S5', '');

                $sheet->mergeCells('T5:T6');
                $sheet->setCellValue('T5', 'IBY');

                $sheet->mergeCells('U5:U6');
                $sheet->setCellValue('U5', 'ITB');

                $sheet->mergeCells('V5:V6');
                $sheet->setCellValue('V5', 'M');

                $sheet->mergeCells('W5:W6');
                $sheet->setCellValue('W5', 'DT');

                $sheet->mergeCells('X5:X6');
                $sheet->setCellValue('X5', 'PC');

                $sheet->mergeCells('Y5:Y6');
                $sheet->setCellValue('Y5', 'DTPC');

                $sheet->mergeCells('Z5:Z6');
                $sheet->setCellValue('Z5', 'LBY');

                $sheet->mergeCells('AA5:AA6');
                $sheet->setCellValue('AA5', 'LSM');

                $sheet->mergeCells('AB5:AB6');
                $sheet->setCellValue('AB5', 'R');

                $sheet->mergeCells('AC5:AC6');
                $sheet->setCellValue('AC5', 'OK');

                $sheet->mergeCells('AD5:AD6');
                $sheet->setCellValue('AD5', 'Hari Kerja');

                $sheet->mergeCells('AE5:AE6');
                $sheet->setCellValue('AE5', 'Pot. Hari Kerja');

                $sheet->mergeCells('AF5:AF6');
                $sheet->setCellValue('AF5', 'Total Absensi');

                $sheet->mergeCells('AG5:AG6');
                $sheet->setCellValue('AG5', 'Bruto Desember');

                $sheet->mergeCells('AH5:AH6');
                $sheet->setCellValue('AH5', 'Bruto Januari');

                $sheet->mergeCells('AI5:AI6');
                $sheet->setCellValue('AI5', 'Total Bruto');

                $sheet->mergeCells('AJ5:AJ6');
                $sheet->setCellValue('AJ5', 'PPH');

                $sheet->mergeCells('AK5:AK6');
                $sheet->setCellValue('AK5', 'Netto');

                $sheet->mergeCells('AL5:AL6');
                $sheet->setCellValue('AL5', 'Bpjamsostek');

                $sheet->mergeCells('AM5:AM6');
                $sheet->setCellValue('AM5', 'BPJS Kesehatan');

                $sheet->mergeCells('AN5:AN6');
                $sheet->setCellValue('AN5', 'Serikat');

                $sheet->mergeCells('AO5:AO6');
                $sheet->setCellValue('AO5', 'Koperasi');

                $sheet->mergeCells('AP5:AP6');
                $sheet->setCellValue('AP5', 'Total Potongan');

                $sheet->mergeCells('AQ5:AQ6');
                $sheet->setCellValue('AQ5', 'Pembulatan');

                $sheet->mergeCells('AR5:AR6');
                $sheet->setCellValue('AR5', 'Jumlah');

            },
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'PT NAG - HRIS',
            'lastModifiedBy' => 'HRIS',
            'title'          => 'Rekap Perhitungan Payroll Karyawan',
            'description'    => 'Rekap Perhitungan Payroll Karyawan',
            'subject'        => 'Rekap Perhitungan Payroll Karyawan',
            'keywords'       => 'perhitungan,payroll,hr,hris,hrm,daily,report,karyawan,data',
            'category'       => 'RekapPerhitunganPayrollKaryawan',
            'manager'        => 'HRIS',
            'company'        => 'PT Nirwana Alabare Garment',
        ];
    }
}
