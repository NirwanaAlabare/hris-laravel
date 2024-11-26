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
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

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


class MasterDataAbsenKehadiranExport implements FromView, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle, WithColumnFormatting
{
    use Exportable;

    public function exportParams(string $daterange1, string $selectDepartment, string $selectBagian, string $status_staff, string $searchData)
    {
        $this->daterange1 = $daterange1;
        $daterange1 = explode(" s/d ", $this->daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSampai = $tanggalSampai;

        $this->selectDepartment = $selectDepartment;

        $this->status_staff = $status_staff;
        $this->searchData = strtoupper($searchData);

        $filterStaff = "";
        if($status_staff) {
            $filterStaff = " AND employee_atribut.status_staff = '" . $status_staff . "'";
        }

        $inDepartment = "";
        if($selectDepartment) {
            $inDepartment = ' AND employee_atribut.department_name = "' . $selectDepartment . '"';

        }

        $inBagian = "";
        if($selectBagian) {
            $inBagian = ' AND employee_atribut.sub_dept_name = "' . $selectBagian . '"';

        }

        $inSearchData = "";
        if($searchData) {
            $inSearchData = '
                AND (
                    UPPER(master_data_absen_kehadiran.enroll_id) LIKE ("%' . $searchData . '%")
                    OR UPPER(master_data_absen_kehadiran.nik) LIKE ("%' . $searchData . '%")
                    OR UPPER(master_data_absen_kehadiran.employee_name) LIKE ("%' . $searchData . '%")
                )
            ';

        }

        $this->daterange1 = $daterange1;
        $this->filterStaff = $filterStaff;
        $this->inDepartment = $inDepartment;
        $this->inBagian = $inBagian;
        $this->inSearchData = $inSearchData;

        return $this;
    }

    public function view() : View
    {
        $tanggalMulai = $this->tanggalMulai;
        $tanggalSampai = $this->tanggalSampai;

        $dataAbsen = MasterDataAbsenKehadiran::query()
        ->selectRaw('
            master_data_absen_kehadiran.tanggal_berjalan,
            master_data_absen_kehadiran.kode_hari,
            master_data_absen_kehadiran.nama_hari,
            employee_atribut.nik,
            employee_atribut.enroll_id,
            employee_atribut.employee_name,
            employee_atribut.status_staff,
            employee_atribut.department_name,
            master_data_absen_kehadiran.mulai_jam_kerja,
            master_data_absen_kehadiran.akhir_jam_kerja,
            master_data_absen_kehadiran.absen_masuk_kerja,
            master_data_absen_kehadiran.absen_pulang_kerja,
            master_data_absen_kehadiran.jumlah_absen_menit_kerja,
            data_absen_perijinan.time_mulai_ijin,
            data_absen_perijinan.time_akhir_ijin,
            data_absen_perijinan.total_time_ijin,
            master_data_absen_kehadiran.jumlah_menit_absen_dt,
            master_data_absen_kehadiran.jumlah_menit_absen_pc,
            master_data_absen_kehadiran.jumlah_menit_absen_dtpc,
            master_data_absen_kehadiran.status_absen,
            master_data_absen_kehadiran.absen_alasan,
            master_data_absen_kehadiran.catatan_hrd,
            master_data_absen_kehadiran.mulai_jam_lembur,
            master_data_absen_kehadiran.akhir_jam_lembur,
            substr(master_data_absen_kehadiran.jumlah_jam_lembur_approved, 1, 5) jumlah_jam_lembur_approved,
            substr(master_data_absen_kehadiran.jumlah_jam_istirahat_lembur, 1, 5) jumlah_jam_istirahat_lembur,
            rekap_perhitungan_lembur.nomor_form_lembur,
            rekap_perhitungan_lembur.final_mulai_jam_lembur,
            rekap_perhitungan_lembur.final_selesai_jam_lembur,
            rekap_perhitungan_lembur.final_total_jam_lembur,
            rekap_perhitungan_lembur.final_jam_istirahat_lembur,
            rekap_perhitungan_lembur.final_total_menit_lembur,
            rekap_perhitungan_lembur.final_jam_lembur_roundown,
            rekap_perhitungan_lembur.final_menit_lembur_roundown,
            rekap_perhitungan_lembur.lembur_1,
            rekap_perhitungan_lembur.lembur_2,
            rekap_perhitungan_lembur.lembur_3,
            rekap_perhitungan_lembur.lembur_4,
            rekap_perhitungan_lembur.total_lembur_1234
        ')
        ->whereRaw('
            substr(master_data_absen_kehadiran.tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
            ' . $this->filterStaff . ' ' . $this->inDepartment . ' ' . $this->inBagian . ' ' . $this->inSearchData . '
        ')
        ->leftJoin('employee_atribut','master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
        ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
        ->leftJoin('data_absen_perijinan', 'master_data_absen_kehadiran.nomor_absen_ijin', '=', 'data_absen_perijinan.nomor_form_perizinan')
        ->leftJoin('rekap_perhitungan_lembur',function($leftjoin){
            $leftjoin->on("master_data_absen_kehadiran.tanggal_berjalan","=","rekap_perhitungan_lembur.tanggal_berjalan")->on("master_data_absen_kehadiran.enroll_id","=","rekap_perhitungan_lembur.enroll_id");
        })
        ->orderBy('employee_atribut.employee_name','asc')
        ->orderBy('master_data_absen_kehadiran.tanggal_berjalan','asc')
        ->get();

        return view("hris.Laporan.absen_excel", ["dataAbsen" => $dataAbsen]);
    }

    public function startCell(): string
    {
        return 'A8';
    }

    public function title(): string
    {
        return $this->daterange1[0] . ' s/d ' . $this->daterange1[1];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'I' => NumberFormat::FORMAT_DATE_TIME3
            // 'I' => NumberFormat:: FORMAT_DATE_TIME2
        ];
    }

    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18);
                $sheet->setCellValue('A2', 'LAPORAN ABSENSI KARYAWAN');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(16);

                setlocale(LC_ALL, 'id-ID', 'id_ID');
                $datePeriode = strtoupper(strftime("%d %b %Y", strtotime($this->tanggalMulai)) . ' s/d ' . strftime("%d %b %Y", strtotime($this->tanggalSampai)));

                $sheet->setCellValue('A3', 'TANGGAL ABSENSI : ' . $datePeriode);
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                if($this->status_staff) {
                    $status_staff = $this->status_staff;
                } else {
                    $status_staff = 'SEMUA KARYAWAN';
                }
                $sheet->setCellValue('A4', 'STAFF / NON STAFF : ' . $status_staff);
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->mergeCells('A1:E1');
                $sheet->mergeCells('A2:E2');
                $sheet->mergeCells('A3:E3');
                $sheet->mergeCells('A4:E4');

                $sheet->mergeCells('A6:A7');
                $sheet->setCellValue('A6', 'TANGGAL');

                $sheet->mergeCells('B6:B7');
                $sheet->setCellValue('B6', 'HARI');

                $sheet->mergeCells('C6:C7');
                $sheet->setCellValue('C6', 'NIK');

                $sheet->mergeCells('D6:D7');
                $sheet->setCellValue('D6', 'NO. ABSEN');

                $sheet->mergeCells('E6:E7');
                $sheet->setCellValue('E6', 'NAMA KARYAWAN');

                $sheet->mergeCells('F6:F7');
                $sheet->setCellValue('F6', 'STAFF / NON STAFF');

                $sheet->mergeCells('G6:G7');
                $sheet->setCellValue('G6', 'DEPARTMENT');

                $sheet->mergeCells('H6:H7');
                $sheet->setCellValue('H6', 'KERJA/LIBUR');

                $sheet->mergeCells('I6:L6');
                $sheet->setCellValue('I6', 'JADWAL KERJA');
                $sheet->setCellValue('I7', 'IN');
                $sheet->setCellValue('J7', 'OUT');
                $sheet->setCellValue('K7', 'DURASI ISTIRAHAT');
                $sheet->setCellValue('L7', 'DURASI KERJA');

                $sheet->mergeCells('M6:O6');
                $sheet->setCellValue('M6', 'ABSENSI');
                $sheet->setCellValue('M7', 'IN');
                $sheet->setCellValue('N7', 'OUT');
                $sheet->setCellValue('O7', 'EFEKTIF KERJA');

                $sheet->mergeCells('P6:R6');
                $sheet->setCellValue('P6', 'IJIN KELUAR SEMENTARA (IKS)');
                $sheet->setCellValue('P7', 'DARI');
                $sheet->setCellValue('Q7', 'SAMPAI');
                $sheet->setCellValue('R7', 'TOTAL');

                $sheet->mergeCells('S6:U6');
                $sheet->setCellValue('S6', 'POTONGAN MENIT');
                $sheet->setCellValue('S7', 'DT');
                $sheet->setCellValue('T7', 'PC');
                $sheet->setCellValue('U7', 'Total');

                $sheet->mergeCells('V6:V7');
                $sheet->setCellValue('V6', 'STATUS ABSEN');

                $sheet->mergeCells('W6:W7');
                $sheet->setCellValue('W6', 'ALASAN ABSEN');

                $sheet->mergeCells('X6:X7');
                $sheet->setCellValue('X6', 'KETERANGAN');

                $sheet->mergeCells('Y6:Y7');

                $sheet->mergeCells('Z6:AJ6');
                $sheet->setCellValue('Z6', 'DATA LEMBUR (ACTUAL)');
                $sheet->setCellValue('Z7', 'NO. SPL');
                $sheet->setCellValue('AA7', 'MULAI');
                $sheet->setCellValue('AB7', 'SELESAI');
                $sheet->setCellValue('AC7', 'JUMLAH JAM');
                $sheet->setCellValue('AD7', 'ISTIRAHAT');
                $sheet->setCellValue('AE7', 'TOTAL LEMBUR');
                $sheet->setCellValue('AF7', 'L1');
                $sheet->setCellValue('AG7', 'L2');
                $sheet->setCellValue('AH7', 'L3');
                $sheet->setCellValue('AI7', 'L4');
                $sheet->setCellValue('AJ7', 'TOTAL L');
                $sheet->mergeCells('AK6:AN6');
                $sheet->setCellValue('AK6', 'DATA LEMBUR (PENGAJUAN)');
                $sheet->setCellValue('AK7', 'MULAI JAM LEMBUR');
                $sheet->setCellValue('AL7', 'AKHIR JAM LEMBUR');
                $sheet->setCellValue('AM7', 'JUMLAH JAM LEMBUR');
                $sheet->setCellValue('AN7', 'JUMLAH JAM ISTIRAHAT');

            },
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'PT NAG - HRIS',
            'lastModifiedBy' => 'HRIS',
            'title'          => 'Data Kehadiran Karyawan (Daily)',
            'description'    => 'Data Kehadiran Karyawan (Daily)',
            'subject'        => 'Kehadiran Karyawan',
            'keywords'       => 'kehadiran,hr,hris,hrm,daily,report,karyawan,data',
            'category'       => 'KehadiranKaryawan',
            'manager'        => 'HRIS',
            'company'        => 'PT Nirwana Alabare Garment',
        ];
    }
}
