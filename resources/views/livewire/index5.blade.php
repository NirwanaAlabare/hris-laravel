@extends('layouts.app')

@section('styles')

	<!-- Morris  Charts css-->
	<link href="{{URL::asset('assets/plugins/morris/morris.css')}}" rel="stylesheet" />

@endsection

@section('content')

					    <!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Dashboard 05</li>
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
							<div class="col-sm-12 col-lg-12 col-xxl-5">
								<div class="row">
									<div class="col-sm-6 col-md-12 col-lg-6 col-xl-6">
										<div class="card">
											<div class="card-header custom-header">
												<div>
													<h3 class="card-title">Total Sales</h3>
													<h6 class="card-subtitle">Sales information</h6>
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
												<div class="chart-circle mt-2 mb-2 chart-circle-lg" data-value="0.65" data-thickness="15" data-color="#467fcf">
													<div class="chart-circle-value"><div class="h1 mb-0">65% </div></div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6 col-md-12 col-lg-6 col-xl-6">
										<div class="card">
											<div class="card-header custom-header">
												<div>
													<h3 class="card-title">Total Orders</h3>
													<h6 class="card-subtitle">Orders information</h6>
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
												<div class="chart-circle mt-2 mb-2 chart-circle-lg" data-value="0.85" data-thickness="15" data-color="#5eba00">
													<div class="chart-circle-value"><div class="h1 mb-0">75% </div></div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6 col-md-12 col-lg-6 col-xl-6">
										<div class="card">
											<div class="card-body">
												<div class="d-flex clearfix">
													<div class="text-left mt-3">
														<p class="card-text text-uppercase mb-1">Total Feedbacks</p>
														<h2 class="mb-0 text-dark mainvalue">1,345</h2>
													</div>
													<div class="ml-auto">
														<span class="bg-primary-transparent icon-service text-primary">
															<i class="si si-note  fs-2"></i>
														</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6 col-md-12 col-lg-6 col-xl-6">
										<div class="card">
											<div class="card-body">
												<div class="d-flex clearfix">
													<div class="text-left mt-3">
														<p class="card-text text-uppercase mb-1">Total Sold</p>
														<h2 class="mb-0 text-dark mainvalue">2,456K</h2>
													</div>
													<div class="ml-auto">
														<span class="bg-danger-transparent icon-service text-danger">
															<i class="si si-basket-loaded  fs-2"></i>
														</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card">
									<div class="card-header custom-header pb-0">
										<div>
											<h3 class="card-title">Sales Analyst</h3>
											<h6 class="card-subtitle">Overview top products sales information</h6>
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
										<div class="mb-5">
											<h2 class="mb-1">63.25%</h2>
											<small class="text-muted">
												<i class="zmdi zmdi-long-arrow-up zmdi-hc-lg mr-2 text-success"></i>
												<span class="text-body">+24%</span>
											</small>
											<small class="text-muted ml-2 mb-2 ">From Last Month</small>
										</div>
										<div id="details-chart2" class="chartsh"></div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-lg-12 col-xxl-7">
								<div class="card">
									<div class="card-header custom-header pb-0">
										<div>
											<h3 class="card-title">Recently Viewed</h3>
											<h6 class="card-subtitle">Overview products sales information</h6>
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
									<div class="card-body pt-3 pb-3">
										<p class="text-muted">Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam</p>
										<div class="row">
											<div class="col-lg-3 col-sm-6 text-center">
												<h6 class="mb-1 text-uppercase">Today Sales</h6>
												<h3 class="mt-2 mb-4 mainvalue font-weight-semibold">2,547</h3>
											</div>
											<div class="col-lg-3 col-sm-6 text-center">
												<h6 class="mb-1 text-uppercase">Yesterday Sales</h6>
												<h3 class="mt-2 mb-4 mainvalue font-weight-semibold">3,937</h3>
											</div>
											<div class="col-lg-3 col-sm-6 text-center">
												<h6 class="mb-1 text-uppercase">This Week Sales</h6>
												<h3 class="mt-2 mb-4 mainvalue font-weight-semibold">6,398</h3>
											</div>
											<div class="col-lg-3 col-sm-6 text-center">
												<h6 class="mb-1 text-uppercase">This Month Sales</h6>
												<h3 class="mt-2 mb-4 mainvalue font-weight-semibold">13,678</h3>
											</div>
										</div>
										<div id="details-chart" class="chartsh"></div>
									</div>
								</div>
								<div class="card">
									<div class="card-header custom-header pb-0">
										<div>
											<h3 class="card-title">Sales Analyst</h3>
											<h6 class="card-subtitle">Overview top products sales information</h6>
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
										<div id="echart1" class="chartsh chart-dropshadow"></div>
										<div class="row">
											<div class="col-lg-4 col-sm-12">
												<div class="mt-5">
													<h5 class="mb-2 d-block">
														<span class="text-uppercase fs-14">Today Sales</span>
														<span class="float-right font-weight-semibold">2,547</span>
													</h5>
													<div class="progress progress-md h-1">
														<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary w-80"></div>
													</div>
												</div>
											</div>
											<div class="col-lg-4 col-sm-12">
												<div class="mt-5">
													<h4 class="mb-2 d-block">
														<span class="text-uppercase fs-14">Yesterday Sales</span>
														<span class="float-right font-weight-semibold">3,937</span>
													</h4>
													<div class="progress progress-md h-1">
														<div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary w-80"></div>
													</div>
												</div>
											</div>
											<div class="col-lg-4 col-sm-12">
												<div class="mt-5">
													<h4 class="mb-2 d-block">
														<span class="text-uppercase fs-14">This Week Sales</span>
														<span class="float-right font-weight-semibold">6,398</span>
													</h4>
													<div class="progress progress-md h-1">
														<div class="progress-bar progress-bar-striped progress-bar-animated bg-danger w-80"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-sm-12">
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
							</div><!-- col end -->
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

		<!-- ECharts js -->
		<script src="{{URL::asset('assets/plugins/echarts/echarts.js')}}"></script>

		<!--Morris  Charts js-->
		<script src="{{URL::asset('assets/plugins/morris/raphael-min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/morris/morris.js')}}"></script>

		<!-- Custom-charts js-->
		<script src="{{URL::asset('assets/js/index5.js')}}"></script>

@endsection