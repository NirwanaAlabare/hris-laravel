<!DOCTYPE html>
<html lang="en">

<table class="table">
    <tr>
        <td colspan='6' style="font-size:15pt;font-weight:bold;font-family:Arial, Helvetica, sans-serif">PT. NIRWANA  ALABARE GARMENT</td>
    </tr>
    <tr>
        <td colspan='6'>Laporan Insentif</td>
    </tr>
    <tr>
        <?php 
        $dari=Carbon\Carbon::parse($from)->translatedFormat('d F Y');
        $sampai=Carbon\Carbon::parse($to)->translatedFormat('d F Y');
        ?>
        <td colspan='6'>{{$dari}} - {{ $sampai }}
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td ></td>
    </tr>
    <thead>
        <tr>
            <th style="border:1px solid black;font-weight:bold" align="center">Tanggal</th>
            <th style="border:1px solid black;font-weight:bold" align="center">ID</th>
            <th style="border:1px solid black;font-weight:bold" align="center">NIK</th>
            <th style="border:1px solid black;font-weight:bold" align="center">Nama Karyawan</th>
            <th style="border:1px solid black;font-weight:bold" align="center">Department</th>
            <th style="border:1px solid black;font-weight:bold" align="center">Jumlah Insentif</th>
            <th style="border:1px solid black;font-weight:bold" align="center">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td style="border:1px solid black">{{PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($item->tgl_lembur)}}</td>
                <td style="border:1px solid black" align="center">{{ $item->enroll_id }}</td>
                <td style="border:1px solid black">{{ $item->nik }}</td>
                <td style="border:1px solid black">{{ $item->employee_name }}</td>
                <td style="border:1px solid black">{{ $item->bagian }}</td>
                <td style="border:1px solid black" align="center">{{ $item->insentif }}</td>
                <td style="border:1px solid black">Insentif</td>
            </tr>
        @endforeach
    </tbody>

</table>

</html>
