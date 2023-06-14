@extends('layouts.intranet')

@section('titulo-pestaña')
    Causa
@endsection

@section('titulo-pagina')
    Causa
@endsection

@section('contenido')
    {{ Form::open(['route'=>'admin.causas.store']) }}
        {{-- admin/causas/causa --}}
        @include('admin.causas.causa', ['deshabilitado'=>[], 'nuevo'=>true])
    {{ Form::close() }}
@endsection