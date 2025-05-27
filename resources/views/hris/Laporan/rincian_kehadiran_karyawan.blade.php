
<?php $no=0; ?>
@foreach ($employee as $key=> $value)
<?php $no++; ?>
@if($no!=count($employee))
<div class="wrapper-page">
@else
<div>
@endif
	<table style="font-family: Arial, Helvetica, sans-serif;letter-spacing: 1px; width: 920px; margin: 0 auto;">
		<tr>
			<td style="font-weight: bold">PT. NIRWANA ALABARE GARMENT</td>
		</tr>
		<tr>
			<td style="font-size: 10pt;font-family: Arial, Helvetica, sans-serif; letter-spacing: 1px;">Laporan Rinci Kehadiran Karyawan</td>
		</tr>
		<tr>
			<td style="height: 10px"></td>
		</tr>
		<tr>
			<td width="520" style="border-bottom: 1px solid black;font-size: 10pt">{{$tanggal_awal_absen}} - {{$tanggal_akhir_absen}}</td>
		</tr>
	</table>
	<table class="textkecil" style="font-size: 10pt;font-family: Arial, Helvetica, sans-serif; letter-spacing: 1px; width: 920px; margin: 0 auto;">
		<tr>
			<td style="height: 10px"></td>
		</tr>
		<tr>
			<td width="90">NIP</td>
			<td width="90">: {{$value->nik}}</td>
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
	<table border="1" class="textkecilbanget" style="font-size: 10pt;font-family: Arial, Helvetica, sans-serif; letter-spacing: 1px; width: 100%; margin: 0 auto;">
		<tr>
			<td height="30" width="120">&nbsp;Tanggal</td>
			<td width="90">&nbsp;Hari</td>
			<td >&nbsp;Keterangan</td>
			<td >&nbsp;Jam masuk</td>
			<td >&nbsp;Jam keluar</td>
			<td >&nbsp;DT</td>
			<td >&nbsp;PC</td>
			<td >&nbsp;Lembur 1</td>
			<td >&nbsp;Lembur 2</td>
			<td >&nbsp;Lembur 3</td>
			<td >&nbsp;Lembur 4</td>
			<td >&nbsp;Total Lembur</td>
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
			<td height="30">&nbsp;{{$tanggal_berjalan}}</td>
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
		<tr>
			<td colspan="5" height="30" style="background-color:#fffc04;font-weight:bold">GRAND TOTAL</td>
			<td style="background-color:#fffc04;font-weight:bold;text-align:center">@if($jumlah_menit[$key]['total_menit_absen_dt']==0) - @else {{$jumlah_menit[$key]['total_menit_absen_dt']}} @endif</td>
			<td style="background-color:#fffc04;font-weight:bold;text-align:center">@if($jumlah_menit[$key]['total_menit_absen_pc']==0) - @else {{$jumlah_menit[$key]['total_menit_absen_pc']}} @endif</td>
			<td style="background-color:#fffc04;font-weight:bold;text-align:center">@if($jumlah_menit[$key]['total_menit_lembur_1']==0) - @else {{$jumlah_menit[$key]['total_menit_lembur_1']}} @endif</td>
			<td style="background-color:#fffc04;font-weight:bold;text-align:center">@if($jumlah_menit[$key]['total_menit_lembur_2']==0) - @else {{$jumlah_menit[$key]['total_menit_lembur_2']}} @endif</td>
			<td style="background-color:#fffc04;font-weight:bold;text-align:center">@if($jumlah_menit[$key]['total_menit_lembur_3']==0) - @else {{$jumlah_menit[$key]['total_menit_lembur_3']}} @endif</td>
			<td style="background-color:#fffc04;font-weight:bold;text-align:center">@if($jumlah_menit[$key]['total_menit_lembur_4']==0) - @else {{$jumlah_menit[$key]['total_menit_lembur_4']}} @endif</td>
			<td style="background-color:#fffc04;font-weight:bold;text-align:center">@if($jumlah_menit[$key]['total_menit_lembur_1234']==0) - @else {{$jumlah_menit[$key]['total_menit_lembur_1234']}} @endif</td>
		</tr>
	</table>
	<table>
		<tr>
			<td style="height: 10px"></td>
		</tr>
	</table>
	<table class="textkecil" style="font-size: 10pt;font-family: Arial, Helvetica, sans-serif; letter-spacing: 1px;">
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
		font-size: 10pt;
		font-weight: bold;
	}
	.textkecilbanget{
		font-size: 9pt;
      	border-collapse: collapse;
        border: 1px solid rgb(173, 173, 173);
	}
	.wrapper-page {
		page-break-after: always;
	}

	.wrapper-page:last-child {
		page-break-after: avoid;
	}
</style>
