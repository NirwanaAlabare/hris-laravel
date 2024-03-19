@extends('admin.layouts.customapp')

@section('custom-styles')

@endsection

@section('content')
<!-- BEGIN LOGIN FORM -->
{!!  Form::open(array('url' => '','id'=> 'adminLogin', 'class' =>'login-form'))  !!}

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
												<img src="{{URL::asset('assets/images/brand/logo2.png')}}" class="" alt="">
											</div>
											<div class="text-center mb-4 ">
												<img src="{{URL::asset('assets/images/users/avatars/avatar4.png')}}" alt="user-img" class="avatar avatar-xl brround mCS_img_loaded">
											</div>
											<span class="m-4 d-none d-lg-block text-center">												
												<span class="h4"><strong>{{ $name }}</strong></span>
												<p class="text-muted"><i class="fa fa-lock"></i> Lock Screen</p>
											</span>
											<div class="form-group">
												<input type="hidden" name="email" value="{{ $email}}">
												<input type="password" class="form-control" name="password" placeholder="Password">
											</div>
											<div class="form-group">
												<button type="button" class="btn btn-secondary btn-block" id="submitbutton" onclick="login();return false;"><i class="fa fa-unlock-alt"></i> Unlock</button>
												<a href="{{ route('admin.logout') }}" class="btn btn-primary btn-block"><i class="fa fa-users"></i> Switch Account</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>

	<!-- page-content end -->
	{!! Form::close() !!}

@endsection('content')

@section('custom-scripts')
<!--[if lt IE 9]>
	{!! HTML::script('assets/global/plugins/respond.min.js') !!}
	{!! HTML::script('assets/global/plugins/excanvas.min.js') !!}
	<![endif]-->
	{!! HTML::script("js/jquery-3.6.0.min.js") !!}
	{!!  HTML::script("assets/global/plugins/bootstrap/js/bootstrap.min.js")  !!}
	{!! HTML::script('assets/global/plugins/froiden-helper/helper.js') !!}
	
	<!-- END PAGE LEVEL SCRIPTS -->
	
	<script>

		jQuery(document).ready(function () {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
		});
	</script>
	
<script>
    function login() {
        $.easyAjax({
            type: 'POST',
            url: "{{route('admin.login')}}",
            data: $('#adminLogin').serialize(),
            container: "#adminLogin",
            messagePosition: 'inline',
            success: function (response) {
                if (response.status == "success") {
                    $('#login-form')[0].reset();
                }
            }
        });
        return false;
    }

	$(document).on('keypress',function(e) {
        if(e.which == 13) {
            login();
        }
    });


</script>
@endsection