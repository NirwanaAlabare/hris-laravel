@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Apps</a></li>
								<li class="breadcrumb-item active" aria-current="page">Cards Design</li>
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
							<div class="col-md-12 col-lg-4">
								<div class="card">
									<div class="card-body">
										<h4 class="card-title">Card title</h4>
										<p class="card-text"> Lorem Ipsum has been the industry's standard dummy text ever since the 1500s  printer took a galley of type and scrambled.</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4">
								<div class="card ">
									<div class="card-body">
										<h4 class="card-title">Card title</h4>
										<p class="card-text"> Lorem Ipsum has been the industry's standard dummy text ever since the 1500s  printer took a galley of type and scrambled.</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4">
								<div class="card ">
									<div class="card-body">
										<h4 class="card-title">Card title</h4>
										<p class="card-text">  Lorem Ipsum has been the industry's standard dummy text ever since the 1500s  printer took a galley of type and scrambled.</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4">
								<div class="card  bg-default">
									<div class="card-body">
										<h4 class="card-title">Default card title</h4>
										<p class="card-text">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC.</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4">
								<div class="card text-white bg-primary ">
									<div class="card-body">
										<h4 class="card-title">Primary card title</h4>
										<p class="card-text"> If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4">
								<div class="card text-white bg-success ">
									<div class="card-body">
										<h4 class="card-title">Success card title</h4>
										<p class="card-text"> If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4">
								<div class="card text-white bg-danger ">
									<div class="card-body">
										<h4 class="card-title">Danger card title</h4>
										<p class="card-text"> If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4">
								<div class="card text-white bg-info ">
									<div class="card-body">
										<h4 class="card-title">Info card title</h4>
										<p class="card-text"> If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4">
								<div class="card text-white bg-warning">
									<div class="card-body">
										<h4 class="card-title">Warning card title</h4>
										<p class="card-text"> If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4">
								<div class="card border-success ">
									<div class="card-body text-success">
										<h4 class="card-title">Success card title</h4>
										<p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4">
								<div class="card border-info">
									<div class="card-body text-info">
										<h4 class="card-title">Info card title</h4>
										<p class="card-text"> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4">
								<div class="card border-primary">
									<div class="card-body text-primary">
										<h4 class="card-title">Primary card title</h4>
										<p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4">
								<div class="card text-white bg-gradient-primary">
									<div class="card-body">
										<h4 class="card-title">Gradient Danger card title</h4>
										<p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4">
								<div class="card text-white bg-gradient-success">
									<div class="card-body">
										<h4 class="card-title">Gradient success card title</h4>
										<p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua..</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-4">
								<div class="card text-white bg-gradient-warning">
									<div class="card-body">
										<h4 class="card-title">Gradient warning card title</h4>
										<p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua..</p>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-xl-4">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Basic card</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
										</div>
									</div>
									<div class="card-body border-top">
										The most well-known dummy text is the 'Lorem Ipsum', which is said to have originated in the 16th century. Lorem Ipsum is composed in a pseudo-Latin language which more or less corresponds to 'proper' Latin.
									</div>
									<div class="card-footer">
										This is Basic card footer
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xl-4">
								<div class="card">
									<div class="card-header bg-primary ">
										<h3 class="card-title text-white">Basic color card-header</h3>
										<div class="card-options ">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x text-white"></i></a>
										</div>
									</div>
									<div class="card-body">
                                        The most well-known dummy text is the 'Lorem Ipsum', which is said to have originated in the 16th century. Lorem Ipsum is composed in a pseudo-Latin language which more or less corresponds to 'proper' Latin.</div>
									<div class="card-footer">
										This is Basic card footer
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xl-4">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Basic color card-footer</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
										</div>
									</div>
									<div class="card-body border-top">
                                          The most well-known dummy text is the 'Lorem Ipsum', which is said to have originated in the 16th century. Lorem Ipsum is composed in a pseudo-Latin language which more or less corresponds to 'proper' Latin</div>
									<div class="card-footer bg-info br-br-7 br-bl-7">
										<div class="text-white">Basic card footer</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-xl-4">
								<div class="card text-white bg-primary">
									<div class="card-header ">
										<h3 class="card-title ">primary card title</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x text-white "></i></a>
										</div>
									</div>
									<div class="card-body border-top">
										<p class="card-text"> It's a convenient tool for mock-ups. It helps to outline the visual elements of a document or presentation, eg typography, font, or layout. .</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xl-4">
								<div class="card text-white bg-secondary">
									<div class="card-header ">
										<h3 class="card-title ">Secondary card title</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x text-white "></i></a>
										</div>
									</div>
									<div class="card-body border-top">
										<p class="card-text"> It's a convenient tool for mock-ups. It helps to outline the visual elements of a document or presentation, eg typography, font, or layout. .</p>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xl-4">
								<div class="card text-white bg-success">
									<div class="card-header ">
										<h3 class="card-title ">Success card title</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x text-white "></i></a>
										</div>
									</div>
									<div class="card-body border-top">
										<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
									</div>
								</div>
							</div>
							<div class="col-md-12  col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Panel with custom buttons</h3>
										<div class="card-options d-none d-sm-block">
											<a href="#" class="btn btn-primary btn-sm">Action 1</a>
											<a href="#" class="btn btn-secondary btn-sm ml-2">Action 2</a>
										</div>
									</div>
									<div class="card-body">
	                                    In a professional context it often happens that private or corporate clients corder a publication to be made and presented with the actual content still not being ready. Think of a news blog that's filled with content hourly on the day of going live.
									</div>
								</div>
							</div>
							<div class="col-md-12  col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Card with search form</h3>
										<div class="card-options">
											<form >
												<div class="input-group">
													<input type="text" class="form-control form-control-sm" placeholder="Search something..." name="s">
													<span class="input-group-btn">
														<button class="btn btn-sm btn-primary" >
															<span class="fe fe-search"></span>
														</button>
													</span>
												</div>
											</form>
										</div>
									</div>
									<div class="card-body">
										In a professional context it often happens that private or corporate clients corder a publication to be made and presented with the actual content still not being ready. Think of a news blog that's filled with content hourly on the day of going live. 									</div>
								</div>
							</div>
							<div class="col-md-12  col-xl-6">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Card with alert</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
										</div>
									</div>
									<div class="card-alert alert alert-success mb-0">
										Adding action was successful
									</div>
									<div class="card-body">
                                        Cicero famously orated against his political opponent Lucius Sergius Catilina. Occasionally the first Oration against Catiline is taken for type specimens.</div>
								</div>
							</div>
							<div class="col-md-12  col-xl-6">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Card with alert</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
										</div>
									</div>
									<div class="card-alert alert alert-danger mb-0">
										Adding action failed
									</div>
									<div class="card-body">
                                          Cicero famously orated against his political opponent Lucius Sergius Catilina. Occasionally the first Oration against Catiline is taken for type specimens.</div>
								</div>
							</div>
							<div class="col-md-12 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Card with loader</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
										</div>
									</div>
									<div class="card-body">
										<div class="dimmer active">
											<div class="spinner"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12  col-xl-4">
								<div class="card">
									<div class="card-header  pb-0">
										<h3 class="card-title">Card with loader</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
										</div>
									</div>
									<div class="card-body">
										<div class="dimmer active">
											<div class="spinner1">
											  <div class="double-bounce1"></div>
											  <div class="double-bounce2"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12  col-xl-4">
								<div class="card">
									<div class="card-header  pb-0">
										<h3 class="card-title">Card with loader</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
										</div>
									</div>
									<div class="card-body">
										<div class="dimmer active">
											<div class="spinner2">
											  <div class="cube1"></div>
											  <div class="cube2"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12  col-xl-4">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">fullscreen button</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
											<a href="#" class="card-options-fullscreen mr-2" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
										</div>
									</div>
									<div class="card-body border-top">
										Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xl-4">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Card with switch</h3>
										<div class="card-options">
											<label class="custom-switch m-0">
												<input type="checkbox" value="1" class="custom-switch-input" checked>
												<span class="custom-switch-indicator"></span>
											</label>
										</div>
									</div>
									<div class="card-body border-top">
										Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xl-4">
								<div class="card card-collapsed">
									<div class="card-header">
										<h3 class="card-title">Initial collapsed card</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
										</div>
									</div>
									<div class="card-body">
										Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum
									</div>
								</div>
							</div>
							<div class="col-md-12 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Built card</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
										</div>
									</div>
									<div class="card-body">
										Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum
									</div>
								</div>
							</div>
							<div class="col-md-12  col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Card</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
										</div>
									</div>
									<div class="card-body">
										Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum
									</div>
								</div>
							</div>

							<div class="col-md-12 col-xl-4">
								<div class="card">
									<div class="card-status card-status-left bg-primary br-bl-7 br-tl-7"></div>
									<div class="card-header pb-0">
										<h3 class="card-title">Card status on left side</h3>
										<div class="card-options">
											<a href="#" class="card-options-collapse mr-2" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
											<a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
										</div>
									</div>
									<div class="card-body">
										Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum
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