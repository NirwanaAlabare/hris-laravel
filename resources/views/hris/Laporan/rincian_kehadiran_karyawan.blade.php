
<?php $no=0; ?>
@foreach ($employee as $key=> $value)
<?php $no++; ?>
@if($no!=count($employee))
<div class="wrapper-page">
@else
<div>
@endif
	<table>
		<tr>
			<td style="font-weight: bold">PT. NIRWANA ALABARE GARMENT</td>
		</tr>
		<tr>
			<td style="font-size: 10pt">Laporan Rinci Kehadiran Karyawan</td>
		</tr>
		<tr>
			<td style="height: 10px"></td>
		</tr>
		<tr>
			<td width="520" style="border-bottom: 1px solid black;font-size: 10pt">{{$tanggal_awal_absen}} - {{$tanggal_akhir_absen}}</td>
		</tr>
	</table>
	<table class="textkecil">
		<tr>
			<td style="height: 10px"></td>
		</tr>
		<tr>
			<td width="90">NIP</td>
			<td width="160">: {{$value->nik}}</td>
			<td width="90">JABATAN</td>
			<td>: {{$value->status_jabatan}}</td>
		</tr>
		<tr>
			<td>NAMA KARYAWAN</td>
			<td>: {{$value->employee_name}}</td>
			<td>BAGIAN</td>
			<td>: {{$value->sub_dept_name}}</td>
		</tr>
		<tr>
			<td style="height: 10px"></td>
		</tr>
	</table>
	<table border="1" class="textkecilbanget">
		<tr>
			<td width="74">&nbsp;Tanggal</td>
			<td width="34">&nbsp;Hari</td>
			<td width="56">&nbsp;Keterangan</td>
			<td width="45">&nbsp;Jam masuk</td>
			<td width="45">&nbsp;Jam keluar</td>
			<td width="17">&nbsp;DT</td>
			<td width="17">&nbsp;PC</td>
			<td width="38">&nbsp;Lembur 1</td>
			<td width="38">&nbsp;Lembur 2</td>
			<td width="38">&nbsp;Lembur 3</td>
			<td width="38">&nbsp;Lembur 4</td>
			<td width="55">&nbsp;Total Lembur</td>
		</tr>
		@foreach ($value->absensi as $val)
		<?php 
		$tanggal_berjalan=Carbon\Carbon::parse($val->tanggal_berjalan)->translatedFormat('d F Y');
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
		if(isset($val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_1')[0]) && $val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_1')[0]!=0){
			$lembur1=$val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_1')[0];
		}
		$lembur2='';
		if(isset($val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_2')[0]) && $val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_2')[0]!=0){
			$lembur2=$val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_2')[0];
		}
		$lembur3='';
		if(isset($val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_3')[0]) && $val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_3')[0]!=0){
			$lembur3=$val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_3')[0];
		}
		$lembur4='';
		if(isset($val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_4')[0]) && $val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_4')[0]!=0){
			$lembur4=$val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('lembur_4')[0];
		}
		$total_lembur_1234='';
		if(isset($val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('total_lembur_1234')[0])){
			$total_lembur_1234=$val->rekap_lembur->where('tanggal_berjalan',$val['tanggal_berjalan'])->pluck('total_lembur_1234')[0];
		}
		?>
		<tr>
			<td>&nbsp;{{$tanggal_berjalan}}</td>
			<td>&nbsp;{{$val->nama_hari}}</td>
			<td>&nbsp;{{$val->status_absen}}</td>
			<td align="center">{{$absen_masuk}}</td>
			<td align="center">{{$absen_pulang}}</td>
			<td align="center">{{$jumlah_menit_absen_dt}}</td>
			<td align="center">{{$jumlah_menit_absen_pc}}</td>
			<td align="center">{{$lembur1}}</td>
			<td align="center">{{$lembur2}}</td>
			<td align="center">{{$lembur3}}</td>
			<td align="center">{{$lembur4}}</td>
			<td align="center">{{$total_lembur_1234}}</td>
		</tr>
		@endforeach
	</table>
	<table>
		<tr>
			<td style="height: 10px"></td>
		</tr>
	</table>
	<table class="textkecil">
		<tr>
			<td width="120">Hari kerja</td>
			<td>{{$jumlah_absen[$key]['hari_kerja']}}</td>
		</tr>
		<tr>
			<td>Absen</td>
			<td>{{$jumlah_absen[$key]['hari_absen']}}</td>
		</tr>
	</table>
</div>
@endforeach
<style>
	.textkecil{
		font-size: 9pt;
		font-weight: bold;
	}
	.textkecilbanget{
		font-size: 9pt;
      	border-collapse: collapse;
	}
	.wrapper-page {
		page-break-after: always;
	}

	.wrapper-page:last-child {
		page-break-after: avoid;
	}
</style>