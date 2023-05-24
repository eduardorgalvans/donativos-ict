                                        <div>
                                            @include( 'layouts.request' ) {{-- layouts/request --}}
                                            <!-- BEGIN widget-table -->
                                            <table class="table table-striped table-bordered widget-table rounded small" data-id="widget">
                                                <thead>
                                                    <tr class="text-nowrap">
                                                        <th role="button" wire:click="order('id')" width="2%">
                                                            id
                                                            <x-short id="id" sort="{{ $sort }}" direction="{{ $direction }}" />
                                                        </th>
                                                        <th role="button" wire:click="order('Variable')" class="">
                                                            Variable
                                                            <x-short id="Variable" sort="{{ $sort }}" direction="{{ $direction }}" />
                                                        </th>
                                                        <th role="button" wire:click="order('Valor')" class="" width="40%">
                                                            Valor
                                                            <x-short id="Valor" sort="{{ $sort }}" direction="{{ $direction }}" />
                                                        </th>
                                                        <th class="" width="10%">Tipo</th>
                                                        <th class="" width="5%"><i class="fas fa-file-alt" title="Descripción"></i></th>
                                                        <th class="" width="10%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($oRegistros as $oRegistro)
                                                        <tr>
                                                            <td>{!! $oRegistro->id !!}</td>
                                                            <td>{!! $oRegistro->Variable !!}</td>
                                                            <td>
                                                                <div class="text-nowrap" style="width: 8rem;">
                                                                    {{ ( strlen( $oRegistro->Valor ) > 45 ) ? substr( $oRegistro->Valor, 0, 45).'...' : $oRegistro->Valor }}
                                                                </div>
                                                            </td>
                                                            <td><span class="badge bg-{{ @$aTipo[ $oRegistro->Tipo ] }}">{{ $oRegistro->Tipo }}</span></td>
                                                            <td>
                                                                @if ( ! empty( $oRegistro->Descripcion ) )
                                                                    <a href="javascript:;" class="eDescricion" data-href="content{{ $oRegistro->id }}" >
                                                                        <i class="fas fa-arrow-alt-circle-down"></i>
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ( substr_count( session('permisos'), ',31,' ) )
                                                                    @livewire( 'configuraciones.permanentes-editar', [ 'oRegistro' => $oRegistro ], key( $oRegistro->id ) )
                                                                @endif
                                                                {{--
                                                                @if ( substr_count( session('permisos'), ',29,' ) )
                                                                    {!! Html::decode(link_to_route('admin.permanentes.edit', '<i class="fas fa-pencil-alt" aria-hidden="true"></i>', ( $oRegistro->id ?? 0 ), ["class"=>"text-success"])) !!}&nbsp;
                                                                @endif
                                                                @if ( substr_count( session('permisos'), ',32,' ) )
                                                                    {!! Html::decode(link_to('#', '<i class="fa fa-trash"></i>', ["class"=>"text-danger", "onclick"=>"var bConf = confirm('¿Estás seguro de que quieres eliminar este registro?'); if(bConf){ event.preventDefault(); document.getElementById('delete-form-".( $oRegistro->id ?? 0 )."').submit(); }"  ])) !!}
                                                                    {!! Form::open(['route'=>['admin.permanentes.destroy', ( $oRegistro->id ?? 0 )], 'method'=>'DELETE', 'id'=>'delete-form-'.( $oRegistro->id ?? 0 ), ]) !!}
                                                                    {!! Form::close() !!}
                                                                @endif
                                                                --}}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr class="unread">
                                                            <td colspan="8" class="">Sin datos que mostrar.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                            <!-- END widget-table -->
                                        </div>
