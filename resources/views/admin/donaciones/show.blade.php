@extends('layouts.intranet')

@section('titulo-pestaña')
    Causa
@endsection

@section('titulo-pagina')
    Causa
@endsection

@section('contenido')
    {{ Form::model($causa) }}
        {{-- admin/causas/causa --}}
        @include('admin.causas.causa', ['deshabilitado'=>['disabled'=>'disabled'], 'nuevo'=>false])
    {{ Form::close() }}
@endsection