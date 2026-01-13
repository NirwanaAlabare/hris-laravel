<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

// class BiayaMakanKaryawanEstimasiData implements FromView, WithTitle, WithColumnFormatting
// {
//     use Exportable;
//     protected $dateFrom,$dataLembur;
//     public function __construct($dateFrom)
//     {
//         $this->dateFrom = $dateFrom ? $dateFrom : date('Y-m-d');
//         // $this->dataLembur = DB::select("select substr(tanggal,1,7) periode,tanggal,d.department_name department,'STAFF' staff_non_staff,staff jumlah_karyawan,10000 harga,10000*staff jumlah,keterangan shift from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' group by department_id) d on estimasi_anggaran_makan.dept=d.department_id where tanggal='$dateFrom'
//         // UNION
//         // select substr(tanggal,1,7) periode,tanggal,d.department_name department,'NON STAFF' staff_non_staff,non_staff jumlah_karyawan,80000 harga,80000*non_staff jumlah,keterangan shift from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' group by department_id) d on estimasi_anggaran_makan.dept=d.department_id where tanggal='$dateFrom'
//         // order by shift, department");

//         // $this->dataLembur = DB::select("select substr(tanggal,1,7) periode,tanggal,d.department_name department, d.sub_dept_name, 'STAFF' staff_non_staff,staff jumlah_karyawan,10000 harga,10000*staff jumlah,keterangan shift from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' ) d on estimasi_anggaran_makan.dept=d.department_id and estimasi_anggaran_makan.sub_dept=d.sub_dept_id where tanggal='$dateFrom'
//         // UNION
//         // select substr(tanggal,1,7) periode,tanggal,d.department_name department, d.sub_dept_name, 'NON STAFF' staff_non_staff,non_staff jumlah_karyawan,8000 harga,8000*non_staff jumlah,keterangan shift from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' ) d on estimasi_anggaran_makan.dept=d.department_id and estimasi_anggaran_makan.sub_dept=d.sub_dept_id where tanggal='$dateFrom'
//         // order by shift, department, sub_dept_name");
//         $this->dataLembur = DB::select("SELECT x.periode,x.tanggal,x.department,x.sub_dept_name,x.staff_non_staff,x.jumlah_karyawan,x.harga,x.jumlah,x.shift,x.created_by FROM ( SELECT SUBSTR(a.tanggal,1,7) AS periode,a.tanggal,d.department_name AS department, d.sub_dept_name as sub_dept_name,'STAFF' AS staff_non_staff,SUM(a.staff) AS jumlah_karyawan,10000 AS harga,SUM(a.staff) * 10000 AS jumlah,a.keterangan AS shift,GROUP_CONCAT(DISTINCT a.created_by ORDER BY a.created_by SEPARATOR ', ') AS created_by FROM estimasi_anggaran_makan a INNER JOIN (SELECT department_id, department_name ,sub_dept_id, sub_dept_name FROM department_all WHERE site_nirwana_id IN ('NAG','NAK','NAGD') AND status = 'AKTIF' ) d ON a.dept = d.department_id and a.sub_dept=d.sub_dept_id WHERE a.tanggal = '$dateFrom' GROUP BY  a.dept, d.department_name, a.tanggal, a.keterangan
//         UNION ALL
//         SELECT SUBSTR(a.tanggal,1,7) AS periode,a.tanggal,d.department_name AS department, d.sub_dept_name as sub_dept_name,'NON STAFF' AS staff_non_staff,  SUM(a.non_staff) AS jumlah_karyawan, 8000 AS harga, SUM(a.non_staff) * 8000 AS jumlah,  a.keterangan AS shift, GROUP_CONCAT(DISTINCT a.created_by ORDER BY a.created_by SEPARATOR ', ') AS created_by FROM estimasi_anggaran_makan a  INNER JOIN ( SELECT department_id, department_name,sub_dept_id, sub_dept_name FROM department_all  WHERE site_nirwana_id IN ('NAG','NAK','NAGD') AND status = 'AKTIF') d ON a.dept = d.department_id and a.sub_dept=d.sub_dept_id WHERE a.tanggal = '$dateFrom' GROUP BY  a.dept, d.department_name, a.tanggal, a.keterangan) x
//         ORDER BY x.shift, x.department");
//     }
//     public function title(): string
//     {
//         return 'Data';
//     }
//     public function view(): View
//     {
//         return view('hris/mutasi-karyawan/form-lembur-sewing/konsumsi_karyawan_estimasi', [
//             'data_lembur' => $this->dataLembur,
//         ]);
//     }
//     public function columnFormats(): array
//     {
//         return [
//             'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
//             'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
//             'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
//             'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
//         ];
//     }
// }

class BiayaMakanKaryawanEstimasiData implements FromView, WithTitle,WithColumnFormatting
{
    use Exportable;

    // protected $dateFrom;
    // protected $type; // <-- PENENTU SHEET
      protected $from, $to, $type;

    public function __construct($from, $to)
    {
        // $this->dateFrom = $dateFrom;
        $this->from = $from;
        $this->to   = $to;
        // $this->type = $type;
        // $this->type = $type;
        // dd($this->from = $from, $this->to = $to, $this->type = $type);
        // if ($this->type === 'lembur') {
            $this->dataLembur = DB::select("
                SELECT
                    SUBSTR(a.tanggal,1,7) AS periode,
                    a.tanggal,

                    -- department AMAN
                    COALESCE(
                        (SELECT d.department_name
                        FROM department_all d
                        WHERE d.department_id = a.dept
                        LIMIT 1),
                        'Unknown'
                    ) AS department,

                    -- sub dept AMAN
                    COALESCE(
                        (SELECT d.sub_dept_name
                        FROM department_all d
                        WHERE d.department_id = a.dept
                        AND d.sub_dept_id = a.sub_dept
                        LIMIT 1),
                        'No Sub Dept'
                    ) AS sub_dept_name,

                    'NON STAFF' AS staff_non_staff,
                    COALESCE(a.non_staff,0) AS jumlah_karyawan,
                    8000 AS harga,
                    COALESCE(a.non_staff,0) * 8000 AS jumlah,
                    a.keterangan AS shift

                FROM estimasi_anggaran_makan a
                WHERE  a.tanggal BETWEEN '$from' AND '$to'
                AND a.keterangan = 'LEMBUR'

                UNION ALL

                SELECT
                    SUBSTR(a.tanggal,1,7) AS periode,
                    a.tanggal,

                    COALESCE(
                        (SELECT d.department_name
                        FROM department_all d
                        WHERE d.department_id = a.dept
                        LIMIT 1),
                        'Unknown'
                    ) AS department,

                    COALESCE(
                        (SELECT d.sub_dept_name
                        FROM department_all d
                        WHERE d.department_id = a.dept
                        AND d.sub_dept_id = a.sub_dept
                        LIMIT 1),
                        'No Sub Dept'
                    ) AS sub_dept_name,

                    'STAFF' AS staff_non_staff,
                    COALESCE(a.staff,0) AS jumlah_karyawan,
                    10000 AS harga,
                    COALESCE(a.staff,0) * 10000 AS jumlah,
                    a.keterangan AS shift

                FROM estimasi_anggaran_makan a
                WHERE a.tanggal BETWEEN '$from' AND '$to'
                AND a.keterangan = 'LEMBUR'

                ORDER BY tanggal, department, sub_dept_name, staff_non_staff

                ");
        // } elseif ($this->type === 'shift_malam') {
        //     $this->dataLembur = DB::select("SELECT x.periode,x.tanggal,x.department,x.sub_dept_name,x.staff_non_staff,x.jumlah_karyawan,x.harga,x.jumlah,x.shift,x.created_by FROM ( SELECT SUBSTR(a.tanggal,1,7) AS periode,a.tanggal,d.department_name AS department, d.sub_dept_name as sub_dept_name,'STAFF' AS staff_non_staff,SUM(a.staff) AS jumlah_karyawan,10000 AS harga,SUM(a.staff) * 10000 AS jumlah,a.keterangan AS shift,GROUP_CONCAT(DISTINCT a.created_by ORDER BY a.created_by SEPARATOR ', ') AS created_by FROM estimasi_anggaran_makan a INNER JOIN (SELECT department_id, department_name ,sub_dept_id, sub_dept_name FROM department_all WHERE site_nirwana_id IN ('NAG','NAK','NAGD') AND status = 'AKTIF' ) d ON a.dept = d.department_id and a.sub_dept=d.sub_dept_id WHERE a.tanggal BETWEEN '$from' AND '$to' GROUP BY  a.dept, d.department_name, a.tanggal, a.keterangan
        // UNION ALL
        // SELECT SUBSTR(a.tanggal,1,7) AS periode,a.tanggal,d.department_name AS department, d.sub_dept_name as sub_dept_name,'NON STAFF' AS staff_non_staff,  SUM(a.non_staff) AS jumlah_karyawan, 8000 AS harga, SUM(a.non_staff) * 8000 AS jumlah,  a.keterangan AS shift, GROUP_CONCAT(DISTINCT a.created_by ORDER BY a.created_by SEPARATOR ', ') AS created_by FROM estimasi_anggaran_makan a  INNER JOIN ( SELECT department_id, department_name,sub_dept_id, sub_dept_name FROM department_all  WHERE site_nirwana_id IN ('NAG','NAK','NAGD') AND status = 'AKTIF') d ON a.dept = d.department_id and a.sub_dept=d.sub_dept_id WHERE a.tanggal BETWEEN '$from' AND '$to' GROUP BY  a.dept, d.department_name, a.tanggal, a.keterangan) x
        // ORDER BY x.tanggal, x.shift, x.department");
        // }
    }

    // public function title(): string
    // {
    //     return $this->type === 'lembur'
    //         ? 'Data Lembur'
    //         : 'Data Shift Malam';
    // }
    public function title(): string
    {
        return 'Data';
    }

    public function view(): View
    {
        return view('hris/mutasi-karyawan/form-lembur-sewing/konsumsi_karyawan_estimasi', [
            'data_lembur' => $this->dataLembur,
        ]);
    }
     public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
        ];
    }
}
