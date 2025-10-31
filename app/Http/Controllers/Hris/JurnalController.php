<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\Jurnal;
use Illuminate\Support\Facades\View;
use App\Models\RekapPerhitunganPayroll;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Datatables;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Exports\JurnalExport;
use App\Models\DataKoreksiPotongan;
use App\Models\DataKoreksiUpah;
use App\Models\DepartmentAll;

/**
 * Class RekapPerhitunganPayrollController
 * @package App\Http\Controllers\Hris
 */
class JurnalController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Rekap Perhitungan Payroll';
    }

    public function index()
    {
        $this->periode_payroll = $this->ajax_getperiodepayroll();

        $this->latestData = Jurnal::latest('updated_at')->first();

        $this->loggedAdmin = Auth::guard('admin')->user();
        return View::make('hris/jurnal', $this->data);
    }


    public function ajax_getperiodepayroll()
    {
        $query =  Jurnal::selectRaw('periode_payroll')
            ->groupby('periode_payroll')
            ->orderby('periode_payroll', 'desc')
            ->get();
        return $query;
    }
    public function ajax_exportexcel(Request $request)
    {
        $data = Jurnal::where('periode_payroll', $request->periode_payroll)->get();

        return Excel::download(new JurnalExport($data), 'Data_jurnal' . time() . '.xlsx');
    }

    public function update_jurnal(Request $request)
    {
        $periode_payroll = $request->periode_payroll;

        // Ambil 4 karakter pertama dari kiri (tahun)
        $year = substr($periode_payroll, 0, 4);

        // Ambil 2 karakter terakhir dari kanan (bulan)
        $month = substr($periode_payroll, -2);


        //rekap jurnal
        $departement = DepartmentAll::get();
        $data_potongan = $this->potongan($periode_payroll);
        $data_koreksi = $this->koreksi($periode_payroll);
        foreach ($departement as $key => $value) {
            $potongan_bpjs_tk = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '1')->sum('jumlah_rp_potongan');
            $potongan_bpjs_ks = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '2')->sum('jumlah_rp_potongan');
            $potongan_bazzar = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '3')->sum('jumlah_rp_potongan');
            $potongan_kasbon = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '6')->sum('jumlah_rp_potongan');
            $potongan_lain = $data_potongan->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_potongan', '5')->sum('jumlah_rp_potongan');
            // $koreksi_insentif = $data_koreksi->where('sub_dept_id', $value->sub_dept_id)->where('status_jabatan', 'NON STAFF')->where('jenis_koreksi', '2')->sum('jumlah_rp_potongan');
            $payroll = RekapPerhitunganPayroll::where('periode_tahun_payroll', $year)->where('periode_bulan_payroll', $month)->where('periode_umk', null)
                ->where('kategori_karyawan', 'NON STAFF')->where('sub_dept_id', $value->sub_dept_id)
                ->get();


            $rp_cuti_tahuna = 0;
            $potongan_kehadiran_rupiah = $payroll->sum('potongan_kehadiran_rupiah');
            $rp_pot_jam = $payroll->sum('potongan_iks_rupiah') + $payroll->sum('potongan_dtpc_rupiah');
            $iuran_serikat_rupiah = $payroll->sum('iuran_serikat_rupiah');
            $iuran_koperasi = $payroll->sum('iuran_koperasi');

            $gaji_umk = $payroll->sum('upah_per_bulan');
            $koreksi_upah = $payroll->sum('koreksi_upah');
            $potongan_upah = $payroll->sum('potongan_upah');

            $total_gaji = 0;
            $total_tunjangan_karyawan = $payroll->sum('tunjangan_karyawan_rupiah');

            foreach ($payroll as $p) {
                $nama_bank = $p->nama_bank ?? '-';
                $tunai = strtoupper($nama_bank) === 'TUNAI';

                $nilai_bersih = $p->upah_neto_rupiah - $p->jumlah_potongan_rupiah;

                if ($tunai) {
                    // Pembulatan ke atas kelipatan 500
                    $total_upah_thp_rupiah_pembulatan = ceil($nilai_bersih / 500) * 500;
                } else {
                    // Pembulatan ke atas kelipatan 100
                    $total_upah_thp_rupiah_pembulatan = ceil($nilai_bersih / 100) * 100;
                }
                $pembulatan = $total_upah_thp_rupiah_pembulatan - $nilai_bersih;
                $total_gaji += ($p->upah_per_bulan + $pembulatan + $p->koreksi_upah) - $p->potongan_upah;

                if ($p->total_kehadiran_net <= 0 && $p->koreksi_upah_rupiah == 0 && $p->total_lembur_rupiah == 0 && ($p->total_bpjs_tk != 0 || $p->total_bpjs_ks != 0)) {
                    $total_tunjangan_karyawan = '0';
                }
                if ($p->total_kehadiran_net == 0) {
                    $total_tunjangan_karyawan = '0';
                }
            }
            $gaji = $total_gaji;
            $insentif_jabatan = $payroll->sum('insentif_jabatan');
            $premi_karyawan = $payroll->sum('premi_karyawan');
            $koreksi_insentif = $payroll->sum('koreksi_insentif');
            $potongan_insentif = $payroll->sum('potongan_insentif');

            $potongan_piutang = $payroll->sum('potongan_piutang');


            $tunjangan_karyawan_rupiah = ($total_tunjangan_karyawan + $koreksi_insentif + $insentif_jabatan + $premi_karyawan) - $potongan_insentif;
            $total_lembur_rupiah = $payroll->sum('total_lembur_rupiah');
            $bonus = 0;
            $piutang_karyawan = $potongan_piutang;
            $piutang_bazzar = $potongan_bazzar;
            $bpjs_tk = $payroll->sum('total_bpjs_tk');
            $bpjs_ks = $payroll->sum('total_bpjs_ks');
            $potongan = $potongan_lain + $rp_cuti_tahuna + $potongan_kehadiran_rupiah + $rp_pot_jam + $iuran_serikat_rupiah + $iuran_koperasi;
            $gaji_note=($gaji+$tunjangan_karyawan_rupiah+$total_lembur_rupiah+$bonus)-
                        ($piutang_karyawan+$piutang_bazzar+$bpjs_tk+$bpjs_ks+$potongan);
            $gaji_neto = ($gaji + $tunjangan_karyawan_rupiah + $total_lembur_rupiah) - ($piutang_karyawan + $piutang_bazzar + $bpjs_tk + $bpjs_ks + $potongan);

            $data = [
                'kode_bagian' => $value->sub_dept_id,
                'nama_bagian' => $value->sub_dept_name,
                'gaji' => $gaji,
                'tunjangan_karyawan_rupiah' => $tunjangan_karyawan_rupiah,
                'total_lembur_rupiah' => $total_lembur_rupiah,
                'bonus' => $bonus,
                'piutang_karyawan' => $piutang_karyawan,
                'piutang_bazzar' => $piutang_bazzar,
                'bpjs_tk' => $bpjs_tk,
                'bpjs_ks' => $bpjs_ks,
                'potongan' => $potongan,
                'gaji_neto' => $gaji_neto,
                'jumlah_karyawn' => $payroll->count(),
                'periode_payroll' => $periode_payroll,
            ];
            $count = Jurnal::where('kode_bagian', $value->sub_dept_id)->where('periode_payroll', $periode_payroll)->count();

            if ($count) {
                Jurnal::where('kode_bagian', $value->sub_dept_id)->where('periode_payroll', $periode_payroll)->update($data);
            } else {
                Jurnal::create($data);
            }
        }
    }

    public function potongan($periode_payroll)
    {
        $DataKoreksiPotongan = DataKoreksiPotongan::where('periode_tanggal_koreksi', $periode_payroll)->get();
        $data = [];
        foreach ($DataKoreksiPotongan as $key => $value) {
            $data[] = [
                'enroll_id' => $value->enroll_id,
                'nik' => $value->nik,
                'employee_name' => $value->employee_name,
                'site_nirwana_id' => $value->site_nirwana_id,
                'site_nirwana_name' => $value->site_nirwana_name,
                'department_id' => $value->department_id,
                'department_name' => $value->department_name,
                'sub_dept_id' => $value->sub_dept_id,
                'sub_dept_name' => $value->sub_dept_name,
                'jumlah_rp_potongan' => $value->jumlah_rp_potongan,
                'periode_tanggal_koreksi' => $value->periode_tanggal_koreksi,
                'jenis_potongan' => $value->jenis_potongan,
                'keterangan' => $value->keterangan,
                'status_jabatan' => $value->atribut->status_staff,
            ];
        }

        $koreksi_potongan = collect($data);

        return  $koreksi_potongan;
    }
    public function koreksi($periode_payroll)
    {
        $DataKoreksiUpah = DataKoreksiUpah::where('periode_tanggal_koreksi', $periode_payroll)->get();
        $data = [];
        foreach ($DataKoreksiUpah as $key => $value) {
            $data[] = [
                'enroll_id' => $value->enroll_id,
                'nik' => $value->nik,
                'employee_name' => $value->employee_name,
                'site_nirwana_id' => $value->site_nirwana_id,
                'site_nirwana_name' => $value->site_nirwana_name,
                'department_id' => $value->department_id,
                'department_name' => $value->department_name,
                'sub_dept_id' => $value->sub_dept_id,
                'sub_dept_name' => $value->sub_dept_name,
                'jumlah_rp_potongan' => $value->jumlah_rp_potongan,
                'periode_tanggal_koreksi' => $value->periode_tanggal_koreksi,
                'jenis_koreksi' => $value->jenis_koreksi,
                'keterangan' => $value->keterangan,
                'status_jabatan' => $value->atribut->status_staff,
            ];
        }

        $data_koreksi = collect($data);

        return $data_koreksi;
    }
}
