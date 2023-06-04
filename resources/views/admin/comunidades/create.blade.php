@extends('layouts.intranet')

@section('titulo-pestaña')
    Comunidades
@endsection

@section('titulo-pagina')
    Comunidades
@endsection

@section('contenido')
    {{ Form::open(['route'=>'admin.comunidades.store']) }}
        {{-- admin/comunidades/comunidad --}}
        @include('admin.comunidades.comunidad', ['deshabilitado'=>[], 'nuevo'=>true])
    {{ Form::close() }}
@endsection