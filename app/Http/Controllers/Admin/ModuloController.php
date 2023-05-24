<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ModuloRequest;

use App\Models\Admin\Modulo;

use DB, Exception, Str, Route;

class ModuloController extends Controller
{
    private $aModulos, $aTipos, $aAccionesPredeterminadas, $aRutas;

    public function __construct()
    {
        $this->aModulos = Modulo::whereIn('Tipo', [1, 2])
            ->select(
                'id',
                DB::raw("CONCAT(id, ' - ', Nombre) AS Nombre")
            )
            ->pluck('Nombre', 'id')
            ->prepend('0 - Raíz', 0)
            ->toArray();

        $this->aTipos = [1 => 'Carpeta', 'Módulo', 'Acción'];

        $this->aAccionesPredeterminadas = ['Ver', 'Agregar', 'Modificar', 'Borrar'];

        // Se determina qué ruta es la que se está viendo, y si es la ruta para crear o
        // modificar módulos entonces extrae la lista de rutas del sistema.
        $c = explode('.', Route::currentRouteName());
        $r = end($c);

        $this->aRutas = ['#' => '#'];
        if (in_array($r, ['create', 'edit'])) {
            $oRutas = Route::getRoutes();
            foreach ($oRutas as $oRuta) {
                if (isset($oRuta->action['as'])) {
                    $this->aRutas[$oRuta->action['as']] = $oRuta->action['as'];
                }
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $oModulos = Modulo::all();

        # admin/modulos/index
        return view('admin.modulos.index')
            ->with(compact('oModulos'))
            ->with('aArbol', $this->obtenerArbol($oModulos))
            ->with( 'sSelecto', "2,4" );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        # admin/modulos/create
        return view('admin.modulos.create')
            ->with('oModulo', null)
            ->with('aModulos', $this->aModulos)
            ->with('aTipos', $this->aTipos)
            ->with('aRutas', $this->aRutas)
            ->with( 'sSelecto', "2,4" );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Admin\ModuloRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModuloRequest $request)
    {
        return $this->guardarDatos($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $oModulo = Modulo::find($id);

        # admin/modulos/show
        return view('admin.modulos.show')
            ->with(compact('oModulo'))
            ->with('aModulos', $this->aModulos)
            ->with('aTipos', $this->aTipos)
            ->with('aRutas', $this->aRutas)
            ->with( 'sSelecto', "2,4" );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $oModulo = Modulo::find($id);

        # admin/modulos/edit
        return view('admin.modulos.edit')
            ->with(compact('oModulo'))
            ->with('aModulos', $this->aModulos)
            ->with('aTipos', $this->aTipos)
            ->with('aRutas', $this->aRutas)
            ->with( 'sSelecto', "2,4" );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Admin\ModuloRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ModuloRequest $request, $id)
    {
        return $this->guardarDatos($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $oModulo = Modulo::find($id);
            $oModulo->delete();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Módulo eliminado correctamente.'
            ]);

            return redirect()->route('admin.modulos.index');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([Str::limit($e, 250, '...')]);
        }
    }

    private function guardarDatos($request, $id = 0)
    {
        try {
            DB::beginTransaction();

            $datos = $request->all();

            # Para las acciones el valor predeterminado de Visible es 1.
            if ($request->Tipo == 3) {
                $datos['Visible'] = 1;
            } else {
                $datos['Visible'] = intval($request->has('Visible'));
            }

            if ($id) {
                $oModulo = Modulo::find($id);
                $oModulo->fill($datos);
                $oModulo->save();
            } else {
                $oModulo = Modulo::create($datos);
            }

            if ($request->Tipo == 2 && $request->has('AccionesPredeterminadas')) {
                # Se extraen las acciones predeterminadas activas correspondientes al módulo.
                $aAccionesPredeterminadas = Modulo::where('IdPadre', $oModulo->id)
                    ->whereIn('Nombre', $this->aAccionesPredeterminadas)
                    ->select('Nombre')
                    ->get()
                    ->toArray();

                # Sólo se crean las acciones predeterminadas que no existan.
                foreach ($this->aAccionesPredeterminadas as $accion) {
                    if (!in_array($accion, $aAccionesPredeterminadas)) {
                        $oModuloAccion = Modulo::create([
                            'IdPadre' => $oModulo->id,
                            'Nombre' => $accion,
                            'Tipo' => 3, # 3 - Acción.
                            'Visible' => 1
                        ]);
                    }
                }
            }

            DB::commit();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Datos guardados correctamente.'
            ]);

            return redirect()->route('admin.modulos.index');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([Str::limit($e, 250, '...')]);
        }
    }

    public function obtenerArbol($oModulos = null)
    {
        if (!$oModulos) {
            $oModulos = Modulo::all();
        }

        $aArbol = [];

        $aArbol[] = [
            'id' => 0,
            'parent' => '#',
            'text' => 'Módulos',
            'icon' => 'fa fa-circle',
            'state' => [
                'opened' => true,
                'disabled' => true
            ]
        ];

        foreach ($oModulos as $oModulo) {
            $aNodo = [
                'id' => $oModulo->id,
                'parent' => $oModulo->IdPadre,
                'text' => "{$oModulo->id} - {$oModulo->Nombre}"
            ];

            switch ($oModulo->Tipo) {
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

            $aArbol[] = $aNodo;
        }

        return json_encode($aArbol);
    }
}
