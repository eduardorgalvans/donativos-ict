<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Causa;
use App\Models\Admin\Comunidad;
use App\Models\Admin\Donacion;
use Illuminate\Http\Request;
use App\Exports\DonacionesExport;

use Auth, DB, Str, Libreria, Exception, Excel;

class DonacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        # recuperamos la busqueda
        $sPaginaAM = $request->input('sPaginaAM', session('sPaginaAM', 10));
        $sBusquedaAM = $request->input('sBusquedaAM', session('sBusquedaAM', ''));
        $sFiltroOrdenAM = $request->input('sFiltroOrdenAM', session('sFiltroOrdenAM', 'id'));
        $sFiltroCausaAM = $request->input('sFiltroCausaAM', session('sFiltroCausaAM', ''));
        $sFiltroComunidadAM = $request->input('sFiltroComunidadAM', session('sFiltroComunidadAM', ''));
        $sFiltroFechaIncAM = $request->input('sFiltroFechaIncAM', session('sFiltroFechaIncAM', ''));
        $sFiltroFechaFinAM = $request->input('sFiltroFechaFinAM', session('sFiltroFechaFinAM', ''));


        # variables de sesion
        Libreria::putSesionSistema($request, [
            'sPaginaAM' => $sPaginaAM,
            'sBusquedaAM' => $sBusquedaAM,
            'sFiltroOrdenAM' => $sFiltroOrdenAM,
            'sFiltroCausaAM' => $sFiltroCausaAM,
            'sFiltroComunidadAM' => $sFiltroComunidadAM,
            'sFiltroFechaIncAM' => $sFiltroFechaIncAM,
            'sFiltroFechaFinAM' => $sFiltroFechaFinAM,
        ]);

        # buscamos los registros
        $oRegistros = Self::getRegistros(TRUE);

        # Ruta del paginacion 
        $sRuteXPagina = 'admin.donaciones.index';



        $comunidades = Comunidad::all('id', 'n_comunidad');
        $causas = Causa::all('id', 'n_causa');

        #array para filtrar por comunidad
        $aComunidad = [];
        foreach ($comunidades as $key => $comunidad) {
            $aComunidad[$comunidad->id] = $comunidad->n_comunidad;
        }

        #array para filtrar por causas
        # se verifica cual es la causa seleccionada en caso de que lo haya
        $aCausa = [];
        $causaSeleccionada = "";
        foreach ($causas as $key => $causa) {
            $aCausa[$causa->id] = $causa->n_causa;
            if ($causa->id == $sFiltroCausaAM) {
                $causaSeleccionada = $causa->n_causa;
            }
        }

        # arrar para la seleccion de orden
        $aOrden = [
            'fecha_asc' => 'Fecha ascendente',
            'fecha_desc' => 'Fecha descendente',
        ];

        # cargamos la vista
        return view(
            'admin.donaciones.index', #admin/causas/index
            compact(
                'oRegistros',
                'sPaginaAM',
                'sBusquedaAM',
                'sFiltroOrdenAM',
                'sFiltroCausaAM',
                'sFiltroComunidadAM',
                'sFiltroFechaIncAM',
                'sFiltroFechaFinAM',
                'aComunidad',
                'aCausa',
                'aOrden',
                'causaSeleccionada',
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //Id de la causa
    public function show($id)
    {
        //

        $detalles = Self::getDetalles();
        $donacion = $detalles['donacion'];
        $donacionPorComunidades = $detalles['donacionPorComunidades'];


        return view('admin.donaciones.show')
            ->with(compact('donacion', 'donacionPorComunidades'));
    }




    /**
     * Devolvemos la busqueda de registros.
     *
     * @return \App\Models\Admin\Causa
     */
    public function getRegistros($bPaginate = false)
    {
        # recuperamos la busqueda
        $sPaginaAM = session('sPaginaAM', 10);
        $sBusquedaAM = session('sBusquedaAM', '');
        $sFiltroCausaAM = session('sFiltroCausaAM', '');
        $sFiltroOrdenAM = session('sFiltroOrdenAM', 'id');
        $sFiltroComunidadAM = session('sFiltroComunidadAM', '');
        $sFiltroFechaIncAM = session('sFiltroFechaIncAM', '');
        $sFiltroFechaFinAM = session('sFiltroFechaFinAM', '');


        # obtenemos los registros
        $oRegistros = DB::table('dss_donaciones as donacion')
            ->select(
                'donacion.id',
                'causa.n_causa',
                'donacion.referencia_banco',
                // 'causa.id as id_causa',
                'donacion.fecha',
                'donacion.nombre',
                'donacion.apellido',
                'donacion.importe',
                'donacion.email',
                'donacion.tel',
                'comunidad.n_comunidad',
                'donacion.deducible',
                'donacion.tipo_persona',
                'donacion.rfc',
                'donacion.razon_social',
                'remigenes.n_regimen',
                'donacion.cp_fiscal',
            )
            ->leftJoin('dss_cat_causas AS causa', 'donacion.id_causa', '=', 'causa.id')
            ->leftJoin('dss_cat_comunidades AS comunidad', 'donacion.id_comunidad', '=', 'comunidad.id')
            ->leftJoin('dss_cat_regimenes AS remigenes', 'donacion.id_regimen', '=', 'remigenes.id')
            ->where(function ($oQuery) use ($sFiltroCausaAM) {
                if ($sFiltroCausaAM != '') {
                    $oQuery->where('causa.id', ':sFiltroCausaAM');
                    $oQuery->setBindings(['sFiltroCausaAM' => $sFiltroCausaAM]);
                }
            })
            ->where(function ($oQuery) use ($sFiltroComunidadAM) {
                if ($sFiltroComunidadAM != '') {
                    $oQuery->where('comunidad.id', ':sFiltroComunidadAM');
                    $oQuery->setBindings(['sFiltroComunidadAM' => $sFiltroComunidadAM,]);
                }
            })
            ->where(function ($oQuery) use ($sFiltroFechaIncAM, $sFiltroFechaFinAM) {
                if ($sFiltroFechaIncAM != '' && $sFiltroFechaFinAM != '') {
                    $oQuery->where('donacion.fecha', '>=', ':sFiltroFechaIncAM');
                    $oQuery->where('donacion.fecha', '<=', ':sFiltroFechaFinAM');
                    $oQuery->setBindings(['sFiltroFechaIncAM' => $sFiltroFechaIncAM, 'sFiltroFechaFinAM' => $sFiltroFechaFinAM]);
                }
            })
            ->where(function ($oQuery) use ($sBusquedaAM) {
                $oQuery->where('donacion.nombre', 'LIKE', '%' . $sBusquedaAM . '%');
                $oQuery->orWhere('donacion.apellido', 'LIKE', '%' . $sBusquedaAM . '%');
                $oQuery->orWhere('donacion.razon_social', 'LIKE', '%' . $sBusquedaAM . '%');
            });


        switch ($sFiltroOrdenAM) {
            case 'fecha_asc':
                $oRegistros->orderBy('donacion.fecha', 'asc');
                break;

            case 'fecha_desc':
                $oRegistros->orderBy('donacion.fecha', 'desc');
                break;
        }

        # si se selecciona todos
        $bPaginate = ($sPaginaAM == 0) ? false : $bPaginate;
        # devolvemos paginacion o todos los registros
        return ($bPaginate) ? $oRegistros->paginate($sPaginaAM) : $oRegistros->get();
    }



    public function getDetalles()
    {

        $sFiltroCausaAM = session('sFiltroCausaAM');

        $donacion = DB::table('dss_donaciones as donacion')
            ->select(
                'donacion.id',
                'causa.id as id_causa',
                'causa.n_causa',
                DB::raw("SUM(donacion.importe) AS total"),
                DB::raw("COUNT(*) AS donaciones")
            )
            ->leftJoin('dss_cat_causas AS causa', 'donacion.id_causa', '=', 'causa.id')
            ->where('causa.id', '=', ':id')
            ->setBindings(['id' => $sFiltroCausaAM])
            ->first();


        $donacionPorComunidades = DB::table('dss_donaciones as donacion')
            ->select(
                'donacion.id',
                'causa.id as id_causa',
                'causa.n_causa',
                'comunidad.n_comunidad',
                DB::raw("SUM(donacion.importe) AS total"),
                DB::raw("COUNT(*) AS donaciones")
            )
            ->leftJoin('dss_cat_causas AS causa', 'donacion.id_causa', '=', 'causa.id')
            ->leftJoin('dss_cat_comunidades AS comunidad', 'donacion.id_comunidad', '=', 'comunidad.id')
            ->where('causa.id', '=', ':id')
            ->groupBy('comunidad.id')
            ->orderBy('comunidad.id', 'asc')
            ->setBindings(['id' => $sFiltroCausaAM])
            ->get();

        return ['donacion' => $donacion, 'donacionPorComunidades' => $donacionPorComunidades];
    }

    /**
     * elimina la busqueda del controlador.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function limpiar(Request $request)
    {
        # limpiamos la busqueda
        Libreria::delSesionSistema($request, [
            'sPaginaAM',
            'sBusquedaAM',
            'sFiltroCausaAM',
            'sFiltroOrdenAM',
            'sFiltroComunidadAM',
            'sFiltroFechaIncAM',
            'sFiltroFechaFinAM',
        ]);

        # redirecciona a clientes
        return redirect()->route('admin.donaciones.index');
    }



    /**
     * cambia el cantidad de elemtos de la paginacio.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pagina(Request $request, $id)
    {
        # limpiamos la busqueda
        $request->session()->put('sPaginaAM', $id);
        # redirecciona a index
        return redirect()->route('admin.donaciones.index');
    }





    /**
     * imprime la realcion de registros.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function imprimir(Request $request)
    {
        // obtenemos los registros
        $detalles = Self::getDetalles();
        $donacion = $detalles['donacion'];
        $donacionPorComunidades = $detalles['donacionPorComunidades'];

        $oRegistros = Self::getRegistros();
        #$oRegistros = $this->getRegistros();
        // cargamos la vista
        return view(
            'admin.donaciones.imprimir', # admin/donaciones/imprimir
            compact(
                'oRegistros',
                'donacion',
                'donacionPorComunidades',
            )
        );
    }

    /**
     * descarga la archivo de Excel de registros.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function xls(Request $request)
    {
        // obtenemos los registros
        $detalles = Self::getDetalles();
        $donacion = $detalles['donacion'];
        $donacionPorComunidades = $detalles['donacionPorComunidades'];
        // dd($donacionPorComunidades);s

        $oRegistros = Self::getRegistros();


        foreach ($oRegistros as $key => $reg) {

            $oRegistros[$key]->nombre = $reg->nombre . " " . $reg->apellido;
            unset($oRegistros[$key]->apellido);
        }


        // generamso el excel
        return Excel::download(new DonacionesExport($oRegistros, $donacion, $donacionPorComunidades), 'Donacion_' . date('d_m_Y_G_i_s') . '.xlsx');
    }














    /**
     * Stores donations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function donate(Request $request)
    {
        try {
            DB::beginTransaction();

            $encryptionResponse = EncryptionController::decrypt($request);

            $jsonResponse = json_decode($encryptionResponse->getContent(), true);

            
            if($jsonResponse['error']){
                throw new Exception($jsonResponse['message']);
            }


            $datos = $request->all();

            if ($datos['deducible'] == "0") {
                $datos['tipo_persona'] = '';
                $datos['rfc'] = '';
                $datos['razon_social'] = '';
                $datos['id_regimen'] = '0';
                $datos['cp_fiscal'] = '';
            }

            $datos['que']    = 'A';
            $datos['quien']  = 'Donador';
            $datos['cuando'] = date('Y-m-d h:m:s');
            $datos['fecha'] = date('Y-m-d');


            Donacion::create($datos);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Donación realizada con éxito'
            ], 201);
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
