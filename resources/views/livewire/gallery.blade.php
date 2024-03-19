@extends('layouts.app')

@section('styles')

	<!-- Gallery css -->
	<link href="{{URL::asset('assets/plugins/gallery/gallery.css')}}" rel="stylesheet">

@endsection

@section('content')
						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Gallery</li>
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
						<div class="gallery row">
							<div class="col-lg-4 hover15">
								<div class="card shadow overflow-hidden">
									<a href="#" class="big"><img src="{{URL::asset('assets/images/photos/14.jpg')}}" alt="" title="Beautiful Image"></a>
								</div>
							</div>
							<div class="col-lg-4 hover15">
								<div class="card shadow overflow-hidden">
									<a href="#" class="big"><img src="{{URL::asset('assets/images/photos/19.jpg')}}" alt="" title="Beautiful Image"></a>
								</div>
							</div>
							<div class="col-lg-4 hover15">
								<div class="card shadow overflow-hidden">
									<a href="#" class="big"><img src="{{URL::asset('assets/images/photos/20.jpg')}}" alt="" title="Beautiful Image"></a>
								</div>
							</div>
							<div class="col-lg-4 hover15">
								<div class="card shadow overflow-hidden">
									<a href="#" class="big"><img src="{{URL::asset('assets/images/photos/4.jpg')}}" alt="" title="Beautiful Image"></a>
								</div>
							</div>
							<div class="col-lg-4 hover15">
								<div class="card shadow overflow-hidden">
									<a href="#" class="big"><img src="{{URL::asset('assets/images/photos/5.jpg')}}" alt="" title="Beautiful Image"></a>
								</div>
							</div>
							<div class="col-lg-4 hover15">
								<div class="card shadow overflow-hidden">
									<a href="#" class="big"><img src="{{URL::asset('assets/images/photos/6.jpg')}}" alt="" title="Beautiful Image"></a>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- gallery -->
						<div class="demo-gallery card">
							<div class="card-header pb-0">
								<div class="card-title">Light Gallery</div>
							</div>
							<div class="card-body">
								<ul id="lightgallery" class="list-unstyled row">
									<li class="col-xs-6 col-sm-4 col-md-3" data-responsive="{{URL::asset('assets/images/photos/1.jpg')}}" data-src="{{URL::asset('assets/images/photos/1.jpg')}}" data-sub-html="<h4>Gallery Image 1</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>" >
										<a href="">
											<img class="img-responsive" src="{{URL::asset('assets/images/photos/1.jpg')}}" alt="Thumb-1">
										</a>
									</li>
									<li class="col-xs-6 col-sm-4 col-md-3" data-responsive="{{URL::asset('assets/images/photos/2.jpg')}}" data-src="{{URL::asset('assets/images/photos/2.jpg')}}" data-sub-html="<h4>Gallery Image 2</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>" >
										<a href="">
											<img class="img-responsive" src="{{URL::asset('assets/images/photos/2.jpg')}}" alt="Thumb-2">
										</a>
									</li>
									<li class="col-xs-6 col-sm-4 col-md-3" data-responsive="{{URL::asset('assets/images/photos/3.jpg')}}" data-src="{{URL::asset('assets/images/photos/3.jpg')}}" data-sub-html="<h4>Gallery Image 3</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
										<a href="">
											<img class="img-responsive" src="{{URL::asset('assets/images/photos/3.jpg')}}" alt="Thumb-1">
										</a>
									</li>
									<li class="col-xs-6 col-sm-4 col-md-3" data-responsive="{{URL::asset('assets/images/photos/4.jpg')}}" data-src="{{URL::asset('assets/images/photos/4.jpg')}}" data-sub-html=" <h4>Gallery Image 4</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>" >
										<a href="">
											<img class="img-responsive" src="{{URL::asset('assets/images/photos/4.jpg')}}" alt="Thumb-2">
										</a>
									</li>
									<li class="col-xs-6 col-sm-4 col-md-3" data-responsive="{{URL::asset('assets/images/photos/5.jpg')}}" data-src="{{URL::asset('assets/images/photos/5.jpg')}}" data-sub-html="<h4>Gallery Image 5</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>" >
										<a href="">
											<img class="img-responsive" src="{{URL::asset('assets/images/photos/5.jpg')}}" alt="Thumb-1">
										</a>
									</li>
									<li class="col-xs-6 col-sm-4 col-md-3" data-responsive="{{URL::asset('assets/images/photos/6.jpg')}}" data-src="{{URL::asset('assets/images/photos/6.jpg')}}" data-sub-html="<h4>Gallery Image 6</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>" >
										<a href="">
											<img class="img-responsive" src="{{URL::asset('assets/images/photos/6.jpg')}}" alt="Thumb-2">
										</a>
									</li>
									<li class="col-xs-6 col-sm-4 col-md-3" data-responsive="{{URL::asset('assets/images/photos/7.jpg')}}" data-src="{{URL::asset('assets/images/photos/7.jpg')}}" data-sub-html="<h4>Gallery Image 7</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>">
										<a href="">
											<img class="img-responsive" src="{{URL::asset('assets/images/photos/7.jpg')}}" alt="Thumb-1">
										</a>
									</li>
									<li class="col-xs-6 col-sm-4 col-md-3" data-responsive="{{URL::asset('assets/images/photos/8.jpg')}}" data-src="{{URL::asset('assets/images/photos/8.jpg')}}" data-sub-html="<h4>Gallery Image 8</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>" >
										<a href="">
											<img class="img-responsive" src="{{URL::asset('assets/images/photos/8.jpg')}}" alt="Thumb-2">
										</a>
									</li>
									<li class="col-xs-6 col-sm-4 col-md-3 mb-0" data-responsive="{{URL::asset('assets/images/photos/9.jpg')}}" data-src="{{URL::asset('assets/images/photos/9.jpg')}}" data-sub-html="<h4>Gallery Image 9</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>" >
										<a href="">
											<img class="img-responsive mb-0" src="{{URL::asset('assets/images/photos/9.jpg')}}" alt="Thumb-1">
										</a>
									</li>
									<li class="col-xs-6 col-sm-4 col-md-3 mb-0" data-responsive="{{URL::asset('assets/images/photos/10.jpg')}}" data-src="{{URL::asset('assets/images/photos/10.jpg')}}" data-sub-html="<h4>Gallery Image 10</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>" >
										<a href="">
											<img class="img-responsive mb-0" src="{{URL::asset('assets/images/photos/10.jpg')}}" alt="Thumb-2">
										</a>
									</li>
									<li class="col-xs-6 col-sm-4 col-md-3 mb-0" data-responsive="{{URL::asset('assets/images/photos/11.jpg')}}" data-src="{{URL::asset('assets/images/photos/11.jpg')}}" data-sub-html="<h4>Gallery Image 11</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>" >
										<a href="">
											<img class="img-responsive mb-0" src="{{URL::asset('assets/images/photos/11.jpg')}}" alt="Thumb-1">
										</a>
									</li>
									<li class="col-xs-6 col-sm-4 col-md-3 mb-0" data-responsive="{{URL::asset('assets/images/photos/12.jpg')}}" data-src="{{URL::asset('assets/images/photos/12.jpg')}}" data-sub-html="<h4>Gallery Image 12</h4><p> Many desktop publishing packages and web page editors now use Lorem Ipsum</p>" >
										<a href="">
											<img class="img-responsive mb-0" src="{{URL::asset('assets/images/photos/12.jpg')}}" alt="Thumb-2">
										</a>
									</li>
								</ul>
							</div>
						</div>
						<!-- gallery end -->

@endsection('content')

@section('scripts')

		<!--Jquery Sparkline js-->
		<script src="{{URL::asset('assets/plugins/vendors/jquery.sparkline.min.js')}}"></script>

		<!-- Chart Circle js-->
		<script src="{{URL::asset('assets/plugins/vendors/circle-progress.min.js')}}"></script>
		
        <!--Time Counter js-->
		<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>
		
        <!-- Gallery js -->
		<script src="{{URL::asset('assets/plugins/gallery/picturefill.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/gallery/lightgallery.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/gallery/lg-pager.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/gallery/lg-autoplay.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/gallery/lg-fullscreen.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/gallery/lg-zoom.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/gallery/lg-hash.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/gallery/lg-share.js')}}"></script>
        <script src="{{URL::asset('assets/plugins/gallery/gallery.js')}}"></script>

@endsection