
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
                    <img class="image4" src="{{ public_path('assets/images/brand/group.png') }}" width="204">
                    @if (file_exists (public_path ('/storage/app/public/images/'.$value->nik.' '.$value->employee_name.'.png')))
                    <img class="image5" src="http://10.10.5.111/hris/public/storage/app/public/images/{{ $poto_profil }}" width="150"/>
                    @else
                    <img class="image5" src="http://10.10.5.111/hris/public/storage/app/public/images/{{ $poto_profil }}" width="150"/>
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
                <img class="image2" src="http://10.10.5.111/hris/public/storage/app/public/images/{{ $poto_profil }}" width="150"/>
                @else
                <img class="image2" src="http://10.10.5.111/hris/public/storage/app/public/images/{{ $poto_profil }}" width="150"/>
                @endif
                <img class="image3" src="{{ public_path('assets/images/brand/foreground-test.png') }}" width="214">
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
                            {{$value->sub_dept_name}} {{$value->department_name}}
                        </div>
                        <div class="col">
                            <img src="data:image/png;base64,{{\DNS2D::getBarcodePNG(''.$value->enroll_id, 'QRCODE',)}}" alt="barcode" style="width: 88px; height: 88px; margin:2px; margin-top:5px; margin-right:15px; padding:2px;background-color:white" />
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
    }
    .image1 {
        position: relative;
        top: 80;
        left: 5;
        width: 300px;
    }

    .image2 {
        position: absolute;
        bottom: -10px;
        left: 4px;
        width: 200px;
    }
    .image3 {
        position: absolute;
        bottom: -80px;
        left: 5;
        width: 300px;
    }
    .garis_hitam {
        position: absolute;
        top: 0;
        left: 3;
    }
    .text1 {
        position: absolute;
        top: 800px;
        right: 3px;
        font-weight: bold;
        font-family:Arial, Helvetica, sans-serif;
        font-size: 8pt;
        letter-spacing: 1px;
    }
    .text2 {
        position: absolute;
        width: 150px;
        text-align: right;
        top: 850px;
        right: 7px;
        font-size: 11px;
        font-family:Arial, Helvetica, sans-serif;
        font-weight:600;
        color: white;
    }

    .image4 {
        position: relative;
        top: 80;
        left: 5;
        width: 250px;
    }
    .image5 {
        position: absolute;
        bottom: -14px;
        left: 5px;
        width: 200px;
    }
    .image6 {
        position: absolute;
        bottom: -80px;
        left: 5px;
        width: 250px;
    }
    .text3 {
        position: absolute;
        top: 680px;
        right: 3px;
        font-weight: bold;
        font-family:Arial, Helvetica, sans-serif;
        font-size: 7pt;
        letter-spacing: 1px;
    }
    .text4 {
        position: absolute;
        width: 100px;
        text-align: right;
        top: 725px;
        right: 7px;
        font-size: 7.5px;
        font-family:Arial, Helvetica, sans-serif;
        font-weight:600;
        color: white;
    }
</style>
