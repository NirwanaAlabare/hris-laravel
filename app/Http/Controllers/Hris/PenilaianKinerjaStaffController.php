<?php

namespace App\Http\Controllers\Hris;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Dompdf\Options;
use Dompdf\FontMetrics;
use App\Models\EmployeeAtribut;
use App\Models\PenilaianKinerja;
use App\Models\VoucherBazzar;
use App\Imports\KontrakKerjaImport;
use App\Imports\KontrakKerjaImportToDatabase;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\MasterDataAbsenKehadiran;
use App\Exports\exportExcelKontrak;
use App\Models\DasarPotBPJS;
use App\Models\EntertainPengajuanTamu;
use App\Models\DepartmentAll;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;

class PenilaianKinerjaStaffController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
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
            $thirty_day_more = date('Y-m-d',strtotime('+30 days',strtotime(date("Y-m-d")))) . PHP_EOL;
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
        $end_date = Carbon::parse($contract_end)->subDays(30);

        $jumlah_mangkir = MasterDataAbsenKehadiran::where('enroll_id', $enroll_id)
            ->whereBetween('tanggal_berjalan', [$start_date, $end_date])
            ->where('status_absen', 'M')
            ->count();
        $jumlah_ijin = MasterDataAbsenKehadiran::where('enroll_id', $enroll_id)
            ->whereBetween('tanggal_berjalan', [$start_date, $end_date])
            ->where('status_absen', 'I')
            ->count();
        // if($data_penilaian){
        //     $kejadian = [
        //         'sp3_kali' => $data_penilaian->sp3_kali,
        //         'sp2_kali' => $data_penilaian->sp2_kali,
        //         'sp1_kali' => $data_penilaian->sp1_kali,
        //         'kecelakaan_kali' => $data_penilaian->kecelakaan_kali,
        //         'mangkir_kali' => $data_penilaian->mangkir_kali,
        //         'ijin_kali' => $data_penilaian->ijin_kali,
        //     ];

        //     $total = [
        //         'sp3_kali' => $data_penilaian->sp3_kali * 6,
        //         'sp2_kali' => $data_penilaian->sp2_kali * 4,
        //         'sp1_kali' => $data_penilaian->sp1_kali * 2,
        //         'kecelakaan_kali' => $data_penilaian->kecelakaan_kali * 2,
        //         'mangkir_kali' => $data_penilaian->mangkir_kali * 1,
        //         'ijin_kali' => $data_penilaian->ijin_kali * 0.5,
        //     ];

        //     $total_pengurangan = array_sum($total);

        //     // Lalu gabungkan ke dalam data_penilaian
        //     $data_penilaian->kejadian = $kejadian;
        //     $data_penilaian->total = $total;
        //     $data_penilaian->total_pengurangan = $total_pengurangan;
        // }

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
        // dd($request->all());
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
            return response()->json([
                'status' => 'success',
                'msg' => 'Data berhasil ditambahkan',
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

            return response()->json([
                'status' => 'success',
                'msg' => 'Data berhasil diperbarui',
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
        return view('hris/hrd/export_nilai_kinerja_karyawan_pdf', compact('data_penilaian','data_karyawan'));
    }

}
