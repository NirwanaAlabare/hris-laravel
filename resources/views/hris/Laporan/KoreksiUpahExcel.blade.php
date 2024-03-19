
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
          <td colspan="5">Periode : {{$DataKoreksiUpah->first()->periode_tanggal_koreksi}}</td>
        <tr>
        </tr>
        <table>
          <thead>
            <tr>
            <th style="font-weight:bold;text-align:center;width:5px;background-color:#C0C0C0;">No</th>
            <th style="font-weight:bold;text-align:center;width:5px;background-color:#C0C0C0;">ID</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">NIK</th>
            <th style="font-weight:bold;text-align:center;width:40px;background-color:#C0C0C0;">Nama</th>
            <th style="font-weight:bold;text-align:center;width:30px;background-color:#C0C0C0;">Departement</th>
            <th style="font-weight:bold;text-align:center;width:30px;background-color:#C0C0C0;">Bagian</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">Jumlah Koreksi</th>
            <th style="font-weight:bold;text-align:center;width:30px;background-color:#C0C0C0;">Jenis Koreksi</th>
            <th style="font-weight:bold;text-align:center;width:50px;background-color:#C0C0C0;">Keterangan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($DataKoreksiUpah as $key => $value)
            <tr>
              <td>{{ $key+1}}</td>
              <td>{{$value->enroll_id}}</td>
              <td>{{$value->nik }}</td>
              <td>{{$value->employee_name }}</td>
              <td>{{$value->department_name}}</td>
              <td>{{$value->sub_dept_name}}</td>
              <td>{{$value->jumlah_rp_potongan}}</td>
              <td> {{ $value->jenis_koreksi == 1 ? 'KOREKSI UPAH' : 'KOREKSI INSENTIF' }}</td>
              <td>{{$value->keterangan }}</td>
        
            </tr>
            @endforeach
          </tbody>
        </table>
      <br>
    </body>
</html>