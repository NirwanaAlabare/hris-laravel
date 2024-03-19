<?php
namespace App\Models;

class EmployeeAtribut extends \Eloquent
{
    protected $table = 'employee_atribut';

    // Don't forget to fill this array
    protected $fillable = [
        'employee_id',
        'employee_name',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'golongan_darah',
        'email',
        'nomor_tlpn',
        'agama',
        'status_kawin',
        'npwp',
        'nomor_ktp',
        'nomor_kk',
        'ptkp',
        'nama_sekolah_terakhir',
        'pendidikan_terakhir',
        'jurusan_pendidikan',
        'nama_bank',
        'nomor_rekening_bank',
        'ibu_kandung',
        'propinsi',
        'kota_kab',
        'kecamatan',
        'kelurahan_desa',
        'alamat_rumah',
        'alamat_sementara',
        'site_nirwana_id',
        'site_nirwana_name',
        'department_id',
        'department_name',
        'sub_dept_id',
        'sub_dept_name',
        'enroll_id',
        'join_date',
        'nik',
        'status_aktif',
        'status_jabatan',
        'status_kontrak_tetap',
        'status_staff',
        'tanggal_resign',
        'tunjangan',
        'kode_grade',
        'referensi',
        'employee_name_atasan',
        'status_aktif_bpjs_tk',
        'tanggal_bpjs_ketenagakerjaan',
        'nomor_bpjs_ketenagakerjaan',
        'status_aktif_bpjs_ks',
        'tanggal_bpjs_kesehatan',
        'nomor_bpjs_kesehatan',
        'pengalaman_bekerja',
        'lokasi_file_cv',
        'nama_kerabat',
        'nomor_tlpn_kerabat',
        'hubungan_kerabat',
        'alamat_kerabat',
        'tanggal_vaccine1',
        'nama_vaksin1',
        'tanggal_vaccine2',
        'nama_vaksin2',
        'tanggal_vaccine3',
        'nama_vaksin3',
        'golongan_sim',
        'nomor_sim',
        'tanggal_expire_sim',
        'catatan',
        'lokasi_foto',
        'operator',
        'tanggal_mulai_kontrak',
        'tanggal_akhir_kontrak',
        'catatan_kontrak',
        'created_at',
        'updated_at',
        'deleted_at',
        'shift_work_id',
        'work_status',
        'employee_status',
        'posisi_name',
        'hamlet',
        'kode_pos',
        'saudara_yang_bisa_dihubungi',
        'allowance',
        'pola_kerja',
        'premi'
    ];
    protected $guarded = ['employee_id'];

    protected $hidden = ['employee_id'];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'employee_id',
        'employee_name',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'golongan_darah',
        'email',
        'nomor_tlpn',
        'agama',
        'status_kawin',
        'npwp',
        'nomor_ktp',
        'nomor_kk',
        'ptkp',
        'nama_sekolah_terakhir',
        'pendidikan_terakhir',
        'jurusan_pendidikan',
        'nama_bank',
        'nomor_rekening_bank',
        'ibu_kandung',
        'propinsi',
        'kota_kab',
        'kecamatan',
        'kelurahan_desa',
        'alamat_rumah',
        'alamat_sementara',
        'site_nirwana_id',
        'site_nirwana_name',
        'department_id',
        'department_name',
        'sub_dept_id',
        'sub_dept_name',
        'enroll_id',
        'join_date',
        'nik',
        'status_aktif',
        'status_jabatan',
        'status_kontrak_tetap',
        'status_staff',
        'tanggal_resign',
        'tunjangan',
        'kode_grade',
        'referensi',
        'employee_name_atasan',
        'status_aktif_bpjs_tk',
        'tanggal_bpjs_ketenagakerjaan',
        'nomor_bpjs_ketenagakerjaan',
        'status_aktif_bpjs_ks',
        'tanggal_bpjs_kesehatan',
        'nomor_bpjs_kesehatan',
        'pengalaman_bekerja',
        'lokasi_file_cv',
        'nama_kerabat',
        'nomor_tlpn_kerabat',
        'hubungan_kerabat',
        'alamat_kerabat',
        'tanggal_vaccine1',
        'nama_vaksin1',
        'tanggal_vaccine2',
        'nama_vaksin2',
        'tanggal_vaccine3',
        'nama_vaksin3',
        'golongan_sim',
        'nomor_sim',
        'tanggal_expire_sim',
        'catatan',
        'lokasi_foto',
        'operator',
        'tanggal_mulai_kontrak',
        'tanggal_akhir_kontrak',
        'catatan_kontrak',
        'created_at',
        'updated_at',
        'deleted_at',
        'shift_work_id',
        'work_status',
        'employee_status',
        'posisi_name',
        'hamlet',
        'kode_pos',
        'saudara_yang_bisa_dihubungi',
        'allowance',
        'pola_kerja',
        'premi'
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['employee_id','enroll_id'];

    public function dept(){
        return $this->belongsTo('App\Models\DepartmentAll', 'sub_dept_id','sub_dept_id');
    }
    public function absensi(){
        return $this->hasMany(MasterDataAbsenKehadiran::class, 'enroll_id','enroll_id');
    }
    public function grading_salary(){
        return $this->hasMany(GradingSalary::class, 'kode_grade','kode_grade');
    }


}
