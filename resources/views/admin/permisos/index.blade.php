@extends('layouts.intranet')

@section('titulo-pestaña')
    Permisos
@endsection

@section('titulo-pagina')
    Permisos
@endsection

@section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/jstree/themes/default/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet">
    <style type="text/css">
        .jstree .jstree-container-ul .jstree-node.jstree-open .jstree-anchor {
            color: inherit !important;
        }

        .oculto {
            display: none;
        }
    </style>
@endsection

@section('contenido')
    <div class="col-12">
        @include('layouts.request') {{-- layouts/request --}}
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Puestos</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                            <i class="fa fa-expand"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse">
                            <i class="fa fa-minus"></i>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div id="arbol-puestos"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Permisos<span id="titulo-detalles"></span></h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                            <i class="fa fa-expand"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse">
                            <i class="fa fa-minus"></i>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row mb-3">
                        <div class="form-group">
                            {{ Form::label('Perfiles', 'Perfiles Asignados:') }}
                            {{ Form::select('Perfiles[]', $aPerfiles, null, ['id'=>'Perfiles', 'class'=>'form-control', 'multiple'=>'multiple', 'disabled'=>'disabled']) }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="form-group">
                            <button type="button" id="cambiar-permisos" class="btn btn-sm btn-primary oculto">
                                <i class="fas fa-pencil-alt"></i>
                                Modificar
                            </button>
                            <button type="button" id="cancelar-guardado" class="btn btn-sm btn-danger oculto">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </button>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <p>Permisos:</p>
                        <div id="arbol-modulos"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/i18n/es.js') }}"></script>
    <script src="{{ asset('assets/plugins/jstree/jstree.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/gritter/js/jquery.gritter.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            // Creación del árbol de puestos.
            $('#arbol-puestos').jstree({
                'plugins': ['types'],
                'core': {
                    'data': {!! $aPuestos !!},
                    'multiple': false
                },
                'types' : {
                    'default': { 'icon': 'fas fa-building fa-lg text-primary' },
                    'puesto':  { 'icon': 'far fa-address-card fa-lg text-blue' }
                }
            })
            .on('ready.jstree', function(){
                $(this).jstree('open_all');
            })
            .on('select_node.jstree', function(nodo, nodoSeleccionado){
                $('#titulo-detalles').html(` de ${nodoSeleccionado.node.text}`);

                let url = '{{ route('admin.permisos.detalles-puesto', '#1') }}'.replace('#1', nodoSeleccionado.node.id);

                $.get({
                    'url': url,
                    'dataType': 'json'
                })
                .done(function(respuesta){
                    mostrarDetalles(respuesta);
                    $('#cambiar-permisos').removeClass('oculto');
                    window.location.href = '#';
                });
            });

            // Creación del árbol de módulos.
            $('#arbol-modulos').jstree({
                'plugins': ['wholerow', 'checkbox', 'types'],
                'core': {
                    'data': {!! $aModulos !!},
                    'multiple': false
                },
                'types' : {
                    'default': { 'icon': 'fas fa-cubes fa-lg text-primary' },
                    'modulo':  { 'icon': 'fas fa-cube fa-lg text-blue' },
                    'accion':  { 'icon': 'fas fa-cog fa-lg text-warning' },
                }
            })
            .on('ready.jstree', function(){
                $(this).jstree('open_all');
            });

            $('#Perfiles').select2({
                language: 'es'
            });

            $('#cambiar-permisos').on('click', function(){
                let texto = $(this).text().replace(/\W/g, '');
                if (texto == 'Modificar') {
                    $('#Perfiles').prop('disabled', false);
                    $(this).html('<i class="fas fa-save"></i> Guardar');
                    $('#cancelar-guardado').removeClass('oculto');
                } else {
                    let idPuesto = $('#arbol-puestos').jstree('get_selected')[0];
                    let url = '{{ route('admin.permisos.update', '#1') }}'.replace('#1', idPuesto);

                    $.post({
                        'url': url,
                        'dataType': 'json',
                        'data': {
                            _method: 'PUT',
                            _token: '{{ csrf_token() }}',
                            perfiles: $('#Perfiles').val().join(',')
                        }
                    })
                    .done(function(respuesta){
                        if (respuesta.hasOwnProperty('mensaje')) {
                            swal('Error', respuesta.mensaje, 'error');
                        } else {
                            mostrarDetalles(respuesta);

                            $.gritter.add({
                                title: 'Datos guardados',
                                text: 'Los permisos del puesto fueron actualizados correctamente.',
                                sticky: false,
                                time: ''
                            });
                        }
                    });

                    restablecerControles();
                }
            });

            $('#cancelar-guardado').on('click', function(){
                restablecerControles();
            });
        });

        function mostrarDetalles(puesto)
        {
            $('#Perfiles').val(puesto.perfiles.split(','));
            $('#Perfiles').trigger('change');

            $('#arbol-modulos').jstree('deselect_all');
            $('#arbol-modulos').jstree('select_node', puesto.permisos.split(','));
        }

        function restablecerControles()
        {
            $('#Perfiles').prop('disabled', true);
            $('#cambiar-permisos').html('<i class="fas fa-pencil-alt"></i> Modificar');

            if (!$('#cancelar-guardado').hasClass('oculto')) {
                $('#cancelar-guardado').addClass('oculto');
            }
        }
    </script>
@endsection