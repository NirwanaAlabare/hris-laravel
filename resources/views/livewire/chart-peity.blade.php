@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Charts</a></li>
								<li class="breadcrumb-item active" aria-current="page">Peity Charts</li>
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
										<h3 class="card-title">Pie Chart</h3>
									</div>
									<div class="text-center">
										<div class="row">
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<span class="pie" data-peity='{ "fill": ["#13dafe", "#ebe9f7"]}'>1/5</span>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<span class="pie" data-peity='{ "fill": ["#467fcf  ", "#ebe9f7"]}'>226/360</span>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<span class="pie" data-peity='{ "fill": ["#63d457 ", "#ebe9f7"]}'>0.52/1.561</span>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<span class="pie" data-peity='{ "fill": ["#99D683", "#ebe9f7"]}'>1,4</span>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<span class="pie" data-peity='{ "fill": ["#FFCA4A", "#ebe9f7"]}'>226,134</span>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<span class="pie" data-peity='{ "fill": ["#4C5667", "#ebe9f7"]}'>0.52,1.041</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Donut Charts</h3>
									</div>
									<div class="text-center">
										<div class="row">
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<span class="donut" data-peity='{ "fill": ["#13DAFE", "#ebe9f7"]}'>1/5</span>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<span class="donut" data-peity='{ "fill": ["#467fcf  ", "#ebe9f7"]}'>226/360</span>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<span class="donut" data-peity='{ "fill": ["#63d457 ", "#ebe9f7"]}'>0.52/1.561</span>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<span class="donut" data-peity='{ "fill": ["#99D683", "#ebe9f7"]}'>1,4</span>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<span class="donut" data-peity='{ "fill": ["#FFCA4A", "#ebe9f7"]}'>226,134</span>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<span class="donut" data-peity='{ "fill": ["#4C5667", "#ebe9f7"]}'>0.52,1.041</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

                        <!-- row -->
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Line Charts</h3>
									</div>
									<div class="text-center">
										<div class="row">
											<div class="col-lg-4">
												<div class="card-body ">
													<span class="peity-line" data-peity='{ "fill": ["#467fcf"],"stroke":["#467fcf"]}' data-width="100%">6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span>
												</div>
											</div>
											<div class="col-lg-4">
												<div class="card-body ">
													<span class="peity-line" data-peity='{ "fill": ["#32cafe"],"stroke":["#32cafe"]}' data-width="100%">6,2,8,4,-3,8,1,-3,6,-5,9,2,-8,1,4,8,9,8,2,1</span>
												</div>
											</div>
											<div class="col-lg-4">
												<div class="card-body ">
													<span class="peity-line" data-peity='{ "fill": ["#FFCA4A"],"stroke":["#FFCA4A"]}' data-width="100%">6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Bar Charts</h3>
									</div>
									<div class="text-center">
										<div class="row">
											<div class="col-lg-4">
												<div class="card-body ">
													<span class="bar" data-peity='{ "fill": ["#13DAFE", "#467fcf  "]}'>6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span>
												</div>
											</div>
											<div class="col-lg-4">
												<div class="card-body ">
													<span class="bar" data-peity='{ "fill": ["#63d457 ", "#ebe9f7"]}'>6,2,8,4,-3,8,1,-3,6,-5,9,2,-8,1,4,8,9,8,2,1</span>
												</div>
											</div>
											<div class="col-lg-4">
												<div class="card-body ">
													<span class="bar" data-peity='{ "fill": ["#99D683", "#4C5667"]}'>6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Data attributes</h3>
									</div>
									<div class="text-center">
										<div class="row">
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<p class="data-attributes">
														<span data-peity='{ "fill": ["#13DAFE", "#ebe9f7"],    "innerRadius": 10, "radius": 40 }'>1/7</span>
													</p>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<p class="data-attributes">
														<span data-peity='{ "fill": ["#467fcf  ", "#ebe9f7"], "innerRadius": 14, "radius": 36 }'>2/7</span>
													</p>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<p class="data-attributes">
														<span data-peity='{ "fill": ["#63d457 ", "#ebe9f7"], "innerRadius": 16, "radius": 32 }'>3/7</span>
													</p>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<p class="data-attributes">
														<span data-peity='{ "fill": ["#99D683", "#ebe9f7"],  "innerRadius": 18, "radius": 28 }'>4/7</span>
													</p>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<p class="data-attributes">
														<span data-peity='{ "fill": ["#FFCA4A", "#ebe9f7"],   "innerRadius": 20, "radius": 24 }'>5/7</span>
													</p>
												</div>
											</div>
											<div class="col-lg-2 col-md-4">
												<div class="card-body ">
													<p class="data-attributes">
														<span data-peity='{ "fill": ["indigo", "#ebe9f7"], "innerRadius": 18, "radius": 20 }'>6/7</span>
													</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Setting Colours Dynamically</h3>
									</div>
									<div class="text-center">
										<div class="row">
											<div class="col-lg-3">
												<div class="card-body ">
													<span class="bar-colours-1">5,3,9,6,5,9,7,3,5,2</span>
												</div>
											</div>
											<div class="col-lg-3">
												<div class="card-body ">
													<span class="bar-colours-2">5,3,2,-1,-3,-2,2,3,5,2</span>
												</div>
											</div>
											<div class="col-lg-3">
												<div class="card-body ">
													<span class="bar-colours-3">0,-3,-6,-4,-5,-4,-7,-3,-5,-2</span>
												</div>
											</div>
											<div class="col-lg-3">
												<div class="card-body ">
													<span class="pie-colours-2">5,3,9,6,5</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Updating Charts</h3>
									</div>
									<div class="card-body text-center">
										<span class="updating-chart" data-peity='{ "fill": ["#467fcf"],"stroke":["#467fcf"]}'>5,3,9,6,5,9,7,3,5,2,5,3,9,6,5,9,7,3,5,2</span>
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
		
        <!--Peitychart js -->
		<script src="{{URL::asset('assets/plugins/peitychart/jquery.peity.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/peitychart/peitychart.init.js')}}"></script>

@endsection