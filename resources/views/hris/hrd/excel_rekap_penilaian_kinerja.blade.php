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
                 <td rowspan="1" style="border:1px solid black">Aktif/NA</td>
                <td rowspan="1" style="border:1px solid black">ID</td>
                <td rowspan="1" style="border:1px solid black">NIP</td>
                <td rowspan="1" style="border:1px solid black">Nama Karyawan</td>
                <td rowspan="1" style="border:1px solid black">Jabatan</td>
                <td rowspan="1" style="border:1px solid black">Bagian</td>
                <td rowspan="1" style="border:1px solid black">Department</td>
                <td rowspan="1" style="border:1px solid black">Join Date</td>
                <td rowspan="1" style="border:1px solid black">Out Date</td>
                <td rowspan="1" style="border:1px solid black">T</td>
                <td rowspan="1" style="border:1px solid black">B</td>
                <td rowspan="1" style="border:1px solid black">H</td>
                <td rowspan="1" style="border:1px solid black">Penilaian Kinerja</td>
                <td rowspan="1" style="border:1px solid black; height: 50px">Tanggung jawab terhadap tugas dan tanggung jawab yang di berikan</td>
                <td rowspan="1" style="border:1px solid black">Inisiatif dan Kerjasama</td>
                <td rowspan="1" style="border:1px solid black">Akurasi dalam pekerjaan</td>
                <td rowspan="1" style="border:1px solid black">Kemauan dan kegigihan dalm mencapai Tujuan</td>
                <td rowspan="1" style="border:1px solid black">Penyampaian dan Penerimaan Informasi</td>
                <td rowspan="1" style="border:1px solid black">Attitued / Sikap Kerja</td>
                <td rowspan="1" style="border:1px solid black">Rata-Rata Penilaian Kompetensi </td>
                <td rowspan="1" style="border:1px solid black">Pengurang</td>
                <td rowspan="1" style="border:1px solid black">Nilai Akhir</td>
                <td rowspan="1" style="border:1px solid black">Tgl Habis Kontrak</td>
                <td rowspan="1" style="border:1px solid black">Day Start</td>
                <td rowspan="1" style="border:1px solid black">Start</td>
                <td rowspan="1" style="border:1px solid black">Day Finish</td>
                <td rowspan="1" style="border:1px solid black">Finish</td>
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
                    $start = \Carbon\Carbon::parse($first->contract_last);
                    $end = \Carbon\Carbon::parse($first->contract_end_last);
                    $startDateFormatted = $start->translatedFormat('l'); // Hari, tanggal lengkap
                    $endDateFormatted = $end->translatedFormat('l'); // Hari, tanggal lengkap
                    if (!empty($first->contract_end_last)) {
                        $date = \Carbon\Carbon::parse($first->contract_end_last);
                        $periode = $date->format('y') . $date->format('m');
                    }
                @endphp
                <tr>
                    <td style="border:1px solid black">{{ $first->status_aktif }}</td>
                    <td style="border:1px solid black">{{ $first->enroll_id }}</td>
                    <td style="border:1px solid black">{{ $first->nik }}</td>
                    <td style="border:1px solid black">{{ $first->employee_name }}</td>
                    <td style="border:1px solid black">{{ $first->status_jabatan }}</td>
                    <td style="border:1px solid black">{{ $first->sub_dept_name }}</td>
                    <td style="border:1px solid black">{{ $first->department_name }}</td>
                    <td style="border:1px solid black">{{ \Carbon\Carbon::parse($first->join_date)->translatedFormat('d F Y') }}</td>
                    <td style="border:1px solid black">{{ $first->tanggal_resign ? \Carbon\Carbon::parse($first->tanggal_resign)->translatedFormat('d F Y') : ''}}</td>
                    <td style="border:1px solid black">{{$years}}</td>
                    <td style="border:1px solid black">{{$month}}</td>
                    <td style="border:1px solid black">{{$day}}</td>
                    <td style="border:1px solid black">{{$first->penilaian_kinerja ? $first->penilaian_kinerja->nilai_kinerja : '' }}</td>
                    <td style="border:1px solid black">{{$first->penilaian_kinerja ? $first->penilaian_kinerja->tanggung_jawab_tugas : '' }}</td>
                    <td style="border:1px solid black">{{$first->penilaian_kinerja ? $first->penilaian_kinerja->inisiatif_kerjasama : '' }}</td>
                    <td style="border:1px solid black">{{$first->penilaian_kinerja ? $first->penilaian_kinerja->akurasi_pekerjaan : '' }}</td>
                    <td style="border:1px solid black">{{$first->penilaian_kinerja ? $first->penilaian_kinerja->kemauan_kegigihan : '' }}</td>
                    <td style="border:1px solid black">{{$first->penilaian_kinerja ? $first->penilaian_kinerja->penyampaian_informasi : '' }}</td>
                    <td style="border:1px solid black">{{$first->penilaian_kinerja ? $first->penilaian_kinerja->attitude_sikap_kerja : '' }}</td>
                    <td style="border:1px solid black">{{$first->penilaian_kinerja ? $first->penilaian_kinerja->rata_rata_kompetensi : '' }}</td>
                    <td style="border:1px solid black">{{$first->penilaian_kinerja ? $first->penilaian_kinerja->total_pengurangan : '' }}</td>
                    <td style="border:1px solid black">{{$first->penilaian_kinerja ? $first->penilaian_kinerja->nilai_akhir : '' }}</td>
                    <td style="border:1px solid black">{{ \Carbon\Carbon::parse($first->contract_end_last)->translatedFormat('d F Y') }}</td>
                    <td style="border:1px solid black">{{$startDateFormatted}}</td>
                    <td style="border:1px solid black">{{ \Carbon\Carbon::parse($first->contract_last)->translatedFormat('d F Y') }}</td>
                    <td style="border:1px solid black">{{$endDateFormatted}}</td>
                    <td style="border:1px solid black">{{ \Carbon\Carbon::parse($first->contract_end_last)->translatedFormat('d F Y') }}</td>
                </tr>
            @endforeach
        </table>
    </body>
</html>
