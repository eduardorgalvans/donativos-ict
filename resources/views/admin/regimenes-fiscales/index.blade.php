@extends('layouts.intranet')

@section('titulo-pestaña')
    Regimenes fiscales
@endsection

@section('titulo-pagina')
    Regimenes fiscales
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
                        link_to_route('admin.regimenes-fiscales.create', '<i class="fa fa-plus"></i> Regimen Fiscal', [], ['class' => 'btn btn-primary']),
                    ) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Regimen</th>
                                <th>Regimen Fiscal</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($regimenes as $regimen)
                                <tr>
                                    <td>{{ $regimen->id_regimen }}</td>
                                    <td>{{ $regimen->n_regimen }}</td>
                                    <td>

                                        {!! Html::decode(
                                            link_to_route('admin.regimenes-fiscales.show', '<i class="fa fa-search"></i>', [$regimen->id_regimen], ['class' => 'text-primary']),
                                        ) !!}
                                        &nbsp;
                                        {!! Html::decode(
                                            link_to_route(
                                                'admin.regimenes-fiscales.edit',
                                                '<i class="fa fa-pencil-alt"></i>',
                                                [$regimen->id_regimen],
                                                ['class' => 'text-blue'],
                                            ),
                                        ) !!}
                                        &nbsp;
                                        <a href="#" class="borrar-regimen text-danger" data-id="{{ $regimen->id_regimen }}" data-regimen="{{ $regimen->n_regimen }}">
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

    {{ Form::open(['route'=>['admin.regimenes-fiscales.destroy', '#1'], 'method'=>'DELETE', 'id'=>'frm-borrar']) }}
        <input type="hidden" name="id-regimen">
    {{ Form::close() }}
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.borrar-regimen').on('click', function() {
                swal({
                    title: '¡Atención!',
                    text: '¿Desea borrar el regimen [' + $(this).data('regimen') + ']?',
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
