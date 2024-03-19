@extends('layouts.app')

@section('styles')

	<!-- Morris  Charts css-->
	<link href="{{URL::asset('assets/plugins/morris/morris.css')}}" rel="stylesheet" />

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Charts</a></li>
								<li class="breadcrumb-item active" aria-current="page">Morris Charts</li>
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
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Area chart</h3>
									</div>
									<div class="card-body">
										<div id="morrisBar2" class="chartsh chart-dropshadow"></div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Area chart 2</h3>
									</div>
									<div class="card-body">
										<div id="morrisBar3" class="chartsh chart-dropshadow"></div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Single bar chart</h3>
									</div>
									<div class="card-body">
										<div id="morrisBar4" class="chartsh chart-dropshadow"></div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Multiple Bar chart</h3>
									</div>
									<div class="card-body">
										<div id="morrisBar5" class="chartsh chart-dropshadow"></div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">line chart animated</h3>
									</div>
									<div class="card-body">
										<div id="morrisBar6" class="chartsh chart-dropshadow"></div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">line chart</h3>
									</div>
									<div class="card-body">
										<div id="morrisBar7" class="chartsh chart-dropshadow"></div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end-->

						<!-- row -->
						<div class="row">
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Donut chart</h3>
									</div>
									<div class="card-body">
										<div id="morrisBar8" class="chartsh chart-dropshadow"></div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Donut chart with multiple colors</h3>
									</div>
									<div class="card-body">
										<div id="morrisBar9" class="chartsh chart-dropshadow"></div>
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
		
        <!--Morris  Charts js-->
		<script src="{{URL::asset('assets/plugins/morris/raphael-min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/morris/morris.js')}}"></script>

		<!-- Custom-charts js-->
		<script src="{{URL::asset('assets/js/morris.js')}}"></script>

@endsection