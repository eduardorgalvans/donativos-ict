@extends('layouts.intranet')

@section('titulo-pestaña')
    Causa
@endsection

@section('titulo-pagina')
    Causa
@endsection

@section('contenido')
    {{ Form::model($comunidad, ['route'=>['admin.comunidades.update', $comunidad->id], 'method'=>'PUT']) }}
        {{-- admin/comunidades/comunidad --}}
        @include('admin.comunidades.comunidad', ['deshabilitado'=>[], 'nuevo'=>false])
    {{ Form::close() }}
@endsection