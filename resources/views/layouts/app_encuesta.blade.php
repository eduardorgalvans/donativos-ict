<html lang="en"><head>
	<meta charset="utf-8">
	<title>Encuesta de Satisfacción - ICT</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
	<meta content="" name="description">
	<meta content="" name="author">
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="{{ asset( '/assets/t511/css/vendor.min.css' ) }}" rel="stylesheet">
	<link href="{{ asset( '/assets/t511/css/facebook/app.min.css' ) }}" rel="stylesheet">
	<!-- ================== END core-css ================== -->
    <!-- ================== BEGIN core-css ================== -->
    <link href="/assets/css/vendor.min.css" rel="stylesheet" />
    <link href="/assets/css/facebook/app.min.css" rel="stylesheet" />
    <link href="{{ asset('/assets/css/default/theme/red.min.css') }}" rel="stylesheet">
    <!-- ================== END core-css ================== -->
    <!-- ================== INICIO plugins-css ================== -->
    <link href="{{ asset('/assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/plugins/bsnav/dist/bsnav.min.css') }}" rel="stylesheet">
    <!-- ================== FIN plugins-css ================== -->
    <!-- ================== CSS de la aplicación ================== -->
    <link href="{{ asset('/assets/css/ict.css') . '?v=' . filemtime(realpath('assets/css/ict.css')) }}" rel="stylesheet">
</head>
	<body class="pace-done theme-red">
		<!-- BEGIN #app -->
		<div id="app" class="app app-header-fixed app-sidebar-fixed app-sidebar-minified">
			<!-- BEGIN #header -->
			<div id="header" class="row bg-graylight">
				<!-- BEGIN navbar-header -->
					<div class="row" align="center">
                        <div class="col-md-12">
                            <img src="https://escolares.ict.edu.mx:8090/img/exalumnos/IMG-ICT-014_BANER_FORMULARIO_GUINDA.jpg" width="50%" align="center" alt="">
                        </div>
                    </div>
					<br><br>
			</div>
			<!-- END #header -->
			<!-- BEGIN #content -->
			<div id="content" class="bg-white">
				<!-- BEGIN page-header -->
				<h1 class="page-header">@yield('titulo-pagina')</h1>
				<!-- END page-header -->
				@yield('contenido')
			</div>
			<!-- END #content -->
		
			<!-- END scroll-top-btn -->
		</div>
		<!-- END #app -->


    <!-- ================== BEGIN core-js ================== -->
    <script src="/assets/js/vendor.min.js"></script>
    <script src="/assets/js/app.min.js"></script>
    <!-- ================== END core-js ================== -->
    <!-- ================== INICIO plugins-js ================== -->
    <script src="{{ asset('/assets/plugins/gritter/js/jquery.gritter.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/bsnav/dist/bsnav.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/printPage/jquery.printPage.js') }}"></script>
    <script src="{{ asset('/assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/WebNotifications/WebNotifications.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.js"></script>
    <script src="{{ asset('/assets/js/nodeClient.js') . '?v=' . filemtime(realpath('assets/js/intra.js')).'&tblDGP='.Auth::user()->TblDGP_id }}"></script>
    <!-- ================== FIN plugins-js ================== -->
    <script src="{{ asset('/assets/js/intra.js') . '?v=' . filemtime(realpath('assets/js/intra.js')) }}"></script>
    @yield('js')

	</body>
</html>