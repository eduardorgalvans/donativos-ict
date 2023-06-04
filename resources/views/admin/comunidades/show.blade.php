@extends('layouts.intranet')

@section('titulo-pestaña')
    Causa
@endsection

@section('titulo-pagina')
    Causa
@endsection

@section('contenido')
    {{ Form::model($comunidad) }}
        {{-- admin/comunidades/causa --}}
        @include('admin.comunidades.comunidad', ['deshabilitado'=>['disabled'=>'disabled'], 'nuevo'=>false])
    {{ Form::close() }}
@endsection