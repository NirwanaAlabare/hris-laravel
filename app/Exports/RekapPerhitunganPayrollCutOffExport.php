<?php

namespace App\Exports;

use App\Models\RekapPerhitunganPayrollCutOff;
use App\Http\Controllers\Hris\RekapPayrollCutOffController;

use App\User;
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

class RekapPerhitunganPayrollCutOffExport implements FromQuery, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle
{
    use Exportable;

    public function exportParams(string $periode_payroll)
    {
        $this->periode_payroll = $periode_payroll;

        return $this;
    }

    public function query()
    {
        $q =  RekapPerhitunganPayrollCutOff::query()
                ->selectRaw('
                    kode_rekap_payroll,
                    periode_kehadiran,
                    CONCAT(periode_tahun_payroll,"-",periode_bulan_payroll) periode_payroll,
                    periode_tahun_payroll,
                    periode_bulan_payroll,
                    enroll_id,
                    nik,
                    employee_name,
                    tanggal_resign,
                    kehadiran_iby,
                    kehadiran_itb,
                    kehadiran_m,
                    kehadiran_dt,
                    kehadiran_pc,
                    kehadiran_dtpc,
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
                    nomor_rekening_bank,
                    npwp,
                    operator,
                    created_at,
                    updated_at
                ')
                ->whereRaw('
                CONCAT(periode_tahun_payroll,"-",periode_bulan_payroll) = "' . $this->periode_payroll . '"                
                ')
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
        if($Data->upah_per_bulan == 0){ $upah_per_bulan = '0';} else { $upah_per_bulan = $Data->upah_per_bulan; }
        if($Data->upah_per_hari == 0){ $upah_per_hari = '0';} else { $upah_per_hari = $Data->upah_per_hari; }
        if($Data->tunjangan_karyawan_rupiah == 0){ $tunjangan_karyawan_rupiah = '0';} else { $tunjangan_karyawan_rupiah = $Data->tunjangan_karyawan_rupiah; }
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
        $nama_department = $Data->nama_department;
        $kategori_karyawan = $Data->kategori_karyawan;
        $aktif_karyawan = $Data->aktif_karyawan;
        $jenis_kelamin = $Data->jenis_kelamin;
        if($Data->nama_bank == 0){ $nama_bank = '-';} else { $nama_bank = $Data->nama_bank; }
        if($Data->nomor_rekening_bank == 0){ $nomor_rekening_bank = '-';} else { $nomor_rekening_bank = $Data->nomor_rekening_bank; }
        if($Data->npwp == 0){ $npwp = '-';} else { $npwp = $Data->npwp; }

        return [
            $enroll_id,
            $nik,
            $employee_name,
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
            $kehadiran_tk,
            $total_kehadiran,
            $total_kehadiran_net,
            $ptkp,
            $upah_per_hari,
            $upah_per_bulan,
            $tunjangan_karyawan_rupiah,
            $premi_karyawan,
            $lembur_1,
            $lembur_2,
            $lembur_3,
            $lembur_4,
            $total_lembur_1234,
            $lembur1_rupiah,
            $lembur2_rupiah,
            $lembur3_rupiah,
            $lembur4_rupiah,
            $total_lembur_rupiah,
            $pendapatan_lainnya_rupiah,
            $koreksi_upah_rupiah,
            $koreksi_potongan_rupiah,
            $potongan_iks_menit,
            $potongan_dt_menit,
            $potongan_pc_menit,
            $potongan_iks_rupiah,
            $potongan_dt_rupiah,
            $potongan_pc_rupiah,
            $total_potongan_jam_rupiah,
            $potongan_kehadiran_rupiah,
            $upah_bruto_rupiah,
            $pph21,
            $upah_neto_rupiah,
            $total_bpjs_tk,
            $total_bpjs_ks,
            $iuran_serikat_rupiah,
            $iuran_koperasi,
            $jumlah_potongan_rupiah,
            $upah_bersih_rupiah,
            $potongan_kasbon_rupiah,
            $total_upah_thp_rupiah,
            $bpjs_tk_jkm_perusahaan_rupiah,
            $bpjs_tk_jkm_karyawan_rupiah,
            $bpjs_tk_jkm_rupiah,
            $bpjs_tk_jkk_perusahaan_rupiah,
            $bpjs_tk_jkk_karyawan_rupiah,
            $bpjs_tk_jkk_rupiah,
            $bpjs_tk_jht_perusahaan_rupiah,
            $bpjs_tk_jht_karyawan_rupiah,
            $bpjs_tk_jht_rupiah,
            $bpjs_tk_jpn_perusahaan_rupiah,
            $bpjs_tk_jpn_karyawan_rupiah,
            $bpjs_tk_jpn_rupiah,
            $bpjs_ks_jkn_perusahaan_rupiah,
            $bpjs_ks_jkn_karyawan_rupiah,
            $bpjs_ks_jkn_rupiah,
            $jabatan_karyawan,
            $nama_bagian,
            $nama_department,
            $kategori_karyawan,
            $aktif_karyawan,
            $jenis_kelamin,
            $nama_bank,
            $nomor_rekening_bank,
            $npwp
        ];
    }

    public function title(): string
    {
        return 'REKAPPERHITUNGANPAYROLL';
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => '0.00',
            'F' => '0.00',
            'G' => '0.00'
        ];
    }

    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18);
                $sheet->setCellValue('A2', 'Rekap Perhitungan Payroll Karyawan');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(16);
                
                setlocale(LC_ALL, 'id-ID', 'id_ID');
                $datePeriode = explode("-", $this->periode_payroll);
                $tanggal = strtoupper(date("F", mktime(0, 0, 0, $datePeriode[1], 10))) . ' ' . $datePeriode[0];

                $sheet->setCellValue('A3', 'Tanggal  : ' . $tanggal);
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');
    
                $sheet->mergeCells('A5:A6');
                $sheet->setCellValue('A5', 'NO. ABSEN');
                $sheet->mergeCells('B5:B6');
                $sheet->setCellValue('B5', 'NIK');
                $sheet->mergeCells('C5:C6');
                $sheet->setCellValue('C5', 'NAMA KARYAWAN');

                $sheet->mergeCells('D5:P5');
                $sheet->setCellValue('D5', 'KEHADIRAN');
                $sheet->setCellValue('D6', 'IBY');
                $sheet->setCellValue('E6', 'ITB');
                $sheet->setCellValue('F6', 'M');
                $sheet->setCellValue('G6', 'DT');
                $sheet->setCellValue('H6', 'PC');
                $sheet->setCellValue('I6', 'DTPC');
                $sheet->setCellValue('J6', 'LBY');
                $sheet->setCellValue('K6', 'LSM');
                $sheet->setCellValue('L6', 'R');
                $sheet->setCellValue('M6', 'OK');
                $sheet->setCellValue('N6', 'TK');
                $sheet->setCellValue('O6', 'TOTAL');
                $sheet->setCellValue('P6', 'HK');

                $sheet->mergeCells('Q5:Q6');
                $sheet->setCellValue('Q5', 'PTKP');
                $sheet->setCellValue('R5', 'UPAH/HARIAN (RP)');
                $sheet->setCellValue('R6', '(RP)');
                $sheet->setCellValue('S5', 'GAJI POKOK');
                $sheet->setCellValue('S6', '(RP)');
                $sheet->setCellValue('T5', 'TUNJANGAN');
                $sheet->setCellValue('T6', '(RP)');
                $sheet->setCellValue('U5', 'PREMI');
                $sheet->setCellValue('U6', '(RP)');

                $sheet->mergeCells('V5:Z5');
                $sheet->setCellValue('V5', 'JAM LEMBUR');
                $sheet->setCellValue('V6', 'L1');
                $sheet->setCellValue('W6', 'L2');
                $sheet->setCellValue('X6', 'L3');
                $sheet->setCellValue('Y6', 'L4');
                $sheet->setCellValue('Z6', 'TOTAL L');

                $sheet->mergeCells('AA5:AE5');
                $sheet->setCellValue('AA5', 'LEMBUR (RP)');
                $sheet->setCellValue('AA6', 'L1');
                $sheet->setCellValue('AB6', 'L2');
                $sheet->setCellValue('AC6', 'L3');
                $sheet->setCellValue('AD6', 'L4');
                $sheet->setCellValue('AE6', 'TOTAL L');

                $sheet->setCellValue('AF5', 'PENDAPATAN LAIN');
                $sheet->setCellValue('AF6', '(RP)');

                $sheet->mergeCells('AG5:AH5');
                $sheet->setCellValue('AG5', 'KOREKSI');
                $sheet->setCellValue('AG6', 'UPAH');
                $sheet->setCellValue('AH6', 'POTONGAN');
                
                $sheet->mergeCells('AI5:AK5');
                $sheet->setCellValue('AI5', 'POTONGAN JAM KERJA');
                $sheet->setCellValue('AI6', 'IKS (MENIT)');
                $sheet->setCellValue('AJ6', 'DT (MENIT)');
                $sheet->setCellValue('AK6', 'PC (MENIT)');
                
                $sheet->mergeCells('AL5:AO5');
                $sheet->setCellValue('AL5', 'POTONGAN JAM KERJA');
                $sheet->setCellValue('AL6', 'IKS (RP)');
                $sheet->setCellValue('AM6', 'DT (RP)');
                $sheet->setCellValue('AN6', 'PC (RP)');
                $sheet->setCellValue('AO6', 'TOTAL POTONGAN JAM');

                $sheet->setCellValue('AP5', 'POTONGAN ABSENSI');
                $sheet->setCellValue('AP6', '(RP)');
                $sheet->setCellValue('AQ5', 'GAJI BRUTO');
                $sheet->setCellValue('AQ6', '(RP)');
                $sheet->setCellValue('AR5', 'PPH 21');
                $sheet->setCellValue('AR6', '(RP)');
                $sheet->setCellValue('AS5', 'GAJI NETTO');
                $sheet->setCellValue('AS6', '(RP)');

                $sheet->mergeCells('AT5:AX5');
                $sheet->setCellValue('AT5', 'POTONGAN (RP)');
                $sheet->setCellValue('AT6', 'BPJS TK');
                $sheet->setCellValue('AU6', 'BPJS KS');
                $sheet->setCellValue('AV6', 'SERIKAT');
                $sheet->setCellValue('AW6', 'KOPERASI');
                $sheet->setCellValue('AX6', 'JUMLAH');
                
                $sheet->setCellValue('AY5', 'GAJI BERSIH');
                $sheet->setCellValue('AY6', '(RP)');

                $sheet->setCellValue('AZ5', 'POTONGAN KASBON');
                $sheet->setCellValue('AZ6', '(RP)');

                $sheet->setCellValue('BA5', 'TOTAL THP');
                $sheet->setCellValue('BA6', '(RP)');

                $sheet->mergeCells('BB5:BM5');
                $sheet->setCellValue('BB5', 'RINCIAN IURAN BPJS KS (RP)');
                $sheet->setCellValue('BB6', 'JKM (PERUSAHAAN)');
                $sheet->setCellValue('BC6', 'JKM (KARYAWAN)');
                $sheet->setCellValue('BD6', 'TOTAL JKM');
                $sheet->setCellValue('BE6', 'JKK (KARYAWAN)');
                $sheet->setCellValue('BF6', 'JKK (PERUSAHAAN)');
                $sheet->setCellValue('BG6', 'TOTAL JKK');
                $sheet->setCellValue('BH6', 'JHT (PERUSAHAAN)');
                $sheet->setCellValue('BI6', 'JHT (KARYAWAN)');
                $sheet->setCellValue('BJ6', 'TOTAL JHT');
                $sheet->setCellValue('BK6', 'JPN (PERUSAHAAN)');
                $sheet->setCellValue('BL6', 'JPN (KARYAWAN)');
                $sheet->setCellValue('BM6', 'TOTAL JPN');

                $sheet->mergeCells('BN5:BP5');
                $sheet->setCellValue('BN5', 'RINCIAN IURAN BPJS KS (RP)');
                $sheet->setCellValue('BN6', 'JKN (PERUSAHAAN)');
                $sheet->setCellValue('BO6', 'JKN (KARYAWAN)');
                $sheet->setCellValue('BP6', 'TOTAL JKN');

                $sheet->mergeCells('BQ5:BQ6');
                $sheet->setCellValue('BQ5', 'JABATAN');
                $sheet->mergeCells('BR5:BR6');
                $sheet->setCellValue('BR5', 'BAGIAN');
                $sheet->mergeCells('BS5:BS6');
                $sheet->setCellValue('BS5', 'DEPARTMENT');
                $sheet->mergeCells('BT5:BT6');
                $sheet->setCellValue('BT5', 'KATEGORI');
                $sheet->mergeCells('BU5:BU6');
                $sheet->setCellValue('BU5', 'AKTIF');
                $sheet->mergeCells('BV5:BV6');
                $sheet->setCellValue('BV5', 'JENIS KELAMIN');
                $sheet->mergeCells('BW5:BW6');
                $sheet->setCellValue('BW5', 'METODE GAJI');
                $sheet->mergeCells('BX5:BX6');
                $sheet->setCellValue('BX5', 'NOMOR REK. BANK');
                $sheet->mergeCells('BY5:BY6');
                $sheet->setCellValue('BY5', 'NPWP');
            },
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'PT NAG - HRIS',
            'lastModifiedBy' => 'HRIS',
            'title'          => 'Rekap Perhitungan Payroll CUTOFF Karyawan',
            'description'    => 'Rekap Perhitungan Payroll CUTOFF Karyawan',
            'subject'        => 'Rekap Perhitungan Payroll CUTOFF Karyawan',
            'keywords'       => 'perhitungan,payroll,hr,hris,hrm,daily,report,karyawan,data',
            'category'       => 'RekapPerhitunganPayrollKaryawanCutOff',
            'manager'        => 'HRIS',
            'company'        => 'PT Nirwana Alabare Garment',
        ];
    }
}
