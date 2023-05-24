@extends('layouts.intranet')

@section('titulo-pestaña')
    Ver módulo
@endsection

@section('titulo-pagina')
    Ver módulo
@endsection

@section('contenido')
    {{ Form::model($oPerfil) }}
        {{-- admin/perfiles/perfil --}}
        @include('admin.perfiles.perfil', ['deshabilitado'=>['disabled'=>'disabled'], 'nuevo'=>false])
    {{ Form::close() }}
@endsection