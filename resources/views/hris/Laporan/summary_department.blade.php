
<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
<head>

</head>
    <body>
        <table>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family: Arial Nova; font-weight:bold; font-size:12pt;">PT NIRWANA ALABARE GARMENT</td>
            </tr>
            <tr>
                <?php
                $periode_awal_bulan=((int)$periode_bulan_payroll)-1;
                $periode_bulan=((int)$periode_bulan_payroll);
                $bulan=['December','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','December'];
                ?>
                <td></td>
                <td style="font-family: Arial Nova;font-weight:bold;font-size:10pt">Periode 26 {{$bulan[$periode_awal_bulan]}} {{$periode_tahun_payroll}} - 25 {{$bulan[$periode_bulan]}} {{$periode_tahun_payroll}}</td>
            </tr>
            <tr>
                <td height='9px'></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family: Arial Nova;font-weight:bold;font-size:10pt">STAFF/NON STAFF</td>
                <td style="font-family: Arial Nova;font-weight:bold;font-size:10pt">{{$status_staff}}</td>
            </tr>
            <tr>
                <td height='9px'></td>
                <td></td>
            </tr>
            <tr>
                <td style="width:2px"></td>
                <td style="border: 2px solid black;background-color:#ffaddd;font-weight:bold;width:30px; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;height:37px;text-align:center;vertical-align:top">Department</td>
                <td style="border: 2px solid black;background-color:#ffaddd;font-weight:bold;width:12px; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;height:37px;text-align:center;vertical-align:top">Jumlah Karyawan</td>
                <td style="border: 2px solid black;background-color:#ffaddd;font-weight:bold;width:13px; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;height:37px;text-align:center;vertical-align:top" >Bruto</td>
                <td style="border: 2px solid black;background-color:#ffaddd;font-weight:bold;width:10px; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;height:37px;text-align:center;vertical-align:top" >PPH</td>
                <td style="border: 2px solid black;background-color:#ffaddd;font-weight:bold;width:13px; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;height:37px;text-align:center;vertical-align:top" >Netto</td>
                <td style="border: 2px solid black;background-color:#ffaddd;font-weight:bold;width:13px; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;height:37px;text-align:center;vertical-align:top" >BPJS TK</td>
                <td style="border: 2px solid black;background-color:#ffaddd;font-weight:bold;width:13px; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;height:37px;text-align:center;vertical-align:top" >BPJS KS</td>
                <td style="border: 2px solid black;background-color:#ffaddd;font-weight:bold;width:13px; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;height:37px;text-align:center;vertical-align:top" >Total Potongan</td>
                <td style="border: 2px solid black;background-color:#ffaddd;font-weight:bold;width:13px; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;height:37px;text-align:center;vertical-align:top" >Jumlah</td>
                <td></td>
                <td style="border: 2px solid black;background-color:#ffaddd;font-weight:bold;width:10px; font-family: Arial Nova;font-size:7pt;word-wrap: break-word;height:37px;text-align:center;vertical-align:top" >Jumlah Karyawan Bulan Sebelum</td>
                <td style="border: 2px solid black;background-color:#ffaddd;font-weight:bold;width:13px; font-family: Arial Nova;font-size:7pt;word-wrap: break-word;height:37px;text-align:center;vertical-align:top" >Jumlah Gaji Bulan Sebelum</td>
                <td style="border: 2px solid black;background-color:#ffaddd;font-weight:bold;width:10px; font-family: Arial Nova;font-size:7pt;word-wrap: break-word;height:37px;text-align:center;vertical-align:top" >Penurunan/<h6 style="color:red">Kenaikan</h6>&nbsp; jml Karyawan</td>
                <td style="border: 2px solid black;background-color:#ffaddd;font-weight:bold;width:13px; font-family: Arial Nova;font-size:7pt;word-wrap: break-word;height:37px;text-align:center;vertical-align:top" >Penurunan/Kenaikan Gaji</td>
            </tr>
            @foreach ($arrayData as $key=>$value)
            @if($value['nama_department']=='GRAND TOTAL')
            <tr>
                <td></td>
                <td style="border: 2px solid black; background-color:#5cf3ee; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;font-weight:bold">{{$value['nama_department']}}</td>
                <td style="border: 2px solid black; background-color:#5cf3ee; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:center;font-weight:bold">{{$value['jumlah_karyawan']}}</td>
                <td style="border: 2px solid black; background-color:#5cf3ee; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;font-weight:bold">{{$value['bruto']}}</td>
                <td style="border: 2px solid black; background-color:#5cf3ee; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;font-weight:bold">{{$value['pph']}}</td>
                <td style="border: 2px solid black; background-color:#5cf3ee; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;font-weight:bold">{{$value['upah_neto_rupiah']}}</td>
                <td style="border: 2px solid black; background-color:#5cf3ee; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;font-weight:bold">{{$value['total_bpjs_tk']}}</td>
                <td style="border: 2px solid black; background-color:#5cf3ee; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;font-weight:bold">{{$value['total_bpjs_ks']}}</td>
                <td style="border: 2px solid black; background-color:#5cf3ee; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;font-weight:bold">{{$value['potongan']}}</td>
                <td style="border: 2px solid black; background-color:#5cf3ee; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;font-weight:bold">{{$value['jumlah']}}</td>
                <td></td>
                <td style="border: 2px solid black; background-color:#5cf3ee; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;font-weight:bold">{{$value['jumlah_karyawan_sebelum']}}</td>
                <td style="border: 2px solid black; background-color:#5cf3ee; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;font-weight:bold">{{$value['jumlah_sebelum']}}</td>
                <td style="border: 2px solid black; background-color:#5cf3ee; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;font-weight:bold">{{$value['selisih_karyawan']}}</td>
                <td style="border: 2px solid black; background-color:#5cf3ee; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;font-weight:bold">{{$value['selisih_gaji']}}</td>
            </tr>
            @else
            <tr>
                <td></td>
                <td style="border: 2px solid black; font-family: Arial Nova;font-size:10pt;word-wrap: break-word">{{$value['nama_department']}}</td>
                <td style="border: 2px solid black; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:center">{{$value['jumlah_karyawan']}}</td>
                <td style="border: 2px solid black; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right">{{$value['bruto']}}</td>
                <td style="border: 2px solid black; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right">{{$value['pph']}}</td>
                <td style="border: 2px solid black; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right">{{$value['upah_neto_rupiah']}}</td>
                <td style="border: 2px solid black; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right">{{$value['total_bpjs_tk']}}</td>
                <td style="border: 2px solid black; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right">{{$value['total_bpjs_ks']}}</td>
                <td style="border: 2px solid black; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right">{{$value['potongan']}}</td>
                <td style="border: 2px solid black; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right">{{$value['jumlah']}}</td>
                <td></td>
                <td style="border: 2px solid black; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right">{{$value['jumlah_karyawan_sebelum']}}</td>
                <td style="border: 2px solid black; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right">{{$value['jumlah_sebelum']}}</td>
                <td style="border: 2px solid black; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right">{{$value['selisih_karyawan']}}</td>
                <td style="border: 2px solid black; font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right">{{$value['selisih_gaji']}}</td>
            </tr>
            @endif
            @endforeach
            <tr>
                <td></td>
                <td></td>
            </tr>
            <?php 
            $date_now=date('d F Y');
            ?>
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
                <td></td>
                <td></td>
                <td style="font-family: Arial Nova;font-size:10pt;font-weight:bold;">Solokan Jeruk, {{$date_now}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="10" rowspan="11" style="width: 15px;height:15px"></td>
            </tr>
            <tr>
                <td></td>
                <td style="border: 2px solid black;background-color:#33FF39;font-weight:bold;font-family: Arial Nova;font-size:10pt;word-wrap: break-word;height:30px;text-align:center;vertical-align:top">Pembayaran</td>
                <td style="border: 2px solid black;background-color:#33FF39;font-weight:bold;font-family: Arial Nova;font-size:10pt;word-wrap: break-word;height:30px;text-align:center;vertical-align:top">Jumlah Karyawan</td>
                <td style="border: 2px solid black;background-color:#33FF39;font-weight:bold;font-family: Arial Nova;font-size:10pt;word-wrap: break-word;height:30px;text-align:center;vertical-align:top">Jumlah</td>
            </tr>
            <tr>
                <td></td>
                <td style="border: 2px solid black;font-family: Arial Nova;font-size:10pt;word-wrap: break-word">BNI</td>
                <td style="border: 2px solid black;font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:center;">{{$jumlah_karyawan_bank_bni}}</td>
                <td style="border: 2px solid black;font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;">{{$gaji_bni}}</td>
            </tr>
            <tr>
                <td></td>
                <td style="border: 2px solid black;font-family: Arial Nova;font-size:10pt;word-wrap: break-word">CIMB</td> 
                <td style="border: 2px solid black;font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:center;">{{$jumlah_karyawan_bank_cimb}}</td>
                <td style="border: 2px solid black;font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;">{{$gaji_cimb}}</td>
            </tr>
            <tr>
                <td></td>
                <td style="border: 2px solid black;font-family: Arial Nova;font-size:10pt;word-wrap: break-word">TUNAI</td> 
                <td style="border: 2px solid black;font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:center;">{{$jumlah_karyawan_no_bank}}</td>
                <td style="border: 2px solid black;font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;">{{$gaji_other}}</td>
            </tr>
            <tr>
                <td></td>
                <td style="border: 2px solid black;font-family: Arial Nova;font-size:10pt;word-wrap: break-word;font-weight:bold">GRAND TOTAL</td> 
                <td style="border: 2px solid black;font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:center;font-weight:bold">{{$jumlah_semua_karyawan}}</td>
                <td style="border: 2px solid black;font-family: Arial Nova;font-size:10pt;word-wrap: break-word;text-align:right;font-weight:bold">{{$all_gaji}}</td>
            </tr>
        </table>
    </body>
</html>
