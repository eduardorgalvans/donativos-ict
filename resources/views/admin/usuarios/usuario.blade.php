@section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

<div class="col-12">
    @include('layouts.request') {{-- layouts/request --}}
</div>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Detalles del usuario</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-3">
                {{ Form::label('username', 'Usuario:', ['class'=>'col-sm-2 col-form-label']) }}
                <div class="col-sm-10">
                    {{ Form::text('username', null, ['class'=>'form-control', 'required'=>'required'] + $deshabilitado) }}
                </div>
        </div>
        <div class="row mb-3">
            {{ Form::label('email', 'Correo-e:', ['class'=>'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {{ Form::text('email', null, ['class'=>'form-control', 'required'=>'required'] + $deshabilitado) }}
            </div>
        </div>
        <div class="row mb-3">
            {{ Form::label('TblDGP_id', 'Nombre:', ['class'=>'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                @if (!$deshabilitado)
                    {{ Form::select('TblDGP_id', $aPersonas, null, ['class'=>'form-control chosen-select', 'required'=>'required', 'placeholder'=>'- Seleccione -']) }}
                @else
                    {{ Form::text('TblDGP_id', $oUsuario->persona->NombreCompleto, ['class'=>'form-control'] + $deshabilitado) }}
                @endif
            </div>
        </div>

        @if (!$deshabilitado)
            <div class="row mb-3">
                {{ Form::label('password', 'Contraseña:', ['class'=>'col-sm-2 col-form-label']) }}
                <div class="col-sm-10">
                    @if (!$nuevo)
                        <div class="form-check">
                            <input type="checkbox" id="chk-cambiar-contrasena" class="form-check-input">
                            <label for="chk-cambiar-contrasena" class="form-check-label">Cambiar contraseña</label>
                        </div>
                    @endif
                    {{ Form::password('password', ['class'=>'form-control', 'required'=>'required'] + (!$nuevo ? ['disabled'=>'disabled'] : [])) }}
                </div>
            </div>
        @endif
    </div>
    <div class="panel-footer text-end">
        {!! Html::decode(link_to_route('admin.usuarios.index', 'Cerrar', [], ['class'=>'btn btn-outline-primary'])) !!}

        @if (!$deshabilitado)
            {{ Form::submit('Guardar', ['class'=>'btn btn-primary']) }}
        @endif
    </div>
</div>

@if (!$deshabilitado)
    @section('js')
        <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/select2/js/i18n/es.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#TblDGP_id').select2({
                    language: 'es'
                });

                $('#chk-cambiar-contrasena').on('click', function(){
                    $('#password').prop('disabled', !$(this).prop('checked'));
                });
            });
        </script>
    @endsection
@endif