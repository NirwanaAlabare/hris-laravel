@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Pages</a></li>
								<li class="breadcrumb-item active" aria-current="page">Terms</li>
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
						<div class="row ">
							<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<h4><b>Welcome to Sparic</b></h4>
										<p>I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences</p>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<h4><b>Using Our Services</b></h4>
										<p>I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences</p>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<h4><b>Privacy policy</b></h4>
										<p>I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences</p>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<h4><b>Copyright </b></h4>
										<p>I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences</p>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<h4><b>Terms and Conditions</b></h4>
										<p>I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences</p>
										<ul>
											<li><i class="fa fa-angle-double-right" ></i> ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores </li>
											<li><i class="fa fa-angle-double-right" ></i> quas molestias excepturi sint occaecati cupiditate non provident</li>
											<li><i class="fa fa-angle-double-right" ></i> Nam libero tempore, cum soluta nobis est eligendi optio cumque</li>
											<li><i class="fa fa-angle-double-right" ></i> Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates</li>
											<li><i class="fa fa-angle-double-right" ></i> repudiandae sint et molestiae non recusandae itaque earum rerum hic tenetur a sapiente delectus</li>
											<li><i class="fa fa-angle-double-right" ></i> ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat</li>
										</ul>
									</div>
								</div>
								<div class="card">
									<div class="card-body">
										<div class="terms text-center">
											<p>Was this information is Helpful?</p>
											<a class="btn btn-primary text-white">Yes</a>
											<a class="btn btn-secondary text-white">No</a>
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