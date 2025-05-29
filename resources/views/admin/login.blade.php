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
									<div class="card" style="border:none; box-shadow: 1px 0 20px rgba(0, 0, 0, 0.08); border-radius: 10px">
										<div class="card-body">
                                            <div class="text-center" style="margin-bottom: 50px; margin-top: 30px">
                                                <img src="{{URL::asset('assets/images/brand/hris.png')}}" width="200px"/>
											</div>
                                            <div id="alert">

                                            </div>
                                            <div class="input-group mb-3">
												<span class="input-group-addon bg-white"><i class="fa fa-user"></i></span>
												<input list="emailSuggestions" type="text" class="form-control" type="email" autocomplete="off"  placeholder="Email" name="email" id="emailInput">
                                                    <datalist id="emailSuggestions">
                                                     <!-- akan diisi oleh JavaScript -->
                                                    </datalist>
											</div>
											<div class="input-group mb-4">
												<span class="input-group-addon bg-white"><i class="fa fa-unlock-alt"></i></span>
												<input type="password" class="form-control" placeholder="Password" name="password">
											</div>
											<div class="row">
												<div class="col-12">
													<button type="button" class="btn btn-newcolor btn-block" id="submitbutton" onclick="login();return false;">Login</button>
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


<script>

    const emailInput = document.getElementById('emailInput');
    const dataList = document.getElementById('emailSuggestions');

    // Ambil email yang tersimpan dari localStorage
    let savedEmails = JSON.parse(localStorage.getItem('savedEmails')) || [];

    // Tampilkan ke datalist
    function populateEmailSuggestions() {
        dataList.innerHTML = '';
        savedEmails.forEach(email => {
            const option = document.createElement('option');
            option.value = email;
            dataList.appendChild(option);
        });
    }

    populateEmailSuggestions();


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
                    const email = emailInput.value.trim();
                   if (email && !savedEmails.includes(email)) {
                       savedEmails.unshift(email); // Tambahkan di awal
                       savedEmails = savedEmails.slice(0, 5); // Simpan max 5 terakhir
                       localStorage.setItem('savedEmails', JSON.stringify(savedEmails));
                   }
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
