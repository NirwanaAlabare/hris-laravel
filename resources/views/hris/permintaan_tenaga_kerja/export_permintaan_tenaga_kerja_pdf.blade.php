<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Document</title>
    <style>
         body {
            font-family: Arial, sans-serif; /* Menggunakan font yang umum */
            font-size: 10px;
            letter-spacing: 1px;
        }
        table {
            border-collapse: collapse;
        }
        .purchase-order {
            height: auto;
            margin: 0;
            padding-left: 5mm;
            padding-right: 5mm;
            padding-top: 0px;
        }
    </style>
</head>
@foreach ($data as $key=>$value)
<body>
    <table class="purchase-order" width="100%" style="border-top: 1px solid; border-left: 1px solid;">
        <thead>
            <tr>
                <td width="100px" style="vertical-align: middle; text-align: center;border-bottom: 1px solid;" colspan="2" rowspan="4">
                    <img height="60" src="{{ public_path('assets/images/brand/nirwana logo.jpg') }}" alt="">
                </td>
                <td style="vertical-align: middle; font-size: 12pt; text-align: center; font-weight: 800;border-bottom: 1px solid; border-left: 1px solid;" colspan="8" rowspan="4">FORMULIR PERMINTAAN TENAGA KERJA</td>
                <td colspan="2" class="border-left" style="border-left: 1px solid; font-size: 7.5pt; height: 18px;">Kode Dokumen</td>
                <td colspan="3" class="border-right" style="border-left: 1px solid; font-size: 7.5pt; border-right: 1px solid;">: F.16.HR.NAG.P-01.F-01.01</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 7.5pt; height: 18px;">Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 7.5pt;">: 02</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 7.5pt; height: 18px;">Tanggal Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 7.5pt;">: 30 Oktober 2019</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid;border-bottom: 1px solid; font-size: 7.5pt; height: 18px;">Tanggal Berlaku</td>
                <td colspan="3" class="border-right" style="border: 1px solid; border-bottom: 1px solid; font-size: 7.5pt;">: 14 November 2019</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:10px;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 7.5pt;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; width:25%; padding-left:40px;">Nomor Permintaan</td>
                <td style="vertical-align: middle; font-size: 7.5pt;border-bottom: 1px solid; border-right: 1px solid; padding-left:5px;"> </td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:10px;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid;"></td>
            </tr>
        </thead>
    </table>
    @php
        $statusPermintaanList = [
            'permintaan_baru' => 'Permintaan Baru',
            'penggantian' => 'Penggantian'
        ];
    @endphp

    <table width="100%">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt; border-right: 1px solid; border-left: 1px solid; border-bottom: 1px solid; border-top:none; text-align: center; width:5%; height: 20px; font-weight: 800;">1</td>
                <td style="vertical-align: middle; font-size: 8pt; border-right: 1px solid; border-left: 1px solid; border-bottom: 1px solid; border-top:none; width:20%; padding-left:5px; font-weight: 800;">Status Permintaan</td>
                <td style="vertical-align: middle; font-size: 8pt; border-right: 1px solid; border-left: 1px solid; border-bottom: 1px solid; border-top:none; padding-left:5px;">
                    <table width="100%">
                        <tr>
                            @foreach($statusPermintaanList as $permintaanList => $db_value)
                                <td style="width: 50%; text-align: left;">
                                    <table>
                                        <tr>
                                            <td style="padding-left: 3px;">
                                                <div style="height: 17px; width: 17px; border: 2px solid black; text-align: center; font-size: 15px; line-height: 15px;">
                                                    @if(strtolower($value->status_permintaan) == strtolower($permintaanList))
                                                        ✔
                                                    @endif
                                                </div>
                                            </td>
                                            <td style="width: 10%;"></td>
                                            <td style="font-size: 8pt; white-space: nowrap;">{{ $db_value }}</td>
                                        </tr>
                                    </table>
                                </td>
                            @endforeach
                        </tr>
                    </table>
                </td>
            </tr>
        </thead>
    </table>

    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800;"> 2</td>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; width:20%; padding-left:5px; font-weight: 800;">Tanggal Pengajuan</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid; border-right: 1px solid; padding-left:5px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 2%;">:</td>
                                <td style="width: 95%;">
                                    {{Carbon\Carbon::parse($value->tanggal_pengajuan)->translatedFormat('d F Y')}}
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid white; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800;"> 3</td>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid; border-left: 1px solid; width:20%; padding-left:5px; font-weight: 800;">Diajukan Oleh</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid; border-right: 1px solid; padding-left:5px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid white; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800;"></td>
                <td style="vertical-align: middle;border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; width:5%; padding-left:5px; "></td>
                <td style="vertical-align: middle;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; width:15.1%; padding-left:5px; ">Nama</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid; border-right: 1px solid; padding-left:5px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 2%;">:</td>
                                <td style="width: 95%;">
                                    {{$value->employee_name}}
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid white; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800;"></td>
                <td style="vertical-align: middle;border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; width:5%; padding-left:5px; "></td>
                <td style="vertical-align: middle;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; width:15.1%; padding-left:5px; ">NIK</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid; border-right: 1px solid; padding-left:5px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 2%;">:</td>
                                <td style="width: 95%;">
                                    {{$value->nik}}
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </thead>
    </table>
      <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid white; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800;"></td>
                <td style="vertical-align: middle;border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; width:5%; padding-left:5px; "></td>
                <td style="vertical-align: middle;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; width:15.1%; padding-left:5px; ">Department</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid; border-right: 1px solid; padding-left:5px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 2%;">:</td>
                                <td style="width: 95%;">
                                    {{$value->department_name}}
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </thead>
    </table>
      <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800;"></td>
                <td style="vertical-align: middle;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; width:5%; padding-left:5px; "></td>
                <td style="vertical-align: middle;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; width:15.1%; padding-left:5px; ">Bagian</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid; border-right: 1px solid; padding-left:5px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 2%;">:</td>
                                <td style="width: 95%;">
                                    {{$value->sub_dept_name}}
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid white; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800;"> 4</td>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid; border-left: 1px solid; width:20%; padding-left:5px; font-weight: 800;">Rencana Kebutuhan</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid; border-right: 1px solid; padding-left:5px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid white; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800;"></td>
                <td style="vertical-align: middle;border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; width:5%; padding-left:5px; "></td>
                <td style="vertical-align: middle;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; width:15.1%; padding-left:5px; ">Department</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid; border-right: 1px solid; padding-left:5px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 2%;">:</td>
                                <td style="width: 95%;">
                                     {{$value->kode_dept_name}}
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid white; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800;"></td>
                <td style="vertical-align: middle;border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; width:5%; padding-left:5px; "></td>
                <td style="vertical-align: middle;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; width:15.1%; padding-left:5px; ">Bagian</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid; border-right: 1px solid; padding-left:5px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 2%;">:</td>
                                <td style="width: 95%;">
                                    {{$value->kode_bagian_name}}
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </thead>
    </table>
      <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid white; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800;"></td>
                <td style="vertical-align: middle;border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; width:5%; padding-left:5px; "></td>
                <td style="vertical-align: middle;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; width:15.1%; padding-left:5px; ">Tanggal</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid; border-right: 1px solid; padding-left:5px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 2%;">:</td>
                                <td style="width: 95%;">
                                     {{Carbon\Carbon::parse($value->tanggal_kebutuhan)->translatedFormat('d F Y')}}
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </thead>
    </table>
      <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800;"></td>
                <td style="vertical-align: middle;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; width:5%; padding-left:5px; "></td>
                <td style="vertical-align: middle;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; width:15.1%; padding-left:5px; ">Jumlah</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid; border-right: 1px solid; padding-left:5px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 2%;">:</td>
                                <td style="width: 95%;">
                                    {{$value->jumlah_kebutuhan}}
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </thead>
    </table>
    @php
        $jabatanList = ['Manager', 'Chief', 'SPV', 'Leader', 'Staff', 'Operator'];
    @endphp

    <table width="100%">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid ; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800;"> 5</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid ; border-right: 1px solid; border-left: 1px solid; width:20%; padding-left:5px; font-weight: 800;">Rencana Jabatan</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid ; border-right: 1px solid; border-left: 1px solid; padding-left:5px;">
                    <table width="100%">
                        <tr>
                            @foreach($jabatanList as $jabatan)
                                <td style="width: 16.66%; text-align: left;">
                                    <table>
                                        <tr>
                                            <td style="font-size: 8pt; white-space: nowrap;">{{ $jabatan }}</td>
                                            <td style="padding-left: 3px;">
                                                <div style="height: 17px; width: 17px; border: 2px solid black; text-align: center; font-size: 15px; line-height: 15px;">
                                                    @if(strtolower($value->rencana_jabatan) == strtolower($jabatan))
                                                        ✔
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            @endforeach
                        </tr>
                    </table>
                </td>
            </tr>
        </thead>
    </table>

<table width="100%" style="border-collapse: collapse;">
    <thead>
        <tr>
            <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; text-align: center; width:5%; height: 20px; font-weight: 800;">
                6
            </td>
            <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; width:20%; padding-left:5px; font-weight: 800;">
                Pendidikan Minimal & Jurusan
            </td>
            <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid;">
                <table width="100%">
                    <tr>
                        <td style="width: 40%; text-align: left;">
                            <table>
                                <tr>
                                    <td style="font-size: 8pt; white-space: nowrap; width: 200px;">Sekolah Dasar</td>
                                    <td style="padding-left: 3px;">
                                        <div style="height: 17px; width: 17px; border: 2px solid black; text-align: center; font-size: 15px; line-height: 15px;">
                                            @if(strtolower($value->pend_minimal) == strtolower('sd'))
                                                ✔
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 4%; text-align: left;">
                        </td>
                        <td style="width: 10%; text-align: left;">
                            <table>
                                <tr>
                                    <td style="font-size: 8pt; white-space: nowrap; width: 200px;">Diploma 1</td>
                                    <td style="padding-left: 3px;">
                                        <div style="height: 17px; width: 17px; border: 2px solid black; text-align: center; font-size: 15px; line-height: 15px;">
                                            @if(strtolower($value->pend_minimal) == strtolower('diploma_1'))
                                                ✔
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%; text-align: left;">
                        </td>
                        <td style="width: 10%; text-align: left;">
                            <table>
                                <tr>
                                    <td style="font-size: 8pt; white-space: nowrap; width: 200px;">Strata 1</td>
                                    <td style="padding-left: 3px;">
                                        <div style="height: 17px; width: 17px; border: 2px solid black; text-align: center; font-size: 15px; line-height: 15px;">
                                            @if(strtolower($value->pend_minimal) == strtolower('strata_1'))
                                                ✔
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%; text-align: left;">
                        </td>
                        <td style="width: 5%; text-align: left;">
                            <table>
                                <tr>
                                    <td style="font-size: 8pt; white-space: nowrap; width: 200px;">dll</td>
                                    <td style="padding-left: 3px;">
                                        <div style="height: 17px; width: 17px; border: 2px solid black; text-align: center; font-size: 15px; line-height: 15px;">
                                            @if(strtolower($value->pend_minimal) == strtolower('dll'))
                                                ✔
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%; text-align: left;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </thead>
</table>

<table width="100%" style="border-collapse: collapse;">
    <thead>
        <tr>
            <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; text-align: center; width:5%; height: 20px; font-weight: 800;">
            </td>
            <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; width:20%; padding-left:5px; font-weight: 800;">
            </td>
            <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid;">
                <table width="100%">
                    <tr>
                        <td style="width: 40%; text-align: left;">
                            <table>
                                <tr>
                                    <td style="font-size: 8pt; white-space: nowrap; width: 200px;">Sekolah Lanjutan Tingkat Pertama</td>
                                    <td style="padding-left: 3px;">
                                        <div style="height: 17px; width: 17px; border: 2px solid black; text-align: center; font-size: 15px; line-height: 15px;">
                                            @if(strtolower($value->pend_minimal) == strtolower('smp'))
                                                ✔
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 3%; text-align: left;">
                        </td>
                        <td style="width: 10%; text-align: left;">
                            <table>
                                <tr>
                                    <td style="font-size: 8pt; white-space: nowrap; width: 200px;">Diploma 2</td>
                                    <td style="padding-left: 3px;">
                                        <div style="height: 17px; width: 17px; border: 2px solid black; text-align: center; font-size: 15px; line-height: 15px;">
                                            @if(strtolower($value->pend_minimal) == strtolower('diploma_2'))
                                                ✔
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%; text-align: left;">
                        </td>
                        <td style="width: 10%; text-align: left;">
                            <table>
                                <tr>
                                    <td style="font-size: 8pt; white-space: nowrap; width: 200px;">Strata 2</td>
                                    <td style="padding-left: 3px;">
                                        <div style="height: 17px; width: 17px; border: 2px solid black; text-align: center; font-size: 15px; line-height: 15px;">
                                            @if(strtolower($value->pend_minimal) == strtolower('strata_2'))
                                                ✔
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 27%; text-align: left;">
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
    </thead>
</table>

<table width="100%" style="border-collapse: collapse;">
    <thead>
        <tr>
            <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; text-align: center; width:5%; height: 20px; font-weight: 800;">
            </td>
            <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; width:20%; padding-left:5px; font-weight: 800;">
            </td>
            <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid;">
                <table width="100%">
                    <tr>
                        <td style="width: 40%; text-align: left;">
                            <table>
                                <tr>
                                    <td style="font-size: 8pt; white-space: nowrap; width: 200px;">Sekolah Menengah Atas </td>
                                    <td style="padding-left: 3px;">
                                        <div style="height: 17px; width: 17px; border: 2px solid black; text-align: center; font-size: 15px; line-height: 15px;">
                                            @if(strtolower($value->pend_minimal) == strtolower('sma'))
                                                ✔
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 3.5%; text-align: left;">
                        </td>
                        <td style="width: 10%; text-align: left;">
                            <table>
                                <tr>
                                    <td style="font-size: 8pt; white-space: nowrap; width: 200px;">Diploma 3</td>
                                    <td style="padding-left: 3px;">
                                        <div style="height: 17px; width: 17px; border: 2px solid black; text-align: center; font-size: 15px; line-height: 15px;">
                                            @if(strtolower($value->pend_minimal) == strtolower('diploma_3'))
                                                ✔
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%; text-align: left;">
                        </td>
                        <td style="width: 10%; text-align: left;">
                            <table>
                                <tr>
                                    <td style="font-size: 8pt; white-space: nowrap; width: 200px;">Strata 3</td>
                                    <td style="padding-left: 3px;">
                                        <div style="height: 17px; width: 17px; border: 2px solid black; text-align: center; font-size: 15px; line-height: 15px;">
                                            @if(strtolower($value->pend_minimal) == strtolower('strata_3'))
                                                ✔
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 27%; text-align: left;">
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
    </thead>
</table>

<table width="100%" style="border-collapse: collapse;">
    <thead>
        <tr>
            <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; text-align: center; width:5%; height: 20px; font-weight: 800;">
            </td>
            <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; width:20%; padding-left:5px; font-weight: 800;">
            </td>
            <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid white; border-left: 1px solid; border-right: 1px solid; border-top: 1px solid;">
                <table width="100%">
                    <tr>
                        <td style="width: 40%; text-align: left;">
                            <table>
                                <tr>
                                    <td style="font-size: 8pt; white-space: nowrap; width: 100px;">Jurusan </td>
                                    <td style="font-size: 8pt; white-space: nowrap; width: 10px;">: </td>
                                    <td style="font-size: 8pt; white-space: nowrap; width: 200px;">{{$value->rencana_jurusan}} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </thead>
</table>

<table width="100%">
    <thead>
        <tr>
            <td style="vertical-align: middle; font-size: 8pt; border: 1px solid; text-align: center; width:5%; height: 20px; font-weight: 800;">7</td>
            <td style="vertical-align: middle; font-size: 8pt; border: 1px solid; width:20%; padding-left:5px; font-weight: 800;">Pengalaman Kerja</td>
            <td style="vertical-align: middle; font-size: 8pt; border: 1px solid; border-left: 1px solid; border-right: 1px solid;">
            <table width="100%">
                <tr>
                    <td style="width: 10%; text-align: left; padding-left: 5px;">
                        :
                    </td>
                    <td style="width: 50%; text-align: left;">
                        <table>
                            <tr>
                                <td style="font-size: 8pt; white-space: nowrap; width: 10px;">Ya</td>
                                <td style="padding-left: 3px;">
                                    <div style="height: 17px; width: 17px; border: 2px solid black; text-align: center; font-size: 15px; line-height: 15px;">
                                        @if(strtolower($value->pengalaman_kerja) == strtolower('ya'))
                                            ✔
                                        @endif
                                    </div>
                                </td>
                                <td style="padding-left: 3px; width: 200px;">
                                    , Waktu Pengalaman {{ ($value->pengalaman_kerja == 'ya' && $value->waktu_pengalaman) ? $value->waktu_pengalaman : "_____"}} tahun
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 4%; text-align: left;">
                    </td>
                    <td style="width: 30%; text-align: left;">
                        <table>
                            <tr>
                                <td style="font-size: 8pt; white-space: nowrap; width: 20px;">Tidak</td>
                                <td style="padding-left: 3px;">
                                    <div style="height: 17px; width: 17px; border: 2px solid black; text-align: center; font-size: 15px; line-height: 15px;">
                                        @if(strtolower($value->pengalaman_kerja) == strtolower('tidak'))
                                            ✔
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>
            </table>
        </td>
        </tr>
    </thead>
</table>
<table width="100%" style="border-collapse: collapse;">
    <tr>
        <td style="vertical-align: top; font-size: 8pt; border-left: 1px solid; border-bottom: 1px solid; border-right: 1px solid; text-align: center; width: 5%; font-weight: 800;">
            8
        </td>
        <td style="vertical-align: top; font-size: 8pt; border-top: 1px solid white; border-bottom: 1px solid; border-right: 1px solid; width: 20%; padding-left:5px; font-weight: 800;">
            Uraian Tugas Secara Umum
        </td>
        @php
            $uraian = is_array($value->uraian_tugas)
                ? $value->uraian_tugas
                : json_decode($value->uraian_tugas, true);
            $uraian = array_pad($uraian, 10, ''); // Pastikan total 10 item (isi kosong jika kurang)
        @endphp

        <td style="vertical-align: top; font-size: 8pt; border-top: 1px solid white; border-bottom: 1px solid; border-right: 1px solid;">
            <table width="100%" style="font-size: 8pt;">
                <tr>
                <td style="vertical-align: top; font-size: 8pt; border-top: 1px solid white; border-bottom: none; border-right: none;">
            <table width="100%" style="font-size: 8pt;">
                <tr>
                    <td width="50%" style="padding-right: 10px;">
                        <br><br>
                        @for ($i = 0; $i < 5; $i++)
                            {{ $i + 1 }}. {{ $uraian[$i] }}<br>
                            <div style="border-bottom: 1px solid #000; width: 100%;">&nbsp;</div><br><br>
                        @endfor
                    </td>
                    <td width="50%" style="padding-left: 10px;">
                        <br><br>
                        @for ($i = 5; $i < 10; $i++)
                            {{ $i + 1 }}. {{ $uraian[$i] }}<br>
                            <div style="border-bottom: 1px solid #000; width: 100%;">&nbsp;</div><br><br>
                        @endfor
                    </td>
                </tr>
            </table>
        </td>
        </tr>
    </table>
</td>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse;">
    <tr>
        <td style="vertical-align: top; font-size: 8pt; border-left: 1px solid; border-bottom: 1px solid white; border-right: 1px solid; text-align: center; width: 5%; font-weight: 800;">
            9
        </td>
        <td style="vertical-align: top; font-size: 8pt; border-top: none; border-bottom: 1px solid white; border-right: 1px solid; width: 20%; padding-left:5px; font-weight: 800;">
            Rencana Gaji & Fasilitas
        </td>
        <td style="vertical-align: top; font-size: 8pt; border-top: none; border-bottom: 1px solid white; border-right: 1px solid;">
              <table width="100%">
                <tr>
                    <td style="width: 20%; text-align: left; height:20px; padding-left: 3px;">
                        Golongan / Besaran Gaji
                    </td>
                    <td style="width: 1%; text-align: left; ">
                        :
                    </td>
                    <td style="width: 60%; text-align: left;">
                        {{ 'Rp ' . number_format($value->besaran_gaji, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" style="border-collapse: collapse;">
    <tr>
        <td style="vertical-align: top; font-size: 8pt; border-left: 1px solid; border-bottom: 1px solid; border-right: 1px solid; text-align: center; width: 5%; font-weight: 800;">
        </td>
        <td style="vertical-align: top; font-size: 8pt; border-top: none; border-bottom: 1px solid; border-right: 1px solid; width: 20%; padding-left:5px; font-weight: 800;">
        </td>
        <td style="vertical-align: top; font-size: 8pt; border-top: none; border-bottom: 1px solid; border-right: 1px solid;">
              <table width="100%">
                <tr>
                    <td style="width: 20%; text-align: left; height:20px; padding-left: 3px;">
                        Fasilitas
                    </td>
                    <td style="width: 1%; text-align: left;">
                        :
                    </td>
                    <td style="width: 60%; text-align: left;">
                        {{$value->fasilitas}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800;"> 10</td>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; width:20%; padding-left:5px; font-weight: 800;">Jangka Waktu Kontrak</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid; border-right: 1px solid; padding-left:5px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 2%;">:</td>
                                <td style="width: 95%;">
                                    {{$value->jangka_waktu_kontrak ? $value->jangka_waktu_kontrak . ' Bulan' : ''}}
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </thead>
</table>
<table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800;"> 11</td>
                <td style="vertical-align: middle; font-size: 8pt;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; width:20%; padding-left:5px; font-weight: 800;">Keterangan Lainnya ( Jumlah, Keterampilan Tambahan, dll )</td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid; border-right: 1px solid; padding-left:5px;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 2%;">:</td>
                                <td style="width: 95%;">
                                    {{$value->keterangan_tambahan ? $value->keterangan_tambahan : ''}}
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </thead>
</table>
<table width="100%" style="">
        <thead>
            <tr>
                <td style="height:10px;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid;"></td>
            </tr>
        </thead>
</table>
<table width="100%">
        <thead>
            <tr>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center; border-top:0px;" >Diajukan</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center; border-top:0px;">Disetujui</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center; border-top:0px;">Diketahui</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center; border-top:0px;">Disetujui</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center; border-top:0px;">Diketahui</td>
            </tr>
            <tr>
                <td style="height: 55px;border:1px solid black;"></td>
                <td style="height: 55px;border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center; text-transform: uppercase;"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center;">Chief/Dept. Head/Manager</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center;">HR-GA</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center;">General Mgr Produksi</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center;">General Mgr Factory</td>
            </tr>
        </thead>
</table>
<table width="100%" style="">
        <thead>
            <tr>
                <td style="height:10px;border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid;"></td>
            </tr>
        </thead>
</table>
<table width="100%" style="">
        <thead>
            <tr>
                <td style="height:10px;border-bottom: none; border-left: none; border-right: none;"></td>
            </tr>
        </thead>
</table>
<table width="100%" style="">
    <thead>
            <tr>
                <td style="border: none; width:80%;"> </td>
                <td style="vertical-align: middle; font-size: 8pt; border-bottom: 1px solid;  border-top: 1px solid; border-left: 1px solid; border-right: 1px solid; width:20%; padding-left:5px; font-weight: 800; text-align: center;">Paraf User</td>
            </tr>
        </thead>
</table>
<table width="100%" style="">
    <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt; border: 1px solid;  border-top: 1px solid; border-right: 1px solid; border-left: 1px solid; text-align: center; border-right: 1px solid; width:5%; height: 20px; font-weight: 800; width:25%;"> Status Permintaan</td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:3%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; border: 1px solid; width:4%; text-align: center;">
                    {{-- @if(strtolower($value->status_pengajuan_realisasi) == strtolower('done'))
                        ✔
                    @endif --}}
                </td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:4%; padding-left:5px;">Done</td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:5%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; border: 1px solid; width:4%; text-align: center;">
                      {{-- @if(strtolower($value->status_pengajuan_realisasi) == strtolower('pending'))
                        ✔
                    @endif --}}
                </td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:4%; padding-left:5px;">Pending</td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:5%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; border: 1px solid; width:4%; text-align: center;">
                      {{-- @if(strtolower($value->status_pengajuan) == strtolower('cancel'))
                        ✔
                    @endif --}}
                </td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:4%; padding-left:5px;">Cancel</td>

                <td style="vertical-align: middle; font-size: 8pt; border: none; width:15.5%;"></td>
                <td style="vertical-align: middle; font-size: 8pt;  border-left: 1px solid; border-right: 1px solid; width:20%; border-top: none; border-bottom: 1px solid white;"></td>
            </tr>
        </thead>
</table>
<table width="100%" style="">
    <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:80%;"></td>
                <td style="vertical-align: middle; font-size: 8pt; border-left: 1px solid; border-right: 1px solid; width:20%; border-top: none; height: 20px; border-bottom: none;"></td>
            </tr>
        </thead>
</table>
<table width="100%" style="">
    <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:80%;"></td>
                <td style="vertical-align: middle; font-size: 8pt;  border-left: 1px solid; border-right: 1px solid; width:20%; border-top: 1px solid; height: 20px; border-bottom: 1px solid;"></td>
            </tr>
        </thead>
</table>
</body>
@endforeach
</html>
