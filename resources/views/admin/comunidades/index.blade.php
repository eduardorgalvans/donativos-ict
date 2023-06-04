@extends('layouts.intranet')

@section('titulo-pestaña')
    Comunidades
@endsection

@section('titulo-pagina')
    Comunidades
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
                        link_to_route('admin.comunidades.create', '<i class="fa fa-plus"></i> Comunidad', [], ['class' => 'btn btn-primary']),
                    ) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Comunidad</th>
                                <th>Comunidad</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comunidades as $comunidad)
                                <tr>
                                    <td>{{ $comunidad->id_comunidad }}</td>
                                    <td>{{ $comunidad->n_comunidad }}</td>
                                    <td>

                                        {!! Html::decode(
                                            link_to_route('admin.comunidades.show', '<i class="fa fa-search"></i>', [$comunidad->id_comunidad], ['class' => 'text-primary']),
                                        ) !!}
                                        &nbsp;
                                        {!! Html::decode(
                                            link_to_route(
                                                'admin.comunidades.edit',
                                                '<i class="fa fa-pencil-alt"></i>',
                                                [$comunidad->id_comunidad],
                                                ['class' => 'text-blue'],
                                            ),
                                        ) !!}
                                        &nbsp;
                                        <a href="#" class="borrar-comunidad text-danger" data-id="{{ $comunidad->id_comunidad }}" data-comunidad="{{ $comunidad->n_comunidad }}">
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

    {{ Form::open(['route'=>['admin.comunidades.destroy', '#1'], 'method'=>'DELETE', 'id'=>'frm-borrar']) }}
        <input type="hidden" name="id-comunidad">
    {{ Form::close() }}
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.borrar-comunidad').on('click', function() {
                swal({
                    title: '¡Atención!',
                    text: '¿Desea borrar la comunidad [' + $(this).data('comunidad') + ']?',
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
