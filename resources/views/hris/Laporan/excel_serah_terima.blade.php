<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
    <head>
    </head>
    <body>
        <table border="1">
            <tr>
                <td colspan="6">PT. NIRWANA ALABARE GARMENT</td>
            </tr>
            <tr>
                <td colspan="6">DATA SERAH TERIMA LEMBUR KARYAWAN</td>
            </tr>
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td width="10" style="border:1px solid black">NO</td>
                <td width="25" style="border:1px solid black">TANGGAL PENGINPUTAN</td>
                <td width="25" style="border:1px solid black" >TANGGAL SPL</td>
                <td width="25" style="border:1px solid black">NOMOR SPL</td>
                <td width="13" style="border:1px solid black">JML DATA</td>
                <td width="20" style="border:1px solid black">TANDA TANGAN</td>
            </tr>
            @foreach ($query as $enrollId => $data_sp)
            <tr>
                <td style="">{{ $enrollId + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($data_sp->created_at)->translatedFormat('d F Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($data_sp->tanggal_berjalan)->translatedFormat('d F Y') }}</td>
                <td style="">{{ $data_sp->nomor_form_lembur }}</td>
                <td style="">{{ $data_sp->jumlah_karyawan }}</td>
                <td style=""></td>
            </tr>
        @endforeach
        </table>
    </body>
</html>
