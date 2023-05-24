
                        <div class="row mb-3 {!! ($errors->has('Variable')) ? "is-invalid" : "" !!}">
                            {{ Form::label('Variable', 'Variable :', ["class"=>"form-label col-form-label col-md-2"]) }}
                            <div class="col-md-10">
                                {{ Form::text('Variable', null, ['class'=>'form-control'] + ((@$disables)?['disabled'=>'disabled']:[])) }}
                                @error('Variable')
                                    <span class="badge bg-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 {!! ($errors->has('Valor')) ? "is-invalid" : "" !!}">
                            {{ Form::label('Valor', 'Valor :', ["class"=>"form-label col-form-label col-md-2"]) }}
                            <div class="col-md-10">
                                {{ Form::text('Valor', null, ['class'=>'form-control'] + ((@$disables)?['disabled'=>'disabled']:[])) }}
                                @error('Valor')
                                    <span class="badge bg-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 {!! ($errors->has('Tipo')) ? "is-invalid" : "" !!}">
                            {{ Form::label('Tipo', 'Tipo :', ["class"=>"form-label col-form-label col-md-2"]) }}
                            <div class="col-md-10">
                                {{ Form::select('Tipo', $aTipoSelect, null, ['class'=>'form-control form-select default-select2', 'placeholder' => 'Seleccione...'] + ((@$disables)?['disabled'=>'disabled']:[])) }}
                                @error('Tipo')
                                    <span class="badge bg-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
