@props( [ 'id', 'sort', 'direction', ] )

@if ( $sort == $id )
    @if ( $direction == 'asc' )
        <i class="fas fa-sort-alpha-up-alt float-right"></i>
    @else
        <i class="fas fa-sort-alpha-down-alt float-right"></i>
    @endif
@else
    <i class="fas fa-sort float-right"></i>
@endif