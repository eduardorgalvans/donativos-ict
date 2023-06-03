{{-- @section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection --}}

<div class="col-12">
    @include('layouts.request') {{-- layouts/request --}}
</div>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Detalles de la causa</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-3">
                {{ Form::label('n_causa', 'Causa:', ['class'=>'col-sm-2 col-form-label']) }}
                <div class="col-sm-10">
                    {{ Form::text('n_causa', null, ['class'=>'form-control', 'required'=>'required'] + $deshabilitado) }}
                </div>
        </div>
        <div class="row mb-3">
            {{ Form::label('minimo', 'Mínimo:', ['class'=>'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {{ Form::number('minimo', null, ['class'=>'form-control', 'required'=>'required'] + $deshabilitado) }}
            </div>
        </div>
        <div class="row mb-3">
            {{ Form::label('maximo', 'Máximo:', ['class'=>'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {{ Form::number('maximo', null, ['class'=>'form-control', 'required'=>'required'] + $deshabilitado) }}
            </div>
        </div>
        <div class="row mb-3">
            {{ Form::label('activo', 'Activo:', ['class'=>'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {{ Form::checkbox('activo', 1, null, ['class'=>'form-check-input', ] + $deshabilitado) }}
            </div>
        </div>
    </div>
    <div class="panel-footer text-end">
        {!! Html::decode(link_to_route('admin.causas.index', 'Cerrar', [], ['class'=>'btn btn-outline-primary'])) !!}

        @if (!$deshabilitado)
            {{ Form::submit('Guardar', ['class'=>'btn btn-primary']) }}
        @endif
    </div>
</div>

@if (!$deshabilitado)
    @section('js')
    @endsection
@endif