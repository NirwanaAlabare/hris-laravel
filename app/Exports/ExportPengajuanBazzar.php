<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\PengajuanBazzar;
use App\Models\VoucherBazzar;
use DB;

class ExportPengajuanBazzar implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($id,$sub_dept_id,$status,$tanggal)
    {
        $this->id = $id;
        $this->sub_dept_id = $sub_dept_id;
        $this->status = $status;
        $this->tanggal = $tanggal;

    }
    public function view(): View
    {
        if($this->sub_dept_id){
            $data = VoucherBazzar::leftJoin('pengajuan_bazzar', 'pengajuan_bazzar.enroll_id', '=', 'voucher_bazzar.enroll_id')->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'voucher_bazzar.enroll_id')->where('pengajuan_bazzar.tanggal_pengajuan', $this->tanggal)->where('pengajuan_bazzar.sub_dept_id', $this->sub_dept_id)->where('pengajuan_bazzar.status', $this->status)
            ->groupBy('voucher_bazzar.nomor_voucher')
            ->orderBy('voucher_bazzar.enroll_id', 'ASC')
            ->get();

        } else if($this->id){
            $data = VoucherBazzar::leftJoin('pengajuan_bazzar', 'pengajuan_bazzar.enroll_id', '=', 'voucher_bazzar.enroll_id')->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'voucher_bazzar.enroll_id')->where('pengajuan_bazzar.tanggal_pengajuan', $this->tanggal)->where('pengajuan_bazzar.status', $this->status)->where('pengajuan_bazzar.id', $this->id)
            ->groupBy('voucher_bazzar.nomor_voucher')
            ->orderBy('voucher_bazzar.enroll_id', 'ASC')
            ->get();
        }else{
            $data = VoucherBazzar::leftJoin('pengajuan_bazzar', 'pengajuan_bazzar.enroll_id', '=', 'voucher_bazzar.enroll_id')->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'voucher_bazzar.enroll_id')->where('pengajuan_bazzar.status', 'approve')
            ->groupBy('voucher_bazzar.nomor_voucher')
            ->orderBy('voucher_bazzar.enroll_id', 'ASC')
            ->get();
        }
        $total_jumlah = $data->sum('jumlah');

        return view('hris/mutasi-karyawan.bazzar.export_pengajuan_excel', [
            'data' => $data,
            'total_jumlah' => $total_jumlah,
        ]);
    }
}
