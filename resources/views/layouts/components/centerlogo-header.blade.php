<!--app-header-->
<div class="top-header hor-topheader" >
					<div class="app-header header d-flex">
						<div class="container">
							<div class="d-flex">
								<a class="header-brand" href="{{url('index')}}">
									<img src="{{URL::asset('assets/images/brand/logo.png')}}" class="header-brand-img desktop-logo" alt="Sparic logo">
                                    <img src="{{URL::asset('assets/images/brand/icon.png')}}" class="header-brand-img icon-logo mr-0" alt="Sparic logo">
                                </a><!-- logo-->
								<a id="horizontal-navtoggle" class="animated-arrow hor-toggle"><span></span></a>
								<a href="#" data-toggle="search" class="nav-link nav-link  navsearch"><i class="fa fa-search"></i></a><!-- search icon -->
								<ul class="nav navbar-nav horizontal-nav header-nav">
									<li class="mega-dropdown nav-item uikit-dropdown">
										<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
											<i class="fe fe-grid mr-2"></i>UI Kit <i class="fa fa-angle-down ml-1"></i>
										</a>
										<ul class="dropdown-menu mega-dropdown-menu container row p-5">
											<li>
												<div class="row">
													<div class="col-2">
														<h4  class="mb-3">Pages</h4>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Client Support</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> About Us</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Calendar</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Add New Pages</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Login Pages</a>
													</div>
													<div class="col-2">
														<h4  class="mb-3">Pages</h4>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Documentation</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Multi Pages</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Edit Profile</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Mail Settings</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Default Setting</a>
													</div>
													<div class="col-md-4">
														<div class="">
															<div class="card-body p-0 relative">
																<div class="arrow-ribbon">Today Events</div>
																<img class="" alt="img" src="{{URL::asset('assets/images/photos/32.png')}}">
																<div class="btn-absolute">
																	<a class="btn btn-primary  btn-sm" href="#">More info</a>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-4">
														<div class="">
															<div class="card-body p-0 relative">
																<div class="arrow-ribbon">Comming Events</div>
																<img class="" alt="img" src="{{URL::asset('assets/images/photos/32.jpg')}}">
																<div class="btn-absolute">
																	<a class="btn btn-primary  btn-sm" href="#">More info</a>
																	<span id="timer-countercallback1" class="h5 text-white float-right mb-0 mt-1"></span>
																</div>
															</div>
														</div>
													</div>
												</div>
											</li>
										</ul>
									</li>
								</ul>
								<ul class="nav navbar-nav horizontal-nav header-nav">
									<li class="mega-dropdown nav-item page-dropdown">
										<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
											<i class="fe fe-grid mr-2"></i>Pages<i class="fa fa-angle-down ml-1"></i>
										</a>
										<ul class="dropdown-menu mega-dropdown-menu container row p-5">
											<li>
												<div class="row">
													<div class="col-md-3">
														<h4  class="mb-3">Pages</h4>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Client Support</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> About Us</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Calendar</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Add New Pages</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Login Pages</a>

													</div>
													<div class="col-md-3">
														<h4  class="mb-3">Pages</h4>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Documentation</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Multi Pages</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Edit Profile</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Mail Settings</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Default Setting</a>
													</div>
													<div class="col-md-3">
														<h4  class="mb-3">Pages</h4>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Client Support</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> About Company</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Calendar</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Add New Pages</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Login Pages</a>

													</div>
													<div class="col-md-3">
														<h4  class="mb-3">Pages</h4>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Client Support</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> About Us</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Calendar</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Add New Pages</a>
														<a class="dropdown-item pl-0 pr-0" href="#"><i class="fa fa-angle-double-right text-muted mr-1"></i> Login Pages</a>
													</div>
												</div>
											</li>
										</ul>
									</li>
								</ul>
								<ul class="nav header-nav navbar-nav">
									<li class="nav-item dropdown header-option m-2 pb-3">
										<a  class="mr-2 nav-link "  id="daterange-btn">
											<span>
												<i class="fa fa-calendar"></i> Events Settings
											</span>
											<i class="fa fa-angle-down ml-1"></i>
										</a>
									</li>
								</ul>
								<a class="header-brand header-brand2 d-none d-lg-block  align-items-center justify-content-center" href="{{url('index')}}">
									<img src="{{URL::asset('assets/images/brand/logo.png')}}" class="header-brand-img main-logo" alt="Sparic logo">
                                    <img src="{{URL::asset('assets/images/brand/logo1.png')}}" class="header-brand-img darklogo" alt="Sparic logo">
                                </a>
								<div class="d-flex order-lg-2 ml-auto header-rightmenu">

									<div class="dropdown">
										<a  class="nav-link icon full-screen-link" id="fullscreen-button">
											<i class="fe fe-maximize-2"></i>
										</a>
									</div><!-- full-screen -->
									<div class="dropdown header-notify">
										<a class="nav-link icon" data-toggle="dropdown" aria-expanded="false">
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
											<span class="avatar avatar-md brround"><img src="{{URL::asset('assets/images/users/avatars/19.png')}}" alt="Profile-img" class="avatar avatar-md brround"></span>
										</a>
										<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
											<div class="header-user text-center mt-4 pb-4">
												<span class="avatar avatar-xxl brround"><img src="{{URL::asset('assets/images/users/avatars/19.png')}}" alt="Profile-img" class="avatar avatar-xxl brround"></span>
												<a href="#" class="dropdown-item text-center font-weight-semibold user h3 mb-0">Alison</a>
												<small>Web Designer</small>
											</div>
											<a class="dropdown-item" href="#">
												<i class="dropdown-icon mdi mdi-account-outline "></i> Spruko technologies
											</a>
											<a class="dropdown-item" href="#">
												<i class="dropdown-icon  mdi mdi-account-plus"></i> Add another Account
											</a>
											<div class="card-body border-top">
												<div class="row">
													<div class="col-6 text-center">
														<a class="" href=""><i class="dropdown-icon mdi  mdi-message-outline fs-30 m-0 leading-tight"></i></a>
														<div>Inbox</div>
													</div>
													<div class="col-6 text-center">
														<a class="" href=""><i class="dropdown-icon mdi mdi-logout-variant fs-30 m-0 leading-tight"></i></a>
														<div>Sign out</div>
													</div>
												</div>
											</div>
										</div>
									</div><!-- profile -->
									<div class="header-form">
										<form class="form-inline">
											<div class="search-element mr-3">
												<input class="form-control" type="search" placeholder="Search" aria-label="Search">
												<span class="Search-icon"><i class="fa fa-search"></i></span>
											</div>
										</form><!-- search-bar -->
									</div>
									<div class="dropdown">
										<a  class="nav-link icon siderbar-link" data-toggle="sidebar-right" data-target=".sidebar-right">
											<i class="fe fe-more-horizontal"></i>
										</a>
									</div><!-- Right-siebar-->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--app-header-->
