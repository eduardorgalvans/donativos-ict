<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Donacion;
use Illuminate\Http\Request;

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


        # variables de sesion
        Libreria::putSesionSistema($request, [
            'sPaginaAM' => $sPaginaAM,
            'sBusquedaAM' => $sBusquedaAM,
            'sFiltroOrdenAM' => $sFiltroOrdenAM,
        ]);

        # buscamos los registros
        $oRegistros = Self::getRegistros(TRUE);

        # Ruta del paginacion 
        $sRuteXPagina = 'admin.donaciones.index';

        # arrar para la seleccion de orden
        $aOrden = [
            'id' => 'id',
            'n_causa' => 'Causa',
            'activo' => 'Activo',
            'inactivo' => 'Inactivo',
        ];

        # cargamos la vista
        return view(
            'admin.donaciones.index', #admin/causas/index
            compact(
                'oRegistros',
                'sPaginaAM',
                'sBusquedaAM',
                'sFiltroOrdenAM',
                'aOrden'
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


        $donacion = DB::table('dss_donaciones as donacion')
            ->select(
                'donacion.id',
                'causa.id as id_causa',
                'causa.n_causa',
                DB::raw("SUM(donacion.importe) AS donaciones")
            )
            ->leftJoin('dss_cat_causas AS causa', 'donacion.id_causa', '=', 'causa.id')
            ->where('causa.id', '=', ':id')
            ->setBindings(['id' => $id])
            ->first();



        " SELECT donacion.id,  causa.n_causa,  comunidad.n_comunidad, SUM(donacion.importe) AS donaciones FROM dss_donaciones AS donacion 
 LEFT JOIN dss_cat_causas AS causa 
 ON donacion.id_causa = causa.id
 LEFT JOIN dss_cat_comunidades AS comunidad
 ON donacion.id_comunidad = comunidad.id
 GROUP BY comunidad.id ORDER BY comunidad.id asc";


        $donacionPorComunidades = DB::table('dss_donaciones as donacion')
            ->select(
                'donacion.id',
                'causa.id as id_causa',
                'causa.n_causa',
                'comunidad.n_comunidad',
                DB::raw("SUM(donacion.importe) AS donaciones")
            )
            ->leftJoin('dss_cat_causas AS causa', 'donacion.id_causa', '=', 'causa.id')
            ->leftJoin('dss_cat_comunidades AS comunidad', 'donacion.id_comunidad', '=', 'comunidad.id')
            ->where('causa.id', '=', ':id')
            ->groupBy('comunidad.id')
            ->orderBy('comunidad.id', 'asc')
            ->setBindings(['id' => $id])
            ->get();

        return view('admin.donaciones.show')
            ->with(compact('donacion', 'donacionPorComunidades'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Donacion  $donacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Donacion $donacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Donacion  $donacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Donacion $donacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Donacion  $donacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Donacion $donacion)
    {
        //
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
        $sFiltroOrdenAM = session('sFiltroOrdenAM', 'id');


        // # obtenemos los registros
        // $oRegistros = DB::select(
        //     "SELECT 
        //         donacion.id, 
        //         donacion.referencia_banco, 
        //         DATE_FORMAT(donacion.fecha, '%d/%m/%Y') AS fecha,
        //         donacion.nombre, 
        //         donacion.paterno, 
        //         donacion.materno, 
        //         donacion.importe,
        //         donacion.email,
        //         donacion.tel, 
        //         comunidad.n_comunidad, 
        //         donacion.deducible, 
        //         donacion.tipo_persona, 
        //         donacion.rfc, 
        //         donacion.razon_social, 
        //         remigenes.n_regimen,
        //         donacion.cp_fiscal, 
        //         donacion.email_fiscal
        //     FROM dss_donaciones AS donacion 
        //     LEFT JOIN dss_cat_causas AS causa 
        //         ON donacion.id_causa = causa.id
        //     LEFT JOIN dss_cat_comunidades AS comunidad
        //         ON donacion.id_comunidad = comunidad.id
        //     LEFT JOIN dss_cat_regimenes AS remigenes
        //         ON donacion.id_regimen = remigenes.id"

        //         // , ['id' => 1]
        // );//->get();


        # obtenemos los registros
        $oRegistros = DB::table('dss_donaciones as donacion')
            ->select(
                'donacion.id',
                'donacion.referencia_banco',
                'causa.id as id_causa',
                'causa.n_causa',
                'donacion.fecha',
                'donacion.nombre',
                'donacion.paterno',
                'donacion.materno',
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
                'donacion.email_fiscal'
            )
            ->leftJoin('dss_cat_causas AS causa', 'donacion.id_causa', '=', 'causa.id')
            ->leftJoin('dss_cat_comunidades AS comunidad', 'donacion.id_comunidad', '=', 'comunidad.id')
            ->leftJoin('dss_cat_regimenes AS remigenes', 'donacion.id_regimen', '=', 'remigenes.id')

            ->orderBy('donacion.id');

        // dd($oRegistros);
        //->get();



        //         $query = Factor::join('factor_items','factors.id','=','factor_items.factor_id')
        //                   ->join('products','factor_items.product_id','=','products.id')
        //                   ->select('factor_items.product_id',
        // DB::raw('COUNT(factor_items.product_id) as count'),'products.*')
        //                   ->where('factors.payment_state',1)
        //                   ->groupBy('factor_items.product_id')
        //                   ->orderBy('count', 'desc');
        // $products = $query->with('user','vote')->paginate(3);
        //dd($oRegistros);


        // $oRegistros = Donacion::select(
        //     'id',
        //     'n_causa',
        //     'minimo',
        //     'maximo',
        //     'activo'
        // )
        //     ->where(function ($oQuery) use ($sBusquedaAM) {
        //         $oQuery->where('id', 'LIKE', '%' . $sBusquedaAM . '%');
        //         $oQuery->orWhere('n_causa', 'LIKE', '%' . $sBusquedaAM . '%');
        //     });


        // if ($sFiltroOrdenAM) {
        //     $oRegistros->orderBy($sFiltroOrdenAM, 'ASC');
        // } else {
        //     $oRegistros->orderBy('id');
        // }

        # si se selecciona todos
        $bPaginate = ($sPaginaAM == 0) ? false : $bPaginate;
        # devolvemos paginacion o todos los registros
        return ($bPaginate) ? $oRegistros->paginate($sPaginaAM) : $oRegistros->get();
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
            'sFiltroPadreAM',
            'sFiltroOrdenAM',
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

            $datos = $request->all();

            if ($datos['deducible'] == "0") {
                $datos['tipo_persona'] = '';
                $datos['rfc'] = '';
                $datos['razon_social'] = '';
                $datos['id_regimen'] = '0';
                $datos['cp_fiscal'] = '';
                $datos['email_fiscal'] = '';
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
                'message' => 'Se realizó la donación pero ocurrió un error al guardar la información: ' . Str::limit($th->getMessage(), 250, '...'),
                // 'message' => 'Se realizó la donación pero ocurrió un error al guardar la información'
            ], 500);
        }
    }
}
