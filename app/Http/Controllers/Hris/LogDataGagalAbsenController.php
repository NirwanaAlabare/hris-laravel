<?php

namespace App\Http\Controllers\Hris;

use App\Models\LogDataGagalAbsen;
use App\Models\MasterDataAbsenKehadiran;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Datatables;


/**
 * Class MdAbsenHadirController
 * @package App\Http\Controllers\Hris
 */
class LogDataGagalAbsenController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Master Data';
    }

    public function index()
    {
        //return View::make('hris/departmentall', $this->data);
    }

    public function store(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        $tanggal_absen = $request->tanggal_absen;
        $kode_hari = $request->kode_hari;
        $nama_hari = $request->nama_hari;
        $no_form = $request->no_form;
        $uuid_master = $request->uuid_master;
        $employee_id = $request->employee_id;
        $nik = $request->nik;
        $enroll_id = $request->enroll_id;
        $employee_name = $request->employee_name;
        $department_id = $request->department_id;
        $department_name = $request->department_name;
        $sub_dept_id = $request->sub_dept_id;
        $sub_dept_name = $request->sub_dept_name;
        $kode_shift = $request->shift_work_id;
        $jadwal_masuk_kerja = $request->jadwal_masuk_kerja;
        $jadwal_pulang_kerja = $request->jadwal_pulang_kerja;
        $status_absen = $request->status_absen;
        $absen_masuk_kerja = $request->absen_masuk_kerja;
        $absen_pulang_kerja = $request->absen_pulang_kerja;
        $absen_alasan = $request->absen_alasan;
        $status_absen_old = $request->status_absen_old;
        $absen_in_old = $request->absen_in_old;
        $absen_out_old = $request->absen_out_old;
        $absen_alasan_old = $request->absen_alasan_old;
        $is_approved = 1;
        $queryNomor = LogDataGagalAbsen::where('nomor_form_gagal_absen','=',$no_form)->count();
        $queryMaster = "";

        if ($queryNomor>0) {
            $query =  LogDataGagalAbsen::where('nomor_form_gagal_absen','=',$no_form)
            ->update([
                'status_absen' => $status_absen,
                'jadwal_masuk_kerja' => $jadwal_masuk_kerja,
                'jadwal_pulang_kerja' => $jadwal_pulang_kerja,
                'absen_in' => $absen_masuk_kerja,
                'absen_out' => $absen_pulang_kerja,
                'absen_alasan' => $absen_alasan,
                'status_absen_old' => $status_absen_old,
                'absen_in_old' => $absen_in_old,
                'absen_out_old' => $absen_out_old,
                'absen_alasan_old' => $absen_alasan_old,
                'is_approved' => $is_approved,
                'operator' => $email
            ]);
            //info("Update Perubahan Absen : " . $no_form);
            $nomor_form_perubahan_absen = $no_form;
        } else {
            try {
                $query = LogDataGagalAbsen::create([
                    'uuid' => Str::uuid(),
                    'uuid_master' => $uuid_master,
                    'employee_id' => $employee_id,
                    'nik' => $nik,
                    'enroll_id' => $enroll_id,
                    'employee_name' => $employee_name,
                    'tanggal_absen' => $tanggal_absen,
                    'kode_hari' => $kode_hari,
                    'nama_hari' => $nama_hari,
                    'status_absen' => $status_absen,
                    'department_id' => $department_id,
                    'department_name' => $department_name,
                    'sub_dept_id' => $sub_dept_id,
                    'sub_dept_name' => $sub_dept_name,
                    'kode_shift' => $kode_shift,
                    'jadwal_masuk_kerja' => $jadwal_masuk_kerja,
                    'jadwal_pulang_kerja' => $jadwal_pulang_kerja,
                    'absen_in' => $absen_masuk_kerja,
                    'absen_out' => $absen_pulang_kerja,
                    'absen_alasan' => $absen_alasan,
                    'status_absen_old' => $status_absen_old,
                    'absen_in_old' => $absen_in_old,
                    'absen_out_old' => $absen_out_old,
                    'absen_alasan_old' => $absen_alasan_old,
                    'is_approved' => $is_approved,
                    'operator' => $email
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
            $nomor_form_perubahan_absen = $query->nomor_form_gagal_absen;
        }

        return Response()->json($nomor_form_perubahan_absen);
    }

}
