<!DOCTYPE html>
<html lang="en">

<table class="table">
    <tr>
        <td colspan='10'>OVERTIME KARYAWAN</td>
    </tr>
    <tr>
        <td colspan='10'></td>
    </tr>
    <tr>
        <td colspan='10'></td>
    </tr>
    <thead>
        <tr>
            <th style="background-color: pink;border:1px solid black;font-weight:bold">No</th>
            <th style="background-color: pink;border:1px solid black;font-weight:bold">Tanggal</th>
            <th style="background-color: pink;border:1px solid black;font-weight:bold">Nama Karyawan</th>
            <th style="background-color: pink;border:1px solid black;font-weight:bold">NIP</th>
            <th style="background-color: pink;border:1px solid black;font-weight:bold">Jabatan</th>
            <th style="background-color: pink;border:1px solid black;font-weight:bold">Keterangan</th>
            <th style="background-color: pink;border:1px solid black;font-weight:bold">Dari</th>
            <th style="background-color: pink;border:1px solid black;font-weight:bold">Sampai</th>
            <th style="background-color: pink;border:1px solid black;font-weight:bold">Istirahat</th>
            <th style="background-color: pink;border:1px solid black;font-weight:bold">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($data as $item)
            <tr>
                <td>{{ $no++ }}.</td>
                <td>{{ $item->tgl_lembur }}</td>
                <td>{{ $item->employee_name }}</td>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->status_jabatan }}</td>
                <td>{{ $item->ket }}</td>
                <td>{{ $item->jam_lembur_awal_rencana }}</td>
                <td>{{ $item->jam_lembur_akhir_rencana }}</td>
                <td>{{ $item->istirahat }}</td>
                <td>{{ $item->total_jam }}</td>
            </tr>
        @endforeach
    </tbody>

</table>

</html>
