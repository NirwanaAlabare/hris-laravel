@extends('layouts.app')

@section('styles')

	<!--- Internal Treeview -->
    <link href="{{URL::asset('assets/plugins/treeview/treeview.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

						<!-- page-header -->
						<div class="page-header">
							<ol class="breadcrumb"><!-- breadcrumb -->
								<li class="breadcrumb-item"><a href="#">Apps</a></li>
								<li class="breadcrumb-item active" aria-current="page">Treeview</li>
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
								<div class="card mg-b-20">
									<div class="card-body">
										<div class="main-content-label mg-b-5">
											Basic Treeview
										</div>
										<p class="mg-b-20">It is Very Easy to Customize and it uses in website apllication.</p>
										<div class="row">
											<!-- col -->
											<div class="col-lg-4">
												<ul id="treeview1">
													<li><a href="#">TECH</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li>XRP
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
												</ul>
											</div>
											<!-- /col -->

											<!-- col -->
											<div class="col-lg-4 mt-4 mt-lg-0">
												<ul id="treeview2">
													<li><a href="#">TECH</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li>XRP
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
												</ul>
											</div>
											<!-- /col -->

											<!-- col -->
											<div class="col-lg-4 mt-4 mt-lg-0">
												<ul id="treeview3">
													<li><a href="#">TECH</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li>XRP
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
												</ul>
											</div>
											<!-- /col -->
										</div>
									</div>
								</div>
								<div class="card">
									<div class="card-body">
										<div class="main-content-label mg-b-5">
											Tree View Styles
										</div>
										<p class="mg-b-20">It is Very Easy to Customize and it uses in website apllication.</p>
										<div class="row">
											<!-- col -->
											<div class="col-lg-4">
												<ul id="tree1">
													<li><a href="#">Treeview1</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview2</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview3</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview4</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview5</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview6</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
												</ul>
											</div>
											<!-- /col -->

											<!-- col -->
											<div class="col-lg-4 mt-4 mt-lg-0">
												<ul id="tree2">
													<li><a href="#">Treeview1</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview2</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview3</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview4</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview5</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview6</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
												</ul>
											</div>
											<!-- /col -->

											<!-- col -->
											<div class="col-lg-4 mt-4 mt-lg-0">
												<ul id="tree3">
													<li><a href="#">Treeview1</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview2</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview3</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview4</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview5</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.
																				<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
													<li><a href="#">Treeview6</a>
														<ul>
															<li>Company Maintenance</li>
															<li>Employees
																<ul>
																	<li>Reports
																		<ul>
																			<li>Report1</li>
																			<li>Report2</li>
																			<li>Report3</li>
																		</ul>
																	</li>
																	<li>Employee Maint.
																		<ul>
																			<li>Reports
																				<ul>
																					<li>Report1</li>
																					<li>Report2</li>
																					<li>Report3</li>
																				</ul>
																			</li>
																			<li>Employee Maint.<ul>
																					<li>Reports
																						<ul>
																							<li>Report1</li>
																							<li>Report2</li>
																							<li>Report3</li>
																						</ul>
																					</li>
																					<li>Employee Maint.</li>
																				</ul>
																			</li>
																		</ul>
																	</li>
																</ul>
															</li>
															<li>Human Resources</li>
														</ul>
													</li>
												</ul>
											</div>
											<!-- /col -->
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- /row -->

@endsection('content')

@section('scripts')

		<!--Jquery Sparkline js-->
		<script src="{{URL::asset('assets/plugins/vendors/jquery.sparkline.min.js')}}"></script>

		<!-- Chart Circle js-->
		<script src="{{URL::asset('assets/plugins/vendors/circle-progress.min.js')}}"></script>
		
        <!--Time Counter js-->
		<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>
		
        <!--- Internal Treeview js -->
		<script src="{{URL::asset('assets/plugins/treeview/treeview.js')}}"></script>

@endsection