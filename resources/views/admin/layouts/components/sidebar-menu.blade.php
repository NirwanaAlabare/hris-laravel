				<!-- Sidebar menu-->
				<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
				<aside class="app-sidebar toggle-sidebar">
					<div class="app-sidebar__user">
						<div class="user-body">
							<img src="{{URL::asset('assets/images/users/avatars/2.png')}}" alt="profile-user" class="rounded-circle w-25">
						</div>
						<div class="user-info">
							<a href="#" class=""><span class="app-sidebar__user-name font-weight-semibold">Alison</span><br>
								<span class="text-muted app-sidebar__user-designation text-sm"> Web Designer</span>
							</a>
						</div>
					</div>
					<ul class="side-menu toggle-menu">
						<li class="slide">
							<a class="side-menu__item"  data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-device-desktop"></i><span class="side-menu__label active">Home</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
								<li><a class="slide-item"  href="{{url('index')}}"><span> Dashboard 01</span></a></li>
								<li><a class="slide-item" href="{{url('index2')}}"><span> Dashboard 02</span></a></li>
								<li><a class="slide-item" href="{{url('index3')}}"><span> Dashboard 03</span></a></li>
								<li><a class="slide-item" href="{{url('index4')}}"><span> Dashboard 04</span></a></li>
								<li><a class="slide-item" href="{{url('index5')}}"><span> Dashboard 05</span></a></li>
							</ul>
						</li>

						<li class="slide">
							<a class="side-menu__item"  data-toggle="slide" href="#"><i class="side-menu__icon ti-layout"></i><span class="side-menu__label">Layouts</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
								<li class="sub-slide">
									<a href="#" data-toggle="sub-slide" class="sub-slide-item"><span class="sub-side-menu__label">Horizontal Versions</span><i class="sub-angle fa fa-angle-right"></i></a>
									<ul class="sub-slide-menu">
										<li><a class="sub-slide-item"  href="{{url('horizontal')}}"><span> Horizontal</span></a></li>
										<li><a class="sub-slide-item" href="{{url('horizontal-boxed')}}"><span>Boxed</span></a></li>
										<li><a class="sub-slide-item" href="{{url('horizontal-centerlogo')}}"><span>CenterLogo</span></a></li>
										<li><a class="sub-slide-item" href="{{url('horizontal-centerlogo-boxed')}}"><span>CenterLogo-Boxed</span></a></li>
									</ul>
								</li>
								<li class="sub-slide">
									<a href="#" data-toggle="sub-slide" class="sub-slide-item"><span class="sub-side-menu__label">Vertical Versions</span><i class="sub-angle fa fa-angle-right"></i></a>
									<ul class="sub-slide-menu">
										<li class="sub-slide2">
											<a href="#" data-toggle="sub-slide2" class="sub-slide-item2"><span class="sub-side-menu__label">Vertical-Menu</span><i class="sub-angle2 fa fa-angle-right"></i> </a>
											<ul class="sub-slide-menu2">
												<li><a  class="sub-slide-item2 " href="{{url('verticalmenu-icon')}}">Vertical</a></li>
												<li><a  class="sub-slide-item2 " href="{{url('verticalmenu-icon-boxed')}}">Boxed</a></li>
											</ul>
										</li>
										<li class="sub-slide2">
											<a href="#" data-toggle="sub-slide2" class="sub-slide-item2"><span class="sub-side-menu__label">Closed-Sidebar</span><i class="sub-angle2 fa fa-angle-right"></i> </a>
											<ul class="sub-slide-menu2">
												<li><a class="sub-slide-item2" href="{{url('verticalmenu-closed-sidebar')}}" >Closed</a></li>
												<li><a  class="sub-slide-item2" href="{{url('verticalmenu-closed-sidebar-boxed')}}">Closed-Boxed</a></li>
											</ul>
										</li>
										<li class="sub-slide2">
											<a href="#" data-toggle="sub-slide2" class="sub-slide-item2"><span class="sub-side-menu__label">Toggle-sidebar</span><i class="sub-angle2 fa fa-angle-right"></i> </a>
											<ul class="sub-slide-menu2">
												<li><a  class="sub-slide-item2" href="{{url('vertical-toggle')}}">Toggle</a></li>
												<li><a  class="sub-slide-item2" href="{{url('vertical-toggle-boxed')}}">Toggle-Boxed</a></li>
											</ul>
										</li>
										<li class="sub-slide2">
											<a href="#" data-toggle="sub-slide2" class="sub-slide-item2"><span class="sub-side-menu__label">Combined-Menu</span><i class="sub-angle2 fa fa-angle-right"></i> </a>
											<ul class="sub-slide-menu2">
												<li><a  class="sub-slide-item2" href="{{url('vertical-horizontal-combined')}}">Combined</a></li>
												<li><a  class="sub-slide-item2" href="{{url('vertical-horizontal-combined-boxed')}}">Combined-Boxed</a></li>
											</ul>
										</li>
									</ul>
								</li>
							</ul>
						</li>


						<li class="slide">
							<a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-th-large-outline"></i><span class="side-menu__label">Apps</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
								<li><a href="{{url('cards')}}" class="slide-item"> Cards design</a></li>
								<li><a href="{{url('cards-image')}}" class="slide-item"> Image Cards design</a></li>
								<li><a href="{{url('chat')}}" class="slide-item"> Default Chat</a></li>
								<li><a href="{{url('notify')}}" class="slide-item"> Notifications</a></li>
								<li><a href="{{url('sweetalert')}}" class="slide-item"> Sweet alerts</a></li>
								<li><a href="{{url('rangeslider')}}" class="slide-item"> Range slider</a></li>
								<li><a href="{{url('scroll')}}" class="slide-item"> Content Scroll bar</a></li>
								<li><a href="{{url('counters')}}" class="slide-item"> Counters</a></li>
								<li><a href="{{url('loaders')}}" class="slide-item"> Loaders</a></li>
								<li><a href="{{url('time-line')}}" class="slide-item"> Time Line</a></li>
								<li><a href="{{url('rating')}}" class="slide-item"> Rating</a></li>
							</ul>
						</li>
						<li>
							<a class="side-menu__item" href="{{url('widgets')}}"><i class="side-menu__icon typcn typcn-arrow-move-outline"></i><span class="side-menu__label">Widgets</span></a>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-chart-pie-outline"></i><span class="side-menu__label">Charts</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
								<li><a href="{{url('chart-chartist')}}" class="slide-item"> Chartist Charts</a></li>
								<li><a href="{{url('chart-morris')}}" class="slide-item"> Morris Charts</a></li>
								<li><a href="{{url('chart-js')}}" class="slide-item"> Charts js</a></li>
								<li><a href="{{url('chart-peity')}}" class="slide-item"> Pie Charts</a></li>
								<li><a href="{{url('chart-echart')}}" class="slide-item"> Echart Charts</a></li>
								<li><a href="{{url('chart-flot')}}" class="slide-item"> Flot Charts</a></li>
								<li><a href="{{url('chart-nvd3')}}" class="slide-item"> Nvd3 Charts</a></li>
								<li><a href="{{url('chart-dygraph')}}" class="slide-item"> Dygraph Charts</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-spanner-outline"></i><span class="side-menu__label">Elements</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
								<li><a href="{{url('alerts')}}" class="slide-item"> Alerts</a></li>
								<li><a href="{{url('buttons')}}" class="slide-item"> Buttons</a></li>
								<li><a href="{{url('colors')}}" class="slide-item"> Colors</a></li>
								<li><a href="{{url('avatars')}}" class="slide-item"> Avatars</a></li>
								<li><a href="{{url('dropdown')}}" class="slide-item"> Drop downs</a></li>
								<li><a href="{{url('thumbnails')}}" class="slide-item"> Thumbnails</a></li>
								<li><a href="{{url('mediaobject')}}" class="slide-item"> Media Object</a></li>
								<li><a href="{{url('list-group')}}" class="slide-item"> List</a></li>
								<li><a href="{{url('tags')}}" class="slide-item">Tags</a></li>
								<li><a href="{{url('pagination')}}" class="slide-item"> Pagination</a></li>
								<li><a href="{{url('navigation')}}" class="slide-item"> Navigation</a></li>
								<li><a href="{{url('typography')}}" class="slide-item"> Typography</a></li>
								<li><a href="{{url('breadcrumbs')}}" class="slide-item"> Breadcrumbs</a></li>
								<li><a href="{{url('badge')}}" class="slide-item"> Badges</a></li>
								<li><a href="{{url('jumbotron')}}" class="slide-item"> Jumbotron</a></li>
								<li><a href="{{url('panels')}}" class="slide-item"> Panels</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-briefcase"></i><span class="side-menu__label">Advanced UI</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
								<li><a href="{{url('modal')}}" class="slide-item"> Modal</a></li>
								<li><a href="{{url('tooltipandpopover')}}" class="slide-item"> Tooltip and popover</a></li>
								<li><a href="{{url('progress')}}" class="slide-item"> Progress</a></li>
								<li><a href="{{url('carousel')}}" class="slide-item"> Carousels</a></li>
								<li><a href="{{url('accordion')}}" class="slide-item"> Accordions</a></li>
								<li><a href="{{url('tabs')}}" class="slide-item"> Tabs</a></li>
								<li><a href="{{url('treeview')}}" class="slide-item">Treeview</a></li>
								<li><a href="{{url('headers')}}" class="slide-item"> Headers</a></li>
								<li><a href="{{url('footers')}}" class="slide-item"> Footers</a></li>
								<li><a href="{{url('crypto-currencies')}}" class="slide-item">Crypto-currencies</a></li>
								<li><a href="{{url('users-list')}}" class="slide-item"> User List</a></li>
								<li><a href="{{url('search')}}" class="slide-item"> Search page</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-document"></i><span class="side-menu__label"> Forms</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
								<li><a href="{{url('form-elements')}}" class="slide-item"> Form Elements</a></li>
								<li><a href="{{url('form-wizard')}}" class="slide-item"> Form-wizard</a></li>
								<li><a href="{{url('wysiwyag')}}" class="slide-item"> Text Editor</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-point-of-interest-outline"></i><span class="side-menu__label">Icons</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
								<li><a href="{{url('icons')}}" class="slide-item"> Font Awesome</a></li>
								<li><a href="{{url('icons2')}}" class="slide-item"> Material Design Icons</a></li>
								<li><a href="{{url('icons3')}}" class="slide-item"> Simple Line Iocns</a></li>
								<li><a href="{{url('icons4')}}" class="slide-item"> Feather Icons</a></li>
								<li><a href="{{url('icons5')}}" class="slide-item"> Ionic Icons</a></li>
								<li><a href="{{url('icons6')}}" class="slide-item"> Flag Icons</a></li>
								<li><a href="{{url('icons7')}}" class="slide-item"> pe7 Icons</a></li>
								<li><a href="{{url('icons8')}}" class="slide-item"> Themify Icons</a></li>
								<li><a href="{{url('icons9')}}" class="slide-item"> Typicons Icons</a></li>
								<li><a href="{{url('icons10')}}" class="slide-item">Weather Icons</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-calendar-outline"></i><span class="side-menu__label">Calendar</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
									<li><a href="{{url('calendar')}}" class="slide-item"> Default calendar</a></li>
									<li><a href="{{url('calendar2')}}" class="slide-item"> Full calendar</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-clipboard"></i><span class="side-menu__label">Tables</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
									<li><a href="{{url('tables')}}" class="slide-item">Default table</a></li>
									<li><a href="{{url('datatable')}}" class="slide-item">Data tables</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-cog-outline"></i><span class="side-menu__label">Pages</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
								<li><a href="{{url('profile')}}" class="slide-item"> Profile</a></li>
								<li><a href="{{url('editprofile')}}" class="slide-item"> Edit Profile</a></li>
								<li><a href="{{url('email')}}" class="slide-item"> Email</a></li>
								<li><a href="{{url('emailservices')}}" class="slide-item">Email Inbox</a></li>
								<li><a href="{{url('gallery')}}" class="slide-item"> Gallery</a></li>
								<li><a href="{{url('about')}}" class="slide-item"> About Company</a></li>
								<li><a href="{{url('services')}}" class="slide-item"> Services</a></li>
								<li><a href="{{url('faq')}}" class="slide-item"> FAQs</a></li>
								<li><a href="{{url('terms')}}" class="slide-item"> Terms and Conditions</a></li>
								<li><a href="{{url('emptypage')}}" class="slide-item"> Empty Page</a></li>
								<li><a href="{{url('construction')}}" class="slide-item">Under Construction</a></li>
								<li><a href="{{url('blog')}}" class="slide-item"> Blog</a></li>
								<li><a href="{{url('invoice')}}" class="slide-item">Invoice</a></li>
								<li><a href="{{url('pricing')}}" class="slide-item">Pricing Tables</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-shopping-cart"></i><span class="side-menu__label">E-Commerce</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
								<li><a href="{{url('shop')}}" class="slide-item"> Products</a></li>
								<li><a href="{{url('shop-des')}}" class="slide-item">Product Details</a></li>
								<li><a href="{{url('cart')}}" class="slide-item">Shopping Cart</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-download-outline"></i><span class="side-menu__label">Custom</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
								<li><a href="{{url('login')}}" class="slide-item"> Login</a></li>
								<li><a href="{{url('register')}}" class="slide-item"> Register</a></li>
								<li><a href="{{url('forgot-password')}}" class="slide-item"> Forgot Password</a></li>
								<li><a href="{{url('lockscreen')}}" class="slide-item"> Lock screen</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-info-outline"></i><span class="side-menu__label">Errors</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
								<li><a href="{{url('error400')}}" class="slide-item">400 Error</a></li>
								<li><a href="{{url('error401')}}" class="slide-item">401 Error</a></li>
								<li><a href="{{url('error403')}}" class="slide-item">403 Error</a></li>
								<li><a href="{{url('error404')}}" class="slide-item">404 Error</a></li>
								<li><a href="{{url('error500')}}" class="slide-item">500 Error</a></li>
								<li><a href="{{url('error503')}}" class="slide-item">503 Error</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon bx bx-minus-front typcn-info-outline"></i><span class="side-menu__label">Submenus</span><i class="angle fa fa-angle-right"></i></a>
							<ul class="slide-menu">
								<li><a href="#" class="slide-item">Submenu 1</a></li>
								<li class="sub-slide">
									<a href="#" data-toggle="sub-slide" class="sub-slide-item"><span class="sub-side-menu__label">Submenu 2</span><i class="sub-angle fa fa-angle-right"></i></a>
										<ul class="sub-slide-menu">
											<li><a href="#" class="sub-slide-item">Submenu 2.1</a></li>
											<li class="sub-slide2">
												<a href="#" data-toggle="sub-slide2" class="sub-slide-item2"><span class="sub-side-menu__label2"> Submenu 2.2</span><i class="sub-angle2 fa fa-angle-right"></i> </a>
												<ul class="sub-slide-menu2">
													<li><a href="#" class="sub-slide-item2">Submenu 2.2.1</a></li>
													<li><a href="#" class="sub-slide-item2">Submenu 2.2.2</a></li>
													<li><a href="#" class="sub-slide-item2">Submenu 2.2.3</a></li>
												</ul>
											</li>
											<li><a href="#" class="sub-slide-item">Submenu 2.3</a></li>
										</ul>
									</li>
								<li><a href="#" class="slide-item">Submenu 3</a></li>
							</ul>
						</li>
					</ul>
				</aside>
				<!--sidemenu end-->
