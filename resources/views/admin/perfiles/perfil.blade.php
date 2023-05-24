@section('css')
    <link href="{{ asset('assets/plugins/jstree/themes/default/style.min.css') }}" rel="stylesheet">
@endsection

<div class="col-12">
    @include('layouts.request') {{-- layouts/request --}}
</div>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Detalles del perfil</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-3">
            {{ Form::label('Nombre', 'Nombre:', ['class'=>'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {{ Form::text('Nombre', null, ['class'=>'form-control', 'required'=>'required'] + $deshabilitado) }}
            </div>
        </div>

        <div class="row mb-3">
            {{ Form::label('chk-acciones-predeterminadas', 'Opciones:', ['class'=>'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                <div class="form-check mt-2">
                    {{ Form::checkbox('Activo', 1, null, ['class'=>'form-check-input', 'id'=>'chk-activo'] + $deshabilitado) }}
                    <label for="chk-activo" class="form-check-label">Activo</label>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            {{ Form::label('Permisos', 'Permisos:', ['class'=>'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                <div id="arbol"></div>
            </div>
        </div>
    </div>
    <div class="panel-footer text-end">
        {!! Html::decode(link_to_route('admin.perfiles.index', 'Cerrar', [], ['class'=>'btn btn-outline-primary'])) !!}

        @if (!$deshabilitado)
            {{ Form::submit('Guardar', ['class'=>'btn btn-primary', 'id'=>'btn-guardar']) }}
            {{ Form::hidden('Permisos', '', ['id'=>'Permisos']) }}
        @endif
    </div>
</div>

@section('js')
    <script src="{{ asset('assets/plugins/jstree/jstree.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            // Creación del árbol de módulos.
            $('#arbol').jstree({
                'plugins': ['wholerow', 'checkbox', 'types'],
                'core': {
                    'data': {!! $aArbol !!},
                    'multiple': true
                },
                'types' : {
                    'default': { 'icon': 'fa fa-cubes fa-lg text-primary' },
                    'modulo':  { 'icon': 'fa fa-cube fa-lg text-blue' },
                    'accion':  { 'icon': 'fa fa-cog fa-lg text-warning' },
                }
            })
            .on('ready.jstree', function(){
                $(this).jstree('open_all');

                @if (old('Permisos'))
                    $(this).jstree('select_node', [{{ old('Permisos') }}]);
                @elseif (!$nuevo && $oPerfil->Permisos)
                    $(this).jstree('select_node', [{{ $oPerfil->Permisos }}]);
                @endif
            });

            $('#btn-guardar').on('click', function(){
                let aIDs = $('#arbol').jstree('get_selected');

                /*
                 * El ID cero indica el nodo raíz (que no existe). Si está seleccionado
                 * se elimina de la lista. Por precaución se busca dicho ID en lugar de
                 * asumir que está siempre al inicio.
                 */
                let i = aIDs.indexOf('0');
                if (i > -1) {
                    aIDs.splice(i, 1);
                }

                // Se incluyen los nodos semimarcados para que los usuarios puedan tener acceso
                // a los módulos dueños de los permisos marcados.
                $('#arbol').find('.jstree-undetermined').each(function(i, elemento){
                    let idNodo = $(elemento).closest('.jstree-node').prop('id');

                    if (idNodo > 0) {
                        aIDs.push(-idNodo);
                    }
                });

                // Se almacenan los módulos seleccionados como una cadena de valores separados
                // por comas antes de enviar los datos al servidor.
                $('#Permisos').val(aIDs.toString());
            });
        });
    </script>
@endsection
