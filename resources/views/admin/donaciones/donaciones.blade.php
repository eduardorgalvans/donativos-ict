{{-- @section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection --}}

<div class="col-12">
    @include('layouts.request') {{-- layouts/request --}}
</div>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Detalles de la donacion</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-3">
            <div class="col-md-4 col-sm-12">
                <strong>
                    <h5>ID de la causa:</h5>
                </strong>
                {{ $donacion->id_causa }}
            </div>
            <div class="col-md-4 col-sm-12">
                <strong>
                    <h5>Causa:</h5>
                </strong>
                {{ $donacion->n_causa }}
            </div>
            <div class="col-md-4 col-sm-12">
                <strong>
                    <h5>Total recaudado:</h5>
                </strong>
                ${{ number_format($donacion->donaciones, 2) }}
            </div>
            <div class="col-md-4 col-sm-12">
                <strong>
                    <h5>Total donaciones:</h5>
                </strong>
                {{-- TODO:agragar el count de donadores --}}
                {{-- ${{ number_format($donacion->donaciones, 2) }} --}}
            </div>
        </div>
        <div class="row">
            <hr>
            <h5>Donaciones por comunidad</h5>
            <table class="table table-striped table-bordered widget-table rounded p-3" data-id="widget">
                <thead>
                    <tr class="text-nowrap">
                        <th>Comunidad</th>
                        <th>Donaciones</th>
                        {{-- <th>No. de donaciones</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($donacionPorComunidades as $donacion)
                        <tr>
                            <td>{{ $donacion->n_comunidad }}</td>
                            <td>{{ number_format($donacion->donaciones, 2) }}</td>
                                {{-- Total de donaciones por comunidad --}}
                            {{-- <td>{{ number_format($donacion->donaciones, 2) }}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-footer text-end">
            {!! Html::decode(link_to_route('admin.donaciones.index', 'Cerrar', [], ['class' => 'btn btn-outline-primary'])) !!}
        </div>
    </div>
