@extends('layouts.app')

@section('styles')

	<!-- Nvd3 Charts css-->
	<link href="{{URL::asset('assets/plugins/charts-nvd3/nv.d3.css')}}" rel="stylesheet" />

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Charts</a></li>
								<li class="breadcrumb-item active" aria-current="page">Nvd3 Charts</li>
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
							<div class="col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Bar Chart With Multiple colors</h3>
									</div>
									<div class="card-body">
										<div id="nvd3-chart1" class="chart-dropshadow chartsh"> <svg></svg></div>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Bar Chart</h3>
									</div>
									<div class="card-body">
										<svg id="nvd3-chart2" class="chart-dropshadow chartsh h-300"></svg>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Line Chart With Zooming Options</h3>
									</div>
									<div class="card-body">
										<div id="chartZoom">
											<a class="btn btn-outline-secondary btn-sm" id="zoomIn">+</a> <a  class="btn btn-outline-secondary btn-sm" id="zoomOut">-</a>
										</div>
										<div id="nvd3-chart3" class="chart-dropshadow with-transitions chartsh">
											<svg></svg>
										</div>
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
		
        <!-- Nvd3Charts js -->
		<script src="{{URL::asset('assets/plugins/charts-nvd3/d3.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/charts-nvd3/nv.d3.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/charts-nvd3/stream_layers.js')}}"></script>
		
        <!-- Custom-charts js-->
		<script src="{{URL::asset('assets/js/nvd3.js')}}"></script>

@endsection