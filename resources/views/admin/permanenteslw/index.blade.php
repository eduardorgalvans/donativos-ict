@extends('layouts.app') {{-- layouts/app --}}

@section('titulo-pestaña')
    Menús
@endsection

@section('css')
    <link href="{{ asset('/assets/plugins/jstree/themes/default/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />

@endsection

@section('breadcrumb')
                <li class="breadcrumb-item"><a href="javascript:;">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">Configuración</a></li>
                <li class="breadcrumb-item active">Variables</li>
@endsection

@section('titulo-pagina')
    Variables <small>permanentes</small>
@endsection

@section('contenido')
            <livewire:configuraciones.permanentes />
            {{--

            @foreach ($oRegistros as $oRegistro)
                <div class="dDescripcion" id="content{{ $oRegistro->id }}">
                    <h5>Descripción</h5>
                    <p>{!! $oRegistro->Descripcion !!}</p>
                </div>
            @endforeach

            <!-- BEGIN Offcanvas -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasRightLabel">Filtros</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    {!! Form::open(["route"=>'admin.permanentes.filtro', "method"=>"POST", "role"=>"form" ]) !!}
                         {{-- @include('admin.menu.forms.formFiltro')admin/menus/forms/formFiltro - -}}
                        {!! Form::submit( 'Filtrar', [ 'class'=>'btn btn-primary w-100px me-5px', ] ) !!}
                    {!! Form::close() !!}
                </div>
            </div>
            <!-- END Offcanvas --> 

            --}}

@endsection

@section('js')
    <script src="{{ asset('/assets/plugins/jstree/jstree.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/select2/js/select2.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            /*
            $(".default-select2").select2();
            // Creación del árbol de módulos.
            $('#arbol-modulos').jstree({
                'plugins': ['wholerow',  'types'],
                'core': {
                    'data': {!! $jTree ?? '' !!},
                    'multiple': false
                },
                'types' : {
                    'default': { 'icon': 'fas fa-plus-square fa-lg text-primary' },
                    'modulo':  { 'icon': 'fas fa-square fa-lg text-danger' },
                    'accion':  { 'icon': 'fas fa-cog fa-lg text-warning' },
                }
            })
            .on('ready.jstree', function(){
                // $(this).jstree('open_all');
            });

            $('.dDescripcion').hide();

            $(".eDescricion").click(function () {
                if ($('tr#' + $(this).data("href")).is(":visible")) {
                    $('tr#' + $(this).data("href")).remove();
                } else {
                    $(this).closest('tr').after('<tr id="' + $(this).data("href") + '"><td colspan="5">' + $('#' + $(this).data("href")).html() + '</td></tr>');
                }                       
            });
            */
        });
    </script>
    <i class="fas fa-square"></i>
@endsection