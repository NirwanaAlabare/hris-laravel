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
                                                <img src="{{URL::asset('assets/images/brand/logo2.png')}}" width="200px"/>
											</div>
											<h3>Login</h3>
											<p class="text-muted">Sign In to your account</p>
                                            <div id="alert">

                                            </div>
                                            <div class="input-group mb-3">
												<span class="input-group-addon bg-white"><i class="fa fa-user"></i></span>
												<input type="text" class="form-control" type="email" autocomplete="off"  placeholder="Email" name="email">
											</div>
											<div class="input-group mb-4">
												<span class="input-group-addon bg-white"><i class="fa fa-unlock-alt"></i></span>
												<input type="password" class="form-control" placeholder="Password" name="password">
											</div>
											<div class="row">
												<div class="col-12">
													<button type="button" class="btn btn-primary btn-block" id="submitbutton" onclick="login();return false;">Login</button>
												</div>
												<div class="col-12">
													<a href="{{url('forgot-password')}}" class="btn btn-link box-shadow-0 px-0">Forgot password?</a>
												</div>
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
            <!-- END LOGIN FORM -->

@endsection('content')

@section('custom-scripts')
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
{!! HTML::script('assets/global/plugins/respond.min.js') !!}
{!! HTML::script('assets/global/plugins/excanvas.min.js') !!}
<![endif]-->
{!! HTML::script("js/jquery-3.6.0.min.js") !!}
{!!  HTML::script("assets/global/plugins/bootstrap/js/bootstrap.min.js")  !!}
{!!  HTML::script("assets/global/scripts/metronic.js")  !!}
{!!  HTML::script("assets/admin/layout/scripts/demo.js")  !!}
{!! HTML::script('assets/global/plugins/froiden-helper/helper.js') !!}

<!-- END PAGE LEVEL SCRIPTS -->

<script>

    jQuery(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        Metronic.init(); // init metronic core components

{{--          // init background slide images
        $.backstretch([
                "{{ URL::asset('assets/admin/pages/media/bg/1.jpg') }}",
                "{{ URL::asset('assets/admin/pages/media/bg/2.jpg') }}",
                "{{ URL::asset('assets/admin/pages/media/bg/3.jpg') }}",
                "{{ URL::asset('assets/admin/pages/media/bg/4.jpg') }}"
            ], {
                fade: 1000,
                duration: 8000
            }
        );
  --}}    });
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
<!-- END JAVASCRIPTS -->
@endsection
