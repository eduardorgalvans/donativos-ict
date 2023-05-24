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
                {!! Form::model($oRegistro,['route' => ['admin.menus.update', $oRegistro->id], 'method' => 'PUT', 'class'=>'form-horizontal']) !!}
                    <fieldset>
                        <h4 class="mb-3">Agregar <small>un nodo al menú del sistema.</small></h4>
                        @include('layouts.request')
                        @include('admin.menu.forms.form', ['disables' => TRUE] ) {{-- admin/menus/forms/form --}}

                        <div class="row mb-3 {!! ($errors->has('username')) ? "is-invalid" : "" !!}">
                            {!! Form::label('username', 'Usuario :', ["class"=>"form-label col-form-label col-md-2",]); !!}
                            <div class="col-sm-10">
                                {!! Form::text('username', null, ["id"=>"username", "class"=>"form-control", "maxlength"=>"50", "minlength"=>"4", "required", "placeholder"=>"Introduzca su usuario", 'disabled'=>'disabled', ]) !!}
                            </div>
                        </div>
                        <div class="row mb-3 {!! ($errors->has('created_at')) ? "is-invalid" : "" !!}">
                            {!! Form::label('created_at', 'Creado :', ["class"=>"form-label col-form-label col-md-2",]); !!}
                            <div class="col-sm-10">
                                {!! Form::text('created_at', null, ["id"=>"created_at", "class"=>"form-control", "maxlength"=>"50", "minlength"=>"4", "required", "placeholder"=>"Introduzca su usuario", 'disabled'=>'disabled', ]) !!}
                            </div>
                        </div>
                        <div class="row mb-3 {!! ($errors->has('updated_at')) ? "is-invalid" : "" !!}">
                            {!! Form::label('updated_at', 'Modificado :', ["class"=>"form-label col-form-label col-md-2",]); !!}
                            <div class="col-sm-10">
                                {!! Form::text('updated_at', null, ["id"=>"updated_at", "class"=>"form-control", "maxlength"=>"50", "minlength"=>"4", "required", "placeholder"=>"Introduzca su usuario", 'disabled'=>'disabled', ]) !!}
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                {!! Html::decode(link_to_route('admin.menus.edit', 'Modificar', ( $oRegistro->id ?? 0 ), ["class"=>"btn btn-primary w-100px me-5px"])) !!}
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
    @include('admin.menu.js') {{-- admin/menus/js --}}
@endsection