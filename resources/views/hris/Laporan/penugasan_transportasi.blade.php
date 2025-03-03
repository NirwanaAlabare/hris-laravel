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
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top" width="17%">Tanggal Input</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top" width="42%">{{Carbon\Carbon::parse($value->created_at)->translatedFormat('d F Y')}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top" width="16%"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top" width="1%"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="height:10px"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">Nomor Pengajuan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">Tanggal Penugasan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">{{Carbon\Carbon::parse($value->tanggal_pemberangkatan)->translatedFormat('d F Y')}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top;width:150px">Nama User</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">{{$value->employee_name}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">NIK</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">{{$value->nik}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">Department</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">{{$value->department_name}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
            </tr>
        </thead>
    </table>
    @endforeach
    <table width="100%" style="padding-top:8px;">
        <thead>
            <tr>
                <td style="height:20px"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center" width="10%">Jam berangkat</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center" width="22%">Alamat Keberangkatan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center" width="6%">Jarak</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center" width="22%">Alamat Lengkap Kedatangan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center" width="9%">Waktu Kedatangan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center" width="19%">keterangan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center" width="6%">Status</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center" width="6%">Tanda Tangan Driver</td>
            </tr>
            @foreach ($data2 as $key => $value)
            <tr>
                @if($key==0)
                <td rowspan={{count($data2)}} style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">{{substr($value['jam_pemberangkatan'],0,5)}}</td>
                <td rowspan={{count($data2)}} style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;padding-left:4px;padding-right:4px">{{$value['detail_alamat_asal']}}<br>{{ucwords(strtolower($value['desa']))}}</td>
                @endif
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;vertical-align:top;text-align:left"> {{$value['jarak_tempuh']}} Km</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;padding-left:4px;padding-right:4px">{{$value['detail_alamat_tujuan']}}<br>{{ucwords(strtolower($value['desa_tujuan']))}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:left;vertical-align:top;border:1px solid black;text-align:center">{{Carbon\Carbon::parse($value['tanggal_kedatangan'])->translatedFormat('d M Y')}}-{{$value['jam_kedatangan']}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black;padding-left:4px;padding-right:4px">
                    @if(str_contains($value['tujuan_pemberangkatan'], 'antar_barang')||str_contains($value['tujuan_pemberangkatan'], 'jemput_barang'))
                    <div class="row">
                        <div class="col-12" style="text-decoration: underline">
                            Keterangan Barang
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table>
                                <tr>
                                    <td>{{$value['keterangan_barang']}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table>
                                <tr>
                                    <td>Jenis : </td>
                                    <td>{{$value['jenis_barang']}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table>
                                <tr>
                                    <td>Quantity : </td>
                                    <td>{{$value['quantity']}} {{$value['satuan']}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table>
                                <tr>
                                    <td>Penerima : </td>
                                    <td>{{$value['nama_penerima']}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @elseif (str_contains($value['tujuan_pemberangkatan'], 'antar_tamu')||str_contains($value['tujuan_pemberangkatan'], 'jemput_tamu'))
                    <div class="row">
                        <div class="col-12" style="text-decoration: underline">
                            Keterangan Tamu
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table>
                                <tr>
                                    <td>Nama : </td>
                                    <td>{{$value['nama_tamu']}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table>
                                <tr>
                                    <td>Nomor HP : </td>
                                    <td>{{$value['nomor_hp_tamu']}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-12" style="text-decoration: underline">
                            Keterangan Dinas
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table>
                                <tr>
                                    <td style="vertical-align: top">Nama</td>
                                    <td style="vertical-align: top">:</td>
                                    <td>{{$value['karyawan_dinas_luar']}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif
                </td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;border:1px solid black"></td>
            </tr>
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