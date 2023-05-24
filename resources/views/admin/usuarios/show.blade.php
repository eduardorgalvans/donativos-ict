@extends('layouts.intranet')

@section('titulo-pestaña')
    Usuario
@endsection

@section('titulo-pagina')
    Usuario
@endsection

@section('contenido')
    {{ Form::model($oUsuario) }}
        {{-- admin/usuarios/usuario --}}
        @include('admin.usuarios.usuario', ['deshabilitado'=>['disabled'=>'disabled'], 'nuevo'=>false])
    {{ Form::close() }}
@endsection