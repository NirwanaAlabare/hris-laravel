@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Apps</a></li>
								<li class="breadcrumb-item active" aria-current="page">Time Line</li>
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
							<div class="col-md-12 m-b-30">
								<ul class="timelineleft pb-5">
									<li class="timeleft-label"><span class="bg-danger">01 Mar. 2019</span></li>
									<li>
										<i class="fa fa-envelope bg-primary"></i>
										<div class="timelineleft-item">
											<span class="time"><i class="fa fa-clock-o text-danger"></i> 12:05</span>
											<h3 class="timelineleft-header"><a href="#">Support Team</a> <span class="text-muted">sent you an email</span></h3>
											<div class="timelineleft-body">
												Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
												weebly ning heekya handango imeem plugg dopplr jibjab, movity
												jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
												quora plaxo ideeli hulu weebly balihoo...
											</div>
											<div class="timelineleft-footer">
												<a class="btn btn-primary text-white btn-sm">Read more</a>
												<a class="btn btn-secondary text-white btn-sm ">Delete</a>
											</div>
										</div>
									</li>
									<li>
										<i class="fa fa-user bg-secondary"></i>
										<div class="timelineleft-item">
											<span class="time"><i class="fa fa-clock-o text-danger"></i> 5 mins ago</span>
											<h3 class="timelineleft-header border-0"><a href="#">Sarah Young</a> <span class="text-muted">accepted your friend request</span></h3>
										</div>
									</li>
									<li>
										<i class="fa fa-comments bg-warning"></i>
										<div class="timelineleft-item">
											<span class="time"><i class="fa fa-clock-o text-danger"></i> 27 mins ago</span>
											<h3 class="timelineleft-header"><a href="#">Jay White</a> <span class="text-muted">commented on your post</span></h3>
											<div class="timelineleft-body">
												Take me to your leader!
												Switzerland is small and neutral!
												We are more like Germany, ambitious and misunderstood!
											</div>
											<div class="timelineleft-footer">
												<a class="btn btn-info text-white btn-flat btn-sm">View comment</a>
											</div>
										</div>
									</li>
									<li class="timeleft-label">
										<span class="bg-success"> 25 Feb. 2019</span>
									</li>
									<li>
										<i class="fa fa-camera bg-orange"></i>
										<div class="timelineleft-item">
											<span class="time"><i class="fa fa-clock-o text-danger"></i> 2 days ago</span>
											<h3 class="timelineleft-header"><a href="#">Mina Lee</a><span class="text-muted">uploaded new photos</span></h3>
											<div class="timelineleft-body">
												<img src="{{URL::asset('assets/images/photos/6.jpg')}}" alt="..." class="margin mt-2 mb-2">
												<img src="{{URL::asset('assets/images/photos/10.jpg')}}" alt="..." class="margin mt-2 mb-2 ">
												<img src="{{URL::asset('assets/images/photos/3.jpg')}}" alt="..." class="margin mt-2 mb-2 ">
												<img src="{{URL::asset('assets/images/photos/4.jpg')}}" alt="..." class="margin mt-2 mb-2">
											</div>
										</div>
									</li>
									<li>
										<i class="fa fa-video-camera bg-pink"></i>
										<div class="timelineleft-item">
											<span class="time"><i class="fa fa-clock-o text-danger"></i> 5 days ago</span>
											<h3 class="timelineleft-header"><a href="#">Mr. Doe</a><span class="text-muted"> shared a video</span></h3>
											<div class="timelineleft-body">
												<div class="embed-responsive embed-responsive-16by9 w-75" >
													<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/tMWkeBIohBs"
													 allowfullscreen></iframe>
												</div>
											</div>
											<div class="timelineleft-footer">
												<a href="#" class="btn btn-sm bg-warning text-white">See comments</a>
											</div>
										</div>
									</li>
									<li>
										<i class="fa fa-clock-o bg-success pb-3"></i>
									</li>
								</ul>
							</div><!-- col -->
						</div><!-- row -->

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