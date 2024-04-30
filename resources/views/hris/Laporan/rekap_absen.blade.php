
<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
<head>

</head>
    <body>
        <table>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-weight:bold; font-size:13pt; text-decoration:underline">PT. NIRWANA ALABARE GARMENT</td>
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-weight:bold; font-size:11pt;">Attendance Daily Report</td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:8pt;">1) Summary Jumlah Karyawan Sewing / Non Sewing ( Aktif )</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;background-color:orange; font-weight:bold; padding-top:6px; padding-bottom:6px;border:1px solid black" width="20">PERIODE</td>
                <td align="center" colspan="{{$bulan_pertama}}" style="font-family: Arial Nova; font-size:8pt;background-color:orange; font-weight:bold; padding-top:6px; padding-bottom:6px;border:1px solid black">JANUARI</td>
                <td align="center" colspan="{{$bulan_kedua}}" style="font-family: Arial Nova; font-size:8pt;background-color:orange; font-weight:bold; padding-top:6px; padding-bottom:6px;border:1px solid black">FEBRUARI</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">KETERANGAN</td>
                @foreach ($tanggal_absensi as $tanggal)
                    <?php 
                        $tgl=substr($tanggal,8);
                        $tgl_string=strtotime($tanggal);
                    ?>
                    @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center" width="6">{{$tgl}}</td>
                    @else
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center" width="6">{{$tgl}}</td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">NON SEWING</td>
                @foreach ($tanggal_absensi as $value)
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">NON STAFF</td>
                @foreach ($non_sewing_nonstaff as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">STAFF</td>
                @foreach ($non_sewing_staff as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">NON SEWING TOTAL</td>
                @foreach ($non_sewing_total as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">SEWING</td>
                @foreach ($tanggal_absensi as $value)
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">NON STAFF</td>
                @foreach ($sewing_nonstaff as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">STAFF</td>
                @foreach ($sewing_staff as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">SEWING TOTAL</td>
                @foreach ($sewing_total as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">GRAND TOTAL</td>
                @foreach ($grand_total as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt">% Jumlah Karyawan (Aktif)</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:yellow">NON SEWING</td>
                @foreach ($persentase_non_sewing as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue">SEWING</td>
                @foreach ($persentase_sewing as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:8pt;font-style:italic;padding-top:3px;padding-bottom:3px">Tabel 1 : Menunjukan jumlah karyawan aktif ( kecuali resign )</td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:8pt;padding-top:3px;padding-bottom:3px">2) Summary Karyawan Hadir Perhari</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;background-color:blue;color:white; font-weight:bold; padding-top:6px; padding-bottom:6px;border:1px solid black">PERIODE</td>
                <td align="center" colspan="{{$bulan_pertama}}" style="font-family: Arial Nova; font-size:8pt;background-color:blue;color:white; font-weight:bold; padding-top:6px; padding-bottom:6px;border:1px solid black">JANUARI</td>
                <td align="center" colspan="{{$bulan_kedua}}" style="font-family: Arial Nova; font-size:8pt;background-color:blue;color:white; font-weight:bold; padding-top:6px; padding-bottom:6px;border:1px solid black">FEBRUARI</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">KETERANGAN</td>
                @foreach ($tanggal_absensi as $tanggal)
                    <?php 
                        $tgl=substr($tanggal,8);
                        $tgl_string=strtotime($tanggal);
                    ?>
                    @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$tgl}}</td>
                    @else
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$tgl}}</td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">NON SEWING</td>
                @foreach ($tanggal_absensi as $value)
                    <td style="border:1px solid black"></td>
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">NON STAFF</td>
                @foreach ($non_sewing_nonstaff_present as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">STAFF</td>
                @foreach ($non_sewing_staff_present as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">NON SEWING TOTAL</td>
                @foreach ($non_sewing_present as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">SEWING</td>
                @foreach ($tanggal_absensi as $value)
                    <td style="border:1px solid black"></td>
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">NON STAFF</td>
                @foreach ($sewing_nonstaff_present as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">STAFF</td>
                @foreach ($sewing_staff_present as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">SEWING TOTAL</td>
                @foreach ($sewing_present as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">GRAND TOTAL</td>
                @foreach ($grand_total_present as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:8pt;padding-top:3px;padding-bottom:3px">2.1) Persentase kehadiran sewing/Non Sewing</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;background-color:hotpink; font-weight:bold; padding-top:6px; padding-bottom:6px;border:1px solid black">PERIODE</td>
                <td align="center" colspan="{{$bulan_pertama}}" style="font-family: Arial Nova; font-size:8pt;background-color:hotpink; font-weight:bold; padding-top:6px; padding-bottom:6px;border:1px solid black">JANUARI</td>
                <td align="center" colspan="{{$bulan_kedua}}" style="font-family: Arial Nova; font-size:8pt;background-color:hotpink; font-weight:bold; padding-top:6px; padding-bottom:6px;border:1px solid black">FEBRUARI</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">%</td>
                @foreach ($tanggal_absensi as $tanggal)
                    <?php 
                        $tgl=substr($tanggal,8);
                        $tgl_string=strtotime($tanggal);
                    ?>
                    @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center" width="6">{{$tgl}}</td>
                    @else
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center" width="6">{{$tgl}}</td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td colspan="{{$jumlah_tanggal_absensi}}" style="font-family: Arial Nova; font-size:8pt;border:1px solid black">% Jumlah karyawan hadir perhari</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">% NON SEWING</td>
                @foreach ($persentase_non_sewing_present as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}%</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}%</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">% SEWING</td>
                @foreach ($persentase_sewing_present as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}%</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}%</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:8pt;font-style:italic;padding-top:3px;padding-bottom:3px">Tabel 2 : Menunjukan jumlah kehadiran karyawan per hari</td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:8pt;padding-top:3px;padding-bottom:3px">3) Summary ketidakhadiran</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;background-color:mediumseagreen; font-weight:bold; padding-top:6px; padding-bottom:6px;color:white;border:1px solid black">PERIODE</td>
                <td align="center" colspan="{{$bulan_pertama}}" style="font-family: Arial Nova; font-size:8pt;background-color:mediumseagreen; font-weight:bold; padding-top:6px; padding-bottom:6px;color:white;border:1px solid black">JANUARI</td>
                <td align="center" colspan="{{$bulan_kedua}}" style="font-family: Arial Nova; font-size:8pt;background-color:mediumseagreen; font-weight:bold; padding-top:6px; padding-bottom:6px;color:white;border:1px solid black">FEBRUARI</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">ABSENSI</td>
                @foreach ($tanggal_absensi as $tanggal)
                    <?php 
                        $tgl=substr($tanggal,8);
                        $tgl_string=strtotime($tanggal);
                    ?>
                    @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$tgl}}</td>
                    @else
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$tgl}}</td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">NON SEWING</td>
                @foreach ($tanggal_absensi as $value)
                    <td style="border:1px solid black"></td>
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">NON STAFF</td>
                @foreach ($non_sewing_nonstaff_absent as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">STAFF</td>
                @foreach ($non_sewing_staff_absent as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">NON SEWING TOTAL</td>
                @foreach ($non_sewing_absent as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">SEWING</td>
                @foreach ($tanggal_absensi as $value)
                    <td style="border:1px solid black"></td>
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">NON STAFF</td>
                @foreach ($sewing_nonstaff_absent as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">STAFF</td>
                @foreach ($sewing_staff_absent as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">SEWING TOTAL</td>
                @foreach ($sewing_absent as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">GRAND TOTAL</td>
                @foreach ($grand_total_absent as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:8pt;font-style:italic;padding-top:3px;padding-bottom:3px">Tabel 3: Menunjukan jumlah ketidakhadiran karyawan per hari</td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:8pt;padding-top:3px;padding-bottom:3px">3.1) Persentase ketidakhadiran</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;background-color:firebrick;font-weight:bold; padding-top:6px; padding-bottom:6px;color:white;border:1px solid black">PERIODE</td>
                <td align="center" colspan="{{$bulan_pertama}}" style="font-family: Arial Nova; font-size:8pt;background-color:firebrick; font-weight:bold; padding-top:6px; padding-bottom:6px;color:white;border:1px solid black">JANUARI</td>
                <td align="center" colspan="{{$bulan_kedua}}" style="font-family: Arial Nova; font-size:8pt;background-color:firebrick; font-weight:bold; padding-top:6px; padding-bottom:6px;color:white;border:1px solid black">FEBRUARI</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;">%</td>
                @foreach ($tanggal_absensi as $tanggal)
                    <?php 
                        $tgl=substr($tanggal,8);
                        $tgl_string=strtotime($tanggal);
                    ?>
                    @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$tgl}}</td>
                    @else
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$tgl}}</td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">% NON SEWING</td>
                @foreach ($persentase_non_sewing_absent as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}%</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}%</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">% SEWING</td>
                @foreach ($persentase_sewing_absent as $value)
                   <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}%</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}%</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:8pt;font-style:italic;padding-top:3px;padding-bottom:3px">Tabel 3.1 : Menunjukan persentase ketidakhadiran karyawan per hari</td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:8pt;padding-top:3px;padding-bottom:3px">3) Status ketidakhadiran</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;background-color:firebrick;font-weight:bold; padding-top:6px; padding-bottom:6px;color:white;border:1px solid black">PERIODE</td>
                <td align="center" colspan="{{$bulan_pertama}}" style="font-family: Arial Nova; font-size:8pt;background-color:firebrick; font-weight:bold; padding-top:6px; padding-bottom:6px;color:white;border:1px solid black">JANUARI</td>
                <td align="center" colspan="{{$bulan_kedua}}" style="font-family: Arial Nova; font-size:8pt;background-color:firebrick; font-weight:bold; padding-top:6px; padding-bottom:6px;color:white;border:1px solid black">FEBRUARI</td>
                <td rowspan="2" valign="center" align="center" style="font-family: Arial Nova; font-size:8pt;background-color:firebrick; font-weight:bold; padding-top:6px; padding-bottom:6px;color:white;border:1px solid black">TOTAL</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;">KETERANGAN</td>
                @foreach ($tanggal_absensi as $tanggal)
                    <?php 
                        $tgl=substr($tanggal,8);
                        $tgl_string=strtotime($tanggal);
                    ?>
                    @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$tgl}}</td>
                    @else
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$tgl}}</td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">SAKIT</td>
                @foreach ($karyawan_sakit as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">IJIN</td>
                @foreach ($karyawan_izin as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">CUTI</td>
                @foreach ($karyawan_cuti as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">MANGKIR</td>
                @foreach ($karyawan_mangkir as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">DINAS LUAR</td>
                @foreach ($karyawan_dinas_luar as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">LIBUR</td>
                @foreach ($karyawan_libur as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">RESIGN</td>
                @foreach ($karyawan_resign as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:8pt;font-style:italic;padding-top:3px;padding-bottom:3px">Tabel 3.2 : Menunjukan tabel alasan ketidak hadiran karyawan perhari</td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:8pt;padding-top:3px;padding-bottom:3px">4) Recruitment</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;background-color:lightblue;font-weight:bold; padding-top:6px; padding-bottom:6px;border:1px solid black">PERIODE</td>
                <td align="center" colspan="{{$bulan_pertama}}" style="font-family: Arial Nova; font-size:8pt;background-color:lightblue; font-weight:bold; padding-top:6px; padding-bottom:6px;border:1px solid black">JANUARI</td>
                <td align="center" colspan="{{$bulan_kedua}}" style="font-family: Arial Nova; font-size:8pt;background-color:lightblue; font-weight:bold; padding-top:6px; padding-bottom:6px;border:1px solid black">FEBRUARI</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;">KETERANGAN</td>
                @foreach ($tanggal_absensi as $tanggal)
                    <?php 
                        $tgl=substr($tanggal,8);
                        $tgl_string=strtotime($tanggal);
                    ?>
                    @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$tgl}}</td>
                    @else
                    <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$tgl}}</td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">SEWING</td>
                @foreach ($recruitment_sewing as $value)
                    <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black">NON SEWING</td>
                @foreach ($recruitment_nonsewing as $value)
                   <?php 
                        $tgl_string=strtotime($value['tanggal']);
                    ?>
                    @if($value['tanggal']<=$date_now && (date('D',strtotime($value['tanggal'])=='Sun')||date('D',strtotime($value['tanggal'])=='Sat')))
                        @if($value['total']==0)
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">-</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">-</td>
                            @endif
                        @else
                            @if(date('D', $tgl_string)=='Sat' || date('D', $tgl_string)=='Sun')
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;color:red" align="center">{{$value['total']}}</td>
                            @else
                            <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center">{{$value['total']}}</td>
                            @endif
                        @endif
                    @else
                        <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black" align="center"></td>
                    @endif
                @endforeach
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:8pt;font-style:italic;padding-top:3px;padding-bottom:3px">Tabel 4 : Menunjukan recruitment karyawan perhari</td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:8pt;padding-top:3px;padding-bottom:3px">5) Komposisi Direct - Indirect</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:khaki" align="center" valign="center">KETERANGAN</td>
                <td style="font-family: Arial Nova; font-size:7pt;border:1px solid black;background-color:khaki;word-wrap:break-word" align="center" valign="center">Jumlah Karyawan Aktif</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:khaki" align="center" valign="center">%</td>
                <td style="font-family: Arial Nova; font-size:7pt;border:1px solid black;background-color:khaki;word-wrap:break-word" align="center" valign="center">Jumlah Karyawan Hadir</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:khaki" align="center" valign="center">%</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue">DIRECT</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue"></td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue"></td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue"></td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue"></td>
            </tr>
            @foreach ($all_dept_direct as $key=>$value)
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;">{{$value['dept_name']}}</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;" align="center">
                    @if($value['active_employee']!=0)
                    {{$value['active_employee']}}
                    @endif
                </td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;" align="center">
                    @if($value['persentase_active_employee']!=0)
                    {{$value['persentase_active_employee']}}%
                    @endif
                </td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;" align="center">
                    @if($value['present_employee']!=0)
                    {{$value['present_employee']}}
                    @endif
                </td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;" align="center">
                    @if($value['persentase_present_employee']!=0)
                    {{$value['persentase_present_employee']}}%
                    @endif
                </td>
            </tr>
            @endforeach
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue">{{$total_direct_employee['title']}}</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue" align="center">{{$total_direct_employee['active_employee']}}</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue" align="center">{{$total_direct_employee['persentase_active_employee']}}%</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue" align="center">{{$total_direct_employee['present_employee']}}</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue" align="center">{{$total_direct_employee['persentase_present_employee']}}%</td>
            </tr>
            @foreach ($all_dept_indirect as $key=>$value)
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;">{{$value['dept_name']}}</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;" align="center">
                    @if($value['active_employee']!=0)
                    {{$value['active_employee']}}
                    @endif
                </td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;" align="center">
                    @if($value['persentase_active_employee']!=0)
                    {{$value['persentase_active_employee']}}%
                    @endif
                </td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;" align="center">
                    @if($value['present_employee']!=0)
                    {{$value['present_employee']}}
                    @endif
                </td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;" align="center">
                    @if($value['persentase_present_employee']!=0)
                    {{$value['persentase_present_employee']}}%
                    @endif
                </td>
            </tr>
            @endforeach
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue">{{$total_indirect_employee['title']}}</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue" align="center">{{$total_indirect_employee['active_employee']}}</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue" align="center">{{$total_indirect_employee['persentase_active_employee']}}%</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue" align="center">{{$total_indirect_employee['present_employee']}}</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:lightblue" align="center">{{$total_indirect_employee['persentase_present_employee']}}%</td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:khaki">{{$grand_total_direct_employee['title']}}</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:khaki" align="center">{{$grand_total_direct_employee['active_employee']}}</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:khaki" align="center">{{$grand_total_direct_employee['persentase_active_employee']}}</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:khaki" align="center">{{$grand_total_direct_employee['present_employee']}}</td>
                <td style="font-family: Arial Nova; font-size:8pt;border:1px solid black;background-color:khaki" align="center">{{$grand_total_direct_employee['persentase_present_employee']}}</td>
            </tr>
            <tr>
            </tr>
            <?php 
            use Carbon\Carbon;
            $hari_ini=Carbon::now()->translatedFormat('l');
            $tanggal_ini=Carbon::now()->translatedFormat('d F Y');
            ?>
            <tr>
                <td colspan="4" style="font-family: Arial Nova; font-size:9pt;font-weight:bold;padding-bottom:8px;background-color:lightblue;border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;">PT. NIRWANA ALABARE GARMENT</td>
                <td colspan="8" style="font-family: Arial Nova; font-size:9pt;font-weight:bold;padding-bottom:8px;background-color:lightblue;border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;" align="center">{{$hari_ini}}</td>
            </tr>
            <tr>
                <td colspan="4" style="font-family: Arial Nova; font-size:9pt;font-weight:bold;padding-bottom:8px;background-color:lightblue;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;">{{$date_string}}</td>
                <td colspan="8" style="font-family: Arial Nova; font-size:9pt;font-weight:bold;padding-bottom:8px;background-color:lightblue;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;" align="center">{{$tanggal_ini}}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-family: Arial Nova; font-size:8pt;border:1px solid black;font-weight:bold;padding-bottom:8px;background-color:lightblue" align="center">DEPARTMENT</td>
                <td colspan="2" style="font-family: Arial Nova; font-size:8pt;border:1px solid black;font-weight:bold;padding-bottom:8px;background-color:lightblue" align="center">ORANG</td>
                <td colspan="2" style="font-family: Arial Nova; font-size:8pt;border:1px solid black;font-weight:bold;padding-bottom:8px;background-color:lightblue" align="center">HADIR</td>
                <td colspan="2" style="font-family: Arial Nova; font-size:8pt;border:1px solid black;font-weight:bold;padding-bottom:8px;background-color:lightblue" align="center">ABSEN</td>
                <td colspan="2" style="font-family: Arial Nova; font-size:8pt;border:1px solid black;font-weight:bold;padding-bottom:8px;background-color:firebrick;color:white" align="center">RESIGN</td>
                <td colspan="2" style="font-family: Arial Nova; font-size:8pt;border:1px solid black;font-weight:bold;padding-bottom:8px;background-color:hotpink" align="center">RECRUITMENT</td>
            </tr>
            @foreach ($all_dept as $key=>$value)
            <tr>
                <td colspan="2" style="font-family: Arial Nova; font-size:8pt;border:1px solid black;">{{$value['sub_dept_name']}}</td>
                <td colspan="2" style="font-family: Arial Nova; font-size:8pt;border:1px solid black;" align="center">
                    @if($value['employee']!=0)
                    {{$value['employee']}}
                    @endif
                </td>
                <td colspan="2" style="font-family: Arial Nova; font-size:8pt;border:1px solid black;" align="center">
                    @if($value['present_employee']!=0)
                    {{$value['present_employee']}}
                    @endif
                </td>
                <td colspan="2" style="font-family: Arial Nova; font-size:8pt;border:1px solid black;" align="center">
                    @if($value['absent_employee']!=0)
                    {{$value['absent_employee']}}
                    @endif
                </td>
                <td colspan="2" style="font-family: Arial Nova; font-size:8pt;border:1px solid black;" align="center">
                    @if($value['resign_employee']!=0)
                    {{$value['resign_employee']}}
                    @endif
                </td>
                <td colspan="2" style="font-family: Arial Nova; font-size:8pt;border:1px solid black;" align="center">
                    @if($value['recruitment_employee']!=0)
                    {{$value['recruitment_employee']}}
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </body>
</html>
