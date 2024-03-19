@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Elements</a></li>
								<li class="breadcrumb-item active" aria-current="page">Colors</li>
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
							<div class="col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Contextual colors</h3>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example p-0">
												<div class="row">
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7 bg-primary  mr-4"></div>
															<div>
																<strong>Primary</strong><br>
																<code>.bg-primary</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-info  mr-4"></div>
															<div>
																<strong>Info</strong><br>
																<code>.bg-info</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-secondary  mr-4"></div>
															<div>
																<strong>Secondary</strong><br>
																<code>.bg-secondary</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-warning  mr-4"></div>
															<div>
																<strong>Warning</strong><br>
																<code>.bg-warning</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-danger  mr-4"></div>
															<div>
																<strong>Danger</strong><br>
																<code>.bg-danger</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-success  mr-4"></div>
															<div>
																<strong>Success</strong><br>
																<code>.bg-success</code>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Gradient colors</h3>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example p-0">
												<div class="row">
													<div class="col-md-12 col-lg-6 col-sm-12 col-xl-4">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-gradient-primary  mr-4"></div>
															<div>
																<strong>Primary</strong><br>
																<code>.bg-gradient-primary</code>
															</div>
														</div>
													</div>
													<div class="col-md-12 col-lg-6 col-sm-12 col-xl-4">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-gradient-secondary  mr-4"></div>
															<div>
																<strong>Secondary</strong><br>
																<code>.bg-gradient-secondary</code>
															</div>
														</div>
													</div>
													<div class="col-md-12 col-lg-6 col-sm-12 col-xl-4">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7 bg-gradient-danger  mr-4"></div>
															<div>
																<strong>Danger</strong><br>
																<code>.bg-gradient-danger</code>
															</div>
														</div>
													</div>
													<div class="col-md-12 col-lg-6 col-sm-12 col-xl-4">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-gradient-warning  mr-4"></div>
															<div>
																<strong>Warning</strong><br>
																<code>.bg-gradient-warning</code>
															</div>
														</div>
													</div>
													<div class="col-md-12 col-lg-6 col-sm-12 col-xl-4">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-gradient-info  mr-4"></div>
															<div>
																<strong>info</strong><br>
																<code>.bg-gradient-info</code>
															</div>
														</div>
													</div>
													<div class="col-md-12 col-lg-6 col-sm-12 col-xl-4">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-gradient-success  mr-4"></div>
															<div>
																<strong>Success</strong><br>
																<code>.bg-gradient-success</code>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Other colors</h3>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example p-0">
											    <div class="row">
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-blue  mr-4"></div>
															<div>
																<strong>Blue</strong><br>
																<code>.bg-blue</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-red  mr-4"></div>
															<div>
																<strong>Red</strong><br>
																<code>.bg-red</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-teal  mr-4"></div>
															<div>
																<strong>Teal</strong><br>
																<code>.bg-teal</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-azure   mr-4"></div>
															<div>
																<strong>Azure</strong><br>
																<code>.bg-azure </code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-orange  mr-4"></div>
															<div>
																<strong>Orange</strong><br>
																<code>.bg-orange</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-cyan  mr-4"></div>
															<div>
																<strong>Cyan</strong><br>
																<code>.bg-cyan</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-indigo  mr-4"></div>
															<div>
																<strong>Indigo</strong><br>
																<code>.bg-indigo</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-yellow  mr-4"></div>
															<div>
																<strong>Yellow</strong><br>
																<code>.bg-yellow</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7 bg-gray  mr-4"></div>
															<div>
																<strong>Gray</strong><br>
																<code>.bg-gray</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-purple   mr-4"></div>
															<div>
																<strong>Purple</strong><br>
																<code>.bg-purple </code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-lime  mr-4"></div>
															<div>
																<strong>Lime</strong><br>
																<code>.bg-lime</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7 bg-gray-dark  mr-4"></div>
															<div>
																<strong>Dark Gray</strong><br>
																<code>.bg-gray-dark</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-pink   mr-4"></div>
															<div>
																<strong>Pink</strong><br>
																<code>.bg-pink </code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-green  mr-4"></div>
															<div>
																<strong>Green</strong><br>
																<code>.bg-green</code>
															</div>
														</div>
													</div>
													<div class="col-md-6 col-lg-4 col-sm-6">
														<div class="d-flex align-items-center mb-3 mt-3">
															<div class="w-8 h-8 br-7  bg-secondary  mr-4"></div>
															<div>
																<strong>secondary</strong><br>
																<code>.bg-secondary</code>
															</div>
														</div>
													</div>
												</div>
												<div class="highlight mb-0">
							<pre>Example: <code class="language-html" data-lang="html"><span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">"d-flex align-items-center mb-3 mt-3"</span><span class="nt">&gt;</span>
				<span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">"w-7 h-7 bg-blue rounded mr-4"</span><span class="nt">&gt;&lt;/div&gt;</span>
				<span class="nt">&lt;div&gt;</span>
					<span class="nt">&lt;strong&gt;</span>Blue<span class="nt">&lt;/strong&gt;&lt;br</span> <span class="nt">/&gt;</span>
					<span class="nt">&lt;code&gt;</span>.bg-blue<span class="nt">&lt;/code&gt;</span>
				<span class="nt">&lt;/div&gt;</span>
			<span class="nt">&lt;/div&gt;</span>
				</code></pre>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- row end -->
						</div>

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