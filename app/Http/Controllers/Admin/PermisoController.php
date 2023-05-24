<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\RH\Puesto;
use App\Models\Admin\{
    Modulo,
    Perfil
};

use Exception;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aPuestos = $this->obtenerArbolPuestos();
        $aModulos = $this->obtenerArbolModulos();
        $aPerfiles = Perfil::pluck('Nombre', 'id')
            ->toArray();

        return view(
            'admin.permisos.index', # admin/permisos/index
            compact(
                'aPuestos', 
                'aModulos', 
                'aPerfiles'
            )
        )
        ->with( 'sSelecto', "2,6" );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Puesto  $oPuesto
     * @return \Illuminate\Http\Response
     */
    public function show(Puesto $oPuesto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Puesto  $oPuesto
     * @return \Illuminate\Http\Response
     */
    public function edit(Puesto $oPuesto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Puesto  $oPuesto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Puesto $oPuesto)
    {
        try {
            $oPuesto->Perfiles = $request->perfiles;
            $oPuesto->save();

            return $this->obtenerDetallesPuesto($oPuesto);
        } catch (Exception $e) {
            return json_encode([
                'mensaje' => str_limit($e->getMessage(), 100)
            ]);
        }
    }

    public function obtenerDetallesPuesto(Puesto $oPuesto)
    {
        $aPerfiles = explode(',', $oPuesto->Perfiles);

        $oPerfiles = Perfil::whereIn('id', $aPerfiles)
            ->get();

        $aPermisos = [];
        foreach ($oPerfiles as $oPerfil) {
            $aPermisos = array_merge($aPermisos, explode(',', $oPerfil->Permisos));
        }

        $aRespuesta = [
            'perfiles' => $oPuesto->Perfiles ?? '',
            'permisos' => implode(',', array_unique($aPermisos))
        ];

        return json_encode($aRespuesta);
    }

    private function obtenerArbolPuestos()
    {
        $oPuestos = Puesto::all();

        $aArbol = [];

        $aArbol[] = [
            'id' => 0,
            'parent' => '#',
            'text' => 'Puestos',
            'icon' => 'fa fa-circle',
            'state' => [
                'opened' => true,
                'disabled' => true
            ]
        ];

        foreach ($oPuestos as $oPuesto) {
            $aNodo = [
                'id' => $oPuesto->id,
                'parent' => $oPuesto->IdPadre,
                'text' => $oPuesto->Nombre,
                'type' => ($oPuesto->IdPadre == 0) ? 'default' : 'puesto',
                'state' => [
                    'disabled' => ($oPuesto->IdPadre == 0)
                ]
            ];

            $aArbol[] = $aNodo;
        }

        return json_encode($aArbol);
    }

    private function obtenerArbolModulos()
    {
        $oModulos = Modulo::all();

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
                'text' => "{$oModulo->id} - {$oModulo->Nombre}",
                'state' =>[
                    'disabled' => true
                ]
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
