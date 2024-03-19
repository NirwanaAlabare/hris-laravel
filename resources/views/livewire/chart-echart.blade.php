@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Charts</a></li>
								<li class="breadcrumb-item active" aria-current="page">ECharts</li>
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
										<h3 class="card-title">Line chart</h3>
									</div>
									<div class="card-body">
										<div id="echart1" class="chartsh chart-dropshadow"></div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Combination of Line & Bar Chart</h3>
									</div>
									<div class="card-body">
										<div id="echart2" class="chartsh chart-dropshadow"></div>
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
										<h3 class="card-title">Vertical Line chart</h3>
									</div>
									<div class="card-body">
										<div id="echart3" class="chartsh chart-dropshadow"></div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Vertical Combination of Line & Bar Chart</h3>
									</div>
									<div class="card-body">
										<div id="echart4" class="chartsh chart-dropshadow"></div>
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
										<h3 class="card-title">Bar Chart</h3>
									</div>
									<div class="card-body">
										<div id="echart5" class="chartsh chart-dropshadow"></div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Horizontal Bar Chart</h3>
									</div>
									<div class="card-body">
										<div id="echart6" class="chartsh chart-dropshadow"></div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header  pb-0">
										<h3 class="card-title">Single line chart</h3>
									</div>
									<div class="card-body">
										<div id="echart7" class="chartsh chart-dropshadow"></div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Single smooth line chart</h3>
									</div>
									<div class="card-body">
										<div id="echart8" class="chartsh chart-dropshadow"></div>
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
										<h3 class="card-title">Radar chart</h3>
									</div>
									<div class="card-body">
										<div id="echart-1" class="chartsh chart-dropshadow"></div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Live Area Chart</h3>
									</div>
									<div class="card-body">
										<div id="echart-2" class="chartsh chart-dropshadow"></div>
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
										<h3 class="card-title">Pie Chart</h3>
									</div>
									<div class="card-body">
										<div id="echart-3" class="chartsh chart-dropshadow"></div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Gauge Chart</h3>
									</div>
									<div class="card-body">
										<div id="echart-4" class="chartsh chart-dropshadow"></div>
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
		
        <!-- ECharts js -->
		<script src="{{URL::asset('assets/plugins/echarts/echarts.js')}}"></script>

		<!-- Custom-charts js-->
		<script src="{{URL::asset('assets/js/echarts.js')}}"></script>

@endsection