@extends('layouts.intranet')

@section('titulo-pestaña')
    Causa
@endsection

@section('titulo-pagina')
    Causa
@endsection

@section('contenido')
    {{ Form::model($causa, ['route'=>['admin.causas.update', $causa->id], 'method'=>'PUT']) }}
        {{-- admin/causas/causa --}}
        @include('admin.causas.causa', ['deshabilitado'=>[], 'nuevo'=>false])
    {{ Form::close() }}
@endsection