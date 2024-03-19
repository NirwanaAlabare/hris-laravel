@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Widgets</li>
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
							<div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
								<div class="card card-counter bg-danger">
									<div class="card-body">
										<div class="row">
											<div class="col-4">
												<i class="si si-eye mt-3 mb-0 text-white-transparent"></i>
											</div>
											<div class="col-8 text-center">
												<div class="mt-2 mb-0 text-white">
													<h2 class="mb-0">54,234</h2>
													<p class="text-white mt-1 mb-0">Daily Visitors </p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
								<div class="card card-counter bg-secondary">
									<div class="card-body">
										<div class="row">
											<div class="col-4">
												<i class="si si-basket mt-3 mb-0 text-white-transparent"></i>
											</div>
											<div class="col-8 text-center">
												<div class="mt-2 mb-0 text-white">
													<h2 class="mb-0">80,956</h2>
													<p class="text-white mt-1 mb-0">Daily Orders </p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
								<div class="card card-counter bg-primary">
									<div class="card-body">
										<div class="row">
											<div class="col-4">
												<i class="si si-people mt-3 mb-0 text-white-transparent"></i>
											</div>
											<div class="col-8 text-center">
												<div class="mt-2 mb-0 text-white">
													<h2 class="mb-0">34,762</h2>
													<p class="text-white mt-1 mb-0">Daily  Customers</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
								<div class="card card-counter bg-warning">
									<div class="card-body">
										<div class="row">
											<div class="col-4">
												<i class="si si-paper-plane mt-3 mb-0 text-white-transparent"></i>
											</div>
											<div class="col-8 text-center">
												<div class="mt-2 mb-0 text-white">
													<h2 class="mb-0">25,789</h2>
													<p class="text-white mt-1 mb-0">Daily Revenue</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>

						<div class="row">
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card">
									<div class="row">
										<div class="col-4">
											<div class="feature">
												<div class="fa-stack fa-lg fa-2x icon bg-purple">
													<i class="fa fa-bed fa-stack-1x text-white"></i>
												</div>
											</div>
										</div>
										<div class="col-8">
											<div class="card-body p-3  d-flex">
												<div>
													<p class="text-muted mb-2">Total Patients</p>
													<h2 class="mb-0 text-dark">23,786K</h2>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card">
									<div class="row">
										<div class="col-4">
											<div class="feature">
												<div class="fa-stack fa-lg fa-2x icon bg-green">
													<i class="fa fa-user-md fa-stack-1x text-white"></i>
												</div>
											</div>
										</div>
										<div class="col-8">
											<div class="card-body p-3  d-flex">
												<div>
													<p class="text-muted mb-2">Total Staff</p>
													<h2 class="mb-0 text-dark">5,752</h2>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card">
									<div class="row">
										<div class="col-4">
											<div class="feature">
												<div class="fa-stack fa-lg fa-2x icon bg-orange">
													<i class="fa fa-hospital-o fa-stack-1x text-white"></i>
												</div>
											</div>
										</div>
										<div class="col-8">
											<div class="card-body p-3  d-flex">
												<div>
													<p class="text-muted mb-2">Total Rooms</p>
													<h2 class="mb-0 text-dark">34,678</h2>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card">
									<div class="row">
										<div class="col-4">
											<div class="feature">
												<div class="fa-stack fa-lg fa-2x icon bg-yellow">
													<i class="fa fa-flask fa-stack-1x text-white"></i>
												</div>
											</div>
										</div>
										<div class="col-8">
											<div class="card-body p-3  d-flex">
												<div>
													<p class="text-muted mb-2">Total Labs</p>
													<h2 class="mb-0 text-dark">456</h2>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>

						<div class="row">
							<div class="col-xl-4 col-md-12">
								<div class="card card-img-holder">
									<div class="card-body">
										<div class="clearfix">
											<div class="float-left">
												<p class="text-muted mb-1">Total Purchase</p>
												<h1 class="mb-0 text-dark mainvalue">$7,483</h1>
											</div>
											<div class="float-right text-right mt-2">
												<span class="pie" data-peity='{ "fill": ["#467fcf", "#ebe9f7"]}'>0.52/1.561</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-4 col-md-12">
								<div class="card card-img-holder">
									<div class="card-body">
										<div class="clearfix">
											<div class="float-left">
												<p class="text-muted mb-1">Total Sales</p>
												<h1 class="mb-0 text-dark mainvalue">$6,129</h1>
											</div>
											<div class="float-right text-right mt-2">
												<span class="pie" data-peity='{ "fill": ["#5eba00", "#ebe9f7"]}'>226/360</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-4 col-md-12">
								<div class="card card-img-holder">
									<div class="card-body">
										<div class="clearfix">
											<div class="float-left">
												<p class="text-muted mb-1">Total Profits</p>
												<h1 class="mb-0 text-dark mainvalue">10%</h1>
											</div>
											<div class="float-right text-right mt-2">
												<span class="pie" data-peity='{ "fill": ["#e34a42", "#ebe9f7"]}'>226/360</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<div class="d-flex clearfix">
											<div class="text-left mt-3">
												<p class="card-text text-muted mb-1">Total Orders</p>
												<h2 class="mb-0 text-dark mainvalue">6,895</h2>
											</div>
											<div class="ml-auto">
												<span class="bg-primary-transparent icon-service text-primary ">
													<i class="si si-briefcase  fs-2"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<div class="d-flex clearfix">
											<div class="text-left mt-3">
												<p class="card-text text-muted mb-1">Total Products</p>
												<h2 class="mb-0 text-dark mainvalue">8,379</h2>
											</div>
											<div class="ml-auto">
												<span class="bg-success-transparent icon-service text-success">
													<i class="si si-layers fs-2"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<div class="d-flex clearfix">
											<div class="text-left mt-3">
												<p class="card-text text-muted mb-1">Total Feedbacks</p>
												<h2 class="mb-0 text-dark mainvalue">1,345</h2>
											</div>
											<div class="ml-auto">
												<span class="bg-danger-transparent icon-service text-danger">
													<i class="si si-note  fs-2"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<div class="d-flex clearfix">
											<div class="text-left mt-3">
												<p class="card-text text-muted mb-1">Total Sold</p>
												<h2 class="mb-0 text-dark mainvalue">2,456K</h2>
											</div>
											<div class="ml-auto">
												<span class="bg-warning-transparent icon-service text-warning">
													<i class="si si-basket-loaded  fs-2"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- row -->
						<div class="row ">
							<div class="col-lg-12">
								<div class="card ">
									<div class="card-body p-4 text-dark">
										<div class="statistics-info">
											<div class="row text-center">
												<div class="col-lg-3 col-md-6 mt-4 mb-4">
													<div class="counter-status">
														<div class="counter-icon text-danger">
															<i class="si si-people"></i>
														</div>
														<h5 class="text-muted">EMPLOYEE'S</h5>
														<h2 class="counter text-primary mb-0">2569</h2>
													</div>
												</div>
												<div class="col-lg-3 col-md-6 mt-4 mb-4">
													<div class="counter-status">
														<div class="counter-icon text-warning">
															<i class="si si-rocket"></i>
														</div>
														<h5 class="text-muted">Total Sales</h5>
														<h2 class="counter text-primary  mb-0">1765</h2>
													</div>
												</div>
												<div class="col-lg-3 col-md-6  mt-4 mb-4">
													<div class="counter-statuss">
														<div class="counter-icon text-primary">
															<i class="si si-docs"></i>
														</div>
														<h5 class="text-muted">Total Projects</h5>
														<h2 class="counter text-primary  mb-0">846</h2>
													</div>
												</div>
												<div class="col-lg-3 col-md-6 mt-4 mb-4">
													<div class="counter-status">
														<div class="counter-icon text-success">
															<i class="si si-graph"></i>
														</div>
														<h5 class="text-muted">Growth</h5>
														<h2 class="counter text-primary  mb-0">7253</h2>
													</div>
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
							<div class="col-lg-6 col-xl-3 col-md-6 col-12">
								<div class="card">
									<div class="card-body">
										<div class=" ">
											<h5>First call resolution</h5>
										</div>
										<h2 class="mb-2">6,382<span class="sparkline_bar1 float-right"></span></h2>
										<div><i class="fa fa-arrow-circle-o-up  text-success"></i> 30% Increase</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-6 col-xl-3 col-md-6 col-12">
								<div class="card">
									<div class="card-body">
										<div class=" ">
											<h5>Unresolved calls</h5>
										</div>
										<h2 class="mb-2">654<span class="sparkline_bar2 float-right"></span></h2>
										<div><i class="fa fa-arrow-circle-o-up  text-success"></i> 10% Increase</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-6 col-xl-3 col-md-6 col-12">
								<div class="card">
									<div class="card-body">
										<div class=" ">
											<h5>Avg Response time</h5>
										</div>
										<h2 class="mb-2">7,637<span class="sparkline_bar3 float-right"></span></h2>
										<div><i class="fa fa-arrow-circle-o-down  text-danger"></i> 8% Decrease</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-6 col-xl-3 col-md-6 col-12">
								<div class="card">
									<div class="card-body">
										<div class=" ">
											<h5>Candidates Placed</h5>
										</div>
										<h2 class="mb-2">$7,850<span class="sparkline_bar4 float-right"></span></h2>
										<div><i class="fa fa-arrow-circle-o-up  text-success"></i> 15% Increase</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
                        <div class="row row-cards">
							<div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
							    <div class="card">
									<div class="card-body text-center list-icons">
										<i class="si si-briefcase text-primary"></i>
										<p class="card-text mt-3 mb-3">Total Projects</p>
										<p class="h1 text-center  text-primary">459</p>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
								<div class="card">
									<div class="card-body text-center list-icons">
										<i class="si si-basket-loaded text-secondary"></i>
										<p class="card-text mt-3 mb-3">New Sales</p>
										<p class="h1 text-center  text-secondary">262</p>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
							    <div class="card">
									<div class="card-body text-center list-icons">
										<i class="si si-people text-warning"></i>
										<p class="card-text mt-3 mb-3">Employees</p>
										<p class="h1 text-center  text-warning">789</p>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-6 col-lg-3 col-xl-3">
							    <div class="card">
									<div class="card-body text-center list-icons">
										<i class="si si-eye text-success"></i>
										<p class="card-text mt-3 mb-3">Customer Visitis</p>
										<p class="h1 text-center text-success">2635</p>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row row-cards">
							<div class="col-sm-12 col-lg-4">
								<div class="card">
									<div class="card-body iconfont text-center">
										<h4>Total Participations</h4>
										<h1 class="mb-1 text-dark">4598</h1>
										<p><span class="text-purple"><i class="fa fa-arrow-up text-success"> </i>23% increase</span> in Last week</p>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-4">
								<div class="card">
									<div class="card-body iconfont text-center">
										<h4>Total Tournaments</h4>
										<h1 class="mb-1 ">$92.72</h1>
										<p><span class="text-purple"><i class="fa fa-arrow-down text-green"></i> 4 lead less</span> than last week</p>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-4">
								<div class="card">
									<div class="card-body iconfont text-center">
										<h4>Contribution</h4>
										<h1 class="mb-1">789</h1>
										<p><span class="text-purple"><i class="fa fa-arrow-up text-blue"></i> 2.15% less</span> than 1 year ago</p>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row ">
							<div class="col-xl-3 col-md-6">
								<div class="card text-center">
									<div class="card-body">
										<h5 class="mb-3 font-weight-600">Gross profit Margin</h5>
										<div class="chart-circle" data-value="0.75" data-thickness="10" data-color="rgba(96, 82, 159,0.9)">
											<div class="chart-circle-value"><div class="text-xl">75% </div></div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-xl-3 col-md-6">
								<div class="card text-center">
									<div class="card-body">
										<h5 class="mb-3 font-weight-600">Opex Ratio</h5>
										<div class="chart-circle" data-value="0.55" data-thickness="10" data-color="rgba(50, 202, 254,0.9)">
											<div class="chart-circle-value"><div class="text-xl">55%</div></div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-xl-3 col-md-6">
								<div class="card text-center">
									<div class="card-body">
										<h5 class="mb-3 font-weight-600">Operating Profit Margin</h5>
										<div class="chart-circle" data-value="0.30" data-thickness="10" data-color="rgba(255, 204, 0, 0.9)">
											<div class="chart-circle-value"><div class="text-xl">30%</div></div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-xl-3 col-md-6">
								<div class="card text-center">
									<div class="card-body">
										<h5 class="mb-3 font-weight-600">Net Profit Margin</h5>
										<div class="chart-circle" data-value="0.45" data-thickness="10" data-color="rgba(34, 192, 60,0.9)">
											<div class="chart-circle-value"><div class="text-xl">45%</div></div>
										</div>
									</div>
								</div>
							</div>
						</div><!-- col end -->
						<!-- row end -->

                        <!-- row -->
                       <div class="row row-cards">
							<div class="col-sm-12 col-lg-6 col-md-6 col-xl-3 ">
								<div class="card card-img-holder">
								    <div class="card-body">
										<p class="card-text text-dark font-weight-semibold mb-1">Total Projects</p>
										<div class="clearfix">
											<div class="float-left  mt-2">
												<h1>10,456</h1>
											</div>
											<div class="float-right text-right">
												<span class="pie" data-peity='{ "fill": ["#6352a0", "#ebe9f7"]}'>226,134</span>
											</div>
										</div>
										<div class="progress progress-md mt-1 h-2">
											<div class="progress-bar w-70 bg-gradient-primary"></div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-6 col-md-6 col-xl-3">
								<div class="card card-img-holder">
								    <div class="card-body">
										<p class="card-text text-dark font-weight-semibold mb-1">Projects Submitted</p>
										<div class="clearfix">
											<div class="float-left  mt-2">
												<h1>5,356</h1>
											</div>
											<div class="float-right text-right">
												<span class="pie" data-peity='{ "fill": ["#32cafe", "#ebe9f7"]}'>1,4</span>
											</div>
										</div>
										<div class="progress progress-md mt-1 h-2">
											<div class="progress-bar w-50  bg-gradient-secondary"></div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-6 col-md-6 col-xl-3">
							    <div class="card card-img-holder">
								    <div class="card-body">
										<p class="card-text text-dark font-weight-semibold mb-1">Ongoing Projects</p>
										<div class="clearfix">
											<div class="float-left  mt-2">
												<h1>2,345</h1>
											</div>
											<div class="float-right text-right">
												<span class="pie" data-peity='{ "fill": ["#5ed84f", "#ebe9f7"]}'>0.52/1.561</span>
											</div>
										</div>
										<div class="progress progress-md mt-1 h-2">
											<div class="progress-bar w-30 bg-gradient-success"></div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-6 col-md-6 col-xl-3">
							    <div class="card card-img-holder">
								    <div class="card-body">
										<p class="card-text text-dark font-weight-semibold mb-1">Task Completed</p>
										<div class="clearfix">
											<div class="float-left  mt-2">
												<h1>2,678</h1>
											</div>
											<div class="float-right text-right">
												<span class="pie" data-peity='{ "fill": ["#fdb901", "#ebe9f7"]}'>0.52,1.041</span>
											</div>
										</div>
										<div class="progress progress-md mt-1 h-2">
											<div class="progress-bar  progress-bar-animated w-60 bg-warning"></div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

                        <!-- row -->
						<div class="row row-cards">
							<div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
								<div class="card card-counter bg-gradient-purple">
									<div class="card-body">
										<div class="row">
											<div class="col-8">
												<div class="mt-4 mb-0 text-white">
													<h3 class="mb-0">80,956</h3>
													<p class="text-white mt-1">Total Graph </p>
												</div>
											</div>
											<div class="col-4">
												<i class="fa fa-line-chart mt-3 mb-0 text-white-transparent"></i>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
								<div class="card card-counter bg-gradient-secondary">
									<div class="card-body">
										<div class="row">
											<div class="col-8">
												<div class="mt-4 mb-0 text-white">
													<h3 class="mb-0">54,234</h3>
													<p class="text-white mt-1">Requesting Projects </p>
												</div>
											</div>
											<div class="col-4">
												<i class="fa fa-sign-out mt-3 mb-0 text-white-transparent"></i>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
								<div class="card card-counter bg-gradient-warning">
									<div class="card-body">
										<div class="row">
											<div class="col-8">
												<div class="mt-4 mb-0 text-white">
													<h3 class="mb-0">25,789</h3>
													<p class="text-white mt-1 ">Requests Receiving</p>
												</div>
											</div>
											<div class="col-4">
												<i class="fa fa-reply-all mt-3 mb-0 text-white-transparent"></i>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-xl-3 col-lg-6 col-md-12 col-sm-12">
								<div class="card card-counter bg-gradient-success">
									<div class="card-body">
										<div class="row">
											<div class="col-8">
												<div class="mt-4 mb-0 text-white">
													<h3 class="mb-0">34,762</h3>
													<p class="text-white mt-1">Supported projects </p>
												</div>
											</div>
											<div class="col-4">
												<i class="fa fa-suitcase mt-3 mb-0 text-white-transparent"></i>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row row-cards">
							<div class="col-lg-6 col-xl-3 col-md-6 col-sm-12">
								<div class="card text-center">
									<div class="card-body text-center">
                                        <h5 class="mb-5">Tournaments</h5>
									    <span class="bar" data-peity='{ "fill": ["#ff695c", "#ff4f7b"]}'>6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span>
								    </div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-6 col-xl-3 col-md-6 col-sm-12">
								<div class="card text-center">
									<div class="card-body text-center">
                                        <h5 class="mb-5">Participating IN Games</h5>
									    <span class="bar" data-peity='{ "fill": ["#32cafe ", "#3582ec"]}'>5,3,9,6,5,9,7,3,5,2,5,3,9,6,5,9,3,7,2,9</span>
								    </div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-6 col-xl-3 col-md-6 col-sm-12">
								<div class="card text-center">
									<div class="card-body text-center ">
                                        <h5 class="mb-5">Monthly Players</h5>
									    <span class="bar" data-peity='{ "fill": ["#ecd53e", "#efaf28"]}'>3,7,9,4,2,8,4,6,4,9,2,3,9,4,1,7,3,9,8,4,5</span>
								    </div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-6 col-xl-3 col-md-6 col-sm-12">
								<div class="card text-center">
									<div class="card-body text-center">
                                        <h5 class="mb-5">Weekly Players</h5>
									    <span class="bar" data-peity='{ "fill": ["#63d457", "#3cbf2d"]}'>2,7,3,9,4,5,2,8,4,6,5,2,8,4,7,2,4,6,4,8,4</span>
								    </div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<h3 class="mb-1">568</h3>
										<div class="text-muted">Online Projects</div>
										<div class="progress progress-md mt-4">
											<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary w-35"></div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<h3 class="mb-1">761</h3>
										<div class="text-muted">Sales Of Projects</div>
										<div class="progress progress-md mt-4">
											<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning w-55"></div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<h3 class="mb-1">234</h3>
										<div class="text-muted"> Offline Projects</div>
										<div class="progress progress-md mt-4">
											<div class="progress-bar progress-bar-striped progress-bar-animated bg-info w-70"></div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
								<div class="card">
									<div class="card-body">
										<h3 class="mb-1">897</h3>
										<div class="text-muted"> Income</div>
										<div class="progress progress-md mt-4">
											<div class="progress-bar progress-bar-striped progress-bar-animated bg-secondary w-85"></div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-md-12 col-xl-3 col-lg-6">
								<div class="card text-center bg-gradient-primary text-white">
									<div class="card-body"> <h5>Monthly Orders</h5>
									  <h3 class="display-5 mb-1 mt-1">1432</h3>
									  <div><i class="si si-arrow-up-circle text-white"></i> <span class="text-white">25%</span> Increase</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-md-12 col-xl-3 col-lg-6">
								<div class="card text-center bg-gradient-success text-white">
									<div class="card-body"> <h5>Monthly Sales</h5>
									  <h3 class="display-5 mb-1 mt-1">1452</h3>
									  <div><i class="si si-arrow-up-circle text-white"></i> <span class="text-white">54%</span> Increase</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-md-12 col-xl-3 col-lg-6">
								<div class="card text-center bg-gradient-info text-white">
									<div class="card-body"> <h5>Monthly Profit</h5>
									  <h3 class="display-5 mb-1 mt-1">$13288</h3>
									  <div><i class="si si-arrow-up-circle text-white"></i> <span class="text-white">22%</span> Increase</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-md-12 col-xl-3 col-lg-6">
								<div class="card text-center bg-gradient-danger text-white">
									<div class="card-body"> <h5> Monthly revenue</h5>
									  <h3 class="display-5 mb-1 mt-1">$7632</h3>
									  <div><i class="si si-arrow-up-circle text-white"></i> <span class="text-white">12%</span> Increase</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card card-img-holder">
									<div class="card-body list-icons">
										<div class="clearfix">
											<div class="float-right  mt-2">
												<span class="text-primary ">
													<i class="si si-basket-loaded "></i>
												</span>
											</div>
											<div class="float-left text-right">
												<p class="card-text text-muted mb-1">Services</p>
												<h1>124</h1>
											</div>
										</div>
										<div class="card-footer p-0">
											<p class="text-muted mb-0 pt-4"><i class="si si-arrow-down-circle text-warning mr-1" aria-hidden="true"></i>Daily Orders</p>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card card-img-holder">
									<div class="card-body list-icons">
										<div class="clearfix">
											<div class="float-right  mt-2">
												<span class="text-primary ">
													<i class="si si-credit-card "></i>
												</span>
											</div>
											<div class="float-left">
												<p class="card-text text-muted mb-1">Sources</p>
												<h1>$124</h1>
											</div>
										</div>
										<div class="card-footer p-0">
											<p class="text-muted mb-0 pt-4"><i class="si si-arrow-up-circle text-success mr-1"></i>Less Sales</p>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card card-img-holder">
									<div class="card-body list-icons">
										<div class="clearfix">
											<div class="float-right  mt-2">
												<span class="text-primary">
													<i class="si si-chart"></i>
												</span>
											</div>
											<div class="float-left">
												<p class="card-text text-muted mb-1">Income</p>
												<h1>21%</h1>
											</div>
										</div>
										<div class="card-footer p-0">
											<p class="text-muted mb-0 pt-4"><i class="si si-exclamation text-info mr-1" ></i>From Last Month</p>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-lg-6 col-xl-3">
								<div class="card card-img-holder">
									<div class="card-body list-icons">
										<div class="clearfix">
											<div class="float-right  mt-2">
												<span class="text-primary">
													<i class="si si-social-facebook "></i>
												</span>
											</div>
											<div class="float-left">
												<p class="card-text text-muted mb-1">Followers</p>
												<h1>24K</h1>
											</div>
										</div>
										<div class="card-footer p-0">
											<p class="text-muted mb-0 pt-4"><i class="si si-check mr-1 text-primary" ></i> Recent History</p>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-6 col-lg-3">
								<div class="card">
									<div class="card-body text-center list-icons">
										<h3 class="card-text mt-1 mb-3">Clients</h3>
										<i class="si si-basket-loaded text-primary"></i>
										<p class="h3 mt-3 text-center  text-dark">159</p>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-6 col-lg-3">
								<div class="card">
									<div class="card-body text-center list-icons">
										<h3 class="card-text mt-3 mb-3">Customers</h3>
										<i class="si si-eye text-primary"></i>
										<p class="h3 mt-3 text-center text-dark">1452</p>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-6 col-lg-3">
								<div class="card">
									<div class="card-body text-center list-icons">
									   <h3 class="card-text mt-3 mb-3">Email</h3>
										<i class="si si-envelope text-primary"></i>
										<p class="h3 mt-3 text-center text-dark">154</p>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-6 col-lg-3">
								<div class="card">
									<div class="card-body text-center list-icons">
									   <h3 class="card-text mt-3 mb-3">Shares</h3>
										<i class="si si-share-alt text-primary"></i>
										<p class="h3 mt-3 text-center text-dark">452</p>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-6 col-sm-6 col-lg-3">
								<div class="card">
									<div class="card-body text-center">
										<div class="h1 m-0"><i class="mdi mdi-account-multiple-outline text-primary"></i><strong>90</strong></div>
										<div class="text-muted mb-0">Work</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-6 col-sm-6 col-lg-3">
								<div class="card">
									<div class="card-body text-center">
										<div class="h1 m-0"><i class="mdi mdi-cash-multiple text-primary"></i><strong>82</strong></div>
										<div class="text-muted mb-0">Business</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-6 col-sm-6 col-lg-3">
								<div class="card">
									<div class="card-body text-center">
										<div class="h1 m-0"><i class="mdi mdi-chart-line text-primary"></i><strong> 85</strong></div>
										<div class="text-muted mb-0"> Research</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-6 col-sm-6 col-lg-3">
								<div class="card">
									<div class="card-body text-center">
										<div class="h1 m-0"><i class="mdi mdi-account-outline text-primary"></i><strong> 42</strong></div>
										<div class="text-muted mb-0">Estimation</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-6 col-lg-3">
								<div class="card bg-primary">
									<div class="card-body">
										<div class="d-flex no-block align-items-center">
											<div>
												<h6 class="text-white">Voice Process</h6>
												<h2 class="text-white m-0 ">565</h2>
											</div>
											<div class="ml-auto">
												<span class="text-white display-6"><i class="fa fa-file-text-o fa-2x"></i></span>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-6 col-lg-3">
								<div class="card bg-info">
									<div class="card-body">
										<div class="d-flex no-block align-items-center">
											<div>
												<h6 class="text-white">Graphs of Sales</h6>
												<h2 class="text-white m-0 ">67k</h2>
											</div>
											<div class="ml-auto">
												<span class="text-white display-6"><i class="fa fa-signal fa-2x"></i></span>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-6 col-lg-3">
								<div class="card bg-success">
									<div class="card-body">
										<div class="d-flex no-block align-items-center">
											<div>
												<h6 class="text-white">Profits Of Projects</h6>
												<h2 class="text-white m-0 ">71K</h2>
											</div>
											<div class="ml-auto">
												<span class="text-white display-6"><i class="fa fa-usd fa-2x"></i></span>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-6 col-lg-3">
								<div class="card bg-danger">
									<div class="card-body">
										<div class="d-flex no-block align-items-center">
											<div>
												<h6 class="text-white">Services</h6>
												<h2 class="text-white m-0 ">192</h2>
											</div>
											<div class="ml-auto">
												<span class="text-white display-6"><i class="fa fa-newspaper-o fa-2x"></i></span>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

                        <!-- row -->
						<div class="row">
							<div class="col-xl-3 col-md-6 col-lg-6 col-sm-12 m-b-3">
								<div class="card">
									<div class="">
										<div class="row"><!-- row -->
											<div class="col-12">
												<div class="p-2 bg-primary br-tr-7 br-tl-7">
													<div class="text-center text-white social mt-3">
														<h4>Shares of Projects</h4>
													</div>
												</div>
												<div class="mt-7 chart-circle chart-circle-md donutShadow" data-value="0.67" data-thickness="20" data-color="#467fcf ">
										              <div class="chart-circle-value fs"><i class="fa fa-share-square-o"></i></div>
									            </div>
												<div class="card-body mt-4">
													<div class="d-flex  align-items-center">
														<div><h4 class="font-medium ml-3">10%</h4>
															<h4 class="mb-0"><span class="text-primary"><i class="fa fa-plus mr-2"></i>Positive</span></h4>
														</div>
														<div class="ml-auto">
															<h4 class="font-medium ml-3">20%</h4>
															<h4 class=" mb-0"><span class="text-danger"><i class="fa fa-minus mr-2"></i> Negative</span></h4>
														</div>
													</div>
												</div>
											</div>
										</div><!-- row end -->
									</div>
								</div>
							</div><!-- col end -->

							<div class="col-xl-3 col-md-6 col-lg-6 col-sm-12 m-b-3">
								<div class="card">
									<div class="">
										<div class="row">
											<div class="col-12">
												<div class="p-2 bg-blue br-tr-7 br-tl-7">
													<div class="text-center text-white social mt-3">
														<h4>Total Projects</h4>
													</div>
												</div>
												<div class="mt-7 chart-circle chart-circle-md donutShadow" data-value="0.67" data-thickness="20" data-color="#32cafe ">
										              <div class="chart-circle-value fs"><i class="fa fa-envelope-open-o"></i></div>
									            </div>
												<div class="card-body mt-4">
													<div class="d-flex  align-items-center">
														<div><h4 class="font-medium ml-3">10%</h4>
															<h4 class="mb-0"><span class="text-primary"><i class="fa fa-plus mr-2"></i>Positive</span></h4>
														</div>
														<div class="ml-auto">
															<h4 class="font-medium ml-3">20%</h4>
															<h4 class=" mb-0"><span class="text-danger"><i class="fa fa-minus mr-2"></i> Negative</span></h4>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->

							<div class="col-xl-3 col-md-6 col-lg-6 col-sm-12 m-b-3">
								<div class="card">
									<div class="">
										<div class="row">
											<div class="col-12">
												<div class="p-2 bg-yellow br-tr-7 br-tl-7">
													<div class="text-center text-white social mt-3">
														<h4>Users Of Projects</h4>
													</div>
												</div>
												<div class="mt-7 chart-circle chart-circle-md donutShadow" data-value="0.67" data-thickness="20" data-color="#fdb901 ">
										              <div class="chart-circle-value fs"><i class="fa fa-users"></i></div>
									            </div>
												<div class="card-body mt-4">
													<div class="d-flex  align-items-center">
														<div><h4 class="font-medium ml-3">10%</h4>
															<h4 class="mb-0"><span class="text-primary"><i class="fa fa-plus mr-2"></i>Positive</span></h4>
														</div>
														<div class="ml-auto">
															<h4 class="font-medium ml-3">20%</h4>
															<h4 class=" mb-0"><span class="text-danger"><i class="fa fa-minus mr-2"></i> Negative</span></h4>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->

							<div class="col-xl-3 col-md-6 col-lg-6 col-sm-12 m-b-3">
								<div class="card">
									<div class="">
										<div class="row">
											<div class="col-12">
												<div class="p-2 bg-green br-tr-7 br-tl-7">
													<div class="text-center text-white social mt-3">
														<h4>Review Projects</h4>
													</div>
												</div>
												<div class="mt-7 chart-circle chart-circle-md donutShadow" data-value="0.67" data-thickness="20" data-color="#5ed84f ">
										              <div class="chart-circle-value fs"><i class="fa fa-repeat"></i></div>
									            </div>
												<div class="card-body mt-4">
													<div class="d-flex  align-items-center">
														<div><h4 class="font-medium ml-3">10%</h4>
															<h4 class="mb-0"><span class="text-primary"><i class="fa fa-plus mr-2"></i>Positive</span></h4>
														</div>
														<div class="ml-auto">
															<h4 class="font-medium ml-3">20%</h4>
															<h4 class=" mb-0"><span class="text-danger"><i class="fa fa-minus mr-2"></i> Negative</span></h4>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

                        <!-- row -->
						<div class="row">
							<div class="col-md-12 col-sm-12 col-lg-12">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Best Pictures for Today</h3>
									</div>
									<div class="card-body p-2">
										<div>
											<div class="row img-gallery">
												<div class="col-4">
													<a href="javascript:void(0)" class="d-block link-overlay">
														<img class="d-block img-fluid" src="{{URL::asset('assets/images/photos/1.jpg')}}" alt="">
														<span class="link-overlay-bg rounded">
															<i class="fa fa-search"></i>
														</span>
													</a>
												</div>
												<div class="col-4">
													<a href="javascript:void(0)" class="d-block link-overlay">
														<img class="d-block img-fluid" src="{{URL::asset('assets/images/photos/2.jpg')}}" alt="">
														<span class="link-overlay-bg rounded">
															<i class="fa fa-search"></i>
														</span>
													</a>
												</div>
												<div class="col-4">
													<a href="javascript:void(0)" class="d-block link-overlay">
														<img class="d-block img-fluid" src="{{URL::asset('assets/images/photos/3.jpg')}}" alt="">
														<span class="link-overlay-bg rounded">
															<i class="fa fa-search"></i>
														</span>
													</a>
												</div>
												<div class="col-4">
													<a href="javascript:void(0)" class="d-block link-overlay">
														<img class="d-block img-fluid" src="{{URL::asset('assets/images/photos/4.jpg')}}" alt="">
														<span class="link-overlay-bg rounded">
															<i class="fa fa-search"></i>
														</span>
													</a>
												</div>
												<div class="col-4">
													<a href="javascript:void(0)" class="d-block link-overlay">
														<img class="d-block img-fluid" src="{{URL::asset('assets/images/photos/5.jpg')}}" alt="">
														<span class="link-overlay-bg rounded">
															<i class="fa fa-search"></i>
														</span>
													</a>
												</div>
												<div class="col-4">
													<a href="javascript:void(0)" class="d-block link-overlay">
														<img class="d-block img-fluid" src="{{URL::asset('assets/images/photos/6.jpg')}}" alt="">
														<span class="link-overlay-bg rounded">
															<i class="fa fa-search"></i>
														</span>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row row-cards">
							<div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
								<div class="card overflow-hidden">
									<div class="card-header pb-0">
										<h3 class="card-title">Price</h3>
										<div class="card-options">
											<a class="btn btn-sm btn-primary" href="#">View</a>
										</div>
									</div>
									<div class="card-body ">
										<h5 class="">Total Price</h5>
										<h3 class="text-dark count mt-0 font-30">4,657</h3>
										<div class="progress progress-sm mt-0 mb-2">
											<div class="progress-bar bg-gradient-primary w-75" role="progressbar"></div>
										</div>
										<div class=""><i class="fa fa-caret-up text-green"></i>10% increases</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class=" col-sm-12 col-md-6 col-lg-6 col-xl-3">
								<div class="card overflow-hidden">
									<div class="card-header pb-0">
										<h3 class="card-title">Stock</h3>
										<div class="card-options">
											<a class="btn btn-sm btn-secondary" href="#">View</a>
										</div>
									</div>
									<div class="card-body ">
										<h5 class="">Total Stock</h5>
										<h3 class="text-dark count mt-0 font-30">2,592</h3>
										<div class="progress progress-sm mt-0 mb-2">
											<div class="progress-bar bg-gradient-secondary w-45" role="progressbar"></div>
										</div>
										<div class=""><i class="fa fa-caret-down text-danger"></i>12% decrease</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class=" col-sm-12 col-md-6 col-lg-6 col-xl-3">
								<div class="card overflow-hidden">
									<div class="card-header pb-0">
										<h3 class="card-title">Revenue</h3>
										<div class="card-options">
											<a class="btn btn-sm btn-warning" href="#">View</a>
										</div>
									</div>
									<div class="card-body ">
										<h5 class="">Total Revenue</h5>
										<h3 class="text-dark count mt-0 font-30">3,517</h3>
										<div class="progress progress-sm mt-0 mb-2">
											<div class="progress-bar bg-gradient-warning w-50" role="progressbar"></div>
										</div>
										<div class=""><i class="fa fa-caret-down text-danger"></i>5% decrease</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class=" col-sm-12 col-md-6  col-lg-6 col-xl-3">
								<div class="card overflow-hidden">
									<div class="card-header pb-0">
										<h3 class="card-title">Investiment</h3>
										<div class="card-options">
											<a class="btn btn-sm btn-success" href="#">View</a>
										</div>
									</div>
									<div class="card-body ">
										<h5 class="">Total Investiment</h5>
										<h3 class="text-dark count mt-0 font-30 ">5,759</h3>
										<div class="progress progress-sm mt-0 mb-2">
											<div class="progress-bar bg-gradient-success w-25" role="progressbar"></div>
										</div>
										<div class=""><i class="fa fa-caret-up text-success"></i>15% increase</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-lg-12 col-xl-4 col-sm-12">
								<div class="card  mb-5">
									<div class="card-body">
										<div class="media mt-0">
											<figure class="rounded-circle align-self-start mb-0">
												<img src="{{URL::asset('assets/images/users/female/1.jpg')}}" alt="Generic placeholder image" class="avatar brround avatar-md mr-3">
											</figure>
											<div class="media-body">
												<h5 class="time-title p-0 mb-0 font-weight-semibold leading-normal">Victoria</h5>
												New york, UK
											</div>
											<button class="btn btn-primary d-none d-sm-block mr-2"><i class="fa fa-comments"></i> </button>
											<button class="btn btn-info d-none d-sm-block"><i class="fa fa-phone"></i> </button>
										</div>
									</div>
									<div class="card-footer text-dark border-top">
										Email: <span class="text-primary">victoriacott@Sparic.com</span>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-12 col-xl-4 col-sm-12">
								<div class="card mb-5">
									<div class="card-body">
										<div class="media mt-0">
											<figure class="rounded-circle align-self-start mb-0">
												<img src="{{URL::asset('assets/images/users/male/18.jpg')}}" alt="Generic placeholder image" class="avatar brround avatar-md mr-3">
											</figure>
											<div class="media-body">
												<h5 class="time-title p-0 mb-0 font-weight-semibold leading-normal">Thomas Jaim</h5>
												Sparic, UN
											</div>
											<button class="btn btn-primary d-none d-sm-block mr-2"><i class="fa fa-comments"></i> </button>
											<button class="btn btn-info d-none d-sm-block"><i class="fa fa-phone"></i> </button>
										</div>
									</div>
									<div class="card-footer text-dark border-top">
										Email: <span class="text-primary">thomasjaim@Sparic.com</span>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-12 col-xl-4 col-sm-12">
								<div class="card mb-5">
									<div class="card-body">
										<div class="media mt-0">
											<figure class="rounded-circle align-self-start mb-0">
												<img src="{{URL::asset('assets/images/users/female/18.jpg')}}" alt="Generic placeholder image" class="avatar brround avatar-md mr-3">
											</figure>
											<div class="media-body">
												<h5 class="time-title p-0 font-weight-semibold leading-normal mb-0">Rebbaca wisely</h5>
												Japan, UN
											</div>
											<button class="btn btn-primary d-none d-sm-block mr-2"><i class="fa fa-comments"></i> </button>
											<button class="btn btn-info d-none d-sm-block"><i class="fa fa-phone"></i> </button>
										</div>
									</div>
									<div class="card-footer text-dark border-top">
										Email: <span class="text-primary">rebbacawisely@Sparic.com</span>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-lg-6 col-xl-3 col-md-6 col-sm-12 m-b-3">
								<div class="card">
									<div class="">
										<div class="row">
											<div class="col-12">
												<div class="facebook p-4 br-tr-7 br-tl-7">
													<div class="text-center text-white social">
														<i class="fa fa-facebook"></i>
													</div>
												</div>
												<div class="card-body mt-0">
													<div class="d-flex  align-items-center">
														<div><h3 class="font-medium">50k</h3>
															<h5 class="text-muted mb-0">Following</h5>
														</div>
														<div class="ml-auto">
															<h3 class="font-medium">21k</h3>
															<h5 class="text-muted mb-0">Friends</h5>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-6 col-xl-3 col-md-6 col-sm-12 m-b-3">
								<div class="card">
									<div class="">
										<div class="row">
											<div class="col-12">
												<div class="twitter p-4 br-tr-7 br-tl-7">
													<div class="text-center text-white social">
														<i class="fa fa-twitter"></i>
													</div>
												</div>
												<div class="card-body mt-0">
													<div class="d-flex  align-items-center">
														<div><h3 class="font-medium">92k</h3>
															<h5 class="text-muted mb-0">Following</h5>
														</div>
														<div class="ml-auto">
															<h3 class="font-medium">14k</h3>
															<h5 class="text-muted mb-0">Friends</h5>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-6 col-xl-3 col-md-6 col-sm-12 m-b-3">
								<div class="card">
									<div class="">
										<div class="row">
											<div class="col-12">
												<div class="linkedin p-4 br-tr-7 br-tl-7">
													<div class="text-center text-white social">
														<i class="fa fa-linkedin"></i>
													</div>
												</div>
												<div class="card-body mt-0">
													<div class="d-flex  align-items-center">
														<div><h3 class="font-medium">34k</h3>
															<h5 class="text-muted mb-0">Following</h5>
														</div>
														<div class="ml-auto">
															<h3 class="font-medium">19k</h3>
															<h5 class="text-muted mb-0">Friends</h5>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-6 col-xl-3 col-md-6 col-sm-12 m-b-3">
								<div class="card">
									<div class="">
										<div class="row">
											<div class="col-12">
												<div class="instagram p-4 br-tr-7 br-tl-7">
													<div class="text-center text-white social">
														<i class="fa fa-instagram"></i>
													</div>
												</div>
												<div class="card-body mt-0">
													<div class="d-flex  align-items-center">
														<div><h3 class="font-medium">143k</h3>
															<h5 class="text-muted mb-0">Following</h5>
														</div>
														<div class="ml-auto">
															<h3 class="font-medium">43k</h3>
															<h5 class="text-muted mb-0">Friends</h5>
														</div>
													</div>
												</div>
											</div>
										</div><!-- row end -->
									</div>
								</div>
							</div><!-- col end -->
						</div>

						<div class="row">
							<div class="col-md-12 col-lg-12 col-xl-4 col-sm-12">
								<div class="card">
									<div class="card-header pb-0">
										<h2 class="card-title">Category</h2>
									</div>
									<div >
										<table class="table card-table ">
											<tr class="border-bottom">
												<td class="border-top-0">Admin Template</td>
												<td class="text-right border-top-0">
													<span class="badge badge-primary">29</span>
												</td>
											</tr>
											<tr class="border-bottom">
												<td>Landing Page</td>
												<td class="text-right">
													<span class="badge badge-success">12</span>
												</td>
											</tr>
											<tr class="border-bottom">
												<td>Backend UI</td>
												<td class="text-right">
													<span class="badge badge-danger">12</span>
												</td>
											</tr>
											<tr class="border-bottom">
												<td>Personal Blog</td>
												<td class="text-right">
													<span class="badge badge-default">60</span>
												</td>
											</tr>
											<tr class="border-bottom">
												<td>E-mail Templates</td>
												<td class="text-right">
													<span class="badge badge-danger">15</span>
												</td>
											</tr>
											<tr class="border-bottom">
												<td>Corporate Website</td>
												<td class="text-right">
													<span class="badge badge-cyan">45</span>
												</td>
											</tr>
											<tr class="border-bottom">
												<td> Educational Templates</td>
												<td class="text-right">
													<span class="badge badge-info">35</span>
												</td>
											</tr>
											<tr class="border-bottom">
												<td>Beauty Templates</td>
												<td class="text-right">
													<span class="badge badge-info">36</span>
												</td>
											</tr>
											<tr class="">
												<td>personal Website</td>
												<td class="text-right">
													<span class="badge badge-warning">55</span>
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<div class="col-xl-4 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Top Ongoing Projects</h3>
									</div>
									<div class="card-body p-0">
										<div class="list-group projects-list">
											<a href="#" class="list-group-item list-group-item-action flex-column align-items-start border-top-0">
												<div class="d-flex w-100 justify-content-between">
													<h5 class="mb-1 font-weight-sembold">PSD Projects</h5>
													<small class="text-danger"><i class="fa fa-caret-down mr-1"></i>5 days ago</small>
												</div>
												<p class="mb-0">Started:17-02-2019</p>
												<small>Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</small>
											</a>
											<a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
												<div class="d-flex w-100 justify-content-between">
													<h5 class="mb-1 font-weight-sembold">Wordpress Projects</h5>
													<small class="text-success"><i class="fa fa-caret-up mr-1"></i>2 days ago</small>
												</div>
												<p class="mb-0">Started:15-02-2019</p>
												<small>Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</small>
											</a>
											<a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
												<div class="d-flex w-100 justify-content-between">
													<h5 class="mb-1 font-weight-sembold">HTML &amp; CSS3 Projects</h5>
													<small class="text-danger"><i class="fa fa-caret-down mr-1"></i>1 days ago</small>
												</div>
												<p class="mb-0">Started:26-02-2019</p>
												<small>Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</small>
											</a>
											<a href="#" class="list-group-item list-group-item-action flex-column align-items-start br-br-7 br-bl-7">
												<div class="d-flex w-100 justify-content-between">
													<h5 class="mb-1 font-weight-sembold">Java Projects</h5>
													<small class="text-success"><i class="fa fa-caret-up mr-1"></i>10 days ago</small>
												</div>
												<p class="mb-0">Started:06-02-2019</p>
												<small>Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</small>
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-4 col-lg-12 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Customer Feedbacks</h3>
									</div>
									<div class="card-body p-0 ">
										<div class="list-group list-lg-group list-group-flush">
											<a class="list-group-item list-group-item-action" href="#">
												<div class="media mt-0">
													<img class="avatar-lg rounded-circle mr-3" src="{{URL::asset('assets/images/users/female/2.jpg')}}" alt="Image description">
													<div class="media-body">
														<div class="d-md-flex align-items-center">
															<h4 class="mb-1">
																Samantha Wilson
															</h4>
															<small class="text-primary ml-md-auto"><i class="fe fe-calendar mr-1"></i> 28 Feb 2019</small>
														</div>

														<p class="mb-0">Itaque earum rerum hic tenetur a sapiente reiciendis voluptatibus.</p>
													</div>
												</div>
											</a>
											<a class="list-group-item list-group-item-action" href="#">
												<div class="media mt-0">
													<img class="avatar-lg rounded-circle mr-3" src="{{URL::asset('assets/images/users/male/2.jpg')}}" alt="Image description">
													<div class="media-body">
														<div class="d-md-flex align-items-center">
															<h4 class="mb-1">
																Kevin North
															</h4>
															<small class="text-primary ml-md-auto"><i class="fe fe-calendar mr-1"></i> 26 Feb 2019</small>
														</div>

														<p class="mb-0">Itaque earum rerum hic tenetur a sapiente reiciendis voluptatibus.</p>
													</div>
												</div>
											</a>
											<a class="list-group-item list-group-item-action" href="#">
												<div class="media mt-0">
													<img class="avatar-lg rounded-circle mr-3" src="{{URL::asset('assets/images/users/male/12.jpg')}}" alt="Image description">
													<div class="media-body">
														<div class="d-md-flex align-items-center">
															<h4 class="mb-1">
																Steven Fisher
															</h4>
															<small class="text-primary ml-md-auto"><i class="fe fe-calendar mr-1"></i> 26 Feb 2019</small>
														</div>

														<p class="mb-0">Itaque earum rerum hic tenetur a sapiente reiciendis voluptatibus.</p>
													</div>
												</div>
											</a>
											<a class="list-group-item list-group-item-action" href="#">
												<div class="media mt-0">
													<img class="avatar-lg rounded-circle mr-3" src="{{URL::asset('assets/images/users/male/12.jpg')}}" alt="Image description">
													<div class="media-body">
														<div class="d-md-flex align-items-center">
															<h4 class="mb-1">
																Steven Fisher
															</h4>
															<small class="text-primary ml-md-auto"><i class="fe fe-calendar mr-1"></i> 26 Feb 2019</small>
														</div>

														<p class="mb-0">Itaque earum rerum hic tenetur a sapiente reiciendis voluptatibus.</p>
													</div>
												</div>
											</a>
											<a class="list-group-item list-group-item-action br-br-7 br-bl-7" href="#">
												<div class="media mt-0">
													<img class="avatar-lg rounded-circle mr-3" src="{{URL::asset('assets/images/users/female/5.jpg')}}" alt="Image description">
													<div class="media-body">
														<div class="d-md-flex align-items-center">
															<h4 class="mb-1">
																Joanne Taylor
															</h4>
															<small class="text-primary ml-md-auto"><i class="fe fe-calendar mr-1"></i> 25 Feb 2019</small>
														</div>

														<p class="mb-0">Itaque earum rerum hic tenetur a sapiente reiciendis voluptatibus.</p>
													</div>
												</div>
											</a>
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

		<!-- C3 Charts js-->
		<script src="{{URL::asset('assets/plugins/charts-c3/d3.v5.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/charts-c3/c3-chart.js')}}"></script>

		<!--Peitychart js-->
		<script src="{{URL::asset('assets/plugins/peitychart/jquery.peity.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/peitychart/peitychart.init.js')}}"></script>

		<!--Counters -->
		<script src="{{URL::asset('assets/plugins/counters/counterup.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/counters/waypoints.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/counters/counters.js')}}"></script>

@endsection