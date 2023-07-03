<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Comunidad;
use Illuminate\Http\Request;
use App\Exports\ComunidadesExport;


use Auth, DB, Str, Libreria, Exception, Excel;

class ComunidadController extends Controller
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
            'n_comunidad' => 'Comunidad',
        ];


        return view(
            'admin.comunidades.index', #admin/comunidad/index
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
        # admin/comunidades/create
        return view('admin.comunidades.create');
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

            $datos['que']    = 'A';
            $datos['quien']  = Auth::id();
            $datos['cuando'] = date('Y-m-d hh:mm:ss');


            Comunidad::create($datos);

            DB::commit();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Datos guardados correctamente.'
            ]);

            return redirect()->route('admin.comunidades.index');
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
        $comunidad = Comunidad::find($id);

        # admin/comunidades/show
        return view('admin.comunidades.show')
            ->with(compact('comunidad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $int
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comunidad = Comunidad::find($id);

        # admin/comunidades/edit
        return view('admin.comunidades.edit')
            ->with(compact('comunidad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $datos = $request->all();

            $datos['que']    = 'C';
            $datos['quien']  = Auth::id();
            $datos['cuando'] = date('Y-m-d hh:mm:ss');

            $comunidad = Comunidad::find($id);
            $comunidad->fill($datos);
            $comunidad->save();

            DB::commit();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Datos guardados correctamente.'
            ]);

            return redirect()->route('admin.comunidades.index');
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
        $comunidad = Comunidad::find($id);
        $comunidad->delete();

        return redirect()->route('admin.comunidades.index');
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
        $oRegistros = Comunidad::select(
            'id',
            'n_comunidad',
        )
            ->where(function ($oQuery) use ($sBusquedaAM) {
                $oQuery->where('id', 'LIKE', '%' . $sBusquedaAM . '%');
                $oQuery->orWhere('n_comunidad', 'LIKE', '%' . $sBusquedaAM . '%');
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
            'sFiltroOrdenAM',
        ]);

        # redirecciona a conunidades
        return redirect()->route('admin.comunidades.index');
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
        return redirect()->route('admin.comunidades.index');
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
            'admin.comunidades.imprimir', # admin/comunidades/imprimir
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
        return Excel::download(new ComunidadesExport($oRegistros), 'Comunidad_' . date('d_m_Y_G_i_s') . '.xlsx');
    }




    /**
     * Obtiene las comunidades registradas.
     *
     *@param  \Illuminate\Http\Request  $request
     *
     */
    public function getComunidadesAPI(Request  $request)
    {
        try {
            $comunidades = Comunidad::all(['id', 'n_comunidad']);

            return response()->json($comunidades, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
