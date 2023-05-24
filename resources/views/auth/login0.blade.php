<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Intranet | Login</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="" name="description">
    <meta content="" name="author">

    <!-- ================== BEGIN core-css ================== -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/default/app.min.css') }}" rel="stylesheet">
    <!-- ================== END core-css ================== -->
</head>
<body class='pace-top'>
    <!-- BEGIN #loader -->
    <div id="loader" class="app-loader">
        <span class="spinner"></span>
    </div>
    <!-- END #loader -->

    <!-- BEGIN #app -->
    <div id="app" class="app">
        <!-- BEGIN login -->
        <div class="login login-with-news-feed">
            <!-- BEGIN news-feed -->
            <div class="news-feed">
                <div class="news-image" style="background-image: url({{ asset('assets/img/login-bg/login-bg-00'.rand(1, 8).'-min.jpg') }})"></div>
                <div class="news-caption">
                    <h4 class="caption-title"><b>Educación Jesuita</b> en Tamaulipas</h4>
                    <p>
                        HOMBRES Y MUJERES PARA LOS DEMÁS Y CON LOS DEMÁS
                    </p>
                </div>
            </div>
            <!-- END news-feed -->

            <!-- BEGIN login-container -->
            <div class="login-container">
                <!-- BEGIN login-header -->
                <div class="login-header mb-30px">
                    <a href="{{ asset('/') }}"><img src="{{ asset('assets/img/ICT_logo.png') }}" width="300px" alt=""></a>
                </div>
                <!-- END login-header -->

                <!-- BEGIN login-content -->
                <div class="login-content">
                    @include('layouts.request')
                    <form action="{{ route('login') }}" method="POST" class="margin-bottom-0">
                        @csrf
                        <div class="mb-15px">
                            <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="Usuario" required autofocus value="{{ old('username') }}">
                        </div>
                        <div class="mb-15px">
                            <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Contraseña" required autocomplete="current-password">
                        </div>
                        <div class="form-check mb-30px">
                            <input class="form-check-input" type="checkbox" value="1" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">
                                Recuérdame
                            </label>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">Ingresar</button>
                            <a href="{{ route('password.request') }}" class="btn btn-primary btn-block btn-flat">
                                <i class="fa fa-info-circle"></i> Olvidé mi contraseña
                            </a>
                        </div>
                    </form>
                </div>
                <!-- END login-content -->
            </div>
            <!-- END login-container -->
        </div>
        <!-- END login -->

        <!-- BEGIN scroll-top-btn -->
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
        <!-- END scroll-top-btn -->
    </div>
    <!-- END #app -->

    <!-- ================== BEGIN core-js ================== -->
    <script src="../assets/js/vendor.min.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="../assets/js/theme/default.min.js"></script>
    <!-- ================== END core-js ================== -->
</body>
</html>