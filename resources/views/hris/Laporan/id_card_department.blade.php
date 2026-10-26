<table>
    <tr>
        @if ($type == 'employee')
            <?php $counter=3;?>
            @foreach($employee as $value)
            <?php $counter++;?>
            @if($counter%4==0)
                </tr><tr>
            @endif
            <td>
                @php
                    $poto_profil = $value->nik . ' ' . $value->employee_name . '.png';
                    $poto_profil_jpg = $value->nik . ' ' . $value->employee_name . '.jpg';
                @endphp
                <div class="parent">
                    <img class="image4" src="{{ public_path('assets/images/brand/group.png') }}" >
                    @if (file_exists (public_path ('/storage/app/public/images/'.$value->nik.' '.$value->employee_name.'.png')))
                    <img class="image5" src="http://10.10.5.111/hris/public/storage/app/public/images/{{ $poto_profil }}" />
                    @else
                    <img class="image5" src="http://10.10.5.111/hris/public/storage/app/public/images/{{ $poto_profil }}" />
                    @endif
                    <img class="image6" src="{{ public_path('assets/images/brand/foreground-test.png') }}" >

                    <!-- NEW POSITION FOR YELLOW LABEL - Bottom Horizontal -->
                    @if (in_array($value->sub_dept_name, [
                        'EXPORT & IMPORT',
                        'FABRIC WAREHOUSE'
                    ]))
                <div class="access-label2">
                        {{ str_repeat('  AREA ACCESS - RESTRICTED', 3) }}
                </div>
                <div class="access-label22">
                        {{ str_repeat($value->sub_dept_name . ' - ', 8) }}
                </div>
                @endif
                @if (in_array($value->sub_dept_name, [
                        'FINISHED GOOD WAREHOUSE',
                        'GOODS STOCK WAREHOUSE',
                        'SAMPLE',
                        'ACCESSORIES WAREHOUSE',
                    ]))
                <div class="access-label2">
                        {{ str_repeat('  AREA ACCESS - RESTRICTED', 3) }}
                </div>
                <div class="access-label21">
                        {{ str_repeat($value->sub_dept_name . ' - ', 8) }}
                </div>
                @endif

                    <h6 class="text3">{{$value->nik}}</h6>
                    <div class="row text4">
                        <div class="col" >
                            @php
                                $nameParts = explode(' ', $value->employee_name);
                                $formattedName = $value->employee_name;

                                if (count($nameParts) > 2) {
                                    $formattedName = $nameParts[0] . ' ' . $nameParts[1];

                                    for ($i = 2; $i < count($nameParts); $i++) {
                                        $formattedName .= ' ' . substr($nameParts[$i], 0, 1) . '.';
                                    }
                                }
                            @endphp
                            {{$formattedName}}
                        </div><br>
                        <div class="col">
                            {{$value->status_jabatan}} <br> {{$value->department_name}}
                        </div>
                        <div class="col">
                            <img src="data:image/png;base64,{{\DNS2D::getBarcodePNG(''.$value->enroll_id, 'QRCODE',)}}" alt="barcode" style="width: 60px; height: 60px; margin:2px; margin-top:5px; margin-right:10px; padding:2px;background-color:white" />
                        </div>
                    </div>
                </div>
            </td>
            @endforeach
        @elseif($type == 'department')
            <?php $counter=-1;?>
            @foreach($employee as $value)
            <?php $counter++;?>
            @if($counter%5==0)
                </tr><tr>
            @endif
            <td style="padding-left: 0;padding-right:0">
            <div class="parent">
                @php
                    $poto_profil = $value->nik . ' ' . $value->employee_name . '.png';
                    $poto_profil_jpg = $value->nik . ' ' . $value->employee_name . '.jpg';
                @endphp
                <img class="image1" src="{{ public_path('assets/images/brand/group.png') }}" width="214">
                @if (file_exists (public_path ('/storage/app/public/images/'.$value->nik.' '.$value->employee_name.'.png')))
                <img class="image2" src="http://10.10.5.111/hris/public/storage/app/public/images/{{ $poto_profil }}"/>
                @else
                <img class="image2" src="http://10.10.5.111/hris/public/storage/app/public/images/{{ $poto_profil }}" />
                @endif
                <img class="image3" src="{{ public_path('assets/images/brand/foreground-test.png') }}">

                <!-- NEW POSITION FOR YELLOW LABEL - Bottom Horizontal -->
                @if (in_array($value->sub_dept_name, [
                        'EXPORT & IMPORT',
                        'FABRIC WAREHOUSE'
                    ]))
                <div class="access-label2">
                        {{ str_repeat('  AREA ACCESS - RESTRICTED', 3) }}
                </div>
                <div class="access-label22">
                        {{ str_repeat($value->sub_dept_name . ' - ', 8) }}
                </div>
                @endif
                @if (in_array($value->sub_dept_name, [
                        'FINISHED GOOD WAREHOUSE',
                        'GOODS STOCK WAREHOUSE',
                        'SAMPLE',
                        'ACCESSORIES WAREHOUSE',
                    ]))
                <div class="access-label2">
                        {{ str_repeat('  AREA ACCESS - RESTRICTED', 3) }}
                </div>
                <div class="access-label21">
                        {{ str_repeat($value->sub_dept_name . ' - ', 8) }}
                </div>
                @endif

                    <h6 class="text1">{{$value->nik}}</h6>
                    <div class="row text2">
                        <div class="col" >
                            @php
                                $nameParts = explode(' ', $value->employee_name);
                                $formattedName = $value->employee_name;

                                if (count($nameParts) > 2) {
                                    $formattedName = $nameParts[0] . ' ' . $nameParts[1];

                                    for ($i = 2; $i < count($nameParts); $i++) {
                                        $formattedName .= ' ' . substr($nameParts[$i], 0, 1) . '.';
                                    }
                                }
                            @endphp
                            {{$formattedName}}
                        </div><br>
                        <div class="col">
                            {{$value->sub_dept_name}} {{$value->department_name}}
                        </div>
                        <div class="col">
                            <img src="data:image/png;base64,{{\DNS2D::getBarcodePNG(''.$value->enroll_id, 'QRCODE',)}}" alt="barcode" style="width: 60px; height: 60px; margin:2px; margin-top:5px; margin-right:10px; padding:2px;background-color:white" />
                        </div>
                    </div>
            </div>
            </td>
            @endforeach
        @endif

    </tr>
</table>
<style>
   .parent {
    position: relative;
    top: 0;
    left: 0;
    width: 250px; /* Samakan dengan lebar gambar background / kartu */
    height: 900px; /* Samakan dengan tinggi total kartu */
    overflow: hidden;

     /* Sesuaikan dengan lebar gambar/kartu Anda */
    /* Sesuaikan dengan tinggi gambar/kartu Anda */
    /* overflow: hidden; */
}
.access-label1 {
    position: absolute;


    bottom: 200px;
    left: 0px;

    width: 300px;
    height: 25px;

    background: #ffff00;
    color: #000;

    font-family: "Bebas Neue", sans-serif;
    font-size: 9px;
    font-weight: 700;
    font-style: normal;
    line-height: 25px;
    text-align: center;
    white-space: nowrap;

    z-index: 5;


    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;


    -webkit-transform: rotate(-45deg) translateZ(0);
    -moz-transform: rotate(-45deg) translateZ(0);
    -ms-transform: rotate(-45deg) translateZ(0);
    -o-transform: rotate(-45deg) translateZ(0);
    transform: rotate(-45deg) translateZ(0);
}
/* .access-label21 { */
    /* position: absolute; */

    /* Atur posisi tengah pita di sudut bawah-kiri */
    /* bottom: 100px;
    left: 0%;

    width: 210px;
    height: 10px;

    background: hsl(277, 93%, 73%);
    color: #000;

    font-family: "Bebas Neue", sans-serif;
    font-size: 5px;
    font-weight: 700;
    font-style: normal;
    line-height: 10px;
    text-align: center;
    white-space: nowrap;

    z-index: 5;   */
    /* Naikkan z-index agar pita di atas gambar/elemen lain */

    /* Kunci agar rotasi seimbang di titik tengah */
    /* -webkit-transform-origin: center;
    -moz-transform-origin: center;
    -ms-transform-origin: center;
    -o-transform-origin: center;
    transform-origin: center; */

    /* Rotasi pita */
    /* -webkit-transform: rotate(-45deg);
    -moz-transform: rotate(-45deg);
    -ms-transform: rotate(-45deg);
    -o-transform: rotate(-45deg);
    transform: rotate(-45deg); */

    /* Smooth rendering */
    /* -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale; */
/* } */
/* .access-label1 span {
    display: block;

    font-family: Arial, sans-serif;
    font-size: 9px;
    font-weight: 700;
    line-height: 25px;

    color: #000;

    /* Balikkan rotasi tulisan
    -webkit-transform: rotate(-45deg);
    -moz-transform: rotate(-45deg);
    -ms-transform: rotate(45deg);
    -o-transform: rotate(45deg);
    transform: rotate(45deg);
} */
    .image1 {
        position: relative;
        top: 80;
        left: 0;
        width: 250px;
        z-index: 5;
    }

    .image2 {
        position: absolute;
         bottom: 90px;
        width: 160px;
        left: 2px;
        object-fit: cover;            /* Potong otomatis */
        object-position: bottom center;
         z-index: 5;
    }
    .image3 {
        position: absolute;
        bottom: 25px;
        left: 0px;
        width: 250px;
         z-index: 7;
    }
    .garis_hitam {
        position: absolute;
        top: 0;
        left: 3;
    }
    .text1 {
        position: absolute;
        top: 680px;
        right: 3px;
        font-weight: bold;
        font-family:Arial, Helvetica, sans-serif;
        font-size: 7pt;
        letter-spacing: 1px;
        z-index: 10;
    }
    .text2 {


        position: absolute;
        width: 120px;
        text-align: right;
        top: 730px;
        right: 2px;
        font-size: 7.5px;
        font-family:Arial, Helvetica, sans-serif;
        font-weight:600;
        color: white;
         z-index: 10;
    }
    .access-label2 {
    position: absolute;

    /* Posisi pita */
    bottom: 205px;
    left: -0px;

    /* Panjang pita */
    width: 310px;
    height: 20px;

    background: #ffff00;
    color: #000;

    font-family: "Bebas Neue", sans-serif;
    font-size: 9px; /* Sedikit dinaikkan agar lebih terbaca */
    font-weight: 700;
    font-style: normal;
    line-height: 20px;
    text-align: center;
    white-space: nowrap;

    z-index: 7;

    /* Anti-aliasing agar pinggiran teks tidak bergerigi */
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;

    /* Rotasi & Trik GPU Acceleration agar tetap miring dan tajam */
    -webkit-transform: rotate(-45deg) translateZ(0);
    -moz-transform: rotate(-45deg) translateZ(0);
    -ms-transform: rotate(-45deg) translateZ(0);
    -o-transform: rotate(-45deg) translateZ(0);
    transform: rotate(-45deg) translateZ(0);

    /* Supaya bagian luar kartu tidak mengganggu */


}
.access-label22 {
    position: absolute;

    /* Atur posisi tengah pita di sudut bawah-kiri */
    bottom: 196px;
    left: 12px;

    width: 300px;
    height: 7px;

    background: hsl(277, 93%, 73%);
    color: #000;

    font-family: "Bebas Neue", sans-serif;
    font-size: 5px;
    font-weight: 700;
    font-style: normal;
    line-height: 7px; /* Samakan dengan height agar teks pas di tengah vertikal */
    text-align: center;
    white-space: nowrap;

    z-index: 7; /* Naikkan z-index agar pita di atas gambar/elemen lain */

    /* Kunci agar rotasi seimbang di titik tengah */
    -webkit-transform-origin: center;
    -moz-transform-origin: center;
    -ms-transform-origin: center;
    -o-transform-origin: center;
    transform-origin: center;

    /* Rotasi pita */
    -webkit-transform: rotate(-45deg);
    -moz-transform: rotate(-45deg);
    -ms-transform: rotate(-45deg);
    -o-transform: rotate(-45deg);
    transform: rotate(-4deg);

    /* Smooth rendering */
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.access-label21 {
    position: absolute;

    /* Atur posisi tengah pita di sudut bawah-kiri */
    bottom: 196px;
    left: 12px;

    width: 300px;
    height: 7px;

    background: hsl(241, 94%, 56%);
    color: #fffbfb;

    font-family: "Bebas Neue", sans-serif;
    font-size: 5px;
    font-weight: 700;
    font-style: normal;
    line-height: 7px; /* Samakan dengan height agar teks pas di tengah vertikal */
    text-align: center;
    white-space: nowrap;

    z-index: 7; /* Naikkan z-index agar pita di atas gambar/elemen lain */

    /* Kunci agar rotasi seimbang di titik tengah */
    -webkit-transform-origin: center;
    -moz-transform-origin: center;
    -ms-transform-origin: center;
    -o-transform-origin: center;
    transform-origin: center;

    /* Rotasi pita */
    -webkit-transform: rotate(-45deg);
    -moz-transform: rotate(-45deg);
    -ms-transform: rotate(-45deg);
    -o-transform: rotate(-45deg);
    transform: rotate(-4deg);

    /* Smooth rendering */
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

    .image4 {
        position: relative;
        top: 80;
        left: 0;
        width: 250px;
        /* height: 750px; */
         z-index: 5;
    }
    .image5 {
        position: absolute;
        bottom: 90px;
        width: 160px;
        left: 2px;

         z-index: 5;
    }
    .image6 {
        position: absolute;
        bottom: 25px;
        left: 0px;
        width: 250px;
         z-index: 7;
    }
    .text3 {
        position: absolute;
        top: 680px;
        right: 3px;
        font-weight: bold;
        font-family:Arial, Helvetica, sans-serif;
        font-size: 7pt;
        letter-spacing: 1px;
         z-index: 8;
    }
    .text4 {
        position: absolute;
        width: 150px;
        text-align: right;
        top: 730px;
        right: 2px;
        font-size: 7.5px;
        font-family:Arial, Helvetica, sans-serif;
        font-weight:600;
        color: white;
         z-index: 8;
    }
</style>
