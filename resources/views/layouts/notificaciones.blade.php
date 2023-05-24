
                <div class="navbar-item dropdown">
                    <a href="#" data-bs-toggle="dropdown" class="navbar-link dropdown-toggle icon">
                        <i class="fa fa-bell"></i>
                        <span class="badge">{!! count( $oNotificaciones ) !!}</span>
                    </a>
                    <div class="dropdown-menu media-list dropdown-menu-end">
                        <div class="dropdown-header">NOTIFICACIONES ({!! count( $oNotificaciones ) !!})</div>
                        @forelse ( $oNotificaciones as $oNotificacion )
                            {{-- expr --}}
                            <a href="{!! ( ( $oNotificacion->Ruta == '#' ) ? '#' : ( Route::has( $oNotificacion->Ruta ) ? route( $oNotificacion->Ruta ) : '#No_existe_la_ruta' ) ) !!}" class="dropdown-item media">
                                <div class="media-left">
                                    <i class="{!! $oNotificacion->Icono ?? 'fa fa-bug' !!} media-object bg-gray-400"></i>
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading">{!! $oNotificacion->Nombre !!}</h6>
                                    <p>{!! $oNotificacion->Texto !!}</p>
                                    <div class="text-muted fs-10px">{!! Libreria::imprimirTiempo( date( 'Y-m-d', strtotime( $oNotificacion->updated_at ) ), date( 'H:i:s', strtotime( $oNotificacion->updated_at ) ) ) !!}</div>
                                </div>
                            </a>
                        @empty
                            {{-- empty expr --}}
                        @endforelse
                        {{-- 
                        <a href="javascript:;" class="dropdown-item media">
                            <div class="media-left">
                                <img src="../assets/img/user/user-1.jpg" class="media-object" alt="">
                                <i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading">John Smith</h6>
                                <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                                <div class="text-muted fs-10px">25 minutes ago</div>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item media">
                            <div class="media-left">
                                <img src="../assets/img/user/user-2.jpg" class="media-object" alt="">
                                <i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading">Olivia</h6>
                                <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                                <div class="text-muted fs-10px">35 minutes ago</div>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item media">
                            <div class="media-left">
                                <i class="fa fa-plus media-object bg-gray-400"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading"> New User Registered</h6>
                                <div class="text-muted fs-10px">1 hour ago</div>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item media">
                            <div class="media-left">
                                <i class="fa fa-envelope media-object bg-gray-400"></i>
                                <i class="fab fa-google text-warning media-object-icon fs-14px"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading"> New Email From John</h6>
                                <div class="text-muted fs-10px">2 hour ago</div>
                            </div>
                        </a>
                        --}}
                        <div class="dropdown-footer text-center">
                            <a href="javascript:;" class="text-decoration-none">View more</a>
                        </div>
                    </div>
                </div>
