<?php

namespace App\Http\Controllers\Hris;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use DB;
use Illuminate\Support\Facades\Auth;
// use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Dompdf\Options;
use Dompdf\FontMetrics;
use App\Models\EmployeeAtribut;
use App\Models\PenilaianKinerja;
use App\Models\VoucherBazzar;
use App\Imports\PenilaianKinerjaStaffImport;
use App\Imports\PenilaianKinerjaStaffImportToDatabase;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\MasterDataAbsenKehadiran;
use App\Exports\exportExcelKontrak;
use App\Exports\ExcelRekapPenilaianKinerja;
use App\Exports\ExcelRencanaAdjustmentSallary;
use App\Models\DasarPotBPJS;
use App\Models\EntertainPengajuanTamu;
use App\Exports\ExcelPenilaianKinerjaNonstaff;
use App\Models\DepartmentAll;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use PDF;


class PenilaianKinerjaStaffController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
    }

    private function adjustDate($inputDate)
    {
        $originalDate = Carbon::parse($inputDate);
        $date = $originalDate->copy();
        $holidays = $this->getHolidays($date->year);

        $wasAdjusted = false;

        // 1. Jika Sabtu/Minggu → mundur ke Jumat
        if ($date->isSaturday() || $date->isSunday()) {
            $date = $date->previous(Carbon::FRIDAY);
            $wasAdjusted = true;
        }

        // 2. Jika termasuk hari libur, terus mundur
        while (in_array($date->toDateString(), $holidays)) {
            $date->subDay();
            $wasAdjusted = true;
        }


        if (! $wasAdjusted && !$date->isFriday()) {
            $date->addDay();
        }
        return $date->toDateString();
    }

    private function getHolidays($year)
    {
        $response = Http::get("https://api-harilibur.vercel.app/api?year={$year}");
        if ($response->successful()) {
            return collect($response->json())->pluck('holiday_date')->toArray();
        }
        return [];
    }

    public function get_employee_contract_staff(){
        $loggedAdmin = Auth::guard('admin')->user();
        $loggedEmail = $loggedAdmin->email;
        $email = ['mega@ptnag.com', 'rudy@ptnag.com', 'fadli'];
        $department_id = EmployeeAtribut::where('enroll_id', $loggedAdmin->enroll_id)->value('department_id');
        $inSearchVariable='';
        $inEnrollId='';
        $inIbuKandung='';
        $inNoKTP='';
        $status_kontrak=request()->status_kontrak;
        $compare='';
        if (request("search_variable")) {
            $search_variable=request()->search_variable;
            $inSearchVariable = 'AND (z.enroll_id = "'.$search_variable.'" or z.nik LIKE "'.$search_variable.'%" or z.employee_name LIKE "%'.$search_variable.'%" or z.tempat_lahir LIKE "%'.$search_variable.'%" or z.nomor_tlpn LIKE "'.$search_variable.'%" or z.agama LIKE "'.$search_variable.'%" or z.status_kawin LIKE "'.$search_variable.'%" or z.nomor_kk LIKE "'.$search_variable.'%" or z.pendidikan_terakhir LIKE "'.$search_variable.'%" or z.jurusan_pendidikan LIKE "'.$search_variable.'%" or z.alamat_rumah LIKE "%'.$search_variable.'%" or z.department_name LIKE "%'.$search_variable.'%" or z.sub_dept_name LIKE "%'.$search_variable.'%" or z.status_aktif LIKE "'.$search_variable.'%" or z.ibu_kandung LIKE "%'.$search_variable.'%" or z.nomor_ktp LIKE "'.$search_variable.'%")';
        }
        if(request()->notification_id){
            $notification_id = request()->notification_id;
            $notification_enroll_id = DB::table('notifications')
                ->where('id', $notification_id)
                ->value('enroll_ids');

            // Decode JSON string ke array
            $enroll_ids_array = json_decode($notification_enroll_id, true);

            // Pastikan hasil decode adalah array
            if (is_array($enroll_ids_array)) {
                // Ubah array menjadi string "5684,8085,8086,..."
                $enroll_id_string = implode(',', $enroll_ids_array);

                // Masukkan ke dalam klausa SQL
                $inEnrollId = 'AND z.enroll_id IN (' . $enroll_id_string . ')';
            } else {
                // Tangani jika format data tidak valid
                $inEnrollId = '';
            }
        }
        if(request()->ibu_kandung){
            $ibu_kandung_string=request()->ibu_kandung;
            $inIbuKandung='AND z.ibu_kandung LIKE "%'.$ibu_kandung_string.'%"';
        }
        if(request()->no_ktp){
            $no_ktp_string=request()->no_ktp;
            $inNoKTP='AND z.nomor_ktp LIKE "'.$no_ktp_string.'%"';
        }
        $inDepartment = '';
        if (!in_array($loggedEmail, $email)) {
            $inDepartment = 'AND z.department_id =  "'.$department_id.'"';
        }
        $inStatusKontrak='';
        if($status_kontrak=='Active'){
            $inStatusKontrak='AND y.contract_end >= curdate()';
        }else if($status_kontrak=='Nonactive'){
            $inStatusKontrak='AND y.contract_end < curdate()';
        }else if($status_kontrak=='One Day'){
            $inStatusKontrak='AND y.contract_end = curdate()';
        }else if($status_kontrak=='Thirty Day'){
            // $thirty_day_more = date('Y-m-d',strtotime('+30 days',strtotime(date("Y-m-d")))) . PHP_EOL;
            // $inStatusKontrak='AND y.contract_end = "'.$thirty_day_more.'"';
            $today = date('Y-m-d');
            $thirty_days_later = date('Y-m-d', strtotime('+30 days'));
            $inStatusKontrak = 'AND y.contract_end BETWEEN "'.$today.'" AND "'.$thirty_days_later.'"';

        }else if($status_kontrak=='Sixty Day'){
            $thirty_day_more = date('Y-m-d',strtotime('+60 days',strtotime(date("Y-m-d")))) . PHP_EOL;
            $inStatusKontrak='AND y.contract_end = "'.$thirty_day_more.'"';
        }else if($status_kontrak=='Not yet extended'){
            $inStatusKontrak='AND y.contract_end < curdate() AND z.status_aktif ="AKTIF"';
        }else if($status_kontrak=='Unfilled'){
            $inStatusKontrak='AND y.contract_end is null';
        }
        $inStatusAktif='';
        if(request()->status_aktif){
            $status_aktif=request()->status_aktif;
            $inStatusAktif='AND z.status_aktif = "'.$status_aktif.'"';
        }
        $data_input = DB::select("select z.enroll_id,z.nik,z.employee_name,z.department_name, z.department_id,z.status_staff,z.sub_dept_name,z.status_aktif,z.tanggal_resign,z.ibu_kandung,z.nomor_ktp,y.id,y.contract,y.contract_end from (select a.enroll_id,a.id,e.contract,e.contract_end from (select enroll_id,max(contract) contract,max(contract_end) contract_end from employee_contract group by enroll_id)e inner join (select id,enroll_id,contract,contract_end from employee_contract)a on e.enroll_id=a.enroll_id and e.contract_end=a.contract_end)y right join (select enroll_id,nik,employee_name,tanggal_resign,tempat_lahir,nomor_tlpn,agama,status_kawin,nomor_kk,pendidikan_terakhir,jurusan_pendidikan,alamat_rumah,department_name,department_id,status_staff,sub_dept_name,status_aktif,ibu_kandung,nomor_ktp from employee_atribut)z on y.enroll_id=z.enroll_id where z.enroll_id is not null ".$inSearchVariable." ".$inIbuKandung." ".$inNoKTP." ".$inStatusKontrak." ".$inStatusAktif." ".$inEnrollId." ".$inDepartment." AND z.status_staff = 'STAFF' GROUP BY enroll_id  order by enroll_id");
        return DataTables::of($data_input)->toJson();
    }

    public function get_employee_contract_staff_by_id(){
        $enroll_id=request()->id;
        $contract=request()->contract;
        $contract_end=request()->contract_end;
        $data=DB::select("select a.status_staff,a.enroll_id,a.nik,a.employee_name,a.status_jabatan,a.sub_dept_name,a.department_name,a.status_kontrak_tetap,a.status_aktif,a.join_date,a.tanggal_resign,a.nomor_ktp,a.tempat_lahir,a.alamat_rumah,a.tanggal_lahir,a.no_surat,b.contract,b.contract_end,c.max_contract,c.max_contract_end from employee_atribut a left join employee_contract b on a.enroll_id=b.enroll_id left join (select enroll_id,max(contract) max_contract,max(contract_end) max_contract_end from employee_contract group by enroll_id)c on a.enroll_id=c.enroll_id where a.enroll_id=".$enroll_id." group by a.enroll_id");

        $data_penilaian = PenilaianKinerja::where('enroll_id', $enroll_id)->where('tgl_awal_kontrak',$contract)->where('tgl_akhir_kontrak', $contract_end)->first();

        $start_date = Carbon::parse($contract);
        $end_date = Carbon::parse($contract_end)->subDays(14);

        $jumlah_mangkir = MasterDataAbsenKehadiran::where('enroll_id', $enroll_id)
            ->whereBetween('tanggal_berjalan', [$start_date, $end_date])
            ->where('status_absen', 'M')
            ->count();
        $jumlah_ijin = MasterDataAbsenKehadiran::where('enroll_id', $enroll_id)
            ->whereBetween('tanggal_berjalan', [$start_date, $end_date])
            ->where('status_absen', 'I')
            ->count();


        if (!$data_penilaian) {
            $data_penilaian = new \stdClass();
        }
        // Default kejadian dan total, bisa juga digunakan saat data_penilaian tidak ada
        $kejadian = [
            'sp3_kali' => $data_penilaian->sp3_kali ?? 0,
            'sp2_kali' => $data_penilaian->sp2_kali ?? 0,
            'sp1_kali' => $data_penilaian->sp1_kali ?? 0,
            'kecelakaan_kali' => $data_penilaian->kecelakaan_kali ?? 0,
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

        // Masukkan ke objek
        $data_penilaian->kejadian = $kejadian;
        $data_penilaian->total = $total;
        $data_penilaian->total_pengurangan = $total_pengurangan;

        return response()->json([
            'status' => 'success',
            'msg' => 'Data berhasil ditambahkan',
            'data' => $data,
            'data_penilaian' => $data_penilaian,
            'jumlah_mangkir' => $jumlah_mangkir,
            'jumlah_ijin' => $jumlah_ijin,
        ]);
    }
    public function store_penilaian_kinerja_staff(Request $request){
        try {
            $kejadian = [
                'sp3_kali' => $request->kejadian['sp3_kali'] ?? null,
                'sp2_kali' => $request->kejadian['sp2_kali'] ?? null,
                'sp1_kali' => $request->kejadian['sp1_kali'] ?? null,
                'kecelakaan_kali' => $request->kejadian['kecelakaan_kali'] ?? null,
                'mangkir_kali' => $request->kejadian['mangkir_kali'] ?? null,
                'ijin_kali' => $request->kejadian['ijin_kali'] ?? null,
            ];

            $total = [
                'sp3_kali' => ($request->kejadian['sp3_kali'] ?? 0) * 6,
                'sp2_kali' => ($request->kejadian['sp2_kali'] ?? 0) * 4,
                'sp1_kali' => ($request->kejadian['sp1_kali'] ?? 0) * 2,
                'kecelakaan_kali' => ($request->kejadian['kecelakaan_kali'] ?? 0) * 2,
                'mangkir_kali' => ($request->kejadian['mangkir_kali'] ?? 0) * 1,
                'ijin_kali' => ($request->kejadian['ijin_kali'] ?? 0) * 0.5,
            ];

            $total_pengurangan = array_sum($total);

              // Hitung total kompetensi
            $kompetensi_fields = [
                'tanggung_jawab_tugas',
                'inisiatif_kerjasama',
                'akurasi_pekerjaan',
                'kemauan_kegigihan',
                'penyampaian_informasi',
                'attitude_sikap_kerja'
            ];

            $total_kompetensi = 0;
            foreach ($kompetensi_fields as $field) {
                $total_kompetensi += floatval($request->kompetensi[$field] ?? 0);
            }

            // Hitung nilai akhir
            $nilai_kinerja = floatval($request->nilai_kinerja);
            // $nilai_rata2 = ($nilai_kinerja + $total_kompetensi) / 6;
            $nilai_rata2 = $total_kompetensi / 6;
            $penilaian_akhir = ($nilai_rata2 + $nilai_kinerja) - $total_pengurangan;

            // $timestamp = Carbon::now();
            // DB::insert("insert into employee_contract (id, enroll_id, contract, contract_end, created_at, updated_at) VALUES ('','$request->enroll_id_input_2_val','$contract','$contract_end','$timestamp','$timestamp')");

            $penilaian_kinerja = PenilaianKinerja::create([
                'enroll_id' => $request->enroll_id_input_2_val,
                'tgl_awal_kontrak' => $request->awal_kontrak_text_val,
                'tgl_akhir_kontrak' => $request->akhir_kontrak_text_val,
                'periode_kontrak' => $request->periode_penilaian_text_val,
                'uraian_tugas_1' => $request->uraian_tugas_1,
                'target_pencapaian_1' => $request->target_pencapaian_1,
                'uraian_tugas_2' => $request->uraian_tugas_2,
                'target_pencapaian_2' => $request->target_pencapaian_2,
                'uraian_tugas_3' => $request->uraian_tugas_3,
                'target_pencapaian_3' => $request->target_pencapaian_3,
                'uraian_tugas_4' => $request->uraian_tugas_4,
                'target_pencapaian_4' => $request->target_pencapaian_4,
                'uraian_tugas_5' => $request->uraian_tugas_5,
                'target_pencapaian_5' => $request->target_pencapaian_5,
                'nilai_kinerja' => $request->nilai_kinerja,

                'tanggung_jawab_tugas' => $request->kompetensi['tanggung_jawab_tugas'] ?? null,
                'inisiatif_kerjasama' => $request->kompetensi['inisiatif_kerjasama'] ?? null,
                'akurasi_pekerjaan' => $request->kompetensi['akurasi_pekerjaan'] ?? null,
                'kemauan_kegigihan' => $request->kompetensi['kemauan_kegigihan'] ?? null,
                'penyampaian_informasi' => $request->kompetensi['penyampaian_informasi'] ?? null,
                'attitude_sikap_kerja' => $request->kompetensi['attitude_sikap_kerja'] ?? null,

                'sp3_kali' => $request->kejadian['sp3_kali'] ?? null,
                'sp2_kali' => $request->kejadian['sp2_kali'] ?? null,
                'sp1_kali' => $request->kejadian['sp1_kali'] ?? null,
                'kecelakaan_kali' => $request->kejadian['kecelakaan_kali'] ?? null,
                'mangkir_kali' => $request->kejadian['mangkir_kali'] ?? null,
                'ijin_kali' => $request->kejadian['ijin_kali'] ?? null,
                'total_pengurangan' => $total_pengurangan,

                'rekomendasi_tindak_lanjut' => $request->rekomendasi,
                'perpanjang_bulan' => $request->rekomendasi == 'perpanjang' ? $request->perpanjang_bulan : null,
                'judul_training' => $request->rekomendasi == 'training' ? $request->judul_training : null,
                'rekomendasi_training' => null,
                'rekomendasi_perpanjang_kontrak' => null,
                'rekomendasi_phk' => null,
                'rekomendasi_demosi' => null,
                'rekomendasi_promosi' => null,


                'rata_rata_kompetensi' => $nilai_rata2,
                'nilai_akhir' => $penilaian_akhir,
                'total_kompetensi' => $total_kompetensi,
                'penilai' => $request->penilai,
            ]);

             if ($request->rekomendasi == 'perpanjang') {
                $timestamp = Carbon::now();
                $akhirKontrak = Carbon::parse($request->akhir_kontrak_text_val);
                $adjustedDate = $akhirKontrak->copy()->addDay();
                $adjustedContractEndCarbon = $adjustedDate->copy()->addMonths($request->perpanjang_bulan)->subDay(); // 2025-05-20

                $exists = DB::table('employee_contract')
                    ->where('enroll_id', $request->enroll_id_input_2_val)
                    ->where('contract', $adjustedDate)
                    ->where('contract_end', $adjustedContractEndCarbon)
                    ->exists();

                if (!$exists) {
                    DB::table('employee_contract')->insert([
                        'enroll_id'   => $request->enroll_id_input_2_val,
                        'contract'    => $adjustedDate,
                        'contract_end'=> $adjustedContractEndCarbon,
                        'created_at'  => $timestamp,
                        'updated_at'  => $timestamp
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'msg' => 'Data berhasil ditambahkan',
                'enroll_id' => $request->enroll_id_input_2_val,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }


    public function update_penilaian_kinerja_staff(Request $request, $id)
    {
        try {
            $penilaian_kinerja = PenilaianKinerja::findOrFail($id);

            $kejadian = [
                'sp3_kali' => $request->kejadian['sp3_kali'] ?? null,
                'sp2_kali' => $request->kejadian['sp2_kali'] ?? null,
                'sp1_kali' => $request->kejadian['sp1_kali'] ?? null,
                'kecelakaan_kali' => $request->kejadian['kecelakaan_kali'] ?? null,
                'mangkir_kali' => $request->kejadian['mangkir_kali'] ?? null,
                'ijin_kali' => $request->kejadian['ijin_kali'] ?? null,
            ];

            $total = [
                'sp3_kali' => ($request->kejadian['sp3_kali'] ?? 0) * 6,
                'sp2_kali' => ($request->kejadian['sp2_kali'] ?? 0) * 4,
                'sp1_kali' => ($request->kejadian['sp1_kali'] ?? 0) * 2,
                'kecelakaan_kali' => ($request->kejadian['kecelakaan_kali'] ?? 0) * 2,
                'mangkir_kali' => ($request->kejadian['mangkir_kali'] ?? 0) * 1,
                'ijin_kali' => ($request->kejadian['ijin_kali'] ?? 0) * 0.5,
            ];

            $total_pengurangan = array_sum($total);


            // Hitung total kompetensi
            $kompetensi_fields = [
                'tanggung_jawab_tugas',
                'inisiatif_kerjasama',
                'akurasi_pekerjaan',
                'kemauan_kegigihan',
                'penyampaian_informasi',
                'attitude_sikap_kerja'
            ];

            $total_kompetensi = 0;
            foreach ($kompetensi_fields as $field) {
                $total_kompetensi += floatval($request->kompetensi[$field] ?? 0);
            }

            // Hitung nilai akhir
            $nilai_kinerja = floatval($request->nilai_kinerja);
            // $nilai_rata2 = ($nilai_kinerja + $total_kompetensi) / 6;
            $nilai_rata2 = $total_kompetensi / 6;
            $penilaian_akhir = ($nilai_rata2 + $nilai_kinerja) - $total_pengurangan;

            $penilaian_kinerja->update([
                'enroll_id' => $request->enroll_id_input_2_val,
                'tgl_awal_kontrak' => $request->awal_kontrak_text_val,
                'tgl_akhir_kontrak' => $request->akhir_kontrak_text_val,
                'periode_kontrak' => $request->periode_penilaian_text_val,
                'uraian_tugas_1' => $request->uraian_tugas_1,
                'target_pencapaian_1' => $request->target_pencapaian_1,
                'uraian_tugas_2' => $request->uraian_tugas_2,
                'target_pencapaian_2' => $request->target_pencapaian_2,
                'uraian_tugas_3' => $request->uraian_tugas_3,
                'target_pencapaian_3' => $request->target_pencapaian_3,
                'uraian_tugas_4' => $request->uraian_tugas_4,
                'target_pencapaian_4' => $request->target_pencapaian_4,
                'uraian_tugas_5' => $request->uraian_tugas_5,
                'target_pencapaian_5' => $request->target_pencapaian_5,
                'nilai_kinerja' => $request->nilai_kinerja,

                // Kompetensi
                'tanggung_jawab_tugas' => $request->kompetensi['tanggung_jawab_tugas'] ?? null,
                'inisiatif_kerjasama' => $request->kompetensi['inisiatif_kerjasama'] ?? null,
                'akurasi_pekerjaan' => $request->kompetensi['akurasi_pekerjaan'] ?? null,
                'kemauan_kegigihan' => $request->kompetensi['kemauan_kegigihan'] ?? null,
                'penyampaian_informasi' => $request->kompetensi['penyampaian_informasi'] ?? null,
                'attitude_sikap_kerja' => $request->kompetensi['attitude_sikap_kerja'] ?? null,

                // Kejadian
                'sp3_kali' => $request->kejadian['sp3_kali'] ?? 0,
                'sp2_kali' => $request->kejadian['sp2_kali'] ?? 0,
                'sp1_kali' => $request->kejadian['sp1_kali'] ?? 0,
                'kecelakaan_kali' => $request->kejadian['kecelakaan_kali'] ?? 0,
                'mangkir_kali' => $request->kejadian['mangkir_kali'] ?? 0,
                'ijin_kali' => $request->kejadian['ijin_kali'] ?? 0,
                'total_pengurangan' => $total_pengurangan,

                // Rekomendasi dan data lainnya
                'rekomendasi_tindak_lanjut' => $request->rekomendasi,
                'perpanjang_bulan' => $request->rekomendasi == 'perpanjang' ? $request->perpanjang_bulan : null,
                'judul_training' => $request->rekomendasi == 'training' ? $request->judul_training : null,

                'rekomendasi_perpanjang_kontrak' => null,
                'rekomendasi_phk' => null,
                'rekomendasi_demosi' => null,
                'rekomendasi_promosi' => null,
                'rekomendasi_training' => null,

                'rata_rata_kompetensi' => $nilai_rata2,
                'nilai_akhir' => $penilaian_akhir,
                'total_kompetensi' => $total_kompetensi,
                'penilai' => $request->penilai,
            ]);


            if ($request->rekomendasi == 'perpanjang') {
                $timestamp = Carbon::now();
                $akhirKontrak = Carbon::parse($request->akhir_kontrak_text_val);
                $adjustedDate = $akhirKontrak->copy()->addDay();
                $adjustedContractEndCarbon = $adjustedDate->copy()->addMonths($request->perpanjang_bulan)->subDay(); // 2025-05-20

                $exists = DB::table('employee_contract')
                    ->where('enroll_id', $request->enroll_id_input_2_val)
                    ->where('contract', $adjustedDate)
                    ->where('contract_end', $adjustedContractEndCarbon)
                    ->exists();

                if (!$exists) {
                    DB::table('employee_contract')->insert([
                        'enroll_id'   => $request->enroll_id_input_2_val,
                        'contract'    => $adjustedDate,
                        'contract_end'=> $adjustedContractEndCarbon,
                        'created_at'  => $timestamp,
                        'updated_at'  => $timestamp
                    ]);
                }
            }



            return response()->json([
                'status' => 'success',
                'msg' => 'Data berhasil diperbarui',
                'enroll_id' => $request->enroll_id_input_2_val,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }

    public function export_penilaian_kinerja_staff_pdf(Request $request)
    {
        $enroll_id=$request->enroll_id;
        $contract=$request->contract;
        $contract_end=$request->contract_end;
        $data_karyawan = EmployeeAtribut::where('enroll_id', $enroll_id)->first();

        $data_penilaian = PenilaianKinerja::where('enroll_id', $enroll_id)->where('tgl_awal_kontrak',$contract)->where('tgl_akhir_kontrak', $contract_end)->first();

        if($data_penilaian){
            $kejadian = [
                'sp3_kali' => $data_penilaian->sp3_kali,
                'sp2_kali' => $data_penilaian->sp2_kali,
                'sp1_kali' => $data_penilaian->sp1_kali,
                'kecelakaan_kali' => $data_penilaian->kecelakaan_kali,
                'mangkir_kali' => $data_penilaian->mangkir_kali,
                'ijin_kali' => $data_penilaian->ijin_kali,
            ];

            $total = [
                'sp3_kali' => $data_penilaian->sp3_kali * 6,
                'sp2_kali' => $data_penilaian->sp2_kali * 4,
                'sp1_kali' => $data_penilaian->sp1_kali * 2,
                'kecelakaan_kali' => $data_penilaian->kecelakaan_kali * 2,
                'mangkir_kali' => $data_penilaian->mangkir_kali * 1,
                'ijin_kali' => $data_penilaian->ijin_kali * 0.5,
            ];

            $total_pengurangan = array_sum($total);

            // Lalu gabungkan ke dalam data_penilaian
            $data_penilaian->kejadian = $kejadian;
            $data_penilaian->total = $total;
            $data_penilaian->total_pengurangan = $total_pengurangan;
        }
        $pdf = PDF::loadview('hris/hrd/export_nilai_kinerja_karyawan_pdf_custom',['data_penilaian'=>$data_penilaian,'data_karyawan'=>$data_karyawan,'contract'=>$contract,'contract_end'=>$contract_end]);
        return $pdf->stream('laporan-pegawai.pdf');
    }

    public function import_penilaian_kinerja_staff(){
        $import = new PenilaianKinerjaStaffImport;
        Excel::import($import, request()->file('excel_file'));
        return $import->getRowCount();
    }

    public function import_penilaian_kinerja_staff_to_database(){
        // khawatir terjadi penumpukan
        ini_set("max_execution_time", 0);
        ini_set("max_input_time", 0);
        Excel::import(new PenilaianKinerjaStaffImportToDatabase, request()->file('excel_file'));
    }

    public function download_excel_penilaian_kinerja_nonstaff(){
        $loggedAdmin = Auth::guard('admin')->user();
        $loggedEmail = $loggedAdmin->email;
        $email = ['mega@ptnag.com', 'rudy@ptnag.com', 'fadli'];

        $inEnrollId='';
        $inStatusAktif='';
        $inStatusKontrak='';
        $inStatusKontrakRange='';
        $inDepartment_name='';

        if(request()->enroll_id){
            $enroll_id=request()->enroll_id;
            $enroll_id_string=implode(',', $enroll_id);
            $inEnrollId='AND z.enroll_id in ('.$enroll_id_string.')';
        }

        if(request("department_name")){
            $department=request("department_name");
            $inDepartment_name = ' AND z.department_name = "'.$department.'"';
        }

        $today = date('Y-m-d');
        $currentMonth = date('m');
        $currentYear = date('Y');


        $inStatusKontrak='';
        $status_kontrak = request()->status_kontrak;

        if($status_kontrak){
            if($status_kontrak=='One Day'){
                $one_days_later = date('Y-m-d', strtotime('+1 days'));
                $inStatusKontrak='AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) >= "'.$today.'"
                    AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) <= "'.$one_days_later.'"';
            }else if($status_kontrak=='Nine Day'){
                $nine_days_later = date('Y-m-d', strtotime('+9 days'));
                $inStatusKontrak= 'AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) >= "'.$today.'"
                    AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) <= "'.$nine_days_later.'"';
            }else if($status_kontrak=='Thirty Day'){
                $thirty_days_later = date('Y-m-d', strtotime('+30 days'));
                $inStatusKontrak = 'AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) >= "'.$today.'"
                    AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) <= "'.$thirty_days_later.'"';
            }else if($status_kontrak=='Sixty Day'){
                $thirty_day_more = date('Y-m-d',strtotime('+60 days',strtotime(date("Y-m-d")))) . PHP_EOL;
                $inStatusKontrak='AND (
                    CASE
                        WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                        ELSE y.contract_end
                    END
                ) >= "'.$thirty_day_more.'"';
            }
        }

        if (request()->date_range) {
            $daterange1 = explode(" s/d ", request()->date_range);
            $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
            $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
            $bulanAkhir = date('m', strtotime($tanggalSampai));
            $tahunAkhir = date('Y', strtotime($tanggalSampai));
            $inStatusKontrakRange = 'AND (
                CASE
                    WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                    ELSE y.contract_end
                END
            ) >= "'.$tanggalMulai.'"
            AND (
                CASE
                    WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                    ELSE y.contract_end
                END
            ) <= "'.$tanggalSampai.'"';
        }
        $inStatusAktif='AND z.status_aktif = "aktif" AND z.status_staff = "NON STAFF"';

       $data_kontrak_non_staff = DB::select("
        SELECT
            z.enroll_id,
            z.nik,
            z.employee_name,
            z.department_name,
            z.sub_dept_name,
            z.status_aktif,
            z.status_staff,
            z.tanggal_resign,
            z.join_date,
            z.ibu_kandung,
            z.nomor_ktp,
            y.id,
            y.contract,
            y.contract AS contract_last,
            y.contract_end  AS contract_end_last,
            -- logika untuk mengganti contract_end dengan tanggal_resign jika ada
            CASE
                WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                ELSE y.contract_end
            END AS contract_end
        FROM (
            SELECT
                a.enroll_id,
                a.id,
                e.contract,
                e.contract_end
            FROM (
                SELECT
                    enroll_id,
                    MAX(contract) AS contract,
                    MAX(contract_end) AS contract_end
                FROM employee_contract
                GROUP BY enroll_id
            ) e
            INNER JOIN (
                SELECT
                    id,
                    enroll_id,
                    contract,
                    contract_end
                FROM employee_contract
            ) a ON e.enroll_id = a.enroll_id AND e.contract_end = a.contract_end
        ) y
        RIGHT JOIN (
            SELECT
                enroll_id,
                nik,
                employee_name,
                tanggal_resign,
                tempat_lahir,
                nomor_tlpn,
                agama,
                status_kawin,
                join_date,
                nomor_kk,
                pendidikan_terakhir,
                jurusan_pendidikan,
                alamat_rumah,
                department_name,
                sub_dept_name,
                status_aktif,
                status_staff,
                ibu_kandung,
                nomor_ktp
            FROM employee_atribut
        ) z ON y.enroll_id = z.enroll_id
        WHERE z.enroll_id IS NOT NULL
            $inStatusKontrakRange
            $inDepartment_name
            $inEnrollId
            $inStatusKontrak
            $inStatusAktif
        GROUP BY z.enroll_id
        ORDER BY z.enroll_id
     ");

        // Pisahkan data menjadi dua kelompok
        $data = [];

        foreach ($data_kontrak_non_staff as $item) {
            $item->tanggal_pengurang = date('Y-m-d', strtotime($item->contract_end . ' -14 days'));
            $jumlah_ijin = DB::select("select count(*) as jumlah_ijin from master_data_absen_kehadiran where enroll_id = ? and status_absen = 'I' and tanggal_berjalan BETWEEN ? AND ?", [$item->enroll_id, $item->contract, $item->tanggal_pengurang]);
            $jumlah_sakit = DB::select("select count(*) as jumlah_sakit from master_data_absen_kehadiran where enroll_id = ? and status_absen = 'S' and tanggal_berjalan BETWEEN ? AND ?", [$item->enroll_id, $item->contract, $item->tanggal_pengurang]);
            $jumlah_mangkir = DB::select("select count(*) as jumlah_mangkir from master_data_absen_kehadiran where enroll_id = ? and status_absen = 'M' and tanggal_berjalan BETWEEN ? AND ?", [$item->enroll_id, $item->contract, $item->tanggal_pengurang]);

            $item->jumlah_sakit = $jumlah_sakit[0]->jumlah_sakit;
            $item->jumlah_mangkir = $jumlah_mangkir[0]->jumlah_mangkir;
            $item->jumlah_ijin = $jumlah_ijin[0]->jumlah_ijin;
            $data[] = $item;
        }
        return Excel::download(new ExcelPenilaianKinerjaNonstaff($data), 'Form Penilaian Kinerja Non Staff.xlsx');
    }
    public function download_excel_rencana_adjustment_grade(){
        $loggedAdmin = Auth::guard('admin')->user();
        $loggedEmail = $loggedAdmin->email;
        $email = ['mega@ptnag.com', 'rudy@ptnag.com', 'fadli'];

        $inEnrollId='';
        $inStatusAktif='';
        $inStatusKontrak='';
        $inStatusKontrakRange='';
        $inDepartment_name='';

        if(request()->enroll_id){
            $enroll_id=request()->enroll_id;
            $enroll_id_string=implode(',', $enroll_id);
            $inEnrollId='AND z.enroll_id in ('.$enroll_id_string.')';
        }

        if(request("department_name")){
            $department=request("department_name");
            $inDepartment_name = ' AND z.department_name = "'.$department.'"';
        }

        $today = date('Y-m-d');
        $currentMonth = date('m');
        $currentYear = date('Y');


        $inStatusKontrak='';
        $status_kontrak = request()->status_kontrak;

        if($status_kontrak){
            if($status_kontrak=='One Day'){
                $one_days_later = date('Y-m-d', strtotime('+1 days'));
                $inStatusKontrak='AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) >= "'.$today.'"
                    AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) <= "'.$one_days_later.'"';
            }else if($status_kontrak=='Nine Day'){
                $nine_days_later = date('Y-m-d', strtotime('+9 days'));
                $inStatusKontrak= 'AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) >= "'.$today.'"
                    AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) <= "'.$nine_days_later.'"';
            }else if($status_kontrak=='Thirty Day'){
                $thirty_days_later = date('Y-m-d', strtotime('+30 days'));
                $inStatusKontrak = 'AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) >= "'.$today.'"
                    AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) <= "'.$thirty_days_later.'"';
            }else if($status_kontrak=='Sixty Day'){
                $thirty_day_more = date('Y-m-d',strtotime('+60 days',strtotime(date("Y-m-d")))) . PHP_EOL;
                $inStatusKontrak='AND (
                    CASE
                        WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                        ELSE y.contract_end
                    END
                ) >= "'.$thirty_day_more.'"';
            }
        }

        if (request()->date_range) {
            $daterange1 = explode(" s/d ", request()->date_range);
            $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
            $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
            $bulanAkhir = date('m', strtotime($tanggalSampai));
            $tahunAkhir = date('Y', strtotime($tanggalSampai));
            $inStatusKontrakRange = 'AND (
                CASE
                    WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                    ELSE y.contract_end
                END
            ) >= "'.$tanggalMulai.'"
            AND (
                CASE
                    WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                    ELSE y.contract_end
                END
            ) <= "'.$tanggalSampai.'"';
        }
        $inStatusAktif='AND z.status_aktif = "aktif" AND z.status_staff = "NON STAFF"';

       $data_kontrak_non_staff = DB::select("
        SELECT
            z.enroll_id,
            z.nik,
            z.employee_name,
            z.department_name,
            z.sub_dept_name,
            z.status_aktif,
            z.status_staff,
            z.kode_grade,
            z.tanggal_resign,
            z.status_jabatan,
            z.join_date,
            z.ibu_kandung,
            z.nomor_ktp,
            y.id,
            y.contract,
            y.contract AS contract_last,
            y.contract_end  AS contract_end_last,
            -- logika untuk mengganti contract_end dengan tanggal_resign jika ada
            CASE
                WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                ELSE y.contract_end
            END AS contract_end
        FROM (
            SELECT
                a.enroll_id,
                a.id,
                e.contract,
                e.contract_end
            FROM (
                SELECT
                    enroll_id,
                    MAX(contract) AS contract,
                    MAX(contract_end) AS contract_end
                FROM employee_contract
                GROUP BY enroll_id
            ) e
            INNER JOIN (
                SELECT
                    id,
                    enroll_id,
                    contract,
                    contract_end
                FROM employee_contract
            ) a ON e.enroll_id = a.enroll_id AND e.contract_end = a.contract_end
        ) y
        RIGHT JOIN (
            SELECT
                enroll_id,
                nik,
                employee_name,
                tanggal_resign,
                tempat_lahir,
                nomor_tlpn,
                agama,
                status_kawin,
                join_date,
                nomor_kk,
                pendidikan_terakhir,
                jurusan_pendidikan,
                alamat_rumah,
                department_name,
                sub_dept_name,
                status_aktif,
                status_staff,
                status_jabatan,
                kode_grade,
                ibu_kandung,
                nomor_ktp
            FROM employee_atribut
        ) z ON y.enroll_id = z.enroll_id
        WHERE z.enroll_id IS NOT NULL
            $inStatusKontrakRange
            $inDepartment_name
            $inEnrollId
            $inStatusKontrak
            $inStatusAktif
        GROUP BY z.enroll_id
        ORDER BY z.enroll_id
     ");

        // Pisahkan data menjadi dua kelompok
        $data = [];

        foreach ($data_kontrak_non_staff as $item) {
            $item->tanggal_pengurang = date('Y-m-d', strtotime($item->contract_end . ' -14 days'));
            $jumlah_ijin = DB::select("select count(*) as jumlah_ijin from master_data_absen_kehadiran where enroll_id = ? and status_absen = 'I' and tanggal_berjalan BETWEEN ? AND ?", [$item->enroll_id, $item->contract, $item->tanggal_pengurang]);
            $jumlah_sakit = DB::select("select count(*) as jumlah_sakit from master_data_absen_kehadiran where enroll_id = ? and status_absen = 'S' and tanggal_berjalan BETWEEN ? AND ?", [$item->enroll_id, $item->contract, $item->tanggal_pengurang]);
            $jumlah_mangkir = DB::select("select count(*) as jumlah_mangkir from master_data_absen_kehadiran where enroll_id = ? and status_absen = 'M' and tanggal_berjalan BETWEEN ? AND ?", [$item->enroll_id, $item->contract, $item->tanggal_pengurang]);

            $item->jumlah_sakit = $jumlah_sakit[0]->jumlah_sakit;
            $item->jumlah_mangkir = $jumlah_mangkir[0]->jumlah_mangkir;
            $item->jumlah_ijin = $jumlah_ijin[0]->jumlah_ijin;
            $data[] = $item;
        }
        return Excel::download(new ExcelRencanaAdjustmentSallary($data), 'Form Rencana Adjustment Sallary.xlsx');
    }

    public function download_excel_rekap_penilaian(){
        $loggedAdmin = Auth::guard('admin')->user();
        $loggedEmail = $loggedAdmin->email;
        $email = ['mega@ptnag.com', 'rudy@ptnag.com', 'fadli'];
        $inEnrollId='';
        $inStatusAktif='';
        $inStatusStaff='AND z.status_staff = "STAFF"';
        $inStatusKontrak='';
        $inStatusKontrakRange='';
        $inDepartment_name='';

        if(request()->enroll_id){
            $enroll_id=request()->enroll_id;
            $enroll_id_string=implode(',', $enroll_id);
            $inEnrollId='AND z.enroll_id in ('.$enroll_id_string.')';
        }

        if(request("department_name")){
            $department=request("department_name");
            $inDepartment_name = ' AND z.department_name = "'.$department.'"';
        }
        if(request("statuf_staff")){
            $statuf_staff=request("statuf_staff");
            $inStatusStaff = ' AND z.status_staff = "'.$department.'"';
        }

        $today = date('Y-m-d');
        $currentMonth = date('m');
        $currentYear = date('Y');


        $inStatusKontrak='';
        $status_kontrak = request()->status_kontrak;

        if($status_kontrak){
            if($status_kontrak=='One Day'){
                $one_days_later = date('Y-m-d', strtotime('+1 days'));
                $inStatusKontrak='AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) >= "'.$today.'"
                    AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) <= "'.$one_days_later.'"';
            }else if($status_kontrak=='Nine Day'){
                $nine_days_later = date('Y-m-d', strtotime('+9 days'));
                $inStatusKontrak= 'AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) >= "'.$today.'"
                    AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) <= "'.$nine_days_later.'"';
            }else if($status_kontrak=='Thirty Day'){
                $thirty_days_later = date('Y-m-d', strtotime('+30 days'));
                $inStatusKontrak = 'AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) >= "'.$today.'"
                    AND (
                        CASE
                            WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                            ELSE y.contract_end
                        END
                    ) <= "'.$thirty_days_later.'"';
            }else if($status_kontrak=='Sixty Day'){
                $thirty_day_more = date('Y-m-d',strtotime('+60 days',strtotime(date("Y-m-d")))) . PHP_EOL;
                $inStatusKontrak='AND (
                    CASE
                        WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                        ELSE y.contract_end
                    END
                ) >= "'.$thirty_day_more.'"';
            }
        }

        if (request()->date_range) {
            $daterange1 = explode(" s/d ", request()->date_range);
            $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
            $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
            $bulanAkhir = date('m', strtotime($tanggalSampai));
            $tahunAkhir = date('Y', strtotime($tanggalSampai));
            $inStatusKontrakRange = 'AND (
                CASE
                    WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                    ELSE y.contract_end
                END
            ) >= "'.$tanggalMulai.'"
            AND (
                CASE
                    WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                    ELSE y.contract_end
                END
            ) <= "'.$tanggalSampai.'"';
        }
        $inStatusAktif='AND z.status_aktif = "aktif"';

       $data_kontrak_non_staff = DB::select("
            SELECT
                z.enroll_id,
                z.nik,
                z.employee_name,
                z.status_jabatan,
                z.department_name,
                z.sub_dept_name,
                z.status_aktif,
                z.status_staff,
                z.tanggal_resign,
                z.join_date,
                z.ibu_kandung,
                z.nomor_ktp,
                y.id,
                y.contract,
                y.contract AS contract_last,
                y.contract_end  AS contract_end_last,
                -- logika untuk mengganti contract_end dengan tanggal_resign jika ada
                CASE
                    WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                    ELSE y.contract_end
                END AS contract_end
            FROM (
                SELECT
                    a.enroll_id,
                    a.id,
                    e.contract,
                    e.contract_end
                FROM (
                    SELECT
                        enroll_id,
                        MAX(contract) AS contract,
                        MAX(contract_end) AS contract_end
                    FROM employee_contract
                    GROUP BY enroll_id
                ) e
                INNER JOIN (
                    SELECT
                        id,
                        enroll_id,
                        contract,
                        contract_end
                    FROM employee_contract
                ) a ON e.enroll_id = a.enroll_id AND e.contract_end = a.contract_end
            ) y
            RIGHT JOIN (
                SELECT
                    enroll_id,
                    nik,
                    employee_name,
                    tanggal_resign,
                    tempat_lahir,
                    status_jabatan,
                    nomor_tlpn,
                    agama,
                    status_kawin,
                    join_date,
                    nomor_kk,
                    pendidikan_terakhir,
                    jurusan_pendidikan,
                    alamat_rumah,
                    department_name,
                    sub_dept_name,
                    status_aktif,
                    status_staff,
                    ibu_kandung,
                    nomor_ktp
                FROM employee_atribut
            ) z ON y.enroll_id = z.enroll_id
            WHERE z.enroll_id IS NOT NULL
                $inStatusKontrakRange
                $inDepartment_name
                $inEnrollId
                $inStatusStaff
                $inStatusKontrak
                $inStatusAktif
            GROUP BY z.enroll_id
            ORDER BY z.enroll_id
        ");

        $data = [];

        foreach ($data_kontrak_non_staff as $item) {
            // Ambil data penilaian kinerja sesuai kriteria
            $penilaian = PenilaianKinerja::where('enroll_id', $item->enroll_id)
                ->where('tgl_awal_kontrak', $item->contract_last)
                ->where('tgl_akhir_kontrak', $item->contract_end_last)
                ->first();

            // Gabungkan data penilaian ke data utama
            $item->penilaian_kinerja = $penilaian;

            $data[] = $item;
        }
        return Excel::download(new ExcelRekapPenilaianKinerja($data), 'Rekap Penilaian Kinerja.xlsx');
    }

    public function print_selected_form_penilaian(){
        $enroll_id = request()->enroll_id;
        $inDateRangeContract='';
        $inEnrollIds='';
        $data_penilaian = collect();
        $today = date('Y-m-d');

        if(request()->status_kontrak){
            $status_kontrak=request()->status_kontrak;
             if($status_kontrak=='One Day'){
                $one_days_later = date('Y-m-d', strtotime('+1 days'));
                $inStatusKontrak = 'AND (
                    CASE
                        WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                        ELSE y.contract_end
                    END
                ) >= "'.$today.'"
                AND (
                    CASE
                        WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                        ELSE y.contract_end
                    END
                ) <= "'.$one_days_later.'"';
            }else if($status_kontrak=='Nine Day'){
                $nine_days_later = date('Y-m-d', strtotime('+9 days'));
                $inStatusKontrak = 'AND (
                    CASE
                        WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                        ELSE y.contract_end
                    END
                ) >= "'.$today.'"
                AND (
                    CASE
                        WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                        ELSE y.contract_end
                    END
                ) <= "'.$nine_days_later.'"';
            }else if($status_kontrak=='Thirty Day'){
                $thirty_days_later = date('Y-m-d', strtotime('+30 days'));
                $inStatusKontrak = 'AND (
                    CASE
                        WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                        ELSE y.contract_end
                    END
                ) >= "'.$today.'"
                AND (
                    CASE
                        WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                        ELSE y.contract_end
                    END
                ) <= "'.$thirty_days_later.'"';
            }else if($status_kontrak=='Sixty Day'){
                $thirty_day_more = date('Y-m-d',strtotime('+60 days',strtotime(date("Y-m-d")))) . PHP_EOL;
                $inStatusKontrak='AND (
                    CASE
                        WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                        ELSE y.contract_end
                    END
                ) >= "'.$thirty_day_more.'"';
            }
        }
        if(request()->date_range){
            $daterange1 = explode(" s/d ", request()->date_range);
            $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
            $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
            $inDateRangeContract = 'AND (
                CASE
                    WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                    ELSE y.contract_end
                END
            ) >= "'.$tanggalMulai.'"
            AND (
                CASE
                    WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                    ELSE y.contract_end
                END
            ) <= "'.$tanggalSampai.'"';

            // Ambil semua data penilaian sesuai tanggal
            $data_penilaian = PenilaianKinerja::where('tgl_awal_kontrak', $tanggalMulai)
                ->where('tgl_akhir_kontrak', $tanggalSampai)
                ->get()
                ->keyBy('enroll_id'); // Group berdasarkan enroll_id supaya lebih mudah digabung nanti
        }
        if ($enrollIds = request()->enroll_id) {
            // Pastikan ini array dan aman digunakan
            $escapedIds = implode(',', array_map('intval', $enrollIds)); // sanitize ID to integer
            $inEnrollIds = "AND z.enroll_id IN ($escapedIds)";
        }

        // Jalankan raw SQL
        $data_karyawan = collect(DB::select("
            SELECT
                z.*,
                y.contract,
                CASE
                    WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                    ELSE y.contract_end
                END AS contract_end
            FROM (
                SELECT
                    a.enroll_id,
                    a.id,
                    e.contract,
                    e.contract_end
                FROM (
                    SELECT
                        enroll_id,
                        MAX(contract) AS contract,
                        MAX(contract_end) AS contract_end
                    FROM employee_contract
                    GROUP BY enroll_id
                ) e
                INNER JOIN (
                    SELECT
                        id,
                        enroll_id,
                        contract,
                        contract_end
                    FROM employee_contract
                ) a ON e.enroll_id = a.enroll_id AND e.contract_end = a.contract_end
            ) y
            RIGHT JOIN (
                SELECT
                    enroll_id,
                    nik,
                    employee_name,
                    tanggal_resign,
                    tempat_lahir,
                    nomor_tlpn,
                    agama,
                    status_kawin,
                    status_jabatan,
                    pendidikan_terakhir,
                    jurusan_pendidikan,
                    alamat_rumah,
                    department_name,
                    sub_dept_name,
                    status_aktif,
                    status_staff,
                    ibu_kandung,
                    nomor_ktp,
                    join_date
                FROM employee_atribut
            ) z ON y.enroll_id = z.enroll_id
            WHERE z.enroll_id IS NOT NULL
                $inDateRangeContract
                $inEnrollIds
                $inStatusKontrak
            AND z.status_aktif = 'AKTIF'
            GROUP BY z.enroll_id
            ORDER BY z.enroll_id
        "));

        // Gabungkan data_penilaian ke masing-masing data_karyawan berdasarkan enroll_id
        $data = $data_karyawan->map(function ($karyawan) use ($data_penilaian) {
            $enroll_id = $karyawan->enroll_id;

            if ($data_penilaian->has($enroll_id)) {
                $penilaian = $data_penilaian->get($enroll_id);

                $kejadian = [
                    'sp3_kali' => $penilaian->sp3_kali,
                    'sp2_kali' => $penilaian->sp2_kali,
                    'sp1_kali' => $penilaian->sp1_kali,
                    'kecelakaan_kali' => $penilaian->kecelakaan_kali,
                    'mangkir_kali' => $penilaian->mangkir_kali,
                    'ijin_kali' => $penilaian->ijin_kali,
                ];

                $total = [
                    'sp3_kali' => $penilaian->sp3_kali * 6,
                    'sp2_kali' => $penilaian->sp2_kali * 4,
                    'sp1_kali' => $penilaian->sp1_kali * 2,
                    'kecelakaan_kali' => $penilaian->kecelakaan_kali * 2,
                    'mangkir_kali' => $penilaian->mangkir_kali * 1,
                    'ijin_kali' => $penilaian->ijin_kali * 0.5,
                ];

                $total_pengurangan = array_sum($total);

                $karyawan->penilaian = [
                    'kejadian' => $kejadian,
                    'total' => $total,
                    'total_pengurangan' => $total_pengurangan,
                ];
            } else {
                $karyawan->penilaian = null;
            }

            return $karyawan;
        });

        $pdf = PDF::loadview('hris/hrd/export_nilai_kinerja_karyawan_pdf_all',['data'=>$data]);
        return $pdf->stream('form-nilai-kinerja.pdf');
    }
}
