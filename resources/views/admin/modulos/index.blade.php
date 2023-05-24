@extends('layouts.intranet')

@section('titulo-pestaña')
    Módulos
@endsection

@section('titulo-pagina')
    Módulos
@endsection

@section('css')
    <link href="{{ asset('assets/plugins/jstree/themes/default/style.min.css') }}" rel="stylesheet">
    <style type="text/css">
        .jstree .jstree-container-ul .jstree-node.jstree-open .jstree-anchor {
            color: inherit !important;
        }
    </style>
@endsection

@section('contenido')
    <div class="col-12">
        @include('layouts.request') {{-- layouts/request --}}
    </div>

    <div class="row mb-3">
        <div class="col-12">
            {!! Html::decode(link_to_route('admin.modulos.create', '<i class="fa fa-plus"></i> Módulo', [], ['class'=>'btn btn-primary'])) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Listado</h4>
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
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>ID Padre</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Icono</th>
                                        <th>Visible</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($oModulos as $oModulo)
                                        <tr>
                                            <td>{{ $oModulo->id }}</td>
                                            <td>{{ $oModulo->IdPadre }}</td>
                                            <td>{{ $oModulo->Nombre }}</td>
                                            <td>
                                                @switch($oModulo->Tipo)
                                                    @case(1)
                                                        <span class="badge bg-primary">Carpeta</span>
                                                        @break

                                                    @case(2)
                                                        <span class="badge bg-blue">Módulo</span>
                                                        @break

                                                    @default
                                                        <span class="badge bg-warning">Acción</span>
                                                @endswitch
                                            </td>
                                            <td><i class="{{ $oModulo->Icono }} fa-lg"></i></td>
                                            <td>
                                                @if ($oModulo->Visible)
                                                    <i class="fa fa-check-square text-green"></i>
                                                @else
                                                    <i class="far fa-square text-danger"></i>
                                                @endif
                                            </td>
                                            <td>
                                                {!! Html::decode(link_to_route('admin.modulos.show', '<i class="fa fa-search"></i>', [$oModulo->id], ['class'=>'text-primary'])) !!}
                                                &nbsp;
                                                {!! Html::decode(link_to_route('admin.modulos.edit', '<i class="fa fa-pencil-alt"></i>', [$oModulo->id], ['class'=>'text-blue'])) !!}
                                                &nbsp;
                                                <a href="#" id="borrar-modulo-{{ $oModulo->id }}" class="borrar-modulo text-danger" data-id="{{ $oModulo->id }}" data-modulo="{{ $oModulo->Nombre }}">
                                                    <i class="fa fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Árbol</h4>
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
                    <div id="arbol"></div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::open(['route'=>['admin.modulos.destroy', '#1'], 'method'=>'DELETE', 'id'=>'frm-borrar']) }}
        <input type="hidden" name="id-modulo">
    {{ Form::close() }}
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jstree/jstree.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.borrar-modulo').on('click', function(){
                swal({
                    title: '¡Atención!',
                    text: '¿Desea borrar el módulo [' + $(this).data('modulo') + ']?',
                    icon: 'warning',
                    buttons: ['Cancelar', 'Borrar'],
                    dangerMode: true
                }).then(borrar => {
                    if (borrar) {
                        var action = $('#frm-borrar').prop('action');
                        var id = $(this).data('id');

                        $('#frm-borrar').prop('action', action.replace('#1', id));
                        $('#frm-borrar').submit();
                    }
                });
            });

            $('#arbol').jstree({
                'plugins': ['types', 'contextmenu'],
                'core': {
                    'data': {!! $aArbol !!},
                    'multiple': false
                },
                'types' : {
                    'default': { 'icon': 'fa fa-cubes fa-lg text-primary' },
                    'modulo':  { 'icon': 'fa fa-cube fa-lg text-blue' },
                    'accion':  { 'icon': 'fa fa-cog fa-lg text-warning' },
                },
                'contextmenu': {
                    'items': function(nodo){
                        let arbol = $('#arbol').jstree(true);
                        return obtenerMenuContextual(nodo, arbol);
                    }
                }
            });
        });

        function obtenerMenuContextual(nodo, arbol)
        {
            // Menú común a todo el árbol
            let menu = {};

            if (nodo.parent != '#') {
                menu = {
                    'Editar': {
                        'label': 'Editar',
                        'action': function(obj) {
                            let url = '{{ route('admin.modulos.edit', '#1') }}'.replace('#1', nodo.id);

                            window.location.href = url;
                        }
                    },
                    'Borrar': {
                        'label': 'Borrar',
                        'action': function(obj) {
                            $(`#borrar-modulo-${nodo.id}`).click();
                        }
                    }
                };
            }

            let menuNuevo = {};
            if (nodo.type != 'accion' || nodo.parent == '#') {
                let etiqueta = 'Nueva carpeta';
                let tipo = 1;

                if (nodo.parent != '#') {
                    etiqueta = (nodo.type == 'default') ? 'Nuevo módulo' : 'Nueva acción';
                    tipo = (nodo.type == 'default') ? 2 : 3;
                }

                menuNuevo = {
                    'Nuevo': {
                        'label': etiqueta,
                        'action': function(obj) {
                            window.location.href = `{{ route('admin.modulos.create') }}?p=${nodo.id}&t=${tipo}`;
                        }
                    },

                };
            }

            return {...menuNuevo, ...menu};
        }
    </script>
@endsection