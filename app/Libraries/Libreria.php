<?php
namespace App\Libraries;

use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\Redis;

use \App\Models\{
    User,
    DatosGeneralesPersona
};
use \App\Models\RH\{
    Puesto,
    Trabajador,
    ArbolPuesto,
    HistorialPuestos,
    Personal
};
use App\Models\Pagos\{
    PagoOrdinario,
    PagosConceptos,
    PagosPeriodosAcademicos
};
use \App\Models\Admin\{
    Menu,
    Notificaciones,
    CorreoConfiguracion,
    VariablesPermanentes
};
use \App\Models\Escolar\{
    Grados,
    Alumno,
    Familia,
    CatFamilia,
    CatPeriodosAdmisiones,
    HistorialAlumnosenGrupo,
    Calificacion
};
use \App\Models\Escolar\Aspirantes\{
    AspCalificacion, 
    AspCalificacionCriterio, 
    AspCalificacionSubCriterio,
    AspObservacion, 
    AspObservacionCriterios,
    AspDocumentoEvaluacion, 
    AspProspecto,
    AspProspectoDatosContacto,
    AspProspectoSeguimiento,
    Aspirante,
    ACMOpcion,
    AspFamilia,
    AspCatFecha,
    AspDocumento,
    AspCatFamilia,
    AspCatMateria,
    AspCatCriterio,
    AspCatDocumento,
    AspEstatusCorreo,
    AspCatSubCriterio,
    AspEstatusCuestionario,
    AspCatTipoIngreso,
    AspCatEstatusEvaluacion,
};

use \App\Models\Escolares\{
    Niveles
};

use \App\Models\Escolar\Encuestas\{
    EncuestasSubCategoria,
    EncuestasRespuestasCerradasCatalogo,
    EncuestasPreguntas,
    EncuestasRespuestasCerradas
};

use \App\Models\GestorDocs\{
    GDCarpeta,GDPermiso
};
use App\Models\Solicitudes\{
    SSTareas,
    SSTareasLog,
    SSEtiquetas,
    SSDepartamentos,
    SSTareasComentariosLectura
};
use App\Models\Escolar\Talleres\{
    Talleres,
    TalleresArticulos,
    TalleresInscripciones
};

/** */
use App\Models\General\{
    Estado
};

use Route, Auth, DateTime, Str, DB, Form, Config, Mail;

class Libreria {

    /**
     * retorna el nombre de la persona pasada por parametro. o el capo selecccionado en el segundo parametro
     *
     * @param  int $tblDGP_id
     * @param  int $sCampo
     * @return string
     */
    public static function getNombrePersona( $tblDGP_id='', $sCampo='' )
    {
        $users = DatosGeneralesPersona::where( 'idpersona', $tblDGP_id )
            ->first();
        #
        if ( is_null( $users ) ) {
            $Nombre = ".";
        }else{
            if ( $sCampo == '' ) {
                $Nombre = $users->Nombres.' '.$users->ApPaterno.' '.$users->ApMaterno;
            }else{
                $Nombre = @$users->{$sCampo};
            }
        }
        return $Nombre;
    }

    public static function obtenerFotoTrabajador($numTrabajador)
    {
        $sDirectorio = 'assets/img/personal';

        if (!$numTrabajador) {
            return "/$sDirectorio/noimage.jpg";
        }

        $aImagenes = glob("$sDirectorio/$numTrabajador.*");

        if (count($aImagenes)) {
            $foto = $aImagenes[0];
        } else {
            $foto = "$sDirectorio/noimage.jpg";
        }

        return "/$foto";
    }

    /**
     * Devuelve un arreglo con los países.
     *
     * @return array  El arreglo de países.
     */
    public static function obtenerPaises($usarISO = false, $devolverJSON = false)
    {
        $aPaises = \App\Models\General\Pais::where('orden', '<>', 0)
            ->orderBy('orden')
            ->pluck('nombre', ($usarISO ? 'ISO' : 'id'));

        $aPaises2 = \App\Models\General\Pais::where('orden', 0)
            ->orderBy('orden')
            ->pluck('nombre', ($usarISO ? 'ISO' : 'id'));

        $aPaises->put(0, '-----------------------------------------------------------');
        $aPaises = $aPaises->union($aPaises2);

        if ($devolverJSON) {
            $paises = [];
            foreach ($aPaises->toArray() as $id => $pais) {
                $paises[] = [
                    'value' => $id,
                    'label' => $pais
                ];
            }

            return $paises;
        }

        return $aPaises->toArray();
    }

    /**
     * Devuelve un arreglo con los estados del país especificado.
     *
     * @param string $idPais  Código ISO del País especificado.
     * @return array          El arreglo de estados del país.
     */
    public static function obtenerEstados($isoPais, $devolverJSON = false)
    {
        if (!$isoPais) {
            return ['' => '- Seleccione -'];
        }

        if (is_numeric($isoPais)) {
            $oPais = \App\Models\General\Pais::find($isoPais);

            $isoPais = optional($oPais)->ISO ?? '';
        }

        $aEstados = [];
        if ($isoPais == 'MX') {
            $aEstados = \App\Models\General\Estado::orderBy('nombre')
                ->pluck('nombre', 'id')
                ->prepend('- Seleccione -', '')
                ->toArray();
        } else {
            $aEstados = \App\Models\General\EstadoMundial::where('Pais', $isoPais)
                ->orderBy('Estado')
                ->pluck('Estado', 'id')
                ->prepend('- Seleccione -', '')
                ->toArray();
        }

        if ($devolverJSON) {
            $estados = [];
            foreach ($aEstados as $id => $estado) {
                $estados[] = [
                    'id' => $id,
                    'nombre' => $estado
                ];
            }

            return $estados;
        }

        return $aEstados;
    }

    /**
     * Devuelve un arreglo con los municipios del estado y país especificados.
     *
     * @param string $idPais  Código ISO del País especificado.
     * @param int $idEstado   Estado especificado.
     * @return array          El arreglo de municipios del estado.
     */
    public static function obtenerMunicipios($isoPais, $idEstado, $devolverJSON = false)
    {
        if (!$isoPais || !$idEstado) {
            return ['' => '- Seleccione -'];
        }

        if (is_numeric($isoPais)) {
            $oPais = \App\Models\General\Pais::find($isoPais);

            $isoPais = optional($oPais)->ISO ?? '';
        }

        $aMunicipios = [];
        if ($isoPais == 'MX') { # 153 = México
            $aMunicipios = \App\Models\General\Municipio::where('estado_id', $idEstado)
                ->orderBy('nombre')
                ->pluck('nombre', 'id')
                ->prepend('- Seleccione -', '')
                ->toArray();
        } else {
            $oEstado = \App\Models\General\EstadoMundial::find($idEstado);

            $aMunicipios = \App\Models\General\CiudadMundial::where('Pais', $isoPais)
                ->where('Estado', $oEstado->ISO)
                ->orderBy('Ciudad')
                ->pluck('Ciudad', 'id')
                ->prepend('- Seleccione -', '')
                ->toArray();
        }

        if ($devolverJSON) {
            $municipios = [];
            foreach ($aMunicipios as $id => $municipio) {
                $municipios[] = [
                    'id' => $id,
                    'nombre' => $municipio
                ];
            }

            return $municipios;
        }

        return $aMunicipios;
    }

    /**
     * Devuelve un arreglo con las localidades del municipio especificado.
     *
     * @param int $idMunicipio  Municipio especificado.
     * @return array            El arreglo de localidades del municipio.
     */
    public static function obtenerLocalidades($idMunicipio, $devolverJSON = false)
    {
        if (!$idMunicipio) {
            return ['' => '- Seleccione -'];
        }

        $aLocalidades = [];
        $aLocalidades = \App\Models\General\Localidad::where('municipio_id', $idMunicipio)
            ->orderBy('nombre')
            ->pluck('nombre', 'id')
            ->prepend('- Seleccione -', '')
            ->toArray();

        if ($devolverJSON) {
            $localidades = [];
            foreach ($aLocalidades as $id => $localidad) {
                $localidades[] = [
                    'id' => $id,
                    'nombre' => $localidad
                ];
            }

            return $localidades;
        }

        return $aLocalidades;
    }

    /**
     * Devuelve un arreglo con las secciones del colegio.
     *
     * @return array   El arreglo de secciones del colegio.
     */
    public static function obtenerSecciones()
    {
        return \App\Models\RH\Puesto::where('idPadre', 0)
            ->orderBy('id')
            ->pluck('Nombre', 'id')
            ->toArray();
    }

    /**
     * Almacena en un arreglo los puestos que pertenecen a una sección específica.
     *
     * @param int $idSeccion   La sección de la que se extraerán los puestos.
     * @param array $aPuestos  El arreglo de puestos.
     */
    public static function obtenerPuestosPorSeccion($idSeccion, &$aPuestos)
    {
        $aPuestosTmp = \App\Models\RH\Puesto::where('idPadre', $idSeccion)
            ->orderBy('Nombre')
            ->pluck('Nombre', 'id')
            ->toArray();

        foreach ($aPuestosTmp as $idPuesto => $nombre) {
            $aPuestos[$idPuesto] = $nombre;

            self::obtenerPuestosPorSeccion($idPuesto, $aPuestos);
        }
    }

    /**
     * recibimos un array con los elementos a almacenar
     *
     * @param  array $aVariables
     * @return null
     */
    public static function putSesionSistema( Request $request, $aVariables = [] )
    {
        // recorremos el array de elermntos
        foreach ( $aVariables as $key => $value ) {
            # code...
            $request->session()->put( $key, $value );
        }
    }

    /**
     * recibimos un array con los elementos a eleminar
     *
     * @param  array $aVariables
     * @return null
     */
    public static function delSesionSistema( Request $request, $aVariables = [] )
    {
        // recorremos el array de elermntos
        foreach ( $aVariables as $key => $value ) {
            $request->session()->forget( $value );
        }
    }

    /**
     * recibimos un array con los elementos a eleminar
     *
     * @param  array $aVariables
     * @return null
     */
    public static function getRouteBase()
    {
        $sRoute = \Route::currentRouteName();
        $aSegmentos = explode( '.', $sRoute );
        array_pop( $aSegmentos );
        $sRuta = implode( '.', $aSegmentos ) . '.';
        return $sRuta;
    }

    /**
     * Devuelve un arreglo con los IDs de los módulos a los que tiene derecho de acceso
     * el usuario.
     *
     * @return array Arreglo con los IDs de módulos.
     */
    public static function obtenerPermisosUsuario()
    {
        // Se obtienen los puestos del usuario que entró al sistema y los perfiles asociados
        // a los mismos.
        $oPuestos = \App\Models\RH\HistorialPuestos::select(
                'TblP.id',
                'TblP.Perfiles'
            )
            ->join('DbIntranet.TblPuestos AS TblP', function($join){
                $join->on('TblHistorialPuestos.TblPP_id', '=', 'TblP.id')
                    ->whereNull('TblP.deleted_at');
            })
            ->where('TblHistorialPuestos.TblDGP_id', \Auth::user()->TblDGP_id)
            ->where('TblHistorialPuestos.TblPF_id', 0)
            ->get();

        $aPermisos = [];
        foreach ($oPuestos as $oPuesto) {
            $aPerfiles = explode(',', $oPuesto->Perfiles);

            $oPermisos = \App\Models\Admin\Perfil::select('Permisos')
                ->whereIn('id', $aPerfiles)
                ->get();

            $aTempPermisos = [];
            foreach ($oPermisos as $oPermiso) {
                $aTempPermisos[] = $oPermiso->Permisos;
            }

            $aPermisos[] = implode(',', $aTempPermisos);
        }

        /*
         * La lista de permisos resultante se une en una sola cadena, que posteriormente se separa
         * por comas para quedarnos al final con los IDs únicos, en caso de que los perfiles asignados
         * tengan permisos repetidos.
         */
        $aPermisos = array_unique(explode(',', implode(',', $aPermisos)));

        $aPermisos = array_map( function( $id ){ return abs( (int) ( $id ?? 0 ) ); }, $aPermisos);

        return $aPermisos;
    }

    /**
     * retorna una cadena con la estructura de menú.
     *
     * @param  int $id
     * @return string
     */
    public static function fGetMenu( $id='' )
    {
        # \Log::info( $id );
        # \Log::info('-=-=-=-=-');
        global $sCadenaMenu;
        # inicializamos la cadena
        $sCadenaMenu = '';
        # obtenemos todos los registros
        $oJsonMenu = Menu::select(
                'TblMenu.id',
                'TblMenu.id_Padre',
                'TblMenu.Nombre',
                'TblMenu.Ruta',
                'TblMenu.Icono',
                'TblMenu.Permiso',
                'TblMenu.Tipo',
                \DB::raw('(Select Count(*) From TblMenu AS TblMenu2 Where TblMenu.id = TblMenu2.id_Padre ) As Hijos')
            )
            ->orderBy( 'TblMenu.id_Padre' )
            ->orderBy( 'TblMenu.Orden' )
            ->get()
            ->toJson();
        # recoremos el
        $sCadenaMenu = Self::fCreaMenu( $id, $oJsonMenu, 0, 0 );
        # retornamos el JSon
        return $sCadenaMenu;
    }

    /**
     * retorna la el nombre de la perosna pasada por parametro.
     *
     * @param  int $id
     * @return string
     */
    public static function fCreaMenu($id='', $oJson='[]', $iBuscar=0, $iNivel=0)
    {
        //
        $aJson = json_decode($oJson, true);
        //
        $hasChildren = false;
        $outputHtml = '%s';
        $childrenHtml = '';

        foreach($aJson as $oValue){
            // si no tiene permiso se lo brinca
            if (!substr_count( session('permisos'), ','.$oValue['Permiso'].',' ) ) {
                continue;
            }
            //
            if ($oValue['id_Padre'] == $iBuscar) {
                $sRuta = ( ( $oValue['Ruta'] == '#' ) ? '#' : ( Route::has( $oValue['Ruta'] ) ? route( $oValue['Ruta'] ) : '#No_existe_la_ruta' ) );
                $bEsActivo = in_array( $oValue['id'], explode( ",", $id ) );
                # \Log::info( $oValue['id'] );
                # \Log::info( explode( ",", $id ) );
                # \Log::info( $bEsActivo );
                if ($oValue['Hijos'] > 0) {
                    $hasChildren = true;

                    $childrenHtml .= '  <div class="menu-item has-sub'.( ( $bEsActivo )? ' active' : '').'">';
                    $childrenHtml .= '      <a href="'.$sRuta.'" class="menu-link">';
                    $childrenHtml .= '          <div class="menu-icon">';
                    $childrenHtml .= '              <i class="'.$oValue['Icono'].'"></i>';
                    $childrenHtml .= '          </div>';
                    $childrenHtml .= '          <div class="menu-text" data-ids="'.$oValue['id'].'" >'.$oValue['Nombre'].'</div>';
                    $childrenHtml .= '          <div class="menu-caret"></div>';
                    $childrenHtml .= '      </a>';
                    $childrenHtml .= '      <div class="menu-submenu">';
                    $childrenHtml .= '          '.Self::fCreaMenu( $id, $oJson, $oValue['id'], 1 );
                    $childrenHtml .= '      </div>';
                    $childrenHtml .= '  </div>';
                    /*
                    $childrenHtml .= '  <li '.( ( $bEsActivo )? 'class="active"' : '').'>';
                    $childrenHtml .= '      <a href="'.$sRuta.'">';
                    $childrenHtml .= '          <i class="'.$oValue['Icono'].'" aria-hidden="true"></i>';
                    $childrenHtml .= '          <span class="nav-label">'.$oValue['Nombre'].'</span><span class="fa arrow"></span>';
                    $childrenHtml .= '      </a>';
                    $childrenHtml .= '      <ul class="nav nav-second-level collapse">';
                    $childrenHtml .= '          '.Self::fCreaMenu( $id, $oJson, $oValue['id'], 1 );
                    $childrenHtml .= '      </ul>';
                    $childrenHtml .= '  </li>';
                    */
                }else{
                    if ($iNivel==0) {
                        $childrenHtml .= ' <div class="menu-item '.( ( $bEsActivo )? ' active' : '').'">';
                        $childrenHtml .= '     <a href="'.$sRuta.'" class="menu-link">';
                        $childrenHtml .= '         <div class="menu-icon">';
                        $childrenHtml .= '             <i class="'.$oValue['Icono'].'"></i>';
                        $childrenHtml .= '         </div>';
                        $childrenHtml .= '         <div class="menu-text" data-ids="'.$oValue['id'].'" >'.$oValue['Nombre'].'</div>';
                        $childrenHtml .= '     </a>';
                        $childrenHtml .= ' </div>';
                        /*
                        $childrenHtml .= '  <li '.( ( $bEsActivo )? 'class="active"' : '').'>';
                        $childrenHtml .= '      <a href="'.$sRuta.'">';
                        $childrenHtml .= '          <i class="'.$oValue['Icono'].'" data-ids="'.$oValue['id'].'" aria-hidden="true"></i>';
                        $childrenHtml .= '          <span class="nav-label">';
                        $childrenHtml .= '                  '.$oValue['Nombre'].'';
                        $childrenHtml .= '          </span>';
                        $childrenHtml .= '      </a>';
                        $childrenHtml .= '  </li>';
                        */
                    }else{
                        $childrenHtml .= '  <div class="menu-item'.( ( $bEsActivo )? ' active' : '').'">';
                        $childrenHtml .= '      <a href="'.$sRuta.'" class="menu-link">';
                        $childrenHtml .= '         <div class="menu-icon">';
                        $childrenHtml .= '             <i class="'.$oValue['Icono'].'"></i>';
                        $childrenHtml .= '         </div>';
                        $childrenHtml .= '          <div class="menu-text" data-ids="'.$oValue['id'].'" >'.$oValue['Nombre'].'</div>';
                        $childrenHtml .= '      </a>';
                        $childrenHtml .= '  </div>';
                        /*
                        $childrenHtml .= '  <li '.( ( $bEsActivo )? 'class="active"' : '').'>';
                        $childrenHtml .= '      <a href="'.$sRuta.'">';
                        $childrenHtml .= '          <i class="'.$oValue['Icono'].'" data-ids="'.$oValue['id'].'" aria-hidden="true"></i>';
                        $childrenHtml .= '          '.$oValue['Nombre'].'';
                        $childrenHtml .= '      </a>';
                        $childrenHtml .= '  </li>';
                        */
                    }
                }
            }
        }
        // Without children, we do not need the <ul> tag.
        if (!$hasChildren) {
            $outputHtml = '';
        }
        // Returns the HTML
        return $childrenHtml;
        //return $sCadenaMenu;
    }

    /**
     * retorna el valor de la "variable" seleccionada en la tabla TblVariablesPermanentes
     *
     * @param  string $sVariable,
     * @return integer o date o json o string
     */
    public static function getVariablePermante($sVariable='', $sDefault = '')
    {
        # buscamos la variable
        $oVariable = VariablesPermanentes::firstOrCreate(['Variable' => \DB::raw("'$sVariable'")]);
        # determinamos si se creo si es asi agregamos el valor poe defecto
        if( $oVariable->wasRecentlyCreated ){
            $oVariable->Valor = $sDefault;
            $oVariable->save();
        }
        # validamos el tipo de variable
        switch ( $oVariable->Tipo ) {
            case 'int':
                return (int) $oVariable->Valor;
                break;

            case 'date':
                $sValor = $oVariable->Valor;
                # si tiene / los cambiamos por -
                if ( strpos( $sValor , "/" ) === true ) {
                    $sValor = str_replace('/', '-', $sValor );
                }
                return date( "Y-m-d", strtotime( $sValor ) );
                break;

            case 'json':
                return json_decode( $oVariable->Valor );
                break;

            default:
                return $oVariable->Valor;
                break;
        }
    }

    /**
     * alamcena el valos de la "variable" seleccionada en la tabla TblVariablesPermanentes
     *
     * @param  string $sVariable,
     * @return bool
     */
    public static function putVariablePermante($sVariable='', $sValor='', $sTipo='' )
    {
        # buscamos la variable
        $oVariable = VariablesPermanentes::firstOrCreate(['Variable' => \DB::raw("'$sVariable'")]);

        $oVariable->Valor = $sValor;
        $oVariable->Tipo = ( $sTipo == '' ) ? 'str' : $sTipo;

        $oVariable->save();

        return TRUE;
    }

    /**
     * Retorna una cadena representando el estatus del registro.
     *
     * @param  int $id
     * @return string
     */
    public static function GetEstatus($id='')
    {
        switch ($id) {
            case '1': return '<b class="text-success"><i class="fas fa-check-circle"></i></b>';
                break;
            case '0': return '<b class="text-danger"><i class="fas fa-times-circle"></i></b>';
                break;
            default: return '<b class="text-danger"><i class="fas fa-times-circle"></i></b>';
                break;
        }
    }

    /**
     * Retorna una cadena representando el estatus del registro.
     *
     * @param  int $id
     * @return string
     */
    public static function GetAlerta($id='')
    {
        switch ($id) {
            case '0': return '<b class="text-success"><i class="fas fa-clipboard-check"></i></b>';
                break;
            case '1': return '<b class="text-danger"><i class="fas fa-exclamation-triangle"></i></b>';
                break;
            default: return '<b class="text-danger"><i class="fas fa-times-circle"></i></b>';
                break;
        }
    }

    /**
     * Devuelve una cadena normalizada para un nombre de archivo.
     *
     * @param  string $sCadena
     * @return string
     */
    public static function getNormalizeString( $sCadena = '' )
    {
       $sCadena = strip_tags($sCadena);
       $sCadena = preg_replace('/[\r\n\t ]+/', ' ', $sCadena);
       $sCadena = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $sCadena);
       $sCadena = strtolower($sCadena);
       $sCadena = html_entity_decode( $sCadena, ENT_QUOTES, "utf-8" );
       $sCadena = htmlentities($sCadena, ENT_QUOTES, "utf-8");
       $sCadena = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $sCadena);
       $sCadena = str_replace(' ', '_', $sCadena);
       $sCadena = rawurlencode($sCadena);
       $sCadena = str_replace('%', '_', $sCadena);
       return $sCadena;
    }

    /**
     * recibimos un array con los elementos a eleminar
     *
     * @param  array $aOriginal
     * @param  array $aMofificado
     * @return json
     */
    public static function getJSONModificados( $aOriginal = [], $aMofificado = [] )
    {
        $aKeysModificados = [];
        // recorremos el array de elermntos
        foreach ($aOriginal as $key => $value) {
            # detectamos las diferencias
            if ( $aOriginal[ $key ] != $aMofificado[ $key ] ) {
                # recordamos la key
                array_push( $aKeysModificados, $key );
            }
        }
        # creamso la estructura
        $aModOriginal = [];
        $aModMofificado = [];
        foreach ($aOriginal as $key => $value) {
            # detectamos las diferencias
            if ( in_array( $key, $aKeysModificados ) ) {
                # creamso las 2  estruecturas
                $aModOriginal[ $key ]  = $aOriginal[ $key ];
                $aModMofificado[ $key ]  = $aMofificado[ $key ];
            }
        }
        if ( count( $aKeysModificados ) == 0 ) {
            $aModMofificado = $aMofificado;
        }
        # regresamso el json
        return json_encode([
            'original' => $aModOriginal,
            'modificado' => $aModMofificado,
        ]);
    }

    /**
     * devuelve una cadena con la forma de tiempo atras en base a la fecha y hora quese paso
     *
     * @param  string $fecha
     * @param  string $hora
     * @return string
     */
    public static function imprimirTiempo($fecha,$hora){
        $start_date = new DateTime($fecha." ".$hora);
        $since_start = $start_date->diff(new DateTime(date("Y-m-d")." ".date("H:i:s")));
        echo "Hace ";
        if($since_start->y==0){
            if($since_start->m==0){
                if($since_start->d==0){
                   if($since_start->h==0){
                       if($since_start->i==0){
                          if($since_start->s==0){
                             echo $since_start->s.' segundos';
                          }else{
                              if($since_start->s==1){
                                 echo $since_start->s.' segundo';
                              }else{
                                 echo $since_start->s.' segundos';
                              }
                          }
                       }else{
                          if($since_start->i==1){
                              echo $since_start->i.' minuto';
                          }else{
                            echo $since_start->i.' minutos';
                          }
                       }
                   }else{
                      if($since_start->h==1){
                        echo $since_start->h.' hora';
                      }else{
                        echo $since_start->h.' horas';
                      }
                   }
                }else{
                    if($since_start->d==1){
                        echo $since_start->d.' día';
                    }else{
                        echo $since_start->d.' días';
                    }
                }
            }else{
                if($since_start->m==1){
                   echo $since_start->m.' mes';
                }else{
                    echo $since_start->m.' meses';
                }
            }
        }else{
            if($since_start->y==1){
                echo $since_start->y.' año';
            }else{
                echo $since_start->y.' años';
            }
        }
    }

    /**
     * retorna una cadena preformateada con labels o sin ellos dependiendo del la
     * bandera (2do parametro)
     *
     * @param  array $aNiveles
     * @param  bolean $bLista
     * @return string
     */
    public static function ArrNivToLabels( $aNiveles=[], $bLista=false )
    {
        # Cadena a regresar
        $sLabels = '';
        # recoremos el array
        foreach ($aNiveles as $key => $value) {
            # datos del nivel que recuperamos del array
            $oRegistro = Niveles::select(
                    'NombreNivel',
                    'Color'
                )
                ->orderBy('Orden')
                ->find($value);
            # no es nulo creamos/agregamos la cadena
            if (!is_null($oRegistro)) {
                # lo agregamso a la cadena
                $sLabels .= ($bLista)?
                    Str::limit($oRegistro->NombreNivel, 3, '') :
                    '<span class="badge bg-'.$oRegistro->Color.'">'.Str::limit($oRegistro->NombreNivel, 3, '').'</span>';
            }
        }
        return $sLabels;
    }

    /**
     * retorna una cadena que representa el nivel pasada por parametro
     *
     * @param  int $iNivel
     * @return string
     */
    public static function NivelLetra( $iNivel = null )
    {
        switch ( $iNivel ) {
            case 1:
                  $sNivelLetra = "Primaria";
                break;
            case 2:
                  $sNivelLetra = "Secundaria";
                break;
            case 3:
                  $sNivelLetra = "Bachillerato";
                break;
            case 4:
                  $sNivelLetra = "Preescolar";
                break;
            default:
                $sNivelLetra = "";
                break;
        }
        return $sNivelLetra;
    }

    /**
     * retorna una cadena que representa el nivel abrebiado pasada por parametro
     *
     * @param  int $iNivel
     * @return string
     */
    public static function NivelLetraAbreviada( $iNivel = null )
    {
        switch ( $iNivel ) {
            case 1:
                  $sNivelLetra = "PRIM";
                break;
            case 2:
                  $sNivelLetra = "SEC";
                break;
            case 3:
                  $sNivelLetra = "BACH";
                break;
            case 4:
                  $sNivelLetra = "PRE";
                break;
            default:
                $sNivelLetra = "";
                break;
        }
        return $sNivelLetra;
    }

    /**
     * Retorna una cadena representando el estatus visible del registro.
     *
     * @param  int $id
     * @return string
     */
    public static function GetVisible( $id='' )
    {
        switch ($id) {
            case '1': return '<b class="text-navy"><i class="fas fa-eye"></i></b>';
                break;
            case '0': return '<b class="text-danger"><i class="far fa-eye-slash"></i></b>';
                break;
            default: return '<b class="text-danger"><i class="far fa-eye-slash"></i></b>';
                break;
        }
    }

    /**
     * Calcula la diferencia en años entre 2 fechas dadas.
     */
    public static function obtenerAños($fechaInicial, $fechaFinal)
    {
        #
        $fn = new \DateTime($fechaInicial ?: date('Y-m-d'));
        $fc = new \DateTime($fechaFinal ?: date('Y-m-d'));

        return $fc->diff($fn)->y;
    }

    /**
     * Regresamos una cadena la cual oculta el correo elect
     *
     * @param  string $sEmail
     * @return string
     */
    public static function getIdPerActSig()
    {
        # pagos endeudados del alumno
        return PagosPeriodosAcademicos::select(
                'TblCPA.id AS Actual',
                'TblCPA.periododeadmision AS ActualCadena',
                \DB::raw( " ( SELECT id FROM TblCatPeriodosAdmisiones AS TblCPA2 WHERE TblCPA2.periododeadmision = TblCPA.periodoacademicosiguiente and TblCPA2.deleted_at IS NULL LIMIT 1 ) AS Siguente " ),
                'TblCPA.periodoacademicosiguiente AS SiguenteCadena'
            )
            ->leftJoin( 'TblCatPeriodosAdmisiones AS TblCPA', 'TblCPA.periododeadmision', '=', 'TblPagosPeriodosAcademicos.periodoacademico' )
            ->where( 'TblPagosPeriodosAcademicos.estatus', DB::raw( "'ACTIVO'" ) )
            ->first();
    }

    /**
     * Validamos si la fecha en formato de cadena
     *
     * @param  string $date
     * @param  string $format
     * @return boolean
     */
    public static function validateDate( $date, $format = 'Y-m-d H:i:s' )
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }


    /************************************************
    * te devuelve una fecha con el siguiente formato
    * recibe fecha de formato DD/MM/YYYY o DYYYYMMAA
    * y devuelve :
    * 0000-00-00
    * ***********************************************/
    public static function FechaToDate( $sFecha='', $bReturnNull=false,  $bTieneD=false )
    {
        /*
        if ( ! self::validateDate( $sFecha ) ) {
            return ($bReturnNull) ? NULL : "0000-00-00";
        }
        */

        if ( strlen( $sFecha ) < 10 ) {
            return ($bReturnNull) ? NULL : "0000-00-00";
        }
        if ( is_null( $sFecha ) ) {
            return ($bReturnNull) ? NULL : "0000-00-00";
        }
        if ($bTieneD) {
            $sFecha = substr($sFecha, 1);
            $sFecha = substr($sFecha, 6,2). "/" . substr($sFecha, 4,2) . "/" . substr($sFecha, 0,4);
        }
        if (strpos($sFecha, "-")) {
            list($year,$month,$day) = explode("-", $sFecha);
        }else{
            list($day,$month,$year) = explode("/", $sFecha);
        }
        if ($year!=0){
            return  date("Y-m-d", strtotime($day."-".$month."-".$year));
        }
        return ($bReturnNull) ? NULL : "0000-00-00";
    }


    public static function NombrePersona($id='',$tipo=1)
    {
        $users = DatosGeneralesPersona::where('IdPersona', $id)->first();

        if (is_null($users)) {
            $Nombre =".";
        }else{
            if($tipo==1)
            {
                $Nombre = Self::titleCase($users->Nombres).' '.Self::titleCase($users->ApPaterno).' '.Self::titleCase($users->ApMaterno);
            }

            if($tipo==2)
            {
                $Nombre = Self::titleCase($users->ApPaterno).' '.Self::titleCase($users->ApMaterno).' '.Self::titleCase($users->Nombres);
            }
        }

        return $Nombre;
    }

    public static function titleCase($string,$delimiters=array(" "),$exceptions=array("I", "II", "III", "IV", "V", "VI","VII","VIII","IX","X"))
    {
        $aNombre = explode(" ", $string);
        $sPalabra = "";
        $bFalse = false;
        foreach ($aNombre as $sValue) {
            if (in_array(strtoupper($sValue),$exceptions,true))
            {
                $sPalabra .=" ".strtoupper($sValue);
            }else{
                $sPalabra .=" ".ucwords(strtolower($sValue));
            }
        }
        return trim($sPalabra);
    }

    /**
     * Retorna una cadena representando la estencion del archivo.
     *
     * @param  int $id
     * @return string
     */
    public static function GetIconArchivo( $sArchivo='' )
    {
        $aNonbre = explode( '.', $sArchivo );
        $sExtencion = end( $aNonbre );

        switch ( $sExtencion ) {
            case 'pdf':
                return '<i class="fas fa-file-pdf fa-3x text-danger"></i>';
                break;
            case 'xls':
            case 'xlsx':
            case 'csv':
                return '<i class="fas fa-file-excel fa-3x text-success"></i>';
                break;
            case 'doc':
                return '<i class="fas fa-file-word fa-3x text-info"></i>';
                break;
            case 'jpg':
            case 'jpeg':
            case 'png':
                return '<i class="fas fa-file-image fa-3x text-primary"></i>';
                break;
            default:
                return '<i class="fas fa-file fa-3x text-muted"></i>';
                break;
        }
    }

    /**
     * Retorna una cadena representando la estencion del archivo.
     *
     * @param  int $id
     * @return string
     */
    public static function GetIsImage( $sArchivo='' )
    {
        $aNonbre = explode( '.', $sArchivo );
        $sExtencion = end( $aNonbre );

        switch ( $sExtencion ) {
            case 'jpeg':
            case 'jpg':
            case 'png':
                return TRUE;
                break;
            default:
                return FALSE;
                break;
        }
    }

    /**
     * Retorna una cadena representando la estencion del archivo.
     *
     * @param  int $id
     * @return string
     */
    public static function GetIsPDF( $sArchivo='' )
    {
        $aNonbre = explode( '.', $sArchivo );
        $sExtencion = end( $aNonbre );

        switch ( $sExtencion ) {
            case 'pdf':
                return TRUE;
                break;
            default:
                return FALSE;
                break;
        }
    }

    /**
     * Retorna una bandera si 1-Si o 0-No
     * Rebeca Ruiz
     * @param  int $idpersona, int $TblCPA_id
     * @return string
     */
    public static function GetDocumentosCompletados($idpersona,$TblCPA_id)
    {
        //Buscamos los datos en TblDatosGeneralesPersonas
        $oDGP = DatosGeneralesPersona::where('IdPersona', $idpersona)->first();

        //Buscamos los datos en TblAspirantes
        $oAspirante = Aspirante::where('idpersona', $idpersona)
            ->where('TblCPA_id', $TblCPA_id)
            ->first();

        // Buscamos en TblAspCatFamilias
        $oAspCF = AspCatFamilia::where('TblDGP_id',$oDGP->IdPersona)->first();

        // Buscamos en TblAspFamilias
        if($oAspCF) {
            //Busco si existe el Padre en la Familia
            $oBuscaPadreenFamilia = AspFamilia::leftJoin('TblDatosGeneralesPersonas','TblDatosGeneralesPersonas.idpersona','=','TblAspFamilias.TblDGP_id')
                ->where('TblAspCF_id',$oAspCF->id)
                ->where('estatuspersonaenfamilia',"Padre")
                ->where('Vive','Si')
                ->first();

            //Busco si existe el Madre en la Familia
            $oBuscaMadreenFamilia = AspFamilia::leftJoin('TblDatosGeneralesPersonas','TblDatosGeneralesPersonas.idpersona','=','TblAspFamilias.TblDGP_id')
                ->where('TblAspCF_id',$oAspCF->id)
                ->where('estatuspersonaenfamilia',"Madre")
                ->where('Vive','Si')
                ->first();
        }

        $nacionalidad = '';
        if($oDGP->Nacionalidad == 'Mexicana') {
            $nacionalidad = "%extranjero%";
        }

        $oCatDocumentos=null;
        if($oAspCF) {
            //Obtengo el catálogo de Documentos
            $oCatDocumentos = AspCatDocumento::select('*')
                ->where('TblCPA_id',$TblCPA_id)
                ->where('nivel',$oAspirante->nivel)
                ->where('grado',$oAspirante->grado)
                ->where(function($query) use ($nacionalidad){
                    if ($nacionalidad != '') {
                        $query->where('descripcion','NOT LIKE', $nacionalidad);
                    }
                })
                ->where(function($query) use ($oBuscaPadreenFamilia){
                    if (is_null($oBuscaPadreenFamilia)) {
                        $query->where('descripcion','NOT LIKE', '%padre%');
                    }
                })
                ->where(function($query) use ($oBuscaMadreenFamilia){
                    if (is_null($oBuscaMadreenFamilia)) {
                        $query->where('descripcion','NOT LIKE', '%madre%');
                    }
                })
                ->orderBy('orden')
                ->get();
        }

        //Busco la cantidad de documentos que lleva capturado
        $DocumentosSubidos = AspDocumento::leftJoin('TblAspCatDocumentos AS TblACD','TblACD.id','=','TblAspDocumentos.TblACD_id')
           ->where('TblACD.TblCPA_id', $TblCPA_id)
           ->where('TblAspDocumentos.TblDGP_id', $idpersona)
           ->get();

        clock()->debug($DocumentosSubidos->toArray());

        if($DocumentosSubidos->count() == 0) {
            return 0;
        } else {
            return $DocumentosSubidos->count() == count($oCatDocumentos) ? 1 : 2;
        }
    }

    /**
     * Retorna una bandera si 1- Pagó o 0-No Pagó el examen de admisión
     * Rebeca Ruiz
     * @param  int $idpersona, int $TblCPA_id
     * @return string
     */
    public static function GetPagoExamen($idpersona,$TblCPA_id)
    {
        # Obtenemos el nombre del período de admision por medio del id, ya que la tabla TblPagosOrdinarios no tiene ese campo
        $oCatPeriodosAdmisiones = CatPeriodosAdmisiones::where('id', $TblCPA_id)
                                    ->first();

        # Si el adeudo de Examen de Admisión no existe se genera
        $oAdeudo = PagoOrdinario::where('idpersona', $idpersona)
                ->where('clavesubconcepto', \DB::raw("'023'"))
                ->where('periodoacademico', $oCatPeriodosAdmisiones->periododeadmision)
                ->where('estatus','<>','Cancelado')
                ->first();
        $pagado=0;

        //Buscamos los datos en TblAspirantes
        $oAspirante = Aspirante::where('idpersona', $idpersona)
                                ->where('TblCPA_id', $TblCPA_id)
                                ->first();

        if(@$oAspirante->nivel==4 && @$oAspirante->grado==4)
        {
            if(Self::GetDocumentosCompletados($idpersona,$TblCPA_id)==1)
            {
                $pagado=1;
            }
            else
            {
                $pagado=0;
            }

        }
        else
        {
            if(!is_null($oAdeudo))
            {
                if($oAdeudo->estatus=='Pagado')
                //|| stripos($oAdeudo->foliobancario, '0001|EN ESPERA DE CONFIRMACION') !== false
                {
                    $pagado=1 ;
                }
            }
        }

        return $pagado;
    }

    /**
     * Retorna una bandera con la cantidad de cuestionarios consultados
     * Rebeca Ruiz
     * @param  int $idpersona
     * @return string
     */
    public static function GetSolicituddeAdmision($idpersona)
    {
        //Falta modificar esto, asi se queda por mientras
        $ids = [1,2,3,4,5,6,7,8,9,10,11];
        $Cantidad = AspEstatusCuestionario::select('*')
                                    ->where('TblDGP_id',$idpersona)
                                    ->whereIn('TblAspCC_id',$ids)
                                    ->count();
        return $Cantidad;
    }

    /**
     * Retorna el nombre del grado a partir del ID
     * Rebeca Ruiz
     * @param  int $idpersona
     */
    public static function GetNombreGrado($id)
    {
        return  Grados::select('NombreGrado')
                         ->where('id',$id)
                         ->first();

    }

    public static function GetAspEstatusCuestionario($id,$idpersona)
    {
        $oCuestionario = AspEstatusCuestionario::where('TblDGP_id',$idpersona)->where('TblAspCC_id',$id)->first();
        if(!is_null($oCuestionario))
        {
            return $oCuestionario->estatus;
        }
        else
        {
            return 0;
        }
    }

    public static function GetResultadodelExamen($idpersona,$TblCPA_id)
    {
        //Buscamos los datos en TblAspirantes
        $oAspirante = Aspirante::where('idpersona', $idpersona)
                                ->where('TblCPA_id', $TblCPA_id)
                                ->first();

        if($oAspirante->estatus!=0 && @$oAspirante->publicarresultados==1)
        {
            //Servicios Escolares ya capturó el Resultado
            return 1;
        }
        else
        {
            //Servicios Escolares NO le ha captura el Resultado
            return 0;
        }
    }

    public static function GetoResultadoExamen($idpersona,$TblCPA_id)
    {
        //Buscamos los datos en TblAspirantes
        $oAspirante = Aspirante::where('idpersona', $idpersona)
                                ->where('TblCPA_id', $TblCPA_id)
                                ->first();
        if($oAspirante->estatus!=0)
        {
            //Servicios Escolares ya capturó el Resultado
            return $oAspirante;
        }
        else
        {
            //Servicios Escolares NO le ha captura el Resultado
            return 0;
        }
    }

    public static function GetResultadodelExamenCapturado($idpersona,$TblCPA_id)
    {
        //Buscamos los datos en TblAspirantes
        $oAspirante = Aspirante::where('idpersona', $idpersona)
                                ->where('TblCPA_id', $TblCPA_id)
                                ->first();
        if($oAspirante->estatus!=0)
        {
            //Servicios Escolares ya capturó el Resultado
            return 1;
        }
        else
        {
            //Servicios Escolares NO le ha captura el Resultado
            return 0;
        }
    }

    public static function getByIdDocumentoAsp($iddocumento,$idpersona)
    {
        //busca si existe el idDocumento - TblACD_id
        $oDocumento = AspDocumento::where('TblACD_id',$iddocumento)
                                   ->where('TblDGP_id',$idpersona)
                                   ->first();

        if(!is_null($oDocumento))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public static function getEstatusByIdDocumentoAsp($iddocumento,$idpersona)
    {
        //busca si existe el idDocumento - TblACD_id
        $oDocumento = AspDocumento::where('TblACD_id',$iddocumento)
                                   ->where('TblDGP_id',$idpersona)
                                   ->first();

        if(!is_null($oDocumento))
        {
            return $oDocumento->estatus;
        }
        else
        {
            return 0;
        }
    }

    public static function getBloqueadoByIdDocumentoAsp($iddocumento,$idpersona)
    {
        //busca si existe el idDocumento - TblACD_id
        $oDocumento = AspDocumento::where('TblACD_id',$iddocumento)
                                   ->where('TblDGP_id',$idpersona)
                                   ->first();

        if(!is_null($oDocumento))
        {
            return $oDocumento;
        }
        else
        {
            return NULL;
        }
    }

    public static function GetFechaAdmisiones($nivel,$clave,$TblCPA_id)
    {
        $oAspCatFechas = AspCatFecha::select('*')
                                ->where('nivel', $nivel)
                                ->where('clave', $clave)
                                ->where('TblCPA_id',$TblCPA_id)
                                ->first();
        if(!is_null($oAspCatFechas))
        {
            return $oAspCatFechas->fecha;
        }
        else
        {
            $fecha="";
            return $fecha;
        }
    }

    public static function getCantidadCorreosEnviados($idcorreo,$idpersona)
    {
        #
        $oEstatusCorreo = AspEstatusCorreo::where('TblACC_id',$idcorreo)
                                   ->where('TblDGP_id',$idpersona)
                                   ->get();

        if(!is_null($oEstatusCorreo))
        {
            return count($oEstatusCorreo);
        }
        else
        {
            return NULL;
        }
    }

    public static function getUltimoCorreoEnviado($idcorreo,$idpersona)
    {
        #
        $oEstatusCorreo = AspEstatusCorreo::where('TblACC_id',$idcorreo)
                                   ->where('TblDGP_id',$idpersona)
                                   ->orderBy('created_at','desc')
                                   ->first();

        if(!is_null($oEstatusCorreo))
        {
            return $oEstatusCorreo;
        }
        else
        {
            return NULL;
        }
    }

    public static function getIdGrado($nivel, $grado)
    {
        #
        $oGrados = Grados::where('TblN_id', $nivel)
                          ->where('NombreGrado', $grado)
                          ->first();


        if(!is_null($oGrados))
        {
            return $oGrados->id;
        }
        else
        {
            return NULL;
        }
    }

    public static function SexobyCurp($curp)
    {
        $letrasexo= strtoupper(substr($curp, 10,1));
        $sexo=NULL;
        if($letrasexo=='M'){$sexo='Femenino';}
        if($letrasexo=='H'){$sexo='Masculino';}
        return $sexo;
    }

    public static function FechaNacbyCurp($curp)
    {
        $dia= substr($curp, 8,2);
        $mes= substr($curp, 6,2);
        $anno= substr($curp, 4,2);
        $u_digitos = substr($curp, 16,1);
        if(is_numeric($u_digitos))
        { //nacido antes del 2000
            $anno = $anno+1900;
        }
        else
        { //nacido en el 2000 en adelante
            $anno = $anno+2000;
        }
        return $anno."-".$mes."-".$dia;
    }

    public static function LugarNacEstadobyCurp($curp)
    {
        $estado= substr($curp, 11,2);
        $id_estado=0;
        $oEstados = Estado::where('clavecurp',$estado)->first();
        if(!is_null($oEstados))
        {
            $id_estado=$oEstados->id;
        }
        return $id_estado;
    }

    /************************************************
    * te devuelve una cadena con el siguiente formato
    * lunes, 22 de Febrero del 1974
    * dada una fecha 1974-02-22 o 22/02/1974
    * ***********************************************/
    public static function getFechaEspannolSinComa($fecha='')
    {
        $times = strtotime($fecha);
        $dias = array("domingo","lunes","martes","miércoles","jueves","viernes","sábado");
        $meses = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
        return $dias[date('w', $times)]." ".date('d', $times)." de ".$meses[date('n', $times)-1]. " de ".date('Y', $times) ;
        //Salida: Viernes 24 de Febrero del 2012
    }
    /*
     * Obtengo los registros de la familia del aspirante.
     * @param idfamilia aspirante.
     *
     */
    public static function GetFamiliaAspirante($idfamilia)
    {
        $oAspFamilia = AspFamilia::select('TblAspCF_id','TblDGP_id','estatuspersonaenfamilia',
                                               'appaterno','apmaterno','nombres','curp')
                        ->leftJoin('TblDatosGeneralesPersonas','TblDatosGeneralesPersonas.idpersona','=','TblAspFamilias.TblDGP_id')
                        ->where('TblAspCF_id',$idfamilia)
                        ->get();
        return $oAspFamilia;
    }

    /**
     * Devuelve los datos generales del aspirante seleccionado
     *
     * @param int $idAlumno
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getDatosAspirante($idAspirante)
    {
        # Se obtiene la información del aspirante
        return \App\Models\Escolar\Aspirantes\Aspirante::select(
                'TblAspirantes.idpersona',
                'TblAspirantes.folio',
                'TblAspirantes.periododeadmision',
                'TblCPA.id AS TblCPA_id',
                'TblN.NombreNivel',
                'TblAspirantes.nivel',
                'TblAspirantes.grado',
                DB::raw("CONCAT_WS(' ', TblDGP.ApPaterno, TblDGP.ApMaterno, TblDGP.Nombres) AS NombreCompleto"),
                'TblDGP.FechaNac',
                DB::raw('TblG.id AS TblG_id'),
                'TblDGP.Nacionalidad'
            )
            ->join('TblDatosGeneralesPersonas as TblDGP', 'TblAspirantes.idpersona', '=', 'TblDGP.IdPersona')
            ->join('TblNiveles as TblN', 'TblAspirantes.nivel', '=', 'TblN.id')
            ->join('TblGrados as TblG', function($join){
                $join->on('TblN.id', '=', 'TblG.TblN_id')
                    ->on('TblAspirantes.grado', '=', 'TblG.NombreGrado');
            })
            ->join('TblCatPeriodosAdmisiones AS TblCPA', function($join){
                $join->on('TblAspirantes.periododeadmision', '=', 'TblCPA.periododeadmision')
                    ->whereNull('TblCPA.deleted_at');
            })
            ->where('TblAspirantes.id', $idAspirante)
            ->first();
    }

    /**
     * Devuelve los datos generales del alumnmo seleccionado
     *
     * @param int $idAlumno
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getDatosAlumno( $idAlumno=null, $sPeriodoAdmision = '' )
    {
        if (!$sPeriodoAdmision) {
            # Se obtiene el último período de admisiones registrado del alumno.
            $aDatosAlumno = self::obtenerTipoPeriodoAlumno($idAlumno, true);
            $sPeriodoAdmision = $aDatosAlumno['periodo'];
        }

        # Se obtiene la información del alumno
        return \App\Models\Escolar\HistorialAlumnosenGrupo::select(
                'TblHistorialAlumnosenGrupos.idpersona',
                'TblDGP.Curp',
                'TblHistorialAlumnosenGrupos.idpersona AS TblTDGP_id',
                'TblHistorialAlumnosenGrupos.periododeadmision',
                'TblHistorialAlumnosenGrupos.periodoacademico',
                'TblCPA.periodoacademicosiguiente',
                'TblHistorialAlumnosenGrupos.numcontrol',
                'TblN.NombreNivel',
                'TblHistorialAlumnosenGrupos.nivel',
                'TblHistorialAlumnosenGrupos.grado',
                'TblHistorialAlumnosenGrupos.grupo',
                \DB::raw("CONCAT_WS(' ', TblDGP.ApPaterno, TblDGP.ApMaterno, TblDGP.Nombres) as NombreCompleto"),
                'TblDGP.FechaNac',
                \DB::raw('TblG.id AS TblG_id'),
                \DB::raw('TblCPA.id AS TblCPA_id')
            )
            ->join('TblDatosGeneralesPersonas as TblDGP', 'TblHistorialAlumnosenGrupos.idpersona', '=', 'TblDGP.IdPersona')
            ->join('TblNiveles as TblN', 'TblHistorialAlumnosenGrupos.nivel', '=', 'TblN.id')
            ->join('TblGrados as TblG', function($join){
                $join->on('TblN.id', '=', 'TblG.TblN_id')
                    ->on('TblHistorialAlumnosenGrupos.grado', '=', 'TblG.NombreGrado');
            })
            ->leftJoin('TblCatPeriodosAdmisiones AS TblCPA', function($join){
                $join->on('TblHistorialAlumnosenGrupos.periododeadmision', '=', 'TblCPA.periododeadmision')
                    ->whereNull('TblCPA.deleted_at');
            })
            ->where('TblHistorialAlumnosenGrupos.idpersona', $idAlumno)
            ->where('TblHistorialAlumnosenGrupos.periododeadmision', $sPeriodoAdmision)
            ->orderByDesc('TblHistorialAlumnosenGrupos.periodoacademico')
            ->first();
    }

    /**
     * Devuelve el período de admisión al que ingresará el alumno y el tipo del mismo.
     *
     * @param  int    $id
     * @param  boolean $bCambioPorFecha   Indica si el cambio de ciclo debe ser por una fecha determinada.
     * @return array  Tipo y período del alumno
     */
    public static function obtenerTipoPeriodoAlumno($idAlumno, $bCambioPorFecha = false)
    {
        $tipo = '';
        $periodo = '';

        $oPeriodoAcademico = \App\Models\Escolar\EscoCatPeriodosAcademicos::where('estatus', 'ACTIVO')
            ->first();

        $oPeriodoAdmision = \App\Models\Escolar\CatPeriodosAdmisiones::where('estatus', 'ACTIVO')
            ->first();

        $oPeriodoAdmisionAnterior = \App\Models\Escolar\CatPeriodosAdmisiones::where(
                'periodoacademicosiguiente', $oPeriodoAdmision->periododeadmision
            )
            ->first();

        $oPeriodoAdmisionAnterior2 = \App\Models\Escolar\CatPeriodosAdmisiones::where(
                'periodoacademicosiguiente', $oPeriodoAdmisionAnterior->periododeadmision
            )
            ->first();

        $tipo = 'Aspirante';
        $periodo = $oPeriodoAdmision->periododeadmision;
        $idPeriodoAdmision = $oPeriodoAdmision->id;
        $oCPA = $oPeriodoAdmision;
        $tieneMatricula = true; # Se asume que tiene matrícula a menos que no la encuentre.

        $oMatriculas = \App\Models\DatosGeneralesPersona::select(
                'TblHAGSig.numcontrol AS ncSig',
                'TblHAGAct.numcontrol AS ncAct',
                'TblHAGAnt.numcontrol AS ncAnt',
                'TblASig.folio AS folioSig',
                'TblAAct.folio AS folioAct'
            )
            ->leftJoin('TblHistorialAlumnosenGrupos AS TblHAGSig', function($join) use($oPeriodoAdmision){
                $join->on('TblDatosGeneralesPersonas.IdPersona', '=', 'TblHAGSig.idpersona')
                    ->where('TblHAGSig.periododeadmision', '=', $oPeriodoAdmision->periododeadmision)
                    ->where('TblHAGSig.estatusescolar', '=', 'A')
                    ->where('TblHAGSig.estatuspagos', '=', 'A')
                    ->whereNull('TblHAGSig.deleted_at');
            })
            ->leftJoin('TblHistorialAlumnosenGrupos AS TblHAGAct', function($join) use($oPeriodoAdmisionAnterior){
                $join->on('TblDatosGeneralesPersonas.IdPersona', '=', 'TblHAGAct.idpersona')
                    ->where('TblHAGAct.periododeadmision', '=', $oPeriodoAdmisionAnterior->periododeadmision)
                    ->where('TblHAGAct.estatusescolar', '=', 'A')
                    ->where('TblHAGAct.estatuspagos', '=', 'A')
                    ->whereNull('TblHAGAct.deleted_at');
            })
            ->leftJoin('TblHistorialAlumnosenGrupos AS TblHAGAnt', function($join) use($oPeriodoAdmisionAnterior2){
                $join->on('TblDatosGeneralesPersonas.IdPersona', '=', 'TblHAGAnt.idpersona')
                    ->where('TblHAGAnt.periododeadmision', '=', $oPeriodoAdmisionAnterior2->periododeadmision)
                    ->where('TblHAGAnt.estatusescolar', '=', 'A')
                    ->where('TblHAGAnt.estatuspagos', '=', 'A')
                    ->whereNull('TblHAGAnt.deleted_at');
            })
            ->leftJoin('TblAspirantes AS TblASig', function($join) use($oPeriodoAdmision){
                $join->on('TblDatosGeneralesPersonas.IdPersona', '=', 'TblASig.idpersona')
                    ->where('TblASig.periododeadmision', '=', $oPeriodoAdmision->periododeadmision)
                    ->whereNull('TblASig.deleted_at');
            })
            ->leftJoin('TblAspirantes AS TblAAct', function($join) use($oPeriodoAdmisionAnterior){
                $join->on('TblDatosGeneralesPersonas.IdPersona', '=', 'TblAAct.idpersona')
                    ->where('TblAAct.periododeadmision', '=', $oPeriodoAdmisionAnterior->periododeadmision)
                    ->whereNull('TblAAct.deleted_at');
            })
            ->find($idAlumno);

        // Lo primero es determinar en qué ciclo se está inscribiendo buscando el ciclo de la matrícula
        // más reciente (siguiente vs. actual).
        if (!$oMatriculas->ncSig) {
            if ($oMatriculas->ncAct) {
                $periodo = $oPeriodoAdmisionAnterior->periododeadmision;
                $oCPA = $oPeriodoAdmisionAnterior;
            } else {
                # Si no está en el ciclo siguiente ni el actual entonces se considera como "Sin matrícula".
                $tieneMatricula = false;
            }
        }

        /*
         * Lo siguiente es determinar el tipo de alumno que es (Nuevo Ingreso o Reingreso). Para catalogarlo
         * como Reingreso se considera lo siguiente:
         *
         * 1) Si tiene matrícula en el ciclo siguiente y no está en el proceso de admisión del mismo ciclo.
         * 2) Si no tiene matrícula en el ciclo siguiente pero sí en el actual y no está en el proceso de admisión
         *    del actual.
         *
         * Para cualquier otro caso se considera Nuevo Ingreso.
         */
        if (($oMatriculas->ncSig && !$oMatriculas->folioSig) || (!$oMatriculas->ncSig && $oMatriculas->ncAct && !$oMatriculas->folioAct)) {
            $tipo = 'Reingreso';
        }

        // Los alumnos de Reingreso deben tener matrícula en el ciclo siguiente para que sean considerados
        // como "Con matrícula".
        if ($tipo == 'Reingreso' && $tieneMatricula) {
            $anioMatricula = intval(substr($periodo, -4));
            $anioAcademico = intval(substr($oPeriodoAcademico->periododeadmision, -4));
            $anioAdmision = intval(substr($oPeriodoAdmision->periododeadmision, -4));

            if ($anioAdmision > $anioAcademico && $bCambioPorFecha) {
                $sFechaCambio = date('Y-') . self::getVariablePermante('SL_fecha_cambio_ciclo', '08-01');

                if (strtotime('now') < strtotime($sFechaCambio)) {
                    $periodo = $oPeriodoAdmisionAnterior->periododeadmision;
                    $idPeriodoAdmision = $oPeriodoAdmisionAnterior->id;
                }
            }
        } elseif ($tipo == 'Aspirante') {
            $oAspirante = \App\Models\Escolar\EscoAspirantes::where('idpersona', $idAlumno)
                ->orderBy('TblCPA_id', 'desc')
                ->first();

            $idPeriodoAdmision = $oAspirante->TblCPA_id;
        }

        # Si no tiene matrícula se busca el último registro de matrícula que tiene.
        if (!$tieneMatricula) {
        $oRegistro = \App\Models\Escolar\HistorialAlumnosenGrupo::select(
                'TblCPA.id',
                'TblCPA.periododeadmision',
            )
            ->where('idpersona', $idAlumno)
            ->join('TblCatPeriodosAdmisiones AS TblCPA', function($join){
                $join->on('TblHistorialAlumnosenGrupos.periododeadmision', '=', 'TblCPA.periododeadmision')
                    ->whereNull('TblCPA.deleted_at');
            })
            ->orderByDesc('periododeadmision')
            ->orderByDesc('periodoacademico')
            ->first();

            $periodo = optional($oRegistro)->periododeadmision;
            $idPeriodoAdmision = optional($oRegistro)->id;
        }

        return [
            'tipo' => $tipo,
            'periodo' => $periodo,
            'TblCPA_id' =>$idPeriodoAdmision,
            'oCPA' => $oCPA,
            'tieneMatricula' => $tieneMatricula
        ];
    }

    /*
     * Obtengo todos los idfamilia donde se encuentre el idpersona dado
     * @param idpersona
     *
     */
    //
    public static function GetIdFamilias($idpersona)
    {
        $oFamilia = Familia::where('idpersona',$idpersona)
                            ->orderBy('idfamilia','asc')
                            ->get();
        if (is_null($oFamilia))
        {
            return null;
        }else
        {
            return $oFamilia;
        }
    }

    /*
     * Obtengo el registro de la tabla TblCatFamilias
     * @param idfamilia
     *
     */
    //
    public static function GetCatFamilias($idfamilia)
    {
        $oFamilia = CatFamilia::where('idfamilia',$idfamilia)
                            ->first();
        if (is_null($oFamilia))
        {
            return null;
        }else
        {
            return $oFamilia;
        }
    }

    /*
     * Obtengo los registros de la familia de alumnos.
     * @param idfamilia aspirante/alumno.
     *
     */
    public static function GetFamilia($idfamilia)
    {
        $oFamilia = Familia::select('TblFamilias.idfamilia','TblFamilias.idpersona','estatuspersonaenfamilia',
                                               'appaterno','apmaterno','nombres','curp', 'esexalumno','generacion')
                        ->leftJoin('TblDatosGeneralesPersonas','TblDatosGeneralesPersonas.idpersona','=','TblFamilias.idpersona')
                        ->where('idfamilia',$idfamilia)
                        ->get();
        return $oFamilia;
    }

    /*
     * Obtenemos el registro del alumno en HAG de acuerdo al período academico activo
     * @param idpersona y periodos académicos activos
     *
     */
    public static function GetEsAlumnosActivo($idPersona, $aPeriodosactivos)
    {
        $oAlumnos = HistorialAlumnosenGrupo::where('idpersona', $idPersona)
                                            ->whereIn('periodoacademico', $aPeriodosactivos)
                                            ->first();
        if (is_null($oAlumnos))
        {
            return null;
        }else
        {
            return $oAlumnos;
        }                                            
    }


    /*
     * Buscamos si el idpersona esta en la tabla TblAspirantes
     * @param idpersona y periodos de admisión
     *
     */
    public static function GetEsAspirante($idPersona, $TblCPA_id)
    {
        $oAspirante = Aspirante::where('idpersona', $idPersona)
                            ->where('TblCPA_id', $TblCPA_id)
                            ->first();

        if (is_null($oAspirante))
        {
            return null;
        }else
        {
            return $oAspirante;
        }                                            
    }

    /*
     * Obtenemos el registro de la persona en HAG 
     * @param idpersona 
     *
     */
    public static function GetEstaenHAG($idPersona)
    {
        $oHAG = HistorialAlumnosenGrupo::where('idpersona', $idPersona)
                                            ->get();
        if (is_null($oHAG))
        {
            return null;
        }else
        {
            return $oHAG;
        }                                            
    }


    /*
     * Buscamos si existe la persona en la Tabla TblPersonal
     * @param idpersona
     *
     */
    public static function GetPersonal($idPersona)
    {
        $oPersonal = Personal::where('idpersona', $idPersona)
                            ->first();
                            
        if (is_null($oPersonal))
        {
            return null;
        }else
        {
            return $oPersonal;
        }                                            
    }

    /*
     * Cantidad de Registros que tiene el catálgo 
     * @param id catálogo
     *
     */
    public static function GetCantidadRegistrosxIdCatalogo($catalogo,$id)
    {
        /*
         1- TblAspCatTipoIngreso
         2- TblAspCatEstatusEvaluacion
         3- TblAspCatLugarCita
        */
        if($catalogo==1)
        {
            $oCatalogo = Aspirante::where('TblACTI_id', $id)
                                        ->get();
            return count($oCatalogo);
        }

        if($catalogo==2)
        {
            $oCatalogo = Aspirante::where('TblACEE_id', $id)
                                    ->get();
            return count($oCatalogo);
        }

        if($catalogo==3)
        {
            $oCatalogo = Aspirante::where('TblACLC_id', $id)
                                    ->get();
            return count($oCatalogo);
        }

        if($catalogo==4)
        {
            $oCatalogo = Aspirante::where('TblACMB_id', $id)
                                    ->get();
            return count($oCatalogo);
        }
    }
    /*
     * Generación de Número de Control
     * @param idpersona, período de admisión
     *
     */
    public static function GenerarNC($iIdPersona=0, $sPeriodo='')
    {
        $aDatos = array();

        // obtenemo los datos de los aspirtante
        $oAspirante = Aspirante::where('idpersona', $iIdPersona)->where('periododeadmision', $sPeriodo)->whereNull('deleted_at')->first();
        // obtenemos los datos del alunos
        $oAlumno = Alumno::where('idpersona', $iIdPersona)->whereNull('deleted_at')->first();
        // numero de control vacio
        $sNumControl = "0";
        // si existe el id persona en la base de datos de alumnos
        if (!is_null($oAlumno)) {
            // cargamos el numero de control
            $sNumControl = $oAlumno->numcontrol;
            array_push($aDatos, $sNumControl);
            array_push($aDatos, '1');
        }
        else
        {
            // si no existe en la base de alumnos
            // revisamos si existe en el historial
            $oHistorial = HistorialAlumnosenGrupo::where('idpersona', $iIdPersona)->where('periododeadmision', $sPeriodo)->whereNull('deleted_at')->first();
            // si existe el id persona en la base de datos de Hist de alumnos en grupo
            if (!is_null($oHistorial)) {
                // cargamos el numero de control
                $sNumControl = $oHistorial->numcontrol;
                array_push($aDatos, $sNumControl);
                array_push($aDatos, '2');

            }
            else
            {
                //
                $oPeriodoAct = CatPeriodosAdmisiones::select( '*', \DB::raw('year(fechainicio)+1 as annoactivo') )->where('periododeadmision', $sPeriodo)->first();
                //
                $iAnno = $oPeriodoAct->annoactivo-1;
                //
                if (count((array)$oAspirante->nivel)!=0) {
                    // buscamos el ultimo numeor de control creado
                    $oUltimoNC = HistorialAlumnosenGrupo::select('TblHistorialAlumnosenGrupos.numcontrol as numcontrol')
                                ->join('TblAspirantes', 'TblAspirantes.idpersona', '=', 'TblHistorialAlumnosenGrupos.idpersona')
                                ->where('TblHistorialAlumnosenGrupos.periododeadmision', $sPeriodo)
                                ->where('TblAspirantes.periododeadmision', $sPeriodo)
                                ->where('TblAspirantes.grado', $oAspirante->grado)
                                ->where('TblAspirantes.nivel', $oAspirante->nivel)
                                ->where(\DB::raw('left(TblHistorialAlumnosenGrupos.numcontrol,4)'), $iAnno)
                                ->where(\DB::raw('left(TblHistorialAlumnosenGrupos.numcontrol,5)'), $iAnno.$oAspirante->nivel)
                                ->where(\DB::raw('left(TblHistorialAlumnosenGrupos.numcontrol,6)'), $iAnno.$oAspirante->nivel.$oAspirante->grado)
                                ->whereNull('TblAspirantes.deleted_at')
                                ->whereNull('TblHistorialAlumnosenGrupos.deleted_at')
                                ->orderBy('TblHistorialAlumnosenGrupos.numcontrol', 'DESC')
                                ->first();
                    // si es que no existe un registri asignamos cero si esxite
                    // damos el retornado
                    $sLastNC = (!is_null($oUltimoNC))?$oUltimoNC->numcontrol:'0';
                    $iConsecutivo = (substr($sLastNC,strlen($sLastNC)-3,3)) + 1;
                    $sNumControl = $iAnno.$oAspirante->nivel.$oAspirante->grado.sprintf("%03s", $iConsecutivo);
                    array_push($aDatos, $sNumControl);
                    array_push($aDatos, '3');
                }
                else
                {
                    $sNumControl = '0';
                    array_push($aDatos, $sNumControl);
                    array_push($aDatos, '4');
                }
            }
        }
        # code...
        return $aDatos;
    }


     public static function genera_password()
    { 
        $exp_reg="[^0-9]"; 
        $longitud=4;
        $words = array("AZUL","PALO","VERDE","SUMA","CASA","CEJA",
        "ROJO","CAFE","ROSA","GRIS","AUTO", "BETA","BOTA","CAJA","ALFA",
        "COCO","EURO","PESO","IMAN","JEFE","JEFA","JOYA","JUEZ","KILO",
        "LAGO","LIMA","LONA","LOTE","LUPA","MAYO","MODA","NOTA",
        "NUEZ","HILO","LOBO","ORCA","PUMA","PAIS","SUMA","RESTA",
        "TAXI","DONA","CENA","BECA","CONO","CRUZ","FOCO","ZONA");
        /*return $words[rand(0,count($words)-1)].
                substr(
                    preg_replace($exp_reg, "", md5(rand())).
                preg_replace($exp_reg, "", md5(rand())) .
                preg_replace($exp_reg, "", md5(rand())),0, $longitud
            ); */

        return $words[rand(0,count($words)-1)].rand(1000,9999); 

    }   

    //Funcion que me sirve para obtener la cantidad de registro que tiene en la tabla TblCalificaciones,
    // si es mayor 0 es que se puede mostar la boleta
    public static function TieneCalificaciones($idpersona,$periodoacademico)
    {
        $CantidadCalif = Calificacion::where('idpersona',$idpersona)
                                           ->where('periodoacademico',$periodoacademico)
                            ->count();

        if ($CantidadCalif<=1)
        {
            return false;
        }else
        {
            return true;
        }
    }

    /*
     * Obtengo las materias de TblAspCatMaterias
     * @param TblCPA_id, nivel, grado
     *
     */
    //
    public static function GetAspCatMaterias($TblCPA_id, $nivel, $grado)
    {
        $oMaterias = AspCatMateria::where('TblCPA_id', $TblCPA_id)->where('nivel', $nivel)->where('grado', $grado)->orderBy('orden')->get();
        if (is_null($oMaterias))
        {
            return null;
        }else
        {
            return $oMaterias;
        }
    }

    /*
     * Obtengo las materias de TblAspCatMaterias
     * @param TblCPA_id, nivel
     *
     */
    //
    public static function GetAspCatMateriasByNivelPA($TblCPA_id, $nivel)
    {
        $oMaterias = AspCatMateria::select(DB::Raw('distinct nombreabreviado'))
                                ->where('TblCPA_id', $TblCPA_id)->where('nivel', $nivel)->orderBy('orden')->get();
        if (is_null($oMaterias))
        {
            return null;
        }else
        {
            return $oMaterias;
        }
    }


    /*
     * Obtengo las materias de TblAspCatMaterias
     * @param TblCPA_id, nivel, nombreabreviado
     *
     */
    //
    public static function GetAspCatMateriasByNivelPANombreAbreviado($TblCPA_id, $nivel, $nombreabreviado)
    {
        $oMaterias = AspCatMateria::select(DB::Raw('distinct nombreabreviado'))
                                ->where('TblCPA_id', $TblCPA_id)
                                ->where('nivel', $nivel)
                                ->where('nombreabreviado', $nombreabreviado)
                                ->orderBy('orden')->get();
                                
        if (is_null($oMaterias))
        {
            return null;
        }else
        {
            return $oMaterias;
        }
    }


    public static function GetAspCatMateriasByNivelPAGradoNombreAbreviado($TblCPA_id, $nivel, $grado, $nombreabreviado)
    {
        $oMaterias = AspCatMateria::where('TblCPA_id', $TblCPA_id)
                                ->where('nivel', $nivel)
                                ->where('grado', $grado)
                                ->where('nombreabreviado', $nombreabreviado)
                                ->orderBy('orden')->get();

        if (is_null($oMaterias))
        {
            return NULL;
        }else
        {
            return $oMaterias;
        }
    }


    /*
     * Obtengo las materias tipo Criterio de TblAspCatMaterias
     * @param TblCPA_id, nivel, grado
     *
     */
    //
    public static function GetAspCatMateriasByNivelPAGrado($TblCPA_id, $nivel, $grado, $tipo)
    {
        $oMaterias = AspCatMateria::select('*')
                                ->where('TblCPA_id', $TblCPA_id)
                                ->where('nivel', $nivel)
                                ->where('grado', $grado)
                                ->where('tipo', $tipo)    // Materias tipo Criterio
                                ->orderBy('orden')
                                ->get();
        if (is_null($oMaterias))
        {
            return null;
        }else
        {
            return $oMaterias;
        }
    }

    /*
     * Obtengo el id materia a partir de: nombreabreviado, nivel,grado y periododeadmisión
     * @param nombreabreviado, TblCPA_id, nivel, grado
     *
     */
    //
    public static function GetIdMateriasByNomAbPANG($nombreabreviado, $TblCPA_id, $nivel, $grado)
    {
        $oMaterias = AspCatMateria::where('TblCPA_id', $TblCPA_id)
                                ->where('nivel', $nivel)
                                ->where('grado', $grado)
                                ->where('nombreabreviado', $nombreabreviado)
                                ->first();

        if (is_null($oMaterias))
        {
            return null;
        }else
        {
            return $oMaterias->id;
        }
    }

    /*
     * Obtengo los documentos que tenga registrado por: Periodo de admision e idpersona 
     * @param nombreabreviado, TblCPA_id, TblDGP_id
     *
     */
    //
    public static function GetDocumentosByPAIdPersona($TblCPA_id, $TblDGP_id)
    {
        $oDocumentos = AspDocumentoEvaluacion::where('TblCPA_id', $TblCPA_id)
                                ->where('TblDGP_id', $TblDGP_id)
                                ->get();

        if (is_null($oDocumentos))
        {
            return null;
        }else
        {
            return $oDocumentos;
        }
    }

    /*
     * Obtengo las opciones capturadas por materia -  TblACMOpciones
     * @param idmateria
     *
     */
    //
    public static function GetACMOpciones($idmateria)
    {
        $oACMOpciones = ACMOpcion::where('TblACM_id', $idmateria)->get();
        if (is_null($oACMOpciones))
        {
            return null;
        }else
        {
            return $oACMOpciones;
        }
    }

     public static function GetMateriasUnicasArreglo($id)
    {
        $oUnicasArreglo = ACMOpcion::where('TblACM_id',$id)
                                    ->pluck('nombre','valor')
                                    ->toArray();
        if (is_null($oUnicasArreglo))
        {
            return null;
        }else
        {
            return $oUnicasArreglo;
        }

    }
    /*
        * Funcion para obtener los Promedios finales
        * @param IdPersona
    */
    public static function PromediosFinales($idpersona=0)
    {
        $oPromedios = HistorialAlumnosenGrupo::select('TblCalificacionesPromedios.idpersona','TblCalificacionesPromedios.periodoacademico',
                            'TblHistorialAlumnosenGrupos.grado','TblHistorialAlumnosenGrupos.grupo','TblHistorialAlumnosenGrupos.nivel',
                            'promedioexterno','promediointerno','TblHistorialAlumnosenGrupos.externo','TblHistorialAlumnosenGrupos.TblCTHAG_id')
                        ->join('TblCalificacionesPromedios', 'TblCalificacionesPromedios.periodoacademico', '=', 'TblHistorialAlumnosenGrupos.periodoacademico')
                        ->where('TblHistorialAlumnosenGrupos.idpersona',$idpersona)
                        ->where('TblCalificacionesPromedios.idpersona',$idpersona)
                        ->where('tipo','CAL')
                        ->where('periodoevaluacion','NotaFinal')
                        ->where('estatusescolar','A')
                        ->orderBy('idpromedio','desc')
                        ->get();
        if (is_null($oPromedios))
        {
            return null;
        }else
        {
            return $oPromedios;
        }
    }

    /*
     * Obtengo la relación Criterio / Materia a partir del IdCriterio
     * @param id
     * Esta función me sirve para deternimar a que nivel y grado pertenece un Id Criterio

     */
    //
    public static function GetMateriaCriterioByIdCriterio($id)
    {
        $oAspCatCriterio = AspCatCriterio::select('TblAspCatCriterios.id','TblAspCatCriterios.nombre','TblAspCatCriterios.nombreabreviado',
                                                  'TblAspCatMaterias.nivel','TblAspCatMaterias.grado')
                                        ->leftJoin('TblAspCatMaterias','TblAspCatMaterias.id','=','TblAspCatCriterios.TblACM_id')
                                        ->where('TblAspCatCriterios.id', $id)
                                        ->first();

        if (is_null($oAspCatCriterio))
        {
            return null;
        }else
        {
            return $oAspCatCriterio;
        }
    }


    /*
     * Obtengo los criterios de cada Materia -  TblAspCatCriterios
     * @param TblACM_id
     *
     */
    //
    public static function GetAspCatCriterios($id)
    {
        $oAspCatCriterio = AspCatCriterio::where('TblACM_id', $id)->orderBy('orden')->get();
        if (is_null($oAspCatCriterio))
        {
            return null;
        }else
        {
            return $oAspCatCriterio;
        }
    }


    /*
     * Obtengo los Criterios a partir del nivel, grado y período de admisión TblAspCatCriterios
     * @param nivel, grado, TblCPA_id
     *
     */
    //
    public static function GetCriteriosByNivelGradoPAdmision($nivel, $grado, $TblCPA_id)
    {
        if(!is_null($grado))
        {
            $oAspCatCriterio = AspCatCriterio::select('TblAspCatCriterios.nombreabreviado','TblAspCatCriterios.id')
                                ->leftJoin('TblAspCatMaterias','TblAspCatMaterias.id','=','TblAspCatCriterios.TblACM_id')
                                ->where('nivel', $nivel)
                                ->where('grado', $grado)
                                ->where('TblCPA_id', $TblCPA_id)
                                ->orderBy('TblAspCatCriterios.orden')->get();
        }
        else
        {
            $oAspCatCriterio = AspCatCriterio::select('TblAspCatCriterios.nombreabreviado','TblAspCatCriterios.id')
                                ->leftJoin('TblAspCatMaterias','TblAspCatMaterias.id','=','TblAspCatCriterios.TblACM_id')
                                ->where('nivel', $nivel)
                                ->whereIn('grado', ['2','1','4'])
                                ->where('TblCPA_id', $TblCPA_id)
                                ->orderBy('TblAspCatMaterias.grado')
                                ->orderBy('TblAspCatCriterios.orden')
                                ->get();
        }

        if (is_null($oAspCatCriterio))
        {
            return null;
        }else
        {
            return $oAspCatCriterio;
        }
    }



    /*
     * Obtengo la suma de los criterios de la Materia -  TblAspCatCriterios
     * @param TblACM_id
     *
     */
    //
    public static function GetSumaAspCatCriterios($id)
    {
        $oAspCatCriterio = AspCatCriterio::where('TblACM_id', $id)->orderBy('orden')->get();
        if (is_null($oAspCatCriterio))
        {
            return null;
        }else
        {
            $suma = 0;
            foreach($oAspCatCriterio as $c)
            {
                $suma = $suma + $c->peso ;

            }
            return $suma;
        }
    }

    /*
     * Obtengo los subcriterios de cada Criterio -  TblAspCatSubCriterios
     * @param TblACC_id
     *
     */
    //
    public static function GetAspCatSubCriterios($id)
    {
        $oAspCatSubCriterio = AspCatSubCriterio::where('TblACC_id', $id)->orderBy('orden')->get();
        if (is_null($oAspCatSubCriterio))
        {
            return null;
        }else
        {
            return $oAspCatSubCriterio;
        }
    }

    /*
    # cantidad de Criterios
    SELECT *
    FROM tblaspcatmaterias 
    join tblaspcatcriterios ON tblaspcatcriterios.TblACM_id=tblaspcatmaterias.id
    WHERE nivel=4 AND grado='3' AND TblCPA_id=18;

    # cantidad de Sub Criterios
    SELECT *
    FROM tblaspcatmaterias 
    join tblaspcatcriterios ON tblaspcatcriterios.TblACM_id=tblaspcatmaterias.id
    JOIN tblaspcatsubcriterios ON tblaspcatsubcriterios.TblACC_id = tblaspcatcriterios.id
    WHERE nivel=4 AND grado='3' AND TblCPA_id=18;*/

    /*
        Obtengo la cantidad de Criterios por Nivel / Grado y Período de Admisión
        @param nivel, grado, período de admisión
    */
    public static function GetCantidadCriteriosByNivelGradoPA($nivel, $grado, $TblCPA_id)
    {
        $oAspCatMaterias = AspCatMateria::join('DbInscripciones.TblAspCatCriterios',
                                                   'DbInscripciones.TblAspCatCriterios.TblACM_id','=','DbInscripciones.TblAspCatMaterias.id')
                            ->where('nivel', $nivel)
                            ->where('grado', $grado)
                            ->where('TblCPA_id', $TblCPA_id)
                            ->get();

        if (is_null($oAspCatMaterias))
        {
            return 0;
        }else
        {
            return count($oAspCatMaterias);
        }
    }

    /*
        Obtengo la cantidad de SubCriterios por Nivel / Grado y Período de Admisión
        @param nivel, grado, período de admisión
    */
    public static function GetCantidadSubCriteriosByNivelGradoPA($nivel, $grado, $TblCPA_id)
    {
        $oAspCatMaterias = AspCatMateria::join('DbInscripciones.TblAspCatCriterios',
                                                   'DbInscripciones.TblAspCatCriterios.TblACM_id','=','DbInscripciones.TblAspCatMaterias.id')
                            ->join('DbInscripciones.TblAspCatSubCriterios',
                                   'DbInscripciones.TblAspCatSubCriterios.TblACC_id','=','DbInscripciones.TblAspCatCriterios.id')
                            ->where('nivel', $nivel)
                            ->where('grado', $grado)
                            ->where('TblCPA_id', $TblCPA_id)
                            ->get();

        if (is_null($oAspCatMaterias))
        {
            return 0;
        }else
        {
            return count($oAspCatMaterias);
        }
    }

    /*
     * Obtengo la cantidad SubCriterios que tiene una Materia (la suma de subcriterios de cada Criterio)
     * @param idmateria
     *
     */
    //
    /*SELECT * 
    FROM tblaspcatsubcriterios 
    LEFT JOIN tblaspcatcriterios ON tblaspcatsubcriterios.TblACC_id=tblaspcatcriterios.id
    LEFT JOIN tblaspcatmaterias ON tblaspcatmaterias.id=tblaspcatcriterios.TblACM_id
    WHERE tblaspcatmaterias.id=576
    */  
    public static function GetCantidadSubCriteriosByIdMateria($idmateria)
    {
        $oAspCatSubCriterio = AspCatSubCriterio::leftJoin('DbInscripciones.TblAspCatCriterios','DbInscripciones.TblAspCatCriterios.id','=','DbInscripciones.TblAspCatSubCriterios.TblACC_id')
                                            ->leftJoin('DbInscripciones.TblAspCatMaterias','DbInscripciones.TblAspCatCriterios.TblACM_id','=','DbInscripciones.TblAspCatMaterias.id')
                                            ->where('TblAspCatMaterias.id', $idmateria)
                                            ->get();

        if (is_null($oAspCatSubCriterio))
        {
            return 0;
        }else
        {
            return count($oAspCatSubCriterio);
        }
    }



    public static function GetSumaAspCatSubCriterios($id)
    {
        $oAspCatSubCriterio = AspCatSubCriterio::where('TblACC_id', $id)->get();
        if (is_null($oAspCatSubCriterio))
        {
            return null;
        }else
        {
            $suma = 0;
            foreach($oAspCatSubCriterio as $sc)
            {
                $suma = $suma + $sc->peso ;

            }
            return $suma;
        }
    }

    // Función para buscar la calificacion de la materia en TblAspCalificaciones
     public static function GetAspCalificaciones($id,$TblCPA_id,$idmateria,$oportunidad=1)
    {
        $oAspCalificacion = AspCalificacion::where('TblDGP_id', $id)
                            ->where('TblCPA_id', $TblCPA_id)
                            ->where('TblACM_id', $idmateria)
                            ->where('oportunidad', $oportunidad)
                            ->first();

        if (is_null($oAspCalificacion))
        {
            return null;
        }else
        {
            return $oAspCalificacion->calificacion;
        }

    }

    // Función para buscar la evaluacion de la materia en TblAspCalificaciones
     public static function GetAspCalificacionesEvaluacion($id,$TblCPA_id,$idmateria,$letra)
    {
        $oAspCalificacion = AspCalificacion::select('TblAspCalificaciones.*','TblACMOpciones.valor','TblACMOpciones.nombre')
                            ->leftJoin( 'DbInscripciones.TblACMOpciones', function( $oJoin ){
                                $oJoin->on( 'DbInscripciones.TblACMOpciones.TblACM_id', '=', 'TblAspCalificaciones.TblACM_id')
                                ->on( 'DbInscripciones.TblACMOpciones.valor', '=', 'TblAspCalificaciones.evaluacion')
                                      ->whereNull('DbInscripciones.TblACMOpciones.deleted_at');
                            })
                            ->where('TblAspCalificaciones.TblDGP_id',$id)
                            ->where('TblAspCalificaciones.TblCPA_id',$TblCPA_id)
                            ->where('TblAspCalificaciones.TblACM_id',$idmateria)
                            ->first();

        if (is_null($oAspCalificacion))
        {
            return null;
        }else
        {
            if($letra==1)
            {
                return $oAspCalificacion->nombre;
            }
            else
            {
                return $oAspCalificacion->evaluacion;
            }
        }

    }

    // Función para buscar la calificacion del Criterio en TblAspCalificacionesCriterios
     public static function GetAspCalificacionesCriterios($id,$TblCPA_id,$idcriterio,$oportunidad=1)
    {
        $oAspCalificacionCriterio = AspCalificacionCriterio::where('TblDGP_id',$id)
                                                        ->where('TblCPA_id', $TblCPA_id)
                                                        ->where('TblACC_id', $idcriterio)
                                                        ->where('oportunidad', $oportunidad)
                                                        ->first();
        if (is_null($oAspCalificacionCriterio))
        {
            return null;
        }else
        {
            return $oAspCalificacionCriterio;
        }
    }

    // Función para buscar la calificacion del SubCriterio en TblAspCalificacionesSubCriterios
     public static function GetAspCalificacionesSubCriterios($id,$TblCPA_id,$idsubcriterio,$oportunidad=1)
    {
        $oAspCalificacionSubCriterio = AspCalificacionSubCriterio::where('TblDGP_id', $id)
                                                        ->where('TblCPA_id', $TblCPA_id)
                                                        ->where('TblACS_id', $idsubcriterio)
                                                        ->where('oportunidad', $oportunidad)
                                                        ->first();
        if (is_null($oAspCalificacionSubCriterio))
        {
            return null;
        }else
        {
            return $oAspCalificacionSubCriterio;
        }
    }

    // Función para obtener las observaciones capturadas por idpersona, período de admisión e id materia en TblAspObservaciones
     public static function GetAspObservaciones($id,$TblCPA_id,$idmateria,$oportunidad=1)
    {
        $oAspObservaciones = AspObservacion::where('TblDGP_id', $id)
                                        ->where('TblCPA_id', $TblCPA_id)
                                        ->where('TblACM_id', $idmateria)
                                        ->where('oportunidad', $oportunidad)
                                        ->first();
        if (is_null($oAspObservaciones))
        {
            return null;
        }else
        {
            return $oAspObservaciones;
        }
    }

    // Función para obtener las observaciones capturadas por idpersona, período de admisión e id criterio en TblAspObservacionesCriterios
     public static function GetAspObservacionesCriterios($id,$TblCPA_id,$idcriterio)
    {
        $oAspObservacionesCriterios = AspObservacionCriterios::where('TblDGP_id',$id)
                                        ->where('TblCPA_id',$TblCPA_id)
                                        ->where('TblACC_id',$idcriterio)
                                        ->first();
        if (is_null($oAspObservacionesCriterios))
        {
            return null;
        }else
        {
            return $oAspObservacionesCriterios;
        }
    }

    // Función para obtener las evaluación de TblAspCalificaciones por TblDGP_id, TblCPA_id, TblACM_id
     public static function GetValorCalificacionByIDPAM($id,$TblCPA_id,$idmateria,$oportunidad=1)
    {
        $oAspCalificacion = AspCalificacion::where('TblDGP_id',$id)
                            ->where('TblCPA_id',$TblCPA_id)
                            ->where('TblACM_id',$idmateria)
                            ->where('oportunidad',$oportunidad)
                            ->first();

        $valorcalificacion = NULL;

        #\Log::info("TblDGP_id -> ".$id);
        #\Log::info("TblCPA_id -> ".$TblCPA_id);
        #\Log::info("TblACM_id -> ".$idmateria);
        #\Log::info($oAspCalificacion);

        if (!is_null($oAspCalificacion))
        {
            # Busco la materia en el catálogo
            $oMaterias = AspCatMateria::where('id', $idmateria)->first();
            if(!is_null($oMaterias))
            {
                if($oMaterias->tipo==1)
                {
                    # Obtengo el array de las opciones (solo para las únicas)
                    $oUnicasArreglo = ACMOpcion::where('TblACM_id',$idmateria)->pluck('nombre','valor')->toArray();
                    $oUnicasArregloNull = ["NULL"=>'No Capturado'];
                    if(is_null($oAspCalificacion->evaluacion))
                    {
                        $valorcalificacion = NULL;
                    }
                    else
                    {
                        $valorcalificacion = $oUnicasArreglo[$oAspCalificacion->evaluacion];
                    }
                }
                else
                {
                    #Criterio
                    $valorcalificacion = $oAspCalificacion->ponderacion;
                }
            }
        }
        return $valorcalificacion;
    }

    public static function getNameFromNumber($num)
    {
        $numeric = ($num - 1) % 26; $letter = chr(65 + $numeric);
        $num2 = intval(($num - 1) / 26);
        if ($num2 > 0)
        {
            return $this->getNameFromNumber($num2) . $letter;
        }
        else
        {
            return $letter;
        }
    }    

    /*
     * Cantidad calificaciones promediadas
     * @param TblCPA_id, TblACM_id, TblACTI_id
     *
     */
    //
    public static function GetCantidadRegistrosPromediadosByIdMateriaTipoIngreso($idmateria,$tipoingreso,$periododeadmision)
    {
        $Cantidad = AspCalificacion::select(Db::Raw('count(*) as cantidad'))
                            ->leftJoin('DbInscripciones.TblAspirantes','DbInscripciones.TblAspirantes.idpersona','=','DbEscolar.TblAspCalificaciones.TblDGP_id')
                            ->where('DbEscolar.TblAspCalificaciones.TblCPA_id',$periododeadmision)
                            ->where('DbInscripciones.TblAspirantes.TblCPA_id',$periododeadmision)
                            ->where('TblACM_id',$idmateria)
                            ->where(function ( $oQuery ) use ($tipoingreso) {
                                if ( $tipoingreso != '' ) {
                                    $oQuery->where( 'TblAspirantes.TblACTI_id', $tipoingreso); 
                                }
                            })                            
                            ->first();

            return $Cantidad->cantidad;
    }

    /*
     * Promedio de calificaciones de aspirantes 
     * @param TblCPA_id, TblACM_id, TblACTI_id
     *
     */
    //
    public static function GetPromedioByIdMateriaTipoIngreso($idmateria,$tipoingreso,$periododeadmision)
    {
        $Cantidad = AspCalificacion::select(Db::Raw('avg(calificacion) as promedio'))
                            ->leftJoin('DbInscripciones.TblAspirantes','DbInscripciones.TblAspirantes.idpersona','=','DbEscolar.TblAspCalificaciones.TblDGP_id')
                            ->where('DbEscolar.TblAspCalificaciones.TblCPA_id',$periododeadmision)
                            ->where('DbInscripciones.TblAspirantes.TblCPA_id',$periododeadmision)
                            ->where('TblACM_id',$idmateria)
                            ->where(function ( $oQuery ) use ($tipoingreso) {
                                if ( $tipoingreso != '' ) {
                                    $oQuery->where( 'TblAspirantes.TblACTI_id', $tipoingreso); 
                                }
                            })                            
                            ->first();

            return $Cantidad->promedio;
    }

    /*
     * Obtengo el Contacto registrado en TblAspPropectosDatosContacto, ya sea 1-Teléfono o 2-Correo
     * @param TblAPC_id = id del contacto del prospecto, tipo = 1-Teléfono o 2-Correo
     *
     */
    //
    public static function GetDatosContactoByIdContactoProspecto($idcontactoprospecto,$tipo)
    {
        $oAspProspectosDatosContacto = AspProspectoDatosContacto::where('TblAPC_id',$idcontactoprospecto)
                                                                ->where('tipo', $tipo)
                                                                ->get();
        if (is_null($oAspProspectosDatosContacto))
        {
            return null;
        }else
        {
            return $oAspProspectosDatosContacto;
        }        
    }
    /*
     * Obtengo la cantidad de cita generadas al prospecto o aspirante TblAspSeguimiento (TblA_id -> para Aspirante | TblAP_id -> para prospectos)
     * @param TblA_id | TblAP_id | TblCPA_id -> período de admisión
     *
     */
    //
    public static function GetCantidadCitasAspiranteoProspecto($TblCPA_id, $TblA_id, $TblAP_id)
    {
        $cantidad =0;
        if($TblA_id != 0) // Aspirantes
        {
            #Busco si existe el Aspirante en la tabla de Prospectos TblA_id
            $oProspecto = AspProspecto::where('TblA_id',$TblA_id)->first();
            if(!is_null($oProspecto)) 
            {  
                $oAspSeguimientoP = AspProspectoSeguimiento::where('TblCPA_id',$TblCPA_id)
                                                    ->where('TblAP_id', $oProspecto->id)
                                                    ->get();                
                $cantidad = count($oAspSeguimientoP);
            }

            $oAspSeguimiento = AspProspectoSeguimiento::where('TblCPA_id',$TblCPA_id)
                                                    ->where('TblA_id', $TblA_id)
                                                    ->get();
            $cantidad = $cantidad+count($oAspSeguimiento);
        }
        else
        {
            if($TblAP_id != 0) // Prospectos
            {
                $oAspSeguimiento = AspProspectoSeguimiento::where('TblCPA_id',$TblCPA_id)
                                                        ->where('TblAP_id', $TblAP_id)
                                                        ->get();
                $cantidad = count($oAspSeguimiento);
            }
        }
        return $cantidad;
    }

    // Buscamos el folio del Aspirante a partir de apellido paterno, apellido materno y nombres en la tabla TblAspirantes
    public static function BuscaFolioByNombreCompleto($appaterno,$apmaterno,$nombres,$periodoadmision,$idprospecto)
    {
        // obtenemos los registros
        $oDGA = Aspirante::select('TblAspirantes.folio','TblAspirantes.id')
                ->leftJoin('TblDatosGeneralesPersonas','TblDatosGeneralesPersonas.idpersona','=','TblAspirantes.idpersona')
                ->where('TblAspirantes.TblCPA_id', $periodoadmision)
                ->where('appaterno',$appaterno)
                ->where('apmaterno',$apmaterno)
                ->where('nombres',$nombres)
                ->first();

        if (is_null($oDGA))
        {
            return null;
        }
        else
        {
            #Agrego el idAspirante a la tabla de Prospectos
            $oProspecto = AspProspecto::where('id',$idprospecto)
                                      ->first();
            if(!is_null($oProspecto))                                      
            {
                if(is_null($oProspecto->TblA_id))
                {
                    $oProspecto->TblA_id = $oDGA->id;
                    $oProspecto->save();
                }
            }
            return $oDGA;
        }
    }

/*
    SELECT DbInscripciones.TblAspCatMaterias.id,nombre,calificacion,evaluacion,ponderacion,DbInscripciones.TblAspCatMaterias.tipo
    from    DbInscripciones.TblAspCatMaterias     
    LEFT JOIN TblAspCalificaciones ON DbInscripciones.TblAspCatMaterias.id=TblAspCalificaciones.TblACM_id
    AND TblDGP_id=31728
    WHERE DbInscripciones.TblAspCatMaterias.TblCPA_id=19 AND  nivel=4 AND grado=2;
*/
    
    /**
        Determinamos si el aspirante ya fue completamente evaluado y determinamos el "estatus" para aparecer o no en el módulo de Selección de Alumnos
     *
     * @param idpersona , periododeadmision
     *
     * @return true / false
     */
    public static function getEstatusSeleccion($idpersona, $periodoadmision)
    {
        # Buscamos en TblAspirantes el nivel y grado del aspirante
        $oDGA = Aspirante::select('*')
                ->where('idpersona', $idpersona)
                ->where('TblCPA_id', $periodoadmision)
                ->first();

        # Obtengo las evaluaciones capturadas
        $oEvaluaciones = AspCatMateria::select('DbInscripciones.TblAspCatMaterias.id','nombre','calificacion',
                                               'evaluacion','ponderacion','DbInscripciones.TblAspCatMaterias.tipo')
                                        ->leftJoin( 'DbEscolar.TblAspCalificaciones', function( $oJoin)  use ($idpersona){
                                                $oJoin->on( 'DbInscripciones.TblAspCatMaterias.id', '=', 'TblAspCalificaciones.TblACM_id')
                                                      ->on( 'TblDGP_id', '=', Db::Raw($idpersona));
                                        })
                                        ->where('DbInscripciones.TblAspCatMaterias.TblCPA_id', $periodoadmision)
                                        ->where('nivel', $oDGA->nivel)
                                        ->where('grado', $oDGA->grado)
                                        ->get();
        $valor = 1; //"Se muestra";
        if(!is_null($oEvaluaciones))
        {
            foreach($oEvaluaciones as $ev)
            {
                if($ev->tipo==1)
                {
                    #\Log::info($ev->nombre.'-> Tipo: '.$ev->tipo . '  Evaluacion:' . $ev->evaluacion);
                    if($ev->evaluacion<0 || is_null($ev->evaluacion))
                    {
                        $valor = 0; //"NO ! Se muestra";     
                    }
                }
                else
                {
                    #\Log::info($ev->nombre.'-> Tipo: '.$ev->tipo.' Calificacion:'.$ev->calificacion);
                    if($ev->calificacion<=0  || is_null($ev->calificacion))
                    {
                        $valor = 0;//"NO ! Se muestra";   
                    }
                }
            }
        }

        if($oDGA->nivel==1)
        {
            //Para el caso de Primaria
            $oAspCatCriterios2 = Libreria::GetCriteriosByNivelGradoPAdmision($oDGA->nivel, $oDGA->grado, $periodoadmision);        
            foreach($oAspCatCriterios2 as $c) 
            {
                $criterios = Libreria::GetAspCalificacionesCriterios($oDGA->idpersona,$periodoadmision,$c->id);
                if(is_null($criterios) || $criterios->ponderacion<=0)
                {
                  $valor = 0;  
                }
            }
        }
        return $valor;
    }

    # 2a Oportunidad - Solo para Secundaria y Bachillerato
    public static function getEstatusSeleccion2aOportunidad($idpersona, $periodoadmision)
    {
        # Buscamos en TblAspirantes el nivel y grado del aspirante
        $oDGA = Aspirante::select('*')
                ->where('idpersona', $idpersona)
                ->where('TblCPA_id', $periodoadmision)
                ->first();

        \Log::info("Entro a 2a Oportunidad");
        # Obtengo las evaluaciones capturadas
        $oEvaluaciones = AspCatMateria::select('DbInscripciones.TblAspCatMaterias.id','nombre','calificacion',
                                               'evaluacion','ponderacion','DbInscripciones.TblAspCatMaterias.tipo')
                                        ->leftJoin( 'DbEscolar.TblAspCalificaciones', function( $oJoin)  use ($idpersona){
                                                $oJoin->on( 'DbInscripciones.TblAspCatMaterias.id', '=', 'TblAspCalificaciones.TblACM_id')
                                                      ->on( 'TblDGP_id', '=', Db::Raw($idpersona))
                                                      ->on( 'TblAspCalificaciones.oportunidad', '=', Db::Raw('2'));
                                        })
                                        ->where('DbInscripciones.TblAspCatMaterias.TblCPA_id', $periodoadmision)
                                        ->where('nivel', $oDGA->nivel)
                                        ->where('grado', $oDGA->grado)
                                        ->get();
        $valor = 0; //"No Se muestra";
        if(!is_null($oEvaluaciones))
        {
            foreach($oEvaluaciones as $ev)
            {
                if($ev->tipo==2)
                {
                    #\Log::info($ev->nombre.'-> Tipo: '.$ev->tipo.' Calificacion:'.$ev->calificacion);
                    if($ev->calificacion>0  || !is_null($ev->calificacion))
                    {
                        $valor = 1;//"NO ! Se muestra";   
                    }
                }
            }
        }
        \Log::info($valor);
        return $valor;
    }
    /**
     * Busca y Obtengo el Historial de puestos Activos
     *
     * @param 
     *
     * @return objeto
     */
    public static function getHistorialdePuestosActivosUsuarioSesion()
    {
        //Temporalmente lo podré en una variable para poder realizar prueba
        $usuario = Auth::user()->id;
        // Busco el Id Persona
        $oUsers = User::where('id', $usuario)->first();
        $oHistorialdePuestos =  HistorialPuestos::leftJoin('DbIntranet.TblPuestos','DbIntranet.TblPuestos.id','=','TblHistorialPuestos.TblPP_id')
                                        ->where('TblDGP_id', $oUsers->TblDGP_id)
                                        ->where('TblPF_id',0)
                                        ->get();
        return $oHistorialdePuestos;
    }


    public static function getArrayHistorialdePuestosActivosUsuarioSesion()
    {
        //Temporalmente lo podré en una variable para poder realizar prueba
        $usuario = Auth::user()->id;
        // Busco el Id Persona
        $oUsers = User::where('id', $usuario)->first();
        $arrHistorialdePuestos = HistorialPuestos::leftJoin('DbIntranet.TblPuestos','DbIntranet.TblPuestos.id','=','TblHistorialPuestos.TblPP_id')
                                        ->where('TblDGP_id', $oUsers->TblDGP_id)
                                        ->where('TblPF_id',0)
                                        ->pluck('TblPP_id')
                                        ->toArray();
        return $arrHistorialdePuestos;
    }

    public static function getTipoPermisoDocumento($TblGDC_id)
    {
        $aHistorialdePuestos = self::getArrayHistorialdePuestosActivosUsuarioSesion();
        $oPermisos = GDPermiso::where('TblGDC_id', $TblGDC_id)->first();
            
        $tipo_permiso = "lectura";
            
        if(!is_null($oPermisos))
        {
            if(in_array($oPermisos->TblP_id, $aHistorialdePuestos))
            {
                $tipo_permiso = 'escritura';
            }
        }
        return $tipo_permiso;
    }
    /**
     * Busca y Obtengo el idPadre 0 (último en la rama) del id puesto dado
     *
     * @param int $idPuesto Nombre id del Puesto. Requerido.
     *
     * @return int Id Puesto Padre = 0
     */
    public static function getPuestoIdPadreCero($idPuesto)
    {
        $idPuestoPadre = NULL;
        $idPadreCero = false;

        while ( ! $idPadreCero ) 
        {
            //SELECT * FROM dbintranet.tblpuestos WHERE id=78;
            $oPuestos = Puesto::where('id', $idPuesto)->first();
            $idPuesto = $oPuestos->IdPadre;
            if($idPuesto==0)
            {
               $idPadreCero=true; 
            }
            #\Log::info($idPuesto);
            #\Log::info($oPuestos->id);
            #\Log::info($idPadreCero);
        }

        $oPuestos = Puesto::where('id', $oPuestos->id)->first();

        return  $oPuestos;
    }

    /**
     * Busca y Obtengo el departamento (siguiente rama superior) del id puesto dado
     *
     * @param int $idPuesto Nombre id del Puesto. Requerido.
     *
     * @return objeto
     */
    public static function getPuestoSuperior($idPuesto)
    {
        $idPadreSuperior = false;
        $x=0;

        while ( ! $idPadreSuperior ) 
        {
            $x++;
            //SELECT * FROM dbintranet.tblpuestos WHERE id=78;
            $oPuestos = Puesto::where('id', $idPuesto)->first();
            $idPuesto = $oPuestos->IdPadre;
            if($x==1)
            {
               $idPadreSuperior=true; 
            }
            #\Log::info($idPuesto);
            #\Log::info($oPuestos->id);
            #\Log::info($idPadreSuperior);
        }

        $oPuestos = Puesto::where('id', $oPuestos->id)->first();

        return  $oPuestos;
    }

    /**
     * Busca y Obtengo toda la ubicación del Archivo a partir de su Id de Carpeta
     *
     * @param int $TblGDC_id id de la Carpeta donde se encuentra el archivo. Requerido.
     *
     * @return objeto
     */
    public static function getRutaDocummento($TblGDC_id)
    {
        $oCarpeta = GDCarpeta::where('id',$TblGDC_id)->first();

        $IdPadre =  $oCarpeta->IdPadre;
        $arrCarpetas = [];
        array_push($arrCarpetas, $oCarpeta->Nombre);
        while ( $IdPadre != 0) 
        {
            $oBusquedaPadreCero = GDCarpeta::where('id',$IdPadre)->first();
            array_push($arrCarpetas, $oBusquedaPadreCero->Nombre);
            $IdPadre = $oBusquedaPadreCero->IdPadre;
        }

        return  $arrCarpetas;
    }

    /**
     * Genera un cliente para la conexión con la API de Google.
     *
     * @param string $nombreAplicacion Nombre para el cliente. Requerido.
     *
     * @return Google\Client $cliente
     */
    public static function crearClienteAPIGoogle($nombreAplicacion)
    {
        # El archivo de credenciales es necesario para conectarse con la API de Google.
        $rutaArchivoCredenciales = realpath(self::getVariablePermante('ruta_credenciales_google_api', ''));

        $cliente = null;
        if ($rutaArchivoCredenciales) {
            $cliente = new \Google\Client;
            $cliente->setApplicationName($nombreAplicacion);
            $cliente->setSubject('adminict@ict.edu.mx');
            $cliente->setAuthConfig($rutaArchivoCredenciales);
        }

        return $cliente;
    }

    /**
     * Función para obtener un json formateado
     *
     * @param  array $aElementos
     * @return json
     */
    public static function getArrayToJson( $aElementos = [] )
    {
        $aResultado = [];
        # recoremos el array para genera el resultado
        foreach ($aElementos as $key => $valor) {
            $aElemento = [
                'value'=>$key,
                'label'=>$valor,
            ];
            array_push( $aResultado, $aElemento );
        }
        # retornamos el json
        return json_encode( $aResultado );
    }

    /**
     * retorna el valor de una llave en particular, se puede pasar sub llaves separadas por |, maximo 3 niveles.
     *
     * @param string $sJSON     - Cadena JSON
     * @param string $sKey      - llave a buscar
     * @param bolean $bJSON     - Devuente array ( false ) o cadena ( true )
     * @return array / string
     */
    public static function getValJSON( $sJSON='', $sKey='', $bJSON = false )
    {
       # converimos a array el json
        $aJSON = json_decode( $sJSON, true );
        # detectamos los errores de la decodificacion
        if ( ! ( is_string( $sJSON ) && is_array( json_decode( $sJSON, true ) ) && ( json_last_error() == JSON_ERROR_NONE ) ) ) {
            # code...
            return 0;
        }
        #\Log::info( $sJSON );
        #\Log::info( $aJSON );
        #\Log::info( $sKey );
        #\Log::info( json_last_error() );
        # creamos la cadena que devolveremos de inicio vacia
        $sValor = '';
        # separamos las keys su sob varias
        $aKeys = explode('|', $sKey);
        # si existe lo obtenemos
        switch (count($aKeys)) {
            case 1:
                # code...
                if (array_key_exists($aKeys[0], $aJSON)) {
                    $sValor = $aJSON[$aKeys[0]];
                }
                break;
            case 2:
                # code...
                if (array_key_exists($aKeys[0], $aJSON)) {
                    #\Log::info('entro 1');
                    #\Log::info($aKeys[0]);
                    #\Log::info($aJSON[$aKeys[0]]);
                    if (array_key_exists($aKeys[1], $aJSON[$aKeys[0]])) {
                        #\Log::info('entro 2');
                        #\Log::info($aKeys[1]);
                        $sValor = $aJSON[$aKeys[0]][$aKeys[1]];
                    }else{
                        #\Log::info('entro 3');
                        foreach ( $aJSON[$aKeys[0]] as $key => $aValue ) {
                            # code...
                            #\Log::info($aValue);
                            if ( @$aValue['campo'] == $aKeys[1] ) {
                                # code...
                                $sValor = $aValue['Valor'];
                            }
                        }
                    }
                }
                break;
            case 3:
                # code...
                if (array_key_exists($aKeys[0], $aJSON)) {
                    if (array_key_exists($aKeys[1], $aJSON[$aKeys[0]])) {
                        if (array_key_exists($aKeys[2], $aJSON[$aKeys[0]][$aKeys[1]])) {
                            $sValor = $aJSON[$aKeys[0]][$aKeys[1]][$aKeys[2]];
                        }
                    }
                }
                break;
        }
        //$sValor = $aJSON[$sKey];
        # retornamos el valor
        return ( $bJSON ) ? json_encode( $sValor ) : $sValor;
    }

    /**
     * retorna una cadena con la estructura de la encuesta
     *
     * @param string $sJSON     - Cadena JSON
     * @return string
     */
    public static function GetEncuestaValor( $aEncustas= [], $sCID = '', $iValor = 0 ){
        $sEtiqueta = '';
        foreach ( $aEncustas as $key => $aPregunta ) {
            if ( $aPregunta[ 'cid' ] == $sCID ) {
                foreach ( $aPregunta[ 'field_options' ][ 'options' ] as $key => $aOpcion ) {
                    if ( ( $key + 1 ) == $iValor ) {
                        $sEtiqueta = $aOpcion[ 'label' ];
                        break;
                    }
                }
                break;
            }
        }
        return $sEtiqueta;
    }

    /**
     * Publica una notificacion en el sistema
     *
     * @param int $TblDGP_id_De         - id de la persona que envia la notificacion
     * @param int $TblDGP_id_Para       - id de la persona a la que va la notificacion
     * @param string $sNotificacion     - Textos a mostrar
     * @param int $TblM_id              - id del modulo que envia la notificación, es necesario tener un enlace en la taba TblMenu para obtener el url
     */
    public static function PutNotificacion( $TblDGP_id_De = 0, $TblDGP_id_Para = 0, $sTitulo = '', $sNotificacion = '', $TblM_id = 0 )
    {
        // creamos la estructura
        $data = [
            "id" => 1,
            "channel" => "intra.notificaciones",
            "emit" => "notification",
            'TblDGP_id_De' => $TblDGP_id_De,
            'TblDGP_id_Para' => $TblDGP_id_Para,
            'Titulo' => $sTitulo,
            'Notificacion' => $sNotificacion,
        ];
        // conectamos con redis
        $oRedis = Redis::connection();
        // publicamos el en canal referenciado
        $oRespuesta = $oRedis->publish( $data['channel'] , json_encode( $data ) );
        # habilitamos la nav notificacion
        if ( $TblM_id != 0 ) {
            # creamos o acturalizamos la notificacion en el nav
            $oNotificacion = Notificaciones::updateOrCreate(
                [
                    'TblM_id' => $TblM_id,
                    'TblDGP_id' => $TblDGP_id_Para,
                ],
                [
                    'Texto' => $sNotificacion,
                    'Estatus' => 1,
                ]
            );
        }
    }

    /**
     * maraca las notifcaciones del modulo pasada por parametros para eliminanos las notificaciones del nav
     *
     * @param int $TblM_id              - id del modulo que envia la notificación
     * @return void
     */
    public static function voidLimpiaNotificacion( $TblM_id = 0 )
    {
        # eliminanos las notificaciones
        $oNotificacion = Notificaciones::updateOrCreate(
            [
                'TblM_id' => $TblM_id,
                'TblDGP_id' => Auth::user()->TblDGP_id,
            ],
            [
                'Estatus' => 0,
            ]
        );

    }

    /**
     * Retorna una vista si se encuentran tareas pendientes por cerrar
     *
     * @param  int $id
     * @return string
     */
    public static function GetNotificaciones()
    {
        $oNotificaciones = Notificaciones::select(
                'TblNotificaciones.*',
                'TblMod.Nombre',
                'TblMen.Icono',
                'TblMen.Ruta'
            )
            ->leftJoin( 'TblModulos AS TblMod', function( $oJoin ){
                $oJoin->on( 'TblMod.id', '=', 'TblNotificaciones.TblM_id' )
                    ->whereNull('TblMod.deleted_at');
            })
            ->leftJoin( 'TblMenu AS TblMen', function( $oJoin ){
                $oJoin->on( 'TblMen.Permiso', '=', 'TblNotificaciones.TblM_id' )
                    ->whereNull('TblMen.deleted_at');
            })
            ->where( 'TblNotificaciones.TblDGP_id', Auth::user()->TblDGP_id )
            ->where( 'TblNotificaciones.Estatus', 1 )
            ->get();
        # cargamos la vista
        return ( count( $oNotificaciones ) > 0 ) ?
            view(
                'layouts.notificaciones', #/layouts/notificaciones
                compact(
                    'oNotificaciones'
                )
            ) :
            '';
    }

    /**
     * Publica una notificacion en el sistema
     *
     * @param int $TblDGP_id_De         - id de la persona que envia la notificacion
     * @param int $TblDGP_id_Para       - id de la persona a la que va la notificacion
     * @param string $sNotificacion     - Textos a mostrar
     * @param int $TblM_id              - id del modulo que envia la notificación, es necesario tener un enlace en la taba TblMenu para obtener el url
     */
    public static function voidPutNotificacionLW( $sTitulo = '', $sNotificacion = '' )
    {
        // creamos la estructura
        $data = [
            "id" => 1,
            "channel" => "intra.notificaciones",
            "emit" => "LW",
            'Titulo' => $sTitulo,
            'Notificacion' => $sNotificacion,
        ];
        // conectamos con redis
        $oRedis = Redis::connection();
        // publicamos el en canal referenciado
        $oRespuesta = $oRedis->publish( $data['channel'] , json_encode( $data ) );
    }

    /**
     * configuramos un buzón diferente para cada envío de coreos para evitar la limitación de 1800 por buzón
     *
     * @return void
     */
    public static function voidCorreoConfiguracion()
    {
        # recuperamos la configuración activa
        $oConfiguration = CorreoConfiguracion::where( "Activo", 1 )
            ->first();
        if( ! is_null( $oConfiguration ) ) {
            # aplicamos la configuración al sistema
            $aConfiguracion = array(
                'driver'     =>     $oConfiguration->driver,
                'host'       =>     $oConfiguration->host,
                'port'       =>     $oConfiguration->port,
                'username'   =>     $oConfiguration->user_name,
                'password'   =>     $oConfiguration->password,
                'encryption' =>     $oConfiguration->encryption,
                'from'       =>     array( 'address' => $oConfiguration->sender_email, 'name' => $oConfiguration->sender_name ),
            );
            Config::set( 'mail', $aConfiguracion );
            # si el mes almacenado es distinto al registrado reiniciamos los contadores de envíos
            if ( date( 'm', strtotime( $oConfiguration->Fecha ) <> date( 'm' ) ) ) {
                # reiniciamos al inicio del mes
                CorreoConfiguracion::where( 'Activo', 1 )
                    ->update( [ 'Conteo' => 0 ] );
            }
            # registramos el envió en el contador
            $oTaller = CorreoConfiguracion::where( 'Activo', 1 )
                ->increment( 'Conteo' );
            # buscamos el siguiente registro
            $iId = $oConfiguration->id + 1;
            # verificamos que exista el registro para reiniciar el indicador al primero
            $oSiguente = CorreoConfiguracion::find( $iId );
            $iId = ( is_null( $oSiguente ) ) ? 1 : $iId;
            # cambiamos el coreo para próximo envió
            CorreoConfiguracion::where( 'Activo', 1 )
                ->update( [ 'Activo' => 0 ] );
            CorreoConfiguracion::where( 'id', $iId )
                ->update( [
                    'Activo' => 1,
                    'Fecha' => date( 'Y-m-d' ),
                ] );
        }
    }

    /**
     * Retorna una cadena representando el turno de vigilancia.
     *
     * @param  int $id
     * @return string
     */
    public static function GetTurnoVigilancia( $id='' )
    {
        # ['' => 'Seleccione...', '1' => 'MATUTINO', '2' => 'VESPERTINO', '3' => 'NOCTURNO', ];
        switch ($id) {
            case '1': return '<span class="badge bg-success">Mat</span>';
                break;
            case '2': return '<span class="badge bg-warning">Ves</span>';
                break;
            case '3': return '<span class="badge bg-dark">Noc</span>';
                break;
            default: return '<span class="badge bg-light text-dark">?</span>';
                break;
        }
    }

    /**
     * Retorna la validacion si una hora se encuentra entre un rango dado.
     *
     * @param  string $sHoraInicial
     * @param  string $sHoraFinal
     * @param  string $sHora
     * @return boolean
     */
    public static function getHoraEntre( $sHoraInicial, $sHoraFinal, $sHora ) {
        $dateFrom = DateTime::createFromFormat('!H:i', $sHoraInicial);
        $dateTo = DateTime::createFromFormat('!H:i', $sHoraFinal);
        $dateInput = DateTime::createFromFormat('!H:i', $sHora);
        if ($dateFrom > $dateTo) $dateTo->modify('+1 day');
        return ($dateFrom <= $dateInput && $dateInput <= $dateTo) || ($dateFrom <= $dateInput->modify('+1 day') && $dateInput <= $dateTo);
    }

    /**
     * Retorna una cadena formateada que la limita al número de caracteres pasados por parámetro.
     *
     * @param  str $sCadena       cadena a recortar
     * @param  int $iLargo        número de caracteres a mostrar
     * @return str
     */
    public static function strTooltip( $sCadena='', $iLargo=10 )
    {
        if(strlen($sCadena)>$iLargo){
            return substr($sCadena, 0, $iLargo)."... <a data-toggle='tooltip', data-placement='top', title='".$sCadena."' href='#'>[+]</a>";
        }else{
            return $sCadena;
        }
    }

    /**
     * retorna array con dias de la semana.
     *
     * @return string
     */
    public static function getArraySemana()
    {
        $aDias = ['1'=>'Lunes', '2'=>'Martes', '3'=>'Miércoles', '4'=>'Jueves', '5'=>'Viernes', '6'=>'Sabado', '7'=>'Domingo',];
        return $aDias;
    }

    /**
     * retorna array con dias de la semana.
     *
     * @param  string $strCadena, str $StrCampo [m,d]
     * @return string
     */
    public static function getStringDiasSemana( $sCadena="" )
    {
        $aDiasNum = explode( ',', $sCadena );
        $sDiasCadena = "";
        $aDias = self::getArraySemana();
        foreach ( $aDiasNum as $key => $value ) {
            $sDiasCadena .= ( @$aDias[ $value ] ?? '-' ).", ";
        }
        return $sDiasCadena;
    }

    /**
     * retorna array con dias de la semana.
     *
     * @return string
     */
    public static function getIdTurnoVigilancia()
    {
        $dHoy = new DateTime();
        $sHora = $dHoy->format( 'H:i' );
        return ( Self::getHoraEntre( '00:00', '06:30', $sHora ) ? 3 : ( Self::getHoraEntre( '06:30', '13:00', $sHora ) ? 1 : ( Self::getHoraEntre( '13:00', '22:00', $sHora ) ? 2 : 3 ) ) );
    }

    /**
     * retorna una cadena preformateada con labels de los tallere
     * bandera (2do parametro)
     *
     * @param  string $sTelleres
     * @param  boolean $bBadge
     * @return string
     */
    public static function getBadgeTalleres( $sTalleres = '', $bBadge = true ) : string
    {
        $aColores = [ 0=>"danger", 1=>"warning", 2=>"yellow", 3=>"lime", 4=>"green", 5=>"success", 6=>"primary", 7=>"info", 8=>"purple", 9=>"indigo", 10=>"dark", 11=>"secondary", ];
        # creamos un array con los talleres
        $aTalleres = explode( ',', $sTalleres );
        # datos del nivel que recuperamos del array
        $oRegistros = Talleres::select( 'Taller' )
            ->find( $aTalleres );
        # Cadena a regresar
        $sLabels = '';
        # recoremos el array
        foreach ( $oRegistros as $key => $oRegistro ) {
            # lo agregamso a la cadena
            $sLabels .= ( $bBadge ) ? ( '<span class="badge bg-'. @$aColores[ $key ] .'">'. $oRegistro->Taller .'</span> ' ) : ( $oRegistro->Taller . ', ' );
        }
        return $sLabels;
    }

    /**
     * devuelve el control banco
     *
     * @param  string $RFC
     * @return boolean
     */
    public static function GetControlBanco($FolioCompleto)
    {
        // El folio se voltea como parte del proceso del algoritmo
        $FolioCompleto = strrev($FolioCompleto);

        $LenFolio=strlen($FolioCompleto);
        $sumaProductos = 0;
        for ($i=0; $i <= $LenFolio - 1; $i++) {
            // Las posiciones impares se multiplican por 2, las pares por 1
            $producto = substr($FolioCompleto, $i, 1) * (($i + 1) % 2 + 1);

            // Si el producto tiene 2 dígitos éstos se suman
            if ($producto > 9) {
                $producto = intval($producto / 10) + $producto % 10;
            }

            $sumaProductos += $producto;
        }

        // El dígito verificador es la diferencia de 10 menos el remanente de la suma
        // de los productos entre 10
        $mod = $sumaProductos % 10;
        if ($mod == 0) {
            $digitoVerificador = 0;
        } else {
            $digitoVerificador = 10 - $mod;
        }

        $ControlBanco = strrev($FolioCompleto) . $digitoVerificador;
        return $ControlBanco;
    }

    /**
     * Crea el pago para el taller
     *
     * @param  object $oPeriodos
     * @param  object $oDatosAlumno
     * @param  object $oInscrito
     * @return null
     */
    public static function getPagoInscripcionTaller( $oPeriodos, $oDatosAlumno, $oInscrito )
    {
        # Note : GD [ Talleres ] - Si se modifica algo cambiarlo en SL 2 f: getPagoInscripcionTaller - Libreria.php:3196
        #
        $fSuma = TalleresArticulos::select(
                'TblSLOLA.Precio'
            )
            ->leftJoin( 'DbServiciosEnLinea.TblSLOLArticulos AS TblSLOLA', function( $oJoin ){
                $oJoin->on( 'TblSLOLA.id', '=', 'TblIctTalleresArticulos.TblSLOLA_id' )
                    ->whereNull( 'TblSLOLA.deleted_at' );
            })
            ->where( 'TblIctTalleresArticulos.TblIT_id', $oInscrito->TblIT_id )
            ->where( 'TblIctTalleresArticulos.Estatus', 1 )
            ->sum( 'TblSLOLA.Precio' );
        # recuperamos los datos del sub concepto de la cuenta
        $oSubconcepto = PagosConceptos::select(
                DB::raw( 'TblPagosConceptos.id AS TblPC_id' ),
                'TblPSI.Cuenta',
                DB::raw( 'TblPSI.id AS TblPSI_id' )
            )
            ->leftjoin( 'TblPagosSubconceptos AS TblPS', function( $join ){
                $join->on( 'TblPagosConceptos.id', '=', 'TblPS.TblPC_id' )
                    ->whereNull( 'TblPS.deleted_at' );
            })
            ->leftjoin( 'TblPagosSubconceptosImportes AS TblPSI', function( $join ){
                $join->on( 'TblPS.id', '=', 'TblPSI.TblPS_id' )
                    ->whereNull( 'TblPSI.deleted_at' );
            })
            ->where( 'TblPagosConceptos.Nivel', $oDatosAlumno->nivel )
            ->where( 'TblPagosConceptos.Concepto', 'NOT LIKE', DB::raw( "'%EXTRA%'" ) )
            ->where( 'TblPS.Clave', DB::raw( "'074'" ) )
            ->whereIn( 'TblPSI.Grado', [ $oDatosAlumno->grado, 0 ] )
            ->first();
        #
        $sConsecutivo = '01';
        # Se buscan todos los registros de pago (incluyendo los dados de baja) que coincidan con los criterios
        $oPagosConsecutivos = PagoOrdinario::withTrashed()
            ->where( 'idpersona', $oDatosAlumno->idpersona )
            ->where( 'nivel', $oDatosAlumno->nivel )
            ->where( 'periodoacademico', $oPeriodos->ActualCadena )
            ->where( 'clavesubconcepto', DB::raw( "'074'" ) )
            ->where( 'claveconcepto', $oSubconcepto->TblPC_id )
            ->orderBy( 'id', 'desc' )
            ->get();
        # Sólo se calcula el siguiente consecutivo si existen registros, de lo contrario el consecutivo sera '01'
        if ( $oPagosConsecutivos->count() > 0 ) {
            # Si el controlbanco es menor de 24 dígitos estamos hablando de un CB previo a la inclusión del consecutivo,
            # por tanto se calculará con base en la cantidad de regitros encontrados + 1
            $n = ( strlen( $oPagosConsecutivos->first()->controlbanco ) == 24 ) ?
                ( $oPagosConsecutivos->count() + 1 ) : # contamos los elemetos ya creados e incrementamos
                ( substr( $oPagosConsecutivos->first()->controlbanco, -3, 2 ) + 1 ); # si no, tomamso los elemenos 2 ultimos de el control banco
            # creramos la cadena del consecutivo
            $sConsecutivo = str_pad( $n, 2 , "0", STR_PAD_LEFT ); # rellemamos de 0 maimo 2
        }
        # Formato: <año><nivel><concepto><subconcepto><numcontrol><consecutivo>,
        # 4 + 2 + 3 + 3 + 9 + 2 = 23 caracteres
        $sCB = sprintf(
            '%04s%02s%03s%03s%09s%02s',
            substr($oPeriodos->ActualCadena, -4),
            $oDatosAlumno->nivel,
            $oSubconcepto->TblPC_id,
            '074',
            ($oDatosAlumno->numcontrol != '0') ? $oDatosAlumno->numcontrol : $oDatosAlumno->idpersona,
            $sConsecutivo
        );
        # le agregamos la homoclave
        $sCB = Self::GetControlBanco( $sCB );
        # Se genera el adeudo de la tienda de uniformes
        $oAdeudo = PagoOrdinario::create([
            'idpersona' => $oDatosAlumno->idpersona,
            'nivel' => $oDatosAlumno->nivel,
            'grado' => $oDatosAlumno->grado,
            'periodoacademico' => $oPeriodos->ActualCadena,
            'controlbanco' => $sCB,
            'fechadevencimiento' => date( 'Y-m-d', strtotime( '+10 days' ) ),
            'importebruto' => $fSuma,
            'importedescuentorecargo' => 0,
            'importeneto' => $fSuma,
            'numcuenta' => $oSubconcepto->Cuenta,
            'clavesubconcepto' => '074',
            'claveconcepto' => $oSubconcepto->TblPC_id,
            'TblPSI_id' => $oSubconcepto->TblPSI_id,
            'folio' => 0,
            'clavetransferencia' => 0,
            'fechadepago' => '',
            'mediodepago' => '',
            'formadepago' => '',
            'referencia1' => '',
            'referencia2' => '',
            'referencia3' => '',
            'clavesucursal' => '',
            'hora' => '',
            'foliobancario' => '',
            'tipoderegistro' => 0,
            'tipomovimiento' => 0,
            'codigorechazo' => 0,
            'folioficha' => 0,
            'estatus' => 'No Pagado',
        ]);
        #
        $oRegistro = TalleresInscripciones::create([
            'TblTDGP_id' => $oDatosAlumno->idpersona,
            'TblIT_id' => $oInscrito->TblIT_id,
            'TblCPA_id' => $oPeriodos->Actual,
            'TblPO_id' => $oAdeudo->id,
            'Estatus' => 1,
        ]);

        return $oRegistro;
    }

    /**
     * devuelve un json con un arbol de departamentos
     *
     * @return json
     */
    public static function getJSONDepartamentos()
    {
        # creamos el arbol de puestos
        $aPuestos = [];
        # obtenemos todos los registros de los puestos
        $aPuesto = Puesto::select(
                'id',
                'IdPadre',
                'Nombre AS text',
            )
            ->get()
            ->toArray();
        # creamos la
        $aPuestoOriginal = $aPuesto;
        $aPuestosNuevo = [];
        foreach ( $aPuestoOriginal as $aElemento ){
            $aPuestosNuevo[ $aElemento[ 'IdPadre' ] ][] = $aElemento;
        }
        #
        foreach ( $aPuestoOriginal as $key => $value ) {
            if ( $aPuestoOriginal[ $key ][ 'IdPadre' ] == 0  ) {
                $aArbol = Self::createTree($aPuestosNuevo, array($aPuestoOriginal[$key]));
                $aPuestos[] = $aArbol[ 0 ];
            }
        }
        # retornamos el arbol
        return json_encode( $aPuestos, JSON_UNESCAPED_UNICODE );
    }

    /**
     * devuelve la rama de nodo pasado por parametro
     *
     * @param  array $aList
     * @param  array $aParent
     * @return array
     */
    public static function createTree( &$aList, $aParent ){
        $aArbol = [];
        foreach ( $aParent as $k => $l ){
            if( isset( $aList[ $l[ 'id' ] ] ) ){
                $l[ 'inc' ] = Self::createTree( $aList, $aList[ $l[ 'id' ] ] );
            }
            $aArbol[] = $l;
        }
        return $aArbol;
    }

    /**
     * retorna un booleano con la validación si una fecha se encuentra dentro de un rango de fechas
     *
     * @param  string $fecha_inicio, $fecha_fin, $fecha
     * @return boolen
     */
    public static function FechaEnRango( $fecha_inicio, $fecha_fin, $fecha )
    {
        // fechas
        $fecha_inicio = strtotime( $fecha_inicio ?? '' );
        $fecha_fin = strtotime( $fecha_fin ?? '' );
        $fecha = strtotime($fecha);
        //esta
        if(($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Calcula la diferencia en años entre 2 fechas dadas.
     *
     * @param  array $aList
     * @param  array $aParent
     */
    public static function fDiferienciaFechas( $fInicial = null, $fFinal = null, $bBag = true ) : string
    {
        $fechaInicial = new DateTime( $fInicial );
        $fechaFinal = new DateTime( $fFinal );
        $intervalo = $fechaInicial->diff( $fechaFinal );

        //Para obtener el periodo en formato d h m
        return $intervalo->format( ( $bBag ) ? ( ( $intervalo->d > 2 ) ? "<span class='text-danger'>%dD %hH %iM</span>" : "%dD %hH %iM" ) : "%dD %hH %iM" );
    }


    public static function to_word($number, $miMoneda = null)
    {
        $MONEDAS = [
            ['country' => 'Colombia', 'currency' => 'COP', 'singular' => 'PESO COLOMBIANO', 'plural' => 'PESOS COLOMBIANOS', 'symbol', '$'],
            ['country' => 'Estados Unidos', 'currency' => 'USD', 'singular' => 'DÓLAR', 'plural' => 'DÓLARES', 'symbol', 'US$'],
            ['country' => 'Europa', 'currency' => 'EUR', 'singular' => 'EURO', 'plural' => 'EUROS', 'symbol', '€'],
            ['country' => 'México', 'currency' => 'MXN', 'singular' => 'PESO 00/100 M.N.', 'plural' => 'PESOS 00/100 M.N.', 'symbol', '$'],
            ['country' => 'Perú', 'currency' => 'PEN', 'singular' => 'NUEVO SOL', 'plural' => 'NUEVOS SOLES', 'symbol', 'S/'],
            ['country' => 'Reino Unido', 'currency' => 'GBP', 'singular' => 'LIBRA', 'plural' => 'LIBRAS', 'symbol', '£']
        ];

        if ($miMoneda !== null) {
            try {

                $moneda = array_filter( $MONEDAS, function($m) use ($miMoneda) {
                    return ($m['currency'] == $miMoneda);
                });
                $moneda = array_values($moneda);
                if (count($moneda) <= 0) {
                    throw new Exception("Tipo de moneda inválido");
                    return;
                }
                if ($number < 2) {
                    $moneda = $moneda[0]['singular'];
                } else {
                    $moneda = $moneda[0]['plural'];
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                return;
            }
        } else {
            $moneda = " ";
        }
        $converted = '';
        if (($number < 0) || ($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }
        $numberStr = (string) $number;
        $porciones = explode(".", $numberStr);
        $numberStr =  @$porciones[0]; // porción1
        $descimalStr =  @$porciones[1]."/"; // porción2
        $moneda = str_replace( "00/", $descimalStr, $moneda );
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);
        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', Self::convertGroup($millones));
            }
        }

        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', Self::convertGroup($miles));
            }
        }
        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', Self::convertGroup($cientos));
            }
        }
        $converted .= $moneda;
        return $converted;
    }

    private static function convertGroup($n)
    {
        $UNIDADES = [
            '',
            'UN ',
            'DOS ',
            'TRES ',
            'CUATRO ',
            'CINCO ',
            'SEIS ',
            'SIETE ',
            'OCHO ',
            'NUEVE ',
            'DIEZ ',
            'ONCE ',
            'DOCE ',
            'TRECE ',
            'CATORCE ',
            'QUINCE ',
            'DIECISEIS ',
            'DIECISIETE ',
            'DIECIOCHO ',
            'DIECINUEVE ',
            'VEINTE '
        ];
        $DECENAS = [
            'VENTI',
            'TREINTA ',
            'CUARENTA ',
            'CINCUENTA ',
            'SESENTA ',
            'SETENTA ',
            'OCHENTA ',
            'NOVENTA ',
            'CIEN '
        ];
        $CENTENAS = [
            'CIENTO ',
            'DOSCIENTOS ',
            'TRESCIENTOS ',
            'CUATROCIENTOS ',
            'QUINIENTOS ',
            'SEISCIENTOS ',
            'SETECIENTOS ',
            'OCHOCIENTOS ',
            'NOVECIENTOS '
        ];

        $output = '';
        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = $CENTENAS[$n[0] - 1];
        }
        $k = intval(substr($n,1));
        if ($k <= 20) {
            $output .= $UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', $DECENAS[intval($n[1]) - 2], $UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', $DECENAS[intval($n[1]) - 2], $UNIDADES[intval($n[2])]);
            }
        }
        return $output;
    }

    public static function apartarCita($oDatosAspirante)
    {
        $aPsicologos = [];
        if ($oDatosAspirante->nivel == 4 && $oDatosAspirante->grado == 4) {
            $oPeriodoAdmision = \App\Models\Escolar\CatPeriodosAdmisiones::where('estatus', 'ACTIVO')
                ->distinct()
                ->first();

            $aPsicologos = \App\Models\Escolar\Aspirantes\Entrevistador::whereIn('numaplicacion', ['A','B'])
                ->where('TblCPA_id', $oPeriodoAdmision->id)
                ->pluck('id')
                ->toArray();
        }
        // La cita a tomar debe ser al menos 48 horas después de la fecha
        // y hora de la conciliación del pago.
        $oCita = \App\Models\Escolar\Aspirantes\Cita::whereNull('TblA_id')
            ->whereRaw("CONCAT(Fecha, ' ', Hora) >= NOW() + INTERVAL 48 HOUR")
            ->where(function($oConsulta) use($aPsicologos){
                if ($aPsicologos) {
                    $oConsulta->whereIn('TblPA_id', $aPsicologos);
                }
            })
            ->orderBy('Fecha')
            ->orderBy('Hora')
            ->first();

        if ($oCita) {
            $oCita->TblA_id = $oDatosAspirante->TblA_id;
            $oCita->save();
        }

        return optional($oCita)->id;
    }

    /**
     * retorna una cadena preformateada con labels o sin ellos dependiendo del la
     * bandera (2do parametro)
     *
     * @param  array $aNiveles
     * @param  bolean $bLista
     * @return string
     */
    public static function ArrGradToLabels($aNiveles=[], $bLista=false)
    {
        # elimiar cadena vacias
        $aNiveles = array_filter( $aNiveles );
        # Cadena a regresar
        $sLabels = '';
        # recoremos el array
        foreach ($aNiveles as $key => $value) {
            # datos del nivel que recuperamos del array
            $oRegistro = Grados::select(
                    DB::raw('TblN.id as Nivel'),
                    'TblN.NombreNivel',
                    'TblGrados.NombreGrado',
                    'TblN.Color'
                )
                ->leftJoin('TblNiveles AS TblN','TblN.id','=','TblGrados.TblN_id')
                ->orderBy('TblN.Orden')
                ->find($value);
            # no es nulo creamos/agregamos la cadena
            if (!is_null($oRegistro)) {
                # lo agregamso a la cadena
                $sLabels .= ($bLista)?
                    Str::limit($oRegistro->NombreGrado, 3, '') :
                    '<span class="badge bg-'.$oRegistro->Color.'" data-toggle="tooltip" data-placement="top" title data-bs-original-title="'.$oRegistro->NombreNivel.'" aria-label="'.$oRegistro->NombreNivel.'" >'.Str::limit($oRegistro->Nivel.'-'.$oRegistro->NombreGrado, 3, '').'</span> ';
            }
        }
        # comparamos si son todas los grados para poner una etiqueta full
        $oRegistro = Grados::select(
                'TblGrados.id'
            )
            ->get();
        # retornamos
        return ( count( $oRegistro ) == count( $aNiveles ) ) ? '<span class="badge bg-dark" >Todos</span>' : $sLabels;
    }


    #---- Módulo de Encuestas - Escolares | Rebeca Ruiz Roque 27/04/2023

    /*
     * Obtengo las subcategorías de cada Categoría -  TblSubCategorias
     * @param TblACC_id
     *
     */
    //
    public static function GetEncuestasSubCategorias($id)
    {
        $oEncuestasSubCategorias = EncuestasSubCategoria::where('TblC_id', $id)->orderBy('orden')->get();
        if (is_null($oEncuestasSubCategorias))
        {
            return null;
        }else
        {
            return $oEncuestasSubCategorias;
        }
    }
    /**
     * Devuelve las Respuestas del catálago TblEncuestaCatRespuestasCerradas
     *    
    */
    public static function GetEncuestasCatRespuestas($idpregunta)
    {
        $oEncuestasCatRespuestas = EncuestasRespuestasCerradasCatalogo::where('TblCP_id', $idpregunta)
                                                    ->orderBy('Orden')
                                                    ->get();

        return $oEncuestasCatRespuestas; 
    }   

    public static function GetArrayEncuestasCatRespuestas2($idpregunta)
    {
        $oEncuestasCatRespuestas = EncuestasRespuestasCerradasCatalogo::where('TblCP_id', $idpregunta)
                                                    ->orderBy('Orden')
                                                    ->pluck('Respuesta', 'Valor')
                                                    ->toArray();
        return $oEncuestasCatRespuestas; 
    }   



    /**
     * Devuelve un Array las Respuestas del catálago TblEncuestaCatRespuestasCerradas
     *    
    */
    public static function GetArrayEncuestasCatRespuestas($idpregunta)
    {
        $oEncuestasCatRespuestas = EncuestasRespuestasCerradasCatalogo::where('TblCP_id', $idpregunta)
                                                    ->orderBy('Orden')
                                                    ->pluck('Respuesta')
                                                    ->toArray();

        return $oEncuestasCatRespuestas; 
    }

    /**
     * Devuelve las Pregutas del catálago TblCatPreguntas
     *    
    */
    public static function GetEncuestasCatPreguntas($idsubcategoria)
    {
        $oEncuestasCatPreguntas = EncuestasPreguntas::where('TblSC_id', $idsubcategoria)
                                                    ->orderBy('Orden')
                                                    ->get();

        return $oEncuestasCatPreguntas; 
    }    

    /**
     * Devuelve las Pregutas del catálago TblCatPreguntas
     *    
    */
    public static function GetEncuestasCatPreguntasPrimerPregunta($idsubcategoria)
    {
        $oEncuestasCatPreguntas = EncuestasPreguntas::where('TblSC_id', $idsubcategoria)
                                                    ->orderBy('Orden')
                                                    ->first();

        return $oEncuestasCatPreguntas; 
    } 


    /*SELECT TblCP_id, COUNT(*) AS num
    FROM tblrespuestascerradas
    LEFT JOIN TblCatPreguntas ON tblrespuestascerradas.TblCP_id=TblCatPreguntas.id
    WHERE TblCatPreguntas.TblCE_id=1
    GROUP BY TblCP_id*/
    /**
     * Devuelve la cantidad de encuestas respondidas
     *    
    */
    public static function GetCantidadEncuestasRespondidas($idencuesta)
    {
        $oEncuestasRespuestasCerradas = EncuestasRespuestasCerradas::select(DB::Raw("TblCP_id, COUNT(*) AS num"))
                                                ->leftJoin('TblCatPreguntas','TblCatPreguntas.id','=','TblRespuestasCerradas.TblCP_id')
                                                ->where('TblCatPreguntas.TblCE_id', $idencuesta)
                                                ->groupBy('TblCP_id')
                                                ->first();
        if (is_null($oEncuestasRespuestasCerradas))
        {
            return 0;
        }else
        {
            return $oEncuestasRespuestasCerradas->num;
        }
    }

    // Función para obtener la cantidad de respuestas por idpregunta y valor de la respuesta, TblRespuestasCerradas
    // parámetros id de la pregunta y Valor de la respuesta
    public function GetCantidaddeRespuestasByIdPreguntaValor($id, $valor)
    {
        $oERC = EncuestasRespuestasCerradas::where('TblCP_id', $id)
                                                ->where('Valor', $valor)
                                                ->whereNull('deleted_at')
                                                ->count();
        return $oERC;
    }

    // Función para obtener los datos para la gráfica de pastel de la Encuesta de Becas
    // parámetros: idpregunta 
    public function GetDatosEncuestaficaByIdPregunta($idpregunta)
    {
        $oERC = EncuestasRespuestasCerradasCatalogo::select(\DB::raw("count(*) as cantidad, TblCatRespuestasCerradas.Respuesta"))
                            ->leftJoin('TblRespuestasCerradas',function($join){
                            $join->on('TblCatRespuestasCerradas.TblCP_id','=','TblRespuestasCerradas.TblCP_id');
                            $join->on('TblCatRespuestasCerradas.Valor','=','TblRespuestasCerradas.Valor');
                        })
                        ->where('TblRespuestasCerradas.TblCP_id', $idpregunta)
                        ->whereNull('TblCatRespuestasCerradas.deleted_at')
                        ->groupBy('TblCatRespuestasCerradas.Respuesta')
                        ->get();
        return $oERC;
    }


    #---- Fin Módulo Encuestas - Escolares | Rebeca Ruiz Roque 27/04/2023
}
