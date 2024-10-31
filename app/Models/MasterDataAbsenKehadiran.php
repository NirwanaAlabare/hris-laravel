<?php
namespace App\Models;

class MasterDataAbsenKehadiran extends \Eloquent
{
    protected $table = 'master_data_absen_kehadiran';

    // Don't forget to fill this array
    protected $fillable = [
        'uuid',
        'tanggal_berjalan',
        'tanggal_absen',
        'shift_work_id',
        'kode_hari',
        'nama_hari',
        'time_table_name',
        'mulai_jam_kerja',
        'akhir_jam_kerja',
        'jam_kerja',
        'jumlah_jam_kerja',
        'jumlah_menit_kerja',
        'mulai_jam_istirahat',
        'akhir_jam_istirahat',
        'jumlah_jam_istirahat',
        'jumlah_menit_istirahat',
        'absen_masuk_kerja',
        'absen_pulang_kerja',
        'enroll_id',
        'nik',
        'employee_id',
        'employee_name',
        'join_date',
        'work_status',
        'employee_status',
        'posisi_name',
        'kode_grade',
        'site_nirwana_id',
        'site_nirwana_name',
        'department_id',
        'department_name',
        'status_staff',
        'status_jabatan',
        'sub_dept_id',
        'sub_dept_name',
        'status_absen',
        'nama_absen_ijin',
        'kode_ijin_payroll',
        'absen_ijin_id',
        'nomor_absen_ijin',
        'tanggal_mulai_ijin',
        'tanggal_akhir_ijin',
        'permits_dari_pukul',
        'permits_sampai_pukul',
        'total_menit_permits',
        'jumlah_menit_absen_dt',
        'jumlah_menit_absen_pc',
        'jumlah_menit_absen_dtpc',
        'jumlah_absen_menit_kerja',
        'absen_s_sakit',
        'absen_iby_ijin_dibayar',
        'absen_itb_ijin_tidak_dibayar',
        'absen_m_mangkir',
        'absen_pc_pulang_cepat',
        'absen_dt_datang_terlambat',
        'absen_dtpc_datang_terlambat_pulang_cepat',
        'absen_lby_libur_dibayar',
        'absen_lsm_libur_sabtu_minggu',
        'absen_ltb_libur_tidak_dibayar',
        'absen_r_resign',
        'absen_dl_dinas_luar',
        'absen_ok_hadir',
        'absen_tk_tidak_kerja',
        'absen_cuti_umum',
        'absen_cuti_khusus',
        'absen_hari_resmi',
        'absen_alasan',
        'holiday_id',
        'holiday_name',
        'kelebihan_jam_kerja_l1',
        'kelebihan_jam_kerja_l2',
        'kelebihan_jam_kerja_l3',
        'kelebihan_jam_kerja_l4',
        'jumlah_selisih_menit_kerja',
        'operator',
        'catatan_hrd',
        'nomor_form_perubahan_absen',
        'nomor_form_lembur',
        'jumlah_jam_lembur',
        'jumlah_jam_istirahat_lembur',
        'mulai_jam_lembur',
        'akhir_jam_lembur',
        'status_lembur',
        'jumlah_jam_lembur_approved',
        'updated_absen_cekinout',
        'updated_absen_ijin',
        'updated_absen_permits',
        'updated_absen_dtpc',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $guarded = [];

    protected $hidden = [];

    protected $appends = [];

    protected $casts = [
        'uuid',
        'tanggal_berjalan',
        'tanggal_absen',
        'shift_work_id',
        'kode_hari',
        'nama_hari',
        'time_table_name',
        'mulai_jam_kerja',
        'akhir_jam_kerja',
        'jam_kerja',
        'jumlah_jam_kerja',
        'jumlah_menit_kerja',
        'mulai_jam_istirahat',
        'akhir_jam_istirahat',
        'jumlah_jam_istirahat',
        'jumlah_menit_istirahat',
        'absen_masuk_kerja',
        'absen_pulang_kerja',
        'enroll_id',
        'nik',
        'employee_id',
        'employee_name',
        'join_date',
        'work_status',
        'employee_status',
        'posisi_name',
        'kode_grade',
        'site_nirwana_id',
        'site_nirwana_name',
        'department_id',
        'department_name',
        'status_staff',
        'status_jabatan',
        'sub_dept_id',
        'sub_dept_name',
        'status_absen',
        'nama_absen_ijin',
        'kode_ijin_payroll',
        'absen_ijin_id',
        'nomor_absen_ijin',
        'tanggal_mulai_ijin',
        'tanggal_akhir_ijin',
        'permits_dari_pukul',
        'permits_sampai_pukul',
        'total_menit_permits',
        'jumlah_menit_absen_dt',
        'jumlah_menit_absen_pc',
        'jumlah_menit_absen_dtpc',
        'jumlah_absen_menit_kerja',
        'absen_s_sakit',
        'absen_iby_ijin_dibayar',
        'absen_itb_ijin_tidak_dibayar',
        'absen_m_mangkir',
        'absen_pc_pulang_cepat',
        'absen_dt_datang_terlambat',
        'absen_dtpc_datang_terlambat_pulang_cepat',
        'absen_lby_libur_dibayar',
        'absen_lsm_libur_sabtu_minggu',
        'absen_ltb_libur_tidak_dibayar',
        'absen_r_resign',
        'absen_dl_dinas_luar',
        'absen_ok_hadir',
        'absen_tk_tidak_kerja',
        'absen_cuti_umum',
        'absen_cuti_khusus',
        'absen_hari_resmi',
        'absen_alasan',
        'holiday_id',
        'holiday_name',
        'kelebihan_jam_kerja_l1',
        'kelebihan_jam_kerja_l2',
        'kelebihan_jam_kerja_l3',
        'kelebihan_jam_kerja_l4',
        'jumlah_selisih_menit_kerja',
        'operator',
        'catatan_hrd',
        'nomor_form_perubahan_absen',
        'nomor_form_lembur',
        'jumlah_jam_lembur',
        'jumlah_jam_istirahat_lembur',
        'mulai_jam_lembur',
        'akhir_jam_lembur',
        'status_lembur',
        'jumlah_jam_lembur_approved',
        'updated_absen_cekinout',
        'updated_absen_ijin',
        'updated_absen_permits',
        'updated_absen_dtpc',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = [
        'uuid','tanggal_berjalan','employee_id'
    ];
    public function employee_atribut(){
        return $this->belongsTo(EmployeeAtribut::class, 'enroll_id', 'enroll_id');
    }
    public function ref_absen(){
        return $this->belongsTo(RefAbsenIjin::class,'status_absen','kode_absen_ijin');
    }
    public function data_lembur(){
        return $this->hasOne(DataLembur::class, 'uuid_master','uuid');
    }

    public function rekap_lembur(){
        return $this->hasMany(RekapPerhitunganLembur::class, 'enroll_id','enroll_id');
    }
    public function daily_labor_cost(){
        return $this->hasMany(DailyLaborCost::class,'enroll_id','enroll_id');
    }
    public function rekap_perhitungan_kehadiran(){
        return $this->hasMany(RekapPerhitunganKehadiranKaryawan::class, 'enroll_id','enroll_id');
    }
    public function koreksi_upah(){
        return $this->hasMany(DataKoreksiUpah::class, 'enroll_id','enroll_id');
    }
    public function koreksi_potongan(){
        return $this->hasMany(DataKoreksiPotongan::class, 'enroll_id','enroll_id');
    }
    public function mut_karyawan_input_form_lembur(){
        return $this->hasMany(MutKaryawanInputFormLembur::class, 'tgl_lembur','tanggal_berjalan');
    }
    public function mut_karyawan_input_non_sewing_form_lembur(){
        return $this->hasMany(MutKaryawanInputNonSewingFormLembur::class, 'tgl_lembur','tanggal_berjalan');
    }
}
