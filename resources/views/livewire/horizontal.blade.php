@extends('layouts.app')

@section('styles')

@endsection

@section('content')

			<!-- page-header -->
			<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
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

						<div class="row">
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card overflow-hidden">
									<div class="card-header custom-header pb-0">
										<div>
											<h2 class="card-title">Projects</h2>
											<h6 class="card-subtitle">Overview of this month</h6>
										</div>
										<div class="card-options">
											<label class="custom-switch">
												<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" checked>
												<span class="custom-switch-indicator"></span>
											</label>
										</div>
									</div>
									<div class="card-body">
										<p class="text-muted">Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit</p>
										<div class="row">
											<div class="col-8">
												<h6 class="text-body text-uppercase font-weight-semibold">Total Projects</h6>
												<h2 class="text-primary count mt-0 font-30 mb-0">3,456</h2>
											</div>
											<div class="col-4">
												<img src="{{URL::asset('assets/images/svg/chart.svg')}}" alt="imag" class="w-40 h-100 text-right float-right">
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card overflow-hidden">
									<div class="card-header custom-header pb-0">
										<div>
											<h3 class="card-title">Employees</h3>
											<h6 class="card-subtitle">Overview of this month</h6>
										</div>
										<div class="card-options">
											<label class="custom-switch">
												<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" >
												<span class="custom-switch-indicator"></span>
											</label>
										</div>
									</div>
									<div class="card-body">
										<p class="text-muted">Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit</p>
										<div class="row">
											<div class="col-8">
												<h6 class="text-body text-uppercase font-weight-semibold">Total Employees</h6>
												<h2 class="text-secondary count mt-0 font-30 mb-0">4,738</h2>
											</div>
											<div class="col-4">
												<img src="{{URL::asset('assets/images/svg/businessman.svg')}}" alt="imag" class="w-40 h-100 text-right float-right">
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card overflow-hidden">
									<div class="card-header custom-header pb-0">
										<div>
											<h3 class="card-title">Task</h3>
											<h6 class="card-subtitle">Overview of this month</h6>
										</div>
										<div class="card-options">
											<label class="custom-switch">
												<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input">
												<span class="custom-switch-indicator"></span>
											</label>
										</div>
									</div>
									<div class="card-body">
										<p class="text-muted">Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit</p>
										<div class="row">
											<div class="col-8">
												<h6 class="text-body text-uppercase font-weight-semibold">Total Tasks</h6>
												<h2 class="text-warning count mt-0 font-30 mb-0">6,738</h2>
											</div>
											<div class="col-4">
												<img src="{{URL::asset('assets/images/svg/list.svg')}}" alt="imag" class="w-40 h-100 text-right float-right">
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card overflow-hidden">
									<div class="card-header custom-header pb-0">
										<div>
											<h3 class="card-title">Earnings</h3>
											<h6 class="card-subtitle">Overview of this month</h6>
										</div>
										<div class="card-options">
											<label class="custom-switch">
												<input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input">
												<span class="custom-switch-indicator"></span>
											</label>
										</div>
									</div>
									<div class="card-body">
										<p class="text-muted">Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit</p>
										<div class="row">
											<div class="col-8">
												<h6 class="text-body text-uppercase font-weight-semibold">Total Earnings</h6>
												<h2 class="text-danger count mt-0 font-30 mb-0">$18,963</h2>
											</div>
											<div class="col-4">
												<img src="{{URL::asset('assets/images/svg/investment.svg')}}" alt="imag" class="w-40 h-100 text-right float-right">
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>

						<div class="row">
							<div class="col-xxl-8 col-lg-12 col-md-12">
								<div class="card icon-iconfont">
									<div class="row">
										<div class="col-lg-4 border-right">
											<div class="card-body iconfont">
												<div class="row">
													<div class="col">
														<h6 class="text-uppercase">Total Purchase</h6>
														<h3 class="mt-2 mb-1 text-dark mainvalue font-weight-semibold">$7,483</h3>
														<p class="text-muted"><span class="text-body font-weight-semibold"><i class="fa fa-arrow-up text-success mr-1"> </i>23% </span> in this year</p>
													</div>
													<div class="col col-auto feature">
														<div class="fa-stack fa-lg fa-1x border bg-primary mb-3">
															<i class="fa fa-shopping-bag fa-stack-1x text-white"></i>
														</div>
													</div>
												</div>
												<div class="progress progress-sm mb-0 mt-0">
													<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary w-50"></div>
												</div>
											</div>
										</div>
										<div class="col-lg-4 border-right">
											<div class="card-body iconfont">
												<div class="row">
													<div class="col">
														<h6 class="text-uppercase">Total Orders</h6>
														<h3 class="mt-2 mb-1 text-dark mainvalue font-weight-semibold">65,457</h3>
														<p class="text-muted"><span class="text-body font-weight-semibold"><i class="fa fa-arrow-up text-success mr-1"> </i>13% </span> in this year</p>
													</div>
													<div class="col col-auto feature">
														<div class="fa-stack fa-lg fa-1x border bg-secondary mb-3">
															<i class="fa fa-cart-plus fa-stack-1x text-white"></i>
														</div>
													</div>
												</div>
												<div class="progress progress-sm mb-0 mt-0">
													<div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary w-50"></div>
												</div>
											</div>
										</div>
										<div class="col-lg-4">
											<div class="card-body iconfont">
												<div class="row">
													<div class="col">
														<h6 class="text-uppercase">Total Sales</h6>
														<h3 class="mt-2 mb-1 text-dark mainvalue font-weight-semibold">$6,129</h3>
														<p class="text-muted"><span class="text-body font-weight-semibold"><i class="fa fa-arrow-down text-danger mr-1"> </i>12%</span> in this year</p>
													</div>
													<div class="col col-auto feature">
														<div class="fa-stack fa-lg fa-1x border bg-red mb-3">
															<i class="fa fa-usd fa-stack-1x text-white"></i>
														</div>
													</div>
												</div>
												<div class="progress progress-sm mb-0 mt-0">
													<div class="progress-bar progress-bar-striped progress-bar-animated bg-red w-70"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card overflow-hidden">
									<div class="card-header custom-header pb-0">
										<div>
											<h3 class="card-title">Sales Monthly</h3>
											<h6 class="card-subtitle">Overview of this year live charts</h6>
										</div>
										<div class="card-options">
											<a href="" class="mr-4 text-default" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
												<span class="fa fa-ellipsis-v"></span>
											</a>
											<ul class="dropdown-menu dropdown-menu-right" role="menu">
												<li><a href="#"><i class="si si-plus mr-2"></i>Add</a></li>
												<li><a href="#"><i class="si si-trash mr-2"></i>Remove</a></li>
												<li><a href="#"><i class="si si-eye mr-2"></i>View</a></li>
												<li><a href="#"><i class="si si-settings mr-2"></i>More</a></li>
											</ul>
										</div>
									</div>
									<div class="card-body">
										<div id="timeline-chart" class="h-300"></div>
										<p class="text-muted">Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example</p>
										<div class="row">
											<div class="col-md-4 mb-5 mb-md-0">
												<h6 class="mb-2 text-uppercase">Sales Weekly</h6>
												<h3 class="mb-3 font-weight-semibold">8,965</h3>
												<div class="mb-0">
													<h5 class="mb-2 d-block text-muted">
														<span class="fs-16">Weekly</span>
														<span class="float-right">55%</span>
													</h5>
													<div class="progress progress-md h-2">
														<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary w-55"></div>
													</div>
												</div>
											</div>
											<div class="col-md-4 mb-5 mb-md-0">
												<h6 class="mb-2 text-uppercase">Sales Monthly</h6>
												<h3 class="mb-3 font-weight-semibold">19,758</h3>
												<div class="mb-0">
													<h5 class="mb-2 d-block text-muted">
														<span class="fs-16">Monthly</span>
														<span class="float-right">75%</span>
													</h5>
													<div class="progress progress-md h-2">
														<div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary w-75"></div>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<h6 class="mb-2 text-uppercase">Sales Yearly</h6>
												<h3 class="mb-3 font-weight-semibold">1,52,635</h3>
												<div class="mb-0">
													<h5 class="mb-2 d-block text-muted">
														<span class="fs-16">Yearly</span>
														<span class="float-right">85%</span>
													</h5>
													<div class="progress progress-md h-2">
														<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning w-85"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xxl-4 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header custom-header pb-0">
										<div>
											<h3 class="card-title">Sales Country Wise</h3>
											<h6 class="card-subtitle">Overview of this year live charts</h6>
										</div>
										<div class="card-options">
											<a href="" class="mr-4 text-default" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
												<span class="fa fa-ellipsis-v"></span>
											</a>
											<ul class="dropdown-menu dropdown-menu-right" role="menu">
												<li><a href="#"><i class="si si-plus mr-2"></i>Add</a></li>
												<li><a href="#"><i class="si si-trash mr-2"></i>Remove</a></li>
												<li><a href="#"><i class="si si-eye mr-2"></i>View</a></li>
												<li><a href="#"><i class="si si-settings mr-2"></i>More</a></li>
											</ul>
										</div>
									</div>
									<div class="card-body">
										<div id="chart2" class="h-300"></div>
										<h5 class="mb-5 text-uppercase font-weight-semibold">Country Wise Sales</h5>
										<div class="table-responsive">
											<table class="table border table-bordered text-nowrap mb-0">
												<thead>
													<tr>
														<th>Country</th>
														<th>2019</th>
														<th>2018</th>
														<th>2017</th>
														<th>2016</th>
														<th>2015</th>
														<th>2014</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>USA</td>
														<td>44K <i class="fe fe-arrow-up text-success"></i></td>
														<td>55K <i class="fe fe-arrow-up text-success"></i></td>
														<td>41K <i class="fe fe-arrow-up text-success"></i></td>
														<td>37K <i class="fe fe-arrow-up text-success"></i></td>
														<td>22K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>43K <i class="fe fe-arrow-up text-success"></i></td>
													</tr>
													<tr>
														<td>India</td>
														<td>53K <i class="fe fe-arrow-up text-success"></i></td>
														<td>32K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>33K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>52K <i class="fe fe-arrow-up text-success"></i></td>
														<td>13K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>43K <i class="fe fe-arrow-up text-success"></i></td>
													</tr>
													<tr>
														<td>Russia</td>
														<td>12K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>17K <i class="fe fe-arrow-up text-success"></i></td>
														<td>11K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>9K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>15K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>11K <i class="fe fe-arrow-down text-danger"></i></td>
													</tr>
													<tr>
														<td>Canada</td>
														<td>9K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>7K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>5K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>8K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>6K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>9K <i class="fe fe-arrow-down text-danger"></i></td>
													</tr>
													<tr>
														<td>Germany</td>
														<td>25K <i class="fe fe-arrow-up text-success"></i></td>
														<td>12K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>19K <i class="fe fe-arrow-down text-danger"></i></td>
														<td>32K <i class="fe fe-arrow-up text-success"></i></td>
														<td>25K <i class="fe fe-arrow-up text-success"></i></td>
														<td>24K <i class="fe fe-arrow-up text-success"></i></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row row-deck">
							<div class="col-xxl-8 col-xl-12 col-lg-12">
								<div class="card">
									<div class="card-header custom-header">
										<div>
											<h3 class="card-title">Country Wise locations</h3>
											<h6 class="card-subtitle">Overview of this year live charts</h6>
										</div>
										<div class="card-options">
											<a href="" class="mr-4 text-default" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
												<span class="fa fa-ellipsis-v"></span>
											</a>
											<ul class="dropdown-menu dropdown-menu-right" role="menu">
												<li><a href="#"><i class="si si-plus mr-2"></i>Add</a></li>
												<li><a href="#"><i class="si si-trash mr-2"></i>Remove</a></li>
												<li><a href="#"><i class="si si-eye mr-2"></i>View</a></li>
												<li><a href="#"><i class="si si-settings mr-2"></i>More</a></li>
											</ul>
										</div>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-lg-5">
												<div id="chart3" class="mn-auto"></div>
											</div>
											<div class="col-lg-7">
												<div class="table-responsive">
													<table class="table border table-bordered text-nowrap border-0">
														<tbody>
															<tr>
																<td><img src="{{URL::asset('assets/images/flags/us.svg')}}" class="w-5 h-5 text-center mx-auto d-block" alt=""></td>
																<td><h5 class="mb-0 fs-14 text-uppercase">USA</h5></td>
																<td><h5 class="mb-0 fs-14">53 Locations</h5></td>
															</tr>
															<tr>
																<td><img src="{{URL::asset('assets/images/flags/in.svg')}}" class="w-5 h-5 text-center mx-auto d-block" alt=""></td>
																<td><h5 class="mb-0 fs-14 text-uppercase">India</h5></td>
																<td><h5 class="mb-0 fs-14">39 Locations</h5></td>
															</tr>
															<tr>
																<td><img src="{{URL::asset('assets/images/flags/ru.svg')}}" class="w-5 h-5 text-center mx-auto d-block" alt=""></td>
																<td><h5 class="mb-0 fs-14 text-uppercase">Russia</h5></td>
																<td><h5 class="mb-0 fs-14">25 Locations</h5></td>
															</tr>
															<tr>
																<td><img src="{{URL::asset('assets/images/flags/ca.svg')}}" class="w-5 h-5 text-center mx-auto d-block" alt=""></td>
																<td><h5 class="mb-0 fs-14 text-uppercase">Canada</h5></td>
																<td><h5 class="mb-0 fs-14">23 Locations</h5></td>
															</tr>
															<tr>
																<td><img src="{{URL::asset('assets/images/flags/ge.svg')}}" class="w-5 h-5 text-center mx-auto d-block border p-0" alt=""></td>
																<td><h5 class="mb-0 fs-14 text-uppercase">Germany</h5></td>
																<td><h5 class="mb-0 fs-14">42 Locations</h5></td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<p class="text-muted"> If You want See More locations of any country first login to <a hef="">Sparic.in</a> and find More locations. And  add More locations.It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
									</div>
								</div>
							</div>
							<div class="col-xxl-4 col-xl-12 col-lg-12 col-md-12">
								<div class="card">
									<a href="#"><img class="card-img-top br-tl-7 br-tr-7" src="{{URL::asset('assets/images/photos/6.jpg')}}" alt="img"></a>
									<div class="card-body d-flex flex-column">
										<small class="text-muted mb-1"><i class="fe fe-calendar mr-1"></i> 25 Feb 2019</small>
										<h4><a href="#">voluptatem quia voluptas.</a></h4>
										<div class="text-muted">To take a trivial example, which of us ever undertakes laborious physical exerciser</div>
										<a href="" class=" mt-3 btn btn-sm btn-primary">Read more</a>
									</div>
								</div>
							</div>
							<div class="col-xxl-8 col-xl-6 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header custom-header">
										<div>
											<h3 class="card-title">Comments</h3>
											<h6 class="card-subtitle">Overview of this week</h6>
										</div>
										<div class="card-options">
											<a href="" class="mr-4 text-default" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
												<span class="fa fa-ellipsis-v"></span>
											</a>
											<ul class="dropdown-menu dropdown-menu-right" role="menu">
												<li><a href="#"><i class="si si-plus mr-2"></i>Add</a></li>
												<li><a href="#"><i class="si si-trash mr-2"></i>Remove</a></li>
												<li><a href="#"><i class="si si-eye mr-2"></i>View</a></li>
												<li><a href="#"><i class="si si-settings mr-2"></i>More</a></li>
											</ul>
										</div>
									</div>
									<div class="card-body p-0 ">
										<div class="list-group list-lg-group list-group-flush">
											<a class="list-group-item list-group-item-action" href="#">
												<div class="media mt-0">
													<img class="avatar-xxl rounded-circle mr-3" src="{{URL::asset('assets/images/users/female/2.jpg')}}" alt="Image description">
													<div class="media-body">
														<div class="d-md-flex align-items-center">
															<h4 class="mb-3">
																Samantha Wilson
															</h4>
															<small class="text-primary ml-md-auto"><i class="fe fe-calendar mr-1"></i> 28 Feb 2019</small>
														</div>
														<p class="mb-0 text-muted">Itaque earum rerum hic tenetur a sapiente reiciendis voluptatibus.</p>
													</div>
												</div>
											</a>
											<a class="list-group-item list-group-item-action" href="#">
												<div class="media mt-0">
													<img class="avatar-xxl rounded-circle mr-3" src="{{URL::asset('assets/images/users/male/2.jpg')}}" alt="Image description">
													<div class="media-body">
														<div class="d-md-flex align-items-center">
															<h4 class="mb-3">
																Kevin North
															</h4>
															<small class="text-primary ml-md-auto"><i class="fe fe-calendar mr-1"></i> 26 Feb 2019</small>
														</div>
														<p class="mb-0 text-muted">Itaque earum rerum hic tenetur a sapiente reiciendis voluptatibus.</p>
													</div>
												</div>
											</a>
											<a class="list-group-item list-group-item-action" href="#">
												<div class="media mt-0">
													<img class="avatar-xxl rounded-circle mr-3" src="{{URL::asset('assets/images/users/male/12.jpg')}}" alt="Image description">
													<div class="media-body">
														<div class="d-md-flex align-items-center">
															<h4 class="mb-3">
																Steven Fisher
															</h4>
															<small class="text-primary ml-md-auto"><i class="fe fe-calendar mr-1"></i> 26 Feb 2019</small>
														</div>
														<p class="mb-0 text-muted">Itaque earum rerum hic tenetur a sapiente reiciendis voluptatibus.</p>
													</div>
												</div>
											</a>
											<a class="list-group-item list-group-item-action" href="#">
												<div class="media mt-0">
													<img class="avatar-xxl rounded-circle mr-3" src="{{URL::asset('assets/images/users/male/12.jpg')}}" alt="Image description">
													<div class="media-body">
														<div class="d-md-flex align-items-center">
															<h4 class="mb-3">
																Steven Fisher
															</h4>
															<small class="text-primary ml-md-auto"><i class="fe fe-calendar mr-1"></i> 26 Feb 2019</small>
														</div>
														<p class="mb-0 text-muted">Itaque earum rerum hic tenetur a sapiente reiciendis voluptatibus.</p>
													</div>
												</div>
											</a>
											<a class="list-group-item list-group-item-action br-br-7 br-bl-7" href="#">
												<div class="media mt-0">
													<img class="avatar-xxl rounded-circle mr-3" src="{{URL::asset('assets/images/users/female/5.jpg')}}" alt="Image description">
													<div class="media-body">
														<div class="d-md-flex align-items-center">
															<h4 class="mb-3">
																Joanne Taylor
															</h4>
															<small class="text-primary ml-md-auto"><i class="fe fe-calendar mr-1"></i> 25 Feb 2019</small>
														</div>
														<p class="mb-0 text-muted">Itaque earum rerum hic tenetur a sapiente reiciendis voluptatibus.</p>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xxl-4  col-xl-6 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header custom-header pb-0">
										<div>
											<h3 class="card-title">Company Status</h3>
											<h6 class="card-subtitle">Overview of this year</h6>
										</div>
										<div class="card-options">
											<a href="" class="mr-4 text-default" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
												<span class="fa fa-ellipsis-v"></span>
											</a>
											<ul class="dropdown-menu dropdown-menu-right" role="menu">
												<li><a href="#"><i class="si si-plus mr-2"></i>Add</a></li>
												<li><a href="#"><i class="si si-trash mr-2"></i>Remove</a></li>
												<li><a href="#"><i class="si si-eye mr-2"></i>View</a></li>
												<li><a href="#"><i class="si si-settings mr-2"></i>More</a></li>
											</ul>
										</div>
									</div>
									<div class="card-body">
										<p class="mb-0 text-muted">Itaque earum rerum hic tenetur a sapiente reiciendis voluptatibus.</p>
										<div id="pieChart" class="mn-auto"></div>

									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-xl-3 col-lg-12 col-md-12">
								<div class="card">
									<a href="#"><img class="card-img-top br-tl-7 br-tr-7" src="{{URL::asset('assets/images/photos/6.jpg')}}" alt="img"></a>
									<div class="card-body d-flex flex-column">
										<small class="text-muted mb-1"><i class="fe fe-calendar mr-1"></i> 25 Feb 2019</small>
										<h4><a href="#">voluptatem quia voluptas.</a></h4>
										<div class="text-muted">To take a trivial example, which of us ever undertakes laborious physical exerciser</div>
										<a href="" class=" mt-3 btn btn-sm btn-primary">Read more</a>
									</div>
								</div>
							</div>
							<div class="col-xl-9 col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header custom-header pb-0">
										<div>
											<h3 class="card-title">Marketing  Campaign</h3>
											<h6 class="card-subtitle">Overview of all marketing vales</h6>
										</div>
										<div class="card-options d-none d-sm-block">
											<div class="btn-group btn-sm">
												<button type="button" class="btn btn-light btn-sm">
													<span class="">Today</span>
												</button>
												<button type="button" class="btn btn-light btn-sm">
													<span class="">Month</span>
												</button>
												<button type="button" class="btn btn-light btn-sm">
													<span class="">Year</span>
												</button>
											</div>
										</div>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table class="table border table-bordered text-nowrap mb-0">
												<thead>
													<tr>
														<th>NO</th>
														<th>ICON</th>
														<th>Currency</th>
														<th>Price</th>
														<th>Market Cap</th>
														<th>Volume 1D</th>
														<th>Change % (1D)</th>
														<th>Change % (1W)</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>1</td>
														<td><img src="{{URL::asset('assets/images/crypto-currencies/bitcoin.svg')}}" class="w-5 h-5" alt=""></td>
														<td>Bitcoin</td>
														<td>$1.67</td>
														<td>$61,191,183,730</td>
														<td>$10,133,400,000</td>
														<td><span class="badge badge-pill badge-primary"><i class="fe fe-chevron-up"></i> 17.66%</span></td>
														<td><span class="badge badge-pill badge-success"><i class="fe fe-chevron-down"></i> -15.25%</span></td>

													</tr>
													<tr>
														<td>2</td>
														<td><img src="{{URL::asset('assets/images/crypto-currencies/dash.svg')}}" class="w-5 h-5" alt=""></td>
														<td>Dash</td>
														<td>$865.25</td>
														<td>$6,778,308,110</td>
														<td>$193,430,000</td>
														<td><span class="badge badge-pill  badge-primary"><i class="fe fe-chevron-up"></i> 30.80%</span></td>
														<td><span class="badge  badge-pill badge-success"><i class="fe fe-chevron-down"></i> -16.40%</span></td>
													</tr>
													<tr>
														<td>3</td>
														<td><img src="{{URL::asset('assets/images/crypto-currencies/euro.svg')}}" class="w-5 h-5" alt=""></td>
														<td>Euro</td>
														<td>$0.70</td>
														<td>$17,633,890,043</td>
														<td>$1,677,430,000</td>
														<td><span class="badge badge-pill  badge-primary"><i class="fe fe-chevron-up"></i> 40.79%</span></td>
														<td><span class="badge badge-pill  badge-success"><i class="fe fe-chevron-down"></i> -5.81%</span></td>
													</tr>
													<tr>
														<td>3</td>
														<td><img src="{{URL::asset('assets/images/crypto-currencies/qtum.svg')}}" class="w-5 h-5" alt=""></td>
														<td>Qtum</td>
														<td>$1.67</td>
														<td>$61,191,183,730</td>
														<td>$10,133,400,000</td>
														<td><span class="badge badge-pill  badge-primary"><i class="fe fe-chevron-up"></i> 17.66%</span></td>
														<td><span class="badge badge-pill badge-success"><i class="fe fe-chevron-down"></i> -15.25%</span></td>
													</tr>
													<tr>
														<td>4</td>
														<td><img src="{{URL::asset('assets/images/crypto-currencies/ripple.svg')}}" class="w-5 h-5" alt=""></td>
														<td>Ripple</td>
														<td>$11,723.48</td>
														<td>$179,078,267,295</td>
														<td>$17,959,900,000</td>
														<td><span class="badge badge-pill badge-primary"><i class="fe fe-chevron-up"></i> 66.26%</span></td>
														<td><span class="badge badge-pill badge-success"><i class="fe fe-chevron-down"></i> -16.48%</span></td>
													</tr>
													<tr>
														<td>5</td>
														<td><img src="{{URL::asset('assets/images/crypto-currencies/stellar.svg')}}" class="w-5 h-5" alt=""></td>
														<td>Stellar</td>
														<td>$149.18</td>
														<td>$9,644,490,000</td>
														<td>$1,310,130,000</td>
														<td><span class="badge badge-pill badge-primary"><i class="fe fe-chevron-up"></i> 36.98%</span></td>
														<td><span class="badge  badge-pill badge-success"><i class="fe fe-chevron-down"></i> 31.09%</span></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>

@endsection('content')

@section('scripts')

		<!--Jquery Sparkline js-->
		<script src="{{URL::asset('assets/plugins/vendors/jquery.sparkline.min.js')}}"></script>

		<!-- Chart Circle js-->
		<script src="{{URL::asset('assets/plugins/vendors/circle-progress.min.js')}}"></script>

		<!--Time Counter js-->
		<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>

		<!-- ApexChart -->
		<script src="{{URL::asset('assets/plugins/apexcharts/apexcharts.js')}}"></script>

		<!-- Charts js-->
		<script src="{{URL::asset('assets/plugins/chart/chart.bundle.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/chart/utils.js')}}"></script>

		<!--Peitychart js-->
		<script src="{{URL::asset('assets/plugins/peitychart/jquery.peity.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/peitychart/peitychart.init.js')}}"></script>

		<!-- Custom-charts js-->
		<script src="{{URL::asset('assets/js/index1.js')}}"></script>

@endsection
