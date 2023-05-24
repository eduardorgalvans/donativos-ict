@extends('layouts.intranet')

@section('titulo-pestaña')
    Editar módulo
@endsection

@section('titulo-pagina')
    Editar módulo
@endsection

@section('contenido')
    {{ Form::model($oPerfil, ['route'=>['admin.perfiles.update', $oPerfil->id], 'method'=>'PUT']) }}
        {{-- admin/perfiles/perfil --}}
        @include('admin.perfiles.perfil', ['deshabilitado'=>[], 'nuevo'=>false])
    {{ Form::close() }}
@endsection