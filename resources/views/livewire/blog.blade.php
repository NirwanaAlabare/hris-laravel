@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Pages</a></li>
								<li class="breadcrumb-item active" aria-current="page">Blog</li>
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
							<div class="col-xl-4 col-lg-4 col-md-12">
								<div class="card">
									<img class="card-img-top w-100" src="{{URL::asset('assets/images/photos/20.jpg')}}" alt="">
									<div class="card-body">
										<h4 class="card-title">Blog Title</h4>
										<p class="text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.</p>
										<a class="btn btn-primary" href="{{url('blog-detail')}}">Read More</a>
									</div>
								 </div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-12">
								<div class="card">
									<img class="card-img-top w-100" src="{{URL::asset('assets/images/photos/21.jpg')}}" alt="">
									<div class="card-body">
										<h4 class="card-title">Blog Title</h4>
										<p class="text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.</p>
										<a class="btn btn-secondary" href="{{url('blog-detail')}}">Read More</a>
									</div>
								 </div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-12">
								<div class="card">
									<img class="card-img-top w-100" src="{{URL::asset('assets/images/photos/23.jpg')}}" alt="">
									<div class="card-body">
										<h4 class="card-title">Blog Title</h4>
										<p class="text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.</p>
										<a class="btn btn-success" href="{{url('blog-detail')}}">Read More</a>
									</div>
								 </div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-xl-4 col-lg-4 col-md-12">
								<div class="card text-center">
									<img class="card-img-top w-100" src="{{URL::asset('assets/images/photos/24.jpg')}}" alt="">
									<div class="card-body">
										<h4 class="card-title">Blog Title</h4>
										<p class="text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.</p>
										<a class="btn btn-info" href="{{url('blog-detail')}}">Read More</a>
									</div>
								 </div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-12">
								<div class="card text-center">
									<img class="card-img-top w-100" src="{{URL::asset('assets/images/photos/25.jpg')}}" alt="">
									<div class="card-body">
										<h4 class="card-title">Blog Title</h4>
										<p class="text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.</p>
										<a class="btn btn-warning" href="{{url('blog-detail')}}">Read More</a>
									</div>
								 </div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-12">
								<div class="card text-center">
									<img class="card-img-top w-100" src="{{URL::asset('assets/images/photos/13.jpg')}}" alt="">
									<div class="card-body">
										<h4 class="card-title">Blog Title</h4>
										<p class="text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.</p>
										<a class="btn btn-danger" href="{{url('blog-detail')}}">Read More</a>
									</div>
								 </div>
							</div>
						</div>
						<!-- row end -->

                        <!-- row -->
						<div class="row row-cards">
							<div class="col-lg-6">
								<div class="card card-aside">
									<a href="#" class="card-aside-column cover-image" data-image-src="{{URL::asset('assets/images/photos/7.jpg')}}"></a>
									<div class="card-body flex-column">
										<div class="d-flex align-items-center mb-5">
											<div><img class="avatar brround avatar-md mr-3" src="{{URL::asset('assets/images/users/male/6.jpg')}}" alt="img"></div>
											<div>
												<a href="{{url('profile')}}" class="text-default font-weight-semibold">Thomos Scott</a>
												<small class="d-block text-muted">1 day ago</small>
											</div>
											<div class="ml-auto text-muted">
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fe fe-heart mr-1"></i></a>
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fa fa-thumbs-o-up"></i></a>
											</div>
										</div>
										<h4><a href="#">Publishing packages</a></h4>
										<div class="text-muted ">Many desktop publishing packages and web page editors now use  default model text, and a search for will uncover...</div>
										<a href="" class=" mt-3 btn btn-md btn-secondary">Read more</a>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="card card-aside">
									<div class="card-body flex-column">
										<div class="d-flex align-items-center mb-5">
											<div class="avatar  brround avatar-md mr-3 cover-image" data-image-src="{{URL::asset('assets/images/users/male/16.jpg')}}"></div>
											<div>
												<a href="{{url('profile')}}" class="text-default font-weight-semibold">Irene	Scott</a>
												<small class="d-block text-muted">2 days ago</small>
											</div>
											<div class="ml-auto text-muted">
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fe fe-heart mr-1"></i></a>
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fa fa-thumbs-o-up"></i></a>
											</div>
										</div>
										<h4><a href="#">Nihil molestaturrgt.</a></h4>
										<div class="text-muted ">Many desktop publishing packages and web page editors now use  default model text, and a search for will uncover...</div>
										<a href="" class=" mt-3 btn btn-md btn-primary">Read more</a>
									</div>
									<a href="#" class="card-aside-column cover-image" data-image-src="{{URL::asset('assets/images/photos/8.jpg')}}"></a>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="card card-aside">
									<a href="#" class="card-aside-column cover-image" data-image-src="{{URL::asset('assets/images/photos/2.jpg')}}"></a>
									<div class="card-body flex-column">
										<h4><a href="#">Generator on the Internet..</a></h4>
										<div class="text-muted">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum....</div>
										<div class="d-flex align-items-center pt-5 mt-auto">
											<div class="avatar  brround avatar-md mr-3 cover-image" data-image-src="{{URL::asset('assets/images/users/female/18.jpg')}}"></div>
											<div>
												<a href="{{url('profile')}}" class="text-default font-weight-semibold">Anna Ogden</a>
												<small class="d-block text-muted">1 days ago</small>
											</div>
											<div class="ml-auto text-muted">
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fe fe-heart mr-1"></i></a>
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fa fa-thumbs-o-up"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="card card-aside">
									<div class="card-body flex-column">
										<h4><a href="#">Nihil molestiae consequatur.</a></h4>
										<div class="text-muted">Many desktop publishing packages and web page editors now use  default model text, and a search for will uncover...</div>
										<div class="d-flex align-items-center pt-5 mt-auto">
											<div class="avatar  brround avatar-md mr-3 cover-image" data-image-src="{{URL::asset('assets/images/users/male/3.jpg')}}"></div>
											<div>
												<a href="{{url('profile')}}" class="text-default font-weight-semibold">Irene	Scott</a>
												<small class="d-block text-muted">2 days ago</small>
											</div>
											<div class="ml-auto text-muted">
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fe fe-heart mr-1"></i></a>
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fa fa-thumbs-o-up"></i></a>
											</div>
										</div>
									</div>
									<a href="#" class="card-aside-column cover-image" data-image-src="{{URL::asset('assets/images/photos/18.jpg')}}"></a>
								</div>
							</div>
							<div class="col-sm-6 col-xl-3">
								<div class="card">
									<a href="#"><img class="card-img-top " src="{{URL::asset('assets/images/photos/13.jpg')}}" alt="And this isn&#39;t my nose. This is a false one."></a>
									<div class="card-body d-flex flex-column">
										<h4><a href="#">voluptatem quia voluptas.</a></h4>
										<div class="text-muted">To take a trivial example, which of us ever undertakes laborious physical exerciser , except to obtain some advantage from it...</div>
										<div class="d-flex align-items-center pt-5 mt-auto">
											<div class="avatar brround avatar-md mr-3 cover-image" data-image-src="{{URL::asset('assets/images/users/female/18.jpg')}}"></div>
											<div>
												<a href="{{url('profile')}}" class="text-default font-weight-semibold">Megan Peters</a>
												<small class="d-block text-muted">1 days ago</small>
											</div>
											<div class="ml-auto text-muted">
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fe fe-heart mr-1"></i></a>
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fa fa-thumbs-o-up"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-xl-3">
								<div class="card">
									<a href="#"><img class="card-img-top" src="{{URL::asset('assets/images/photos/14.jpg')}}" alt="Well, I didn&#39;t vote for you."></a>
									<div class="card-body d-flex flex-column">
										<h4><a href="#">voluptatem quia voluptas.</a></h4>
										<div class="text-muted">Who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces.</div>
										<div class="d-flex align-items-center pt-5 mt-auto">
											<div class="avatar brround avatar-md mr-3 cover-image" data-image-src="{{URL::asset('assets/images/users/male/16.jpg')}}"></div>
											<div>
												<a href="{{url('profile')}}" class="text-default font-weight-semibold">Anna Ogden</a>
												<small class="d-block text-muted">2 days ago</small>
											</div>
											<div class="ml-auto text-muted">
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fe fe-heart mr-1"></i></a>
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fa fa-thumbs-o-up"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-xl-3">
								<div class="card">
									<div class="card-body d-flex flex-column">
										<h4><a href="#">voluptatem quia voluptas</a></h4>
										<div class="text-muted">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque...</div>
										<div class="d-flex align-items-center pt-5 mt-auto">
											<div class="avatar  brround avatar-md mr-3 cover-image" data-image-src="{{URL::asset('assets/images/users/male/26.jpg')}}"></div>
											<div>
												<a href="{{url('profile')}}" class="text-default font-weight-semibold">Carol Paige</a>
												<small class="d-block text-muted">2 days ago</small>
											</div>
											<div class="ml-auto text-muted">
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fe fe-heart mr-1"></i></a>
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fa fa-thumbs-o-up"></i></a>
											</div>
										</div>
									</div>
									<a href="#"><img class="card-img-top" src="{{URL::asset('assets/images/photos/15.jpg')}}" alt="How do you know she is a witch?"></a>
								</div>
							</div>
							<div class="col-sm-6 col-xl-3">
								<div class="card">
									<div class="card-body d-flex flex-column">
										<h4><a href="#">voluptatem quia voluptas..</a></h4>
										<div class="text-muted">Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut ..</div>
										<div class="d-flex align-items-center pt-5 mt-auto">
											<div class="avatar brround avatar-md mr-3 cover-image" data-image-src="{{URL::asset('assets/images/users/female/7.jpg')}}"></div>
											<div>
												<a href="{{url('profile')}}" class="text-default font-weight-semibold">Fiona Hodges</a>
												<small class="d-block text-muted">5 days ago</small>
											</div>
											<div class="ml-auto text-muted">
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fe fe-heart mr-1"></i></a>
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fa fa-thumbs-o-up"></i></a>
											</div>
										</div>
									</div>
									<a href="#"><img class="card-img-top" src="{{URL::asset('assets/images/photos/16.jpg')}}" alt="Shut up!"></a>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-sm-6 col-xl-4">
								<div class="card">
									<a href="#"><img class="card-img-top" src="{{URL::asset('assets/images/photos/8.jpg')}}" alt="img" ></a>
									<div class="card-body d-flex flex-column">
										<div class="d-flex align-items-center pb-5 mt-auto">
											<div><img src="{{URL::asset('assets/images/users/female/8.jpg')}}" alt="img" class="avatar brround avatar-md mr-3"></div>
											<div>
												<a href="{{url('profile')}}" class="text-default font-weight-semibold">Lilly jaims</a>
												<small class="d-block text-muted">10 min ago</small>
											</div>
											<div class="ml-auto text-muted">
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fe fe-heart mr-1"></i></a>
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fa fa-thumbs-o-up"></i></a>
											</div>
										</div>
										<h4><a href="#">voluptatem quia voluptas.</a></h4>
										<div class="text-muted">To take a trivial example, which of us ever undertakes laborious physical exerciser , except to obtain some advantage from it...</div>
										<a href="" class=" mt-3 btn btn-md btn-primary">Read more</a>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-xl-4">
								<div class="card">
									<a href="#"><img class="card-img-top" src="{{URL::asset('assets/images/photos/9.jpg')}}" alt="img"></a>
									<div class="card-body d-flex flex-column">
										<div class="d-flex align-items-center pb-5 mt-auto">
											<div><img class="avatar brround avatar-md mr-3" src="{{URL::asset('assets/images/users/male/18.jpg')}}" alt="img"></div>
											<div>
												<a href="{{url('profile')}}" class="text-default font-weight-semibold">James</a>
												<small class="d-block text-muted">10 min ago</small>
											</div>
											<div class="ml-auto text-muted">
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fe fe-heart mr-1"></i></a>
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fa fa-thumbs-o-up"></i></a>
											</div>
										</div>
										<h4><a href="#">voluptatem quia voluptas.</a></h4>
										<div class="text-muted">To take a trivial example, which of us ever undertakes laborious physical exerciser , except to obtain some advantage from it...</div>
										<a href="" class=" mt-3 btn btn-md btn-primary">Read more</a>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-xl-4">
								<div class="card">
									<a href="#"><img class="card-img-top" src="{{URL::asset('assets/images/photos/19.jpg')}}" alt="img"></a>
									<div class="card-body d-flex flex-column">
										<div class="d-flex align-items-center pb-5 mt-auto">
											<div><img class="avatar brround avatar-md mr-3" src="{{URL::asset('assets/images/users/male/17.jpg')}}" alt="img"></div>
											<div>
												<a href="{{url('profile')}}" class="text-default font-weight-semibold">Villma</a>
												<small class="d-block text-muted">10 min ago</small>
											</div>
											<div class="ml-auto text-muted">
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fe fe-heart mr-1"></i></a>
												<a href="javascript:void(0)" class="icon d-none d-md-inline-block ml-3"><i class="fa fa-thumbs-o-up"></i></a>
											</div>
										</div>
										<h4><a href="#">voluptatem quia voluptas.</a></h4>
										<div class="text-muted">To take a trivial example, which of us ever undertakes laborious physical exerciser , except to obtain some advantage from it...</div>
										<a href="" class=" mt-3 btn btn-md btn-primary">Read more</a>
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