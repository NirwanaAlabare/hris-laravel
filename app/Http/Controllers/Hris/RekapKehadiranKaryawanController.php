<?php

namespace App\Http\Controllers\Hris;

use App\Exports\RekapKehadiranKaryawanExport;
use App\Http\Controllers\AdminBaseController;
use App\Models\RekapKehadiranKaryawan;
use App\Models\EmployeeAtribut;
use App\Models\DepartmentAll;
use App\Models\RefAbsenIjin;
use App\Models\MasterDataAbsenKehadiran;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Datatables;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Exports\DataRekapAbsenExport;
use App\Exports\SummaryReportExport;
use Carbon\Carbon;
use \avadim\FastExcelLaravel\Excel as FastExcel;

/**
 * Class RekapPerhitunganLemburController
 * @package App\Http\Controllers\Hris
 */
class RekapKehadiranKaryawanController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Rekap Kehadiran Karyawan';
    }

    public function index()
    {
        $this->periode_payroll = $this->ajax_getperiodepayroll();
        return View::make('hris/rekapkehadirankaryawan', $this->data);
    }
    public function proses_rekap(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        $bulan_priode=substr(request()->periode_payroll,15,7);
        $status_staff=request()->status_staff;
        $searchData=request()->searchData;
        $bulan_sekarang1 = strtotime(date( $bulan_priode));
		$tanggal_sekarang=date('Y-m-d');
        $bulan_sebelum = strtotime("-1 month", $bulan_sekarang1);
        $bulan_sebelum=date('Y-m-', $bulan_sebelum);
        $bulan_sekarang=date('Y-m-', $bulan_sekarang1);

        $tanggal_awal=$bulan_sebelum.'26';
        $tanggal_akhir=$bulan_sekarang.'25';
        $bulan=date('m', $bulan_sekarang1);
        $tahun=date('Y', $bulan_sekarang1);
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');

        $tidak_bayar=RefAbsenIjin::where('kode_ijin_payroll','ITB')->where('kode_absen_ijin','!=','M')->where('kode_absen_ijin','!=','IKS')->get()->toArray();
        $ITB=array_column($tidak_bayar,'kode_absen_ijin');

        $timestamp1 = strtotime($tanggal_awal);
        $timestamp2 = strtotime($tanggal_akhir);
        $jumlah_hari =(abs($timestamp2 - $timestamp1) / (60 * 60 * 24)+1);

        $jumlah_hari_sabtu_minggu = 0;

        $security=EmployeeAtribut::where('sub_dept_id','DEP08SUB005')->where('jenis_kelamin','LAKI-LAKI')->where('enroll_id', '!=' , 7445)->get();
        for ($i = strtotime($tanggal_awal); $i <= strtotime($tanggal_akhir); $i += 86400) {
            if ((date('N', $i) == 6)||(date('N', $i) == 7)) {
                $jumlah_hari_sabtu_minggu++;
            }
        }
        $inStatusStaff='';
        if($status_staff){
            $inStatusStaff='AND status_staff = "'.$status_staff.'"';
        }
        $inSearchData='';
        if($searchData){
            $inSearchData='AND enroll_id = "'.$searchData.'"';
        }

        $x=MasterDataAbsenKehadiran::selectRaw('*')->whereRaw('tanggal_berjalan >= "' . $tanggal_awal . '" and tanggal_berjalan <= "' . $tanggal_akhir.'"'.$inSearchData.'')->get()->groupby('enroll_id');

        foreach ($x as $key => $value) {
            $IBY_employe=$value->wherein('status_absen', $IBY)->count();
            $LBY_employe=$value->where('status_absen','LN')->whereNotin('kode_hari', ['5','6'])->count();
            $ITB_employe=$value->wherein('status_absen', $ITB)->count();
            $lsm_employe=$value->wherein('kode_hari', ['5','6'])->count();
           // $dt_employe=$value->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','0')->count();
           // $pc_employe=$value->where('jumlah_menit_absen_pc','>','0')->where('jumlah_menit_absen_dt','0')->count();

           // $dtpc_employe=$value->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','>','0')->count();
 			$dt_employe=$value->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','0')->where('status_absen',null)->count();
            $pc_employe=$value->where('jumlah_menit_absen_pc','>','0')->where('jumlah_menit_absen_dt','0')->where('status_absen',null)->count();

            $dtpc_employe=$value->where('jumlah_menit_absen_dt','>','0')->where('jumlah_menit_absen_pc','>','0')->where('status_absen',null)->count();

            $absen_M=$value->where('status_absen','M')->count();
            $absen_R=$value->where('status_absen','R')->count();
            $absen_TL=$value->where('status_absen','TL')->where('mulai_jam_kerja','!=',null)->count();
            $absen_IKS=$value->where('status_absen','IKS')->where('jumlah_menit_absen_dtpc','0')->count();
            // $absen_ok=$value->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja','!=',null)
            //                 ->where('mulai_jam_kerja','!=',null)->where('akhir_jam_kerja','!=',null)
            //                 ->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();
            $absen_ok=$value->whereNotin('kode_hari', ['5','6'])->where('status_absen',null)->where('jumlah_menit_absen_dtpc','0')->count();
            $absen_ok=$absen_ok+$absen_IKS;
            $unik=$angka_string =sprintf("%04d", $key);
            $kode_rekap_kehadiran=$bulan_sekarang.$unik;

            $total_kehadiran=$IBY_employe+$ITB_employe+$lsm_employe+$dtpc_employe+$absen_M+$absen_R+$absen_ok+$LBY_employe+$dt_employe+$pc_employe+$absen_TL;
            // $kehadiran_tk=$jumlah_hari-($total_kehadiran+$absen_TL);

            $total_kehadiran_net=$absen_ok+$dt_employe+$pc_employe+$dtpc_employe;
            $aa=$total_kehadiran_net+$ITB_employe+$LBY_employe+$IBY_employe+$lsm_employe+$absen_M+$absen_TL+$absen_R;
            $kehadiran_tk=$jumlah_hari-$aa;

            if($security->where('enroll_id',$value->first()->enroll_id)->count()){
                $jumlah_hari_kerja=25;
            }else{
                $jumlah_hari_kerja=$jumlah_hari-$jumlah_hari_sabtu_minggu;
            }
 			$karyawan=EmployeeAtribut::where('enroll_id',$value->first()->enroll_id)->first();
            $kehadiran_dl=$value->where('status_absen','DL')->count();
            $kehadiran_cb=$value->where('status_absen','CB')->count();
            $kehadiran_cbd=$value->where('status_absen','CBD')->count();
            $kehadiran_cg=$value->where('status_absen','CG')->count();
            $kehadiran_ch=$value->where('status_absen','CH')->count();
            $kehadiran_cm=$value->where('status_absen','CM')->count();
            $kehadiran_cn=$value->where('status_absen','CN')->count();
            $kehadiran_ct=$value->where('status_absen','CT')->count();
            $kehadiran_ig=$value->where('status_absen','IG')->count();
            $kehadiran_im=$value->where('status_absen','IM')->count();
            $kehadiran_ka=$value->where('status_absen','KA')->count();
            $kehadiran_km=$value->where('status_absen','KM')->count();
            $kehadiran_kr=$value->where('status_absen','KR')->count();
            $kehadiran_na=$value->where('status_absen','NA')->count();
            $kehadiran_pp=$value->where('status_absen','PP')->count();
            $kehadiran_i=$value->where('status_absen','I')->count();
            $kehadiran_lp=$value->where('status_absen','LP')->count();
            $kehadiran_l=$value->where('status_absen','L')->count();
            $kehadiran_tl=$value->where('status_absen','TL')->count();
            $kehadiran_iks=$value->where('status_absen','IKS')->count();
            $kehadiran_s=$value->where('status_absen','S')->count();

            $M_estimasi=$value->where('status_absen','M')->where('tanggal_berjalan','>=',$tanggal_sekarang)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('tanggal_berjalan','>=',date('Y-m-d'))->count();
            $TL_estimasi=$value->where('status_absen','TL')->where('mulai_jam_kerja','!=',null)->where('tanggal_berjalan','>=',$tanggal_sekarang)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('tanggal_berjalan','>=',date('Y-m-d'))->count();


            $y=[
                'kode_rekap_kehadiran'=> $kode_rekap_kehadiran,
                'periode_payroll'=>$tanggal_awal.' s/d '.$tanggal_akhir,
                'periode_tahun'=>$tahun,
                'periode_bulan'=>$bulan,
                'enroll_id'=>$value->first()->enroll_id,
                'kehadiran_iby'=>$IBY_employe,
                'kehadiran_itb'=>$ITB_employe,
                'kehadiran_lby'=>$LBY_employe,
                'kehadiran_lsm'=>$lsm_employe,
                'kehadiran_dt'=> $dt_employe,
                'kehadiran_pc'=> $pc_employe,
                'kehadiran_dtpc'=>$dtpc_employe,
                'kehadiran_m'=>$absen_M+$absen_TL,
                'kehadiran_r'=>$absen_R,
                'kehadiran_tk'=>$kehadiran_tk,
                'kehadiran_ok'=>$absen_ok,
                'total_kehadiran'=> $total_kehadiran,
                'total_kehadiran_net'=>$absen_ok+$dt_employe+$pc_employe+$dtpc_employe+$LBY_employe+$IBY_employe,
                'jumlah_hari'=>$jumlah_hari,
                'jumlah_hari_kerja'=> $jumlah_hari_kerja,


                'kehadiran_dl'=>$kehadiran_dl,
                'kehadiran_cb'=>$kehadiran_cb,
                'kehadiran_cbd'=>$kehadiran_cbd,
                'kehadiran_cg'=>$kehadiran_cg,
                'kehadiran_ch'=>$kehadiran_ch,
                'kehadiran_cm'=>$kehadiran_cm,
                'kehadiran_cn'=>$kehadiran_cn,
                'kehadiran_ct'=>$kehadiran_ct,
                'kehadiran_ig'=>$kehadiran_ig,
                'kehadiran_im'=>$kehadiran_im,
                'kehadiran_ka'=>$kehadiran_ka,
                'kehadiran_km'=>$kehadiran_km,
                'kehadiran_kr'=>$kehadiran_kr,
                'kehadiran_na'=>$kehadiran_na,
                'kehadiran_pp'=>$kehadiran_pp,
                'kehadiran_i'=>$kehadiran_i,
                'kehadiran_lp'=>$kehadiran_lp,
                'kehadiran_l'=>$kehadiran_l,
                'kehadiran_tl'=>$kehadiran_tl,
                'kehadiran_iks'=>$kehadiran_iks,
                'kehadiran_s'=>$kehadiran_s,
                'kehadiran_m_estimasi'=> $TL_estimasi+$M_estimasi,
            ];
                $count=RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->where('enroll_id',$value->first()->enroll_id)->count();
                if($count){
                    RekapKehadiranKaryawan::where( 'kode_rekap_kehadiran',$kode_rekap_kehadiran)->where('enroll_id',$value->first()->enroll_id)->update($y);
                }
                else{
                    $y['uuid'] = Str::uuid('uuid');
                    RekapKehadiranKaryawan::create($y);
                }
        }
    }


    public function ajax_getperiodepayroll()
    {
        $query =  RekapKehadiranKaryawan::selectRaw('periode_payroll, periode_tahun, periode_bulan')
                                    ->groupby('periode_payroll')
                                    ->orderby('periode_payroll', 'desc')
                                    ->get();

        return $query;

    }

    public function ajax_getemployeselectstaff(Request $request)
    {
        $status_staff = $request->status_staff;

        if($status_staff) {
            $query =  EmployeeAtribut::
            selectRaw('enroll_id no_pin, nik, employee_name,
                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
            ->whereRaw('
             status_staff = "' . $status_staff . '"
            ')
            ->groupby('enroll_id')
            ->orderby('employee_name', 'asc')
            ->get();

        } else {
            $query =  EmployeeAtribut::
            selectRaw('enroll_id no_pin, nik, employee_name,
                            concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
            ->groupby('enroll_id')
            ->orderby('employee_name', 'asc')
            ->get();
        }

        return $query;

    }

    public function ajax_rekap(Request $request)
    {

        $periode_payroll = $request->periode_payroll;
        list($tgl_awal, $tgl_akhir) = explode(' s/d ', $request->periode_payroll);

        $status_staff = $request->status_staff;
        if($status_staff) {
            $whereStatusStaff = ' AND status_staff = "' . $status_staff . '" ';
        } else {
            $whereStatusStaff = '';
        }

        $searchData = $request->searchData;
        if($searchData) {
            $whereSearchData = ' AND (upper( enroll_id ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_name ) LIKE upper( "%' . $searchData . '%" ) OR upper( sub_dept_name ) LIKE upper( "%' . $searchData . '%" ) OR upper( status_aktif ) LIKE upper( "%' . $searchData . '%" ) ) ';
        } else {
            $whereSearchData = '';
        }

        $query =  RekapKehadiranKaryawan::from('rekap_kehadiran_karyawan as rkk')
        ->selectRaw('
                   *')
            ->whereRaw('
                periode_payroll = "' . $periode_payroll . '"
                ' . $whereStatusStaff . '
                ' . $whereSearchData . '
            ')
            ->leftJoin('employee_atribut as employee', 'rkk.enroll_id', '=', 'employee.enroll_id')
             ->where(function ($query) use ($tgl_awal) {
                    $query->orWhereNull('tanggal_resign')
                        ->orWhere('tanggal_resign', '>', $tgl_awal);
                })
            ->orderBy('employee.employee_name','asc')
            ->get();

        return Response()->json($query);

    }

    public function ajax_exportexcel(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        $periode_payroll = $request->input('periode_payroll');
        list($tgl_awal, $tgl_akhir) = explode(' s/d ', $request->periode_payroll);

        $status_staff = $request->input('status_staff');
        if(!$status_staff) { $status_staff = ""; }

        $searchData = $request->input('searchData');
        if(!$searchData) { $searchData = ""; }

        $fileName = 'RekapKehadiranKaryawan_' . time() . '.xlsx';
       // return (new RekapKehadiranKaryawanExport)->exportParams($periode_payroll, $status_staff, $searchData)->download($fileName);
        return (new RekapKehadiranKaryawanExport)->exportParams($periode_payroll, $status_staff, $searchData, $tgl_awal)->download($fileName);

    }
    public function summary_report(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        $excel = FastExcel::create('dataAbsen');
        $sheet = $excel->getSheet();
        $periode_payroll=request()->periode_payroll;
        $tanggal=explode(" s/d ",$periode_payroll);
        $tanggal_awal=$tanggal[0];
        $tanggal_akhir=$tanggal[1];
        $bulan_akhir=substr($tanggal_akhir,5,2);
        $bulan='';
        if($bulan_akhir=='01'){
            $bulan='Januari';
        }else if($bulan_akhir=='02'){
            $bulan='Februari';
        }else if($bulan_akhir=='03'){
            $bulan='Maret';
        }else if($bulan_akhir=='04'){
            $bulan='April';
        }else if($bulan_akhir=='05'){
            $bulan='Mei';
        }else if($bulan_akhir=='06'){
            $bulan='Juni';
        }else if($bulan_akhir=='07'){
            $bulan='Juli';
        }else if($bulan_akhir=='08'){
            $bulan='Agustus';
        }else if($bulan_akhir=='09'){
            $bulan='September';
        }else if($bulan_akhir=='10'){
            $bulan='Oktober';
        }else if($bulan_akhir=='11'){
            $bulan='November';
        }else if($bulan_akhir=='12'){
            $bulan='Desember';
        }
        $tahun=substr($tanggal_akhir,0,4);
        $sheet->writeTo('A1', 'Controlling Absensi', ['font-size' => 14]);
        $sheet->writeTo('A2', 'Periode '.$bulan.' '.$tahun, ['font-size' => 12]);
        $dataCollect=[];
        $tanggal=MasterDataAbsenKehadiran::select('tanggal_berjalan')->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('enroll_id','2')->pluck('tanggal_berjalan');
        array_push($dataCollect, 'ID','NIK','NAMA','AKTIF/NON AKTIF','JOIN DATE','RESIGN DATE','STAFF/NON STAFF','JABATAN','BAGIAN','DEPARTMENT');
        $sheet->writeArrayTo('A4', [$dataCollect]);
        $style = [
            'font-style' => 'bold',
            'text-align' => 'center',
            'vertical-align'=> 'center',
            'border-style' => 'thin',
        ];
        $sheet->mergeCells('A4:A5')->setStyle('A4:A5', $style)->applyBgColor('#ffff00');
        $sheet->mergeCells('B4:B5')->setStyle('B4:B5', $style)->applyBgColor('#ffff00');
        $sheet->mergeCells('C4:C5')->setStyle('C4:C5', $style)->applyBgColor('#ffff00');
        $sheet->mergeCells('D4:D5')->setStyle('D4:D5', $style)->applyBgColor('#ffff00');
        $sheet->mergeCells('E4:E5')->setStyle('E4:E5', $style)->applyBgColor('#ffff00');
        $sheet->mergeCells('F4:F5')->setStyle('F4:F5', $style)->applyBgColor('#ffff00');
        $sheet->mergeCells('G4:G5')->setStyle('G4:G5', $style)->applyBgColor('#ffff00');
        $sheet->mergeCells('H4:H5')->setStyle('H4:H5', $style)->applyBgColor('#ffff00');
        $sheet->mergeCells('I4:I5')->setStyle('I4:I5', $style)->applyBgColor('#ffff00');
        $sheet->mergeCells('J4:J5')->setStyle('J4:J5', $style)->applyBgColor('#ffff00');
        $dataHariCollect=[];
        $dataTanggalCollect=[];
        function tanggal_indo($tanggal)
        {
            $bulan = array (1 =>   'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des');
            $split = explode('-', $tanggal);
            return $split[2] . '-' . $bulan[ (int)$split[1] ] . '-' . $split[0];
        }
        foreach($tanggal as $key=>$value){
            $day = date_format(date_create($value), 'l');
            if($day=='Monday'){
                $day='Senin';
            }
            else if($day=='Tuesday'){
                $day='Selasa';
            }
            else if($day=='Wednesday'){
                $day='Rabu';
            }
            else if($day=='Thursday'){
                $day='Kamis';
            }
            else if($day=='Friday'){
                $day='Jumat';
            }
            else if($day=='Saturday'){
                $day='Sabtu';
            }
            else if($day=='Sunday'){
                $day='Minggu';
            }
            $tgl = tanggal_indo($value);
            array_push($dataTanggalCollect, $tgl);
            array_push($dataHariCollect, $day);
        }
        array_push($dataHariCollect, 'Jumlah Ketidakhadiran');
        array_push($dataTanggalCollect, 'CB','CBD','CG','CH','CM','CN','CT','DL','I','IG','IKS','IM','KA','KM','KR','LN','LP','M','NA','PP');
        $sheet->writeArrayTo('K4', [$dataHariCollect]);
        $sheet->writeArrayTo('K5', [$dataTanggalCollect]);
        $daftar_karyawan=EmployeeAtribut::where(function($query)use($tanggal_awal,$tanggal_akhir){
            $query->where(function($query)use($tanggal_akhir){
                $query->where('status_aktif','AKTIF')
                ->where('join_date','<=',$tanggal_akhir);
            })->orWhere(function($query)use($tanggal_awal){
                $query->where('status_aktif','TIDAK AKTIF')
                ->where('tanggal_resign','>=',$tanggal_awal);
            });
        })->with(['absensi' => function ($query) use ($tanggal_awal,$tanggal_akhir) {
            $query->where('tanggal_berjalan', '>=', $tanggal_awal)
            ->where('tanggal_berjalan','<=',$tanggal_akhir);
        }])->get();
        $data_daftar_karyawan=[];
        foreach($daftar_karyawan as $v){
            $join_date=Carbon::parse($v->join_date)->translatedFormat('d F Y');
            $tanggal_resign='';
            if($v->tanggal_resign!=''){
                $tanggal_resign=Carbon::parse($v->tanggal_resign)->translatedFormat('d F Y');
            }
            $data=[
                $v->enroll_id,
                $v->nik,
                $v->employee_name,
                $v->status_aktif,
                $join_date,
                $tanggal_resign,
                $v->status_staff,
                $v->status_jabatan,
                $v->sub_dept_name,
                $v->department_name,
            ];
            foreach($tanggal as $value){
                $kode_hari='';
                if(isset($v['absensi']->where('tanggal_berjalan',$value)->pluck('kode_hari')[0])){
                    $kode_hari=$v['absensi']->where('tanggal_berjalan',$value)->pluck('kode_hari')[0];
                }else{
                    $kode_hari=8;
                }

                $libur=$v['absensi']->where('tanggal_berjalan',$value)->where('absen_masuk_kerja',null);
                $status='';
                if($kode_hari==5 || $kode_hari==6){
                    if(count($libur)>0){
                        $status='L';
                    }else{
                        $status='';
                    }
                }else if($kode_hari==8){
                    $status='-';
                }else{
                    $status=$v['absensi']->where('tanggal_berjalan',$value)->pluck('status_absen')[0];
                }
                array_push($data,$status);
            }
            $CB=$v['absensi']->where('status_absen','CB')->count();
            $CBD=$v['absensi']->where('status_absen','CBD')->count();
            $CG=$v['absensi']->where('status_absen','CG')->count();
            $CH=$v['absensi']->where('status_absen','CH')->count();
            $CM=$v['absensi']->where('status_absen','CM')->count();
            $CN=$v['absensi']->where('status_absen','CN')->count();
            $CT=$v['absensi']->where('status_absen','CT')->count();
            $DL=$v['absensi']->where('status_absen','DL')->count();
            $I=$v['absensi']->where('status_absen','I')->count();
            $IG=$v['absensi']->where('status_absen','IG')->count();
            $IKS=$v['absensi']->where('status_absen','IKS')->count();
            $IM=$v['absensi']->where('status_absen','IM')->count();
            $KA=$v['absensi']->where('status_absen','KA')->count();
            $KM=$v['absensi']->where('status_absen','KM')->count();
            $KR=$v['absensi']->where('status_absen','KR')->count();
            $LN=$v['absensi']->where('status_absen','LN')->count();
            $LP=$v['absensi']->where('status_absen','LP')->count();
            $M=$v['absensi']->where('status_absen','M')->count();
            $NA=$v['absensi']->where('status_absen','NA')->count();
            $PP=$v['absensi']->where('status_absen','PP')->count();
            $S=$v['absensi']->where('status_absen','S')->count();
            array_push($data,$CB,$CBD,$CG,$CH,$CM,$CN,$CT,$DL,$I,$IG,$IKS,$IM,$KA,$KM,$KR,$LN,$LP,$M,$NA,$PP);
            $sheet->writeRow($data);
        }
        $finename=date('Y-m').'_Time and Attendance PT.NAG_'.rand(10,10000000).'xlsx';
        ob_end_clean();
        $excel->download($finename);
    }
    public function excel_rekap_absen(){
        $date_now=date('Y-m-d');
        $periode_payroll=request()->periode_payroll;
        $tanggal=explode(" s/d ",$periode_payroll);
        $tanggal_awal=$tanggal[0];
        $tanggal_akhir=$tanggal[1];
        $tanggal_awal_string=date('d F', strtotime($tanggal_awal));
        $tanggal_akhir_string=date('d F', strtotime($tanggal_akhir));
        $year_string=date('Y', strtotime($tanggal_akhir));
        $date_string=$tanggal_awal_string.' - '.$tanggal_akhir_string.' '.$year_string;
        $non_sewing_staff=[];
        $non_sewing_nonstaff=[];
        $sewing_staff=[];
        $sewing_nonstaff=[];
        $non_sewing_total=[];
        $sewing_total=[];
        $tanggal_absensi=[];
        $month_year=[];
        $periode=[];
        $periode_bulan=[];
        $grand_total=[];
        $data_tanggal_absensi=MasterDataAbsenKehadiran::select('tanggal_berjalan')->whereHas('employee_atribut',function($query){
            $query->where('site_nirwana_id','!=','SA');
        })->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('enroll_id','4241')->pluck('tanggal_berjalan');
        $jumlah_tanggal_absensi=count($data_tanggal_absensi)+1;
        $ijin_bayar=RefAbsenIjin::where('kode_ijin_payroll','IBY')->get()->toArray();
        $IBY=array_column($ijin_bayar,'kode_absen_ijin');
        $total_karyawan_sakit=[
            'tanggal'=>'2002-02-19',
            'total'=>MasterDataAbsenKehadiran::select('tanggal_berjalan')->where('tanggal_berjalan','>=',$tanggal_awal)->whereHas('employee_atribut',function($query){
                $query->where('site_nirwana_id','!=','SA');
            })->where('tanggal_berjalan','<=',$date_now)->where('status_absen','S')->count()
        ];
        $total_karyawan_izin=[
            'tanggal'=>'2002-02-19',
            'total'=>MasterDataAbsenKehadiran::select('tanggal_berjalan')->where('tanggal_berjalan','>=',$tanggal_awal)->whereHas('employee_atribut',function($query){
                $query->where('site_nirwana_id','!=','SA');
            })->where('tanggal_berjalan','<=',$date_now)->where('status_absen','I')->count()
        ];
        $total_karyawan_cuti=[
            'tanggal'=>'2002-02-19',
            'total'=>MasterDataAbsenKehadiran::select('tanggal_berjalan')->where('tanggal_berjalan','>=',$tanggal_awal)->whereHas('employee_atribut',function($query){
                $query->where('site_nirwana_id','!=','SA');
            })->where('tanggal_berjalan','<=',$date_now)->whereIn('status_absen',$IBY)->count()
        ];
        $total_karyawan_mangkir=[
            'tanggal'=>'2002-02-19',
            'total'=>MasterDataAbsenKehadiran::select('tanggal_berjalan')->where('tanggal_berjalan','>=',$tanggal_awal)->whereHas('employee_atribut',function($query){
                $query->where('site_nirwana_id','!=','SA');
            })->where('tanggal_berjalan','<=',$date_now)->where('status_absen','M')->count()
        ];
        $total_karyawan_dinas_luar=[
            'tanggal'=>'2002-02-19',
            'total'=>MasterDataAbsenKehadiran::select('tanggal_berjalan')->where('tanggal_berjalan','>=',$tanggal_awal)->whereHas('employee_atribut',function($query){
                $query->where('site_nirwana_id','!=','SA');
            })->where('tanggal_berjalan','<=',$date_now)->where('status_absen','DL')->count()
        ];
        $total_karyawan_libur=[
            'tanggal'=>'2002-02-19',
            'total'=>MasterDataAbsenKehadiran::select('tanggal_berjalan')->where('tanggal_berjalan','>=',$tanggal_awal)->whereHas('employee_atribut',function($query){
                $query->where('site_nirwana_id','!=','SA');
            })->where('tanggal_berjalan','<=',$date_now)->where('mulai_jam_kerja',null)->where('absen_masuk_kerja',null)->where('status_absen',null)->count()
        ];
        $total_karyawan_resign=[
            'tanggal'=>'2002-02-19',
            'total'=>MasterDataAbsenKehadiran::select('tanggal_berjalan')->where('tanggal_berjalan','>=',$tanggal_awal)->whereHas('employee_atribut',function($query){
                $query->where('site_nirwana_id','!=','SA');
            })->where('tanggal_berjalan','<=',$date_now)->where('status_absen','R')->count()
        ];

        foreach($data_tanggal_absensi as $key=>$value){
            $tanggal_absensi[$key]=$value;
            $month_year[$key]=substr($value,0,7);
            $non_sewing_staff[$key]=[
                'tanggal'=>$value,
                'total'=>EmployeeAtribut::where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF')->where(function($query)use($value){
                    $query->where(function($queryes)use($value){
                        $queryes->where('status_aktif','AKTIF')
                        ->orWhere('tanggal_resign','>',$value);
                    })->where('join_date','<=',$value);
                })->count()
            ];
            $non_sewing_nonstaff[$key]=[
                'tanggal'=>$value,
                'total'=>EmployeeAtribut::where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF')->where(function($query)use($value){
                    $query->where(function($queryes)use($value){
                        $queryes->where('status_aktif','AKTIF')
                        ->orWhere('tanggal_resign','>',$value);
                    })->where('join_date','<=',$value);
                })->count()
            ];
            $non_sewing_total[$key]=[
                'tanggal'=>$value,
                'total'=>EmployeeAtribut::where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF')->where(function($query)use($value){
                    $query->where(function($queryes)use($value){
                        $queryes->where('status_aktif','AKTIF')
                        ->orWhere('tanggal_resign','>',$value);
                    })->where('join_date','<=',$value);
                })->count()+EmployeeAtribut::where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF')->where(function($query)use($value){
                    $query->where(function($queryes)use($value){
                        $queryes->where('status_aktif','AKTIF')
                        ->orWhere('tanggal_resign','>',$value);
                    })->where('join_date','<=',$value);
                })->count()
            ];
            $sewing_staff[$key]=[
                'tanggal'=>$value,
                'total'=>EmployeeAtribut::where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF')->where(function($query)use($value){
                    $query->where(function($queryes)use($value){
                        $queryes->where('status_aktif','AKTIF')
                        ->orWhere('tanggal_resign','>',$value);
                    })->where('join_date','<=',$value);
                })->count()
            ];
            $sewing_nonstaff[$key]=[
                'tanggal'=>$value,
                'total'=>EmployeeAtribut::where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF')->where(function($query)use($value){
                    $query->where(function($queryes)use($value){
                        $queryes->where('status_aktif','AKTIF')
                        ->orWhere('tanggal_resign','>',$value);
                    })->where('join_date','<=',$value);
                })->count()
            ];
            $sewing_total[$key]=[
                'tanggal'=>$value,
                'total'=>EmployeeAtribut::where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF')->where(function($query)use($value){
                    $query->where(function($queryes)use($value){
                        $queryes->where('status_aktif','AKTIF')
                        ->orWhere('tanggal_resign','>',$value);
                    })->where('join_date','<=',$value);
                })->count()+EmployeeAtribut::where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF')->where(function($query)use($value){
                    $query->where(function($queryes)use($value){
                        $queryes->where('status_aktif','AKTIF')
                        ->orWhere('tanggal_resign','>',$value);
                    })->where('join_date','<=',$value);
                })->count()
            ];
            $grand_total[$key]=[
                'tanggal'=>$value,
                'total'=>EmployeeAtribut::where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF')->where(function($query)use($value){
                    $query->where(function($queryes)use($value){
                        $queryes->where('status_aktif','AKTIF')
                        ->orWhere('tanggal_resign','>',$value);
                    })->where('join_date','<=',$value);
                })->count()+EmployeeAtribut::where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF')->where(function($query)use($value){
                    $query->where(function($queryes)use($value){
                        $queryes->where('status_aktif','AKTIF')
                        ->orWhere('tanggal_resign','>',$value);
                    })->where('join_date','<=',$value);
                })->count()+EmployeeAtribut::where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF')->where(function($query)use($value){
                    $query->where(function($queryes)use($value){
                        $queryes->where('status_aktif','AKTIF')
                        ->orWhere('tanggal_resign','>',$value);
                    })->where('join_date','<=',$value);
                })->count()+EmployeeAtribut::where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF')->where(function($query)use($value){
                    $query->where(function($queryes)use($value){
                        $queryes->where('status_aktif','AKTIF')
                        ->orWhere('tanggal_resign','>',$value);
                    })->where('join_date','<=',$value);
                })->count()
            ];
            $non_sewing_nonstaff_present[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeseses)use($date_now,$value){
                        $queryeseses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()
            ];
            $non_sewing_staff_present[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeseses)use($date_now,$value){
                        $queryeseses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()
            ];
            $non_sewing_present[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeseses)use($date_now,$value){
                        $queryeseses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()+MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeseses)use($date_now,$value){
                        $queryeseses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()
            ];
            $sewing_nonstaff_present[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeseses)use($date_now,$value){
                        $queryeseses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()
            ];
            $sewing_staff_present[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeseses)use($date_now,$value){
                        $queryeseses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()
            ];
            $sewing_present[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeseses)use($date_now,$value){
                        $queryeseses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()+MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeseses)use($date_now,$value){
                        $queryeseses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()
            ];
            $grand_total_present[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeseses)use($date_now,$value){
                        $queryeseses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()+MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeseses)use($date_now,$value){
                        $queryeseses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()+MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeseses)use($date_now,$value){
                        $queryeseses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()+MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja','!=',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeseses)use($date_now,$value){
                        $queryeseses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()
            ];
            $non_sewing_nonstaff_absent[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()
            ];
            $non_sewing_staff_absent[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()
            ];
            $non_sewing_absent[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()+MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()
            ];
            $sewing_nonstaff_absent[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()
            ];
            $sewing_staff_absent[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()
            ];
            $sewing_absent[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()+MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()
            ];
            $grand_total_absent[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()+MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()+MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()+MasterDataAbsenKehadiran::whereHas('employee_atribut',function($query){
                    $query->where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('status_staff','NON STAFF');
                })->where('tanggal_berjalan',$value)->where(function($query)use($date_now,$value){
                    $query->where(function($queryes)use($date_now,$value){
                        $queryes->where('absen_masuk_kerja',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan',$value);
                    })->orWhere(function($queryeses)use($date_now,$value){
                        $queryeses->where('absen_masuk_kerja','!=',null)
                        ->where('absen_pulang_kerja',null)
                        ->where('tanggal_berjalan','!=',$date_now)
                        ->where('tanggal_berjalan',$value);
                    });
                })->count()
            ];
            $karyawan_sakit[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',$value)->where('status_absen','S')->whereHas('employee_atribut',function($query){
                    $query->where('site_nirwana_id','!=','SA');
                })->count()
            ];
            $karyawan_izin[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',$value)->where('status_absen','I')->whereHas('employee_atribut',function($query){
                    $query->where('site_nirwana_id','!=','SA');
                })->count()
            ];
            $karyawan_cuti[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',$value)->whereIn('status_absen',$IBY)->where('status_absen','!=','DL')->whereHas('employee_atribut',function($query){
                    $query->where('site_nirwana_id','!=','SA');
                })->count()
            ];
            $karyawan_mangkir[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',$value)->where('status_absen','M')->whereHas('employee_atribut',function($query){
                    $query->where('site_nirwana_id','!=','SA');
                })->count()
            ];
            $karyawan_dinas_luar[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',$value)->where('status_absen','DL')->whereHas('employee_atribut',function($query){
                    $query->where('site_nirwana_id','!=','SA');
                })->count()
            ];
            $karyawan_libur[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',$value)->where('status_absen','LP')->whereHas('employee_atribut',function($query){
                    $query->where('site_nirwana_id','!=','SA');
                })->count()
            ];
            $karyawan_resign[$key]=[
                'tanggal'=>$value,
                'total'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',$value)->whereHas('employee_atribut',function($query)use($value){
                    $query->where('site_nirwana_id','!=','SA')->where('tanggal_resign',$value);
                })->count()
            ];
            $recruitment_sewing[$key]=[
                'tanggal'=>$value,
                'total'=>EmployeeAtribut::where('department_name','SEWING')->where('site_nirwana_id','!=','SA')->where('join_date',$value)->count()
            ];
            $recruitment_nonsewing[$key]=[
                'tanggal'=>$value,
                'total'=>EmployeeAtribut::where('department_name','!=','SEWING')->where('site_nirwana_id','!=','SA')->where('join_date',$value)->count()
            ];
        }
        array_push($karyawan_sakit,$total_karyawan_sakit);
        array_push($karyawan_izin,$total_karyawan_izin);
        array_push($karyawan_cuti,$total_karyawan_cuti);
        array_push($karyawan_mangkir,$total_karyawan_mangkir);
        array_push($karyawan_dinas_luar,$total_karyawan_dinas_luar);
        array_push($karyawan_libur,$total_karyawan_libur);
        array_push($karyawan_resign,$total_karyawan_resign);
        foreach($month_year as $key=>$value){
            $periode[$value]=$value;
        }
        $no=-1;
        foreach($periode as $key=>$value){
            $no++;
            $periode_bulan[$no]=$value;
        }

        $persentase_non_sewing=[];
        $persentase_sewing=[];
        $persentase_non_sewing_present=[];
        $persentase_sewing_present=[];
        $persentase_non_sewing_absent=[];
        $persentase_sewing_absent=[];
        foreach($data_tanggal_absensi as $key=>$value){
            if($non_sewing_present[$key]['total']==0){
                $total_persentase_non_sewing_present=0;
            }else{
                $total_persentase_non_sewing_present=round((($non_sewing_present[$key]['total']/($non_sewing_present[$key]['total']+$non_sewing_absent[$key]['total']))*100),1);
            }
            if($sewing_present[$key]['total']==0){
                $total_persentase_sewing_present=0;
            }else{
                $total_persentase_sewing_present=round((($sewing_present[$key]['total']/($sewing_present[$key]['total']+$sewing_absent[$key]['total']))*100),1);
            }
            if($non_sewing_absent[$key]['total']==0){
                $total_persentase_non_sewing_absent=0;
            }else{
                $total_persentase_non_sewing_absent=round((($non_sewing_absent[$key]['total']/($non_sewing_absent[$key]['total']+$non_sewing_present[$key]['total']))*100),1);
            }
            if($sewing_absent[$key]['total']==0){
                $total_persentase_sewing_absent=0;
            }else{
                $total_persentase_sewing_absent=round((($sewing_absent[$key]['total']/($sewing_absent[$key]['total']+$sewing_present[$key]['total']))*100),1);
            }
            $persentase_non_sewing[$key]=[
                'tanggal'=>$value,
                'total'=>round((($non_sewing_total[$key]['total']/($non_sewing_total[$key]['total']+$sewing_total[$key]['total']))*100),1)
            ];
            $persentase_sewing[$key]=[
                'tanggal'=>$value,
                'total'=>round((($sewing_total[$key]['total']/($non_sewing_total[$key]['total']+$sewing_total[$key]['total']))*100),1)
            ];
            $persentase_non_sewing_present[$key]=[
                'tanggal'=>$value,
                'total'=>$total_persentase_non_sewing_present
            ];
            $persentase_sewing_present[$key]=[
                'tanggal'=>$value,
                'total'=>$total_persentase_sewing_present
            ];
            $persentase_non_sewing_absent[$key]=[
                'tanggal'=>$value,
                'total'=>$total_persentase_non_sewing_absent
            ];
            $persentase_sewing_absent[$key]=[
                'tanggal'=>$value,
                'total'=>$total_persentase_sewing_absent
            ];
        }
        $bulan_pertama=MasterDataAbsenKehadiran::select('tanggal_berjalan')->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('tanggal_berjalan','like',$periode_bulan[0].'%')->where('enroll_id','4241')->count();
        $bulan_kedua=MasterDataAbsenKehadiran::select('tanggal_berjalan')->where('tanggal_berjalan','>=',$tanggal_awal)->where('tanggal_berjalan','<=',$tanggal_akhir)->where('tanggal_berjalan','like',$periode_bulan[1].'%')->where('enroll_id','4241')->count();
        $sub_dept_names=DepartmentAll::where('site_nirwana_id','NAG')->get();
        $all_dept=[];
        foreach($sub_dept_names as $key=>$value){
            $all_dept[$key]=[
                'sub_dept_name'=>$value->sub_dept_name,
                'employee'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->whereHas('employee_atribut',function($query)use($value){$query->where('sub_dept_name',$value->sub_dept_name);})->count(),
                'present_employee'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->whereHas('employee_atribut',function($query)use($value){$query->where('sub_dept_name',$value->sub_dept_name);})->where('absen_masuk_kerja','!=',null)->count(),
                'absent_employee'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->whereHas('employee_atribut',function($query)use($value){$query->where('sub_dept_name',$value->sub_dept_name);})->where('absen_masuk_kerja',null)->count(),
                'resign_employee'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->whereHas('employee_atribut',function($query)use($value){$query->where('sub_dept_name',$value->sub_dept_name)->where('tanggal_resign',date('Y-m-d'));})->count(),
                'recruitment_employee'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->whereHas('employee_atribut',function($query)use($value){$query->where('sub_dept_name',$value->sub_dept_name)->where('join_date',date('Y-m-d'));})->count(),
            ];
        }
        $dept_names=DepartmentAll::select('department_name')->where('site_nirwana_id','NAG')->groupBy('department_name')->whereIn('department_name',['SEWING','ART WORK','STEAM','CUTTING'])->pluck('department_name');
        $dept_names_2=DepartmentAll::select('department_name')->where('site_nirwana_id','NAG')->groupBy('department_name')->whereNotIn('department_name',['SEWING','ART WORK','STEAM','CUTTING'])->pluck('department_name');
        $all_active_employee_dept_name_count=MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->whereHas('employee_atribut',function($query){
            $query->where('site_nirwana_id','!=','SA')->whereIn('department_name',['SEWING','ART WORK','STEAM','CUTTING']);
        })->where('status_absen','!=','R')->count();
        $all_active_employee_dept_name_count_2=MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->whereHas('employee_atribut',function($query){
            $query->where('site_nirwana_id','!=','SA')->whereNotIn('department_name',['SEWING','ART WORK','STEAM','CUTTING']);
        })->where('status_absen','!=','R')->count();
        $all_active_employee_dept_names=MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->whereHas('employee_atribut',function($query){
            $query->where('site_nirwana_id','!=','SA');
        })->where('status_absen','!=','R')->count();
        $all_present_employee_dept_name_count=MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->whereHas('employee_atribut',function($query){
            $query->where('site_nirwana_id','!=','SA')->whereIn('department_name',['SEWING','ART WORK','STEAM','CUTTING']);
        })->where('absen_masuk_kerja','!=',null)->count();
        $all_present_employee_dept_name_count_2=MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->whereHas('employee_atribut',function($query){
            $query->where('site_nirwana_id','!=','SA')->whereNotIn('department_name',['SEWING','ART WORK','STEAM','CUTTING']);
        })->where('absen_masuk_kerja','!=',null)->count();
        $all_present_employee_dept_names=MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->whereHas('employee_atribut',function($query){
            $query->where('site_nirwana_id','!=','SA');
        })->where('absen_masuk_kerja','!=',null)->count();
        $all_dept_direct=[];
        foreach($dept_names as $key=>$value){
            if(MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->where('absen_masuk_kerja','!=',null)->whereHas('employee_atribut',function($query)use($value){
                $query->where('department_name',$value);
            })->count()==0){
                $persentase_present_employee=0;
            }else{
                $persentase_present_employee=round(((MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->where('absen_masuk_kerja','!=',null)->whereHas('employee_atribut',function($query)use($value){
                    $query->where('department_name',$value);
                })->count()/$all_present_employee_dept_names)*100),1);
            }
            $all_dept_direct[$key]=[
                'dept_name'=>$value,
                'active_employee'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->where('status_absen','!=','R')->whereHas('employee_atribut',function($query)use($value){
                    $query->where('department_name',$value);
                })->count(),
                'persentase_active_employee'=>round(((MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->where('status_absen','!=','R')->whereHas('employee_atribut',function($query)use($value){
                    $query->where('department_name',$value);
                })->count()/$all_active_employee_dept_names)*100),1),
                'present_employee'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->where('absen_masuk_kerja','!=',null)->whereHas('employee_atribut',function($query)use($value){
                    $query->where('department_name',$value);
                })->count(),
                'persentase_present_employee'=>$persentase_present_employee
            ];
        }
        if($all_present_employee_dept_name_count==0){
            $persentase_present_employee2=0;
        }else{
            $persentase_present_employee2=round((($all_present_employee_dept_name_count/$all_present_employee_dept_names)*100),1);
        }
        $total_direct_employee=[
            'title'=>'DIRECT TOTAL',
            'active_employee'=>$all_active_employee_dept_name_count,
            'persentase_active_employee'=>round((($all_active_employee_dept_name_count/$all_active_employee_dept_names)*100),1),
            'present_employee'=>$all_present_employee_dept_name_count,
            'persentase_present_employee'=>$persentase_present_employee2
        ];
        $all_dept_indirect=[];
        foreach($dept_names_2 as $key=>$value){
            if(MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->where('absen_masuk_kerja','!=',null)->whereHas('employee_atribut',function($query)use($value){
                $query->where('department_name',$value);
            })->count()==0){
                $persentase_present_employee3=0;
            }else{
                $persentase_present_employee3=round(((MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->where('absen_masuk_kerja','!=',null)->whereHas('employee_atribut',function($query)use($value){
                    $query->where('department_name',$value);
                })->count()/$all_present_employee_dept_names)*100),1);
            }
            $all_dept_indirect[$key]=[
                'dept_name'=>$value,
                'active_employee'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->where('status_absen','!=','R')->whereHas('employee_atribut',function($query)use($value){
                    $query->where('department_name',$value);
                })->count(),
                'persentase_active_employee'=>round(((MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->where('status_absen','!=','R')->whereHas('employee_atribut',function($query)use($value){
                    $query->where('department_name',$value);
                })->count()/$all_active_employee_dept_names)*100),1),
                'present_employee'=>MasterDataAbsenKehadiran::where('tanggal_berjalan',date('Y-m-d'))->where('absen_masuk_kerja','!=',null)->whereHas('employee_atribut',function($query)use($value){
                    $query->where('department_name',$value);
                })->count(),
                'persentase_present_employee'=>$persentase_present_employee3
            ];
        }
        if($all_present_employee_dept_name_count==0){
            $persentase_present_employee4=0;
        }else{
            $persentase_present_employee4=round((($all_present_employee_dept_name_count_2/$all_present_employee_dept_names)*100),1);
        }
        $total_indirect_employee=[
            'title'=>'INDIRECT TOTAL',
            'active_employee'=>$all_active_employee_dept_name_count_2,
            'persentase_active_employee'=>round((($all_active_employee_dept_name_count_2/$all_active_employee_dept_names)*100),1),
            'present_employee'=>$all_present_employee_dept_name_count_2,
            'persentase_present_employee'=>$persentase_present_employee4
        ];
        $grand_total_direct_employee=[
            'title'=>'GRAND TOTAL',
            'active_employee'=>$all_active_employee_dept_names,
            'persentase_active_employee'=>'100%',
            'present_employee'=>$all_present_employee_dept_names,
            'persentase_present_employee'=>'100%'
        ];
        // return view('hris.Laporan.rekap_absen',compact('date_now','periode_payroll','tanggal_absensi','jumlah_tanggal_absensi','bulan_pertama','bulan_kedua','non_sewing_staff','non_sewing_nonstaff','non_sewing_total','sewing_total','sewing_staff','sewing_nonstaff','grand_total','persentase_non_sewing','persentase_sewing','non_sewing_nonstaff_present','non_sewing_staff_present','non_sewing_present','sewing_nonstaff_present','sewing_staff_present','sewing_present','grand_total_present','persentase_non_sewing_present','persentase_sewing_present','non_sewing_nonstaff_absent','non_sewing_staff_absent','non_sewing_absent','sewing_nonstaff_absent','sewing_staff_absent','sewing_absent','grand_total_absent','persentase_non_sewing_absent','persentase_sewing_absent','karyawan_sakit','karyawan_izin','karyawan_mangkir','karyawan_libur','karyawan_cuti','karyawan_dinas_luar','karyawan_resign','recruitment_sewing','recruitment_nonsewing','date_string','all_dept','all_dept_direct','total_direct_employee','all_dept_indirect','total_indirect_employee','grand_total_direct_employee'));
        $fileName='rekap_absen_'.time().'.xlsx';

        $response = Excel::download(new DataRekapAbsenExport($date_now,$periode_payroll,$tanggal_absensi,$jumlah_tanggal_absensi,$bulan_pertama,$bulan_kedua,$non_sewing_staff,$non_sewing_nonstaff,$non_sewing_total,$sewing_total,$sewing_staff,$sewing_nonstaff,$grand_total,$persentase_non_sewing,$persentase_sewing,$non_sewing_nonstaff_present,$non_sewing_staff_present,$non_sewing_present,$sewing_nonstaff_present,$sewing_staff_present,$sewing_present,$grand_total_present,$persentase_non_sewing_present,$persentase_sewing_present,$non_sewing_nonstaff_absent,$non_sewing_staff_absent,$non_sewing_absent,$sewing_nonstaff_absent,$sewing_staff_absent,$sewing_absent,$grand_total_absent,$persentase_non_sewing_absent,$persentase_sewing_absent,$karyawan_sakit,$karyawan_izin,$karyawan_mangkir,$karyawan_libur,$karyawan_cuti,$karyawan_dinas_luar,$karyawan_resign,$recruitment_sewing,$recruitment_nonsewing,$date_string,$all_dept,$all_dept_direct,$total_direct_employee,$all_dept_indirect,$total_indirect_employee,$grand_total_direct_employee), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;

    }

}
