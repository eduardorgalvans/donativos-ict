<div class="col-12">
    @include('layouts.request') {{-- layouts/request --}}
</div>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Detalles del regimen</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-3">
            {{ Form::label('id_regimen', 'ID regimen fiscal:', ['class' => 'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {{ Form::text(
                    'id_regimen',
                    null,
                    ['class' => 'form-control', 'required' => 'required', 'maxlength' => 11, 'pattern' => '[0-9]'] +
                        ($nuevo == false ? ['disabled' => 'disabled'] : []),
                ) }}
            </div>
        </div>
        <div class="row mb-3">
            {{ Form::label('n_regimen', 'Regimen:', ['class' => 'col-sm-2 col-form-label']) }}
            <div class="col-sm-10">
                {{ Form::text('n_regimen', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => 255] + $deshabilitado) }}
            </div>
        </div>
    </div>
    <div class="panel-footer text-end">
        {!! Html::decode(
            link_to_route('admin.regimenes-fiscales.index', 'Cerrar', [], ['class' => 'btn btn-outline-primary']),
        ) !!}

        @if (!$deshabilitado)
            {{ Form::submit('Guardar', ['class' => 'btn btn-primary']) }}
        @endif
    </div>
</div>

@if (!$deshabilitado)
    @section('js')
    @endsection
@endif
