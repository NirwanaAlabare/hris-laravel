
@php
    use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>
    <style>
        @page { margin: 20px 45px 0px 45px; }
        #watermark {
            position: fixed;
            top: 30%;
            width: 100%;
            text-align: center;
            opacity: .3;
            transform-origin: 50% 50%;
            z-index: -1000;
        }
    </style>
</head>

<body>
    <div id="watermark">
        <img src="{{ public_path('assets/images/brand/nirwana logo.jpg') }}" width="460">
    </div>
    <table width="506" style="border-bottom: 2px solid black">
        <tr>
            <td width="10%"></td>
            <td width="13%" align="right"><img src="{{ public_path('assets/images/brand/nirwana logo.jpg') }}" width="42"></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:18pt;font-weight:bold">PT NIRWANA ALABARE GARMENT</td>
            <td width="10%"></td>
        </tr>
        <tr>
            <td></td>
            <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;font-weight:bold">Jl. Raya Rancaekek – Majalaya No. 289</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;font-weight:bold">Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung 40382</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;font-weight:bold">Telp. 022-85962081</td>
            <td></td>
        </tr>
    </table>
    <table width="506">
        <thead>
            @foreach ($data as $key=>$value)
            <?php
            $tanggal_akhir='';
            if($value->status_aktif=='AKTIF'){
                $tanggal_akhir='SEKARANG';
            }else{
                $tanggal_akhir=Carbon\Carbon::parse($value->tanggal_resign)->translatedFormat('d F Y');
            }
            ?>
            <tr>
                <td colspan="5" style="height: 9"></td>
            </tr>
            <tr>
                <td colspan="5" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top;font-size:14pt" align="center"><b><u>CERTIFICATE OF EMPLOYMENT</u></b></td>
            </tr>
            <tr>
                <td colspan="5" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top;font-size:14pt" align="center"><b>NO. {{$no_surat ? $no_surat.'/' : $value->no_surat.'/'}}{{$no_form}}{{ $value->catatan ? ('/' . Str::substr($value->catatan, 0, 3)) : '' }}</b></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 0"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Dengan ini menerangkan bahwa :</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>This Certificate that</i></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td width="4%"></td>
                <td width="31%" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><u>Nama</u></td>
                <td width="3%">:</td>
                <td width="73%" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value->employee_name}}</td>
                <td width="4%"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>Name</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><u>No. Pegawai</u></td>
                <td>:</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value->nik}}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>Reg. No.</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><u>Bagian</u></td>
                <td>:</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value->sub_dept_name}}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>Section</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><u>Departemen</u></td>
                <td>:</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value->department_name}}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>Department</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><u>Jabatan Terakhir</u></td>
                <td>:</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value->status_jabatan}}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>Last Position</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><u>Lamanya Bekerja</u></td>
                <td>:</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{Carbon\Carbon::parse($value->join_date)->translatedFormat('d F Y')}} s/d {{$tanggal_akhir}}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>Length of Service</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><u>Sebab Berhenti Bekerja</u></td>
                <td>:</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value->sebab_resign}}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>Reason Of Termination</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify">Kami mengucapkan terima kasih atas usaha dan kerjasama yang telah saudara sumbangkan kepada perusahaan. </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify"><i>We express our sincere thanks for your efforts and cooperation extended to company.</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify">Semoga keberhasilan dan kesuksesan senantiasa menyertai saudara di waktu mendatang.</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify"><i>Wishing you achievement and success always accompanying in the future.</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Majalaya, {{Carbon\Carbon::parse(date('Y-m-d'))->translatedFormat('d F Y')}}</td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">PT. Nirwana Alabare Garment</td>
            </tr>
            <tr>
                <td colspan="5" style="height: 45"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><b><u>Rudy Aristian Fajar</u></b></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><b>Compliance - HRGA</b></td>
            </tr>
            <table width="562">
                <thead >
                    <tr>
                        <td style="border-bottom:1px solid black;" width="85%"></td>
                        <td rowspan="2"><img src="data:image/png;base64,{{ \DNS2D::getBarcodePNG(url('hris/identity/card_employee_form_identity') . '?enroll_id=' . $value->enroll_id . '&no_form=' . urlencode($no_form)  . '&type=PAKLARING', 'QRCODE') }}"  alt="barcode" style="width: 60px; height: 60px;background-color:white" /></td>
                    </tr>

                    <tr>
                          <td style="height:24px"></td>
                    </tr>
                </thead>
            </table>
            @endforeach
        </thead>
    </table>

</body>
</html>
