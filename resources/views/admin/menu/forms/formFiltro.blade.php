
                        <div class="mb-3">
                            {!! Form::label( 'sFiltroPadreAM', 'Padres :', [ "class"=>"form-label", ] ) !!}
                            {!! Form::select( 'sFiltroPadreAM', $aPadres, $sFiltroPadreAM, [ 'class'=>'form-control form-select default-select2', 'placeholder' => 'Seleccione...', ] ) !!}
                        </div>
                        <div class="mb-3">
                            {!! Form::label( 'sFiltroOrdenAM', 'Orden :', ["class"=>"form-label",]) !!}
                            {!! Form::select( 'sFiltroOrdenAM', $aOrden, $sFiltroOrdenAM, [ 'class'=>'form-control form-select default-select2', 'placeholder' => 'Seleccione...', ] ) !!}
                        </div>
                        <hr>
