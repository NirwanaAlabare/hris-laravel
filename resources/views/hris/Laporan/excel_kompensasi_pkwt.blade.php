<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
    <head>
    </head>
    <body>
        <table border="1">
            <tr>
                <td colspan="6">Rekap Kompensasi PKWT</td>
            </tr>
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td rowspan="2" width="18">Aktif/ Non Aktif</td>
                <td rowspan="2" width="10">ID</td>
                <td rowspan="2" width="13">NIP</td>
                <td rowspan="2" width="20">Nama Karyawan</td>
                <td rowspan="2" width="20">Jabatan</td>
                <td rowspan="2" width="20">Bagian</td>
                <td rowspan="2" width="20">Department</td>
                <td rowspan="2" width="20">Tanggal Masuk</td>
                <td rowspan="2" width="20">Tanggal Keluar</td>
                <td colspan="3" style="text-align:center">Masa Kerja</td>
                <td rowspan="2" width="20">PKS (Hari Mulai)</td>
                <td rowspan="2" width="20">PKS Mulai</td>
                <td rowspan="2" width="20">PKS (Hari Akhir)</td>
                <td rowspan="2" width="20">PKS Akhir</td>
                <td rowspan="2" width="20">PKS (Bulan)</td>
                <td rowspan="2" width="20">Gapok</td>
                <td rowspan="2" width="20">TMK</td>
                <td rowspan="2" width="20">THP</td>
                <td rowspan="2" width="20">Kompensasi</td>
            </tr>
            <tr>
                <td style="text-align:center">T</td>
                <td style="text-align:center">B</td>
                <td style="text-align:center">H</td>
            </tr>
            @foreach ($query as $enrollId => $data_pkwt)
            @php
                $join_date = $data_pkwt->join_date;
                $today = date('Y-m-d');
                $diff = abs(strtotime($today) - strtotime($join_date));
                $years = floor($diff / (365 * 60 * 60 * 24));
                $month = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                $day = floor(($diff - $years * 365 * 60 * 60 * 24 - $month * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            @endphp
            <tr>
                <td style="">{{ $data_pkwt->status_aktif }}</td>
                <td style="">{{ $data_pkwt->enroll_id }}</td>
                <td style="">{{ $data_pkwt->nik }}</td>
                <td style="">{{ $data_pkwt->employee_name }}</td>
                <td style="">{{ $data_pkwt->status_jabatan }}</td>
                <td style="">{{ $data_pkwt->sub_dept_name }}</td>
                <td style="">{{ $data_pkwt->department_name }}</td>
                <td>{{ \Carbon\Carbon::parse($data_pkwt->join_date)->translatedFormat('d F Y') }}</td>
                <td>{{ $data_pkwt->tanggal_resign ? \Carbon\Carbon::parse($data_pkwt->tanggal_resign)->translatedFormat('d F Y') : '' }}</td>
                <td style="border:1px solid black; text-align:center">{{ $years }}</td>
                <td style="border:1px solid black; text-align:center">{{ $month }}</td>
                <td style="border:1px solid black; text-align:center">{{ $day }}</td>
                <td>{{ $data_pkwt->contract ? \Carbon\Carbon::parse($data_pkwt->contract)->translatedFormat('l') : '' }}</td>
                <td>{{ $data_pkwt->contract ? \Carbon\Carbon::parse($data_pkwt->contract)->translatedFormat('d F Y') : '' }}</td>
                <td>{{ $data_pkwt->contract_end ? \Carbon\Carbon::parse($data_pkwt->contract_end)->translatedFormat('l') : '' }}</td>
                <td>{{ $data_pkwt->contract_end ? \Carbon\Carbon::parse($data_pkwt->contract_end)->translatedFormat('d F Y') : '' }}</td>
                <td style="border:1px solid black; text-align:center">{{ $data_pkwt->jumlah_bulan }}</td>
                <td style="border:1px solid black; text-align:center">{{number_format($data_pkwt->umk, 0, '.', '.');}}</td>
                <td style="border:1px solid black; text-align:center">{{$data_pkwt->tunjangan, 0, '.', '.'}}</td>
                <td style="border:1px solid black; text-align:center">{{number_format($data_pkwt->total_penghasilan_bulanan, 0, '.', '.');}}</td>
                <td style="border:1px solid black; text-align:center">{{ number_format($data_pkwt->total_kompensasi, 0, '.', '.'); }}</td>
            </tr>
        @endforeach
        </table>
    </body>
</html>
