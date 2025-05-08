<?php
namespace App\Models;

class PenilaianKinerja extends \Eloquent
{
    protected $table = 'penilaian_kinerja';



    // Don't forget to fill this array
    protected $fillable = [
    'enroll_id',
    'employee_contract_id',
    'tgl_awal_kontrak',
    'tgl_akhir_kontrak',
    'uraian_tugas_1',
    'target_pencapaian_1',
    'uraian_tugas_2',
    'target_pencapaian_2',
    'uraian_tugas_3',
    'target_pencapaian_3',
    'uraian_tugas_4',
    'target_pencapaian_4',
    'uraian_tugas_5',
    'target_pencapaian_5',
    'nilai_kinerja',
    'tanggung_jawab_tugas',
    'inisiatif_kerjasama',
    'akurasi_pekerjaan',
    'kemauan_kegigihan',
    'penyampaian_informasi',
    'attitude_sikap_kerja',
    'rata_rata_kompetensi',
    'sp3_kali',
    'sp2_kali',
    'sp1_kali',
    'kecelakaan_kali',
    'mangkir_kali',
    'ijin_kali',
    'total_pengurangan',
    'nilai_kedisiplinan',
    'rekomendasi_perpanjang_kontrak',
    'perpanjang_bulan',
    'rekomendasi_phk',
    'rekomendasi_demosi',
    'rekomendasi_promosi',
    'rekomendasi_training',
    'nilai_akhir',
    'penilai',
    'diketahui_chief_manager',
    'diketahui_hrd',
    'disetujui_gm',
    'created_at',
    'updated_at',
    'judul_training',
    'periode_kontrak',
    'total_kompetensi',
    'rekomendasi_tindak_lanjut'
    ];

}
