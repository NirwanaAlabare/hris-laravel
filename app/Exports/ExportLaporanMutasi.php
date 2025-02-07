<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class ExportLaporanMutasi implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }
    public function view(): View
    {
        $data = DB::select('
        select a.id, b.*,ea.sewing_nonsewing,
        c.absen_masuk_kerja,
        DATE_FORMAT(tgl_pindah, "%d-%m-%Y") tgl_pindah_fix,
        DATE_FORMAT(b.updated_at, "%d-%m-%Y %H:%i:%s") tgl_update_fix,
        ea.status_aktif,c.tanggal_berjalan,c.status_absen
        from
        (select max(id) id from mut_karyawan_input a
        group by nik)a
        inner join mut_karyawan_input b on a.id = b.id
        inner join employee_atribut ea on b.enroll_id = ea.enroll_id
        left join (select enroll_id, absen_masuk_kerja, status_absen, tanggal_berjalan from master_data_absen_kehadiran where tanggal_berjalan >= "' . $this->from . '" and tanggal_berjalan <="' . $this->to . '") c on b.enroll_id = c.enroll_id
        where ea.status_aktif = "AKTIF" and absen_masuk_kerja is null
        order by tanggal_berjalan desc
        ');
        return view('hris/mutasi-karyawan.export_mutasi_kehadiran', [
            'data' => $data,
            'from' => $this->from,
            'to' => $this->to
        ]);
    }
}
