@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Elements</a></li>
								<li class="breadcrumb-item active" aria-current="page">Breadcrumbs</li>
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
							<div class="col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Simple Breadcrumbs</h3>
									</div>
									<div class="card-body">
										<ol class="breadcrumb1">
											<li class="breadcrumb-item1 active">Home</li>
											<li class="breadcrumb-item1 active">About</li>
										</ol>
										<ol class="breadcrumb1">
											<li class="breadcrumb-item1"><a href="#">Home</a></li>
											<li class="breadcrumb-item1 active">Library</li>
										</ol>
										<ol class="breadcrumb1">
											<li class="breadcrumb-item1"><a href="#">Home</a></li>
											<li class="breadcrumb-item1"><a href="#">Library</a></li>
											<li class="breadcrumb-item1 active">Data</li>
										</ol>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<div class="row">
							<div class="col-lg-6">
								<div class="card">
									<div class="card-header  pb-0">
										<h3 class="card-title">Breadcrumbs-Center align</h3>
									</div>
									<div class="card-body">
										<div class="breadcrumb-3">
											<ol class="breadcrumb1">
												<li class="breadcrumb-item1"><a href="#">Home</a></li>
												<li class="breadcrumb-item1 active">about</li>
											</ol>
										</div>
										<div class="breadcrumb-4">
											<ol class="breadcrumb1">
												<li class="breadcrumb-item1"><a href="#">Home</a></li>
												<li class="breadcrumb-item1 active">Library</li>
											</ol>
										</div>
										<div class="breadcrumb-5">
											<ol class="breadcrumb1 mb-0">
												<li class="breadcrumb-item1"><a href="#">Home</a></li>
												<li class="breadcrumb-item1"><a href="#">Library</a></li>
												<li class="breadcrumb-item1 active">Data</li>
											</ol>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="card">
									<div class="card-header  pb-0">
										<h3 class="card-title">Breadcrumbs-Right align</h3>
									</div>
									<div class="card-body">
										<div class="breadcrumb-1">
											<ol class="breadcrumb1">
												<li class="breadcrumb-item1"><a href="#">Home</a></li>
												<li class="breadcrumb-item1 active">about</li>
											</ol>
										</div>
										<div class="breadcrumb-2">
											<ol class="breadcrumb1">
												<li class="breadcrumb-item1"><a href="#">Home</a></li>
												<li class="breadcrumb-item1"><a href="#">Library</a></li>
											</ol>
										</div>
										<div class="breadcrumb-6">
											<ol class="breadcrumb1 mb-0">
												<li class="breadcrumb-item1"><a href="#">Home</a></li>
												<li class="breadcrumb-item1"><a href="#">Library</a></li>
												<li class="breadcrumb-item1 active">Data</li>
											</ol>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header  pb-0">
										<h3 class="card-title">Color Breadcrumbs</h3>
									</div>
									<div class="card-body">
										<ol class="breadcrumb1 bg-blue">
											<li class="breadcrumb-item1 active text-white">Home</li>
											<li class="breadcrumb-item1 active text-white">About</li>
										</ol>
										<ol class="breadcrumb1 bg-purple">
											<li class="breadcrumb-item1"><a href="#" class="text-white">Home</a></li>
											<li class="breadcrumb-item1 active text-white">Library</li>
										</ol>
										<ol class="breadcrumb1 bg-orange">
											<li class="breadcrumb-item1"><a href="#" class="text-white">Home</a></li>
											<li class="breadcrumb-item1"><a href="#" class="text-white">Library</a></li>
											<li class="breadcrumb-item1 active text-white">Data</li>
										</ol>
									</div>
								</div>
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Color Breadcrumbs</h3>
									</div>
									<div class="card-body">
										<ol class="breadcrumb breadcrumb-arrow mt-3">
											<li><a href="#">Home</a></li>
											<li class="active"><span>Data</span></li>
										</ol>
										<ol class="breadcrumb breadcrumb-arrow mt-3">
											<li><a href="#">Home</a></li>
											<li><a href="#">Library</a></li>
											<li class="active"><span>Data</span></li>
										</ol>
										<ol class="breadcrumb breadcrumb-arrow mt-3">
											<li><a href="#">Home</a></li>
											<li><a href="#">Library</a></li>
											<li><a href="#">Elements</a></li>
											<li class="active"><span>Data</span></li>
										</ol>
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