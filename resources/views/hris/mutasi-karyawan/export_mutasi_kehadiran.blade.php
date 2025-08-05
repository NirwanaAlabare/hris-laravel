<!DOCTYPE html>
<html lang="en">

<table class="table">
    <tr>
        <td colspan='7' style="font-weight: bold">Laporan Karyawan Absen</td>
    </tr>
    <tr>
        <td colspan='7' style="font-weight: bold">{{ date('d F Y', strtotime( $from )) }} - {{ date('d F Y', strtotime( $to )) }}
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <thead>
        <tr>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="25px">No</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="130px">Tgl Absen</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="100px">Enroll ID</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="100px">NIP</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="150px">Nama Karyawan</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="150px">Kategori</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="100px">Line Asal</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="100px">Line Sekarang</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="130px">Tgl pindah</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="130px">Status Aktif</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="130px">Status Absen</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=0;?>
        @foreach($data as $value)
        <?php
        $no++;
        ?>
        <tr>
            <td style="border:1px solid black">{{ $no }}.</td>
            <td style="border:1px solid black">{{ date('d F Y', strtotime( $value->tanggal_berjalan )) }}</td>
            <td style="border:1px solid black">{{ preg_replace('/[^\x20-\x7E]/', '', $value->enroll_id_fix) ?? '-' }}</td>
            <td style="border:1px solid black">{{ $value->nik }}</td>
            <td style="border:1px solid black">{{ $value->nm_karyawan }}</td>
            <td style="border:1px solid black">{{ $value->sewing_nonsewing }}</td>
            <td style="border:1px solid black">{{ $value->line_asal }}</td>
            <td style="border:1px solid black">{{ $value->line }}</td>
            <td style="border:1px solid black">{{ date('d F Y', strtotime( $value->tgl_pindah_fix )) }}</td>
            <td style="border:1px solid black">{{ $value->status_aktif }}</td>
            <td style="border:1px solid black">{{ $value->status_absen }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</html>
