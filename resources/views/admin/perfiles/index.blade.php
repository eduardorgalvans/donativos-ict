@extends('layouts.intranet')

@section('titulo-pestaña')
    Perfiles
@endsection

@section('titulo-pagina')
    Perfiles
@endsection

@section('css')
    <link href="{{ asset('assets/plugins/jstree/themes/default/style.min.css') }}" rel="stylesheet">
    <style type="text/css">
        .jstree .jstree-container-ul .jstree-node.jstree-open .jstree-anchor {
            color: inherit !important;
        }
    </style>
@endsection

@section('contenido')
    <div class="col-12">
        @include('layouts.request') {{-- layouts/request --}}
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.perfiles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Perfil
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Listado</h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand">
                            <i class="fa fa-expand"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse">
                            <i class="fa fa-minus"></i>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Activo</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($oPerfiles as $oPerfil)
                                        <tr>
                                            <td>{{ $oPerfil->id }}</td>
                                            <td>{{ $oPerfil->Nombre }}</td>
                                            <td>
                                                @if ($oPerfil->Activo)
                                                    <i class="fa fa-check-square text-green"></i>
                                                @else
                                                    <i class="far fa-square text-danger"></i>
                                                @endif
                                            </td>
                                            <td>
                                                {!! Html::decode(link_to_route('admin.perfiles.show', '<i class="fa fa-search"></i>', [$oPerfil->id], ['class'=>'text-primary'])) !!}
                                                &nbsp;
                                                {!! Html::decode(link_to_route('admin.perfiles.edit', '<i class="fa fa-pencil-alt"></i>', [$oPerfil->id], ['class'=>'text-blue'])) !!}
                                                &nbsp;
                                                <a href="#" id="borrar-perfil-{{ $oPerfil->id }}" class="borrar-perfil text-danger" data-id="{{ $oPerfil->id }}" data-perfil="{{ $oPerfil->Nombre }}">
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
        </div>
    </div>
    {{ Form::open(['route'=>['admin.perfiles.destroy', '#1'], 'method'=>'DELETE', 'id'=>'frm-borrar']) }}
        <input type="hidden" name="id-perfil">
    {{ Form::close() }}
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.borrar-perfil').on('click', function(){
                swal({
                    title: '¡Atención!',
                    text: '¿Desea borrar el perfil [' + $(this).data('perfil') + ']?',
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