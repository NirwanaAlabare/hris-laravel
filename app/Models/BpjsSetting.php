<?php
namespace App\Models;

class BpjsSetting extends \Eloquent
{
    protected $table = 'bpjs_setting';

    // Don't forget to fill this array
    protected $fillable = [
        'kode_periode_bpjs',
        'kode_dasar_pot_bpjs',
        'dasar_pot_bpjs_rupiah',
        'bpjs_tk_jkm_persen',
        'bpjs_tk_jkk_persen',
        'bpjs_tk_jht_persen',
        'bpjs_tk_jpn_persen',
        'bpjs_ks_jkn_persen',
        'bpjs_tk_jkm_perusahaan_persen',
        'bpjs_tk_jkm_karyawan_persen',
        'bpjs_tk_jkk_perusahaan_persen',
        'bpjs_tk_jkk_karyawan_persen',
        'bpjs_tk_jht_perusahaan_persen',
        'bpjs_tk_jht_karyawan_persen',
        'bpjs_tk_jpn_perusahaan_persen',
        'bpjs_tk_jpn_karyawan_persen',
        'bpjs_ks_jkn_perusahaan_persen',
        'bpjs_ks_jkn_karyawan_persen',        
        'operator',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $guarded = [];

    protected $hidden = [];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'kode_periode_bpjs',
        'kode_dasar_pot_bpjs',
        'dasar_pot_bpjs_rupiah',
        'bpjs_tk_jkm_persen',
        'bpjs_tk_jkk_persen',
        'bpjs_tk_jht_persen',
        'bpjs_tk_jpn_persen',
        'bpjs_ks_jkn_persen',
        'bpjs_tk_jkm_perusahaan_persen',
        'bpjs_tk_jkm_karyawan_persen',
        'bpjs_tk_jkk_perusahaan_persen',
        'bpjs_tk_jkk_karyawan_persen',
        'bpjs_tk_jht_perusahaan_persen',
        'bpjs_tk_jht_karyawan_persen',
        'bpjs_tk_jpn_perusahaan_persen',
        'bpjs_tk_jpn_karyawan_persen',
        'bpjs_ks_jkn_perusahaan_persen',
        'bpjs_ks_jkn_karyawan_persen',        
        'operator',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['kode_periode_bpjs'];

}
