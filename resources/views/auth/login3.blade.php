<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Intranet | Login</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="" name="description">
    <meta content="" name="author">

    <style>
* {
    margin: 0;
    padding: 0
}

body {
    background-color: #1a1a1a
}

.card1 {
    height: 400px;
    width: 100%;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.5s
}

.card1:hover {
    transform: scale(1.1)
}

.card2 {
    height: 400px;
    width: 100%;
    border: none;
    background-color: #000;
    border-radius: 8px;
    transition: all 0.5s;
    background-image: url({{ asset('assets/img/login-bg/login-bg-00'.rand(1, 8).'-min.jpg') }});
    background-size: cover;
}

.card2:hover {
    transform: scale(1.1)
}

.login {
    font-size: 20px;
    font-weight: bold;
    margin-left: 10px
}

.input-field span {
    font-size: 12px;
    color: #cecdcd;
    margin-left: 10px
}

.form-control {
    font-size: 13px;
    color: #767473;
    font-weight: 500;
    border-left: none;
    border-right: none;
    border-top: none;
    border-bottom: 1px solid #9b9b9b;
    box-shadow: none
}

.btn {
    height: 35px;
    width: 100%;
    background-color: #000;
    font-weight: 500;
    color: #cdcdc4;
    border: none;
    font-size: 15px
}

.text1 .forget {
    color: #767676;
    font-weight: 500
}

.text2 span {
    font-weight: 500;
    color: #7a7778
}

.text2 .register {
    color: #4f4942;
    font-weight: bold
}    
    </style>
    
    <!-- ================== BEGIN core-css ================== -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/default/app.min.css') }}" rel="stylesheet">
    <!-- ================== END core-css ================== -->
</head>
<body class='pace-top'>

    <br>
    <br>
    <div class="container mt-5 mb-5">
        <div class="d-flex flex row g-0">
            <div class="col-md-6 mt-3">
                <div class="card card1 p-3">
                    <div class="d-flex flex-column">
                        <img src="assets/img/ICT_logo.png" width="300" />
                        <span class="login mt-3">Iniciar sesión en su cuenta</span>
                    </div>
                    <div class="input-field d-flex flex-column mt-3">
                        <span>Usuario</span> 
                        <input class="form-control" placeholder="Usuario"> 
                        <span class="mt-3">Contraseña</span> 
                        <input class="form-control" placeholder="Ingresa tu contraseña"> 
                        <button class="mt-4 btn btn-dark d-flex justify-content-center align-items-center">Acceso</button>
                        <div class="mt-3 text1"> 
                            <span class="mt-3 forget">¿Se te olvidó tu contraseña?</span> 
                        </div>
                        <div class="text2 mt-4 d-flex flex-row align-items-center"> 
                            {{--
                            <span>Don't have an account?<span class="register">Register here</span></span> 
                            --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <div class="card card2 p-3">
                    {{-- 
                    <div class="image"> <img src="https://i.imgur.com/OgCz2Ly.jpg"> </div>
                    --}}
                </div>
            </div>
        </div>
    </div>

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
    <script type="text/javascript">

    $(document).ready(function(){
        $('.login-info-box').fadeOut();
        $('.login-show').addClass('show-log-panel');
    });

    $('.login-reg-panel input[type="radio"]').on('change', function() {
        if($('#log-login-show').is(':checked')) {
            $('.register-info-box').fadeOut(); 
            $('.login-info-box').fadeIn();
            
            $('.white-panel').addClass('right-log');
            $('.register-show').addClass('show-log-panel');
            $('.login-show').removeClass('show-log-panel');
            
        }
        else if($('#log-reg-show').is(':checked')) {
            $('.register-info-box').fadeIn();
            $('.login-info-box').fadeOut();
            
            $('.white-panel').removeClass('right-log');
            
            $('.login-show').addClass('show-log-panel');
            $('.register-show').removeClass('show-log-panel');
        }
    });
          
    </script>
    <!-- ================== END core-js ================== -->
</body>
</html>