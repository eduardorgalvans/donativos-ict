@extends('layouts.intranet')

@section('titulo-pestaña')
    Causas
@endsection

@section('titulo-pagina')
    Causas
@endsection

@section('contenido')
    <div class="panel">
        <div class="panel-body">
            <div class="col-12">
                @include('layouts.request') {{-- layouts/request --}}
            </div>

            <div class="row my-3">
                <div class="col-12">
                    {!! Html::decode(
                        link_to_route('admin.causas.create', '<i class="fa fa-plus"></i> Causa', [], ['class' => 'btn btn-primary']),
                    ) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Causa</th>
                                <th>Causa</th>
                                <th>Monto mínimo</th>
                                <th>Monto máximo</th>
                                <th>Causa activa</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($causas as $causa)
                                <tr>
                                    <td>{{ $causa->id_causa }}</td>
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
                                            link_to_route('admin.causas.show', '<i class="fa fa-search"></i>', [$causa->id_causa], ['class' => 'text-primary']),
                                        ) !!}
                                        &nbsp;
                                        {!! Html::decode(
                                            link_to_route(
                                                'admin.causas.edit',
                                                '<i class="fa fa-pencil-alt"></i>',
                                                [$causa->id_causa],
                                                ['class' => 'text-blue'],
                                            ),
                                        ) !!}
                                        &nbsp;
                                        <a href="#" class="borrar-causa text-danger" data-id="{{ $causa->id_causa }}" data-causa="{{ $causa->n_causa }}">
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

    {{ Form::open(['route'=>['admin.causas.destroy', '#1'], 'method'=>'DELETE', 'id'=>'frm-borrar']) }}
        <input type="hidden" name="id-causa">
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
