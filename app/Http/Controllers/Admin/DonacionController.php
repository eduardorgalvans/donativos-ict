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
     * @param  \App\Models\Donacion  $donacion
     * @return \Illuminate\Http\Response
     */
    public function show(Donacion $donacion)
    {
        //
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


        # obtenemos los registros
        $oRegistros = Donacion::select(
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
}
