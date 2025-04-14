@foreach ($employee as $key=> $value)
<div>
	<table>
		<tr>
			<td style="font-weight: bold;font-size:13pt" colspan="12">PT. NIRWANA ALABARE GARMENT</td>
		</tr>
		<tr>
			<td style="font-size: 11pt; font-weight: bold" colspan="12">Laporan Rekap Cuti Karyawan</td>
		</tr>
		<tr>
			<td style="height: 12px" colspan="12"></td>
		</tr>
	</table>
	<table>
		<tr>
			<td style="font-weight: bold">NIP</td>
			<td style="font-weight: bold" colspan="4">: {{$value->nik}}</td>
			<td style="font-weight: bold" colspan="3">JABATAN</td>
			<td style="font-weight: bold">: {{$value->status_jabatan}}</td>
		</tr>
		<tr>
			<td style="font-weight: bold">NAMA KARYAWAN</td>
			<td style="font-weight: bold" colspan="4">: {{$value->employee_name}}</td>
			<td style="font-weight: bold" colspan="3">BAGIAN</td>
			<td style="font-weight: bold">: {{$value->sub_dept_name}}</td>
		</tr>
	</table>
	<table>
		<tr>
			<td width="18" style="font-weight: bold;border:2px solid black;">&nbsp;Tanggal</td>
			<td width="8" style="font-weight: bold;border:2px solid black;">&nbsp;Hari</td>
			<td width="13" style="font-weight: bold;border:2px solid black;">&nbsp;Keterangan</td>
			<td width="11" style="font-weight: bold;border:2px solid black;">&nbsp;Jam masuk</td>
			<td width="11" style="font-weight: bold;border:2px solid black;">&nbsp;Jam keluar</td>
			<td width="4" style="font-weight: bold;border:2px solid black;">&nbsp;DT</td>
			<td width="4" style="font-weight: bold;border:2px solid black;">&nbsp;PC</td>
			<td width="10" style="font-weight: bold;border:2px solid black;">&nbsp;Lembur 1</td>
			<td width="10" style="font-weight: bold;border:2px solid black;">&nbsp;Lembur 2</td>
			<td width="10" style="font-weight: bold;border:2px solid black;">&nbsp;Lembur 3</td>
			<td width="10" style="font-weight: bold;border:2px solid black;">&nbsp;Lembur 4</td>
			<td width="13" style="font-weight: bold;border:2px solid black;">&nbsp;Total Lembur</td>
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
			<td style="border:2px solid black;">&nbsp;{{$tanggal_berjalan}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->nama_hari}}</td>
			<td style="border:2px solid black;">&nbsp;{{$val->status_absen}}</td>
			<td style="border:2px solid black;" align="center">{{$absen_masuk}}</td>
			<td style="border:2px solid black;" align="center">{{$absen_pulang}}</td>
			<td style="border:2px solid black;" align="center">{{$jumlah_menit_absen_dt}}</td>
			<td style="border:2px solid black;" align="center">{{$jumlah_menit_absen_pc}}</td>
			<td style="border:2px solid black;" align="center">{{$lembur1}}</td>
			<td style="border:2px solid black;" align="center">{{$lembur2}}</td>
			<td style="border:2px solid black;" align="center">{{$lembur3}}</td>
			<td style="border:2px solid black;" align="center">{{$lembur4}}</td>
			<td style="border:2px solid black;" align="center">{{$total_lembur_1234}}</td>
		</tr>
		@endforeach
		<tr>
			<td colspan="5" style="background-color:#fffc04;font-weight:bold;border:2px solid black">GRAND TOTAL</td>
			<td style="background-color:#fffc04;font-weight:bold;text-align:center;border:2px solid black">@if($jumlah_menit[$key]['total_menit_absen_dt']==0) - @else {{$jumlah_menit[$key]['total_menit_absen_dt']}} @endif</td>
			<td style="background-color:#fffc04;font-weight:bold;text-align:center;border:2px solid black">@if($jumlah_menit[$key]['total_menit_absen_pc']==0) - @else {{$jumlah_menit[$key]['total_menit_absen_pc']}} @endif</td>
			<td style="background-color:#fffc04;font-weight:bold;text-align:center;border:2px solid black">@if($jumlah_menit[$key]['total_menit_lembur_1']==0) - @else {{$jumlah_menit[$key]['total_menit_lembur_1']}} @endif</td>
			<td style="background-color:#fffc04;font-weight:bold;text-align:center;border:2px solid black">@if($jumlah_menit[$key]['total_menit_lembur_2']==0) - @else {{$jumlah_menit[$key]['total_menit_lembur_2']}} @endif</td>
			<td style="background-color:#fffc04;font-weight:bold;text-align:center;border:2px solid black">@if($jumlah_menit[$key]['total_menit_lembur_3']==0) - @else {{$jumlah_menit[$key]['total_menit_lembur_3']}} @endif</td>
			<td style="background-color:#fffc04;font-weight:bold;text-align:center;border:2px solid black">@if($jumlah_menit[$key]['total_menit_lembur_4']==0) - @else {{$jumlah_menit[$key]['total_menit_lembur_4']}} @endif</td>
			<td style="background-color:#fffc04;font-weight:bold;text-align:center;border:2px solid black">@if($jumlah_menit[$key]['total_menit_lembur_1234']==0) - @else {{$jumlah_menit[$key]['total_menit_lembur_1234']}} @endif</td>
		</tr>
	</table>
	<table>
		<tr>
			<td>Hari kerja</td>
			<td>{{$jumlah_absen[$key]['hari_kerja']}}</td>
		</tr>
		<tr>
			<td>Absen</td>
			<td>{{$jumlah_absen[$key]['hari_absen']}}</td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td></td>
		</tr>
	</table>
</div>
@endforeach
