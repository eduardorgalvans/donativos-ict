<html lang="en"><head>
	<meta charset="utf-8">
	<title>Color Admin | Page Blank</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
	<meta content="" name="description">
	<meta content="" name="author">
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="{{ asset( '/assets/t511/css/vendor.min.css' ) }}" rel="stylesheet">
	<link href="{{ asset( '/assets/t511/css/facebook/app.min.css' ) }}" rel="stylesheet">
	<!-- ================== END core-css ================== -->
	@livewireStyles
</head>
	<body class="pace-done theme-red">
		<div class="pace pace-inactive">
			<div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
				<div class="pace-progress-inner"></div>
			</div>
			<div class="pace-activity"></div>
		</div>
		<!-- BEGIN #loader -->
		<div id="loader" class="app-loader loaded">
			<span class="spinner"></span>
		</div>
		<!-- END #loader -->
		<!-- BEGIN #app -->
		<div id="app" class="app app-header-fixed app-sidebar-fixed app-sidebar-minified">
			<!-- BEGIN #header -->
			<div id="header" class="app-header bg-white app-header-inverse">
				<!-- BEGIN navbar-header -->
				<div class="navbar-header">
					<a href="/" class="navbar-brand text-black-900"><img src="{{ asset('/assets/img/ict-32x35.png') }}" > <b>Intra</b> ICT <small>Admin</small></a>
					<button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<!-- END navbar-header -->
				<!-- BEGIN header-nav -->
				<div class="navbar-nav">
					{{--
					<div class="navbar-item navbar-form">
						<form action="" method="POST" name="search">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Enter keyword">
								<button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
							</div>
						</form>
					</div>
					--}}
					{{ Libreria::GetNotificaciones() }}
					{{--
					<div class="navbar-item dropdown">
						<a href="#" data-bs-toggle="dropdown" class="navbar-link text-black-900 dropdown-toggle icon">
							<i class="fa fa-bell"></i>
							<span class="badge">5</span>
						</a>
						<div class="dropdown-menu media-list dropdown-menu-end">
							<div class="dropdown-header">NOTIFICATIONS (5)</div>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<i class="fa fa-bug media-object bg-gray-400"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading">Server Error Reports <i class="fa fa-exclamation-circle text-danger"></i></h6>
									<div class="text-muted fs-10px">3 minutes ago</div>
								</div>
							</a>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<img src="../assets/t511/img/user/user-1.jpg" class="media-object" alt="">
									<i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading">John Smith</h6>
									<p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
									<div class="text-muted fs-10px">25 minutes ago</div>
								</div>
							</a>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<img src="../assets/t511/img/user/user-2.jpg" class="media-object" alt="">
									<i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading">Olivia</h6>
									<p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
									<div class="text-muted fs-10px">35 minutes ago</div>
								</div>
							</a>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<i class="fa fa-plus media-object bg-gray-400"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading"> New User Registered</h6>
									<div class="text-muted fs-10px">1 hour ago</div>
								</div>
							</a>
							<a href="javascript:;" class="dropdown-item media">
								<div class="media-left">
									<i class="fa fa-envelope media-object bg-gray-400"></i>
									<i class="fab fa-google text-warning media-object-icon fs-14px"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading"> New Email From John</h6>
									<div class="text-muted fs-10px">2 hour ago</div>
								</div>
							</a>
							<div class="dropdown-footer text-center">
								<a href="javascript:;" class="text-decoration-none">View more</a>
							</div>
						</div>
					</div>
					--}}
					<div class="navbar-item navbar-user dropdown">
						<a href="#" class="navbar-link dropdown-toggle d-flex align-items-center text-black-900" data-bs-toggle="dropdown">
							<img src="../assets/t511/img/user/user-13.jpg" alt=""> 
							<span class="d-none d-md-inline">Adam Schwartz</span> <b class="caret ms-6px"></b>
						</a>
						<div class="dropdown-menu dropdown-menu-end me-1">
							<a href="javascript:;" class="dropdown-item">Edit Profile</a>
							<a href="javascript:;" class="dropdown-item d-flex align-items-center">
								Inbox
								<span class="badge bg-danger rounded-pill ms-auto pb-4px">2</span> 
							</a>
							<a href="javascript:;" class="dropdown-item">Calendar</a>
							<a href="javascript:;" class="dropdown-item">Setting</a>
							<div class="dropdown-divider"></div>
							<a href="javascript:;" class="dropdown-item">Log Out</a>
						</div>
					</div>
				</div>
				<!-- END header-nav -->
			</div>
			<!-- END #header -->
			<!-- BEGIN #sidebar -->
			<div id="sidebar" class="app-sidebar">
				<!-- BEGIN scrollbar -->
				<div class="app-sidebar-content ps ps--active-y" data-scrollbar="true" data-height="100%" data-init="true" style="height: 100%;">
					<!-- BEGIN menu -->
					<div class="menu">
						<div class="menu-profile">
							<a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile" data-target="#appSidebarProfileMenu">
								<div class="menu-profile-cover with-shadow"></div>
								<div class="menu-profile-image">
									<img src="../assets/t511/img/user/user-13.jpg" alt="">
								</div>
								<div class="menu-profile-info">
									<div class="d-flex align-items-center">
										<div class="flex-grow-1">
											Sean Ngu
										</div>
										<div class="menu-caret ms-auto"></div>
									</div>
									<small>Front end developer</small>
								</div>
							</a>
						</div>
						<div id="appSidebarProfileMenu" class="collapse">
							<div class="menu-item pt-5px">
								<a href="javascript:;" class="menu-link">
									<div class="menu-icon"><i class="fa fa-cog"></i></div>
									<div class="menu-text">Settings</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="javascript:;" class="menu-link">
									<div class="menu-icon"><i class="fa fa-pencil-alt"></i></div>
									<div class="menu-text"> Send Feedback</div>
								</a>
							</div>
							<div class="menu-item pb-5px">
								<a href="javascript:;" class="menu-link">
									<div class="menu-icon"><i class="fa fa-question-circle"></i></div>
									<div class="menu-text"> Helps</div>
								</a>
							</div>
							<div class="menu-divider m-0"></div>
						</div>
						<div class="menu-header">Navegación</div>
						@include('layouts.menu') {{-- layouts/menu --}}							
						<!-- BEGIN minify-button -->
						<div class="menu-item d-flex">
							<a href="javascript:;" class="app-sidebar-minify-btn ms-auto" data-toggle="app-sidebar-minify"><i class="fa fa-angle-double-left"></i></a>
						</div>
						<!-- END minify-button -->
					</div>
					<!-- END menu -->
				<div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 718px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 618px;"></div></div></div>
				<!-- END scrollbar -->
			</div>
			<div class="app-sidebar-bg"></div>
			<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
			<!-- END #sidebar -->

			<!-- BEGIN #content -->
			<div id="content" class="app-content">
				<!-- BEGIN breadcrumb -->
				<ol class="breadcrumb">
					@yield('breadcrumb')
				</ol>
				<!-- END breadcrumb -->
				<!-- BEGIN page-header -->
				<h1 class="page-header">@yield('titulo-pagina')</h1>
				<!-- END page-header -->
				@yield('contenido')
			</div>
			<!-- END #content -->

			{{--
			<!-- BEGIN theme-panel -->
			<div class="theme-panel">
				<a href="javascript:;" data-toggle="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-cog"></i></a>
				<div class="theme-panel-content ps ps--active-y" data-scrollbar="true" data-height="100%" data-init="true" style="height: 100%;">
					<h5>App Settings</h5>
					
					<!-- BEGIN theme-list -->
					<div class="theme-list">
						<div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-red" data-theme-class="theme-red" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Red" data-bs-original-title="" title="">&nbsp;</a></div>
						<div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-pink" data-theme-class="theme-pink" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Pink" data-bs-original-title="" title="">&nbsp;</a></div>
						<div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-orange" data-theme-class="theme-orange" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Orange" data-bs-original-title="" title="">&nbsp;</a></div>
						<div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-yellow" data-theme-class="theme-yellow" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Yellow" data-bs-original-title="" title="">&nbsp;</a></div>
						<div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-lime" data-theme-class="theme-lime" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Lime" data-bs-original-title="" title="">&nbsp;</a></div>
						<div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-green" data-theme-class="theme-green" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Green" data-bs-original-title="" title="">&nbsp;</a></div>
						<div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-teal" data-theme-class="theme-teal" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Teal" data-bs-original-title="" title="">&nbsp;</a></div>
						<div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-cyan" data-theme-class="theme-cyan" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Cyan" data-bs-original-title="" title="">&nbsp;</a></div>
						<div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-blue" data-theme-class="theme-blue" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Blue" data-bs-original-title="" title="">&nbsp;</a></div>
						<div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-purple" data-theme-class="theme-purple" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Purple" data-bs-original-title="" title="">&nbsp;</a></div>
						<div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-indigo" data-theme-class="theme-indigo" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Indigo" data-bs-original-title="" title="">&nbsp;</a></div>
						<div class="theme-list-item"><a href="javascript:;" class="theme-list-link bg-black" data-theme-class="theme-gray-600" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Black" data-bs-original-title="" title="">&nbsp;</a></div>
					</div>
					<!-- END theme-list -->
					<div class="theme-panel-divider"></div>
					<div class="row mt-10px">
						<div class="col-8 control-label text-dark fw-bold">
							<div>Dark Mode <span class="badge bg-primary ms-1 py-2px position-relative" style="top: -1px;">NEW</span></div>
							<div class="lh-14">
								<small class="text-dark opacity-50">
									Adjust the appearance to reduce glare and give your eyes a break.
								</small>
							</div>
						</div>
						<div class="col-4 d-flex">
							<div class="form-check form-switch ms-auto mb-0">
								<input type="checkbox" class="form-check-input" name="app-theme-dark-mode" id="appThemeDarkMode" value="1">
								<label class="form-check-label" for="appThemeDarkMode">&nbsp;</label>
							</div>
						</div>
					</div>
					<div class="theme-panel-divider"></div>
					<!-- BEGIN theme-switch -->
					<div class="row mt-10px align-items-center">
						<div class="col-8 control-label text-dark fw-bold">Header Fixed</div>
						<div class="col-4 d-flex">
							<div class="form-check form-switch ms-auto mb-0">
								<input type="checkbox" class="form-check-input" name="app-header-fixed" id="appHeaderFixed" value="1" checked="">
								<label class="form-check-label" for="appHeaderFixed">&nbsp;</label>
							</div>
						</div>
					</div>
					<div class="row mt-10px align-items-center">
						<div class="col-8 control-label text-dark fw-bold">Header Inverse</div>
						<div class="col-4 d-flex">
							<div class="form-check form-switch ms-auto mb-0">
								<input type="checkbox" class="form-check-input" name="app-header-inverse" id="appHeaderInverse" value="1" checked="">
								<label class="form-check-label" for="appHeaderInverse">&nbsp;</label>
							</div>
						</div>
					</div>
					<div class="row mt-10px align-items-center">
						<div class="col-8 control-label text-dark fw-bold">Sidebar Fixed</div>
						<div class="col-4 d-flex">
							<div class="form-check form-switch ms-auto mb-0">
								<input type="checkbox" class="form-check-input" name="app-sidebar-fixed" id="appSidebarFixed" value="1" checked="">
								<label class="form-check-label" for="appSidebarFixed">&nbsp;</label>
							</div>
						</div>
					</div>
					<div class="row mt-10px align-items-center">
						<div class="col-md-8 control-label text-dark fw-bold">Gradient Enabled</div>
						<div class="col-md-4 d-flex">
							<div class="form-check form-switch ms-auto mb-0">
								<input type="checkbox" class="form-check-input" name="app-gradient-enabled" id="appGradientEnabled" value="1">
								<label class="form-check-label" for="appGradientEnabled">&nbsp;</label>
							</div>
						</div>
					</div>
					<!-- END theme-switch -->
					<div class="theme-panel-divider"></div>
					<h5>Admin Design (5)</h5>
					<!-- BEGIN theme-version -->
					<div class="theme-version">
						<div class="theme-version-item">
							<a href="../template_html/index_v2.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/theme/default.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="../template_transparent/index_v2.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/theme/transparent.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="../template_apple/index_v2.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/theme/apple.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="../template_material/index_v2.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/theme/material.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="../template_facebook/index_v2.html" class="theme-version-link active">
								<span style="background-image: url(../assets/t511/img/theme/facebook.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="../template_google/index_v2.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/theme/google.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
					</div>
					<!-- END theme-version -->
					<div class="theme-panel-divider"></div>
					<h5>Language Version (7)</h5>
					<!-- BEGIN theme-version -->
					<div class="theme-version">
						<div class="theme-version-item">
							<a href="../template_html/index.html" class="theme-version-link active">
								<span style="background-image: url(../assets/t511/img/version/html.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="../template_ajax/index.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/version/ajax.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="../template_angularjs/index.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/version/angular1x.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="../template_angularjs13/index.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/version/angular10x.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="javascript:alert('Laravel Version only available in downloaded version.');" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/version/laravel.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="../template_vuejs/index.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/version/vuejs.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="../template_reactjs/index.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/version/reactjs.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="javascript:alert('.NET Core 3.1 MVC Version only available in downloaded version.');" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/version/dotnet.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
					</div>
					<!-- END theme-version -->
					<div class="theme-panel-divider"></div>
					<h5>Frontend Design (5)</h5>
					<!-- BEGIN theme-version -->
					<div class="theme-version">
						<div class="theme-version-item">
							<a href="../../../frontend/template/template_one_page_parallax/index.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/theme/one-page-parallax.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="../../../frontend/template/template_e_commerce/index.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/theme/e-commerce.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="../../../frontend/template/template_blog/index.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/theme/blog.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="../../../frontend/template/template_forum/index.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/theme/forum.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
						<div class="theme-version-item">
							<a href="../../../frontend/template/template_corporate/index.html" class="theme-version-link">
								<span style="background-image: url(../assets/t511/img/theme/corporate.jpg);" class="theme-version-cover"></span>
							</a>
						</div>
					</div>
					<!-- END theme-version -->
					<div class="theme-panel-divider"></div>
					<a href="https://seantheme.com/color-admin/documentation/" class="btn btn-dark d-block w-100 rounded-pill mb-10px" target="_blank"><b>Documentation</b></a>
					<a href="javascript:;" class="btn btn-default d-block w-100 rounded-pill" data-toggle="reset-local-storage"><b>Reset Local Storage</b></a>
				<div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 768px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 418px;"></div></div></div>
			</div>
			<!-- END theme-panel -->
			--}}
			<!-- BEGIN scroll-top-btn -->
			<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
			<!-- END scroll-top-btn -->
		</div>
		<!-- END #app -->
		<!-- ================== BEGIN core-js ================== -->
		<script src="{{ asset( '/assets/t511/js/vendor.min.js' ) }}"></script>
		<script src="{{ asset( '/assets/t511/js/app.min.js' ) }}"></script>
		<script src="{{ asset( 'assets/plugins/sweetalert/sweetalert.min.js' ) }}"></script>
		<!-- ================== END core-js ================== -->
		@livewireScripts
		<script>
			Livewire.on( 'alert-success', function ( message ) {
				swal({
					title: message.title,
					text: message.message,
					icon: 'success',
					buttons: {
						confirm: {
							text: 'Ok',
							value: true,
							visible: true,
							className: 'btn btn-success',
							closeModal: true
						}
					}
				});
			} )
		</script>
		@yield('js')
	</body>
</html>