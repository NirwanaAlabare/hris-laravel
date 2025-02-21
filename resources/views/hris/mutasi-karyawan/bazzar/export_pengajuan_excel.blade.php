<!DOCTYPE html>
<html lang="en">

<table class="table">
    <tr>
        <td colspan='7' style="font-weight: bold">Laporan Pengajuan Kupon Karyawan</td>
    </tr>
    <tr>
        <td colspan='7' style="font-weight: bold">
            {{-- {{ date('d F Y', strtotime( $from )) }} - {{ date('d F Y', strtotime( $to )) }} --}}
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <thead>
        <tr>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="25px">No</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="100px">TGL PENGAJUAN</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="100px">NOMOR VOUCHER</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="100px">ID</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="150px">NAMA KARYAWAN</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="150px">BAGIAN</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="100px">STAFF/ NON STAFF</th>
            <th style="background-color: yellow;border:1px solid black;font-weight:bold" width="130px">JUMLAH</th>
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
            <td style="border:1px solid black">{{ \Carbon\Carbon::parse($value->created_at)->format('Y-m-d') }}</td>
            <td style="border:1px solid black">{{ $value->nomor_voucher }}</td>
            <td style="border:1px solid black">{{ $value->enroll_id }}</td>
            <td style="border:1px solid black">{{ $value->employee_name }}</td>
            <td style="border:1px solid black">{{ $value->sub_dept_name }}</td>
            <td style="border:1px solid black">{{ $value->status_staff }}</td>
            <td style="border:1px solid black">{{ $value->nominal}}</td>

        </tr>
        @endforeach
    </tbody>
</table>
</html>


