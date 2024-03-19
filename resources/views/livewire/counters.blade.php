@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Apps</a></li>
								<li class="breadcrumb-item active" aria-current="page">Counters</li>
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
						<div class="row row-cards">
							<div class="col-md-12 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">
											Time Counting From 0
										</div>
									</div>
									<div class="card-body text-center">
										<div class="example">
											<span id="timer-countup" class="h3 text-primary"></span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">
											Time Counting From 60 to 20
										</div>
									</div>
									<div class="card-body text-center">
										<div class="example">
											<span id="timer-countinbetween" class="h3 text-primary"></span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">
											Time 1 minute counter
										</div>
									</div>
									<div class="card-body text-center">
										<div class="example">
											<span id="timer-countercallback" class="h3 text-primary"></span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">
											Time Counting days Limit
										</div>
									</div>
									<div class="card-body text-center">
										<div class="example">
											<span id="timer-outputpattern" class="h3 text-info"></span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">
											Numbers counter
										</div>
									</div>
									<div class="card-body text-center">
										<h2 class="counter text-success">2569</h2>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">
											Numbers counter
										</div>
									</div>
									<div class="card-body text-center">
										<h2 class="counter text-success"> 2,56989.32</h2>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">
											Numbers counter
										</div>
									</div>
									<div class="card-body text-center">
										<h2 class="counter text-success">0.8998</h2>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">
											Day Counter
										</div>
									</div>
									<div class="card-body text-center">
										<div class="count-down row">
											<div class="col-6 col-sm-2 col-md-12 col-xl-2 countdown">
												<span class="days text-primary ">35</span>
												<span class="text-dark">Days</span>
											</div>
											<div class="col-6 col-sm-2 col-md-12 col-xl-2 countdown">
												<span class="hours text-primary">17</span>
												<span class="text-dark">Hours</span>
											</div>

											<div class="col-6 col-sm-2 col-md-12 col-xl-2 countdown">
												<span class="minutes text-primary">50</span>
												<span class="text-dark">Minutes</span>
											</div>
											<div class="col-6 col-sm-2 col-md-12 col-xl-2 countdown">
												<span class="seconds text-primary">39</span>
												<span class="text-dark">Seconds</span>
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

		<!--Counters -->
		<script src="{{URL::asset('assets/plugins/counters/counterup.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/counters/waypoints.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/counters/counters.js')}}"></script>

		<!--Counters js-->
		<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/counters/count-down.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/countdown/moment.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/countdown/moment-timezone.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/countdown/moment-timezone-with-data.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/countdown/countdowntime.js')}}"></script>

@endsection