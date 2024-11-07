
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
                <div class="parent">
                    <img class="image4" src="{{ public_path('assets/images/brand/id card background.jpeg') }}" width="204">
                    @if (file_exists (public_path ('/storage/app/public/images/'.$value->nik.' '.$value->employee_name.'.png')))
                    <img class="image5" src="{{ public_path('storage/app/public/images/'.$value->nik.' '.$value->employee_name.'.png') }}" width="130"/>
                    @else
                    <img class="image5" src="{{ public_path('storage/app/public/images/'.$value->nik.' '.$value->employee_name.'.jpg') }}" width="130"/>
                    @endif
                    <img class="image6" src="{{ public_path('assets/images/brand/id card foreground.png') }}" width="204">
                    <h6 class="text3">{{$value->nik}}</h6>
                    <div class="row text4">
                        <div class="col">
                            {{$value->employee_name}}
                        </div>
                        <div class="col">
                            {{$value->status_jabatan}} {{$value->department_name}}
                        </div>
                        <div class="col">
                            <img src="data:image/png;base64,{{\DNS2D::getBarcodePNG(''.$value->enroll_id, 'QRCODE',)}}" alt="barcode" style="width: 32px; height: 32px; margin:2px; padding:2px;background-color:white" />
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
                    <img class="garis_hitam" src="{{ public_path('assets/images/brand/garis hitam.png') }}" style="height: 650px">
                    <img class="image1" src="{{ public_path('assets/images/brand/id card background 2.png') }}" width="204">
                    @if (file_exists (public_path ('/storage/app/public/images/'.$value->nik.' '.$value->employee_name.'.png')))
                    <img class="image2" src="{{ public_path('storage/app/public/images/'.$value->nik.' '.$value->employee_name.'.png') }}" width="130"/>
                    @else
                    <img class="image2" src="{{ public_path('storage/app/public/images/'.$value->nik.' '.$value->employee_name.'.jpg') }}" width="130"/>
                    @endif
                    <img class="image3" src="{{ public_path('assets/images/brand/id card foreground.png') }}" width="204">
                    <h6 class="text1">{{$value->nik}}</h6>
                    <div class="row text2">
                        <div class="col">
                            {{$value->employee_name}}
                        </div>
                        <div class="col">
                            {{$value->status_jabatan}} {{$value->sub_dept_name}}
                        </div>
                        <div class="col">
                            <img src="data:image/png;base64,{{\DNS2D::getBarcodePNG(''.$value->enroll_id, 'QRCODE',)}}" alt="barcode" style="width: 32px; height: 32px; margin:2px; padding:2px;background-color:white" />
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
        left: 5;
    }
    .image1 {
        position: relative;
        top: 0;
        left: 5;
    }
    .image2 {
        position: absolute;
        bottom: 140px;
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
        top: 518px;
        left: 152px;
        font-weight: bold;
        font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 7pt;
    }
    .text2 {
        position: absolute;
        width: 98;
        text-align: right;
        top: 420;
        left: 76px;
        font-weight: bold;
        font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 7pt;
        color: white;
    }
    .image4 {
        position: relative;
        top: 80;
        left: 5;
    }
    .image5 {
        position: absolute;
        bottom: 163px;
        left: 8px;
    }
    .image6 {
        position: absolute;
        top: 398px;
        left: 7px;
    }
    .text3 {
        position: absolute;
        top: 495px;
        left: 152px;
        font-weight: bold;
        font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 7pt;
    }
    .text4 {
        position: absolute;
        width: 98;
        text-align: right;
        top: 534px;
        left: 76px;
        font-weight: bold;
        font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 7pt;
        color: white;
    }
</style>