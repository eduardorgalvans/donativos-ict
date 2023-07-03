                        <div class="mb-3">
                            {!! Form::label('sFiltroCausaAM', 'Causa :', ['class' => 'form-label']) !!}
                            {!! Form::select('sFiltroCausaAM', $aCausa, $sFiltroCausaAM, [
                                'class' => 'form-control form-select default-select2',
                                'placeholder' => 'Seleccione...',
                            ]) !!}
                        </div>
                        <div class="mb-3">
                            {!! Form::label('sFiltroComunidadAM', 'Comunidad :', ['class' => 'form-label']) !!}
                            {!! Form::select('sFiltroComunidadAM', $aComunidad, $sFiltroComunidadAM, [
                                'class' => 'form-control form-select default-select2',
                                'placeholder' => 'Seleccione...',
                            ]) !!}
                        </div>
                        <div class="mb-3">
                            {!! Form::label('sFiltroOrdenAM', 'Orden:', ['class' => 'form-label']) !!}
                            {!! Form::select('sFiltroOrdenAM', $aOrden, $sFiltroOrdenAM, [
                                'class' => 'form-control form-select default-select2',
                                'placeholder' => 'Seleccione...',
                            ]) !!}
                        </div>
                        <div class="mb-3">
                            {!! Form::label('sFiltroFechaIncAM', 'Fecha inicio :', ['class' => 'form-label']) !!}
                            {!! Form::date('sFiltroFechaIncAM', $sFiltroFechaIncAM, [
                                'class' => 'form-control ',
                            ]) !!}
                        </div>
                        <div class="mb-3">
                            {!! Form::label('sFiltroFechaFinAM', 'Fecha Fin :', ['class' => 'form-label']) !!}
                            {!! Form::date('sFiltroFechaFinAM', $sFiltroFechaFinAM, [
                                'class' => 'form-control ',
                            ]) !!}
                        </div>
                        <hr>
