@extends('layouts.intranet')

@section('titulo-pestaña')
    Rutas
@endsection

@section('titulo-pagina')
    Rutas de Intranet
@endsection

@section('css')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet">

    <style type="text/css">
        div.dataTables_wrapper div.dataTables_filter {
            text-align: left;
        }
    </style>
@endsection

@section('contenido')
    <table id="tbl-rutas" class="table table-striped table-hover">
        <thead>
            <th>Método</th>
            <th>URI</th>
            <th>Nombre</th>
            <th>Acción</th>
            <th>Middleware</th>
        </thead>
        <tbody>
            @foreach ($oRutas as $oRuta)
                {{-- Se excluyen las rutas que empiezan con _ignition y __clockwork --}}
                @if (preg_match('/^(?!(_ignition|__clockwork)).+/', $oRuta->uri))
                    <tr>
                        <td>{{ implode('|', $oRuta->methods) }}</td>
                        <td>{{ $oRuta->uri }}</td>
                        <td>{{ $oRuta->action['as'] ?? '' }}</td>
                        <td>{{ is_callable($oRuta->action['uses']) ? 'Closure' : str_replace("App\\Http\\Controllers\\", '', $oRuta->action['uses']) }}</td>
                        <td>{{ isset($oRuta->action['middleware']) ? implode(',', $oRuta->action['middleware']) : '' }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <th>Método</th>
            <th>URI</th>
            <th>Nombre</th>
            <th>Acción</th>
            <th>Middleware</th>
        </tfoot>
    </table>
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script>
        var tabla = '';
        $(document).ready(function(){
            tabla = $('#tbl-rutas').DataTable({
                paging: false,
                responsive: true,
                scrollY: 600,
                scroller: true,
                dom: 'frtip',
                language: {
                    url: '{{ asset('assets/plugins/datatables.net/js/es-MX.json') }}'
                }
            })
        });
    </script>
@endsection