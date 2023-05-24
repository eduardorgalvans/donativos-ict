@section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

<div class="col-12">
    @include('layouts.request') {{-- layouts/request --}}
</div>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Detalles del módulo</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-3">
            {{ Form::label('IdPadre', 'Módulo Padre:', ['class'=>'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {{ Form::select('IdPadre', $aModulos, app()->request->query('p', null), ['class'=>'form-control select2', 'required'=>'required', 'placeholder'=>'- Seleccione -'] + $deshabilitado) }}
            </div>
        </div>
        <div class="row mb-3">
            {{ Form::label('Nombre', 'Nombre:', ['class'=>'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {{ Form::text('Nombre', null, ['class'=>'form-control', 'required'=>'required'] + $deshabilitado) }}
            </div>
        </div>
        <div class="row mb-3">
            {{ Form::label('Tipo', 'Tipo:', ['class'=>'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {{ Form::select('Tipo', $aTipos, app()->request->query('t', null), ['class'=>'form-control select2', 'required'=>'required', 'placeholder'=>'- Seleccione -'] + $deshabilitado) }}
            </div>
        </div>
        <div class="row mb-3">
            {{ Form::label('Icono', 'Icono:', ['class'=>'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                <div class="input-group mb-3">
                    <span class="input-group-text selected-icon">
                        @if ($deshabilitado)
                            <i class="{{ optional($oModulo)->Icono }}"></i>
                        @endif
                    </span>
                    {{ Form::text('Icono', null, ['class'=>'form-control iconpicker'] + ((optional($oModulo)->Tipo == 3) ? ['disabled'=>'disabled'] : $deshabilitado)) }}
                </div>
            </div>
        </div>
        <div class="row mb-3">
            {{ Form::label('Ruta', 'Ruta:', ['class'=>'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {{ Form::select('Ruta', $aRutas, null, ['class'=>'form-control select2'] + ((optional($oModulo)->Tipo == 2) ? $deshabilitado : ['disabled'=>'disabled'])) }}
            </div>
        </div>
        <div class="row mb-3">
            {{ Form::label('chk-acciones-predeterminadas', 'Opciones:', ['class'=>'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                <div class="form-check">
                    <input type="checkbox" id="chk-acciones-predeterminadas" name="AccionesPredeterminadas" class="form-check-input" value="1" disabled>
                    <label for="chk-acciones-predeterminadas" class="form-check-label">
                        Generar acciones predeterminadas
                        <a href="#" class="text-black" data-bs-toggle="tooltip" title="Dichas acciones son: Ver, Agregar, Modificar, Borrar">
                            <i class="fa fa-info-circle"></i>
                        </a>
                    </label>
                </div>
                <div class="form-check mt-2">
                    {{ Form::checkbox('Visible', 1, null, ['class'=>'form-check-input', 'id'=>'chk-visible'] + $deshabilitado) }}
                    <label for="chk-visible" class="form-check-label">Visible</label>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer text-end">
        {!! Html::decode(link_to_route('admin.modulos.index', 'Cerrar', [], ['class'=>'btn btn-outline-primary'])) !!}

        @if (!$deshabilitado)
            {{ Form::submit('Guardar', ['class'=>'btn btn-primary']) }}
        @endif
    </div>
</div>

@if (!$deshabilitado)
    @section('js')
        <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/select2/js/i18n/es.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap-iconpicker/iconpicker.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                $('.select2').select2({
                    language: 'es'
                });

                $('#Tipo').on('change', function(){
                    // Sólo se habilita cuando se selecciona el tipo "Módulo".
                    $('#chk-acciones-predeterminadas, #Ruta').prop('disabled', $(this).val() != 2);

                    $('#Icono').prop('disabled', ['1','2'].indexOf($(this).val()) == -1);

                    // Se deshabilita si se selecciona el tipo "Acción".
                    $('#chk-visible').prop('disabled', $(this).val() == 3);
                });

                @if (app()->request->query('t', '') == 2)
                    $('#chk-acciones-predeterminadas, #Ruta, #Icono').prop('disabled', false);
                @endif

                @if (app()->request->query('t', '') == 3)
                    $('#chk-visible').prop('disabled', true);
                @endif

                (async () => {
                    const respuesta = await fetch('{{ asset('assets/plugins/bootstrap-iconpicker/fontawesome.json') }}');
                    const resultado = await respuesta.json();

                    new Iconpicker(document.querySelector('.iconpicker'), {
                        icons: resultado,
                        valueFormat: val => `${val}`,
                        defaultValue: '{{ optional($oModulo)->Icono ?? 'far fa-square' }}',
                        showSelectedIn: document.querySelector('.selected-icon'),
                        fade: true
                    });
                })();
            });
        </script>
    @endsection
@endif