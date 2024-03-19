@extends('layouts.app')

@section('styles')

	<!---Sweetalert Css-->
	<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Apps</a></li>
								<li class="breadcrumb-item active" aria-current="page">Sweet-alert</li>
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

						<!-- row -->
						<div class="row">
							<div class="col-sm-12 ">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Forms Sweet-alert</h3>
									</div>
									<div class="card-body">
										<table class="table card-table">
											<tr >
												<td class="border-top-0">Title</td>
												<td class="border-top-0"><input type='text' class="form-control" placeholder='Title text' id='title'></td>
											</tr>
											<tr>
												<td class="border-top-0">Message</td>
												<td class="border-top-0"><input type='text' class="form-control" placeholder='Your message' id='message'></td>
											</tr>
											<tr>
												<td colspan='2' class="mt-5 text-center border-top-0">
													<input type='button' class="btn btn-primary mt-2" value='Simple alert' id='but1'>&nbsp;
													<input type='button' class="btn btn-secondary mt-2" value='Alert with title' id='but2'>&nbsp;
													<input type='button' class="btn btn-warning mt-2" value='Alert with image' id='but3'>&nbsp;
													<input type='button' class="btn btn-success mt-2" value='With timer' id='but4'>
												</td>
											</tr>
										</table>
									</div>
								</div>
								<div class="card text-center">
									<div class="card-header">
										<h3 class="card-title">Alert Types</h3>
									</div>
									<div class="card-body">
										<p>You can show following types of message box &#8211; You need to specify this in <code>type</code> property as value.</p>

										<input type='button' class="btn btn-success mt-2" value='success alert' id='click'>
										<input type='button' class="btn btn-warning mt-2" value='Warning alert' id='click1'>
										<input type='button' class="btn btn-danger mt-2" value='Danger alert' id='click2'>
									</div>
								</div>
							</div>
						</div><!-- row end -->
						<div class="row">
							<div class="col-sm-12 ">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Prompt and confirm box Demo</h3>
									</div>
									<div class="card-body">
										<table class="table card-table border">
											<tr>
												<td><input type='button' class="btn btn-warning btn-block "  value='Prompt' id='prompt'></td>
												<td><input type='button' class="btn btn-primary btn-block" value='Confirm' id='confirm'></td>
											</tr>
										</table>
									</div>
								</div>
							</div><!-- col end -->
						</div><!-- row end -->

@endsection('content')

@section('scripts')

		<!--Jquery Sparkline js-->
		<script src="{{URL::asset('assets/plugins/vendors/jquery.sparkline.min.js')}}"></script>

		<!-- Chart Circle js-->
		<script src="{{URL::asset('assets/plugins/vendors/circle-progress.min.js')}}"></script>
		
        <!--Time Counter js-->
		<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>
		
        <!-- Sweet alert js-->
		<script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('assets/js/sweet-alert.js')}}"></script>

@endsection