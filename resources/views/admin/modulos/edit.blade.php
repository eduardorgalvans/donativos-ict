@extends('layouts.intranet')

@section('titulo-pestaña')
    Editar módulo
@endsection

@section('titulo-pagina')
    Editar módulo
@endsection

@section('contenido')
    {{ Form::model($oModulo, ['route'=>['admin.modulos.update', $oModulo->id], 'method'=>'PUT']) }}
        {{-- admin/modulos/modulo --}}
        @include('admin.modulos.modulo', ['deshabilitado'=>[], 'nuevo'=>false])
    {{ Form::close() }}
@endsection