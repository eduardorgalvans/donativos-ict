@extends('layouts.intranet')

@section('titulo-pestaña')
    Causas
@endsection

@section('titulo-pagina')
    Causas
@endsection

@section('contenido')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="d-block d-md-flex align-items-center mb-3">
                <!-- BEGIN enlaces -->
                <div class="d-flex">
                    <div class="btn-group">
                        @if (substr_count(session('permisos'), ',30,'))
                            {!! Html::decode(
                                link_to_route(
                                    'admin.causas.create',
                                    '<i class="fa fa-plus-circle"></i> Nuevo',
                                    [],
                                    ['class' => 'btn btn-success btn-sm'],
                                ),
                            ) !!}
                        @endif
                        <button class="btn btn-dark btn-sm" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" data-toggle="tooltip"
                            data-placement="top" title="Filtrar"><i class="fas fa-filter"></i></button>
                        {!! Html::decode(
                            link_to_route('admin.causas.imprimir', '<i class="fa fa-print"></i>', null, [
                                'class' => 'btn btn-warning btn-sm btnPrint',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'title' => 'Imprimir',
                            ]),
                        ) !!}
                        {!! Html::decode(
                            link_to_route('admin.causas.xls', '<i class="far fa-file-excel"></i>', null, [
                                'class' => 'btn btn-gray btn-sm',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'title' => 'Exporta a excel',
                            ]),
                        ) !!}
                    </div>
                </div>
                <!-- END enlaces -->
                <!-- BEGIN filtro -->
                <div class="ms-auto d-none d-lg-block">
                    {!! Form::open(['route' => 'admin.causas.filtro', 'method' => 'POST', 'role' => 'form']) !!}
                    <div class="input-group input-group-sm">
                        {!! Form::text('sBusquedaAM', $sBusquedaAM, [
                            'id' => 'sBusquedaAM',
                            'class' => 'form-control',
                            'placeholder' => 'Buscar',
                            'required',
                        ]) !!}
                        <button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="top"
                            title="Buscar"><i class="fas fa-binoculars"></i></i></button>
                        {!! Html::decode(
                            link_to_route('admin.causas.limpiar', '<i class="fas fa-ban"></i>', null, [
                                'class' => 'btn btn-warning',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'title' => 'Eliminar el filtro',
                            ]),
                        ) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
                <!-- END filtro -->
            </div>
            <div class="table-responsive">
                @include('layouts.request') {{-- layouts/request --}}
                <!-- BEGIN widget-table -->
                <table class="table table-striped table-bordered widget-table rounded" data-id="widget">
                    <thead>
                        <tr class="text-nowrap">
                            <th>ID Causa</th>
                            <th>Causa</th>
                            <th>Monto mínimo</th>
                            <th>Monto máximo</th>
                            <th>Causa activa</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($oRegistros as $causa)
                            <tr>
                                <td>{{ $causa->id }}</td>
                                <td>{{ $causa->n_causa }}</td>
                                <td>${{ number_format($causa->minimo, 2) }}</td>
                                <td>${{ number_format($causa->maximo, 2) }}</td>
                                <td>
                                    @if ($causa->activo == 1)
                                        <span class="badge bg-success">Activa</span>
                                    @else
                                        <span class="badge bg-danger">Inactiva</span>
                                    @endif
                                </td>
                                <td>

                                    {!! Html::decode(
                                        link_to_route('admin.causas.show', '<i class="fa fa-search"></i>', [$causa->id], ['class' => 'text-primary']),
                                    ) !!}
                                    &nbsp;
                                    {!! Html::decode(
                                        link_to_route('admin.causas.edit', '<i class="fa fa-pencil-alt"></i>', [$causa->id], ['class' => 'text-blue']),
                                    ) !!}
                                    &nbsp;
                                    <a href="#" class="borrar-causa text-danger" data-id="{{ $causa->id }}"
                                        data-causa="{{ $causa->n_causa }}">
                                        <i class="fa fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- END widget-table -->
            </div>
            <div class="row mb-10px">
                <div class="col">
                    @if ($sPaginaAM != 0)
                        {!! $oRegistros->links('layouts.pagination', ['sPagina' => $sPaginaAM]) !!}
                    @else
                        @include('layouts.pagination', ['sPagina' => $sPaginaAM]) {{-- layouts/pagination --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Filtros</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            {!! Form::open(['route' => 'admin.causas.filtro', 'method' => 'POST', 'role' => 'form']) !!}
            @include('admin.causas.forms.formFiltro') {{-- admin/causas/forms/formFiltro --}}
            {!! Form::submit('Filtrar', ['class' => 'btn btn-primary w-100px me-5px']) !!}
            {!! Form::close() !!}
        </div>
    </div>
    <!-- END Offcanvas -->

    {{ Form::open(['route' => ['admin.causas.destroy', '#1'], 'method' => 'DELETE', 'id' => 'frm-borrar']) }}
    <input type="hidden" name="id">
    {{ Form::close() }}
@endsection
@section('js')
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.borrar-causa').on('click', function() {
                swal({
                    title: '¡Atención!',
                    text: '¿Desea borrar la causa [' + $(this).data('causa') + ']?',
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
        });
    </script>
@endsection
