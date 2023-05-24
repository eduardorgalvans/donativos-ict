@extends('layouts.intranet')

@section('titulo-pestaña')
    Ver módulo
@endsection

@section('titulo-pagina')
    Ver módulo
@endsection

@section('contenido')
    {{ Form::model($oModulo) }}
        {{-- admin/modulos/modulo --}}
        @include('admin.modulos.modulo', ['deshabilitado'=>['disabled'=>'disabled'], 'nuevo'=>false])
    {{ Form::close() }}
@endsection