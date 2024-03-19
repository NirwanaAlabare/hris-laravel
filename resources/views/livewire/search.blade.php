@extends('layouts.app')

@section('styles')

		<!--Select2 css -->
		<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

		<!---Font icons css-->
		<link href="{{URL::asset('assets/plugins/webfonts/plugin.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('assets/plugins/webfonts/icons.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('assets/plugins/iconfonts/icons.css')}}" rel="stylesheet" />
		<link  href="{{URL::asset('assets/fonts/fonts/font-awesome.min.css')}}" rel="stylesheet">

		<!-- INTERNAl Themes  css-->
		<link rel="stylesheet" href="{{URL::asset('assets/plugins/rating/dist/themes/themes.css')}}">
		<link rel="stylesheet" href="{{URL::asset('assets/plugins/rating/css/examples.css')}}">

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Advanced UI</a></li>
								<li class="breadcrumb-item active" aria-current="page">Search</li>
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
						<div class="row row-cards">
							<div class="col-lg-3">
								<div class="row"><!-- row -->
									<div class="col-md-12 col-lg-12">
									    <div class="card">
											<div class="card-header pb-0">
												<h3 class="card-title">Categories</h3>
											</div>
											<div class="card-body">
												<div class="search-radio mb-3">
													<div class="radio">
														<input type="radio" name="shop-filter__price" id="shop-filter-price_1" value="" checked="">
														<label for="shop-filter-price_1">Mens</label>
													</div>
													<div class="radio">
														<input type="radio" name="shop-filter__price" id="shop-filter-price_2" value="">
														<label for="shop-filter-price_2">Womens</label>
													</div>
													<div class="radio">
														<input type="radio" name="shop-filter__price" id="shop-filter-price_3" value="">
														<label for="shop-filter-price_3">kids</label>
													</div>
													<div class="radio">
														<input type="radio" name="shop-filter__price" id="shop-filter-price_4" value="specify">
														<label for="shop-filter-price_4" class="mb-0"> Accessories</label>
													</div>
												</div>
												<div class="form-group">
													<label class="form-label">Category</label>
													<select name="beast" id="select-beast4" class="form-control custom-select select2">
														<option value="0">--Select--</option>
														<option value="1">Dress</option>
														<option value="2">Bags &amp; Purses</option>
														<option value="3">Coat &amp; Jacket</option>
														<option value="4">Beauty</option>
														<option value="5">Jeans</option>
														<option value="5">Jewellery</option>
														<option value="5">Electronics</option>
														<option value="5">Sports</option>
														<option value="5">Technology</option>
														<option value="5">Watches</option>
														<option value="5">Accessories</option>
													</select>
												</div>
												<a class="btn btn-info" href="#">Search</a>
											</div>
										</div>
										<div class="card">
											<div class="card-header pb-0">
												<h3 class="card-title">Category Filters</h3>
											</div>
											<div class="card-body">
												<div class="form-group">
													<label class="form-label">Brand</label>
													<select name="beast" id="select-beast" class="form-control custom-select select2">
														<option value="0">--Select--</option>
														<option value="1">Wallmart</option>
														<option value="2">Catseye</option>
														<option value="3">Moonsoon</option>
														<option value="4">Textmart</option>
													</select>
												</div>
												<div class="form-group">
													<label class="form-label">Color</label>
													<select name="beast" id="select-beast2" class="form-control custom-select select2">
														<option value="0">--Select--</option>
														<option value="1">White</option>
														<option value="2">Black</option>
														<option value="3">Red</option>
														<option value="4">Green</option>
														<option value="5">Blue</option>
														<option value="6">Yellow</option>
													</select>
												</div>
												<div class="form-group">
													<label class="form-label">Type</label>
													<select name="beast" id="select-beast3" class="form-control custom-select select2">
														<option value="0">--Select--</option>
														<option value="1">Extra Small</option>
														<option value="2">Small</option>
														<option value="3">Medium</option>
														<option value="4">Large</option>
														<option value="5">Extra Large</option>
													</select>
												</div>
												<a class="btn btn-primary" href="#">Apply Filter</a>
											</div>
										</div>
									</div>
								</div><!-- row end -->
							</div><!-- col end -->
							<div class="col-lg-9">
								<div class="input-group">
									<input type="text" class="form-control bg-white" placeholder="">
									<div class="input-group-append ">
										<button type="button" class="btn btn-primary ">
											<i class="fa fa-search " aria-hidden="true"></i>
										</button>
									</div>
								</div>
								<div class="card mt-5 store border-top-0">
									<div class="table-responsive">
										<table class="table card-table table-vcenter text-nowrap">
											<tr>
												<td>Mens Jackets</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">987 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">50% off</td>
												<td class="text-right">
													<strong>$1,987</strong>
												</td>
											</tr>
											<tr>
												<td> Occational Special Edition</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome1" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">675 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap ">60% off</td>
												<td class="text-right">
													<strong>$2,679</strong>
												</td>
											</tr>
											<tr>
												<td>Sares
													<div class="badge badge-primary badge-md">New</div>
												</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome2" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">765 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">25% off</td>
												<td class="text-right">
													<strong>$999</strong>
												</td>
											</tr>
											<tr>
												<td>Western Wear</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome3" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">678 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">30% off</td>
												<td class="text-right">
													<strong>$699</strong>
												</td>
											</tr>
											<tr>
												<td>Kids Dress
													<div class="badge badge-danger badge-md">New</div>
												</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome4" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">345 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">47% off</td>
												<td class="text-right">
													<strong>$399</strong>
												</td>
											</tr>
											<tr>
												<td>Kids Toys</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome5" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">876 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">26% off</td>
												<td class="text-right">
													<strong>$299</strong>
												</td>
											</tr>
											<tr>
												<td>Sony KD-49XE7005
													<div class="badge badge-primary badge-md">New</div>
												</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome6" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">563 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">43% off</td>
												<td class="text-right">
													<strong>$22,269</strong>
												</td>
											</tr>
											<tr>
												<td>Huawei Mate Pro Dual SIM </td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome7" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">671 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">20% off</td>
												<td class="text-right">
													<strong>$9,999</strong>
												</td>
											</tr>
											<tr>
												<td>Novel Books
													<div class="badge badge-info badge-md">New</div>
												</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome8" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">354 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">14% off</td>
												<td class="text-right">
													<strong>$899</strong>
												</td>
											</tr>
											<tr>
												<td>Welcare Fitness</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome9" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">937 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">40% off</td>
												<td class="text-right">
													<strong>$3,987</strong>
												</td>
											</tr>
											<tr>
												<td>Red rose Gift</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome10" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">137 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">80% off</td>
												<td class="text-right">
													<strong>$987</strong>
												</td>
											</tr>
											<tr>
												<td>New Model Saree</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome11" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">157 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">30% off</td>
												<td class="text-right">
													<strong>$2,452</strong>
												</td>
											</tr>
											<tr>
												<td>Welcare Fitness</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome12" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">937 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">40% off</td>
												<td class="text-right">
													<strong>$3,987</strong>
												</td>
											</tr>
											<tr>
												<td>W-top</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome13" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">1452 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">40% off</td>
												<td class="text-right">
													<strong>$123</strong>
												</td>
											</tr>
											<tr>
												<td>Watch</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome14" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">637 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">10% off</td>
												<td class="text-right">
													<strong>$87</strong>
												</td>
											</tr>
											<tr>
												<td>College Bag</td>
												<td>
													<div class="star-ratings start-ratings-main clearfix  mt-2 mb-0">
														<div class="stars stars-example-fontawesome">
															<select id="example-fontawesome15" name="rating" autocomplete="off">
																<option value="1">1</option>
																<option value="2">2</option>
																<option value="3">3</option>
																<option value="4">4</option>
																<option value="5">5</option>
															</select>
														</div>
													</div>
												</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">536 views</td>
												<td class="text-right text-muted d-none d-md-table-cell text-nowrap">25% off</td>
												<td class="text-right">
													<strong>$1,251</strong>
												</td>
											</tr>
										</table>
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
		
        <!-- INTERNAL Rating js-->
		<script src="{{URL::asset('assets/plugins/rating/jquery.rating-stars.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/rating/jquery.barrating.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/rating/js/examples.js')}}"></script>
		
        <!--Time Counter js-->
		<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>
		
        <!--Select2 js -->
		<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
		<script src="{{URL::asset('assets/js/select2.js')}}"></script>

@endsection