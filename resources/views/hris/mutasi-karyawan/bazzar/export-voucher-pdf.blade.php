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
    <table width="100%" page-break-inside: auto; >
    <thead>
        <tr>
            @foreach ($data as $key=>$value)
            @for ($i=0;$i<($value->jumlah/50000);$i++)
            <td style="height:265px;vertical-align:top">
                <div class="parent">
                    <img class="image1" width="100%" src="{{ public_path('/assets/images/hrd/voucher bazar.png') }}">
                    <h6 class="text1">2025.<span style="color:red;font-size:9pt">{{sprintf("%05d", ($i+1))}}</span>.{{$value->enroll_id}}.{{$value->employee->employee_name}}</h6>
                </div>
            </td>
            @if (($i+1)%2==0)
            </tr><tr>
            @endif
            @endfor
            @endforeach
        </tr>
    </thead>
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
