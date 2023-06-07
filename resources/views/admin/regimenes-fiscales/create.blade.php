@extends('layouts.intranet')

@section('titulo-pestaña')
    Regimenes fiscales
@endsection

@section('titulo-pagina')
    Regimenes fiscales
@endsection

@section('contenido')
    {{ Form::open(['route'=>'admin.regimenes-fiscales.store']) }}
        {{-- admin/regimenes-fiscales/regimen-fiscal --}}
        @include('admin.regimenes-fiscales.regimen-fiscal', ['deshabilitado'=>[], 'nuevo'=>true])
    {{ Form::close() }}
@endsection