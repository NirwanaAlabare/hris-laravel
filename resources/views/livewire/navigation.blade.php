@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Navigation</li>
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

						<!-- app-content -->
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Basic Nav</h3>
									</div>
									<div class="card-body">
										<ul class="nav1">
											<li class="nav-item1">
												<a class="nav-link active" href="#">Active</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link disabled" href="#">Disabled</a>
											</li>
										</ul>
									</div>
								</div>
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Nav Vertical</h3>
									</div>
									<div class="card-body">
										<ul class="nav1 flex-column">
											<li class="nav-item1">
												<a class="nav-link active" href="#">Active</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link disabled" href="#">Disabled</a>
											</li>
										</ul>
									</div>
								</div>
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Color Nav</h3>
									</div>
									<div class="card-body">
										<ul class="nav nav-pills">
											<li class="nav-item m-2">
												<a class="nav-link active" href="#">Active</a>
											</li>
											<li class="nav-item dropdown m-2">
												<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
												<div class="dropdown-menu">
													<a class="dropdown-item" href="#">Action</a>
													<a class="dropdown-item" href="#">Another action</a>
													<a class="dropdown-item" href="#">Something else here</a>
													<div class="dropdown-divider"></div>
													<a class="dropdown-item" href="#">Separated link</a>
												</div>
											</li>
											<li class="nav-item m-2">
												<a class="nav-link" href="#">Link</a>
											</li>
											<li class="nav-item m-2">
												<a class="nav-link disabled" href="#">Disabled</a>
											</li>
										</ul>
									</div>
								</div>

								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Nav Tabs</h3>
									</div>
									<div class="card-body">
										<ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-text" role="tablist">
											<li class="nav-item">
												<a class="nav-link mt-2 mr-2 border p-3" id="tabs-text-1-tab" data-toggle="tab" href="#tabs-text-1" role="tab"  aria-selected="false">UI/UX Design</a>
											</li>
											<li class="nav-item">
												<a class="nav-link mt-2 mr-2 border p-3" id="tabs-text-2-tab" data-toggle="tab" href="#tabs-text-2" role="tab" aria-selected="false">Programming</a>
											</li>
											<li class="nav-item">
												<a class="nav-link mt-2 mr-2  border active show p-3" id="tabs-text-3-tab" data-toggle="tab" href="#tabs-text-3" role="tab"  aria-selected="true">Graphic</a>
											</li>
											<li class="nav-item">
												<a class="nav-link mt-2 mr-2 border p-3" id="tabs-text-4-tab" data-toggle="tab" href="#tabs-text-4" role="tab" aria-selected="false">Developing</a>
											</li>
											<li class="nav-item">
												<a class="nav-link mt-2 mr-2 border p-3" id="tabs-text-5-tab" data-toggle="tab" href="#tabs-text-5" role="tab" aria-selected="false">Photoshop</a>
											</li>
										</ul>
									</div>
								</div>
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Icon With Title</h3>
									</div>
									<div class="card-body">
										<ul class="nav nav-pills nav-pills-circle" id="tabs_2" role="tablist">
											<li class="nav-item">
												<a class="nav-link border p-3 m-2" id="tab1" data-toggle="tab" href="#tabs_2_1" role="tab" aria-selected="false">
													<span class="nav-link-icon d-block"><i class="fe fe-home"></i> Home</span>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link border p-3 m-2" id="tab2" data-toggle="tab" href="#tabs_2_2" role="tab"  aria-selected="false">
													<span class="nav-link-icon d-block"><i class="fe fe-unlock"></i> Lock </span>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link p-3 border active show m-2" id="tab3" data-toggle="tab" href="#tabs_2_3" role="tab" aria-selected="true">
													<span class="nav-link-icon d-block"><i class="fe fe-play"></i> Videos</span>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link border p-3 m-2" id="tab4" data-toggle="tab" href="#tabs_2_3" role="tab" aria-selected="false">
													<span class="nav-link-icon d-block"><i class="fe fe-layers"></i> Severs</span>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link border p-3 m-2" id="tab5" data-toggle="tab" href="#tabs_2_4" role="tab" aria-selected="false">
													<span class="nav-link-icon d-block"><i class="fe fe-image"></i> Gallery </span>
												</a>
											</li>
										</ul>
									</div>
								</div>
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Icon Nav Bar</h3>
									</div>
									<div class="card-body">
										<ul class="nav nav-pills nav-pills-circle" id="tabs_3" role="tablist">
											<li class="nav-item">
												<a class="nav-link border w-8 h-8 text-center br-100 m-2" id="tab21" data-toggle="tab" href="#tabs_2_1" role="tab" aria-controls="tab1" aria-selected="false">
													<span class="nav-link-icon d-block text-center mx-auto"><i class="fe fe-home"></i></span>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link border w-8 h-8 br-100 m-2" id="tab22" data-toggle="tab" href="#tabs_2_2" role="tab" aria-controls="tab2" aria-selected="false">
													<span class="nav-link-icon d-block text-center mx-auto"><i class="fe fe-unlock"></i></span>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link border active show w-8 h-8 br-100 m-2" id="tab23" data-toggle="tab" href="#tabs_2_3" role="tab" aria-controls="tab3" aria-selected="true">
													<span class="nav-link-icon d-block text-center mx-auto"><i class="fe fe-play"></i></span>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link border w-8 h-8 br-100 m-2" id="tab24" data-toggle="tab" href="#tabs_2_3" role="tab" aria-controls="tab4" aria-selected="false">
													<span class="nav-link-icon d-block text-center mx-auto"><i class="fe fe-layers"></i></span>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link border  w-8 h-8 br-100 m-2" id="tab25" data-toggle="tab" href="#tabs_2_4" role="tab" aria-controls="tab5" aria-selected="false">
													<span class="nav-link-icon d-block text-center mx-auto"><i class="fe fe-image"></i> </span>
												</a>
											</li>
										</ul>
									</div>
								</div>
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Color Nav</h3>
									</div>
									<div class="card-body">
										<ul class="nav1 bg-primary">
											<li class="nav-item1">
												<a class="nav-link text-white active" href="#">Active</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link text-white" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link text-white" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link disabled text-white-50" href="#">Disabled</a>
											</li>
										</ul>
										<ul class="nav1 bg-info mt-4">
											<li class="nav-item1">
												<a class="nav-link text-white active" href="#">Active</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link text-white" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link text-white" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link disabled text-white-50" href="#">Disabled</a>
											</li>
										</ul>
										<ul class="nav1 bg-success mt-4">
											<li class="nav-item1">
												<a class="nav-link text-white active" href="#">Active</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link text-white" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link text-white" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link disabled text-white-50" href="#">Disabled</a>
											</li>
										</ul>
										<ul class="nav1 bg-danger mt-4">
											<li class="nav-item1">
												<a class="nav-link text-white active" href="#">Active</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link text-white" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link text-white" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link disabled text-white-50" href="#">Disabled</a>
											</li>
										</ul>
										<ul class="nav1 bg-secondary mt-4">
											<li class="nav-item1">
												<a class="nav-link text-white active" href="#">Active</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link text-white" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link text-white" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link disabled text-white-50" href="#">Disabled</a>
											</li>
										</ul>
										<ul class="nav1 bg-yellow mt-4">
											<li class="nav-item1">
												<a class="nav-link text-white active" href="#">Active</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link text-white" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link text-white" href="#">Link</a>
											</li>
											<li class="nav-item1">
												<a class="nav-link disabled text-white-50" href="#">Disabled</a>
											</li>
										</ul>
									</div>
								</div>
							</div><!-- col end -->
						</div><!-- row end-->

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