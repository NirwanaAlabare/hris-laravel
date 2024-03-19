@extends('vendor.installer.layouts.master')
@section('style')
    <style>
        .paragraph{
            text-align: center;
        }
    </style>
@endsection
@section('title', trans('installer_messages.final.title'))
@section('container')
    <p class="paragraph">{{ session('message')['message'] }}</p>
    <div class="buttons">
        <a href="{{ url('/admin') }}" class="button">{{ trans('installer_messages.final.exit') }}</a>
    </div>
@stop
