@extends('layouts.app')

@section('styles')

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Elements</a></li>
								<li class="breadcrumb-item active" aria-current="page">Tags</li>
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
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Default tag</h3>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example border border-bottom-0">
												<div class="tags">
													<span class="tag">First tag</span>
													<span class="tag">Second tag</span>
													<span class="tag">Third tag</span>
												</div>
											</div>
											<div class="highlight">
						<pre>Example:
<code class="language-html" data-lang="html"><span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"tag"</span><span class="nt">&gt;</span>First tag<span class="nt">&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"tag"</span><span class="nt">&gt;</span>Second tag<span class="nt">&lt;/span&gt;</span>
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"tag"</span><span class="nt">&gt;</span>Third tag<span class="nt">&lt;/span&gt;</span></code></pre>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Link tag</h3>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example border border-bottom-0">
												<div class="tags">
													<a href="#" class="tag">First tag</a>
													<a href="#" class="tag">Second tag</a>
													<a href="#" class="tag">Third tag</a>
												</div>
											</div>
											<div class="highlight">
                        <pre>Example:
<code class="language-html" data-lang="html"><span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">"#"</span> <span class="na">class=</span><span class="s">"tag"</span><span class="nt">&gt;</span>First tag<span class="nt">&lt;/a&gt;</span>
<span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">"#"</span> <span class="na">class=</span><span class="s">"tag"</span><span class="nt">&gt;</span>Second tag<span class="nt">&lt;/a&gt;</span>
<span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">"#"</span> <span class="na">class=</span><span class="s">"tag"</span><span class="nt">&gt;</span>Third tag<span class="nt">&lt;/a&gt;</span></code></pre>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- row end -->

						<!-- row -->
						<div class="row">
							<div class="col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Rounded tag</h3>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example border">
												<div class="tags">
													<span class="tag tag-rounded">First tag</span>
													<span class="tag tag-rounded">Second tag</span>
													<span class="tag tag-rounded">Third tag</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Color tag</h3>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example border">
												<div class="tags">
													<span class="tag tag-blue">Blue tag</span>
													<span class="tag tag-azure">Azure tag</span>
													<span class="tag tag-indigo">Indigo tag</span>
													<span class="tag tag-purple">Purple tag</span>
													<span class="tag tag-pink">Pink tag</span>
													<span class="tag tag-red">Red tag</span>
													<span class="tag tag-orange">Orange tag</span>
													<span class="tag tag-yellow">Yellow tag</span>
													<span class="tag tag-lime">Lime tag</span>
													<span class="tag tag-green">Green tag</span>
													<span class="tag tag-teal">Teal tag</span>
													<span class="tag tag-cyan">Cyan tag</span>
													<span class="tag tag-gray">Gray tag</span>
													<span class="tag tag-gray-dark">Dark gray tag</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">List of tags</h3>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<p>You can create a list of tags with the <code class="highlighter-rouge">.tags</code> container.</p>
											<div class="example border border-bottom-0">
												<div class="tags">
													<span class="tag">First tag</span>
													<span class="tag">Second tag</span>
													<span class="tag">Third tag</span>
												</div>
											</div>
											<p class="mt-4">If the list is very long, it will automatically wrap on multiple lines, while keeping all tags evenly spaced.</p>
											<div class="example">
												<div class="tags">
													<span class="tag">One</span>
													<span class="tag">Two</span>
													<span class="tag">Three</span>
													<span class="tag">Four</span>
													<span class="tag">Five</span>
													<span class="tag">Six</span>
													<span class="tag">Seven</span>
													<span class="tag">Eight</span>
													<span class="tag">Nine</span>
													<span class="tag">Ten</span>
													<span class="tag">Eleven</span>
													<span class="tag">Twelve</span>
													<span class="tag">Thirteen</span>
													<span class="tag">Fourteen</span>
													<span class="tag">Fifteen</span>
													<span class="tag">Sixteen</span>
													<span class="tag">Seventeen</span>
													<span class="tag">Eighteen</span>
													<span class="tag">Nineteen</span>
													<span class="tag">Twenty</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Avatar tag</h3>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example border border-bottom-0">
												<div class="tags">
													<span class="tag">
														<span class=""><img src="{{URL::asset('assets/images/users/female/16.jpg')}}" class="tag-avatar avatar" alt="img"></span>
														Victoria
													</span>
													<span class="tag">
														<span class=""><img src="{{URL::asset('assets/images/users/male/41.jpg')}}" class="tag-avatar avatar" alt="img"></span>
														Nathan
													</span>
													<span class="tag">
														<span class=""><img src="{{URL::asset('assets/images/users/female/1.jpg')}}" class="tag-avatar avatar" alt="img"></span>
														Alice
													</span>
													<span class="tag">
														<span class=""><img src="{{URL::asset('assets/images/users/female/18.jpg')}}" class="tag-avatar avatar" alt="img"></span>
														Rose
													</span>
													<span class="tag">
														<span class=""><img src="{{URL::asset('assets/images/users/male/16.jpg')}}" class="tag-avatar avatar" alt="img"></span>
														Peter
													</span>
													<span class="tag">
														<span class=""><img src="{{URL::asset('assets/images/users/male/26.jpg')}}" class="tag-avatar avatar" alt="img"></span>
														Wayne
													</span>
													<span class="tag">
														<span class=""><img src="{{URL::asset('assets/images/users/female/7.jpg')}}" class="tag-avatar avatar" alt="img"></span>
														Michelle
													</span>
													<span class="tag">
														<span class=""><img src="{{URL::asset('assets/images/users/female/17.jpg')}}" class="tag-avatar avatar" alt="img"></span>
														Debra
													</span>
												</div>
											</div>
											<div class="highlight">
                        <pre>Example: <code class="language-html" data-lang="html"><span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"tag"</span><span class="nt">&gt;</span>
			<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"tag-avatar avatar"</span> <span class="na">style=</span><span class="s">"background-image: url({{URL::asset('assets/images/users/female/16.jpg')}})"</span><span class="nt">&gt;&lt;/span&gt;</span>
			Victoria
		<span class="nt">&lt;/span&gt;</span></code></pre>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Tag remove</h3>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example border border-bottom-0">
												<div class="tags">
													<span class="tag">
														One
														<a href="javascript:void(0)" class="tag-addon"><i class="fe fe-x"></i></a>
													</span>
													<span class="tag">
														Two
														<a href="javascript:void(0)" class="tag-addon"><i class="fe fe-x"></i></a>
													</span>
													<span class="tag">
														Three
														<a href="javascript:void(0)" class="tag-addon"><i class="fe fe-x"></i></a>
													</span>
													<span class="tag">
														Four
														<a href="javascript:void(0)" class="tag-addon"><i class="fe fe-x"></i></a>
													</span>
												</div>
											</div>
											<div class="highlight">
                        <pre>Example: <code class="language-html" data-lang="html"><span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">"tags"</span><span class="nt">&gt;</span>
			<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">"tag"</span><span class="nt">&gt;</span>
				One
				<span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">"#"</span> <span class="na">class=</span><span class="s">"tag-addon"</span><span class="nt">&gt;&lt;i</span> <span class="na">class=</span><span class="s">"fe fe-x"</span><span class="nt">&gt;&lt;/i&gt;&lt;/a&gt;</span>
			<span class="nt">&lt;/span&gt;</span>
		<span class="nt">&lt;/div&gt;</span></code></pre>
											</div>
										</div>
									</div>
								</div>
							</div><!-- col end -->
							<div class="col-lg-12">
								<div class="card">
									<div class="card-header pb-0">
										<h3 class="card-title">Tag addons</h3>
									</div>
									<div class="card-body">
										<div class="text-wrap">
											<div class="example border border-bottom-0">
												<div class="tags">
													<div class="tag">
														npm
														<a href="#" class="tag-addon"><i class="fe fe-x"></i></a>
													</div>
													<div class="tag tag-danger">
														npm
														<span class="tag-addon"><i class="fe fe-activity"></i></span>
													</div>
													<div class="tag">
														bundle
														<span class="tag-addon tag-success">passing</span>
													</div>
													<span class="tag tag-dark">
														CSS gzip size
														<span class="tag-addon tag-warning">20.9 kB</span>
													</span>
												</div>
											</div>
											<div class="highlight">
                        <pre>Example:<code class="language-html" data-lang="html"><span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">"tag"</span><span class="nt">&gt;</span>
			npm
			<span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">"#"</span> <span class="na">class=</span><span class="s">"tag-addon"</span><span class="nt">&gt;&lt;i</span> <span class="na">class=</span><span class="s">"fe fe-x"</span><span class="nt">&gt;&lt;/i&gt;&lt;/a&gt;</span>
		<span class="nt">&lt;/div&gt;</span>
		</code></pre>
											</div>
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