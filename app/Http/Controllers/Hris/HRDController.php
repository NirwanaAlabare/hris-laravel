<?php

namespace App\Http\Controllers\Hris;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Dompdf\Options;
use Dompdf\FontMetrics;
use App\Models\EmployeeAtribut;
use App\Models\RekapPerhitunganPayroll;
use App\Models\VoucherBazzar;
use App\Models\PenilaianKinerja;
use App\Imports\KontrakKerjaImport;
use App\Imports\KontrakKerjaImportToDatabase;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\DepartmentAll;
use App\Exports\exportExcelKontrak;
use App\Exports\exportExcelHadirLayoff;
use App\Exports\exportExcelKompensasiPKWT;
use App\Models\DasarPotBPJS;
use App\Models\GradingSalary;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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

    public function layoff_termination(){
        $periode_payroll = $this->ajax_getperiodepayroll();
        $selectEmployee =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')->groupby('enroll_id')->orderby('employee_name', 'asc')->get();
        $selectNoKTP = EmployeeAtribut::selectRaw('nomor_ktp')->groupby('nomor_ktp')->orderby('nomor_ktp', 'asc')->get();
        return View::make('hris/hrd/layoff_termination',compact('selectEmployee','selectNoKTP','periode_payroll'), $this->data);
    }

    public function ajax_getperiodepayroll()
    {
        $query =  RekapPerhitunganPayroll::selectRaw('CONCAT(periode_tahun_payroll,"-",periode_bulan_payroll) periode_payroll')
                        ->groupby('periode_payroll')
                        ->orderby('periode_payroll', 'desc')
                        ->get();
        return $query;

    }

    public function tandai_sp_kerja(Request $request)
    {
        $enroll_id = $request->enroll_id;
        $status = $request->status;

        // Update the 'sudah_diprint' field for the specified enroll_id
        EmployeeAtribut::where('enroll_id', $enroll_id)->update(['sp_kerja' => $status]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function sp_hadir_adjustment(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '10240000000000000M');

        $today = \Carbon\Carbon::today();
        $maxDaysToCheck = 30;
        $startDate = $today->copy()->subDays($maxDaysToCheck)->toDateString();
        $endDate = $today->toDateString();
        $inSearchVariable='';
        $inEnrollId='';
        $inPeriodePayroll="";
        $tanggalSampai=$endDate;

        if (request("search_variable")) {
            $search_variable=request()->search_variable;
            $inSearchVariable = 'AND (ea.enroll_id = "'.$search_variable.'" or ea.nik LIKE "'.$search_variable.'%" or ea.employee_name LIKE "%'.$search_variable.'%" or ea.tempat_lahir LIKE "%'.$search_variable.'%" or ea.nomor_tlpn LIKE "'.$search_variable.'%" or ea.agama LIKE "'.$search_variable.'%" or ea.status_kawin LIKE "'.$search_variable.'%" or ea.nomor_kk LIKE "'.$search_variable.'%" or ea.pendidikan_terakhir LIKE "'.$search_variable.'%" or ea.jurusan_pendidikan LIKE "'.$search_variable.'%" or ea.alamat_rumah LIKE "%'.$search_variable.'%" or ea.department_name LIKE "%'.$search_variable.'%" or ea.sub_dept_name LIKE "%'.$search_variable.'%" or ea.status_aktif LIKE "'.$search_variable.'%" or ea.ibu_kandung LIKE "%'.$search_variable.'%" or ea.nomor_ktp LIKE "'.$search_variable.'%")';
        }
        if(request()->enroll_id){
            $enroll_id = request()->enroll_id;
            $enroll_id_string = implode(',', $enroll_id);
            $inEnrollId='AND ea.enroll_id in ('.$enroll_id_string.')';
        }
        if(request("periode_payroll")) {
            $daterange1 = explode(" s/d ", request()->periode_payroll);
            $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
            $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
            $inPeriodePayroll = 'AND mda.tanggal_berjalan between "'.$tanggalMulai.'" and "'.$tanggalSampai.'"';
        }else{
            $date = \Carbon\Carbon::createFromFormat('Y-m-d', $endDate);
            $startDate = $date->copy()->subMonth()->day(26);
            $endDateDay = now();
            $inPeriodePayroll = 'AND mda.tanggal_berjalan between "'.$startDate.'" and "'.$endDateDay.'"';
        }
        $data = DB::select(DB::raw("
            SELECT mda.tanggal_berjalan, mda.enroll_id, ea.employee_name, mda.status_absen, ea.department_name, mda.kode_hari, ea.sp_kerja
            FROM master_data_absen_kehadiran mda
            JOIN employee_atribut ea ON mda.enroll_id = ea.enroll_id
            WHERE ea.status_aktif = 'Aktif'
            ".$inPeriodePayroll." ".$inSearchVariable." ".$inEnrollId."
            ORDER BY mda.enroll_id, mda.tanggal_berjalan DESC
        "));
        $absenPerOrang = [];
        foreach ($data as $row) {
            $absenPerOrang[$row->enroll_id][] = [
                'tanggal' => $row->tanggal_berjalan,
                'status' => $row->status_absen,
                'nama' => $row->employee_name,
                'department_name' => $row->department_name,
                'kode_hari' => $row->kode_hari,
                'sp_kerja' => $row->sp_kerja,
            ];
        }
        $hasil = [];

        foreach ($absenPerOrang as $enroll_id => $absens) {
            $streak = 0;
            $tanggal_akhir = null;
            $tanggal_mulai = null;
            $nama = $absens[0]['nama'] ?? '-';
            foreach ($absens as $absen) {
                $isWeekend = in_array($absen['kode_hari'], [5, 6]);
                $isMangkir = $absen['status'] === 'M';
                $isTidakHadirLainnya = in_array($absen['status'], ['CG','CM','CN','CT','DL','I','IG','IKS','IM','KA','KM','KR','L','LN','LP','NA','R','S','TL']);
                $isHadir = !$isMangkir && !$isWeekend && !$isTidakHadirLainnya;

                if ($isMangkir) {
                    $streak++;
                    if (!$tanggal_akhir) {
                        $tanggal_akhir = $absen['tanggal'];
                    }
                    $tanggal_mulai = $absen['tanggal'];
                } elseif ($isHadir) {
                    break;
                }
            }

            if($tanggalSampai == $today->toDateString()){
                if ($streak > 1 && $tanggal_akhir === $today->toDateString()) {
                    $kategori = match (true) {
                        $streak >= 6 => 'RESIGNED',
                        $streak >= 3 => 'SP-2',
                        default => 'SP-1',
                    };

                    $hasil[] = [
                        'enroll_id' => $enroll_id,
                        'employee_name' => $nama,
                        'jumlah_hari_mangkir' => $streak,
                        'mulai' => Carbon::parse($tanggal_mulai)->translatedFormat('d F Y'),
                        'selesai' => Carbon::parse($tanggal_akhir)->translatedFormat('d F Y'),
                        'kategori' => $kategori,
                        'department_name' => $absens[0]['department_name'] ?? '-',
                        'sp_kerja' => $absens[0]['sp_kerja'] ?? '-',
                    ];
                }
            } else{
                 if ($streak > 1) {
                    $kategori = match (true) {
                        $streak >= 6 => 'RESIGNED',
                        $streak >= 3 => 'SP-2',
                        default => 'SP-1',
                    };

                    $hasil[] = [
                        'enroll_id' => $enroll_id,
                        'employee_name' => $nama,
                        'jumlah_hari_mangkir' => $streak,
                        'mulai' => Carbon::parse($tanggal_mulai)->translatedFormat('d F Y'),
                        'selesai' => Carbon::parse($tanggal_akhir)->translatedFormat('d F Y'),
                        'kategori' => $kategori,
                        'department_name' => $absens[0]['department_name'] ?? '-',
                        'sp_kerja' => $absens[0]['sp_kerja'] ?? '-',
                    ];
                }
            }
        }
        return DataTables::of($hasil)->toJson();
    }


    public function export_sp_kehadiran_karyawan_adjustment(){

        $bulan = now()->format('n'); // 1–12
        $tahun = now()->format('Y');

        // Array bulan romawi
        $bulanRomawi = [
        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
        5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
        9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];

        // Format nomor form dinamis
        $from=request()->from;
        $to=request()->to;
        // dd($kategori = request()->kategori);
        $kategori1 = request()->kategori;
        $jumlah_hari_mangkir=request()->jumlah_hari_mangkir;
        $no_form_url=request()->no_form;
        $no_form = $no_form_url.'/HRD-NAC/EXT/' . $bulanRomawi[$bulan] . '/' . $tahun;

        $enroll_id=request()->enroll_id;
        $reason=request()->reason;
        $fileName=request()->enroll_id;
        $date_now = Carbon::parse(date('Y-m-d'))->translatedFormat('d F Y');

        $date = date('Y-m-d');

        $today = Carbon::today();
        $maxDaysToCheck = 30; // maksimal cek 30 hari ke belakang
        $startDate = $today->copy()->subDays($maxDaysToCheck)->toDateString();
        $endDate = $today->toDateString();

        // Ambil semua data karyawan aktif dengan status absen (M dan lainnya) dalam rentang tanggal tersebut
        $data = DB::select(DB::raw("
            SELECT mda.tanggal_berjalan, mda.enroll_id, ea.employee_name, mda.status_absen, ea.department_name, ea.nik, ea.status_jabatan, ea.alamat_rumah, mda.kode_hari
            FROM master_data_absen_kehadiran mda
            JOIN employee_atribut ea ON mda.enroll_id = ea.enroll_id
            WHERE mda.tanggal_berjalan <= '$endDate'
            AND ea.status_aktif = 'Aktif'
            AND mda.enroll_id = '$enroll_id'
            ORDER BY mda.enroll_id, mda.tanggal_berjalan DESC
        "));

        $absenPerOrang = [];
        foreach ($data as $row) {
            $absenPerOrang[$row->enroll_id][] = [
                'tanggal' => $row->tanggal_berjalan,
                'status' => $row->status_absen,
                'kode_hari' => $row->kode_hari,
                'nama' => $row->employee_name,
                'department_name' => $row->department_name,
                'nik' => $row->nik,
                'status_jabatan' => $row->status_jabatan,
                'alamat_rumah' => $row->alamat_rumah
            ];
        }
        $hasil = [];

        foreach ($absenPerOrang as $enroll_id => $absens) {
            $streak = 0;
            $tanggal_akhir = null;
            $tanggal_mulai = null;
            $nama = $absens[0]['nama'] ?? '-';

            foreach ($absens as $absen) {
                $isWeekend = in_array($absen['kode_hari'], [5, 6]);
                $isMangkir = $absen['status'] === 'M';
                $isTidakHadirLainnya = in_array($absen['status'], ['CG','CM','CN','CT','DL','I','IG','IKS','IM','KA','KM','KR','L','LN','LP','NA','R','S','TL','ITB']);
                $isHadir = !$isMangkir && !$isWeekend && !$isTidakHadirLainnya;
               if ($isMangkir) {
                    $streak++;

                    if (!$tanggal_akhir) {
                        $tanggal_akhir = $absen['tanggal'];
                    }

                    $tanggal_mulai = $absen['tanggal'];
                } else {
                    break;
                }

            }
            if ($streak > 0) {
                $kategori = match (true) {
                    $streak >= 6 => 'RESIGNED',
                    $streak >= 3 => 'II',
                    default => 'I',
                };

                $hasil[] = [
                    'enroll_id' => $enroll_id,
                    'employee_name' => $nama,
                    'jumlah_hari_mangkir' => $streak,
                    'mulai' => Carbon::parse($tanggal_mulai)->translatedFormat('d F Y'),
                    'selesai' => Carbon::parse($tanggal_akhir)->translatedFormat('d F Y'),
                    'kategori' => $kategori,
                    'department_name' => $absens[0]['department_name'] ?? '-',
                    'nik' => $absens[0]['nik'] ?? '-',
                    'status_jabatan' => $absens[0]['status_jabatan'] ?? '-',
                    'alamat_rumah' => $absens[0]['alamat_rumah'] ?? '-',
                ];
            }
        }
        if($jumlah_hari_mangkir){
            if($jumlah_hari_mangkir >= 1 && $jumlah_hari_mangkir <= 3){
                $hasil[0]['kategori'] = 'I';
            }else if($jumlah_hari_mangkir >= 4 && $jumlah_hari_mangkir <= 5){
                $hasil[0]['kategori'] = 'II';
            }else if($jumlah_hari_mangkir >= 6){
                $hasil[0]['kategori'] = 'RESIGNED';
            }
        }
        $pdf = PDF::loadView('hris.sp_kehadiran_karyawan',["data" => $hasil[0],"no_form"=>$no_form,"from"=>$from,"to"=>$to,"jumlah_hari_mangkir"=>$jumlah_hari_mangkir])->setPaper('letter', 'fotrait')->stream($fileName.'_'.$nama . '_' . $kategori1.'.pdf');
        return $pdf;
    }

    public function export_rekap_hadir_layoff(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '10240000000000000M');

        $today = \Carbon\Carbon::today();
        $maxDaysToCheck = 30;
        $startDate = $today->copy()->subDays($maxDaysToCheck)->toDateString();
        $endDate = $today->toDateString();
        $date="";
        $inEnrollId='';
        $inSearchVariable='';
        $inPeriodePayroll="";
        $tanggalSampai=$endDate;

        $inEnrollId='';
        if(request()->enroll_id){
            $enroll_id = request()->enroll_id;
            $enroll_id_string = implode(',', $enroll_id);
            $inEnrollId='AND ea.enroll_id in ('.$enroll_id_string.')';
        }
       if(request("periode_payroll")) {
            $daterange1 = explode(" s/d ", request()->periode_payroll);
            $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
            $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
            $inPeriodePayroll = 'AND mda.tanggal_berjalan between "'.$tanggalMulai.'" and "'.$tanggalSampai.'"';
        }else{
            $date = \Carbon\Carbon::createFromFormat('Y-m-d', $endDate);
            $startDate = $date->copy()->subMonth()->day(26);
            $endDateDay = now();
            $inPeriodePayroll = 'AND mda.tanggal_berjalan between "'.$startDate.'" and "'.$endDateDay.'"';
        }

        // Ambil semua data karyawan aktif dengan status absen (M dan lainnya) dalam rentang tanggal tersebut
        $data = DB::select(DB::raw("
            SELECT mda.tanggal_berjalan, mda.enroll_id, ea.employee_name, mda.status_absen, ea.department_name, ea.nik, ea.status_jabatan, ea.alamat_rumah, mda.kode_hari, ea.status_staff, ea.sub_dept_name
            FROM master_data_absen_kehadiran mda
            JOIN employee_atribut ea ON mda.enroll_id = ea.enroll_id
            WHERE ea.status_aktif = 'Aktif'
            ".$inPeriodePayroll."
            ".$inEnrollId."
            ORDER BY mda.enroll_id, mda.tanggal_berjalan DESC
        "));
        $absenPerOrang = [];
        foreach ($data as $row) {
            $absenPerOrang[$row->enroll_id][] = [
                'tanggal' => $row->tanggal_berjalan,
                'status' => $row->status_absen,
                'nama' => $row->employee_name,
                'department_name' => $row->department_name,
                'kode_hari' => $row->kode_hari,
                'nik' => $row->nik,
                'status_jabatan' => $row->status_jabatan,
                'alamat_rumah' => $row->alamat_rumah,
                'status_staff' => $row->status_staff,
                'sub_dept_name' => $row->sub_dept_name
            ];
        }
        $hasil = [];
        foreach ($absenPerOrang as $enroll_id => $absens) {
            $streak = 0;
            $tanggal_akhir = null;
            $tanggal_mulai = null;
            $nama = $absens[0]['nama'] ?? '-';

            foreach ($absens as $absen) {
                if ($absen['tanggal'] > $endDate) continue;

                $isWeekend = in_array($absen['kode_hari'], [5, 6]);
                $isMangkir = $absen['status'] === 'M';
                $isTidakHadirLainnya = in_array($absen['status'], ['CG','CM','CN','CT','DL','I','IG','IKS','IM','KA','KM','KR','L','LN','LP','NA','R','S','TL']);
                $isHadir = !$isMangkir && !$isWeekend && !$isTidakHadirLainnya;

                if ($isMangkir) {
                    $streak++;
                    if (!$tanggal_akhir) {
                        $tanggal_akhir = $absen['tanggal'];
                    }
                    $tanggal_mulai = $absen['tanggal'];
                } elseif ($isHadir) {
                    break;
                }
            }
              if($tanggalSampai == $today->toDateString()){
                if ($streak > 1 && $tanggal_akhir === $today->toDateString()) {
                    $kategori = match (true) {
                        $streak >= 6 => 'RESIGNED',
                        $streak >= 3 => 'SP-2',
                        default => 'SP-1',
                    };

                    $hasil[] = [
                        'enroll_id' => $enroll_id,
                        'employee_name' => $nama,
                        'jumlah_hari_mangkir' => $streak,
                        'mulai' => Carbon::parse($tanggal_mulai)->translatedFormat('d F Y'),
                        'selesai' => Carbon::parse($tanggal_akhir)->translatedFormat('d F Y'),
                        'kategori' => $kategori,
                        'department_name' => $absens[0]['department_name'] ?? '-',
                        'nik' => $absens[0]['nik'] ?? '-',
                        'status_jabatan' => $absens[0]['status_jabatan'] ?? '-',
                        'alamat_rumah' => $absens[0]['alamat_rumah'] ?? '-',
                        'status_staff' => $absens[0]['status_staff'] ?? '-',
                        'sub_dept_name' => $absens[0]['sub_dept_name'] ?? '-'
                    ];
                }
             } else {
                if ($streak > 1) {
                    $kategori = match (true) {
                        $streak >= 6 => 'RESIGNED',
                        $streak >= 3 => 'SP-2',
                        default => 'SP-1',
                    };

                    $hasil[] = [
                        'enroll_id' => $enroll_id,
                        'employee_name' => $nama,
                        'jumlah_hari_mangkir' => $streak,
                        'mulai' => Carbon::parse($tanggal_mulai)->translatedFormat('d F Y'),
                        'selesai' => Carbon::parse($tanggal_akhir)->translatedFormat('d F Y'),
                        'kategori' => $kategori,
                        'department_name' => $absens[0]['department_name'] ?? '-',
                        'nik' => $absens[0]['nik'] ?? '-',
                        'status_jabatan' => $absens[0]['status_jabatan'] ?? '-',
                        'alamat_rumah' => $absens[0]['alamat_rumah'] ?? '-',
                        'status_staff' => $absens[0]['status_staff'] ?? '-',
                        'sub_dept_name' => $absens[0]['sub_dept_name'] ?? '-'
                    ];
                }
             }
        }
        return Excel::download(new exportExcelHadirLayoff($hasil), 'Rekap hadir layoff.xlsx');
    }

    public function sp_hadir(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '10240000000000000M');

        $inSearchVariable='';
        $inEnrollId='';
        if (request("search_variable")) {
            $search_variable=request()->search_variable;
            $inSearchVariable = 'AND (ea.enroll_id = "'.$search_variable.'" or ea.nik LIKE "'.$search_variable.'%" or ea.employee_name LIKE "%'.$search_variable.'%" or ea.tempat_lahir LIKE "%'.$search_variable.'%" or ea.nomor_tlpn LIKE "'.$search_variable.'%" or ea.agama LIKE "'.$search_variable.'%" or ea.status_kawin LIKE "'.$search_variable.'%" or ea.nomor_kk LIKE "'.$search_variable.'%" or ea.pendidikan_terakhir LIKE "'.$search_variable.'%" or ea.jurusan_pendidikan LIKE "'.$search_variable.'%" or ea.alamat_rumah LIKE "%'.$search_variable.'%" or ea.department_name LIKE "%'.$search_variable.'%" or ea.sub_dept_name LIKE "%'.$search_variable.'%" or ea.status_aktif LIKE "'.$search_variable.'%" or ea.ibu_kandung LIKE "%'.$search_variable.'%" or ea.nomor_ktp LIKE "'.$search_variable.'%")';
        }
        if(request()->enroll_id){
            $enroll_id = request()->enroll_id;
            $enroll_id_string = implode(',', $enroll_id);
            $inEnrollId='AND ea.enroll_id in ('.$enroll_id_string.')';
        }

        $date = date('Y-m-d');


       $query = DB::select(DB::raw("
            SELECT mda.tanggal_berjalan, mda.kode_hari, mda.enroll_id, ea.employee_name, mda.status_absen, ea.department_name
            FROM master_data_absen_kehadiran mda
            JOIN employee_atribut AS ea ON mda.enroll_id = ea.enroll_id
            WHERE mda.status_absen = 'M' ".$inSearchVariable." ".$inEnrollId."
            AND mda.tanggal_berjalan between '2024-01-01' and '".$date."'
        "));


        $streak = 0;
        $maxStreak = 5;
        $gapThreshold = 2;
        $result = [];
        $tempGroup = [];
        $prevKodeHari = null;
        $prevEnrollId = null;
        $prevDate = null;
        $prevMonth = null;
        $prevYear = null;

        foreach ($query as $index => $row) {
            $currentDate = new \DateTime($row->tanggal_berjalan);
            $currentMonth = $currentDate->format('m');
            $currentYear = $currentDate->format('Y');


            if ($prevEnrollId !== null && $row->enroll_id === $prevEnrollId) {

                $dateDiff = $prevDate ? $prevDate->diff($currentDate)->days : 0;


                if (($currentMonth == $prevMonth && $currentYear == $prevYear && $dateDiff <= $gapThreshold) ||
                    ($currentMonth == $prevMonth && $currentYear == $prevYear)) {

                    $streak++;
                    $tempGroup[] = $row;
                } else {

                    if ($streak >= $maxStreak) {

                        $tempGroup[0]->tanggal_mulai = $tempGroup[0]->tanggal_berjalan;
                        $tempGroup[count($tempGroup) - 1]->tanggal_selesai = $tempGroup[count($tempGroup) - 1]->tanggal_berjalan;

                        $tempGroup[0]->jumlah_hari_mangkir = count($tempGroup);


                        $result[] = $tempGroup;
                    }


                    $streak = 1;
                    $tempGroup = [$row];
                }
            } else {

                if ($streak >= $maxStreak) {

                    $tempGroup[0]->tanggal_mulai = $tempGroup[0]->tanggal_berjalan;
                    $tempGroup[count($tempGroup) - 1]->tanggal_selesai = $tempGroup[count($tempGroup) - 1]->tanggal_berjalan;

                    $tempGroup[0]->jumlah_hari_mangkir = count($tempGroup);
                    $result[] = $tempGroup;
                }


                $streak = 1;
                $tempGroup = [$row];
            }


            $prevKodeHari = $row->kode_hari;
            $prevEnrollId = $row->enroll_id;
            $prevDate = $currentDate;
            $prevMonth = $currentMonth;
            $prevYear = $currentYear;
        }


        if ($streak >= $maxStreak) {

            $tempGroup[0]->tanggal_mulai = $tempGroup[0]->tanggal_berjalan;
            $tempGroup[count($tempGroup) - 1]->tanggal_selesai = $tempGroup[count($tempGroup) - 1]->tanggal_berjalan;

            $tempGroup[0]->jumlah_hari_mangkir = count($tempGroup);
            $result[] = $tempGroup;
        }

        $firstResult = [];


        foreach ($result as $key => $value) {
            $firstResult[] =  [
                'mulai' => Carbon::parse($value[0]->tanggal_mulai)->translatedFormat('d F Y'),
                'selesai' => Carbon::parse($value[count($value) - 1]->tanggal_selesai)->translatedFormat('d F Y'),
                'jumlah_hari_mangkir' => $value[0]->jumlah_hari_mangkir,
                'enroll_id' => $value[0]->enroll_id,
                'employee_name' => $value[0]->employee_name,
                'department_name' => $value[0]->department_name
            ];
        }
        return DataTables::of($firstResult)->toJson();
    }

    public function export_pdf_print_sk(){
        $tipe_surat=request()->tipe_surat;
        foreach($tipe_surat as $key=>$value){
            $sudah_diprint=EmployeeAtribut::where('enroll_id',$key)->first()->sudah_diprint;
            if($sudah_diprint==1){
                continue;
            }else{
                $data[]=[
                    'enroll_id'=>$key,
                    'nik'=>EmployeeAtribut::where('enroll_id',$key)->first()->nik,
                    'employee_name'=>EmployeeAtribut::where('enroll_id',$key)->first()->employee_name,
                    'join_date'=>EmployeeAtribut::where('enroll_id',$key)->first()->join_date,
                    'status_aktif'=>EmployeeAtribut::where('enroll_id',$key)->first()->status_aktif,
                    'status_jabatan'=>EmployeeAtribut::where('enroll_id',$key)->first()->status_jabatan,
                    'tanggal_resign'=>EmployeeAtribut::where('enroll_id',$key)->first()->tanggal_resign,
                    'department_name'=>EmployeeAtribut::where('enroll_id',$key)->first()->department_name,
                    'sub_dept_name'=>EmployeeAtribut::where('enroll_id',$key)->first()->sub_dept_name,
                    'site_nirwana_name'=>EmployeeAtribut::where('enroll_id',$key)->first()->site_nirwana_name,
                    'alamat_rumah'=>EmployeeAtribut::where('enroll_id',$key)->first()->alamat_rumah,
                    'sebab_resign'=>EmployeeAtribut::where('enroll_id',$key)->first()->sebab_resign,
                    'tipe_surat'=>$value,
                    'no_surat'=>EmployeeAtribut::where('enroll_id',$key)->first()->no_surat,
                    'catatan'=>EmployeeAtribut::where('enroll_id',$key)->first()->catatan,
                ];
            }
        }
        if(isset($data)){
            $no_forms=request()->no_form;
            $fileName='all sk kerja.'.date('His').'_'.rand();
            $pdf = PDF::loadView('hris.laporan.all_sk_kerja_karyawan',["data" => $data,"no_form"=>$no_forms])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
            return $pdf;
        }else{
            $pdf = PDF::loadView('hris.laporan.blank_page')->setPaper('A4', 'fotrait')->stream('no data receipt'.'.pdf');
            return $pdf;
        }
    }
    public function export_pdf_sk_kerja(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $no_surat=request()->no_surat;
        $reason=request()->reason;
        $data=DB::select("select*from employee_atribut where enroll_id='$enroll_id'");
        $fileName=request()->enroll_id.'_'.date('His');
        $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan',["data" => $data,"no_form"=>$no_form,"reason"=>$reason,"no_surat"=>$no_surat])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        return $pdf;

    }
    public function card_employee_form_identity(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $type=request()->type;

        if($type == 'VOUCHER'){
            $data = VoucherBazzar::leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'voucher_bazzar.enroll_id')->where('nomor_voucher', $no_form)
                ->orderBy('voucher_bazzar.enroll_id', 'ASC')
                ->get();
        }else{
            $data=DB::select("select*from employee_atribut where enroll_id='$enroll_id'");
        }
        return view('hris/card_employee_form_identity', ["data" => $data,"no_form"=>$no_form, "type"=>$type]);
    }
    public function export_sp_kehadiran_karyawan(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $reason=request()->reason;
        $fileName=request()->enroll_id.'_'.date('His');
        $date_now = Carbon::parse(date('Y-m-d'))->translatedFormat('d F Y');


        $date = date('Y-m-d');


       $query = DB::select(DB::raw("
            SELECT mda.tanggal_berjalan, mda.kode_hari, mda.enroll_id, ea.employee_name, ea.nik, ea.status_jabatan, ea.alamat_rumah, mda.status_absen, ea.department_name
            FROM master_data_absen_kehadiran mda
            JOIN employee_atribut AS ea ON mda.enroll_id = ea.enroll_id
            WHERE mda.status_absen = 'M' AND mda.enroll_id = '$enroll_id'
            AND mda.tanggal_berjalan >= '2024-01-01'
        "));

        $streak = 0;
        $maxStreak = 5;
        $gapThreshold = 2;
        $result = [];
        $tempGroup = [];
        $prevKodeHari = null;
        $prevEnrollId = null;
        $prevDate = null;
        $prevMonth = null;
        $prevYear = null;

        foreach ($query as $index => $row) {
            $currentDate = new \DateTime($row->tanggal_berjalan);
            $currentMonth = $currentDate->format('m');
            $currentYear = $currentDate->format('Y');


            if ($prevEnrollId !== null && $row->enroll_id === $prevEnrollId) {

                $dateDiff = $prevDate ? $prevDate->diff($currentDate)->days : 0;


                if (($currentMonth == $prevMonth && $currentYear == $prevYear && $dateDiff <= $gapThreshold) ||
                    ($currentMonth == $prevMonth && $currentYear == $prevYear)) {

                    $streak++;
                    $tempGroup[] = $row;
                } else {

                    if ($streak >= $maxStreak) {

                        $tempGroup[0]->tanggal_mulai = $tempGroup[0]->tanggal_berjalan;
                        $tempGroup[count($tempGroup) - 1]->tanggal_selesai = $tempGroup[count($tempGroup) - 1]->tanggal_berjalan;

                        $tempGroup[0]->jumlah_hari_mangkir = count($tempGroup);


                        $result[] = $tempGroup;
                    }


                    $streak = 1;
                    $tempGroup = [$row];
                }
            } else {

                if ($streak >= $maxStreak) {

                    $tempGroup[0]->tanggal_mulai = $tempGroup[0]->tanggal_berjalan;
                    $tempGroup[count($tempGroup) - 1]->tanggal_selesai = $tempGroup[count($tempGroup) - 1]->tanggal_berjalan;

                    $tempGroup[0]->jumlah_hari_mangkir = count($tempGroup);
                    $result[] = $tempGroup;
                }


                $streak = 1;
                $tempGroup = [$row];
            }


            $prevKodeHari = $row->kode_hari;
            $prevEnrollId = $row->enroll_id;
            $prevDate = $currentDate;
            $prevMonth = $currentMonth;
            $prevYear = $currentYear;
        }


        if ($streak >= $maxStreak) {

            $tempGroup[0]->tanggal_mulai = $tempGroup[0]->tanggal_berjalan;
            $tempGroup[count($tempGroup) - 1]->tanggal_selesai = $tempGroup[count($tempGroup) - 1]->tanggal_berjalan;

            $tempGroup[0]->jumlah_hari_mangkir = count($tempGroup);
            $result[] = $tempGroup;
        }

        $firstResult = [];


        foreach ($result as $key => $value) {
            $firstResult[] =  [
                'mulai' => $value[0]->tanggal_mulai,
                'selesai' => $value[count($value) - 1]->tanggal_selesai,
                'jumlah_hari_mangkir' => $value[0]->jumlah_hari_mangkir,
                'enroll_id' => $value[0]->enroll_id,
                'employee_name' => $value[0]->employee_name,
                'department_name' => $value[0]->department_name,
                'nik' => $value[0]->nik,
                'status_jabatan' => $value[0]->status_jabatan,
                'alamat_rumah' => $value[0]->alamat_rumah,
            ];
        }
        $pdf = PDF::loadView('hris.sp_kehadiran_karyawan',["data" => $firstResult[0],"no_form"=>$no_form,"reason"=>$reason])->setPaper('letter', 'fotrait')->stream($fileName.'.pdf');
        return $pdf;
    }
    public function export_pdf_paklaring(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $no_surat=request()->no_surat;
        $reason=request()->reason;
        $data=DB::select("select*from employee_atribut where enroll_id='$enroll_id'");
        if($data[0]->tanggal_resign==null){
            $fileName=request()->enroll_id.'_'.date('His');
            $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan_lebih_1_tahun',["data" => $data,"no_form"=>$no_form,"reason"=>$reason,"no_surat"=>$no_surat])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
            return $pdf;
        }else{
            $tanggal_masuk = $data[0]->join_date;
            $tanggal_resign = $data[0]->tanggal_resign;
            $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_resign))->y;
            if($selisih_tahun<1){
                $fileName=request()->enroll_id.'_'.date('His');
                $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan_lebih_1_tahun',["data" => $data,"no_form"=>$no_form,"reason"=>$reason,"no_surat"=>$no_surat])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
                return $pdf;
            }else{
                $fileName=request()->enroll_id.'_'.date('His');
                $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan_lebih_1_tahun',["data" => $data,"no_form"=>$no_form,"reason"=>$reason,"no_surat"=>$no_surat])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
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
        $fileName='Surat Ketarangan BNI '.$data[0]->employee_name.'_'.request()->enroll_id.'_'.date('His');
        $pdf = PDF::loadView('hris.laporan.sk_bni',["data" => $data,"no_form"=>$no_form,"item"=>$item,"reason"=>$reason,"created_date"=>$created_date])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        return $pdf;
    }
    public function kontrak_kerja(){
        $selectEmployee =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')->groupby('enroll_id')->orderby('employee_name', 'asc')->get();
        $selectNoKTP = EmployeeAtribut::selectRaw('nomor_ktp')->groupby('nomor_ktp')->orderby('nomor_ktp', 'asc')->get();
        $department =DepartmentAll::select('department_name')->distinct()->orderBy('department_id')->groupBy('department_id')->get();
        return View::make('hris/hrd/kontrak_kerja',compact('selectEmployee','selectNoKTP','department'), $this->data);
    }
    public function get_employee_contract(){
        $inSearchVariable='';
        $inEnrollId='';
        $inIbuKandung='';
        $inNoKTP='';
        $inStatusStaff='';
        $statusPenilaian="AND y.status_penilaian IS NULL";
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
            }
        }
        if(request()->enroll_id){
            $enroll_id_array = request()->enroll_id;
            // Escaping tiap item untuk mencegah SQL Injection (opsional jika pakai query builder)
            $enroll_id_string = implode(',', array_map('intval', $enroll_id_array));
            $inEnrollId = 'AND z.enroll_id IN (' . $enroll_id_string . ')';
        }
        if(request()->ibu_kandung){
            $ibu_kandung_string=request()->ibu_kandung;
            $inIbuKandung='AND z.ibu_kandung LIKE "%'.$ibu_kandung_string.'%"';
        }
        if(request()->no_ktp){
            $no_ktp_string=request()->no_ktp;
            $inNoKTP='AND z.nomor_ktp LIKE "'.$no_ktp_string.'%"';
        }
        if(request()->status_staff){
            $status_staff_string=request()->status_staff;
            $inStatusStaff='AND z.status_staff LIKE "'.$status_staff_string.'%"';
        }
        if(request()->status_penilaian){
            $status_penilaian=request()->status_penilaian;
            $statusPenilaian = "AND y.status_penilaian = '{$status_penilaian}'";
        }
        $inStatusKontrak='';
        $today = date('Y-m-d');

        if($status_kontrak=='One Day'){
            $one_days_later = date('Y-m-d', strtotime('+1 days'));
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
            ) <= "'.$thirty_days_later.'"';
        }else if($status_kontrak=='Sixty Day'){
            $thirty_day_more = date('Y-m-d',strtotime('+60 days',strtotime(date("Y-m-d")))) . PHP_EOL;
            $inStatusKontrak= 'AND (
                CASE
                    WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
                    ELSE y.contract_end
                END
            ) >= "'.$thirty_day_more.'"';
        }
        else if($status_kontrak=='Not yet extended'){
            $inStatusKontrak= 'AND z.tanggal_resign IS NULL AND y.contract_end <= "'.$today.'"';
        }
        $inStatusAktif='';
        if(request()->status_aktif){
            $status_aktif=request()->status_aktif;
            $inStatusAktif='AND z.status_aktif = "'.$status_aktif.'"';
        }
        $inDateRangeContract='';
        if(request()->contract){
            $daterange1 = explode(" s/d ", request()->contract);
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
        }

        $inDepartment_name='';
        if(request("department_name")){
            $department=request("department_name");
            $inDepartment_name = ' AND z.department_name = "'.$department.'"';
        }

        $inContractAfterResign = 'AND z.tanggal_resign IS NOT NULL AND y.contract > z.tanggal_resign';

        $data_input = DB::select("
        SELECT
            z.enroll_id,
            z.nik,
            z.employee_name,
            z.department_name,
            z.sub_dept_name,
            z.status_aktif,
            z.status_staff,
            z.tanggal_resign,
            z.ibu_kandung,
            z.nomor_ktp,
            y.status_penilaian,
            y.id,
            y.contract,
            -- logika untuk mengganti contract_end dengan tanggal_resign jika ada
            -- CASE
            --    WHEN z.tanggal_resign IS NOT NULL THEN z.tanggal_resign
            --     ELSE y.contract_end
            -- END y.contract_end AS contract_end
            y.contract_end AS contract_end
        FROM (
           SELECT
            a.enroll_id,
            a.id,
            a.status_penilaian,
            a.contract,
            a.contract_end
        FROM employee_contract a
        INNER JOIN (
            SELECT enroll_id, MAX(contract_end) AS contract_end
            FROM employee_contract
            GROUP BY enroll_id
        ) e ON a.enroll_id = e.enroll_id AND a.contract_end = e.contract_end
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
            $inSearchVariable
            $inIbuKandung
            $inNoKTP
            $inStatusKontrak
            $inStatusAktif
            $inEnrollId
            $inStatusStaff
            $statusPenilaian
            $inDateRangeContract
            $inDepartment_name
        GROUP BY z.enroll_id
        ORDER BY z.enroll_id
    ");
    return DataTables::of($data_input)->toJson();

    }
    public function get_employee_contract2(){

        $enroll_id = request()->id;

        $contracts = DB::select("
            SELECT
                ec.*,
                CASE
                    WHEN z.tanggal_resign IS NOT NULL
                         AND z.tanggal_resign BETWEEN ec.contract AND ec.contract_end
                    THEN z.tanggal_resign
                    ELSE NULL
                END AS tanggal_resign
            FROM employee_contract ec
            RIGHT JOIN (
                SELECT
                    enroll_id,
                    tanggal_resign
                FROM employee_atribut
            ) z ON ec.enroll_id = z.enroll_id
            WHERE ec.enroll_id = '$enroll_id'
            ORDER BY ec.contract_end
        ");

        return $contracts;
    }

    public function update_employee_contract(){
        $id=request()->last_id;
        $start_contract=request()->start_contract;
        $last_date=request()->last_date;
        DB::update("UPDATE employee_contract
        SET contract = ?, contract_end = ?
        WHERE id = ?", [$start_contract, $last_date, $id]);

        $updatedData = DB::table('employee_contract')->where('id', $id)->first();

        $query = EmployeeAtribut::whereRaw('enroll_id = "' . $updatedData->enroll_id . '"')
        ->update([
            'tanggal_mulai_kontrak' => $start_contract,
            'tanggal_akhir_kontrak' => $last_date,
        ]);

        return $updatedData->enroll_id;
    }
    // public function new_employee_contract(){
    //     $timestamp = Carbon::now();
    //     $enroll_id=request()->id;
    //     $contract=request()->contract;
    //     $end_contract=request()->end_contract;
    //     DB::insert("insert into employee_contract (id, enroll_id, contract, contract_end, created_at, updated_at) VALUES ('','$enroll_id','$contract','$end_contract','$timestamp','$timestamp')");
    //     $query = EmployeeAtribut::whereRaw('enroll_id = "' . $enroll_id . '"')
    //         ->update([
    //             'tanggal_mulai_kontrak' => $contract,
    //             'tanggal_akhir_kontrak' => $end_contract,
    //         ]);
    //     DB::table('employee_contract')
    //                     ->where('enroll_id', $enroll_id)
    //                     ->update(['status_penilaian' => null]);
    //     return $enroll_id;
    // }
public function new_employee_contract()
{
    $enroll_id    = request()->id;
    $contract     = Carbon::parse(request()->contract);
    $end_contract = Carbon::parse(request()->end_contract);
    $timestamp    = Carbon::now();

    // 🔎 Cek apakah tanggal mulai masih di dalam kontrak lama
    $exists = DB::table('employee_contract')
        ->where('enroll_id', $enroll_id)
        ->whereDate('contract', '<=', $contract)
        ->whereDate('contract_end', '>=', $contract)
        ->exists();

    if ($exists) {
        return response()->json([
            'message' => 'Tanggal mulai kontrak masih berada dalam periode kontrak sebelumnya.'
        ], 422);
    }

    // ✅ Simpan kontrak baru
    DB::table('employee_contract')->insert([
        'enroll_id'        => $enroll_id,
        'contract'         => $contract,
        'contract_end'     => $end_contract,
        'status_penilaian' => null,
        'created_at'       => $timestamp,
        'updated_at'       => $timestamp,
    ]);

    // Update atribut employee
    EmployeeAtribut::where('enroll_id', $enroll_id)
        ->update([
            'tanggal_mulai_kontrak' => $contract,
            'tanggal_akhir_kontrak' => $end_contract,
        ]);

    return response()->json([
        'message' => 'Kontrak berhasil dibuat',
        'enroll_id' => $enroll_id
    ]);
}

    public function delete_employee_contract(){
        $id=request()->id;
        $data_contract = DB::table('employee_contract')->where('id', $id)->first();
        $enroll_id = $data_contract->enroll_id;
        $contract = $data_contract->contract;
        $contract_end = $data_contract->contract_end;
        $data_penilaian = PenilaianKinerja::where('enroll_id', $enroll_id)->where('tgl_awal_kontrak',$contract)->where('tgl_akhir_kontrak', $contract_end)->first();
        if($data_penilaian){
            $data_penilaian->delete();
        }
        DB::delete("delete from employee_contract where id = '$id'");
        $lastContract = DB::table('employee_contract')
        ->where('enroll_id', $enroll_id)
        ->orderByDesc('contract')
        ->first();

        if($lastContract){
            EmployeeAtribut::where('enroll_id', $enroll_id)->update([
                'tanggal_mulai_kontrak' => $lastContract->contract,
                'tanggal_akhir_kontrak' => $lastContract->contract_end
            ]);
        }
        return request()->enroll_id;
    }
    public function import_kontrak_kerja(){
        $import = new KontrakKerjaImport;
        Excel::import($import, request()->file('excel_file'));
        return $import->getRowCount();
    }
    public function import_kontrak_kerja_to_database(Request $request){
        ini_set("max_execution_time", 0);
        ini_set("max_input_time", 0);
        // Excel::import(new KontrakKerjaImportToDatabase, request()->file('excel_file'));
        $import = new KontrakKerjaImportToDatabase;
        Excel::import($import, $request->file('excel_file'));

        return response()->json([
            'success' => true,
            'enroll_ids' => $import->importedEnrollIds,
        ]);
    }
    public function export_excel_kontrak(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '10240000000000000M');
        $inSearchVariable='';
        $inNoKTP='';
        $inEnrollId='';
        $inIbuKandung='';
        $inStatusAktif='';
        $inStatusStaff='';
        $inStatusKontrak='';
        $inDepartment='';
        $incheckedEmployeeArr='';
        $inDateRange='';
        if(request()->date_range){
            $daterange1 = explode(" s/d ", request()->date_range);
            $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
            $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
            $inDateRange='AND c.max_contract_end BETWEEN "'.$tanggalMulai.'" AND "'.$tanggalSampai.'"';
        }
        if (request("search_variable")) {
            $search_variable=request()->search_variable;
            $inSearchVariable = 'AND (a.enroll_id = "'.$search_variable.'" or a.nik LIKE "'.$search_variable.'%" or a.employee_name LIKE "%'.$search_variable.'%" or a.tempat_lahir LIKE "%'.$search_variable.'%" or a.nomor_tlpn LIKE "'.$search_variable.'%" or a.agama LIKE "'.$search_variable.'%" or a.status_kawin LIKE "'.$search_variable.'%" or a.nomor_kk LIKE "'.$search_variable.'%" or a.pendidikan_terakhir LIKE "'.$search_variable.'%" or a.jurusan_pendidikan LIKE "'.$search_variable.'%" or a.alamat_rumah LIKE "%'.$search_variable.'%" or a.department_name LIKE "%'.$search_variable.'%" or a.sub_dept_name LIKE "%'.$search_variable.'%" or a.status_aktif LIKE "'.$search_variable.'%" or a.ibu_kandung LIKE "%'.$search_variable.'%" or a.nomor_ktp LIKE "'.$search_variable.'%")';
        }
        if(request()->no_ktp){
            $no_ktp_string=request()->no_ktp;
            $inNoKTP='AND a.nomor_ktp LIKE "'.$no_ktp_string.'%"';
        }
        if(request()->enroll_id){
            $enroll_id=request()->enroll_id;
            $enroll_id_string=implode(',', $enroll_id);
            $inEnrollId='AND a.enroll_id in ('.$enroll_id_string.')';
        }
        if(request()->ibu_kandung){
            $ibu_kandung_string=request()->ibu_kandung;
            $inIbuKandung='AND a.ibu_kandung LIKE "%'.$ibu_kandung_string.'%"';
        }
        if(request()->status_aktif){
            $status_aktif=request()->status_aktif;
            $inStatusAktif='AND a.status_aktif = "'.$status_aktif.'"';
        }
        if(request()->status_staff){
            $status_staff=request()->status_staff;
            $inStatusStaff='AND a.status_staff = "'.$status_staff.'"';
        }
        if(request()->department_id){
            $department_id=request()->department_id;
            $inDepartment='AND a.department_name = "'.$department_id.'"';
        }
        if(request()->checkedEmployeeArr){
            $incheckedEmployeeArr='AND a.enroll_id in (' . implode(',', request()->checkedEmployeeArr) . ')';
        }
        if(request()->status_kontrak){
            $today = date('Y-m-d');
            $status_kontrak=request()->status_kontrak;
            if($status_kontrak=='One Day'){
                $one_days_later = date('Y-m-d', strtotime('+1 days'));
                $inStatusKontrak='AND c.max_contract_end BETWEEN "'.$today.'" AND "'.$one_days_later.'"';
            }else if($status_kontrak=='Nine Day'){
                $nine_days_later = date('Y-m-d', strtotime('+9 days'));
                $inStatusKontrak='AND c.max_contract_end BETWEEN "'.$today.'" AND "'.$nine_days_later.'"';
            }else if($status_kontrak=='Thirty Day'){
                $thirty_days_later = date('Y-m-d', strtotime('+30 days'));
                $inStatusKontrak = 'AND c.max_contract_end BETWEEN "'.$today.'" AND "'.$thirty_days_later.'"';
            }else if($status_kontrak=='Sixty Day'){
                $thirty_day_more = date('Y-m-d',strtotime('+60 days',strtotime(date("Y-m-d")))) . PHP_EOL;
                $inStatusKontrak='AND c.max_contract_end >= "'.$thirty_day_more.'"';
            }else if($status_kontrak=='Not yet extended'){
                $inStatusKontrak= 'AND a.tanggal_resign IS NULL AND c.max_contract_end <= "'.$today.'"';
            }
        }
        $query = DB::select("
                SELECT
                    a.status_staff,
                    a.enroll_id,
                    a.nik,
                    a.employee_name,
                    a.status_jabatan,
                    a.sub_dept_name,
                    a.department_name,
                    a.department_id,
                    a.status_kontrak_tetap,
                    a.status_aktif,
                    a.status_staff,
                    a.join_date,
                    a.tanggal_resign,
                    a.nomor_ktp,
                    b.contract,
                    b.contract_end,
                    c.max_contract_end,
                    d.contract AS contract_last,
                    d.contract_end AS contract_end_last
                FROM employee_atribut a
                LEFT JOIN employee_contract b
                    ON a.enroll_id = b.enroll_id
                LEFT JOIN (
                    SELECT enroll_id, MAX(contract_end) AS max_contract_end
                    FROM employee_contract
                    GROUP BY enroll_id
                ) c ON a.enroll_id = c.enroll_id
                LEFT JOIN (
                    SELECT *
                    FROM (
                        SELECT enroll_id, contract, contract_end,
                            ROW_NUMBER() OVER (PARTITION BY enroll_id ORDER BY contract_end DESC) as rn
                        FROM employee_contract
                    ) sub
                    WHERE rn = 1
                ) d ON a.enroll_id = d.enroll_id
                WHERE a.enroll_id IS NOT NULL
                    $inSearchVariable
                    $inEnrollId
                    $inNoKTP
                    $incheckedEmployeeArr
                    $inIbuKandung
                    $inStatusAktif
                    $inStatusKontrak
                    $inStatusStaff
                    $inDepartment
                    $inDateRange
                ORDER BY a.enroll_id, b.contract_end
            ");
        return Excel::download(new exportExcelKontrak($query), 'Laporan_Penerimaan FG_Stok.xlsx');
    }
    public function print_pdf_kontrak(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;

        $data=DB::select("
                    SELECT
                    a.status_staff,
                    a.enroll_id,
                    a.nik,
                    a.employee_name,
                    a.status_jabatan,
                    a.sub_dept_name,
                    a.department_name,
                    a.status_kontrak_tetap,
                    a.status_aktif,
                    a.join_date,
                    a.tanggal_resign,
                    a.nomor_ktp,
                    a.tempat_lahir,
                    a.alamat_rumah,
                    a.tanggal_lahir,
                    a.no_surat,
                    a.tanggal_mulai_kontrak,
                    b.contract,
                    b.contract_end,
                    c.max_contract,
                    c.max_contract_end,
                    mda.*
                    FROM employee_atribut a
                    LEFT JOIN employee_contract b ON a.enroll_id = b.enroll_id
                    LEFT JOIN (
                    SELECT
                        enroll_id,
                        MAX(contract) AS max_contract,
                        MAX(contract_end) AS max_contract_end
                    FROM employee_contract
                    GROUP BY enroll_id
                    ) c ON a.enroll_id = c.enroll_id
                    LEFT JOIN (
                    SELECT mulai_jam_kerja, akhir_jam_kerja, tanggal_berjalan, enroll_id
                    FROM master_data_absen_kehadiran
                    WHERE (enroll_id, tanggal_berjalan) IN (
                        SELECT enroll_id, MAX(tanggal_berjalan)
                        FROM master_data_absen_kehadiran
                        GROUP BY enroll_id
                    )
                    ) mda ON a.enroll_id = mda.enroll_id
                    WHERE  a.enroll_id=".$enroll_id." ORDER BY a.enroll_id, b.contract_end DESC");
        foreach ($data as $item) {

            $start = Carbon::parse($item->contract);
            $end   = Carbon::parse($item->tanggal_resign ?? $item->contract_end ?? now());

            $tahun_umk = $start->year;

            // if ($start->month == 12 && $start->year < $end->year) {
            //     $tahun_umk = $end->year;
            // }

            if ($tahun_umk <= 2020) {
                $tahun_umk = 2021;
            }

            $grading = GradingSalary::where('kode_grade', 'D')
                ->where('periode_umk', $tahun_umk)
                ->first();

            // SIMPAN KE DATA KONTRAK
            $item->umk = $grading->salary_bulanan;
            $item->umk_latin = $grading->salary_latin;
        }


        $fileName='PKS ' .request()->enroll_id.' ' .$data[0]->employee_name.' '.Carbon::parse($data[0]->contract)->translatedFormat('d-m-Y').' ';
        // $pdf = PDF::loadView('hris.laporan.kontrak_kerja_karyawan',["no_form"=>$no_form,"data" => $data,"umk"=>$umk])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        $pdf = PDF::loadView('hris.laporan.kontrak_kerja_karyawan',["no_form"=>$no_form,"data" => $data])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        return $pdf;
    }
    public function print_pdf_kontrak_2(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $contract=request()->contract;
        $contract_end=request()->contract_end;
        // $data=DB::select("select a.status_staff,c.max_contract,mda.*,
        //             c.max_contract_end,a.enroll_id,a.nik,a.employee_name,a.status_jabatan,a.sub_dept_name,a.department_name,a.status_kontrak_tetap,a.status_aktif,a.join_date,a.tanggal_resign,a.nomor_ktp,a.tempat_lahir,a.alamat_rumah,a.tanggal_lahir,a.no_surat,b.contract,b.contract_end,c.max_contract,c.max_contract_end from employee_atribut a left join employee_contract b on a.enroll_id=b.enroll_id left join (select enroll_id,max(contract) max_contract,max(contract_end) max_contract_end from employee_contract group by enroll_id)c on a.enroll_id=c.enroll_id LEFT JOIN (
        //             SELECT mulai_jam_kerja, akhir_jam_kerja, tanggal_berjalan, enroll_id
        //             FROM master_data_absen_kehadiran
        //             WHERE (enroll_id, tanggal_berjalan) IN (
        //                 SELECT enroll_id, MAX(tanggal_berjalan)
        //                 FROM master_data_absen_kehadiran
        //                 GROUP BY enroll_id
        //             )
        //             ) mda ON a.enroll_id = mda.enroll_id where a.enroll_id=".$enroll_id." group by a.enroll_id order by a.enroll_id, b.contract_end desc");
        $data = DB::select("
                 SELECT
                    a.status_staff,
                    a.enroll_id,
                    a.nik,
                    a.employee_name,
                    a.status_jabatan,
                    a.sub_dept_name,
                    a.department_name,
                    a.status_kontrak_tetap,
                    a.status_aktif,
                    a.join_date,
                    a.tanggal_resign,
                    a.nomor_ktp,
                    a.tempat_lahir,
                    a.alamat_rumah,
                    a.tanggal_lahir,
                    a.no_surat,
                    b.contract,
                    b.contract_end,
                    mda.*
                FROM employee_atribut a
                INNER JOIN employee_contract b
                    ON a.enroll_id = b.enroll_id
                    AND b.contract = '".$contract."'
                    AND b.contract_end = '".$contract_end."'
                LEFT JOIN (
                    SELECT mulai_jam_kerja, akhir_jam_kerja, tanggal_berjalan, enroll_id
                    FROM master_data_absen_kehadiran
                    WHERE tanggal_berjalan = CURDATE()
                ) mda ON a.enroll_id = mda.enroll_id
                WHERE a.enroll_id = ".$enroll_id."
            ");
            foreach ($data as $item) {   // agar sesua dengan kontrak dan enroll

                // ACUAN TAHUN DARI KONTRAK YANG DIPILIH
                $start = Carbon::parse($contract);
                $end   = Carbon::parse($contract_end);

                $tahun_umk = $start->year;

                // Khusus 2020 ke bawah → pakai 2021
                if ($tahun_umk <= 2020) {
                    $tahun_umk = 2021;
                }

                $grading = GradingSalary::where('kode_grade', 'D')
                    ->where('periode_umk', $tahun_umk)
                    ->first();

                $item->umk = $grading?->salary_bulanan ?? 0;
                $item->umk_latin = ucwords(strtolower($grading?->salary_latin ?? ''));
            }


        // $umk=DasarPotBPJS::orderBy('created_at','desc')->limit(1)->first()->dasar_pot_bpjs_rupiah;
        $fileName='PKS ' .request()->enroll_id.' ' .$data[0]->employee_name.' '.Carbon::parse($contract)->translatedFormat('d-m-Y').' ';
        $pdf = PDF::loadView('hris.laporan.kontrak_kerja_karyawan_2',["no_form"=>$no_form,"contract2"=>$contract,"contract_end2"=>$contract_end,"data" => $data])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        return $pdf;
    }



      public function download_excel_rekap_pkwt(Request $request){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '10240000000000000M');

        $inEnrollId='';
        $inIbuKandung='';
        $inStatusAktif='';
        $inStatusStaff='';
        $inStatusKontrak='';
        $inDepartment='';
        $inDateRange='';
        if(request()->date_range){
            $daterange1 = explode(" s/d ", request()->date_range);
            $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
            $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
            $inDateRange='AND c.max_contract_end BETWEEN "'.$tanggalMulai.'" AND "'.$tanggalSampai.'"';
        }
        if(request()->enroll_id){
            $enroll_id=request()->enroll_id;
            $enroll_id_string=implode(',', $enroll_id);
            $inEnrollId='AND a.enroll_id in ('.$enroll_id_string.')';
        }
        if(request()->status_aktif){
            $status_aktif=request()->status_aktif;
            $inStatusAktif='AND a.status_aktif = "'.$status_aktif.'"';
        }
        if(request()->status_staff){
            $status_staff=request()->status_staff;
            $inStatusStaff='AND a.status_staff = "'.$status_staff.'"';
        }
        if(request()->department_name){
            $department_name=request()->department_name;
            $inDepartment='AND a.department_name = "'.$department_name.'"';
        }
        if(request()->status_kontrak){
            $today = date('Y-m-d');
            $status_kontrak=request()->status_kontrak;
            if($status_kontrak=='One Day'){
                $one_days_later = date('Y-m-d', strtotime('+1 days'));
                $inStatusKontrak='AND c.max_contract_end BETWEEN "'.$today.'" AND "'.$one_days_later.'"';
            }else if($status_kontrak=='Nine Day'){
                $nine_days_later = date('Y-m-d', strtotime('+9 days'));
                $inStatusKontrak='AND c.max_contract_end BETWEEN "'.$today.'" AND "'.$nine_days_later.'"';
            }else if($status_kontrak=='Thirty Day'){
                $thirty_days_later = date('Y-m-d', strtotime('+30 days'));
                $inStatusKontrak = 'AND c.max_contract_end BETWEEN "'.$today.'" AND "'.$thirty_days_later.'"';
            }else if($status_kontrak=='Sixty Day'){
                $thirty_day_more = date('Y-m-d',strtotime('+60 days',strtotime(date("Y-m-d")))) . PHP_EOL;
                $inStatusKontrak='AND c.max_contract_end >= "'.$thirty_day_more.'"';
            }else if($status_kontrak=='Not yet extended'){
                $inStatusKontrak= 'AND a.tanggal_resign IS NULL AND c.max_contract_end <= "'.$today.'"';
            }
        }

        $data = DB::select("
        SELECT
            a.status_staff,
            a.enroll_id,
            a.nik,
            a.employee_name,
            a.status_jabatan,
            a.sub_dept_name,
            a.department_name,
            a.status_kontrak_tetap,
            a.status_aktif,
            a.join_date,
            a.tanggal_resign,
            a.nomor_ktp,
            a.tempat_lahir,
            a.alamat_rumah,
            a.tanggal_lahir,
            a.no_surat,
            b.contract,
            b.contract_end,
            c.max_contract,
            c.max_contract_end,
            b.jumlah_bulan,
            c.created_at
        FROM employee_atribut a
        LEFT JOIN employee_contract b ON a.enroll_id = b.enroll_id
        LEFT JOIN (
            SELECT
                ec1.enroll_id,
                ec1.jumlah_bulan,
                ec1.created_at,
                ec1.contract AS max_contract,
                ec1.contract_end AS max_contract_end
            FROM employee_contract ec1
            INNER JOIN (
                SELECT enroll_id, MAX(contract) AS max_contract
                FROM employee_contract
                GROUP BY enroll_id
            ) ec2 ON ec1.enroll_id = ec2.enroll_id AND ec1.contract = ec2.max_contract
        ) c ON a.enroll_id = c.enroll_id
         WHERE a.enroll_id IS NOT NULL $inDateRange $inEnrollId $inIbuKandung $inStatusAktif $inStatusStaff $inDepartment $inStatusKontrak
         ORDER BY
         COALESCE(b.contract, a.join_date) ASC
    ");
    // $tahun_umk = date('Y');
    // $tahun_umk = 'UMK '.$tahun_umk;

    // $umk = DasarPotBPJS::where('kode_dasar_pot_bpjs', $tahun_umk)->first()->dasar_pot_bpjs_rupiah ?? 0;
foreach ($data as $item) {

    $tanggal_masuk = $item->join_date;
    $contractEnd   = Carbon::parse($item->contract_end);
    $resignDate    = $item->tanggal_resign ? Carbon::parse($item->tanggal_resign) : null;

    // Tentukan tanggal akhir untuk perhitungan durasi
    $tanggal_akhir = $resignDate && $resignDate->lt($contractEnd) ? $resignDate : $contractEnd;

    // Hitung selisih tahun untuk tunjangan
    $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_akhir))->y;

    // Jika karyawan resign lebih awal dari kontrak dan belum 1 tahun, tunjangan = 0
    if ($resignDate && $resignDate->lt($contractEnd) && $selisih_tahun < 1) {
        $tunjangan = 0;
    } else {
        if ($selisih_tahun < 1) {
            $tunjangan = 0;
        } elseif ($selisih_tahun < 3) {
            $tunjangan = 2500;
        } elseif ($selisih_tahun < 6) {
            $tunjangan = 5000;
        } elseif ($selisih_tahun < 9) {
            $tunjangan = 7500;
        } elseif ($selisih_tahun < 12) {
            $tunjangan = 10000;
        } else {
            $tunjangan = 12500;
        }
    }

    // Batasi data lama
    $batasMulai = Carbon::create(2020, 11, 2);

    // Ambil tanggal awal & akhir
    $start = Carbon::parse($item->contract ?? $item->join_date);
    $end   = Carbon::parse($item->contract_end ?? $item->tanggal_resign ?? now());

    if ($start->lt($batasMulai)) {
        $start = $batasMulai;
    }

    // Tentukan tahun UMK
    $tahun_umk = $end->year;
    if ($start->month == 12 && $start->year < $end->year) {
        $tahun_umk = $end->year;
    }
    if ($tahun_umk <= 2020) {
        $tahun_umk = 2021;
    }

    // Ambil UMK
    $umk = GradingSalary::where('kode_grade', 'D')
            ->where('periode_umk', $tahun_umk)
            ->value('salary_bulanan');

    // Hitung selisih tahun/bulan/hari

    /* ===============================
    * PKS AKHIR → untuk perhitungan jumlah bulan
    * =============================== */
    $contractEnd = Carbon::parse($item->contract_end);
    $resignDate  = $item->tanggal_resign ? Carbon::parse($item->tanggal_resign) : null;

    // Tentukan PKS akhir (resign atau kontrak)
    $pksAkhir = $contractEnd;
    if ($resignDate && $resignDate->lt($contractEnd)) {
        $pksAkhir = $resignDate;
    }

    // Hitung selisih tahun/bulan/hari
    $diff = $start->diff($pksAkhir);
    $item->years  = $diff->y;
    $item->months = $diff->m;
    $item->days   = $diff->d;

    /* ===============================
    * Hitung jumlah bulan sesuai kontrak
    * =============================== */
    $jumlah_bulan = $start->diffInMonths($pksAkhir); // bulan penuh
    $sisa_hari    = $start->copy()->addMonths($jumlah_bulan)->diffInDays($pksAkhir);

    if ($jumlah_bulan == 0 && $sisa_hari < 28) {
        // Masa kerja kurang dari 1 bulan
        $jumlah_bulan = 0;
    } elseif ($sisa_hari > 0) {
        // Jika ada sisa hari lebih dari 0 → hitung sebagai 1 bulan tambahan
        $jumlah_bulan += 1;
    }
    // Hitung kompensasi
    $total_penghasilan_bulanan = $umk + $tunjangan;
    $total_kompensasi = ($jumlah_bulan < 1) ? 0 : (($total_penghasilan_bulanan / 12) * $jumlah_bulan);
    $total_kompensasi = (int) ceil($total_kompensasi / 100) * 100;

    // Simpan hasil
    $item->contract_start_fixed      = $start;
    $item->umk                       = $umk;
    $item->tunjangan                 = $tunjangan;
    $item->total_penghasilan_bulanan = $total_penghasilan_bulanan;
    $item->jumlah_bulan              = $jumlah_bulan;
    $item->total_kompensasi          = $total_kompensasi;
    $item->pks_akhir_fixed           = $pksAkhir;
}


        return Excel::download(new exportExcelKompensasiPKWT($data), 'Kompensasi PKWT.xlsx');
    }



    public function print_pdf_kompensasi_pkwt(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $contract=request()->contract;
        $contract_end=request()->contract_end;
        $data = DB::select("
        SELECT
            a.status_staff,
            a.enroll_id,
            a.nik,
            a.employee_name,
            a.status_jabatan,
            a.sub_dept_name,
            a.department_name,
            a.status_kontrak_tetap,
            a.status_aktif,
            a.join_date,
            -- tampilkan tanggal resign hanya jika berada dalam rentang kontrak
            CASE
                WHEN a.tanggal_resign IS NOT NULL
                    AND a.tanggal_resign BETWEEN b.contract AND b.contract_end
                THEN a.tanggal_resign
                ELSE NULL
            END AS tanggal_resign,
            a.nomor_ktp,
            a.tempat_lahir,
            a.alamat_rumah,
            a.tanggal_lahir,
            a.no_surat,
            b.contract,
            b.contract_end,
            c.max_contract,
            c.max_contract_end,
            b.jumlah_bulan,
            c.created_at
                FROM employee_atribut a
                LEFT JOIN employee_contract b ON a.enroll_id = b.enroll_id
                LEFT JOIN (
            SELECT
                ec1.enroll_id,
                ec1.jumlah_bulan,
                ec1.created_at,
                ec1.contract AS max_contract,
                ec1.contract_end AS max_contract_end
            FROM employee_contract ec1
            INNER JOIN (
                SELECT enroll_id, MAX(contract) AS max_contract
                FROM employee_contract
                GROUP BY enroll_id
            ) ec2 ON ec1.enroll_id = ec2.enroll_id AND ec1.contract = ec2.max_contract
        ) c ON a.enroll_id = c.enroll_id
                WHERE
                    a.enroll_id = $enroll_id
                    AND b.contract = '$contract'
                    AND b.contract_end = '$contract_end'
                GROUP BY a.enroll_id
            ");

        // $tahun_umk = date('Y', strtotime($contract_end));
        // $tahun_umk = 'UMK '.$tahun_umk;
        // $umk = DasarPotBPJS::where('kode_dasar_pot_bpjs', $tahun_umk)->first()->dasar_pot_bpjs_rupiah ?? 0;
        $data = $data[0];
        $tanggal_masuk = $data->join_date;
        $tanggal_awal = $data->tanggal_resign ? $data->tanggal_resign : $data->contract_end;
        $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_awal))->y;
        if ($selisih_tahun < 1) {
            $tunjangan = 0;
        } elseif ($selisih_tahun < 3) {
            $tunjangan = 2500;
        } elseif ($selisih_tahun < 6) {
            $tunjangan = 5000;
        }elseif ($selisih_tahun < 9) {
            $tunjangan = 7500;
        }elseif ($selisih_tahun < 12) {
            $tunjangan = 10000;
        }else{
            $tunjangan = 12500;
        }

        // 2 bulan 1 hari → dibulatkan menjadi 3 bulan ✔
        // 1 bulan 15 hari → dibulatkan menjadi 2 bulan ✔
        // 10 hari kerja → tidak mendapatkan kompensasi, karena belum genap 1 bulan ❌

        $endDate = $data->tanggal_resign ? $data->tanggal_resign : $data->contract_end;
        $endDate = Carbon::parse($endDate);

         $batasMulai = Carbon::create(2020, 11, 2);
        $contract = Carbon::parse($data->contract ?? $data->join_date);
         if ($contract->lt($batasMulai)) {
                $contract = $batasMulai;
            }
         $tahun_umk = $endDate->year;

            // KHUSUS:
            // Jika mulai bulan Desember & lintas tahun → pakai tahun akhir
            if ($contract->month == 12 && $contract->year < $endDate->year) {
                $tahun_umk = $endDate->year;
            }

            /* ===============================
            * 2️⃣ Ambil UMK dari master
            * =============================== */
             if ($tahun_umk <= 2020) {
                $tahun_umk = 2021;
            }
            $kode_umk = 'UMK ' . $tahun_umk;
            $umk = GradingSalary::where('kode_grade', 'D')->where('periode_umk', $tahun_umk)->value('salary_bulanan');
            // $umk = DasarPotBPJS::where('kode_dasar_pot_bpjs', $kode_umk)
            //     ->value('dasar_pot_bpjs_rupiah');
        // $endDate = '2025-02-20';
        $contractEnd = Carbon::parse($data->contract_end);
        $resignDate  = $data->tanggal_resign ? Carbon::parse($data->tanggal_resign) : null;

        $pksAkhir = $contractEnd; // default
        if ($resignDate && $resignDate->lt($contractEnd)) {
            $pksAkhir = $resignDate;
        }
        $contract_start = $contract ->format('Y-m-d');
        $contract_end   = $pksAkhir->format('Y-m-d'); // gunakan PKS akhir
        $jumlah_bulan_manual = $this->hitungBulanKontrak($contract_start, $contract_end);

        $diffDays = $contract->diffInDays($pksAkhir); // total hari
        $jumlah_bulan_manual = $diffDays / 30;

        // Jika kurang dari 1 bulan, kompensasi = 0
        if ($jumlah_bulan_manual < 1) {
            $jumlah_bulan = 0;
        } else {
            $jumlah_bulan =  $data->jumlah_bulan ?: floor($jumlah_bulan_manual);
        }



        $total_penghasilan_bulanan = $umk + $tunjangan;
        $data->jumlah_bulan              = $jumlah_bulan;
        $total_kompensasi = $total_penghasilan_bulanan * ($jumlah_bulan / 12);
        $total_kompensasi = ceil($total_kompensasi / 100)*100;

        $total_kompensasi = (int) ceil($total_kompensasi / 100) * 100;
        //  dd($total_kompensasi);
        $fileName='Kompensasi PKWT '.$data->employee_name.'('.request()->enroll_id.') '.$contract_end.' '.date('His');
        $pdf = PDF::loadView('hris.laporan.pdf_kompensasi_pkwt',["no_form"=>$no_form,"contract2"=>$contract,"contract_end2"=>$endDate,"data" => $data,"umk"=>$umk, "tunjangan"=>$tunjangan, "total_kompensasi"=>$total_kompensasi, "jumlah_bulan"=>$jumlah_bulan])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        return $pdf;
    }

    public function hitungBulanKontrak(string $awal, string $akhir): int {
        $start = new DateTime($awal);
        $end = new DateTime($akhir);

        if ($end < $start) return 0;

        // Hitung total hari
        $totalHari = (int)$start->diff($end)->format('%a') + 1; // inklusif
        if ($totalHari < 10) {
            return 0;
        }

        // Hitung bulan dan hari
        $interval = $start->diff($end);
        $bulan = $interval->y * 12 + $interval->m;
        $hari = $interval->d;

        // Konversi semua ke hari untuk logika lebih fleksibel
        $hariPerBulan = 30; // asumsi kasar
        $totalBulanEstimasi = $totalHari / $hariPerBulan;

        // Aturan pembulatan sesuai instruksi
        // if ($totalBulanEstimasi < 1.5) {
        //     return 1;
        // }
        if ($bulan === 0) {
        return 1;
    }

        // // Bulatkan ke atas jika lewat 1 hari dari angka bulat
        // $extra = $totalBulanEstimasi - floor($totalBulanEstimasi);
        // if ($extra > 0.033) { // kira-kira 1 hari
        //     return (int)floor($totalBulanEstimasi) + 1;
        // }

        // return (int)floor($totalBulanEstimasi);
         return $hari >= 15 ? $bulan + 1 : $bulan;
    }



    public function print_all_pdf_kontrak(){
        $enroll_id = request()->enroll_id;
        $no_form = request()->no_form;

     // 1. Ambil data karyawan dan kontrak
       $employee = DB::table('employee_atribut as a')
        ->select(
            'a.status_staff','a.enroll_id','a.nik','a.employee_name','a.status_jabatan',
            'a.sub_dept_name','a.department_name','a.status_kontrak_tetap','a.status_aktif',
            'a.join_date','a.tanggal_resign','a.nomor_ktp','a.tempat_lahir','a.alamat_rumah',
            'a.tanggal_lahir','a.no_surat'
        )
        ->whereIn('a.enroll_id', $enroll_id)
        ->orderBy('a.sub_dept_name','ASC')
        ->get()
        ->keyBy('enroll_id');


        // 2. Ambil absen terakhir per enroll_id
      $contract = DB::table('employee_contract as ec1')
        ->select('ec1.enroll_id', 'ec1.contract', 'ec1.contract_end')
        ->join(DB::raw('(
            SELECT enroll_id, MAX(contract_end) as latest_contract_end
            FROM employee_contract
            GROUP BY enroll_id
        ) as latest'), function($join) {
            $join->on('ec1.enroll_id', '=', 'latest.enroll_id');
            $join->on('ec1.contract_end', '=', 'latest.latest_contract_end');
        })
        ->whereIn('ec1.enroll_id', $enroll_id)
        ->get()
        ->keyBy('enroll_id');
      $absen = DB::table('master_data_absen_kehadiran as m1')
        ->select('m1.enroll_id', 'm1.mulai_jam_kerja', 'm1.akhir_jam_kerja', 'm1.tanggal_berjalan')
        ->join(DB::raw('(SELECT enroll_id, MAX(tanggal_berjalan) as latest_date
                        FROM master_data_absen_kehadiran
                        GROUP BY enroll_id) as m2'), function($join) {
            $join->on('m1.enroll_id', '=', 'm2.enroll_id');
            $join->on('m1.tanggal_berjalan', '=', 'm2.latest_date');
        })
        ->whereIn('m1.enroll_id', $enroll_id)
        ->get()
        ->keyBy('enroll_id');
        $final = $employee->map(function ($item, $key) use ($contract, $absen) {
        $item->contract = $contract[$key]->contract ?? null;
        $item->contract_end = $contract[$key]->contract_end ?? null;

        $item->mulai_jam_kerja = $absen[$key]->mulai_jam_kerja ?? null;
        $item->akhir_jam_kerja = $absen[$key]->akhir_jam_kerja ?? null;
        $item->tanggal_berjalan = $absen[$key]->tanggal_berjalan ?? null;

    return $item;
})->values();




        // Ambil kontrak terakhir per enroll_id
        $latest_per_employee = $final->groupBy('enroll_id')->map(function ($contracts) {
            return $contracts->sortByDesc('contract_end')->first();
        })->values();

        // Gandakan setiap data sebanyak 2x
        $data_final = collect();
        foreach ($latest_per_employee as $item) {
            $data_final->push($item);
            $data_final->push($item); // push dua kali
        }
        foreach ($data_final as $item) {

            if (!$item->contract) {
                $item->umk = 0;
                $item->umk_latin = '';
                continue;
            }

            // ACUAN TAHUN = TANGGAL KONTRAK TERAKHIR
            $start = Carbon::parse($item->contract);
            $end   = Carbon::parse($item->contract_end ?? now());

            $tahun_umk = $start->year;

            // Khusus 2020 ke bawah
            if ($tahun_umk <= 2020) {
                $tahun_umk = 2021;
            }

            $grading = GradingSalary::where('kode_grade', 'D')
                ->where('periode_umk', $tahun_umk)
                ->first();

            $item->umk = $grading?->salary_bulanan ?? 0;
            $item->umk_latin = ucwords(strtolower($grading?->salary_latin ?? ''));
        }


        // UMK dan PDF
        // $umk = DasarPotBPJS::orderBy('created_at','desc')->first()->dasar_pot_bpjs_rupiah;
        $fileName = 'Kontrak Kerja All ' . date('His');

        $pdf = PDF::loadView('hris.laporan.kontrak_kerja_karyawan', [
            "data" => $data_final,
            // "umk" => $umk,
            "no_form" => $no_form
        ])->setPaper('A4', 'portrait')->stream($fileName . '.pdf');

        return $pdf;
    }


    public function ajax_getemployeeidbyfilter(){
        $inSearchVariable='';
        $inNoKTP='';
        $inEnrollId='';
        $inIbuKandung='';
        $inStatusAktif='';
        $statusPenilaian="AND b.status_penilaian IS NULL";
        $inStatusStaff='';
        $inStatusKontrak='';
        if (request("search_variable")) {
            $search_variable=request()->search_variable;
            $inSearchVariable = 'AND (a.enroll_id = "'.$search_variable.'" or a.nik LIKE "'.$search_variable.'%" or a.employee_name LIKE "%'.$search_variable.'%" or a.tempat_lahir LIKE "%'.$search_variable.'%" or a.nomor_tlpn LIKE "'.$search_variable.'%" or a.agama LIKE "'.$search_variable.'%" or a.status_kawin LIKE "'.$search_variable.'%" or a.nomor_kk LIKE "'.$search_variable.'%" or a.pendidikan_terakhir LIKE "'.$search_variable.'%" or a.jurusan_pendidikan LIKE "'.$search_variable.'%" or a.alamat_rumah LIKE "%'.$search_variable.'%" or a.department_name LIKE "%'.$search_variable.'%" or a.sub_dept_name LIKE "%'.$search_variable.'%" or a.status_aktif LIKE "'.$search_variable.'%" or a.ibu_kandung LIKE "%'.$search_variable.'%" or a.nomor_ktp LIKE "'.$search_variable.'%")';
        }
        if(request()->no_ktp){
            $no_ktp_string=request()->no_ktp;
            $inNoKTP='AND a.nomor_ktp LIKE "'.$no_ktp_string.'%"';
        }
        if(request()->enroll_id){
            $enroll_id=request()->enroll_id;
            $enroll_id_string=implode(',', $enroll_id);
            $inEnrollId='AND a.enroll_id in ('.$enroll_id_string.')';
        }
        if(request()->ibu_kandung){
            $ibu_kandung_string=request()->ibu_kandung;
            $inIbuKandung='AND a.ibu_kandung LIKE "%'.$ibu_kandung_string.'%"';
        }
        if(request()->status_staff){
            $status_staff=request()->status_staff;
            $inStatusStaff='AND a.status_staff = "'.$status_staff.'"';
        }
        if(request()->status_penilaian){
            $status_penilaian=request()->status_penilaian;
            $statusPenilaian = "AND b.status_penilaian = '{$status_penilaian}'";
        }
        $today = date('Y-m-d');
        if(request()->status_kontrak){
            $status_kontrak=request()->status_kontrak;
           if($status_kontrak=='One Day'){
                $one_days_later = date('Y-m-d', strtotime('+1 days'));
                $inStatusKontrak='AND c.max_contract_end BETWEEN "'.$today.'" AND "'.$one_days_later.'"';
            }else if($status_kontrak=='Nine Day'){
                $nine_days_later = date('Y-m-d', strtotime('+9 days'));
                $inStatusKontrak='AND c.max_contract_end BETWEEN "'.$today.'" AND "'.$nine_days_later.'"';
            }else if($status_kontrak=='Thirty Day'){
                $thirty_days_later = date('Y-m-d', strtotime('+30 days'));
                $inStatusKontrak = 'AND c.max_contract_end BETWEEN "'.$today.'" AND "'.$thirty_days_later.'"';
            }else if($status_kontrak=='Sixty Day'){
                $thirty_day_more = date('Y-m-d',strtotime('+60 days',strtotime(date("Y-m-d")))) . PHP_EOL;
                $inStatusKontrak='AND c.max_contract_end >= "'.$thirty_day_more.'"';
            }else if($status_kontrak=='Not yet extended'){
                $inStatusKontrak= 'AND a.tanggal_resign IS NULL AND c.max_contract_end <= "'.$today.'"';
            }
        }
        $inDateRangeContract='';
        if(request()->date_range){
            $daterange1 = explode(" s/d ", request()->date_range);
            $tanggalMulai = date('Y-m-d', strtotime($daterange1[0]));
            $tanggalSampai = date('Y-m-d', strtotime($daterange1[1]));
            $inDateRangeContract = 'AND (
                CASE
                    WHEN a.tanggal_resign IS NOT NULL THEN a.tanggal_resign
                    ELSE b.contract_end
                END
            ) >= "'.$tanggalMulai.'"
            AND (
                CASE
                    WHEN a.tanggal_resign IS NOT NULL THEN a.tanggal_resign
                    ELSE b.contract_end
                END
            ) <= "'.$tanggalSampai.'"';
        }

        $inDepartment_name='';
        if(request("department_name")){
            $department=request("department_name");
            $inDepartment_name = ' AND a.department_name = "'.$department.'"';
        }
        // $query= DB::select("select a.enroll_id, a.tanggal_resign, a.department_name from employee_atribut a left join employee_contract b on a.enroll_id=b.enroll_id left join (select enroll_id,max(contract_end) max_contract_end from employee_contract group by enroll_id)c on a.enroll_id=c.enroll_id where a.enroll_id is not null ".$inSearchVariable." ".$inEnrollId." ".$inNoKTP." ".$inIbuKandung." ".$inStatusAktif." ".$inStatusKontrak." ".$inStatusStaff." ".$inDateRangeContract." ".$inDepartment_name." group by a.enroll_id");
        $query = DB::select("
        SELECT
            a.enroll_id,
            a.tanggal_resign,
            a.department_name,
            b.status_penilaian
        FROM employee_atribut a
        LEFT JOIN employee_contract b
            ON a.enroll_id = b.enroll_id
        LEFT JOIN (
            SELECT enroll_id, MAX(contract_end) AS max_contract_end
            FROM employee_contract
            GROUP BY enroll_id
        ) c ON a.enroll_id = c.enroll_id AND b.contract_end = c.max_contract_end
        WHERE a.enroll_id IS NOT NULL
            $inSearchVariable
            $inEnrollId
            $inNoKTP
            $inIbuKandung
            $inStatusAktif
            $inStatusKontrak
            $inStatusStaff
            $inDateRangeContract
            $inDepartment_name
            $statusPenilaian
        GROUP BY a.enroll_id, a.tanggal_resign, a.department_name, b.status_penilaian
    ");


        $enroll_id_array=array_column($query,'enroll_id');
        return $enroll_id_array;
    }

    public function send_to_whatsapp_laporan_pemanggilan(Request $request){
        $bulan = now()->format('n'); // 1–12
        $tahun = now()->format('Y');

        // Array bulan romawi
        $bulanRomawi = [
        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
        5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
        9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];

        $mulai=request()->mulai;
        $selesai=request()->selesai;
        $jumlah_hari_mangkir=request()->jumlah_hari_mangkir;

        // Format nomor form dinamis
        $no_form_url=request()->no_form;
        $no_form = $no_form_url.'/HRD-NAC/EXT/' . $bulanRomawi[$bulan] . '/' . $tahun;

        $enroll_id=request()->enroll_id;
        $reason=request()->reason;
        $fileName="Surat Pemanggilan Karyawan ".request()->enroll_id.'_'.date('His');
        $date_now = Carbon::parse(date('Y-m-d'))->translatedFormat('d F Y');

        $date = date('Y-m-d');

        $today = Carbon::today();
        $maxDaysToCheck = 30; // maksimal cek 30 hari ke belakang
        $startDate = $today->copy()->subDays($maxDaysToCheck)->toDateString();
        $endDate = $today->toDateString();

        // Ambil semua data karyawan aktif dengan status absen (M dan lainnya) dalam rentang tanggal tersebut
        $data = DB::select(DB::raw("
            SELECT mda.tanggal_berjalan, mda.enroll_id, ea.employee_name, ea.sub_dept_name, mda.status_absen, ea.department_name, ea.nik, ea.status_jabatan, ea.alamat_rumah, ea.nomor_tlpn, mda.kode_hari
            FROM master_data_absen_kehadiran mda
            JOIN employee_atribut ea ON mda.enroll_id = ea.enroll_id
            WHERE mda.tanggal_berjalan <= '$endDate'
            AND ea.status_aktif = 'Aktif'
            AND mda.enroll_id = '$enroll_id'
            ORDER BY mda.enroll_id, mda.tanggal_berjalan DESC
        "));

        $absenPerOrang = [];
        foreach ($data as $row) {
            $absenPerOrang[$row->enroll_id][] = [
                'tanggal' => $row->tanggal_berjalan,
                'status' => $row->status_absen,
                'nomor_tlpn' => $row->nomor_tlpn,
                'sub_dept_name' => $row->sub_dept_name,
                'kode_hari' => $row->kode_hari,
                'nama' => $row->employee_name,
                'department_name' => $row->department_name,
                'nik' => $row->nik,
                'status_jabatan' => $row->status_jabatan,
                'alamat_rumah' => $row->alamat_rumah
            ];
        }

        $hasil = [];

        foreach ($absenPerOrang as $enroll_id => $absens) {
            $streak = 0;
            $tanggal_akhir = null;
            $tanggal_mulai = null;
            $nama = $absens[0]['nama'] ?? '-';

            foreach ($absens as $absen) {
                $isWeekend = in_array($absen['kode_hari'], [5, 6]);
                $isMangkir = $absen['status'] === 'M';
                $isTidakHadirLainnya = in_array($absen['status'], ['CG','CM','CN','CT','DL','I','IG','IKS','IM','KA','KM','KR','L','LN','LP','NA','R','S','TL']);
                $isHadir = !$isMangkir && !$isWeekend && !$isTidakHadirLainnya;

                if ($isMangkir) {
                    $streak++;

                    if (!$tanggal_akhir) {
                        $tanggal_akhir = $absen['tanggal'];  // Mangkir pertama ditemukan (tanggal terbaru)
                    }

                    $tanggal_mulai = $absen['tanggal'];      // Mangkir terakhir dalam streak
                } else {
                    // Jika ketemu hadir atau izin lainnya, streak dianggap selesai.
                    break;
                }
            }
            if ($streak >= 1) {
                $kategori = match (true) {
                    $streak >= 6 => 'RESIGNED',
                    $streak >= 3 => 'II',
                    default => 'I',
                };

                $hasil[] = [
                    'enroll_id' => $enroll_id,
                    'employee_name' => $nama,
                    'jumlah_hari_mangkir' => $streak,
                    'mulai' => Carbon::parse($tanggal_mulai)->translatedFormat('d F Y'),
                    'selesai' => Carbon::parse($tanggal_akhir)->translatedFormat('d F Y'),
                    'kategori' => $kategori,
                    'department_name' => $absens[0]['department_name'] ?? '-',
                    'nik' => $absens[0]['nik'] ?? '-',
                    'status_jabatan' => $absens[0]['status_jabatan'] ?? '-',
                    'alamat_rumah' => $absens[0]['alamat_rumah'] ?? '-',
                ];
            }
        }
         if($jumlah_hari_mangkir){
            if($jumlah_hari_mangkir >= 1 && $jumlah_hari_mangkir <= 3){
                $hasil[0]['kategori'] = 'I';
            }else if($jumlah_hari_mangkir >= 4 && $jumlah_hari_mangkir <= 5){
                $hasil[0]['kategori'] = 'II';
            }else if($jumlah_hari_mangkir >= 6){
                $hasil[0]['kategori'] = 'RESIGNED';
            }
        }

        $pdfContent = PDF::loadView('hris.sp_kehadiran_karyawan', [
                "data" => $hasil[0],
                "no_form" => $no_form,
                "from"=>$mulai,
                "to"=>$selesai,
                "jumlah_hari_mangkir"=>$jumlah_hari_mangkir
            ])
            ->setPaper('letter', 'portrait')
            ->output(); // <-- Output isi file (bukan stream)

        $fileName = $fileName . '.pdf';

        // Simpan sementara di storage Laravel
        Storage::put('public/' . $fileName, $pdfContent);
        $caption = "*Kepada Yth. Sdr/i.*\n\n".
           "Nama : *{$absenPerOrang[$enroll_id][0]['nama']}*\n".
           "NIP : *{$absenPerOrang[$enroll_id][0]['nik']}*\n".
           "Jabatan : *{$absenPerOrang[$enroll_id][0]['status_jabatan']}*\n".
           "Bagian : *{$absenPerOrang[$enroll_id][0]['sub_dept_name']}*\n".
           "Department : *{$absenPerOrang[$enroll_id][0]['department_name']}*\n\n".
           "Dengan hormat,\n\n".
           "Sehubungan dengan hasil pemantauan absensi dan evaluasi internal perusahaan, kami mencatat adanya ketidakhadiran Saudara/i dalam beberapa waktu terakhir tanpa keterangan yang dapat kami verifikasi secara resmi.\n\n".
           "Oleh karena itu, melalui surat ini kami mengundang Saudara/i untuk hadir dalam rangka klarifikasi atas ketidakhadiran tersebut. Surat panggilan resmi telah kami lampirkan sebagai bagian dari prosedur penanganan ketidakhadiran yang berlaku di perusahaan.\n\n".
           "Dimohon kepada Saudara/i untuk membaca dan mematuhi isi surat panggilan tersebut dengan penuh tanggung jawab. Kehadiran dan penjelasan Saudara/i sangat penting sebagai bagian dari proses klarifikasi dan penegakan kedisiplinan kerja.\n\n".
           "Atas perhatian dan kerja samanya, kami sampaikan terima kasih.\n\n".
           "Hormat kami,\n".
           "PT. Nirwana Alabare Garment\n".
           "HR & GA Department";

        $nomor_tlpn = $absenPerOrang[$enroll_id][0]['nomor_tlpn'] ?? null;

        if ($nomor_tlpn) {
            $nomor_tlpn = preg_replace('/^0/', '62', $nomor_tlpn);
            $nomor_whatsapp = $nomor_tlpn;
        } else {
            $nomor_whatsapp = null;
        }
        // Kirim ke API WhatsApp
        $response = Http::withHeaders([
                    'Authorization' => 'Bearer SECRET_API_KEY_123',
                ])->attach(
                'file',
                Storage::get('public/' . $fileName),
                $fileName
            )->post('http://10.10.5.111:3000/send-document', [
                'nomor' => $nomor_tlpn, // Ganti sesuai kebutuhan
                'caption' => $caption
            ]);

        // Hapus file setelah dikirim (opsional)
        Storage::delete('public/' . $fileName);

        // Kembalikan respon dari API
        return $response->json();
        // return response()->json([
        //     'status' => false,
        //     'message' => 'Sedang dalam perbaikan.',
        //     'error'   => 'Sedang dalam perbaikan.'
        // ]);

    }
    public function update_kontrak_kerja(){                  // update kontrak kerja
        DB::insert("insert into employee_contract(enroll_id,contract,contract_end)
            SELECT
                ea.enroll_id,
                ea.tanggal_mulai_kontrak AS contract,
                ea.tanggal_akhir_kontrak AS contract_end
            FROM
                employee_atribut ea
            LEFT JOIN
                employee_contract ec
                ON ea.enroll_id = ec.enroll_id
            WHERE
                ea.status_aktif = 'AKTIF'
                AND ea.enroll_id != 2
                AND ec.enroll_id IS NULL
            ");
            return response()->json([
                'status' => true,
                'message' => 'kontrak berhasil diupdate',
            ]);
    }
}
