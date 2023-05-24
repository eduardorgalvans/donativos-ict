<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\PermanentesRequest;
use Illuminate\Support\Facades\Route;

use App\Exports\MenusExport;

use \App\Models\Admin\{
    Menu,
    Modulo,
    VariablesPermanentes
};

use DB, Session, Redirect, Libreria, Str, Excel;

class PermanentesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->aTipo = [
            'int' => 'success',
            'date' => 'warning',
            'json' => 'primary',
            'str' => 'info',
        ];

        $this->aTipoSelect = [
            'int' => 'Entero',
            'date' => 'Fecha',
            'json' => 'JSON',
            'str' => 'Cadena',
        ];


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
        $this->aRutas = [ ''=>'Selecciona...' ];        
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
        $sPaginaAVP = $request->input( 'sPaginaAVP', session( 'sPaginaAVP', 10 ) );
        $sActivosAVP = $request->input( 'sActivosAVP', session( 'sActivosAVP', '' ) );
        $sBusquedaAVP = $request->input( 'sBusquedaAVP', session( 'sBusquedaAVP', '' ) );
        $sFiltroOrdenAVP = $request->input( 'sFiltroOrdenAVP', session( 'sFiltroOrdenAVP', 'id' ) );
       # variables de sesion
        Libreria::putSesionSistema( $request, [
            'sPaginaAVP'=>$sPaginaAVP,
            'sActivosAVP'=>$sActivosAVP,
            'sBusquedaAVP'=>$sBusquedaAVP,
            'sFiltroOrdenAVP'=>$sFiltroOrdenAVP,
        ] );
        # buscamos los registros
        $oRegistros = Self::getRegistros( TRUE );
        # creamos la estructura del tree
        $oTree = Self::getRegistros( FALSE );
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
                'admin.permanentes.index', # admin/permanentes/index
                compact(
                    'jTree',
                    'aOrden',
                    'sPaginaAVP',
                    'oRegistros', 
                    'sActivosAVP',
                    'sBusquedaAVP',
                    'sRuteXPagina',
                    'sFiltroOrdenAVP'
                )
            )
            ->with( 'aTipo', $this->aTipo )
            ->with( 'aPadres', $this->aPadres )
            ->with( 'sSelecto', "2,69" );
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
                'admin.permanentes.create' # admin/permanentes/create
            )
            ->with( 'aTipoSelect', $this->aTipoSelect )
            ->with( 'aRutas', $this->aRutas )
            ->with( 'aPadres', $this->aPadres )
            ->with( 'aModulos', $this->aModulos )
            ->with( 'aAtributosOpciones', $this->aAtributosOpciones )
            ->with( 'sSelecto', "2,69" );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermanentesRequest $request)
    {
        try {
            # Comienza la transacción
            DB::connection( 'mysql' )->beginTransaction();
            #
            if(! isset( $request['Estatus'] ) ) $request['Estatus'] = '0';
            // Creamos el registro
            $oRegistro = VariablesPermanentes::create($request->all());
            # Compromete las consultas
            DB::connection( 'mysql' )->commit();
        } catch( \Exception $e ) {
            # Rollback y luego redirigir volver al formulario con errores
            DB::connection( 'mysql' )->rollBack();
            \Log::debug( 'MenuController@store' );
            \Log::debug( $e );
            return redirect()
                ->back()
                ->withInput()
                ->withErrors( [ Str::limit( $e, 150, '...' ) ] );
        }
        # redirecciona a ciudades
        Session::flash( 'success', 'Registro creado correctamente...' );
        return redirect()->route( 'admin.permanentes.index' );
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
        $oRegistro = VariablesPermanentes::select(
                'TblVariablesPermanentes.*',
                'appict.users.username'
            )
            ->join('appict.users', 'appict.users.id', '=', 'TblVariablesPermanentes.TblU_id' )
            ->find($id);
        // cargamos la vista
        return view(
            'admin.permanentes.show', # admin/permanentes/show
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
    public function getRegistros( $bPaginate = false )
    {
        # recuperamos la busqueda
        $sPaginaAVP = session( 'sPaginaAVP', 10 );
        $sActivosAVP = session( 'sActivosAVP', '' );
        $sBusquedaAVP = session( 'sBusquedaAVP', '' );
        $sFiltroOrdenAVP = session( 'sFiltroOrdenAVP', 'id' );
        # obtenemos los registros
        $oRegistros = VariablesPermanentes::select(
                'TblVariablesPermanentes.*'
            )
            ->where(function ( $oQuery ) use ( $sActivosAVP ){
                if ( $sActivosAVP == 1 ) {
                    $oQuery->where( 'Estatus', '1' ); # mostramos solo los activos
                }
                if ( $sActivosAVP == 2 ) {
                    $oQuery->where( 'Estatus', '0' ); # mostramos solo los inactivos
                }
            })
            ->where(function ( $oQuery ) use ( $sBusquedaAVP ){
                $oQuery->where( 'id', 'LIKE', '%'.$sBusquedaAVP.'%' );
                $oQuery->orWhere( 'Variable', 'LIKE', '%'.$sBusquedaAVP.'%' );
                $oQuery->orWhere( 'Valor', 'LIKE', '%'.$sBusquedaAVP.'%' );
            })
            ->orderBy( $sFiltroOrdenAVP, 'ASC' );
        # si se selecciona todos
        $bPaginate = ( $sPaginaAVP == 0 ) ? false : $bPaginate;
        # devolvemos paginacion o todos los registros
        return ( $bPaginate )? $oRegistros->paginate( $sPaginaAVP ) : $oRegistros->get();
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
            'sPaginaAVP',
            'sActivosAVP',
            'sBusquedaAVP',
            'sFiltroOrdenAVP',
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
        $request->session()->put( 'sPaginaAVP', $id );
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
