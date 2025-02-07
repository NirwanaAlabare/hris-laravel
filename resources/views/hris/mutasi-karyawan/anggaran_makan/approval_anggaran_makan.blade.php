<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>
    <style>
        table tr th,
        table tr td {
            border: 1px solid;
            font-size:9pt;
            padding-left: 3;
            padding-right: 3;
            font-family: Arial, Helvetica, sans-serif
        }
        table tr th.textkecil,
        table tr td.textkecil {
            border: 1px solid;
            font-size:9pt;
            padding-left: 3;
            padding-right: 3;
            font-family: Arial, Helvetica, sans-serif
        }
        table tr th.boldtext,
        table tr td.boldtext {
            border: 1px solid;
            font-size:9pt;
            padding-left: 3;
            padding-right: 3;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif
        }
        table tr th.boldtextKecil,
        table tr td.boldtextKecil {
            border: 1px solid;
            font-size:9pt;
            padding-left: 3;
            padding-right: 3;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif
        }
        table tr th.boldtextKecil2,
        table tr td.boldtextKecil2 {
            border: 1px solid;
            font-size:9pt;
            padding-left: 3;
            padding-right: 3;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif
        }
        table tr th.boldtextyellowBG,
        table tr td.boldtextyellowBG {
            border: 1px solid;
            background-color: yellow;
            font-size:9pt;
            padding-left: 3;
            padding-right: 3;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif
        }
        table tr th.boldtextyellowBGKecil,
        table tr td.boldtextyellowBGKecil {
            border: 1px solid;
            background-color: yellow;
            font-size: 9pt;
            padding-left: 3;
            padding-right: 3;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif
        }
        table tr th.boldtextblueBG,
        table tr td.boldtextblueBG {
            border: 1px solid;
            background-color: rgb(118, 214, 255);
            font-size:9pt;
            padding-left: 3;
            padding-right: 3;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif
        }
        table tr th.boldtextblueBGKecil,
        table tr td.boldtextblueBGKecil {
            border: 1px solid;
            background-color: rgb(118, 214, 255);
            font-size:9pt;
            padding-left: 3;
            padding-right: 3;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif
        }
        table tr th.borderless,
        table tr td.borderless {
            border: none;
            font-size:13pt;
            height: 1;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif
        }
        table tr th.borderless2,
        table tr td.borderless2 {
            border: none;
            font-size:11pt;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif
        }
        table tr th.borderless3,
        table tr td.borderless3 {
            border: none;
            font-size:11pt;
            padding-bottom: 5;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif
        }
        table {
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <td class="borderless">PT. NIRWANA ALABARE GARMENT</td>
            </tr>
            <tr>
                <td class="borderless2">Laporan Makan Harian</td>
            </tr>
            <tr>
                <td class="borderless3">Hari / Tanggal : {{Carbon\Carbon::parse($tanggal)->translatedFormat('l, j F Y')}}</td>
            </tr>
        </thead>
    </table>
    @if (count($data)+count($data2)>13)
    <table width="700px">
        <tr>
            <td rowspan="2" width="13%" style="vertical-align: middle" class="boldtextblueBGKecil">Keterangan</td>
            <td rowspan="2" width="16%" style="vertical-align: middle" class="boldtextblueBGKecil">Department</td>
            <td colspan="3" align="center" class="boldtextblueBGKecil">NON STAFF</td>
            <td colspan="3" align="center" class="boldtextblueBGKecil">STAFF</td>
            <td colspan="2" align="center" class="boldtextblueBGKecil">GRAND TOTAL</td>
        </tr>
        <tr>
            <td width="7%" align="center" class="boldtextblueBGKecil">Jumlah Karyawan</td>
            <td width="8%" align="center" class="boldtextblueBGKecil">Harga</td>
            <td width="8%" align="center" class="boldtextblueBGKecil">Jumlah</td>
            <td width="7%" align="center" class="boldtextblueBGKecil">Jumlah Karyawan</td>
            <td width="8%" align="center" class="boldtextblueBGKecil">Harga</td>
            <td width="8%" align="center" class="boldtextblueBGKecil">Jumlah</td>
            <td width="7%" align="center" class="boldtextblueBGKecil">Jumlah Karyawan</td>
            <td width="8%" align="center" class="boldtextblueBGKecil">Total</td>
        </tr>
        @foreach ($data as $key=>$value)
            <tr>
                <td class="textkecil">@if($key==0)LEMBUR @endif</td>
                <td class="textkecil">{{$value->department}}</td>
                <td class="textkecil" align="right">
                    @if(str_replace(',', '.', number_format($value->non_staff))!=0)
                    {{str_replace(',', '.', number_format($value->non_staff))}}
                    @else
                    @endif
                </td>
                <td class="textkecil" align="right">
                    @if(str_replace(',', '.', number_format($value->harga))!=0)
                    {{str_replace(',', '.', number_format($value->harga))}}
                    @else
                    @endif
                </td>
                <td class="textkecil" align="right">
                    @if(str_replace(',', '.', number_format($value->jumlah))!=0)
                    {{str_replace(',', '.', number_format($value->jumlah))}}
                    @else
                    @endif
                </td>
                <td class="textkecil" align="right">
                    @if(str_replace(',', '.', number_format($value->staff))!=0)
                    {{str_replace(',', '.', number_format($value->staff))}}
                    @else
                    @endif
                </td>
                <td class="textkecil" align="right">
                    @if(str_replace(',', '.', number_format($value->harga2))!=0)
                    {{str_replace(',', '.', number_format($value->harga2))}}
                    @else
                    @endif
                </td>
                <td class="textkecil" align="right">
                    @if(str_replace(',', '.', number_format($value->jumlah2))!=0)
                    {{str_replace(',', '.', number_format($value->jumlah2))}}
                    @else
                    @endif
                </td>
                <td class="textkecil" align="right">{{$value->jumlah_karyawan}}</td>
                <td class="textkecil" align="right">{{str_replace(',', '.', number_format($value->total))}}</td>
            </tr>
        @endforeach
        @foreach ($data3 as $key=>$value)
            <tr>
                <td class="boldtextKecil">{{$value->shift}}</td>
                <td class="boldtextKecil">{{$value->department}}</td>
                <td class="boldtextKecil" align="right">{{$value->non_staff}}</td>
                <td class="boldtextKecil" align="right">{{str_replace(',', '.', number_format($value->harga))}}</td>
                <td class="boldtextKecil" align="right">{{str_replace(',', '.', number_format($value->jumlah))}}</td>
                <td class="boldtextKecil" align="right">{{$value->staff}}</td>
                <td class="boldtextKecil" align="right">{{str_replace(',', '.', number_format($value->harga2))}}</td>
                <td class="boldtextKecil" align="right">{{str_replace(',', '.', number_format($value->jumlah2))}}</td>
                <td class="boldtextKecil" align="right">{{$value->jumlah_karyawan}}</td>
                <td class="boldtextKecil" align="right">{{str_replace(',', '.', number_format($value->total))}}</td>
            </tr>
        @endforeach
        @foreach ($data2 as $key=>$value)
            <tr>
                <td class="textkecil">@if($key==0)SHIFT MALAM @endif</td>
                <td class="textkecil">{{$value->department}}</td>
                <td class="textkecil" align="right">
                    @if(str_replace(',', '.', number_format($value->non_staff))!=0)
                    {{str_replace(',', '.', number_format($value->non_staff))}}
                    @else
                    @endif
                </td>
                <td class="textkecil" align="right">
                    @if(str_replace(',', '.', number_format($value->harga))!=0)
                    {{str_replace(',', '.', number_format($value->harga))}}
                    @else
                    @endif
                </td>
                <td class="textkecil" align="right">
                    @if(str_replace(',', '.', number_format($value->jumlah))!=0)
                    {{str_replace(',', '.', number_format($value->jumlah))}}
                    @else
                    @endif
                </td>
                <td class="textkecil" align="right">
                    @if(str_replace(',', '.', number_format($value->staff))!=0)
                    {{str_replace(',', '.', number_format($value->staff))}}
                    @else
                    @endif
                </td>
                <td class="textkecil" align="right">
                    @if(str_replace(',', '.', number_format($value->harga2))!=0)
                    {{str_replace(',', '.', number_format($value->harga2))}}
                    @else
                    @endif
                </td>
                <td class="textkecil" align="right">
                    @if(str_replace(',', '.', number_format($value->jumlah2))!=0)
                    {{str_replace(',', '.', number_format($value->jumlah2))}}
                    @else
                    @endif
                </td>
                <td class="textkecil" align="right">{{$value->jumlah_karyawan}}</td>
                <td class="textkecil" align="right">{{str_replace(',', '.', number_format($value->total))}}</td>
            </tr>
        @endforeach
        @foreach ($data4 as $value)
            <tr>
                <td class="boldtextKecil">{{$value->shift}}</td>
                <td class="boldtextKecil">{{$value->department}}</td>
                <td class="boldtextKecil" align="right">{{$value->non_staff}}</td>
                <td class="boldtextKecil" align="right">{{str_replace(',', '.', number_format($value->harga))}}</td>
                <td class="boldtextKecil" align="right">{{str_replace(',', '.', number_format($value->jumlah))}}</td>
                <td class="boldtextKecil" align="right">{{$value->staff}}</td>
                <td class="boldtextKecil" align="right">{{str_replace(',', '.', number_format($value->harga2))}}</td>
                <td class="boldtextKecil" align="right">{{str_replace(',', '.', number_format($value->jumlah2))}}</td>
                <td class="boldtextKecil" align="right">{{$value->jumlah_karyawan}}</td>
                <td class="boldtextKecil" align="right">{{str_replace(',', '.', number_format($value->total))}}</td>
            </tr>
        @endforeach
        @foreach ($data5 as $value)
            <tr>
                <td class="boldtextyellowBGKecil">{{$value->shift}}</td>
                <td class="boldtextyellowBGKecil">{{$value->department}}</td>
                <td class="boldtextyellowBGKecil" align="right">{{$value->non_staff}}</td>
                <td class="boldtextyellowBGKecil" align="right">{{str_replace(',', '.', number_format($value->harga))}}</td>
                <td class="boldtextyellowBGKecil" align="right">{{str_replace(',', '.', number_format($value->jumlah))}}</td>
                <td class="boldtextyellowBGKecil" align="right">{{$value->staff}}</td>
                <td class="boldtextyellowBGKecil" align="right">{{str_replace(',', '.', number_format($value->harga2))}}</td>
                <td class="boldtextyellowBGKecil" align="right">{{str_replace(',', '.', number_format($value->jumlah2))}}</td>
                <td class="boldtextyellowBGKecil" align="right">{{$value->jumlah_karyawan}}</td>
                <td class="boldtextyellowBGKecil" align="right">{{str_replace(',', '.', number_format($value->total))}}</td>
            </tr>
        @endforeach
    </table>
    @else

    <table width="700px">
        <tr>
            <td rowspan="2" width="13%" style="vertical-align: middle" class="boldtextblueBG">Keterangan</td>
            <td rowspan="2" width="16%" style="vertical-align: middle" class="boldtextblueBG">Department</td>
            <td colspan="3" align="center" class="boldtextblueBG">NON STAFF</td>
            <td colspan="3" align="center" class="boldtextblueBG">STAFF</td>
            <td colspan="2" align="center" class="boldtextblueBG">GRAND TOTAL</td>
        </tr>
        <tr>
            <td width="7%" align="center" class="boldtextblueBG">Jumlah Karyawan</td>
            <td width="8%" align="center" class="boldtextblueBG">Harga</td>
            <td width="8%" align="center" class="boldtextblueBG">Jumlah</td>
            <td width="7%" align="center" class="boldtextblueBG">Jumlah Karyawan</td>
            <td width="8%" align="center" class="boldtextblueBG">Harga</td>
            <td width="8%" align="center" class="boldtextblueBG">Jumlah</td>
            <td width="7%" align="center" class="boldtextblueBG">Jumlah Karyawan</td>
            <td width="8%" align="center" class="boldtextblueBG">Total</td>
        </tr>
        @foreach ($data as $key=>$value)
            <tr>
                <td>@if($key==0)LEMBUR @endif</td>
                <td>{{$value->department}}</td>
                <td align="right">
                    @if(str_replace(',', '.', number_format($value->non_staff))!=0)
                    {{str_replace(',', '.', number_format($value->non_staff))}}
                    @else
                    @endif
                </td>
                <td align="right">
                    @if(str_replace(',', '.', number_format($value->harga))!=0)
                    {{str_replace(',', '.', number_format($value->harga))}}
                    @else
                    @endif
                </td>
                <td align="right">
                    @if(str_replace(',', '.', number_format($value->jumlah))!=0)
                    {{str_replace(',', '.', number_format($value->jumlah))}}
                    @else
                    @endif
                </td>
                <td align="right">
                    @if(str_replace(',', '.', number_format($value->staff))!=0)
                    {{str_replace(',', '.', number_format($value->staff))}}
                    @else
                    @endif
                </td>
                <td align="right">
                    @if(str_replace(',', '.', number_format($value->harga2))!=0)
                    {{str_replace(',', '.', number_format($value->harga2))}}
                    @else
                    @endif
                </td>
                <td align="right">
                    @if(str_replace(',', '.', number_format($value->jumlah2))!=0)
                    {{str_replace(',', '.', number_format($value->jumlah2))}}
                    @else
                    @endif
                </td>
                <td align="right">{{$value->jumlah_karyawan}}</td>
                <td align="right">{{str_replace(',', '.', number_format($value->total))}}</td>
            </tr>
        @endforeach
        @foreach ($data3 as $key=>$value)
            <tr>
                <td class="boldtext">{{$value->shift}}</td>
                <td class="boldtext">{{$value->department}}</td>
                <td class="boldtext" align="right">{{$value->non_staff}}</td>
                <td class="boldtext" align="right">{{str_replace(',', '.', number_format($value->harga))}}</td>
                <td class="boldtext" align="right">{{str_replace(',', '.', number_format($value->jumlah))}}</td>
                <td class="boldtext" align="right">{{$value->staff}}</td>
                <td class="boldtext" align="right">{{str_replace(',', '.', number_format($value->harga2))}}</td>
                <td class="boldtext" align="right">{{str_replace(',', '.', number_format($value->jumlah2))}}</td>
                <td class="boldtext" align="right">{{$value->jumlah_karyawan}}</td>
                <td class="boldtext" align="right">{{str_replace(',', '.', number_format($value->total))}}</td>
            </tr>
        @endforeach
        @foreach ($data2 as $key=>$value)
            <tr>
                <td>@if($key==0)SHIFT MALAM @endif</td>
                <td>{{$value->department}}</td>
                <td align="right">
                    @if(str_replace(',', '.', number_format($value->non_staff))!=0)
                    {{str_replace(',', '.', number_format($value->non_staff))}}
                    @else
                    @endif
                </td>
                <td align="right">
                    @if(str_replace(',', '.', number_format($value->harga))!=0)
                    {{str_replace(',', '.', number_format($value->harga))}}
                    @else
                    @endif
                </td>
                <td align="right">
                    @if(str_replace(',', '.', number_format($value->jumlah))!=0)
                    {{str_replace(',', '.', number_format($value->jumlah))}}
                    @else
                    @endif
                </td>
                <td align="right">
                    @if(str_replace(',', '.', number_format($value->staff))!=0)
                    {{str_replace(',', '.', number_format($value->staff))}}
                    @else
                    @endif
                </td>
                <td align="right">
                    @if(str_replace(',', '.', number_format($value->harga2))!=0)
                    {{str_replace(',', '.', number_format($value->harga2))}}
                    @else
                    @endif
                </td>
                <td align="right">
                    @if(str_replace(',', '.', number_format($value->jumlah2))!=0)
                    {{str_replace(',', '.', number_format($value->jumlah2))}}
                    @else
                    @endif
                </td>
                <td align="right">{{$value->jumlah_karyawan}}</td>
                <td align="right">{{str_replace(',', '.', number_format($value->total))}}</td>
            </tr>
        @endforeach
        @foreach ($data4 as $value)
            <tr>
                <td class="boldtext">{{$value->shift}}</td>
                <td class="boldtext">{{$value->department}}</td>
                <td class="boldtext" align="right">{{$value->non_staff}}</td>
                <td class="boldtext" align="right">{{str_replace(',', '.', number_format($value->harga))}}</td>
                <td class="boldtext" align="right">{{str_replace(',', '.', number_format($value->jumlah))}}</td>
                <td class="boldtext" align="right">{{$value->staff}}</td>
                <td class="boldtext" align="right">{{str_replace(',', '.', number_format($value->harga2))}}</td>
                <td class="boldtext" align="right">{{str_replace(',', '.', number_format($value->jumlah2))}}</td>
                <td class="boldtext" align="right">{{$value->jumlah_karyawan}}</td>
                <td class="boldtext" align="right">{{str_replace(',', '.', number_format($value->total))}}</td>
            </tr>
        @endforeach
        @foreach ($data5 as $value)
            <tr>
                <td class="boldtextyellowBG">{{$value->shift}}</td>
                <td class="boldtextyellowBG">{{$value->department}}</td>
                <td class="boldtextyellowBG" align="right">{{$value->non_staff}}</td>
                <td class="boldtextyellowBG" align="right">{{str_replace(',', '.', number_format($value->harga))}}</td>
                <td class="boldtextyellowBG" align="right">{{str_replace(',', '.', number_format($value->jumlah))}}</td>
                <td class="boldtextyellowBG" align="right">{{$value->staff}}</td>
                <td class="boldtextyellowBG" align="right">{{str_replace(',', '.', number_format($value->harga2))}}</td>
                <td class="boldtextyellowBG" align="right">{{str_replace(',', '.', number_format($value->jumlah2))}}</td>
                <td class="boldtextyellowBG" align="right">{{$value->jumlah_karyawan}}</td>
                <td class="boldtextyellowBG" align="right">{{str_replace(',', '.', number_format($value->total))}}</td>
            </tr>
        @endforeach
    </table>
    @endif
    <table width="700px" style="margin-top: 20">
        <tr>
            <td colspan="6" class="borderless"></td>
        </tr>
        <tr>
            <td colspan="3" class="boldtextKecil2" align="center">PERSETUJUAN PERMINTAAN</td>
            <td colspan="3" class="boldtextKecil2" align="center">PEMBAYARAN</td>
        </tr>
        <tr>
            <td width="17%" class="boldtext" class="boldtextKecil2" align="center">PEMOHON</td>
            <td width="34%" class="boldtext" class="boldtextKecil2" colspan="2" align="center">DISETUJUI</td>
            <td width="17%" class="boldtext" class="boldtextKecil2" align="center">KASIR</td>
            <td width="16%" class="boldtext" class="boldtextKecil2" align="center">FIN & ACC MANAGER</td>
            <td width="16%" class="boldtext" class="boldtextKecil2" align="center">PENERIMA</td>
        </tr>
        <tr>
            <td style="height: 50"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="font-size: 9pt">Nama&nbsp;&nbsp;&nbsp;&nbsp;: Mega F.H.<br>Tanggal&nbsp;:</td>
            <td style="font-size: 9pt">Nama&nbsp;&nbsp;&nbsp;&nbsp;: Bobby T.<br>Tanggal&nbsp;:</td>
            <td style="font-size: 9pt">Nama&nbsp;&nbsp;&nbsp;&nbsp;: Ronald H.<br>Tanggal&nbsp;:</td>
            <td style="font-size: 9pt">Nama&nbsp;&nbsp;&nbsp;&nbsp;:<br>Tanggal&nbsp;:</td>
            <td style="font-size: 9pt">Nama&nbsp;&nbsp;&nbsp;&nbsp;:<br>Tanggal&nbsp;:</td>
            <td style="font-size: 9pt">Nama&nbsp;&nbsp;&nbsp;&nbsp;:<br>Tanggal&nbsp;:</td>
        </tr>
    </table>
</body>
</html>