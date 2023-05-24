<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Intranet | Login</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="" name="description">
    <meta content="" name="author">

    <style>
        /* @ import url('https://fonts.googleapis.com/css?family=Mukta'); */
        body{
          font-family: 'Mukta', sans-serif;
            height:100vh;
            min-height:550px;
            background-image: url({{ asset('assets/img/login-bg/login-bg.jpg') }});
            background-repeat: no-repeat;
            background-size:cover;
            background-position:center;
            position:relative;
            overflow-y: hidden;
        }
        a{
          text-decoration:none;
          color:#444444;
        }
        .login-reg-panel{
            position: relative;
            top: 50%;
            transform: translateY(-50%);
            text-align:center;
            width:70%;
            right:0;left:0;
            margin:auto;
            height:400px;
            background-image: url({{ asset('assets/img/login-bg/login-bg-000'.rand(1, 5).'-min.jpg') }});
            background-size: cover;
            /*background-color: rgba(236, 48, 20, 0.9);*/
        }
        .white-panel{
            background-color: rgba(255,255, 255, 1);
            height:500px;
            position:absolute;
            top:-50px;
            width:50%;
            right:calc(50% - 50px);
            transition:.3s ease-in-out;
            z-index:0;
            box-shadow: 0 0 15px 9px #00000096;
        }
        .login-reg-panel input[type="radio"]{
            position:relative;
            display:none;
        }
        .login-reg-panel{
            color:#FFFFFF;
        }
        .login-reg-panel #label-login, 
        .login-reg-panel #label-register{
            border:1px solid #9E9E9E;
            padding:5px 5px;
            width:150px;
            display:block;
            text-align:center;
            border-radius:10px;
            cursor:pointer;
            font-weight: 600;
            font-size: 18px;
            background-color: #348fe2;
        }
        .login-info-box{
            width:30%;
            padding:0 50px;
            top:20%;
            left:0;
            position:absolute;
            text-align:left;
        }
        .register-info-box{
            width:40%;
            padding:0 50px;
            top:20%;
            right:0;
            position:absolute;
            text-align:left;
            
        }
        .right-log{right:50px !important;}

        .login-show, 
        .register-show{
            z-index: 1;
            display:none;
            opacity:0;
            transition:0.3s ease-in-out;
            color:#242424;
            text-align:left;
            padding:50px;
        }
        .show-log-panel{
            display:block;
            opacity:0.9;
        }
        .login-show input[type="text"], .login-show input[type="password"]{
            width: 100%;
            display: block;
            margin:20px 0;
            padding: 15px;
            border: 1px solid #b5b5b5;
            outline: none;
        }
        .login-show input[type="submit"] {
            max-width: 150px;
            width: 100%;
            background: #444444;
            color: #f9f9f9;
            border: none;
            padding: 10px;
            text-transform: uppercase;
            border-radius: 2px;
            float:right;
            cursor:pointer;
        }
        .login-show a{
            display:inline-block;
            padding:10px 0;
        }

        .register-show input[type="text"], .register-show input[type="password"]{
            width: 100%;
            display: block;
            margin:20px 0;
            padding: 15px;
            border: 1px solid #b5b5b5;
            outline: none;
        }
        .register-show input[type="button"] {
            max-width: 150px;
            width: 100%;
            background: #444444;
            color: #f9f9f9;
            border: none;
            padding: 10px;
            text-transform: uppercase;
            border-radius: 2px;
            float:right;
            cursor:pointer;
        }
        .credit {
            position:absolute;
            bottom:10px;
            left:10px;
            color: #3B3B25;
            margin: 0;
            padding: 0;
            font-family: Arial,sans-serif;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            z-index: 99;
        }
        a{
          text-decoration:none;
          color:#2c7715;
        }
        .sombra {
            color: white; 
            text-shadow: black 0.1em 0.1em 0.2em
        }
        .small {
            font-size:1rem
        }
        .derecha{
            text-align: right;
        }
        .centro{
            text-align: center;
        }
        .separador{
            height: 100px;
        }
        .boton-derecha{
            float: right;
        }
    </style>
    
    <!-- ================== BEGIN core-css ================== -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/default/app.min.css') }}" rel="stylesheet">
    <!-- ================== END core-css ================== -->
</head>
<body class='pace-top'>


    <div class="login-reg-panel">
        <div class="login-info-box">
            <div class="separador"></div>
            <h2 class="sombra">¿Tener una cuenta?</h2>
            <p class="sombra">Ingresa con tu usuario</p>
            <label id="label-register" for="log-reg-show">Acceso</label>
            <input type="radio" name="active-log-panel" id="log-reg-show" checked="checked">
        </div>
                            
        <div class="register-info-box">
            <h1 class="sombra derecha"><b>Educación Jesuita</b></h1>
            <h3 class="sombra derecha">en Tamaulipas</h3>
            <div class="separador"></div>
            <p class="sombra derecha">¿Se te olvidó tu contraseña?</p>
            <p class="boton-derecha">
                <label id="label-login" for="log-login-show">Recuperar</label>
                <input type="radio" name="active-log-panel" id="log-login-show">
            </p>
        </div>
                            
        <div class="white-panel">
            <div class="login-show">
                <form action="{{ route('login') }}" method="POST" class="margin-bottom-0">
                    @csrf
                    <img src="assets/img/ICT_logo.png" width="300px" alt="logo" class="">
                    <hr>
                    <h4 class="centro">Iniciar sesión en su cuenta</h4>
                    <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="Usuario" required autofocus value="{{ old('username') }}">
                    <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Contraseña" required autocomplete="current-password">
                        <div class="form-check mb-30px">
                            <input class="form-check-input" type="checkbox" value="1" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">
                                Recuérdame
                            </label>
                        </div>
                    <input type="submit" class="btn" value="Ingresar">
                </form>
            </div>
            <div class="register-show">
                <img src="assets/img/ICT_logo.png" width="300px" alt="logo" class="">
                <h2 class="">Recuperar</h2>
                <input type="text" placeholder="Email">
                {{-- 
                <input type="password" placeholder="Password">
                <input type="password" placeholder="Confirm Password">
                --}}
                <input type="button" value="Recuperar">
            </div>
        </div>
    </div>
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