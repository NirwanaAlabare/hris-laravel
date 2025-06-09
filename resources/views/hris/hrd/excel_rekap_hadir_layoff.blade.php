<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
    <head>
    </head>
    <body>
        <table border="1">
            <tr>
                <td colspan="6">Rekap Layoff Karyawan {{Carbon\Carbon::parse(date('Y-m-d'))->translatedFormat('d F Y')}}</td>
            </tr>
            <tr>
                <td rowspan="1" style="border:1px solid black">Staff / Non Staff</td>
                <td rowspan="1" style="border:1px solid black">NO ID</td>
                <td rowspan="1" style="border:1px solid black">NIP</td>
                <td rowspan="1" style="border:1px solid black">Nama</td>
                <td rowspan="1" style="border:1px solid black">Jabatan</td>
                <td rowspan="1" style="border:1px solid black">Bagian</td>
                <td rowspan="1" style="border:1px solid black">Department</td>
                <td rowspan="1" style="border:1px solid black">Tanggal Mulai</td>
                <td rowspan="1" style="border:1px solid black">Tanggal Akhir</td>
                <td rowspan="1" style="border:1px solid black">Jumlah Mangkir</td>
                <td rowspan="1" style="border:1px solid black">Rekomendasi</td>
            </tr>
        @foreach ($hasil as $enrollId => $contracts)
            <tr>
                <td style="border:1px solid black">{{ $contracts['status_staff'] }}</td>
                <td style="border:1px solid black">{{ $contracts['enroll_id'] }}</td>
                <td style="border:1px solid black">{{ $contracts['nik'] }}</td>
                <td style="border:1px solid black">{{ $contracts['employee_name'] }}</td>
                <td style="border:1px solid black">{{ $contracts['status_jabatan'] }}</td>
                <td style="border:1px solid black">{{ $contracts['sub_dept_name'] }}</td>
                <td style="border:1px solid black">{{ $contracts['department_name'] }}</td>
                <td style="border:1px solid black">{{ $contracts['mulai'] }}</td>
                <td style="border:1px solid black">{{ $contracts['selesai'] }}</td>
                <td style="border:1px solid black">{{ $contracts['jumlah_hari_mangkir'] }}</td>
                <td style="border:1px solid black">{{ $contracts['kategori'] }}</td>
            </tr>
        @endforeach
        </table>
    </body>
</html>
