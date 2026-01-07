<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
    <head>
    </head>
    <body>
        <table border="1">
            <tr>
                <td colspan="22">Rekap Kompensasi PKWT</td>
            </tr>
            <tr>
                <td colspan="22"></td>
            </tr>
            <tr>
                <td rowspan="2" width="18">Aktif/ Non Aktif</td>
                <td rowspan="2" width="10">ID</td>
                <td rowspan="2" width="13">NIP</td>
                <td rowspan="2" width="20">Nama Karyawan</td>
                <td rowspan="2" width="20">Status Staff</td>
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
            <tr>
                <td style="">{{ $data_pkwt->status_aktif }}</td>
                <td style="">{{ $data_pkwt->enroll_id }}</td>
                <td style="">{{ $data_pkwt->nik }}</td>
                <td style="">{{ $data_pkwt->employee_name }}</td>
                <td style=""> {{ $data_pkwt->status_staff }}</td>
                <td style="">{{ $data_pkwt->status_jabatan }}</td>
                <td style="">{{ $data_pkwt->sub_dept_name }}</td>
                <td style="">{{ $data_pkwt->department_name }}</td>
                <td>
                    {{ $data_pkwt->join_date
                        ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(\Carbon\Carbon::parse($data_pkwt->join_date))
                        : ''
                    }}
                </td>
                <td>
                    {{ $data_pkwt->tanggal_resign
                        ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(\Carbon\Carbon::parse($data_pkwt->tanggal_resign))
                        : ''
                    }}
                </td>

                {{-- <td>
                    {{ $data_pkwt->tanggal_resign
                        ? \Carbon\Carbon::parse($data_pkwt->tanggal_resign)->format('d-m-Y')
                        : ''
                    }}
                </td> --}}
                <td style="text-align:center">{{ $data_pkwt->years }}</td>
                <td style="text-align:center">{{ $data_pkwt->months }}</td>
                <td style="text-align:center">{{ $data_pkwt->days }}</td>
                {{-- <td>{{ $data_pkwt->contract ? \Carbon\Carbon::parse($data_pkwt->contract)->translatedFormat('l') : '' }}</td> --}}
                <td>
                    {{ $data_pkwt->contract_start_fixed
                        ? \Carbon\Carbon::parse($data_pkwt->contract_start_fixed)->translatedFormat('l')
                        : ''
                    }}
                </td>
                <td>
                    {{ $data_pkwt->contract_start_fixed
                        ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(
                            \Carbon\Carbon::parse($data_pkwt->contract_start_fixed)
                        )
                        : ''
                    }}
                </td>

                <td>{{ $data_pkwt->contract_end ? \Carbon\Carbon::parse($data_pkwt->contract_end)->translatedFormat('l') : '' }}</td>
                <td>
                    {{ $data_pkwt->contract_end
                        ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(
                            \Carbon\Carbon::parse($data_pkwt->contract_end)
                        )
                        : ''
                    }}
                </td>
                 {{-- <td>
                    {{ $data_pkwt->contract_end
                        ? \Carbon\Carbon::parse($data_pkwt->contract_end)->format('d-m-Y')
                        : ''
                    }}
                </td> --}}
                <td style="text-align:center">{{ $data_pkwt->jumlah_bulan }}</td>
                <td style="text-align:center">{{$data_pkwt->umk}}</td>
                <td style="text-align:center">{{$data_pkwt->tunjangan}}</td>
                <td style="text-align:center">{{$data_pkwt->total_penghasilan_bulanan}}</td>
                <td style="text-align:center">{{ $data_pkwt->total_kompensasi }}</td>

            </tr>
        @endforeach
        </table>
    </body>
</html>
