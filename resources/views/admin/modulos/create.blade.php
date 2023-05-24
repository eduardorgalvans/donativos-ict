@extends('layouts.intranet')

@section('titulo-pestaña')
    Módulo nuevo
@endsection

@section('titulo-pagina')
    Módulo nuevo
@endsection

@section('contenido')
    {{ Form::open(['route'=>'admin.modulos.store']) }}
        {{-- admin/modulos/modulo --}}
        @include('admin.modulos.modulo', ['deshabilitado'=>[], 'nuevo'=>true])
    {{ Form::close() }}
@endsection