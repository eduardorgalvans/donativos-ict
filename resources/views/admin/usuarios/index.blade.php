@extends('layouts.intranet')

@section('titulo-pestaña')
    Usuarios
@endsection

@section('titulo-pagina')
    Usuarios
@endsection

@section('contenido')
    <div class="panel">
        <div class="panel-body">
            <div class="col-12">
                @include('layouts.request') {{-- layouts/request --}}
            </div>

            <div class="row my-3">
                <div class="col-12">
                    {!! Html::decode(link_to_route('admin.usuarios.create', '<i class="fa fa-plus"></i> Usuario', [], ['class'=>'btn btn-primary'])) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID Persona</th>
                                <th>Usuario</th>
                                <th>Nombre</th>
                                <th>Correo-e</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($oUsuarios as $oUsuario)
                                <tr>
                                    <td>{{ $oUsuario->TblDGP_id }}</td>
                                    <td>{{ $oUsuario->username }}</td>
                                    <td>{{ optional($oUsuario->persona)->NombreCompleto }}</td>
                                    <td>{{ $oUsuario->email }}</td>
                                    <td>
                                        {!! Html::decode(link_to_route('admin.usuarios.show', '<i class="fa fa-search"></i>', [$oUsuario->id], ['class'=>'text-primary'])) !!}
                                        &nbsp;
                                        {!! Html::decode(link_to_route('admin.usuarios.edit', '<i class="fa fa-pencil-alt"></i>', [$oUsuario->id], ['class'=>'text-blue'])) !!}
                                        &nbsp;
                                        <a href="#" class="borrar-usuario text-danger" data-id="{{ $oUsuario->id }}" data-usuario="{{ $oUsuario->username }}">
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
    {{ Form::open(['route'=>['admin.usuarios.destroy', '#1'], 'method'=>'DELETE', 'id'=>'frm-borrar']) }}
        <input type="hidden" name="id-usuario">
    {{ Form::close() }}
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.borrar-usuario').on('click', function(){
                swal({
                    title: '¡Atención!',
                    text: '¿Desea borrar el usuario [' + $(this).data('usuario') + ']?',
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