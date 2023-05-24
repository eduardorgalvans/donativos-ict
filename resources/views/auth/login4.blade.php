<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Intranet | Login</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="" name="description">
    <meta content="" name="author">

    <style>
    </style>
    
    <!-- ================== BEGIN core-css ================== -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/default/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/nucleo/css/nucleo.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/argon.css?v=1.0.1') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css?v=1.0.1') }}" rel="stylesheet">
    <!-- ================== END core-css ================== -->
    <style type="text/css">
        .box-2{
            height:500px;
            background-image: url({{ asset('assets/img/login-bg/login-bg-00'.rand(1, 8).'-min.jpg') }});
            background-size: cover;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }
        
    </style>
</head>
<body class='pace-top'>


        <section>
            <div class="container shape-container align-items-center py-lg" style="width: 60%;border-radius: 5px;">
                <div class="row" style="box-shadow: 5px 10px 35px rgba(0, 0, 0, 0.2);">
                    <div class="col-md-5 col-sm-12 bg-white text-center box-1">
                        <div class="bg-white  border-0">
                            <div class="card-body px-lg-5 py-lg-5">
                                <div class="text-center text-muted mb-4">
                                    <div class="logo">
                                        <img src="assets/img/ICT_logo.png">
                                    </div>
                                </div>
                                <form role="form">
                                    <small class="title">Iniciar sesión en su cuenta</small>
                                    
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-alternative input-box-style">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-email-83 global-color"></i></span>
                                            </div>
                                            <input  class="form-control" placeholder="Usuario" type="email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div  class="input-group input-group-alternative input-box-style">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-lock-circle-open global-color"></i></span>
                                            </div>
                                            <input class="form-control" placeholder="Contraseña" type="password">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="mt-4 btn btn-dark d-flex justify-content-center align-items-center">Acceso</button>
                                        {{-- 
                                        <button type="button" class="btn btn-icon btn-enter my-2">
                                        <span class="btn-inner--text">Enter</span>
                                        <span class="btn-inner--icon">
                                            <i class="ni ni-lock-circle-open"></i>
                                        </span>
                                        
                                        </button>
                                        --}}
                                    </div>
                                </form>
                                
                            </div>
                            <div class="btn-wrapper text-left">
                                <a href="#" class="btn">
                                    <span class="btn-inner--text">¿Se te olvidó tu contraseña?</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 p-0 box-2">
                        <div class="bg-color">
                            <div>
                                <h3 class="h2-style text-white"><b>Educación Jesuita</b> <br>en Tamaulipas.</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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