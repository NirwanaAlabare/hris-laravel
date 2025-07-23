@extends('admin.adminlayouts.adminlayout-mut-karyawan')

@section('head')
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<!---Sweetalert Css-->
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/js/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/izitoast/dist/css/iziToast.min.css') }}" rel="stylesheet">

@stop
@section('mainarea')
<div class="">
    <div class="card-header mt-7 pt-1 pb-0">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="btn btn-primary" style="background-color:blue" href="{{route('hris.ga.form_pengajuan_transportasi')}}">Formulir</a>
            </li>
            <li class="nav-item">
                @if ($id_user==4241 || $id_user==20 || $id_user==7765 || $id_user==6083)
                <a class="btn btn-white" style="background-color:rgb(228, 228, 228); position: relative; padding-right: 40px; padding-left: 40px" href="{{route('hris.ga.data_pengajuan_transportasi_admin')}}">Data <span class="text-dark h-5 w-5" style="font-weight:bold; background-color:#d2eafc; border:1px solid #0091ff;font-size:10px; display: flex; justify-content: center; align-items: center;position: absolute; top: 7px; right: 10px; border-radius: 100%;">{{$pengajuan_transportasi}}</span></a>
                @else
                    <a class="btn btn-white" href="{{route('hris.ga.data_pengajuan_transportasi_admin')}}">Data</a>
                @endif
            </li>
        </ul>
    </div>

    <div class="card-body pl-6 py-4" style="border: 1px solid #d8d4dc">
        <div class="row">
            <div class="col-2 pt-1">
                <label class="form-label"> Nama Karyawan</label>
            </div>
            <div class="col-4">
                <input type="hidden" value="{{$id_user}}" id="user">
                <select id="selectEmployeeID" name="selectEmployeeID[]" multiple data-placeholder="Pilih karyawan" class="form-control select2oneSelect EmployeeID col-11" onchange="karyawanChange(this.value)">
                    @foreach ($selectemployee as $r_empl)
                        <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                    @endforeach
                </select>
                <h6 id="warning_employee" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>
            </div>
        </div>
        <div class="row pb-2 pt-1">
            <div class="col-2 pt-1">
                <label class="form-label"> Keberangkatan Awal</label>
            </div>
            <div class="col-4 pt-2">
                <a href="#" onclick="change_initial_destination(12,161,2196,30109)" style="text-decoration-line: underline">PT. NAG</a><img src="{{URL::asset('assets/images/brand/shortcut.png')}}" width="18">
            </div>
        </div>
        <div class="row px-1 pt-3">
          <div class="col-12">
            <div class="row">
              <div class="col-2 pr-0">
                <label class="form-label">Provinsi</label>
              </div>
              <div class="col-2 px-1">
                <label class="form-label">Kabupaten/Kota</label>
              </div>
              <div class="col-2 px-1">
                <label class="form-label">Kecamatan</label>
              </div>
              <div class="col-2 px-1">
                <label class="form-label">Desa</label>
              </div>
              <div class="col-2 px-1">
                <label class="form-label">Detail Alamat</label>
              </div>
              <div class="col-2 pl-1">
                <label class="form-label">Waktu Pemberangkatan</label>
              </div>
            </div>
            <div class="row">
              <div class="col-2 pr-0">
                <select id="provinsi" name="selectProvinsi[]" multiple data-placeholder="Pilih Provinsi" class="form-control select2oneSelect" onchange="change_city(this.value)">
                    @foreach($provincies as $prov)
                        <option value="{{$prov->prov_id}}">{{$prov->prov_name}}</option>
                    @endforeach
                </select>
                <h6 id="warning_provinsi" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>
              </div>
              <div class="col-2 px-1">
                <select id="cities" name="selectCity[]" multiple data-placeholder="Pilih Kota" class="form-control select2oneSelect" style="background-color: white" onchange="change_district(this.value)">
                </select>
                <h6 id="warning_kota" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>
              </div>
              <div class="col-2 px-1">
                <select id="districts" name="selectDistrict[]" multiple data-placeholder="Pilih Kecamatan" class="form-control select2oneSelect" style="background-color: white"onchange="change_subdistrict(this.value)">
                </select>
                <h6 id="warning_kecamatan" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>
              </div>
              <div class="col-2 px-1">
                <select id="sub_districts" name="selectSubdistrict[]" multiple data-placeholder="Pilih Desa" class="form-control select2oneSelect" style="background-color: white" onchange="subdistrict_change(this.value)">
                </select>
                <h6 id="warning_desa" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>
              </div>
              <div class="col-2 px-1">
                <input id="instansi" class="form-control" style="background-color: white" placeholder="Instansi">
                <input id="detail_alamat" class="form-control mt-1" style="background-color: white" placeholder="Nama Gedung, Jalan atau Blok" onchange="detail_alamat_change(this.value)">
              </div>
              <div class="col-2 pl-1">
                <div class="row">
                    <div class="col-7 pr-0">
                        <input type="date" id="tanggal_pemberangkatan" class="form-control" style="background-color: white;cursor:pointer" readonly onchange="tanggal_pemberangkatan_change(this.value)">
                    </div>
                    <div class="col-5 pl-0">
                        <input class="form-control" id="jam_pemberangkatan" name="jam_pemberangkatan" type="text" style="background-color: white; cursor:pointer;"  onchange="jam_pemberangkatan_change(this.value)">
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="tujuan_advanced">

        </div>
        <div class="row pt-2">
            <div class="col-12 text-center">
                <button class="btn btn-success" onclick="saveChanges()"><i class="fa fa-send"></i> SEND REQUEST</button>
            </div>
        </div>
    </div>
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
      $(picker).css('padding-left', 0);
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
    $(function(){
        'use strict';

        $('.select2').select2({
            minimumResultsForSearch: Infinity,
        });
        $('.select2oneSelect').select2({
            minimumResultsForSearch: Infinity,
            maximumSelectionLength: 1
        });

        // Select2 by showing the search
        $('.select2-show-search').select2({
            minimumResultsForSearch: ''
        });

        // Colored Hover
        $('#select2').select2({
            dropdownCssClass: 'hover-success',// disabling search
        });
    });
    $("#jam_pemberangkatan").timepicker({
      timeFormat: "%H:%i"
    });
</script>
<script>
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
    var array_keterangan=[];
    $(document).ready(function() {
        add_array_item();
        get_province();
    });
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
        get_all_destination_history();
    }
    function get_province(){
      $.ajax({
          type:"POST",
          url: "{{route('hris.ga.get_province')}}",
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          success: function(res){
              $('#provinsi').empty();
              jQuery.each(res, function(k,v){
                  $('#provinsi').append('<option value="'+ v['prov_id'] +'">'+ v['prov_name'] +'</option>');
              });
          },
          error: function(res){
              swal({
                  title: "Ambil data provinsi",
                  text: "Data provinsi gagal di ambil",
                  icon: "danger",
              });
          }
      });
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
        get_all_destination_history();
    }
    function show_destination_list(){
        $('#tujuan_advanced').empty();
        jQuery.each(array_provinsi, function(key,val){
          if(key==array_provinsi.length-1){
            if(key==0){
              $('#tujuan_advanced').append(`<div class="row pb-2 pt-1">\
                      <div class="col-2 pt-2">\
                          <label class="form-label" style="font-weight: bold;font-size:12pt">Tujuan Pertama</label>\
                      </div>\
                  </div>\
                  <div class="row p-0">\
                    <div class="col-12 px-0">\
                      <table class="w-100">\
                        <tr>\
                          <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Provinsi</th>\
                          <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Kabupaten/Kota</th>\
                          <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Kecamatan</th>\
                          <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Desa</th>\
                          <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Detail Alamat</th>\
                          <th width="18%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Waktu Kedatangan</th>\
                          <th width="1%" style="padding:9px"></th>\
                        </tr>\
                        <tr>\
                          <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                              <select id="provinsi_yang_ke_`+key+`" name="selectProvinsiTujuan[]" multiple data-placeholder="Pilih Provinsi" class="form-control select2oneSelecttwo" onchange="changeCity(`+key+`,this.value)" value="array">\
                              </select>
                              <h6 id="warning_provinsi_tujuan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                              <select id="history_alamat_yang_ke_`+key+`" name="history_alamat" data-placeholder="Pilih Provinsi" class="form-control" onchange="changeAddress(`+key+`,this.value)">
                              </select>
                          </td>\
                          <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                              <select id="kota_yang_ke_`+key+`" name="selectKotaTujuan[]" multiple data-placeholder="Pilih Kota" class="form-control select2oneSelecttwo" onchange="changeDistrict(`+key+`,this.value)">\
                              </select>\
                              <h6 id="warning_kota_tujuan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                          </td>
                          <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                              <select id="kecamatan_yang_ke_`+key+`" name="selectKecamatanTujuan[]" multiple data-placeholder="Pilih Kecamatan" class="form-control select2oneSelecttwo" onchange="changeSubDistrict(`+key+`,this.value)">\
                              </select>\
                              <h6 id="warning_kecamatan_tujuan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                          </td>\
                          <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                              <select id="desa_yang_ke_`+key+`" name="selectDesaTujuan[]" multiple data-placeholder="Pilih Desa" class="form-control select2oneSelecttwo" onchange="subdistrictChange(`+key+`,this.value)">\
                              </select>\
                              <h6 id="warning_desa_tujuan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                          </td>\
                          <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                              <input id="instansi_yang_ke_`+key+`" placeholder="Masukkan Instansi" class="form-control" onchange="instansiChange(`+key+`,this.value)" style="background-color:white" value='`+array_instansi[key]+`'>\
                              <input id="detail_alamat_yang_ke_`+key+`" placeholder="Masukkan Detail Alamat" class="form-control mt-1" onchange="detailAlamatChange(`+key+`,this.value)" style="background-color:white" value='`+array_detail_alamat[key]+`'>\
                          </td>\
                          <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                              <div class="row">\
                                  <div class="col-7 pr-0">\
                                      <input type="date" id="tanggal_pemberangkatan_yang_ke_`+key+`" class="form-control" style="background-color: white;cursor:pointer" onChange="tanggalPemberangkatanChange(`+key+`,this.value)" readonly>\
                                  </div>\
                                  <div class="col-5 pl-0">\
                                      <input class="form-control" id="jam_pemberangkatan_yang_ke_`+key+`" type="text" style="background-color: white; cursor:pointer;" onChange="jamPemberangkatanChange(`+key+`,this.value)"  value="`+array_jam_pemberangkatan[key]+`">\
                                  </div>\
                              </div>\
                          </td>\
                          <td style="vertical-align:top">\
                              <a style="cursor:pointer;background-color:#18445c;color:yellow;padding:5px" onclick="add_array_item()"><i class="fa fa-plus"></i></a>\
                          </td>\
                        </tr>\
                        <tr>
                          <th class="bg-primary" colspan="2" style="padding:9px;border:1px solid #c4c0c0">Tujuan</th>
                          <th class="bg-primary" colspan="4" style="padding:9px;border:1px solid #c4c0c0">Keterangan</th>
                          <th style="padding:9px"></th>
                        </tr>
                        <tr>
                          <td colspan="2" style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">
                            <div class="row">
                              <div class="col-xl-4">
                                <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="antar_tamu" id="checkbox_1_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Antar tamu
                              </div>
                              <div class="col-xl-4">
                                <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="antar_barang" id="checkbox_3_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Antar barang
                              </div>
                              <div class="col-xl-4">
                                <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="antar_dinas" id="checkbox_5_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Antar dinas
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-xl-4">
                                <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="jemput_tamu" id="checkbox_2_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Jemput tamu
                              </div>
                              <div class="col-xl-4">
                                <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="jemput_barang" id="checkbox_4_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Jemput barang
                              </div>
                              <div class="col-xl-4">
                                <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="jemput_dinas" id="checkbox_6_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Jemput dinas
                              </div>
                            </div>
                            <h6 id="warning_tujuan_pemberangkatan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>
                          </td>
                          <td rowspan="3" style="padding-bottom:9px;padding-right:19px;padding-left:19px;vertical-align:top;border:1px solid #c4c0c0" colspan="4">
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
                                    <div class="col-8">
                                      <input type="text" id="jenis_barang_yang_ke_`+key+`" class="form-control" value="`+array_jenis_barang[key]+`" onchange="change_jenis_barang(`+key+`,this.value)">
                                    </div>
                                  </div>
                                  <div class="row pt-1">
                                    <div class="col-4">
                                      <label class="form-label pt-1">Quantity</label>
                                    </div>
                                    <div class="col-8">
                                      <table class="w-100">
                                        <tr>
                                          <td width="50%">
                                            <input type="number" id="quantity_yang_ke_`+key+`" class="form-control" value="`+array_quantity[key]+`" onchange="change_quantity(`+key+`,this.value)">
                                          </td>
                                          <td width="50%">
                                            <input type="text" id="satuan_yang_ke_`+key+`" class="form-control" placeholder="Satuan" value="`+array_satuan[key]+`" onchange="change_satuan(`+key+`,this.value)">
                                          </td>
                                        </tr>
                                      </table>
                                    </div>
                                  </div>
                                  <div class="row pt-1">
                                    <div class="col-4">
                                      <label class="form-label pt-1">Nama Penerima</label>
                                    </div>
                                    <div class="col-8">
                                      <input type="text" id="nama_penerima_yang_ke_`+key+`" class="form-control" value="`+array_nama_penerima[key]+`" onchange="change_nama_penerima(`+key+`,this.value)">
                                    </div>
                                  </div>
                                </div>
                                <div class="col-6">
                                  <div class="row">
                                    <div class="col-4">
                                      <label class="form-label pt-1 pl-4">Keterangan</label>
                                    </div>
                                    <div class="col-8">
                                      <textarea id="keterangan_barang_yang_ke_`+key+`" class="form-control" value="`+array_keterangan_barang[key]+`" onchange="change_keterangan_barang(`+key+`,this.value)">`+array_keterangan_barang[key]+`</textarea>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>\
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
                                    <div class="col-8">
                                      <input type="text" id="nama_tamu_yang_ke_`+key+`" class="form-control" value="`+array_nama_tamu[key]+`" onchange="change_nama_tamu(`+key+`,this.value)">
                                    </div>
                                  </div>
                                </div>
                                <div class="col-6">
                                  <div class="row">
                                    <div class="col-4">
                                      <label class="form-label pt-1 pl-4">Nomor HP</label>
                                    </div>
                                    <div class="col-8">
                                      <input id="nomor_hp_yang_ke_`+key+`" class="form-control" value="`+array_nomor_hp_tamu[key]+`" onchange="change_nomor_hp_tamu(`+key+`,this.value)">
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
                                <div class="col-9">
                                  <select id="karyawan_dinas_yang_ke_`+key+`" name="selectEmployeeDinas`+key+`[]" multiple data-placeholder="Pilih karyawan" class="form-control select3" style="width:80%" onchange="changeKaryawanDinas(`+key+`)">
                                      @foreach ($selectemployee as $r_empl)
                                          <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                      @endforeach
                                  </select>
                                  <h6 id="warning_karyawan_dinas_ke_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                                </div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <th class="bg-primary" colspan="2" style="padding:9px;border:1px solid #c4c0c0">Jarak Tempuh</th>
                        </tr>
                        <tr>
                          <td colspan="2" style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">
                            <div class="row">
                              <div class="col-5 pr-0">
                                <input type="number" id="jarak_tempuh_yang_ke_`+key+`" value="`+array_jarak_tempuh[key]+`" class="form-control" onchange="jarakTempuhChange(`+key+`,this.value)">
                              </div>
                              <div class="col-7 pt-2">
                                <label class="form-label">KM</label>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </table>\
                    </div>\
                  </div>\
              `);
            }else{
              $('#tujuan_advanced').append(`<div class="row pb-2 pt-1">\
                    <div class="col-2 pt-2">\
                        <label class="form-label" style="font-weight: bold;font-size:12pt">Tujuan ke `+(key+1)+`</label>\
                    </div>\
                  </div>\
                  <div class="row p-0">\
                      <div class="col-12 px-0">\
                          <table class="w-100">\
                              <tr>\
                                  <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Provinsi</th>\
                                  <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Kabupaten/Kota</th>\
                                  <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Kecamatan</th>\
                                  <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Desa</th>\
                                  <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Detail Alamat</th>\
                                  <th width="18%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Waktu Kedatangan</th>\
                                  <th width="1%" style="padding:9px"></th>\
                              </tr>\
                              <tr>\
                                  <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                                      <select id="provinsi_yang_ke_`+key+`" name="selectProvinsiTujuan[]" multiple data-placeholder="Pilih Provinsi" class="form-control select2oneSelecttwo" onchange="changeCity(`+key+`,this.value)" value="array">\
                                      </select>\
                                      <h6 id="warning_provinsi_tujuan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                                      <select id="history_alamat_yang_ke_`+key+`" name="history_alamat" data-placeholder="Pilih Provinsi" class="form-control" onchange="changeAddress(`+key+`,this.value)">
                                      </select>
                                  </td>\
                                  <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                                      <select id="kota_yang_ke_`+key+`" name="selectKotaTujuan[]" multiple data-placeholder="Pilih Kota" class="form-control select2oneSelecttwo" onchange="changeDistrict(`+key+`,this.value)">\
                                      </select>\
                                      <h6 id="warning_kota_tujuan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                                  </td>
                                  <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                                      <select id="kecamatan_yang_ke_`+key+`" name="selectKecamatanTujuan[]" multiple data-placeholder="Pilih Kecamatan" class="form-control select2oneSelecttwo" onchange="changeSubDistrict(`+key+`,this.value)">\
                                      </select>\
                                      <h6 id="warning_kecamatan_tujuan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                                  </td>\
                                  <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                                      <select id="desa_yang_ke_`+key+`" name="selectDesaTujuan[]" multiple data-placeholder="Pilih Desa" class="form-control select2oneSelecttwo" onchange="subdistrictChange(`+key+`,this.value)">\
                                      </select>\
                                      <h6 id="warning_desa_tujuan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                                  </td>\
                                  <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                                      <input id="instansi_yang_ke_`+key+`" placeholder="Masukkan Instansi" class="form-control" onchange="instansiChange(`+key+`,this.value)" style="background-color:white" value='`+array_instansi[key]+`'>\
                                      <input id="detail_alamat_yang_ke_`+key+`" placeholder="Masukkan Detail Alamat" class="form-control mt-1" onchange="detailAlamatChange(`+key+`,this.value)" style="background-color:white" value='`+array_detail_alamat[key]+`'>\
                                  </td>\
                                  <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                                      <div class="row">\
                                          <div class="col-7 pr-0">\
                                              <input type="date" id="tanggal_pemberangkatan_yang_ke_`+key+`" class="form-control" style="background-color: white;cursor:pointer" onChange="tanggalPemberangkatanChange(`+key+`,this.value)" readonly>\
                                          </div>\
                                          <div class="col-5 pl-0">\
                                              <input class="form-control" id="jam_pemberangkatan_yang_ke_`+key+`" type="text" style="background-color: white; cursor:pointer;" onChange="jamPemberangkatanChange(`+key+`,this.value)"  value="`+array_jam_pemberangkatan[key]+`">\
                                          </div>\
                                      </div>\
                                  </td>\
                                  <td style="vertical-align:top">\
                                    <div class="row">
                                      <div class="col-12">
                                        <a style="cursor:pointer;background-color:#18445c;color:yellow;padding:5px" onclick="add_array_item()"><i class="fa fa-plus"></i></a>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-12 pt-2">
                                        <a style="color: white;cursor:pointer;background-color:red;padding:5px" onclick="delete_item(`+key+`)"><i class="fa fa-minus"></i></a>
                                      </div>
                                    </div>
                                  </td>\
                              </tr>\
                              <tr>
                                  <th class="bg-primary" colspan="2" style="padding:9px;border:1px solid #c4c0c0">Tujuan</th>
                                  <th class="bg-primary" colspan="4" style="padding:9px;border:1px solid #c4c0c0">Keterangan</th>
                                  <th style="padding:9px"></th>
                              </tr>
                              <tr>
                                  <td colspan="2" style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">
                                      <div class="row">
                                        <div class="col-xl-4">
                                          <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="antar_tamu" id="checkbox_1_`+key+`" onchange="checkbox_checked(`+key+`)" >&nbsp;Antar tamu
                                        </div>
                                        <div class="col-xl-4">
                                          <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="antar_barang" id="checkbox_3_`+key+`" onchange="checkbox_checked(`+key+`)" >&nbsp;Antar barang
                                        </div>
                                        <div class="col-xl-4">
                                          <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="antar_dinas" id="checkbox_5_`+key+`" onchange="checkbox_checked(`+key+`)" >&nbsp;Antar dinas
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-xl-4">
                                          <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="jemput_tamu" id="checkbox_2_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Jemput tamu
                                        </div>
                                        <div class="col-xl-4">
                                          <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="jemput_barang" id="checkbox_4_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Jemput barang
                                        </div>
                                        <div class="col-xl-4">
                                          <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="jemput_dinas" id="checkbox_6_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Jemput dinas
                                        </div>
                                      </div>
                                      <h6 id="warning_tujuan_pemberangkatan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>
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
                                            <div class="col-8">
                                              <input type="text" id="jenis_barang_yang_ke_`+key+`" class="form-control" value="`+array_jenis_barang[key]+`" onchange="change_jenis_barang(`+key+`,this.value)">
                                            </div>
                                          </div>
                                          <div class="row pt-1">
                                            <div class="col-4">
                                              <label class="form-label pt-1">Quantity</label>
                                            </div>
                                            <div class="col-8">
                                              <table class="w-100">
                                                <tr>
                                                  <td width="50%">
                                                    <input type="number" id="quantity_yang_ke_`+key+`" class="form-control" value="`+array_quantity[key]+`" onchange="change_quantity(`+key+`,this.value)">
                                                  </td>
                                                  <td width="50%">
                                                    <input type="text" id="satuan_yang_ke_`+key+`" class="form-control" placeholder="Satuan" value="`+array_satuan[key]+`" onchange="change_satuan(`+key+`,this.value)">
                                                  </td>
                                                </tr>
                                              </table>
                                            </div>
                                          </div>
                                          <div class="row pt-1">
                                            <div class="col-4">
                                              <label class="form-label pt-1">Nama Penerima</label>
                                            </div>
                                            <div class="col-8">
                                              <input type="text" id="nama_penerima_yang_ke_`+key+`" class="form-control" value="`+array_nama_penerima[key]+`" onchange="change_nama_penerima(`+key+`,this.value)">
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-6">
                                          <div class="row">
                                            <div class="col-4">
                                              <label class="form-label pt-1 pl-4">Keterangan</label>
                                            </div>
                                            <div class="col-8">
                                              <textarea id="keterangan_barang_yang_ke_`+key+`" class="form-control" value="`+array_keterangan_barang[key]+`" onchange="change_keterangan_barang(`+key+`,this.value)">`+array_keterangan_barang[key]+`</textarea>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>\
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
                                            <div class="col-8">
                                              <input type="text" id="nama_tamu_yang_ke_`+key+`" class="form-control" value="`+array_nama_tamu[key]+`" onchange="change_nama_tamu(`+key+`,this.value)">
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-6">
                                          <div class="row">
                                            <div class="col-4">
                                              <label class="form-label pt-1 pl-4">Nomor HP</label>
                                            </div>
                                            <div class="col-8">
                                              <input id="nomor_hp_yang_ke_`+key+`" class="form-control" value="`+array_nomor_hp_tamu[key]+`" onchange="change_nomor_hp_tamu(`+key+`,this.value)">
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
                                        <div class="col-9">
                                          <select id="karyawan_dinas_yang_ke_`+key+`" name="selectEmployeeDinas`+key+`[]" multiple data-placeholder="Pilih karyawan" class="form-control select3" style="width:80%" onchange="changeKaryawanDinas(`+key+`)">
                                              @foreach ($selectemployee as $r_empl)
                                                  <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                              @endforeach
                                          </select>
                                          <h6 id="warning_karyawan_dinas_ke_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                                        </div>
                                      </div>
                                    </div>
                                  </td>
                              </tr>
                              <tr>
                                <th class="bg-primary" colspan="2" style="padding:9px;border:1px solid #c4c0c0">Jarak Tempuh</th>
                              </tr>
                              <tr>
                                <td colspan="2" style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">
                                  <div class="row">
                                    <div class="col-5 pr-0">
                                      <input type="number" id="jarak_tempuh_yang_ke_`+key+`" value="`+array_jarak_tempuh[key]+`" class="form-control" onchange="jarakTempuhChange(`+key+`,this.value)">
                                    </div>
                                    <div class="col-7 pt-2">
                                      <label class="form-label">KM</label>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                          </table>\
                      </div>\
                  </div>\
              `);
            }
          }else{
            $('#tujuan_advanced').append(`<div class="row pb-2 pt-1">\
                <div class="col-2 pt-2">\
                    <label class="form-label" style="font-weight: bold;font-size:12pt">Tujuan ke `+(key+1)+`</label>\
                </div>\
              </div>\
              <div class="row p-0">\
                  <div class="col-12 px-0">\
                      <table class="w-100">\
                          <tr>\
                              <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Provinsi</th>\
                              <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Kabupaten/Kota</th>\
                              <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Kecamatan</th>\
                              <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Desa</th>\
                              <th width="16%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Detail Alamat</th>\
                              <th width="18%" class="bg-primary" style="padding:9px;border:1px solid #c4c0c0">Waktu Kedatangan</th>\
                              <th width="1%" style="padding:9px"></th>\
                          </tr>\
                          <tr>\
                              <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                                  <select id="provinsi_yang_ke_`+key+`" name="selectProvinsiTujuan[]" multiple data-placeholder="Pilih Provinsi" class="form-control select2oneSelecttwo" onchange="changeCity(`+key+`,this.value)" value="array">\
                                  </select>\
                                  <h6 id="warning_provinsi_tujuan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                                  <select id="history_alamat_yang_ke_`+key+`" name="history_alamat" data-placeholder="Pilih Provinsi" class="form-control" onchange="changeAddress(`+key+`,this.value)">
                                  </select>
                              </td>\
                              <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                                  <select id="kota_yang_ke_`+key+`" name="selectKotaTujuan[]" multiple data-placeholder="Pilih Kota" class="form-control select2oneSelecttwo" onchange="changeDistrict(`+key+`,this.value)">\
                                  </select>\
                                  <h6 id="warning_kota_tujuan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                              </td>
                              <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                                  <select id="kecamatan_yang_ke_`+key+`" name="selectKecamatanTujuan[]" multiple data-placeholder="Pilih Kecamatan" class="form-control select2oneSelecttwo" onchange="changeSubDistrict(`+key+`,this.value)">\
                                  </select>\
                                  <h6 id="warning_kecamatan_tujuan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                              </td>\
                              <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                                  <select id="desa_yang_ke_`+key+`" name="selectDesaTujuan[]" multiple data-placeholder="Pilih Desa" class="form-control select2oneSelecttwo" onchange="subdistrictChange(`+key+`,this.value)">\
                                  </select>\
                                  <h6 id="warning_desa_tujuan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                              </td>\
                              <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                                  <input id="instansi_yang_ke_`+key+`" placeholder="Masukkan Instansi" class="form-control" onchange="instansiChange(`+key+`,this.value)" style="background-color:white" value='`+array_instansi[key]+`'>\
                                  <input id="detail_alamat_yang_ke_`+key+`" placeholder="Masukkan Detail Alamat" class="form-control mt-1" onchange="detailAlamatChange(`+key+`,this.value)" style="background-color:white" value='`+array_detail_alamat[key]+`'>\
                              </td>\
                              <td style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">\
                                  <div class="row">\
                                      <div class="col-7 pr-0">\
                                          <input type="date" id="tanggal_pemberangkatan_yang_ke_`+key+`" class="form-control" style="background-color: white;cursor:pointer" onChange="tanggalPemberangkatanChange(`+key+`,this.value)" readonly>\
                                      </div>\
                                      <div class="col-5 pl-0">\
                                          <input class="form-control" id="jam_pemberangkatan_yang_ke_`+key+`" type="text" style="background-color: white; cursor:pointer;" onChange="jamPemberangkatanChange(`+key+`,this.value)"  value="`+array_jam_pemberangkatan[key]+`">\
                                      </div>\
                                  </div>\
                              </td>\
                              <td style="vertical-align:top">\
                                  <a style="color: white;cursor:pointer;background-color:red;padding:5px" onclick="delete_item(`+key+`)"><i class="fa fa-minus"></i></a>\
                              </td>\
                          </tr>\
                          <tr>
                              <th class="bg-primary" colspan="2" style="padding:9px;border:1px solid #c4c0c0">Tujuan</th>
                              <th class="bg-primary" colspan="4" style="padding:9px;border:1px solid #c4c0c0">Keterangan</th>
                              <th style="padding:9px"></th>
                          </tr>
                          <tr>
                              <td colspan="2" style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">
                                  <div class="row">
                                      <div class="col-xl-4">
                                      <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="antar_tamu" id="checkbox_1_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Antar tamu
                                      </div>
                                      <div class="col-xl-4">
                                      <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="antar_barang" id="checkbox_3_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Antar barang
                                      </div>
                                      <div class="col-xl-4">
                                      <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="antar_dinas" id="checkbox_5_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Antar dinas
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-xl-4">
                                          <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="jemput_tamu" id="checkbox_2_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Jemput tamu
                                      </div>
                                      <div class="col-xl-4">
                                          <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="jemput_barang" id="checkbox_4_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Jemput barang
                                      </div>
                                      <div class="col-xl-4">
                                          <input type="checkbox" name="tujuan_pemberangkatan_`+key+`" value="jemput_dinas" id="checkbox_6_`+key+`" onchange="checkbox_checked(`+key+`)">&nbsp;Jemput dinas
                                      </div>
                                  </div>
                                <h6 id="warning_tujuan_pemberangkatan_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>
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
                                        <div class="col-8">
                                          <input type="text" id="jenis_barang_yang_ke_`+key+`" class="form-control" value="`+array_jenis_barang[key]+`" onchange="change_jenis_barang(`+key+`,this.value)">
                                        </div>
                                      </div>
                                      <div class="row pt-1">
                                        <div class="col-4">
                                          <label class="form-label pt-1">Quantity</label>
                                        </div>
                                        <div class="col-8">
                                          <table class="w-100">
                                            <tr>
                                              <td width="50%">
                                                <input type="number" id="quantity_yang_ke_`+key+`" class="form-control" value="`+array_quantity[key]+`" onchange="change_quantity(`+key+`,this.value)">
                                              </td>
                                              <td width="50%">
                                                <input type="text" id="satuan_yang_ke_`+key+`" class="form-control" placeholder="Satuan" value="`+array_satuan[key]+`" onchange="change_satuan(`+key+`,this.value)">
                                              </td>
                                            </tr>
                                          </table>
                                        </div>
                                      </div>
                                      <div class="row pt-1">
                                        <div class="col-4">
                                          <label class="form-label pt-1">Nama Penerima</label>
                                        </div>
                                        <div class="col-8">
                                          <input type="text" id="nama_penerima_yang_ke_`+key+`" class="form-control" value="`+array_nama_penerima[key]+`" onchange="change_nama_penerima(`+key+`,this.value)">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-6">
                                      <div class="row">
                                        <div class="col-4">
                                          <label class="form-label pt-1 pl-4">Keterangan</label>
                                        </div>
                                        <div class="col-8">
                                          <textarea id="keterangan_barang_yang_ke_`+key+`" class="form-control" onchange="change_keterangan_barang(`+key+`,this.value)">`+array_keterangan_barang[key]+`</textarea>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>\
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
                                        <div class="col-8">
                                          <input type="text" id="nama_tamu_yang_ke_`+key+`" class="form-control" value="`+array_nama_tamu[key]+`" onchange="change_nama_tamu(`+key+`,this.value)">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-6">
                                      <div class="row">
                                        <div class="col-4">
                                          <label class="form-label pt-1 pl-4">Nomor HP</label>
                                        </div>
                                        <div class="col-8">
                                          <input id="nomor_hp_yang_ke_`+key+`" class="form-control" value="`+array_nomor_hp_tamu[key]+`" onchange="change_nomor_hp_tamu(`+key+`,this.value)">
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
                                    <div class="col-9">
                                      <select id="karyawan_dinas_yang_ke_`+key+`" name="selectEmployeeDinas`+key+`[]" multiple data-placeholder="Pilih karyawan" class="form-control select3" style="width:80%" onchange="changeKaryawanDinas(`+key+`)">
                                          @foreach ($selectemployee as $r_empl)
                                              <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                          @endforeach
                                      </select>
                                      <h6 id="warning_karyawan_dinas_ke_`+key+`" style="margin-bottom: 0px;padding-top:2px;color:red"></h6>\
                                    </div>
                                  </div>
                                </div>
                              </td>
                          </tr>
                          <tr>
                            <th class="bg-primary" colspan="2" style="padding:9px;border:1px solid #c4c0c0">Jarak Tempuh</th>
                          </tr>
                          <tr>
                            <td colspan="2" style="padding:9px;vertical-align:top;border:1px solid #c4c0c0">
                              <div class="row">
                                <div class="col-5 pr-0">
                                  <input type="number" id="jarak_tempuh_yang_ke_`+key+`" value="`+array_jarak_tempuh[key]+`" class="form-control" onchange="jarakTempuhChange(`+key+`,this.value)">
                                </div>
                                <div class="col-7 pt-2">
                                  <label class="form-label">KM</label>
                                </div>
                              </div>
                            </td>
                          </tr>
                      </table>\
                  </div>\
              </div>\
            `);
          }
            $('#jam_pemberangkatan_yang_ke_'+key).timepicker({
                timeFormat: "%H:%i"
            });
            setValueToDropdown(key,val);
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
            $('#tanggal_pemberangkatan_yang_ke_'+key).val(array_tanggal_pemberangkatan[key]);
            $('#tanggal_pemberangkatan_yang_ke_'+key+'-display').val(array_tanggal_pemberangkatan[key]);
            if (array_tujuan_pemberangkatan[key].includes("antar_tamu")) {
              $('#checkbox_1_'+key).prop('checked', true);
              document.getElementById('tag_keterangan_tamu_ke_'+key).style.display='block';
            }else{
              $('#checkbox_1_'+key).prop('checked', false);
            }
            if (array_tujuan_pemberangkatan[key].includes("jemput_tamu")) {
              $('#checkbox_2_'+key).prop('checked', true);
              document.getElementById('tag_keterangan_tamu_ke_'+key).style.display='block';
            }else{
              $('#checkbox_2_'+key).prop('checked', false);
            }
            if (array_tujuan_pemberangkatan[key].includes("antar_barang")) {
              $('#checkbox_3_'+key).prop('checked', true);
              document.getElementById('tag_keterangan_barang_ke_'+key).style.display='block';
            }else{
              $('#checkbox_3_'+key).prop('checked', false);
            }
            if (array_tujuan_pemberangkatan[key].includes("jemput_barang")) {
              $('#checkbox_4_'+key).prop('checked', true);
              document.getElementById('tag_keterangan_barang_ke_'+key).style.display='block';
            }else{
              $('#checkbox_4_'+key).prop('checked', false);
            }
            if (array_tujuan_pemberangkatan[key].includes("antar_dinas")) {
              $('#checkbox_5_'+key).prop('checked', true);
              document.getElementById('tag_keterangan_dinas_ke_'+key).style.display='block';
            }else{
              $('#checkbox_5_'+key).prop('checked', false);
            }
            if (array_tujuan_pemberangkatan[key].includes("jemput_dinas")) {
              $('#checkbox_6_'+key).prop('checked', true);
              document.getElementById('tag_keterangan_dinas_ke_'+key).style.display='block';
            }else{
              $('#checkbox_6_'+key).prop('checked', false);
            }
            const myArrayDinasLuar = array_karyawan_dinas_luar[key].split(",");
            $('#karyawan_dinas_yang_ke_'+key).val(myArrayDinasLuar).trigger('change');
        });
        $('.select2oneSelecttwo').select2({
            minimumResultsForSearch: Infinity,
            maximumSelectionLength: 1
        });
        $('.select3').select2({
            minimumResultsForSearch: Infinity,
        });
    }
    function change_initial_destination(province,city,district,subdistrict){
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
        document.getElementById('warning_provinsi').innerHTML='';
        document.getElementById('warning_kota').innerHTML='';
        document.getElementById('warning_kecamatan').innerHTML='';
        document.getElementById('warning_desa').innerHTML='';
        document.getElementById('detail_alamat').style.border='';
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
    function change_subdistrict(district,subdistrict){
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
    function tanggal_pemberangkatan_change(value){
        if(value!=''){
            document.getElementById("tanggal_pemberangkatan-display").style.border='';
        }
    }
    function jam_pemberangkatan_change(value){
      if(value!=''){
        document.getElementById("jam_pemberangkatan").style.border='';
      }
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
                $('#kecamatan_yang_ke_'+key).empty();
                $('#desa_yang_ke_'+key).empty();
                if(res.length>0){
                    document.getElementById('warning_provinsi_tujuan_'+key).innerHTML='';
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
    function check_empty_value(){
      jQuery.each(array_provinsi, function(key,val){
        if(val!=''){
          document.getElementById("warning_provinsi_tujuan_"+key).innerHTML='';
        }
        if(array_kota[key]!=''){
          document.getElementById("warning_kota_tujuan_"+key).innerHTML='';
        }
        if(array_kecamatan[key]!=''){
          document.getElementById("warning_kecamatan_tujuan_"+key).innerHTML='';
        }
        if(array_desa[key]!=''){
          document.getElementById("warning_desa_tujuan_"+key).innerHTML='';
        }
        if(array_detail_alamat[key]!=''){
          document.getElementById("detail_alamat_yang_ke_"+key).style.border = "";
        }
        if(array_tanggal_pemberangkatan[key]!=''){
          document.getElementById("tanggal_pemberangkatan_yang_ke_"+key+"-display").style.border = "";
        }
        if(array_jam_pemberangkatan[key]!=''){
          document.getElementById("jam_pemberangkatan_yang_ke_"+key).style.border = "";
        }
        if(array_tujuan_pemberangkatan[key]!=''){
          document.getElementById("warning_tujuan_pemberangkatan_"+key).innerHTML='';
          if(array_tujuan_pemberangkatan[key].includes("antar_barang")||array_tujuan_pemberangkatan[key].includes("jemput_barang")){
            if(array_jenis_barang[key]!=''){
              document.getElementById("jenis_barang_yang_ke_"+key).style.border = "";
            }
            if(array_quantity[key]!=''){
              document.getElementById("quantity_yang_ke_"+key).style.border = "";
            }
            if(array_satuan[key]!=''){
              document.getElementById("satuan_yang_ke_"+key).style.border = "";
            }
            if(array_nama_penerima[key]!=''){
              document.getElementById("nama_penerima_yang_ke_"+key).style.border = "";
            }
          }
          if(array_tujuan_pemberangkatan[key].includes("antar_tamu")||array_tujuan_pemberangkatan[key].includes("jemput_tamu")){
            if(array_nama_tamu[key]!=''){
              document.getElementById("nama_tamu_yang_ke_"+key).style.border = "";
            }
            if(array_nomor_hp_tamu[key]!=''){
              document.getElementById("nomor_hp_yang_ke_"+key).style.border = "";
            }
          }
          if(array_tujuan_pemberangkatan[key].includes("antar_dinas")||array_tujuan_pemberangkatan[key].includes("jemput_dinas")){
            if(array_karyawan_dinas_luar[key]!=''){
              document.getElementById("warning_karyawan_dinas_ke_"+key).innerHTML='';
            }
          }
        }
      });
    }
    function saveChanges(){
      array_keterangan=[];
      var employeeId = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
      var employeeId_string=employeeId.toString();
      if(employeeId_string==''){
        document.getElementById("warning_employee").innerHTML='Karyawan harus dipilih';
      }else{
        document.getElementById("warning_employee").innerHTML='';
      }
      var provinsi_asal = $("select[name='selectProvinsi[]']").map(function(){return $(this).val();}).get();
      var provinsi_asal_string=provinsi_asal.toString();
      if(provinsi_asal_string==''){
        document.getElementById("warning_provinsi").innerHTML='Provinsi harus dipilih';
      }else{
        document.getElementById("warning_provinsi").innerHTML='';
      }
      var kota_asal = $("select[name='selectCity[]']").map(function(){return $(this).val();}).get();
      var kota_asal_string=kota_asal.toString();
      if(kota_asal_string==''){
        document.getElementById("warning_kota").innerHTML='Kota harus dipilih';
      }else{
        document.getElementById("warning_kota").innerHTML='';
      }
      var kecamatan_asal = $("select[name='selectDistrict[]']").map(function(){return $(this).val();}).get();
      var kecamatan_asal_string=kecamatan_asal.toString();
      if(kecamatan_asal_string==''){
        document.getElementById("warning_kecamatan").innerHTML='Kecamatan harus dipilih';
      }else{
        document.getElementById("warning_kecamatan").innerHTML='';
      }
      var desa_asal = $("select[name='selectSubdistrict[]']").map(function(){return $(this).val();}).get();
      var desa_asal_string=desa_asal.toString();
      if(desa_asal_string==''){
        document.getElementById("warning_desa").innerHTML='Desa harus dipilih';
      }else{
        document.getElementById("warning_desa").innerHTML='';
      }
      var instansi = $("#instansi").val();
      var detail_alamat_asal = $("#detail_alamat").val();
      var detail_alamat_asal_string=detail_alamat_asal.toString();
      if(detail_alamat_asal_string==''){
        document.getElementById("detail_alamat").style.border = "1px solid red";
      }else{
        document.getElementById("detail_alamat").style.border = "";
      }
      var tanggal_pemberangkatan_asal=$('#tanggal_pemberangkatan').val();
      if(tanggal_pemberangkatan_asal==''){
        document.getElementById("tanggal_pemberangkatan-display").style.border = "1px solid red";
      }else{
        document.getElementById("tanggal_pemberangkatan-display").style.border = "";
      }
      var jam_pemberangkatan_asal=$('#jam_pemberangkatan').val();
      if(jam_pemberangkatan_asal==''){
        document.getElementById("jam_pemberangkatan").style.border = "1px solid red";
      }else{
        document.getElementById("jam_pemberangkatan").style.border = "";
      }
      jQuery.each(array_provinsi, function(key,val){
        if(val==''){
          document.getElementById("warning_provinsi_tujuan_"+key).innerHTML='Provinsi harus dipilih';
        }else{
          document.getElementById("warning_provinsi_tujuan_"+key).innerHTML='';
        }
        if(array_kota[key]==''){
          document.getElementById("warning_kota_tujuan_"+key).innerHTML='Kota harus dipilih';
        }else{
          document.getElementById("warning_kota_tujuan_"+key).innerHTML='';
        }
        if(array_kecamatan[key]==''){
          document.getElementById("warning_kecamatan_tujuan_"+key).innerHTML='Kecamatan harus dipilih';
        }else{
          document.getElementById("warning_kecamatan_tujuan_"+key).innerHTML='';
        }
        if(array_desa[key]==''){
          document.getElementById("warning_desa_tujuan_"+key).innerHTML='Kecamatan harus dipilih';
        }else{
          document.getElementById("warning_desa_tujuan_"+key).innerHTML='';
        }
        if(array_detail_alamat[key]==''){
          document.getElementById("detail_alamat_yang_ke_"+key).style.border = "1px solid red";
        }else{
          document.getElementById("detail_alamat_yang_ke_"+key).style.border = "";
        }
        if(array_instansi[key]==''){
          document.getElementById("instansi_yang_ke_"+key).style.border = "1px solid red";
        }else{
          document.getElementById("instansi_yang_ke_"+key).style.border = "";
        }
        if(array_tanggal_pemberangkatan[key]==''){
          document.getElementById("tanggal_pemberangkatan_yang_ke_"+key+"-display").style.border = "1px solid red";
        }else{
          document.getElementById("tanggal_pemberangkatan_yang_ke_"+key+"-display").style.border = "";
        }
        if(array_jam_pemberangkatan[key]==''){
          document.getElementById("jam_pemberangkatan_yang_ke_"+key).style.border = "1px solid red";
        }else{
          document.getElementById("jam_pemberangkatan_yang_ke_"+key).style.border = "";
        }
        if(array_tujuan_pemberangkatan[key]==''){
          document.getElementById("warning_tujuan_pemberangkatan_"+key).innerHTML='Tujuan Pemberangkatan harus dipilih';
        }else{
          if(array_tujuan_pemberangkatan[key].includes("antar_barang")||array_tujuan_pemberangkatan[key].includes("jemput_barang")){
            if(array_jenis_barang[key]==''){
              array_keterangan.push('');
              document.getElementById("jenis_barang_yang_ke_"+key).style.border = "1px solid red";
            }else{
              array_keterangan.push(array_jenis_barang[key]);
              document.getElementById("jenis_barang_yang_ke_"+key).style.border = "";
            }
            if(array_quantity[key]==''){
              array_keterangan.push('');
              document.getElementById("quantity_yang_ke_"+key).style.border = "1px solid red";
            }else{
              array_keterangan.push(array_quantity[key]);
              document.getElementById("quantity_yang_ke_"+key).style.border = "";
            }
            if(array_satuan[key]==''){
              array_keterangan.push('');
              document.getElementById("satuan_yang_ke_"+key).style.border = "1px solid red";
            }else{
              array_keterangan.push(array_satuan[key]);
              document.getElementById("satuan_yang_ke_"+key).style.border = "";
            }
            if(array_nama_penerima[key]==''){
              array_keterangan.push('');
              document.getElementById("nama_penerima_yang_ke_"+key).style.border = "1px solid red";
            }else{
              array_keterangan.push(array_nama_penerima[key]);
              document.getElementById("nama_penerima_yang_ke_"+key).style.border = "";
            }
          }
          if(array_tujuan_pemberangkatan[key].includes("antar_tamu")||array_tujuan_pemberangkatan[key].includes("jemput_tamu")){
            if(array_nama_tamu[key]==''){
              array_keterangan.push('');
              document.getElementById("nama_tamu_yang_ke_"+key).style.border = "1px solid red";
            }else{
              array_keterangan.push(array_nama_tamu[key]);
              document.getElementById("nama_tamu_yang_ke_"+key).style.border = "";
            }
            if(array_nomor_hp_tamu[key]==''){
              array_keterangan.push('');
              document.getElementById("nomor_hp_yang_ke_"+key).style.border = "1px solid red";
            }else{
              array_keterangan.push(array_nomor_hp_tamu[key]);
              document.getElementById("nomor_hp_yang_ke_"+key).style.border = "";
            }
          }
          if(array_tujuan_pemberangkatan[key].includes("antar_dinas")||array_tujuan_pemberangkatan[key].includes("jemput_dinas")){
            if(array_karyawan_dinas_luar[key]==''){
              array_keterangan.push('');
              document.getElementById("warning_karyawan_dinas_ke_"+key).innerHTML='Karyawan harus dipilih';
            }else{
              array_keterangan.push(array_karyawan_dinas_luar[key]);
              document.getElementById("warning_karyawan_dinas_ke_"+key).innerHTML='';
            }
          }
        }
      });
      if(employeeId_string!='' && provinsi_asal_string!='' && kota_asal_string!='' && kecamatan_asal_string!='' && desa_asal_string!='' && detail_alamat_asal_string!='' && tanggal_pemberangkatan_asal!='' && jam_pemberangkatan_asal!='' && !array_provinsi.includes('') && !array_kota.includes('') && !array_kecamatan.includes('') && !array_desa.includes('') && !array_detail_alamat.includes('') && !array_tanggal_pemberangkatan.includes('') && !array_jam_pemberangkatan.includes('') && !array_tujuan_pemberangkatan.includes('') && !array_keterangan.includes('')){
        $.ajax({
          type:"POST",
          url: "{{route('hris.ga.store_car_request')}}",
          data: {
            employee:employeeId_string,
            provinsi:provinsi_asal_string,
            cities:kota_asal_string,
            districts:kecamatan_asal_string,
            sub_districts:desa_asal_string,
            instansi:instansi,
            detail_alamat:detail_alamat_asal_string,
            tanggal_pemberangkatan:tanggal_pemberangkatan_asal,
            jam_pemberangkatan:jam_pemberangkatan_asal,
            tujuan_pemberangkatan_array:array_tujuan_pemberangkatan,
            provinsi_array:array_provinsi,
            city_array:array_kota,
            kecamatan_array:array_kecamatan,
            desa_array:array_desa,
            instansi_array:array_instansi,
            detail_alamat_array:array_detail_alamat,
            tanggal_pemberangkatan_array:array_tanggal_pemberangkatan,
            jam_pemberangkatan_array:array_jam_pemberangkatan,
            jarak_tempuh_array:array_jarak_tempuh,
            jenis_barang_array:array_jenis_barang,
            quantity_array:array_quantity,
            satuan_array:array_satuan,
            nama_penerima_array:array_nama_penerima,
            keterangan_barang_array:array_keterangan_barang,
            nama_tamu_array:array_nama_tamu,
            nomor_hp_tamu_array:array_nomor_hp_tamu,
            karyawan_dinas_luar_array:array_karyawan_dinas_luar,
          },
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          success: function(res){
              swal("", "Permintaan transportasi terkirim", "success");
              var url = 'data_pengajuan_transportasi_admin';
              window.open(url, '_self');
          },
          error: function(error){
            swal("", "Permintaan transportasi gagal terkirim", "error");
          }
      });
      }
    }
    function get_all_destination_history(){
      $.ajax({
        type:"POST",
        url: "{{route('hris.ga.get_all_destination_history')}}",
        data: {
          id:$('#user').val(),
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res){
          console.log(res);
          $('select[name="history_alamat"]').append('<option value="">History Alamat</option>');
          jQuery.each(res, function(key,value){
            $('select[name="history_alamat"]').append('<option value="'+value.instansi+' | '+value.detail_alamat+' | '+value.subdistrict+' | '+value.dis_id+' | '+value.city_id+' | '+value.prov_id+'">'+value.detail_alamat_tujuan+'</option>');
          });
        },
        error: function(error){
          swal("", "Gagal mengambil data riwayat alamat", "error");
        }
      });
    }
    function changeAddress(key,value){
      myArray=[];
      var tujuan_id = $('#history_alamat_yang_ke_'+key).val();
      myArray = tujuan_id.split(" | ");
      $('#history_alamat_yang_ke_'+key).val('');
      setHistoryToDropdown(key,myArray[0],myArray[1],myArray[5],myArray[4],myArray[3],myArray[2])

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
    function setHistoryToDropdown(key,instansi,detail_alamat,province,city,district,subdistrict){
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_province')}}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#provinsi_yang_ke_'+key).empty();
                jQuery.each(res, function(k,v){
                    $('#provinsi_yang_ke_'+key).append('<option value="'+ v['prov_id'] +'">'+ v['prov_name'] +'</option>');
                });
                $('#provinsi_yang_ke_'+key+' option[value="' + province + '"]').prop('selected',true);
            },
            error: function(res){
                swal({
                    title: "Ambil data provinsi",
                    text: "Data provinsi gagal di ambil",
                    icon: "danger",
                });
            }
        });
        document.getElementById("warning_provinsi_tujuan_"+key).innerHTML='';
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_cities')}}",
            data: {
                provinsi:province,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#kota_yang_ke_'+key).empty();
                jQuery.each(res, function(k,v){
                    $('#kota_yang_ke_'+key).append('<option value="'+ v['city_id'] +'">'+ v['city_name'] +'</option>');
                });
                $('#kota_yang_ke_'+key+' option[value="' + city + '"]').prop('selected',true);
            },
            error: function(res){
                swal({
                    title: "Ambil data kota",
                    text: "Data kota gagal di ambil",
                    icon: "danger",
                });
            }
        });
        document.getElementById("warning_kota_tujuan_"+key).innerHTML='';
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_districts')}}",
            data: {
                cities:city,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#kecamatan_yang_ke_'+key).empty();
                jQuery.each(res, function(k,v){
                    $('#kecamatan_yang_ke_'+key).append('<option value="'+ v['dis_id'] +'">'+ v['dis_name'] +'</option>');
                });
                $('#kecamatan_yang_ke_'+key+' option[value="' + district + '"]').prop('selected',true);
            },
            error: function(res){
                swal({
                    title: "Ambil data desa",
                    text: "Data desa gagal di ambil",
                    icon: "danger",
                });
            }
        });
        document.getElementById("warning_kecamatan_tujuan_"+key).innerHTML='';
        $.ajax({
            type:"POST",
            url: "{{route('hris.ga.get_subdistricts')}}",
            data: {
                districts:district,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res){
                $('#desa_yang_ke_'+key).empty();
                jQuery.each(res, function(k,v){
                    $('#desa_yang_ke_'+key).append('<option value="'+ v['subdis_id'] +'">'+ v['subdis_name'] +'</option>');
                });
                $('#desa_yang_ke_'+key+' option[value="' + subdistrict + '"]').prop('selected',true);
            },
            error: function(res){
                swal({
                    title: "Ambil data desa",
                    text: "Data desa gagal di ambil",
                    icon: "danger",
                });
            }
        });
        array_provinsi[key]=province;
        array_kota[key]=city;
        array_kecamatan[key]=district;
        array_desa[key]=subdistrict;
        array_instansi[key]=instansi;
        array_detail_alamat[key]=detail_alamat;
        document.getElementById("warning_desa_tujuan_"+key).innerHTML='';
        document.getElementById("instansi_yang_ke_"+key).style.border='';
        document.getElementById("detail_alamat_yang_ke_"+key).style.border='';
        $('#instansi_yang_ke_'+key).val(instansi);
        $('#detail_alamat_yang_ke_'+key).val(detail_alamat);
    }
</script>
@endsection
