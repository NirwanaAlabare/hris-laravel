
<table>
    <tr>
        <?php $counter=3;?>
        @foreach($employee as $value)
        <?php $counter++;?>
        @if($counter%4==0)
            </tr><tr>
        @endif
        <td>
            @php
                $poto_profil = $value->nik . ' ' . $value->employee_name . '.png';
            @endphp
            <div class="parent">
                <img class="image1" src="{{ public_path('assets/images/brand/group.png') }}" width="204">
                @if (file_exists (public_path ('/storage/app/public/images/'.$value->nik.' '.$value->employee_name.'.png')))
                    <img class="image2" src="http://10.10.5.111/hris/public/storage/app/public/images/{{ $poto_profil }}" width="130"/>
                @else
                    <img class="image2" src="http://10.10.5.111/hris/public/storage/app/public/images/{{ $poto_profil }}" width="130"/>
                @endif
                <img class="image3" src="{{ public_path('assets/images/brand/foreground-test.png') }}" width="203">
                @if (in_array($value->sub_dept_name, [
                        'EXPORT & IMPORT',
                        'FABRIC WAREHOUSE'
                    ]))
                <div class="access-label1">
                        {{ str_repeat('  AREA ACCESS - RESTRICTED', 3) }}
                </div>
                <div class="access-label21">
                        {{ str_repeat($value->sub_dept_name . ' - ', 8) }}
                </div>
                @endif
                @if (in_array($value->sub_dept_name, [
                        'FINISHED GOOD WAREHOUSE',
                        'GOODS STOCK WAREHOUSE',
                        'SAMPLE',
                        'ACCESSORIES WAREHOUSE',
                    ]))
                <div class="access-label1">
                        {{ str_repeat('  AREA ACCESS - RESTRICTED', 3) }}
                </div>
                <div class="access-label22">
                        {{ str_repeat($value->sub_dept_name . ' - ', 8) }}
                </div>
                @endif
                    <h6 class="text1">{{$value->nik}}</h6>
                    <div class="row text2">
                        <div class="col" >
                            @php
                                $nameParts = explode(' ', $value->employee_name); // Pecah nama berdasarkan spasi
                                $formattedName = $value->employee_name;

                                if (count($nameParts) > 2) {
                                    $formattedName = $nameParts[0] . ' ' . $nameParts[1];

                                    for ($i = 2; $i < count($nameParts); $i++) {
                                        $formattedName .= ' ' . substr($nameParts[$i], 0, 1) . '.';
                                    }
                                }
                            @endphp
                            {{$formattedName}}
                        </div>
                        <div class="col">
                            {{$value->status_jabatan}} {{$value->department_name}}
                        </div>
                        <div class="col">
                            <img src="data:image/png;base64,{{\DNS2D::getBarcodePNG(''.$value->enroll_id, 'QRCODE',)}}" alt="barcode" style="width: 66px; height: 66px; margin:2px; margin-top:5px; margin-right:15px; padding:2px;background-color:white" />
                        </div>
                    </div>
            </div>
        </td>
        @endforeach
    </tr>
</table>
<style>
   .parent {
    position: relative;
    top: 0;
    left: 0;
    width: 250px; /* Samakan dengan lebar gambar background / kartu */
    height: 900px; /* Samakan dengan tinggi total kartu */
    overflow: hidden; /* Otomatis memotong pita kuning yang keluar batas */
}
.access-label1 {
    position: absolute;

    /* Atur posisi tengah pita di sudut bawah-kiri */
    bottom: 290px;
    left: 2px;

    width: 320px;
    height: 20px;

    background: #ffff00;
    color: #000;

    font-family: "Bebas Neue", sans-serif;
    font-size: 9px;
    font-weight: 700;
    font-style: normal;
    line-height: 20px; /* Samakan dengan height agar teks pas di tengah vertikal */
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
    transform: rotate(-45deg);

    /* Smooth rendering */
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.access-label21 {
    position: absolute;

    /* Atur posisi tengah pita di sudut bawah-kiri */
    bottom: 270px;
    left: 20px;

    width: 280px;
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
    transform: rotate(-45deg);

    /* Smooth rendering */
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.access-label22 {
    position: absolute;

    /* Atur posisi tengah pita di sudut bawah-kiri */
    bottom: 270px;
    left: 20px;

    width: 280px;
    height: 7px;

    background: hsl(241, 94%, 56%);
    color: #fffbfb;

    font-family: "Bebas Neue", sans-serif;
    font-size: 4px;
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
    transform: rotate(-45deg);

    /* Smooth rendering */
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
    .image1 {
        position: relative;
        top: 0px;
        left: 5;
        width: 250px;
        z-index: 5;
    }
    .image2 {
        position: absolute;
        bottom: 140px;
        left: 5px;
        width: 190px;
        z-index: 5;
    }
    .image3 {
        position: absolute;
        top: 520px;
        left: 5px;
        width: 250px;
        z-index: 6;
    }
    .text1 {
        position: absolute;
        top: 605px;
        right: 3px;
        font-weight: 600;
        font-family:Arial, Helvetica, sans-serif;
        font-size: 7.5px;
        letter-spacing: 1px;
        z-index: 8;
    }
    .text2 {
        position: absolute;
        width: 100px;
        text-align: right;
        top: 650px;
        right: 3px;
        font-size: 7.5px;
        font-family:Arial, Helvetica, sans-serif;
        font-weight:600;
        color: white;
        z-index: 7;
    }

</style>
