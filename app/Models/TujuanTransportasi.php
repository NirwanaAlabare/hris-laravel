<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanTransportasi extends Model
{
    use HasFactory;
    protected $table = 'tujuan_transportasi';
    protected $fillable = ['id', 'permintaan_transportasi_id', 'tujuan_id', 'subdistrict','instansi', 'detail_alamat','tanggal_kedatangan','jam_kedatangan','jarak_tempuh','tujuan_pemberangkatan','jenis_barang','quantity','satuan','nama_penerima','keterangan_barang','nama_tamu','nomor_hp_tamu','karyawan_dinas_luar','driver','vehicle','status'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
    public function employee_atribut(){
        return $this->belongsTo(EmployeeAtribut::class, 'enroll_id', 'enroll_id');
    }
    public function subdistrict(){
        return $this->belongsTo(Subdistrict::class, 'subdistrict', 'subdis_id');
    }
}
