@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Elements</a></li>
								<li class="breadcrumb-item active" aria-current="page">Jumbotron</li>
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
										<h3 class="card-title">Simple Jumbotron</h3>
									</div>
									<div class="card-body">
										<div class="jumbotron">
											<h1 class="display-3">Hello, world!</h1>
											<p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
											<hr class="my-4">
											<p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
											<p class="lead m-0">
												<a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row close -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Jumbotron</h3>
									</div>
									<div class="card-body">
										<div class="jumbotron ">
											<div class="container">
												<h1 class="display-3">jumbotron</h1>
												<p class="lead m-0">This is a modified jumbotron that occupies the entire horizontal space of its parent.</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row close -->

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