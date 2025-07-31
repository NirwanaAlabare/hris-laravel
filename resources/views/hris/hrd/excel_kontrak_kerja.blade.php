@php
    use Maatwebsite\Excel\Excel;
@endphp

<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
    <head>
    </head>
    <body>
        <table border="1">
        @php
            $grouped = collect($query)->groupBy('enroll_id');

            $maxContracts = $grouped->map(function ($contracts) {
                return $contracts->count();
            })->max();
        @endphp
            <tr>
                <td colspan="6" style="font-size: 12px; font-weight:600;">Rekap Kontrak Kerja Karyawan</td>
            </tr>
            <tr>
            <td rowspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Aktif/Tidak Aktif</td>
            <td rowspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Staff / Non Staff</td>
            <td rowspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;"></td>
            <td rowspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">NO ID</td>
            <td rowspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">NIP</td>
            <td rowspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Nama</td>
            <td rowspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Jabatan</td>
            <td rowspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Bagian</td>
            <td rowspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Department</td>
            <td rowspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Status</td>
            <td rowspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Join Date</td>
            <td rowspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Tanggal Resign</td>
            <td colspan="3" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Masa Kerja</td>

            @for ($i = 1; $i <= $maxContracts; $i++)
                <td colspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">PKS {{ $i }}</td>
            @endfor

            <td rowspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;"></td>
            <td colspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Tanggal Terakhir Kontrak</td>
            <td rowspan="2" style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Periode</td>
        </tr>
        <tr>
            <td style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">THN</td>
            <td style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">BLN</td>
            <td style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">HR</td>

            @for ($i = 1; $i <= $maxContracts; $i++)
                <td style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Start</td>
                <td style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">End</td>
            @endfor

            <td style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Awal Kontrak</td>
            <td style="border:1px solid black; font-size: 8px; font-weight:600; text-align:center;">Akhir Kontrak</td>
        </tr>

        @foreach ($grouped as $enrollId => $contracts)
            @php
                $first = $contracts->first();
                $join_date = $first->join_date;
                $today = date('Y-m-d');
                $diff = abs(strtotime($today) - strtotime($join_date));
                $years = floor($diff / (365 * 60 * 60 * 24));
                $month = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                $day = floor(($diff - $years * 365 * 60 * 60 * 24 - $month * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                $date = \Carbon\Carbon::parse($first->contract_end_last);
                $periode = $date->format('y') . $date->format('m');
            @endphp

            <tr>
                {{-- Kolom Data Karyawan --}}
                <td style="border:1px solid black; font-size: 8px;">{{ $first->status_aktif }}</td>
                <td style="border:1px solid black; font-size: 8px;">{{ $first->status_staff }}</td>
                <td style="border:1px solid black; font-size: 8px;"></td>
                <td style="border:1px solid black; font-size: 8px;">{{ $first->enroll_id }}</td>
                <td style="border:1px solid black; font-size: 8px;">{{ $first->nik }}</td>
                <td style="border:1px solid black; font-size: 8px;">{{ $first->employee_name }}</td>
                <td style="border:1px solid black; font-size: 8px;">{{ $first->status_jabatan }}</td>
                <td style="border:1px solid black; font-size: 8px;">{{ $first->sub_dept_name }}</td>
                <td style="border:1px solid black; font-size: 8px;">{{ $first->department_name }}</td>
                <td style="border:1px solid black; font-size: 8px;">{{ $first->status_kontrak_tetap }}</td>
                <td style="border:1px solid black; font-size: 8px;">{{ \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($first->join_date) }}</td>
                <td style="border:1px solid black; font-size: 8px;">{{ \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($first->tanggal_resign) }}</td>

                {{-- Masa Kerja --}}
                <td style="border:1px solid black; font-size: 8px;">{{ $years }}</td>
                <td style="border:1px solid black; font-size: 8px;">{{ $month }}</td>
                <td style="border:1px solid black; font-size: 8px;">{{ $day }}</td>

                {{-- Kontrak 1 - 46 --}}
                @foreach ($contracts as $contract)
                    <td style="border:1px solid black; font-size: 8px;">
                        {{ \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($contract->contract) }}
                    </td>
                    <td style="border:1px solid black; font-size: 8px;">
                        {{ \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($contract->contract_end) }}
                    </td>
                @endforeach

                {{-- Jika kurang dari 46 kontrak, tambahkan kolom kosong --}}
                @for ($i = $contracts->count(); $i < $maxContracts; $i++)
                    <td style="border:1px solid black; font-size: 8px;"></td>
                    <td style="border:1px solid black; font-size: 8px;"></td>
                @endfor

                {{-- Kolom Kosong Tambahan --}}
                <td style="border:1px solid black; font-size: 8px;"></td>

                {{-- Tanggal Terakhir Kontrak --}}
                <td style="border:1px solid black; font-size: 8px;">
                    {{ \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($first->contract_last) }}
                </td>
                <td style="border:1px solid black; font-size: 8px;">
                    {{ \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($first->contract_end_last) }}
                </td>

                {{-- Periode --}}
              <td style="border:1px solid black; font-size: 8px;">
                ="{{ $periode }}"
            </td>

            </tr>
        @endforeach

        </table>
    </body>
</html>
