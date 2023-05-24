@extends('layouts.intranet') {{-- layouts/intranet --}}

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
                <li class="breadcrumb-item active">Menús</li>
@endsection

@section('titulo-pagina')
    Variables <small>permanentes</small>
@endsection

@section('contenido')
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">            
                    <div class="d-block d-md-flex align-items-center mb-3">
                        <!-- BEGIN enlaces -->
                        <div class="d-flex">
                            <div class="btn-group">
                                @if ( substr_count( session('permisos'), ',30,' ) )
                                    {!! Html::decode( link_to_route( 'admin.permanentes.create', '<i class="fa fa-plus-circle"></i> Nuevo', null, [ "class"=>"btn btn-success btn-sm", ] ) ) !!}
                                @endif
                                <button class="btn btn-dark btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="fas fa-filter"></i></button>
                                {!! Html::decode( link_to_route( 'admin.permanentes.imprimir', '<i class="fa fa-print"></i>', null, [ "class"=>"btn btn-warning btn-sm btnPrint", "data-toggle"=>"tooltip", "data-placement"=>"top", "title"=>"Imprimir", ] ) ) !!}
                                {!! Html::decode( link_to_route( 'admin.permanentes.xls', '<i class="far fa-file-excel"></i>', null, [ "class"=>"btn btn-gray btn-sm", "data-toggle"=>"tooltip", "data-placement"=>"top", "title"=>"Exporta a excel", ] ) ) !!}
                            </div>
                        </div>
                        <!-- END enlaces -->
                        <!-- BEGIN filtro -->
                        <div class="ms-auto d-none d-lg-block">
                            {!! Form::open(["route"=>'admin.permanentes.filtro', "method"=>"POST", "role"=>"form" ]) !!}
                                <div class="input-group input-group-sm">
                                    {!! Form::text('sBusquedaAVP', $sBusquedaAVP, ['id'=>'sBusquedaAVP', 'class'=>'form-control', 'placeholder'=>'Buscar', 'required'] ) !!}
                                    <button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Filtrar"><i class="fas fa-filter"></i></button>
                                    {!! Html::decode( link_to_route('admin.permanentes.limpiar', '<i class="fas fa-ban"></i>', null, ["class"=>"btn btn-warning", "data-toggle"=>"tooltip", "data-placement"=>"top", "title"=>"Eliminar el filtro" ]) ) !!}
                                </div>
                            {!! Form::close() !!}
                        </div>
                        <!-- END filtro -->
                    </div>
                    <div class="table-responsive">
                        @include( 'layouts.request' ) {{-- layouts/request --}}
                        <!-- BEGIN widget-table -->
                        <table class="table table-striped table-bordered widget-table rounded" data-id="widget">
                            <thead>
                                <tr class="text-nowrap">
                                    <th width="1%">id</th>
                                    <th>Variable</th>
                                    <th width="40%">Valor</th>
                                    <th width="10%">Tipo</th>
                                    <th width="5%"><i class="fas fa-file-alt" title="Descripción"></i></th>
                                    <th width="10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($oRegistros as $oRegistro)
                                    <tr>
                                        <td>{!! $oRegistro->id !!}</td>
                                        <td>{!! $oRegistro->Variable !!}</td>
                                        <td>
                                            <div class="text-nowrap" style="width: 8rem;">
                                                {{ ( strlen( $oRegistro->Valor ) > 45 ) ? substr( $oRegistro->Valor, 0, 45).'...' : $oRegistro->Valor }}
                                            </div>
                                        </td>
                                        <td><span class="badge bg-{{ @$aTipo[ $oRegistro->Tipo ] }}">{{ $oRegistro->Tipo }}</span></td>
                                        <td>
                                            @if ( ! empty( $oRegistro->Descripcion ) )
                                                <a href="javascript:;" class="eDescricion" data-href="content{{ $oRegistro->id }}" >
                                                    <i class="fas fa-arrow-alt-circle-down"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if ( substr_count( session('permisos'), ',29,' ) )
                                                {!! Html::decode(link_to_route('admin.permanentes.show', '<i class="fa fa-search" aria-hidden="true"></i>', ( $oRegistro->id ?? 0 ), ["class"=>"text-primary"])) !!}&nbsp;
                                            @endif
                                            @if ( substr_count( session('permisos'), ',31,' ) )
                                                {!! Html::decode(link_to_route('admin.permanentes.edit', '<i class="fas fa-pencil-alt" aria-hidden="true"></i>', ( $oRegistro->id ?? 0 ), ["class"=>"text-success"])) !!}&nbsp;
                                            @endif
                                            @if ( substr_count( session('permisos'), ',32,' ) )
                                                {!! Html::decode(link_to('#', '<i class="fa fa-trash"></i>', ["class"=>"text-danger", "onclick"=>"var bConf = confirm('¿Estás seguro de que quieres eliminar este registro?'); if(bConf){ event.preventDefault(); document.getElementById('delete-form-".( $oRegistro->id ?? 0 )."').submit(); }"  ])) !!}
                                                {!! Form::open(['route'=>['admin.permanentes.destroy', ( $oRegistro->id ?? 0 )], 'method'=>'DELETE', 'id'=>'delete-form-'.( $oRegistro->id ?? 0 ), ]) !!}
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="unread">
                                        <td colspan="8"  class="">Sin datos que mostrar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!-- END widget-table -->
                    </div>
                    <div class="row mb-10px">
                        <div class="col">
                            @if ( $sPaginaAVP != 0 )
                                {!! $oRegistros->links( 'layouts.pagination', [ 'sPagina' => $sPaginaAVP ] ) !!}
                            @else
                                @include( 'layouts.pagination', [ 'sPagina' => $sPaginaAVP ] ) {{-- layouts/pagination --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($oRegistros as $oRegistro)
                {{-- expr --}}
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
                         {{-- @include('admin.menu.forms.formFiltro')admin/menus/forms/formFiltro --}}
                        {!! Form::submit( 'Filtrar', [ 'class'=>'btn btn-primary w-100px me-5px', ] ) !!}
                    {!! Form::close() !!}
                </div>
            </div>
            <!-- END Offcanvas --> 



@endsection

@section('js')
    <script src="{{ asset('/assets/plugins/jstree/jstree.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/select2/js/select2.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $(".default-select2").select2();
            // Creación del árbol de módulos.
            $('#arbol-modulos').jstree({
                'plugins': ['wholerow', /*'checkbox',*/ 'types'],
                'core': {
                    'data': {!! $jTree !!},
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
        });
    </script>
    <i class="fas fa-square"></i>
@endsection