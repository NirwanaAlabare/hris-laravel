<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>
    <style>
        table {
            border-collapse: collapse;
        }
        @page { margin: 20px 20px 40px 20px; }
    </style>
</head>

<body>
    <table width="100%">
        <thead>
            <tr>
                <td width="100px" style="vertical-align: middle; text-align: center;border: 1px solid;" colspan="2" rowspan="4">
                    <img height="60" src="{{ public_path('assets/images/brand/nirwana logo.jpg') }}" alt="">
                </td>
                <td style="vertical-align: middle; font-size: 12pt; text-align: center; font-weight: 800;border: 1px solid;" colspan="8" rowspan="4">FORM PENUGASAN TRANSPORTASI</td>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Kode Dokumen</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: F.16.HR.NAG.P-03.F-01.01</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: 1</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Tanggal Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: 26 April 2022</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid;border-bottom: 1px solid; font-size: 9pt;">Tanggal Efektif</td>
                <td colspan="3" class="border-right" style="border: 1px solid; border-bottom: 1px solid; font-size: 9pt;">: 27 April 2022</td>
            </tr>
        </thead>
    </table>
    @foreach ($data as $key=>$value)
    <table width="100%" style="padding-top:8px">
        <thead>
            <tr>
                <td style="height:10px"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top" width="17%">Tanggal</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top" width="46%">{{Carbon\Carbon::parse(date('Y-m-d'))->translatedFormat('d F Y')}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top" width="16%"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top" width="1%"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="height:10px"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Nomor Pengajuan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Driver</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Tanggal Penugasan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{Carbon\Carbon::parse($value->tanggal_pemberangkatan)->translatedFormat('d F Y')}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Nama</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$value->driver}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Nama User</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$value->employee_name}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">NIK</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$value->nik_driver}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">NIK</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$value->nik}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Nomor Kendaraan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$nomor_kendaraan[0]->plat_no}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Department</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$value->department_name}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Jenis Kendaraan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$nomor_kendaraan[0]->tipe}}</td>
            </tr>
        </thead>
    </table>
    <table style="padding-top:8px">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Tujuan Pemberangkatan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{ucwords(str_replace("_"," ",$value->tujuan_pemberangkatan))}}</td>
            </tr>
            @if (str_contains($value->tujuan_pemberangkatan,'antar_barang')||str_contains($value->tujuan_pemberangkatan,'jemput_barang'))
                <tr>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Jenis Barang</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$value->jenis_barang}}</td>
                </tr>
                <tr>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Quantity</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$value->quantity}} {{$value->satuan}}</td>
                </tr>
                <tr>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Nama Penerima</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$value->nama_penerima}}</td>
                </tr>
                <tr>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Nama Instansi</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$value->nama_instansi}}</td>
                </tr>
                <tr>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Keterangan</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$value->keterangan_barang}}</td>
                </tr>
            @endif
            @if (str_contains($value->tujuan_pemberangkatan,'antar_tamu')||str_contains($value->tujuan_pemberangkatan,'jemput_tamu'))
                <tr>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Nama Tamu</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$value->nama_tamu}}</td>
                </tr>
                <tr>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Instansi Tamu</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$value->instansi_tamu}}</td>
                </tr>
                <tr>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">No. HP Tamu</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$value->nomor_hp_tamu}}</td>
                </tr>
            @endif
            @if (str_contains($value->tujuan_pemberangkatan,'antar_dinas')||str_contains($value->tujuan_pemberangkatan,'jemput_dinas'))
                <tr>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">Nama Karyawan</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">:</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top">{{$nama_karyawan_dinas_luar}}</td>
                </tr>
            @endif
        </thead>
    </table>
    @endforeach
    <table width="100%" style="padding-top:8px;">
        <thead>
            <tr>
                <td style="height:20px"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">Jam berangkat</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">Alamat Keberangkatan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">Jarak Tempuh</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">Alamat Lengkap Kedatangan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">Waktu Kedatangan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center" width="70px">Status</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center" width="100px">Tanda Tangan Driver</td>
            </tr>
            @foreach ($data2 as $key => $value)
            @if($key==0)
            <tr>
                <td rowspan="{{count($data2)}}" style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">{{substr($value->jam_pemberangkatan,0,5)}}</td>
                <td rowspan="{{count($data2)}}" style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black">{{$value->detail_alamat}} - {{ucwords(strtolower($value->desa))}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;vertical-align:middle;text-align:center"> {{$value->jarak_tempuh}} Km</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black">{{$value->detail_alamat_tujuan}} - {{ucwords(strtolower($value->desa_tujuan))}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:left;vertical-align:top;border:1px solid black;text-align:center">{{Carbon\Carbon::parse($value->tanggal_kedatangan)->translatedFormat('d M Y')}}-{{$value->jam_kedatangan}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black"></td>
            </tr>
            @else
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;vertical-align:middle;text-align:center"> ....... Km</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black">{{$value->detail_alamat_tujuan}} - {{ucwords(strtolower($value->desa_tujuan))}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:left;vertical-align:top;border:1px solid black;text-align:center">{{Carbon\Carbon::parse($value->tanggal_kedatangan)->translatedFormat('d M Y')}}-{{$value->jam_kedatangan}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black"></td>
            </tr>
            @endif
            @endforeach
        </thead>
    </table>
    <table width="100%" style="padding-top:8px;">
        <thead>
            <tr>
                <td style="height:20px"></td>
            </tr>
            <tr>
                <td width="37%"></td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center" >Dibuat</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">Disetujui</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">Diketahui</td>
            </tr>
            <tr>
                <td></td>
                <td style="height: 55px;border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
            </tr>
            <tr>
                <td></td>
                <td style="border:1px solid black;text-align:center" ></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">Manager</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">General Affair</td>
            </tr>
        </thead>
    </table>
</body>
</html>