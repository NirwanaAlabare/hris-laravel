@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Elements</a></li>
								<li class="breadcrumb-item active" aria-current="page">List</li>
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
							<div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">Default Active  Link List</div>
									</div>
									<div class="card-body">
										<div class="panel panel-default">
											<div class="list-group">
												<a href="#" class="list-group-item">Cras justo odio</a>
												<a href="#" class="list-group-item active">Dapibus ac facilisis in</a>
												<a href="#" class="list-group-item">Morbi leo risus</a>
												<a href="#" class="list-group-item">Porta ac consectetur ac</a>
												<a href="#" class="list-group-item">Vestibulum at eros</a>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">Primary Active  Link List</div>
									</div>
									<div class="card-body">
										<div class="panel panel-primary">
											<div class="list-group">
												<a href="#" class="list-group-item">Cras justo odio</a>
												<a href="#" class="list-group-item active">Dapibus ac facilisis in</a>
												<a href="#" class="list-group-item">Morbi leo risus</a>
												<a href="#" class="list-group-item">Porta ac consectetur ac</a>
												<a href="#" class="list-group-item">Vestibulum at eros</a>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">Success Active  Link List</div>
									</div>
									<div class="card-body">
										<div class="panel panel-success">
											<div class="list-group">
												<a href="#" class="list-group-item">Cras justo odio</a>
												<a href="#" class="list-group-item active">Dapibus ac facilisis in</a>
												<a href="#" class="list-group-item">Morbi leo risus</a>
												<a href="#" class="list-group-item">Porta ac consectetur ac</a>
												<a href="#" class="list-group-item">Vestibulum at eros</a>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">Info Active Link List</div>
									</div>
									<div class="card-body">
										<div class="panel panel-info">
											<div class="list-group">
												<a href="#" class="list-group-item">Cras justo odio</a>
												<a href="#" class="list-group-item active">Dapibus ac facilisis in</a>
												<a href="#" class="list-group-item">Morbi leo risus</a>
												<a href="#" class="list-group-item">Porta ac consectetur ac</a>
												<a href="#" class="list-group-item">Vestibulum at eros</a>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">Warning Active Link List</div>
									</div>
									<div class="card-body">
										<div class="panel panel-warning">
											<div class="list-group">
												<a href="#" class="list-group-item">Cras justo odio</a>
												<a href="#" class="list-group-item active">Dapibus ac facilisis in</a>
												<a href="#" class="list-group-item">Morbi leo risus</a>
												<a href="#" class="list-group-item">Porta ac consectetur ac</a>
												<a href="#" class="list-group-item">Vestibulum at eros</a>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
								<div class="card">
									<div class="card-header pb-0">
										<div class="card-title">list styles</div>
									</div>
									<div class="card-body">
										<div class="panel panel-danger">
											<div class="list-group">
												<a href="#" class="list-group-item">Cras justo odio</a>
												<a href="#" class="list-group-item active">Dapibus ac facilisis in</a>
												<a href="#" class="list-group-item">Morbi leo risus</a>
												<a href="#" class="list-group-item">Porta ac consectetur ac</a>
												<a href="#" class="list-group-item">Vestibulum at eros</a>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Basic List Group</h3>
									</div>
									<div class="card-body">
										<ul class="list-group">
											<li class="list-group-item">Cras justo odio</li>
											<li class="list-group-item">Dapibus ac facilisis in</li>
											<li class="list-group-item">Morbi leo risus</li>
											<li class="list-group-item">Porta ac consectetur ac</li>
											<li class="list-group-item">Vestibulum at eros</li>
										</ul>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Active List item</h3>
									</div>
									<div class="card-body">
										<ul class="list-group">
											<li class="list-group-item active">Cras justo odio</li>
											<li class="list-group-item">Dapibus ac facilisis in</li>
											<li class="list-group-item">Morbi leo risus</li>
											<li class="list-group-item">Porta ac consectetur ac</li>
											<li class="list-group-item">Vestibulum at eros</li>
										</ul>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Order list</h3>
									</div>
									<div class="card-body">
										<ul class="list-group">
											<li class="listorder">Cras justo odio</li>
											<li class="listorder">Dapibus ac facilisis in</li>
											<li class="listorder">Morbi leo risus</li>
											<li class="listorder">Porta ac consectetur ac</li>
											<li class="listorder">Vestibulum at eros</li>
										</ul>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Unorder list</h3>
									</div>
									<div class="card-body">
										<ul class="list-group">
											<li class="listunorder">Cras justo odio</li>
											<li class="listunorder">Dapibus ac facilisis in</li>
											<li class="listunorder">Morbi leo risus</li>
											<li class="listunorder">Porta ac consectetur ac</li>
											<li class="listunorder">Vestibulum at eros</li>
										</ul>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

                        <!-- row -->
						<div class="row">
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Disabled  List item</h3>
									</div>
									<div class="card-body">
										<ul class="list-group">
											<li class="list-group-item disabled">Cras justo odio</li>
											<li class="list-group-item">Dapibus ac facilisis in</li>
											<li class="list-group-item">Morbi leo risus</li>
											<li class="list-group-item">Porta ac consectetur ac</li>
											<li class="list-group-item">Vestibulum at eros</li>
										</ul>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Contextual classes with List Group</h3>
									</div>
									<div class="card-body">
										<ul class="list-group">
											<li class="list-group-item">Dapibus ac facilisis in</li>
											<li class="list-group-item list-group-item-success">Dapibus ac facilisis in</li>
											<li class="list-group-item list-group-item-info">Cras sit amet nibh libero</li>
											<li class="list-group-item list-group-item-warning">Porta ac consectetur ac</li>
											<li class="list-group-item list-group-item-danger">Vestibulum at eros</li>
										</ul>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- rwo -->
						<div class="row">
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">List Group with icons</h3>
									</div>
									<div class="card-body">
										<ul class="list-group">
											<li class="list-group-item"><i class="fa fa-check" aria-hidden="true"></i> Cras justo odio</li>
											<li class="list-group-item"><i class="fa fa-check" aria-hidden="true"></i> Dapibus ac facilisis in</li>
											<li class="list-group-item"><i class="fa fa-check" aria-hidden="true"></i> Morbi leo risus</li>
											<li class="list-group-item"><i class="fa fa-check" aria-hidden="true"></i> Porta ac consectetur ac</li>
											<li class="list-group-item"><i class="fa fa-check" aria-hidden="true"></i> Vestibulum at eros</li>
										</ul>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">List Group with colored icons</h3>
									</div>
									<div class="card-body">
										<ul class="list-group">
											<li class="list-group-item"><i class="fa fa-cog text-primary" aria-hidden="true"></i> Dapibus ac facilisis in</li>
											<li class="list-group-item"><i class="fa fa-cog text-danger" aria-hidden="true"></i> Dapibus ac facilisis in</li>
											<li class="list-group-item"><i class="fa fa-cog text-success" aria-hidden="true"></i> Cras sit amet nibh libero</li>
											<li class="list-group-item"><i class="fa fa-cog text-warning" aria-hidden="true"></i> Porta ac consectetur ac</li>
											<li class="list-group-item"><i class="fa fa-cog text-info" aria-hidden="true"></i> Vestibulum at eros</li>
										</ul>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">List Group  With defalut badges</h3>
									</div>
									<div class="card-body">
										<ul class="list-group">
											<li class="list-group-item">
											Cras justo odio
											<span class="badgetext badge badge-default badge-pill">14</span>
											</li>
											<li class="list-group-item">
											Dapibus ac facilisis in
											<span class=" badgetext badge badge-default badge-pill">2</span>
											</li>
											<li class="list-group-item">
											Morbi leo risus
											<span class=" badgetext badge badge-default badge-pill">1</span>
											</li>
										</ul>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">List Group  With color badges</h3>
									</div>
									<div class="card-body">
										<ul class="list-group">
											<li class="list-group-item justify-content-between">
											Cras justo odio
											<span class="badgetext badge badge-primary badge-pill">14</span>
											</li>
											<li class="list-group-item justify-content-between">
											Dapibus ac facilisis in
											<span class="badgetext badge badge-danger badge-pill">2</span>
											</li>
											<li class="list-group-item justify-content-between">
											Morbi leo risus
											<span class="badgetext badge badge-success badge-pill">1</span>
											</li>
										</ul>
									</div>
								</div>
							</div><!-- col end -->
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">List Group  With Custom content</h3>
									</div>
									<div class="card-body">
										<div class="list-group">
											<a href="#" class="list-group-item list-group-item-action flex-column align-items-start active">
												<div class="d-flex w-100 justify-content-between">
													<h5 class="mb-1 text-primary">List group item heading</h5>
													<small>3 days ago</small>
												</div>
												<p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
												<small>Donec id elit non mi porta.</small>
											</a>
											<a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
												<div class="d-flex w-100 justify-content-between">
													<h5 class="mb-1">List group item heading</h5>
													<small class="text-muted">3 days ago</small>
												</div>
												<p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
												<small class="text-muted">Donec id elit non mi porta.</small>
											</a>
											<a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
												<div class="d-flex w-100 justify-content-between">
													<h5 class="mb-1">List group item heading</h5>
													<small class="text-muted">3 days ago</small>
												</div>
												<p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
												<small class="text-muted">Donec id elit non mi porta.</small>
											</a>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-sm-12 col-md-12 col-lg-6  col-xl-6">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">List Group  With Disabled Custom content</h3>
									</div>
									<div class="card-body">
										<div class="list-group">
											<a href="#" class="list-group-item list-group-item-action flex-column align-items-start disabled">
												<div class="d-flex w-100 justify-content-between">
													<h5 class="mb-1">List group item heading</h5>
													<small>3 days ago</small>
												</div>
												<p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
												<small>Donec id elit non mi porta.</small>
											</a>
											<a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
												<div class="d-flex w-100 justify-content-between">
													<h5 class="mb-1">List group item heading</h5>
													<small class="text-muted">3 days ago</small>
												</div>
												<p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
												<small class="text-muted">Donec id elit non mi porta.</small>
											</a>
											<a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
												<div class="d-flex w-100 justify-content-between">
													<h5 class="mb-1">List group item heading</h5>
													<small class="text-muted">3 days ago</small>
												</div>
												<p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
												<small class="text-muted">Donec id elit non mi porta.</small>
											</a>
										</div>
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