<?php

namespace App\Exports;

use App\Models\DataProduksi;
use App\Models\DataDetailProduksi;
use App\Models\DataDetailProduksiDay;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class ExportLineSheet implements WithMultipleSheets, ShouldQueue
{
    use Exportable;

    protected $line;

    public function __construct($line)
    {
        $this->line = $line;
    }

    public function sheets(): array
    {
        $tgl_skrg = date('Y-m-d');
        $sheets = [];

        $data = DB::select("
        SELECT
        line,
        b.enroll_id,
        ea.employee_name nm_karyawan,
        ea.nik,
        b.line_asal,
        ea.status_jabatan,
        absen_masuk_kerja,
        status_absen,
        DATE_FORMAT(tgl_pindah, '%d-%m-%Y') tgl_pindah_fix,
        DATE_FORMAT(b.updated_at, '%d-%m-%Y %H:%i:%s') tgl_update_fix
        from
        (
        select max(id) id from mut_karyawan_input a where tgl_pindah = '$tgl_skrg'
        group by nik
        )a
        inner join mut_karyawan_input b on a.id = b.id
        inner join employee_atribut ea on b.enroll_id = ea.enroll_id
        left join
        (
        select enroll_id,absen_masuk_kerja,status_absen from master_data_absen_kehadiran where tanggal_berjalan = '$tgl_skrg'
        ) mk on b.enroll_id = mk.enroll_id
				where b.line = '$this->line'
				order by employee_name asc
            ");

        $totalData = count($data);

        if ($totalData > 0) {
            $dataSplit = array_chunk($data, 50);

            foreach ($dataSplit as $split) {
                $sheets[] = new ExportLine($split, $this->line);
            }
        } else {
            $sheets[] = new NoDataExport();
        }

        return $sheets;
    }
}
