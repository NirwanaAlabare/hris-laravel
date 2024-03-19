@extends('layouts.app')

@section('styles')

	<!-- Jvectormap css -->
    <link href="{{URL::asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.5.css')}}" rel="stylesheet" />

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Maps</li>
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
							<div class="col-lg-12">
								<div class="card m-b-20">
									<h3 class="card-header pb-0">World Map</h3>
									<div class="card-body">
											<div id="world-map-markers" class="worldh h-400 " ></div>
									</div>
								</div>
							</div><!-- end col -->
						</div>
						<!-- end row -->

						<div class="row">
							<div class="col-lg-6">
								<div class="card m-b-20">
									<h3 class="card-header pb-0">USA Map</h3>
									<div class="card-body">
										<div id="usa" class="stateh w-100 h-400" ></div>
									</div>
								</div>
							</div><!-- end col -->

							<div class="col-lg-6">
								<div class="card m-b-20">
									<h3 class="card-header pb-0">Canada Map</h3>
									<div class="card-body">
										<div id="canada" class="stateh h-400"></div>
									</div>
								</div>
							</div><!-- end col -->

							<div class="col-lg-6">
								<div class="card m-b-20">
									<h3 class="card-header pb-0">UK Map</h3>
									<div class="card-body">
										<div id="uk" class="stateh h-400"></div>
									</div>
								</div>
							</div><!-- end col -->

							<div class="col-lg-6">
								<div class="card m-b-20">
									<h3 class="card-header pb-0">Australia Map</h3>
									<div class="card-body">
										<div id="australia" class="stateh w-100 h-400"></div>
									</div>
								</div>
							</div><!-- end col -->
						</div>
						<!-- end row -->

@endsection('content')

@section('scripts')

		<!--Jquery Sparkline js-->
		<script src="{{URL::asset('assets/plugins/vendors/jquery.sparkline.min.js')}}"></script>

		<!-- Chart Circle js-->
		<script src="{{URL::asset('assets/plugins/vendors/circle-progress.min.js')}}"></script>
		
        <!--Time Counter js-->
		<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>
		
        <!-- Google Maps Plugin -->
		<script src="http://maps.google.com/maps/api/js?key=AIzaSyAykAdIIsNMu0V2wyGOMQcguo8zKngWlyM"></script>
		<script src="{{URL::asset('assets/plugins/maps-google/jquery.googlemap.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/maps-google/map.js')}}"></script>

		<!-- JVectormap js-->
		<script src="{{URL::asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.5.min.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/jvectormap/gdp-data.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/jvectormap/jquery-jvectormap-us-aea-en.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/jvectormap/jquery-jvectormap-uk-mill-en.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/jvectormap/jquery-jvectormap-au-mill.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/jvectormap/jquery-jvectormap-ca-lcc.js')}}"></script>
		<script src="{{URL::asset('assets/js/jvectormap.js')}}"></script>

@endsection