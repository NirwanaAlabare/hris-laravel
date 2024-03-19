@extends('layouts.app')

@section('styles')

    <!-- WYSIWYG Editor css -->
    <link href="{{URL::asset('assets/plugins/wysiwyag/richtext.css')}}" rel="stylesheet" />
    <!--Summernote css-->
    <link rel="stylesheet" href="{{URL::asset('assets/plugins/summernote/summernote-bs4.css')}}"/>

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Forms</a></li>
								<li class="breadcrumb-item active" aria-current="page">WYSIWYG Editor</li>
							</ol><!-- End breadcrumb -->
							<div class="ml-auto">
								<div class="input-group">
									<a href="#" class="btn btn-secondary text-white mr-2 btn-sm" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Rating">
										<span>
											<i class="fa fa-star"></i>
										</span>
									</a>
									<a href="{{url('lockscreen')}}" class="btn btn-primary text-white mr-2 btn-sm" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="lock">
										<span>
											<i class="fa fa-lock"></i>
										</span>
									</a>
									<a href="#" class="btn btn-warning text-white btn-sm" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Add New">
										<span>
											<i class="fa fa-plus"></i>
										</span>
									</a>
								</div>
							</div>
						</div>
						<!-- End page-header -->

						<!--row open-->
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Summerynote</h3>
									</div>
									<div class="card-body">
										<div id="summernote"></div>
									</div>
								</div>
							</div>
						</div>
						<!--row closed-->

						<!--row open-->
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Wysiwing Editor</h3>
									</div>
									<div class="card-body">
										<form method="post">
											<textarea id="elm1" name="area">Hello!....</textarea>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!--row closed-->

						<!-- row  -->
						<div class="row row-cards">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">WYSIWYG Editor</h3>
									</div>
									<div class="card-body">
										<textarea class="content" name="example"></textarea>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

@endsection('content')

@section('scripts')

    <!--Jquery Sparkline js-->
    <script src="{{URL::asset('assets/plugins/vendors/jquery.sparkline.min.js')}}"></script>

    <!-- Chart Circle js-->
    <script src="{{URL::asset('assets/plugins/vendors/circle-progress.min.js')}}"></script>
    <!--Time Counter js-->
    <script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>
    <!-- WYSIWYG Editor js -->
    <script src="{{URL::asset('assets/plugins/wysiwyag/jquery.richtext.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/wysiwyag/richText1.js')}}"></script>

    <!--Summernote js-->
    <script src="{{URL::asset('assets/plugins/summernote/summernote-bs4.js')}}"></script>
    <script src="{{URL::asset('assets/js/summernote.js')}}"></script>

    <!--ckeditor js-->
    <script src="{{URL::asset('assets/plugins/tinymce/tinymce.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/formeditor.js')}}"></script>

@endsection