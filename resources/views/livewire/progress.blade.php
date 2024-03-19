@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Advanced UI</a></li>
								<li class="breadcrumb-item active" aria-current="page">Progress</li>
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
							<div class="col-md-6 col-sm-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Basic Progress</h3>
									</div>
									<div class="card-body">
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-default w-1"></div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-primary w-20"></div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-primary w-40"></div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-primary w-60"></div>
										</div>
										<div class="progress progress-md">
											<div class="progress-bar bg-primary w-80"></div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-md-6 col-sm-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Contextual Progress</h3>
									</div>
									<div class="card-body">
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-pink w-1"></div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-green w-20"></div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-yellow w-40"></div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-blue w-60"></div>
										</div>
										<div class="progress progress-md ">
											<div class="progress-bar bg-orange w-80"></div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-md-6 col-sm-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Basic Progress with label</h3>
									</div>
									<div class="card-body">
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-primary w-1">10%</div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-primary w-20"> 20%</div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-primary w-40"> 40%</div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-primary w-60"> 60%</div>
										</div>
										<div class="progress progress-md">
											<div class="progress-bar bg-primary w-80"> 80%</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-md-6 col-sm-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Contextual Progress with label</h3>
									</div>
									<div class="card-body">
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-pink w-1"> 10%</div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-green w-20">20%</div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-yellow w-40">40%</div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-blue w-60">60%</div>
										</div>
										<div class="progress progress-md ">
											<div class="progress-bar bg-orange w-80"> 80%</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Progress Sizes</h3>
									</div>
									<div class="card-body">
										<div class="progress progress-xs mb-3">
											<div class="progress-bar bg-blue w-30">30%</div>
										</div>
										<div class="progress progress-sm mb-3">
											<div class="progress-bar bg-blue w-60">60%</div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-blue w-70">70%</div>
										</div>
										<div class="progress progress-lg">
											<div class="progress-bar bg-blue w-80">80%</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-md-6 col-sm-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Mixed color Progress with Sizes</h3>
									</div>
									<div class="card-body">
										<div class="progress progress-xs mb-3">
											<div class="progress-bar bg-orange w-1"></div>
											<div class="progress-bar bg-warning w-1"></div>
											<div class="progress-bar bg-info w-1"></div>
										</div>
										<div class="progress progress-sm mb-3">
											<div class="progress-bar bg-pink w-1"></div>
											<div class="progress-bar bg-yellow w-15"></div>
											<div class="progress-bar bg-teal w-15"></div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar bg-pink w-15"></div>
											<div class="progress-bar bg-blue w-20"></div>
											<div class="progress-bar bg-cyan w-30"></div>
										</div>

										<div class="progress progress-lg">
											<div class="progress-bar bg-green w-30"></div>
											<div class="progress-bar bg-pink w-20"></div>
											<div class="progress-bar bg-orange w-40"></div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Striped Progress</h3>
									</div>
									<div class="card-body">
										<div class="progress progress-md mb-3">
											<div class="progress-bar progress-bar-striped progress-bar-animated bg-teal w-15"></div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar progress-bar-striped progress-bar-animated bg-info w-25"></div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar progress-bar-striped progress-bar-animated bg-yellow w-50">50%</div>
										</div>
										<div class="progress progress-md">
											<div class="progress-bar progress-bar-striped progress-bar-animated bg-green w-70">70%</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-md-6 col-sm-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Animated Progress</h3>
									</div>
									<div class="card-body">
										<div class="progress progress-md mb-3">
											<div class="progress-bar progress-bar-indeterminate bg-pink" ></div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar progress-bar-indeterminate bg-cyan"></div>
										</div>
										<div class="progress progress-md mb-3">
											<div class="progress-bar progress-bar-indeterminate bg-teal"></div>
										</div>
										<div class="progress progress-md">
											<div class="progress-bar progress-bar-indeterminate bg-purple"></div>
										</div>
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

@endsection