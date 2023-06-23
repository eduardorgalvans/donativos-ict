<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Servicios en Linea - ICT</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"
        type="text/css" />
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <!--
        <link href="/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        -->
    <style type="text/css">
        td {
            text-align: center;
        }

        .izq {
            text-align: left;
        }

        .cent {
            text-align: center;
        }

        .minifont {
            font-size: 10px;
        }

        body {
            font-size: 11px;
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
</head>

<body class="skin-ict">
    <!-- Main content -->
    <section class="content invoice">
        <!-- title row -->
        <div class="row">
            <div class="jumbotron">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ asset('assets/img/logoict.png') }}" width="15%" align="right"
                                alt="">
                        </div>
                        <div class="col-md-8 text-center ">
                            <h2>INSTITUTO CULTURAL TAMPICO</h2>
                            <h3>{{ $oRegistros[0]->n_causa }}.</h3>
                            Reporte de donaciones.<br>
                            Fecha : {{ date('d-m-Y') }}.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="jumbotron">
                <div class="container">
                    <table class="table table-striped table-bordered widget-table rounded p-3" data-id="widget">
                        <thead>
                            <tr class="text-nowrap">
                                <th>ID de la causa</th>
                                <th>Causa</th>
                                <th>Total recuadado</th>
                                <th>Total donaciones</th>
                                <th>ID de la causa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td> {{ $donacion->id_causa }}</td>
                                <td> {{ $donacion->n_causa }}</td>
                                <td> ${{ number_format($donacion->total, 2) }}</td>
                                <td> {{ $donacion->donaciones }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <h5>Donaciones por comunidad</h5>
                        <table class="table table-striped table-bordered widget-table rounded p-3" data-id="widget">
                            <thead>
                                <tr class="text-nowrap">
                                    <th>Comunidad</th>
                                    <th>Donaciones</th>
                                    <th>No. de donaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($donacionPorComunidades as $donacion)
                                    <tr>
                                        <td>{{ $donacion->n_comunidad }}</td>
                                        <td>{{ number_format($donacion->total, 2) }}</td>
                                        <td>{{ $donacion->donaciones }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <table class="table table-hover table-mail">
                    <thead>
                        <tr class="text-nowrap">
                            <th style="width: 65px;">ID</th>
                            <th style="width: 200px">Causa</th>
                            <th style="width: 130px;">Referencia</th>
                            <th style="width: 90px;">Fecha</th>
                            <th style="width: 250px;">Donador</th>
                            <th style="width: 90px;">Importe</th>
                            <th style="width: 200px;">Email</th>
                            <th style="width: 100px;">Teléfono</th>
                            <th style="width: 150px;">Comunidad</th>
                            <th style="width: 80px;">Deducible</th>
                            <th style="width: 120px;">Tipo de persona</th>
                            <th style="width: 120px;">RFC</th>
                            <th style="width: 300px;">Razon Social</th>
                            <th style="width: 300px;">Regimen físcal</th>
                            <th style="width: 60px;">CP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $iCont = 0; ?>
                        @foreach ($oRegistros as $oRegistro)
                            <?php $iCont++; ?>
                            <tr class="{!! $iCont % 2 == 0 ? 'read' : 'unread' !!}">
                                <td>{{ $oRegistro->id }}</td>
                                <td>{{ $oRegistro->n_causa }}</td>
                                <td>{{ $oRegistro->referencia_banco }}</td>
                                <td>{{ date('d/m/Y', strtotime($oRegistro->fecha)) }}</td>
                                <td>{{ $oRegistro->nombre }} {{ $oRegistro->apellido }} </td>
                                <td>${{ number_format($oRegistro->importe, 2) }}</td>
                                <td>{{ $oRegistro->email }}</td>
                                <td>{{ $oRegistro->tel }}</td>
                                <td>{{ $oRegistro->n_comunidad }}</td>
                                <td>
                                    @if ($oRegistro->deducible == 1)
                                        Sí
                                    @else
                                        No
                                    @endif
                                </td>
                                <td>
                                    @if ($oRegistro->deducible == 1)
                                        @if ($oRegistro->tipo_persona == 'M')
                                            Moral
                                        @else
                                            Física
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $oRegistro->rfc }}</td>
                                <td>{{ $oRegistro->razon_social }}</td>
                                <td>{{ $oRegistro->n_regimen }}</td>
                                <td>{{ $oRegistro->cp_fiscal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section><!-- /.content -->
</body>
<script type="text/javascript">
    /*
        $( document ).ready( function($) {

        });
        */
</script>

</html>
