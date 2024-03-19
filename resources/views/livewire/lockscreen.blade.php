@extends('layouts.customapp')

@section('custom-styles')

@endsection

@section('content')

			<!-- page-content -->
			<div class="page-content">
				<div class="container text-center text-dark">
					<div class="row">
						<div class="col-lg-4 d-block mx-auto">
							<div class="row">
								<div class="col-xl-12 col-md-12 col-md-12">
									<div class="card">
										<div class="card-body">
											<div class="text-center mb-6">
												<img src="{{URL::asset('assets/images/brand/logo.png')}}" class="" alt="">
											</div>
											<div class="text-center mb-4 ">
												<img src="{{URL::asset('assets/images/users/female/5.jpg')}}" alt="user-img" class="avatar avatar-xl brround mCS_img_loaded">
											</div>
											<span class="m-4 d-none d-lg-block text-center">
												<span class="h4"><strong>Alison</strong></span>
											</span>
											<div class="form-group">
												<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
											</div>
											<a href="{{url('index')}}" class="btn btn-primary btn-block">Unlock</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
			<!-- page-content end -->

@endsection('content')

@section('custom-scripts')

@endsection