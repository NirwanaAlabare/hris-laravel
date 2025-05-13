<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
    <head>
    </head>
    <body>
        <table border="1">
            <tr>
                <td colspan="4">PT. NIRWANA ALABARE GARMENT</td>
            </tr>
            <tr>
                <td colspan="4">Form Penilaian Karyawan</td>
            </tr>
            <tr>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td rowspan="2" style="border:1px solid black">ID</td>
                <td rowspan="2" style="border:1px solid black">Nama Karyawan</td>
                <td rowspan="2" style="border:1px solid black">Bagian</td>
                <td rowspan="2" style="border:1px solid black">Department</td>
                <td rowspan="2" style="border:1px solid black">Kontrak Awal</td>
                <td rowspan="2" style="border:1px solid black">Kontrak Akhir</td>
                <td rowspan="2" style="border:1px solid black">Periode Penilaian</td>
                <td rowspan="2" style="border:1px solid black">I</td>
                <td rowspan="2" style="border:1px solid black">M</td>
                <td rowspan="2" style="border:1px solid black">S</td>
                <td rowspan="2" style="border:1px solid black">SP</td>
                <td colspan="2" style="border:1px solid black">Perpanjangan Kontrak</td>
                <td rowspan="2" style="border:1px solid black">Masa Kontrak (Bulan)</td>
            </tr>
            <tr>
                <td style="border:1px solid black">✔</td>
                <td style="border:1px solid black">✖</td>
            </tr>
            @php
                $grouped = collect($query)->groupBy('enroll_id');
            @endphp
            @foreach ($grouped as $enrollId => $contracts)
                @php
                    $first = $contracts->first();
                    $join_date = $first->join_date;
                    $today = date('Y-m-d');
                    $diff = abs(strtotime($today) - strtotime($join_date));
                    $years = floor($diff / (365 * 60 * 60 * 24));
                    $month = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                    $day = floor(($diff - $years * 365 * 60 * 60 * 24 - $month * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                    $periode = '';
                    if (!empty($first->contract_end_last)) {
                        $date = \Carbon\Carbon::parse($first->contract_end_last);
                        $periode = $date->format('y') . $date->format('m'); // contoh: 25 + 04 = 2504
                    }
                @endphp

                <tr>
                    {{-- Kolom Data Karyawan --}}
                    <td style="border:1px solid black">{{ $first->enroll_id }}</td>
                    <td style="border:1px solid black">{{ $first->employee_name }}</td>
                    <td style="border:1px solid black">{{ $first->sub_dept_name }}</td>
                    <td style="border:1px solid black">{{ $first->department_name }}</td>
                    <td style="border:1px solid black">
                        {{ \Carbon\Carbon::parse($first->contract_last)->translatedFormat('d F Y') }}
                    </td>
                    <td style="border:1px solid black">
                        {{ \Carbon\Carbon::parse($first->contract_end_last)->translatedFormat('d F Y') }}
                    </td>
                    <td style="border:1px solid black">
                        {{ \Carbon\Carbon::parse($first->contract)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($first->tanggal_pengurang)->translatedFormat('d F Y') }}
                    </td>

                    {{-- Masa Kerja --}}
                    <td style="border:1px solid black">{{ $first->jumlah_ijin }}</td>
                    <td style="border:1px solid black">{{ $first->jumlah_mangkir }}</td>
                    <td style="border:1px solid black">{{ $first->jumlah_sakit }}</td>
                    <td style="border:1px solid black"></td>
                    <td style="border:1px solid black"></td>
                    <td style="border:1px solid black"></td>
                    <td style="border:1px solid black"></td>
                </tr>
            @endforeach
        </table>
    </body>
</html>
