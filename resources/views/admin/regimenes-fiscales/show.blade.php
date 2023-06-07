@extends('layouts.intranet')

@section('titulo-pestaña')
    Regimen fiscal
@endsection

@section('titulo-pagina')
    Regimen fiscal
@endsection

@section('contenido')
    {{ Form::model($regimen) }}
        {{-- admin/regimenes-fiscales/regimen-fiscal --}}
        @include('admin.regimenes-fiscales.regimen-fiscal', ['deshabilitado'=>['disabled'=>'disabled'], 'nuevo'=>false])
    {{ Form::close() }}
@endsection