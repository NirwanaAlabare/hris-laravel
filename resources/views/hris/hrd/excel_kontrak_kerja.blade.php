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
                <td colspan="6">Rekap Kontrak Kerja Karyawan</td>
            </tr>
            <tr>
            <td rowspan="2" style="border:1px solid black">Aktif/Tidak Aktif</td>
            <td rowspan="2" style="border:1px solid black">Staff / Non Staff</td>
            <td rowspan="2" style="border:1px solid black"></td>
            <td rowspan="2" style="border:1px solid black">NO ID</td>
            <td rowspan="2" style="border:1px solid black">NIP</td>
            <td rowspan="2" style="border:1px solid black">Nama</td>
            <td rowspan="2" style="border:1px solid black">Jabatan</td>
            <td rowspan="2" style="border:1px solid black">Bagian</td>
            <td rowspan="2" style="border:1px solid black">Department</td>
            <td rowspan="2" style="border:1px solid black">Status</td>
            <td rowspan="2" style="border:1px solid black">Join Date</td>
            <td rowspan="2" style="border:1px solid black">Tanggal Resign</td>
            <td colspan="3" style="border:1px solid black">Masa Kerja</td>

            @for ($i = 1; $i <= $maxContracts; $i++)
                <td colspan="2" style="border:1px solid black">Kontrak {{ $i }}</td>
            @endfor

            <td rowspan="2" style="border:1px solid black"></td>
            <td colspan="2" style="border:1px solid black">Tanggal Terakhir Kontrak</td>
            <td rowspan="2" style="border:1px solid black">Periode</td>
        </tr>
        <tr>
            <td style="border:1px solid black">THN</td>
            <td style="border:1px solid black">BLN</td>
            <td style="border:1px solid black">HR</td>

            @for ($i = 1; $i <= $maxContracts; $i++)
                <td style="border:1px solid black">Start</td>
                <td style="border:1px solid black">End</td>
            @endfor

            <td style="border:1px solid black">Awal Kontrak</td>
            <td style="border:1px solid black">Akhir Kontrak</td>
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
                $periode = '';
                if (!empty($first->contract_end_last)) {
                    $date = \Carbon\Carbon::parse($first->contract_end_last);
                    $periode = $date->format('y') . $date->format('m'); // contoh: 25 + 04 = 2504
                }
            @endphp

            <tr>
                {{-- Kolom Data Karyawan --}}
                <td style="border:1px solid black">{{ $first->status_aktif }}</td>
                <td style="border:1px solid black">{{ $first->status_staff }}</td>
                <td style="border:1px solid black"></td>
                <td style="border:1px solid black">{{ $first->enroll_id }}</td>
                <td style="border:1px solid black">{{ $first->nik }}</td>
                <td style="border:1px solid black">{{ $first->employee_name }}</td>
                <td style="border:1px solid black">{{ $first->status_jabatan }}</td>
                <td style="border:1px solid black">{{ $first->sub_dept_name }}</td>
                <td style="border:1px solid black">{{ $first->department_name }}</td>
                <td style="border:1px solid black">{{ $first->status_kontrak_tetap }}</td>
                <td style="border:1px solid black">{{ \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($first->join_date) }}</td>
                <td style="border:1px solid black">{{ \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($first->tanggal_resign) }}</td>

                {{-- Masa Kerja --}}
                <td style="border:1px solid black">{{ $years }}</td>
                <td style="border:1px solid black">{{ $month }}</td>
                <td style="border:1px solid black">{{ $day }}</td>

                {{-- Kontrak 1 - 46 --}}
                @foreach ($contracts as $contract)
                    <td style="border:1px solid black">
                        {{ \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($contract->contract) }}
                    </td>
                    <td style="border:1px solid black">
                        {{ \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($contract->contract_end) }}
                    </td>
                @endforeach

                {{-- Jika kurang dari 46 kontrak, tambahkan kolom kosong --}}
                @for ($i = $contracts->count(); $i < $maxContracts; $i++)
                    <td style="border:1px solid black"></td>
                    <td style="border:1px solid black"></td>
                @endfor

                {{-- Kolom Kosong Tambahan --}}
                <td style="border:1px solid black"></td>

                {{-- Tanggal Terakhir Kontrak --}}
                <td style="border:1px solid black">
                    {{ \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($first->contract_last) }}
                </td>
                <td style="border:1px solid black">
                    {{ \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($first->contract_end_last) }}
                </td>

                {{-- Periode --}}
                <td style="border:1px solid black">{{ $periode }}</td>
            </tr>
        @endforeach

        </table>
    </body>
</html>
