<?php

namespace App\Http\Controllers\Hris;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\FontMetrics;
use App\Models\EmployeeAtribut;
use Illuminate\Http\Request;

class HRDController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
    }
    public function index(){
        $selectEmployee =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')->groupby('enroll_id')->orderby('employee_name', 'asc')->get();
        $selectNoKTP = EmployeeAtribut::selectRaw('nomor_ktp')->groupby('nomor_ktp')->orderby('nomor_ktp', 'asc')->get();
        return View::make('hris/hrd',compact('selectEmployee','selectNoKTP'), $this->data);
    }
    public function export_pdf_print_sk(){
        $enroll_id=request()->employee;
        $arrayEnrollId=explode(',',$enroll_id);
        $data=EmployeeAtribut::whereIn('enroll_id',$arrayEnrollId)->where(function($query){
            $query->where('sudah_diprint',null)
            ->orWhere('sudah_diprint','!=',1);
        })->get();
        $no_forms=request()->no_form;
        $fileName='all sk kerja.'.date('His').'_'.rand();
        $pdf = PDF::loadView('hris.laporan.all_sk_kerja_karyawan',["data" => $data,"no_form"=>$no_forms])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        return $pdf;
    }
    public function export_pdf_sk_kerja(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $reason=request()->reason;
        $data=DB::select("select*from employee_atribut where enroll_id='$enroll_id'");
        // if($data[0]->tanggal_resign==null){
            $fileName=request()->enroll_id.'_'.date('His');
            $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan',["data" => $data,"no_form"=>$no_form,"reason"=>$reason])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
            return $pdf;
        // }else{
        //     $tanggal_masuk = $data[0]->join_date;
        //     $tanggal_resign = $data[0]->tanggal_resign;
        //     $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_resign))->y;
        //     if($selisih_tahun<1){
        //         $fileName=request()->enroll_id.'_'.date('His');
        //         $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan',["data" => $data,"no_form"=>$no_form])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        //         return $pdf;
        //     }else{
        //         $fileName=request()->enroll_id.'_'.date('His');
        //         $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan_lebih_1_tahun',["data" => $data,"no_form"=>$no_form])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        //         return $pdf;
        //     }
        // }
    }
    public function export_pdf_paklaring(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $reason=request()->reason;
        $data=DB::select("select*from employee_atribut where enroll_id='$enroll_id'");
        if($data[0]->tanggal_resign==null){
            $fileName=request()->enroll_id.'_'.date('His');
            $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan_lebih_1_tahun',["data" => $data,"no_form"=>$no_form,"reason"=>$reason])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
            return $pdf;
        }else{
            $tanggal_masuk = $data[0]->join_date;
            $tanggal_resign = $data[0]->tanggal_resign;
            $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_resign))->y;
            if($selisih_tahun<1){
                $fileName=request()->enroll_id.'_'.date('His');
                $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan_lebih_1_tahun',["data" => $data,"no_form"=>$no_form,"reason"=>$reason])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
                return $pdf;
            }else{
                $fileName=request()->enroll_id.'_'.date('His');
                $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan_lebih_1_tahun',["data" => $data,"no_form"=>$no_form,"reason"=>$reason])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
                return $pdf;
            }
        }
    }
    public function export_pdf_sk_bni(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $item=request()->item;
        $reason=request()->reason;
        $created_date=request()->created_date;
        $data=DB::select("select*from employee_atribut where enroll_id='$enroll_id'");
        // if($data[0]->tanggal_resign==null){
        //     $fileName=request()->enroll_id.'_'.date('His');
        //     $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan',["data" => $data,"no_form"=>$no_form,"reason"=>$reason])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        //     return $pdf;
        // }else{
        //     $tanggal_masuk = $data[0]->join_date;
        //     $tanggal_resign = $data[0]->tanggal_resign;
        //     $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_resign))->y;
        //     if($selisih_tahun<1){
                $fileName='Surat Ketarangan BNI '.$data[0]->employee_name.'_'.request()->enroll_id.'_'.date('His');
                $pdf = PDF::loadView('hris.laporan.sk_bni',["data" => $data,"no_form"=>$no_form,"item"=>$item,"reason"=>$reason,"created_date"=>$created_date])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
                return $pdf;
            // }else{
            //     $fileName=request()->enroll_id.'_'.date('His');
            //     $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan_lebih_1_tahun',["data" => $data,"no_form"=>$no_form,"reason"=>$reason])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
            //     return $pdf;
            // }
        // }
    }
}
