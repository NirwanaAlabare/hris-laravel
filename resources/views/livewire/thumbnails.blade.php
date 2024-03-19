@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Elements</a></li>
								<li class="breadcrumb-item active" aria-current="page">Thumbnails</li>
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
										<h3 class="card-title">Basic Thumbnails</h3>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-6 col-md-3">
												<a href="#" class="thumbnail mb-md-0">
													<img src="{{URL::asset('assets/images/photos/1.jpg')}}" alt="thumb1" class="thumbimg">
												</a>
											</div>
											<div class="col-6 col-md-3">
												<a href="#" class="thumbnail mb-md-0">
													<img src="{{URL::asset('assets/images/photos/2.jpg')}}" alt="thumb1" class="thumbimg">
												</a>
											</div>
											<div class="col-6 col-md-3">
												<a href="#" class="thumbnail mb-0">
													<img src="{{URL::asset('assets/images/photos/3.jpg')}}" alt="thumb1" class="thumbimg">
												</a>
											</div>
											<div class="col-6 col-md-3">
												<a href="#" class="thumbnail mb-0">
													<img src="{{URL::asset('assets/images/photos/5.jpg')}}" alt="thumb1" class="thumbimg">
												</a>
											</div>
										</div>
									</div>
								</div>
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Basic Thumbnails</h3>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-lg-4 col-md-4">
												<div class="thumbnail mb-md-0">
													<img src="{{URL::asset('assets/images/photos/5.jpg')}}" alt="ALT NAME" class="img-responsive" />
													<div class="caption">
														<h4>Thumbnail label</h4>
														<p>Description</p>
														<p><a href="" class="btn btn-primary btn-block">Open</a>
														</p>
													</div>
												</div>
											</div>
											<div class="col-lg-4 col-md-4">
												<div class="thumbnail mb-md-0">
													<img src="{{URL::asset('assets/images/photos/2.jpg')}}" alt="ALT NAME" class="img-responsive" />
													<div class="caption">
														<h4>Thumbnail label</h4>
														<p>Description</p>
														<p><a href="" class="btn btn-primary btn-block">Open</a>
														</p>
													</div>
												</div>
											</div>
											<div class="col-lg-4 col-md-4">
												<div class="thumbnail mb-0">
													<img src="{{URL::asset('assets/images/photos/3.jpg')}}" alt="ALT NAME" class="img-responsive" />
													<div class="caption">
														<h4>Thumbnail label</h4>
														<p>Description</p>
														<p><a href="" class="btn btn-primary btn-block">Open</a>
														</p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Custom content Thumbnails</h3>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 col-lg-4">
												<div class="thumbnail mb-lg-0">
													<a href="#">
														<img src="{{URL::asset('assets/images/photos/19.jpg')}}" alt="thumb1" class="thumbimg">
													</a>
													<div class="caption">
														<h4><strong>Thumbnail label</strong></h4>
														<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia.</p>
														<p>
															<a href="#" class="btn btn-primary mt-1 mb-1" role="button">Button</a>
															<a href="#" class="btn btn-success mt-1 mb-1" role="button">Button</a>
														</p>
													</div>
												</div>
											</div>
											<div class="col-md-12 col-lg-4">
												<div class="thumbnail mb-lg-0">
													<a href="#">
														<img src="{{URL::asset('assets/images/photos/20.jpg')}}" alt="thumb1" class="thumbimg">
													</a>
													<div class="caption">
														<h4><strong>Thumbnail label</strong></h4>
														<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia.</p>
														<p>
															<a href="#" class="btn btn-primary mt-1 mb-1" role="button">Button</a>
															<a href="#" class="btn btn-success mt-1 mb-1" role="button">Button</a>
														</p>
													</div>
												</div>
											</div>
											<div class="col-md-12 col-lg-4">
												<div class="thumbnail mb-0">
													<a href="#">
														<img src="{{URL::asset('assets/images/photos/21.jpg')}}" alt="thumb1" class="thumbimg">
													</a>
													<div class="caption">
														<h4><strong>Thumbnail label</strong></h4>
														<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia.</p>
														<p>
															<a href="#" class="btn btn-primary mt-1 mb-1" role="button">Button</a>
															<a href="#" class="btn btn-success mt-1 mb-1" role="button">Button</a>
														</p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Custom content Thumbnails</h3>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 col-lg-6 col-xl-3">
												<div class="thumbnail mb-xl-0">
													<a href="#">
														<img src="{{URL::asset('assets/images/photos/22.jpg')}}" alt="thumb1" class="thumbimg">
													</a>
													<div class="caption">
														<h4><strong>Thumbnail label</strong></h4>
														<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia.</p>
													</div>
												</div>
											</div>
											<div class="col-md-12 col-lg-6 col-xl-3">
												<div class="thumbnail mb-xl-0">
													<a href="#">
														<img src="{{URL::asset('assets/images/photos/23.jpg')}}" alt="thumb1" class="thumbimg">
													</a>
													<div class="caption">
														<h4><strong>Thumbnail label</strong></h4>
														<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia.</p>
													</div>
												</div>
											</div>
											<div class="col-md-12 col-lg-6 col-xl-3">
												<div class="thumbnail mb-lg-0">
													<div class="caption">
														<h4><strong>Thumbnail label</strong></h4>
														<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia.</p>
													</div>
													<a href="#">
														<img src="{{URL::asset('assets/images/photos/24.jpg')}}" alt="thumb1" class="thumbimg">
													</a>
												</div>
											</div>
											<div class="col-md-12 col-lg-6 col-xl-3">
												<div class="thumbnail mb-0">
													<div class="caption">
														<h4><strong>Thumbnail label</strong></h4>
														<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia.</p>
													</div>
													<a href="#">
														<img src="{{URL::asset('assets/images/photos/25.jpg')}}" alt="thumb1" class="thumbimg">
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Video List Thumbnail</h3>
									</div>
									<div class="card-body">
										<div class="">
											<ul class="list-unstyled video-list-thumbs row mb-0">
												<li class="col-sm-12 col-lg-3 col-md-6 border-0 mb-lg-0">
													<a href="https://www.youtube.com/embed/f3NWvUV8MD8" title="sed do eiusmod tempor incidi dunt ut labore et dolore magna">
														<img src="{{URL::asset('assets/images/photos/24.jpg')}}" alt="Barca" class="img-responsive"/>

														<span class="fa fa-play-circle-o"></span>
														<span class="duration">06:28</span>
													</a>
												</li>
												<li class="col-sm-12 col-lg-3 col-md-6 border-0 mb-lg-0">
													<a href="https://www.youtube.com/embed/f3NWvUV8MD8" title="sed do eiusmod tempor incidi dunt ut labore et dolore magna">
														<img src="{{URL::asset('assets/images/photos/25.jpg')}}" alt="Barca" class="img-responsive" />
														<span class="fa fa-play-circle-o"></span>
														<span class="duration">06:28</span>
													</a>
												</li>
												<li class="ccol-sm-12 col-lg-3 col-md-6 border-0 mb-md-0">
													<a href="https://www.youtube.com/embed/f3NWvUV8MD8" title="sed do eiusmod tempor incidi dunt ut labore et dolore magna">
														<img src="{{URL::asset('assets/images/photos/20.jpg')}}" alt="Barca" class="img-responsive"  />
														<span class="fa fa-play-circle-o"></span>
														<span class="duration">06:28</span>
													</a>
												</li>
												<li class="col-sm-12 col-lg-3 col-md-6 border-0 mb-0">
													<a href="https://www.youtube.com/embed/f3NWvUV8MD8" title="sed do eiusmod tempor incidi dunt ut labore et dolore magna">
														<img src="{{URL::asset('assets/images/photos/22.jpg')}}" alt="Barca" class="img-responsive" />
														<span class="fa fa-play-circle-o"></span>
														<span class="duration">06:28</span>
													</a>
												</li>
											</ul>
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