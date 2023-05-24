<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8" />
	    <title>ICT - Encuesta de Satisfacción | @yield('titulo-pestaña', 'ICT')</title>
	    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	    <meta content="" name="description" />
	    <meta content="" name="author" />
	    <!-- ================== BEGIN core-css ================== -->
	    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	    <link href="/assets/css/vendor.min.css" rel="stylesheet" />
	    <link href="/assets/css/facebook/app.min.css" rel="stylesheet" />
	    <link href="{{ asset('/assets/css/default/theme/red.min.css') }}" rel="stylesheet">
	    <!-- ================== END core-css ================== -->
	    <!-- ================== INICIO plugins-css ================== -->
	    <link href="{{ asset('/assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet">
	    <link href="{{ asset('/assets/plugins/bsnav/dist/bsnav.min.css') }}" rel="stylesheet">
	    <!-- ================== FIN plugins-css ================== -->
	    <!-- ================== CSS de la aplicación ================== -->

	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/css/bootstrap.min.css'>
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.4/vue.js'></script>
	   
	    @yield('css')
	</head>
	<body class="pace-done theme-red">
		<!-- BEGIN #app -->
		<div id="app" class="app  app-sidebar-fixed app-sidebar-minified">
			<!-- BEGIN #header -->
			<div id="header" class="row bg-graylight">
				<!-- BEGIN navbar-header -->
					<div class="row" align="center">
                        <div class="col-md-12">
                            <img src="https://escolares.ict.edu.mx:8090/img/exalumnos/Banner_encuesta_1.jpg" 
                            	 width="100%"  height="90%" align="center" alt="">
                        </div>
                    </div>
					<br><br>
			</div>
			<!-- END #header -->
			<!-- BEGIN #content -->
			<div id="content" class="bg-white">
				<!-- BEGIN page-header -->
				
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
    
    
</script>
    <!-- ================== FIN plugins-js ================== -->
    
    @yield('js')

	</body>
</html>