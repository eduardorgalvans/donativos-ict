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
                            <h3>Menú de Intrtanet.</h3>
                            Reporte de causas.<br>
                            Fecha : {{ date('d-m-Y') }}.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <table class="table table-hover table-mail">
                    <thead>
                        <tr class="text-nowrap">
                            <th>ID Causa</th>
                            <th>Causa</th>
                            <th>Monto mínimo</th>
                            <th>Monto máximo</th>
                            <th>Causa activa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $iCont = 0; ?>
                        @foreach ($oRegistros as $oRegistro)
                            <?php $iCont++; ?>
                            <tr class="{!! $iCont % 2 == 0 ? 'read' : 'unread' !!}">
                                <td>{!! $oRegistro->id !!}</td>
                                <td>{!! $oRegistro->n_causa !!}</td>
                                <td>{!! number_format($oRegistro->minimo, 2) !!}</td>
                                <td>{!! number_format($oRegistro->maximo, 2) !!}</td>
                                <td>{!! $oRegistro->activo == 1
                                    ? '  <span class="badge bg-success">Activa</span>'
                                    : '<span class="badge bg-danger">Inactiva</span>' !!}</td>
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
