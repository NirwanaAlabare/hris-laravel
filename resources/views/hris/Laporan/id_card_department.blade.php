
<table>
    <tr>
        @if (count($employee) <= 4)
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
                    <img class="image4" src="{{ public_path('assets/images/brand/group.png') }}" width="204">
                    @if (file_exists (public_path ('/storage/app/public/images/'.$value->nik.' '.$value->employee_name.'.png')))
                    <img class="image5" src="http://10.10.5.111/hris/public/storage/app/public/images/{{ $poto_profil }}" width="130"/>
                    @else
                    <img class="image5" src="http://10.10.5.111/hris/public/storage/app/public/images/{{ $poto_profil }}" width="130"/>
                    @endif
                    <img class="image6" src="{{ public_path('assets/images/brand/foreground-test.png') }}" width="203">
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
        @elseif(count($employee)>4)
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
                <img class="image1" src="{{ public_path('assets/images/brand/group.png') }}" width="204">
                @if (file_exists (public_path ('/storage/app/public/images/'.$value->nik.' '.$value->employee_name.'.png')))
                <img class="image2" src="http://10.10.5.111/hris/public/storage/app/public/images/{{ $poto_profil }}" width="130"/>
                @else
                <img class="image2" src="http://10.10.5.111/hris/public/storage/app/public/images/{{ $poto_profil }}" width="130"/>
                @endif
                <img class="image3" src="{{ public_path('assets/images/brand/foreground-test.png') }}" width="203">
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
        @endif

    </tr>
</table>
<style>
    @page { margin-left: 2px; }
    .parent {
        position: relative;
        top: 0;
        left: 0;
    }
    .image1 {
        position: relative;
        top: 0;
        left: 5px;
    }
    .image1 {
        position: relative;
        top: 0;
        left: 5;
    }
    .image2 {
        position: absolute;
        bottom: 70px;
        left: 8px;
    }
    .image3 {
        position: absolute;
        top: 421px;
        left: 5;
    }
    .garis_hitam {
        position: absolute;
        top: 0;
        left: 3;
    }
    .text1 {
        position: absolute;
        top: 480px;
        left: 152px;
        font-weight: bold;
        font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 7pt;
    }
    .text2 {
        position: absolute;
        width: 100px;
        text-align: right;
        top: 519px;
        left: 100px;
        font-weight: bold;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 7pt;
        color: white;
        white-space: normal;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }

    .image4 {
        position: relative;
        top: 80;
        left: 5;
    }
    .image5 {
        position: absolute;
        bottom: -14px;
        left: 5px;
    }
    .image6 {
        position: absolute;
        top: 505px;
        left: 5px;
    }
    .text3 {
        position: absolute;
        top: 565px;
        left: 152px;
        font-weight: bold;
        font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 7pt;
    }
    .text4 {
        position: absolute;
        width: 100px;
        text-align: right;
        top: 604px;
        left: 100px;
        font-weight: bold;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 7pt;
        color: white;
        white-space: normal;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
</style>
