@extends('layouts.intranet')

@section('titulo-pestaña')
    Usuario
@endsection

@section('titulo-pagina')
    Usuario
@endsection

@section('contenido')
    {{ Form::open(['route'=>'admin.usuarios.store']) }}
        {{-- admin/usuarios/usuario --}}
        @include('admin.usuarios.usuario', ['deshabilitado'=>[], 'nuevo'=>true])
    {{ Form::close() }}
@endsection