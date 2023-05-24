@extends('layouts.intranet')

@section('titulo-pestaña')
    Dashboard
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="javascript:;">Dashboard</a></li>
@endsection

@section('titulo-pagina')
    Dashboard
@endsection

@section('css')
    <style>
        .image {
            float: left;
            width: 60px;
            height: 60px;
            overflow: hidden;
            border-radius: 6px;
        }
    </style>
@endsection

@section('contenido')
    @include( 'layouts.request' ) {{-- layouts/request --}}
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="panel pb-3">
                <div class="panel-heading bg-red-800 text-white">
                    <h4 class="panel-title">Mis Accesos</h4>

                    <div class="panel-heading-btn">
                        <a href="{{ route('admin.accesos.index') }}" class="btn btn-xs btn-warning me-2">
                            <i class="fa fa-cog"></i> Configurar
                        </a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                            <i class="fa fa-expand"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse">
                            <i class="fa fa-minus"></i>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    = Mis accesos =
                </div>
            </div>
            <!-- -->
            <div class="panel">
                <div class="panel-heading bg-red-800 text-white">
                    <h4 class="panel-title">Calendarios</h4>
                </div>
                <div class="panel-body">
                    = Calendarios =
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <!-- BEGIN Cumpleaños -->
            {{-- Inicia : Cumpleaños --}}
            <!-- BEGIN widget-list -->
            <div class="mb-10px mt-10px fs-10px">
                <a href="#modal-widget-list" class="float-end text-gray-600 text-decoration-none me-3px fw-bold" data-bs-toggle="modal">0</a>
                <b class="text-dark"><i class="fas fa-birthday-cake"></i> Cumpleaños</b>
            </div>
            <div class="widget-list shadow rounded mb-4" data-id="widget">
                <div class="row pt-2 pb-2">
                    <div class="col-12 text-center">
                        <img src="{{ asset('assets/img/cake.png') }}" width="80px" alt="">
                        <br>
                        Sin datos que mostrar.
                    </div>
                </div>
            </div>
            <!-- END Cumpleaños -->
        </div>
        <div class="col-12 col-md-3">
            {{-- Inicia : Usuarios en linea --}}
            <div class="mb-10px mt-10px fs-10px">
                <a href="#modal-widget-list" class="float-end text-gray-600 text-decoration-none me-3px fw-bold" data-bs-toggle="modal"></a>
                <b class="text-dark"><i class="fas fa-users"></i> Usuarios en linea</b>
            </div>
            <div class="panel shadow rounded panel-default" data-sortable-id="index-4" data-init="true" style="">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            <div class="float-start">
                                @foreach ($oUsers as $oUser)
                                    @if($oUser->isOnline())
                                        <a href="javascript:;" class="" >
                                            <div class="widget-img rounded bg-dark float-start me-5px mb-5px cUsuarios" data-user="{!! $oUser->username !!}" style="background-image: url({!! Libreria::obtenerFotoTrabajador( optional( $oUser->trabajador )->NumTrabajador ) !!})"></div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <a href="javascript:;" id="OnLineName" class="text-decoration-none text-dark ">Usuarios</a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Termina : Usuarios en linea --}}
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function(){
            // cambia el usuario que se seleccione
            $('.cUsuarios').hover(function(){
                var sUsusrio = $( this ).data( "user" );
                $( "#OnLineName" ).text( sUsusrio );
            });
        });
    </script>
@endsection
