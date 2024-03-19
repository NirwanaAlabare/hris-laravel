<?php

namespace App\Exports;

use App\Models\MasterDataAbsenKehadiran;
use App\Http\Controllers\Hris\DepartmentAllController;

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
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Illuminate\Support\Facades\DB;

use Auth;

class DataLemburExport implements FromView, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle
{
    use Exportable;

    public function exportParams(string $periode_lembur)
    {
        $this->periode_lembur = $periode_lembur;
        $array_periode_lembur = explode(' s/d ', $periode_lembur);
        $this->awal_bulan = substr($array_periode_lembur[0], 0, 10);
        $this->akhir_bulan = substr($array_periode_lembur[1], 0, 10);

        return $this;
    }



    public function view() : View
    {
        $dateRange = 'and master_data_absen_kehadiran.tanggal_berjalan between "' . $this->awal_bulan . '" and "' . $this->akhir_bulan . '"';

        $dataLembur = MasterDataAbsenKehadiran::query()
            ->selectRaw('
            master_data_absen_kehadiran.tanggal_berjalan, 
            master_data_absen_kehadiran.tanggal_absen, 
            master_data_absen_kehadiran.kode_hari,
            master_data_absen_kehadiran.holiday_name,
            substr(master_data_absen_kehadiran.absen_masuk_kerja, 1, 5) absen_masuk_kerja,
            substr(master_data_absen_kehadiran.absen_pulang_kerja, 1, 5) absen_pulang_kerja,
            master_data_absen_kehadiran.nama_hari,
            master_data_absen_kehadiran.nomor_form_lembur,
            employee_atribut.nik,
            master_data_absen_kehadiran.enroll_id,
            employee_atribut.employee_name,
            employee_atribut.status_staff,
            department_all.department_name,
            department_all.sub_dept_name,
            substr(master_data_absen_kehadiran.mulai_jam_kerja, 1, 5) mulai_jam_kerja,
            substr(master_data_absen_kehadiran.akhir_jam_kerja, 1, 5) akhir_jam_kerja,
            master_data_absen_kehadiran.mulai_jam_lembur,
            master_data_absen_kehadiran.akhir_jam_lembur,
            substr(master_data_absen_kehadiran.jumlah_jam_lembur_approved, 1, 5) jumlah_jam_lembur_approved,
            master_data_absen_kehadiran.catatan_hrd,
            rekap_perhitungan_lembur.lembur_1,
            rekap_perhitungan_lembur.lembur_2,
            rekap_perhitungan_lembur.lembur_3,
            rekap_perhitungan_lembur.lembur_4,
            rekap_perhitungan_lembur.total_lembur_1234,
            data_lembur.is_verifikasi
            ')
            ->whereRaw('
            data_lembur.is_verifikasi is not null 
            ' . $dateRange . '
        ')
        ->leftJoin('employee_atribut','employee_atribut.enroll_id','=','master_data_absen_kehadiran.enroll_id')
        ->leftJoin('department_all','department_all.sub_dept_id','=','employee_atribut.sub_dept_id')
        ->leftJoin('rekap_perhitungan_lembur',function($leftjoin){
            $leftjoin->on("master_data_absen_kehadiran.tanggal_berjalan","=","rekap_perhitungan_lembur.tanggal_berjalan")
                ->on("master_data_absen_kehadiran.enroll_id","=","rekap_perhitungan_lembur.enroll_id");
        })
        ->leftJoin('data_lembur',function($leftjoin){
            $leftjoin->on("master_data_absen_kehadiran.tanggal_berjalan","=","data_lembur.tanggal_berjalan")
                ->on("master_data_absen_kehadiran.enroll_id","=","data_lembur.enroll_id");
        })
        ->groupBy('master_data_absen_kehadiran.tanggal_berjalan')
        ->groupBy('employee_atribut.enroll_id')
        ->groupBy('master_data_absen_kehadiran.nomor_form_lembur')
        ->orderBy('master_data_absen_kehadiran.tanggal_berjalan','asc')
        ->orderBy('master_data_absen_kehadiran.nomor_form_lembur','asc')
        ->orderBy('employee_atribut.employee_name','asc')->get();

        return view("hris.Laporan.data_lembur_excel", ["dataLembur" => $dataLembur]);
    }

    public function startCell(): string
    {
        return 'A8';
    }


    public function title(): string
    {
        return $this->awal_bulan . ' s/d ' . $this->akhir_bulan;
    }

    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18);
                $sheet->setCellValue('A2', 'LAPORAN DATA LEMBUR KARYAWAN');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(16);

                setlocale(LC_ALL, 'id-ID', 'id_ID');
                $datePeriode = strtoupper(strftime("%d %b %Y", strtotime($this->awal_bulan)) . ' s/d ' . strftime("%d %b %Y", strtotime($this->akhir_bulan)));

                $sheet->setCellValue('A3', 'TANGGAL LEMBUR : ' . $datePeriode);
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);

                $sheet->setCellValue('A4', 'STAFF/NON STAFF : SEMUA KARYAWAN');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');
                $sheet->mergeCells('A4:D4');

                $sheet->setCellValue('A6', 'TANGGAL');
                $sheet->setCellValue('B6', 'HARI');
                $sheet->setCellValue('C6', 'NO. SPL');
                $sheet->setCellValue('D6', 'NIK');
                $sheet->setCellValue('E6', 'NO. ABSEN');
                $sheet->setCellValue('F6', 'NAMA KARYAWAN');
                $sheet->setCellValue('G6', 'KERJA/LIBUR');
                $sheet->setCellValue('H6', 'STATUS');
                $sheet->setCellValue('I6', 'DEPARTMENT');
                $sheet->setCellValue('J6', 'BAGIAN');

                $sheet->setCellValue('K6', 'JADWAL KERJA');
                $sheet->setCellValue('K7', 'IN');
                $sheet->setCellValue('L7', 'OUT');

                $sheet->setCellValue('M6', 'ABSEN');
                $sheet->setCellValue('M7', 'IN');
                $sheet->setCellValue('N7', 'OUT');

                $sheet->setCellValue('O6', 'WAKTU LEMBUR PENGAJUAN');
                $sheet->setCellValue('O7', 'MULAI');
                $sheet->setCellValue('P7', 'SELESAI');
                $sheet->setCellValue('Q7', 'TOTAL LEMBUR');

                $sheet->setCellValue('R6', 'CATATAN');
                $sheet->setCellValue('S6', 'WAKTU LEMBUR ACTUAL');
                $sheet->setCellValue('S7', 'LEMBUR 1');
                $sheet->setCellValue('T7', 'LEMBUR 2');
                $sheet->setCellValue('U7', 'LEMBUR 3');
                $sheet->setCellValue('V7', 'LEMBUR 4');
                $sheet->setCellValue('W7', 'TOTAL LEMBUR 1234');
                $sheet->setCellValue('X7', 'Verify Status');

                $sheet->mergeCells('A6:A7');
                $sheet->mergeCells('B6:B7');
                $sheet->mergeCells('C6:C7');
                $sheet->mergeCells('D6:D7');
                $sheet->mergeCells('E6:E7');
                $sheet->mergeCells('F6:F7');
                $sheet->mergeCells('G6:G7');
                $sheet->mergeCells('H6:H7');
                $sheet->mergeCells('I6:I7');
                $sheet->mergeCells('J6:J7');

                $sheet->mergeCells('K6:L6');
                $sheet->mergeCells('M6:N6');
                $sheet->mergeCells('O6:Q6');

                $sheet->mergeCells('R6:R7');
                $sheet->mergeCells('S6:W6');
            },
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'PT NAG - HRIS',
            'lastModifiedBy' => 'HRIS',
            'title'          => 'Data Lembur Karyawan',
            'description'    => 'Data Lembur Karyawan',
            'subject'        => 'Data Lembur Karyawan',
            'keywords'       => 'lembur,hr,hris,hrm,daily,report,karyawan,data',
            'category'       => 'LemburKaryawan',
            'manager'        => 'HRIS',
            'company'        => 'PT Nirwana Alabare Garment',
        ];
    }
}
