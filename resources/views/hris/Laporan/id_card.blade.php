
<table>
    <tr>
        <?php $counter=2;?>
        @foreach($employee as $value)
        <?php $counter++;?>
        @if($counter%3==0)
            </tr><tr>
        @endif
        <td>
            <div class="parent">
                <img class="image1" src="{{ public_path('assets/images/brand/id card background.jpeg') }}" width="204">
                <img class="image2" src="{{ public_path('assets/images/brand/pas photo.png') }}" width="130"/>
                <img class="image3" src="{{ public_path('assets/images/brand/id card foreground.png') }}" width="204">
                <h6 class="text1">{{$value->nik}}</h6>
                <div class="row text2">
                    <div class="col">
                        {{$value->employee_name}}
                    </div>
                    <div class="col">
                        {{$value->sub_dept_name}}
                    </div>
                    <div class="col">
                        <img src="data:image/png;base64,{{\DNS2D::getBarcodePNG(''.$value->enroll_id, 'QRCODE',)}}" alt="barcode" style="width: 32px; height: 32px; margin:2px; padding:2px;background-color:white" />
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
    }
    .image1 {
        position: relative;
        top: 80;
        left: 5;
    }
    .image2 {
        position: absolute;
        top: 380px;
        left: 18px;
    }
    .image3 {
        position: absolute;
        top: 398px;
        left: 7px;
    }
    .text1 {
        position: absolute;
        top: 495px;
        left: 152px;
        font-weight: bold;
        font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 7pt;
    }
    .text2 {
        position: absolute;
        width: 98;
        text-align: right;
        top: 534px;
        left: 76px;
        font-weight: bold;
        font-size: 7pt;
        color: white;
    }
</style>