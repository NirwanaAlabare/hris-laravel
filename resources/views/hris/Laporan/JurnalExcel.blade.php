
<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
<head>

</head>
    <body> 
        <tr>
          <td colspan="5" style="font-weight:bold; font-size: 24px;" >PT NIRWANA ALABARE GARMENT</td>
        </tr>
        <tr>
          <td colspan="5">Laporan Rekap Koreksi Upah Karyawan</td>
        </tr>
        <tr>
          <td colspan="5">Periode : {{$data->first()->periode_payroll}}</td>
        <tr>

        <table>
          <thead>
            <tr>
              <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">Kode Bagian</th>
              <th style="font-weight:bold;text-align:center;width:30px;background-color:#C0C0C0;">Nama Bagian</th>
              <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">Gaji</th>
              <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">Tunjangan</th>
              <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">Lembur</th>
              <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">Bonus</th>
              <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">Piutang Karyawan</th>
              <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">Piutang Karyawan Bazzar</th>
              <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">BPJS TK</th>
              <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">BPJS KS</th>
              <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">Potongan</th>
              <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">Gaji Neto</th>
              <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">Jumlah Karyawan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $key => $value)
            <tr>
              <td>{{$value->kode_bagian}}</td>
              <td>{{$value->nama_bagian}}</td>
              <td>{{$value->gaji}}</td>
              <td>{{$value->tunjangan_karyawan_rupiah}}</td>
              <td>{{$value->total_lembur_rupiah}}</td>
              <td>{{$value->bonus}}</td>
              <td>{{$value->piutang_karyawan}}</td>
              <td>{{$value->piutang_bazzar}}</td>
              <td>{{$value->bpjs_tk}}</td>
              <td>{{$value->bpjs_ks}}</td>
              <td>{{$value->potongan}}</td>
              <td>{{$value->gaji_neto}}</td>
              <td>{{$value->jumlah_karyawn}}</td>
            </tr>
            @endforeach
            
          </tbody>
        </table>
      <br>
    </body>
</html>