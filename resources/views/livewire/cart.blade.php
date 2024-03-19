@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">E-commerce</a></li>
								<li class="breadcrumb-item active" aria-current="page">Cart</li>
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
							<div class="col-lg-12 col-md-12 col-sm-12 bootstrap snippets">
								<!-- Cart -->
								<div class="card">
									<div class="card-header">
										<span class="card-title">Shopping Cart</span>
									</div>
									<div class="card-body hero-feature">
										<div class="table-responsive push">
											<table class="table table-bordered table-hover tbl-cart text-nowrap">
												<thead>
													<tr>
														<td class="hidden-xs"></td>
														<td>Product Name</td>
														<td>Product Price</td>
														<td>Size</td>
														<td>Delete</td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="hidden-xs">
															<a href="#">
																<img src="{{URL::asset('assets/images/products/1.png')}}" alt="Metal Watch" title="" width="47" height="47">
															</a>
														</td>
														<td><a href="#">Metal Watch</a>
														</td>
														<td class="price">$ 122.21</td>
														<td>
															<div class="input-group bootstrap-touchspin w-50">
																<span class="input-group-btn">
																	<button class="btn btn-primary bootstrap-touchspin-down" type="button">-</button>
																</span>
																<span class="input-group-addon bootstrap-touchspin-prefix d-none"></span>
																<input type="text" name="product" value="1" class="input-qty form-control text-center d-block">
																<span class="input-group-addon bootstrap-touchspin-postfix d-none"></span>
																<span class="input-group-btn">
																	<button class="btn btn-primary  bootstrap-touchspin-up" type="button">+</button>
																</span>
															</div>
														</td>
														<td class="text-center">
															<span class="remove_cart" >
																<button class="btn btn-danger" type="button"><i class="fa fa-trash-o"></i></button>
															</span>
														</td>
													</tr>
													<tr>
														<td class="hidden-xs">
															<a>
																<img src="{{URL::asset('assets/images/products/8.png')}}" alt="Model Ladies footwear" title="" width="47" height="47">
															</a>
														</td>
														<td><a href="#">Model Ladies footwear</a>
														</td>
														<td class="price">$ 20.63</td>
														<td>
															<div class="input-group bootstrap-touchspin w-50">
																<span class="input-group-btn">
																	<button class="btn btn-primary  bootstrap-touchspin-down" type="button">-</button>
																</span>
																<span class="input-group-addon bootstrap-touchspin-prefix d-none"></span>
																<input type="text" name="product" value="2" class="input-qty form-control text-center d-block">
																<span class="input-group-addon bootstrap-touchspin-postfix d-none"></span>
																<span class="input-group-btn">
																	<button class="btn btn-primary  bootstrap-touchspin-up" type="button">+</button>
																</span>
															</div>
														</td>
														<td class="text-center">
															<span  class="remove_cart">
																<button class="btn btn-danger" type="button"><i class="fa fa-trash-o"></i></button>
															</span>
														</td>
													</tr>
													<tr>
														<td class="hidden-xs">
															<a href="#">
																<img src="{{URL::asset('assets/images/products/9.png')}}" alt="Men Footwear" title="" width="47" height="47">
															</a>
														</td>
														<td><a href="#">Men Footwear</a>
														</td>
														<td class="price">$ 40.63</td>
														<td>
															<div class="input-group bootstrap-touchspin w-50">
																<span class="input-group-btn">
																	<button class="btn btn-primary  bootstrap-touchspin-down" type="button">-</button>
																</span>
																<span class="input-group-addon bootstrap-touchspin-prefix d-none"></span>
																<input type="text" name="product" value="1" class="input-qty form-control text-center d-block">
																<span class="input-group-addon bootstrap-touchspin-postfix d-none"></span>
																<span class="input-group-btn">
																	<button class="btn btn-primary  bootstrap-touchspin-up" type="button">+</button>
																</span>
															</div>
														</td>
														<td class="text-center">
															<span class="remove_cart">
																<button class="btn btn-danger" type="button"><i class="fa fa-trash-o"></i></button>
															</span>
														</td>
													</tr>
													<tr>
														<td class="hidden-xs">
															<a href="#">
																<img src="{{URL::asset('assets/images/products/4.png')}}" alt="Fast Track Watch" title="" width="47" height="47">
															</a>
														</td>
														<td><a href="#">Fast Track Watch</a>
														</td>
														<td class="price">$ 60.63</td>
														<td>
															<div class="input-group bootstrap-touchspin w-50">
																<span class="input-group-btn">
																	<button class="btn btn-primary  bootstrap-touchspin-down" type="button">-</button>
																</span>
																<span class="input-group-addon bootstrap-touchspin-prefix d-none"></span>
																<input type="text" name="product" value="1" class="input-qty form-control text-center d-block">
																<span class="input-group-addon bootstrap-touchspin-postfix d-none"></span>
																<span class="input-group-btn">
																	<button class="btn btn-primary bootstrap-touchspin-up" type="button">+</button>
																</span>
															</div>
														</td>
														<td class="text-center">
															<span class="remove_cart">
																<button class="btn btn-danger" type="button"><i class="fa fa-trash-o"></i></button>
															</span>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<form class="text-left  product-cart mt-2 mb-0">
											<div class="row">
												<div class="col-6"><input class="productcart form-control" type="text" placeholder="Coupon Code"></div>
												<div class="col-6"><a href="#" class="btn btn-primary">Apply</a></div>
											</div>
										</form>


									</div>
								</div>
								<!-- End Cart -->
							</div>

							<div class="col-lg-12">
								<div class="card ">
									<div class="card-header ">
										<h3 class="card-title">Order Summery</h3>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table class="table table-bordered align-items-center">
												<tbody>
													<tr>
														<td>Cart Subtotal</td>
														<td class="text-right">$456.00</td>
													</tr>
													<tr>
														<td><span>Discount</span></td>
														<td class="text-right text-muted"><span>0.5%</span></td>
													</tr>
													<tr>
														<td><span>Totals</span></td>
														<td class="text-right text-muted"><span>$456.00</span></td>
													</tr>
													<tr>
														<td><span>Order Total</span></td>
														<td><h2 class="price text-right mb-0">$456.00</h2></td>
													</tr>
												</tbody>
											</table>
										</div>
										<form class="text-center mt-0">
											<a href="{{url('shop')}}" class="btn btn-primary  mt-2"><i class="fa fa-arrow-circle-left"></i> Continue Shopping</a>
											<a href="#" class="btn btn-success mt-2">Checkout <i class="fa fa-arrow-circle-right"></i></a>
										</form>
									</div>
								</div>
							</div>
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