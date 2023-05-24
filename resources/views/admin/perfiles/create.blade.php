@extends('layouts.intranet')

@section('titulo-pestaña')
    Perfil nuevo
@endsection

@section('titulo-pagina')
    Perfil nuevo
@endsection

@section('contenido')
    {{ Form::open(['route'=>'admin.perfiles.store']) }}
        {{-- admin/perfiles/perfil --}}
        @include('admin.perfiles.perfil', ['deshabilitado'=>[], 'nuevo'=>true])
    {{ Form::close() }}
@endsection