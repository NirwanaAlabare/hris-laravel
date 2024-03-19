@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Tables</a></li>
								<li class="breadcrumb-item active" aria-current="page">Default Table</li>
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
							<div class="col-12">
								<div class="card">
									<div class="card-header bg-transparent border-0">
										<h3 class="card-title mb-0">Default Table</h3>
									</div>
									<div class="">
										<div class="grid-margin">
											<div class="">
												<div class="table-responsive">
													<table class="table card-table table-vcenter text-nowrap  align-items-center">
														<thead class="thead-light">
															<tr>
																<th>Id</th>
																<th>Project Name</th>
																<th>Team</th>
																<th>Feedback</th>
																<th>Date</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>2345</td>
																<td class="text-sm font-weight-600">Megan Peters</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/19.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/20.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/21.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/22.jpg')}}"></a>
																	</div>
																</td>
																<td>please check pricing Info </td>
																<td class="text-nowrap">July 13, 2018</td>
															</tr>
															<tr>
																<td>4562</td>
																<td class="text-sm font-weight-600">Phil Vance</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/23.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/24.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/25.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/26.jpg')}}"></a>
																	</div>
																</td>
																<td>New stock</td>
																<td class="text-nowrap">June 15, 2018</td>
															</tr>
															<tr>
																<td>8765</td>
																<td class="text-sm font-weight-600">Adam Sharp</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/8.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/9.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/10.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/11.jpg')}}"></a>
																	</div>
																</td>
																<td>Daily updates</td>
																<td class="text-nowrap">July 8, 2018</td>
															</tr>
															<tr>
																<td>2665</td>
																<td class="text-sm font-weight-600">Samantha Slater</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/12.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/21.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/18.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/1.jpg')}}"></a>
																	</div>
																</td>
																<td>available item list</td>
																<td class="text-nowrap">June 28, 2018</td>
															</tr>
															<tr>
																<td>1245</td>
																<td class="text-sm font-weight-600">Joanne Nash</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/15.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/16.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/17.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/18.jpg')}}"></a>
																	</div>
																</td>
																<td>Provide Best Services</td>
																<td class="text-nowrap">July 2, 2018</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-12">
								<div class="card ">
									<div class="card-header table-primary border-0">
										<h3 class="card-title mb-0">Primary Table</h3>
									</div>
									<div class="">
										<div class="grid-margin">
											<div class="">
												<div class="table-responsive">
													<table class="table card-table  table-primary table-vcenter text-nowrap  align-items-center">
														<thead class="thead-light">
															<tr>
																<th>Id</th>
																<th>Project Name</th>
																<th>Team</th>
																<th>Feedback</th>
																<th>Date</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>2345</td>
																<td class="text-sm font-weight-600">Megan Peters</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/19.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/20.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/21.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/22.jpg')}}"></a>
																	</div>
																</td>
																<td>please check pricing Info </td>
																<td class="text-nowrap">July 13, 2018</td>
															</tr>
															<tr>
																<td>4562</td>
																<td class="text-sm font-weight-600">Phil Vance</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/23.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/24.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/25.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/26.jpg')}}"></a>
																	</div>
																</td>
																<td>New stock</td>
																<td class="text-nowrap">June 15, 2018</td>
															</tr>
															<tr>
																<td>8765</td>
																<td class="text-sm font-weight-600">Adam Sharp</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/8.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/9.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/10.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/11.jpg')}}"></a>
																	</div>
																</td>
																<td>Daily updates</td>
																<td class="text-nowrap">July 8, 2018</td>
															</tr>
															<tr>
																<td>2665</td>
																<td class="text-sm font-weight-600">Samantha Slater</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/12.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/21.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/18.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/1.jpg')}}"></a>
																	</div>
																</td>
																<td>available item list</td>
																<td class="text-nowrap">June 28, 2018</td>
															</tr>
															<tr class="border-bottom-0">
																<td>1245</td>
																<td class="text-sm font-weight-600">Joanne Nash</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/15.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/16.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/17.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/18.jpg')}}"></a>
																	</div>
																</td>
																<td>Provide Best Services</td>
																<td class="text-nowrap">July 2, 2018</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12">
								<div class="card">
									<div class="card-header table-success  border-0">
										<h3 class=" mb-0 card-title">Success Table</h3>
									</div>
									<div class="">
										<div class="grid-margin">
											<div class="">
												<div class="table-responsive">
													<table class="table card-table  table-success table-vcenter text-nowrap  align-items-center">
														<thead class="thead-light">
															<tr>
																<th>Id</th>
																<th>Project Name</th>
																<th>Team</th>
																<th>Feedback</th>
																<th>Date</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>2345</td>
																<td class="text-sm font-weight-600">Megan Peters</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/19.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/20.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/21.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/22.jpg')}}"></a>
																	</div>
																</td>
																<td>please check pricing Info </td>
																<td class="text-nowrap">July 13, 2018</td>
															</tr>
															<tr>
																<td>4562</td>
																<td class="text-sm font-weight-600">Phil Vance</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/23.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/24.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/25.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/26.jpg')}}"></a>
																	</div>
																</td>
																<td>New stock</td>
																<td class="text-nowrap">June 15, 2018</td>
															</tr>
															<tr>
																<td>8765</td>
																<td class="text-sm font-weight-600">Adam Sharp</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/8.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/9.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/10.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/11.jpg')}}"></a>
																	</div>
																</td>
																<td>Daily updates</td>
																<td class="text-nowrap">July 8, 2018</td>
															</tr>
															<tr>
																<td>2665</td>
																<td class="text-sm font-weight-600">Samantha Slater</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/12.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/21.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/18.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/1.jpg')}}"></a>
																	</div>
																</td>
																<td>available item list</td>
																<td class="text-nowrap">June 28, 2018</td>
															</tr>
															<tr class="border-bottom-0">
																<td>1245</td>
																<td class="text-sm font-weight-600">Joanne Nash</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/15.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/16.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/17.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/18.jpg')}}"></a>
																	</div>
																</td>
																<td>Provide Best Services</td>
																<td class="text-nowrap">July 2, 2018</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12">
								<div class="card">
									<div class="card-header table-danger border-0">
										<h3 class=" mb-0 card-title">Danger Table</h3>
									</div>
									<div class="">
										<div class="grid-margin">
											<div class="">
												<div class="table-responsive">
													<table class="table card-table  table-danger table-vcenter text-nowrap  align-items-center">
														<thead class="thead-light">
															<tr>
																<th>Id</th>
																<th>Project Name</th>
																<th>Team</th>
																<th>Feedback</th>
																<th>Date</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>2345</td>
																<td class="text-sm font-weight-600">Megan Peters</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/19.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/20.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/21.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/22.jpg')}}"></a>
																	</div>
																</td>
																<td>please check pricing Info </td>
																<td class="text-nowrap">July 13, 2018</td>
															</tr>
															<tr>
																<td>4562</td>
																<td class="text-sm font-weight-600">Phil Vance</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/23.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/24.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/25.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/26.jpg')}}"></a>
																	</div>
																</td>
																<td>New stock</td>
																<td class="text-nowrap">June 15, 2018</td>
															</tr>
															<tr>
																<td>8765</td>
																<td class="text-sm font-weight-600">Adam Sharp</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/8.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/9.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/10.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/11.jpg')}}"></a>
																	</div>
																</td>
																<td>Daily updates</td>
																<td class="text-nowrap">July 8, 2018</td>
															</tr>
															<tr>
																<td>2665</td>
																<td class="text-sm font-weight-600">Samantha Slater</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/12.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/21.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/18.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/1.jpg')}}"></a>
																	</div>
																</td>
																<td>available item list</td>
																<td class="text-nowrap">June 28, 2018</td>
															</tr>
															<tr class="border-bottom-0">
																<td>1245</td>
																<td class="text-sm font-weight-600">Joanne Nash</td>
																<td><div class="avatar-group">
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/15.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/16.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/female/17.jpg')}}"></a>
																		<a class="avatar avatar-sm" data-toggle="tooltip" href="#" data-original-title="" title=""><img alt="Image placeholder" class="rounded-circle" src="{{URL::asset('assets/images/users/male/18.jpg')}}"></a>
																	</div>
																</td>
																<td>Provide Best Services</td>
																<td class="text-nowrap">July 2, 2018</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header border-0">
										<h3 class="card-title">Basic Table</h3>
									</div>
									<div class="table-responsive">
										<table class="table card-table table-vcenter text-nowrap">
											<thead >
												<tr>
													<th>ID</th>
													<th>Name</th>
													<th>Position</th>
													<th>Salary</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td>Joan Powell</td>
													<td>Associate Developer</td>
													<td>$450,870</td>
												</tr>
												<tr>
													<td>2</td>
													<td>Gavin Gibson</td>
													<td>Account manager</td>
													<td>$230,540</td>
												</tr>
												<tr>
													<td>3</td>
													<td>Julian Kerr</td>
													<td>Senior Javascript Developer</td>
													<td>$55,300</td>
												</tr>
												<tr>
													<td>4</td>
													<td>Cedric Kelly</td>
													<td>Accountant</td>
													<td>$234,100</td>
												</tr>
												<tr>
													<td>5</td>
													<td>Samantha May</td>
													<td>Junior Technical Author</td>
													<td>$43,198</td>
												</tr>
											</tbody>
										</table>
									</div>
									<!-- table-responsive -->
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Full color variations</h3>
									</div>
									<div class="table-responsive">
										<table class="table card-table table-vcenter text-nowrap table-nowrap" >
											<thead  class="bg-primary text-white">
												<tr >
													<th class="text-white">ID</th>
													<th class="text-white">Name</th>
													<th class="text-white">Position</th>
													<th class="text-white">Salary</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td>Joan Powell</td>
													<td>Associate Developer</td>
													<td>$450,870</td>
												</tr>
												<tr>
													<td>2</td>
													<td>Gavin Gibson</td>
													<td>Account manager</td>
													<td>$230,540</td>
												</tr>
												<tr>
													<td>3</td>
													<td>Julian Kerr</td>
													<td>Senior Javascript Developer</td>
													<td>$55,300</td>
												</tr>
												<tr>
													<td>4</td>
													<td>Cedric Kelly</td>
													<td>Accountant</td>
													<td>$234,100</td>
												</tr>
												<tr>
													<td>5</td>
													<td>Samantha May</td>
													<td>Junior Technical Author</td>
													<td>$43,198</td>
												</tr>
											</tbody>
										</table>
									</div>
									<!-- table-responsive -->
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header">
										<h3 class="card-title">Full color variations2</h3>
									</div>
									<div class="table-responsive">
										<table class="table card-table table-vcenter text-nowrap table-nowrap" >
											<thead  class="bg-blue text-white">
												<tr>
													<th class="text-white">ID</th>
													<th class="text-white">Name</th>
													<th class="text-white">Position</th>
													<th class="text-white">Salary</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td>Joan Powell</td>
													<td>Associate Developer</td>
													<td>$450,870</td>
												</tr>
												<tr>
													<td>2</td>
													<td>Gavin Gibson</td>
													<td>Account manager</td>
													<td>$230,540</td>
												</tr>
												<tr>
													<td>3</td>
													<td>Julian Kerr</td>
													<td>Senior Javascript Developer</td>
													<td>$55,300</td>
												</tr>
												<tr>
													<td>4</td>
													<td>Cedric Kelly</td>
													<td>Accountant</td>
													<td>$234,100</td>
												</tr>
												<tr>
													<td>5</td>
													<td>Samantha May</td>
													<td>Junior Technical Author</td>
													<td>$43,198</td>
												</tr>
											</tbody>
										</table>
									</div>
									<!-- table-responsive -->
								</div>
							</div><!-- col end -->
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