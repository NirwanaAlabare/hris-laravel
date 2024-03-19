@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Pages</a></li>
								<li class="breadcrumb-item active" aria-current="page">About</li>
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

                        <!-- row open -->
						<div class="row">
							<div class="col-xl-4 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Why Choose US?</h3>
									</div>
									<div class="card-body">
										<p class="">"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. </p>
										<p class="mb-0"> Excepteur sint occaecat cupidatat non proident teachings of the great explorer of the truth, the master-builder of human happiness."</p>
									</div>
								</div>
							</div>

							<div class="col-xl-4 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Our Expertise</h3>
									</div>
									<div class="card-body">
										<div class="mb-4">
											<p class="mb-2">Web Designers<span class="float-right text-muted">80%</span></p>
											<div class="progress progress-sm mb-3">
												<div class="progress-bar progress-bar-striped progress-bar-animated bg-teal w-80"></div>
											</div>
										</div>
										<div class="mb-4">
											<p class="mb-2">App Designers<span class="float-right text-muted">46%</span></p>
											<div class="progress progress-sm mb-3">
												<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary w-45"></div>
											</div>
										</div>
										<div class="mb-0">
											<p class="mb-2">Web Development<span class="float-right text-muted">76%</span></p>
											<div class="progress progress-sm mb-0">
												<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning w-75"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-4 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Career Info</h3>
									</div>
									<div class="card-body">
										<p class="">"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. </p>
										<p class="mb-0"> Excepteur sint occaecat cupidatat non proident teachings of the great explorer of the truth, the master-builder of human happiness."</p>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xl-3 col-lg-6 col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="abouts text-center">
											<h4 class="font-weight-semibold">IT strategy</h4>
											<p class="mb-3">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and by desire.</p>
											<a href="#" class="btn btn-outline-primary btn-pill">View More</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-6 col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="abouts text-center">
											<h4 class="font-weight-semibold">Development</h4>
											<p class="mb-3">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and by desire.</p>
											<a href="#" class="btn btn-outline-primary btn-pill">View More</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-6 col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="abouts text-center">
											<h4 class="font-weight-semibold">Products</h4>
											<p class="mb-3">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and by desire.</p>
											<a href="#" class="btn btn-outline-primary btn-pill">View More</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-6 col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="abouts text-center">
											<h4 class="font-weight-semibold">Quality</h4>
											<p class="mb-3">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and by desire.</p>
											<a href="#" class="btn btn-outline-primary btn-pill">View More</a>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-sm-12 col-lg-4 col-xl-4">
								<div class="card text-center">
									<img src="{{URL::asset('assets/images/photos/19.jpg')}}" alt="img">
									<div class="card-body">
										<h4 class="font-weight-semibold mb-3">Company history</h4>
										<p class="text-muted">I must explain to you how all this mistaken idea of denouncing pleasure and you a complete account of the system</p>
										<a href="#" class=" mt-3 btn btn-purple">Read More</a>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-sm-12 col-lg-4 col-xl-4">
								<div class="card text-center">
									<img src="{{URL::asset('assets/images/photos/20.jpg')}}" alt="img">
									<div class="card-body">
										<h4 class="font-weight-semibold mb-3">About Team</h4>
										<p class="text-muted">I must explain to you how all this mistaken idea of denouncing pleasure and you a complete account of the system</p>
										<a href="#" class=" mt-3 btn btn-secondary">Read More</a>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-sm-12 col-lg-4 col-xl-4">
								<div class="card text-center">
									<img src="{{URL::asset('assets/images/photos/21.jpg')}}" alt="img">
									<div class="card-body">
										<h4 class="font-weight-semibold mb-3">Company growth</h4>
										<p class="text-muted">I must explain to you how all this mistaken idea of denouncing pleasure and you a complete account of the system</p>
										<a href="#" class=" mt-3 btn btn-primary">Read More</a>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-3 ">
								<div class="card">
									<div class="card-body">
										<div class="item-box text-center">
											<div class="stamp text-center stamp-lg bg-primary mb-4 "><i class="fa fa-users"></i></div>
											<div class="item-box-wrap">
												<h5 class="mb-2 font-weight-semibold">Creative solutions</h5>
												<p class="text-muted mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<div class="item-box text-center">
											<div class="stamp text-center stamp-lg bg-success mb-4"><i class="fa fa-clock-o"></i></div>
											<div class="item-box-wrap">
												<h5 class="mb-2 font-weight-semibold">Trace your time</h5>
												<p class="text-muted mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<div class="item-box text-center">
											<div class="stamp text-center stamp-lg bg-info mb-4"><i class="fa fa-building-o"></i></div>
											<div class="item-box-wrap">
												<h5 class="mb-2 font-weight-semibold">Business FrameWork</h5>
												<p class="text-muted mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<div class="item-box text-center">
											<div class="stamp text-center stamp-lg bg-danger mb-4"><i class="fa fa-share"></i></div>
											<div class="item-box-wrap">
												<h5 class="mb-2 font-weight-semibold">Shares</h5>
												<p class="text-muted mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
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