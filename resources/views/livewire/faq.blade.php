@extends('layouts.app')

@section('styles')

@endsection

@section('content')

					    <!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Pages</a></li>
								<li class="breadcrumb-item active" aria-current="page">Faq</li>
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
							<div class="col-md-12">
								<div class="card">
									<div class="card-header pb-0">
										<h4 class="card-title">FAQS</h4>
									</div>
									<div class="card-body">
										<div class="acc-1 js-accordion  ">
											<div class="accordion__item js-accordion-item active">
												<div class="accordion-header js-accordion-header">1.How To Insert All The Plugins?</div>
												<div class="accordion-body js-accordion-body">
													<div class="accordion-body__contents">
														But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure.
													</div>
												</div>
											</div>
											<div class="accordion__item js-accordion-item">
												<div class="accordion-header js-accordion-header">2. Can I use this Plugins in Another Template?</div>
												<div class="accordion-body js-accordion-body">
													<div class="accordion-body__contents">
														These cases are perfectly simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when nothing prevents our being able to do what we like best, every pleasure is to be welcomed and every pain avoided. But in certain circumstances and owing to the claims of duty or the obligations of business it will frequently occur that pleasures have to be repudiated and annoyances accepted. The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains
													</div>
												</div>
											</div>
											<div class="accordion__item js-accordion-item">
												<div class="accordion-header js-accordion-header">3. It is Easy to Customizable?</div>
												<div class="accordion-body js-accordion-body">
													<div class="accordion-body__contents">
														But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure.
													</div>
													<div class="accordion js-accordion">
														<div class="accordion__item js-accordion-item">
															<div class="accordion-header js-accordion-header">At vero eos et accusamus et iusto odio dignissimos?</div>
															<div class="accordion-body js-accordion-body">
																<div class="accordion-body__contents">
																	Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
																</div>
															</div>
														</div>
														<div class="accordion__item js-accordion-item">
															<div class="accordion-header js-accordion-header">Nam libero tempore, cum soluta nobis est eligendi?</div>
															<div class="accordion-body js-accordion-body">
																<div class="accordion-body__contents">
																	Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="accordion__item js-accordion-item">
												<div class="accordion-header js-accordion-header">4. How Can I contact?</div>
												<div class="accordion-body js-accordion-body">
													<div class="accordion-body__contents">
														On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain.
													</div>
												</div>
											</div>
											<div class="accordion__item js-accordion-item">
												<div class="accordion-header js-accordion-header">5. How Can I Add another page in Template?</div>
												<div class="accordion-body js-accordion-body">
													<div class="accordion-body__contents">
														Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.
													</div>
												</div>
											</div>
											<div class="accordion__item js-accordion-item">
												<div class="accordion-header js-accordion-header">6. How can I download This template?</div>
												<div class="accordion-body js-accordion-body">
													<div class="accordion-body__contents">
														Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.
													</div>
												</div>
											</div>
											<div class="accordion__item js-accordion-item">
												<div class="accordion-header js-accordion-header">7. Who Can Use the Web Protal?</div>
												<div class="accordion-body js-accordion-body">
													<div class="accordion-body__contents">
														There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.
													</div>
												</div>
											</div>
											<div class="accordion__item js-accordion-item">
												<div class="accordion-header js-accordion-header">8. How to Login?</div>
												<div class="accordion-body js-accordion-body">
													<div class="accordion-body__contents">
														It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer">
										<a class="btn btn-secondary mt-2 mb-2" href="#">Send me your Questions?</a>
										<a class="btn btn-info mt-2 mb-2" href="#">view More</a>
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

		<!-- Accordion js-->
		<script src="{{URL::asset('assets/js/accordiation.js')}}"></script>

@endsection