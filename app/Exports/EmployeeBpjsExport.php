<?php

namespace App\Exports;

use App\Models\EmployeeBpjs;
use App\Http\Controllers\Hris\EmployeeBpjsController;

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

class EmployeeBpjsExport implements FromQuery, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle
{
    use Exportable;

    public function exportParams(string $periode_payroll, string $status_staff, string $searchData)
    {
        $this->periode_payroll = $periode_payroll;
        $this->status_staff = $status_staff;
        $this->searchData = $searchData;

        return $this;
    }

    public function query()
    {
        $explodeKode = explode(" s/d ", $this->periode_payroll);
        $kodeperiode = explode("-", $explodeKode[1]);
        $periode_kehadiran = $kodeperiode[0] . "-" . $kodeperiode[1];

        $status_staff = $this->status_staff;
        if($status_staff) {
            $whereStatusStaff = ' AND status_staff = "' . $status_staff . '" ';
        } else {
            $whereStatusStaff = '';
        }

        $searchData = $this->searchData;
        if($searchData) {
            $whereSearchData = ' AND (upper( employee_atribut.enroll_id ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_atribut.employee_name ) LIKE upper( "%' . $searchData . '%" ) OR upper( department_all.sub_dept_name ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_atribut.status_aktif ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_bpjs.status_aktif_bpjs_tk ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_bpjs.status_aktif_bpjs_ks ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_bpjs.nomor_bpjs_ketenagakerjaan ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_bpjs.nomor_bpjs_kesehatan ) LIKE upper( "%' . $searchData . '%" ) ) ';
        } else {
            $whereSearchData = '';
        }

        $query = EmployeeBpjs::selectRaw('
                uuid, 
                employee_bpjs.kode_bpjs, 
                concat(substr(employee_bpjs.kode_bpjs, 1, 4), "-", substr(employee_bpjs.kode_bpjs, 5, 2)) bpjs_kehadiran, 
                employee_atribut.enroll_id, 
                employee_atribut.nik, 
                employee_atribut.employee_name, 
                department_all.site_nirwana_id, 
                department_all.site_nirwana_name, 
                department_all.department_id, 
                department_all.department_name,
                department_all.sub_dept_id, 
                department_all.sub_dept_name, 
                employee_atribut.join_date, 
                employee_atribut.tanggal_resign,
                employee_atribut.status_aktif_bpjs_tk, 
                employee_atribut.tanggal_bpjs_ketenagakerjaan, 
                employee_atribut.nomor_bpjs_ketenagakerjaan, 
                employee_atribut.status_aktif_bpjs_ks, 
                employee_atribut.tanggal_bpjs_kesehatan, 
                employee_atribut.nomor_bpjs_kesehatan,
                employee_atribut.status_aktif, 
                employee_atribut.status_staff, 
                employee_bpjs.dasar_pot_bpjs_rupiah dasar_pot_bpjs_rupiah,
                
                employee_bpjs.bpjs_tk_jht_bruto_rupiah + employee_bpjs.bpjs_tk_jht_neto_rupiah bpjs_tk_jht_rupiah,
                employee_bpjs.bpjs_tk_jpn_bruto_rupiah + employee_bpjs.bpjs_tk_jpn_neto_rupiah bpjs_tk_jpn_rupiah,
                employee_bpjs.bpjs_ks_jkn_bruto_rupiah + employee_bpjs.bpjs_ks_jkn_neto_rupiah bpjs_ks_jkn_rupiah,
                employee_bpjs.bpjs_tk_jkk_bruto_rupiah + employee_bpjs.bpjs_tk_jkm_bruto_rupiah + employee_bpjs.bpjs_tk_jht_bruto_rupiah + employee_bpjs.bpjs_tk_jht_neto_rupiah + employee_bpjs.bpjs_tk_jpn_bruto_rupiah + employee_bpjs.bpjs_tk_jpn_neto_rupiah + employee_bpjs.bpjs_ks_jkn_bruto_rupiah + employee_bpjs.bpjs_ks_jkn_neto_rupiah total_iuran,

                employee_bpjs.bpjs_tk_jkm_persen, 
                employee_bpjs.bpjs_tk_jkm_bruto_persen bpjs_tk_jkm_perusahaan_persen, 
                employee_bpjs.bpjs_tk_jkm_neto_persen bpjs_tk_jkm_karyawan_persen,
                employee_bpjs.bpjs_tk_jkm_bruto_rupiah + employee_bpjs.bpjs_tk_jkm_neto_rupiah bpjs_tk_jkm_rupiah, 
                employee_bpjs.bpjs_tk_jkm_bruto_rupiah bpjs_tk_jkm_perusahaan_rupiah, 
                employee_bpjs.bpjs_tk_jkm_neto_rupiah bpjs_tk_jkm_karyawan_rupiah,
                
                employee_bpjs.bpjs_tk_jkk_persen, 
                employee_bpjs.bpjs_tk_jkk_bruto_persen bpjs_tk_jkk_perusahaan_persen, 
                employee_bpjs.bpjs_tk_jkk_neto_persen bpjs_tk_jkk_karyawan_persen,
                employee_bpjs.bpjs_tk_jkk_bruto_rupiah + employee_bpjs.bpjs_tk_jkk_neto_rupiah bpjs_tk_jkk_rupiah, 
                employee_bpjs.bpjs_tk_jkk_bruto_rupiah bpjs_tk_jkk_perusahaan_rupiah, 
                employee_bpjs.bpjs_tk_jkk_neto_rupiah bpjs_tk_jkk_karyawan_rupiah,
                
                employee_bpjs.bpjs_tk_jht_persen, 
                employee_bpjs.bpjs_tk_jht_bruto_persen bpjs_tk_jht_perusahaan_persen, 
                employee_bpjs.bpjs_tk_jht_neto_persen bpjs_tk_jht_karyawan_persen,
                employee_bpjs.bpjs_tk_jht_bruto_rupiah bpjs_tk_jht_perusahaan_rupiah, 
                employee_bpjs.bpjs_tk_jht_neto_rupiah bpjs_tk_jht_karyawan_rupiah,

                employee_bpjs.bpjs_tk_jpn_persen,
                employee_bpjs.bpjs_tk_jpn_bruto_persen bpjs_tk_jpn_perusahaan_persen, 
                employee_bpjs.bpjs_tk_jpn_neto_persen bpjs_tk_jpn_karyawan_persen,
                employee_bpjs.bpjs_tk_jpn_bruto_rupiah bpjs_tk_jpn_perusahaan_rupiah, 
                employee_bpjs.bpjs_tk_jpn_neto_rupiah bpjs_tk_jpn_karyawan_rupiah,

                employee_bpjs.bpjs_ks_jkn_persen,
                employee_bpjs.bpjs_ks_jkn_bruto_persen bpjs_ks_jkn_perusahaan_persen, 
                employee_bpjs.bpjs_ks_jkn_neto_persen bpjs_ks_jkn_karyawan_persen,
                employee_bpjs.bpjs_ks_jkn_bruto_rupiah bpjs_ks_jkn_perusahaan_rupiah, 
                employee_bpjs.bpjs_ks_jkn_neto_rupiah bpjs_ks_jkn_karyawan_rupiah,

                employee_bpjs.operator, substr(employee_bpjs.created_at, 1, 19) created_at,
                substr(employee_bpjs.updated_at, 1, 19) updated_at, 
                substr(employee_bpjs.deleted_at, 1, 19) deleted_at
            ')
            ->whereRaw('
                concat(substr(employee_bpjs.kode_bpjs, 1, 4), "-", substr(employee_bpjs.kode_bpjs, 5, 2)) = "' . $periode_kehadiran . '"
                ' . $whereStatusStaff . '
                ' . $whereSearchData . '
            ')
            ->join('employee_atribut','employee_bpjs.enroll_id','=','employee_atribut.enroll_id')
            ->join('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
            ->orderBy('employee_atribut.employee_name','asc')
            ->limit(1);

        return $query;
    }

    public function startCell(): string
    {
        return 'A8';
    }

    public function map($Data): array
    {
        $enroll_id = $Data->enroll_id;
        $nik = $Data->nik;
        $employee_name = $Data->employee_name;
        if($Data->dasar_pot_bpjs_rupiah == 0){ $dasar_pot_bpjs_rupiah = '0';} else { $dasar_pot_bpjs_rupiah = $Data->dasar_pot_bpjs_rupiah; }
        $tunjangan = "0";
        if($Data->bpjs_tk_jkk_rupiah == 0){ $bpjs_tk_jkk_rupiah = '0';} else { $bpjs_tk_jkk_rupiah = $Data->bpjs_tk_jkk_rupiah; }
        if($Data->bpjs_tk_jht_rupiah == 0){ $bpjs_tk_jht_rupiah = '0';} else { $bpjs_tk_jht_rupiah = $Data->bpjs_tk_jht_rupiah; }
        if($Data->bpjs_tk_jkm_rupiah == 0){ $bpjs_tk_jkm_rupiah = '0';} else { $bpjs_tk_jkm_rupiah = $Data->bpjs_tk_jkm_rupiah; }
        if($Data->bpjs_tk_jpn_rupiah == 0){ $bpjs_tk_jpn_rupiah = '0';} else { $bpjs_tk_jpn_rupiah = $Data->bpjs_tk_jpn_rupiah; }
        if($Data->bpjs_ks_jkn_rupiah == 0){ $bpjs_ks_jkn_rupiah = '0';} else { $bpjs_ks_jkn_rupiah = $Data->bpjs_ks_jkn_rupiah; }
        if($Data->total_iuran == 0){ $total_iuran = '0';} else { $total_iuran = $Data->total_iuran; }
        if($Data->bpjs_tk_jkk_perusahaan_rupiah == 0){ $bpjs_tk_jkk_perusahaan_rupiah = '0';} else { $bpjs_tk_jkk_perusahaan_rupiah = $Data->bpjs_tk_jkk_perusahaan_rupiah; }
        if($Data->bpjs_tk_jkk_karyawan_rupiah == 0){ $bpjs_tk_jkk_karyawan_rupiah = '0';} else { $bpjs_tk_jkk_karyawan_rupiah = $Data->bpjs_tk_jkk_karyawan_rupiah; }
        if($Data->bpjs_tk_jht_perusahaan_rupiah == 0){ $bpjs_tk_jht_perusahaan_rupiah = '0';} else { $bpjs_tk_jht_perusahaan_rupiah = $Data->bpjs_tk_jht_perusahaan_rupiah; }
        if($Data->bpjs_tk_jht_karyawan_rupiah == 0){ $bpjs_tk_jht_karyawan_rupiah = '0';} else { $bpjs_tk_jht_karyawan_rupiah = $Data->bpjs_tk_jht_karyawan_rupiah; }
        if($Data->bpjs_tk_jkm_perusahaan_rupiah == 0){ $bpjs_tk_jkm_perusahaan_rupiah = '0';} else { $bpjs_tk_jkm_perusahaan_rupiah = $Data->bpjs_tk_jkm_perusahaan_rupiah; }
        if($Data->bpjs_tk_jkm_karyawan_rupiah == 0){ $bpjs_tk_jkm_karyawan_rupiah = '0';} else { $bpjs_tk_jkm_karyawan_rupiah = $Data->bpjs_tk_jkm_karyawan_rupiah; }
        if($Data->bpjs_tk_jpn_perusahaan_rupiah == 0){ $bpjs_tk_jpn_perusahaan_rupiah = '0';} else { $bpjs_tk_jpn_perusahaan_rupiah = $Data->bpjs_tk_jpn_perusahaan_rupiah; }
        if($Data->bpjs_tk_jpn_karyawan_rupiah == 0){ $bpjs_tk_jpn_karyawan_rupiah = '0';} else { $bpjs_tk_jpn_karyawan_rupiah = $Data->bpjs_tk_jpn_karyawan_rupiah; }
        if($Data->bpjs_ks_jkn_perusahaan_rupiah == 0){ $bpjs_ks_jkn_perusahaan_rupiah = '0';} else { $bpjs_ks_jkn_perusahaan_rupiah = $Data->bpjs_ks_jkn_perusahaan_rupiah; }
        if($Data->bpjs_ks_jkn_karyawan_rupiah == 0){ $bpjs_ks_jkn_karyawan_rupiah = '0';} else { $bpjs_ks_jkn_karyawan_rupiah = $Data->bpjs_ks_jkn_karyawan_rupiah; }

        return [
            $enroll_id,
            $nik,
            $employee_name,
            $dasar_pot_bpjs_rupiah,
            $tunjangan,
            $bpjs_tk_jkk_rupiah,
            $bpjs_tk_jht_rupiah,
            $bpjs_tk_jkm_rupiah,
            $bpjs_tk_jpn_rupiah,
            $bpjs_ks_jkn_rupiah,
            $total_iuran,
            $bpjs_tk_jkk_perusahaan_rupiah,
            $bpjs_tk_jkk_karyawan_rupiah,
            $bpjs_tk_jht_perusahaan_rupiah,
            $bpjs_tk_jht_karyawan_rupiah,
            $bpjs_tk_jkm_perusahaan_rupiah,
            $bpjs_tk_jkm_karyawan_rupiah,
            $bpjs_tk_jpn_perusahaan_rupiah,
            $bpjs_tk_jpn_karyawan_rupiah,
            $bpjs_ks_jkn_perusahaan_rupiah,
            $bpjs_ks_jkn_karyawan_rupiah,
        ];
    }

    public function title(): string
    {
        return $this->periode_payroll;
    }

    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18);
                $sheet->setCellValue('A2', 'DATA BPJS KARYAWAN');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(16);
                $sheet->setCellValue('A3', 'TANGGAL : ' . $this->periode_payroll);
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->setCellValue('A4', 'STAFF/NONSTAFF : ' . $this->status_staff);
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');
                $sheet->mergeCells('A4:D4');

                $sheet->mergeCells('A6:A7');
                $sheet->setCellValue('A6', 'NO. ABSEN');
                $sheet->mergeCells('B6:B7');
                $sheet->setCellValue('B6', 'NIK');
                $sheet->mergeCells('C6:C7');
                $sheet->setCellValue('C6', 'NAMA PEGAWAI');
                $sheet->mergeCells('D6:D7');
                $sheet->setCellValue('D6', 'GAJI POKOK (RP)');
                $sheet->mergeCells('E6:E7');
                $sheet->setCellValue('E6', 'TUNJANGAN TETAP (RP)');

                $sheet->mergeCells('F6:J6');
                $sheet->setCellValue('F6', 'BPJS');
                $sheet->setCellValue('F7', 'JKK 0.24%');
                $sheet->setCellValue('G7', 'JHT 5.70%');
                $sheet->setCellValue('H7', 'JKM 0.30%');
                $sheet->setCellValue('I7', 'JPN 3%');
                $sheet->setCellValue('J7', 'JKN 5%');

                $sheet->mergeCells('K6:K7');
                $sheet->setCellValue('K6', 'TOTAL IURAN');

                $sheet->mergeCells('L6:M6');
                $sheet->setCellValue('L6', 'BPJS-TK JKK 0.24%');
                $sheet->setCellValue('L7', 'PERUSAHAAN 0.24%');
                $sheet->setCellValue('M7', 'KARYAWAN 0%');

                $sheet->mergeCells('N6:O6');
                $sheet->setCellValue('N6', 'BPJS-TK JHT 5.70%');
                $sheet->setCellValue('N7', 'PERUSAHAAN 3.7%');
                $sheet->setCellValue('O7', 'KARYAWAN 2%');

                $sheet->mergeCells('P6:Q6');
                $sheet->setCellValue('P6', 'BPJS-TK JKM 0.30%');
                $sheet->setCellValue('P7', 'PERUSAHAAN 0.30%');
                $sheet->setCellValue('Q7', 'KARYAWAN 0%');

                $sheet->mergeCells('R6:S6');
                $sheet->setCellValue('R6', 'BPJS-TK JPN 3%');
                $sheet->setCellValue('R7', 'PERUSAHAAN 2%');
                $sheet->setCellValue('S7', 'KARYAWAN 1%');

                $sheet->mergeCells('T6:U6');
                $sheet->setCellValue('T6', 'BPJS-KS JKN 5%');
                $sheet->setCellValue('T7', 'PERUSAHAAN 4%');
                $sheet->setCellValue('U7', 'KARYAWAN 1%');

            },
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'PT NAG - HRIS',
            'lastModifiedBy' => 'HRIS',
            'title'          => 'DATA BPJS KARYAWAN',
            'description'    => 'DATA BPJS KARYAWAN',
            'subject'        => 'DATA BPJS KARYAWAN',
            'keywords'       => 'bpjs,hr,hris,hrm,daily,report,karyawan,data',
            'category'       => 'BpjsKaryawan',
            'manager'        => 'HRIS',
            'company'        => 'PT Nirwana Alabare Garment',
        ];
    }
}
