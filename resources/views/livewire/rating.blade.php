@extends('layouts.app')

@section('styles')

		<!---Font icons css-->
		<link href="{{URL::asset('assets/plugins/webfonts/plugin.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('assets/plugins/webfonts/icons.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('assets/plugins/iconfonts/icons.css')}}" rel="stylesheet" />
		<link  href="{{URL::asset('assets/fonts/fonts/font-awesome.min.css')}}" rel="stylesheet">

		<!-- INTERNAl Themes  css-->
		<link rel="stylesheet" href="{{URL::asset('assets/plugins/rating/dist/themes/themes.css')}}">
		<link rel="stylesheet" href="{{URL::asset('assets/plugins/rating/css/examples.css')}}">

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Apps</a></li>
								<li class="breadcrumb-item active" aria-current="page">Rating</li>
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

						<!-- Row -->
						<div class="row">
							<div class="col-md-12 col-lg-6 col-xl-4">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Fractional Star rating</h3>
									</div>
									<div class="card-body">
										<div class="star-ratings mt-5 mb-5">
											<div class="stars stars-example-fontawesome-o">
												<select id="example-fontawesome-o" name="rating" data-current-rating="3.6" autocomplete="off">
													<option value="0">0</option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6 col-xl-4">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Font Awesome Star Rating</h3>
									</div>
									<div class="card-body text-center">
										<div class="star-ratings start-ratings-main clearfix  mt-5 mb-5">
											<div class="stars stars-example-fontawesome">
												<select id="example-fontawesome" name="rating" autocomplete="off">
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6 col-xl-4">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Bar 1/10 Rating</h3>
									</div>
									<div class="card-body">
										<div class="box  box-example-1to10">
											<div class="box-body">
												<select id="example-1to10" name="rating" autocomplete="off">
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7" selected="selected">7</option>
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Opinion rating</h3>
									</div>
									<div class="card-body">
										<div class="box box-example-movie">
											<div class="box-body">
												<select id="example-movie" name="rating" autocomplete="off">
													<option value="Bad">Bad</option>
													<option value="Mediocre">Mediocre</option>
													<option value="Good" selected="selected">Good</option>
													<option value="Awesome">Awesome</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6 col-xl-4">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Square Rating</h3>
									</div>
									<div class="card-body">
										<div class="box box-example-square">
											<div class="box-body">
												<select id="example-square" name="rating" autocomplete="off">
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6 col-xl-4">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Grade Rating</h3>
									</div>
									<div class="card-body">
										<div class="box  box-example-pill">
											<div class="box-body">
												<select id="example-pill" name="rating" autocomplete="off">
													<option value="A">A</option>
													<option value="B">B</option>
													<option value="C">C</option>
													<option value="D">D</option>
													<option value="E">E</option>
													<option value="F">F</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6 col-xl-4">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Horizontal Bar Rating</h3>
									</div>
									<div class="card-body">
										<div class="box box-large box-example-horizontal">
											<div class="box-body">
												<select id="example-horizontal" name="rating" autocomplete="off">
													<option value="10">10</option>
													<option value="9">9</option>
													<option value="8">8</option>
													<option value="7">7</option>
													<option value="6">6</option>
													<option value="5">5</option>
													<option value="4">4</option>
													<option value="3">3</option>
													<option value="2">2</option>
													<option value="1" selected="selected">1</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Row -->

@endsection('content')

@section('scripts')

		<!--Jquery Sparkline js-->
		<script src="{{URL::asset('assets/plugins/vendors/jquery.sparkline.min.js')}}"></script>

		<!-- Chart Circle js-->
		<script src="{{URL::asset('assets/plugins/vendors/circle-progress.min.js')}}"></script>
		
        <!--Time Counter js-->
		<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>
		
        <!-- INTERNAL Rating js-->
		<script src="{{URL::asset('assets/plugins/rating/jquery.rating-stars.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/rating/jquery.barrating.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/rating/js/examples.js')}}"></script>

@endsection