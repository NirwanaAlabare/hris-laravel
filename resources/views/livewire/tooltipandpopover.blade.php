@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Advanced UI</a></li>
								<li class="breadcrumb-item active" aria-current="page">Tooltip and Popover</li>
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

						<!--row-->
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Static Tooltip</h3>
									</div>
									<div class="card-body text-center">
										<div class="bd-example bd-example-tooltip-static">
											<div class="tooltip tooltip-top" role="tooltip">
												<div class="tooltip-inner">
												  Tooltip on the top
												</div>
											</div>
											<div class="tooltip tooltip-right" role="tooltip">
												<div class="tooltip-inner">
												  Tooltip on the right
												</div>
											</div>
											<div class="tooltip tooltip-bottom" role="tooltip">
												<div class="tooltip-inner">
												  Tooltip on the bottom
												</div>
											</div>
											<div class="tooltip tooltip-left" role="tooltip">
												<div class="tooltip-inner">
												  Tooltip on the left
												</div>
											</div>
										</div>
									</div>
									<div class="card-body">
										<h3 class="card-title">Interactive Demo Tooltip</h3>
										<div class="bd-example tooltip-demo">
											<div class="bd-example-tooltips">
												<div class="row text-center">
													<div class="col-md-6 col-xl-3 mt-2 mb-2">
														<button type="button" class="btn btn-green" data-toggle="tooltip" data-placement="top" title="Tooltip on top">Tooltip on top</button>
													</div>
													<div class="col-md-6 col-xl-3 mt-2 mb-2">
														<button type="button" class="btn btn-cyan" data-toggle="tooltip" data-placement="right" title="Tooltip on right">Tooltip on right</button>
													</div>
													<div class="col-md-6 col-xl-3 mt-2 mb-2">
														<button type="button" class="btn btn-pink" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">Tooltip on bottom</button>
													</div>
													<div class="col-md-6 col-xl-3 mt-2 mb-2">
														<button type="button" class="btn btn-teal" data-toggle="tooltip" data-placement="left" title="Tooltip on left">Tooltip on left</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!--col end-->
							<div class="col-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Basic Popover</h3>
									</div>
									<div class="card-body">
										<div class="row text-center">
											<div class="col-sm-6 col-lg-3 mt-2 mb-2">
												<button type="button" class="btn btn-primary" data-container="body" data-toggle="popover" data-popover-color="default" data-placement="top" title="Popover top" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
													Popover top
												</button>
											</div><!-- col-3 -->
											<div class="col-sm-6 col-lg-3 mt-2 mb-2 ">
												<button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover" data-popover-color="default" data-placement="bottom" title="Popover bottom" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
													Popover bottom
												</button>
											</div><!-- col-3 -->
											<div class="col-sm-6 col-lg-3 mt-2 mb-2">
												<button type="button" class="btn btn-danger" data-container="body" data-toggle="popover" data-popover-color="default" data-placement="right" title="Popover right" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
													Popover right
												</button>
											</div><!-- col-3 -->
											<div class="col-sm-6 col-lg-3 mt-2 mb-2">
												<button type="button" class="btn btn-success" data-container="body" data-toggle="popover" data-popover-color="default" data-placement="left" title="Popover left" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
													Popover left
												</button>
											</div><!-- col-3 -->
										</div>
									</div>
								</div>
							</div><!--col end-->
						</div>
						<!--row end-->

@endsection('content')

@section('scripts')

		<!--Jquery Sparkline js-->
		<script src="{{URL::asset('assets/plugins/vendors/jquery.sparkline.min.js')}}"></script>

		<!-- Chart Circle js-->
		<script src="{{URL::asset('assets/plugins/vendors/circle-progress.min.js')}}"></script>
		
        <!--Time Counter js-->
		<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>
		
        <!-- Popover js-->
        <script src="{{URL::asset('assets/js/popover.js')}}"></script>

@endsection