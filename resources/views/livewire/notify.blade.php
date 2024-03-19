@extends('layouts.app')

@section('styles')

	<!-- Notifications  css -->
	<link href="{{URL::asset('assets/plugins/notify-growl/css/jquery.growl.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/notify-growl/css/notifIt.css')}}" rel="stylesheet" />

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Apps</a></li>
								<li class="breadcrumb-item active" aria-current="page">Notifications</li>
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
							<div class="col-md-12 col-xl-12 col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Alerts Styles Notifications</h3>
									</div>
									<div class="card-body text-center">
										<div class="example">
											<div class="btn-list">
												<button onclick="not1()" class="btn btn-success">success</button>
												<button onclick="not2()" class="btn btn-danger">Center</button>
												<button onclick="not3()" class="btn btn-warning">Left</button>
												<button onclick="not4()" class="btn btn-info">Center Info</button>
												<button onclick="not5()" class="btn btn-danger">Center Error</button>
												<button onclick="not6()" class="btn btn-warning">Center Warning</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xl-12 col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Side Alerts Notifications</h3>
									</div>
									<div class="card-body text-center">
										<div class="example">
											<div class="btn-list">
												<a href="#" class="btn btn-success notice">success</a>
												<a href="#" class="btn btn-warning warning">Warning</a>
												<a href="#" class="btn btn-danger error">Danger</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xl-12 col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Alerts Popovers</h3>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-4 mt-2 mb-2">
												<button type="button" class="btn btn-success btn-block " data-container="body" data-toggle="popover" data-popover-color="popsuccess" data-placement="bottom" title="alert sucess" data-content="Well done! You successfully read this important alert message..">
													Show success
												</button>
											</div>
											<div class="col-md-4 mt-2 mb-2">
												<button type="button" class="btn btn-info btn-block" data-container="body" data-toggle="popover" data-popover-color="popinfo" data-placement="top" title="alert info" data-content="Heads up! This alert needs your attention, but it's not super important...">
													Show info
												</button>
											</div>
											<div class="col-md-4 mt-2 mb-2">
												<button type="button" class="btn btn-block btn-warning " data-container="body" data-toggle="popover" data-popover-color="popwarning" data-placement="bottom" title="alert warning" data-content="Warning! Best check yo self, you're not looking too good..">
													Show warning
												</button>
											</div>
											<div class="col-md-4 mt-2 mb-2">
												<button type="button" class="btn btn-block btn-secondary" data-container="body" data-toggle="popover" data-popover-color="popsecondary" data-placement="top" title="alert secondary" data-content="Error! Please Check u r emial id..">
													Show secondary
												</button>
											</div>
											<div class="col-md-4 mt-2 mb-2">
												<button type="button" class="btn btn-block btn-danger" data-container="body" data-toggle="popover" data-popover-color="popdanger" data-placement="bottom" title="alert danger" data-content="Oh snap! Change a few things up and try submitting again.">
												Show danger
												</button>
											</div>
											<div class="col-md-4 mt-2 mb-2">
												<button type="button" class="btn btn-block btn-primary" data-container="body"  data-toggle="popover" data-popover-color="pop-primary" data-placement="top" title="alert primary" data-content="Cool! This alert will close in 3 seconds. The data-delay attribute is in milliseconds.">
													Show primary
												</button>
											</div>
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
		
        <!-- Popover js -->
		<script src="{{URL::asset('assets/js/popover.js')}}"></script>

		<!-- Notifications js -->
		<script src="{{URL::asset('assets/plugins/notify-growl/js/rainbow.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/notify-growl/js/sample.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/notify-growl/js/jquery.growl.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/notify-growl/js/notifIt.js')}}"></script>

@endsection