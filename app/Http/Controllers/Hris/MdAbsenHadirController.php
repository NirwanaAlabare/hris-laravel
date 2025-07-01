<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\EmployeeAtribut;
use App\Models\DepartmentAll;
use App\Models\RefAbsenIjin;
use App\Models\CheckInOut;
use App\Models\CheckInOutServer;
use App\Models\AttCheckInOut;
use App\Models\LogDataGagalAbsen;
use App\Models\DataKehadiranInOutEdited;
use App\Models\AttUserInfo;
use App\Models\WorkTimeTable;
use Spipu\Html2Pdf\Html2Pdf;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Datatables;
use App\Exports\DepartmentAllExport;
use App\Exports\RekapKehadiranKaryawanExportMdHadir;
use App\Exports\rincianKehadiranKaryawan;
use App\Exports\MasterDataAbsenKehadiranExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\DataJadwalKerjaLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use \avadim\FastExcelLaravel\Excel as FastExcel;
use PDF;

/**
 * Class MdAbsenHadirController
 * @package App\Http\Controllers\Hris
 */
class MdAbsenHadirController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Data Kehadiran Karyawan';
    }
    public function export_pdf(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');
        $tanggal = request()->tanggal_awal;
        $tanggal_array=explode(" s/d ",$tanggal);
        $tanggal_awal = $tanggal_array[0];
        $tanggal_akhir = $tanggal_array[1];
        $tanggal_awal_absen = Carbon::parse($tanggal_awal)->translatedFormat('d F Y');
        $tanggal_akhir_absen = Carbon::parse($tanggal_akhir)->translatedFormat('d F Y');
        $selectedEnrollId=request()->employee;
        $selectedFactory=request()->factory;
        $inEnrollId='';
        if($selectedEnrollId){
            $allEnroll_id= '('.$selectedEnrollId.')';
            $inEnrollId = ' AND enroll_id IN '.$allEnroll_id.'';
        }
        $selectedDepartment=request()->department;
        $inDepartment='';
        if($selectedDepartment){
            $inDepartment = ' AND department_name = "'.$selectedDepartment.'"';
        }
        $selectedSection=request()->section;
        $inSection='';
        if($selectedSection){
            $inSection = ' AND sub_dept_name = "'.$selectedSection.'"';
        }
        $selectedStatusStaff=request()->status_staff;
        $inStatusStaff='';
        if($selectedStatusStaff){
            $inStatusStaff = ' AND status_staff = "'.$selectedStatusStaff.'"';
        }
        $selectedFactory=request()->factory;
        $inFactory='';
        if($selectedFactory){
            $inFactory = ' AND site_nirwana_id = "'.$selectedFactory.'"';
        }
        $tanggal_awal_absen = Carbon::parse($tanggal_awal)->translatedFormat('d F Y');
        $tanggal_akhir_absen = Carbon::parse($tanggal_akhir)->translatedFormat('d F Y');
        $employee=EmployeeAtribut::where(function ($query)use($tanggal_awal,$tanggal_akhir){
            $query->where(function ($querys)use($tanggal_akhir){
                $querys->where('status_aktif','AKTIF')
                ->where('join_date','<=',$tanggal_akhir);
            })->orWhere('tanggal_resign','>=',$tanggal_awal);
        })->with(['absensi' => function ($query) use ($tanggal_awal,$tanggal_akhir) {
            $query->where('tanggal_berjalan', '>=', $tanggal_awal)
            ->where('tanggal_berjalan','<=',$tanggal_akhir)->orderBy('tanggal_berjalan');
        }])->with(['rekap_lembur'=>function($query)use($tanggal_awal,$tanggal_akhir){
            $query->where('tanggal_berjalan','>=',$tanggal_awal)
            ->where('tanggal_berjalan','<=',$tanggal_akhir);
        }])->whereRaw('status_aktif is not null '.$inEnrollId.''.$inDepartment.''.$inSection.''.$inStatusStaff.''.$inFactory)->get();
        $jumlah_absen=[];
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');
        $null_absen='';
        $ln_absen='LN';
        array_push($IBY,$null_absen,$ln_absen);
        $tidak_bayar=RefAbsenIjin::where('kode_ijin_payroll','ITB')->where('kode_absen_ijin','!=','M')->where('kode_absen_ijin','!=','IKS')->get()->toArray();
        $ITB=array_column($tidak_bayar,'kode_absen_ijin');
        array_push($ITB,'R');
        $tl_absen='TL';
        $m_absen='M';
        array_push($ITB,$tl_absen,$m_absen);
        foreach($employee as $emp){
            $jumlah_menit[]=[
                'enroll_id'=>$emp->enroll_id,
                'employee_name'=>$emp->employee_name,
                'total_menit_absen_dt'=>$emp->absensi->sum('jumlah_menit_absen_dt'),
                'total_menit_absen_pc'=>$emp->absensi->sum('jumlah_menit_absen_pc'),
                'total_menit_lembur_1'=>$emp->rekap_lembur->sum('lembur_1'),
                'total_menit_lembur_2'=>$emp->rekap_lembur->sum('lembur_2'),
                'total_menit_lembur_3'=>$emp->rekap_lembur->sum('lembur_3'),
                'total_menit_lembur_4'=>$emp->rekap_lembur->sum('lembur_4'),
                'total_menit_lembur_1234'=>$emp->rekap_lembur->sum('total_lembur_1234'),
            ];
            $jumlah_absen[]=[
                'enroll_id'=>$emp->enroll_id,
                'employee_name'=>$emp->employee_name,
                'hari_kerja'=>$emp->absensi->whereIn('status_absen',$IBY)->whereNotIn('kode_hari',[5,6])->count(),
                'hari_absen'=>$emp->absensi->whereIn('status_absen',$ITB)->whereNotIn('kode_hari',[5,6])->count()
            ];
        }
        if(str_contains($selectedEnrollId, ',') || $selectedEnrollId==null){
            $fileName=substr($tanggal_akhir,2,2).substr($tanggal_akhir,5,2).' TNA DETAIL';
        }else{
            $employee_name=EmployeeAtribut::where('enroll_id',$selectedEnrollId)->pluck('employee_name')[0];
            $fileName=substr($tanggal_akhir,2,2).substr($tanggal_akhir,5,2).' '.$selectedEnrollId.' '.$employee_name;
        }
        $pdf = PDF::loadView('hris.Laporan.rincian_kehadiran_karyawan',["tanggal_awal_absen" => $tanggal_awal_absen, "tanggal_akhir_absen" => $tanggal_akhir_absen, "employee" => $employee, "jumlah_menit" => $jumlah_menit, "jumlah_absen" => $jumlah_absen])->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
    }
    public function view_excel(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '10240000000000000M');
        $tanggal = request()->tanggal_awal;
        $tanggal_array=explode(" s/d ",$tanggal);
        $tanggal_awal = $tanggal_array[0];
        $tanggal_akhir = $tanggal_array[1];
        $tanggal_awal_absen = Carbon::parse($tanggal_awal)->translatedFormat('d F Y');
        $tanggal_akhir_absen = Carbon::parse($tanggal_akhir)->translatedFormat('d F Y');
        $selectedEnrollId=request()->employee;
        $selectedFactory=request()->factory;
        $inEnrollId='';
        if($selectedEnrollId){
            $enroll_id = implode(", ", $selectedEnrollId);
            $allEnroll_id= '('.$enroll_id.')';
            $inEnrollId = ' AND enroll_id IN '.$allEnroll_id.'';
        }
        $selectedDepartment=request()->department;
        $inDepartment='';
        if($selectedDepartment){
            $inDepartment = ' AND department_name = "'.$selectedDepartment.'"';
        }
        $selectedSection=request()->section;
        $inSection='';
        if($selectedSection){
            $inSection = ' AND sub_dept_name = "'.$selectedSection.'"';
        }
        $selectedStatusStaff=request()->status_staff;
        $inStatusStaff='';
        if($selectedStatusStaff){
            $inStatusStaff = ' AND status_staff = "'.$selectedStatusStaff.'"';
        }
        $selectedFactory=request()->factory;
        $inFactory='';
        if($selectedFactory){
            $inFactory = ' AND site_nirwana_id = "'.$selectedFactory.'"';
        }
        $tanggal_awal_absen = Carbon::parse($tanggal_awal)->translatedFormat('d F Y');
        $tanggal_akhir_absen = Carbon::parse($tanggal_akhir)->translatedFormat('d F Y');
        $employee=EmployeeAtribut::where(function ($query)use($tanggal_awal,$tanggal_akhir){
            $query->where(function ($querys)use($tanggal_akhir){
                $querys->where('status_aktif','AKTIF')
                ->where('join_date','<=',$tanggal_akhir);
            })->orWhere('tanggal_resign','>=',$tanggal_awal);
        })->with(['absensi' => function ($query) use ($tanggal_awal,$tanggal_akhir) {
            $query->where('tanggal_berjalan', '>=', $tanggal_awal)
            ->where('tanggal_berjalan','<=',$tanggal_akhir);
        }])->with(['rekap_lembur'=>function($query)use($tanggal_awal,$tanggal_akhir){
            $query->where('tanggal_berjalan','>=',$tanggal_awal)
            ->where('tanggal_berjalan','<=',$tanggal_akhir);
        }])->whereRaw('status_aktif is not null '.$inEnrollId.''.$inDepartment.''.$inSection.''.$inStatusStaff.''.$inFactory)->get();
        $jumlah_absen=[];
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');
        $null_absen='';
        $ln_absen='LN';
        array_push($IBY,$null_absen,$ln_absen);
        $tidak_bayar=RefAbsenIjin::where('kode_ijin_payroll','ITB')->where('kode_absen_ijin','!=','M')->where('kode_absen_ijin','!=','IKS')->get()->toArray();
        $ITB=array_column($tidak_bayar,'kode_absen_ijin');
        $tl_absen='TL';
        $m_absen='M';
        array_push($ITB,$tl_absen,$m_absen);
        foreach($employee as $emp){
            $jumlah_menit[]=[
                'enroll_id'=>$emp->enroll_id,
                'employee_name'=>$emp->employee_name,
                'total_menit_absen_dt'=>$emp->absensi->sum('jumlah_menit_absen_dt'),
                'total_menit_absen_pc'=>$emp->absensi->sum('jumlah_menit_absen_pc'),
                'total_menit_lembur_1'=>$emp->rekap_lembur->sum('lembur_1'),
                'total_menit_lembur_2'=>$emp->rekap_lembur->sum('lembur_2'),
                'total_menit_lembur_3'=>$emp->rekap_lembur->sum('lembur_3'),
                'total_menit_lembur_4'=>$emp->rekap_lembur->sum('lembur_4'),
                'total_menit_lembur_1234'=>$emp->rekap_lembur->sum('total_lembur_1234'),
            ];
            $jumlah_absen[]=[
                'enroll_id'=>$emp->enroll_id,
                'employee_name'=>$emp->employee_name,
                'hari_kerja'=>$emp->absensi->whereIn('status_absen',$IBY)->whereNotIn('kode_hari',[5,6])->count(),
                'hari_absen'=>$emp->absensi->whereIn('status_absen',$ITB)->whereNotIn('kode_hari',[5,6])->count()
            ];
        }
        $fileName = 'DataRincianKehadiran.xlsx';
        // return view('hris.Laporan.rincian_kehadiran_karyawan',compact("tanggal_awal_absen", "tanggal_akhir_absen", "employee", "jumlah_absen"));
        $response= Excel::download(new rincianKehadiranKaryawan($tanggal_awal_absen,$tanggal_akhir_absen,$employee,$jumlah_menit,$jumlah_absen), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;
    }
    public function rekap_kehadiran(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '10240000000000000M');
        $tanggal = request()->tanggal_awal;
        $tanggal_array=explode(" s/d ",$tanggal);
        $tanggal_awal = $tanggal_array[0];
        $tanggal_akhir = $tanggal_array[1];
        $selectedEnrollId=request()->employee;
        $selectedFactory=request()->factory;
        $inEnrollId='';
        if($selectedEnrollId){
            $enroll_id = implode(", ", $selectedEnrollId);
            $allEnroll_id= '('.$enroll_id.')';
            $inEnrollId = 'master_data_absen_kehadiran.enroll_id IN '.$allEnroll_id.'';
        }
        $selectedDepartment=request()->department;
        $inDepartment='';
        if($selectedDepartment){
            $inDepartment = 'employee_atribut.department_name = "'.$selectedDepartment.'"';
        }
        $selectedSection=request()->section;
        $inSection='';
        if($selectedSection){
            $inSection = 'employee_atribut.sub_dept_name = "'.$selectedSection.'"';
        }
        $selectedStatusStaff=request()->status_staff;
        $inStatusStaff='';
        if($selectedStatusStaff){
            $inStatusStaff = 'employee_atribut.status_staff = "'.$selectedStatusStaff.'"';
        }
        $selectedFactory=request()->factory;
        $inFactory='';
        if($selectedFactory){
            $inFactory = 'employee_atribut.site_nirwana_id = "'.$selectedFactory.'"';
        }

        $fileName = 'RekapKehadiranKaryawan_' . time() . '.xlsx';
        return (new RekapKehadiranKaryawanExportMdHadir)->exportParams($tanggal_awal, $tanggal_akhir, $inEnrollId, $inDepartment, $inSection, $inStatusStaff, $inFactory)->download($fileName);
    }

    public function import_datahadir(Request $request){
        $data=Excel::toArray([],$request->file('excel_file'));
        $array_data=[];
        $h=0;
        for($i=3;$i<count($data[0]);$i++){
            $h++;
            $tanggal_berjalan='';
            if(is_int($data[0][$i][2])==true){
                $excel_date = $data[0][$i][2];
                $unix_date = ($excel_date - 25569) * 86400;
                $excel_date = 25569 + ($unix_date / 86400);
                $unix_date = ($excel_date - 25569) * 86400;
                $tanggal_berjalan=gmdate("Y-m-d", $unix_date);
            }else{
                $tanggal_berjalan=$data[0][$i][2];
            }
            if($data[0][$i][6]==null){
                $jadwal_masuk_kerja='-';
            }else{
                if(is_string($data[0][$i][6])==false){
                    $the_values = $data[0][$i][6];
                    $totals = ($the_values * 24)+0.0001;
                    $hourss = floor($totals);
                    $hours_displays =sprintf("%02d", $hourss);
                    $minute_fractions = $totals - $hourss;
                    $minutess = $minute_fractions * 60;
                    $minutes_displays =sprintf("%02d", $minutess);
                    $jadwal_masuk_kerja = $hours_displays . ":" . $minutes_displays;
                }else{
                    $jadwal_masuk_kerja=$data[0][$i][6];
                }
            }
            if($data[0][$i][7]==null){
                $jadwal_pulang_kerja='-';
            }else{
                if(is_string($data[0][$i][7])==false){
                    $the_valuess = $data[0][$i][7];
                    $totalss = ($the_valuess * 24)+0.0001;
                    $hoursss = floor($totalss);
                    $hours_displayss =sprintf("%02d", $hoursss);
                    $minute_fractionss = $totalss - $hoursss;
                    $minutesss = $minute_fractionss * 60;
                    $minutes_displayss =sprintf("%02d", $minutesss);
                    $jadwal_pulang_kerja = $hours_displayss . ":" . $minutes_displayss;
                }else{
                    $jadwal_pulang_kerja=$data[0][$i][7];
                }
            }
            if($data[0][$i][8]==null){
                $absen_masuk_kerja='-';
            }else{
                if(is_string($data[0][$i][8])==false){
                    $the_valuesss = $data[0][$i][8];
                    $totalsss = ($the_valuesss * 24)+0.0001;
                    $hourssss = floor($totalsss);
                    $hours_displaysss =sprintf("%02d", $hourssss);
                    $minute_fractionsss = $totalsss - $hourssss;
                    $minutessss = $minute_fractionsss * 60;
                    $minutes_displaysss =sprintf("%02d", $minutessss);
                    $absen_masuk_kerja = $hours_displaysss . ":" . $minutes_displaysss;
                }else{
                    $absen_masuk_kerja=$data[0][$i][8];
                }
            }
            if($data[0][$i][9]==null){
                $absen_pulang_kerja='-';
            }else{
                if(is_string($data[0][$i][9])==false){
                    $the_valuessss = $data[0][$i][9];
                    $totalssss = ($the_valuessss * 24)+0.0001;
                    $hoursssss = floor($totalssss);
                    $hours_displayssss =sprintf("%02d", $hoursssss);
                    $minute_fractionssss = $totalssss - $hoursssss;
                    $minutesssss = $minute_fractionssss * 60;
                    $minutes_displayssss =sprintf("%02d", $minutesssss);
                    $absen_pulang_kerja = $hours_displayssss . ":" . $minutes_displayssss;
                }else{
                    $absen_pulang_kerja=$data[0][$i][9];
                }
            }
            $array_data[$h]=[
                'no'=>$h,
                'nik'=>$data[0][$i][0],
                'enroll_id'=>$data[0][$i][1],
                'tanggal_berjalan'=>$tanggal_berjalan,
                'employee_name'=>$data[0][$i][4],
                'kerja_libur'=>$data[0][$i][5],
                'jadwal_masuk_kerja'=>$jadwal_masuk_kerja,
                'jadwal_pulang_kerja'=>$jadwal_pulang_kerja,
                'absen_masuk_kerja'=>$absen_masuk_kerja,
                'absen_pulang_kerja'=>$absen_pulang_kerja,
                'jumlah_data'=>count($data[0])-3
            ];
        }
        $fix_array_data=[];
        foreach($array_data as $key=>$value){
            if($value['enroll_id']==null || $value['tanggal_berjalan']==null){
                continue;
            }
            $fix_array_data[$key]=[
                'no'=>$h,
                'nik'=>$value['nik'],
                'enroll_id'=>$value['enroll_id'],
                'tanggal_berjalan'=>$value['tanggal_berjalan'],
                'employee_name'=>$value['employee_name'],
                'kerja_libur'=>$value['kerja_libur'],
                'jadwal_masuk_kerja'=>$value['jadwal_masuk_kerja'],
                'jadwal_pulang_kerja'=>$value['jadwal_pulang_kerja'],
                'absen_masuk_kerja'=>$value['absen_masuk_kerja'],
                'absen_pulang_kerja'=>$value['absen_pulang_kerja'],
                'jumlah_data'=>$value['jumlah_data'],
            ];
        }
        $sort_fix_array_data=[];
        $j=0;
        foreach($fix_array_data as $key=>$value){
            $j++;
            $sort_fix_array_data[$key]=[
                'no'=>$j,
                'nik'=>$value['nik'],
                'enroll_id'=>$value['enroll_id'],
                'tanggal_berjalan'=>$value['tanggal_berjalan'],
                'employee_name'=>$value['employee_name'],
                'kerja_libur'=>$value['kerja_libur'],
                'jadwal_masuk_kerja'=>$value['jadwal_masuk_kerja'],
                'jadwal_pulang_kerja'=>$value['jadwal_pulang_kerja'],
                'absen_masuk_kerja'=>$value['absen_masuk_kerja'],
                'absen_pulang_kerja'=>$value['absen_pulang_kerja'],
                'jumlah_data'=>count($fix_array_data),
            ];
        }
        return $sort_fix_array_data;
    }

    public function importing_datahadir(Request $request){
        $data=Excel::toArray([],$request->file('excel_file'));
        $array_data=[];
        $h=0;
        for($i=3;$i<count($data[0]);$i++){
            $h++;
            $tanggal_berjalan='';
            if(is_int($data[0][$i][2])==true){
                $excel_date = $data[0][$i][2];
                $unix_date = ($excel_date - 25569) * 86400;
                $excel_date = 25569 + ($unix_date / 86400);
                $unix_date = ($excel_date - 25569) * 86400;
                $tanggal_berjalan=gmdate("Y-m-d", $unix_date);
            }else{
                $tanggal_berjalan=$data[0][$i][2];
            }
            if($data[0][$i][6]==null){
                $jadwal_masuk_kerja=null;
            }else{
                if(is_string($data[0][$i][6])==false){
                    $the_values = $data[0][$i][6];
                    $totals = ($the_values * 24)+0.0001;
                    $hourss = floor($totals);
                    $hours_displays =sprintf("%02d", $hourss);
                    $minute_fractions = $totals - $hourss;
                    $minutess = $minute_fractions * 60;
                    $minutes_displays =sprintf("%02d", $minutess);
                    $jadwal_masuk_kerja = $hours_displays . ":" . $minutes_displays;
                }else{
                    $jadwal_masuk_kerja=$data[0][$i][6];
                }
            }
            if($data[0][$i][7]==null){
                $jadwal_pulang_kerja=null;
            }else{
                if(is_string($data[0][$i][7])==false){
                    $the_valuess = $data[0][$i][7];
                    $totalss = ($the_valuess * 24)+0.0001;
                    $hoursss = floor($totalss);
                    $hours_displayss =sprintf("%02d", $hoursss);
                    $minute_fractionss = $totalss - $hoursss;
                    $minutesss = $minute_fractionss * 60;
                    $minutes_displayss =sprintf("%02d", $minutesss);
                    $jadwal_pulang_kerja = $hours_displayss . ":" . $minutes_displayss;
                }else{
                    $jadwal_pulang_kerja=$data[0][$i][7];
                }
            }
            if($data[0][$i][8]==null){
                $absen_masuk_kerja=null;
            }else{
                if(is_string($data[0][$i][8])==false){
                    $the_valuesss = $data[0][$i][8];
                    $totalsss = ($the_valuesss * 24)+0.0001;
                    $hourssss = floor($totalsss);
                    $hours_displaysss =sprintf("%02d", $hourssss);
                    $minute_fractionsss = $totalsss - $hourssss;
                    $minutessss = $minute_fractionsss * 60;
                    $minutes_displaysss =sprintf("%02d", $minutessss);
                    $absen_masuk_kerja = $hours_displaysss . ":" . $minutes_displaysss;
                }else{
                    $absen_masuk_kerja=$data[0][$i][8];
                }
            }
            if($data[0][$i][9]==null){
                $absen_pulang_kerja=null;
            }else{
                if(is_string($data[0][$i][9])==false){
                    $the_valuessss = $data[0][$i][9];
                    $totalssss = ($the_valuessss * 24)+0.0001;
                    $hoursssss = floor($totalssss);
                    $hours_displayssss =sprintf("%02d", $hoursssss);
                    $minute_fractionssss = $totalssss - $hoursssss;
                    $minutesssss = $minute_fractionssss * 60;
                    $minutes_displayssss =sprintf("%02d", $minutesssss);
                    $absen_pulang_kerja = $hours_displayssss . ":" . $minutes_displayssss;
                }else{
                    $absen_pulang_kerja=$data[0][$i][9];
                }
            }
            $array_data[$h]=[
                'no'=>$h,
                'enroll_id'=>$data[0][$i][1],
                'tanggal_berjalan'=>$tanggal_berjalan,
                'kerja_libur'=>$data[0][$i][5],
                'jadwal_masuk_kerja'=>$jadwal_masuk_kerja,
                'jadwal_pulang_kerja'=>$jadwal_pulang_kerja,
                'absen_masuk_kerja'=>$absen_masuk_kerja,
                'absen_pulang_kerja'=>$absen_pulang_kerja,
                'jumlah_data'=>count($data[0])-3
            ];
        }
        $fix_array_data=[];
        foreach($array_data as $key=>$value){
            if($value['enroll_id']==null || $value['tanggal_berjalan']==null){
                continue;
            }
            $absen_masuk_kerja='';
            if(isset(MasterDataAbsenKehadiran::where('enroll_id',$value['enroll_id'])->where('tanggal_berjalan',$value['tanggal_berjalan'])->pluck('absen_masuk_kerja')[0])){
                $absen_masuk_kerja=MasterDataAbsenKehadiran::where('enroll_id',$value['enroll_id'])->where('tanggal_berjalan',$value['tanggal_berjalan'])->pluck('absen_masuk_kerja')[0];
            }
            $absen_pulang_kerja='';
            if(isset(MasterDataAbsenKehadiran::where('enroll_id',$value['enroll_id'])->where('tanggal_berjalan',$value['tanggal_berjalan'])->pluck('absen_pulang_kerja')[0])){
                $absen_pulang_kerja=MasterDataAbsenKehadiran::where('enroll_id',$value['enroll_id'])->where('tanggal_berjalan',$value['tanggal_berjalan'])->pluck('absen_pulang_kerja')[0];
            }
            if($absen_masuk_kerja==$value['absen_masuk_kerja'] && $absen_pulang_kerja==$value['absen_pulang_kerja']){
                $fix_array_data[$key]=[
                    'enroll_id'=>$value['enroll_id'],
                    'tanggal_berjalan'=>$value['tanggal_berjalan'],
                    'mulai_jam_kerja'=>$value['jadwal_masuk_kerja'],
                    'akhir_jam_kerja'=>$value['jadwal_pulang_kerja'],
                    'absen_masuk_kerja'=>$value['absen_masuk_kerja'],
                    'absen_pulang_kerja'=>$value['absen_pulang_kerja'],
                ];
            }else if($absen_masuk_kerja!=$value['absen_masuk_kerja'] || $absen_pulang_kerja!=$value['absen_pulang_kerja']){
                $fix_array_data[$key]=[
                    'enroll_id'=>$value['enroll_id'],
                    'tanggal_berjalan'=>$value['tanggal_berjalan'],
                    'mulai_jam_kerja'=>$value['jadwal_masuk_kerja'],
                    'akhir_jam_kerja'=>$value['jadwal_pulang_kerja'],
                    'absen_masuk_kerja'=>$value['absen_masuk_kerja'],
                    'absen_pulang_kerja'=>$value['absen_pulang_kerja'],
                    'operator'=>'inject absen by excel file'
                ];
            }else{
                $fix_array_data[$key]=[
                    'enroll_id'=>$value['enroll_id'],
                    'tanggal_berjalan'=>$value['tanggal_berjalan'],
                    'mulai_jam_kerja'=>$value['jadwal_masuk_kerja'],
                    'akhir_jam_kerja'=>$value['jadwal_pulang_kerja'],
                    'absen_masuk_kerja'=>$value['absen_masuk_kerja'],
                    'absen_pulang_kerja'=>$value['absen_pulang_kerja'],
                ];
            }
        }
        foreach($fix_array_data as $key=>$value){
            $result=array_filter($value);
            MasterDataAbsenKehadiran::where('enroll_id',$value['enroll_id'])->where('tanggal_berjalan',$value['tanggal_berjalan'])->update($result);
            $absen_TL=MasterDataAbsenKehadiran::where('enroll_id',$value['enroll_id'])->where('tanggal_berjalan',$value['tanggal_berjalan'])->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja','!=',null)->whereIn('status_absen',['TL','M'])->get();
            if($absen_TL){
                MasterDataAbsenKehadiran::where('enroll_id',$value['enroll_id'])->where('tanggal_berjalan',$value['tanggal_berjalan'])->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja','!=',null)->whereIn('status_absen',['TL','M'])->update([
                    'status_absen'=>null
                ]);
            }
            $absen_M=MasterDataAbsenKehadiran::where('enroll_id',$value['enroll_id'])->where('tanggal_berjalan',$value['tanggal_berjalan'])->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja',null)->whereIn('status_absen',['M'])->get();
            if($absen_M){
                MasterDataAbsenKehadiran::where('enroll_id',$value['enroll_id'])->where('tanggal_berjalan',$value['tanggal_berjalan'])->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja',null)->whereIn('status_absen',['M'])->update([
                    'status_absen'=>'TL'
                ]);
            }

            //update dtpc
            $masterAbsen=MasterDataAbsenKehadiran::where('enroll_id',$value['enroll_id'])->where('tanggal_berjalan',$value['tanggal_berjalan'])->get();
            foreach ($masterAbsen as $k => $v) {
                $jadwal_in=$v->mulai_jam_kerja;
                $jadwal_out=$v->akhir_jam_kerja;

                $absen_in=$v->absen_masuk_kerja;
                $absen_out=$v->absen_pulang_kerja;

                $durasi_kerja=date_diff(date_create($jadwal_in),date_create($jadwal_out));
                $durasi_kerja_menit=$durasi_kerja->i +($durasi_kerja->h*60);

                $DT = date_diff(date_create($jadwal_in),date_create($absen_in));
                $PC = date_diff(date_create($jadwal_out),date_create($absen_out));
                if( $jadwal_in!=null && $absen_in!=null && $absen_in>$jadwal_in && $v->status_absen==null){
                    $total_DT1 = $DT->i +($DT->h*60);
                    if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                        if($absen_in >'13:00:00'){
                            $total_DT=$total_DT1-60;
                        }
                        else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                            $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                            $selisih_menit = round($selisih_menit / 60);
                            $total_DT=$total_DT1-$selisih_menit;
                        }
                        else {
                            $total_DT=$total_DT1;
                        }
                    }else if($jadwal_in=='06:00:00'){
                        if($absen_in >'11:00:00'){
                            $total_DT=$total_DT1-60;
                        }else if($absen_in >'10:00:00' && $absen_in <='11:00:00'){
                            $selisih_menit = strtotime($absen_in) - strtotime('11:00:00');
                            $selisih_menit = round($selisih_menit / 60);
                            $total_DT=$total_DT1-$selisih_menit;
                        }else {
                            $total_DT=$total_DT1;
                        }
                    }else{
                        $total_DT=$total_DT1;
                    }
                    $total_DT = $total_DT < 480 ? $total_DT : 480;
                }else{
                    $total_DT=0;
                }
                if($absen_out<$jadwal_in){
                    $total_PC=0;
                }else if( $jadwal_out !=null && $absen_out !=null && $absen_out<$jadwal_out && ($v->status_absen==null || $v->status_absen=='IKS')){
                    $total_PC1 = $PC->i +($PC->h*60);
                    if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                        if($absen_out <='12:00:00'){
                            $total_PC=$total_PC1-60;
                        }else if($absen_out >'12:00:00' && $absen_out <='13:00:00'){
                            $selisih_menit = strtotime('13:00:00') - strtotime($absen_out);
                            $selisih_menit = round($selisih_menit / 60);
                            $total_PC=$total_PC1-$selisih_menit;
                        }else {
                            $total_PC=$total_PC1;
                        }
                    }else if($jadwal_in=='06:00:00'){
                        if($absen_out <='10:00:00'){
                            $total_PC=$total_PC1-60;
                        }
                        else if($absen_out >'10:00:00' && $absen_out <='11:00:00'){
                            $selisih_menit = strtotime('11:00:00') - strtotime($absen_out);
                            $selisih_menit = round($selisih_menit / 60);
                            $total_PC=$total_PC1-$selisih_menit;
                        }
                        else {
                            $total_PC=$total_PC1;
                        }
                    }else{
                        $total_PC=$total_PC1;
                    }
                    $total_PC = $total_PC < 480 ? $total_PC : 480;
                }else{
                    $total_PC=0;
                }

                $jumlah_menit_absen_dtpc=$total_DT+$total_PC;
                if( $absen_in!=null && $absen_out !=null){
                    $jumlah_absen_menit_kerja=$durasi_kerja_menit-$jumlah_menit_absen_dtpc;
                }
                else{
                    $jumlah_absen_menit_kerja=0;
                }

                $jumlah_menit_absen_dtpc=$total_DT+$total_PC;
                $data_update=[
                    'jumlah_menit_absen_dtpc'=>$jumlah_menit_absen_dtpc,
                    'jumlah_absen_menit_kerja'=>$durasi_kerja_menit-$jumlah_menit_absen_dtpc,
                    'jumlah_menit_absen_dt'=>$total_DT,
                    'jumlah_menit_absen_pc'=>$total_PC,
                ];
                MasterDataAbsenKehadiran::where('tanggal_berjalan', $v->tanggal_berjalan)
                            ->where('enroll_id', $v->enroll_id)->update($data_update);
            }
        }
    }

    public function update_dtpc(){
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');
        $masterAbsen=MasterDataAbsenKehadiran::where('tanggal_berjalan',date('2024-01-02'))->get();
        foreach ($masterAbsen as $k => $v) {
            $enroll_id=$v->enroll_id;
            $employee_name=$v->employee_name;
            $jadwal_in=$v->mulai_jam_kerja;
            $jadwal_out=$v->akhir_jam_kerja;
            $absen_in=$v->absen_masuk_kerja;
            $absen_out=$v->absen_pulang_kerja;
            $total_dt_real='';
            $total_pc_real='';
            $status_absen=$v->status_absen;
            if($jadwal_in!=null && $absen_in!=null){
                $dt=date_diff(date_create($jadwal_in),date_create($absen_in));
            }else{
                $dt=0;
            }
            if($jadwal_out!=null && $absen_out!=null){
                $pc=date_diff(date_create($jadwal_out),date_create($absen_out));
            }else{
                $pc=0;
            }
            if($v->mulai_jam_kerja!=null && $v->akhir_jam_kerja!=null){
                $durasi_kerja=date_diff(date_create($jadwal_in),date_create($jadwal_out));
                $durasi_kerja_menit=$durasi_kerja->i+($durasi_kerja->h*60);
            }else{
                $durasi_kerja_menit=0;
            }
            if($jadwal_in!=null && $absen_in!=null && $absen_in>$jadwal_in && in_array($status_absen,$IBY)==false && $status_absen!='TL'){
                $total_dt=$dt->i+$dt->h*60;
                if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                    if($absen_in>'13:00:00'){
                        $total_dt_real=$total_dt-60;
                    }else if($absen_in>'12:00:00' && $absen_in<='13:00:00'){
                        $selisih_menit=strtotime($absen_in)-strtotime('12:00:00');
                        $selisih_menit=round($selisih_menit/60);
                        $total_dt_real=$total_dt-$selisih_menit;
                    }else{
                        $total_dt_real=$total_dt;
                    }
                }else{
                    $total_dt_real=$total_dt;
                }
            }else{
                $total_dt_real=0;
            }
            $total_dt_real=$total_dt_real<480?$total_dt_real:480;
            if($jadwal_out!=null && $absen_out!=null && $absen_out<$jadwal_out && in_array($status_absen,$IBY)==false  && $status_absen!='TL'){
                $total_pc=$pc->i+$pc->h*60;
                if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                    if($v->absen_pulang_kerja<='12:00:00'){
                        $total_pc_real=$total_pc-60;
                    }else if($absen_out>'12:00:00' && $absen_out<='13:00:00'){
                        $selisih_menit=strtotime('13:00:00')-strtotime($absen_out);
                        $selisih_menit=round($selisih_menit/60);
                        $total_pc_real=$total_pc-$selisih_menit;
                    }else{
                        $total_pc_real=$total_pc;
                    }
                }else{
                    $total_pc_real=$total_pc;
                }
            }else{
                $total_pc_real=0;
            }
            $jumlah_menit_absen_dtpc=$total_dt_real+$total_pc_real;
            $jumlah_absen_menit_kerja=$durasi_kerja_menit-$jumlah_menit_absen_dtpc;
            MasterDataAbsenKehadiran::where('enroll_id',$enroll_id)->where('tanggal_berjalan',date('2024-01-02'))->update([
                'jumlah_menit_absen_dt'=>$total_dt_real,
                'jumlah_menit_absen_pc'=>$total_pc_real,
                'jumlah_menit_kerja'=>$durasi_kerja_menit,
                'jumlah_menit_absen_dtpc'=>$jumlah_menit_absen_dtpc,
                'jumlah_absen_menit_kerja'=>$jumlah_absen_menit_kerja
            ]);
        }
        dd('update dtsa berhasil');
    }
    public function index()
    {

        //return View::make('admin/dashboard', $this->data);
    }

    public function datahadir()
    {
        // $DepartmentAllModel = new DepartmentAll();

        $DepartmentAllModel =  DepartmentAll::groupBy('department_name')
        ->orderBy('department_name','asc')
        ->get();
        $NirwananameAllModel =  DepartmentAll::groupBy('site_nirwana_name')
        ->orderBy('site_nirwana_name','asc')
        ->get();

        $this->department =  $DepartmentAllModel;
        $this->site =  $NirwananameAllModel;

        $this->selectemployee = $this->ajax_getallemployeeatribut();
        $this->refabsenijin = $this->ajax_getselectrefabsenijin();
        return View::make('hris/datahadir', $this->data);
    }

    public function print()
    {
        $selectemployee = $this->ajax_getallemployeeatribut();
        echo json_encode($selectemployee);
    }

    public function laporan_harian_kehadiran()
    {
        $this->department = $this->ajax_getselectdepart();
        return view('hris/laporan_harian_kehadiran', $this->data);
    }

    public function lihatabsen()
    {

        return View::make('hris/lihatabsen', $this->data);
    }

    public function ajax_datahadir(Request $request)
    {
        $selectDepartment = $request->selectDepartment;
        $selectBagian = $request->selectBagian;
        $selectSiteNirwana = $request->siteNirwana;
        $daterange1 = $request->daterange1;
        $status_staff = $request->status_staff;
        $searchData = $request->searchData;
        $daterange1 = explode(" s/d ", $request->daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));

        $filterStaff = "";
        if($status_staff) {
            $filterStaff = " AND employee_atribut.status_staff = '" . $status_staff . "'";
        }

        $inDepartment = "";
        if($selectDepartment) {
            $inDepartment = ' AND employee_atribut.department_name = "' . $selectDepartment . '"';
        }

        $inBagian = "";
        if($selectBagian) {
            $inBagian = ' AND employee_atribut.sub_dept_name = "' . $selectBagian . '"';

        }
        $inSiteNirwana = "";
        if($selectSiteNirwana) {
            $inSiteNirwana = ' AND employee_atribut.site_nirwana_id = "' . $selectSiteNirwana . '"';
        }

        $inSearchData='';
        if($searchData){
            $enroll_id = implode(", ", $searchData);
            $allEnroll_id= '('.$enroll_id.')';
            $inSearchData = ' AND employee_atribut.enroll_id IN '.$allEnroll_id.'';
        }

        if(request()->ajax()) {

            $limit = $request->input('length');
            $start = $request->input('start');

            if(empty($department_id)) {
                $totalData = MasterDataAbsenKehadiran::
                whereRaw('
                    substr(master_data_absen_kehadiran.tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                    ' . $filterStaff . ' ' . $inDepartment . ' ' . $inBagian . ' ' . $inSearchData . ' ' . $inSiteNirwana.' ')
                ->leftJoin('employee_atribut','master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
                ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
                ->count();
                $totalFiltered = $totalData;

                $query = MasterDataAbsenKehadiran::
                selectRaw('
                    master_data_absen_kehadiran.uuid,
                    employee_atribut.employee_id,
                    employee_atribut.nik,
                    master_data_absen_kehadiran.enroll_id,
                    employee_atribut.employee_name,
                    master_data_absen_kehadiran.tanggal_berjalan,
                    master_data_absen_kehadiran.kode_hari,
                    master_data_absen_kehadiran.nama_hari,
                    master_data_absen_kehadiran.mulai_jam_kerja,
                    master_data_absen_kehadiran.akhir_jam_kerja,
                    master_data_absen_kehadiran.absen_masuk_kerja,
                    master_data_absen_kehadiran.absen_pulang_kerja,
                    master_data_absen_kehadiran.jumlah_jam_kerja,
                    master_data_absen_kehadiran.status_absen,
                    master_data_absen_kehadiran.nomor_form_perubahan_absen,
                    master_data_absen_kehadiran.nomor_absen_ijin,
                    master_data_absen_kehadiran.nomor_form_lembur,
                    data_absen_perijinan.time_mulai_ijin as permits_dari_pukul,
                    data_absen_perijinan.time_akhir_ijin as permits_sampai_pukul,
                    data_absen_perijinan.total_time_ijin as total_menit_permits,
                    master_data_absen_kehadiran.operator,
                    master_data_absen_kehadiran.holiday_name,
                    master_data_absen_kehadiran.catatan_hrd,
                    department_all.site_nirwana_name,
                    department_all.department_name,
                    department_all.sub_dept_name,
                    master_data_absen_kehadiran.tanggal_absen,
                    master_data_absen_kehadiran.jumlah_menit_absen_dt,
                    master_data_absen_kehadiran.jumlah_menit_absen_pc,
                    master_data_absen_kehadiran.jumlah_menit_absen_dtpc,
                    master_data_absen_kehadiran.jumlah_absen_menit_kerja,
                    employee_atribut.status_staff,
                    employee_atribut.status_aktif,
                    employee_atribut.status_jabatan,
                    employee_atribut.status_staff,
                    employee_atribut.site_nirwana_id,
                    employee_atribut.work_status,
                    employee_atribut.status_kontrak_tetap,
                    employee_atribut.employee_status,
                    data_lembur.mulai_jam_lembur,
                    data_lembur.akhir_jam_lembur,
                    data_lembur.jumlah_jam_lembur,
                    ref_absen_ijin.kode_ijin_payroll,
                    master_data_absen_kehadiran.created_at,
                    master_data_absen_kehadiran.updated_at,
                    data_absen_perijinan.nomor_form_perizinan,
                    data_absen_perijinan.absen_alasan,
                    data_absen_perijinan.tanggal_mulai_ijin,
                    data_absen_perijinan.tanggal_akhir_ijin,
                    ref_absen_ijin.nama_absen_ijin,
                    log_data_gagal_absen.absen_alasan as log_absen_alasan
                ')
                ->whereRaw('
                    substr(master_data_absen_kehadiran.tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                    ' . $filterStaff . ' ' . $inDepartment . ' ' . $inBagian . ' ' . $inSearchData . ' ' . $inSiteNirwana . ' ')
                ->leftJoin('employee_atribut','master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
                ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
                ->leftJoin('ref_absen_ijin','master_data_absen_kehadiran.status_absen','ref_absen_ijin.kode_absen_ijin')
                ->leftJoin('data_absen_perijinan','master_data_absen_kehadiran.nomor_absen_ijin','data_absen_perijinan.nomor_form_perizinan')
                ->leftJoin('log_data_gagal_absen','master_data_absen_kehadiran.uuid','log_data_gagal_absen.uuid_master')
                ->leftJoin('data_lembur','master_data_absen_kehadiran.uuid','data_lembur.uuid_master')
                ->offset($start)
                ->limit($limit)
                ->orderBy('master_data_absen_kehadiran.tanggal_berjalan','asc')
                ->orderBy('employee_atribut.employee_name','asc')
                ->get();
            } else {
                $totalData = MasterDataAbsenKehadiran::
                whereRaw('
                    substr(tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                    ' . $filterStaff . ' ' . $inDepartment . ' ' . $inBagian . ' ' . $inSearchData . ' ' . $inSiteNirwana . ' ')
                ->leftJoin('employee_atribut','master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
                ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
                ->leftJoin('ref_absen_ijin','master_data_absen_kehadiran.status_absen','ref_absen_ijin.kode_absen_ijin')
                ->count();
                $totalFiltered = $totalData;

                $query =  MasterDataAbsenKehadiran::
                selectRaw('
                    master_data_absen_kehadiran.uuid,
                    employee_atribut.employee_id,
                    employee_atribut.nik,
                    master_data_absen_kehadiran.enroll_id,
                    employee_atribut.employee_name,
                    master_data_absen_kehadiran.tanggal_berjalan,
                    master_data_absen_kehadiran.kode_hari,
                    master_data_absen_kehadiran.nama_hari,
                    master_data_absen_kehadiran.mulai_jam_kerja,
                    master_data_absen_kehadiran.akhir_jam_kerja,
                    master_data_absen_kehadiran.absen_masuk_kerja,
                    master_data_absen_kehadiran.absen_pulang_kerja,
                    master_data_absen_kehadiran.jumlah_jam_kerja,
                    master_data_absen_kehadiran.status_absen,
                    master_data_absen_kehadiran.nomor_form_perubahan_absen,
                    master_data_absen_kehadiran.nomor_absen_ijin,
                    master_data_absen_kehadiran.nomor_form_lembur,
                   data_absen_perijinan.time_mulai_ijin as permits_dari_pukul,
                    data_absen_perijinan.time_akhir_ijin as permits_sampai_pukul,
                    data_absen_perijinan.total_time_ijin as total_menit_permits,
                    master_data_absen_kehadiran.operator,
                    master_data_absen_kehadiran.holiday_name,
                    master_data_absen_kehadiran.catatan_hrd,
                    department_all.site_nirwana_name,
                    department_all.department_name,
                    department_all.sub_dept_name,
                    master_data_absen_kehadiran.tanggal_absen,
                    master_data_absen_kehadiran.jumlah_menit_absen_dt,
                    master_data_absen_kehadiran.jumlah_menit_absen_pc,
                    master_data_absen_kehadiran.jumlah_menit_absen_dtpc,
                    master_data_absen_kehadiran.jumlah_absen_menit_kerja,
                    employee_atribut.status_staff,
                    employee_atribut.status_aktif,
                    employee_atribut.status_jabatan,
                    employee_atribut.status_kontrak_tetap,
                    employee_atribut.site_nirwana_id,
                    employee_atribut.work_status,
                    employee_atribut.employee_status,
                    data_lembur.mulai_jam_lembur,
                    data_lembur.akhir_jam_lembur,
                    data_lembur.jumlah_jam_lembur,
                    ref_absen_ijin.kode_ijin_payroll,
                    master_data_absen_kehadiran.created_at,
                    master_data_absen_kehadiran.updated_at,
                    data_absen_perijinan.nomor_absen_ijin,
                    data_absen_perijinan.absen_alasan,
                    data_absen_perijinan.tanggal_mulai_ijin,
                    data_absen_perijinan.tanggal_akhir_ijin,
                    ref_absen_ijin.nama_absen_ijin,
                    log_data_gagal_absen.absen_alasan as log_absen_alasan
                ')
                ->whereRaw('
                    (substr(master_data_absen_kehadiran.tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '")
                    ' . $filterStaff . ' ' . $inDepartment . ' ' . $inBagian . ' ' . $inSearchData . ' ' . $inSiteNirwana . ' ')
                ->leftJoin('employee_atribut','master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
                ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
                ->leftJoin('ref_absen_ijin','master_data_absen_kehadiran.status_absen','ref_absen_ijin.kode_absen_ijin')
                ->leftJoin('data_absen_perijinan','master_data_absen_kehadiran.nomor_absen_ijin','data_absen_perijinan.nomor_form_perizinan')
                ->leftJoin('data_lembur','master_data_absen_kehadiran.uuid','data_lembur.uuid_master')
                ->leftJoin('log_data_gagal_absen','master_data_absen_kehadiran.uuid','log_data_gagal_absen.uuid_master')
                ->offset($start)
                ->limit($limit)
                ->orderBy('master_data_absen_kehadiran.tanggal_berjalan','asc')
                ->orderBy('employee_atribut.employee_name','asc')
                ->get();

            }

            $data = array();
            // dd($query);
            if(!empty($query))
            {
                foreach ($query as $q)
                {
                    $tanggal_absen = $q->tanggal_absen;
                    $kode_hari = $q->kode_hari;
                    $liburnasional = $q->holiday_name;
                    $status_absen=$q->status_absen;
                    $absen_pulang_kerja=$q->absen_pulang_kerja;
                    $absen_masuk_kerja=$q->absen_masuk_kerja;
                    $kode_ijin_payroll=$q->kode_ijin_payroll;
                    if($kode_ijin_payroll==null){
                        if($q->mulai_jam_kerja!=null && $q->akhir_jam_kerja!=null){
                            if($q->absen_masuk_kerja!=null && $q->absen_pulang_kerja!=null && $q->status_absen!='R'){
                                if($q->jumlah_menit_absen_dt!=0 && $q->jumlah_menit_absen_pc==0 && $q->status_staff=="NON STAFF"){
                                    $kode_ijin_payroll='DT';
                                }else if($q->jumlah_menit_absen_dt>10 && $q->jumlah_menit_absen_pc==0 && $q->status_staff=="STAFF"){
                                    $kode_ijin_payroll='DT';
                                }else if($q->jumlah_menit_absen_dt==0 && $q->jumlah_menit_absen_pc!=0){
                                    $kode_ijin_payroll='PC';
                                }else if($q->jumlah_menit_absen_dt!=0 && $q->jumlah_menit_absen_pc!=0){
                                    $kode_ijin_payroll='DTPC';
                                }else{
                                    $kode_ijin_payroll='OK';
                                }
                            }else if($q->absen_masuk_kerja!=null && $q->absen_pulang_kerja!=null && $q->status_absen=='R'){
                                $kode_ijin_payroll='R';
                            }else if($q->absen_masuk_kerja!=null && $q->absen_pulang_kerja==null && $q->status_absen!='R'){
                                $kode_ijin_payroll='M';
                            }else if($q->absen_masuk_kerja==null && $q->absen_pulang_kerja!=null && $q->status_absen!='R'){
                                $kode_ijin_payroll='M';
                            }else if($q->absen_masuk_kerja!=null && $q->absen_pulang_kerja==null && $q->status_absen=='R'){
                                $kode_ijin_payroll='R';
                            }else if($q->absen_masuk_kerja==null && $q->absen_pulang_kerja==null && $q->status_absen!='R'){
                                $kode_ijin_payroll='M';
                            }else if($q->absen_masuk_kerja==null && $q->absen_pulang_kerja==null && $q->status_absen=='R'){
                                $kode_ijin_payroll='R';
                            }
                        }else{
                            if($q->status_absen=='LN'){
                                $kode_ijin_payroll='LBY';
                            }else if($q->status_absen=='R'){
                                $kode_ijin_payroll='R';
                            }else if($q->status_absen==null){
                                $kode_ijin_payroll='LSM';
                            }else if($q->status_absen=='IKS'){
                                $kode_ijin_payroll='OK';
                            }else if($q->status_absen=='TL'){
                                $kode_ijin_payroll='LSM';
                            }
                        }
                    }else if($kode_ijin_payroll=='ITB'){
                        if($q->status_absen=='M'){
                            $kode_ijin_payroll='M';
                        }else if($q->status_absen=='IKS'){
                            if($q->absen_masuk_kerja!=null && $q->absen_pulang_kerja!=null){
                                if($q->jumlah_menit_absen_dt!=0 && $q->jumlah_menit_absen_pc==0 && $q->status_staff=="NON STAFF"){
                                    $kode_ijin_payroll='DT';
                                    }else if($q->jumlah_menit_absen_dt>10 && $q->jumlah_menit_absen_pc==0 && $q->status_staff=="STAFF"){
                                        $kode_ijin_payroll='DT';
                                    }else if($q->jumlah_menit_absen_dt==0 && $q->jumlah_menit_absen_pc!=0){
                                        $kode_ijin_payroll='PC';
                                    }else if($q->jumlah_menit_absen_dt!=0 && $q->jumlah_menit_absen_pc!=0){
                                        $kode_ijin_payroll='DTPC';
                                    }else{
                                        $kode_ijin_payroll='OK';
                                    }
                                }
                                else{
                                    $kode_ijin_payroll='OK';
                                }
                         }else if($q->status_absen=='LP'){
                            $kode_ijin_payroll='LP';
                        }else if($q->status_absen=='S'){
                            $kode_ijin_payroll='S';
                        }
                    }else if($kode_ijin_payroll=='IBY'){
                        if($q->status_absen=='DL'){
                            $kode_ijin_payroll='DL';
                        }
                    }

                    $kerjalibur = "KERJA";
                    if($tanggal_absen <> "") {
                        $kerjalibur = "KERJA";
                        switch ($kode_hari) {
                            case '5':
                                $kerjalibur = "LIBUR";
                                break;
                            case '6':
                                $kerjalibur = "LIBUR";
                                break;
                        }
                    } else if($tanggal_absen <> null) {
                        $kerjalibur = "KERJA";
                        switch ($kode_hari) {
                            case '5':
                                $kerjalibur = "LIBUR";
                                break;
                            case '6':
                                $kerjalibur = "LIBUR";
                                break;
                        }
                    } else {
                        switch ($kode_hari) {
                            case '5':
                                $kerjalibur = "LIBUR";
                                break;
                            case '6':
                                $kerjalibur = "LIBUR";
                                break;
                        }
                    }
                    if(($kode_hari=='6' || $kode_hari=='5' ) && ($q->mulai_jam_kerja!=null)){
                        $kerjalibur = "KERJA";
                    }

                    if($liburnasional <> "") {
                        $kerjalibur = "LIBUR";
                    }
                    if(($status_absen == "LP" ) &&($absen_pulang_kerja==null) && ($absen_masuk_kerja==null) ) {
                        $kerjalibur = "LIBUR";
                    }
                    if($status_absen == "LN" || $status_absen == "CG" || $status_absen == "CM" || $status_absen == "CT" ||$status_absen == "L" ) {
                        $kerjalibur = "LIBUR";
                    }
                    if($q->mulai_jam_kerja == null){
                        $kerjalibur = "LIBUR";
                    }

                    $nestedData['uuid'] = $q->uuid;
                    $nestedData['employee_id'] = $q->employee_id;
                    $nestedData['nik'] = $q->nik;
                    $nestedData['enroll_id'] = $q->enroll_id;
                    $nestedData['employee_name'] = $q->employee_name;
                    $nestedData['tanggal_berjalan'] = $q->tanggal_berjalan;
                    $nestedData['kode_hari'] = $q->kode_hari;
                    $nestedData['nama_hari'] = $q->nama_hari;
                    $nestedData['kerjalibur'] = $kerjalibur;
                    $nestedData['mulai_jam_kerja'] = substr($q->mulai_jam_kerja, 0, 5);
                    $nestedData['akhir_jam_kerja'] = substr($q->akhir_jam_kerja, 0, 5);
                    $nestedData['absen_masuk_kerja'] = substr($q->absen_masuk_kerja, 0, 5);
                    $nestedData['absen_pulang_kerja'] = substr($q->absen_pulang_kerja, 0, 5);
                    $nestedData['jumlah_jam_kerja'] = $q->jumlah_jam_kerja;
                    $nestedData['status_absen'] = strtoupper($q->status_absen);
                    $nestedData['nomor_form_perubahan_absen'] = $q->nomor_form_perubahan_absen;
                    $nestedData['nomor_absen_ijin'] = $q->nomor_absen_ijin;
                    $nestedData['nomor_form_lembur'] = $q->nomor_form_lembur;
                    $nestedData['permits_dari_pukul'] = $q->permits_dari_pukul;
                    $nestedData['permits_sampai_pukul'] = $q->permits_sampai_pukul;
                    $nestedData['total_menit_permits'] = $q->total_menit_permits;
                    $nestedData['updated_at'] = substr($q->updated_at, 0, 10) . " " . substr($q->updated_at, 11, 8);
                    $nestedData['operator'] = $q->operator;
                    $nestedData['holiday_name'] = $q->holiday_name;
                    $nestedData['catatan_hrd'] = $q->catatan_hrd;
                    $nestedData['site_nirwana_name'] = $q->site_nirwana_name;
                    $nestedData['department_name'] = $q->department_name;
                    $nestedData['sub_dept_name'] = $q->sub_dept_name;
                    $nestedData['tanggal_absen'] = $q->tanggal_absen;
                    $nestedData['nama_absen_ijin'] = $q->nama_absen_ijin;
                    $nestedData['tanggal_mulai_ijin'] = $q->tanggal_mulai_ijin;
                    $nestedData['tanggal_akhir_ijin'] = $q->tanggal_akhir_ijin;
                    $nestedData['absen_alasan'] = $q->absen_alasan;
                    $nestedData['jumlah_menit_absen_dt'] = number_format($q->jumlah_menit_absen_dt, 0);
                    $nestedData['jumlah_menit_absen_pc'] = number_format($q->jumlah_menit_absen_pc, 0);
                    $nestedData['jumlah_menit_absen_dtpc'] = number_format($q->jumlah_menit_absen_dtpc, 0);
                    $nestedData['jumlah_absen_menit_kerja'] = number_format($q->jumlah_absen_menit_kerja, 0);
                    $nestedData['status_staff'] = $q->status_staff;
                    $nestedData['status_aktif'] = $q->status_aktif;
                    $nestedData['status_jabatan'] = $q->status_jabatan;
                    $nestedData['status_staff'] = $q->status_staff;
                    $nestedData['log_absen_alasan'] = $q->log_absen_alasan;
                    $nestedData['work_status'] = $q->status_aktif;
                    $nestedData['employee_status'] = $q->status_kontrak_tetap;
                    $nestedData['mulai_jam_lembur'] = $q->mulai_jam_lembur;
                    $nestedData['akhir_jam_lembur'] = $q->akhir_jam_lembur;
                    $nestedData['jumlah_jam_lembur'] = $q->jumlah_jam_lembur;
                    $nestedData['kode_ijin_payroll'] = $kode_ijin_payroll;
                    $nestedData['created_at'] = $q->created_at;
                    $nestedData['updated_at'] = $q->updated_at;

                    $data[] = $nestedData;

                }
            }

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data
                );

            echo json_encode($json_data);
        }
    }

    private function ajax_getselectdepart()
    {
        $query =  DepartmentAll::selectRaw('department_id,
                                           concat("[",site_nirwana_id,"] "
                                                  ,department_name) department_name')
                                 ->groupby('department_name')
                                 ->orderby('department_name', 'asc')
                                 ->get();

        return $query;

    }

    private function ajax_getselectrefabsenijin()
    {
        $query =  RefAbsenIjin::selectRaw('kode_absen_ijin,
                                           concat(kode_absen_ijin," - "
                                                  ,nama_absen_ijin) kode_nama_absen_ijin')
                                 ->orderby('nama_absen_ijin', 'asc')
                                 ->get();

        return $query;

    }

    public function ajax_exportexceldeptall()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        return Excel::download(new DepartmentAllExport, 'DepartmentAll.xlsx');

    }

    // laravel fast excel
    public function ajax_exportexcel(Request $request)
    {
        ini_set("max_execution_time", 5210);
        ini_set('memory_limit', '5120000M');

        if($request->selectDepartment) {
            $selectDepartment = $request->selectDepartment;
        } else {
            $selectDepartment = "";
        }

        if($request->selectBagian) {
            $selectBagian = $request->selectBagian;
        } else {
            $selectBagian = "";
        }

        if($request->status_staff) {
            $status_staff = $request->status_staff;
        } else {
            $status_staff = "";
        }
        if($request->selectEmployeeID) {
            $searchData = $request->selectEmployeeID;
        } else {
            $searchData = "";
        }

        if($request->daterange1) {
            $daterange1 = $request->daterange1;
        } else {
            $daterange1 = "";
        }

        $daterange1 = $daterange1;
        $daterange1 = explode(" s/d ", $daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
        $tanggalMulai = $tanggalMulai;
        $tanggalSampai = $tanggalSampai;

        $selectDepartment = $selectDepartment;

        $status_staff = $status_staff;

        $filterStaff = "";
        if($status_staff) {
            $filterStaff = " AND employee_atribut.status_staff = '" . $status_staff . "'";
        }

        $inDepartment = "";
        if($selectDepartment) {
            $inDepartment = ' AND employee_atribut.department_name = "' . $selectDepartment . '"';

        }

        $inBagian = "";
        if($selectBagian) {
            $inBagian = ' AND employee_atribut.sub_dept_name = "' . $selectBagian . '"';
        }

        $inSearchData='';
        if($searchData){
            $enroll_id = implode(", ", $searchData);
            $allEnroll_id= '('.$enroll_id.')';
            $inSearchData = ' AND employee_atribut.enroll_id IN '.$allEnroll_id.'';
        }
        $dataAbsen = DB::select('
            SELECT
                master_data_absen_kehadiran.tanggal_berjalan,
                master_data_absen_kehadiran.kode_hari,
                master_data_absen_kehadiran.nama_hari,
                employee_atribut.nik,
                employee_atribut.enroll_id,
                employee_atribut.employee_name,
                employee_atribut.status_staff,
                employee_atribut.status_jabatan,
                employee_atribut.department_name,
                employee_atribut.sub_dept_name,
                master_data_absen_kehadiran.mulai_jam_kerja,
                master_data_absen_kehadiran.akhir_jam_kerja,
                master_data_absen_kehadiran.absen_masuk_kerja,
                master_data_absen_kehadiran.absen_pulang_kerja,
                master_data_absen_kehadiran.jumlah_absen_menit_kerja,
                c.is_verifikasi_pengajuan_admin as is_verifikasi_pengajuan_admin,
                c.time_mulai_ijin as permits_dari_pukul,
                c.time_akhir_ijin as permits_sampai_pukul,
                c.total_time_ijin as total_menit_permits,
                master_data_absen_kehadiran.jumlah_menit_absen_dt,
                master_data_absen_kehadiran.jumlah_menit_absen_pc,
                master_data_absen_kehadiran.jumlah_menit_absen_dtpc,
                master_data_absen_kehadiran.status_absen,
                master_data_absen_kehadiran.catatan_hrd,
                b.kode_ijin_payroll,
                data_lembur.mulai_jam_lembur,
                data_lembur.akhir_jam_lembur,
                data_lembur.mulai_jam_lembur  as mulai_jam_lembur_rekap,
                data_lembur.akhir_jam_lembur  as akhir_jam_lembur_rekap,
                rekap_perhitungan_lembur.nomor_form_lembur,
                data_lembur.nomor_form_lembur as nomor_form_lembur_form,
                data_lembur.mulai_jam_lembur as mulai_jam_lembur_form,
                data_lembur.akhir_jam_lembur as akhir_jam_lembur_form,
                data_lembur.jumlah_jam_istirahat_lembur as jumlah_jam_istirahat_lembur_form,
                data_lembur.jumlah_jam_lembur as jumlah_jam_lembur_form,
                data_lembur.is_verifikasi,
                rekap_perhitungan_lembur.final_selesai_jam_lembur,
                rekap_perhitungan_lembur.final_total_jam_lembur,
                rekap_perhitungan_lembur.final_jam_istirahat_lembur,
                rekap_perhitungan_lembur.final_total_menit_lembur,
                rekap_perhitungan_lembur.final_jam_lembur_roundown,
                rekap_perhitungan_lembur.final_menit_lembur_roundown,
                rekap_perhitungan_lembur.lembur_1,
                rekap_perhitungan_lembur.lembur_2,
                rekap_perhitungan_lembur.lembur_3,
                rekap_perhitungan_lembur.lembur_4,
                rekap_perhitungan_lembur.total_lembur_1234,
                rekap_perhitungan_lembur.lembur1_rupiah,
                rekap_perhitungan_lembur.lembur2_rupiah,
                rekap_perhitungan_lembur.lembur3_rupiah,
                rekap_perhitungan_lembur.lembur4_rupiah,
                rekap_perhitungan_lembur.total_lembur_rupiah,
                c.absen_alasan
            FROM
                `master_data_absen_kehadiran`
                LEFT JOIN `employee_atribut` ON `master_data_absen_kehadiran`.`enroll_id` = `employee_atribut`.`enroll_id`
                LEFT JOIN ref_absen_ijin b on master_data_absen_kehadiran.status_absen=b.kode_absen_ijin
                LEFT JOIN `data_lembur` ON `master_data_absen_kehadiran`.`enroll_id`= `data_lembur`.`enroll_id` AND `master_data_absen_kehadiran`.`tanggal_berjalan`=`data_lembur`.`tanggal_berjalan`
                LEFT JOIN `rekap_perhitungan_lembur` ON `master_data_absen_kehadiran`.`tanggal_berjalan` = `rekap_perhitungan_lembur`.`tanggal_berjalan` and `master_data_absen_kehadiran`.`enroll_id` = `rekap_perhitungan_lembur`.`enroll_id`
                LEFT JOIN data_absen_perijinan c on master_data_absen_kehadiran.nomor_absen_ijin=c.nomor_form_perizinan

            WHERE
                substr(master_data_absen_kehadiran.tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                ' . $filterStaff . ' ' . $inDepartment . ' ' . $inBagian . ' ' . $inSearchData . '
            GROUP BY
                master_data_absen_kehadiran.enroll_id,
                master_data_absen_kehadiran.tanggal_berjalan
            ORDER BY
                `employee_atribut`.`employee_name` ASC,
                `master_data_absen_kehadiran`.`tanggal_berjalan` ASC
        ');
        $excel = FastExcel::create('dataAbsen');
        $sheet = $excel->getSheet();

        $area = $sheet->beginArea();

        $sheet->writeTo('A1', 'PT NIRWANA ALABARE GARMENT', ['font-size' => 18]);
        $sheet->writeTo('A2', 'LAPORAN ABSENSI KARYAWAN', ['font-size' => 16]);

        $sheet->writeTo('A3', 'TANGGAL ABSENSI : ' . strtoupper(strftime("%d %b %Y", strtotime($tanggalMulai)) . ' s/d ' . strftime("%d %b %Y", strtotime($tanggalSampai))), ['font-size' => 14]);
        $sheet->writeTo('A4', 'STAFF / NON STAFF : ' . ($status_staff ? $status_staff : "SEMUA KARYAWAN"), ['font-size' => 14]);
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');
        $sheet->mergeCells('A4:E4');
        $sheet->mergeCells('A6:A7');
        $sheet->writeTo('A6', 'TANGGAL');

        $sheet->mergeCells('B6:B7');
        $sheet->writeTo('B6', 'HARI');

        $sheet->mergeCells('C6:C7');
        $sheet->writeTo('C6', 'NIK');

        $sheet->mergeCells('D6:D7');
        $sheet->writeTo('D6', 'NO. ABSEN');

        $sheet->mergeCells('E6:E7');
        $sheet->writeTo('E6', 'NAMA KARYAWAN');

        $sheet->mergeCells('F6:F7');
        $sheet->writeTo('F6', 'STAFF / NON STAFF');

        $sheet->mergeCells('G6:G7');
        $sheet->writeTo('G6', 'JABATAN');

        $sheet->mergeCells('H6:H7');
        $sheet->writeTo('H6', 'BAGIAN');

        $sheet->mergeCells('I6:I7');
        $sheet->writeTo('I6', 'DEPARTMENT');

        $sheet->mergeCells('J6:J7');
        $sheet->writeTo('J6', 'KERJA/LIBUR');

        $sheet->mergeCells('K6:L6');
        $sheet->writeTo('K6', 'JADWAL KERJA');
        $sheet->writeTo('K7', 'IN');
        $sheet->writeTo('L7', 'OUT');

        $sheet->mergeCells('M6:M7');
        $sheet->writeTo('M6', 'DURASI ISTIRAHAT');

        $sheet->mergeCells('N6:N7');
        $sheet->writeTo('N6', 'DURASI KERJA');

        $sheet->mergeCells('O6:P6');
        $sheet->writeTo('O6', 'ABSENSI');
        $sheet->writeTo('O7', 'IN');
        $sheet->writeTo('P7', 'OUT');

        $sheet->mergeCells('Q6:Q7');
        $sheet->writeTo('Q6', 'EFEKTIF KERJA');

        $sheet->mergeCells('R6:T6');
        $sheet->writeTo('R6', 'IJIN KELUAR SEMENTARA (IKS)');
        $sheet->writeTo('R7', 'DARI');
        $sheet->writeTo('S7', 'SAMPAI');
        $sheet->writeTo('T7', 'TOTAL');

        $sheet->mergeCells('U6:W6');
        $sheet->writeTo('U6', 'POTONGAN MENIT');
        $sheet->writeTo('U7', 'DT');
        $sheet->writeTo('V7', 'PC');
        $sheet->writeTo('W7', 'Total');

        $sheet->mergeCells('X6:X7');
        $sheet->writeTo('X6', 'STATUS ABSEN');

        $sheet->mergeCells('Y6:Y7');
        $sheet->writeTo('Y6', 'STATUS PAYROLL');

        $sheet->mergeCells('Z6:Z7');
        $sheet->writeTo('Z6', 'ALASAN ABSEN');

        $sheet->mergeCells('AB6:AB7');
        $sheet->writeTo('AB6', 'KETERANGAN');

        $sheet->mergeCells('AC6:AL6');
        $sheet->writeTo('AC6', 'DATA LEMBUR');
        $sheet->writeTo('AC7', 'NO. SPL');
        $sheet->writeTo('AD7', 'MULAI');
        $sheet->writeTo('AE7', 'SELESAI');
        $sheet->writeTo('AF7', 'ISTIRAHAT');
        $sheet->writeTo('AG7', 'TOTAL LEMBUR');
        $sheet->writeTo('AH7', 'L1');
        $sheet->writeTo('AI7', 'L2');
        $sheet->writeTo('AJ7', 'L3');
        $sheet->writeTo('AK7', 'L4');
        $sheet->writeTo('AL7', 'TOTAL LEMBUR');

        $sheet->mergeCells('AM6:AM7');

        $sheet->mergeCells('AN6:AR6');
        $sheet->writeTo('AN6', 'BIAYA LEMBUR');
        $sheet->writeTo('AN7', 'RP LEMBUR 1');
        $sheet->writeTo('AO7', 'RP LEMBUR 2');
        $sheet->writeTo('AP7', 'RP LEMBUR 3');
        $sheet->writeTo('AQ7', 'RP LEMBUR 4');
        $sheet->writeTo('AR7', 'TOTAL LEMBUR');

        $sheet->mergeCells('AS6:AS7');

        $sheet->mergeCells('AT6:AW6');
        $sheet->writeTo('AT6', 'DATA LEMBUR VERIFIKASI');
        $sheet->writeTo('AT7', 'MULAI JAM LEMBUR');
        $sheet->writeTo('AU7', 'AKHIR JAM LEMBUR');
        $sheet->writeTo('AV7', 'JUMLAH JAM ISTIRAHAT');
        $sheet->writeTo('AW7', 'JUMLAH JAM LEMBUR');

        $sheet->writeAreas();

        $sheet->setColOptions([
            'A' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 12],
            'K' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'L' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'M' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'N' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'O' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'P' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'R' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'S' => ['format' => NumberFormat::FORMAT_DATE_TIME3],

            'AB' => ['width' => 20],
            'AD' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'AE' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'AN' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4],
            'AO' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4],
            'AP' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4],
            'AQ' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4],
            'AT' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'AU' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            // 'K' => ['format' => '0.00'],
            // 'L' => ['format' => '0.00'],
        ]);


        foreach($dataAbsen as $Kehadiran) {
            $kode_ijin_payroll=$Kehadiran->kode_ijin_payroll;
            if($kode_ijin_payroll==null){
                if($Kehadiran->mulai_jam_kerja!=null && $Kehadiran->akhir_jam_kerja!=null){
                    if($Kehadiran->absen_masuk_kerja!=null && $Kehadiran->absen_pulang_kerja!=null && $Kehadiran->status_absen!='R'){
                        if($Kehadiran->jumlah_menit_absen_dt!=0 && $Kehadiran->jumlah_menit_absen_pc==0){
                            $kode_ijin_payroll='DT';
                        }else if($Kehadiran->jumlah_menit_absen_dt==0 && $Kehadiran->jumlah_menit_absen_pc!=0){
                            $kode_ijin_payroll='PC';
                        }else if($Kehadiran->jumlah_menit_absen_dt!=0 && $Kehadiran->jumlah_menit_absen_pc!=0){
                            $kode_ijin_payroll='DTPC';
                        }else{
                            $kode_ijin_payroll='OK';
                        }
                    }else if($Kehadiran->absen_masuk_kerja!=null && $Kehadiran->absen_pulang_kerja!=null && $Kehadiran->status_absen=='R'){
                        $kode_ijin_payroll='R';
                    }else if($Kehadiran->absen_masuk_kerja!=null && $Kehadiran->absen_pulang_kerja==null && $Kehadiran->status_absen!='R'){
                        $kode_ijin_payroll='M';
                    }else if($Kehadiran->absen_masuk_kerja==null && $Kehadiran->absen_pulang_kerja!=null && $Kehadiran->status_absen!='R'){
                        $kode_ijin_payroll='M';
                    }else if($Kehadiran->absen_masuk_kerja!=null && $Kehadiran->absen_pulang_kerja==null && $Kehadiran->status_absen=='R'){
                        $kode_ijin_payroll='R';
                    }else if($Kehadiran->absen_masuk_kerja==null && $Kehadiran->absen_pulang_kerja==null && $Kehadiran->status_absen!='R'){
                        $kode_ijin_payroll='M';
                    }else if($Kehadiran->absen_masuk_kerja==null && $Kehadiran->absen_pulang_kerja==null && $Kehadiran->status_absen=='R'){
                        $kode_ijin_payroll='R';
                    }
                }else{
                    if($Kehadiran->status_absen=='LN'){
                        $kode_ijin_payroll='LBY';
                    }else if($Kehadiran->status_absen=='R'){
                        $kode_ijin_payroll='R';
                    }else if($Kehadiran->status_absen==null){
                        $kode_ijin_payroll='LSM';
                    }else if($Kehadiran->status_absen=='IKS'){
                        $kode_ijin_payroll='OK';
                    }else if($Kehadiran->status_absen=='TL'){
                        $kode_ijin_payroll='LSM';
                    }
                }
            }else if($kode_ijin_payroll=='ITB'){
                if($Kehadiran->status_absen=='M'){
                    $kode_ijin_payroll='M';
                }else if($Kehadiran->status_absen=='IKS'){
                    if($Kehadiran->absen_masuk_kerja!=null && $Kehadiran->absen_pulang_kerja!=null){
                        if($Kehadiran->jumlah_menit_absen_dt!=0 && $Kehadiran->jumlah_menit_absen_pc==0){
                            $kode_ijin_payroll='DT';
                        }else if($Kehadiran->jumlah_menit_absen_dt==0 && $Kehadiran->jumlah_menit_absen_pc!=0){
                            $kode_ijin_payroll='PC';
                        }else if($Kehadiran->jumlah_menit_absen_dt!=0 && $Kehadiran->jumlah_menit_absen_pc!=0){
                            $kode_ijin_payroll='DTPC';
                        }else{
                            $kode_ijin_payroll='OK';
                        }
                        }
                    else{
                        $kode_ijin_payroll='OK';
                    }
                }else if($Kehadiran->status_absen=='LP'){
                    $kode_ijin_payroll='LP';
                }else if($Kehadiran->status_absen=='S'){
                    $kode_ijin_payroll='S';
                }
            }else if($kode_ijin_payroll=='IBY'){
                if($Kehadiran->status_absen=='DL'){
                    $kode_ijin_payroll='DL';
                }
            }

            $interval = date_diff(date_create(substr($Kehadiran->akhir_jam_kerja, 0, 5)), date_create(substr($Kehadiran->mulai_jam_kerja, 0, 5)));
            $minutes = $interval->days * 24 * 60;
            $minutes += $interval->h * 60;
            $minutes += $interval->i;
            $total_seconds = ($interval->h)*3600;
            $seconds = intval($total_seconds%60);
            $total_minutes = intval($total_seconds/60);
            $minutes = $total_minutes%60;
            $hours = intval($total_minutes/60);
            $jumlah_menit_kerja= sprintf("%02d", $hours).':'.sprintf("%02d", $minutes);
            $jumlah_menit_istirahat = '01:00';
            $interval_kerja = date_diff(date_create($jumlah_menit_kerja), date_create($jumlah_menit_istirahat));
            $total_seconds2 = ($interval_kerja->h)*3600;
            $seconds2 = intval($total_seconds2%60);
            $total_minutes2 = intval($total_seconds2/60);
            $minutes2 = $total_minutes2%60;
            $hours2 = intval($total_minutes2/60);
            $jumlah_menit_kerja_string= sprintf("%02d", $hours2).':'.sprintf("%02d", $minutes2);
            $kerjalibur = "KERJA";
            if(($Kehadiran->mulai_jam_kerja == null) || ($Kehadiran->status_absen == "LN" || $Kehadiran->status_absen == "CG" || $Kehadiran->status_absen == "CM" || $Kehadiran->status_absen == "CT" ||$Kehadiran->status_absen == "L") || (($Kehadiran->status_absen == "LP" ) && ($Kehadiran->absen_masuk_kerja==null) && ($Kehadiran->absen_pulang_kerja==null))) {
                $kerjalibur = "LIBUR";
            } else if(($Kehadiran->kode_hari=='6' || $Kehadiran->kode_hari=='5' ) && ($Kehadiran->mulai_jam_kerja!=null)){
                $kerjalibur = "KERJA";
            } else {
                if(($Kehadiran->absen_masuk_kerja <> null) || ($Kehadiran->absen_masuk_kerja <> "") || ($Kehadiran->absen_pulang_kerja <> null) || ($Kehadiran->absen_pulang_kerja <> "")) {
                    $kerjalibur = "KERJA";
                    switch ($Kehadiran->kode_hari) {
                        case '5':
                            $kerjalibur = "LIBUR";
                            $jumlah_menit_istirahat = '00:30';
                            break;
                        case '6':
                            $kerjalibur = "LIBUR";
                            $jumlah_menit_istirahat = '00:30';
                            break;
                    }
                }
            }

            $total_jam_lembur = "";
            if ($Kehadiran->final_total_jam_lembur == 0) {
                $total_jam_lembur = "";
            } else {
                $hms = $Kehadiran->final_total_jam_lembur;
                $final_total_jam_lembur = explode(":", $hms);
                $total_jam_lembur=intval($final_total_jam_lembur[0])+(intval($final_total_jam_lembur[1])/60);
            }
            if ($Kehadiran->jumlah_jam_istirahat_lembur_form == 0) {
                $jumlah_jam_istirahat_lembur_form = "";
            } else {
                $final3 = $Kehadiran->jumlah_jam_istirahat_lembur_form;
                $seconds3 = ($final3 * 3600);
                // we're given hours, so let's get those the easy way
                $hours3 = floor($final3);
                // since we've "calculated" hours, let's remove them from the seconds variable
                $seconds3 -= $hours3 * 3600;
                // calculate minutes left
                $minutes3 = floor($seconds3 / 60);
                // remove those from seconds as well
                $seconds3 -= $minutes3 * 60;
                // return the time formatted HH:MM:SS
                $jumlah_jam_istirahat_lembur_form = ((strlen($hours3) < 2) ? "0{$hours3}" : $hours3).":".((strlen($minutes3) < 2) ? "0{$minutes3}" : $minutes3);
            }
            $final_mulai_jam_lembur='';
            $final_akhir_jam_lembur='';
            $final_jam_istirahat = "";
            $lembur_1=$Kehadiran->lembur_1;
            $lembur_2=$Kehadiran->lembur_2;
            $lembur_3=$Kehadiran->lembur_3;
            $lembur_4=$Kehadiran->lembur_4;
            $total_lembur_1234=$Kehadiran->total_lembur_1234;
            $total_lembur_12345='';
                $final_mulai_jam_lembur=substr($Kehadiran->mulai_jam_lembur_rekap, 11, 5);
                $final_akhir_jam_lembur=substr($Kehadiran->akhir_jam_lembur_rekap, 11, 5);
                if ($Kehadiran->final_jam_istirahat_lembur == 0) {
                    $final_jam_istirahat = "";
                } else {
                    $final = $Kehadiran->final_jam_istirahat_lembur;
                    $seconds = ($final * 3600);
                    // we're given hours, so let's get those the easy way
                    $hours = floor($final);
                    // since we've "calculated" hours, let's remove them from the seconds variable
                    $seconds -= $hours * 3600;
                    // calculate minutes left
                    $minutes = floor($seconds / 60);
                    // remove those from seconds as well
                    $seconds -= $minutes * 60;
                    // return the time formatted HH:MM:SS
                    $final_jam_istirahat = ((strlen($hours) < 2) ? "0{$hours}" : $hours).":".((strlen($minutes) < 2) ? "0{$minutes}" : $minutes);
                }
            $total_lembur_12345=$Kehadiran->total_lembur_1234;

            $status = $Kehadiran->status_absen;
            if($status == 'IKS'){
                if ($Kehadiran->jumlah_menit_absen_dt > 0 && $Kehadiran->jumlah_menit_absen_pc > 0) {
                    $status = 'DTPC';
                } elseif ($Kehadiran->jumlah_menit_absen_dt > 0) {
                    $status = 'DT';
                } elseif ($Kehadiran->jumlah_menit_absen_pc > 0) {
                    $status = 'PC';
                } else {
                    $status = 'IKS';
                }
            }

            $data = [
                Date::stringToExcel($Kehadiran->tanggal_berjalan),
                $Kehadiran->nama_hari,
                $Kehadiran->nik,
                $Kehadiran->enroll_id,
                $Kehadiran->employee_name,
                $Kehadiran->status_staff,
                $Kehadiran->status_jabatan,
                $Kehadiran->sub_dept_name,
                $Kehadiran->department_name,
                $kerjalibur,
                substr($Kehadiran->mulai_jam_kerja, 0, 5),
                substr($Kehadiran->akhir_jam_kerja, 0, 5),
                $jumlah_menit_istirahat,
                $jumlah_menit_kerja_string,
                substr($Kehadiran->absen_masuk_kerja, 0, 5),
                substr($Kehadiran->absen_pulang_kerja, 0, 5),
                $Kehadiran->jumlah_absen_menit_kerja,
                $Kehadiran->status_absen == 'IKS' && $Kehadiran->is_verifikasi_pengajuan_admin == 1 ? substr($Kehadiran->permits_dari_pukul, 0, 5) : "",
                $Kehadiran->status_absen == 'IKS' && $Kehadiran->is_verifikasi_pengajuan_admin == 1 ? substr($Kehadiran->permits_sampai_pukul, 0, 5) : "",
                $Kehadiran->status_absen == 'IKS' && $Kehadiran->is_verifikasi_pengajuan_admin == 1 ? $Kehadiran->total_menit_permits : "",
                $Kehadiran->jumlah_menit_absen_dt,
                $Kehadiran->jumlah_menit_absen_pc,
                $Kehadiran->jumlah_menit_absen_dtpc,
                $status,
                $kode_ijin_payroll,
                $Kehadiran->absen_alasan,
                "",
                $Kehadiran->catatan_hrd,
                $Kehadiran->nomor_form_lembur_form,
                substr($Kehadiran->mulai_jam_lembur_form, 11, 5),
                substr($Kehadiran->akhir_jam_lembur_form, 11, 5),
                $jumlah_jam_istirahat_lembur_form,
                $Kehadiran->jumlah_jam_lembur_form,
                $lembur_1,
                $lembur_2,
                $lembur_3,
                $lembur_4,
                $total_lembur_1234,
                "",
                $Kehadiran->lembur1_rupiah,
                $Kehadiran->lembur2_rupiah,
                $Kehadiran->lembur3_rupiah,
                $Kehadiran->lembur4_rupiah,
                $Kehadiran->total_lembur_rupiah,
                "",
                $final_mulai_jam_lembur,
                $final_akhir_jam_lembur,
                $final_jam_istirahat,
                $total_lembur_12345,
            ];

            $sheet->writeRow($data);
        }
        $finename=date('Y-m', strtotime($daterange1[1])).'_Time and Attendance PT.NAG_'.rand(10,10000000).'xlsx';
        ob_end_clean();
        $excel->download($finename);
    }

    public function ajax_getselectsubdept(Request $request)
    {
        $department_id = $request->department_id;
        $query =  DepartmentAll::where('department_id','=',$department_id)
                                 ->groupby('sub_dept_id')
                                 ->orderby('sub_dept_name', 'asc')
                                 ->pluck('sub_dept_id','sub_dept_name');
        return $query;

    }

    public function ajax_getallemployeeatribut()
    {
        $query =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name,
                                           concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                    ->groupby('enroll_id')
                                    ->orderby('employee_name', 'asc')
                                    ->get();
        return $query;

    }

    public function ajax_getselectemployee(Request $request)
    {
        $department_id = $request->department_id;
        $sub_dept_id = $request->sub_dept_id;

        if(($department_id) && (!$sub_dept_id)) {
            $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                        ->where('department_id','=',$department_id)
                                        ->groupby('nik')
                                        ->orderby('employee_name', 'asc')
                                        ->get();

            return $query;
        } else if(($department_id) && ($sub_dept_id)) {
            $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                        ->where('department_id','=',$department_id)
                                        ->where('sub_dept_id','=',$sub_dept_id)
                                        ->groupby('nik')
                                        ->orderby('employee_name', 'asc')
                                        ->get();

            return $query;
        } else {
            $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                        ->groupby('nik')
                                        ->orderby('employee_name', 'asc')
                                        ->get();

            return $query;
        }

        //return $query;

    }

    public function ajax_getemployeselectdeptid(Request $request)
    {
        $department_id = $request->department_id;
        $dataDepartment = "";
        $inDepartmentID = '';

        if($department_id) {
            $dataDepartment = implode('","',$department_id);
            $inDepartmentID = '"' . $dataDepartment . '"';

            $query =  EmployeeAtribut::
            selectRaw('enroll_id no_pin, nik, employee_name,
                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
            ->whereRaw('
                department_id in (' . $inDepartmentID . ')
            ')
            ->groupby('nik')
            ->orderby('employee_name', 'asc')
            ->get();

        } else {
            $query =  EmployeeAtribut::
            selectRaw('enroll_id no_pin, nik, employee_name,
                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
            ->groupby('nik')
            ->orderby('employee_name', 'asc')
            ->get();
        }

        //info('Query :' . $query);
        return $query;

    }

    public function ajax_getemployeselectposisi(Request $request)
    {
        $selectPosisiName = $request->input('selectPosisiName');
        $posisi = '';

        if($selectPosisiName) {
            $posisi = ' posisi_name = "' . $selectPosisiName . '" ';
            $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                            ->whereRaw('
                                ' . $posisi . '
                            ')
                            ->groupby('nik')
                            ->orderby('employee_name', 'asc')
                            ->get();
            info("Posisi : " . $posisi);
        } else {
            $query =  EmployeeAtribut::selectRaw('enroll_id no_pin, nik, employee_name,
                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                            ->groupby('nik')
                            ->orderby('employee_name', 'asc')
                            ->get();
        }

        return $query;

    }
    public function download_mesin(Request $request){
        $tanggal_mesin_absensi = $request->tanggal_mesin_absensi;
        $selectedEnrollId=$request->enroll_id;
        $inEnrollId='';
        $inEnrollsId='';
        if($selectedEnrollId){
            $enroll_id = implode(", ", $selectedEnrollId);
            $allEnroll_id= '('.$enroll_id.')';
            $inEnrollId = ' AND b.BadgeNumber IN '.$allEnroll_id.'';
            $enrolls_id = implode(", ", $selectedEnrollId);
            $allEnrolls_id= '('.$enroll_id.')';
            $inEnrollsId = ' AND enroll_id IN '.$allEnroll_id.'';
        }
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $checkinout =  DB::connection('sqlsrv2')->select(
        DB::raw("
            SELECT CONVERT
                ( VARCHAR ( 10 ), a.CHECKTIME, 126 ) AS tanggal_absen,
                b.Badgenumber AS enroll_id,
                MIN ( CONVERT ( VARCHAR ( 5 ), a.CHECKTIME, 114 ) ) AS absen_in,
                CASE WHEN MIN ( CONVERT ( VARCHAR ( 5 ), a.CHECKTIME, 114 ) ) = MAX ( CONVERT ( VARCHAR ( 5 ), a.CHECKTIME, 114 ) )
                    THEN NULL
                    ELSE MAX ( CONVERT ( VARCHAR ( 5 ), a.CHECKTIME, 114 ) )
                END AS absen_out,
                CASE WHEN MIN ( CONVERT ( VARCHAR ( 5 ), a.CHECKTIME, 114 ) ) = MAX ( CONVERT ( VARCHAR ( 5 ), a.CHECKTIME, 114 ) )
                    THEN 'TL'
                    ELSE NULL
                END status_absen
            FROM
                CHECKINOUT a
                JOIN USERINFO b ON ( a.USERID = b.USERID )
            WHERE
                CONVERT ( VARCHAR ( 10 ), a.CHECKTIME, 126 ) = '" . $tanggal_mesin_absensi . "'".$inEnrollId."
            GROUP BY
                CONVERT ( VARCHAR ( 10 ), a.CHECKTIME, 126 ),
                b.Badgenumber
        ") );

        $kehadiran=[];
        foreach($checkinout as $key=> $value) {
            $kehadiran = MasterDataAbsenKehadiran::selectRaw("
                substr(tanggal_berjalan,1, 10) tanggal_absen,
                enroll_id,
                substr(absen_masuk_kerja, 1, 5) absen_in,
                substr(absen_pulang_kerja, 1, 5) absen_out,
                status_absen,
                operator
            ")
            ->whereRaw("
                tanggal_berjalan = '" . $tanggal_mesin_absensi . "'
                AND enroll_id = '" . $value->enroll_id . "'
            ")
            ->get();
            foreach($kehadiran as $val) {
                if($val["operator"]=='system' || $val["operator"]=='system_injek_lebaran') {
                    if($val["status_absen"] == "TL" || $val["status_absen"] == "M" || $val["status_absen"] == "IKS" || $val["status_absen"] == "" || $val["status_absen"] == null || !$val["status_absen"] || $val["status_absen"] == "LN" || $val["status_absen"] == "LP" || $val["status_absen"] == "CT" || $val["status_absen"] == "L") {
                        if($val["status_absen"] == "LN"){
                            $status_absen='LN';
                        }
                        else{
                            $status_absen=$value->status_absen;
                        }
                        MasterDataAbsenKehadiran::where('tanggal_berjalan','=', $tanggal_mesin_absensi)
                        ->where('enroll_id','=', $val["enroll_id"])
                        ->update([
                            'absen_masuk_kerja' => $value->absen_in,
                            'absen_pulang_kerja' => $value->absen_out,
                            'status_absen' => $status_absen
                        ]);
                    }
                } else {
                    if ($val["status_absen"] == "TL" || $val["status_absen"] == "M") {
                        if($val["status_absen"] == "LN"){
                            $status_absen='LN';
                        }
                        else{
                            $status_absen=$value->status_absen;
                        }
                        MasterDataAbsenKehadiran::where('tanggal_berjalan','=', $tanggal_mesin_absensi)
                        ->where('enroll_id','=', $val["enroll_id"])
                        ->update([
                            'absen_masuk_kerja' => $value->absen_in,
                            'absen_pulang_kerja' => $value->absen_out,
                            'status_absen' => $status_absen
                        ]);
                    }
                }
            }
        }
        return $kehadiran;
    }


    // rombakan 2024-12-02
    public function download_mesin_kehadiran(Request $request)
    {
        $tanggal_mesin_absensi = $request->tanggal_mesin_absensi;
        $tanggal_array=explode(' - ',$tanggal_mesin_absensi);
        $tanggal_awal=Carbon::parse($tanggal_array[0])->format('Y-m-d');
        $tanggal_akhir=Carbon::parse($tanggal_array[1])->format('Y-m-d');
        $selectedEnrollId=$request->enroll_id;
        $inEnrollId='';
        $inEnrollsId='';
        if($selectedEnrollId){
            $enroll_id = implode(", ", $selectedEnrollId);
            $allEnroll_id= '('.$enroll_id.')';
            $inEnrollId = ' AND b.BadgeNumber IN '.$allEnroll_id.'';
            $enrolls_id = implode(", ", $selectedEnrollId);
            $allEnrolls_id= '('.$enroll_id.')';
            $inEnrollsId = ' AND enroll_id IN '.$allEnroll_id.'';
        }
        $adaData = "ADA";
        $countData = MasterDataAbsenKehadiran::whereRaw("tanggal_berjalan >= '" . $tanggal_awal."' and tanggal_berjalan <= '".$tanggal_akhir."'".$inEnrollsId.'')->where(function($query){
            $query->whereColumn('mulai_jam_kerja','<','akhir_jam_kerja')->orWhereNull('mulai_jam_kerja');
        })->count();
        if($countData > 0 ) {
            $checkinout =  DB::connection('sqlsrv2')->select(
            DB::raw("
                SELECT CONVERT
                    ( VARCHAR ( 10 ), a.CHECKTIME, 126 ) AS tanggal_absen,
                    b.Badgenumber AS enroll_id,
                    MIN ( CONVERT ( VARCHAR ( 5 ), a.CHECKTIME, 114 ) ) AS absen_in,
                    CASE WHEN MIN ( CONVERT ( VARCHAR ( 5 ), a.CHECKTIME, 114 ) ) = MAX ( CONVERT ( VARCHAR ( 5 ), a.CHECKTIME, 114 ) )
                        THEN NULL
                        ELSE MAX ( CONVERT ( VARCHAR ( 5 ), a.CHECKTIME, 114 ) )
                    END AS absen_out,
                    CASE WHEN MIN ( CONVERT ( VARCHAR ( 5 ), a.CHECKTIME, 114 ) ) = MAX ( CONVERT ( VARCHAR ( 5 ), a.CHECKTIME, 114 ) )
                        THEN 'TL'
                        ELSE NULL
                    END status_absen
                FROM
                    CHECKINOUT a
                    JOIN USERINFO b ON ( a.USERID = b.USERID )
                WHERE
                    CONVERT ( VARCHAR ( 10 ), a.CHECKTIME, 126 ) >= '" . $tanggal_awal . "' and CONVERT ( VARCHAR ( 10 ), a.CHECKTIME, 126 ) <= '".$tanggal_akhir."'".$inEnrollId."
                GROUP BY
                    CONVERT ( VARCHAR ( 10 ), a.CHECKTIME, 126 ),
                    b.Badgenumber
            ") );
            $arr_list = [];
            $data_update_1 = [];
            $data_update_2 = [];
            foreach($checkinout as $value) {
                $kehardiran_arr = MasterDataAbsenKehadiran::selectRaw("
                    substr(master_data_absen_kehadiran.tanggal_berjalan,1, 10) tanggal_absen,
                    master_data_absen_kehadiran.enroll_id,
                    substr(absen_masuk_kerja, 1, 6) absen_in,
                    substr(absen_pulang_kerja, 1, 6) absen_out,
                    mulai_jam_kerja,
                    master_data_absen_kehadiran.nomor_form_lembur,
                    substr(data_lembur.mulai_jam_lembur,11,6) mulai_lembur,
                    substr(data_lembur.akhir_jam_lembur,11,6) akhir_lembur,
                    status_absen,
                    master_data_absen_kehadiran.operator
                ")
                ->leftJoin('data_lembur','master_data_absen_kehadiran.nomor_form_lembur','=','data_lembur.nomor_form_lembur')
                ->whereRaw("
                    master_data_absen_kehadiran.tanggal_berjalan = '" . $value->tanggal_absen . "'
                    AND master_data_absen_kehadiran.enroll_id = '" . $value->enroll_id . "'
                ")->where(function($query){
                    $query->whereColumn('mulai_jam_kerja','<','akhir_jam_kerja')->orWhereNull('mulai_jam_kerja');
                })
                ->get();
                foreach($kehardiran_arr as $val) {
                    $countEditedData=DataKehadiranInOutEdited::where('tanggal_absen', $val->tanggal_absen)->where('enroll_id','=', $val["enroll_id"])->count();
                    $count=LogDataGagalAbsen::where('tanggal_absen',$val->tanggal_absen)->where('enroll_id',$val["enroll_id"])->count();

                    if($countEditedData<1 && $count<1){
                        if($val["operator"]=='system' || $val["operator"]=='system_injek_lebaran') {
                            if($val["status_absen"] == "TL" || $val["status_absen"] == "M" || $val["status_absen"] == "IKS" || $val["status_absen"] == "" || $val["status_absen"] == null || !$val["status_absen"] || $val["status_absen"] == "LN" || $val["status_absen"] == "LP" || $val["status_absen"] == "CT" || $val["status_absen"] == "L") {
                                if($val["mulai_jam_kerja"]!=null){
                                    if($val["status_absen"] == "LN"||$val["status_absen"] == "IKS"||$val["status_absen"]=='LP'){
                                        if($val["status_absen"]=='LP'){
                                            if($value->absen_in!='' && $value->absen_out==''){
                                                $status_absen=$val["status_absen"];
                                            }else if($value->absen_in=='' && $value->absen_out==''){
                                                $status_absen=$val["status_absen"];
                                            }else if($value->absen_in=='' && $value->absen_out!=''){
                                                $status_absen=$val["status_absen"];
                                            }else{
                                                $status_absen=$value->status_absen;
                                            }
                                        }else{
                                            $status_absen=$val["status_absen"];
                                        }
                                    }
                                    else{
                                        $status_absen=$value->status_absen;
                                    }
                                    // MasterDataAbsenKehadiran::where('tanggal_berjalan', $val->tanggal_absen)
                                    // ->where('enroll_id', $val->enroll_id)->update([
                                    //     'absen_masuk_kerja' => $value->absen_in,
                                    //     'absen_pulang_kerja' => $value->absen_out,
                                    //     'status_absen' => $status_absen
                                    // ]);
                                    $data_update_1[] = [
                                        'tanggal_berjalan' => $val->tanggal_absen,
                                        'enroll_id' => $val->enroll_id,
                                        'absen_masuk_kerja' => $value->absen_in,
                                        'absen_pulang_kerja' => $value->absen_out,
                                        'status_absen' => $status_absen
                                    ];
                                }else{
                                    if($val["mulai_lembur"]!=null && $val["akhir_lembur"]!=null){
                                        if($val["mulai_lembur"]<$val["akhir_lembur"]){
                                            if($val["status_absen"] == "LN"){
                                                $status_absen='LN';
                                            }
                                            else{
                                                $status_absen=$value->status_absen;
                                            }
                                            // MasterDataAbsenKehadiran::where('tanggal_berjalan', $val->tanggal_absen)
                                            // ->where('enroll_id', $val->enroll_id)->update([
                                            //     'absen_masuk_kerja' => $value->absen_in,
                                            //     'absen_pulang_kerja' => $value->absen_out,
                                            //     'status_absen' => $status_absen
                                            // ]);
                                            $data_update_1[] = [
                                                'tanggal_berjalan' => $val->tanggal_absen,
                                                'enroll_id' => $val->enroll_id,
                                                'absen_masuk_kerja' => $value->absen_in,
                                                'absen_pulang_kerja' => $value->absen_out,
                                                'status_absen' => $status_absen
                                            ];
                                        }
                                    }else{
                                        $mulai_jam_kerja_kemarin=MasterDataAbsenKehadiran::where('tanggal_berjalan','<', $val->tanggal_absen)->where('enroll_id', $val->enroll_id)->whereNotNull('mulai_jam_kerja')->orderBy('tanggal_berjalan','DESC')->limit(1)->first()->mulai_jam_kerja;
                                        $akhir_jam_kerja_kemarin=MasterDataAbsenKehadiran::where('tanggal_berjalan','<', $val->tanggal_absen)->where('enroll_id', $val->enroll_id)->whereNotNull('akhir_jam_kerja')->orderBy('tanggal_berjalan','DESC')->limit(1)->first()->akhir_jam_kerja;
                                        if($mulai_jam_kerja_kemarin<$akhir_jam_kerja_kemarin){
                                            if($val["status_absen"] == "LN" ||$val["status_absen"]=='IKS'){
                                                $status_absen=$val["status_absen"];
                                            }
                                            else{
                                                $status_absen=$value->status_absen;
                                            }
                                            // MasterDataAbsenKehadiran::where('tanggal_berjalan', $val->tanggal_absen)
                                            // ->where('enroll_id', $val->enroll_id)->update([
                                            //     'absen_masuk_kerja' => $value->absen_in,
                                            //     'absen_pulang_kerja' => $value->absen_out,
                                            //     'status_absen' => $status_absen
                                            // ]);
                                            $data_update_1[] = [
                                                'tanggal_berjalan' => $val->tanggal_absen,
                                                'enroll_id' => $val->enroll_id,
                                                'absen_masuk_kerja' => $value->absen_in,
                                                'absen_pulang_kerja' => $value->absen_out,
                                                'status_absen' => $status_absen
                                            ];
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($val["status_absen"] == "TL" || $val["status_absen"] == "M") {
                                if($val["mulai_jam_kerja"]!=null){
                                    if($val["status_absen"] == "LN"||$val["status_absen"] == "IKS"||$val["status_absen"] == "DL"||$val["status_absen"]=='LP'){
                                        if($val["status_absen"]=='LP'){
                                            if($value->absen_in!='' && $value->absen_out==''){
                                                $status_absen=$val["status_absen"];
                                            }else if($value->absen_in=='' && $value->absen_out==''){
                                                $status_absen=$val["status_absen"];
                                            }else if($value->absen_in=='' && $value->absen_out!=''){
                                                $status_absen=$val["status_absen"];
                                            }else{
                                                $status_absen=$value->status_absen;
                                            }
                                        }else{
                                            $status_absen=$val["status_absen"];
                                        }
                                    }
                                    else{
                                        $status_absen=$value->status_absen;
                                    }
                                    // MasterDataAbsenKehadiran::where('tanggal_berjalan', $val->tanggal_absen)
                                    // ->where('enroll_id', $val->enroll_id)
                                    // ->update([
                                    //     'absen_masuk_kerja' => $value->absen_in,
                                    //     'absen_pulang_kerja' => $value->absen_out,
                                    //     'status_absen' => $status_absen
                                    // ]);
                                    $data_update_1[] = [
                                        'tanggal_berjalan' => $val->tanggal_absen,
                                        'enroll_id' => $val->enroll_id,
                                        'absen_masuk_kerja' => $value->absen_in,
                                        'absen_pulang_kerja' => $value->absen_out,
                                        'status_absen' => $status_absen
                                    ];
                                }else{
                                    if($val["mulai_lembur"]<$val["akhir_lembur"]){
                                        if($val["status_absen"] == "LN"||$val["status_absen"] == "IKS"){
                                            $status_absen=$val["status_absen"];
                                        }
                                        else{
                                            $status_absen=$value->status_absen;
                                        }
                                        // MasterDataAbsenKehadiran::where('tanggal_berjalan', $val->tanggal_absen)
                                        // ->where('enroll_id', $val->enroll_id)->update([
                                        //     'absen_masuk_kerja' => $value->absen_in,
                                        //     'absen_pulang_kerja' => $value->absen_out,
                                        //     'status_absen' => $status_absen
                                        // ]);
                                        $data_update_1[] = [
                                            'tanggal_berjalan' => $val->tanggal_absen,
                                            'enroll_id' => $val->enroll_id,
                                            'absen_masuk_kerja' => $value->absen_in,
                                            'absen_pulang_kerja' => $value->absen_out,
                                            'status_absen' => $status_absen
                                        ];
                                    }
                                }
                            }else{
                                if($count<1){
                                    if($val["mulai_jam_kerja"]!=null){
                                        if($val["status_absen"] == "LN"||$val["status_absen"] == "IKS"||$val["status_absen"] == "DL"||$val["status_absen"] == "R"){
                                            $status_absen=$val["status_absen"];
                                        }
                                        else{
                                            if($val["status_absen"]=="S"||$val["status_absen"]=='LP'){
                                                if($value->absen_in!='' && $value->absen_out==''){
                                                    $status_absen=$val["status_absen"];
                                                }else if($value->absen_in=='' && $value->absen_out==''){
                                                    $status_absen=$val["status_absen"];
                                                }else if($value->absen_in=='' && $value->absen_out!=''){
                                                    $status_absen=$val["status_absen"];
                                                }else{
                                                    $status_absen=$value->status_absen;
                                                }
                                            }else{
                                                $status_absen=$value->status_absen;
                                            }
                                        }
                                        // MasterDataAbsenKehadiran::where('tanggal_berjalan', $val->tanggal_absen)
                                        // ->where('enroll_id', $val->enroll_id)
                                        // ->update([
                                        //     'absen_masuk_kerja' => $value->absen_in,
                                        //     'absen_pulang_kerja' => $value->absen_out,
                                        //     'status_absen' => $status_absen
                                        // ]);
                                        $data_update_1[] = [
                                            'tanggal_berjalan' => $val->tanggal_absen,
                                            'enroll_id' => $val->enroll_id,
                                            'absen_masuk_kerja' => $value->absen_in,
                                            'absen_pulang_kerja' => $value->absen_out,
                                            'status_absen' => $status_absen
                                        ];
                                    }else{
                                        if($val['nomor_form_lembur']!=null){
                                            if($val["mulai_lembur"]<$val["akhir_lembur"]){
                                                if($val["status_absen"] == "LN"||$val["status_absen"] == "IKS"||$val["status_absen"] == "DL"||$val["status_absen"] == "R"){
                                                    $status_absen=$val["status_absen"];
                                                }
                                                else{
                                                    $status_absen=null;
                                                }
                                                // MasterDataAbsenKehadiran::where('tanggal_berjalan', $val->tanggal_absen)->where('enroll_id', $val->enroll_id)->update([
                                                //     'absen_masuk_kerja' => $value->absen_in,
                                                //     'absen_pulang_kerja' => $value->absen_out,
                                                //     'status_absen' => $status_absen
                                                // ]);
                                                $data_update_1[] = [
                                                    'tanggal_berjalan' => $val->tanggal_absen,
                                                    'enroll_id' => $val->enroll_id,
                                                    'absen_masuk_kerja' => $value->absen_in,
                                                    'absen_pulang_kerja' => $value->absen_out,
                                                    'status_absen' => $status_absen
                                                ];
                                            }
                                        }else{
                                            $mulai_jam_kerja_kemarin=MasterDataAbsenKehadiran::where('tanggal_berjalan','<', $val->tanggal_absen)->where('enroll_id', $val->enroll_id)->whereNotNull('mulai_jam_kerja')->orderBy('tanggal_berjalan','DESC')->limit(1)->first()->mulai_jam_kerja;
                                            $akhir_jam_kerja_kemarin=MasterDataAbsenKehadiran::where('tanggal_berjalan','<', $val->tanggal_absen)->where('enroll_id', $val->enroll_id)->whereNotNull('akhir_jam_kerja')->orderBy('tanggal_berjalan','DESC')->limit(1)->first()->akhir_jam_kerja;
                                            if($mulai_jam_kerja_kemarin<$akhir_jam_kerja_kemarin){
                                                if($val["status_absen"] == "LN"||$val["status_absen"] == "IKS"||$val["status_absen"] == "DL"||$val["status_absen"] == "R"){
                                                    $status_absen=$val["status_absen"];
                                                }
                                                else{
                                                    $status_absen=null;
                                                }
                                                // MasterDataAbsenKehadiran::where('tanggal_berjalan', $val->tanggal_absen)
                                                // ->where('enroll_id', $val->enroll_id)->update([
                                                //     'absen_masuk_kerja' => $value->absen_in,
                                                //     'absen_pulang_kerja' => $value->absen_out,
                                                //     'status_absen' => $status_absen
                                                // ]);
                                                $data_update_1[] = [
                                                    'tanggal_berjalan' => $val->tanggal_absen,
                                                    'enroll_id' => $val->enroll_id,
                                                    'absen_masuk_kerja' => $value->absen_in,
                                                    'absen_pulang_kerja' => $value->absen_out,
                                                    'status_absen' => $status_absen
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }else if($countEditedData>0 && $count<1){
                        $datakehadiraneditin = count(DataKehadiranInOutEdited::where('tanggal_absen', $val->tanggal_absen)->where('enroll_id','=', $val["enroll_id"])->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja',null)->get());
                        //gagal absen pagi
                        if($datakehadiraneditin==1){
                            if($value->absen_in!=null && $value->absen_out==null){
                                if($value->absen_in>$datakehadiraneditin){
                                    // MasterDataAbsenKehadiran::where('tanggal_berjalan', $val->tanggal_absen)
                                    // ->where('enroll_id', $val->enroll_id)
                                    // ->update([
                                    //     'absen_pulang_kerja' => $value->absen_in,
                                    //     'status_absen'=>null
                                    // ]);

                                    $data_update_2[] = [
                                        'tanggal_berjalan' => $val->tanggal_absen,
                                        'enroll_id' => $val->enroll_id,
                                        'absen_pulang_kerja' => $value->absen_in,
                                        'status_absen' => null
                                    ];
                                }
                            }else if($value->absen_in!=null && $value->absen_out!=null){
                                // MasterDataAbsenKehadiran::where('tanggal_berjalan', $val->tanggal_absen)
                                // ->where('enroll_id', $val->enroll_id)
                                // ->update([
                                //     'absen_pulang_kerja' => $value->absen_out,
                                //     'status_absen'=>null
                                // ]);
                                $data_update_2[] = [
                                    'tanggal_berjalan' => $val->tanggal_absen,
                                    'enroll_id' => $val->enroll_id,
                                    'absen_pulang_kerja' => $value->absen_out,
                                    'status_absen' => null
                                ];
                            }
                        }
                    }
                }
            }

            foreach ($data_update_1 as $k => $v) {
                MasterDataAbsenKehadiran::where('tanggal_berjalan', $v["tanggal_berjalan"])
                ->where('enroll_id', $v["enroll_id"])
                ->update([
                    'absen_pulang_kerja' => $v["absen_pulang_kerja"],
                    'status_absen' => $v["status_absen"],
                    'absen_masuk_kerja' => $v["absen_masuk_kerja"],
                ]);
            }

            foreach ($data_update_2 as $k => $v) {
                MasterDataAbsenKehadiran::where('tanggal_berjalan', $v["tanggal_berjalan"])
                ->where('enroll_id', $v["enroll_id"])
                ->update([
                    'absen_pulang_kerja' => $v["absen_pulang_kerja"],
                    'status_absen' => $v["status_absen"],
                ]);
            }


            $masterAbsen=MasterDataAbsenKehadiran::whereRaw("tanggal_berjalan >= '" . $tanggal_awal."' and tanggal_berjalan <= '".$tanggal_akhir."'".$inEnrollsId.'')->with('employee_atribut')->where(function($query){
                $query->whereColumn('mulai_jam_kerja','<','akhir_jam_kerja')->orWhereNull('mulai_jam_kerja');
            })->get();

            $today=date('Y-m-d');
            foreach ($masterAbsen as $k => $v) {
                $countEditedData1=DataKehadiranInOutEdited::where('tanggal_absen', $v->tanggal_berjalan)->where('enroll_id', $v->enroll_id)->count();
                $count1=LogDataGagalAbsen::where('tanggal_absen', $v->tanggal_berjalan)->where('enroll_id', $v->enroll_id)->count();
                if($countEditedData1<1 && $count1<1){
                    $jadwal_in=$v->mulai_jam_kerja;
                    $jadwal_out=$v->akhir_jam_kerja;
                    $absen_in=$v->absen_masuk_kerja;
                    $absen_out=$v->absen_pulang_kerja;
                    $status_staff=$v->employee_atribut->status_staff;
                    $durasi_kerja=date_diff(date_create($jadwal_in),date_create($jadwal_out));
                    $durasi_kerja_menit=$durasi_kerja->i +($durasi_kerja->h*60);
                    $DT = date_diff(date_create($jadwal_in),date_create($absen_in));
                    $PC = date_diff(date_create($jadwal_out),date_create($absen_out));
                    if($v->tanggal_berjalan==$today){
                        if( $jadwal_in!=null && $absen_in!=null && $absen_in>$jadwal_in && ($v->status_absen==null || $v->status_absen=='TL' || $v->status_absen=='IKS')){
                            $total_DT1 = $DT->i +($DT->h*60);
                            if($status_staff=='STAFF'){
                                if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                                    if($jadwal_in=='07:00:00'){
                                        if($absen_in >'13:00:00'){
                                            $total_DT=$total_DT1-60;
                                        }
                                        else if($absen_in>'07:00:00' && $absen_in<'07:11:00'){
                                            $total_DT=0;
                                        }
                                        else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                            $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_DT=$total_DT1-$selisih_menit;
                                        }
                                        else {
                                            $total_DT=$total_DT1;
                                        }
                                    }else if($jadwal_in=='07:30:00'){
                                        if($absen_in >'13:00:00'){
                                            $total_DT=$total_DT1-60;
                                        }
                                        else if($absen_in>'07:30:00' && $absen_in<'07:41:00'){
                                            $total_DT=0;
                                        }
                                        else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                            $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_DT=$total_DT1-$selisih_menit;
                                        }
                                        else {
                                            $total_DT=$total_DT1;
                                        }
                                    }
                                }else if($jadwal_in=='05:30:00'){
                                    if($absen_in >'10:30:00'){
                                        $total_DT=$total_DT1-60;
                                    }else if($absen_in >'09:30:00' && $absen_in <='10:30:00'){
                                        $selisih_menit = strtotime($absen_in) - strtotime('09:30:00');
                                        $selisih_menit = round($selisih_menit / 60);
                                        $total_DT=$total_DT1-$selisih_menit;
                                    }else if($absen_in>'05:30:00' && $absen_in<'05:40:00'){
                                        $total_DT=0;
                                    }else{
                                        $total_DT=$total_DT1;
                                    }
                                }else if($jadwal_in=='06:00:00'){
                                    if($absen_in >'11:00:00'){
                                        $total_DT=$total_DT1-60;
                                    }else if($absen_in >'10:00:00' && $absen_in <='11:00:00'){
                                        $selisih_menit = strtotime($absen_in) - strtotime('10:00:00');
                                        $selisih_menit = round($selisih_menit / 60);
                                        $total_DT=$total_DT1-$selisih_menit;
                                    }else if($absen_in>'06:00:00' && $absen_in<'06:11:00'){
                                        $total_DT=0;
                                    }else {
                                        $total_DT=$total_DT1;
                                    }
                                }else if($jadwal_in=='13:00:00'){
                                    if($absen_in >'18:00:00'){
                                        $total_DT=$total_DT1-60;
                                    }else if($absen_in >'17:00:00' && $absen_in <='18:00:00'){
                                        $selisih_menit = strtotime($absen_in) - strtotime('17:00:00');
                                        $selisih_menit = round($selisih_menit / 60);
                                        $total_DT=$total_DT1-$selisih_menit;
                                    }else if($absen_in>'13:00:00' && $absen_in<'13:11:00'){
                                        $total_DT=0;
                                    }else {
                                        $total_DT=$total_DT1;
                                    }
                                }else{
                                    if($total_DT<=10){
                                        $total_DT=0;
                                    }else{
                                        $total_DT=$total_DT1;
                                    }
                                }
                                $total_DT = $total_DT < 480 ? $total_DT : 480;
                            }else{
                                if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                                    if($jadwal_in=='07:00:00'){
                                        if($absen_in >'13:00:00'){
                                            $total_DT=$total_DT1-60;
                                        }
                                        else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                            $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_DT=$total_DT1-$selisih_menit;
                                        }
                                        else {
                                            $total_DT=$total_DT1;
                                        }
                                    }else if($jadwal_in=='07:30:00'){
                                        if($absen_in >'13:00:00'){
                                            $total_DT=$total_DT1-60;
                                        }else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                            $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_DT=$total_DT1-$selisih_menit;
                                        }
                                        else {
                                            $total_DT=$total_DT1;
                                        }
                                    }
                                }else if($jadwal_in=='05:30:00'){
                                    if($absen_in >'10:30:00'){
                                        $total_DT=$total_DT1-60;
                                    }else if($absen_in >'09:30:00' && $absen_in <='10:30:00'){
                                        $selisih_menit = strtotime($absen_in) - strtotime('09:30:00');
                                        $selisih_menit = round($selisih_menit / 60);
                                        $total_DT=$total_DT1-$selisih_menit;
                                    }else {
                                        $total_DT=$total_DT1;
                                    }
                                }else if($jadwal_in=='06:00:00'){
                                    if($absen_in >'11:00:00'){
                                        $total_DT=$total_DT1-60;
                                    }else if($absen_in >'10:00:00' && $absen_in <='11:00:00'){
                                        $selisih_menit = strtotime($absen_in) - strtotime('10:00:00');
                                        $selisih_menit = round($selisih_menit / 60);
                                        $total_DT=$total_DT1-$selisih_menit;
                                    }else {
                                        $total_DT=$total_DT1;
                                    }
                                }else if($jadwal_in=='13:00:00'){
                                    if($absen_in >'18:00:00'){
                                        $total_DT=$total_DT1-60;
                                    }else if($absen_in >'17:00:00' && $absen_in <='18:00:00'){
                                        $selisih_menit = strtotime($absen_in) - strtotime('17:00:00');
                                        $selisih_menit = round($selisih_menit / 60);
                                        $total_DT=$total_DT1-$selisih_menit;
                                    }else {
                                        $total_DT=$total_DT1;
                                    }
                                }else{
                                    $total_DT=$total_DT1;
                                }
                                $total_DT = $total_DT < 480 ? $total_DT : 480;
                            }

                        }else{
                            $total_DT=0;
                        }
                        if($absen_out<$jadwal_in && $absen_out<$absen_in){
                            $total_PC=0;
                        }else if( $jadwal_out !=null && $absen_out !=null && $absen_out<$jadwal_out && ($v->status_absen==null || $v->status_absen=='TL' || $v->status_absen=='IKS')){
                            $total_PC1 = $PC->i +($PC->h*60);
                            if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                                if($absen_out <='12:00:00'){
                                    $total_PC=$total_PC1-60;
                                }else if($absen_out >'12:00:00' && $absen_out <='13:00:00'){
                                    $selisih_menit = strtotime('13:00:00') - strtotime($absen_out);
                                    $selisih_menit = round($selisih_menit / 60);
                                    $total_PC=$total_PC1-$selisih_menit;
                                }else {
                                    $total_PC=$total_PC1;
                                }
                            }else if($jadwal_in=='05:30:00'){
                                if($absen_out <='09:30:00'){
                                    $total_PC=$total_PC1-60;
                                }else if($absen_out >'09:30:00' && $absen_out <='10:30:00'){
                                    $selisih_menit = strtotime('10:30:00') - strtotime($absen_out);
                                    $selisih_menit = round($selisih_menit / 60);
                                    $total_PC=$total_PC1-$selisih_menit;
                                }else {
                                    $total_PC=$total_PC1;
                                }
                            }else if($jadwal_in=='06:00:00'){
                                if($absen_out <='10:00:00'){
                                    $total_PC=$total_PC1-60;
                                }
                                else if($absen_out >'10:00:00' && $absen_out <='11:00:00'){
                                    $selisih_menit = strtotime('11:00:00') - strtotime($absen_out);
                                    $selisih_menit = round($selisih_menit / 60);
                                    $total_PC=$total_PC1-$selisih_menit;
                                }
                                else {
                                    $total_PC=$total_PC1;
                                }
                            }else if($jadwal_in=='13:00:00'){
                                if($absen_out <='17:00:00'){
                                    $total_PC=$total_PC1-60;
                                }
                                else if($absen_out >'17:00:00' && $absen_out <='18:00:00'){
                                    $selisih_menit = strtotime('18:00:00') - strtotime($absen_out);
                                    $selisih_menit = round($selisih_menit / 60);
                                    $total_PC=$total_PC1-$selisih_menit;
                                }
                                else {
                                    $total_PC=$total_PC1;
                                }
                            }else{
                                $total_PC=$total_PC1;
                            }
                            $total_PC = $total_PC < 480 ? $total_PC : 480;
                        }else{
                            $total_PC=0;
                        }
                    }else{
                        if( $jadwal_in!=null && $absen_in!=null && $absen_in>$jadwal_in && ($v->status_absen==null || $v->status_absen=='IKS')){
                            $total_DT1 = $DT->i +($DT->h*60);
                            if($status_staff=='STAFF'){
                                if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                                    if($jadwal_in=='07:00:00'){
                                        if($absen_in >'13:00:00'){
                                            $total_DT=$total_DT1-60;

                                        }
                                        else if($absen_in>'07:00:00' && $absen_in<'07:11:00'){
                                            $total_DT=0;
                                        }
                                        else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                            $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_DT=$total_DT1-$selisih_menit;

                                        }
                                        else {
                                            $total_DT=$total_DT1;
                                        }
                                    }else if($jadwal_in=='07:30:00'){
                                        if($absen_in >'13:00:00'){
                                            $total_DT=$total_DT1-60;
                                        }
                                        else if($absen_in>'07:30:00' && $absen_in<'07:41:00'){
                                            $total_DT=0;
                                        }
                                        else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                            $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_DT=$total_DT1-$selisih_menit;

                                        }
                                        else {
                                            $total_DT=$total_DT1;
                                        }
                                    }

                                }else if($jadwal_in=='05:30:00'){
                                    if($absen_in >'10:30:00'){
                                        $total_DT=$total_DT1-60;
                                    }else if($absen_in >'09:30:00' && $absen_in <='10:30:00'){
                                        $selisih_menit = strtotime($absen_in) - strtotime('09:30:00');
                                        $selisih_menit = round($selisih_menit / 60);
                                        $total_DT=$total_DT1-$selisih_menit;
                                    }else if($absen_in>'05:30:00' && $absen_in<'05:40:00'){
                                        $total_DT=0;
                                    }else{
                                        $total_DT=$total_DT1;
                                    }
                                }else if($jadwal_in=='06:00:00'){
                                    if($absen_in >'11:00:00'){
                                        $total_DT=$total_DT1-60;
                                    }else if($absen_in >'10:00:00' && $absen_in <='11:00:00'){
                                        $selisih_menit = strtotime($absen_in) - strtotime('10:00:00');
                                        $selisih_menit = round($selisih_menit / 60);
                                        $total_DT=$total_DT1-$selisih_menit;
                                    }else if($absen_in>'06:00:00' && $absen_in<'06:11:00'){
                                        $total_DT=0;
                                    }else {
                                        $total_DT=$total_DT1;
                                    }
                                }else if($jadwal_in=='13:00:00'){
                                    if($absen_in >'18:00:00'){
                                        $total_DT=$total_DT1-60;
                                    }else if($absen_in >'17:00:00' && $absen_in <='18:00:00'){
                                        $selisih_menit = strtotime($absen_in) - strtotime('17:00:00');
                                        $selisih_menit = round($selisih_menit / 60);
                                        $total_DT=$total_DT1-$selisih_menit;
                                    }else if($absen_in>'13:00:00' && $absen_in<'13:11:00'){
                                        $total_DT=0;
                                    }else {
                                        $total_DT=$total_DT1;
                                    }
                                }else{
                                    if($total_DT<=10){
                                        $total_DT=0;
                                    }else{
                                        $total_DT=$total_DT1;
                                    }
                                }
                                $total_DT = $total_DT < 480 ? $total_DT : 480;
                            }else{
                                if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                                    if($jadwal_in=='07:00:00'){
                                        if($absen_in >'13:00:00'){
                                            $total_DT=$total_DT1-60;
                                        }
                                        else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                            $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_DT=$total_DT1-$selisih_menit;
                                        }
                                        else {
                                            $total_DT=$total_DT1;
                                        }
                                    }else if($jadwal_in=='07:30:00'){
                                        if($absen_in >'13:00:00'){
                                            $total_DT=$total_DT1-60;
                                        }else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                            $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_DT=$total_DT1-$selisih_menit;
                                        }
                                        else {
                                            $total_DT=$total_DT1;
                                        }
                                    }
                                }else if($jadwal_in=='05:30:00'){
                                    if($absen_in >'10:30:00'){
                                        $total_DT=$total_DT1-60;
                                    }else if($absen_in >'09:30:00' && $absen_in <='10:30:00'){
                                        $selisih_menit = strtotime($absen_in) - strtotime('09:30:00');
                                        $selisih_menit = round($selisih_menit / 60);
                                        $total_DT=$total_DT1-$selisih_menit;
                                    }else {
                                        $total_DT=$total_DT1;
                                    }
                                }else if($jadwal_in=='06:00:00'){
                                    if($absen_in >'11:00:00'){
                                        $total_DT=$total_DT1-60;
                                    }else if($absen_in >'10:00:00' && $absen_in <='11:00:00'){
                                        $selisih_menit = strtotime($absen_in) - strtotime('10:00:00');
                                        $selisih_menit = round($selisih_menit / 60);
                                        $total_DT=$total_DT1-$selisih_menit;
                                    }else {
                                        $total_DT=$total_DT1;
                                    }
                                }else if($jadwal_in=='13:00:00'){
                                    if($absen_in >'18:00:00'){
                                        $total_DT=$total_DT1-60;
                                    }else if($absen_in >'17:00:00' && $absen_in <='18:00:00'){
                                        $selisih_menit = strtotime($absen_in) - strtotime('17:00:00');
                                        $selisih_menit = round($selisih_menit / 60);
                                        $total_DT=$total_DT1-$selisih_menit;
                                    }else {
                                        $total_DT=$total_DT1;
                                    }
                                }else{
                                    $total_DT=$total_DT1;
                                }
                                $total_DT = $total_DT < 480 ? $total_DT : 480;
                            }
                        }else{
                            $total_DT=0;
                        }

                        if($absen_out<$jadwal_in && $absen_out<$absen_in){
                            $total_PC=0;
                        }else if( $jadwal_out !=null && $absen_out !=null && $absen_out<$jadwal_out && ($v->status_absen==null || $v->status_absen=="IKS")){
                            $total_PC1 = $PC->i +($PC->h*60);
                            if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                                if($absen_out <='12:00:00'){
                                    $total_PC=$total_PC1-60;
                                }else if($absen_out >'12:00:00' && $absen_out <='13:00:00'){
                                    $selisih_menit = strtotime('13:00:00') - strtotime($absen_out);
                                    $selisih_menit = round($selisih_menit / 60);
                                    $total_PC=$total_PC1-$selisih_menit;
                                }else {
                                    $total_PC=$total_PC1;
                                }
                            }else if($jadwal_in=='05:30:00'){
                                if($absen_out <='09:30:00'){
                                    $total_PC=$total_PC1-60;
                                }else if($absen_out >'09:30:00' && $absen_out <='10:30:00'){
                                    $selisih_menit = strtotime('10:30:00') - strtotime($absen_out);
                                    $selisih_menit = round($selisih_menit / 60);
                                    $total_PC=$total_PC1-$selisih_menit;
                                }else {
                                    $total_PC=$total_PC1;
                                }
                            }else if($jadwal_in=='06:00:00'){
                                if($absen_out <='10:00:00'){
                                    $total_PC=$total_PC1-60;
                                }
                                else if($absen_out >'10:00:00' && $absen_out <='11:00:00'){
                                    $selisih_menit = strtotime('11:00:00') - strtotime($absen_out);
                                    $selisih_menit = round($selisih_menit / 60);
                                    $total_PC=$total_PC1-$selisih_menit;
                                }
                                else {
                                    $total_PC=$total_PC1;
                                }
                            }else if($jadwal_in=='13:00:00'){
                                if($absen_out <='17:00:00'){
                                    $total_PC=$total_PC1-60;
                                }
                                else if($absen_out >'17:00:00' && $absen_out <='18:00:00'){
                                    $selisih_menit = strtotime('18:00:00') - strtotime($absen_out);
                                    $selisih_menit = round($selisih_menit / 60);
                                    $total_PC=$total_PC1-$selisih_menit;
                                }
                                else {
                                    $total_PC=$total_PC1;
                                }
                            }else{
                                $total_PC=$total_PC1;
                            }
                            $total_PC = $total_PC < 480 ? $total_PC : 480;
                        }else{
                            $total_PC=0;
                        }

                    }
                    $jumlah_menit_absen_dtpc=$total_DT+$total_PC;
                    if( $absen_in!=null && $absen_out !=null){
                        $jumlah_absen_menit_kerja=$durasi_kerja_menit-$jumlah_menit_absen_dtpc;
                    }
                    else{
                        $jumlah_absen_menit_kerja=0;
                    }
                    $jumlah_menit_absen_dtpc=$total_DT+$total_PC;
                    $data_update=[
                        'jumlah_menit_absen_dtpc'=>$jumlah_menit_absen_dtpc,
                        'jumlah_absen_menit_kerja'=>$durasi_kerja_menit-$jumlah_menit_absen_dtpc,
                        'jumlah_menit_absen_dt'=>$total_DT,
                        'jumlah_menit_absen_pc'=>$total_PC,
                        'tanggal_berjalan'=>$v->tanggal_berjalan,
                        'enroll_id'=>$v->enroll_id,
                    ];
                    MasterDataAbsenKehadiran::where('tanggal_berjalan', $v->tanggal_berjalan)->where('enroll_id', $v->enroll_id)->update($data_update);
                }
                else if($countEditedData1>0 && $count1<1){
                    $datakehadiraneditin = count(DataKehadiranInOutEdited::where('tanggal_absen', $v->tanggal_berjalan)->where('enroll_id','=', $v->enroll_id)->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja',null)->get());
                    //gagal absen pagi
                    if($datakehadiraneditin==1){
                        if($v->absen_masuk_kerja!=null && $v->absen_pulang_kerja!=null){
                            $jadwal_in=$v->mulai_jam_kerja;
                            $jadwal_out=$v->akhir_jam_kerja;
                            $absen_in=$v->absen_masuk_kerja;
                            $absen_out=$v->absen_pulang_kerja;
                            $status_staff=$v->employee_atribut->status_staff;
                            $durasi_kerja=date_diff(date_create($jadwal_in),date_create($jadwal_out));
                            $durasi_kerja_menit=$durasi_kerja->i +($durasi_kerja->h*60);

                            $DT = date_diff(date_create($jadwal_in),date_create($absen_in));
                            $PC = date_diff(date_create($jadwal_out),date_create($absen_out));
                            if($today==$v->tanggal_berjalan){
                                if( $jadwal_in!=null && $absen_in!=null && $absen_in>$jadwal_in && ($v->status_absen==null || $v->status_absen=='TL' || $v->status_absen=='IKS')){
                                    $total_DT1 = $DT->i +($DT->h*60);
                                    if($status_staff=='STAFF'){
                                        if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                                            if($jadwal_in=='07:00:00'){
                                                if($absen_in >'13:00:00'){
                                                    $total_DT=$total_DT1-60;
                                                }
                                                else if($absen_in>'07:00:00' && $absen_in<'07:11:00'){
                                                    $total_DT=0;
                                                }
                                                else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                                    $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                                    $selisih_menit = round($selisih_menit / 60);
                                                    $total_DT=$total_DT1-$selisih_menit;
                                                }
                                                else {
                                                    $total_DT=$total_DT1;
                                                }
                                            }else if($jadwal_in=='07:30:00'){
                                                if($absen_in >'13:00:00'){
                                                    $total_DT=$total_DT1-60;
                                                }
                                                else if($absen_in>'07:30:00' && $absen_in<'07:41:00'){
                                                    $total_DT=0;
                                                }
                                                else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                                    $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                                    $selisih_menit = round($selisih_menit / 60);
                                                    $total_DT=$total_DT1-$selisih_menit;
                                                }
                                                else {
                                                    $total_DT=$total_DT1;
                                                }
                                            }
                                        }else if($jadwal_in=='06:00:00'){
                                            if($absen_in >'11:00:00'){
                                                $total_DT=$total_DT1-60;
                                            }else if($absen_in >'10:00:00' && $absen_in <='11:00:00'){
                                                $selisih_menit = strtotime($absen_in) - strtotime('10:00:00');
                                                $selisih_menit = round($selisih_menit / 60);
                                                $total_DT=$total_DT1-$selisih_menit;
                                            }else if($absen_in>'06:00:00' && $absen_in<'06:11:00'){
                                                $total_DT=0;
                                            }else {
                                                $total_DT=$total_DT1;
                                            }
                                        }else if($jadwal_in=='13:00:00'){
                                            if($absen_in >'18:00:00'){
                                                $total_DT=$total_DT1-60;
                                            }else if($absen_in >'17:00:00' && $absen_in <='18:00:00'){
                                                $selisih_menit = strtotime($absen_in) - strtotime('17:00:00');
                                                $selisih_menit = round($selisih_menit / 60);
                                                $total_DT=$total_DT1-$selisih_menit;
                                            }else if($absen_in>'13:00:00' && $absen_in<'13:11:00'){
                                                $total_DT=0;
                                            }else {
                                                $total_DT=$total_DT1;
                                            }
                                        }else{
                                            if($total_DT<=10){
                                                $total_DT=0;
                                            }else{
                                                $total_DT=$total_DT1;
                                            }
                                        }
                                        $total_DT = $total_DT < 480 ? $total_DT : 480;
                                    }else{
                                        if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                                            if($jadwal_in=='07:00:00'){
                                                if($absen_in >'13:00:00'){
                                                    $total_DT=$total_DT1-60;
                                                }
                                                else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                                    $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                                    $selisih_menit = round($selisih_menit / 60);
                                                    $total_DT=$total_DT1-$selisih_menit;
                                                }
                                                else {
                                                    $total_DT=$total_DT1;
                                                }
                                            }else if($jadwal_in=='07:30:00'){
                                                if($absen_in >'13:00:00'){
                                                    $total_DT=$total_DT1-60;
                                                }else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                                    $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                                    $selisih_menit = round($selisih_menit / 60);
                                                    $total_DT=$total_DT1-$selisih_menit;
                                                }
                                                else {
                                                    $total_DT=$total_DT1;
                                                }
                                            }
                                        }else if($jadwal_in=='06:00:00'){
                                            if($absen_in >'11:00:00'){
                                                $total_DT=$total_DT1-60;
                                            }else if($absen_in >'10:00:00' && $absen_in <='11:00:00'){
                                                $selisih_menit = strtotime($absen_in) - strtotime('10:00:00');
                                                $selisih_menit = round($selisih_menit / 60);
                                                $total_DT=$total_DT1-$selisih_menit;
                                            }else {
                                                $total_DT=$total_DT1;
                                            }
                                        }else if($jadwal_in=='13:00:00'){
                                            if($absen_in >'18:00:00'){
                                                $total_DT=$total_DT1-60;
                                            }else if($absen_in >'17:00:00' && $absen_in <='18:00:00'){
                                                $selisih_menit = strtotime($absen_in) - strtotime('17:00:00');
                                                $selisih_menit = round($selisih_menit / 60);
                                                $total_DT=$total_DT1-$selisih_menit;
                                            }else {
                                                $total_DT=$total_DT1;
                                            }
                                        }else{
                                            $total_DT=$total_DT1;
                                        }
                                        $total_DT = $total_DT < 480 ? $total_DT : 480;
                                    }
                                }else{
                                    $total_DT=0;
                                }
                                if($absen_out<$jadwal_in){
                                    $total_PC=0;
                                }else if( $jadwal_out !=null && $absen_out !=null && $absen_out<$jadwal_out && ($v->status_absen==null || $v->status_absen=='TL' || $v->status_absen=='IKS')){
                                    $total_PC1 = $PC->i +($PC->h*60);
                                    if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                                        if($absen_out <='12:00:00'){
                                            $total_PC=$total_PC1-60;
                                        }else if($absen_out >'12:00:00' && $absen_out <='13:00:00'){
                                            $selisih_menit = strtotime('13:00:00') - strtotime($absen_out);
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_PC=$total_PC1-$selisih_menit;
                                        }else {
                                            $total_PC=$total_PC1;
                                        }
                                    }else if($jadwal_in=='06:00:00'){
                                        if($absen_out <='10:00:00'){
                                            $total_PC=$total_PC1-60;
                                        }
                                        else if($absen_out >'10:00:00' && $absen_out <='11:00:00'){
                                            $selisih_menit = strtotime('11:00:00') - strtotime($absen_out);
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_PC=$total_PC1-$selisih_menit;
                                        }
                                        else {
                                            $total_PC=$total_PC1;
                                        }
                                    }else if($jadwal_in=='13:00:00'){
                                        if($absen_out <='17:00:00'){
                                            $total_PC=$total_PC1-60;
                                        }
                                        else if($absen_out >'17:00:00' && $absen_out <='18:00:00'){
                                            $selisih_menit = strtotime('18:00:00') - strtotime($absen_out);
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_PC=$total_PC1-$selisih_menit;
                                        }
                                        else {
                                            $total_PC=$total_PC1;
                                        }
                                    }else{
                                        $total_PC=$total_PC1;
                                    }
                                    $total_PC = $total_PC < 480 ? $total_PC : 480;
                                }else{
                                    $total_PC=0;
                                }

                                $jumlah_menit_absen_dtpc=$total_DT+$total_PC;
                                if( $absen_in!=null && $absen_out !=null){
                                    $jumlah_absen_menit_kerja=$durasi_kerja_menit-$jumlah_menit_absen_dtpc;
                                }
                                else{
                                    $jumlah_absen_menit_kerja=0;
                                }

                                $jumlah_menit_absen_dtpc=$total_DT+$total_PC;
                                $data_update=[
                                    'jumlah_menit_absen_dtpc'=>$jumlah_menit_absen_dtpc,
                                    'jumlah_absen_menit_kerja'=>$durasi_kerja_menit-$jumlah_menit_absen_dtpc,
                                    'jumlah_menit_absen_dt'=>$total_DT,
                                    'jumlah_menit_absen_pc'=>$total_PC,
                                    'tanggal_berjalan'=>$v->tanggal_berjalan,
                                    'enroll_id'=>$v->enroll_id,
                                ];
                                MasterDataAbsenKehadiran::where('tanggal_berjalan', $v->tanggal_berjalan)->where('enroll_id', $v->enroll_id)->update($data_update);
                            }else{
                                if( $jadwal_in!=null && $absen_in!=null && $absen_in>$jadwal_in && ($v->status_absen==null || $v->status_absen=='IKS')){
                                    $total_DT1 = $DT->i +($DT->h*60);
                                    if($status_staff=='STAFF'){
                                        if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                                            if($jadwal_in=='07:00:00'){
                                                if($absen_in >'13:00:00'){
                                                    $total_DT=$total_DT1-60;
                                                }
                                                else if($absen_in>'07:00:00' && $absen_in<'07:11:00'){
                                                    $total_DT=0;
                                                }
                                                else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                                    $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                                    $selisih_menit = round($selisih_menit / 60);
                                                    $total_DT=$total_DT1-$selisih_menit;
                                                }
                                                else {
                                                    $total_DT=$total_DT1;
                                                }
                                            }else if($jadwal_in=='07:30:00'){
                                                if($absen_in >'13:00:00'){
                                                    $total_DT=$total_DT1-60;
                                                }
                                                else if($absen_in>'07:30:00' && $absen_in<'07:41:00'){
                                                    $total_DT=0;
                                                }
                                                else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                                    $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                                    $selisih_menit = round($selisih_menit / 60);
                                                    $total_DT=$total_DT1-$selisih_menit;
                                                }
                                                else {
                                                    $total_DT=$total_DT1;
                                                }
                                            }
                                        }else if($jadwal_in=='05:30:00'){
                                            if($absen_in >'10:30:00'){
                                                $total_DT=$total_DT1-60;
                                            }else if($absen_in >'09:30:00' && $absen_in <='10:30:00'){
                                                $selisih_menit = strtotime($absen_in) - strtotime('09:30:00');
                                                $selisih_menit = round($selisih_menit / 60);
                                                $total_DT=$total_DT1-$selisih_menit;
                                            }else if($absen_in>'05:30:00' && $absen_in<'05:40:00'){
                                                $total_DT=0;
                                            }else{
                                                $total_DT=$total_DT1;
                                            }
                                        }else if($jadwal_in=='06:00:00'){
                                            if($absen_in >'11:00:00'){
                                                $total_DT=$total_DT1-60;
                                            }else if($absen_in >'10:00:00' && $absen_in <='11:00:00'){
                                                $selisih_menit = strtotime($absen_in) - strtotime('10:00:00');
                                                $selisih_menit = round($selisih_menit / 60);
                                                $total_DT=$total_DT1-$selisih_menit;
                                            }else if($absen_in>'06:00:00' && $absen_in<'06:11:00'){
                                                $total_DT=0;
                                            }else {
                                                $total_DT=$total_DT1;
                                            }
                                        }else if($jadwal_in=='13:00:00'){
                                            if($absen_in >'18:00:00'){
                                                $total_DT=$total_DT1-60;
                                            }else if($absen_in >'17:00:00' && $absen_in <='18:00:00'){
                                                $selisih_menit = strtotime($absen_in) - strtotime('17:00:00');
                                                $selisih_menit = round($selisih_menit / 60);
                                                $total_DT=$total_DT1-$selisih_menit;
                                            }else if($absen_in>'13:00:00' && $absen_in<'13:11:00'){
                                                $total_DT=0;
                                            }else {
                                                $total_DT=$total_DT1;
                                            }
                                        }else{
                                            if($total_DT<=10){
                                                $total_DT=0;
                                            }else{
                                                $total_DT=$total_DT1;
                                            }
                                        }
                                        $total_DT = $total_DT < 480 ? $total_DT : 480;
                                    }else{
                                        if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                                            if($jadwal_in=='07:00:00'){
                                                if($absen_in >'13:00:00'){
                                                    $total_DT=$total_DT1-60;
                                                }
                                                else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                                    $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                                    $selisih_menit = round($selisih_menit / 60);
                                                    $total_DT=$total_DT1-$selisih_menit;
                                                }
                                                else {
                                                    $total_DT=$total_DT1;
                                                }
                                            }else if($jadwal_in=='07:30:00'){
                                                if($absen_in >'13:00:00'){
                                                    $total_DT=$total_DT1-60;
                                                }else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                                                    $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                                                    $selisih_menit = round($selisih_menit / 60);
                                                    $total_DT=$total_DT1-$selisih_menit;
                                                }
                                                else {
                                                    $total_DT=$total_DT1;
                                                }
                                            }
                                        }else if($jadwal_in=='05:30:00'){
                                            if($absen_in >'10:30:00'){
                                                $total_DT=$total_DT1-60;
                                            }else if($absen_in >'09:30:00' && $absen_in <='10:30:00'){
                                                $selisih_menit = strtotime($absen_in) - strtotime('09:30:00');
                                                $selisih_menit = round($selisih_menit / 60);
                                                $total_DT=$total_DT1-$selisih_menit;
                                            }else {
                                                $total_DT=$total_DT1;
                                            }
                                        }else if($jadwal_in=='06:00:00'){
                                            if($absen_in >'11:00:00'){
                                                $total_DT=$total_DT1-60;
                                            }else if($absen_in >'10:00:00' && $absen_in <='11:00:00'){
                                                $selisih_menit = strtotime($absen_in) - strtotime('10:00:00');
                                                $selisih_menit = round($selisih_menit / 60);
                                                $total_DT=$total_DT1-$selisih_menit;
                                            }else {
                                                $total_DT=$total_DT1;
                                            }
                                        }else if($jadwal_in=='13:00:00'){
                                            if($absen_in >'18:00:00'){
                                                $total_DT=$total_DT1-60;
                                            }else if($absen_in >'17:00:00' && $absen_in <='18:00:00'){
                                                $selisih_menit = strtotime($absen_in) - strtotime('17:00:00');
                                                $selisih_menit = round($selisih_menit / 60);
                                                $total_DT=$total_DT1-$selisih_menit;
                                            }else {
                                                $total_DT=$total_DT1;
                                            }
                                        }else{
                                            $total_DT=$total_DT1;
                                        }
                                        $total_DT = $total_DT < 480 ? $total_DT : 480;
                                    }
                                }else{
                                    $total_DT=0;
                                }
                                if($absen_out<$jadwal_in){
                                    $total_PC=0;
                                }else if( $jadwal_out !=null && $absen_out !=null && $absen_out<$jadwal_out && ($v->status_absen==null || $v->status_absen=='IKS')){
                                    $total_PC1 = $PC->i +($PC->h*60);
                                    if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                                        if($absen_out <='12:00:00'){
                                            $total_PC=$total_PC1-60;
                                        }else if($absen_out >'12:00:00' && $absen_out <='13:00:00'){
                                            $selisih_menit = strtotime('13:00:00') - strtotime($absen_out);
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_PC=$total_PC1-$selisih_menit;
                                        }else {
                                            $total_PC=$total_PC1;
                                        }
                                    }else if($jadwal_in=='05:30:00'){
                                        if($absen_out <='09:30:00'){
                                            $total_PC=$total_PC1-60;
                                        }else if($absen_out >'09:30:00' && $absen_out <='10:30:00'){
                                            $selisih_menit = strtotime('10:30:00') - strtotime($absen_out);
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_PC=$total_PC1-$selisih_menit;
                                        }else {
                                            $total_PC=$total_PC1;
                                        }
                                    }else if($jadwal_in=='06:00:00'){
                                        if($absen_out <='10:00:00'){
                                            $total_PC=$total_PC1-60;
                                        }
                                        else if($absen_out >'10:00:00' && $absen_out <='11:00:00'){
                                            $selisih_menit = strtotime('11:00:00') - strtotime($absen_out);
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_PC=$total_PC1-$selisih_menit;
                                        }
                                        else {
                                            $total_PC=$total_PC1;
                                        }
                                    }else if($jadwal_in=='13:00:00'){
                                        if($absen_out <='17:00:00'){
                                            $total_PC=$total_PC1-60;
                                        }
                                        else if($absen_out >'17:00:00' && $absen_out <='18:00:00'){
                                            $selisih_menit = strtotime('18:00:00') - strtotime($absen_out);
                                            $selisih_menit = round($selisih_menit / 60);
                                            $total_PC=$total_PC1-$selisih_menit;
                                        }
                                        else {
                                            $total_PC=$total_PC1;
                                        }
                                    }else{
                                        $total_PC=$total_PC1;
                                    }
                                    $total_PC = $total_PC < 480 ? $total_PC : 480;
                                }else{
                                    $total_PC=0;
                                }

                                $jumlah_menit_absen_dtpc=$total_DT+$total_PC;
                                if( $absen_in!=null && $absen_out !=null){
                                    $jumlah_absen_menit_kerja=$durasi_kerja_menit-$jumlah_menit_absen_dtpc;
                                }
                                else{
                                    $jumlah_absen_menit_kerja=0;
                                }

                                $jumlah_menit_absen_dtpc=$total_DT+$total_PC;
                                $data_update=[
                                    'jumlah_menit_absen_dtpc'=>$jumlah_menit_absen_dtpc,
                                    'jumlah_absen_menit_kerja'=>$durasi_kerja_menit-$jumlah_menit_absen_dtpc,
                                    'jumlah_menit_absen_dt'=>$total_DT,
                                    'jumlah_menit_absen_pc'=>$total_PC,
                                    'tanggal_berjalan'=>$v->tanggal_berjalan,
                                    'enroll_id'=>$v->enroll_id,
                                ];
                                MasterDataAbsenKehadiran::where('tanggal_berjalan', $v->tanggal_berjalan)->where('enroll_id', $v->enroll_id)->update($data_update);
                            }
                        }
                    }
                }
            }


            // foreach($data_update as $value){
            //     MasterDataAbsenKehadiran::where('tanggal_berjalan', $value['tanggal_berjalan'])->where('enroll_id', $value['enroll_id'])
            //     ->update([
            //         'jumlah_menit_absen_dtpc'=>$value['jumlah_menit_absen_dtpc'],
            //         'jumlah_absen_menit_kerja'=>$value['jumlah_absen_menit_kerja'],
            //         'jumlah_menit_absen_dt'=>$value['jumlah_menit_absen_dt'],
            //         'jumlah_menit_absen_pc'=>$value['jumlah_menit_absen_pc'],
            //     ]);
            // }


            $setClearMTL = MasterDataAbsenKehadiran::selectRaw("
                substr(tanggal_berjalan,1, 10) tanggal_absen,
                enroll_id,
                substr(absen_masuk_kerja, 1, 5) absen_in,
                substr(absen_pulang_kerja, 1, 5) absen_out,
                null status_absen
            ")->whereRaw('absen_masuk_kerja is not null AND absen_pulang_kerja is not null
                AND status_absen in ("M", "TL") AND tanggal_berjalan >= "' . $tanggal_awal . '" and tanggal_berjalan <= "'.$tanggal_akhir.'"'.$inEnrollsId.'
            ')->where(function($query){
                $query->whereColumn('mulai_jam_kerja','<','akhir_jam_kerja')->orWhereNull('mulai_jam_kerja');
            })->get();
            foreach($setClearMTL as $value) {
                $countEditedData2=DataKehadiranInOutEdited::where('tanggal_absen','=', $value->tanggal_absen)->where('enroll_id','=', $value->enroll_id)->count();
                $count2=LogDataGagalAbsen::where('tanggal_absen',$value->tanggal_absen)->where('enroll_id','=', $value->enroll_id)->count();

                if($countEditedData2<1 && $count2<1){
                    MasterDataAbsenKehadiran::where('tanggal_berjalan', $value->tanggal_absen)
                    ->where('enroll_id', $value->enroll_id)
                    ->update([
                        'status_absen' => $value->status_absen
                    ]);
                }
            }
            $setSetTL = MasterDataAbsenKehadiran::selectRaw("
                substr(tanggal_berjalan,1, 10) tanggal_absen,
                enroll_id,
                substr(absen_masuk_kerja, 1, 5) absen_in,
                substr(absen_pulang_kerja, 1, 5) absen_out,
                'TL' status_absen
            ")
            ->whereRaw('
                tanggal_berjalan >= "' . $tanggal_awal . '" and tanggal_berjalan <= "'.$tanggal_akhir.'"'.$inEnrollsId.'
                AND status_absen in ("M", "TL")
                AND ((absen_masuk_kerja is null AND absen_pulang_kerja is not null) OR (absen_masuk_kerja is not null AND absen_pulang_kerja is null))
            ')->where(function($query){
                $query->whereColumn('mulai_jam_kerja','<','akhir_jam_kerja')->orWhereNull('mulai_jam_kerja');
            })->get();

            foreach($setSetTL as $value) {
                $countEditedData3=DataKehadiranInOutEdited::where('tanggal_absen', $value->tanggal_absen)->where('enroll_id', $value->enroll_id)->count();
                $count3=LogDataGagalAbsen::where('tanggal_absen', $value->tanggal_absen)->where('enroll_id', $value->enroll_id)->count();

                if($countEditedData3<1 && $count3<1){
                    MasterDataAbsenKehadiran::where('tanggal_berjalan', $value->tanggal_absen)
                    ->where('enroll_id', $value->enroll_id)
                    ->update([
                        'status_absen' => $value->status_absen
                    ]);
                }
            }

            $checkinoutAtt =  DB::connection('sqlsrv2')->select(
                DB::raw("
                    SELECT
                        NEWID() uuid,
                        CONVERT( VARCHAR ( 10 ), a.CHECKTIME, 126 ) AS tanggal_absen,
                        b.Badgenumber AS enroll_id,
                        MIN ( CONVERT ( VARCHAR ( 5 ), a.CHECKTIME, 114 ) ) AS absen_in,
                        MAX ( CONVERT ( VARCHAR ( 5 ), a.CHECKTIME, 114 ) ) AS absen_out,
                        null type
                    FROM
                        CHECKINOUT a join	USERINFO b on (a.USERID = b.USERID)
                    WHERE
                        CONVERT ( VARCHAR ( 10 ), a.CHECKTIME, 126 ) >= '" . $tanggal_awal . "' and CONVERT ( VARCHAR ( 10 ), a.CHECKTIME, 126 ) <= '".$tanggal_akhir."'".$inEnrollId."
                    GROUP BY
                        CONVERT( VARCHAR ( 10 ), a.CHECKTIME, 126 ),
                        b.Badgenumber,
                        a.CHECKTYPE
                    ORDER BY
                        CONVERT( VARCHAR ( 10 ), a.CHECKTIME, 126 ) asc
                ") );

            foreach($checkinoutAtt as $value) {
                $countEditedData4=DataKehadiranInOutEdited::where('tanggal_absen', $value->tanggal_absen)->where('enroll_id', $value->enroll_id)->count();
                $count4=LogDataGagalAbsen::where('tanggal_absen', $value->tanggal_absen)->where('enroll_id', $value->enroll_id)->count();
                if($countEditedData4<1 && $count4<1){
                    $checkinoutAttCount = CheckInOut::whereRaw("
                        tanggal_absen = '" . $value->tanggal_absen . "'
                        AND enroll_id = '" . $value->enroll_id . "'
                    ")
                    ->count();

                    if($checkinoutAttCount > 0) {
                        CheckInOut::where('tanggal_absen', $value->tanggal_absen)
                        ->where('enroll_id', $value->enroll_id)
                        ->update([
                            'absen_in' => $value->absen_in,
                            'absen_out' => $value->absen_out,
                            'type' => $value->type
                        ]);
                    } else {
                        CheckInOut::create([
                            'uuid' => $value->uuid,
                            'tanggal_absen' => $value->tanggal_absen,
                            'enroll_id' => $value->enroll_id,
                            'absen_in' => $value->absen_in,
                            'absen_out' => $value->absen_out,
                            'type' => $value->type
                        ]);
                    }
                }
            }
            return $arr_list;
        }
    }

    public function download_mesin_kehadiran_lintas(Request $request)
    {


        $daterange = explode(" - ", $request->periode_absen);
        $tanggal_awal = date('Y-m-d', strtotime($daterange[0]));
        $tanggal_akhir = date('Y-m-d', strtotime($daterange[1]));
        $enroll_id = $request->selectEmployeeID;
        $inEnrollId='';
        $inEnrollsId='';
        if($enroll_id){
            $implodeEnrollId=implode(",", $enroll_id);
            $allEnroll_id= '('.$implodeEnrollId.')';
            $inEnrollId = ' AND enroll_id IN '.$allEnroll_id.'';
            $inEnrollsId = ' AND b.Badgenumber IN '.$allEnroll_id.'';
        }
        $kehadiran=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->whereRaw('tanggal_berjalan is not null'.$inEnrollId)
        ->where(function($query){
            $query->whereColumn('mulai_jam_kerja','>','akhir_jam_kerja')
            ->orWhereNull('mulai_jam_kerja');
        })->get();
        if(count($kehadiran) > 0 ) {
            $query = DB::connection('sqlsrv2')->table('CHECKINOUT as a')
            ->selectRaw("CONVERT(VARCHAR(10), a.CHECKTIME, 126) AS tanggal_absen,
                        b.Badgenumber AS enroll_id,
                        CONVERT(VARCHAR(5), a.CHECKTIME, 114) AS absen_log")
            ->join('USERINFO as b', 'a.USERID', '=', 'b.USERID')
            ->whereDate('a.CHECKTIME', '>=', $tanggal_awal)
            ->whereDate('a.CHECKTIME', '<=', date('Y-m-d', strtotime('+1 days', strtotime($tanggal_akhir))))
            ->whereRaw('a.CHECKTIME is not null '.$inEnrollsId)
            ->get();
            $results=collect($query)->groupBy(['tanggal_absen','enroll_id','absen_log']);
            $records=[];
            foreach ($results as $key1 => $value1) {
                foreach ($value1 as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                    $records[]=[
                        'tanggal_absen'=> $key1,
                        'enroll_id'=> $key2,
                        'absen_log'=> $key3,
                    ];
                    }
                }
            }
            foreach ($kehadiran as $key4 => $value4) {
                $countEditedData=DataKehadiranInOutEdited::where('tanggal_absen','=', $value4->tanggal_berjalan)->where('enroll_id','=', $value4->enroll_id)->count();
                $count=LogDataGagalAbsen::where('tanggal_absen', $value4->tanggal_berjalan)->where('enroll_id',$value4->enroll_id)->count();
                if($countEditedData<1 && $count<1){
                    // if((($value4->operator=='system') || ($value4->operator=='system_lintashari') || ($value4->operator=='system_injek_lebaran') )) {
                    if($value4->status_absen == "TL" || $value4->status_absen == "M" || $value4->status_absen == "IKS" || $value4->status_absen == "" || !$value4->status_absen|| $value4->status_absen == "LN" || $value4->status_absen == "LP" || $value4->status_absen == "CT" || $value4->status_absen == "L" || $value4->status_absen=='S') {
                        $tanggal_berjalan=$value4->tanggal_berjalan;
                        $jadwal_in=$value4->mulai_jam_kerja;
                        $jadwal_out=$value4->akhir_jam_kerja;
                        $nomor_form_lembur=$value4->nomor_form_lembur;
                        $jadwal_in_min=date("H:i", strtotime('-4 hours', strtotime($jadwal_in)));
                        $jadwal_in_max='23:59';

                        $jadwal_out_min=date("00:00");
                        $jadwal_out_max=date("H:i", strtotime('+1 hours 59 minutes', strtotime($jadwal_out)));
                        $jadwal_out_max2=date("H:i", strtotime('+7 hours 59 minutes', strtotime($jadwal_out)));

                        $tanggal_besok= date('Y-m-d', strtotime('+1 days', strtotime($value4->tanggal_berjalan)));
                        $tanggal_kemarin= date('Y-m-d', strtotime('-1 days', strtotime($value4->tanggal_berjalan)));
                        if($jadwal_in>$jadwal_out){
                            if($value4->nomor_form_lembur!=null){
                                $absenIn=collect($records)->where('tanggal_absen',$value4->tanggal_berjalan)->where('enroll_id',$value4->enroll_id)
                                ->where('absen_log','>=', $jadwal_in_min)->where('absen_log','<=', $jadwal_in_max)->min('absen_log');
                                // dd($absenIn);
                                $absenOut=collect($records)->where('tanggal_absen',$tanggal_besok)->where('enroll_id',$value4->enroll_id)
                                    ->where('absen_log','>=', $jadwal_out_min)->where('absen_log','<=', $jadwal_out_max2)->max('absen_log');
                                if($absenIn==null && $absenOut!=null){
                                    $absenInBaru=collect($records)->where('tanggal_absen',$tanggal_besok)->where('enroll_id',$value4->enroll_id)
                                    ->where('absen_log','>=', '00:00')->where('absen_log','<=', $absenOut)->min('absen_log');
                                    $hourdiff = round((strtotime($absenOut) - strtotime($absenInBaru))/3600, 1);
                                    if($hourdiff>=1){
                                        $absenIn=$absenInBaru;
                                    }else{
                                        $absenIn=null;
                                    }
                                }
                                if($absenOut==null && $absenIn!=null){
                                    $absenOutBaru=collect($records)->where('tanggal_absen',$value4->tanggal_berjalan)->where('enroll_id',$value4->enroll_id)
                                    ->where('absen_log','<=', '24:00')->where('absen_log','>=', $absenIn)->max('absen_log');
                                    $hourdiff = round((strtotime($absenOutBaru) - strtotime($absenIn))/3600, 1);
                                    if($hourdiff>=1){
                                        $absenOut=$absenOutBaru;
                                    }else{
                                        $absenOut=null;
                                    }
                                }
                            }else{
                                $absenIn=collect($records)->where('tanggal_absen',$value4->tanggal_berjalan)->where('enroll_id',$value4->enroll_id)
                                ->where('absen_log','>=', $jadwal_in_min)->where('absen_log','<=', $jadwal_in_max)->min('absen_log');
                                // dd($absenIn);
                                $absenOut=collect($records)->where('tanggal_absen',$tanggal_besok)->where('enroll_id',$value4->enroll_id)
                                    ->where('absen_log','>=', $jadwal_out_min)->where('absen_log','<=', $jadwal_out_max2)->max('absen_log');
                                if($absenIn==null && $absenOut!=null){
                                    $absenInBaru=collect($records)->where('tanggal_absen',$tanggal_besok)->where('enroll_id',$value4->enroll_id)
                                    ->where('absen_log','>=', '00:00')->where('absen_log','<=', $absenOut)->min('absen_log');
                                    $hourdiff = round((strtotime($absenOut) - strtotime($absenInBaru))/3600, 1);
                                    if($hourdiff>=1){
                                        $absenIn=$absenInBaru;
                                    }else{
                                        $absenIn=null;
                                    }
                                }
                                if($absenOut==null && $absenIn!=null){
                                    $absenOutBaru=collect($records)->where('tanggal_absen',$value4->tanggal_berjalan)->where('enroll_id',$value4->enroll_id)
                                    ->where('absen_log','<=', '24:00')->where('absen_log','>=', $absenIn)->max('absen_log');
                                    $hourdiff = round((strtotime($absenOutBaru) - strtotime($absenIn))/3600, 1);
                                    if($hourdiff>=1){
                                        $absenOut=$absenOutBaru;
                                    }else{
                                        $absenOut=null;
                                    }
                                }
                            }
                        }
                        else if($jadwal_in==null && $jadwal_out==null){
                            if($value4->nomor_form_lembur!=null){
                                // $jadwal_in_lembur=MasterDataAbsenKehadiran::where('tanggal_berjalan',$value4->tanggal_berjalan)->where('enroll_id',$value4->enroll_id)->pluck('mulai_jam_lembur')[0];
                                $jadwal_in_lembur = MasterDataAbsenKehadiran::leftJoin(
                                    'data_lembur',
                                    'master_data_absen_kehadiran.uuid',
                                    '=',
                                    'data_lembur.uuid_master'
                                )
                                ->where('master_data_absen_kehadiran.tanggal_berjalan', $value4->tanggal_berjalan)
                                ->where('master_data_absen_kehadiran.enroll_id', $value4->enroll_id)
                                ->pluck('data_lembur.mulai_jam_lembur')
                                ->first();
                                $in_lembur=substr($jadwal_in_lembur,11,5);
                                // $jadwal_out_lembur=MasterDataAbsenKehadiran::where('tanggal_berjalan',$value4->tanggal_berjalan)->where('enroll_id',$value4->enroll_id)->pluck('akhir_jam_lembur')[0];
                                $jadwal_out_lembur = MasterDataAbsenKehadiran::leftJoin(
                                    'data_lembur',
                                    'master_data_absen_kehadiran.uuid',
                                    '=',
                                    'data_lembur.uuid_master'
                                )
                                ->where('master_data_absen_kehadiran.tanggal_berjalan', $value4->tanggal_berjalan)
                                ->where('master_data_absen_kehadiran.enroll_id', $value4->enroll_id)
                                ->pluck('data_lembur.akhir_jam_lembur')
                                ->first();
                                $out_lembur=substr($jadwal_out_lembur,11,5);
                                if($out_lembur<$in_lembur){
                                    $out_lembur=substr($jadwal_out_lembur,11,5);
                                    $in_lembur_min=date("H:i", strtotime('-2 hours', strtotime($in_lembur)));
                                    $in_lembur_max=date("H:i", strtotime('+1 hours 59 minutes', strtotime($in_lembur)));
                                    $out_lembur_min=date("H:i", strtotime('-2 hours', strtotime($out_lembur)));
                                    $out_lembur_max=date("H:i", strtotime('+1 hours 59 minutes', strtotime($out_lembur)));
                                    $absenIn=collect($records)->where('tanggal_absen',substr($jadwal_in_lembur,0,10))->where('enroll_id',$value4->enroll_id)
                                    ->where('absen_log','>=', $in_lembur_min)->where('absen_log','<=', $in_lembur_max)->min('absen_log');
                                    $absenOut=collect($records)->where('tanggal_absen',substr($jadwal_out_lembur,0,10))->where('enroll_id',$value4->enroll_id)
                                    ->where('absen_log','>=', $out_lembur_min)->where('absen_log','<=', $out_lembur_max)->max('absen_log');
                                    if($absenIn==null && $absenOut!=null){
                                        $absenInBaru=collect($records)->where('tanggal_absen',$tanggal_besok)->where('enroll_id',$value4->enroll_id)
                                        ->where('absen_log','>=', '00:00')->where('absen_log','<=', $absenOut)->min('absen_log');
                                        $hourdiff = round((strtotime($absenOut) - strtotime($absenInBaru))/3600, 1);
                                        if($hourdiff>=1){
                                            $absenIn=$absenInBaru;
                                        }else{
                                            $absenIn=null;
                                        }
                                    }
                                    if($absenOut==null && $absenIn!=null){
                                        $absenOutBaru=collect($records)->where('tanggal_absen',$value4->tanggal_berjalan)->where('enroll_id',$value4->enroll_id)
                                        ->where('absen_log','<=', '24:00')->where('absen_log','>=', $absenIn)->max('absen_log');
                                        $hourdiff = round((strtotime($absenOutBaru) - strtotime($absenIn))/3600, 1);
                                        if($hourdiff>=1){
                                            $absenOut=$absenOutBaru;
                                        }else{
                                            $absenOut=null;
                                        }
                                    }
                                }
                                else{
                                    continue;
                                }
                            }else if($value4->nomor_form_lembur==null){
                                $mulai_jam_kerja_kemarin=MasterDataAbsenKehadiran::where('tanggal_berjalan','<', $value4->tanggal_berjalan)->where('enroll_id', $value4->enroll_id)->whereNotNull('mulai_jam_kerja')->orderBy('tanggal_berjalan','DESC')->limit(1)->first()->mulai_jam_kerja;
                                $akhir_jam_kerja_kemarin=MasterDataAbsenKehadiran::where('tanggal_berjalan','<', $value4->tanggal_berjalan)->where('enroll_id', $value4->enroll_id)->whereNotNull('akhir_jam_kerja')->orderBy('tanggal_berjalan','DESC')->limit(1)->first()->akhir_jam_kerja;
                                if($mulai_jam_kerja_kemarin>$akhir_jam_kerja_kemarin){
                                    $in_lembur_min=date("H:i", strtotime('-2 hours', strtotime($mulai_jam_kerja_kemarin)));
                                    $in_lembur_max=date("H:i", strtotime('+1 hours 59 minutes', strtotime($mulai_jam_kerja_kemarin)));
                                    $out_lembur_min=date("H:i", strtotime('-2 hours', strtotime($akhir_jam_kerja_kemarin)));
                                    $out_lembur_max=date("H:i", strtotime('+3 hours 59 minutes', strtotime($akhir_jam_kerja_kemarin)));
                                    $absenIn=collect($records)->where('tanggal_absen',$value4->tanggal_berjalan)->where('enroll_id',$value4->enroll_id)->where('absen_log','>=', $in_lembur_min)->where('absen_log','<=', $in_lembur_max)->min('absen_log');
                                    $absenOut=collect($records)->where('tanggal_absen',$tanggal_besok)->where('enroll_id',$value4->enroll_id)->where('absen_log','>=', $out_lembur_min)->where('absen_log','<=', $out_lembur_max)->max('absen_log');
                                    if($absenIn==null && $absenOut!=null){
                                        $absenInBaru=collect($records)->where('tanggal_absen',$tanggal_besok)->where('enroll_id',$value4->enroll_id)
                                        ->where('absen_log','>=', '00:00')->where('absen_log','<=', $absenOut)->min('absen_log');
                                        $hourdiff = round((strtotime($absenOut) - strtotime($absenInBaru))/3600, 1);
                                        if($hourdiff>=1){
                                            $absenIn=$absenInBaru;
                                        }else{
                                            $absenIn=null;
                                        }
                                    }
                                    if($absenOut==null && $absenIn!=null){
                                        $absenOutBaru=collect($records)->where('tanggal_absen',$value4->tanggal_berjalan)->where('enroll_id',$value4->enroll_id)
                                        ->where('absen_log','<=', '24:00')->where('absen_log','>', $in_lembur_min)->where('absen_log','<', $in_lembur_max)->where('absen_log','>',$absenIn)->max('absen_log');
                                        $hourdiff = round((strtotime($absenOutBaru) - strtotime($absenIn))/3600, 1);
                                        if($hourdiff>=1){
                                            $absenOut=$absenOutBaru;
                                        }else{
                                            $absenOut=null;
                                        }
                                    }else{
                                        if(isset(MasterDataAbsenKehadiran::where('enroll_id',$value4->enroll_id)->where('tanggal_berjalan','>',$value4->tanggal_berjalan)->where('mulai_jam_kerja','!=',null)->orderBy('tanggal_berjalan')->limit(1)->pluck('mulai_jam_kerja')[0]))
                                        {
                                            $mulai_jam_kerja_besok=MasterDataAbsenKehadiran::where('enroll_id',$value4->enroll_id)->where('tanggal_berjalan','>',$value4->tanggal_berjalan)->where('mulai_jam_kerja','!=',null)->orderBy('tanggal_berjalan')->limit(1)->pluck('mulai_jam_kerja')[0];
                                            $absenOutBesok=MasterDataAbsenKehadiran::where('enroll_id',$value4->enroll_id)->where('tanggal_berjalan','>',$value4->tanggal_berjalan)->where('mulai_jam_kerja','!=',null)->orderBy('tanggal_berjalan')->limit(1)->pluck('absen_masuk_kerja')[0];
                                            $absenOutBesokBaru=substr($absenOutBesok,0,5);
                                        }else{
                                            $absenOutBesokBaru='ijoijof';
                                        }
                                        if($absenOut==$absenOutBesokBaru){
                                            if($mulai_jam_kerja_besok!=null){
                                                $absenOut=null;
                                            }
                                        }else{
                                            $hourdiff = round((strtotime($absenOut) - strtotime($absenOutBesokBaru))/3600, 1);
                                            if($mulai_jam_kerja_besok!=null){
                                                $absenOut=null;
                                            }
                                        }
                                    }
                                }else{
                                    continue;
                                }
                            }
                            else{
                                $absenIn=null;
                                $absenOut=null;
                            }
                        }
                        if($value4->status_absen == "LN" || $value4->status_absen == "LP" || $value4->status_absen=='S'){
                            if($value4->status_absen=='S' || $value4->status_absen=='LP'){
                                if( $absenIn!=null && $absenOut!=null && ($jadwal_in==null || $jadwal_in!=null)){
                                    $status_absen=null;
                                }
                                else if($absenIn!=null && $absenOut==null && ($jadwal_in==null || $jadwal_in!=null)){
                                    $status_absen=$value4->status_absen;
                                }
                                else if($absenIn==null && $absenOut!=null && ($jadwal_in==null || $jadwal_in!=null)){
                                    $status_absen=$value4->status_absen;
                                }
                                else if( $absenIn==null && $absenOut==null && $jadwal_in!=null){
                                    $status_absen=$value4->status_absen;
                                }
                                else if( $absenIn==null && $absenOut==null && $jadwal_in==null){
                                    $status_absen=null;
                                }else{
                                    $status_absen=$value4->status_absen;
                                }
                            }else{
                                $status_absen=$value4->status_absen;
                            }
                        }
                        else{
                            if( $absenIn!=null && $absenOut!=null && ($jadwal_in==null || $jadwal_in!=null)){
                                $status_absen=null;
                            }
                            else if($absenIn!=null && $absenOut==null && ($jadwal_in==null || $jadwal_in!=null)){
                                $status_absen='TL';
                            }
                            else if($absenIn==null && $absenOut!=null && ($jadwal_in==null || $jadwal_in!=null)){
                                $status_absen='TL';
                            }
                            else if( $absenIn==null && $absenOut==null && $jadwal_in!=null){
                                $status_absen='M';
                            }
                            else if( $absenIn==null && $absenOut==null && $jadwal_in==null){
                                $status_absen=null;
                            }
                            else{
                                $status_absen=$value4->status_absen;
                            }
                        }

                        $z=[
                            'enroll_id'=>$value4->enroll_id,
                            'tanggal_berjalan'=> $value4->tanggal_berjalan,
                            'absen_masuk_kerja' => $absenIn,
                            'absen_pulang_kerja' => $absenOut,
                            'status_absen' => $status_absen,
                            'operator'=>'system_lintashari'
                        ];

                    MasterDataAbsenKehadiran::where('uuid', $value4->uuid)->update( $z);
                    }
                }
            }
            // untuk hitung dt pc
            $kehadiran2=MasterDataAbsenKehadiran::where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->whereRaw('tanggal_berjalan is not null'.$inEnrollId)->whereColumn('mulai_jam_kerja','>','akhir_jam_kerja')->with('employee_atribut')->get();
            $data_update=[];
            foreach ($kehadiran2 as $k => $v) {
                $countEditedData2=DataKehadiranInOutEdited::where('tanggal_absen','=',  $v->tanggal_berjalan)->where('enroll_id','=', $v->enroll_id)->count();
                $count2=LogDataGagalAbsen::where('tanggal_absen', $v->tanggal_berjalan)->where('enroll_id',$v->enroll_id)->count();
                if($countEditedData2<1 && $count2<1){
                    $status_staff=$v->employee_atribut->status_staff;
                    $jadwal_in=$v->mulai_jam_kerja;
                    $jadwal_out=$v->akhir_jam_kerja;

                    $absen_in=$v->absen_masuk_kerja;
                    $absen_out=$v->absen_pulang_kerja;

                    $durasi_kerja=date_diff(date_create($jadwal_in),date_create($jadwal_out));
                    $durasi_kerja_menit=$durasi_kerja->i +($durasi_kerja->h*60);

                    $DT = date_diff(date_create($jadwal_in),date_create($absen_in));
                    $DT2= date_diff(date_create($jadwal_in),date_create('24:00'));
                    $DT3= date_diff(date_create('01:00'),date_create($absen_in));
                    $PC = date_diff(date_create($jadwal_out),date_create($absen_out));
                    $PC2 = date_diff(date_create('01:00'),date_create($jadwal_out));
                    $PC3 = date_diff(date_create('24:00'),date_create($absen_out));
                    if($absen_in>'00:00' && $absen_in<='01:00' && $absen_in<$jadwal_out){
                        $total_DT = $DT2->i +($DT2->h*60);
                    }else if($absen_in>'01:00' && $absen_in<$jadwal_out){
                        $total_DT = $DT3->i + ($DT3->h*60)+$DT2->i +($DT2->h*60);
                    }else{
                        if( $absen_in!=null && $absen_in>$jadwal_in ){
                            $total_DT = $DT->i +($DT->h*60);
                        }else{
                            $total_DT=0;
                        }
                    }
                    if( $absen_out>'00:00' && $absen_out<='01:00' && $absen_out<$jadwal_out){
                        $total_PC = $PC2->i +($PC2->h*60);
                    }else if( $absen_out<='24:00' && $absen_out>$jadwal_in){
                        $total_PC = $PC2->i +($PC2->h*60)+$PC3->i +($PC3->h*60);
                    }else{
                        if( $absen_in!=null && $absen_out<$jadwal_out && $absen_out!=null){
                            $total_PC = $PC->i +($PC->h*60);
                        }else{
                            $total_PC=0;
                        }
                    }

                    $jumlah_menit_absen_dtpc=$total_DT+$total_PC;
                    if( $absen_in!=null && $absen_out !=null){
                        $jumlah_absen_menit_kerja=$durasi_kerja_menit-$jumlah_menit_absen_dtpc;
                        if($v->status_absen=='LN'){
                            $total_DT=0;
                            $total_PC=0;
                        }
                    }
                    else{
                        $jumlah_absen_menit_kerja=0;
                    }
                    if($total_DT<=10 && $status_staff=='STAFF'){
                        $total_DT=0;
                    }
                    $jumlah_menit_absen_dtpc=$total_DT+$total_PC;
                    $data_update=[
                        'jumlah_menit_absen_dtpc'=>$jumlah_menit_absen_dtpc,
                        'jumlah_absen_menit_kerja'=>$durasi_kerja_menit-$jumlah_menit_absen_dtpc,
                        'jumlah_menit_absen_dt'=>$total_DT,
                        'jumlah_menit_absen_pc'=>$total_PC,
                    ];
                    MasterDataAbsenKehadiran::where('uuid', $v->uuid)->update( $data_update);
                }
            }
        }
    }

    public function ajax_getdashkehadiran()
    {
        $query = DB::select(DB::raw("
            SELECT
            CURRENT_DATE() AS tanggal_hari_ini,
            DAYOFWEEK(CURRENT_DATE()) AS kode_hari,
            DAYNAME(CURRENT_DATE()) AS nama_hari,

            FORMAT(COUNT(DISTINCT IF(a.absen_masuk_kerja IS NOT NULL, b.enroll_id, NULL)), 0) AS jumlah_karyawan_masuk,
            COUNT(DISTINCT IF(a.absen_masuk_kerja IS NOT NULL, b.enroll_id, NULL)) AS jumlah_karyawan_masuk_number,

            COUNT(DISTINCT b.enroll_id) AS total_karyawan_number,
            FORMAT(COUNT(DISTINCT b.enroll_id), 0) AS total_karyawan_aktif,

            FORMAT(
                IFNULL(
                    ROUND(
                        (COUNT(DISTINCT IF(a.absen_masuk_kerja IS NOT NULL, b.enroll_id, NULL)) / COUNT(DISTINCT b.enroll_id)) * 100,
                        2
                    ),
                0), 2
            ) AS persentase_kehadiran,

            FORMAT(
                IFNULL(
                    100 - ROUND(
                        (COUNT(DISTINCT IF(a.absen_masuk_kerja IS NOT NULL, b.enroll_id, NULL)) / COUNT(DISTINCT b.enroll_id)) * 100,
                        2
                    ),
                0), 2
            ) AS persentase_ketidakhadiran,

            SUM(c.jumlah_staff) AS jumlah_staff_number,
            SUM(d.jumlah_nonstaff) AS jumlah_nonstaff_number,
            FORMAT(IFNULL(SUM(c.jumlah_staff), 0), 0) AS jumlah_staff,
            FORMAT(IFNULL(SUM(d.jumlah_nonstaff), 0), 0) AS jumlah_nonstaff,

            FORMAT(IFNULL(SUM(e.absen_tl), 0), 0) AS absen_tl_hari_kemarin,
            FORMAT(IFNULL(SUM(f.absen_m_weekly), 0), 0) AS absen_m_weekly,
            FORMAT(IFNULL(SUM(g.absen_m_hari_ini), 0), 0) AS absen_m_hari_ini

            FROM master_data_absen_kehadiran a
            JOIN employee_atribut b
                ON a.enroll_id = b.enroll_id AND b.status_aktif = 'AKTIF'

            LEFT JOIN (
                SELECT enroll_id, COUNT(*) AS jumlah_staff
                FROM employee_atribut
                WHERE status_staff = 'STAFF' AND status_aktif = 'AKTIF'
                GROUP BY enroll_id
            ) c ON b.enroll_id = c.enroll_id

            LEFT JOIN (
                SELECT enroll_id, COUNT(*) AS jumlah_nonstaff
                FROM employee_atribut
                WHERE status_staff = 'NON STAFF' AND status_aktif = 'AKTIF'
                GROUP BY enroll_id
            ) d ON b.enroll_id = d.enroll_id

            LEFT JOIN (
                SELECT enroll_id, COUNT(*) AS absen_tl
                FROM master_data_absen_kehadiran
                WHERE status_absen = 'TL' AND tanggal_berjalan = CURRENT_DATE() - INTERVAL 1 DAY
                GROUP BY enroll_id
            ) e ON b.enroll_id = e.enroll_id

            LEFT JOIN (
                SELECT enroll_id, COUNT(*) AS absen_m_weekly
                FROM master_data_absen_kehadiran
                WHERE status_absen = 'M'
                AND tanggal_berjalan BETWEEN CURRENT_DATE() - INTERVAL 7 DAY AND CURRENT_DATE()
                GROUP BY enroll_id
            ) f ON b.enroll_id = f.enroll_id

            LEFT JOIN (
                SELECT enroll_id, COUNT(*) AS absen_m_hari_ini
                FROM master_data_absen_kehadiran
                WHERE status_absen = 'M' AND tanggal_berjalan = CURRENT_DATE()
                GROUP BY enroll_id
            ) g ON b.enroll_id = g.enroll_id

            WHERE a.tanggal_berjalan = CURRENT_DATE()
            AND (a.absen_masuk_kerja IS NOT NULL OR b.tanggal_resign IS NULL OR b.tanggal_resign <> '0000-00-00')
            GROUP BY CURRENT_DATE(), DAYOFWEEK(CURRENT_DATE()), DAYNAME(CURRENT_DATE());
        "));
        $data_factory = $this->ajax_getdashkehadiran_by_factory();
        return ['data' => $query,
            'data_factory' => $data_factory];
    }

   public function ajax_getdashkehadiran_by_factory()
    {
        $results = DB::select(DB::raw("
            SELECT
                site_nirwana_id,
                status_staff,
                COUNT(enroll_id) as total
            FROM employee_atribut
            WHERE status_aktif = 'AKTIF'
            AND site_nirwana_id IN ('NAK', 'GS', 'SA', 'NAGD')
            GROUP BY site_nirwana_id, status_staff
        "));
        // Ubah hasil query menjadi struktur array terorganisir per site
        $structured = [];

        foreach ($results as $row) {
            $site = $row->site_nirwana_id;
            $status = strtolower(str_replace(' ', '_', trim($row->status_staff)));


            if (!isset($structured[$site])) {
                $structured[$site] = [
                    'staff' => 0,
                    'non_staff' => 0
                ];
            }

            $structured[$site][$status] = (int) $row->total;
        }

        return $structured;
    }


    public function ajax_getTanggalKehadiranSekarang()
    {
        setlocale(LC_ALL, 'id-ID', 'id_ID');
        $tanggalKehadiranSekarang = '<span><i class="fa fa-calendar"></i></span> TERAKHIR DIUPDATE : ' . strtoupper(strftime("%A", strtotime(date("Y-m-d H:i:s")))) . ', ' . strtoupper(strftime("%d %b %Y", strtotime(date("Y-m-d H:i:s")))) . ' PUKUL ' . strtoupper(strftime("%H:%M", strtotime(date("H:i:s"))));

        echo json_encode($tanggalKehadiranSekarang);
    }

    /*    Screen lock controller.When screen lock button from menu is cliked this controller is called.
    *     lock variable is set to 1 when screen is locked.SET to 0  if you dont want screen variable
    */

    public function screenlock()
    {
        Session::put('lock', '1');
        return View::make('admin/screen_lock', $this->data);
    }

    public function edit_jadwal(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $jadwal_in=$request->mulai_jam_kerja;
        $jadwal_out=$request->akhir_jam_kerja;

        $absen_in=$request->absen_masuk_kerja;
        $absen_out=$request->absen_pulang_kerja;

        $enroll_id=$request->enroll_id;
        $tanggal_berjalan=$request->tanggal_berjalan;
        $status_absen=$request->status_absen;

        $durasi_kerja=date_diff(date_create($jadwal_in),date_create($jadwal_out));
        $durasi_kerja_menit=$durasi_kerja->i +($durasi_kerja->h*60);

        $DT = date_diff(date_create($jadwal_in),date_create($absen_in));
        $PC = date_diff(date_create($jadwal_out),date_create($absen_out));

        if( $jadwal_in!=null && $absen_in!=null && $absen_in>$jadwal_in && $status_absen==null){
            $total_DT1 = $DT->i +($DT->h*60);
            if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                if($absen_in >'13:00:00'){
                    $total_DT=$total_DT1-60;
                }
                else if($absen_in >'12:00:00' && $absen_in <='13:00:00'){
                    $selisih_menit = strtotime($absen_in) - strtotime('12:00:00');
                    $selisih_menit = round($selisih_menit / 60);
                    $total_DT=$total_DT1-$selisih_menit;
                }
                else {
                    $total_DT=$total_DT1;
                }
            }else{
                $total_DT=$total_DT1;
            }
            $total_DT = $total_DT < 480 ? $total_DT : 480;
        }else{
            $total_DT=0;
        }

        if( $jadwal_out !=null && $absen_out !=null && $absen_out<$jadwal_out && $status_absen==null){
            $total_PC1 = $PC->i +($PC->h*60);
            if($jadwal_in=='07:00:00' || $jadwal_in=='07:30:00'){
                if($absen_out <='12:00:00'){
                    $total_PC=$total_PC1-60;
                }
                else if($absen_out >'12:00:00' && $absen_out <='13:00:00'){
                    $selisih_menit = strtotime('13:00:00') - strtotime($absen_out);
                    $selisih_menit = round($selisih_menit / 60);
                    $total_PC=$total_PC1-$selisih_menit;
                }
                else {
                    $total_PC=$total_PC1;
                }
            }else{
                $total_PC=$total_PC1;
            }
            $total_PC = $total_PC < 480 ? $total_PC : 480;
        }else{
            $total_PC=0;
        }

        $jumlah_menit_absen_dtpc=$total_DT+$total_PC;
        if( $absen_in!=null && $absen_out !=null){
            $jumlah_absen_menit_kerja=$durasi_kerja_menit-$jumlah_menit_absen_dtpc;
        }
        else{
            $jumlah_absen_menit_kerja=0;
        }

        if($jadwal_in==null && $jadwal_out== null && $status_absen!='LN' ){
            $status_absen_new= null;
        }
        elseif($jadwal_in!=null && $jadwal_out!=null && $absen_in ==null && $absen_out==null ){
            $status_absen_new= 'M';
        }
        else{
            $status_absen_new= $status_absen;
        }

        $data_update=[
            'jumlah_menit_absen_dtpc'=>$jumlah_menit_absen_dtpc,
            'jumlah_absen_menit_kerja'=>$durasi_kerja_menit-$jumlah_menit_absen_dtpc,
            'jumlah_menit_absen_dt'=>$total_DT,
            'jumlah_menit_absen_pc'=>$total_PC,
            'status_absen'=>$status_absen_new,
            'akhir_jam_kerja'=>$request->akhir_jam_kerja,
            'mulai_jam_kerja'=>$request->mulai_jam_kerja,
        ];
        MasterDataAbsenKehadiran::where('tanggal_berjalan', $tanggal_berjalan)->where('enroll_id',$enroll_id)->update($data_update);

        $query1 = DataJadwalKerjaLog::create([
            'uuid' => Str::uuid(),
            'tanggal_berjalan' => $tanggal_berjalan,
            // 'kode_hari' => $value['kode_hari'],
            // 'nama_hari' => $value['nama_hari'],
            'enroll_id' => $enroll_id,
            'nik' => $request->nik,
            'employee_name' => $request->employee_name,
            'akhir_jam_kerja'=>$request->akhir_jam_kerja,
            'mulai_jam_kerja'=>$request->mulai_jam_kerja,
            'absen_masuk_kerja' => $request->absen_masuk_kerja,
            'absen_pulang_kerja' => $request->absen_pulang_kerja,
            'operator' => $email
        ]);

        return true;
    }

    public function export_excel(Request $request)
    {
        ini_set("max_execution_time", 3600);

        setlocale(LC_ALL, 'id-ID', 'id_ID');

        if($request->selectDepartment) {
            $selectDepartment = $request->selectDepartment;
        } else {
            $selectDepartment = "";
        }

        if($request->selectBagian) {
            $selectBagian = $request->selectBagian;
        } else {
            $selectBagian = "";
        }

        if($request->status_staff) {
            $status_staff = $request->status_staff;
        } else {
            $status_staff = "";
        }

        if($request->searchData) {
            $searchData = strtoupper($request->searchData);
        } else {
            $searchData = "";
        }

        if($request->daterange1) {
            $daterange1 = $request->daterange1;
        } else {
            $daterange1 = "";
        }

        $daterange1 = $daterange1;
        $daterange1 = explode(" s/d ", $daterange1);
        $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
        $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
        $tanggalMulai = $tanggalMulai;
        $tanggalSampai = $tanggalSampai;

        $selectDepartment = $selectDepartment;

        $status_staff = $status_staff;
        $searchData = strtoupper($searchData);

        $filterStaff = "";
        if($status_staff) {
            $filterStaff = " AND employee_atribut.status_staff = '" . $status_staff . "'";
        }

        $inDepartment = "";
        if($selectDepartment) {
            $inDepartment = ' AND employee_atribut.department_name = "' . $selectDepartment . '"';

        }

        $inBagian = "";
        if($selectBagian) {
            $inBagian = ' AND employee_atribut.sub_dept_name = "' . $selectBagian . '"';

        }

        $inSearchData = "";
        if($searchData) {
            $inSearchData = '
                AND (
                    UPPER(master_data_absen_kehadiran.enroll_id) LIKE ("%' . $searchData . '%")
                    OR UPPER(master_data_absen_kehadiran.nik) LIKE ("%' . $searchData . '%")
                    OR UPPER(master_data_absen_kehadiran.employee_name) LIKE ("%' . $searchData . '%")
                )
            ';

        }

        $dataAbsen = DB::select('
            SELECT
                master_data_absen_kehadiran.tanggal_berjalan,
                master_data_absen_kehadiran.kode_hari,
                master_data_absen_kehadiran.nama_hari,
                employee_atribut.nik,
                employee_atribut.enroll_id,
                employee_atribut.employee_name,
                employee_atribut.status_staff,
                employee_atribut.department_name,
                master_data_absen_kehadiran.mulai_jam_kerja,
                master_data_absen_kehadiran.akhir_jam_kerja,
                master_data_absen_kehadiran.absen_masuk_kerja,
                master_data_absen_kehadiran.absen_pulang_kerja,
                master_data_absen_kehadiran.jumlah_absen_menit_kerja,
                data_absen_perijinan.time_mulai_ijin as permits_dari_pukul,
                data_absen_perijinan.time_akhir_ijin as permits_sampai_pukul,
                data_absen_perijinan.total_time_ijin as total_menit_permits,
                master_data_absen_kehadiran.jumlah_menit_absen_dt,
                master_data_absen_kehadiran.jumlah_menit_absen_pc,
                master_data_absen_kehadiran.jumlah_menit_absen_dtpc,
                master_data_absen_kehadiran.status_absen,
                master_data_absen_kehadiran.catatan_hrd,
                master_data_absen_kehadiran.mulai_jam_lembur,
                master_data_absen_kehadiran.akhir_jam_lembur,
                substr( master_data_absen_kehadiran.jumlah_jam_lembur_approved, 1, 5 ) jumlah_jam_lembur_approved,
                substr( master_data_absen_kehadiran.jumlah_jam_istirahat_lembur, 1, 5 ) jumlah_jam_istirahat_lembur,
                rekap_perhitungan_lembur.nomor_form_lembur,
                rekap_perhitungan_lembur.final_mulai_jam_lembur,
                rekap_perhitungan_lembur.final_selesai_jam_lembur,
                rekap_perhitungan_lembur.final_total_jam_lembur,
                rekap_perhitungan_lembur.final_jam_istirahat_lembur,
                rekap_perhitungan_lembur.final_total_menit_lembur,
                rekap_perhitungan_lembur.final_jam_lembur_roundown,
                rekap_perhitungan_lembur.final_menit_lembur_roundown,
                rekap_perhitungan_lembur.lembur_1,
                rekap_perhitungan_lembur.lembur_2,
                rekap_perhitungan_lembur.lembur_3,
                rekap_perhitungan_lembur.lembur_4,
                rekap_perhitungan_lembur.total_lembur_1234,
                p.absen_alasan
            FROM
                `master_data_absen_kehadiran`
                LEFT JOIN `employee_atribut` ON `master_data_absen_kehadiran`.`enroll_id` = `employee_atribut`.`enroll_id`
                LEFT JOIN `department_all` ON `employee_atribut`.`sub_dept_id` = `department_all`.`sub_dept_id`
                LEFT JOIN `rekap_perhitungan_lembur` ON `master_data_absen_kehadiran`.`tanggal_berjalan` = `rekap_perhitungan_lembur`.`tanggal_berjalan`
                AND `master_data_absen_kehadiran`.`enroll_id` = `rekap_perhitungan_lembur`.`enroll_id`
                LEFT JOIN `data_absen_perijinan` p ON `master_data_absen_kehadiran`.`nomor_absen_ijin` = `p` . `nomor_form_perizinan`
            WHERE
                substr(master_data_absen_kehadiran.tanggal_berjalan, 1, 10) between "' . $tanggalMulai . '" and "' . $tanggalSampai . '"
                ' . $filterStaff . ' ' . $inDepartment . ' ' . $inBagian . ' ' . $inSearchData . '
            ORDER BY
                `employee_atribut`.`employee_name` ASC,
                `master_data_absen_kehadiran`.`tanggal_berjalan` ASC
        ');

        $excel = FastExcel::create('dataAbsen');
        $sheet = $excel->getSheet();

        // $area = $sheet->beginArea();

        $sheet->writeTo('A1', 'PT NIRWANA ALABARE GARMENT', ['font-size' => 18]);
        $sheet->writeTo('A2', 'LAPORAN ABSENSI KARYAWAN', ['font-size' => 16]);

        $sheet->writeTo('A3', 'TANGGAL ABSENSI : ' . strtoupper(strftime("%d %b %Y", strtotime($tanggalMulai)) . ' s/d ' . strftime("%d %b %Y", strtotime($tanggalSampai))), ['font-size' => 14]);
        $sheet->writeTo('A4', 'STAFF / NON STAFF : ' . ($status_staff ? $status_staff : "SEMUA KARYAWAN"), ['font-size' => 14]);
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');
        $sheet->mergeCells('A4:E4');
        $sheet->mergeCells('A6:A7');
        $sheet->writeTo('A6', 'TANGGAL');

        $sheet->mergeCells('B6:B7');
        $sheet->writeTo('B6', 'HARI');

        $sheet->mergeCells('C6:C7');
        $sheet->writeTo('C6', 'NIK');

        $sheet->mergeCells('D6:D7');
        $sheet->writeTo('D6', 'NO. ABSEN');

        $sheet->mergeCells('E6:E7');
        $sheet->writeTo('E6', 'NAMA KARYAWAN');

        $sheet->mergeCells('F6:F7');
        $sheet->writeTo('F6', 'STAFF / NON STAFF');

        $sheet->mergeCells('G6:G7');
        $sheet->writeTo('G6', 'DEPARTMENT');

        $sheet->mergeCells('H6:H7');
        $sheet->writeTo('H6', 'KERJA/LIBUR');

        $sheet->mergeCells('I6:L6');
        $sheet->writeTo('I6', 'JADWAL KERJA');
        $sheet->writeTo('I7', 'IN');
        $sheet->writeTo('J7', 'OUT');
        $sheet->writeTo('K7', 'DURASI ISTIRAHAT');
        $sheet->writeTo('L7', 'DURASI KERJA');

        $sheet->mergeCells('M6:O6');
        $sheet->writeTo('M6', 'ABSENSI');
        $sheet->writeTo('M7', 'IN');
        $sheet->writeTo('N7', 'OUT');
        $sheet->writeTo('O7', 'EFEKTIF KERJA');

        $sheet->mergeCells('P6:R6');
        $sheet->writeTo('P6', 'IJIN KELUAR SEMENTARA (IKS)');
        $sheet->writeTo('P7', 'DARI');
        $sheet->writeTo('Q7', 'SAMPAI');
        $sheet->writeTo('R7', 'TOTAL');

        $sheet->mergeCells('S6:U6');
        $sheet->writeTo('S6', 'POTONGAN MENIT');
        $sheet->writeTo('S7', 'DT');
        $sheet->writeTo('T7', 'PC');
        $sheet->writeTo('U7', 'Total');

        $sheet->mergeCells('V6:V7');
        $sheet->writeTo('V6', 'STATUS ABSEN');

        $sheet->mergeCells('W6:W7');
        $sheet->writeTo('W6', 'ALASAN ABSEN');

        $sheet->mergeCells('X6:X7');
        $sheet->writeTo('X6', 'KETERANGAN');

        $sheet->mergeCells('Y6:Y7');

        $sheet->mergeCells('Z6:AJ6');
        $sheet->writeTo('Z6', 'DATA LEMBUR (ACTUAL)');
        $sheet->writeTo('Z7', 'NO. SPL');
        $sheet->writeTo('AA7', 'MULAI');
        $sheet->writeTo('AB7', 'SELESAI');
        $sheet->writeTo('AC7', 'JUMLAH JAM');
        $sheet->writeTo('AD7', 'ISTIRAHAT');
        $sheet->writeTo('AE7', 'TOTAL LEMBUR');
        $sheet->writeTo('AF7', 'L1');
        $sheet->writeTo('AG7', 'L2');
        $sheet->writeTo('AH7', 'L3');
        $sheet->writeTo('AI7', 'L4');
        $sheet->writeTo('AJ7', 'TOTAL L');
        $sheet->mergeCells('AK6:AN6');
        $sheet->writeTo('AK6', 'DATA LEMBUR (PENGAJUAN)');
        $sheet->writeTo('AK7', 'MULAI JAM LEMBUR');
        $sheet->writeTo('AL7', 'AKHIR JAM LEMBUR');
        $sheet->writeTo('AM7', 'JUMLAH JAM LEMBUR');
        $sheet->writeTo('AN7', 'JUMLAH JAM ISTIRAHAT');

        $sheet->writeAreas();

        $sheet->setColOptions([
            'A' => ['format' => NumberFormat::FORMAT_DATE_DDMMYYYY, 'width' => 12],
            'E' => ['width' => 50],
            'K' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'L' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'O' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'P' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'R' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'S' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'T' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
            'U' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
            'V' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3],
            'AC' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'AD' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'AE' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'AF' => ['format' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED4],
            'AM' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'AN' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
            'AO' => ['format' => NumberFormat::FORMAT_DATE_TIME3],
        ]);

        foreach($dataAbsen as $Kehadiran) {
            $interval = date_diff(date_create(substr($Kehadiran->mulai_jam_kerja, 0, 5)), date_create(substr($Kehadiran->akhir_jam_kerja, 0, 5)));
            $minutes = $interval->days * 24 * 60;
            $minutes += $interval->h * 60;
            $minutes += $interval->i;
            $jumlah_menit_kerja = $minutes;
            $jumlah_menit_istirahat = 60;
            $kerjalibur = "KERJA";
            if(($Kehadiran->mulai_jam_kerja == null) || ($Kehadiran->status_absen == "LN" || $Kehadiran->status_absen == "CG" || $Kehadiran->status_absen == "CM" || $Kehadiran->status_absen == "CT" ||$Kehadiran->status_absen == "L") || (($Kehadiran->status_absen == "LP" ) && ($Kehadiran->absen_masuk_kerja==null) && ($Kehadiran->absen_pulang_kerja==null))) {
                $kerjalibur = "LIBUR";
            } else if(($Kehadiran->kode_hari=='6' || $Kehadiran->kode_hari=='5' ) && ($Kehadiran->mulai_jam_kerja!=null)){
                $kerjalibur = "KERJA";
            } else {
                if(($Kehadiran->absen_masuk_kerja <> null) || ($Kehadiran->absen_masuk_kerja <> "") || ($Kehadiran->absen_pulang_kerja <> null) || ($Kehadiran->absen_pulang_kerja <> "")) {
                    $kerjalibur = "KERJA";
                    switch ($Kehadiran->kode_hari) {
                        case '5':
                            $kerjalibur = "LIBUR";
                            $jumlah_menit_istirahat = 30;
                            break;
                        case '6':
                            $kerjalibur = "LIBUR";
                            $jumlah_menit_istirahat = 30;
                            break;
                    }
                }
            }

            $total_jam_lembur = "";
            if ($Kehadiran->final_total_jam_lembur == 0) {
                $total_jam_lembur = "";
            } else {
                $hms = $Kehadiran->final_total_jam_lembur;
                $final_total_jam_lembur = explode(":", $hms);
                $total_jam_lembur=intval($final_total_jam_lembur[0])+(intval($final_total_jam_lembur[1])/60);
            }

            $data = [
                Date::stringToExcel($Kehadiran->tanggal_berjalan),
                $Kehadiran->nama_hari,
                $Kehadiran->nik,
                $Kehadiran->enroll_id,
                $Kehadiran->employee_name,
                $Kehadiran->status_staff,
                $Kehadiran->department_name,
                $kerjalibur,
                $Kehadiran->mulai_jam_kerja,
                substr($Kehadiran->akhir_jam_kerja, 0, 5),
                $jumlah_menit_istirahat,
                $jumlah_menit_kerja,
                substr($Kehadiran->absen_masuk_kerja, 0, 5),
                substr($Kehadiran->absen_pulang_kerja, 0, 5),
                $Kehadiran->jumlah_absen_menit_kerja,
                substr($Kehadiran->permits_dari_pukul, 0, 5),
                substr($Kehadiran->permits_sampai_pukul, 0, 5),
                $Kehadiran->total_menit_permits,
                $Kehadiran->jumlah_menit_absen_dt,
                $Kehadiran->jumlah_menit_absen_pc,
                $Kehadiran->jumlah_menit_absen_dtpc,
                $Kehadiran->status_absen,
                $kehadiran->absen_alasan,
                $Kehadiran->catatan_hrd,
                "",
                $Kehadiran->nomor_form_lembur,
                $Kehadiran->final_mulai_jam_lembur,
                $Kehadiran->final_selesai_jam_lembur,
                $total_jam_lembur,
                $Kehadiran->final_jam_istirahat_lembur,
                $Kehadiran->final_jam_lembur_roundown,
                $Kehadiran->lembur_1,
                $Kehadiran->lembur_2,
                $Kehadiran->lembur_3,
                $Kehadiran->lembur_4,
                $Kehadiran->total_lembur_1234,
                $Kehadiran->mulai_jam_lembur,
                $Kehadiran->akhir_jam_lembur,
                $Kehadiran->jumlah_jam_lembur_approved,
                $Kehadiran->jumlah_jam_istirahat_lembur,
            ];

            $sheet->writeRow($data);
        }

        $excel->download('data_absensi.xlsx');
    }
}
