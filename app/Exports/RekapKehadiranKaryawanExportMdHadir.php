<?php

namespace App\Exports;

use App\Http\Controllers\Hris\RekapKehadiranKaryawanController;
use App\Models\RekapKehadiranKaryawan;
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
use App\Models\MasterDataAbsenKehadiran;
use App\Models\EmployeeAtribut;
use App\Models\RefAbsenIjin;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

use Auth;

class RekapKehadiranKaryawanExportMdHadir implements FromQuery, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle
{
    use Exportable;


    public function exportParams(string $tanggal_awal, string $tanggal_akhir, string $inEnrollId, string $inDepartment, string $inSection, string $inStatusStaff, string $inFactory)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->inEnrollId = $inEnrollId;
        $this->inDepartment = $inDepartment;
        $this->inSection = $inSection;
        $this->status_staff = $inStatusStaff;
        $this->inFactory = $inFactory;

        return $this;
    }

    public function query()
    {
        $tanggal_awal = $this->tanggal_awal;
        $tanggal_akhir = $this->tanggal_akhir;
        $inEnrollId = $this->inEnrollId;
        $inDepartment = $this->inDepartment;
        $inSection = $this->inSection;
        $inStatusStaff = $this->status_staff;
        $inFactory = $this->inFactory;

       $query = MasterDataAbsenKehadiran::select([
            'master_data_absen_kehadiran.*',
            'employee_atribut.employee_name',
            'employee_atribut.nik',
            'employee_atribut.status_aktif',
            'employee_atribut.join_date',
            'employee_atribut.status_staff',
            'employee_atribut.status_jabatan',
            'employee_atribut.sub_dept_name',
            'employee_atribut.department_name',
            'employee_atribut.tanggal_resign',
        ])
        ->leftJoin('employee_atribut', 'master_data_absen_kehadiran.enroll_id', '=', 'employee_atribut.enroll_id')
        ->whereBetween('master_data_absen_kehadiran.tanggal_berjalan', [$tanggal_awal, $tanggal_akhir])->groupBy('employee_atribut.enroll_id');

        // Apply conditional where clauses
        if ($this->inEnrollId) {
            $query->whereRaw($this->inEnrollId);
        }

        if ($this->inDepartment) {
            $query->whereRaw($this->inDepartment);
        }

        if ($this->inSection) {
            $query->whereRaw($this->inSection);
        }

        if ($this->status_staff) {
            $query->whereRaw($this->status_staff);
        }

        if ($this->inFactory) {
            $query->whereRaw($this->inFactory);
        }


        return $query;

    }


    public function startCell(): string
    {
        return 'A7';
    }

    public function map($value): array
    {
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');

        $tidak_bayar=RefAbsenIjin::where('kode_ijin_payroll','ITB')->where('kode_absen_ijin','!=','M')->where('kode_absen_ijin','!=','IKS')->get()->toArray();
        $ITB=array_column($tidak_bayar,'kode_absen_ijin');


        $enroll_id = $value->enroll_id;
        $nik = $value->nik;
        $employee_name = $value->employee_name;
        $sub_dept_name = $value->sub_dept_name;
        $status_aktif = $value->status_aktif;
        $status_staff = $value->status_staff;
        $join_date = $value->join_date;
        $status_jabatan = $value->status_jabatan;
        $sub_dept_name = $value->sub_dept_name;
        $department_name = $value->department_name;
        $resign_date = $value->tanggal_resign;


        $IBY_employe=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->wherein('status_absen', $IBY)->count();
        $LBY_employe=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','LN')->whereNotin('kode_hari', ['5','6'])->count();
        $ITB_employe=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->wherein('status_absen', $ITB)->count();
        $lsm_employe=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->wherein('kode_hari', ['5','6'])->count();

        $dt_employe=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','0')->where('status_absen',null)->count();
        $pc_employe=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('jumlah_menit_absen_pc','>','0')->where('jumlah_menit_absen_dt','0')->where('status_absen',null)->count();

        $dtpc_employe=$dt_employe+$pc_employe;

        $absen_M=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','M')->count();
        $absen_R=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','R')->count();
        $absen_TL=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','TL')->where('mulai_jam_kerja','!=',null)->count();
        $absen_IKS=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','IKS')->where('jumlah_menit_absen_dtpc','0')->count();
        $absen_ok=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->whereNotin('kode_hari', ['5','6'])->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();
        $absen_ok=$absen_ok+$absen_IKS;

        $total_kehadiran=$IBY_employe+$ITB_employe+$lsm_employe+$dtpc_employe+$absen_M+$absen_R+$absen_ok+$LBY_employe+$dt_employe+$pc_employe+$absen_TL;


        $kehadiran_dl=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','DL')->count();
        $kehadiran_cb=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','CB')->count();
        $kehadiran_cbd=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','CBD')->count();
        $kehadiran_cg=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','CG')->count();
        $kehadiran_ch=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','CH')->count();
        $kehadiran_cm=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','CM')->count();
        $kehadiran_cn=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','CN')->count();
        $kehadiran_ct=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','CT')->count();
        $kehadiran_ig=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','IG')->count();
        $kehadiran_im=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','IM')->count();
        $kehadiran_ka=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','KA')->count();
        $kehadiran_km=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','KM')->count();
        $kehadiran_kr=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','KR')->count();
        $kehadiran_na=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','NA')->count();
        $kehadiran_pp=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','PP')->count();
        $kehadiran_i=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','I')->count();
        $kehadiran_lp=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','LP')->count();
        $kehadiran_l=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','L')->count();
        $kehadiran_tl=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','TL')->count();
        $kehadiran_iks=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','IKS')->count();
        $kehadiran_s=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','S')->count();

        $M_estimasi=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','M')->count();
        $TL_estimasi=$value->where('enroll_id', $enroll_id)->whereBetween('tanggal_berjalan', [$this->tanggal_awal, $this->tanggal_akhir])->where('status_absen','TL')->where('mulai_jam_kerja','!=',null)->count();



        $kehadiran_iby=$IBY_employe;
        $kehadiran_itb=$ITB_employe;
        $kehadiran_lby=$LBY_employe;
        $kehadiran_lsm=$lsm_employe;
        $kehadiran_dt= $dt_employe;
        $kehadiran_pc= $pc_employe;
        $kehadiran_dtpc=$dtpc_employe;
        $kehadiran_m=$absen_M+$absen_TL;
        $kehadiran_r=$absen_R;
        $kehadiran_ok=$absen_ok;
        $total_kehadiran= $total_kehadiran;
        $total_kehadiran_net=$absen_ok+$dt_employe+$pc_employe+$dtpc_employe+$LBY_employe+$IBY_employe;


        $kehadiran_dl=$kehadiran_dl;
        $kehadiran_cb=$kehadiran_cb;
        $kehadiran_cbd=$kehadiran_cbd;
        $kehadiran_cg=$kehadiran_cg;
        $kehadiran_ch=$kehadiran_ch;
        $kehadiran_cm=$kehadiran_cm;
        $kehadiran_cn=$kehadiran_cn;
        $kehadiran_ct=$kehadiran_ct;
        $kehadiran_ig=$kehadiran_ig;
        $kehadiran_im=$kehadiran_im;
        $kehadiran_ka=$kehadiran_ka;
        $kehadiran_km=$kehadiran_km;
        $kehadiran_kr=$kehadiran_kr;
        $kehadiran_na=$kehadiran_na;
        $kehadiran_pp=$kehadiran_pp;
        $kehadiran_i=$kehadiran_i;
        $kehadiran_lp=$kehadiran_lp;
        $kehadiran_l=$kehadiran_l;
        $kehadiran_tl=$kehadiran_tl;
        $kehadiran_iks=$kehadiran_iks;
        $kehadiran_s=$kehadiran_s;
        $kehadiran_m_estimasi= $TL_estimasi+$M_estimasi;
        $kehadiran_mangkir=$kehadiran_m-$kehadiran_tl;

        $kosong='';

        $row = [
                $value->enroll_id,
                $value->nik,
                $value->employee_name,
                $value->status_aktif,
                $value->join_date ? Carbon::parse($value->join_date)->translatedFormat('d F Y') : '',
                $value->tanggal_resign ? Carbon::parse($value->tanggal_resign)->translatedFormat('d F Y') : '',
                $value->status_staff,
                $value->status_jabatan,
                $value->sub_dept_name,
                $value->department_name,
        ];


        $startDate = Carbon::parse($this->tanggal_awal);
        $endDate = Carbon::parse($this->tanggal_akhir);

        $absenPerTanggal = $value ?? collect();

        while ($startDate->lte($endDate)) {
            $tanggal = $startDate->format('Y-m-d');
            $absenHariIni = $absenPerTanggal->where('enroll_id', $enroll_id)->firstWhere('tanggal_berjalan', $tanggal);
            $statusAbsen = '';

            if ($absenHariIni) {
                $jumlahDT = (int) $absenHariIni->jumlah_menit_absen_dt;
                $jumlahPC = (int) $absenHariIni->jumlah_menit_absen_pc;

                if ($jumlahDT > 0 && $jumlahPC > 0) {
                    $statusAbsen = 'DTPC';
                } elseif ($jumlahDT > 0) {
                    $statusAbsen = 'DT';
                } elseif ($jumlahPC > 0) {
                    $statusAbsen = 'PC';
                } elseif ($absenHariIni->status_absen) {
                    $statusAbsen = $absenHariIni->status_absen;
                }
                $kodeHari = $absenHariIni->kode_hari;
                if ($kodeHari == 5 || $kodeHari == 6) {
                    $statusAbsen = 'L';
                }
            }

            $row[] = $statusAbsen;

            $startDate->addDay();
        }
        $row = array_merge($row, [
            $kosong,
            $kehadiran_dl,
            $kehadiran_dt,
            $kehadiran_pc,
            $kehadiran_dtpc,
            $kehadiran_cb,
            $kehadiran_cbd,
            $kehadiran_cg,
            $kehadiran_ch,
            $kehadiran_cm,
            $kehadiran_cn,
            $kehadiran_ct,
            $kehadiran_ig,
            $kehadiran_im,
            $kehadiran_ka,
            $kehadiran_km,
            $kehadiran_kr,
            $kehadiran_na,
            $kehadiran_pp,
            $kehadiran_i,
            $kehadiran_lby,
            $kehadiran_lp,
            $kehadiran_lsm,
            $kehadiran_l,
            $kehadiran_mangkir,
            $kehadiran_tl,
            $kehadiran_iks,
            $kehadiran_ok,
            $kehadiran_r,
            $kehadiran_s,
        ]);

        return $row;

        //     return [
            // $kosong,
            // $kehadiran_dl,
            // $kehadiran_dt,
            // $kehadiran_pc,
            // $kehadiran_dtpc,
            // $kehadiran_cb,
            // $kehadiran_cbd,
            // $kehadiran_cg,
            // $kehadiran_ch,
            // $kehadiran_cm,
            // $kehadiran_cn,
            // $kehadiran_ct,
            // $kehadiran_ig,
            // $kehadiran_im,
            // $kehadiran_ka,
            // $kehadiran_km,
            // $kehadiran_kr,
            // $kehadiran_na,
            // $kehadiran_pp,
            // $kehadiran_i,
            // $kehadiran_lby,
            // $kehadiran_lp,
            // $kehadiran_lsm,
            // $kehadiran_l,
            // $kehadiran_mangkir,
            // $kehadiran_tl,
            // $kehadiran_iks,
            // $kehadiran_ok,
            // $kehadiran_r,
            // $kehadiran_s,
        // ];


    }

    public function title(): string
    {
        return 'REKAPKEHADIRANKARYAWAN';
    }


    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18);
                $sheet->setCellValue('A2', 'Controlling Absensin');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(16);
                $sheet->setCellValue('A3', 'Periode  : ' . Carbon::parse($this->tanggal_awal)->translatedFormat('d F Y') . ' - ' . Carbon::parse($this->tanggal_akhir)->translatedFormat('d F Y'));
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');
                $sheet->getStyle('A5:AG5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A5:AG5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // (opsional) bungkus teks jika panjang
                $sheet->getStyle('A5:AG5')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A5:J6')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FEFB00'], // Kuning
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Hitam
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                $sheet->getColumnDimension('C')->setAutoSize(false); // penting
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->setCellValue('A5', 'ID');
                $sheet->mergeCells('A5:A6');

                $sheet->setCellValue('B5', 'NIK');
                $sheet->mergeCells('B5:B6');

                $sheet->setCellValue('C5', 'Nama Karyawan');
                $sheet->mergeCells('C5:C6');

                $sheet->setCellValue('D5', 'AKTIF/NON AKTIF');
                $sheet->mergeCells('D5:D6');

                $sheet->setCellValue('E5', 'JOIN DATE');
                $sheet->mergeCells('E5:E6');

                $sheet->setCellValue('F5', 'RESIGN DATE');
                $sheet->mergeCells('F5:F6');

                $sheet->setCellValue('G5', 'STAFF/NON STAFF');
                $sheet->mergeCells('G5:G6');

                $sheet->setCellValue('H5', 'JABATAN');
                $sheet->mergeCells('H5:H6');

                $sheet->setCellValue('I5', 'BAGIAN');
                $sheet->mergeCells('I5:I6');

                $sheet->setCellValue('J5', 'DEPARTMENT');
                $sheet->mergeCells('J5:J6');

                $currentDate = Carbon::parse($this->tanggal_awal);
                $endDate = Carbon::parse($this->tanggal_akhir);
                $colIndex = 11; // K

                while ($currentDate->lte($endDate)) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                    $sheet->getColumnDimension($colLetter)->setAutoSize(false);
                    $sheet->getColumnDimension($colLetter)->setWidth(15);
                    $sheet->setCellValue("{$colLetter}5", strtoupper($currentDate->locale('id')->dayName));
                    $sheet->setCellValue("{$colLetter}6", $currentDate->translatedFormat('d-M-Y'));

                    $sheet->getStyle("{$colLetter}5:{$colLetter}6")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => 'FEFB00'],
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                    ]);

                    $colIndex++;
                    $currentDate->addDay();
                }

                // 2. Tambahkan kolom absen rekap setelah tanggal dinamis
                $rekapLabels = [
                   ' ', 'Summary DL', 'DT', 'PC', 'DTPC', 'CB', 'CBD', 'CG', 'CH', 'CM', 'CN', 'CT',
                    'IG', 'IM', 'KA', 'KM', 'KR', 'NA', 'PP', 'I', 'LN', 'LP', 'LSM', 'L', 'M', 'TL', 'IKS', 'OK', 'R', 'S',
                ];

                foreach ($rekapLabels as $label) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIndex);

                    // Merge baris 5-6
                    $sheet->mergeCells("{$colLetter}5:{$colLetter}6");
                    $sheet->setCellValue("{$colLetter}5", $label);
                    $backgroundColor = trim($label) === '' ? 'FFFFFF' : 'FFCCFF';
                    $sheet->getStyle("{$colLetter}5:{$colLetter}6")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => $backgroundColor],
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                    ]);

                    $colIndex++;
                }

                // $sheet->setCellValue('D5', '');
                // $sheet->setCellValue('E5', 'Summary DL');
                // $sheet->setCellValue('F5', 'DT');
                // $sheet->setCellValue('G5', 'PC');
                // $sheet->setCellValue('H5', 'DTPC');
                // $sheet->setCellValue('I5', 'CB');
                // $sheet->setCellValue('J5', 'CBD');
                // $sheet->setCellValue('K5', 'CG');
                // $sheet->setCellValue('L5', 'CH');
                // $sheet->setCellValue('M5', 'CM');
                // $sheet->setCellValue('N5', 'CN');
                // $sheet->setCellValue('O5', 'CT');
                // $sheet->setCellValue('P5', 'IG');
                // $sheet->setCellValue('Q5', 'IM');
                // $sheet->setCellValue('R5', 'KA');
                // $sheet->setCellValue('S5', 'KM');
                // $sheet->setCellValue('T5', 'KR');
                // $sheet->setCellValue('U5', 'NA');
                // $sheet->setCellValue('V5', 'PP');
                // $sheet->setCellValue('W5', 'I');
                // $sheet->setCellValue('X5', 'LN');
                // $sheet->setCellValue('Y5', 'LP');
                // $sheet->setCellValue('Z5', 'LSM');
                // $sheet->setCellValue('AA5', 'L');
                // $sheet->setCellValue('AB5', 'M');
                // $sheet->setCellValue('AC5', 'TL');
                // $sheet->setCellValue('AD5', 'IKS');
                // $sheet->setCellValue('AE5', 'OK');
                // $sheet->setCellValue('AF5', 'R');
                // $sheet->setCellValue('AG5', 'S');
            },
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'PT NAG - HRIS',
            'lastModifiedBy' => 'HRIS',
            'title'          => 'Rekap Perhitungan Kehadiran Karyawan',
            'description'    => 'Rekap Perhitungan Kehadiran Karyawan',
            'subject'        => 'Rekap Perhitungan Kehadiran Karyawan',
            'keywords'       => 'perhitungan,kehadiran,hr,hris,hrm,daily,report,karyawan,data',
            'category'       => 'RekapPerhitunganKehadiranKaryawan',
            'manager'        => 'HRIS',
            'company'        => 'PT Nirwana Alabare Garment',
        ];
    }
}
