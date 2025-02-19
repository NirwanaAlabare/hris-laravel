<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>

    <style>
        * {
            font-size: 8px;
        }

        .page-break {
            page-break-after: always;
        }

        .parent {
            position: relative;
            top: 1;
            left: 0;
        }
        .image1 {
            position: absolute;
            top: 10px;
            left: 7px;
        }
        .text1 {
            position: absolute;
            top: 80px;
            left: 55px;
            font-weight: bold;
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 9pt;
        }
        @page { margin: 20px 20px 20px 20px; }
    </style>
</head>

<body>
    <table width=" {{ count($data) === 1 ? '50%' : '100%' }}"  style="page-break-inside:auto;">
        <thead>
            {{$data}}
            <tr>
                @foreach ($data as $key=>$value)
                <td style="height:265px;vertical-align:top;position: relative;">
                    <div class="parent">
                        <img class="image1" width="100%" src="{{ public_path('/assets/images/hrd/voucher bazar.png') }}">
                        <h6 class="text1"><span style="color:red;font-size:9pt">{{$value->nomor_voucher}}</span></h6>
                    </div>
                    <div class="barcode" style="display: flex; margin-top: 1px; position: absolute; bottom: -2px; left: 14px;">
                            <h6>{{$value->employee->sub_dept_name}}</h6>
                    </div>
                    <div class="barcode" style="display: flex; margin-top: 1px;  position: absolute; bottom: -6px; right: 0px;">
                        <div align="right">
                            <img src="data:image/png;base64,{{ \DNS2D::getBarcodePNG(url('hris/identity/card_employee_form_identity') . '?enroll_id=' . $value->enroll_id . '&no_form=3872/HRD-NAC/EXT/XII/2024' . '&type=SP_HADIR', 'QRCODE') }}" alt="barcode" style="width: 53px; height: 53px;background-color:white" />
                        </div>
                    </div>
                </td>
                @if (($key+1)%2==0)
                </tr><tr>
                @endif
                @endforeach
            </tr>
        </thead>
    </table>
    <script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            if ($PAGE_COUNT > 1) {
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $size = 6;
                $pageText = "Halaman " . $PAGE_NUM . " dari " . $PAGE_COUNT;
                $y = 5;
                $x = 530;
                $pdf->text($x, $y, $pageText, $font, $size);
            }
        ');
    }
    </script>

</body>

</html>
