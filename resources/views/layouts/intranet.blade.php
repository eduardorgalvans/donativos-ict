<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>IntraCT | @yield('titulo-pestaña', 'Intranet')</title>
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
    <link href="{{ asset('/assets/css/ict.css') . '?v=' . filemtime(realpath('assets/css/ict.css')) }}" rel="stylesheet">
    @yield('css')
</head>
<body>
    <!-- BEGIN #loader -->
    <div id="loader" class="app-loader">
        <span class="spinner"></span>
    </div>
    <!-- END #loader -->
    <!-- BEGIN #app -->
    <div id="app" class="app app-header-fixed app-sidebar-fixed {{ !(isset($contraerMenu) && $contraerMenu) ? 'app-sidebar-minified' : '' }}">
        <!-- BEGIN #header -->
        <div id="header" class="app-header">
            <!-- BEGIN navbar-header -->
            <div class="navbar-header">
                {{-- 
                <a href="index.html" class="navbar-brand"><i class="fab fa-facebook-square fa-lg"></i> <b>Color</b> Admin <small>social</small></a>
                --}}
                <a href="/" class="navbar-brand">
                    <img src="{{ asset('/assets/img/ict-32x35.png') }}" >&nbsp;
                    <b>IntraICT</b>
                </a>
                <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <!-- END navbar-header -->
            <!-- BEGIN header-nav -->
            <div class="navbar-nav">
                <div class="navbar-item navbar-form">
                    {{-- 
                    <form action="" method="POST" name="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Enter keyword">
                            <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                    --}}
                </div>
                {{ Libreria::GetNotificaciones() }}
                <div class="navbar-item navbar-user dropdown">
                    <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                        <img src="{{ Libreria::obtenerFotoTrabajador(optional(Auth::user()->trabajador)->NumTrabajador) }}" alt="Foto personal" />
                        <span class="d-none d-md-inline">{{ Auth::user()->persona->NombreCompleto }}</span> <b class="caret ms-6px"></b>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end me-1">
                        <a href="{{ route('admin.usuarios.cambiar-contrasena', Auth::id()) }}" class="dropdown-item">Cambiar contraseña</a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:;" id="cerrar-sesion" class="dropdown-item">Cerrar sesión</a>
                        <form id="frm-cerrar-sesion" action="{{ route('logout')}}" method="post">
                            @csrf
                        </form>
                        {{-- 
                        <a href="javascript:;" class="dropdown-item">Edit Profile</a>
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center">
                            Inbox
                            <span class="badge bg-danger rounded-pill ms-auto pb-4px">2</span> 
                        </a>
                        <a href="javascript:;" class="dropdown-item">Calendar</a>
                        <a href="javascript:;" class="dropdown-item">Setting</a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:;" class="dropdown-item">Log Out</a>
                        --}}
                    </div>
                </div>
            </div>
            <!-- END header-nav -->
        </div>
        {{-- 
        <div id="header" class="app-header">
            <!-- BEGIN navbar-header -->
            <div class="navbar-header">
                <a href="/" class="navbar-brand">
                    <img src="{{ asset('assets/img/ict-32x35.png') }}">&nbsp;
                    <b>IntraICT</b>
                </a>
                <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <!-- END navbar-header -->
            <!-- BEGIN header-nav -->
            <div class="navbar-nav">
                <div class="navbar-item navbar-form">
                </div>
                @include('layouts.notificaciones') {{-- layouts/notificaciones -}}
                <div class="navbar-item navbar-user dropdown">
                    <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                        <img src="{{ Libreria::obtenerFotoTrabajador(optional(Auth::user()->trabajador)->NumTrabajador) }}" alt="Foto personal" />
                        <span class="d-none d-md-inline">{{ Auth::user()->persona->NombreCompleto }}</span> <b class="caret ms-6px"></b>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end me-1">
                        <a href="javascript:;" class="dropdown-item">Cambiar contraseña</a>
                        {{--
                        <a href="javascript:;" class="dropdown-item"><span class="badge bg-danger float-end rounded-pill">2</span> Inbox</a>
                        <a href="javascript:;" class="dropdown-item">Calendar</a>
                        <a href="javascript:;" class="dropdown-item">Setting</a>
                        -}}
                        <div class="dropdown-divider"></div>
                        <a href="javascript:;" id="cerrar-sesion" class="dropdown-item">Cerrar sesión</a>
                        <form id="frm-cerrar-sesion" action="{{ route('logout')}}" method="post">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
            <!-- END header-nav -->
        </div>
        --}}
        <!-- END #header -->
        <!-- BEGIN #sidebar -->
        <div id="sidebar" class="app-sidebar">
            <!-- BEGIN scrollbar -->
            <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
                <!-- BEGIN menu -->
                <div class="menu">
                    <div class="menu-profile">
                        <a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile" data-target="#appSidebarProfileMenu">
                            <div class="menu-profile-cover with-shadow"></div>
                            <div class="menu-profile-info">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        Departamento
                                    </div>
                                    <div class="menu-caret ms-auto"></div>
                                </div>
                                <small>Puesto</small>
                            </div>
                        </a>
                    </div>
                    <div id="appSidebarProfileMenu" class="collapse">
                        <div class="menu-item pt-5px">
                            <a href="javascript:;" class="menu-link">
                                <div class="menu-icon"><i class="fa fa-cog"></i></div>
                                <div class="menu-text">Configuración</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="javascript:;" class="menu-link">
                                <div class="menu-icon"><i class="fa fa-pencil-alt"></i></div>
                                <div class="menu-text">Enviar comentarios</div>
                            </a>
                        </div>
                        <div class="menu-item pb-5px">
                            <a href="javascript:;" class="menu-link">
                                <div class="menu-icon"><i class="fa fa-question-circle"></i></div>
                                <div class="menu-text"> Ayuda</div>
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
            </div>
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
            <!-- BEGIN panel -->
            @yield('contenido')
            <!-- END panel -->
        </div>
        <!-- END #content -->
        <!-- BEGIN scroll-top-btn -->
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
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
