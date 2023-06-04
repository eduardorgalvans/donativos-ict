
<div class="col-12">
    @include('layouts.request') {{-- layouts/request --}}
</div>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Detalles de la comunidad</h4>
    </div>
    <div class="panel-body">
        <div class="row mb-3">
                {{ Form::label('n_comunidad', 'Comunidad:', ['class'=>'col-sm-2 col-form-label']) }}
                <div class="col-sm-10">
                    {{ Form::text('n_comunidad', null, ['class'=>'form-control', 'required'=>'required' , 'maxlength'=>50] + $deshabilitado) }}
                </div>
        </div>
    </div>
    <div class="panel-footer text-end">
        {!! Html::decode(link_to_route('admin.comunidades.index', 'Cerrar', [], ['class'=>'btn btn-outline-primary'])) !!}

        @if (!$deshabilitado)
            {{ Form::submit('Guardar', ['class'=>'btn btn-primary']) }}
        @endif
    </div>
</div>

@if (!$deshabilitado)
    @section('js')
    @endsection
@endif