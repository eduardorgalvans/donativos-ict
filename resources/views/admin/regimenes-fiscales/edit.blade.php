@extends('layouts.intranet')

@section('titulo-pestaña')
    Regimenes fiscales
@endsection

@section('titulo-pagina')
    Regimenes fiscales
@endsection

@section('contenido')
    {{ Form::model($regimen, ['route'=>['admin.regimenes-fiscales.update', $regimen->id_regimen], 'method'=>'PUT']) }}
        {{-- admin/regimenes-fiscales/regimen-fiscal --}}
        @include('admin.regimenes-fiscales.regimen-fiscal', ['deshabilitado'=>[], 'nuevo'=>false])
    {{ Form::close() }}
@endsection