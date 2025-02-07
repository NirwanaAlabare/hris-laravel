<?php

namespace App\Exports;

use App\Models\DataProduksi;
use App\Models\DataDetailProduksi;
use App\Models\DataDetailProduksiDay;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;

class ExportSplSheet_non_sewing implements WithMultipleSheets, ShouldQueue
{
    use Exportable;

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function sheets(): array
    {
        $sheets = [];
        // date_FORMAT(jam_lembur_awal_rencana,'%H:%i')jam_lembur_awal_rencana
        // date_FORMAT(jam_lembur_akhir_rencana,'%H:%i')jam_lembur_akhir_rencana
        $data = DB::select("
        select
        tgl_lembur,
        DATE_FORMAT(tgl_lembur, '%d-%m-%Y') tgl_lembur_fix,
        a.no_form,
        dept,
        ket,
        b.enroll_id,
        e.nik,
        e.employee_name,
        e.status_jabatan,
        date_FORMAT(jam_lembur_awal_rencana,'%H:%i')jam_lembur_awal_rencana,
        date_FORMAT(jam_lembur_akhir_rencana,'%H:%i')jam_lembur_akhir_rencana,
        date_FORMAT(SEC_TO_TIME(jam_lembur_istirahat*60),'%H:%i') istirahat,
        date_FORMAT(SEC_TO_TIME((((TIMESTAMPDIFF(MINUTE,jam_lembur_awal_rencana,jam_lembur_akhir_rencana)) - jam_lembur_istirahat) * 60)),'%H:%i') total_jam
        from mut_karyawan_input_non_sewing_form_lembur a
        inner join mut_karyawan_input_non_sewing_form_lembur_det b on a.no_form = b.no_form
        inner join employee_atribut e on b.enroll_id = e.enroll_id
        where a.id = '$this->id'
        order by  employee_name asc
            ");

        $totalData = count($data);

        if ($totalData > 0) {
            $dataSplit = array_chunk($data, 100);

            foreach ($dataSplit as $split) {
                $sheets[] = new ExportSpl_non_sewing($split, $this->id);
            }
        } else {
            $sheets[] = new NoDataExport();
        }

        return $sheets;
    }
}
