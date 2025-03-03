<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanTransportasi extends Model
{
    use HasFactory;
    protected $table = 'permintaan_transportasi';
    protected $fillable = ['id', 'enroll_id', 'id_desa','instansi', 'detail_alamat', 'id_desa_tujuan','detail_alamat_tujuan','tanggal_pemberangkatan','jam_pemberangkatan','tanggal_kedatangan','jam_kedatangan','tujuan_pemberangkatan','jarak_tempuh','nama_tamu','instansi_tamu','nomor_hp_tamu','jenis_barang','quantity','satuan','nama_instansi','nama_penerima','keterangan_barang','karyawan_dinas','created_by','status'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
    public function employee_atribut(){
        return $this->belongsTo(EmployeeAtribut::class, 'enroll_id', 'enroll_id');
    }
    public function districts(){
        return $this->belongsTo(District::class, 'id_desa', 'dis_id');
    }
    public function districts_2(){
        return $this->belongsTo(District::class, 'id_desa_tujuan', 'dis_id');
    }
}
