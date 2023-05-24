@extends('layouts.intranet')

@section('titulo-pestaña')
    Usuario: Cambiar contraseña
@endsection

@section('titulo-pagina')
    Usuario: Cambiar contraseña
@endsection

@section('contenido')
    {{ Form::model($oUsuario, ['route'=>['admin.usuarios.guardar-cambio-contrasena', $oUsuario->id]]) }}
        <div class="col-12">
            @include('layouts.request') {{-- layouts/request --}}
        </div>

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">Detalles del usuario</h4>
            </div>
            <div class="panel-body">
                <div class="row mb-3">
                    <div class="alert alert-warning alert-dismissible fade show mb-2" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <h4 class="mb-0">Si cambia la contraseña el sistema cerrará su sesión actual.</h4>
                    </div>
                </div>

                <div class="row mb-3">
                    {{ Form::label('TblDGP_id', 'Nombre:', ['class'=>'col-sm-2 col-form-label']) }}
                    <div class="col-sm-10">
                        {{ Form::text('TblDGP_id', $oUsuario->persona->NombreCompleto, ['class'=>'form-control', 'disabled'=>'disabled']) }}
                    </div>
                </div>

                <div class="row mb-3">
                    {{ Form::label('username', 'Usuario:', ['class'=>'col-sm-2 col-form-label']) }}
                    <div class="col-sm-10">
                        {{ Form::text('username', null, ['class'=>'form-control', 'disabled'=>'disabled']) }}
                    </div>
                </div>

                <div class="row mb-3">
                    {{ Form::label('email', 'Correo-e:', ['class'=>'col-sm-2 col-form-label']) }}
                    <div class="col-sm-10">
                        {{ Form::text('email', null, ['class'=>'form-control', 'disabled'=>'disabled']) }}
                    </div>
                </div>

                <div class="row mb-3">
                    {{ Form::label('ContrasenaActual', 'Contraseña actual:', ['class'=>'col-sm-2 col-form-label']) }}
                    <div class="col-sm-10">
                        {{ Form::password('ContrasenaActual', ['class'=>'form-control' . ($errors->has('ContrasenaActual') ? ' is-invalid' : ''), 'required'=>'required']) }}
                        @error('ContrasenaActual')
                            <span class="badge bg-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    {{ Form::label('ContrasenaNueva', 'Contraseña nueva:', ['class'=>'col-sm-2 col-form-label']) }}
                    <div class="col-sm-10">
                        {{ Form::password('ContrasenaNueva', ['class'=>'form-control' . (($errors->has('ContrasenaNueva') || $errors->has('Confirmacion')) ? ' is-invalid' : ''), 'required'=>'required']) }}
                        @error('ContrasenaNueva')
                            <span class="badge bg-danger">{{ $message }}</span>
                        @enderror
                        @error('Confirmacion')
                            <span class="badge bg-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    {{ Form::label('ConfirmarContrasena', 'Confirmar contraseña:', ['class'=>'col-sm-2 col-form-label']) }}
                    <div class="col-sm-10">
                        {{ Form::password('ConfirmarContrasena', ['class'=>'form-control' . (($errors->has('ContrasenaNueva') || $errors->has('Confirmacion')) ? ' is-invalid' : ''), 'required'=>'required']) }}
                        @error('ConfirmarContrasena')
                            <span class="badge bg-danger">{{ $message }}</span>
                        @enderror
                        @error('Confirmacion')
                            <span class="badge bg-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="panel-footer text-end">
                {!! Html::decode(link_to_route('dashboard.index', 'Cerrar', [], ['class'=>'btn btn-outline-primary'])) !!}
                {{ Form::submit('Guardar', ['class'=>'btn btn-primary']) }}
            </div>
        </div>
    {{ Form::close() }}
@endsection