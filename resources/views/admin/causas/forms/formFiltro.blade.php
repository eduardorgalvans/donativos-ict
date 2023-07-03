                        <div class="mb-3">
                            {!! Form::label( 'sFiltroOrdenAM', 'Orden :', ["class"=>"form-label",]) !!}
                            {!! Form::select( 'sFiltroOrdenAM', $aOrden, $sFiltroOrdenAM, [ 'class'=>'form-control form-select default-select2', 'placeholder' => 'Seleccione...', ] ) !!}
                        </div>
                        <hr>
