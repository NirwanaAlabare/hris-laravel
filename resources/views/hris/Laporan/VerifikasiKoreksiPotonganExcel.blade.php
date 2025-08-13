
<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
<head>

    <style>
    table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    th {
        background-color: #f2f2f2;
    }
</style>

</head>
    <body>
        <tr>
          <td colspan="5" style="font-weight:bold; font-size: 24px;" >PT NIRWANA ALABARE GARMENT</td>

        </tr>
        <tr>
          <td colspan="5">Laporan Rekap Koreksi Potongan Karyawan</td>
        </tr>
        <tr>
          <td colspan="5"></td>
        <tr>
        </tr>
        <table style="table-layout: fixed; width: 100%;">
            <colgroup>
                <col style="width: 40px;">   <!-- No -->
                <col style="width: 100px;">  <!-- Enroll ID -->
                <col style="width: 100px;">  <!-- NIK -->
                <col style="width: 150px;">  <!-- Name -->
                <col style="width: 150px;">  <!-- Department -->
                <col style="width: 150px;">  <!-- Sub Dept -->
                <col style="width: 150px;">  <!-- Sub Dept -->
                <col style="width: 150px;">  <!-- Sub Dept -->
                <col style="width: 150px;">  <!-- Sub Dept -->
                <col style="width: 100px;">  <!-- Jumlah Potongan -->
                <col style="width: 180px;">  <!-- Jenis Potongan -->
                <col style="width: 200px;">  <!-- Keterangan -->
                <col style="width: 100px;">  <!-- Jumlah Potongan -->
            </colgroup>

          <thead>
            <tr>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">No</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">ID</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">NIK</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Nama</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Departement</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Bagian</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Jenis Koreksi</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Tanggal Koreksi</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Periode Koreksi</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Jumlah Potongan</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Jenis Potongan</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Keterangan</th>
            <th style="font-weight:bold;text-align:center;background-color:#C0C0C0;">Status Verifikasi</th>
            </tr>
          </thead>
          <tbody>
             @php
                $jenisPotongan = [
                    1 => 'POTONGAN BPJS TK',
                    2 => 'POTONGAN BPJS KS',
                    3 => 'POTONGAN BAZAR',
                    4 => 'POTONGAN KAS BON',
                    5 => 'POTONGAN LAINNYA',
                    6 => 'POTONGAN KARYAWAN',
                    7 => 'POTONGAN UPAH',
                    8 => 'POTONGAN LEMBUR',
                ];
            @endphp
            @foreach($data as $key => $value)
            <tr>
              <td>{{$key+1}}</td>
              <td>{{$value['enroll_id']}}</td>
              <td>{{$value['nik'] }}</td>
              <td>{{$value['employee_name'] }}</td>
              <td>{{$value['department_name']}}</td>
              <td>{{$value['sub_dept_name']}}</td>
              <td>{{$value['sumber']}}</td>
              <td>{{\PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($value['tanggal_koreksi'])}}</td>
              <td>{{$value['periode_tanggal_koreksi']}}</td>
              <td>{{$value['jumlah_rp_potongan']}}</td>
              <td>
                  {{ $jenisPotongan[$value['jenis_potongan']] ?? 'TIDAK DIKETAHUI' }}
                </td>

                <td>{{$value['keterangan'] }}</td>
                <td>{{$value['is_verifikasi_acc'] == 1 ? 'VERIFY' : 'NEED VERFICATION'}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      <br>
    </body>
</html>
