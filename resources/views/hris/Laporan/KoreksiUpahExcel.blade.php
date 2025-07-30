
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
            <colgroup>
                <col style="width: 40px;">   <!-- No -->
                <col style="width: 100px;">  <!-- Enroll ID -->
                <col style="width: 100px;">  <!-- NIK -->
                <col style="width: 150px;">  <!-- Name -->
                <col style="width: 150px;">  <!-- Department -->
                <col style="width: 150px;">  <!-- Sub Dept -->
                <col style="width: 100px;">  <!-- Jumlah Potongan -->
                <col style="width: 180px;">  <!-- Jenis Potongan -->
                <col style="width: 200px;">  <!-- Keterangan -->
            </colgroup>
          <thead>
            <tr>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">No</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">ID</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">NIK</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Nama</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Departement</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Bagian</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Jumlah Koreksi</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Jenis Koreksi</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Keterangan</th>
            </tr>
          </thead>
          <tbody>
            @php
                $jenisKoreksi = [
                    1 => 'KOREKSI UPAH',
                    2 => 'KOREKSI INSENTIF JABATAN',
                    3 => 'KOREKSI LEMBUR',
                    4 => 'KOREKSI INSENTIF LAINNYA',
                ];
            @endphp
            @foreach($DataKoreksiUpah as $key => $value)
            <tr>
              <td>{{ $key+1}}</td>
              <td>{{$value->enroll_id}}</td>
              <td>{{$value->nik }}</td>
              <td>{{$value->employee_name }}</td>
              <td>{{$value->department_name}}</td>
              <td>{{$value->sub_dept_name}}</td>
              <td>{{$value->jumlah_rp_potongan}}</td>
              <td>{{ $jenisKoreksi[$value->jenis_koreksi] ?? 'TIDAK DIKETAHUI' }}</td>
              <td>{{$value->keterangan }}</td>

            </tr>
            @endforeach
          </tbody>
        </table>
      <br>
    </body>
</html>
