@extends('layouts.app')

@section('styles')

	<!-- Owl Theme css-->
	<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet">

	<!-- Morris  Charts css-->
	<link href="{{URL::asset('assets/plugins/morris/morris.css')}}" rel="stylesheet" />


@endsection

@section('content')

					    <!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Dashboard 03</li>
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

						<div class="row row-deck">
							<div class="col-xl-6 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header custom-header pb-0">
										<div>
											<h3 class="card-title">Click  Conversion</h3>
											<h6 class="card-subtitle">Overview of Latest Month</h6>
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
										<p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt aliqua.</p>
										<div class="row mb-5">
											<div class="col-xl-3 col-lg-6 col-sm-6">
												<div class="mt-0">
													<h6 class="mb-1 text-uppercase">Total Users</h6>
													<h3 class="mb-0 mt-2 text-dark mainvalue">1,653</h3>
												</div>
											</div>
											<div class="col-xl-3 col-lg-6 col-sm-6">
												<div class="mt-4 mt-sm-0">
													<h6 class="mb-1 text-uppercase">Total Leads</h6>
													<h3 class="mb-0 mt-2 text-dark mainvalue">639</h3>
												</div>
											</div>
											<div class="col-xl-3 col-lg-6 col-sm-6">
												<div class="mt-4 mt-lg-0">
													<h6 class="mb-1 text-uppercase">Total Trials</h6>
													<h3 class="mb-0 mt-2 text-dark mainvalue">12,765</h3>
												</div>
											</div>
											<div class="col-xl-3 col-lg-6 col-sm-6">
												<div class="mt-4 mt-lg-0">
													<h6 class="mb-1 text-uppercase">Total Wins</h6>
													<h3 class="mb-0 mt-2 text-dark mainvalue">24</h3>
												</div>
											</div>
										</div>
										<div>
											<canvas id="conversion" class="chart-drop"></canvas>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-body text-center">
										<div class="mb-4">
											<h5 class="card-title">Total Customers</h5>
										</div>
										<p class="text-muted">Duis aute irure dolor in reprehenderit. Excepteur sint occaecat cupidatat non proident</p>
										<h2 class="mb-3"><i class="ti-user"></i> 5,435</h2>
										<div class="mb-4">
											<small class="text-secondary font-weight-bold mb-0">+15%</small>
											<small class="text-muted ml-2">From Last Month</small>
										</div>
										<a href="#" class="btn btn-primary">View More</a>
										<a href="#" class="btn btn-secondary">View List</a>
										<div class="chart-wrapper">
											<canvas id="total-customers" class="chart-dropshadow2 h-150"></canvas>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="mb-4">
											<h5 class="card-title">Total Conversation</h5>
										</div>
										<h2 class="mb-1">15,425</h2>
										<div class="d-flex">
											<small class="text-success font-weight-bold mb-0">+23% </small>
											<small class="text-muted ml-2">From Last Month</small>
										</div>
										<div class="chart-wrapper">
											<canvas id="total-coversations" class="chart-dropshadow-primary h-150"></canvas>
										</div>
										<div class="row mt-5">
											<div class="col-12">
												<p class="text-muted">Duis aute irure dolor in reprehenderitr.Excepteur sint occaecat cupidatat non proident</p>
											</div>
											<div class="col">
												<h4 class="font-weight-semibold mb-1">35%</h4>
												<span class="text-uppercase">Weekly</span>
											</div>
											<div class="col">
												<h4 class="font-weight-semibold mb-1">45%</h4>
												<span class="text-uppercase">Monthly</span>
											</div>
											<div class="col">
												<h4 class="font-weight-semibold mb-1">80%</h4>
												<span class="text-uppercase">Total</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xl-4">
								<div class="card">
									<div class="card-header custom-header pb-3">
										<div>
											<h3 class="card-title">Active Users</h3>
											<h6 class="card-subtitle">Users</h6>
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
											<div class="col-md-4">
												<div class="chart-circle mt-2 mb-2" data-value="0.80" data-thickness="10" data-color="#467fcf">
													<div class="chart-circle-value"><div class="fs-2">80% </div></div>
												</div>
											</div>
											<div class="col-md-8">
												<h4 class="mb-5">Active Users</h4>
												<div class="mb-5">
													<h5 class="mb-2 d-block">
														<span class="fs-16 text-muted"><b class="text-body">74,526</b> Email Accounts</span>
														<span class="float-right">80%</span>
													</h5>
													<div class="progress progress-md h-1">
														<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary w-80"></div>
													</div>
												</div>
												<div class="mb-0">
													<h5 class="mb-2 d-block">
														<span class="fs-16 text-muted"><b class="text-body">14,526</b> Requests</span>
														<span class="float-right">30%</span>
													</h5>
													<div class="progress progress-md h-1">
														<div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary w-30"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-8">
												<h4 class="mb-5">Deactive Users</h4>
												<div class="mb-5">
													<h5 class="mb-2 d-block">
														<span class="fs-16 text-muted"><b class="text-body">7,325</b> Email Accounts</span>
														<span class="float-right">20%</span>
													</h5>
													<div class="progress progress-md h-1">
														<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary w-20"></div>
													</div>
												</div>
												<div class="mb-0">
													<h5 class="mb-2 d-block">
														<span class="fs-16 text-muted"><b class="text-body">1,425</b> Directly</span>
														<span class="float-right">30%</span>
													</h5>
													<div class="progress progress-md h-1">
														<div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary w-15"></div>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="chart-circle mt-2" data-value="0.30" data-thickness="10" data-color="#5eba00">
													<div class="chart-circle-value"><div class="fs-2">30% </div></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-4">
								<div class="card">
									<div class="card-header custom-header pb-0">
										<div>
											<h3 class="card-title">Active Users Monthly</h3>
											<h6 class="card-subtitle">Users Monthly</h6>
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
										<p class="text-muted">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.Excepteur sint occaecat cupidatat non proident</p>
										<div class="chart-wrapper">
											<canvas id="sales-statistics" class="chart-dropshadow-primary h-250"></canvas>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-4">
								<div class="card">
									<div class="card-header custom-header pb-0">
										<div>
											<h3 class="card-title">Top Ongoing Projects</h3>
											<h6 class="card-subtitle">Overview this Month</h6>
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
										<p class="text-muted">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.Excepteur sint occaecat cupidatat non proident</p>
										<div class="chart-wrapper">
											<canvas id="lineChart1" class="h-250"></canvas>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row row-deck">
							<div class="col-xl-4 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header custom-header">
										<div>
											<h3 class="card-title">Acquisitions by Campaign</h3>
											<h6 class="card-subtitle">Overview of all Campaign</h6>
										</div>
										<div class="card-options d-none d-sm-block">
											<div class="btn-group btn-sm">
												<button type="button" class="btn btn-outline-white btn-sm border">
													<span class="">Today</span>
												</button>
												<button type="button" class="btn btn-outline-white btn-sm border">
													<span class="">Month</span>
												</button>
												<button type="button" class="btn btn-outline-white btn-sm border">
													<span class="">Year</span>
												</button>
											</div>
										</div>
									</div>
									<div class="card-body p-0">
										<div class="card-body">
											<h4 class="mb-1 text-center">CPC Campaign</h4>
											<canvas id="donut" class="h-400"></canvas>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-8 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header custom-header pb-0">
										<div>
											<h3 class="card-title">Products Details</h3>
											<h6 class="card-subtitle">Over of this week</h6>
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
										<div class="table-responsive">
											<table class="table table-bordered text-nowrap mb-0">
												<thead>
													<tr>
														<th>Product ID</th>
														<th>Product</th>
														<th>Product Cost</th>
														<th>Payment Mode</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><a href="#">PRO12345</a></td>
														<td>Mi LED Smart TV 4A 80</td>
														<td>$14,500</td>
														<td>Online Payment</td>
														<td><span class="badge badge-success">Available</span></td>
													</tr>
													<tr>
														<td><a href="#">PRO8765</a></td>
														<td>Thomson R9 122cm (48 inch) Full HD LED TV </td>
														<td>$30,000</td>
														<td>Cash on delivered</td>
														<td><span class="badge badge-primary">Available</span></td>
													</tr>
													<tr>
														<td><a href="#">PRO54321</a></td>
														<td>Vu 80cm (32 inch) HD Ready LED TV</td>
														<td>$13,200</td>
														<td>Online Payment</td>
														<td><span class="badge badge-warning">Limited</span></td>
													</tr>
													<tr>
														<td><a href="#">PRO97654</a></td>
														<td>Micromax 81cm (32 inch) HD Ready LED TV</td>
														<td>$15,100</td>
														<td>Cash on delivered</td>
														<td><span class="badge badge-danger">No stock</span></td>
													</tr>
													<tr>
														<td><a href="#">PRO4532</a></td>
														<td>HP 200 Mouse &amp; Wireless Laptop Keyboard </td>
														<td>$5,987</td>
														<td>Online Payment</td>
														<td><span class="badge badge-danger">No stock</span></td>
													</tr>
													<tr>
														<td><a href="#">PRO6789</a></td>
														<td>Digisol DG-HR3400 Router </td>
														<td>$11,987</td>
														<td>Cash on delivered</td>
														<td><span class="badge badge-success">Available</span></td>
													</tr>
													<tr>
														<td><a href="#">PRO4567</a></td>
														<td>Dell WM118 Wireless Optical Mouse</td>
														<td>$4,700</td>
														<td>Online Payment</td>
														<td><span class="badge badge-secondary">Available</span></td>
													</tr>
													<tr>
														<td><a href="#">PRO32156</a></td>
														<td>Dell 16 inch Laptop Backpack </td>
														<td>$678</td>
														<td>Cash On delivered</td>
														<td><span class="badge badge-cyan">Limited</span></td>
													</tr>
													<tr>
														<td><a href="#">PRO4567</a></td>
														<td>Dell WM118 Wireless Optical Mouse</td>
														<td>$4,700</td>
														<td>Online Payment</td>
														<td><span class="badge badge-secondary">Available</span></td>
													</tr>
													<tr>
														<td><a href="#">PRO32156</a></td>
														<td>Dell 16 inch Laptop Backpack </td>
														<td>$678</td>
														<td>Cash On delivered</td>
														<td><span class="badge badge-cyan">Limited</span></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title ">Projects</h3>
										<div class="card-options mr-0">
											<button id="add__new__list" type="button" class="btn btn-sm btn-primary " data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-plus"></i> Add a new Project</button>
										</div>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table class="table card-table table-striped table-vcenter table-outline table-bordered text-nowrap border-top">
												<thead>
													<tr>
														<th scope="col" class="border-top-0">ID</th>
														<th scope="col" class="border-top-0">Project Name</th>
														<th scope="col" class="border-top-0">Backend</th>
														<th scope="col" class="border-top-0">Deadline</th>
														<th scope="col" class="border-top-0">Team Members</th>
														<th scope="col" class="border-top-0">Edit Project Details </th>
														<th scope="col" class="border-top-0">list info</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>1</td>
														<td>At vero eos et accusamus et iusto odio</td>
														<td>PHP</td>
														<td>15/11/2018</td>
														<td>15 Members</td>
														<td>
															<a class="btn btn-sm btn-primary" href="#"><i class="fa fa-edit"></i> Edit</a>
															<a class="btn btn-sm btn-danger" href="#"><i class="fa fa-trash"></i> Delete</a>
														</td>
														<td><a class="btn btn-sm btn-info" href="#"><i class="fa fa-info-circle"></i> Details</a> </td>
													</tr>
													<tr>
														<td>2</td>
														<td>voluptatum deleniti atque corrupti quos</td>
														<td>Angular js</td>
														<td>25/11/2018</td>
														<td>12 Members</td>
														<td>
															<a class="btn btn-sm btn-primary" href="#"><i class="fa fa-edit"></i> Edit</a>
															<a class="btn btn-sm btn-danger" href="#"><i class="fa fa-trash"></i> Delete</a>
														</td>
														<td><a class="btn btn-sm btn-info" href="#"><i class="fa fa-info-circle"></i> Details</a> </td>
													</tr>
													<tr>
														<td>3</td>
														<td>dignissimos ducimus qui blanditiis praesentium </td>
														<td>Java</td>
														<td>5/12/2018</td>
														<td>20 Members</td>
														<td>
															<a class="btn btn-sm btn-primary" href="#"><i class="fa fa-edit"></i> Edit</a>
															<a class="btn btn-sm btn-danger" href="#"><i class="fa fa-trash"></i> Delete</a>
														</td>
														<td><a class="btn btn-sm btn-info" href="#"><i class="fa fa-info-circle"></i> Details</a> </td>
													</tr>
													<tr>
														<td>4</td>
														<td>deleniti atque corrupti quos dolores  </td>
														<td>Phython</td>
														<td>14/12/2018</td>
														<td>10 Members</td>
														<td>
															<a class="btn btn-sm btn-primary" href="#"><i class="fa fa-edit"></i> Edit</a>
															<a class="btn btn-sm btn-danger" href="#"><i class="fa fa-trash"></i> Delete</a>
														</td>
														<td><a class="btn btn-sm btn-info" href="#"><i class="fa fa-info-circle"></i> Details</a> </td>
													</tr>
													<tr>
														<td>5</td>
														<td>et quas molestias excepturi sint occaecati</td>
														<td>Phython</td>
														<td>4/12/2018</td>
														<td>17 Members</td>
														<td>
															<a class="btn btn-sm btn-primary" href="#"><i class="fa fa-edit"></i> Edit</a>
															<a class="btn btn-sm btn-danger" href="#"><i class="fa fa-trash"></i> Delete</a>
														</td>
														<td><a class="btn btn-sm btn-info" href="#"><i class="fa fa-info-circle"></i> Details</a> </td>
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
		
        <!--Owl Carousel js -->
		<script src="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/owl-carousel/owl-main.js')}}"></script>
		
        <!-- Charts js-->
		<script src="{{URL::asset('assets/plugins/chart/chart.bundle.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/chart/utils.js')}}"></script>

		<!--Time Counter js-->
		<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>
		
        <!-- Custom-charts js-->
		<script src="{{URL::asset('assets/js/index3.js')}}"></script>

@endsection