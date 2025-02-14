<div>
	<table>
        <tr>
            <td style="font-size:14pt;font-family:Calibri;font-weight:bold">PT NIRWANA ALABARE GARMENT</td>
        </tr>
        <tr>
            <td style="font-size:12pt;font-family:Calibri;font-weight:bold">LAPORAN PERMINTAAN TRANPORTASI</td>
        </tr>
        <tr>
            <td style="font-size:12pt;font-family:Calibri;font-weight:bold">TANGGAL PEMBERANGKATAN : {{$tanggal_awal}} s/d {{$tanggal_akhir}}</td>
        </tr>
    </table>
	<table>
        <tr>
            <td width="5">No</td>
            <td>Tanggal Dibuat</td>
            <td>ID Karyawan</td>
            <td>Nama Karyawan</td>
            <td>Department</td>
            <td>Bagian</td>
            <td>Destinasi Awal</td>
            <td>Tanggal Pemberangkatan</td>
            <td>Jam Pemberangkatan</td>
            <td>Destinasi Akhir</td>
            <td>Tanggal Kedatangan</td>
            <td>Jam Kedatangan</td>
            <td>Tujuan Pemberangkatan</td>
            <td>Nama Tamu</td>
            <td>Intansi Tamu</td>
            <td>Nomor HP Tamu</td>
            <td>Jenis Barang</td>
            <td>Quantity</td>
            <td>Satuan</td>
            <td>Nama instansi</td>
            <td>Nama Penerima</td>
            <td>Keterangan Barang</td>
            <td>Karyawan yang dinas luar</td>
            <td>ID Pembuat</td>
            <td>Nama Pembuat</td>
            <td>Status</td>
            <td>ID Driver</td>
            <td>Nama Driver</td>
            <td>Nomor Kendaraan</td>
            <td>Merk Kendaraan</td>
            <td>Alternative</td>
        </tr>
        @foreach ($query as $key=>$value)
        @if($key==0)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$value['created_at']}}</td>
            <td>{{$value['enroll_id']}}</td>
            <td>{{$value['employee_name']}}</td>
            <td>{{$value['department']}}</td>
            <td>{{$value['bagian']}}</td>
            <td>{{$value['destinasi_awal']}}</td>
            <td>{{$value['tanggal_pemberangkatan']}}</td>
            <td>{{$value['jam_pemberangkatan']}}</td>
            <td>{{$value['destinasi_akhir']}}</td>
            <td>{{$value['tanggal_kedatangan']}}</td>
            <td>{{$value['jam_kedatangan']}}</td>
            <td>{{str_replace("_"," ",$value['tujuan_pemberangkatan'])}}</td>
            <td>{{$value['nama_tamu']}}</td>
            <td>{{$value['instansi_tamu']}}</td>
            <td>{{$value['nomor_hp_tamu']}}</td>
            <td>{{$value['jenis_barang']}}</td>
            <td>{{$value['quantity']}}</td>
            <td>{{$value['satuan']}}</td>
            <td>{{$value['nama_instansi']}}</td>
            <td>{{$value['nama_penerima']}}</td>
            <td>{{$value['keterangan_barang']}}</td>
            <td>{{$value['karyawan_dinas']}}</td>
            <td>{{$value['created_by']}}</td>
            <td>{{$value['nama_pembuat']}}</td>
            <td>@switch($value['status'])
                @case(0)
                    Pending
                    @break
                @case(1)
                    Approved
                    @break
                @case(2)
                    Alternative
                    @break
                @case(3)
                    On The Way
                    @break
                @case(4)
                    Done
                    @break
                @case(5)
                    Cancel
                    @break
                @default
                    Late
            @endswitch</td>
            <td>{{$value['id_driver']}}</td>
            <td>{{$value['nama_driver']}}</td>
            <td>{{$value['nomor_kendaraan']}}</td>
            <td>{{$value['alternative']}}</td>
        </tr>
        @else
        @if($query[$key-1]['permintaan_transportasi_id']==$value['permintaan_transportasi_id'])
        <tr>
            <td>{{$key+1}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$value['destinasi_akhir']}}</td>
            <td>{{$value['tanggal_kedatangan']}}</td>
            <td>{{$value['jam_kedatangan']}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @else
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$value['created_at']}}</td>
            <td>{{$value['enroll_id']}}</td>
            <td>{{$value['employee_name']}}</td>
            <td>{{$value['department']}}</td>
            <td>{{$value['bagian']}}</td>
            <td>{{$value['destinasi_awal']}}</td>
            <td>{{$value['tanggal_pemberangkatan']}}</td>
            <td>{{$value['jam_pemberangkatan']}}</td>
            <td>{{$value['destinasi_akhir']}}</td>
            <td>{{$value['tanggal_kedatangan']}}</td>
            <td>{{$value['jam_kedatangan']}}</td>
            <td>{{str_replace("_"," ",$value['tujuan_pemberangkatan'])}}</td>
            <td>{{$value['nama_tamu']}}</td>
            <td>{{$value['instansi_tamu']}}</td>
            <td>{{$value['nomor_hp_tamu']}}</td>
            <td>{{$value['jenis_barang']}}</td>
            <td>{{$value['quantity']}}</td>
            <td>{{$value['satuan']}}</td>
            <td>{{$value['nama_instansi']}}</td>
            <td>{{$value['nama_penerima']}}</td>
            <td>{{$value['keterangan_barang']}}</td>
            <td>{{$value['karyawan_dinas']}}</td>
            <td>{{$value['created_by']}}</td>
            <td>{{$value['nama_pembuat']}}</td>
            <td>{{$value['status']}}</td>
            <td>{{$value['id_driver']}}</td>
            <td>{{$value['nama_driver']}}</td>
            <td>{{$value['nomor_kendaraan']}}</td>
            <td>{{$value['alternative']}}</td>
        </tr>
        @endif
        @endif
        @endforeach
    </table>
</div>