@extends('layouts.intranet')

@section('titulo-pestaña')
    Configurar accesos directos
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Mis accesos</li>
@endsection

@section('titulo-pagina')
    Mis Accesos <small>directos</small>
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

    {{ Form::open(['route'=>'admin.accesos.store']) }}
        {{ Form::hidden('IDModulos', '', ['id'=>'id-modulos']) }}
        <div class="row">
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-body">
                        <p>Marque la casilla de las opciones de menú que desea que aparezcan en sus accesos directos en el dashboard.</p>
                        <div id="arbol"></div>
                    </div>
                    <div class="panel-footer">
                        <a href="{{ route('dashboard.index') }}" class="btn btn-sm btn-white">Regresar</a>
                        {{ Form::submit('Guardar', ['id'=>'btn-guardar', 'class'=>'btn btn-sm btn-primary']) }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">Accesos</h4>
                    </div>
                    <div id="pnl-mis-accesos" class="panel-body d-flex">
                        @foreach (json_decode($aArbol) as $nodo)
                            @if (isset($nodo->state->selected) && $nodo->state->selected)
                                <div class="btn btn-md btn-dark ms-2 mb-2">
                                    <i class="{{ $nodo->icon }} fa-2x"></i><br>
                                    {{ preg_replace('/\d+ - (.+)/', '$1', $nodo->text) }}<br>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/jstree/jstree.min.js') }}"></script>
    <script type="text/javascript">
        var nodosSeleccionados = [];
        var x = {!! $aArbol !!};

        $(document).ready(function(){
            $('#arbol').jstree({
                'plugins': ['wholerow', 'checkbox'],
                'core': {
                    'data': {!! $aArbol !!},
                    'multiple': true
                }
            })
            .on('select_node.jstree deselect_node.jstree', function(e, datos){
                // Se obtienen todos los nodos seleccionados.
                nodosSeleccionados = $('#arbol').jstree('get_selected', true);

                // Sólo se consideran los nodos que sean módulos, los demás son descartados.
                let modulosSeleccionados = nodosSeleccionados.filter(
                        elemento => elemento.original.tipo == 'Módulo'
                    );

                // Se limpia el panel de accesos antes de colocar los seleccionados.
                $('#pnl-mis-accesos').empty();

                // Se agregan los módulos seleccionados a la lista de accesos directos.
                modulosSeleccionados.forEach(
                    modulo => {
                        let texto = modulo.text.replace(/\d+ - (.+)/, '$1');

                        let htmlModulo = '<div class="btn btn-md btn-dark ms-2 mb-2">' +
                                `<i class="${modulo.icon} fa-2x"></i><br>` +
                                texto +
                            '</div>';

                        $('#pnl-mis-accesos').append(htmlModulo);
                    }
                );
            }); // Fin de definición del árbol

            $('#btn-guardar').on('click', function(){
                // Se obtienen los módulos seleccionados
                let modulosSeleccionados = nodosSeleccionados.filter(
                        elemento => elemento.original.tipo == 'Módulo'
                    );

                let ms = [];
                modulosSeleccionados.forEach(elemento => ms.push(elemento.original.modulo));

                $('#id-modulos').val(ms.join());
            });
        });
    </script>
@endsection