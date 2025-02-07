<!DOCTYPE html>
<html lang="en">

{{-- <table class="table table-bordered" style="width:100%">
    <tr>
        <th colspan="2" rowspan ="4"></th>
        <th colspan="2" rowspan ="4" align="center"> FORM INSENTIF</th>
        <th>Kode Dokumen</th>
        <th>:</th>
    </tr>
    <tr>
        <th>Revisi</th>
        <th>:</th>
    </tr>
    <tr>
        <td>Tanggal Revisi</td>
        <td>:</td>
    </tr>
    <tr>
        <td>Tanggal Efektif</td>
        <td>:</td>
    </tr>

</table> --}}




<table class="table">

    <tr>
        <td style="vertical-align: middle; text-align: center; width: 100%;" colspan="2" rowspan="4"></td>
        <td style="vertical-align: middle; font-size: 20px; text-align: center; font-weight: 800;" colspan="7"
            rowspan="4">FORM PERSETUJUAN LEMBUR</td>
        <td colspan="2">Kode Dokumen</td>
        <td colspan="2">: F.16.HR.NAG.P-03.F-01.01</td>
    </tr>
    <tr>
        <td colspan="2">Revisi</td>
        <td colspan="2">: 1</td>
    </tr>
    <tr>
        <td colspan="2">Tanggal Revisi</td>
        <td colspan="2">: 26 April 2022</td>
    </tr>
    <tr>
        <td colspan="2">Tanggal Efektif</td>
        <td colspan="2">: 27 April 2022</td>
    </tr>
    <tr>
        <td colspan="13"></td>
    </tr>
    <tr>
        <td colspan='2'>TANGGAL</td>
        <td colspan='2'>
            {{-- {{ date('d-M-Y', strtotime($from)) }} - {{ date('d-M-Y', strtotime($to)) }} --}}
            {{ date('d-M-Y', strtotime($tgl_lembur)) }}
        </td>
        <td colspan='5'></td>
        <td colspan='2'>Nomor Form</td>
        <td colspan='2'>: {{ $no_form }}</td>
    </tr>
    <tr>
        <td colspan='2'>BAGIAN</td>
        <td colspan='2'>
            {{ $line }}
        </td>
    </tr>
    <tr>
        <td colspan="13"></td>
    </tr>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Karyawan</th>
            <th>NIP</th>
            <th>Jabatan</th>
            <th>Keterangan</th>
            <th>Jam Mulai Rencana</th>
            <th>Jam Akhir Rencana</th>
            <th>Jumlah Jam Rencana</th>
            <th>Tanda Tangan Rencana</th>
            <th>Jam Awal Realisasi</th>
            <th>Jam Akhir Realisasi</th>
            <th>Jumlah Jam Realisasi</th>
            <th>Tanda Tangan Realisasi</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
            $style = '';
            $total = 0;
        @endphp
        @foreach ($data as $item)
            {{-- @if ($item->absen_masuk_kerja == null)
                @php
                    $style = 'red';
                @endphp
            @else
                @php
                    $style = 'white';
                @endphp
            @endif --}}

            <tr style="height:35px">
                <td style="text-align: center;">{{ $no++ }}.</td>
                <td style="text-align: left;">{{ $item->employee_name }}</td>
                <td style="text-align: left;">{{ $item->nik }}</td>
                <td style="text-align: left;">{{ $item->status_jabatan }}</td>
                <td style="text-align: left;">{{ $item->ket }}</td>
                <td style="text-align: center;">{{ $item->jam_lembur_awal_rencana }}</td>
                <td style="text-align: center;">{{ $item->jam_lembur_akhir_rencana }}</td>
                <td style="text-align: center;">{{ $item->total_jam }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td style="height:35px"></td>
                {{-- <td style="text-align: center;">{{ $item->absen_masuk_kerja }}</td>
                <td style="text-align: center;">{{ $item->absen_pulang_kerja }}</td>
                <td style="text-align: center;">{{ $item->realisasi_lembur }}</td> --}}
            </tr>

            @if (($no - 1) % 2 != 0 && $no > count($data))
                <tr>
                    <td style="background-color: {{ $style }}"></td>
                    <td style="background-color: {{ $style }};"></td>
                    <td style="background-color: {{ $style }}"></td>
                    <td style="background-color: {{ $style }}"></td>
                    <td style="background-color: {{ $style }}"></td>
                    <td style="background-color: {{ $style }}"></td>
                    <td style="background-color: {{ $style }}"></td>
                    <td style="background-color: {{ $style }}"></td>
                    <td style="background-color: {{ $style }}"></td>
                    <td style="background-color: {{ $style }}"></td>
                    <td style="background-color: {{ $style }}"></td>
                </tr>
            @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="13"></td>
        </tr>

        {{-- <tr>
            <td colspan="13"></td>
        </tr> --}}
        <tr>
            <td colspan="2">Catatan :</td>
        </tr>
        <tr>
            <td colspan="5">1. Pengajuan Rencana Lembur dilakukan sebelum pelaksanaan lembur</td>
        </tr>
        <tr>
            <td colspan="10">2. Approval realisasi lembur harus sudah diserahkan kepada HRD paling lambat 1 hari
                setelah pelaksanaan lembur pukul 09.00</td>
        </tr>
        <tr>
            <td colspan="10">3. Formulir persetujuan lembur harus diisi dengan lengkap dan tidak boleh terdapat
                coretan</td>
        </tr>
        <tr>
            <td colspan="13"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">Diajukan Oleh</td>
            <td colspan="2" style="text-align: center;">Diketahui</td>
            <td colspan="2" style="text-align: center;">Diketahui</td>
            <td colspan="2" style="text-align: center;">Diketahui</td>
            <td colspan="3" style="text-align: center;">Disetujui</td>
            <td colspan="2" style="text-align: center;">Approval Realisasi</td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="2"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="2"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="2"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="2"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;text-decoration: underline;">_________________</td>
            <td colspan="2" style="text-align: center;text-decoration: underline;">_________________</td>
            <td colspan="2" style="text-align: center;text-decoration: underline;">Tedy Nova Liantara</td>
            <td colspan="2" style="text-align: center;text-decoration: underline;">Eka Darmawan</td>
            <td colspan="3" style="text-align: center;text-decoration: underline;">Bobby Tangnga</td>
            <td colspan="2" style="text-align: center;text-decoration: underline;">HRD</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; vertical-align: top; height: 30px;"></td>
            <td colspan="2" style="text-align: center; vertical-align: top; height: 30px;">SPV/ Chief</td>
            <td colspan="2" style="text-align: center; vertical-align: top; height: 30px;">Manager Produksi</td>
            <td colspan="2" style="text-align: center; vertical-align: top; height: 30px;">PPIC Manager</td>
            <td colspan="3" style="text-align: center; vertical-align: top; height: 30px;">General Manager</td>
            <td colspan="2" style="text-align: center; vertical-align: top; height: 30px;"></td>
        </tr>
    </tfoot>


</table>

</html>
