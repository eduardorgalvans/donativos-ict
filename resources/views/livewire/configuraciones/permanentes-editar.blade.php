<div>
    {!! Html::decode( link_to( '#modal-dialog-edit-'.( $oRegistro->id ?? 0 ), '<i class="fas fa-pencil-alt" aria-hidden="true"></i>', [ "class"=>"text-success", "data-bs-toggle"=>"modal", ] ) ) !!}
    <!-- BEGIN modal -->
    <div wire:ignore.self class="modal fade" id="modal-dialog-edit-{{ ( $oRegistro->id ?? 0 ) }}" aria-hidden="true" style="{{ ( ! $isVisible ) ? 'display: none;' : '' }}">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(["url"=>'', "wire:submit.prevent"=>"save", "class"=>"form-horizontal", "role"=>"form" ]) !!}
                    <div class="modal-header">
                        <h4 class="modal-title">Editar variable {{ ( $oRegistro->id ?? 0 ) }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3 {!! ($errors->has('Variable')) ? "is-invalid" : "" !!}">
                            {{ Form::label('Variable', 'Variable :', ["class"=>"form-label col-form-label col-md-2"] ) }}
                            <div class="col-md-10">
                                {{ Form::text( 'Variable', null, [ 'class'=>'form-control', 'wire:model'=>'oRegistro.Variable', ] ) }}
                                @error('Variable')
                                    <span class="badge bg-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 {!! ($errors->has('Valor')) ? "is-invalid" : "" !!}">
                            {{ Form::label( 'Valor', 'Valor :', ["class"=>"form-label col-form-label col-md-2"] ) }}
                            <div class="col-md-10">
                                {{ Form::text('Valor', null, [ 'class'=>'form-control', 'wire:model'=>'oRegistro.Valor', ] ) }}
                                @error('Valor')
                                    <span class="badge bg-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 {!! ($errors->has('Tipo')) ? "is-invalid" : "" !!}">
                            {{ Form::label( 'Tipo', 'Tipo :', ["class"=>"form-label col-form-label col-md-2"] ) }}
                            <div class="col-md-10">
                                {{ Form::select( 'Tipo', $aType, null, ['class'=>'form-control form-select default-select2', 'wire:model'=>'oRegistro.Tipo', 'placeholder' => 'Seleccione...'] ) }}
                                @error('Tipo')
                                    <span class="badge bg-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
                        {!! Form::submit( 'Guardar', [ 'class'=>'btn btn-success w-100px me-5px', 'wire:loading.remove', 'wire:target'=>'save' ] ) !!}
                        <span wire:loading wire:target='save' class="btn btn-default w-100px me-5px" >Cargando...</span>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- END modal -->
    <script>
        window.addEventListener('close-modal-permanentes-edit-{{ $oRegistro->id }}', event => {
            $( "#modal-dialog-edit-{{ ( $oRegistro->id ?? 0 ) }}" ).modal( "hide" );
        })
    </script>

</div>
