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
        <td style="vertical-align: middle; font-size: 20px; text-align: center; font-weight: 800;" colspan="4" rowspan="4">FORM INSENTIF</td>
        <td>Kode Dokumen</td>
        <td>:</td>
    </tr>
    <tr>
        <td>Revisi</td>
        <td>:</td>
    </tr>
    <tr>
        <td>Tanggal Revisi</td>
        <td>:</td>
    </tr>
    <tr>
        <td>Tanggal Efektif</td>
        <td>:</td>
    </tr>
    <tr>
        <td colspan="8"></td>
    </tr>
    <tr>
        <td colspan='2'>TANGGAL</td>
        <td colspan='4'>
            {{-- {{ date('d-M-Y', strtotime($from)) }} - {{ date('d-M-Y', strtotime($to)) }} --}}
            {{ date('d-M-Y') }}
        </td>
        <td>Target</td>
        <td>0</td>
    </tr>
    <tr>
        <td colspan='2'>BAGIAN</td>
        <td colspan='4'>
            {{ $line }}
        </td>
        <td>Actual</td>
        <td>0</td>
    </tr>
    <tr>
        <td colspan='2'>DEPARTEMEN</td>
        <td colspan='4'>
            PRODUKSI
        </td>
    </tr>
    <tr>
        <td colspan="8"></td>
    </tr>
    <thead>
        <tr>
            <th style="background-color: yellow">No</th>
            <th style="background-color: yellow">ID</th>
            <th style="background-color: yellow" colspan="2">Nama Karyawan</th>
            <th style="background-color: yellow">Nominal</th>
            <th style="background-color: yellow">Keterangan</th>
            <th style="background-color: yellow" colspan="2">Tanda Tangan</th>
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

            @php
                $total += 20821;
            @endphp

            <tr style="height:200px">
                <td style="background-color: {{ $style }}; text-align: center;">{{ $no++ }}.</td>
                <td style="background-color: {{ $style }}">{{ $item->enroll_id }}</td>
                <td style="background-color: {{ $style }}" colspan="2">{{ $item->nm_karyawan }}</td>
                <td style="background-color: {{ $style }}">Rp. 20.821</td>
                <td style="background-color: {{ $style }}"></td>
                @if ((($no-1)%2) != 0)
                    <td rowspan=2 style="text-align:left; vertical-align:top; font-size: 7px; background-color: {{ $style }};">{{ $no - 1 <= count($data) ? $no - 1 : '' }}</td>
                    <td rowspan=2 style="text-align:left; vertical-align:top; font-size: 7px; background-color: {{ $style }};">{{ $no <= count($data) ? $no : ''  }}</td>
                @endif
            </tr>

            @if ((($no-1)%2) != 0 && $no > count($data))
                <tr>
                    <td style="background-color: {{ $style }}"></td>
                    <td style="background-color: {{ $style }}"></td>
                    <td style="background-color: {{ $style }}" colspan="2"></td>
                    <td style="background-color: {{ $style }}"></td>
                    <td style="background-color: {{ $style }}"></td>
                </tr>
            @endif
        @endforeach
        <tr>
            <td style="font-weight: 800;" colspan='2'>TOTAL</td>
            <td colspan='2'></td>
            <td></td>
            <td></td> 	
            <td></td>
            <td></td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">Diajukan</td>
            <td colspan="2" style="text-align: center;">Diketahui</td>
            <td style="text-align: center;">Diketahui</td>
            <td></td>
            <td></td>
            <td style="text-align: center;">Disetujui</td>
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
            <td colspan="2" style="text-align: center;text-decoration: underline;">Teddy Nova Liantara</td>
            <td colspan="2" style="text-align: center;text-decoration: underline;">_________________</td>
            <td style="text-align: center;text-decoration: underline;">Bobby Tangnga</td>
            <td></td>
            <td></td>
            <td style="text-align: center;text-decoration: underline;">Ronald Harsanto</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; vertical-align: top; height: 30px;">Manager Produksi</td>
            <td colspan="2" style="text-align: center; vertical-align: top; height: 30px;">HRD</td>
            <td style="text-align: center; vertical-align: top; height: 30px;">General Manager</td>
            <td></td>
            <td></td>
            <td style="text-align: center; vertical-align: top; height: 30px;">COO PT. NAG</td>
        </tr>   
    </tfoot>
</table>

</html>
