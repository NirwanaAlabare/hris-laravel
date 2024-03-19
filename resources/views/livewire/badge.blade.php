@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Elements</a></li>
								<li class="breadcrumb-item active" aria-current="page">Badges</li>
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
							<div class="col-md-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="mb-0 card-title">Badges</h3>
									</div>
									<div class="card-body">
										<span class="badge badge-default mr-1 mb-1 mt-1">Default</span>
										<span class="badge badge-primary  mr-1 mb-1 mt-1">Primary</span>
										<span class="badge badge-success  mr-1 mb-1 mt-1">Success</span>
										<span class="badge badge-danger  mr-1 mb-1 mt-1">Danger</span>
										<span class="badge badge-info  mr-1 mb-1 mt-1">Info</span>
										<span class="badge badge-warning  mr-1 mb-1 mt-1">Warning</span>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="mb-0 card-title">Pill Badges</h3>
									</div>
									<div class="card-body">
										<span class="badge badge-pill badge-default mr-1 mb-1 mt-1">Default</span>
										<span class="badge badge-pill badge-primary mr-1 mb-1 mt-1">Primary</span>
										<span class="badge badge-pill badge-success mr-1 mb-1 mt-1">Success</span>
										<span class="badge badge-pill badge-danger mr-1 mb-1 mt-1">Danger</span>
										<span class="badge badge-pill badge-info mr-1 mb-1 mt-1">Info</span>
										<span class="badge badge-pill badge-warning mr-1 mb-1 mt-1">Warning</span>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-xl-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="mb-0 card-title">Buttons with Badges</h3>
									</div>
									<div class="card-body ">
										<button type="button" class="btn btn-primary mt-1 mb-1 mr-3">
											<span>Notifications</span>
											<span class="badge badge-white">2</span>
										</button>
										<button type="button" class="btn btn-secondary  mt-1 mb-1 mr-3">
											<span>Notifications</span>
											<span class="badge badge-dark">2</span>
										</button>
										<button type="button" class="btn btn-success  mt-1 mb-1 mr-3">
											<span>Notifications</span>
											<span class="badge badge-info">2</span>
										</button>
										<button type="button" class="btn btn-info  mt-1 mb-1 mr-3">
											<span>Notifications</span>
											<span class="badge badge-danger">2</span>
										</button>
										<button type="button" class="btn btn-warning mt-1 mb-1 mr-3">
											<span>Notifications</span>
											<span class="badge badge-primary">2</span>
										</button>
										<button type="button" class="btn btn-danger  mt-1 mb-1 mr-3">
											<span>Notifications</span>
											<span class="badge badge-info">2</span>
										</button>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12  col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Simple Badges</h3>
									</div>
									<div class="card-body">
										<h1>Heading 01 <span class="badge badge-default">New</span></h1>
										<h2>Heading 02 <span class="badge badge-default">New</span></h2>
										<h3>Heading 03 <span class="badge badge-default">New</span></h3>
										<h4>Heading 04 <span class="badge badge-default">New</span></h4>
										<h5>Heading 05 <span class="badge badge-default">New</span></h5>
										<h6>Heading 06 <span class="badge badge-default">New</span></h6>
									</div>
								</div>
							</div>
							<div class="col-md-12  col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Colored Badges</h3>
									</div>
									<div class="card-body">
										<h1 class="text-blue">Heading 01 <span class="badge badge-primary">New</span></h1>
										<h2  class="text-red">Heading 02  <span class="badge badge-danger">New</span></h2>
										<h3  class="text-yellow">Heading 03 <span class="badge badge-warning">New</span></h3>
										<h4  class="text-green">Heading 04 <span class="badge badge-success">New</span></h4>
										<h5  class="text-info">Heading 05  <span class="badge badge-info">New</span></h5>
										<h6  class="text-secondary">Heading 06 <span class="badge badge-secondary">New</span></h6>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12  col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Card Model Badges</h3>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-xs-12 col-sm-6 col-lg-4">
												<div class="offer offer-default">
													<div class="shape">
														<div class="shape-text">
															top
														</div>
													</div>
													<div class="offer-content">
														<h3 class="lead">
															Default badge
														</h3>
														<p class="mb-0">
															And a little description.
															<br> and so one
														</p>
													</div>
												</div>
											</div>
											<div class="col-xs-12 col-sm-6 col-lg-4">
												<div class="offer offer-success">
													<div class="shape">
														<div class="shape-text">
															top
														</div>
													</div>
													<div class="offer-content">
														<h3 class="lead">
															Success badge
														</h3>
														<p class="mb-0">
															And a little description.
															<br> and so one
														</p>
													</div>
												</div>
											</div>
											<div class="col-xs-12 col-sm-6 col-lg-4">
												<div class="offer offer-radius offer-primary">
													<div class="shape">
														<div class="shape-text">
															top
														</div>
													</div>
													<div class="offer-content">
														<h3 class="lead">
															Primary badge
														</h3>
														<p class="mb-0">
															And a little description.
															<br> and so one
														</p>
													</div>
												</div>
											</div>
											<div class="col-xs-12 col-sm-6 col-lg-4">
												<div class="offer offer-info">
													<div class="shape">
														<div class="shape-text">
															top
														</div>
													</div>
													<div class="offer-content">
														<h3 class="lead">
															Info Badge
														</h3>
														<p class="mb-0">
															And a little description.
															<br> and so one
														</p>
													</div>
												</div>
											</div>
											<div class="col-xs-12 col-sm-6 col-lg-4">
												<div class="offer offer-warning">
													<div class="shape">
														<div class="shape-text">
															top
														</div>
													</div>
													<div class="offer-content">
														<h3 class="lead">
															Warning badge
														</h3>
														<p class="mb-0">
															And a little description.
															<br> and so one
														</p>
													</div>
												</div>
											</div>
											<div class="col-xs-12 col-sm-6 col-lg-4">
												<div class="offer offer-radius offer-danger">
													<div class="shape">
														<div class="shape-text">
															top
														</div>
													</div>
													<div class="offer-content">
														<h3 class="lead">
															Danger Badge
														</h3>
														<p class="mb-0">
															And a little description.
															<br> and so one
														</p>
													</div>
												</div>
											</div>
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

@endsection