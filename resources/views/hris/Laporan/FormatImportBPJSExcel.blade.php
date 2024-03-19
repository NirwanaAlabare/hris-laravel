
<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
<head>

</head>
    <body> 
        <table>
          <thead>
            <tr>
            <th style="font-weight:bold;text-align:center;width:10px;background-color:#C0C0C0;">NO. ABSEN</th>
            <th style="font-weight:bold;text-align:center;width:15px;background-color:#C0C0C0;">NIP</th>
            <th style="font-weight:bold;text-align:center;width:40px;background-color:#C0C0C0;">NAMA KARYAWAN</th>
            <th style="font-weight:bold;text-align:center;width:15px;background-color:#C0C0C0;">PAY BPJS TK</th>
            <th style="font-weight:bold;text-align:center;width:15px;background-color:#C0C0C0;">PAY BPJS KS</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">NOMOR BPJS(TK)</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">TANGGAL KEPESERTAAN (TK)</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">NOMOR BPJS (KS)</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">TANGGAL KEPESERTAAN (KS)</th>
            <th style="font-weight:bold;text-align:center;width:15px;background-color:#C0C0C0;">KODE GRADE</th>
            <th style="font-weight:bold;text-align:center;width:15px;background-color:#C0C0C0;">BANK</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">NOMOR REKENING</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">AKTIF / NON AKTIF</th>
            <th style="font-weight:bold;text-align:center;width:20px;background-color:#C0C0C0;">TANGGAL RESIGN</th>


            </tr>
          </thead>
          <tbody>
            @foreach($karyawan as $key => $value)
            <tr>
              <td>{{$value->enroll_id}}</td>
              <td>{{$value->nik }}</td>
              <td>{{$value->employee_name }}</td>
              <td>{{$value->status_aktif_bpjs_tk}}</td>
              <td>{{$value->status_aktif_bpjs_ks}}</td>
              <td>{{$value->nomor_bpjs_ketenagakerjaan}}</td>
              <td>{{\PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($value->tanggal_bpjs_ketenagakerjaan)}}</td>
              <td>{{$value->nomor_bpjs_kesehatan }}</td>
              <td>{{\PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($value->tanggal_bpjs_kesehatan)}}</td>
              <td>{{$value->kode_grade }}</td>
              <td>{{$value->nama_bank}}</td>
              <td>{{$value->nomor_rekening_bank}}</td>
              <td>{{$value->status_aktif}}</td>
              <td>{{\PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($value->tanggal_resign)}}</td>

            </tr>
            @endforeach
            
          </tbody>
        </table>
      <br>
    </body>
</html>