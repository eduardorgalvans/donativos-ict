@extends('layouts.intranet')

@section('titulo-pestaña')
    Usuario
@endsection

@section('titulo-pagina')
    Usuario
@endsection

@section('contenido')
    {{ Form::model($oUsuario, ['route'=>['admin.usuarios.update', $oUsuario->id], 'method'=>'PUT']) }}
        {{-- admin/usuarios/usuario --}}
        @include('admin.usuarios.usuario', ['deshabilitado'=>[], 'nuevo'=>false])
    {{ Form::close() }}
@endsection