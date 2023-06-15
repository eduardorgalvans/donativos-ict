@extends('layouts.intranet')

@section('titulo-pestaña')
    Doncaiones
@endsection

@section('titulo-pagina')
    Donaciones
@endsection

@section('contenido')
    {{-- {{ Form::model($donacion) }} --}}
    {{-- admin/donaciones/donaciones --}}
    @include('admin.donaciones.donaciones')
    {{-- {{ Form::close() }} --}}
@endsection
