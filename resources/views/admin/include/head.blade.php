<meta charset="utf-8"/>
<title>{{$setting->website}} - {{ $pageTitle }}</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="hris,ptnag" name="description">
<meta content="biriodede@gmail.com" name="biriodede">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta http-equiv="refresh" content="{{ Config::get('session.lifetime') }};url={{ url('screenlock') }}" />
<meta name="csrf-token" content="{{ csrf_token() }}"/>

@include('layouts.verticalmenu.closed-sidebar.styles')
