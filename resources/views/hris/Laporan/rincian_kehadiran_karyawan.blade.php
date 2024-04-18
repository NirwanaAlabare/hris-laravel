<html>
	<head>
	</head>
	<body>
		@foreach ($employee as $value)
		<table>
			<tr>
				<td>PT. NIRWANA ALABARE GARMENT</td>
			</tr>
			<tr>
				<td>Laporan Rinci Kehadiran Karyawan</td>
			</tr>
			<tr>
				<td style="height: 10px"></td>
			</tr>
			<tr>
				<td width="520" style="border-bottom: 1px solid black;">{{$tanggal_awal_absen}} - {{$tanggal_akhir_absen}}</td>
			</tr>
		</table>
		<table class="textkecil">
			<tr>
				<td width="90">NIP</td>
				<td width="160">{{$value->nik}}</td>
				<td width="90">JABATAN</td>
				<td>{{$value->status_staff}}</td>
			</tr>
			<tr>
				<td>NAMA KARYAWAN</td>
				<td>{{$value->employee_name}}</td>
				<td>BAGIAN</td>
				<td>{{$value->sub_dept_name}}</td>
			</tr>
		</table>
		<table border="1" class="textkecil">
			<tr>
				<td>Tanggal</td>
				<td>Hari</td>
				<td>Keterangan</td>
				<td>Jam masuk</td>
				<td>Jam keluar</td>
				<td>DT</td>
				<td>PC</td>
				<td>Lembur 1</td>
				<td>Lembur 2</td>
				<td>Lembur 3</td>
				<td>Lembur 4</td>
				<td>Total jam lembur</td>
			</tr>
			@foreach ($value->absensi as $val)
			<?php 
			$absen_masuk=substr($val->absen_masuk_kerja,0,5);
			$absen_pulang=substr($val->absen_pulang_kerja,0,5);
			$jumlah_menit_absen_dt='';
			if($val->jumlah_menit_absen_dt!=0){
				$jumlah_menit_absen_dt=$val->jumlah_menit_absen_dt;
			}
			$jumlah_menit_absen_pc='';
			if($val->jumlah_menit_absen_pc!=0){
				$jumlah_menit_absen_pc=$val->jumlah_menit_absen_pc;
			}
			$lembur1='';
			if(isset($val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_1')[0])){
				$lembur1=$val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_1')[0];
			}
			$lembur2='';
			if(isset($val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_2')[0])){
				$lembur2=$val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_2')[0];
			}
			$lembur3='';
			if(isset($val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_3')[0])){
				$lembur3=$val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_3')[0];
			}
			$lembur4='';
			if(isset($val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_4')[0])){
				$lembur4=$val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_4')[0];
			}
			$total_lembur_1234='';
			if(isset($val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('total_lembur_1234')[0])){
				$total_lembur_1234=$val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('total_lembur_1234')[0];
			}
			?>
			<tr>
				<td>{{$val->tanggal_berjalan}}</td>
				<td>{{$val->nama_hari}}</td>
				<td>{{$val->status_absen}}</td>
				<td>{{$absen_masuk}}</td>
				<td>{{$absen_pulang}}</td>
				<td>{{$jumlah_menit_absen_dt}}</td>
				<td>{{$jumlah_menit_absen_pc}}</td>
				<td>{{$lembur1}}</td>
				<td>{{$lembur2}}</td>
				<td>{{$lembur3}}</td>
				<td>{{$lembur4}}</td>
				<td>{{$total_lembur_1234}}</td>
			</tr>
			@endforeach
		</table>
		@endforeach
	</body>
</html>
<style>
	.textkecil{
		font-size: 9pt;
	}
</style>