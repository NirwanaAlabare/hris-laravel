<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
    <head>
    </head>
    <body>
        <table border="1">
            <tr>
                <td colspan="6">Rekap Surat Peringatan Karyawan</td>
            </tr>
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td width="10" style="border:1px solid black">No Surat</td>
                <td width="10" style="border:1px solid black">Bulan</td>
                <td width="35" style="border:1px solid black" >Nomor Surat</td>
                <td width="10" style="border:1px solid black">ID</td>
                <td width="13" style="border:1px solid black">NIP</td>
                <td width="20" style="border:1px solid black">Nama</td>
                <td width="20" style="border:1px solid black">Bagian</td>
                <td width="20" style="border:1px solid black">Department</td>
                <td width="20" style="border:1px solid black">Jabatan</td>
                <td width="20" style="border:1px solid black">Kode Pasal</td>
                <td width="20" style="border:1px solid black">Ket</td>
                <td width="20" style="border:1px solid black">Pasal</td>
                <td width="20" style="border:1px solid black">Uraian</td>
                <td width="20" style="border:1px solid black">Awal Masa SP</td>
                <td width="20" style="border:1px solid black">Akhir Masa SP</td>
                <td width="10" style="border:1px solid black">SP Ke</td>
                <td width="10" style="border:1px solid black">Terbilang</td>
            </tr>
            @foreach ($query as $enrollId => $data_sp)
            <tr>
            @php
                $bulan = \Carbon\Carbon::parse($data_sp->tanggal_mulai)->month;
                $bulanRomawi = [
                    1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
                    5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
                    9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
                ];
                $tahun = \Carbon\Carbon::parse($data_sp->tanggal_mulai)->year;
            @endphp
                <td style="">{{ $data_sp->no_form }}</td>
                <td style="">{{ $bulanRomawi[$bulan] }}</td>
                <td style="">No.{{$data_sp->no_form}}/HRD-NAG/SP/{{strtolower($data_sp->surat_peringatan) == 'sp_1' ? 1 : (strtolower($data_sp->surat_peringatan) == 'sp_2' ? 2 : (strtolower($data_sp->surat_peringatan) == 'sp_3' ? 3 : ''))}}/{{ $bulanRomawi[$bulan] }}/{{ $tahun }}</td>
                <td style="">{{ $data_sp->enroll_id }}</td>
                <td style="">{{ $data_sp->nik }}</td>
                <td style="">{{ $data_sp->employee_name }}</td>
                <td style="">{{ $data_sp->sub_dept_name }}</td>
                <td style="">{{ $data_sp->department_name }}</td>
                <td style="">{{ $data_sp->status_jabatan }}</td>
                <td style="">{{ $data_sp->kode_pasal }}</td>
                <td style="">{{ $data_sp->deskripsi }}</td>
                <td style="">{{$data_sp->pasal}}. {{ $data_sp->desc_surat_peringatan }}</td>
                <td style="">{{ $data_sp->alasan_pelanggaran }}</td>
                <td>{{ \Carbon\Carbon::parse($data_sp->tanggal_mulai)->translatedFormat('d F Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($data_sp->tanggal_sampai)->translatedFormat('d F Y') }}</td>
                <td>
                    @if(strtolower($data_sp->surat_peringatan) == strtolower('sp_1'))
                        1
                    @endif
                    @if(strtolower($data_sp->surat_peringatan) == strtolower('sp_2'))
                        2
                    @endif
                    @if(strtolower($data_sp->surat_peringatan) == strtolower('sp_3'))
                        3
                    @endif
                </td>
                <td>
                    @if(strtolower($data_sp->surat_peringatan) == strtolower('sp_1'))
                        Satu
                    @endif
                    @if(strtolower($data_sp->surat_peringatan) == strtolower('sp_2'))
                        Dua
                    @endif
                    @if(strtolower($data_sp->surat_peringatan) == strtolower('sp_3'))
                        Tiga
                    @endif
                </td>
            </tr>
        @endforeach
        </table>
    </body>
</html>
