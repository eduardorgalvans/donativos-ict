<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Intranet | Login</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="" name="description">
    <meta content="" name="author">

    <!-- ================== BEGIN core-css ================== -->
    {{-- 
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/default/app.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
    --}}
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet">
    <!-- ================== END core-css ================== -->
</head>
<body class='pace-top'>


    <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
        <div class="container">
            <div class="card login-card">
                <div class="row no-gutters">
                    <div class="col-md-7">
                        <img src="{{ asset('assets/img/login-bg/login-bg-00'.rand(1, 8).'-min.jpg') }}" alt="login" class="login-card-img">
                    </div>
                    <div class="col-md-5">
                        <div class="card-body">
                            <div class="brand-wrapper">
                                <img src="assets/img/ICT_logo.png" width="300px" alt="logo" class="">
                            </div>
                            <p class="login-card-description">Iniciar sesión en su cuenta</p>
                            <form action="{{ route('login') }}">
                                <div class="form-group">
                                    <label for="email" class="sr-only">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email address">
                                </div>
                                <div class="form-group mb-4">
                                    <label for="password" class="sr-only">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="***********">
                                </div>
                                <input name="login" id="login" class="btn btn-block login-btn mb-4" type="button" value="Acceder">
                            </form>
                            <a href="#!" class="forgot-password-link">¿Se te olvidó tu contraseña?</a>
                            <nav class="login-card-footer-nav">
                                <a href="#!">Condiciones de uso.</a>
                                <a href="#!">Política de privacidad</a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 
            <div class="card login-card">
                <img src="assets/img/login.jpg" alt="login" class="login-card-img">
                <div class="card-body">
                    <h2 class="login-card-title">Login</h2>
                    <p class="login-card-description">Sign in to your account to continue.</p>
                    <form action="#!">
                        <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="form-prompt-wrapper">
                            <div class="custom-control custom-checkbox login-card-check-box">
                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">Remember me</label>
                            </div>
                            <a href="#!" class="text-reset">Forgot password?</a>
                        </div>
                        <input name="login" id="login" class="btn btn-block login-btn mb-4" type="button" value="Login">
                    </form>
                    <p class="login-card-footer-text">Don't have an account? <a href="#!" class="text-reset">Register here</a></p>
                </div>
            </div> 
            -->
        </div>
    </main>


    {{-- 
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
    --}}

    <!-- ================== BEGIN core-js ================== -->
    <script src="../assets/js/vendor.min.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="../assets/js/theme/default.min.js"></script>
    <!-- ================== END core-js ================== -->
</body>
</html>