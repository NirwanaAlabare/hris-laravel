
<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
<head>

</head>
    <body>
        <table>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="font-family: Arial Nova; color:blue; font-weight:bold; font-size:10pt; text-decoration:underline">PT NIRWANA ALABARE GARMENT</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="font-family: Arial Nova; color:blue; font-weight:bold; font-size:10pt; text-decoration:underline">PT NIRWANA ALABARE GARMENT</td>
            </tr>
            <tr>
                <?php
                $periode_awal_bulan=((int)$periode_bulan_payroll)-2;
                $periode_bulan=((int)$periode_bulan_payroll)-1;
                $bulan=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','December'];
                ?>
                @if($periode_bulan==0)
                <td style="font-family: Arial Nova;font-weight:bold;font-size:8pt">Periode 26 Desember {{($periode_tahun_payroll)-1}} - 25 {{$bulan[$periode_bulan]}} {{$periode_tahun_payroll}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="font-family: Arial Nova;font-weight:bold;font-size:8pt">Periode 26 Desember {{($periode_tahun_payroll)-1}} - 25 {{$bulan[$periode_bulan]}} {{$periode_tahun_payroll}}</td>
                @else
                <td style="font-family: Arial Nova;font-weight:bold;font-size:8pt">Periode 26 {{$bulan[$periode_awal_bulan]}} {{$periode_tahun_payroll}} - 25 {{$bulan[$periode_bulan]}} {{$periode_tahun_payroll}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="font-family: Arial Nova;font-weight:bold;font-size:8pt">Periode 26 {{$bulan[$periode_awal_bulan]}} {{$periode_tahun_payroll}} - 25 {{$bulan[$periode_bulan]}} {{$periode_tahun_payroll}}</td>
                @endif
            </tr>
        </table>
        <table>
            <tr>
                <td style="font-family: Arial Nova;font-size:8pt">BANK</td>
                <td style="font-family: Arial Nova;font-size:8pt">BNI</td>
                <td></td>
                <td style="font-family: Arial Nova;font-weight: bold;font-size:8pt">{{$bni}}</td>
                <td></td>
                <td></td>
                <td style="font-family: Arial Nova;font-size:8pt">BANK</td>
                <td style="font-family: Arial Nova;font-size:8pt">CIMB NIAGA</td>
                <td></td>
                <td style="font-family: Arial Nova;font-weight: bold;font-size:8pt">{{$cimb}}</td>
            </tr>
        </table>
        <table>
            <tr>
                <th width="12" style="font-family: Arial Nova;font-weight:bold;text-align:center;background-color:#C0C0C0;font-style:italic;border:2px solid black;font-size:8pt">NIP</th>
                <th width="12" style="font-family: Arial Nova;font-weight:bold;text-align:center;background-color:#C0C0C0;font-style:italic;border:2px solid black;font-size:8pt">REKENING</th>
                <th width="20" style="font-family: Arial Nova;font-weight:bold;text-align:center;background-color:#C0C0C0;font-style:italic;border:2px solid black;font-size:8pt">NAMA KARYAWAN</th>
                <th width="12" style="font-family: Arial Nova;font-weight:bold;text-align:center;background-color:#C0C0C0;font-style:italic;border:2px solid black;font-size:8pt">JUMLAH</th>
                <td></td>
                <td></td>
                <th width="12" style="font-family: Arial Nova;font-weight:bold;text-align:center;background-color:#C0C0C0;font-style:italic;border:2px solid black;font-size:8pt">NIP</th>
                <th width="12" style="font-family: Arial Nova;font-weight:bold;text-align:center;background-color:#C0C0C0;font-style:italic;border:2px solid black;font-size:8pt">REKENING</th>
                <th width="20" style="font-family: Arial Nova;font-weight:bold;text-align:center;background-color:#C0C0C0;font-style:italic;border:2px solid black;font-size:8pt">NAMA KARYAWAN</th>
                <th width="12" style="font-family: Arial Nova;font-weight:bold;text-align:center;background-color:#C0C0C0;font-style:italic;border:2px solid black;font-size:8pt">JUMLAH</th>
            </tr>
            <?php $no=-1; $no2=0;?>
            @for($i=0;$i<count($payroll);$i++)
            <?php $no+=2; $no2+=2?>
            <tr>
                @if(isset($payroll[$no]))
                <td style="font-family: Arial Nova;border:2px solid black;font-size:8pt">
                    {{$payroll[$no]['nik']}}&nbsp;
                </td>
                <td style="font-family: Arial Nova;border:2px solid black;font-size:8pt">
                    {{$payroll[$no]['rekening']}}&nbsp;
                </td>
                <td style="font-family: Arial Nova;border:2px solid black;font-size:8pt">
                    {{$payroll[$no]['nama_karyawan']}}
                </td>
                <td style="font-family: Arial Nova;border:2px solid black;font-size:8pt">
                    {{$payroll[$no]['jumlah']}}
                </td>
                @else
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                @endif
                <td></td>
                <td></td>
                @if(isset($payroll[$no2]))
                <td style="font-family: Arial Nova;border:2px solid black;font-size:8pt">
                    {{$payroll[$no2]['nik']}}&nbsp;
                </td>
                <td style="font-family: Arial Nova;border:2px solid black;font-size:8pt">
                    {{$payroll[$no2]['rekening']}}&nbsp;
                </td>
                <td style="font-family: Arial Nova;border:2px solid black;font-size:8pt">
                    {{$payroll[$no2]['nama_karyawan']}}
                </td>
                <td style="font-family: Arial Nova;border:2px solid black;font-size:8pt">
                    {{$payroll[$no2]['jumlah']}}
                </td>
                @else
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                @endif
            </tr>
            @endfor
        </table>
    </body>
</html>
