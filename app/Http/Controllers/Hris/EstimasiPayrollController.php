<?php

namespace App\Http\Controllers\Hris;

use App\Exports\RekapPerhitunganPayrollExport;
use App\Http\Controllers\AdminBaseController;
use App\Models\RekapPerhitunganPayroll;
use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Datatables;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Exports\EstimasiPayrollExport;

/**
 * Class RekapPerhitunganPayrollController
 * @package App\Http\Controllers\Hris
 */
class EstimasiPayrollController extends AdminBaseController
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

        $this->latestData = RekapPerhitunganPayroll::latest('updated_at')->first();

        $this->loggedAdmin = Auth::guard('admin')->user();
        return View::make('hris/estimasiperhitunganpayroll', $this->data);
    }


    public function ajax_getperiodepayroll()
    {
        $query =  RekapPerhitunganPayroll::selectRaw('CONCAT(periode_tahun_payroll,"-",periode_bulan_payroll) periode_payroll')
                        ->groupby('periode_payroll')
                        ->orderby('periode_payroll', 'desc')
                        ->get();
        return $query;

    }

    public function ajax_exportexcel(Request $request)
    {
        list($year, $month) = explode('-', $request->periode_payroll);

        $first=RekapPerhitunganPayroll::where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('periode_umk', NULL)->first();
        list($tgl_awal, $tgl_akhir) = explode(' s/d ', $first->periode_kehadiran);

        $updatedAt = Carbon::parse($first->updated_at);

        // Mengambil hanya bagian tanggal dari updated_at
        $tanggalUpdated = $updatedAt->format('Y-m-d');

        $timestamp1 = strtotime($tgl_awal);
        $timestamp2 = strtotime($tgl_akhir);
        $jumlah_hari =(abs($timestamp2 - $timestamp1) / (60 * 60 * 24)+1);

        $jumlah_hari_sabtu_minggu = 0;
        for ($i = strtotime($tgl_awal); $i <= strtotime($tgl_akhir); $i += 86400) {
            if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
                $jumlah_hari_sabtu_minggu++;
            }
        }
        $jumlah_hari_kerja= $jumlah_hari-$jumlah_hari_sabtu_minggu;

        $sisa_hari_kerja = 0;
        for ($i = strtotime($tanggalUpdated); $i <= strtotime($tgl_akhir); $i += 86400) {
            if ((date('N', $i) != 6)&&(date('N', $i) != 7)) {
                $sisa_hari_kerja++;
            }
        }
        $info=[
            'periode_payroll'=>$first->periode_kehadiran,
            'tanggal_awal'=>$tgl_awal,
            'tanggal_akhir'=>$tgl_akhir,
            'tanggal_update'=> $tanggalUpdated,
            'jumlah_hari'=>$jumlah_hari,
            'jumlah_hari_kerja'=>$jumlah_hari_kerja,
            'sisa_hari_kerja'=>$sisa_hari_kerja,
        ];
        $query=RekapPerhitunganPayroll::query();
        //$query->where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('total_kehadiran_net','>','0');
         $query->where('periode_tahun_payroll',$year)->where('periode_bulan_payroll', $month)->where('periode_umk', NULL)
                ->where(function ($query) use ($tgl_awal) {
                    $query->whereNull('tanggal_resign')
                        ->orWhere('tanggal_resign', '>', $tgl_awal);
                });


        if($request->status_staff!==null && $request->status_staff!=='') {
            $query->where('kategori_karyawan',$request->status_staff);
        }
        $query=$query->get();

        $data=[];
        foreach ($query as $key => $value) {
            $gross_salary=($value->total_kehadiran_net+$value->kehadiran_m_estimasi)*$value->upah_per_hari;
            $rp_pot_jam=$value->potongan_dtpc_rupiah+$value->potongan_iks_rupiah;
            $thp=$gross_salary+$value->total_lembur_rupiah+ $value->tunjangan_karyawan_rupiah;
            $potongan=$rp_pot_jam+$value->total_bpjs_tk+$value->total_bpjs_ks;
            $estimasi_thp= $thp-$potongan;
            $data[]=[
                "enroll_id"         => $value->enroll_id,
                "nik"               => $value->nik,
                "employee_name"     => $value->employee_name,
                "nama_bagian"       => $value->nama_bagian,
                "aktif_karyawan"    => $value->aktif_karyawan,
                "kategori_karyawan" => $value->kategori_karyawan,
                "kehadiran_iby"     => $value->kehadiran_iby,
                "kehadiran_itb"     => $value->kehadiran_itb,
                "kehadiran_lby"     => $value->kehadiran_lby,
                "kehadiran_lsm"     => $value->kehadiran_lsm,
                "kehadiran_dt"      => $value->kehadiran_dt,
                "kehadiran_pc"      => $value->kehadiran_pc,
                "kehadiran_dtpc"    => $value->kehadiran_dtpc,
                "kehadiran_m"       => $value->kehadiran_m,
                "kehadiran_r"       => $value->kehadiran_r,
                "kehadiran_tk"      => $value->kehadiran_tk,
                "kehadiran_ok"      => $value->kehadiran_ok,
                "total_kehadiran"   => $value->total_kehadiran,
                "total_kehadiran_net" => $value->total_kehadiran_net,
                "updated_at"        => $value->updated_at,
                "kehadiran_m_estimasi" => $value->kehadiran_m_estimasi,
                "upah_per_bulan"    => $value->upah_per_bulan,
                // "upah_per_hari"     => $value->upah_per_hari,
                'total_estimasi'    => $value->total_kehadiran_net+$value->kehadiran_m_estimasi,
                'gross_salary'      => $gross_salary,
                "tunjangan_karyawan_rupiah" => $value->tunjangan_karyawan_rupiah,
                "total_lembur_rupiah" =>$value->total_lembur_rupiah,
                "total_bpjs_tk"     => $value->total_bpjs_tk,
                "total_bpjs_ks"     => $value->total_bpjs_ks,
                'rp_pot_jam'        => $rp_pot_jam,
                'estimasi_thp'      =>$estimasi_thp
            ];
        }
        // dd($data);
        return Excel::download(new EstimasiPayrollExport($data,$info),'Estimasi_Payroll'.time().'.xlsx');
        // return (new RekapPerhitunganPayrollExport)->exportParams($periode_payroll)->download($fileName);

    }

}
