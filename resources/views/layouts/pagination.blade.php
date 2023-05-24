                    <div class="d-block d-md-flex align-items-center mb-3">
                        <!-- BEGIN filter -->
                        <div class="d-flex">
                            <!-- BEGIN dropdown -->
                            <div class="dropdown me-2">
                                <a href="#" class="btn btn-white btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ ( $sPagina == 0 ) ? 'Todos' : $sPagina }} <b class="caret"></b>
                                </a>
                                <div class="dropdown-menu dropdown-menu-start" role="menu" style="">
                                    {!! Html::decode( link_to_route( Libreria::getRouteBase().'pagina', '10', ( [ "id"=>10 ]+( $aAdicional ?? [] ) ), ["class"=>"dropdown-item", ]) ) !!}
                                    {!! Html::decode( link_to_route( Libreria::getRouteBase().'pagina', '25', ( [ "id"=>25 ]+( $aAdicional ?? [] ) ), ["class"=>"dropdown-item", ]) ) !!}
                                    {!! Html::decode( link_to_route( Libreria::getRouteBase().'pagina', '50', ( [ "id"=>50 ]+( $aAdicional ?? [] ) ), ["class"=>"dropdown-item", ]) ) !!}
                                    {!! Html::decode( link_to_route( Libreria::getRouteBase().'pagina', '100', ( [ "id"=>100 ]+( $aAdicional ?? [] ) ), ["class"=>"dropdown-item", ]) ) !!}
                                    <div class="dropdown-divider"></div>
                                    {!! Html::decode( link_to_route( Libreria::getRouteBase().'pagina', 'Todos', ( [ "id"=>100 ]+( $aAdicional ?? [] ) ), ["class"=>"dropdown-item", ]) ) !!}
                                </div>
                            </div>
                            <span style="padding: 6px 0px 0px 0px;">
                                @if ( $paginator ?? false )
                                    Muestra <strong>{{ $paginator->firstItem() }}</strong> a <strong>{{ $paginator->lastItem() }}</strong> de <strong>{{ $paginator->total() }}</strong> resultados
                                @else
                                    <strong>{{ $iTotal ?? 'Todos' }}</strong> resultados
                                @endif
                            </span>

                        </div>
                        <!-- END filter -->
                        <!-- BEGIN pagination -->
                        <div class="ms-auto d-none d-lg-block">
                            @if ( isset( $paginator ) ? $paginator->hasPages() : false )
                                <div class="btn-group ">
                                    {{-- Previous Page Link --}}
                                    @if ($paginator->onFirstPage())
                                        {!! Html::decode( link_to( '#', '«', ["class"=>"btn btn-white btn-xs disabled", ] ) ) !!}
                                    @else
                                        {!! Html::decode( link_to( $paginator->previousPageUrl(), '«', ["class"=>"btn btn-white btn-xs", ] ) ) !!}
                                    @endif
                                    {{-- Pagination Elements --}}
                                    @foreach ($elements as $element)
                                        {{-- "Three Dots" Separator --}}
                                        @if (is_string($element))
                                            {!! Html::decode( link_to( '#', $element, ["class"=>"btn btn-white btn-xs disabled", ] ) ) !!}
                                        @endif

                                        {{-- Array Of Links --}}
                                        @if (is_array($element))
                                            @foreach ($element as $page => $url)
                                                @if ($page == $paginator->currentPage())
                                                    {!! Html::decode(link_to( '#', $page, ["class"=>"btn btn-white btn-xs active ", ] ) ) !!}
                                                @else
                                                    {!! Html::decode(link_to( $url, $page, ["class"=>"btn btn-white btn-xs ", ] ) ) !!}
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                    {{-- Next Page Link --}}
                                    @if ($paginator->hasMorePages())
                                        {!! Html::decode( link_to( $paginator->nextPageUrl(), '»', ["class"=>"btn btn-white btn-xs btn-sm", ] ) ) !!}
                                    @else
                                        {!! Html::decode( link_to( '#', '»', ["class"=>"btn btn-white btn-xs disabled", ] ) ) !!}
                                    @endif
                                </div>
                            @endif
                        </div>
                        <!-- END pagination -->
                    </div>


