<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Causa;
use Illuminate\Http\Request;
use App\Exports\CausasExport;


use Auth, DB, Str, Libreria, Exception, Excel;

class CausaController extends Controller
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
        $sRuteXPagina = 'admin.causas.index';

        # arrar para la seleccion de orden
        $aOrden = [
            'id' => 'id',
            'n_causa' => 'Causa',
            'activo' => 'Activo',
            'inactivo' => 'Inactivo',
        ];

        # cargamos la vista
        return view(
            'admin.causas.index', #admin/causas/index
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

        # admin/causas/create
        return view('admin.causas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            DB::beginTransaction();

            $datos = $request->all();

            if (floatval($datos['minimo']) < 0 || floatval($datos['maximo']) < 0) {
                throw new Exception("Los valores mínimo y máximo no deben ser negativos");
            }

            if (floatval($datos['minimo']) > floatval($datos['maximo'])) {
                throw new Exception("El valor mínimo no debe ser mayor al máximo");
            }

            if (floatval($datos['minimo']) == floatval($datos['maximo'])) {
                throw new Exception("El valor mínimo no debe ser igual al máximo");
            }

            if (floatval($datos['minimo']) == floatval($datos['maximo'])) {
                throw new Exception("El valor mínimo no debe ser igual al máximo");
            }

            $datos['activo'] = isset($datos['activo']) ? 1 : 0;
            $datos['que']    = 'A';
            $datos['quien']  = Auth::id();
            $datos['cuando'] = date('Y-m-d hh:mm:ss');


            Causa::create($datos);

            DB::commit();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Datos guardados correctamente.'
            ]);

            return redirect()->route('admin.causas.index');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([Str::limit($e->getMessage(), 250, '...')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $causa = Causa::find($id);


        $causa->minimo = intval($causa->minimo);
        $causa->maximo = intval($causa->maximo);


        # admin/causas/show
        return view('admin.causas.show')
            ->with(compact('causa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $causa = Causa::find($id);

        $causa->minimo = intval($causa->minimo);
        $causa->maximo = intval($causa->maximo);

        # admin/causas/show
        return view('admin.causas.edit')
            ->with(compact('causa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $datos = $request->all();

            if (floatval($datos['minimo']) < 0 || floatval($datos['maximo']) < 0) {
                throw new Exception("Los valores mínimo y máximo no deben ser negativos");
            }

            if (floatval($datos['minimo']) > floatval($datos['maximo'])) {
                throw new Exception("El valor mínimo no debe ser mayor al máximo");
            }

            if (floatval($datos['minimo']) == floatval($datos['maximo'])) {
                throw new Exception("El valor mínimo no debe ser igual al máximo");
            }

            if (floatval($datos['minimo']) == floatval($datos['maximo'])) {
                throw new Exception("El valor mínimo no debe ser igual al máximo");
            }


            $datos['activo'] = isset($datos['activo']) ? 1 : 0;
            $datos['que']    = 'C';
            $datos['quien']  = Auth::id();
            $datos['cuando'] = date('Y-m-d hh:mm:ss');

            $causa = Causa::find($id);
            $causa->fill($datos);
            $causa->save();

            DB::commit();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Datos guardados correctamente.'
            ]);

            return redirect()->route('admin.causas.index');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([Str::limit($e->getMessage(), 250, '...')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $causa = Causa::find($id);
        $causa->delete();

        return redirect()->route('admin.causas.index');
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


        # obtenemos los registros
        $oRegistros = Causa::select(
            'id',
            'n_causa',
            'minimo',
            'maximo',
            'activo'
        )
            ->where(function ($oQuery) use ($sBusquedaAM) {
                $oQuery->where('id', 'LIKE', '%' . $sBusquedaAM . '%');
                $oQuery->orWhere('n_causa', 'LIKE', '%' . $sBusquedaAM . '%');
            });


        if ($sFiltroOrdenAM) {
            $oRegistros->orderBy($sFiltroOrdenAM, 'ASC');
        } else {
            $oRegistros->orderBy('id');
        }

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
        return redirect()->route('admin.causas.index');
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
        return redirect()->route('admin.causas.index');
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
        $oRegistros = Self::getRegistros();
        #$oRegistros = $this->getRegistros();
        // cargamos la vista
        return view(
            'admin.causas.imprimir', # admin/causas/imprimir
            compact(
                'oRegistros'
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
        $oRegistros = $this->getRegistros();
        // generamso el excel
        return Excel::download(new CausasExport($oRegistros), 'Causa_' . date('d_m_Y_G_i_s') . '.xlsx');
    }



    /**
     * Obtiene las causas registradas.
     *
     *@param  \Illuminate\Http\Request  $request
     *
     */
    public function getCausasAPI(Request  $request)
    {
        try {
            $causas = Causa::select('id', 'n_causa', 'minimo', 'maximo', 'activo')->where('activo', '=', '1')->get();


            return response()->json($causas, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
