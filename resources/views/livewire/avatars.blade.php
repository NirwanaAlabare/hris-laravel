@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Elements</a></li>
								<li class="breadcrumb-item active" aria-current="page">Avatars</li>
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
							<div class="col-md-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h4  class="card-title">Simple Square Avatar</h4>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example border border-bottom-0">
												<div class="avatar-list">
													<span><img class="avatar mr-2" src="{{URL::asset('assets/images/users/male/1.jpg')}}" alt="img"></span>
													<span><img class="avatar mr-2" src="{{URL::asset('assets/images/users/male/2.jpg')}}" alt="img"></span>
													<span><img class="avatar mr-2" src="{{URL::asset('assets/images/users/female/1.jpg')}}" alt="img"></span>
													<span><img class="avatar mr-2" src="{{URL::asset('assets/images/users/female/2.jpg')}}" alt="img"></span>
													<span><img class="avatar mr-2" src="{{URL::asset('assets/images/users/male/3.jpg')}}" alt="img"></span>
												</div>
											</div>
											<div class="highlight">
                        <pre><code class="language-html" data-lang="html"><span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url(./{{URL::asset('assets/images/users/male/1.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/2.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/1.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/2.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/3.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span></code></pre>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h4 class="card-title">Simple Round Avatar</h4>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example  border border-bottom-0">
												<div class="avatar-list">
													<span><img class="avatar brround mr-2" src="{{URL::asset('assets/images/users/male/12.jpg')}}" alt="img"></span>
													<span><img class="avatar brround mr-2" src="{{URL::asset('assets/images/users/male/13.jpg')}}" alt="img"></span>
													<span><img class="avatar brround mr-2" src="{{URL::asset('assets/images/users/female/11.jpg')}}" alt="img"></span>
													<span><img class="avatar brround mr-2" src="{{URL::asset('assets/images/users/female/12.jpg')}}" alt="img"></span>
													<span><img class="avatar brround mr-2" src="{{URL::asset('assets/images/users/male/14.jpg')}}" alt="img"></span>
												</div>
											</div>
											<div class="highlight">
                        <pre><code class="language-html" data-lang="html"><span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar  brround"</span> <span class="na">style=</span><span class="s">"background-image: url(./{{URL::asset('assets/images/users/male/12.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar  brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/13.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar  brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/11.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar  brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/12.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar  brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/14.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span></code></pre>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

                        <!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h4  class="card-title">Avatar Size</h4>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example  border border-bottom-0">
												<div class="avatar-list">
													<span><img class="avatar avatar-sm mr-2" src="{{URL::asset('assets/images/users/male/4.jpg')}}" alt="img"></span>
													<span><img class="avatar mr-2" src="{{URL::asset('assets/images/users/female/3.jpg')}}" alt="img"></span>
													<span><img class="avatar avatar-md mr-2" src="{{URL::asset('assets/images/users/male/5.jpg')}}" alt="img"></span>
													<span><img class="avatar avatar-lg mr-2" src="{{URL::asset('assets/images/users/male/6.jpg')}}" alt="img"></span>
													<span><img class="avatar avatar-xl mr-2" src="{{URL::asset('assets/images/users/female/4.jpg')}}" alt="img"></span>
													<span><img class="avatar avatar-xxl mr-2" src="{{URL::asset('assets/images/users/male/7.jpg')}}" alt="img"></span>
												</div>
											</div>
											<div class="highlight">
                        <pre><code class="language-html" data-lang="html"><span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar avatar-sm"</span> <span class="na">style=</span><span class="s">"background-image: url(./{{URL::asset('assets/images/users/male/4.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url(./{{URL::asset('assets/images/users/female/3.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar avatar-md"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/6.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar avatar-lg"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/6.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar avatar-xl"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/4.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar avatar-xxl"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/7.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span></code></pre>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h4 class="card-title">Rounded Avatar Size</h4>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example  border border-bottom-0">
												<div class="avatar-list">
													<span><img class="avatar avatar-sm brround mr-2" src="{{URL::asset('assets/images/users/male/15.jpg')}}" alt="img"></span>
													<span><img class="avatar brround mr-2" src="{{URL::asset('assets/images/users/female/13.jpg')}}" alt="img"></span>
													<span><img class="avatar avatar-md brround mr-2" src="{{URL::asset('assets/images/users/male/16.jpg')}}" alt="img"></span>
													<span><img class="avatar avatar-lg brround mr-2" src="{{URL::asset('assets/images/users/male/17.jpg')}}" alt="img"></span>
													<span><img class="avatar avatar-xl brround mr-2" src="{{URL::asset('assets/images/users/female/14.jpg')}}" alt="img"></span>
													<span><img class="avatar avatar-xxl brround mr-2" src="{{URL::asset('assets/images/users/male/18.jpg')}}" alt="img"></span>
												</div>
											</div>
											<div class="highlight">
                        <pre><code class="language-html" data-lang="html"><span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar avatar-sm brround"</span> <span class="na">style=</span><span class="s">"background-image: url(./{{URL::asset('assets/images/users/male/15.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar brround"</span> <span class="na">style=</span><span class="s">"background-image: url(./{{URL::asset('assets/images/users/female/13.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar avatar-md brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/16.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar avatar-lg brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/17.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar avatar-xl brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/14.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar avatar-xxl brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/18.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span></code></pre>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h4  class="card-title">Avatar Status</h4>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example  border border-bottom-0">
												<div class="avatar-list">
													<img class="avatar" src="{{URL::asset('assets/images/users/male/19.jpg')}}" alt="">
													<span class="avatar"><img class="avatar" src="{{URL::asset('assets/images/users/female/15.jpg')}}" alt="">
														<span class="avatar-status"></span>
													</span>
													<span class="avatar"><img class="avatar" src="{{URL::asset('assets/images/users/male/20.jpg')}}" alt="">
														<span class="avatar-status bg-red"></span>
													</span>
													<span class="avatar"><img class="avatar" src="{{URL::asset('assets/images/users/female/16.jpg')}}" alt="">
														<span class="avatar-status bg-green"></span>
													</span>
													<span class="avatar"><img class="avatar" src="{{URL::asset('assets/images/users/female/17.jpg')}}" alt="">
														<span class="avatar-status bg-yellow"></span>
													</span>
												</div>
											</div>
											<div class="highlight">
                        <pre><code class="language-html" data-lang="html"><span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/8.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/5.jpg')}})"</span><span class="nt">&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar-status"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/9.jpg')}})"</span><span class="nt">&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar-status bg-red"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/6.jpg')}})"</span><span class="nt">&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar-status bg-green"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/7.jpg')}})"</span><span class="nt">&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar-status bg-yellow"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;/span&gt;</span></code></pre>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h4 class="card-title">Rounded Avatar Status</h4>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example  border border-bottom-0">
												<div class="avatar-list">
													<img class="avatar brround" src="{{URL::asset('assets/images/users/male/19.jpg')}}" alt="">
													<span class="avatar brround"><img class="avatar brround" src="{{URL::asset('assets/images/users/female/15.jpg')}}" alt="">
														<span class="avatar-status"></span>
													</span>
													<span class="avatar brround"><img class="avatar brround" src="{{URL::asset('assets/images/users/male/20.jpg')}}" alt="">
														<span class="avatar-status bg-red"></span>
													</span>
													<span class="avatar brround"><img class="avatar brround" src="{{URL::asset('assets/images/users/female/16.jpg')}}" alt="">
														<span class="avatar-status bg-green"></span>
													</span>
													<span class="avatar brround"><img class="avatar brround" src="{{URL::asset('assets/images/users/female/17.jpg')}}" alt="">
														<span class="avatar-status bg-yellow"></span>
													</span>
												</div>
											</div>
											<div class="highlight">
                        <pre><code class="language-html" data-lang="html"><span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/19.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/15.jpg')}})"</span><span class="nt">&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar-status"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/20.jpg')}})"</span><span class="nt">&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar-status bg-red"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/16.jpg')}})"</span><span class="nt">&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar-status bg-green"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/17.jpg')}})"</span><span class="nt">&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar-status bg-yellow"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;/span&gt;</span></code></pre>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

                        <!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h4  class="card-title">Avatars List</h4>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example  border border-bottom-0">
												<div class="avatar-list avatar-list-stacked">
													<span><img class="avatar mr-2" src="{{URL::asset('assets/images/users/male/10.jpg')}}" alt="img"></span>
													<span><img class="avatar mr-2" src="{{URL::asset('assets/images/users/female/8.jpg')}}" alt="img"></span>
													<span><img class="avatar mr-2" src="{{URL::asset('assets/images/users/female/9.jpg')}}" alt="img"></span>
													<span><img class="avatar mr-2" src="{{URL::asset('assets/images/users/female/10.jpg')}}" alt="img"></span>
													<span><img class="avatar mr-2" src="{{URL::asset('assets/images/users/male/11.jpg')}}" alt="img"></span>
												</div>
											</div>
											<div class="highlight">
                        <pre><code class="language-html" data-lang="html"><span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">"avatar-list avatar-list-stacked"</span><span class="nt">&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/10.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/8.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/9.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/10.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/11.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar"</span><span class="nt">&gt;</span>+8<span class="nt">&lt;/span&gt;</span>
<span class="nt">&lt;/div&gt;</span></code></pre>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h4 class="card-title">Rounded Avatars List</h4>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example  border border-bottom-0">
												<div class="avatar-list avatar-list-stacked">
													<span><img class="avatar brround" src="{{URL::asset('assets/images/users/female/12.jpg')}}" alt="img"></span>
													<span><img class="avatar brround" src="{{URL::asset('assets/images/users/female/21.jpg')}}" alt="img"></span>
													<span><img class="avatar brround" src="{{URL::asset('assets/images/users/female/29.jpg')}}" alt="img"></span>
													<span><img class="avatar brround" src="{{URL::asset('assets/images/users/female/2.jpg')}}" alt="img"></span>
													<span><img class="avatar brround" src="{{URL::asset('assets/images/users/male/34.jpg')}}" alt="img"></span>
												</div>
											</div>
											<div class="highlight">
                        <pre><code class="language-html" data-lang="html"><span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">"avatar-list avatar-list-stacked"</span><span class="nt">&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/12.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/21.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/29.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/2.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar brround"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/34.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
  <span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar brround"</span><span class="nt">&gt;</span>+8<span class="nt">&lt;/span&gt;</span>
<span class="nt">&lt;/div&gt;</span></code></pre>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-md-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h4 class="card-title">Simple Radius Avatar</h4>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example  border border-bottom-0">
												<div class="avatar-list">
													<span><img class="avatar bradius mr-2" src="{{URL::asset('assets/images/users/male/1.jpg')}}" alt="img"></span>
													<span><img class="avatar bradius mr-2" src="{{URL::asset('assets/images/users/male/2.jpg')}}" alt="img"></span>
													<span><img class="avatar bradius mr-2" src="{{URL::asset('assets/images/users/female/1.jpg')}}" alt="img"></span>
													<span><img class="avatar bradius mr-2" src="{{URL::asset('assets/images/users/female/2.jpg')}}" alt="img"></span>
													<span><img class="avatar bradius mr-2" src="{{URL::asset('assets/images/users/male/3.jpg')}}" alt="img"></span>
												</div>
											</div>
											<div class="highlight">
						<pre><code class="language-html" data-lang="html"><span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar bradius"</span> <span class="na">style=</span><span class="s">"background-image: url(./{{URL::asset('assets/images/users/male/1.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
	<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar bradius"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/2.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
	<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar bradius"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/1.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
	<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar bradius"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/2.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
	<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar bradius"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/3.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span></code></pre>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 col-lg-6">
								<div class="card">
									<div class="card-header pb-0">
										<h4 class="card-title">Radius Avatar Size</h4>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example  border border-bottom-0">
												<div class="avatar-list">
													<span><img class="avatar bradius avatar-sm mr-2" src="{{URL::asset('assets/images/users/male/4.jpg')}}" alt="img"></span>
													<span><img class="avatar bradius mr-2" src="{{URL::asset('assets/images/users/female/3.jpg')}}" alt="img"></span>
													<span><img class="avatar  bradius avatar-md mr-2" src="{{URL::asset('assets/images/users/male/5.jpg')}}" alt="img"></span>
													<span><img class="avatar bradius avatar-lg mr-2" src="{{URL::asset('assets/images/users/male/6.jpg')}}" alt="img"></span>
													<span><img class="avatar  bradius avatar-xl mr-2" src="{{URL::asset('assets/images/users/female/4.jpg')}}" alt="img"></span>
													<span><img class="avatar  bradius avatar-xxl mr-2" src="{{URL::asset('assets/images/users/male/7.jpg')}}" alt="img"></span>
												</div>
											</div>
											<div class="highlight">
                        <pre><code class="language-html" data-lang="html"><span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar avatar-sm bradius"</span> <span class="na">style=</span><span class="s">"background-image: url(./{{URL::asset('assets/images/users/male/4.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar bradius"</span> <span class="na">style=</span><span class="s">"background-image: url(./{{URL::asset('assets/images/users/female/3.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar bradius avatar-md"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/6.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar  bradius avatar-lg"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/6.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar bradius avatar-xl"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/4.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"avatar bradius avatar-xxl"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/male/7.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span></code></pre>
											</div>
										</div>
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