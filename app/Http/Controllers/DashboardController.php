<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\User;
use \App\Models\RH\{
    Personal,
    Trabajador
};
use \App\Models\Admin\{
    Modulo,
    SLMacAddress
};
use \App\Models\RH\EVA\{
    RHObjetivos,
    RHObjetivosFechas
};
use \App\Models\Escolares\{
    Grados,
    HistorialAlumnosenGrupos
};

use Libreria, Auth, DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # periodo de admision activo
        $oPeriodos = Libreria::getIdPerActSig();
        #
        $oModulos = Modulo::whereIn( 'id', explode( ',', session('permisos', '' ) ) )
            ->get();
        #
        $modulosRaiz = true;

        # Accesos directos del usuario
        $oAccesosDirectos = $oModulos->filter(function($elemento, $indice){
            return in_array( $elemento->id, explode( ',', Auth::user()->Accesos ?? '' ) );
        });
        # recuperamos los usuarios para verificar los usuarios en línea
        $oUsers = User::select(
                'users.*',
                'TblPersonal.idempleado'
            )
            ->leftJoin( 'DbInscripciones.TblPersonal', 'TblPersonal.TblDGP_id', '=', 'users.TblDGP_id' )
            ->get();

        # Cargamos la vista
        return view(
                'dashboard.index', # dashboard/index
                compact(
                    'oUsers',
                    'oModulos',
                    'modulosRaiz',
                    'oAccesosDirectos'
                )
            )
            ->with( 'sSelecto', "1" );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function opcionesModulo($idModulo)
    {
        #
        $oModulos = Modulo::where('IdPadre', $idModulo)
            ->where('Tipo', '<>', 3)
            ->get();
        #
        $modulosRaiz = false;
        # Cargamos la vista
        return view(
            'dashboard.index', # dashboard/index
            compact(
                'oModulos',
                'modulosRaiz'
            )
        )
        ->with( 'sSelecto', "2,4" );
    }

    /**
     * se realiza la búsqueda de un término y devuelve un JSON con el resultado.
     *
     * @param  str  $term
     * @return JSON
     */
    public function personal(Request $request)
    {
        # recuperamos el texto de búsqueda
        $sTerm = $request['term'];
        #
        $oRegistros = Trabajador::select(
                'TblTrabajadores.id',
                'TblTrabajadores.TblDGP_id',
                'TblTrabajadores.NumTrabajador',
                'TblTrabajadores.FechaBaja',
                'TblTrabajadores.Estatus',
                'TblDGP.Nombres',
                'TblDGP.ApPaterno',
                'TblDGP.ApMaterno',
                'TblCPA.id AS TblCPA_id',
                DB::raw( "CONCAT( TblDGP.ApPaterno,' ',TblDGP.ApMaterno,' ',TblDGP.Nombres ) AS NombreFull"),
            )
            ->join('DbInscripciones.TblDatosGeneralesPersonas AS TblDGP', function( $oJoin ){
                $oJoin->on('TblTrabajadores.TblDGP_id', '=', 'TblDGP.IdPersona')
                    ->whereNull('TblDGP.deleted_at');
            })
            ->leftJoin('DbInscripciones.TblPagosPeriodosAcademicos as TblPPA', function( $oJoin ){
                $oJoin->on( 'TblPPA.Status', '=', DB::raw( '1' ) )
                    ->whereNull( 'TblPPA.deleted_at' );
            })
            ->leftJoin('DbInscripciones.TblCatPeriodosAdmisiones as TblCPA', function( $oJoin ){
                $oJoin->on( 'TblCPA.periododeadmision', '=', DB::raw( 'TblPPA.periodoacademico' ) )
                    ->whereNull( 'TblCPA.deleted_at' );
            })
            ->where( function( $oQuery ) use( $sTerm ){
                if ( is_numeric( trim( $sTerm ) ) ) {
                    $oQuery->where( 'TblTrabajadores.NumTrabajador', trim( $sTerm ) );
                    $oQuery->orWhere( 'TblTrabajadores.TblDGP_id', trim( $sTerm ) );
                } elseif (trim($sTerm) != '') {
                    $oQuery->whereRaw( "CONCAT(TblDGP.Nombres,' ',TblDGP.ApPaterno,' ', TblDGP.ApMaterno ) LIKE '%".$sTerm."%' " );
                    $oQuery->orWhereRaw( "CONCAT(TblDGP.ApPaterno, ' ', TblDGP.ApMaterno, ' ',TblDGP.Nombres ) LIKE '%".$sTerm."%' " );
                    $oQuery->orWhere( 'TblDGP.Nombres', 'LIKE', DB::raw('"%'.$sTerm.'%"') );
                    $oQuery->orWhere( 'TblDGP.ApPaterno', 'LIKE', DB::raw('"%'.$sTerm.'%"') );
                    $oQuery->orWhere( 'TblDGP.ApMaterno', 'LIKE', DB::raw('"%'.$sTerm.'%"') );
                }
            })
            ->orderByRaw( "CONCAT_WS(' ', TRIM( TblDGP.ApPaterno ), TRIM( TblDGP.ApMaterno ), TRIM( TblDGP.Nombres ) )")
            ->take(5)
            ->get();
        # formateamos el resultado para mostrarlos en el auto completar
        $aBusqueda = [];
        foreach ( $oRegistros as $key => $oRegistro ) {
            # recuperamos la columna de los valores
            $aColumna = array_column( $aBusqueda, 'value' );
            # revisamos si no existe en la búsqueda y los agregamos
            if ( ! in_array( $oRegistro->NombreFull, $aColumna ) ) {
                $aBusqueda[ $key ][ 'id' ] = $oRegistro->TblDGP_id;
                $aBusqueda[ $key ][ 'value' ] = $oRegistro->NombreFull;
                $aBusqueda[ $key ][ 'Nombres' ] = $oRegistro->Nombres;
                $aBusqueda[ $key ][ 'ApPaterno' ] = $oRegistro->ApPaterno;
                $aBusqueda[ $key ][ 'ApMaterno' ] = $oRegistro->ApMaterno;
                $aBusqueda[ $key ][ 'TblCPA_id' ] = $oRegistro->TblCPA_id;
            }
            # si está vacío creamos el primer nodo
            if ( count( $aBusqueda ) == 0 ) {
                $aBusqueda[ $key ][ 'id' ] = $oRegistro->TblDGP_id;
                $aBusqueda[ $key ][ 'value' ] = $oRegistro->NombreFull;
                $aBusqueda[ $key ][ 'Nombres' ] = $oRegistro->Nombres;
                $aBusqueda[ $key ][ 'ApPaterno' ] = $oRegistro->ApPaterno;
                $aBusqueda[ $key ][ 'ApMaterno' ] = $oRegistro->ApMaterno;
                $aBusqueda[ $key ][ 'TblCPA_id' ] = $oRegistro->TblCPA_id;
            }
        }
        # devolvemos el resultado formateado
        return \Response::json( $aBusqueda );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function foto( $TblDGP_id = 0 )
    {
        # recuperamos el registro del trabajador
        $oRegistro = Trabajador::select( 'NumTrabajador' )
            ->where( 'TblDGP_id', $TblDGP_id )
            ->first();
        # Cargamos la vista
        return json_encode( [ 'foto' => Libreria::obtenerFotoTrabajador( $oRegistro->NumTrabajador ) ] );
    }

}
