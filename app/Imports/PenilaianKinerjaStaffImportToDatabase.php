<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\EmployeeAtribut;
use App\Models\MasterDataAbsenKehadiran;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Carbon\Carbon;

class PenilaianKinerjaStaffImportToDatabase implements ToModel, WithStartRow, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    public function startRow(): int
    {
        return 4;
    }
    public function model(array $row){
        $enroll_id=$row[0];
        $contract_start=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[14])->format('Y-m-d');
        $contract_end=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[15])->format('Y-m-d');
        $timestamp = Carbon::now();

        $start_date = Carbon::parse($contract_start);
        $end_date = Carbon::parse($contract_end)->subDays(30);

        $jumlah_mangkir = MasterDataAbsenKehadiran::where('enroll_id', $enroll_id)
        ->whereBetween('tanggal_berjalan', [$start_date, $end_date])
        ->where('status_absen', 'M')
        ->count();
        $jumlah_ijin = MasterDataAbsenKehadiran::where('enroll_id', $enroll_id)
            ->whereBetween('tanggal_berjalan', [$start_date, $end_date])
            ->where('status_absen', 'I')
            ->count();

        $kejadian = [
            'sp3_kali' => 0,
            'sp2_kali' => 0,
            'sp1_kali' => 0,
            'kecelakaan_kali' => 0,
            'mangkir_kali' => $jumlah_mangkir,
            'ijin_kali' => $jumlah_ijin,
        ];

        $total = [
            'sp3_kali' => $kejadian['sp3_kali'] * 6,
            'sp2_kali' => $kejadian['sp2_kali'] * 4,
            'sp1_kali' => $kejadian['sp1_kali'] * 2,
            'kecelakaan_kali' => $kejadian['kecelakaan_kali'] * 2,
            'mangkir_kali' => $kejadian['mangkir_kali'] * 1,
            'ijin_kali' => $kejadian['ijin_kali'] * 0.5,
        ];
        $total_pengurangan = array_sum($total);

        $nilai_kinerja = floatval($row[4]);
        $nilai_rata2 = $row[11];
        $penilaian_akhir = ($nilai_rata2 + $nilai_kinerja) - $total_pengurangan;

        $penilaian_kinerja=DB::select("select*from penilaian_kinerja where enroll_id = '$enroll_id' AND tgl_awal_kontrak = '$contract_start' AND tgl_akhir_kontrak = '$contract_end'");
        if($penilaian_kinerja){
            DB::delete("delete from penilaian_kinerja where enroll_id = '$enroll_id' AND tgl_awal_kontrak = '$contract_start' AND tgl_akhir_kontrak = '$contract_end'");
        }
        DB::insert("insert into penilaian_kinerja (id, enroll_id, tgl_awal_kontrak, tgl_akhir_kontrak, nilai_kinerja,tanggung_jawab_tugas,inisiatif_kerjasama,akurasi_pekerjaan,kemauan_kegigihan,penyampaian_informasi,attitude_sikap_kerja,rata_rata_kompetensi,total_pengurangan,nilai_akhir, created_at, updated_at) VALUES ('','$enroll_id','$contract_start','$contract_end','$row[4]','$row[5]','$row[6]','$row[7]','$row[8]','$row[9]','$row[10]','$row[11]','$total_pengurangan','$penilaian_akhir','$timestamp','$timestamp')");
    }
}
