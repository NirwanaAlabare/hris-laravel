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
                <td colspan="4">RENCANA ADJUSMENT GRADE</td>
            </tr>
            <tr>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td rowspan="2" style="border:1px solid black">ID</td>
                <td rowspan="2" style="border:1px solid black">Nama Karyawan</td>
                <td rowspan="2" style="border:1px solid black">Bagian</td>
                <td rowspan="2" style="border:1px solid black">TMK</td>
                <td rowspan="2" style="border:1px solid black">Periode Penilaian</td>
                <td colspan="4" style="border:1px solid black">Absensi</td>
                <td rowspan="2" style="border:1px solid black">Penilaian Kinerja</td>
                <td rowspan="2" style="border:1px solid black; height: 50px">Tanggung jawab terhadap tugas dan tanggung jawab yang di berikan</td>
                <td rowspan="2" style="border:1px solid black">Inisiatif dan Kerjasama</td>
                <td rowspan="2" style="border:1px solid black">Akurasi dalam pekerjaan</td>
                <td rowspan="2" style="border:1px solid black">Kemauan dan kegigihan dalm mencapai Tujuan</td>
                <td rowspan="2" style="border:1px solid black">Penyampaian dan Penerimaan Informasi</td>
                <td rowspan="2" style="border:1px solid black">Attitued / Sikap Kerja</td>
                <td rowspan="2" style="border:1px solid black">Hasil Penilaian</td>
                <td rowspan="2" style="border:1px solid black">Grade Sebelumnya</td>
                <td rowspan="2" style="border:1px solid black">Adjusment Grade</td>
            </tr>
            <tr>
                <td style="border:1px solid black">I</td>
                <td style="border:1px solid black">M</td>
                <td style="border:1px solid black">S</td>
                <td style="border:1px solid black">SP</td>
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
                    <td style="border:1px solid black">{{  \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($first->contract_last)}}</td>
                    <td style="border:1px solid black">{{ \Carbon\Carbon::parse($first->tanggal_periode_awal)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($first->tanggal_periode_akhir)->translatedFormat('d F Y') }}</td>
                    {{-- Masa Kerja --}}
                    <td style="border:1px solid black">{{ $first->jumlah_ijin }}</td>
                    <td style="border:1px solid black">{{ $first->jumlah_mangkir }}</td>
                    <td style="border:1px solid black">{{ $first->jumlah_sakit }}</td>
                    <td style="border:1px solid black"></td>
                    <td style="border:1px solid black"></td>
                    <td style="border:1px solid black"></td>
                    <td style="border:1px solid black"></td>
                    <td style="border:1px solid black"></td>
                    <td style="border:1px solid black"></td>
                    <td style="border:1px solid black"></td>
                    <td style="border:1px solid black"></td>
                    <td style="border:1px solid black"></td>
                    <td style="border:1px solid black; font-weight: bold">{{$first->kode_grade}}</td>
                    <td style="border:1px solid black"></td>
                </tr>
            @endforeach
        </table>
    </body>
</html>
