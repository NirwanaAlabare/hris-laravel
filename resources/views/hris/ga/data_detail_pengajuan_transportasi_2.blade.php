@extends('admin.adminlayouts.adminlayout4')

@section('head')
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<!---Sweetalert Css-->
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/js/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/izitoast/dist/css/iziToast.min.css') }}" rel="stylesheet">
<style>
    .select2-container--default .select2-selection--multiple {
        background-color: white !important;
    }
</style>
@stop
@section('mainarea')
<div class="card">
    <div class="card-header mt-7 pt-1 pb-0">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="btn btn-white" href="{{route('hris.ga.form_pengajuan_transportasi')}}">Formulir</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-white" href="{{route('hris.ga.data_pengajuan_transportasi')}}">Data</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary" href="#">Lihat Pengajuan Transportasi</a>
            </li>
        </ul>
    </div>
    @foreach ($pengajuan_transportasi as $key=>$value)
    <?php 
        $tanggal_pemberangkatan=Carbon\Carbon::parse($value->tanggal_pemberangkatan)->format('d F Y');
    ?>
    <div class="card-body pl-6 py-4" style="border: 1px solid #d8d4dc">
        <div class="row">
            <div class="col-2 pt-1">
                <label class="form-label"> Nama Karyawan</label>
                <input type="hidden" value="{{$id_user}}" id="user">
                <input type="hidden" value="{{$value->id}}" id="id_pengajuan">
            </div>
            <div class="col-4">{{$value->employee_name}}
            </div>
        </div>
        <div class="row pb-3">
            <div class="col-2 pt-1">
                <label class="form-label pt-1"> Status Pengajuan</label>
            </div>
            <div class="col-4">
                @if($id_user==4241 || $id_user==20 || $id_user==5321 || $id_user==6083)
                <select class="form-control col-11" id="status" onchange="changeStatus()" style="background-color:white">
                    <option value=0 @if ($value->status == 0) {{ 'selected' }} @endif>Pilih Status</option>
                    <option value=1 @if ($value->status == 1) {{ 'selected' }} @endif>Approved</option>
                    <option value=2 @if ($value->status == 2) {{ 'selected' }} @endif>Alternative</option>
                    <option value=3 @if ($value->status == 3) {{ 'selected' }} @endif>On The Way</option>
                    <option value=4 @if ($value->status == 4) {{ 'selected' }} @endif>Done</option>
                    <option value=5 @if ($value->status == 5) {{ 'selected' }} @endif>Late</option>
                    <option value=6 @if ($value->status == 6) {{ 'selected' }} @endif>Cancel</option>
                </select>
                @else
                    @if($value->status==0)
                        <h6 style="font-size:11pt;color:grey;font-weight:bold">: PENDING</h6>
                    @elseif($value->status==1)
                        <h6 style="font-size:11pt;color:green;font-weight:bold">: APPROVED</h6>
                    @elseif($value->status==2)
                        <h6 style="font-size:11pt;color:red;font-weight:bold">: Alternative</h6>
                    @elseif($value->status==3)
                        <h6 style="font-size:11pt;color:green;font-weight:bold">: ON THE WAY</h6>
                    @elseif($value->status==4)
                        <h6 style="font-size:11pt;color:green;font-weight:bold">: DONE</h6>
                    @elseif($value->status==5)
                        <h6 style="font-size:11pt;color:orange;font-weight:bold">: LATE</h6>
                    @elseif($value->status==6)
                        <h6 style="font-size:11pt;color:red;font-weight:bold">: CANCEL</h6>
                    @endif
                @endif
            </div>
        </div>
        <div id="tag_alternative">
            <div class="row pb-3">
                <div class="col-2 pt-1">
                    <label class="form-label"> Alternative</label>
                </div>
                <div class="col-4">
                    @if($id_user==4241 || $id_user==20 || $id_user==5321 || $id_user==6083)
                    <textarea class="form-control col-11" id="alternative" style="background-color:white" onchange="alternative_change()"></textarea>
                    @else
                    <textarea class="form-control col-11" id="alternative" style="background-color:white" onchange="alternative_change()" disabled style="background-color:white"></textarea>
                    @endif
                </div>
            </div>
        </div>
        <div class="row pb-2 pt-1">
            <div class="col-2 pt-1">
                <label class="form-label"> Keberangkatan Awal</label>
            </div>
            <div class="col-4 pt-2">
            </div>
        </div>
        <div class="row pb-2 pt-1">
            <div class="col-12 px-1">
                <table class="w-100 table-bordered">
                    <tr>
                        <th style="padding:6px">Provinsi</th>
                        <th style="padding:6px">Kabupaten/Kota</th>
                        <th style="padding:6px">Kecamatan</th>
                        <th style="padding:6px">Desa</th>
                        <th style="padding:6px">Instansi & Detail Alamat</th>
                        <th style="padding:6px">Waktu Pemberangkatan</th>
                    </tr>
                    <tr>
                        <td width="16%" style="padding:6px;vertical-align:top">
                            {{$value->nama_provinsi}}
                        </td>
                        <td width="16%" style="padding:6px;vertical-align:top">
                            {{$value->nama_kota}}
                        </td>
                        <td width="16%" style="padding:6px;vertical-align:top">
                            {{$value->nama_kecamatan}}
                        </td>
                        <td width="16%" style="padding:6px;vertical-align:top">
                            {{$value->nama_desa}}
                        </td>
                        <td width="18%" style="padding:6px;vertical-align:top">
                            {{$value->instansi}} <br><h6 style="font-size:8pt"> {{$value->detail_alamat}}</h6>
                        </td>
                        <td width="18%" style="padding:6px;vertical-align:top">
                            {{$tanggal_pemberangkatan}} - {{$value->jam_pemberangkatan}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="tujuan_advanced">
            
        </div>
        <div class="row pt-2">
            <div class="col-12 text-center">
                <a class="btn" style="background-color:rgb(236, 165, 32);color:rgb(0, 0, 0)" href="{{route('hris.ga.data_pengajuan_transportasi')}}"><i class="fa fa-caret-left" style="font-size:12pt" aria-hidden="true"></i> Back</a>
                @if($id_user==4241 || $id_user==20 || $id_user==5321 || $id_user==6083)
                <button class="btn btn-success" onclick="saveChanges()"><i class="fa fa-save" style="font-size:11pt"></i> UPDATE REQUEST</button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
@section('footerjs')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/izitoast/dist/js/iziToast.min.js')}}"></script>
<!-- Sweet alert js-->
<script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{URL::asset('assets/js/script2.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery-ui/jquery-ui.min.js') }}"></script>

<script>
    (function($){
      // return 0-padded number as string whose length is 'length'
      function _padDigit(num, length){
        var num_s = num.toString();
        if (num_s.length < length){
          // pad num_s with 0 if num_s's length is smaller than 'length'
          var num_zeros = length - num_s.length;
          for (var i = 0; i < num_zeros; i++){
            num_s = '0' + num_s;
          }
          return num_s;
        }
        else{
          // if not, return as it is
          return num_s;
        }
      }
    
      // check if $elem is focused
      function _isFocused($elem){
        if ($elem.is(":focus")){ return true; }
        else{ return false; }
      }
    
      // check if some $elem's child is focused
      function _isChildFocused($elem){
        if ($elem.find(":focus").length){ return true; }
        else{ return false; }
      }
    
      // check if we can interpret time_str as formatted time
      function _isTime(time_str, format, ampm_text){
        if (typeof ampm_text === "undefined"){
          // default am/pm text
          ampm_text = { am: "am", pm: "pm", AM: "AM", PM: "PM" };
        }
        var i = j = 0;   // indexes of format and time_str
        while (typeof format[i]!=="undefined" || typeof time_str[j]!=="undefined"){
          if (format[i] === '%'){
            // next text must be an hour, minutes or am/pm
            switch (format[i+1]){
              case "h":  // next 2 digits must be 0-padded hour of 12-hour clock
                var hour = parseInt(time_str.substring(j, j+2), 10);
                if (isNaN(hour) || hour <= 0 || 12 < hour){ return false; }
                i += 2; j += 2;
                break;
              case "H":  // next 2 digits must be 0-padded hour of 24-hour clock
                var hour = parseInt(time_str.substring(j, j+2), 10);
                if (isNaN(hour) || hour < 0 || 24 <= hour){ return false; }
                i += 2; j += 2;
                break;
              case "g":  // next 1 or 2 digits must be an hour of 12-hour clock
                var hour_can1 = parseInt(time_str.substring(j, j+2), 10);
                var hour_can2 = parseInt(time_str.substring(j, j+1), 10);
                if (!isNaN(hour_can1) && 10 <= hour_can1 && hour_can1 <= 12){
                  i += 2; j += 2;
                  break;
                }
                else if (!isNaN(hour_can2) && 1 <= hour_can2 && hour_can2 <= 9){
                  i += 2; j += 1;
                  break;
                }
                return false;
              case "G":  // next 1 or 2 digits must be an hour of 24-hour clock
                var hour_can1 = parseInt(time_str.substring(j, j+2), 10);
                var hour_can2 = parseInt(time_str.substring(j, j+1), 10);
                if (!isNaN(hour_can1) && 10 <= hour_can1 && hour_can1 <= 23){
                  i += 2; j += 2;
                  break;
                }
                else if (!isNaN(hour_can2) && 1 <= hour_can2 && hour_can2 <= 9){
                  i += 2; j += 1;
                  break;
                }
                return false;
              case "i":  // next 2 digits must be minutes
                var min = parseInt(time_str.substring(j, j+2), 10);
                if (isNaN(min) || min < 0 || 60 <= min){ return false; }
                i += 2; j += 2;
                break;
              case "a":  // next text must be small am/pm symbol (e.g. am/pm)
                // the length of the texts should be same, otherwise fail to read
                var len = ampm_text.am.length;
                var ampm = time_str.substring(j, j+len);
                if (ampm !== ampm_text.am && ampm !== ampm_text.pm){ return false; }
                i += 2; j += len;
                break;
              case "A":  // next text must be large am/pm symbol (e.g. AM/PM)
                // the length of the texts should be same, otherwise fail to read
                var len = ampm_text.AM.length;
                var ampm = time_str.substring(j, j+len);
                if (ampm !== ampm_text.AM && ampm !== ampm_text.PM){ return false; }
                i += 2; j += len;
                break;
              default:  // this '%' is not a format string
                if (format[i] !== time_str[j]){ return false; }
                i += 1; j += 1;
                break;
            }
          }
          else{
            // check if the current characters are same
            if (format[i] !== time_str[j]){ return false; }
            i += 1; j += 1;
          }
        }
        return true;
      }
    
      // check if we can interpret time_str as time
      function _isNormalTime(time_str){
        // e.g. 12:32, 11:59 pm, 04:01 AM, etc...
        var match1 = time_str.match(/\d{1,2}:\d\d\s*(a.m.|p.m.|am|pm|A.M.|P.M.|AM|PM)?/g);
        // e.g. 12:00 noon, midnight, etc...
        var match2 = time_str.match(/(12)?\s*(midnight|noon|Midnight|Noon)/g);
        if (match1){
          // if something else is included, this is invalid
          if (match1[0] !== time_str){ return false; }
          // check if the hour and minutes are valid
          var nums = time_str.match(/\d{1,2}:\d\d/)[0].split(":");
          var ampm = time_str.match(/(a.m.|p.m.|am|pm|A.M.|P.M.|AM|PM)/);
          var hour = parseInt(nums[0], 10);
          var min = parseInt(nums[1], 10);
          if (ampm){  // this is 12-hour clock
            return 1 <= hour && hour <= 12 && 0 <= min && min < 60;
          }
          else{  // this is 24-hour clock
            return 0 <= hour && hour <= 23 && 0 <= min && min < 60;
          }
        }
        else if (match2){
          // if something else is included, this is invalid
          if (match2[0] !== time_str){ return false; }
          else{ return true; }
        }
      }
    
      // get the timestamp (represented by minutes) from time-like string
      // if time_str is not
      function _timeToInt(time_str){
        var time = time_str.match(/\d{1,2}:\d\d/);
        var ampm = time_str.match(/(a.m.|p.m.|am|pm|A.M.|P.M.|AM|PM)/);
        if (time){
          var nums = time[0].split(":");
          var hour = parseInt(nums[0], 10);
          var min = parseInt(nums[1], 10);
          if (hour >= 24 || min >= 60){ return null; }      // invalid time string
          if (ampm && hour >= 13){ return null; }           // invalid time string
          if (ampm){
            if (ampm[0].charAt(0) === 'A' || ampm[0].charAt(0) === 'a'){  // the time is am
              return (hour%12) * 60 + min;
            }
            else{                  // the time is pm
              return (hour%12 + 12) * 60 + min;
            }
          }
          else{
            return hour*60 + min;
          }
        }
        else if (time_str.search("noon") != -1){ return 12*60; }
        else if (time_str.search("midnight") != -1){ return 0; }
        else{ return null; }
      }
    
      // get the timestamp (represented by minutes) from formatted time-like string
      function _formattedTimeToInt(time_str, format, ampm_text){
        if (typeof ampm_text === "undefined"){
          // default am/pm text
          ampm_text = { am: "am", pm: "pm", AM: "AM", PM: "PM" };
        }
        var i = j = 0;   // indexes of format and time_str
        var placeholders = {};
        while (typeof format[i]!=="undefined" || typeof time_str[j]!=="undefined"){
          if (format[i] === '%'){
            // next text must be an hour, minutes or am/pm
            switch (format[i+1]){
              case "h":  // next 2 digits must be 0-padded hour of 12-hour clock
                var hour = parseInt(time_str.substring(j, j+2), 10);
                if (isNaN(hour) || hour <= 0 || 12 < hour){ return null; }
                placeholders.hour_ampm = hour;
                i += 2; j += 2;
                break;
              case "H":  // next 2 digits must be 0-padded hour of 24-hour clock
                var hour = parseInt(time_str.substring(j, j+2), 10);
                if (isNaN(hour) || hour < 0 || 24 <= hour){ return null; }
                placeholders.hour_24 = hour;
                i += 2; j += 2;
                break;
              case "g":  // next 1 or 2 digits must be an hour of 12-hour clock
                var hour_can1 = parseInt(time_str.substring(j, j+2), 10);
                var hour_can2 = parseInt(time_str.substring(j, j+1), 10);
                if (!isNaN(hour_can1) && 10 <= hour_can1 && hour_can1 <= 12){
                  placeholders.hour_ampm = hour_can1;
                  i += 2; j += 2;
                  break;
                }
                else if (!isNaN(hour_can2) && 1 <= hour_can2 && hour_can2 <= 9){
                  placeholders.hour_ampm = hour_can2;
                  i += 2; j += 1;
                  break;
                }
                return null;
              case "G":  // next 1 or 2 digits must be an hour of 24-hour clock
                var hour_can1 = parseInt(time_str.substring(j, j+2), 10);
                var hour_can2 = parseInt(time_str.substring(j, j+1), 10);
                if (!isNaN(hour_can1) && 10 <= hour_can1 && hour_can1 <= 23){
                  placeholders.hour_24 = hour_can1;
                  i += 2; j += 2;
                  break;
                }
                else if (!isNaN(hour_can2) && 1 <= hour_can2 && hour_can2 <= 9){
                  placeholders.hour_24 = hour_can2;
                  i += 2; j += 1;
                  break;
                }
                return null;
              case "i":  // next 2 digits must be minutes
                var min = parseInt(time_str.substring(j, j+2), 10);
                if (isNaN(min) || min < 0 || 60 <= min){ return null; }
                placeholders.minute = min;
                i += 2; j += 2;
                break;
              case "a":  // next text must be small am/pm symbol (e.g. am/pm)
                // the length of the texts should be same, otherwise fail to read
                var len = ampm_text.am.length;
                var ampm = time_str.substring(j, j+len);
                if (ampm !== ampm_text.am && ampm !== ampm_text.pm){ return null; }
                placeholders.ampm = ampm;
                i += 2; j += len;
                break;
              case "A":  // next text must be large am/pm symbol (e.g. AM/PM)
                // the length of the texts should be same, otherwise fail to read
                var len = ampm_text.AM.length;
                var ampm = time_str.substring(j, j+len);
                if (ampm !== ampm_text.AM && ampm !== ampm_text.PM){ return null; }
                placeholders.ampm = ampm;
                i += 2; j += len;
                break;
              default:  // this '%' is not a format string
                if (format[i] !== time_str[j]){ return null; }
                i += 1; j += 1;
                break;
            }
          }
          else{
            // check if the current characters are same
            if (format[i] !== time_str[j]){ return null; }
            i += 1; j += 1;
          }
        }
        if (!placeholders.minute){ return null; }
        else if (placeholders.ampm && placeholders.hour_ampm){
          if (placeholders.ampm === ampm_text.am || placeholders.ampm === ampm_text.AM){
            // this time is am
            return (placeholders.hour_ampm%12)*60 + placeholders.minute;
          }
          else{  // this time is pm
            return (placeholders.hour_ampm%12)*60 + 12*60 + placeholders.minute;
          }
        }
        else if(placeholders.hour_24){
          return placeholders.hour_24*60 + placeholders.minute;
        }
        else{
          return null;
        }
      }
    
      // check if a timestamp is in range of [start, end]
      // if start > end, this range spreads across next day
      function _isTimeInRange(timestamp, start, end){
        if (start > end){
          // the range spreads across next day
          return (start <= timestamp || timestamp <= end);
        }
        else{
          return (start <= timestamp && timestamp <= end);
        }
      }
    
      // try to select an option of 'value' and return true if 'value' exist
      function _selectOption(select, value){
        var options = select.options;
        var len = options.length;
        for (var i = 0; i < len; i++){
          if (options[i].value == value && options[i].disabled == false){
            options[i].selected = true;
            return true;
          }
        }
        return false;
      }
    
      // get duration between start and end
      // if start > end, this range spreads across next day
      function _getTimeDuration(start, end){
        if (start > end){ return end + 24*60 - start; }
        else{ return end - start; }
      }
    
      // get the range of hour from the 2 timestamps(minute)
      function _getHourRange(timestamp_start, timestamp_end, hstep){
        var hour_range = [];
        if (timestamp_start < timestamp_end){
          for (var i = Math.floor(timestamp_start/60); i <= Math.floor(timestamp_end/60); i+=hstep){
            hour_range.push(i);
          }
        }
        else{  // start > end
          for (var i = Math.floor(timestamp_start/60); i < 24; i+=hstep){
            hour_range.push(i);
          }
          for (var i = 0; i <= Math.floor(timestamp_end/60); i+=hstep){
            hour_range.push(i);
          }
        }
        return hour_range;
      }
    
      // scroll to an option of 'hour'
      function _scrollToHourOption(select, hour){
        var options = select.options;
        var len = options.length;
        if (parseInt(options[0], 10) == hour){
          $(select).scrollTop(0);
          return;
        }
        var height = $(select).height();
        var opt_height = height / $(select).prop("size");
        for (var i = 1; i < len; i++){
          var prev_hour = parseInt(options[i-1].value, 10);
          var next_hour = parseInt(options[i].value, 10);
          if (next_hour == hour){
            $(select).scrollTop(opt_height * (i+1/2) - height/2);
            return;
          }
          if (prev_hour > next_hour){
            if (prev_hour < hour || hour < next_hour){
              $(select).scrollTop(opt_height * i - height/2);
              return;
            }
          }
          else if (prev_hour < hour && hour < next_hour){
            $(select).scrollTop(opt_height * i - height/2);
            return;
          }
        }
      }
    
      // scroll to an option of 'minute'
      function _scrollToMinuteOption(select, minute){
        var options = select.options;
        var len = options.length;
        if (parseInt(options[0], 10) == minute){
          $(select).scrollTop(0);
          return;
        }
        var height = $(select).height();
        var opt_height = height / $(select).prop("size");
        for (var i = 1; i < len; i++){
          var prev_minute = parseInt(options[i-1].value, 10);
          var next_minute = parseInt(options[i].value, 10);
          if (next_minute == minute){
            $(select).scrollTop(opt_height * (i+1/2) - height/2);
            return;
          }
          else if (prev_minute < minute && minute < next_minute){
            $(select).scrollTop(opt_height * i - height/2);
            return;
          }
        }
      }
    
      // create select-options of hours or minutes
      // This function will be called to create select-options for the first time.
      function _createSelectOptions(which, settings, $picker){
        if (which === "hour"){
          var hour_select = $picker.find(".hour-select").get(0);
          // clear all options
          while (hour_select.firstChild){
            hour_select.removeChild(hour_select.firstChild);
          }
          if (settings.use12HourClock){
            var ampm = $picker.find(".ampm-button").text();
            if (ampm === "am"){
               var len = settings.hour_data_am.length;
               for (var i = 0; i < len; i++){
                 var option = document.createElement("option");
                 var hour = settings.hour_data_am[i].hour;
                 option.value = hour;
                 option.innerHTML = _padDigit(hour, 2);
                 if (settings.hour_data_am[i].disabled){
                   option.disabled = true;
                 }
                 hour_select.appendChild(option);
               }
            }
            else{  // ampm=="pm"
              var len = settings.hour_data_pm.length;
              for (var i = 0; i < len; i++){
                var option = document.createElement("option");
                var hour = settings.hour_data_pm[i].hour;
                option.value = hour;
                option.innerHTML = _padDigit(hour, 2);
                if (settings.hour_data_pm[i].disabled){
                  option.disabled = true;
                }
                hour_select.appendChild(option);
              }
            }
          }
          else{
            var len = settings.hour_data_24.length;
            for (var i = 0; i < len; i++){
              var option = document.createElement("option");
              var hour = settings.hour_data_24[i].hour;
              option.value = hour;
              option.innerHTML = _padDigit(hour, 2);
              if (settings.hour_data_24[i].disabled){
                option.disabled = true;
              }
              hour_select.appendChild(option);
            }
          }
        }
        else if (which === "minute"){
          var min_select = $picker.find(".min-select").get(0);
          var step;
          if (settings.is_set_time_step){
            step = settings.timeStep;
          }
          else{
            step = settings.minStep;
          }
          for (var i = 0; i < 60; i+=step){
            var option = document.createElement("option");
            option.value = i;
            option.innerHTML = _padDigit(i, 2);
            min_select.appendChild(option);
          }
        }
      }
    
      // change select-options of hours or minutes
      // depending on the currently selected hour and am/pm
      // This function will be called when some properties has changed.
      function _changeSelectOptions(which, settings, $picker){
        // change select-options of hours when am/pm has changed
        if (which === "hour" && settings.is_set_minmax_time){
          var hour_select = $picker.find(".hour-select").get(0);
          var hvalue = hour_select.value;
          // clear all options
          while (hour_select.firstChild){
            hour_select.removeChild(hour_select.firstChild);
          }
          if (settings.use12HourClock){
            var ampm = $picker.find(".ampm-button").text();
            if (ampm === "am"){
               var len = settings.hour_data_am.length;
               for (var i = 0; i < len; i++){
                 var option = document.createElement("option");
                 var hour = settings.hour_data_am[i].hour;
                 option.value = hour;
                 option.innerHTML = _padDigit(hour, 2);
                 if (settings.hour_data_am[i].disabled){
                   option.disabled = true;
                 }
                 hour_select.appendChild(option);
               }
            }
            else{  // ampm=="pm"
              var len = settings.hour_data_pm.length;
              for (var i = 0; i < len; i++){
                var option = document.createElement("option");
                var hour = settings.hour_data_pm[i].hour;
                option.value = hour;
                option.innerHTML = _padDigit(hour, 2);
                if (settings.hour_data_pm[i].disabled){
                  option.disabled = true;
                }
                hour_select.appendChild(option);
              }
            }
          }
          if (hvalue){
            // scroll to and select the previously selected option of hours
            // and call the latter part of this function
            _scrollToHourOption(hour_select, hvalue);
            if (_selectOption(hour_select, hvalue)){
              _changeSelectOptions("minute", settings, $picker);
            }
          }
        }
        // change select-options of minutes when hours has changed
        else if (which === "minute"){
          var $hour_select = $picker.find(".hour-select");
          var min_select = $picker.find(".min-select").get(0);
          var hour_index = $hour_select.prop("selectedIndex");
          var hour = parseInt($hour_select.val(), 10);
          var mvalue = min_select.value;
          var ampm = $picker.find(".ampm-button").text();
          var hour_data;
          if (settings.use12HourClock){
            if (ampm === "am"){
              hour_data = settings.hour_data_am[hour_index];
              hour = hour%12;       // get hour in 24-hour clock
            }
            else{
              hour_data = settings.hour_data_pm[hour_index];
              hour = hour%12 + 12;  // get hour in 24-hour clock
            }
          }
          else{
            hour_data = settings.hour_data_24[hour_index];
          }
          // clear all options
          while (min_select.firstChild){
            min_select.removeChild(min_select.firstChild);
          }
          // get min and max minute
          var min_minute = hour_data.min_minute;
          var max_minute = hour_data.max_minute;
          min_minute = (min_minute!=undefined) ? min_minute : 0;
          max_minute = (max_minute!=undefined) ? max_minute : 59;
          // get start minute and end minute
          var start, end, step;
          if (settings.is_set_time_step){    // use timeStep
            step = settings.timeStep;
            var duration = _getTimeDuration(_timeToInt(settings.minTime), hour*60+min_minute);
            start = min_minute + (step - duration%step) % step;
            end = max_minute;
          }
          else{                            // use minStep
            step = settings.minStep;
            start = Math.ceil(min_minute / step) * step;
            end = max_minute;
          }
          // append select-options of minutes
          for (var i = start; i <= end; i+=step){
            var option = document.createElement("option");
            option.value = i;
            option.innerHTML = _padDigit(i, 2);
            min_select.appendChild(option);
          }
          // disable some select-options of minutes
          if (settings.is_set_disabled_time){
            var options = min_select.options;
            var optionLen = options.length;
            var disabledLen = settings.disableTimeRanges.length;
            for (var i = 0; i < disabledLen; i++){
              var disable_start = hour_data["disable_start_minute"+i];
              var disable_end = hour_data["disable_end_minute"+i];
              if (typeof disable_start === "undefined" &&
                  typeof disable_end === "undefined"){
                continue;
              }
              else if (disable_start <= disable_end){
                for (var j = 0; j< optionLen; j++){
                  var min = parseInt(options[j].value, 10);
                  if (disable_start <= min && min <= disable_end){
                    options[j].disabled = true;
                  }
                }
              }
              else{
                for (var j = 0; j < optionLen; j++){
                  var min = parseInt(options[j].value, 10);
                  if (disable_start <= min){
                    options[j].disabled = true;
                  }
                  else if (min <= disable_end){
                    options[j].disabled = true;
                  }
                }
              }
            }
          }
          // select the previously selected option of minutes
          if (mvalue){
            _scrollToMinuteOption(min_select, mvalue);
            _selectOption(min_select, mvalue);
          }
        }
      }
    
      // format and output the selected time
      function _outputTime(settings, $picker, $input){
        var hour = parseInt($picker.find(".hour-select").val(), 10);
        var min = parseInt($picker.find(".min-select").val(), 10);
        if (!isNaN(hour) && !isNaN(min)){
          var formatted = settings.timeFormat
            .replace("%g", (hour==0 || hour==12) ? 12 : hour%12)
            .replace("%G", hour)
            .replace("%h", _padDigit((hour==0 || hour==12) ? 12 : hour%12, 2))
            .replace("%H", _padDigit(hour, 2))
            .replace("%i", _padDigit(min, 2));
          if (settings.use12HourClock){
            var ampm = $picker.find(".ampm-button").text();
            formatted = formatted
              .replace("%a", settings.ampmText[ampm])
              .replace("%A", settings.ampmText[ampm.toUpperCase()]);
          }
          else{
            var ampm = (hour < 12) ? "am" : "pm";
            formatted = formatted
              .replace("%a", settings.ampmText[ampm])
              .replace("%A", settings.ampmText[ampm.toUpperCase()]);
          }
          $input.val(formatted);
        }
      }
    
      // create timepicker object
      function _createTimepicker($input){
        var settings = $input.data("timepicker-settings");
    
        // create a picker and containers
        var picker = document.createElement("div");
        var header_div = document.createElement("div");
        var select_div = document.createElement("div");
        var button_div = document.createElement("div");
    
        // create headers
        var hour_header = document.createElement("div");
        var min_header = document.createElement("div");
        header_div.appendChild(hour_header);
        header_div.appendChild(min_header);
    
        // create select for hours and minutes
        var hour_select = document.createElement("select");
        hour_select.setAttribute("size", settings.selectSize);
        hour_select.setAttribute("tabindex", "-1");
        var min_select = document.createElement("select");
        min_select.setAttribute("size", settings.selectSize);
        min_select.setAttribute("tabindex", "-1");
        // select-options will be created in _showTimepicker for the first time,
        // and may change when some properties has changed
    
    
        // assemble timepicker
        select_div.appendChild(hour_select);
        select_div.appendChild(min_select);
        picker.appendChild(header_div);
        picker.appendChild(select_div);
        picker.appendChild(button_div);
    
        // layout items by setting class name
        picker.className = "my-timepicker-div";
        header_div.className = "row";
        select_div.className = "row";
        button_div.className = "row";
        hour_header.className = "text-center col-6";
        min_header.className = "text-center col-6";
    
        if (settings.use12HourClock){
          // if using 12-hour clock, widen the picker to 18rem
          // and create am/pm button
          $(picker).css("width", "19.2rem");
          hour_header.className = "text-center col-5";
          min_header.className = "text-center col-5";
          hour_select.className = "form-control col-5 hour-select";
          min_select.className = "form-control col-5 min-select";
    
          var ampm_button = document.createElement("button");
          var ampm_button_wrapper = document.createElement("span");
          ampm_button.setAttribute("tabindex", "-1");
          ampm_button.innerHTML = "am";
          ampm_button_wrapper.appendChild(ampm_button);
          select_div.appendChild(ampm_button_wrapper);
          ampm_button.className = "btn btn-primary ampm-button";
          ampm_button_wrapper.className = "col-2 my-auto";
        }
        else{
          // default width of the picker is 12rem
          $(picker).css("width", "6rem");
          $(picker).css("position", "absolute");
          $(picker).css('z-index', 1);
          $(picker).css('padding-left', 8);
          hour_select.className = "form-control col-6 hour-select";
          min_select.className = "form-control col-6 min-select";
        }
    
        return $(picker);
      }
    
      // set event listeners to $picker related to $input
      function _bindTimepicker($picker, $input){
        var settings = $input.data("timepicker-settings");
        // if using 12-hour clock, change select-options of hours when am/pm changed
        if (settings.use12HourClock){
          $picker.find(".ampm-button").on("click", function(){
            var $this = $(this);
            if ($this.html() === "pm"){ $this.html("am"); }
            else{ $this.html("pm"); }
            _changeSelectOptions("hour", settings, $picker);
          });
        }
        // change select-options of minutes when selected hour has changed
        $picker.find(".hour-select").on("change", function(){
          if (settings.is_set_time_step ||
              settings.is_set_minmax_time ||
              settings.is_set_disabled_time){
                _changeSelectOptions("minute", settings, $picker);
          }
        });
        // When its focus blurred, remove the timepicker.
        // Do this process after 50 milliseconds because a blur event
        // fires even when the focus moves to the timepicker.
        $picker.on("focusout", function(){
          setTimeout(function(){
            var $current_picker = _getTimepicker($input);
            // If a picker is being showed,
            if ($current_picker.length){
              // and if the picker does not have focus,
              if (!_isFocused($input) && !_isChildFocused($current_picker)){
                if (settings.selectOnBlur){
                  _outputTime(settings, $picker, $input);
                }
                // remove the picker.
                _outputTime(settings, $picker, $input);
                $input.trigger("change");
                _removeTimepicker($input);
              }
            }
          }, 50);
        });
        // When the ok button was pushed, output the selected time
        // and remove the timepicker.
      }
    
      // show and set up the timepicker
      function _showTimepicker($picker, $input){
        var settings = $input.data("timepicker-settings");
        // create select-options and select each of them which matches
        // the current value of $input before the timepicker is showed
        var timestamp = _formattedTimeToInt($input.val(), settings.timeFormat,
                                                settings.ampmText);
        var hour_select = $picker.find(".hour-select").get(0);
        var min_select = $picker.find(".min-select").get(0);
        var hour, min;
        // $input has a value, so create select-options depending on the value
        if (timestamp != null){
          min = timestamp % 60;
          if (settings.use12HourClock){
            hour = Math.floor(timestamp / 60) % 12;
            hour = (hour != 0) ? hour : 12;
            var ampm = (timestamp < 12*60) ? "am" : "pm";
            // set am/pm firstly
            $picker.find(".ampm-button").text(ampm);
          }
          else{
            hour = Math.floor(timestamp / 60);
          }
          // create select-options of hours
          _createSelectOptions("hour", settings, $picker);
          // select an option of 'hour'
          var selected = _selectOption(hour_select, hour);
          // if succeeded in selecting, change min-select's options
          if (selected){
            _changeSelectOptions("minute", settings, $picker);
          }
          // else, create min-select's options of default
          else{
            _createSelectOptions("minute", settings, $picker);
          }
          // select an option of 'min'
          _selectOption(min_select, min);
        }
        // $input has no value but scrollDefault is set, so change am/pm in advance
        else if (settings.scrollDefault){
          timestamp = _timeToInt(settings.scrollDefault);
          if (timestamp != null){
            min = timestamp % 60;
            if (settings.use12HourClock){
              hour = Math.floor(timestamp / 60) % 12;
              hour = (hour != 0) ? hour : 12;
              var ampm = (timestamp < 12*60) ? "am" : "pm";
              $picker.find(".ampm-button").text(ampm);
            }
            else{
              hour = Math.floor(timestamp / 60);
            }
          }
          // create select-options of default
          _createSelectOptions("hour", settings, $picker);
          _createSelectOptions("minute", settings, $picker);
        }
        // $input has no value, so create select-options of default
        else{
          _createSelectOptions("hour", settings, $picker);
          _createSelectOptions("minute", settings, $picker);
        }
    
        // show the timepicker, $picker
        var left_pos = $input.offset()["left"];
        var top_pos = $input.offset()["top"] +
                      $input.outerHeight() + 5;
        $picker.insertAfter($input);
        $picker.offset({left: left_pos, top: top_pos});
    
        // scroll to options which match 'timestamp'
        if (typeof hour !== "undefined"){
          _scrollToHourOption(hour_select, hour);
        }
        if (typeof min !== "undefined"){
          _scrollToMinuteOption(min_select, min);
        }
      }
    
      // get a timepicker which was bound with $input
      function _getTimepicker($input){
        return $input.next(".my-timepicker-div");
      }
    
      // check if $input has a timepicker
      function _hasTimepicker($input){
        if (_getTimepicker($input).length){ return true; }
        else{ return false; }
      }
    
      // remove a timepicker $input has
      function _removeTimepicker($input){
        var $picker = $input.next(".my-timepicker-div");
        $picker.remove();
      }
    
      var methods = {
    
        /* initialize inputs for timepicker */
        // This method will be called when you call $input.timepicker([option])
        init: function(options){
          var base_data = $.extend({}, $.fn.timepicker.default_options, options);
    
          // set up other settings from options
          var other_data = {
            hour_data_24 : null,
            hour_data_am : null,
            hour_data_pm : null,
            // used for optimization later
            is_set_minmax_time : false,
            is_set_time_step : false,
            is_set_disabled_time : false
          };
          var timestamp_start = 0;     // 00:00 -> 0
          var timestamp_end = 1439;    // 23:59 -> 24*60-1
          if (base_data.minTime){
            timestamp_start = _timeToInt(base_data.minTime);
            other_data.is_set_minmax_time = true;
          }
          if (base_data.maxTime){
            timestamp_end = _timeToInt(base_data.maxTime);
            other_data.is_set_minmax_time = true;
          }
          var hstep;
          if (base_data.timeStep){
            other_data.is_set_time_step = true;
            hstep = 1;
          }
          else{
            hstep = base_data.hourStep;
          }
          if (base_data.disableTimeRanges){
            other_data.is_set_disabled_time = true;
          }
    
          // use 12-hour clock
          if (base_data.use12HourClock){
            // make hour range
            var hour_range = _getHourRange(timestamp_start, timestamp_end, hstep);
            other_data.hour_data_am = hour_range
              .filter(function(hour){ return hour < 12; })
              .map(function(h){ return (h!=0) ? {hour:h} : {hour:12}; });
            other_data.hour_data_pm = hour_range
              .filter(function(hour){ return hour >= 12; })
              .map(function(h){ return (h!=12) ? {hour:h%12} : {hour:12}; });
            // determine the start hour
            if (hour_range[0] < 12){
              // the start hour is am: save min minute in hour_data_am
              other_data.hour_data_am[0].min_minute = timestamp_start%60;
            }
            else{
              // the start hour is pm: save min minute in hour_data_pm
              other_data.hour_data_pm[0].min_minute = timestamp_start%60;
            }
            // determine the end hour
            if (hour_range[hour_range.length-1] < 12){
              // the end hour is am: save end minute in hour_data_am
              other_data.hour_data_am[other_data.hour_data_am.length-1].max_minute
                = timestamp_end%60;
            }
            else{
              // the end hour is pm: save end minute in hour_data_pm
              other_data.hour_data_pm[other_data.hour_data_pm.length-1].max_minute
                = timestamp_end%60;
            }
          }
          // default clock: 24 hour clock
          else{
            // make hour range
            other_data.hour_data_24 =
              _getHourRange(timestamp_start, timestamp_end, hstep)
              .map(function(h){ return {hour:h}; });
            if (other_data.is_set_minmax_time){
              // save start and end minutes in hour_data_24
              other_data.hour_data_24[0].min_minute = timestamp_start%60;
              other_data.hour_data_24[other_data.hour_data_24.length-1].max_minute
                = timestamp_end%60;
            }
          }
    
          // make data for disabled time
          if (other_data.is_set_disabled_time){
            var disabledLen = base_data.disableTimeRanges.length
            for (var i = 0; i < disabledLen; i++){
              var disable_start = _timeToInt(base_data.disableTimeRanges[i][0]);
              var disable_end = _timeToInt(base_data.disableTimeRanges[i][1]);
              // use 12-hour clock
              if (base_data.use12HourClock){
                other_data.hour_data_am.forEach(function(hdata){
                  // get hour in 24-hour clock
                  var hour = hdata.hour%12;
                  if (hour*60 == (disable_start - disable_start%60)){
                    // hour is the start of disabled hour range
                    hdata["disable_start_minute"+i] = disable_start%60;
                  }
                  if (hour*60 == (disable_end - disable_end%60)){
                    // hour is the end of disabled hour range
                    hdata["disable_end_minute"+i] = disalbe_end%60;
                  }
                  if (_isTimeInRange(hour*60, disable_start, disable_end) &&
                      _isTimeInRange(hour*60+59, disable_start, disable_end)){
                    // hour is a disabled hour
                    hdata.disabled = true;
                  }
                });
                other_data.hour_data_pm.forEach(function(hdata){
                  // get hour in 24-hour clock
                  var hour = hdata%12 + 12;
                  if (hour*60 == (disable_start - disable_start%60)){
                    // hour is the start of disabled hour range
                    hdata["disable_start_minute"+i] = disable_start%60;
                  }
                  if (hour == (disable_end - disable_end%60)){
                    // hour is the end of disabled hour range
                    hdata["disable_end_minute"+i] = disable_end%60;
                  }
                  if (_isTimeInRange(hour*60, disable_start, disable_end) &&
                      _isTimeInRange(hour*60+59, disable_start, disable_end)){
                    // hour is a disabled hour
                    hdata.disabled = true;
                  }
                });
              }
              // default clock: 24-hour clock
              else{
                other_data.hour_data_24.forEach(function(hdata){
                  var hour = hdata.hour;
                  if (hour*60 == (disable_start - disable_start%60)){
                    // hour is the start of disabled hour range
                    hdata["disable_start_minute"+i] = disable_start%60;
                  }
                  if (hour*60 == (disable_end - disable_end%60)){
                    // hour is the end of disabled hour range
                    hdata["disable_end_minute"+i] = disable_end%60;
                  }
                  if (_isTimeInRange(hour*60, disable_start, disable_end) &&
                      _isTimeInRange(hour*60+59, disable_start, disable_end)){
                    // hour is a disabled hour
                    hdata.disabled = true;
                  }
                });
              }
            }
          }
    
          // save these settings and set event listeners to $input
          return this.each(function(){
            var settings = $.extend({}, base_data, other_data);
            var $this_input = $(this);
            $this_input.data("timepicker-settings", settings);
    
            // timepicker must be used only for input tag
            if ($this_input.prop("tagName") === "INPUT"){
    
              // set placeholder
              $this_input.attr({ placeholder : "--:--" });
              // show timepicker when
              $this_input.on("focus.timepicker", function(){
                var $this = $(this);
                // do nothing if a timepicker has been already showed
                if (_hasTimepicker($this)){
                  return;
                }
                var $picker = _createTimepicker($this);
                _bindTimepicker($picker, $this);
                _showTimepicker($picker, $this);
              });
              // When its focus blurred, remove the timepicker.
              // Do this process after 50 milliseconds because a blur event
              // fires even when the focus moves to the timepicker.
              $this_input.on("blur.timepicker", function(){
                var $this = $(this);
                setTimeout(function(){
                  var $picker = _getTimepicker($this);
                  // If a picker is being showed,
                  if ($picker.length){
                    // and if the picker does not have focus,
                    if (!_isFocused($this) && !_isChildFocused($picker)){
                      if (settings.selectOnBlur){
                        _outputTime(settings, $picker, $this);
                      }
                      // remove the picker.
                      _removeTimepicker($this);
                    }
                  }
                }, 50);
              })
              // When the value of the input changed to non-time string, reset it
              $this_input.on("change.timepicker", function(){
                var $this = $(this);
                if (!_isTime($this.val(), settings.timeFormat, settings.ampmText)){
                  $this.val("");
                }
              });
            }
          });
        },
    
        /* set up settings */
        // This method must be used for inputs which are uninitialized for timepicker
        setup: function(options){
          return this.each(function(){
            // set data which is needed for the timepicker
            var settings = $.extend({}, base_data, other_data);
            $(this).data("timepicker-settings", settings);
          });
        },
    
        /* configure settings */
        // This method will process slower than setup method if you configure settings
        // for the first time because of the heavy loop
        option: function(options){
          return this.each(function(){
            var base_data;
            var current_settings = $(this).data("timepicker-settings");
            if (typeof current_settings === "undefined"){
              base_data = $.extend({}, $.fn.timepicker.default_options, options);
            }
            else{
              base_data = $.extend({}, current_settings, options);
            }
    
            // set up other settings from options
            var other_data = {
              hour_data_24 : null,
              hour_data_am : null,
              hour_data_pm : null,
              // used for optimization later
              is_set_minmax_time : false,
              is_set_time_step : false,
              is_set_disabled_time : false
            };
            var timestamp_start = 0;     // 00:00 -> 0
            var timestamp_end = 1439;    // 23:59 -> 24*60-1
            if (base_data.minTime){
              timestamp_start = _timeToInt(base_data.minTime);
              other_data.is_set_minmax_time = true;
            }
            if (base_data.maxTime){
              timestamp_end = _timeToInt(base_data.maxTime);
              other_data.is_set_minmax_time = true;
            }
            var hstep;
            if (base_data.timeStep){
              other_data.is_set_time_step = true;
              hstep = 1;
            }
            else{
              hstep = base_data.hourStep;
            }
            if (base_data.disableTimeRanges){
              other_data.is_set_disabled_time = true;
            }
    
            // use 12-hour clock
            if (base_data.use12HourClock){
              // make hour range
              var hour_range = _getHourRange(timestamp_start, timestamp_end, hstep);
              other_data.hour_data_am = hour_range
                .filter(function(hour){ return hour < 12; })
                .map(function(h){ return (h!=0) ? {hour:h} : {hour:12}; });
              other_data.hour_data_pm = hour_range
                .filter(function(hour){ return hour >= 12; })
                .map(function(h){ return (h!=12) ? {hour:h} : {hour:12}; });
              // determine the start hour
              if (hour_range[0] < 12){
                // the start hour is am: save min minute in hour_data_am
                other_data.hour_data_am[0].min_minute = timestamp_start%60;
              }
              else{
                // the start hour is pm: save min minute in hour_data_pm
                other_data.hour_data_pm[0].min_minute = timestamp_start%60;
              }
              // determine the end hour
              if (hour_range[hour_range.length-1] < 12){
                // the end hour is am: save end minute in hour_data_am
                other_data.hour_data_am[other_data.hour_data_am.length-1].max_minute
                  = timestamp_end%60;
              }
              else{
                // the end hour is pm: save end minute in hour_data_pm
                other_data.hour_data_pm[other_data.hour_data_pm.length-1].max_minute
                  = timestamp_end%60;
              }
            }
            // default clock: 24 hour clock
            else{
              // make hour range
              other_data.hour_data_24 =
                _getHourRange(timestamp_start, timestamp_end, hstep)
                .map(function(h){ return {hour:h}; });
              if (other_data.is_set_minmax_time){
                // save start and end minutes in hour_data_24
                other_data.hour_data_24[0].min_minute = timestamp_start%60;
                other_data.hour_data_24[other_data.hour_data_24.length-1].max_minute
                  = timestamp_end%60;
              }
            }
    
            // make data for disabled time
            if (base_data.disableTimeRanges){
              var disabledLen = base_data.disableTimeRanges.length;
              for (var i = 0; i < disabledLen; i++){
                var disable_start = _timeToInt(base_data.disableTimeRanges[i][0]);
                var disable_end = _timeToInt(base_data.disableTimeRanges[i][1]);
                // use 12-hour clock
                if (base_data.use12HourClock){
                  other_data.hour_data_am.forEach(function(hdata){
                    // get hour in 24-hour clock
                    var hour = hdata.hour%12;
                    if (hour*60 == (disable_start - disable_start%60)){
                      // hour is the start of disabled hour range
                      hdata["disable_start_minute"+i] = disable_start%60;
                    }
                    if (hour*60 == (disable_end - disable_end%60)){
                      // hour is the end of disabled hour range
                      hdata["disable_end_minute"+i] = disalbe_end%60;
                    }
                    if (_isTimeInRange(hour*60, disable_start, disable_end) &&
                        _isTimeInRange(hour*60+59, disable_start, disable_end)){
                      // hour is a disabled hour
                      hdata.disabled = true;
                    }
                  });
                  other_data.hour_data_pm.forEach(function(hdata){
                    // get hour in 24-hour clock
                    var hour = hdata%12 + 12;
                    if (hour*60 == (disable_start - disable_start%60)){
                      // hour is the start of disabled hour range
                      hdata["disable_start_minute"+i] = disable_start%60;
                    }
                    if (hour == (disable_end - disable_end%60)){
                      // hour is the end of disabled hour range
                      hdata["disable_end_minute"+i] = disable_end%60;
                    }
                    if (_isTimeInRange(hour*60, disable_start, disable_end) &&
                        _isTimeInRange(hour*60+59, disable_start, disable_end)){
                      // hour is a disabled hour
                      hdata.disabled = true;
                    }
                  });
                }
                // default clock: 24-hour clock
                else{
                  other_data.hour_data_24.forEach(function(hdata){
                    var hour = hdata.hour;
                    if (hour*60 == (disable_start - disable_start%60)){
                      // hour is the start of disabled hour range
                      hdata["disable_start_minute"+i] = disable_start%60;
                    }
                    if (hour*60 == (disable_end - disable_end%60)){
                      // hour is the end of disabled hour range
                      hdata["disable_end_minute"+i] = disable_end%60;
                    }
                    if (_isTimeInRange(hour*60, disable_start, disable_end) &&
                        _isTimeInRange(hour*60+59, disable_start, disable_end)){
                      // hour is a disabled hour
                      hdata.disabled = true;
                    }
                  });
                }
              }
            }
    
            // set data which is needed for the timepicker
            var settings = $.exnted({}, base_data, other_data);
            $(this).data("timepicker-settings", settings);
          });
        },
    
       /* show a timepicker */
        show: function(){
          return this.each(function(){
            var $this_input = $(this);
            // if $this_input is not set up, do nothing
            if (!$this_input.data("timepicker-settings")){
              return false;
            }
            // create, bind, show and initialize timepicker if not being showed
            if (!_hasTimepicker($this_input)){
              var $picker = _createTimepicker($this_input);
              _bindTimepicker($picker, $this_input);
              _showTimepicker($picker, $this_input);
            }
            // ignore the rest of the elements
            return false;
          });
        },
    
        /* hide a timepicker */
        hide: function(){
          return this.each(function(){
            var $this_input = $(this);
            if (_hasTimepicker($this_input)){
              _removeTimepicker($this_input);
            }
          });
        },
    
        /* destroy settings and unbind input of timepicker */
        remove: function(){
          return this.each(function(){
            var $this_input = $(this);
            if (_hasTimepicker($this_input)){
              _removeTimepicker($this_input);
            }
            $this_input.removeData("timepicker-settings");
            $this_input.off(".timepicker");
          });
        }
      }
    
      $.fn.timepicker = function(method){
        if (!this.length) return this;
        if (methods[method]){
          return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === "object" || !method){
          return methods.init.apply(this, arguments);
        }
        else{
          $.error( 'Method ' +  method + ' does not exist on jQuery.timepicker' );
        }
      };
    
      $.fn.timepicker.default_options = {
        ampmText: { am:"am", pm:"pm", AM:"AM", PM:"PM" },
        hourStep: 1,
        minStep: 1,
        timeStep: null,
        minTime: null,
        maxTime: null,
        disableTimeRanges: null,
        selectOnBlur: false,
        selectSize: 4,
        timeFormat: "%H:%i",
        use12HourClock: false
      }
    })(jQuery);
    
</script>
<script>
    var array_provinsi=[];
    var array_kota=[];
    var array_kecamatan=[];
    var array_desa=[];
    var array_instansi=[];
    var array_detail_alamat=[];
    var array_tanggal_pemberangkatan=[];
    var array_jam_pemberangkatan=[];
    var array_tujuan_pemberangkatan=[];
    var array_jarak_tempuh=[];
    var array_jenis_barang=[];
    var array_quantity=[];
    var array_satuan=[];
    var array_nama_penerima=[];
    var array_keterangan_barang=[];
    var array_nama_tamu=[];
    var array_nomor_hp_tamu=[];
    var array_karyawan_dinas_luar=[];
    var array_driver=[];
    var array_driver_name=[];
    var array_vehicle=[];
    var array_vehicle_name=[];
    var array_status=[];
    var array_alternative=[];
    var array_keterangan=[];
    $(document).ready(function() {
        get_tujuan_detail();
        var status=$('#status').val();
        if(status==2){
            document.getElementById('tag_alternative').style.display='block';
        }else{
            document.getElementById('tag_alternative').style.display='none';
        }
    });
    function get_data_detail(){
        var id=$('#id_pengajuan').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_data_detail')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data:{
                id:id,
            },
            success: function(res){
                var dArr = res[0].tanggal_pemberangkatan.split("-"); 
                var tanggal_pemberangkatan = dArr[2]+ "/" +dArr[1]+ "/" +dArr[0];
                change_initial_destination(res[0].prov_id,res[0].city_id,res[0].dis_id,res[0].id_desa);
                $('#tanggal_pemberangkatan').val(tanggal_pemberangkatan);
                $('#tanggal_pemberangkatan-display').val(tanggal_pemberangkatan);
                $('#jam_pemberangkatan').val(res[0].jam_pemberangkatan.substring(0, 5));
            },
            error: function(res){
                swal({
                    title: "Ambil data",
                    text: "Data gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    $(function(){
        'use strict';
        $('.select2oneSelect').select2({
            minimumResultsForSearch: Infinity,
            maximumSelectionLength: 1,
            disabled:true
        });
    });
    function change_initial_destination(province,city,district,subdistrict){
        document.getElementById("warning_provinsi").innerHTML='';
        document.getElementById("warning_kota").innerHTML='';
        document.getElementById("warning_kecamatan").innerHTML='';
        document.getElementById("warning_desa").innerHTML='';
        document.getElementById("detail_alamat").style.border='';
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_province')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#provinsi').empty();
                jQuery.each(res, function(key,value){
                    $('#provinsi').append('<option value="'+ value['prov_id'] +'">'+ value['prov_name'] +'</option>');
                });
                $('#provinsi option[value="' + province + '"]').prop('selected',true);
            },
            error: function(res){
                swal({
                    title: "Ambil data provinsi",
                    text: "Data provinsi gagal di ambil",
                    icon: "danger",
                });
            }
        });
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_cities')}}",
            data: {
                provinsi:province,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#cities').empty();
                jQuery.each(res, function(key,value){
                    $('#cities').append('<option value="'+ value['city_id'] +'">'+ value['city_name'] +'</option>');
                });
                $('#cities option[value="' + city + '"]').prop('selected',true);
            },
            error: function(res){
                swal({
                    title: "Ambil data kota",
                    text: "Data kota gagal di ambil",
                    icon: "danger",
                });
            }
        });
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_districts')}}",
            data: {
                cities:city,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#districts').empty();
                jQuery.each(res, function(key,value){
                    $('#districts').append('<option value="'+ value['dis_id'] +'">'+ value['dis_name'] +'</option>');
                });
                $('#districts option[value="' + district + '"]').prop('selected',true);
            },
            error: function(res){
                swal({
                    title: "Ambil data desa",
                    text: "Data desa gagal di ambil",
                    icon: "danger",
                });
            }
        });
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_subdistricts')}}",
            data: {
                districts:district,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#sub_districts').empty();
                jQuery.each(res, function(key,value){
                    $('#sub_districts').append('<option value="'+ value['subdis_id'] +'">'+ value['subdis_name'] +'</option>');
                });
                $('#sub_districts option[value="' + subdistrict + '"]').prop('selected',true);
            },
            error: function(res){
                swal({
                    title: "Ambil data desa",
                    text: "Data desa gagal di ambil",
                    icon: "danger",
                });
            }
        });
        $('#instansi').val('PT. Nirwana Alabare Garment');
        $('#detail_alamat').val('Jl. Raya Majalaya - Rancaekek No.289');
    }
    $('input[type=date]').each(function (index, element) {
        $(this).attr("type", "text");

        /* Create a hidden clone, which will contain the actual value */
        var clone = $(this).clone();
        clone.insertAfter(this);
        clone.hide();

        /* Rename the original field, used to contain the display value */
        $(this).attr('id', $(this).attr('id') + '-display');
        $(this).attr('name', $(this).attr('name') + '-display');

        /* Create the datepicker with the desired display format and alt field */
        $(this).datepicker({ dateFormat: "dd/mm/yy", altField: "#" + clone.attr("id"), altFormat: "yy-mm-dd" });

        /* Finally, parse the value and change it to the display format */
        if ($(this).attr('value')) {
            var date = $.datepicker.parseDate("yy-mm-dd", $(this).attr('value'));
            $(this).attr('value', $.datepicker.formatDate("dd/mm/yy", date));
        }
    });
    
    $('#jam_pemberangkatan').timepicker({
        timeFormat: "%H:%i"
    });
    function karyawanChange(){
        var employee = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
        var employee_string=employee.toString();
        if(employee_string!=''){
            document.getElementById("warning_employee").innerHTML='';
        }
    }
    function change_city(prov){
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_cities')}}",
            data: {
                provinsi:prov,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#cities').empty();
                jQuery.each(res, function(key,value){
                    $('#cities').append('<option value="'+ value['city_id'] +'">'+ value['city_name'] +'</option>');
                });
                $('#districts').empty();
                $('#sub_districts').empty();
                if(res.length>0){
                    document.getElementById("warning_provinsi").innerHTML='';
                }
            },
            error: function(res){
                swal({
                    title: "Ambil data kota",
                    text: "Data kota gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function change_district(city){
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_districts')}}",
            data: {
                cities:city,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#districts').empty();
                jQuery.each(res, function(key,value){
                    $('#districts').append('<option value="'+ value['dis_id'] +'">'+ value['dis_name'] +'</option>');
                });
                if(res.length>0){
                    document.getElementById("warning_kota").innerHTML='';
                }
                $('#sub_districts').empty();
            },
            error: function(res){
                swal({
                    title: "Ambil data desa",
                    text: "Data desa gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function change_subdistrict(district){
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_subdistricts')}}",
            data: {
                districts:district,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#sub_districts').empty();
                jQuery.each(res, function(key,value){
                    $('#sub_districts').append('<option value="'+ value['subdis_id'] +'">'+ value['subdis_name'] +'</option>');
                });
                if(res.length>0){
                    document.getElementById("warning_kecamatan").innerHTML='';
                }
            },
            error: function(res){
                swal({
                    title: "Ambil data desa",
                    text: "Data desa gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function subdistrict_change(value){
        if(value!=''){
            document.getElementById("warning_desa").innerHTML='';
        }
    }
    function detail_alamat_change(value){
        if(value!=''){
            document.getElementById("detail_alamat").style.border='';
        }
    }
    function get_tujuan_detail(){
        var id=$('#id_pengajuan').val();
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_tujuan_detail')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data:{
                id:id,
            },
            success: function(res){
                jQuery.each(res, function(key,val){
                    array_provinsi.push(val.prov_name);
                    array_kota.push(val.city_name);
                    array_kecamatan.push(val.dis_name);
                    array_desa.push(val.subdis_name);
                    array_instansi.push(val.instansi);
                    if(val.detail_alamat==null){
                        array_detail_alamat.push('');
                    }else{
                        array_detail_alamat.push(val.detail_alamat);
                    }
                    array_tanggal_pemberangkatan.push(val.tanggal_kedatangan);
                    array_jam_pemberangkatan.push(val.jam_kedatangan.substring(0, 5));
                    array_tujuan_pemberangkatan.push(val.tujuan_pemberangkatan);
                    if(val.jarak_tempuh==null){
                        array_jarak_tempuh.push('');
                    }else{
                        array_jarak_tempuh.push(val.jarak_tempuh);
                    }
                    if(val.jenis_barang==null){
                        array_jenis_barang.push('');
                    }else{
                        array_jenis_barang.push(val.jenis_barang);
                    }
                    if(val.quantity==null){
                        array_quantity.push('');
                    }else{
                        array_quantity.push(val.quantity);
                    }
                    if(val.satuan==null){
                        array_satuan.push('');
                    }else{
                        array_satuan.push(val.satuan);
                    }
                    if(val.nama_penerima==null){
                        array_nama_penerima.push('');
                    }else{
                        array_nama_penerima.push(val.nama_penerima);
                    }
                    if(val.keterangan_barang==null){
                        array_keterangan_barang.push('');
                    }else{
                        array_keterangan_barang.push(val.keterangan_barang);
                    }
                    if(val.nama_tamu==null){
                        array_nama_tamu.push('');
                    }else{
                        array_nama_tamu.push(val.nama_tamu);
                    }
                    if(val.nomor_hp_tamu==null){
                        array_nomor_hp_tamu.push('');
                    }else{
                        array_nomor_hp_tamu.push(val.nomor_hp_tamu);
                    }
                    if(val.karyawan_dinas_luar==null){
                        array_karyawan_dinas_luar.push('');
                    }else{
                        array_karyawan_dinas_luar.push(val.karyawan_dinas_luar);
                    }
                    array_driver.push(val.driver);
                    array_driver_name.push(val.driver_name);
                    array_status.push(val.status);
                    array_alternative.push(val.alternative);
                    array_vehicle.push(val.vehicle);
                    array_vehicle_name.push(val.vehicle_name);
                });
                show_destination_list();
            },
            error: function(res){
                swal({
                    title: "Ambil data",
                    text: "Data gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function add_array_item(){
        array_provinsi.push('');
        array_kota.push('');
        array_kecamatan.push('');
        array_desa.push('');
        array_instansi.push('');
        array_detail_alamat.push('');
        array_tanggal_pemberangkatan.push('');
        array_jam_pemberangkatan.push('');
        array_tujuan_pemberangkatan.push('');
        array_jarak_tempuh.push(0);
        array_jenis_barang.push('');
        array_quantity.push('');
        array_satuan.push('');
        array_nama_penerima.push('');
        array_keterangan_barang.push('');
        array_nama_tamu.push('');
        array_nomor_hp_tamu.push('');
        array_karyawan_dinas_luar.push('');
        show_destination_list();
    }
    function delete_item(key){
        array_provinsi.splice(key,1);
        array_kota.splice(key,1);
        array_kecamatan.splice(key,1);
        array_desa.splice(key,1);
        array_instansi.splice(key,1);
        array_detail_alamat.splice(key,1);
        array_tanggal_pemberangkatan.splice(key,1);
        array_jam_pemberangkatan.splice(key,1);
        array_tujuan_pemberangkatan.splice(key,1);
        array_jarak_tempuh.splice(key,1);
        array_jenis_barang.splice(key,1);
        array_quantity.splice(key,1);
        array_satuan.splice(key,1);
        array_nama_penerima.splice(key,1);
        array_keterangan_barang.splice(key,1);
        array_nama_tamu.splice(key,1);
        array_nomor_hp_tamu.splice(key,1);
        array_karyawan_dinas_luar.splice(key,1);
        show_destination_list();
    }
    function show_destination_list(){
        var id_user=$('#user').val();
        $('#tujuan_advanced').empty();
        jQuery.each(array_provinsi, function(key,val){
            var tanggal_pemberangkat=new Date(array_tanggal_pemberangkatan[key]);
            var formattedDate = tanggal_pemberangkat.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            $('#tujuan_advanced').append(`<div class="row pt-3 border-top border-dark">
                <div class="col-1 pt-2">
                    <label class="form-label" style="font-weight: bold;font-size:12pt">Tujuan ke `+(key+1)+`</label>
                </div>
                </div>
                <div class="row pb-2">
                <div class="col-12 pr-7">
                    <div class="row">
                        <table class="w-100">
                            <tr>
                                <td style="padding-left:13px" width="10%"><label class="form-label pt-1 pl-3 border-left border-dark">Driver</label></td>
                                <td width="20%" style="padding-left:13px">
                                ${
                                    id_user==4241 || id_user==20 || id_user==5321 || id_user==6083
                                    ? `
                                            <select id="driver_yang_ke_`+key+`" class="form-control" onchange="driverChange(`+key+`,this.value)">
                                                <option value="">Pilih Driver</option>
                                                @foreach($drivers as $key=>$value)
                                                    <option value="{{$value->enroll_id}}">{{$value->employee_name}}</option>
                                                @endforeach
                                            </select>` 
                                    : (array_driver_name[key]===null?'':array_driver_name[key])
                                }
                                </td>
                                <td style="padding-left:13px" width="10%"><label class="form-label pt-1 pl-3 border-left border-dark">Kendaraan</label></td>
                                <td width="20%" style="padding-left:13px">
                                    ${
                                      id_user==4241 || id_user==20 || id_user==5321 || id_user==6083
                                        ? `
                                            <select id="kendaraan_yang_ke_`+key+`" class="form-control" onchange="vehicleChange(`+key+`,this.value)">
                                                <option value="">Pilih Kendaraan</option>
                                                @foreach($vehicles as $key=>$value)
                                                    <option value="{{$value->id}}">{{$value->plat_no}} || {{$value->merk}} {{$value->tipe}}</option>
                                                @endforeach
                                            </select>` 
                                        : (array_vehicle_name[key]===null?'':array_vehicle_name[key])
                                    }
                                    
                                </td>
                                <td style="padding-left:13px" width="5%"><label class="form-label pt-1 pl-3 border-left border-dark">Status</label></td>
                                <td width="15%" style="padding-left:13px">
                                    ${
                                        id_user==4241 || id_user==20 || id_user==5321 || id_user==6083
                                        ? `
                                            <select id="status_yang_ke_`+key+`" class="form-control" onchange="statusChange(`+key+`,this.value)">
                                                <option value=0>Pilih Status</option>
                                                <option value=1>Approved</option>
                                                <option value=2>Alternative</option>
                                                <option value=3>On The Way</option>
                                                <option value=4>Done</option>
                                                <option value=5>Late</option>
                                                <option value=6>Cancel</option>
                                            </select>` 
                                        : (array_status[key]===null?'':array_status[key])
                                    }
                                </td>
                                <td width="20%" style="padding-left:13px">
                                  ${
                                        id_user==4241 || id_user==20 || id_user==5321 || id_user==6083
                                        ? `<div id="tag_alternative_yang_ke_`+key+`" style="display:none"><input type="text" class="form-control" placeholder="Masukkan Alternative" id="alternative_yang_ke_`+key+`" onchange="alternativeChange(`+key+`,this.value)"></div>` 
                                        : (array_alternative[key]===null?'':array_alternative[key])
                                  }
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row p-0 mb-6">
                <div class="col-12 px-0">
                    <table class="w-100">
                        <tr>
                        <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Provinsi</th>
                        <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Kabupaten/Kota</th>
                        <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Kecamatan</th>
                        <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Desa</th>
                        <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Instansi & Detail Alamat</th>
                        <th width="18%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Waktu Kedatangan</th>
                        <th width="1%" style="padding:9px"></th
                        </tr>
                        <tr>
                            <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">`+array_provinsi[key]+`
                            </td>
                            <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">`+array_kota[key]+`
                            </td>
                            <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">`+array_kecamatan[key]+`
                            </td>
                            <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">`+array_desa[key]+`
                            </td>
                            <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">`+array_instansi[key]+`<br><h6 style='font-size:8pt'>`+array_detail_alamat[key]+`</h6>
                            </td>
                            <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">`+formattedDate+` - `+array_jam_pemberangkatan[key]+`
                            </td>
                            <td style="vertical-align:top">
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-primary" colspan="2" style="padding:9px;border:1px solid #c4c0c0">Tujuan</th>
                            <th class="bg-primary" colspan="4" style="padding:9px;border:1px solid #c4c0c0">Keterangan</th>
                            <th style="padding:9px"></th>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">`+array_tujuan_pemberangkatan[key]+`
                            </td>
                            <td rowspan="3" style="padding:9px;vertical-align:top;border:1px solid #c4c0c0" colspan="4">
                                <div id="tag_keterangan_barang_ke_`+key+`" style="display:none">
                                    <div class="row pt-1">
                                        <div class="col-2">
                                            <label class="form-label border-bottom border-dark">Keterangan Barang</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-4">
                                                    <label class="form-label pt-1">Jenis Barang</label>
                                                </div>
                                                <div class="col-8">`+array_jenis_barang[key]+`
                                                </div>
                                            </div>
                                            <div class="row pt-1">
                                                <div class="col-4">
                                                    <label class="form-label pt-1">Quantity</label>
                                                </div>
                                                <div class="col-8">
                                                    <table class="w-100">
                                                        <tr>
                                                            <td width="50%">`+array_quantity[key]+` `+array_satuan[key]+`
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row pt-1">
                                                <div class="col-4">
                                                    <label class="form-label pt-1">Nama Penerima</label>
                                                </div>
                                                <div class="col-8">`+array_nama_penerima[key]+`
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-4">
                                                    <label class="form-label pt-1 pl-4">Keterangan</label>
                                                </div>
                                                <div class="col-8">`+array_keterangan_barang[key]+`
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tag_keterangan_tamu_ke_`+key+`" style="display:none">
                                    <div class="row pt-1">
                                        <div class="col-2">
                                            <label class="form-label border-bottom border-dark">Keterangan Tamu</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-4">
                                                <label class="form-label pt-1">Nama Tamu</label>
                                                </div>
                                                <div class="col-8">`+array_nama_tamu[key]+`
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-4">
                                                <label class="form-label pt-1 pl-4">Nomor HP</label>
                                                </div>
                                                <div class="col-8">`+array_nomor_hp_tamu[key]+`
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tag_keterangan_dinas_ke_`+key+`" style="display:none">
                                    <div class="row pt-1">
                                        <div class="col-2">
                                            <label class="form-label border-bottom border-dark">Keterangan Dinas</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <label class="form-label pt-2">Karyawan yg dinas luar</label>
                                        </div>
                                        <div class="col-9" id="karyawan_dinas_luar_ke_`+key+`">`+getKaryawanDinasLuar(array_karyawan_dinas_luar[key],key)+`
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-primary" colspan="2" style="padding:9px;border:1px solid #c4c0c0">Jarak Tempuh</th>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">`+array_jarak_tempuh[key]+` KM
                            </td>
                        </tr>
                    </table>
                </div>
            </div>`);
            $('#driver_yang_ke_'+key).val(array_driver[key]);
            $('#kendaraan_yang_ke_'+key).val(array_vehicle[key]);
            $('#status_yang_ke_'+key).val(array_status[key]);
            $('#alternative_yang_ke_'+key).val(array_alternative[key]);
            if (array_tujuan_pemberangkatan[key].includes("antar_tamu")||array_tujuan_pemberangkatan[key].includes("jemput_tamu")) {
              document.getElementById('tag_keterangan_tamu_ke_'+key).style.display='block';
            }
            if (array_tujuan_pemberangkatan[key].includes("antar_barang")||array_tujuan_pemberangkatan[key].includes("jemput_barang")) {
              document.getElementById('tag_keterangan_barang_ke_'+key).style.display='block';
            }
            if (array_tujuan_pemberangkatan[key].includes("antar_dinas")||array_tujuan_pemberangkatan[key].includes("jemput_dinas")) {
              document.getElementById('tag_keterangan_dinas_ke_'+key).style.display='block';
            }else{
              $('#checkbox_5_'+key).prop('checked', false);
            }
            if(array_status[key]==2){
              document.getElementById('tag_alternative_yang_ke_'+key).style.display='block';
            }
        });
    }
    function getKaryawanDinasLuar(value,key){
        if(value!=null){
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.get_employee_dinas')}}",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data:{
                    id:value,
                },
                success: function(res){
                    $('#karyawan_dinas_luar_ke_'+key).text(res);
                }
            });
        }else{
            $('#karyawan_dinas_luar_ke_'+key).text('');
        }
    }
    function driverChange(key,value){
        array_driver[key]=value;
        if(array_status[key]==2||array_status[key]==6){
          $('#status_yang_ke_'+key).val(0);
          $('#alternative_yang_ke_'+key).val('');
          array_status[key]=0;
          array_alternative[key]='';
          document.getElementById('alternative_yang_ke_'+key).style.border='';
          document.getElementById('tag_alternative_yang_ke_'+key).style.display='none';
        }
        if(value!=''){
            document.getElementById('driver_yang_ke_'+key).style.border='';
        }
    }
    function statusChange(key,value){
        array_status[key]=value;
        if(value==6){
            array_driver[key]='';
            array_vehicle[key]='';
            document.getElementById('driver_yang_ke_'+key).style.border='';
            document.getElementById('kendaraan_yang_ke_'+key).style.border='';
            $('#driver_yang_ke_'+key).val('');
            $('#kendaraan_yang_ke_'+key).val('');
            document.getElementById('tag_alternative_yang_ke_'+key).style.display='none';
            document.getElementById('alternative_yang_ke_'+key).style.border='';
            $('#alternative_yang_ke_'+key).val('');
        }else{
            if(value==2){
                array_driver[key]='';
                array_vehicle[key]='';
                document.getElementById('driver_yang_ke_'+key).style.border='';
                document.getElementById('kendaraan_yang_ke_'+key).style.border='';
                $('#driver_yang_ke_'+key).val('');
                $('#kendaraan_yang_ke_'+key).val('');
                document.getElementById('tag_alternative_yang_ke_'+key).style.display='block';
            }else{
                document.getElementById('tag_alternative_yang_ke_'+key).style.display='none';
            }
        }
    }
    function alternativeChange(key,value){
        array_alternative[key]=value;
        if(value!=''){
            document.getElementById('alternative_yang_ke_'+key).style.border='';
        }
    }
    function vehicleChange(key,value){
        array_vehicle[key]=value;
        if(value!=''){
            document.getElementById('kendaraan_yang_ke_'+key).style.border='';
        }
        if(array_status[key]==2||array_status[key]==6){
          $('#status_yang_ke_'+key).val(0);
          $('#alternative_yang_ke_'+key).val('');
          array_status[key]=0;
          array_alternative[key]='';
          document.getElementById('alternative_yang_ke_'+key).style.border='';
          document.getElementById('tag_alternative_yang_ke_'+key).style.display='none';
        }
    }
    function setValueToDropdown(key,val){
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_province')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#provinsi_yang_ke_'+key).empty();
                jQuery.each(res, function(k,v){
                    $('#provinsi_yang_ke_'+key).append('<option value="'+ v['prov_id'] +'">'+ v['prov_name'] +'</option>');
                });
                $('#provinsi_yang_ke_'+key+' option[value="' + val + '"]').prop('selected',true);
            },
            error: function(res){
                swal({
                    title: "Ambil data provinsi",
                    text: "Data provinsi gagal di ambil",
                    icon: "danger",
                });
            }
        });
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_cities')}}",
            data: {
                provinsi:val,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#kota_yang_ke_'+key).empty();
                jQuery.each(res, function(k,v){
                    $('#kota_yang_ke_'+key).append('<option value="'+ v['city_id'] +'">'+ v['city_name'] +'</option>');
                });
                $('#kota_yang_ke_'+key+' option[value="' + array_kota[key] + '"]').prop('selected',true);
            },
            error: function(res){
                swal({
                    title: "Ambil data kota",
                    text: "Data kota gagal di ambil",
                    icon: "danger",
                });
            }
        });
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_districts')}}",
            data: {
                cities:array_kota[key],
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#kecamatan_yang_ke_'+key).empty();
                jQuery.each(res, function(k,v){
                    $('#kecamatan_yang_ke_'+key).append('<option value="'+ v['dis_id'] +'">'+ v['dis_name'] +'</option>');
                });
                $('#kecamatan_yang_ke_'+key+' option[value="' + array_kecamatan[key] + '"]').prop('selected',true);
            },
            error: function(res){
                swal({
                    title: "Ambil data desa",
                    text: "Data desa gagal di ambil",
                    icon: "danger",
                });
            }
        });
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_subdistricts')}}",
            data: {
                districts:array_kecamatan[key],
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#desa_yang_ke_'+key).empty();
                jQuery.each(res, function(k,v){
                    $('#desa_yang_ke_'+key).append('<option value="'+ v['subdis_id'] +'">'+ v['subdis_name'] +'</option>');
                });
                $('#desa_yang_ke_'+key+' option[value="' + array_desa[key] + '"]').prop('selected',true);
            },
            error: function(res){
                swal({
                    title: "Ambil data desa",
                    text: "Data desa gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function changeCity(key,value){
        array_provinsi[key]=value;
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_cities')}}",
            data: {
                provinsi:value,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#kota_yang_ke_'+key).val(null).trigger('change');
                $('#kota_yang_ke_'+key).empty();
                jQuery.each(res, function(k,val){
                    $('#kota_yang_ke_'+key).append('<option value="'+ val['city_id'] +'">'+ val['city_name'] +'</option>');
                });
                if(res.length>0){
                    document.getElementById('warning_provinsi_tujuan_'+key).innerHTML='';
                }
                $('#kecamatan_yang_ke_'+key).empty();
                $('#desa_yang_ke_'+key).empty();
            },
            error: function(res){
                swal({
                    title: "Ambil data kota",
                    text: "Data kota gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function changeDistrict(key,value){
        array_kota[key]=value;
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_districts')}}",
            data: {
                cities:value,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#kecamatan_yang_ke_'+key).val(null).trigger('change');
                $('#kecamatan_yang_ke_'+key).empty();
                jQuery.each(res, function(k,val){
                    $('#kecamatan_yang_ke_'+key).append('<option value="'+ val['dis_id'] +'">'+ val['dis_name'] +'</option>');
                });
                $('#desa_yang_ke_'+key).empty();
                if(res.length>0){
                    document.getElementById('warning_kota_tujuan_'+key).innerHTML='';
                }
            },
            error: function(res){
                swal({
                    title: "Ambil data kota",
                    text: "Data kota gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function changeSubDistrict(key,value){
        array_kecamatan[key]=value;
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_subdistricts')}}",
            data: {
                districts:value,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#desa_yang_ke_'+key).val(null).trigger('change');
                $('#desa_yang_ke_'+key).empty();
                jQuery.each(res, function(k,val){
                    $('#desa_yang_ke_'+key).append('<option value="'+ val['subdis_id'] +'">'+ val['subdis_name'] +'</option>');
                });
                if(res.length>0){
                    document.getElementById('warning_kecamatan_tujuan_'+key).innerHTML='';
                }
            },
            error: function(res){
                swal({
                    title: "Ambil data kota",
                    text: "Data kota gagal di ambil",
                    icon: "danger",
                });
            }
        });
    }
    function subdistrictChange(key,value){
        array_desa[key]=value;
        if(value!=''){
            document.getElementById('warning_desa_tujuan_'+key).innerHTML='';
        }
    }
    function instansiChange(key,value){
        array_instansi[key]=value;
        if(value!=''){
            document.getElementById('instansi_yang_ke_'+key).style.border="";
        }
    }
    function detailAlamatChange(key,value){
        array_detail_alamat[key]=value;
        if(value!=''){
            document.getElementById('detail_alamat_yang_ke_'+key).style.border="";
        }
    }
    function tanggalPemberangkatanChange(key,value){
        array_tanggal_pemberangkatan[key]=value;
        if(value!=''){
            document.getElementById('tanggal_pemberangkatan_yang_ke_'+key+'-display').style.border="";
        }
    }
    function jamPemberangkatanChange(key,value){
        array_jam_pemberangkatan[key]=value;
        if(value!=''){
            document.getElementById('jam_pemberangkatan_yang_ke_'+key).style.border="";
        }
    }
    function jarakTempuhChange(key,value){
      array_jarak_tempuh[key]=value;
    }
    function checkbox_checked(key){
        var tujuan_pemberangkatan = [];
        $("input:checkbox[name=tujuan_pemberangkatan_"+key+"]:checked").each(function() {
            tujuan_pemberangkatan.push($(this).val());
        });
        var tujuan_pemberangkatan_string=tujuan_pemberangkatan.toString();
        array_tujuan_pemberangkatan[key]=tujuan_pemberangkatan_string;
        if(tujuan_pemberangkatan_string!=''){
            document.getElementById("warning_tujuan_pemberangkatan_"+key).innerHTML='';
        }
        if(tujuan_pemberangkatan_string.includes("antar_barang")||tujuan_pemberangkatan_string.includes("jemput_barang")){
            document.getElementById('tag_keterangan_barang_ke_'+key).style.display='block';
        }else{
            document.getElementById('tag_keterangan_barang_ke_'+key).style.display='none';
            $('#jenis_barang_yang_ke_'+key).val('').trigger('change');
            document.getElementById('jenis_barang_yang_ke_'+key).style.border="";
            $('#quantity_yang_ke_'+key).val('').trigger('change');
            document.getElementById('quantity_yang_ke_'+key).style.border="";
            $('#satuan_yang_ke_'+key).val('').trigger('change');
            document.getElementById('satuan_yang_ke_'+key).style.border="";
            $('#nama_penerima_yang_ke_'+key).val('').trigger('change');
            document.getElementById('nama_penerima_yang_ke_'+key).style.border="";
            $('#keterangan_barang_yang_ke_'+key).val('').trigger('change');
        }
        if(tujuan_pemberangkatan_string.includes("antar_tamu")||tujuan_pemberangkatan_string.includes("jemput_tamu")){
            document.getElementById('tag_keterangan_tamu_ke_'+key).style.display='block';
        }else{
            document.getElementById('tag_keterangan_tamu_ke_'+key).style.display='none';
            $('#nama_tamu_yang_ke_'+key).val('').trigger('change');
            document.getElementById('nama_tamu_yang_ke_'+key).style.border="";
            $('#nomor_hp_yang_ke_'+key).val('').trigger('change');
            document.getElementById('nomor_hp_yang_ke_'+key).style.border="";
        }
        if(tujuan_pemberangkatan_string.includes("antar_dinas")||tujuan_pemberangkatan_string.includes("jemput_dinas")){
            document.getElementById('tag_keterangan_dinas_ke_'+key).style.display='block';
        }else{
            document.getElementById('tag_keterangan_dinas_ke_'+key).style.display='none';
            $('#karyawan_dinas_yang_ke_'+key).val(null).trigger('change');
        }
    }
    function change_jenis_barang(key,value){
      array_jenis_barang[key]=value;
      if(value!=''){
        document.getElementById("jenis_barang_yang_ke_"+key).style.border = "";
      }
    }
    function change_quantity(key,value){
      array_quantity[key]=value;
      if(value!=''){
        document.getElementById("quantity_yang_ke_"+key).style.border = "";
      }
    }
    function change_satuan(key,value){
      array_satuan[key]=value;
      if(value!=''){
        document.getElementById("satuan_yang_ke_"+key).style.border = "";
      }
    }
    function change_nama_penerima(key,value){
      array_nama_penerima[key]=value;
      if(value!=''){
        document.getElementById("nama_penerima_yang_ke_"+key).style.border = "";
      }
    }
    function change_keterangan_barang(key,value){
      array_keterangan_barang[key]=value;
    }
    function change_nama_tamu(key,value){
      array_nama_tamu[key]=value;
      if(value!=''){
        document.getElementById("nama_tamu_yang_ke_"+key).style.border = "";
      }
    }
    function change_nomor_hp_tamu(key,value){
      array_nomor_hp_tamu[key]=value;
      if(value!=''){
        document.getElementById("nomor_hp_yang_ke_"+key).style.border = "";
      }
    }
    function changeKaryawanDinas(key){
      var employee_dinas = $("select[name='selectEmployeeDinas"+key+"[]']").map(function(){return $(this).val();}).get();
      var employee_dinas_string=employee_dinas.toString();
      array_karyawan_dinas_luar[key]=employee_dinas_string;
      if(employee_dinas_string!=''){
        document.getElementById("warning_karyawan_dinas_ke_"+key).innerHTML='';
      }
    }
    function changeStatus(){
        var status_permintaan=$('#status').val();

        if(status_permintaan==2){
            document.getElementById('status').style.border='';
            document.getElementById('tag_alternative').style.display='block';
            jQuery.each(array_driver, function(key,val){
                array_driver[key]='';
                array_vehicle[key]='';
                array_status[key]=0;
                array_alternative[key]='';
                $('#driver_yang_ke_'+key).val('');
                $('#kendaraan_yang_ke_'+key).val('');
                $('#status_yang_ke_'+key).val(0);
                $('#alternative_yang_ke_'+key).val('');
                document.getElementById('driver_yang_ke_'+key).style.border='';
                document.getElementById('kendaraan_yang_ke_'+key).style.border='';
                document.getElementById('alternative_yang_ke_'+key).style.border='';
            });
        }else{
            $('#alternative').val('');
            document.getElementById('tag_alternative').style.display='none';
            document.getElementById('alternative').style.border='';
            document.getElementById('status').style.border='';
            if(status_permintaan==0){
                jQuery.each(array_driver, function(key,val){
                  array_driver[key]='';
                  array_vehicle[key]='';
                  array_status[key]=0;
                  array_alternative[key]='';
                  $('#driver_yang_ke_'+key).val('');
                  $('#kendaraan_yang_ke_'+key).val('');
                  $('#status_yang_ke_'+key).val(0);
                  $('#alternative_yang_ke_'+key).val('');
                  document.getElementById('driver_yang_ke_'+key).style.border='';
                  document.getElementById('kendaraan_yang_ke_'+key).style.border='';
                  document.getElementById('alternative_yang_ke_'+key).style.border='';
                });
            }else if(status_permintaan==6){
                jQuery.each(array_driver, function(key,val){
                  array_driver[key]='';
                  array_vehicle[key]='';
                  array_status[key]=0;
                  array_alternative[key]='';
                  $('#driver_yang_ke_'+key).val('');
                  $('#kendaraan_yang_ke_'+key).val('');
                  $('#status_yang_ke_'+key).val(0);
                  $('#alternative_yang_ke_'+key).val('');
                  document.getElementById('driver_yang_ke_'+key).style.border='';
                  document.getElementById('kendaraan_yang_ke_'+key).style.border='';
                  document.getElementById('alternative_yang_ke_'+key).style.border='';
                });
            }else{
                document.getElementById('tag_alternative').style.display='none';
            }
        }
    }
    function alternative_change(){
        var alternative_value=$('#alternative').val();
        if(alternative_value!=''){
            document.getElementById('alternative').style.border='';
        }
    }
    function saveChanges(){
        array_keterangan=[];
        var status_value=$('#status').val();
        if(status_value==2){
            document.getElementById('status').style.border="";
            if($('#alternative').val()==''){
                document.getElementById('alternative').style.border="1px solid red";
                array_keterangan.push('');
            }else{
                document.getElementById('alternative').style.border="";
                array_keterangan.push($('#alternative').val());
            }
            jQuery.each(array_driver, function(key,value){
                array_driver[key]='';
                array_vehicle[key]='';
                array_status[key]=0;
                array_alternative[key]='';
                $('#driver_yang_ke_'+key).val('');
                $('#kendaraan_yang_ke_'+key).val('');
                $('#status_yang_ke_'+key).val(0);
                $('#alternative_yang_ke_'+key).val('');
                document.getElementById('tag_alternative_yang_ke_'+key).style.display="none";
            });
        }else if(status_value==0){
            if($('#status').val()==0){
                document.getElementById('status').style.border="1px solid red";
                array_keterangan.push('');
            }else{
                document.getElementById('status').style.border="";
                array_keterangan.push($('#status').val());
            }
            jQuery.each(array_driver, function(key,value){
                array_driver[key]='';
                array_vehicle[key]='';
                array_status[key]=0;
                array_alternative[key]='';
                $('#driver_yang_ke_'+key).val('');
                $('#kendaraan_yang_ke_'+key).val('');
                $('#status_yang_ke_'+key).val(0);
                $('#alternative_yang_ke_'+key).val('');
                document.getElementById('tag_alternative_yang_ke_'+key).style.display="none";
            });
        }else{
            if(status_value!=6){
                document.getElementById('status').style.border="";
                jQuery.each(array_driver, function(key,value){
                    if(array_status[key]==2){
                        if(array_alternative[key]==null || array_alternative[key]==''){
                            document.getElementById("alternative_yang_ke_"+key).style.border='1px solid red';
                            array_keterangan.push('');
                        }else{
                            document.getElementById("alternative_yang_ke_"+key).style.border='';
                            array_keterangan.push(array_alternative[key]);
                        }
                        array_driver[key]='';
                        array_vehicle[key]='';
                        $('#driver_yang_ke_2_'+key).val('');
                        $('#kendaraan_yang_ke_2_'+key).val('');
                    }else{
                        if(array_status[key]==6){
                            array_driver[key]='';
                            array_vehicle[key]='';
                            array_alternative[key]='';
                            $('#driver_yang_ke_'+key).val('');
                            $('#kendaraan_yang_ke_'+key).val('');
                            $('#alternative_yang_ke_'+key).val('');
                            array_keterangan.push('cancel');
                        }else{
                            if(value==null || value==''){
                                document.getElementById("driver_yang_ke_"+key).style.border='1px solid red';
                                array_keterangan.push('');
                            }else{
                                document.getElementById("driver_yang_ke_"+key).style.border='';
                                array_keterangan.push(value);
                            }
                            if(array_vehicle[key]==null||array_vehicle[key]==''){
                                document.getElementById("kendaraan_yang_ke_"+key).style.border='1px solid red';
                                array_keterangan.push('');
                            }else{
                                document.getElementById("kendaraan_yang_ke_"+key).style.border='';
                                array_keterangan.push(array_vehicle[key]);
                            }
                        }
                    }
                });
            }else{
                jQuery.each(array_driver, function(key,value){
                    array_driver[key]='';
                    array_vehicle[key]='';
                    array_status[key]=0;
                    array_alternative[key]='';
                    $('#driver_yang_ke_'+key).val('');
                    $('#kendaraan_yang_ke_'+key).val('');
                    $('#status_yang_ke_'+key).val(0);
                    $('#alternative_yang_ke_'+key).val('');
                    document.getElementById('tag_alternative_yang_ke_'+key).style.display="none";
                });
                array_keterangan.push('cancel');
            }
        }
        if(!array_keterangan.includes('')){
            $.ajax({
                type:"POST",
                url: "{{route('hris.ga.update_car_request_administrator')}}",
                data: {
                    id:$('#id_pengajuan').val(),
                    array_driver:array_driver,
                    array_vehicle:array_vehicle,
                    array_status:array_status,
                    array_alternative:array_alternative,
                    status:$('#status').val(),
                    alternative:$('#alternative').val()
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    if(res=='hapus'){
                        swal("", "Status permintaan transportasi telah di hapus oleh user", "error");
                    }else{
                        swal("", "Permintaan transportasi berhasil di update", "success");
                        var url = 'data_pengajuan_transportasi';
                        window.open(url, '_self');
                    }
                },
                error: function(error){
                    swal("", "Permintaan transportasi gagal di update", "error");
                }
            });
        }
    }
</script>

@endsection