@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Advanced UI</a></li>
								<li class="breadcrumb-item active" aria-current="page">Headers</li>
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

						<!-- Header-style 1 -->
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">Header Style01</div>
									</div>
									<div class="header-style-1">
										<div class="header headerMenu  py-4">
											<div class="container">
												<div class="d-flex">
													<a class="header-brand" href="{{url('index')}}">
														<img src="{{URL::asset('assets/images/brand/logo.png')}}" class="header-brand-img mt-0" alt="Sparic logo">
													</a>
													<div class="ml-auto mt-3 mb-0">
														<a href="#" class="btn btn-primary btn-sm">Register</a>
														<a href="#" class="btn btn-secondary btn-sm">Login</a>
													</div>
													<a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse4">
													<span class="header-toggler-icon"></span>
													</a>
												</div>
											</div>
										</div>
										<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse4">
											<div class="container">
												<div class="row align-items-center">
													<div class="col-lg order-lg-first">
														<ul class="nav nav-tabs border-0 flex-column flex-lg-row">
															<li class="nav-item">
																<a href="javascript:void(0)" class="nav-link " data-toggle="dropdown">DASHBOARD </a>
																<div class="dropdown-menu dropdown-menu-arrow" >
																	<a href="#" class="dropdown-item ">Dashboard 01</a>
																	<a href="#" class="dropdown-item ">Dashboard 02</a>
																	<a href="#" class="dropdown-item ">Dashboard 03</a>
																	<a href="#" class="dropdown-item ">Dashboard 04</a>
																</div>
															</li>
															<li class="nav-item dropdown">
																<a href="{{url('widgets')}}" class="nav-link ">WIDGETS</a>
															</li>
															<li class="nav-item">
																<a href="javascript:void(0)" class="nav-link" data-toggle="dropdown">UI DESIGN</a>
																<div class="dropdown-menu dropdown-menu-arrow mega-menu">
																	<div class="row">
																		<div class="col-md-6">
																			<a href="#" class="dropdown-item ">Graph Charts</a>
																			<a href="#" class="dropdown-item ">line Charts</a>
																			<a href="#" class="dropdown-item ">Donut Charts</a>
																			<a href="#" class="dropdown-item ">Pie Charts</a>
																			<a href="#" class="dropdown-item ">Cards design</a>
																			<a href="#" class="dropdown-item ">Maps</a>
																			<a href="#" class="dropdown-item ">Sweet alert</a>
																		</div>
																		<div class="col-md-6">
																			<a href="#" class="dropdown-item ">Timeline</a>
																			<a href="#" class="dropdown-item ">Default Chat</a>
																			<a href="#" class="dropdown-item ">Counters</a>
																			<a href="#" class="dropdown-item ">Loaders</a>
																			<a href="#" class="dropdown-item ">Notifications</a>
																			<a href="#" class="dropdown-item ">Range slider</a>
																			<a href="#" class="dropdown-item ">Content Scroll bar</a>
																		</div>
																	</div>
																</div>
															</li>

															<li class="nav-item">
																<a href="javascript:void(0)" class="nav-link active" data-toggle="dropdown">UI ElEMENTS</a>
																<div class="dropdown-menu dropdown-menu-arrow mega-menu">
																	<div class="row">
																		<div class="col-md-6">
																			<a href="#" class="dropdown-item ">Alerts</a>
																			<a href="#" class="dropdown-item ">Buttons</a>
																			<a href="#" class="dropdown-item ">Colors</a>
																			<a href="#" class="dropdown-item ">Sample Charts</a>
																			<a href="#" class="dropdown-item ">Avatars</a>
																			<a href="#" class="dropdown-item ">RoundAvatars</a>
																			<a href="#" class="dropdown-item ">RadiusAvatars</a>
																			<a href="#" class="dropdown-item ">Accordion</a>
																			<a href="#" class="dropdown-item ">Dropdown</a>
																			<a href="#" class="dropdown-item ">List</a>
																			<a href="#" class="dropdown-item ">Tags</a>
																			<a href="#" class="dropdown-item ">Pagination</a>
																		</div>
																		<div class="col-md-6">
																			<a href="#" class="dropdown-item ">Modal</a>
																			<a href="#" class="dropdown-item ">Navigation</a>
																			<a href="#" class="dropdown-item ">Progress</a>
																			<a href="#" class="dropdown-item ">Typography</a>
																			<a href="#" class="dropdown-item ">Tooltip & Popover</a>
																			<a href="#" class="dropdown-item ">Breadcrumbs</a>
																			<a href="#" class="dropdown-item ">Badges</a>
																			<a href="#" class="dropdown-item ">Jumbotron</a>
																			<a href="#" class="dropdown-item ">Panel</a>
																			<a href="#" class="dropdown-item ">Thumbnails</a>
																			<a href="#" class="dropdown-item ">Mediaobject</a>
																			<a href="#" class="dropdown-item ">Tabs</a>

																		</div>
																	</div>
																</div>
															</li>
															<li class="nav-item dropdown">
																<a href="javascript:void(0)" class="nav-link" data-toggle="dropdown">PAGES</a>
																<div class="dropdown-menu dropdown-menu-arrow">
																	<a href="#" class="dropdown-item ">Profile</a>
																	<a href="#" class="dropdown-item ">Edit Profile</a>
																	<a href="#" class="dropdown-item ">Login</a>
																	<a href="#" class="dropdown-item ">Register</a>
																	<a href="#" class="dropdown-item ">Forgot password</a>
																	<a href="#" class="dropdown-item ">Email</a>
																	<a href="#" class="dropdown-item ">Email Inbox</a>
																	<a href="#" class="dropdown-item ">Empty page</a>
																	<a href="#" class="dropdown-item ">Under Construction</a>
																	<a href="#" class="dropdown-item ">Lock screen</a>
																	<a href="#" class="dropdown-item ">400 error</a>
																	<a href="#" class="dropdown-item ">401 error</a>
																	<a href="#" class="dropdown-item ">403 error</a>
																	<a href="#" class="dropdown-item ">404 error</a>
																	<a href="#" class="dropdown-item ">500 error</a>
																	<a href="#" class="dropdown-item ">503 error</a>
																</div>
															</li>
															<li class="nav-item dropdown">
																<a href="javascript:void(0)" class="nav-link" data-toggle="dropdown">FORMS</a>
																<div class="dropdown-menu dropdown-menu-arrow">
																	<a href="#" class="dropdown-item ">Form Elements</a>
																	<a href="#" class="dropdown-item ">form-wizard Elements</a>
																	<a href="#" class="dropdown-item ">Text Editor</a>
																</div>
															</li>
															<li class="nav-item">
																<a href="#" class="nav-link">GALLERY</a>
															</li>
															<li class="nav-item">
																<a href="#" class="nav-link">PROFILE</a>
															</li>
															<li class="nav-item dropdown">
																<a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"> COMPONENTS</a>
																<div class="dropdown-menu dropdown-menu-arrow">
																	<a href="#" class="dropdown-item ">Pricing tables</a>
																	<a href="#" class="dropdown-item ">Crypto-currencies</a>
																	<a href="#" class="dropdown-item ">User-list</a>
																	<a href="#" class="dropdown-item ">Icons</a>
																	<a href="#" class="dropdown-item ">Icons 2</a>
																	<a href="#" class="dropdown-item ">Tables</a>
																	<a href="#" class="dropdown-item ">Data Tables</a>
																	<a href="#" class="dropdown-item ">Store</a>
																	<a href="#" class="dropdown-item ">Blog</a>
																	<a href="#" class="dropdown-item ">Invoice</a>
																	<a href="#" class="dropdown-item ">Carousel</a>
																</div>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Header-style 1-->

						<!-- Header-style 2 -->
						<div class="row">
							<div class="col-md-12">
								<div class="card ">
									<div class="card-header pb-0">
										<div class="card-title">Header Style02</div>
									</div>
									<div class="header-style-2">
										<div class="header headerMenu  py-4">
											<div class="container">
												<div class="d-flex">
													<a class="header-brand" href="{{url('index')}}">
														<img src="{{URL::asset('assets/images/brand/logo.png')}}" class="header-brand-img" alt="Sparic logo">
													</a>
													<div class="d-flex order-lg-2 ml-auto">

														<div class="dropdown mt-1">
															<a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
																<span class="avatar avatar-md brround cover-image" data-image-src="{{URL::asset('assets/images/users/female/5.jpg')}}"></span>
																<span class="ml-2 d-none d-lg-block">
																	<span class="text-dark">Alison</span>
																</span>
															</a>
															<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																<a class="dropdown-item" href="#">
																	<i class="dropdown-icon mdi mdi-account-outline "></i> Profile
																</a>
																<a class="dropdown-item" href="#">
																	<i class="dropdown-icon  mdi mdi-settings"></i> Settings
																</a>
																<a class="dropdown-item" href="#">
																	<span class="float-right"><span class="badge badge-primary">6</span></span>
																	<i class="dropdown-icon mdi  mdi-message-outline"></i> Inbox
																</a>
																<a class="dropdown-item" href="#">
																	<i class="dropdown-icon mdi mdi-comment-check-outline"></i> Message
																</a>
																<div class="dropdown-divider"></div>
																<a class="dropdown-item" href="#">
																	<i class="dropdown-icon mdi mdi-compass-outline"></i> Need help?
																</a>
																<a class="dropdown-item" href="{{url('login')}}">
																	<i class="dropdown-icon mdi  mdi-logout-variant"></i> Sign out
																</a>
															</div>
														</div>
													</div>
													<a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse3">
													<span class="header-toggler-icon"></span>
													</a>
												</div>
											</div>
										</div>
										<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse3">
											<div class="container">
												<div class="row align-items-center">
													<div class="col-lg order-lg-first">
														<ul class="nav nav-tabs border-0 flex-column flex-lg-row">
															<li class="nav-item">
																<a href="javascript:void(0)" class="nav-link " data-toggle="dropdown"><i class="fa fa-home"></i>DASHBOARD </a>
																<div class="dropdown-menu dropdown-menu-arrow" >
																	<a href="#" class="dropdown-item ">Dashboard 01</a>
																	<a href="#" class="dropdown-item ">Dashboard 02</a>
																	<a href="#" class="dropdown-item ">Dashboard 03</a>
																	<a href="#" class="dropdown-item ">Dashboard 04</a>
																</div>
															</li>
															<li class="nav-item dropdown">
																<a href="{{url('widgets')}}" class="nav-link "><i class="fa fa-window-restore"></i> WIDGETS</a>
															</li>
															<li class="nav-item">
																<a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fa fa-snowflake-o"></i>UI DESIGN</a>
																<div class="dropdown-menu dropdown-menu-arrow mega-menu">
																	<div class="row">
																		<div class="col-md-6">
																			<a href="#" class="dropdown-item ">Graph Charts</a>
																			<a href="#" class="dropdown-item ">line Charts</a>
																			<a href="#" class="dropdown-item ">Donut Charts</a>
																			<a href="#" class="dropdown-item ">Pie Charts</a>
																			<a href="#" class="dropdown-item ">Cards design</a>
																			<a href="#" class="dropdown-item ">Maps</a>
																			<a href="#" class="dropdown-item ">Sweet alert</a>
																		</div>
																		<div class="col-md-6">
																			<a href="#" class="dropdown-item ">Timeline</a>
																			<a href="#" class="dropdown-item ">Default Chat</a>
																			<a href="#" class="dropdown-item ">Counters</a>
																			<a href="#" class="dropdown-item ">Loaders</a>
																			<a href="#" class="dropdown-item ">Notifications</a>
																			<a href="#" class="dropdown-item ">Range slider</a>
																			<a href="#" class="dropdown-item ">Content Scroll bar</a>
																		</div>
																	</div>
																</div>
															</li>

															<li class="nav-item">
																<a href="javascript:void(0)" class="nav-link active" data-toggle="dropdown"><i class="fa fa-pencil-square-o"></i>UI ElEMENTS</a>
																<div class="dropdown-menu dropdown-menu-arrow mega-menu">
																	<div class="row">
																		<div class="col-md-6">
																			<a href="#" class="dropdown-item ">Alerts</a>
																			<a href="#" class="dropdown-item ">Buttons</a>
																			<a href="#" class="dropdown-item ">Colors</a>
																			<a href="#" class="dropdown-item ">Sample Charts</a>
																			<a href="#" class="dropdown-item ">Avatars</a>
																			<a href="#" class="dropdown-item ">RoundAvatars</a>
																			<a href="#" class="dropdown-item ">RadiusAvatars</a>
																			<a href="#" class="dropdown-item ">Accordion</a>
																			<a href="#" class="dropdown-item ">Dropdown</a>
																			<a href="#" class="dropdown-item ">List</a>
																			<a href="#" class="dropdown-item ">Tags</a>
																			<a href="#" class="dropdown-item ">Pagination</a>
																		</div>
																		<div class="col-md-6">
																			<a href="#" class="dropdown-item ">Modal</a>
																			<a href="#" class="dropdown-item ">Navigation</a>
																			<a href="#" class="dropdown-item ">Progress</a>
																			<a href="#" class="dropdown-item ">Typography</a>
																			<a href="#" class="dropdown-item ">Tooltip & Popover</a>
																			<a href="#" class="dropdown-item ">Breadcrumbs</a>
																			<a href="#" class="dropdown-item ">Badges</a>
																			<a href="#" class="dropdown-item ">Jumbotron</a>
																			<a href="#" class="dropdown-item ">Panel</a>
																			<a href="#" class="dropdown-item ">Thumbnails</a>
																			<a href="#" class="dropdown-item ">Mediaobject</a>
																			<a href="#" class="dropdown-item ">Tabs</a>
																		</div>
																	</div>
																</div>
															</li>
															<li class="nav-item dropdown">
																<a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fa fa-newspaper-o"></i>PAGES</a>
																<div class="dropdown-menu dropdown-menu-arrow">
																	<a href="#" class="dropdown-item ">Profile</a>
																	<a href="#" class="dropdown-item ">Edit Profile</a>
																	<a href="#" class="dropdown-item ">Login</a>
																	<a href="#" class="dropdown-item ">Register</a>
																	<a href="#" class="dropdown-item ">Forgot password</a>
																	<a href="#" class="dropdown-item ">Email</a>
																	<a href="#" class="dropdown-item ">Email Inbox</a>
																	<a href="#" class="dropdown-item ">Empty page</a>
																	<a href="#" class="dropdown-item ">Under Construction</a>
																	<a href="#" class="dropdown-item ">Lock screen</a>
																	<a href="#" class="dropdown-item ">400 error</a>
																	<a href="#" class="dropdown-item ">401 error</a>
																	<a href="#" class="dropdown-item ">403 error</a>
																	<a href="#" class="dropdown-item ">404 error</a>
																	<a href="#" class="dropdown-item ">500 error</a>
																	<a href="#" class="dropdown-item ">503 error</a>
																</div>
															</li>
															<li class="nav-item dropdown">
																<a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fa fa-file-text-o"></i> FORMS</a>
																<div class="dropdown-menu dropdown-menu-arrow">
																	<a href="#" class="dropdown-item ">Form Elements</a>
																	<a href="#" class="dropdown-item ">form-wizard Elements</a>
																	<a href="#" class="dropdown-item ">Text Editor</a>
																</div>
															</li>
															<li class="nav-item">
																<a href="#" class="nav-link"><i class="fa fa-picture-o"></i>GALLERY</a>
															</li>
															<li class="nav-item dropdown">
																<a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fa fa-cubes"></i> COMPONENTS</a>
																<div class="dropdown-menu dropdown-menu-arrow">
																	<a href="#" class="dropdown-item ">Pricing tables</a>
																	<a href="#" class="dropdown-item ">Crypto-currencies</a>
																	<a href="#" class="dropdown-item ">User-list</a>
																	<a href="#" class="dropdown-item ">Icons</a>
																	<a href="#" class="dropdown-item ">Icons 2</a>
																	<a href="#" class="dropdown-item ">Tables</a>
																	<a href="#" class="dropdown-item ">Data Tables</a>
																	<a href="#" class="dropdown-item ">Store</a>
																	<a href="#" class="dropdown-item ">Blog</a>
																	<a href="#" class="dropdown-item ">Invoice</a>
																	<a href="#" class="dropdown-item ">Carousel</a>
																</div>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Header-style 2 -->

						<!-- Header-style 3 -->
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">Default Navigation</div>
									</div>
									<div class="header-style-3">
										<div class="header header-style-2 headerMenu py-4">
											<div class="container">
												<div class="d-flex">
													<a class="header-brand" href="{{url('index')}}">
														<img src="{{URL::asset('assets/images/brand/logo.png')}}" class="header-brand-img main-logo" alt="Sparic logo">
														<img src="{{URL::asset('assets/images/brand/icon.png')}}" class="header-brand-img icon-logo" alt="Sparic logo">
													</a><!-- logo-->
													<div class="defaultheader">
														<form class="input-icon mt-2 mr-2 ">
															<input type="search" class="form-control header-search" placeholder="Search…" tabindex="1">
															<div class="input-icon-addon">
																<i class="fe fe-search"></i>
															</div>
														</form>
													</div>
													<div class="d-flex order-lg-2 ml-auto header-rightmenu">
														<div class="dropdown">
															<a  class="nav-link icon full-screen-link">
																<i class="fe fe-maximize-2"></i>
															</a>
														</div><!-- full-screen -->
														<div class="dropdown header-notify">
															<a href="#" class="nav-link icon" data-toggle="dropdown" aria-expanded="false">
																<i class="fe fe-bell "></i>
																<span class="pulse bg-success"></span>
															</a>
															<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow ">
																<a href="#" class="dropdown-item text-center">4 New Notifications</a>
																<div class="dropdown-divider"></div>
																<a href="#" class="dropdown-item d-flex pb-3">
																	<div class="notifyimg bg-green">
																		<i class="fe fe-mail"></i>
																	</div>
																	<div>
																		<strong>Message Sent.</strong>
																		<div class="small text-muted">12 mins ago</div>
																	</div>
																</a>
																<a href="#" class="dropdown-item d-flex pb-3">
																	<div class="notifyimg bg-pink">
																		<i class="fe fe-shopping-cart"></i>
																	</div>
																	<div>
																		<strong>Order Placed</strong>
																		<div class="small text-muted">2  hour ago</div>
																	</div>
																</a>
																<a href="#" class="dropdown-item d-flex pb-3">
																	<div class="notifyimg bg-blue">
																		<i class="fe fe-calendar"></i>
																	</div>
																	<div>
																		<strong> Event Started</strong>
																		<div class="small text-muted">1  hour ago</div>
																	</div>
																</a>
																<a href="#" class="dropdown-item d-flex pb-3">
																	<div class="notifyimg bg-orange">
																		<i class="fe fe-monitor"></i>
																	</div>
																	<div>
																		<strong>Your Admin Lanuch</strong>
																		<div class="small text-muted">2  days ago</div>
																	</div>
																</a>
																<div class="dropdown-divider"></div>
																<a href="#" class="dropdown-item text-center">View all Notifications</a>
															</div>
														</div><!-- notifications -->
														<div class="dropdown header-user">
															<a class="nav-link leading-none siderbar-link"  data-toggle="sidebar-right" data-target=".sidebar-right">
																<span class="mr-3 d-none d-lg-block ">
																	<span class="text-gray-white"><span class="ml-2">Alison</span></span>
																</span>
																<span class="avatar avatar-md brround"><img src="{{URL::asset('assets/images/users/female/5.jpg')}}" alt="Profile-img" class="avatar avatar-md brround"></span>
															</a>
															<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
																<div class="header-user text-center">
																	<a href="#" class="dropdown-item text-center font-weight-sembold user">Alison</a>
																	<div class="dropdown-divider"></div>
																</div>
																<a class="dropdown-item" href="#">
																	<i class="dropdown-icon mdi mdi-account-outline "></i> Profile
																</a>
																<a class="dropdown-item" href="#">
																	<i class="dropdown-icon  mdi mdi-settings"></i> Settings
																</a>
																<a class="dropdown-item" href="#">
																	<span class="float-right"></span>
																	<i class="dropdown-icon mdi  mdi-message-outline"></i> Inbox
																</a>
																<a class="dropdown-item" href="#">
																	<i class="dropdown-icon mdi mdi-comment-check-outline"></i> Message
																</a>
																<a class="dropdown-item" href="{{url('login')}}">
																	<i class="dropdown-icon mdi  mdi-logout-variant"></i> Sign out
																</a>
															</div>
														</div><!-- profile -->
														<div class="dropdown">
															<a  class="nav-link icon siderbar-link" data-toggle="sidebar-right" data-target=".sidebar-right">
																<i class="fe fe-more-horizontal"></i>
															</a>
														</div><!-- Right-siebar-->
														<a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse1">
															<span class="header-toggler-icon"></span>
														</a>
													</div>
												</div>
											</div>
										</div>
										<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse2">
											<div class="container">
												<div class="row align-items-center">
													<div class="col-lg order-lg-first">
														<ul class="nav nav-tabs border-0 flex-column flex-lg-row">
															<li class="nav-item">
																<a href="javascript:void(0)" class="nav-link " data-toggle="dropdown"><i class="fa fa-home"></i>DASHBOARD </a>
																<div class="dropdown-menu dropdown-menu-arrow" >
																	<a href="#" class="dropdown-item ">Dashboard 01</a>
																	<a href="#" class="dropdown-item ">Dashboard 02</a>
																	<a href="#" class="dropdown-item ">Dashboard 03</a>
																	<a href="#" class="dropdown-item ">Dashboard 04</a>
																</div>
															</li>
															<li class="nav-item dropdown">
																<a href="{{url('widgets')}}" class="nav-link "><i class="fa fa-window-restore"></i> WIDGETS</a>
															</li>
															<li class="nav-item">
																<a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fa fa-snowflake-o"></i>UI DESIGN</a>
																<div class="dropdown-menu dropdown-menu-arrow mega-menu">
																	<div class="row">
																		<div class="col-md-6">
																			<a href="#" class="dropdown-item ">Graph Charts</a>
																			<a href="#" class="dropdown-item ">line Charts</a>
																			<a href="#" class="dropdown-item ">Donut Charts</a>
																			<a href="#" class="dropdown-item ">Pie Charts</a>
																			<a href="#" class="dropdown-item ">Cards design</a>
																			<a href="#" class="dropdown-item ">Maps</a>
																			<a href="#" class="dropdown-item ">Sweet alert</a>
																		</div>
																		<div class="col-md-6">
																			<a href="#" class="dropdown-item ">Timeline</a>
																			<a href="#" class="dropdown-item ">Default Chat</a>
																			<a href="#" class="dropdown-item ">Counters</a>
																			<a href="#" class="dropdown-item ">Loaders</a>
																			<a href="#" class="dropdown-item ">Notifications</a>
																			<a href="#" class="dropdown-item ">Range slider</a>
																			<a href="#" class="dropdown-item ">Content Scroll bar</a>
																		</div>
																	</div>
																</div>
															</li>

															<li class="nav-item">
																<a href="javascript:void(0)" class="nav-link active" data-toggle="dropdown"><i class="fa fa-pencil-square-o"></i>UI ElEMENTS</a>
																<div class="dropdown-menu dropdown-menu-arrow mega-menu">
																	<div class="row">
																		<div class="col-md-6">
																			<a href="#" class="dropdown-item ">Alerts</a>
																			<a href="#" class="dropdown-item ">Buttons</a>
																			<a href="#" class="dropdown-item ">Colors</a>
																			<a href="#" class="dropdown-item ">Sample Charts</a>
																			<a href="#" class="dropdown-item ">Avatars</a>
																			<a href="#" class="dropdown-item ">RoundAvatars</a>
																			<a href="#" class="dropdown-item ">RadiusAvatars</a>
																			<a href="#" class="dropdown-item ">Accordion</a>
																			<a href="#" class="dropdown-item ">Dropdown</a>
																			<a href="#" class="dropdown-item ">List</a>
																			<a href="#" class="dropdown-item ">Tags</a>
																			<a href="#" class="dropdown-item ">Pagination</a>
																		</div>
																		<div class="col-md-6">
																			<a href="#" class="dropdown-item ">Modal</a>
																			<a href="#" class="dropdown-item ">Navigation</a>
																			<a href="#" class="dropdown-item ">Progress</a>
																			<a href="#" class="dropdown-item ">Typography</a>
																			<a href="#" class="dropdown-item ">Tooltip & Popover</a>
																			<a href="#" class="dropdown-item ">Breadcrumbs</a>
																			<a href="#" class="dropdown-item ">Badges</a>
																			<a href="#" class="dropdown-item ">Jumbotron</a>
																			<a href="#" class="dropdown-item ">Panel</a>
																			<a href="#" class="dropdown-item ">Thumbnails</a>
																			<a href="#" class="dropdown-item ">Mediaobject</a>
																			<a href="#" class="dropdown-item ">Tabs</a>

																		</div>
																	</div>
																</div>
															</li>
															<li class="nav-item dropdown">
																<a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fa fa-newspaper-o"></i>PAGES</a>
																<div class="dropdown-menu dropdown-menu-arrow">
																	<a href="#" class="dropdown-item ">Profile</a>
																	<a href="#" class="dropdown-item ">Edit Profile</a>
																	<a href="#" class="dropdown-item ">Login</a>
																	<a href="#" class="dropdown-item ">Register</a>
																	<a href="#" class="dropdown-item ">Forgot password</a>
																	<a href="#" class="dropdown-item ">Email</a>
																	<a href="#" class="dropdown-item ">Email Inbox</a>
																	<a href="#" class="dropdown-item ">Empty page</a>
																	<a href="#" class="dropdown-item ">Under Construction</a>
																	<a href="#" class="dropdown-item ">Lock screen</a>
																	<a href="#" class="dropdown-item ">400 error</a>
																	<a href="#" class="dropdown-item ">401 error</a>
																	<a href="#" class="dropdown-item ">403 error</a>
																	<a href="#" class="dropdown-item ">404 error</a>
																	<a href="#" class="dropdown-item ">500 error</a>
																	<a href="#" class="dropdown-item ">503 error</a>
																</div>
															</li>
															<li class="nav-item dropdown">
																<a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fa fa-file-text-o"></i> FORMS</a>
																<div class="dropdown-menu dropdown-menu-arrow">
																	<a href="#" class="dropdown-item ">Form Elements</a>
																	<a href="#" class="dropdown-item ">form-wizard Elements</a>
																	<a href="#" class="dropdown-item ">Text Editor</a>
																</div>
															</li>
															<li class="nav-item">
																<a href="#" class="nav-link"><i class="fa fa-picture-o"></i> GALLERY</a>
															</li>
															<li class="nav-item dropdown">
																<a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"><i class="fa fa-cubes"></i> COMPONENTS</a>
																<div class="dropdown-menu dropdown-menu-arrow">
																	<a href="#" class="dropdown-item ">Pricing tables</a>
																	<a href="#" class="dropdown-item ">Crypto-currencies</a>
																	<a href="#" class="dropdown-item ">User-list</a>
																	<a href="#" class="dropdown-item ">Icons</a>
																	<a href="#" class="dropdown-item ">Icons 2</a>
																	<a href="#" class="dropdown-item ">Tables</a>
																	<a href="#" class="dropdown-item ">Data Tables</a>
																	<a href="#" class="dropdown-item ">Store</a>
																	<a href="#" class="dropdown-item ">Blog</a>
																	<a href="#" class="dropdown-item ">Invoice</a>
																	<a href="#" class="dropdown-item ">Carousel</a>
																</div>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End Header-style 3-->

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