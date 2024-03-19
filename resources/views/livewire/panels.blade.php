@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Elements</a></li>
								<li class="breadcrumb-item active" aria-current="page">Panels</li>
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
							<div class="col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Simple Panels</h3>
									</div>
									<div class="card-body">
										<div class="expanel expanel-default mt-4">
											<div class="expanel-body">
												Basic panel example
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Panel with heading</h3>
									</div>
									<div class="card-body">
										<div class="row mt-4">
											<div class="col-md-6">
												<div class="expanel expanel-default">
													<div class="expanel-heading">Panel heading without title</div>
													<div class="expanel-body">
														Panel content
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="expanel expanel-default">
													<div class="expanel-heading">
														<div class="expanel-title">Panel title</div>
													</div>
													<div class="expanel-body">
														Panel content
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div><!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Panel with footer</h3>
									</div>
									<div class="card-body">
										<div class="expanel expanel-default mt-4">
											<div class="expanel-body">
												Panel content
											</div>
											<div class="expanel-footer">Panel footer</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Panel with color header</h3>
									</div>
									<div class="card-body">
										<div class="row mt-4">
											<div class="col-md-6">
												<div class="expanel expanel-primary">
													<div class="expanel-heading">
														<h3 class="expanel-title">Panel title</h3>
													</div>
													<div class="expanel-body">
														Panel content
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="expanel expanel-secondary">
													<div class="expanel-heading">
														<h3 class="expanel-title">Panel title</h3>
													</div>
													<div class="expanel-body">
														Panel content
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="expanel expanel-success">
													<div class="expanel-heading">
														<h3 class="expanel-title">Panel title</h3>
													</div>
													<div class="expanel-body">
														Panel content
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="expanel expanel-danger">
													<div class="expanel-heading">
														<h3 class="expanel-title">Panel title</h3>
													</div>
													<div class="expanel-body">
														Panel content
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
							<div class="col-md-12 col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Panel tabs</h3>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<div class="expanel expanel-primary">
													<div class="expanel-heading clearfix align-items-center">Panel title (with paragraphs inside)
														<div class="float-right">
															<button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#collapse01"
																aria-expanded="false" aria-controls="collapse01"><i class="fa fa-bars"></i></button>
														</div>
													</div>
													<div class="expanel-body collapse" id="collapse01">
														<p>Paragraphs</p>
														<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus.
															Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit.
															Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue.
															Nam tincidunt congue enim, ut porta lorem lacinia consectetur.</p>
													</div>
												</div>
												<div class="expanel expanel-info">
													<div class="expanel-heading clearfix">Panel title (with list-group inside)
														<div class="float-right">
															<button class="btn btn-sm btn-light" type="button" data-toggle="collapse" data-target="#collapse02"
																aria-expanded="false" aria-controls="collapse02"><i class="fa fa-bars"></i></button>
														</div>
													</div>
													<div class="expanel-body collapse" id="collapse02">
														<p>Notice the padding inside.</p>
														<ul class="list-group">
															<li class="list-group-item"><a href="#" role="button" class="btn btn-default btn-block">Some action</a></li>
															<li class="list-group-item"><a href="#" role="button" class="btn btn-default btn-block">Another action</a></li>
														</ul>
													</div>
												</div>
												<div class="expanel expanel-default">
													<div class="expanel-heading clearfix">Panel title (with paragraphs)
														<div class="float-right">
															<button class="btn btn-sm btn-info" type="button" data-toggle="collapse" data-target="#collapse06"
																aria-expanded="false" aria-controls="collapse06"><i class="fa fa-bars"></i></button>
														</div>
													</div>
													<div class="expanel-body collapse" id="collapse06">
														<p>Paragraphs</p>
														<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus.
															Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit.
															Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue.
															Nam tincidunt congue enim, ut porta lorem lacinia consectetur.</p>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="expanel expanel-success">
													<div class="expanel-heading clearfix">Panel title (with table inside)
														<div class="float-right">
															<button class="btn btn-sm btn-success" type="button" data-toggle="collapse" data-target="#collapse03"
																aria-expanded="false" aria-controls="collapse03"><i class="fa fa-bars"></i></button>
														</div>
													</div>
													<div class="expanel-body collapse" id="collapse03">
														<table class="table">
															<thead>
															  <tr>
																<th>#</th>
																<th>First Name</th>
																<th>Last Name</th>
																<th>Username</th>
															  </tr>
															</thead>
															<tbody>
															  <tr>
																<td>1</td>
																<td>Mark</td>
																<td>Otto</td>
																<td>@mdo</td>
															  </tr>
															  <tr>
																<td>2</td>
																<td>Jacob</td>
																<td>Thornton</td>
																<td>@fat</td>
															  </tr>
															  <tr>
																<td>3</td>
																<td>Larry</td>
																<td>the Bird</td>
																<td>@twitter</td>
															  </tr>
															</tbody>
														</table>
													</div>
												</div>
												<div class="expanel expanel-warning">
													<div class="expanel-heading clearfix">Panel title (with paragraphs)
														<div class="float-right">
															<button class="btn btn-sm btn-warning" type="button" data-toggle="collapse" data-target="#collapse04"
																aria-expanded="false" aria-controls="collapse04"><i class="fa fa-bars"></i></button>
														</div>
													</div>
													<div class="expanel-body collapse" id="collapse04">
														<p>Paragraphs</p>
														<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus.
															Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit.
															Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue.
															Nam tincidunt congue enim, ut porta lorem lacinia consectetur.</p>
													</div>
												</div>
												<div class="expanel expanel-danger">
													<div class="expanel-heading clearfix">Panel title (with paragraphs)
														<div class="float-right">
															<button class="btn btn-sm btn-danger" type="button" data-toggle="collapse" data-target="#collapse05"
																aria-expanded="false" aria-controls="collapse05"><i class="fa fa-bars"></i></button>
														</div>
													</div>
													<div class="expanel-body collapse" id="collapse05">
														<p>Paragraphs</p>
														<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus.
															Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit.
															Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue.
															Nam tincidunt congue enim, ut porta lorem lacinia consectetur.</p>
													</div>
												</div>
											</div>
										</div><!-- row end -->
									</div>
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