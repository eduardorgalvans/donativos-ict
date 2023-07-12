@extends('layouts.intranet')

@section('titulo-pestaña')
    Donadores
@endsection

@section('titulo-pagina')
    Donadores
@endsection

@section('contenido')
    <div class="panel">
        <div class="panel-body">
            <div class="col-12">
                @include('layouts.request') {{-- layouts/request --}}
            </div>

            <div class="row">
                <div class="col-12">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Importe</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($donadores as $donador)
                                <tr>
                                    <td>{{ $donador->id }}</td>
                                    <td>{{ $donador->nombre }}</td>
                                    <td>{{ $donador->apellido }}</td>
                                    <td>{{ $donador->email }}</td>
                                    <td>{{ $donador->tel }}</td>
                                    <td>${{ number_format($donador->importe, 2) }}</td>
                                    <td>
                                        &nbsp;
                                        <a href="#" class="borrar-donador text-danger" data-id="{{ $donador->id }}"
                                            data-donador="{{ $donador->id }}">
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

    {{ Form::open(['route' => ['admin.donadores.destroy', '#1'], 'method' => 'DELETE', 'id' => 'frm-borrar']) }}
    <input type="hidden" name="id">
    {{ Form::close() }}
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.borrar-donador').on('click', function() {
                swal({
                    title: '¡Atención!',
                    text: '¿Desea borrar el donador [' + $(this).data('donador') + ']?',
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
