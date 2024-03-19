
<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
<head>

</head>
    <body> 
        <tr>
          <td colspan="21" style="font-weight:bold; font-size: 24px;" >PT NIRWANA ALABARE GARMENT</td>
          <td colspan="2">Attendance Data </td>
          <td >{{$info['tanggal_update']}} </td>
          <td >No of Days</td>
          <td >{{$info['jumlah_hari']}}</td>

        </tr>
        <tr>
          <td colspan="21">Laporan Estimasi Payroll </td>
          <td colspan="2">Start Period Date</td>
          <td >{{$info['tanggal_awal']}} </td>
          <td >No of WD</td>
          <td >{{$info['jumlah_hari_kerja']}}</td>

        </tr>
        <tr>
          <td colspan="21">Periode : {{$info['periode_payroll']}}</td>
          <td colspan="2">End Period Date</td>
          <td >{{$info['tanggal_akhir']}} </td>
          <td >Remaining WD</td>
          <td >{{$info['sisa_hari_kerja']}}</td>

        <tr>
        </tr>
        <table>
          <thead>
            <tr>
            <th style="font-weight:bold;text-align:center;width:10px;background-color:#C0C0C0;">Nomor Absen</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">NIK</th>
            <th style="font-weight:bold;text-align:center;width:40px;background-color:#C0C0C0;">Nama Karyawan</th>
            <th style="font-weight:bold;text-align:center;width:30px;background-color:#C0C0C0;">Bagian</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">Aktif</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">Staff</th>
            <th style="font-weight:bold;text-align:center;width:7px;background-color:#C0C0C0;">IBY</th>
            <th style="font-weight:bold;text-align:center;width:7px;background-color:#C0C0C0;">ITB</th>
            <th style="font-weight:bold;text-align:center;width:7px;background-color:#C0C0C0;">LBY</th>
            <th style="font-weight:bold;text-align:center;width:7px;background-color:#C0C0C0;">LSM</th>
            <th style="font-weight:bold;text-align:center;width:7px;background-color:#C0C0C0;">DT</th>
            <th style="font-weight:bold;text-align:center;width:7px;background-color:#C0C0C0;">PC</th>
            <th style="font-weight:bold;text-align:center;width:7px;background-color:#C0C0C0;">DTPC</th>
            <th style="font-weight:bold;text-align:center;width:7px;background-color:#C0C0C0;">M</th>
            <th style="font-weight:bold;text-align:center;width:7px;background-color:#C0C0C0;">R</th>
            <th style="font-weight:bold;text-align:center;width:7px;background-color:#C0C0C0;">TK</th>
            <th style="font-weight:bold;text-align:center;width:7px;background-color:#C0C0C0;">OK</th>
            <th style="font-weight:bold;text-align:center;width:7px;background-color:#C0C0C0;">Total</th>
            <th style="font-weight:bold;text-align:center;width:7px;background-color:#C0C0C0;">Total Net</th>
            <th style="font-weight:bold;text-align:center;width:25px;background-color:#C0C0C0;">Perubahan Terakhir</th>
            <th ></th>
            <th style="font-weight:bold;text-align:center;width:15px;background-color:#C0C0C0;">ACT HK</th>
            <th style="font-weight:bold;text-align:center;width:15px;background-color:#C0C0C0;">EST REM HK</th>
            <th style="font-weight:bold;text-align:center;width:10px;background-color:#C0C0C0;">TOTAL</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">UMK</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">GROSS SALARY</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">SENIORITY ALLOWANCE</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">OVERTIME</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">BPJS TK</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">BPJS KS</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">POT JAM KERJA</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">EST TAKE HOME PAY</th>

            </tr>
          </thead>
          <tbody>
            @foreach($data as $key => $value)
            <tr>
              <td>{{$value['enroll_id']}}</td>
              <td>{{$value['nik']}}</td>
              <td>{{$value['employee_name']}}</td>
              <td>{{$value['nama_bagian']}}</td>
              <td>{{$value['aktif_karyawan']}}</td>
              <td>{{$value['kategori_karyawan']}}</td>
              <td>{{$value['kehadiran_iby']}}</td>
              <td>{{$value['kehadiran_itb']}}</td>
              <td>{{$value['kehadiran_lby']}}</td>
              <td>{{$value['kehadiran_lsm']}}</td>
              <td>{{$value['kehadiran_dt']}}</td>
              <td>{{$value['kehadiran_pc']}}</td>
              <td>{{$value['kehadiran_dtpc']}}</td>
              <td>{{$value['kehadiran_m']}}</td>
              <td>{{$value['kehadiran_r']}}</td>
              <td>{{$value['kehadiran_tk']}}</td>
              <td>{{$value['kehadiran_ok']}}</td>
              <td>{{$value['total_kehadiran']}}</td>
              <td>{{$value['total_kehadiran_net']}}</td>
              <td>{{$value['updated_at']}}</td>
              <td></td>
              <td>{{$value['total_kehadiran_net']}}</td>
              <td>{{$value['kehadiran_m_estimasi']}}</td>
              <td>{{$value['total_estimasi']}}</td>
              <td>{{$value['upah_per_bulan']}}</td>
              <td>{{$value['gross_salary']}}</td>
              <td>{{$value['tunjangan_karyawan_rupiah']}}</td>
              <td>{{$value['total_lembur_rupiah']}}</td>
              <td>{{$value['total_bpjs_tk']}}</td>
              <td>{{$value['total_bpjs_ks']}}</td>
              <td>{{$value['rp_pot_jam']}}</td>
              <td>{{$value['estimasi_thp']}}</td>
            </tr>
            @endforeach
            
          </tbody>
        </table>
      <br>
    </body>
</html>