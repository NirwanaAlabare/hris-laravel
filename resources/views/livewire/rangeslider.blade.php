@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Apps</a></li>
								<li class="breadcrumb-item active" aria-current="page">Range slider</li>
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
							<div class="col-md-12">
								<div class="card overflow-hidden">
									<div class="card-header pb-0">
										<h3 class="card-title">Default Ranges</h3>
									</div>
									<div class="card-body">
										<input id="range-1-1" type="range" min="0" max="100" value="30">
									</div>
								</div>

								<div class="card overflow-hidden">
									<div class="card-header pb-0">
										<h3 class="card-title">Default Ranges slider2</h3>
									</div>
									<div class="card-body">
										<input id="range-2-1" type="range" min="0" max="100" value="30">
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-md-12">
								<div class="card overflow-hidden">
									<div class="card-header pb-0">
										<h3 class="card-title">Range Slider Models</h3>
									</div>
									<div class="card-body">
										<input id="range-1-2" type="range" min="0" max="100" value="30">
										<input id="range-1-3" type="range" min="0" max="100" value="30">
										<input id="range-2-2" type="range" min="0" max="100" value="30">
										<label class="h4 mb-0">Particular Value Range slider</label>
										<input id="range-3" type="range" min="0" max="100" value="30">
										<label class="h4 mb-0">Fixed Range Slider</label>
										<input id="range-4-1" type="range" min="0" max="100" value="30">
										<input id="range-4-2" type="range" min="0" max="100" value="30">
										<label class="h4 mb-0">2 values of Range Slider</label>
										<input id="range-5-1" type="range" min="0" max="100" value="30">
										<label class="h4 mb-0">Value Not Visible Range Slider</label>
										<input id="range-6-1" type="range" min="0" max="100" value="30">
										<label class="h4 mb-0">0 to 100 Rage slider</label>
										<input id="range-8-1" type="range" min="0" max="100" value="30">
									</div>
								</div>
							</div><!-- col end -->
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
		
        <!--Rang slider js-->
		<script src="{{URL::asset('assets/plugins/range-slider/range-slider.js')}}"></script>
		<script src="{{URL::asset('assets/js/rangeslider.js')}}"></script>

@endsection