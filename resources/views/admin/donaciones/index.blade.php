@extends('layouts.intranet')

@section('titulo-pestaña')
    Donaciones
@endsection

@section('titulo-pagina')
    Donaciones
    @if ($causaSeleccionada && count($oRegistros) > 0)
        - {{ $causaSeleccionada }}
    @endif
@endsection

@section('contenido')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="d-block d-md-flex align-items-center mb-3">
                <!-- BEGIN enlaces -->
                <div class="d-flex">
                    <div class="btn-group">
                        <button class="btn btn-dark btn-sm" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" data-toggle="tooltip"
                            data-placement="top" title="Filtrar"><i class="fas fa-filter"></i></button>
                        {!! Html::decode(
                            link_to_route('admin.donaciones.imprimir', '<i class="fa fa-print"></i>', null, [
                                'class' => 'btn btn-warning btn-sm btnPrint',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'title' => 'Imprimir',
                            ]),
                        ) !!}
                        {!! Html::decode(
                            link_to_route('admin.donaciones.xls', '<i class="far fa-file-excel"></i>', null, [
                                'class' => 'btn btn-gray btn-sm',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'title' => 'Exporta a excel',
                            ]),
                        ) !!}

                        @if ($sFiltroCausaAM && count($oRegistros) > 0)
                            {!! Html::decode(
                                link_to_route(
                                    'admin.donaciones.show',
                                    '<i class="fa fa-search"></i>',
                                    [$sFiltroCausaAM],
                                    [
                                        'class' => 'btn btn-info btn-sm',
                                        'data-toggle' => 'tooltip',
                                        'data-placement' => 'top',
                                        'title' => 'Mostrar detalle',
                                    ],
                                ),
                            ) !!}
                        @endif

                    </div>
                </div>
                <!-- END enlaces -->
                <!-- BEGIN filtro -->
                <div class="ms-auto d-none d-lg-block">
                    {!! Form::open(['route' => 'admin.donaciones.filtro', 'method' => 'POST', 'role' => 'form']) !!}
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
                            link_to_route('admin.donaciones.limpiar', '<i class="fas fa-ban"></i>', null, [
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
                <table style="table-layout:fixed" class="table table-striped table-bordered widget-table rounded"
                    data-id="widget">
                    <thead>
                        <tr class="text-nowrap">
                            <th style="width: 65px;">ID</th>
                            <th style="width: 200px">Causa</th>
                            <th style="width: 130px;">Referencia</th>
                            <th style="width: 90px;">Fecha</th>
                            <th style="width: 250px;">Donador</th>
                            <th style="width: 90px;">Importe</th>
                            <th style="width: 200px;">Email</th>
                            <th style="width: 100px;">Teléfono</th>
                            <th style="width: 150px;">Comunidad</th>
                            <th style="width: 80px;">Deducible</th>
                            <th style="width: 120px;">Tipo de persona</th>
                            <th style="width: 120px;">RFC</th>
                            <th style="width: 300px;">Razon Social</th>
                            <th style="width: 300px;">Regimen físcal</th>
                            <th style="width: 60px;">CP</th>
                            {{-- <th style="width: 50px;">&nbsp;</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($oRegistros as $donacion)
                            <tr>
                                <td>{{ $donacion->id }}</td>
                                <td>{{ $donacion->n_causa }}</td>
                                <td>{{ $donacion->referencia_banco }}</td>
                                <td>{{ date('d/m/Y', strtotime($donacion->fecha)) }}</td>
                                <td>{{ $donacion->nombre }} {{ $donacion->apellido }} </td>
                                <td>${{ number_format($donacion->importe, 2) }}</td>
                                <td>{{ $donacion->email }}</td>
                                <td>{{ $donacion->tel }}</td>
                                <td>{{ $donacion->n_comunidad }}</td>
                                <td>
                                    @if ($donacion->deducible == 1)
                                        Sí
                                    @else
                                        No
                                    @endif
                                </td>
                                <td>
                                    @if ($donacion->deducible == 1)
                                        @if ($donacion->tipo_persona == 'M')
                                            Moral
                                        @else
                                            Física
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $donacion->rfc }}</td>
                                <td>{{ $donacion->razon_social }}</td>
                                <td>{{ $donacion->n_regimen }}</td>
                                <td>{{ $donacion->cp_fiscal }}</td>
                                {{-- <td>
                                    {!! Html::decode(
                                        link_to_route(
                                            'admin.donaciones.show',
                                            '<i class="fa fa-search"></i>',
                                            [$donacion->id_causa],
                                            ['class' => 'text-primary'],
                                        ),
                                    ) !!}
                                </td> --}}
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
            {!! Form::open(['route' => 'admin.donaciones.filtro', 'method' => 'POST', 'role' => 'form']) !!}
            @include('admin.donaciones.forms.formFiltro') {{-- admin/donaciones/forms/formFiltro --}}
            {!! Form::submit('Filtrar', ['class' => 'btn btn-primary w-100px me-5px']) !!}
            {!! Form::close() !!}
        </div>
    </div>
    <!-- END Offcanvas -->
@endsection
@section('js')
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endsection
