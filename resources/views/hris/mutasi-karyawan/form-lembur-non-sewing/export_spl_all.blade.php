<!DOCTYPE html>
<html lang="en">

<table class="table">
    <tr>
        <td colspan='13'>Laporan Checking SPL</td>
    </tr>
    <tr>
        <td colspan='13'>{{ date('d-M-Y', strtotime($from)) }} - {{ date('d-M-Y', strtotime($to)) }}
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <thead>
        <tr>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">No</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">Tgl.Lembur</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">No. Form</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">Line</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">Ket</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">Enroll ID</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">NIK</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">Nama Karyawan</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">Jabatan</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">Jam Mulai Rencana</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">Jam Akhir Rencana</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">Istirahat</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">Jumlah Jam Rencana</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">Jam Awal Realisasi</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">Jam Akhir Realisasi</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold">Jumlah Jam Realisasi</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($data as $item)
            <tr>
                <td>{{ $no++ }}.</td>
                <td>{{ $item->tgl_lembur_fix }}</td>
                <td>{{ $item->no_form }}</td>
                <td>{{ $item->line }}</td>
                <td>{{ $item->ket }}</td>
                <td>{{ $item->enroll_id }}</td>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->employee_name }}</td>
                <td>{{ $item->status_jabatan }}</td>
                <td>{{ $item->jam_lembur_awal_rencana }}</td>
                <td>{{ $item->jam_lembur_akhir_rencana }}</td>
                <td>{{ $item->istirahat }}</td>
                <td>{{ $item->total_jam }}</td>
                <td>{{ $item->absen_masuk_kerja }}</td>
                <td>{{ $item->absen_pulang_kerja }}</td>
                <td>{{ $item->realisasi_lembur }}</td>
            </tr>
        @endforeach
    </tbody>

</table>

</html>
