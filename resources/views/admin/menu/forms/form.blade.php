
                        <div class="row mb-3 {!! ($errors->has('id_Padre')) ? "is-invalid" : "" !!}">
                            {{ Form::label('id_Padre', 'Nodo Padre :', ["class"=>"form-label col-form-label col-md-2"]) }}
                            <div class="col-md-10">
                                {{ Form::select('id_Padre', $aPadres, null, ['class'=>'form-control form-select select2', 'placeholder' => 'Seleccione...'] + ((@$disables)?['disabled'=>'disabled']:[])) }}
                                @error('id_Padre')
                                    <span class="badge bg-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 {!! ($errors->has('Icono')) ? "is-invalid" : "" !!}">
                            {{ Form::label('Icono', 'Icono :', ["class"=>"form-label col-form-label col-md-2"]) }}
                            <div class="col-md-10">

                                <div class="input-group">
                                    <span class="input-group-append">
                                        <button id="convert_example_2" class="btn btn-outline-secondary" data-placement="bottom" data-icon="fas fa-home" role="iconpicker"></button>
                                    </span>
                                    {{ Form::text('Icono', null, ['Icono'=>'Icono', 'class'=>'form-control icp icp-auto', "data-placement"=>"bottomRight", 'readonly'] + ((@$disables)?['disabled'=>'disabled']:[])) }}
                                    <div class="input-group-text">
                                        <i id="sIcono" class="{!! @$oRegistro->Icono !!}" aria-hidden="true"></i>
                                    </div>
                                </div>
                                @error('Icono')
                                    <span class="badge bg-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 {!! ($errors->has('Nombre')) ? "is-invalid" : "" !!}">
                            {{ Form::label('Nombre', 'Nombre :', ["class"=>"form-label col-form-label col-md-2"]) }}
                            <div class="col-md-10">
                                {{ Form::text('Nombre', null, ['class'=>'form-control'] + ((@$disables)?['disabled'=>'disabled']:[])) }}
                                @error('Nombre')
                                    <span class="badge bg-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 {!! ($errors->has('Permiso')) ? "is-invalid" : "" !!}">
                            {{ Form::label('Permiso', 'Módulo asociado :', ["class"=>"form-label col-form-label col-md-2"]) }}
                            <div class="col-md-10">
                                {{ Form::select('Permiso', $aModulos, null, ['class'=>'form-control form-select select2'] + ((@$disables)?['disabled'=>'disabled']:[]), $aAtributosOpciones) }}
                                @error('Permiso')
                                    <span class="badge bg-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 {!! ($errors->has('Ruta')) ? "is-invalid" : "" !!}">
                            {{ Form::label('Ruta', 'Ruta :', ["class"=>"form-label col-form-label col-md-2"]) }}
                            <div class="col-md-10">
                                {{-- Form::text('Ruta', null, ['class'=>'form-control'] + ((@$disables)?['disabled'=>'disabled']:[])) --}}
                                {{ Form::select('Ruta', $aRutas, null, ['class'=>'form-control form-select select2', ] + ((@$disables)?['disabled'=>'disabled']:[])) }}
                                @error('Ruta')
                                    <span class="badge bg-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 {!! ($errors->has('Orden')) ? "is-invalid" : "" !!}">
                            {!! Form::label('Orden', 'Orden :', ["class"=>"form-label col-form-label col-md-2",]); !!}
                            <div class="col-md-10">
                                {!! Form::number('Orden', null, ['class'=>'form-control',] + ((@$disables)?['disabled'=>'disabled']:[])); !!}
                                @error('Orden')
                                    <span class="badge bg-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-md-10 offset-md-2">
                                <div class="form-check">
                                    {{ Form::checkbox('Tipo', '1', ( @$oRegistro->Tipo == 1 )?TRUE:FALSE, [ 'id'=>'Tipo', 'class'=>'form-check-input',] + ((@$disables)?['disabled'=>'disabled']:[])) }}
                                    <label class="form-check-label" for="Tipo">Contiene más elementos</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-10 offset-md-2">
                                <div class="form-check">
                                    {{ Form::checkbox('Estatus', '1', ( @$oRegistro->Estatus )?TRUE:FALSE, [ 'id'=>'Estatus', 'class'=>'form-check-input',] + ((@$disables)?['disabled'=>'disabled']:[])) }}
                                    <label class="form-check-label" for="Estatus">Está activo el elemento</label>
                                </div>
                            </div>
                        </div>

