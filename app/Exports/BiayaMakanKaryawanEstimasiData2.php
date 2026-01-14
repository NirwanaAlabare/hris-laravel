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

class BiayaMakanKaryawanEstimasiData2 implements FromView, WithTitle, WithColumnFormatting
{
    use Exportable;
    protected $dateFrom;
    protected $dataLembur1;
    protected $dataLembur2;
    protected $dataLembur3;
    protected $dataLembur4;
    protected $dataLembur5;
    // public function __construct($dateFrom)
    // {
    //     $this->dateFrom = $dateFrom ? $dateFrom : date('Y-m-d');

    //     $this->dataLembur1 = DB::select("select 'Lembur' shift,b.department_name department,non_staff,if(non_staff!=0,8000,0) harga,8000*non_staff jumlah,staff,if(staff!=0,10000,0) harga2,10000*staff jumlah2,staff+non_staff jumlah_karyawan,(non_staff*8000)+(staff*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$dateFrom' and keterangan='LEMBUR' order by keterangan,dept");
    //     $this->dataLembur2 = DB::select("select 'Shift Malam' shift,b.department_name department,non_staff,if(non_staff!=0,8000,0) harga,8000*non_staff jumlah,staff,if(staff!=0,10000,0) harga2,10000*staff jumlah2,staff+non_staff jumlah_karyawan,(non_staff*8000)+(staff*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$dateFrom' and keterangan='SHIFT MALAM' order by keterangan,dept");
    //     $this->dataLembur3 = DB::select("select 'LEMBUR TOTAL' shift,'' department,sum(non_staff) as non_staff,sum(if(non_staff!=0,1,0))*8000 harga,8000*sum(non_staff) jumlah,sum(staff) staff,sum(if(staff!=0,1,0))*10000 harga2,10000*sum(staff) jumlah2,sum(staff)+sum(non_staff) jumlah_karyawan,(sum(non_staff)*8000)+(sum(staff)*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$dateFrom' and keterangan='LEMBUR' group by keterangan order by keterangan,dept");
    //     $this->dataLembur4 = DB::select("select 'SHIFT MALAM TOTAL' shift,'' department,sum(non_staff) as non_staff,sum(if(non_staff!=0,1,0))*8000 harga,8000*sum(non_staff) jumlah,sum(staff) staff,sum(if(staff!=0,1,0))*10000 harga2,10000*sum(staff) jumlah2,sum(staff)+sum(non_staff) jumlah_karyawan,(sum(non_staff)*8000)+(sum(staff)*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$dateFrom' and keterangan='SHIFT MALAM' group by keterangan order by keterangan,dept");
    //     $this->dataLembur5 = DB::select("select 'GRAND TOTAL' shift,'' department,sum(non_staff) as non_staff,sum(if(non_staff!=0,1,0))*8000 harga,8000*sum(non_staff) jumlah,sum(staff) staff,sum(if(non_staff!=0,1,0))*10000 harga2,10000*sum(staff) jumlah2,sum(staff)+sum(non_staff) jumlah_karyawan,(sum(non_staff)*8000)+(sum(staff)*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id IN ('NAG','NAK','NAGD') and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$dateFrom' group by shift order by keterangan,dept");


        public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to   = $to;
        // $this->dateFrom = $dateFrom ? $dateFrom : date('Y-m-d');
        // dd($this->from = $from, $this->to = $to);

        // $this->dataLembur1 = DB::select("SELECT 'Lembur' AS shift,b.department_name AS department,SUM(a.non_staff) AS non_staff,8000 AS harga,SUM(a.non_staff) * 8000 AS jumlah,SUM(a.staff) AS staff, 10000 AS harga2,SUM(a.staff) * 10000 AS jumlah2,SUM(a.staff) + SUM(a.non_staff) AS jumlah_karyawan,(SUM(a.non_staff) * 8000) + (SUM(a.staff) * 10000) AS total FROM estimasi_anggaran_makan a JOIN department_all b ON a.dept = b.department_id and a.sub_dept = b.sub_dept_id WHERE a.tanggal BETWEEN '$from' AND '$to' AND a.keterangan = 'LEMBUR' AND b.site_nirwana_id IN ('NAG','NAK','NAGD') AND b.status = 'AKTIF' GROUP BY b.department_name ORDER BY b.department_name");
        // $this->dataLembur2 = DB::select("SELECT 'Shift Malam' AS shift,b.department_name AS department,SUM(a.non_staff) AS non_staff,8000 AS harga,SUM(a.non_staff) * 8000 AS jumlah,SUM(a.staff) AS staff, 10000 AS harga2,SUM(a.staff) * 10000 AS jumlah2,SUM(a.staff) + SUM(a.non_staff) AS jumlah_karyawan,(SUM(a.non_staff) * 8000) + (SUM(a.staff) * 10000) AS total FROM estimasi_anggaran_makan a JOIN department_all b ON a.dept = b.department_id and a.sub_dept = b.sub_dept_id WHERE a.tanggal BETWEEN '$from' AND '$to' AND a.keterangan = 'SHIFT MALAM' AND b.site_nirwana_id IN ('NAG','NAK','NAGD') AND b.status = 'AKTIF' GROUP BY b.department_name ORDER BY b.department_name");
        // $this->dataLembur3 = DB::select("SELECT 'LEMBUR TOTAL' AS shift,'' AS department, SUM(a.non_staff) AS non_staff,COUNT(DISTINCT CASE WHEN a.non_staff > 0 THEN b.department_name END) * 8000 AS harga, SUM(a.non_staff) * 8000 AS jumlah, SUM(a.staff) AS staff, COUNT(DISTINCT CASE WHEN a.staff > 0 THEN b.department_name END) * 10000 AS harga2, SUM(a.staff) * 10000 AS jumlah2, SUM(a.staff) + SUM(a.non_staff) AS jumlah_karyawan, (SUM(a.non_staff) * 8000) + (SUM(a.staff) * 10000) AS total FROM estimasi_anggaran_makan a JOIN department_all b ON a.dept = b.department_id and a.sub_dept = b.sub_dept_id WHERE a.tanggal BETWEEN '$from' AND '$to' AND a.keterangan = 'LEMBUR' AND b.site_nirwana_id IN ('NAG','NAK','NAGD') AND b.status = 'AKTIF'");
        // $this->dataLembur4 = DB::select("SELECT 'SHIFT MALAM TOTAL' AS shift,'' AS department, SUM(a.non_staff) AS non_staff,COUNT(DISTINCT CASE WHEN a.non_staff > 0 THEN b.department_name END) * 8000 AS harga, SUM(a.non_staff) * 8000 AS jumlah, SUM(a.staff) AS staff, COUNT(DISTINCT CASE WHEN a.staff > 0 THEN b.department_name END) * 10000 AS harga2, SUM(a.staff) * 10000 AS jumlah2, SUM(a.staff) + SUM(a.non_staff) AS jumlah_karyawan, (SUM(a.non_staff) * 8000) + (SUM(a.staff) * 10000) AS total FROM estimasi_anggaran_makan a JOIN department_all b ON a.dept = b.department_id and a.sub_dept = b.sub_dept_id WHERE a.tanggal BETWEEN '$from' AND '$to' AND a.keterangan = 'SHIFT MALAM' AND b.site_nirwana_id IN ('NAG','NAK','NAGD') AND b.status = 'AKTIF'");
        // $this->dataLembur5 = DB::select("SELECT 'GRAND TOTAL' AS shift,'' AS department, SUM(a.non_staff) AS non_staff,COUNT(DISTINCT CASE WHEN a.non_staff > 0 THEN CONCAT(b.department_name, '-', a.keterangan) END ) * 8000 AS harga, SUM(a.non_staff) * 8000 AS jumlah, SUM(a.staff) AS staff, COUNT( DISTINCT CASE WHEN a.staff > 0 THEN CONCAT(b.department_name, '-', a.keterangan) END ) * 10000 AS harga2, SUM(a.staff) * 10000 AS jumlah2, SUM(a.staff) + SUM(a.non_staff) AS jumlah_karyawan, (SUM(a.non_staff) * 8000) + (SUM(a.staff) * 10000) AS total FROM estimasi_anggaran_makan a JOIN department_all b ON a.dept = b.department_id and a.sub_dept = b.sub_dept_id WHERE a.tanggal BETWEEN '$from' AND '$to'  AND b.site_nirwana_id IN ('NAG','NAK','NAGD') AND b.status = 'AKTIF'");
        $this->dataLembur1 = DB::select("
            SELECT 'LEMBUR' shift,
                a.tanggal,
                    DATE_FORMAT(a.tanggal, '%d %M %Y') AS tanggal_fix,
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
                COALESCE(a.non_staff,0) AS non_staff,
                IF(COALESCE(a.non_staff,0) > 0, 8000, 0) AS harga,
                COALESCE(a.non_staff,0) * 8000 AS jumlah,
                COALESCE(a.staff,0) AS staff,
                IF(COALESCE(a.staff,0) > 0, 10000, 0) AS harga2,
                COALESCE(a.staff,0) * 10000 AS jumlah2,
                (COALESCE(a.staff,0) + COALESCE(a.non_staff,0)) AS jumlah_karyawan,
                (COALESCE(a.staff,0) * 10000)
                + (COALESCE(a.non_staff,0) * 8000) AS total
                FROM estimasi_anggaran_makan a
                WHERE a.tanggal BETWEEN '$from' AND '$to'
                and a.keterangan ='LEMBUR'
                ORDER BY department, sub_dept_name
        ");
        $this->dataLembur2 = DB::select("
            SELECT 'SHIFT MALAM' shift,
                a.tanggal,
                    DATE_FORMAT(a.tanggal, '%d %M %Y') AS tanggal_fix,

                    -- Department (aman walau dept NULL)
                    COALESCE(
                        (SELECT d.department_name
                        FROM department_all d
                        WHERE d.department_id = a.dept
                        LIMIT 1),
                        'Unknown'
                    ) AS department,

                    -- Sub Department (aman walau sub_dept NULL)
                    COALESCE(
                        (SELECT d.sub_dept_name
                        FROM department_all d
                        WHERE d.department_id = a.dept
                        AND d.sub_dept_id = a.sub_dept
                        LIMIT 1),
                        'No Sub Dept'
                    ) AS sub_dept_name,
                COALESCE(a.non_staff,0) AS non_staff,
                IF(COALESCE(a.non_staff,0) > 0, 8000, 0) AS harga,
                COALESCE(a.non_staff,0) * 8000 AS jumlah,

                COALESCE(a.staff,0) AS staff,
                IF(COALESCE(a.staff,0) > 0, 10000, 0) AS harga2,
                COALESCE(a.staff,0) * 10000 AS jumlah2,

                (COALESCE(a.staff,0) + COALESCE(a.non_staff,0)) AS jumlah_karyawan,
                (COALESCE(a.staff,0) * 10000)
                + (COALESCE(a.non_staff,0) * 8000) AS total

                FROM estimasi_anggaran_makan a
                WHERE a.tanggal BETWEEN '$from' AND '$to'
                and a.keterangan ='SHIFT MALAM'
                ORDER BY department, sub_dept_name
        ");
        $this->dataLembur3 = DB::select("
            SELECT
                    'LEMBUR TOTAL' AS shift,
                    '' AS department,

                    SUM(COALESCE(a.non_staff, 0)) AS non_staff,
                    COUNT(DISTINCT CASE
                    WHEN COALESCE(a.non_staff,0) > 0
                    THEN COALESCE(NULLIF(a.sub_dept, ''), a.dept)
                END) * 8000 AS harga,
                    SUM(COALESCE(a.non_staff, 0)) * 8000 AS jumlah,

                    SUM(COALESCE(a.staff, 0)) AS staff,
                COUNT(DISTINCT CASE
                    WHEN COALESCE(a.staff,0) > 0
                    THEN COALESCE(NULLIF(a.sub_dept, ''), a.dept)
                END) * 10000 AS harga2,
                    SUM(COALESCE(a.staff, 0)) * 10000 AS jumlah2,

                    SUM(COALESCE(a.staff, 0) + COALESCE(a.non_staff, 0)) AS jumlah_karyawan,

                    (SUM(COALESCE(a.non_staff, 0)) * 8000)
                    + (SUM(COALESCE(a.staff, 0)) * 10000) AS total
                FROM estimasi_anggaran_makan a
                WHERE a.tanggal BETWEEN '$from' AND '$to'
                AND a.keterangan = 'LEMBUR'
                AND EXISTS (
                    SELECT 1
                    FROM department_all b
                    WHERE b.department_id = a.dept
                    AND b.site_nirwana_id IN ('NAG','NAK','NAGD')
                    )
        ");
        $this->dataLembur4 = DB::select("
            SELECT
                    'SHIFT MALAM TOTAL' AS shift,
                    '' AS department,

                    SUM(COALESCE(a.non_staff, 0)) AS non_staff,
                    COUNT(DISTINCT CASE
                    WHEN COALESCE(a.non_staff,0) > 0
                    THEN COALESCE(NULLIF(a.sub_dept, ''), a.dept)
                END) * 8000 AS harga,
                    SUM(COALESCE(a.non_staff, 0)) * 8000 AS jumlah,

                    SUM(COALESCE(a.staff, 0)) AS staff,
                COUNT(DISTINCT CASE
                    WHEN COALESCE(a.staff,0) > 0
                    THEN COALESCE(NULLIF(a.sub_dept, ''), a.dept)
                END) * 10000 AS harga2,
                    SUM(COALESCE(a.staff, 0)) * 10000 AS jumlah2,

                    SUM(COALESCE(a.staff, 0) + COALESCE(a.non_staff, 0)) AS jumlah_karyawan,

                    (SUM(COALESCE(a.non_staff, 0)) * 8000)
                    + (SUM(COALESCE(a.staff, 0)) * 10000) AS total
                FROM estimasi_anggaran_makan a
                WHERE a.tanggal BETWEEN '$from' AND '$to'
                AND a.keterangan = 'SHIFT MALAM'
                AND EXISTS (
                    SELECT 1
                    FROM department_all b
                    WHERE b.department_id = a.dept
                    AND b.site_nirwana_id IN ('NAG','NAK','NAGD')
                    )
        ");
        $this->dataLembur5 = DB::select("
            SELECT
                    'GRANT TOTAL' AS shift,
                    '' AS department,

                    SUM(COALESCE(a.non_staff, 0)) AS non_staff,
                                COUNT(DISTINCT CASE
                    WHEN COALESCE(a.non_staff,0) > 0
                    THEN CONCAT(
                        COALESCE(NULLIF(a.sub_dept, ''), a.dept),
                        '-',
                        a.keterangan
                    )
                END) * 8000 AS harga,
                    SUM(COALESCE(a.non_staff, 0)) * 8000 AS jumlah,

                    SUM(COALESCE(a.staff, 0)) AS staff,
                    COUNT(DISTINCT CASE
                    WHEN COALESCE(a.staff,0) > 0
                    THEN CONCAT(
                        COALESCE(NULLIF(a.sub_dept, ''), a.dept),
                        '-',
                        a.keterangan
                    )
                    END) * 10000 AS harga2,

                    SUM(COALESCE(a.staff, 0)) * 10000 AS jumlah2,

                    SUM(COALESCE(a.staff, 0) + COALESCE(a.non_staff, 0)) AS jumlah_karyawan,

                    (SUM(COALESCE(a.non_staff, 0)) * 8000)
                    + (SUM(COALESCE(a.staff, 0)) * 10000) AS total
                FROM estimasi_anggaran_makan a
                WHERE a.tanggal BETWEEN '$from' AND '$to'

                AND EXISTS (
                    SELECT 1
                    FROM department_all b
                    WHERE b.department_id = a.dept
                    AND b.site_nirwana_id IN ('NAG','NAK','NAGD')
                    
                )
        ");
    }



    public function title(): string
    {
        return 'Summary';
    }
    public function view(): View
    {
        return view('hris/mutasi-karyawan/form-lembur-sewing/konsumsi_karyawan_estimasi_summary', [
            'from' => $this->from,
            'to' => $this->to,
            'dateFrom' => $this->dateFrom,
            'data_lembur1' => $this->dataLembur1,
            'data_lembur2' => $this->dataLembur2,
            'data_lembur3' => $this->dataLembur3,
            'data_lembur4' => $this->dataLembur4,
            'data_lembur5' => $this->dataLembur5,
        ]);
    }
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
        ];
    }
}
