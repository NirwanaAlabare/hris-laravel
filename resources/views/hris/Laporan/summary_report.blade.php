
<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
    <head>

    </head>
    <body>
        <table>
            <tr>
                <td colspan="5" style="font-family: Arial Nova;font-size:11pt; font-weight:bold;">Controling Absensi</td>
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova;font-size:11pt; font-weight:bold;">Periode {{$bulan}}&nbsp;{{$tahun}}</td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" rowspan="2" align="center" valign="center" width="6">ID</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" rowspan="2" align="center" valign="center" width="12">NIK</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" rowspan="2" align="center" valign="center" width="40">NAMA</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" rowspan="2" align="center" valign="center" width="15">AKTIF/NON AKTIF</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" rowspan="2" align="center" valign="center" width="15">JOIN DATE</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" rowspan="2" align="center" valign="center" width="15">RESIGN DATE</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" rowspan="2" align="center" valign="center" width="15">STAFF/NON STAFF</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" rowspan="2" align="center" valign="center" width="15">JABATAN</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" rowspan="2" align="center" valign="center" width="15">BAGIAN</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" rowspan="2" align="center" valign="center" width="15">DEPARTMENT</td>
                @foreach ($data_tanggal_absensi as $key=>$value)
                <?php 
                $hari=Carbon\Carbon::parse($value)->translatedFormat('l');
                ?>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border-top:2px solid black;border-left:2px solid black;border-right:2px solid black;background-color:yellow" align="center" width="12">{{$hari}}</td>
                @endforeach
                <td colspan="21" style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center">Jumlah Ketidakhadiran</td>
            </tr>
            <tr>
                @foreach ($data_tanggal_absensi as $key=>$value)
                <?php 
                $tanggal=Carbon\Carbon::parse($value)->translatedFormat('d');
                $bulan=Carbon\Carbon::parse($value)->translatedFormat('F');
                $bulan_sub=substr($bulan,0,3);
                $tahun=Carbon\Carbon::parse($value)->translatedFormat('Y');
                ?>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;background-color:yellow;border-bottom:2px solid black;border-left:2px solid black;border-right:2px solid black" align="center">{{$tanggal}}-{{$bulan_sub}}-{{$tahun}}</td>
                @endforeach
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="6">CB</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="6">CBD</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="6">CG</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="6">CH</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="6">CM</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="6">CN</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="8">CT</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="8">DL</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="8">I</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="8">IG</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="8">IKS</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="8">IM</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="8">KA</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="8">KM</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="8">KR</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="8">LN</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="8">LP</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:red" align="center" width="8">M</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="8">NA</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="8">PP</td>
                <td style="font-family: Arial Nova;font-size:9pt; font-weight:bold;border:2px solid black;background-color:yellow" align="center" width="8">S</td>
            </tr>
            @foreach ($daftar_karyawan as $key=>$value)
            <?php 
            $join_date=Carbon\Carbon::parse($value->join_date)->translatedFormat('d F Y');
            $tanggal_resign=Carbon\Carbon::parse($value->tanggal_resign)->translatedFormat('d F Y');
            ?>
            <tr>
                <td style="font-family: Arial Nova;font-size:8pt;border:2px solid black;">{{$value->enroll_id}}</td>
                <td style="font-family: Arial Nova;font-size:8pt;border:2px solid black;">{{$value->nik}}</td>
                <td style="font-family: Arial Nova;font-size:8pt;border:2px solid black;">{{$value->employee_name}}</td>
                <td style="font-family: Arial Nova;font-size:8pt;border:2px solid black;">{{$value->status_aktif}}</td>
                <td style="font-family: Arial Nova;font-size:8pt;border:2px solid black;">{{$join_date}}</td>
                <td style="font-family: Arial Nova;font-size:8pt;border:2px solid black;">
                    @if($value->tanggal_resign!=null)
                    {{$tanggal_resign}}
                    @endif
                </td>
                <td style="font-family: Arial Nova;font-size:8pt;border:2px solid black;">{{$value->status_staff}}</td>
                <td style="font-family: Arial Nova;font-size:8pt;border:2px solid black;">{{$value->status_jabatan}}</td>
                <td style="font-family: Arial Nova;font-size:8pt;border:2px solid black;">{{$value->sub_dept_name}}</td>
                <td style="font-family: Arial Nova;font-size:8pt;border:2px solid black;">{{$value->department_name}}</td>
                @foreach($data_tanggal_absensi as $keys=>$bon)
                <?php 
                $tanggal_berjalan_enroll_id=$bon.'-'.$value->enroll_id;
                ?>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$data_daftar_karyawan[array_search($tanggal_berjalan_enroll_id, array_column($data_daftar_karyawan, 'tanggal_enroll_id'))]['status_absen']}}
                </td>
                @endforeach
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['cb']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['cbd']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['cg']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['ch']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['cm']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['cn']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['ct']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['dl']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['i']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['ig']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['iks']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['im']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['ka']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['km']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['kr']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['ln']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['lp']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['m']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['na']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['pp']}}
                </td>
                <td style="font-family: Arial Nova;font-size:9pt;border:2px solid black;" align="center">
                    {{$keterangan_absensi[array_search($value->enroll_id, array_column($keterangan_absensi, 'enroll_id'))]['s']}}
                </td>
            </tr>
            @endforeach
        </table>
    </body>
</html>