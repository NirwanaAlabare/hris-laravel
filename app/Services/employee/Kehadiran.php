<?php

namespace App\Services\employee;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
Use App\Models\HR_GA\AuditBuyer\Apar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\EmployeeAtribut;
use Illuminate\Support\Facades\Auth;




class Kehadiran{

    public function new_employee($enroll_id)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $loggedAdmin->email;


        // $enroll_id=8888;
        $employee=EmployeeAtribut::where('enroll_id', $enroll_id)->first();
        $mulai_jam_kerja='';
        $akhir_jam_kerja='';
        $mulai='';
        $akhir='';
        $join_date=$employee->join_date;
        $tgl=date('d',strtotime( $join_date));
        $bln_sekarang=date('M-Y',strtotime( $join_date));
        $date = strtotime($bln_sekarang);
        $bulan_sebelum= strtotime("-1 month", $date);
        $bulan_sesudah= strtotime("+1 month", $date);
        $bulan_sebelum=date('M-Y', $bulan_sebelum);
        $bulan_sesudah=date('M-Y', $bulan_sesudah);

        if($tgl>=26){
            $tanggal_akhir=date('Y-m-d', strtotime('25-'.$bulan_sesudah));
        }
        elseif($tgl<26){
            $tanggal_akhir=date('Y-m-d', strtotime('25-'.$bln_sekarang));
        }
        $tgl_berjalan=$join_date;
        while (strtotime( $tgl_berjalan) <= strtotime($tanggal_akhir)) {
            $hari=date('D',strtotime(  $tgl_berjalan));
            $staffnonstaff=$employee->status_staff;
            if($hari=='Sun'){
                $kode_hari='6';
                $nama_hari='Minggu';
                $status_absen=null;
                $mulai_jam_kerja=null;
                $akhir_jam_kerja=null;
            }else if($hari=='Mon'){
                $kode_hari='0';
                $nama_hari='Senin';
                $status_absen='M';
                if($staffnonstaff=='STAFF'){
                    $mulai_jam_kerja='07:30:00';
                    $akhir_jam_kerja='17:30:00';
                }else if($staffnonstaff=='NON STAFF'){
                    $mulai_jam_kerja='07:00:00';
                    $akhir_jam_kerja='16:00:00';
                }else{
                    $mulai_jam_kerja=null;
                    $akhir_jam_kerja=null;
                }
            }else if($hari=='Tue'){
                $kode_hari='1';
                $nama_hari='Selasa';
                $status_absen='M';
                if($staffnonstaff=='STAFF'){
                    $mulai_jam_kerja='07:30:00';
                    $akhir_jam_kerja='17:30:00';
                }else if($staffnonstaff=='NON STAFF'){
                    $mulai_jam_kerja='07:00:00';
                    $akhir_jam_kerja='16:00:00';
                }else{
                    $mulai_jam_kerja=null;
                    $akhir_jam_kerja=null;
                }
            }else if($hari=='Wed'){
                $kode_hari='2';
                $nama_hari='Rabu';
                $status_absen='M';
                if($staffnonstaff=='STAFF'){
                    $mulai_jam_kerja='07:30:00';
                    $akhir_jam_kerja='17:30:00';
                }else if($staffnonstaff=='NON STAFF'){
                    $mulai_jam_kerja='07:00:00';
                    $akhir_jam_kerja='16:00:00';
                }else{
                    $mulai_jam_kerja=null;
                    $akhir_jam_kerja=null;
                }
            }else if($hari=='Thu'){
                $kode_hari='3';
                $nama_hari='Kamis';
                $status_absen='M';
                if($staffnonstaff=='STAFF'){
                    $mulai_jam_kerja='07:30:00';
                    $akhir_jam_kerja='17:30:00';
                }else if($staffnonstaff=='NON STAFF'){
                    $mulai_jam_kerja='07:00:00';
                    $akhir_jam_kerja='16:00:00';
                }else{
                    $mulai_jam_kerja=null;
                    $akhir_jam_kerja=null;
                }
            }else if($hari=='Fri'){
                $kode_hari='4';
                $nama_hari='Jumat';
                $status_absen='M';
                if($staffnonstaff=='STAFF'){
                    $mulai_jam_kerja='07:30:00';
                    $akhir_jam_kerja='17:30:00';
                }else if($staffnonstaff=='NON STAFF'){
                    $mulai_jam_kerja='07:00:00';
                    $akhir_jam_kerja='16:00:00';
                }else{
                    $mulai_jam_kerja=null;
                    $akhir_jam_kerja=null;
                }
            }else if($hari=='Sat'){
                $kode_hari='5';
                $nama_hari='Sabtu';
                $status_absen=null;
                $mulai_jam_kerja=null;
                $akhir_jam_kerja=null;

            }


            $x=[
                'uuid' => Str::uuid('uuid'),
                'tanggal_berjalan' => $tgl_berjalan,
                'kode_hari' =>$kode_hari,
                'nama_hari' => $nama_hari,
                'mulai_jam_kerja' => $mulai_jam_kerja,
                'akhir_jam_kerja' => $akhir_jam_kerja,
                'employee_id' => $employee->employee_id,
                'employee_name' => $employee->employee_name,
                'enroll_id' => $employee->enroll_id,
                'join_date' => $employee->join_date,
                'tanggal_resign' => $employee->tanggal_resign,
                'work_status' => $employee->work_status,
                'status_aktif' => $employee->status_aktif,
                'status_kontrak_tetap' => $employee->status_kontrak_tetap,
                'employee_status' => $employee->employee_status,
                'status_jabatan' => $employee->status_jabatan,
                'posisi_name' => $employee->posisi_name,
                'status_staff' => $employee->status_staff,
                'nik' => $employee->nik,
                'site_nirwana_id' => $employee->site_nirwana_id,
                'site_nirwana_name' => $employee->site_nirwana_name,
                'department_id' => $employee->department_id,
                'department_name' => $employee->department_name,
                'sub_dept_id' => $employee->sub_dept_id,
                'sub_dept_name' => $employee->sub_dept_name,
                'status_absen' => $status_absen,
                'operator' =>$loggedAdmin->email,
            ];
            MasterDataAbsenKehadiran::create($x);
             $tgl_berjalan = date ("Y-m-d", strtotime("+1 day", strtotime($tgl_berjalan)));
        }
        $employee2=EmployeeAtribut::where('enroll_id',$enroll_id)->get();
        foreach ($employee2 as $key => $value2) {
            $tgl_berjalan2=$value2->join_date;
            while (strtotime( $tgl_berjalan2) <= strtotime($tanggal_akhir)) {
                $x2=[
                    'status_aktif' => $value2->status_aktif,
                    'status_kontrak_tetap' => $value2->status_kontrak_tetap,
                    'employee_status' => $value2->status_kontrak_tetap,
                    'status_jabatan' => $value2->status_jabatan,
                    'posisi_name' => $value2->posisi_name,
                    'status_staff' => $value2->status_staff,
                ];
                MasterDataAbsenKehadiran::where('tanggal_berjalan',$tgl_berjalan2)->where('enroll_id',$value2->enroll_id)->update($x2);
                $tgl_berjalan2 = date ("Y-m-d", strtotime("+1 day", strtotime($tgl_berjalan2)));

            }
        }

    }

    public function creat_master_absen_26()
    {
        $employee=EmployeeAtribut::where('status_aktif', 'AKTIF')->where('join_date','2023-10-31')->get();
        // dd($employee);
        $mulai_jam_kerja='';
        $akhir_jam_kerja='';
        foreach ($employee as $key => $value) {
            if($value->status_staff=='STAFF'){
                $mulai_jam_kerja='07:30:00';
                $akhir_jam_kerja='17:30:00';
            }else if($value->status_staff=='NON STAFF'){
                $mulai_jam_kerja='07:00:00';
                $akhir_jam_kerja='16:00:00';
            }else{
                $mulai_jam_kerja=null;
                $akhir_jam_kerja=null;
            }
            $tanggal_akhir='2023-11-25';
            $tgl_berjalan='2023-10-31';
            while (strtotime( $tgl_berjalan) <= strtotime($tanggal_akhir)) {
                $hari=date('D',strtotime(  $tgl_berjalan));
                if($hari=='Sun'){
                    $kode_hari='6';
                    $nama_hari='Minggu';
                    $status_absen=null;
                }else if($hari=='Mon'){
                    $kode_hari='0';
                    $nama_hari='Senin';
                    $status_absen='M';
                }else if($hari=='Tue'){
                    $kode_hari='1';
                    $nama_hari='Selasa';
                    $status_absen='M';
                }else if($hari=='Wed'){
                    $kode_hari='2';
                    $nama_hari='Rabu';
                    $status_absen='M';
                }else if($hari=='Thu'){
                    $kode_hari='3';
                    $nama_hari='Kamis';
                    $status_absen='M';
                }else if($hari=='Fri'){
                    $kode_hari='4';
                    $nama_hari='Jumat';
                    $status_absen='M';
                }else if($hari=='Sat'){
                    $kode_hari='5';
                    $nama_hari='Sabtu';
                    $status_absen=null;

                }
                $x=[
                    'uuid' => Str::uuid('uuid'),
                    'tanggal_berjalan' => $tgl_berjalan,
                    'kode_hari' =>$kode_hari,
                    'nama_hari' => $nama_hari,
                    'mulai_jam_kerja' => $mulai_jam_kerja,
                    'akhir_jam_kerja' => $akhir_jam_kerja,
                    'employee_id' => $value->employee_id,
                    'employee_name' => $value->employee_name,
                    'enroll_id' => $value->enroll_id,
                    'join_date' => $value->join_date,
                    'tanggal_resign' => $value->tanggal_resign,
                    'work_status' => $value->work_status,
                    'status_aktif' => $value->status_aktif,
                    'status_kontrak_tetap' => $value->status_kontrak_tetap,
                    'employee_status' => $value->employee_status,
                    'status_jabatan' => $value->status_jabatan,
                    'posisi_name' => $value->posisi_name,
                    'status_staff' => $value->status_staff,
                    'nik' => $value->nik,
                    'site_nirwana_id' => $value->site_nirwana_id,
                    'site_nirwana_name' => $value->site_nirwana_name,
                    'department_id' => $value->department_id,
                    'department_name' => $value->department_name,
                    'sub_dept_id' => $value->sub_dept_id,
                    'sub_dept_name' => $value->sub_dept_name,
                    'status_absen' => $status_absen,
                    'operator' =>'system',
                ];
                MasterDataAbsenKehadiran::create($x);
                $tgl_berjalan = date ("Y-m-d", strtotime("+1 day", strtotime($tgl_berjalan)));

            }
        }
    }
    // public function index()
    // {
    //     $employee=EmployeeAtribut::where('status_aktif', 'AKTIF')->wherein(,['4955',
        // '4956',
        // '4957',
        // '4958',
        // '4959',
        // '4960',
        // '4961',
        // '4962',
        // '4963',
        // '9991',
        // '9993',
        // '9995',
        // '9996',])->get();
    //     // dd($employee);

    //     foreach ($employee as $key => $value) {
    //         $tanggal_akhir='2023-05-25';
    //         $tgl_berjalan='2023-04-26';
    //         while (strtotime( $tgl_berjalan) <= strtotime($tanggal_akhir)) {
    //             $hari=date('D',strtotime(  $tgl_berjalan));
    //             if($hari=='Sun'){
    //                 $kode_hari='6';
    //                 $nama_hari='Minggu';
    //                 $status_absen=null;
    //             }else if($hari=='Mon'){
    //                 $kode_hari='0';
    //                 $nama_hari='Senin';
    //                 $status_absen='M';
    //             }else if($hari=='Tue'){
    //                 $kode_hari='1';
    //                 $nama_hari='Selasa';
    //                 $status_absen='M';
    //             }else if($hari=='Wed'){
    //                 $kode_hari='2';
    //                 $nama_hari='Rabu';
    //                 $status_absen='M';
    //             }else if($hari=='Thu'){
    //                 $kode_hari='3';
    //                 $nama_hari='Kamis';
    //                 $status_absen='M';
    //             }else if($hari=='Fri'){
    //                 $kode_hari='4';
    //                 $nama_hari='Jumat';
    //                 $status_absen='M';
    //             }else if($hari=='Sat'){
    //                 $kode_hari='5';
    //                 $nama_hari='Sabtu';
    //                 $status_absen=null;

    //             }
    //             $x=[
    //                 // 'uuid' => Str::uuid('uuid'),
    //                 // 'tanggal_berjalan' => $tgl_berjalan,
    //                 // 'kode_hari' =>$kode_hari,
    //                 // 'nama_hari' => $nama_hari,
    //                 // 'employee_id' => $value->employee_id,
    //                 // 'employee_name' => $value->employee_name,
    //                 // 'enroll_id' => $value->enroll_id,
    //                 // 'join_date' => $value->join_date,
    //                 // 'tanggal_resign' => $value->tanggal_resign,
    //                 // 'work_status' => $value->status_aktif,
    //                 'status_aktif' => $value->status_aktif,
    //                 'status_kontrak_tetap' => $value->status_kontrak_tetap,
    //                 // 'employee_status' => $value->status_kontrak_tetap,
    //                 'status_jabatan' => $value->status_jabatan,
    //                 // 'posisi_name' => $value->posisi_name,
    //                 'status_staff' => $value->status_staff,
    //                 // 'nik' => $value->nik,
    //                 // 'site_nirwana_id' => $value->site_nirwana_id,
    //                 // 'site_nirwana_name' => $value->site_nirwana_name,
    //                 // 'department_id' => $value->department_id,
    //                 // 'department_name' => $value->department_name,
    //                 // 'sub_dept_id' => $value->sub_dept_id,
    //                 // 'sub_dept_name' => $value->sub_dept_name,
    //                 // 'status_absen' => $status_absen,
    //                 // 'operator' =>'system',
    //             ];
    //             MasterDataAbsenKehadiran::where('tanggal_berjalan',$tgl_berjalan)->where('enroll_id',$value->enroll_id)->update($x);
    //             // MasterDataAbsenKehadiran::create($x);
    //             $tgl_berjalan = date ("Y-m-d", strtotime("+1 day", strtotime($tgl_berjalan)));

    //         }
    //     }
    //     dd($x);

    // }

    // public function index()
    // {
    //     $employee=EmployeeAtribut::where('status_aktif', 'AKTIF')->get();
    //     // dd($employee);

    //     foreach ($employee as $key => $value) {
    //         $tanggal_akhir='2023-05-25';
    //         $tgl_berjalan='2023-04-26';
    //         while (strtotime( $tgl_berjalan) <= strtotime($tanggal_akhir)) {
    //             $hari=date('D',strtotime(  $tgl_berjalan));
    //             if($hari=='Sun'){
    //                 $kode_hari='6';
    //                 $nama_hari='Minggu';
    //                 $status_absen=null;
    //             }else if($hari=='Mon'){
    //                 $kode_hari='0';
    //                 $nama_hari='Senin';
    //                 $status_absen='M';
    //             }else if($hari=='Tue'){
    //                 $kode_hari='1';
    //                 $nama_hari='Selasa';
    //                 $status_absen='M';
    //             }else if($hari=='Wed'){
    //                 $kode_hari='2';
    //                 $nama_hari='Rabu';
    //                 $status_absen='M';
    //             }else if($hari=='Thu'){
    //                 $kode_hari='3';
    //                 $nama_hari='Kamis';
    //                 $status_absen='M';
    //             }else if($hari=='Fri'){
    //                 $kode_hari='4';
    //                 $nama_hari='Jumat';
    //                 $status_absen='M';
    //             }else if($hari=='Sat'){
    //                 $kode_hari='5';
    //                 $nama_hari='Sabtu';
    //                 $status_absen=null;

    //             }
    //             $jadwal=MasterDataAbsenKehadiran::where('tanggal_berjalan','2023-04-25')->where('enroll_id',$value->enroll_id)->first();
    //             // dd($jadwal->mulai_jam_kerja??null);
    //             // dd($jadwal->akhir_jam_kerja??null);

    //             $x=[
    //                 // 'uuid' => Str::uuid('uuid'),
    //                 // 'tanggal_berjalan' => $tgl_berjalan,
    //                 // 'kode_hari' =>$kode_hari,
    //                 // 'nama_hari' => $nama_hari,
    //                 // 'employee_id' => $value->employee_id,
    //                 // 'employee_name' => $value->employee_name,
    //                 // 'enroll_id' => $value->enroll_id,
    //                 // 'join_date' => $value->join_date,
    //                 // 'tanggal_resign' => $value->tanggal_resign,
    //                 // 'work_status' => $value->status_aktif,
    //                 // 'status_aktif' => $value->status_aktif,
    //                 // 'status_kontrak_tetap' => $value->status_kontrak_tetap,
    //                 // 'employee_status' => $value->status_kontrak_tetap,
    //                 // 'status_jabatan' => $value->status_jabatan,
    //                 // 'posisi_name' => $value->posisi_name,
    //                 // 'status_staff' => $value->status_staff,
    //                 // 'nik' => $value->nik,
    //                 // 'site_nirwana_id' => $value->site_nirwana_id,
    //                 // 'site_nirwana_name' => $value->site_nirwana_name,
    //                 // 'department_id' => $value->department_id,
    //                 // 'department_name' => $value->department_name,
    //                 // 'sub_dept_id' => $value->sub_dept_id,
    //                 // 'sub_dept_name' => $value->sub_dept_name,
    //                 // 'status_absen' => $status_absen,
    //                 // 'operator' =>'system',
    //                 'mulai_jam_kerja'=>$jadwal->mulai_jam_kerja??null,
    //                 'akhir_jam_kerja'=>$jadwal->akhir_jam_kerja??null,
    //             ];
    //             MasterDataAbsenKehadiran::where('tanggal_berjalan',$tgl_berjalan)->where('enroll_id',$value->enroll_id)->update($x);
    //             // MasterDataAbsenKehadiran::create($x);
    //             $tgl_berjalan = date ("Y-m-d", strtotime("+1 day", strtotime($tgl_berjalan)));

    //         }
    //     }
    //     dd('ok');

    // }

    // public function index()
    // {
    //     $employee=EmployeeAtribut::where('status_aktif', 'AKTIF')->get();
    //     // dd($employee);

    //     foreach ($employee as $key => $value) {
    //         $tanggal_akhir='2023-05-25';
    //         $tgl_berjalan='2023-04-26';
    //         while (strtotime( $tgl_berjalan) <= strtotime($tanggal_akhir)) {
    //             $hari=date('D',strtotime(  $tgl_berjalan));
    //             if($hari=='Sun'){
    //                 $kode_hari='6';
    //                 $nama_hari='Minggu';
    //                 $status_absen=null;
    //             }else if($hari=='Mon'){
    //                 $kode_hari='0';
    //                 $nama_hari='Senin';
    //                 $status_absen='M';
    //             }else if($hari=='Tue'){
    //                 $kode_hari='1';
    //                 $nama_hari='Selasa';
    //                 $status_absen='M';
    //             }else if($hari=='Wed'){
    //                 $kode_hari='2';
    //                 $nama_hari='Rabu';
    //                 $status_absen='M';
    //             }else if($hari=='Thu'){
    //                 $kode_hari='3';
    //                 $nama_hari='Kamis';
    //                 $status_absen='M';
    //             }else if($hari=='Fri'){
    //                 $kode_hari='4';
    //                 $nama_hari='Jumat';
    //                 $status_absen='M';
    //             }else if($hari=='Sat'){
    //                 $kode_hari='5';
    //                 $nama_hari='Sabtu';
    //                 $status_absen=null;

    //             }
    //             $jadwal=MasterDataAbsenKehadiran::where('tanggal_berjalan','2023-04-25')->where('enroll_id',$value->enroll_id)->first();
    //             // dd($jadwal->mulai_jam_kerja??null);
    //             // dd($jadwal->akhir_jam_kerja??null);
    //             if($kode_hari==6 ||$kode_hari==5){

    //             $x=[
    //                 // 'uuid' => Str::uuid('uuid'),
    //                 // 'tanggal_berjalan' => $tgl_berjalan,
    //                 // 'kode_hari' =>$kode_hari,
    //                 // 'nama_hari' => $nama_hari,
    //                 // 'employee_id' => $value->employee_id,
    //                 // 'employee_name' => $value->employee_name,
    //                 // 'enroll_id' => $value->enroll_id,
    //                 // 'join_date' => $value->join_date,
    //                 // 'tanggal_resign' => $value->tanggal_resign,
    //                 // 'work_status' => $value->status_aktif,
    //                 // 'status_aktif' => $value->status_aktif,
    //                 // 'status_kontrak_tetap' => $value->status_kontrak_tetap,
    //                 // 'employee_status' => $value->status_kontrak_tetap,
    //                 // 'status_jabatan' => $value->status_jabatan,
    //                 // 'posisi_name' => $value->posisi_name,
    //                 // 'status_staff' => $value->status_staff,
    //                 // 'nik' => $value->nik,
    //                 // 'site_nirwana_id' => $value->site_nirwana_id,
    //                 // 'site_nirwana_name' => $value->site_nirwana_name,
    //                 // 'department_id' => $value->department_id,
    //                 // 'department_name' => $value->department_name,
    //                 // 'sub_dept_id' => $value->sub_dept_id,
    //                 // 'sub_dept_name' => $value->sub_dept_name,
    //                 // 'status_absen' => $status_absen,
    //                 // 'operator' =>'system',
    //                 'mulai_jam_kerja'=>null,
    //                 'akhir_jam_kerja'=>null,
    //             ];
    //             MasterDataAbsenKehadiran::where('tanggal_berjalan',$tgl_berjalan)->where('enroll_id',$value->enroll_id)->update($x);
    //             // MasterDataAbsenKehadiran::create($x);

    //             }
    //             $tgl_berjalan = date ("Y-m-d", strtotime("+1 day", strtotime($tgl_berjalan)));


    //         }
    //     }
    //     dd('ok');

    // }

    // public function index()
    // {
    //     $employee=EmployeeAtribut::where('status_aktif', 'AKTIF')->get();
    //     // dd($employee);

    //     foreach ($employee as $key => $value) {
    //         $tanggal_akhir='2023-05-25';
    //         $tgl_berjalan='2023-04-26';
    //         while (strtotime( $tgl_berjalan) <= strtotime($tanggal_akhir)) {
    //             $hari=date('D',strtotime(  $tgl_berjalan));
    //             if($hari=='Sun'){
    //                 $kode_hari='6';
    //                 $nama_hari='Minggu';
    //                 $status_absen=null;
    //             }else if($hari=='Mon'){
    //                 $kode_hari='0';
    //                 $nama_hari='Senin';
    //                 $status_absen='M';
    //             }else if($hari=='Tue'){
    //                 $kode_hari='1';
    //                 $nama_hari='Selasa';
    //                 $status_absen='M';
    //             }else if($hari=='Wed'){
    //                 $kode_hari='2';
    //                 $nama_hari='Rabu';
    //                 $status_absen='M';
    //             }else if($hari=='Thu'){
    //                 $kode_hari='3';
    //                 $nama_hari='Kamis';
    //                 $status_absen='M';
    //             }else if($hari=='Fri'){
    //                 $kode_hari='4';
    //                 $nama_hari='Jumat';
    //                 $status_absen='M';
    //             }else if($hari=='Sat'){
    //                 $kode_hari='5';
    //                 $nama_hari='Sabtu';
    //                 $status_absen=null;

    //             }
    //             // $jadwal=MasterDataAbsenKehadiran::where('tanggal_berjalan','2023-04-25')->where('enroll_id',$value->enroll_id)->first();
    //             // dd($jadwal->mulai_jam_kerja??null);
    //             // dd($jadwal->akhir_jam_kerja??null);

    //             $ijin=DataAbsenPerijinan::where('tanggal_akhir_ijin','>=',$tgl_berjalan)->where('enroll_id',$value->enroll_id)->wherein('kode_absen_ijin',['CM','CG'])->first();
    //             $cek_ijin=$ijin->nomor_form_perizinan??null;
    //             if($kode_hari!=6 && $kode_hari!=5 && $cek_ijin!=null){

    //             $x=[
    //                 'nomor_absen_ijin' => $ijin->nomor_form_perizinan,
    //                 'status_absen' => $ijin->kode_absen_ijin,
    //                 'absen_alasan' => $ijin->absen_alasan,
    //                 'tanggal_mulai_ijin' => $ijin->tanggal_mulai_ijin,
    //                 'tanggal_akhir_ijin' => $ijin->tanggal_akhir_ijin,
    //                 'operator' => $ijin->operator,
    //                 'updated_absen_ijin' => now(),
    //                 // 'uuid' => Str::uuid('uuid'),
    //                 'tanggal_berjalan' => $tgl_berjalan,
    //                 // 'kode_hari' =>$kode_hari,
    //                 // 'nama_hari' => $nama_hari,
    //                 // 'employee_id' => $value->employee_id,
    //                 // 'employee_name' => $value->employee_name,
    //                 'enroll_id' => $value->enroll_id,
    //                 // 'join_date' => $value->join_date,
    //                 // 'tanggal_resign' => $value->tanggal_resign,
    //                 // 'work_status' => $value->status_aktif,
    //                 // 'status_aktif' => $value->status_aktif,
    //                 // 'status_kontrak_tetap' => $value->status_kontrak_tetap,
    //                 // 'employee_status' => $value->status_kontrak_tetap,
    //                 // 'status_jabatan' => $value->status_jabatan,
    //                 // 'posisi_name' => $value->posisi_name,
    //                 // 'status_staff' => $value->status_staff,
    //                 // 'nik' => $value->nik,
    //                 // 'site_nirwana_id' => $value->site_nirwana_id,
    //                 // 'site_nirwana_name' => $value->site_nirwana_name,
    //                 // 'department_id' => $value->department_id,
    //                 // 'department_name' => $value->department_name,
    //                 // 'sub_dept_id' => $value->sub_dept_id,
    //                 // 'sub_dept_name' => $value->sub_dept_name,
    //                 // 'status_absen' => $status_absen,
    //                 // 'operator' =>'system',
    //                 // 'mulai_jam_kerja'=>null,
    //                 // 'akhir_jam_kerja'=>null,
    //             ];
    //             MasterDataAbsenKehadiran::where('tanggal_berjalan',$tgl_berjalan)->where('enroll_id',$value->enroll_id)->update($x);
    //             // MasterDataAbsenKehadiran::create($x);

    //             }
    //             $tgl_berjalan = date ("Y-m-d", strtotime("+1 day", strtotime($tgl_berjalan)));


    //         }
    //     }
    //     dd('ok');

    // }

    // public function index()
    // {
    //     $employee=EmployeeAtribut::where('status_aktif', 'AKTIF')->where('join_date','>','2023-05-08')->get();

    //     foreach ($employee as $key => $value) {
    //         $tanggal_akhir='2023-05-25';
    //         $tgl_berjalan=$value->join_date;
    //         while (strtotime( $tgl_berjalan) <= strtotime($tanggal_akhir)) {
    //             $hari=date('D',strtotime(  $tgl_berjalan));
    //             if($hari=='Sun'){
    //                 $kode_hari='6';
    //                 $nama_hari='Minggu';
    //                 $status_absen=null;
    //             }else if($hari=='Mon'){
    //                 $kode_hari='0';
    //                 $nama_hari='Senin';
    //                 $status_absen='M';
    //             }else if($hari=='Tue'){
    //                 $kode_hari='1';
    //                 $nama_hari='Selasa';
    //                 $status_absen='M';
    //             }else if($hari=='Wed'){
    //                 $kode_hari='2';
    //                 $nama_hari='Rabu';
    //                 $status_absen='M';
    //             }else if($hari=='Thu'){
    //                 $kode_hari='3';
    //                 $nama_hari='Kamis';
    //                 $status_absen='M';
    //             }else if($hari=='Fri'){
    //                 $kode_hari='4';
    //                 $nama_hari='Jumat';
    //                 $status_absen='M';
    //             }else if($hari=='Sat'){
    //                 $kode_hari='5';
    //                 $nama_hari='Sabtu';
    //                 $status_absen=null;

    //             }
    //             $x=[
    //                 'uuid' => Str::uuid('uuid'),
    //                 'tanggal_berjalan' => $tgl_berjalan,
    //                 'kode_hari' =>$kode_hari,
    //                 'nama_hari' => $nama_hari,
    //                 'employee_id' => $value->employee_id,
    //                 'employee_name' => $value->employee_name,
    //                 'enroll_id' => $value->enroll_id,
    //                 'join_date' => $value->join_date,
    //                 'tanggal_resign' => $value->tanggal_resign,
    //                 'work_status' => $value->work_status,
    //                 'status_aktif' => $value->status_aktif,
    //                 'status_kontrak_tetap' => $value->status_kontrak_tetap,
    //                 'employee_status' => $value->employee_status,
    //                 'status_jabatan' => $value->status_jabatan,
    //                 'posisi_name' => $value->posisi_name,
    //                 'status_staff' => $value->status_staff,
    //                 'nik' => $value->nik,
    //                 'site_nirwana_id' => $value->site_nirwana_id,
    //                 'site_nirwana_name' => $value->site_nirwana_name,
    //                 'department_id' => $value->department_id,
    //                 'department_name' => $value->department_name,
    //                 'sub_dept_id' => $value->sub_dept_id,
    //                 'sub_dept_name' => $value->sub_dept_name,
    //                 'status_absen' => $status_absen,
    //                 'operator' =>'system',
    //             ];
    //             // dd($x);
    //             MasterDataAbsenKehadiran::create($x);
    //             $tgl_berjalan = date ("Y-m-d", strtotime("+1 day", strtotime($tgl_berjalan)));

    //         }
    //     }
    // }

    // public function index1()
    // {
    //     $employee=EmployeeAtribut::wherein('enroll_id',[
    //         '752',
    //         '2693',
    //         '4310',])->get();

    //     foreach ($employee as $key => $value) {
    //         $tanggal_akhir='2023-06-25';
    //         $tgl_berjalan='2023-05-26';
    //         while (strtotime( $tgl_berjalan) <= strtotime($tanggal_akhir)) {
    //             $hari=date('D',strtotime(  $tgl_berjalan));
    //             if($hari=='Sun'){
    //                 $kode_hari='6';
    //                 $nama_hari='Minggu';
    //                 $status_absen=null;
    //                 $jadwal_mulai_jam_kerja=null;
    //                 $jadwal_akhir_jam_kerja=null;
    //             }else if($hari=='Mon'){
    //                 $kode_hari='0';
    //                 $nama_hari='Senin';
    //                 $status_absen='M';
    //                 $jadwal_mulai_jam_kerja='07:30';
    //                 $jadwal_akhir_jam_kerja='16:30';
    //             }else if($hari=='Tue'){
    //                 $kode_hari='1';
    //                 $nama_hari='Selasa';
    //                 $status_absen='M';
    //                 $jadwal_mulai_jam_kerja='07:30';
    //                 $jadwal_akhir_jam_kerja='16:30';
    //             }else if($hari=='Wed'){
    //                 $kode_hari='2';
    //                 $nama_hari='Rabu';
    //                 $status_absen='M';
    //                 $jadwal_mulai_jam_kerja='07:30';
    //                 $jadwal_akhir_jam_kerja='16:30';
    //             }else if($hari=='Thu'){
    //                 $kode_hari='3';
    //                 $nama_hari='Kamis';
    //                 $status_absen='M';
    //                 $jadwal_mulai_jam_kerja='07:30';
    //                 $jadwal_akhir_jam_kerja='16:30';
    //             }else if($hari=='Fri'){
    //                 $kode_hari='4';
    //                 $nama_hari='Jumat';
    //                 $status_absen='M';
    //                 $jadwal_mulai_jam_kerja='07:30';
    //                 $jadwal_akhir_jam_kerja='16:30';
    //             }else if($hari=='Sat'){
    //                 $kode_hari='5';
    //                 $nama_hari='Sabtu';
    //                 $status_absen=null;
    //                 $jadwal_mulai_jam_kerja=null;
    //                 $jadwal_akhir_jam_kerja=null;

    //             }
    //             $jadwal=MasterDataAbsenKehadiran::where('tanggal_berjalan','2023-05-25')->where('enroll_id',$value->enroll_id)->first();

    //             $jadwal_mulai_jam_kerja=$jadwal->mulai_jam_kerja;
    //             $jadwal_akhir_jam_kerja=$jadwal->akhir_jam_kerja;
    //             if($kode_hari==6 ||$kode_hari==5){
    //                 $jadwal_mulai_jam_kerja=null;
    //                 $jadwal_akhir_jam_kerja=null;
    //             }
    //             $x=[
    //                 'uuid' => Str::uuid('uuid'),
    //                 'tanggal_berjalan' => $tgl_berjalan,
    //                 'kode_hari' =>$kode_hari,
    //                 'nama_hari' => $nama_hari,
    //                 'employee_id' => $value->employee_id,
    //                 'employee_name' => $value->employee_name,
    //                 'enroll_id' => $value->enroll_id,
    //                 'join_date' => $value->join_date,
    //                 'tanggal_resign' => $value->tanggal_resign,
    //                 'work_status' => $value->work_status,
    //                 'status_aktif' => $value->status_aktif,
    //                 'status_kontrak_tetap' => $value->status_kontrak_tetap,
    //                 'employee_status' => $value->employee_status,
    //                 'status_jabatan' => $value->status_jabatan,
    //                 'posisi_name' => $value->posisi_name,
    //                 'status_staff' => $value->status_staff,
    //                 'nik' => $value->nik,
    //                 'site_nirwana_id' => $value->site_nirwana_id,
    //                 'site_nirwana_name' => $value->site_nirwana_name,
    //                 'department_id' => $value->department_id,
    //                 'department_name' => $value->department_name,
    //                 'sub_dept_id' => $value->sub_dept_id,
    //                 'sub_dept_name' => $value->sub_dept_name,
    //                 'status_absen' => $status_absen,
    //                 'operator' =>'system',
    //                 'mulai_jam_kerja'=>$jadwal_mulai_jam_kerja,
    //                 'akhir_jam_kerja'=>$jadwal_akhir_jam_kerja,
    //             ];
    //             // dd($x);
    //             MasterDataAbsenKehadiran::create($x);
    //             $tgl_berjalan = date ("Y-m-d", strtotime("+1 day", strtotime($tgl_berjalan)));

    //         }
    //     }
    // }

    // public function index2()
    // {
    //     $employee=EmployeeAtribut::wherein('enroll_id',[
    //         '752',
    //         '2693',
    //         '4310',])->get();
    //     // dd($employee);

    //     foreach ($employee as $key => $value) {
    //         $tanggal_akhir='2023-06-25';
    //         $tgl_berjalan='2023-05-26';
    //         while (strtotime( $tgl_berjalan) <= strtotime($tanggal_akhir)) {
    //             $hari=date('D',strtotime(  $tgl_berjalan));
    //             if($hari=='Sun'){
    //                 $kode_hari='6';
    //                 $nama_hari='Minggu';
    //                 $status_absen=null;
    //             }else if($hari=='Mon'){
    //                 $kode_hari='0';
    //                 $nama_hari='Senin';
    //                 $status_absen='M';
    //             }else if($hari=='Tue'){
    //                 $kode_hari='1';
    //                 $nama_hari='Selasa';
    //                 $status_absen='M';
    //             }else if($hari=='Wed'){
    //                 $kode_hari='2';
    //                 $nama_hari='Rabu';
    //                 $status_absen='M';
    //             }else if($hari=='Thu'){
    //                 $kode_hari='3';
    //                 $nama_hari='Kamis';
    //                 $status_absen='M';
    //             }else if($hari=='Fri'){
    //                 $kode_hari='4';
    //                 $nama_hari='Jumat';
    //                 $status_absen='M';
    //             }else if($hari=='Sat'){
    //                 $kode_hari='5';
    //                 $nama_hari='Sabtu';
    //                 $status_absen=null;

    //             }
    //             $x=[
    //                 // 'uuid' => Str::uuid('uuid'),
    //                 // 'tanggal_berjalan' => $tgl_berjalan,
    //                 // 'kode_hari' =>$kode_hari,
    //                 // 'nama_hari' => $nama_hari,
    //                 // 'employee_id' => $value->employee_id,
    //                 // 'employee_name' => $value->employee_name,
    //                 // 'enroll_id' => $value->enroll_id,
    //                 // 'join_date' => $value->join_date,
    //                 // 'tanggal_resign' => $value->tanggal_resign,
    //                 // 'work_status' => $value->status_aktif,
    //                 'status_aktif' => $value->status_aktif,
    //                 'status_kontrak_tetap' => $value->status_kontrak_tetap,
    //                 'employee_status' => $value->status_kontrak_tetap,
    //                 'status_jabatan' => $value->status_jabatan,
    //                 'posisi_name' => $value->posisi_name,
    //                 'status_staff' => $value->status_staff,
    //                 // 'nik' => $value->nik,
    //                 // 'site_nirwana_id' => $value->site_nirwana_id,
    //                 // 'site_nirwana_name' => $value->site_nirwana_name,
    //                 // 'department_id' => $value->department_id,
    //                 // 'department_name' => $value->department_name,
    //                 // 'sub_dept_id' => $value->sub_dept_id,
    //                 // 'sub_dept_name' => $value->sub_dept_name,
    //                 // 'status_absen' => $status_absen,
    //                 // 'operator' =>'system',
    //             ];
    //             MasterDataAbsenKehadiran::where('tanggal_berjalan',$tgl_berjalan)->where('enroll_id',$value->enroll_id)->update($x);
    //             // MasterDataAbsenKehadiran::create($x);
    //             $tgl_berjalan = date ("Y-m-d", strtotime("+1 day", strtotime($tgl_berjalan)));

    //         }
    //     }
    //     dd($x);

    // }

    // public function index3()
    // {
    //     $employee=EmployeeAtribut::wherein('enroll_id',[
    //         '752',
    //         '2693',])->get();
    //     // dd($employee);

    //     foreach ($employee as $key => $value) {
    //         $tanggal_akhir='2023-06-25';
    //         $tgl_berjalan='2023-05-26';
    //         while (strtotime( $tgl_berjalan) <= strtotime($tanggal_akhir)) {
    //             $hari=date('D',strtotime(  $tgl_berjalan));
    //             if($hari=='Sun'){
    //                 $kode_hari='6';
    //                 $nama_hari='Minggu';
    //                 $status_absen=null;
    //             }else if($hari=='Mon'){
    //                 $kode_hari='0';
    //                 $nama_hari='Senin';
    //                 $status_absen='M';
    //             }else if($hari=='Tue'){
    //                 $kode_hari='1';
    //                 $nama_hari='Selasa';
    //                 $status_absen='M';
    //             }else if($hari=='Wed'){
    //                 $kode_hari='2';
    //                 $nama_hari='Rabu';
    //                 $status_absen='M';
    //             }else if($hari=='Thu'){
    //                 $kode_hari='3';
    //                 $nama_hari='Kamis';
    //                 $status_absen='M';
    //             }else if($hari=='Fri'){
    //                 $kode_hari='4';
    //                 $nama_hari='Jumat';
    //                 $status_absen='M';
    //             }else if($hari=='Sat'){
    //                 $kode_hari='5';
    //                 $nama_hari='Sabtu';
    //                 $status_absen=null;

    //             }
    //             // $jadwal=MasterDataAbsenKehadiran::where('tanggal_berjalan','2023-04-25')->where('enroll_id',$value->enroll_id)->first();
    //             // dd($jadwal->mulai_jam_kerja??null);
    //             // dd($jadwal->akhir_jam_kerja??null);

    //             $ijin=DataAbsenPerijinan::where('tanggal_akhir_ijin','>=',$tgl_berjalan)->where('enroll_id',$value->enroll_id)->wherein('kode_absen_ijin',['CM','CG'])->first();
    //             $cek_ijin=$ijin->nomor_form_perizinan??null;
    //             if($kode_hari!=6 && $kode_hari!=5 && $cek_ijin!=null){

    //             $x=[
    //                 'nomor_absen_ijin' => $ijin->nomor_form_perizinan,
    //                 'status_absen' => $ijin->kode_absen_ijin,
    //                 'absen_alasan' => $ijin->absen_alasan,
    //                 'tanggal_mulai_ijin' => $ijin->tanggal_mulai_ijin,
    //                 'tanggal_akhir_ijin' => $ijin->tanggal_akhir_ijin,
    //                 'operator' => $ijin->operator,
    //                 'updated_absen_ijin' => now(),
    //                 // 'uuid' => Str::uuid('uuid'),
    //                 'tanggal_berjalan' => $tgl_berjalan,
    //                 // 'kode_hari' =>$kode_hari,
    //                 // 'nama_hari' => $nama_hari,
    //                 // 'employee_id' => $value->employee_id,
    //                 // 'employee_name' => $value->employee_name,
    //                 'enroll_id' => $value->enroll_id,
    //                 // 'join_date' => $value->join_date,
    //                 // 'tanggal_resign' => $value->tanggal_resign,
    //                 // 'work_status' => $value->status_aktif,
    //                 // 'status_aktif' => $value->status_aktif,
    //                 // 'status_kontrak_tetap' => $value->status_kontrak_tetap,
    //                 // 'employee_status' => $value->status_kontrak_tetap,
    //                 // 'status_jabatan' => $value->status_jabatan,
    //                 // 'posisi_name' => $value->posisi_name,
    //                 // 'status_staff' => $value->status_staff,
    //                 // 'nik' => $value->nik,
    //                 // 'site_nirwana_id' => $value->site_nirwana_id,
    //                 // 'site_nirwana_name' => $value->site_nirwana_name,
    //                 // 'department_id' => $value->department_id,
    //                 // 'department_name' => $value->department_name,
    //                 // 'sub_dept_id' => $value->sub_dept_id,
    //                 // 'sub_dept_name' => $value->sub_dept_name,
    //                 // 'status_absen' => $status_absen,
    //                 // 'operator' =>'system',
    //                 // 'mulai_jam_kerja'=>null,
    //                 // 'akhir_jam_kerja'=>null,
    //             ];
    //             MasterDataAbsenKehadiran::where('tanggal_berjalan',$tgl_berjalan)->where('enroll_id',$value->enroll_id)->update($x);
    //             // MasterDataAbsenKehadiran::create($x);

    //             }
    //             $tgl_berjalan = date ("Y-m-d", strtotime("+1 day", strtotime($tgl_berjalan)));


    //         }
    //     }
    //     dd('ok');

    // }
}
