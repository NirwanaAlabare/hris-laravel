@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Elements</a></li>
								<li class="breadcrumb-item active" aria-current="page">Buttons</li>
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
							<div class="col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Default Button</h3>
									</div>
									<div class="card-body">
										<div class="btn-list">
										    <a href="#" class="btn btn-default">Default</a>
											<a href="#" class="btn btn-primary">Primary</a>
											<a href="#" class="btn btn-secondary">Secondary</a>
											<a href="#" class="btn btn-success">Success</a>
											<a href="#" class="btn btn-info">Info</a>
											<a href="#" class="btn btn-warning">Warning</a>
											<a href="#" class="btn btn-danger">Danger</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Disabled buttons</h3>
									</div>
									<div class="card-body">
										<div class="btn-list">
										    <a href="#" class="btn btn-default disabled">Default</a>
											<a href="#" class="btn btn-primary disabled">Primary</a>
											<a href="#" class="btn btn-secondary disabled">Secondary</a>
											<a href="#" class="btn btn-success disabled">Success</a>
											<a href="#" class="btn btn-info disabled">Info</a>
											<a href="#" class="btn btn-warning disabled">Warning</a>
											<a href="#" class="btn btn-danger disabled">Danger</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Square buttons</h3>
									</div>
									<div class="card-body">
										<div class="btn-list">
										    <a href="#" class="btn btn-square btn-default">Default</a>
											<a href="#" class="btn btn-square btn-primary">Primary</a>
											<a href="#" class="btn btn-square btn-secondary">Secondary</a>
											<a href="#" class="btn btn-square btn-success">Success</a>
											<a href="#" class="btn btn-square btn-info">Info</a>
											<a href="#" class="btn btn-square btn-warning">Warning</a>
											<a href="#" class="btn btn-square btn-danger">Danger</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Rounded buttons</h3>
									</div>
									<div class="card-body">
										<div class="btn-list">
										    <a href="#" class="btn btn-pill btn-default">Default</a>
											<a href="#" class="btn btn-pill btn-primary">Primary</a>
											<a href="#" class="btn btn-pill btn-secondary">Secondary</a>
											<a href="#" class="btn btn-pill btn-success">Success</a>
											<a href="#" class="btn btn-pill btn-info">Info</a>
											<a href="#" class="btn btn-pill btn-warning">Warning</a>
											<a href="#" class="btn btn-pill btn-danger">Danger</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Btn Outline buttons</h3>
									</div>
									<div class="card-body">
										<div class="btn-list">
										    <a href="#" class="btn btn-outline-default">Default</a>
											<a href="#" class="btn btn-outline-primary">Primary</a>
											<a href="#" class="btn btn-outline-secondary">Secondary</a>
											<a href="#" class="btn btn-outline-success">Success</a>
											<a href="#" class="btn btn-outline-info">Info</a>
											<a href="#" class="btn btn-outline-warning">Warning</a>
											<a href="#" class="btn btn-outline-danger">Danger</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Btn Outline Disabled buttons</h3>
									</div>
									<div class="card-body">
										<div class="btn-list">
										    <a href="#" class="btn btn-outline-default disabled">Default</a>
											<a href="#" class="btn btn-outline-primary disabled">Primary</a>
											<a href="#" class="btn btn-outline-secondary disabled">Secondary</a>
											<a href="#" class="btn btn-outline-success disabled">Success</a>
											<a href="#" class="btn btn-outline-info disabled">Info</a>
											<a href="#" class="btn btn-outline-warning disabled">Warning</a>
											<a href="#" class="btn btn-outline-danger disabled">Danger</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12">
								<div class="card shadow">
									<div class="card-header pb-0">
										<h3 class="card-title mb-0">App buttons</h3>
									</div>
									<div class="card-body">
										<a class="btn btn-app btn-primary mr-2 mt-1 mb-1">
											<i class="fa fa-edit"></i>Edit
										</a>
										<a class="btn btn-app btn-secondary mr-2 mt-1 mb-1">
											<i class="fa fa-play"></i> Play
										</a>
										<a class="btn btn-app btn-info mr-2 mt-1 mb-1">
											<i class="fa fa-pause"></i> Pause
										</a>
										<a class="btn btn-app btn-danger mr-2 mt-1 mb-1">
											<i class="fa fa-save"></i> Save
										</a>
										<a class="btn btn-app btn-yellow mr-2 mt-1 mb-1">
											<i class="fa fa-bullhorn"></i> Notifications
										</a>
										<a class="btn btn-app btn-success mr-2 mt-1 mb-1">
											<i class="fa fa-users"></i> Users
										</a>
										<a class="btn btn-app btn-gray mr-2 mt-1 mb-1">
											<i class="fa fa-inbox"></i> Orders
										</a>
										<a class="btn btn-app btn-pink mr-2 mt-1 mb-1">
											<i class="fa fa-envelope"></i> Inbox
										</a>
										<a class="btn btn-app btn-indigo mr-2 mt-1 mb-1">
											<i class="fa fa-heart"></i> Likes
										</a>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Button with icon</h3>
									</div>
									<div class="card-body">
										<div class="btn-list">
											<button type="button" class="btn btn-dark"><i class="fe fe-upload mr-2"></i>Upload</button>
											<button type="button" class="btn btn-warning"><i class="fe fe-heart mr-2"></i>I like</button>
											<button type="button" class="btn btn-success"><i class="fe fe-check mr-2"></i>I agree</button>
											<button type="button" class="btn btn-primary"><i class="fe fe-plus mr-2"></i>More</button>
											<button type="button" class="btn btn-danger"><i class="fe fe-link mr-2"></i>Link</button>
											<button type="button" class="btn btn-info"><i class="fe fe-message-circle mr-2"></i>Comment</button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Button with icon-loader</h3>
									</div>
									<div class="card-body">
										<div class="btn-list">
											<button type="button" class="btn btn-dark">Upload <i class="fa fa-upload fa-spin ml-2"></i></button>
											<button type="button" class="btn btn-warning">I like <i class="fa fa-heart fa-spin ml-2"></i></button>
											<button type="button" class="btn btn-success">I agree <i class="fa fa-check fa-spin ml-2"></i></button>
											<button type="button" class="btn btn-primary">More <i class="fa fa-plus fa-spin ml-2"></i></button>
											<button type="button" class="btn btn-danger">Link <i class="fa fa-link fa-spin ml-2"></i></button>
											<button type="button" class="btn btn-info">Comment <i class="fa fa-comment-o fa-spin ml-2"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div  class="row">
							<div class="col-md-12 ">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Button size</h3>
									</div>
									<div class="card-body">
										<div class="btn-list">
											<div class="row">
												<div class="col-md-4 mb-2">
													<button type="button" class="btn btn-warning btn-sm mb-1">Small button</button>
												</div>
												<div class="col-md-4 mb-2">
													<button type="button" class="btn btn-info  mb-1">Medium button</button>
												</div>
												<div class="col-md-4 mb-2">
													<button type="button" class="btn btn-success btn-lg mb-1">Large button</button>
												</div>

											</div>
											<button type="button" class="btn btn-primary btn-block">Block level button</button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Social buttons</h3>
									</div>
									<div class="card-body">
										<div class="btn-list">
											<button type="button" class="btn btn-facebook"><i class="fa fa-facebook mr-2"></i>Facebook</button>
											<button type="button" class="btn btn-twitter"><i class="fa fa-twitter mr-2"></i>Twitter</button>
											<button type="button" class="btn btn-google"><i class="fa fa-google mr-2"></i>Google</button>
											<button type="button" class="btn btn-youtube"><i class="fa fa-youtube mr-2"></i>Youtube</button>
											<button type="button" class="btn btn-vimeo"><i class="fa fa-vimeo mr-2"></i>Vimeo</button>
											<button type="button" class="btn btn-dribbble"><i class="fa fa-dribbble mr-2"></i>Dribble</button>
											<button type="button" class="btn btn-github"><i class="fa fa-github mr-2"></i>Github</button>
											<button type="button" class="btn btn-instagram"><i class="fa fa-instagram mr-2"></i>Instagram</button>
											<button type="button" class="btn btn-pinterest"><i class="fa fa-pinterest mr-2"></i>Pinterest</button>
											<button type="button" class="btn btn-vk"><i class="fa fa-vk mr-2"></i>VKontakte</button>
											<button type="button" class="btn btn-rss"><i class="fa fa-rss mr-2"></i>RSS</button>
											<button type="button" class="btn btn-flickr"><i class="fa fa-flickr mr-2"></i>Flickr</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Color variations</h3>
									</div>
									<div class="card-body">
										<div class="btn-list">
											<a href="#" class="btn btn-blue">Blue</a>
											<a href="#" class="btn btn-azure">Azure</a>
											<a href="#" class="btn btn-indigo">Indigo</a>
											<a href="#" class="btn btn-purple">Purple</a>
											<a href="#" class="btn btn-pink">Pink</a>
											<a href="#" class="btn btn-red">Red</a>
											<a href="#" class="btn btn-orange">Orange</a>
											<a href="#" class="btn btn-yellow">Yellow</a>
											<a href="#" class="btn btn-lime">Lime</a>
											<a href="#" class="btn btn-green">Green</a>
											<a href="#" class="btn btn-teal">Teal</a>
											<a href="#" class="btn btn-cyan">Cyan</a>
											<a href="#" class="btn btn-gray">Gray</a>
											<a href="#" class="btn btn-gray-dark">Dark gray</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div  class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Social buttons</h3>
									</div>
									<div class="card-body">
										<div class="btn-list">
											<button type="button" class="btn btn-icon btn-facebook"><i class="fa fa-facebook"></i></button>
											<button type="button" class="btn btn-icon btn-twitter"><i class="fa fa-twitter"></i></button>
											<button type="button" class="btn btn-icon btn-google"><i class="fa fa-google"></i></button>
											<button type="button" class="btn btn-icon btn-youtube"><i class="fa fa-youtube"></i></button>
											<button type="button" class="btn btn-icon btn-vimeo"><i class="fa fa-vimeo"></i></button>
											<button type="button" class="btn btn-icon btn-dribbble"><i class="fa fa-dribbble"></i></button>
											<button type="button" class="btn btn-icon btn-github"><i class="fa fa-github"></i></button>
											<button type="button" class="btn btn-icon btn-instagram"><i class="fa fa-instagram"></i></button>
											<button type="button" class="btn btn-icon btn-pinterest"><i class="fa fa-pinterest"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Icon buttons</h3>
									</div>
									<div class="card-body">
										<div class="btn-list">
											<button type="button" class="btn btn-icon btn-primary"><i class="fe fe-activity"></i></button>
											<button type="button" class="btn btn-icon btn-dark btn-github"><i class="fe fe-github"></i></button>
											<button type="button" class="btn btn-icon btn-success"><i class="fe fe-bell"></i></button>
											<button type="button" class="btn btn-icon btn-warning"><i class="fe fe-star"></i></button>
											<button type="button" class="btn btn-icon btn-danger"><i class="fe fe-trash"></i></button>
											<button type="button" class="btn btn-icon btn-purple"><i class="fe fe-bar-chart"></i></button>
											<button type="button" class="btn btn-icon btn-secondary"><i class="fe fe-git-merge"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

                        <!-- row -->
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Button dropdown</h3>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<p>Wrap the dropdown’s toggle using different colors and icons implemented in different dropdowns  styles (your button or link) and the dropdown menu within <code class="highlighter-rouge">.dropdown</code>, or another element that declares <code class="highlighter-rouge">position: relative;</code>. Dropdowns can be triggered from <code class="highlighter-rouge">&lt;a&gt;</code> or <code class="highlighter-rouge">&lt;button&gt;</code> elements to better fit your potential needs.</p>
											<div class="example p-0">
												<div class="btn-list">
													<div class="dropdown">
														<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
															<i class="fe fe-calendar"></i>
														</button>
														<div class="dropdown-menu">
															<a class="dropdown-item" href="javascript:void(0)">Dropdown link</a>
															<a class="dropdown-item" href="javascript:void(0)">Dropdown link</a>
														</div>
													</div>
													<div class="dropdown">
														<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
															<i class="fe fe-calendar mr-2"></i>Show calendar
														</button>
														<div class="dropdown-menu">
															<a class="dropdown-item" href="javascript:void(0)">Dropdown link</a>
															<a class="dropdown-item" href="javascript:void(0)">Dropdown link</a>
														</div>
													</div>
													<div class="dropdown">
														<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
															Show calendar
														</button>
														<div class="dropdown-menu">
															<a class="dropdown-item" href="javascript:void(0)">Dropdown link</a>
															<a class="dropdown-item" href="javascript:void(0)">Dropdown link</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Loading button</h3>
									</div>
									<div class="card-body">
										<div class="text-wra">
											<p>Add <code class="highlighter-rouge">.btn-loading</code> to use a loading state on a button. The width of the button depends on the length of the text inside Since the loading spinner is implemented using the element.</p>
											<div class="example p-0">
												<div class="btn-list">
													<button type="button" class="btn btn-primary btn-loading">Button text</button><br>
													<button type="button" class="btn btn-success btn-loading btn-icon"><i class="fe fe-check"></i></button>
													<button type="button" class="btn btn-warning btn-loading btn-sm">Button text</button><br>
													<button type="button" class="btn btn-danger btn-loading btn-lg">Button text</button>
													<button type="button" class="btn btn-info btn-loading btn-block">Button text</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">List of buttons</h3>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<p>You can now create a list of buttons with the <code class="highlighter-rouge">.btn-list</code> container.</p>
											<div class="example p-0">
												<div class="btn-list">
													<a href="#" class="btn btn-primary">Save changes</a>
													<a href="#" class="btn btn-secondary">Save and continue</a>
													<a href="#" class="btn btn-danger">Cancel</a>
												</div>
											</div>
										</div>
										<div class="text-wrap mt-6">
											<p>Use the <code class="highlighter-rouge">.text-center</code>  modifiers to alter the alignment.</p>
											<div class="example p-0">
												<div class="btn-list text-center">
													<a href="#" class="btn btn-primary">Save changes</a>
													<a href="#" class="btn btn-secondary">Save and continue</a>
													<a href="#" class="btn btn-danger">Cancel</a>
												</div>
											</div>
										</div>
										<div class="text-wrap mt-6">
											<p>Use the  <code class="highlighter-rouge">.text-right</code> modifiers to alter the alignment.</p>
											<div class="example p-0">
												<div class="btn-list text-right">
													<a href="#" class="btn btn-primary">Save changes</a>
													<a href="#" class="btn btn-secondary">Save and continue</a>
													<a href="#" class="btn btn-danger">Cancel</a>
												</div>
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