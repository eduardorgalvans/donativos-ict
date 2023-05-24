                        <div>
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">            
                                    <div class="d-block d-md-flex align-items-center mb-3">
                                        <!-- BEGIN enlaces -->
                                        <div class="d-flex">
                                            <div class="btn-group">
                                                @if ( substr_count( session('permisos'), ',30,' ) )
                                                    <livewire:configuraciones.permanentes-crear />
                                                @endif
                                                <button class="btn btn-dark btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="fas fa-filter"></i></button>
                                                {!! Html::decode( link_to_route( 'admin.permanentes.imprimir', '<i class="fa fa-print"></i>', null, [ "class"=>"btn btn-warning btn-sm btnPrint", "data-toggle"=>"tooltip", "data-placement"=>"top", "title"=>"Imprimir", ] ) ) !!}
                                                {!! Html::decode( link_to_route( 'admin.permanentes.xls', '<i class="far fa-file-excel"></i>', null, [ "class"=>"btn btn-gray btn-sm", "data-toggle"=>"tooltip", "data-placement"=>"top", "title"=>"Exporta a excel", ] ) ) !!}
                                            </div>
                                        </div>
                                        <!-- END enlaces -->
                                        <!-- BEGIN filtro -->
                                        <div class="ms-auto d-none d-lg-block">
                                            <div class="input-group input-group-sm">
                                                {!! Form::text('sBusquedaAVP', ( $sBusquedaAVP ?? '' ), [ 'id'=>'sBusquedaAVP', 'class'=>'form-control', 'placeholder'=>'Buscar', 'wire:model.debounce'=>'search', 'wire:keydown'=>'emitTable' ] ) !!}
                                                <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Filtrar"><i class="fas fa-filter"></i></button>
                                                {{--
                                                    {!! Html::decode( link_to_route('admin.permanentes.limpiar', '<i class="fas fa-ban"></i>', null, ["class"=>"btn btn-warning", "data-toggle"=>"tooltip", "data-placement"=>"top", "title"=>"Eliminar el filtro" ]) ) !!}
                                                --}}
                                            </div>
                                            {{-- 
                                                {!! Form::open(["route"=>'admin.permanentes.filtro', "method"=>"POST", "role"=>"form" ]) !!}
                                                {!! Form::close() !!}
                                            --}}
                                        </div>
                                        <!-- END filtro -->
                                    </div>
                                    <div class="table-responsive">
                                        <livewire:configuraciones.permanentes-tabla />
                                    </div>
                                    <div class="row mb-10px">
                                        <div class="col">
                                            {{--
                                                @if ( $sPaginaAVP != 0 )
                                                    {!! $oRegistros->links( 'layouts.pagination', [ 'sPagina' => $sPaginaAVP ] ) !!}
                                                @else
                                                    @include( 'layouts.pagination', [ 'sPagina' => $sPaginaAVP ] ) {{-- layouts/pagination - -}}
                                                @endif
                                            --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
