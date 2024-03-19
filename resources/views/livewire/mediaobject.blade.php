@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Elements</a></li>
								<li class="breadcrumb-item active" aria-current="page">Media Object</li>
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
							<div class="col-12">
								<div class="card Relatedpost nested-media">
									<div class="card-header pb-0">
										<h4 class="card-title">Default Media object </h4>
									</div>
									<div class="card-body">
										<div class="media media-lg mt-0">
											<img class="mr-3" src="{{URL::asset('assets/images/photos/media1.jpg')}}" alt="Generic placeholder image">
											<div class="media-body">
												<h5 class="mt-0">Media heading</h5>
												Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row closed -->

                        <!-- row open -->
						<div class="row">
							<div class="col-12">
								<div class="card Relatedpost nested-media overflow-hidden">
									<div class="card-header pb-0">
										<h4 class="card-title">Nesting Media object </h4>
									</div>
									<div class="card-body overflow-hidden">
										<div class="media media-lg mt-0">
											<img class="mr-3" src="{{URL::asset('assets/images/photos/media2.jpg')}}" alt="Generic placeholder image">
											<div class="media-body overflow-hidden">
												<h5 class="mt-0">Media heading</h5>
												Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
												<div class="media media-lg mt-3">
													<img class="mr-3" src="{{URL::asset('assets/images/photos/media3.jpg')}}" alt="Generic placeholder image">
													<div class="media-body overflow-hidden">
														<h5 class="mt-0">Media heading</h5>
														Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row closed -->

                        <!-- row open -->
						<div class="row">
							<div class="col-12">
								<div class="card Relatedpost nested-media">
									<div class="card-header pb-0">
										<h4 class="card-title">List Media object </h4>
									</div>
									<div class="card-body">
										<ul class="list-unstyled">
											<li class="media media-lg mt-0">
												<img class="mr-3" src="{{URL::asset('assets/images/photos/media4.jpg')}}" alt="Generic placeholder image">
												<div class="media-body">
													<h5 class="mt-0 mb-1">Media heading 01</h5>
													Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
												</div>
											</li>
											<li class="media media-lg my-4">
												<img class="mr-3" src="{{URL::asset('assets/images/photos/media5.jpg')}}" alt="Generic placeholder image">
												<div class="media-body">
													<h5 class="mt-0 mb-1">Media heading 02</h5>
													Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
												</div>
											</li>
											<li class="media media-lg">
												<img class="mr-3" src="{{URL::asset('assets/images/photos/media1.jpg')}}" alt="Generic placeholder image">
												<div class="media-body">
													<h5 class="mt-0 mb-1">Media heading 03</h5>
													Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
												</div>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<!-- row closed -->

                        <!-- row open -->
						<div class="row">
							<div class="col-12">
								<div class="card Relatedpost nested-media">
									<div class="card-header pb-0">
										<h4 class="card-title">Alignments Media object </h4>
									</div>
									<div class="card-body">
										<div class="media media-lg mt-0">
											<img class="align-self-start mr-3" src="{{URL::asset('assets/images/photos/media2.jpg')}}" alt="Generic placeholder image">
											<div class="media-body">
												<h5 class="mt-0">Top-aligned media</h5>
												<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.</p>
												<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
											</div>
										</div>
										<div class="media media-lg">
											<img class="align-self-center mr-3" src="{{URL::asset('assets/images/photos/media3.jpg')}}" alt="Generic placeholder image">
											<div class="media-body">
												<h5 class="mt-0">Center-aligned media</h5>
												<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.</p>
												<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
											</div>
										</div>
										<div class="media media-lg">
											<img class="align-self-end mr-3" src="{{URL::asset('assets/images/photos/media4.jpg')}}" alt="Generic placeholder image">
											<div class="media-body">
												<h5 class="mt-0">Bottom-aligned media</h5>
												<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.</p>
												<p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row closed -->

                        <!-- row open -->
						<div class="row">
							<div class="col-12">
								<div class="card Relatedpost nested-media ">
									<div class="card-header pb-0">
										<h4 class="card-title">Order  Media object </h4>
									</div>
									<div class="card-body">
										<div class="media media-lg mt-0">
											<div class="media-body mt-0">
												<h5 class="mt-0 mb-4">Media object</h5>
												Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
											</div>
											<img class="ml-2 mt-3 mb-3" src="{{URL::asset('assets/images/photos/media5.jpg')}}" alt="Generic placeholder image">
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row closed -->

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
