<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\MenuRequest;
use Illuminate\Support\Facades\Route;

use App\Exports\MenusExport;

use \App\Models\Admin\{
    Menu,
    Modulo
};

use DB, Session, Redirect, Libreria, Str, Excel;

class MenuController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        # obtiene los padres de la bd para el select
        $oPadres = Menu::select(
                'id', 
                'Nombre'
            )
            ->where( 'Tipo', '1' )
            ->get();
        # agrega la opcion sin padre
        $arrInicial = [];
        $arrInicial[ 0 ] = 'Sin padre';
        foreach ( $oPadres as $oPadre ) {
            $arrInicial[ $oPadre->id ] = $oPadre->id.' - '.$oPadre->Nombre;
        }
        # retorna el array
        $this->aPadres = $arrInicial;
        # Todos los modulos
        $this->aModulos = [''=>'Selecciona...'];

        $oModulos = Modulo::where('Tipo', '<>', 3)
            ->get();

        $this->aAtributosOpciones = [];
        foreach ($oModulos as $oModulo) {
            $this->aModulos[$oModulo->id] = "{$oModulo->id} - {$oModulo->Nombre}";
            $this->aAtributosOpciones[$oModulo->id] = [
                'data-ruta' => $oModulo->Ruta
            ];
        }
        # creamos un arrar con las rutas del sistema.
        $this->aRutas = [
            ''=>'Selecciona...',
            '#' => '#'
        ];
        # obtenemos las rutas GET del sistema
        $oRoutes = Route::getRoutes()->getRoutesByMethod()[ "GET" ];
        foreach ( $oRoutes as $oRoute ) {
            $sRuta = $oRoute->getName();
            if ( ! empty( $sRuta ) ) {
                // si la ruta nso es vacia
                $this->aRutas[ $sRuta ] = $sRuta;
                /*
                echo $oRoute->getName(); // get the route name.  
                echo $oRoute->uri(); // get the path
                var_dump($oRoute->getAction()); // get the action. Which controller method will be called.
                */
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        # redirecciona a dashboard si no cuenta con los permisos
        if ( ! substr_count( session('permisos'), ',28,' ) ) {
            Session::flash('message', 'Su usuario no tiene permiso para acceder a este módulo...');
            return redirect()->route( 'dashboard.index' );
        }
        # recuperamos la busqueda
        $sPaginaAM = $request->input( 'sPaginaAM', session( 'sPaginaAM', 10 ) );
        $sActivosAM = $request->input( 'sActivosAM', session( 'sActivosAM', '' ) );
        $sBusquedaAM = $request->input( 'sBusquedaAM', session( 'sBusquedaAM', '' ) );
        $sFiltroPadreAM = $request->input( 'sFiltroPadreAM', session( 'sFiltroPadreAM', '' ) );
        $sFiltroOrdenAM = $request->input( 'sFiltroOrdenAM', session( 'sFiltroOrdenAM', 'id' ) );
       # variables de sesion
        Libreria::putSesionSistema( $request, [
            'sPaginaAM'=>$sPaginaAM,
            'sActivosAM'=>$sActivosAM,
            'sBusquedaAM'=>$sBusquedaAM,
            'sFiltroPadreAM'=>$sFiltroPadreAM,
            'sFiltroOrdenAM'=>$sFiltroOrdenAM,
        ] );
        # buscamos los registros
        $oRegistros = Self::getRegistros( TRUE );
        # creamos la estructura del tree
        $oTree = Self::getRegistros( FALSE, true );
        $jTree = Self::getJSONMenu( $oTree );
        # Ruta del paginacion 
        $sRuteXPagina = 'admin.menus.index';
        # arrar para la seleccion de orden
        $aOrden = [ 
            'id'=>'id', 
            'id_Padre'=>'Padre', 
            'Nombre'=>'Nombre', 
            'Permiso'=>'Permiso', 
            'Orden'=>'Orden', 
        ];
        # cargamos la vista
        return view(
                'admin.menu.index', # admin/menu/index
                compact(
                    'jTree',
                    'aOrden',
                    'sPaginaAM',
                    'oRegistros', 
                    'sActivosAM',
                    'sBusquedaAM',
                    'sRuteXPagina',
                    'sFiltroPadreAM',
                    'sFiltroOrdenAM'
                )
            )
            ->with( 'aPadres', $this->aPadres )
            ->with( 'sSelecto', "2,7" );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        # cargamos la vista
        return view(
                'admin.menu.create' # admin/menu/create
            )
            ->with( 'aRutas', $this->aRutas )
            ->with( 'aPadres', $this->aPadres )
            ->with( 'aModulos', $this->aModulos )
            ->with( 'aAtributosOpciones', $this->aAtributosOpciones )
            ->with( 'sSelecto', "2,7" );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuRequest $request)
    {
        try {
            # Comienza la transacción
            DB::connection( 'DbIntranet' )->beginTransaction();
            #
            if(! isset( $request['Tipo'] ) ) $request['Tipo'] = '2';
            if(! isset( $request['Estatus'] ) ) $request['Estatus'] = '0';
            // Creamos el registro
            $oRegistro = Menu::create($request->all());
            # Compromete las consultas
            DB::connection( 'DbIntranet' )->commit();
        } catch( \Exception $e ) {
            # Rollback y luego redirigir volver al formulario con errores
            DB::connection( 'DbIntranet' )->rollBack();
            \Log::debug( 'MenuController@store' );
            \Log::debug( $e );
            return redirect()
                ->back()
                ->withInput()
                ->withErrors( [ Str::limit( $e, 150, '...' ) ] );
        }
        # redirecciona a ciudades
        Session::flash( 'success', 'Registro creado correctamente...' );
        return redirect()->route( 'admin.menus.index' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // buscamos el id pasado por parametro
        $oRegistro = Menu::select(
                'TblMenu.*',
                'appict.users.username'
            )
            ->join('appict.users', 'appict.users.id', '=', 'TblMenu.TblU_id' )
            ->find($id);
        // cargamos la vista
        return view(
            'admin.menu.show', # admin/menu/show
            compact(
                'oRegistro'
            )
        )
        ->with( 'aRutas', $this->aRutas )
        ->with( 'aPadres', $this->aPadres )
        ->with( 'aModulos', $this->aModulos )
        ->with( 'aAtributosOpciones', $this->aAtributosOpciones )
        ->with('sSelecto', "2,7");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $oRegistro = Menu::find($id);
        // cargamos la vista
        return view(
            'admin.menu.edit', # admin/menu/edit
            compact(
                'oRegistro'
            )
        )
        ->with( 'aRutas', $this->aRutas )
        ->with( 'aPadres', $this->aPadres )
        ->with( 'aModulos', $this->aModulos )
        ->with( 'aAtributosOpciones', $this->aAtributosOpciones )
        ->with('sSelecto', "2,7");
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
            # Comienza la transacción
            DB::connection( 'DbIntranet' )->beginTransaction();
            #
            if(! isset( $request['Tipo'] ) ) $request['Tipo'] = '2';
            if(! isset( $request['Estatus'] ) ) $request['Estatus'] = '0';
            # Creamos el registro
            $oRegistro = Menu::find( $id )
                ->fill( $request->all() )
                ->save();
            # Compromete las consultas
            DB::connection( 'DbIntranet' )->commit();
        } catch( \Exception $e ) {
            # Rollback y luego redirigir volver al formulario con errores
            DB::connection( 'DbIntranet' )->rollBack();
            \Log::debug( 'MenuController@update' );
            \Log::debug( $e );
            return redirect()
                ->back()
                ->withInput()
                ->withErrors( [ Str::limit( $e, 150, '...' ) ] );
        }
        # redirecciona a ciudades
        Session::flash( 'success', 'Registro creado correctamente...' );
        return redirect()->route( 'admin.menus.index' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $oRegistro = Menu::find($id);
        //
        $oRegistro->delete();
        // redirecciona a usuarios
        Session::flash('message', 'Registro eliminado correctamente...');
        return redirect()->route( 'admin.menus.index' );
    }

    /**
     * Devolvemos la busqueda de registros.
     *
     * @return \App\Models\Admin\Menu
     */
    public function getRegistros( $bPaginate = false, $bOrdenarArbol = false )
    {
        # recuperamos la busqueda
        $sPaginaAM = session( 'sPaginaAM', 10 );
        $sActivosAM = session( 'sActivosAM', '' );
        $sBusquedaAM = session( 'sBusquedaAM', '' );
        $sFiltroPadreAM = session( 'sFiltroPadreAM', '' );
        $sFiltroOrdenAM = session( 'sFiltroOrdenAM', 'id' );
        # obtenemos los registros
        $oRegistros = Menu::select(
                'TblMenu.*'
            )
            ->where(function ( $oQuery ) use ( $sActivosAM, $sFiltroPadreAM ){
                if ( $sActivosAM == 1 ) {
                    $oQuery->where( 'Estatus', '1' ); # mostramos solo los activos
                }
                if ( $sActivosAM == 2 ) {
                    $oQuery->where( 'Estatus', '0' ); # mostramos solo los inactivos
                }

                if ( $sFiltroPadreAM != '' ) {
                    $oQuery->where( 'id_Padre', $sFiltroPadreAM ); # filtramos por padre
                }
            })
            ->where(function ( $oQuery ) use ( $sBusquedaAM ){
                $oQuery->where( 'id', 'LIKE', '%'.$sBusquedaAM.'%' );
                $oQuery->orWhere( 'Nombre', 'LIKE', '%'.$sBusquedaAM.'%' );
                $oQuery->orWhere( 'id_Padre', 'LIKE', '%'.$sBusquedaAM.'%' );
            });

        if (!$bOrdenarArbol) {
            $oRegistros->orderBy( $sFiltroOrdenAM, 'ASC' );
        } else {
            $oRegistros->orderBy('id_Padre')
                ->orderBy('Orden')
                ->orderBy('id');
        }
        # si se selecciona todos
        $bPaginate = ( $sPaginaAM == 0 ) ? false : $bPaginate;
        # devolvemos paginacion o todos los registros
        return ( $bPaginate )? $oRegistros->paginate( $sPaginaAM ) : $oRegistros->get();
    }

    /**
     * Devolvemos la busqueda de registros.
     *
     * @return \App\Models\Admin\Menu
     */
    public function getJSONMenu($oRegistros = null)
    {
        # inicializamos la estructura
        $aArbol = [];
        # agregamos el nodo raiz
        $aArbol[] = [
            'id' => 0,
            'parent' => '#',
            'text' => 'Intranet',
            'icon' => 'fas fa-home',
            'state' => [
                'opened' => true,
                'disabled' => false,
            ]
        ];
        # recoremos los registros
        foreach ($oRegistros as $oRegistro) {
            # creamos el nodo
            $aNodo = [
                'id' => $oRegistro->id,
                'parent' => $oRegistro->id_Padre,
                'text' => "{$oRegistro->id} - {$oRegistro->Nombre}",
                'icon' => $oRegistro->Icono,
                'state' =>[
                    'disabled' => false,
                ]
            ];
            # determinamos el icono del nodo
            switch ($oRegistro->Tipo) {
                case 1:
                    $aNodo['type'] = 'default';
                    break;
                case 2:
                    $aNodo['type'] = 'modulo';
                    break;
                default:
                    $aNodo['type'] = 'accion';
                    break;
            }
            # lo añadimos a la estructura
            $aArbol[] = $aNodo;
        }
        # lo pasamos a JSON la estructura
        return json_encode($aArbol);
    }

    /**
     * elimina la busqueda del controlador.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function limpiar( Request $request )
    {
        # limpiamos la busqueda
        Libreria::delSesionSistema( $request, [
            'sPaginaAM',
            'sActivosAM',
            'sBusquedaAM',
            'sFiltroPadreAM',
            'sFiltroOrdenAM',
        ] );
        # redirecciona a clientes
        return redirect()->route( 'admin.menus.index' );
    }

    /**
     * cambia el cantidad de elemtos de la paginacio.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pagina( Request $request, $id )
    {
        # limpiamos la busqueda
        $request->session()->put( 'sPaginaAM', $id );
        # redirecciona a index
        return redirect()->route( 'admin.menus.index' );
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
            'admin.menu.imprimir', # admin/menu/imprimir
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
        return Excel::download(new MenusExport($oRegistros), 'Menus_'.date('d_m_Y_G_i_s').'.xlsx');
    }

}
