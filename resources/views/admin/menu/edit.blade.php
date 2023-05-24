@extends('layouts.intranet')

@section('titulo-pestaña')
    Menús
@endsection

@section('css')
    @include('admin.menu.css') {{-- admin/menus/css --}}
@endsection

@section('breadcrumb')
                <li class="breadcrumb-item"><a href="javascript:;">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">Configuración</a></li>
                <li class="breadcrumb-item active">Menús</li>
@endsection

@section('titulo-pagina')
    Configuración <small>de los menús</small>
@endsection

@section('contenido')
        <div class="panel panel-inverse" data-sortable-id="form-stuff-11">
            <!-- BEGIN panel-body -->
            <div class="panel-body">
                {!! Form::model($oRegistro,['route' => ['admin.menus.update', $oRegistro->id], 'method' => 'PUT', 'class'=>'form-horizontal', 'id'=>'formEnvio']) !!}
                    <fieldset>
                        <h4 class="mb-3">Modifica <small>un nodo del menú del sistema.</small></h4>
                        @include('layouts.request')
                        @include('admin.menu.forms.form') {{-- admin/menu/forms/form --}}
                        <hr>
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                {!! Form::submit('Guardar', ['class'=>'btn btn-primary w-100px me-5px']); !!}
                                {!! Html::decode(link_to_route('admin.menus.index', 'Cancelar', null, ["class"=>"btn btn-default w-100px"])) !!}
                            </div>
                        </div>
                    </fieldset>
                {!! Form::close() !!}
            </div>
            <!-- END panel-body -->
        </div>
@endsection

@section('js')
    @include('admin.menu.js') {{-- admin/menu/js --}}
@endsection