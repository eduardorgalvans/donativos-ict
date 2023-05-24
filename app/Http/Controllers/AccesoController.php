<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Admin\Menu;

use Auth, Libreria;

class AccesoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aPermisos = Libreria::obtenerPermisosUsuario();

        $oEntradasMenu = Menu::whereIn('Permiso', $aPermisos)
            ->get();

        $aArbol = $this->obtenerArbolMenu($oEntradasMenu);

        return view(
            'admin.accesos.index', # admin/accesos/index
            compact(
                'aArbol'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $oUsuario = User::find(Auth::user()->id);
        $oUsuario->Accesos = $request->IDModulos;
        $oUsuario->save();

        return redirect()->route('dashboard.index');
    }

    private function obtenerArbolMenu($oEntradasMenu)
    {
        $aArbol = [];

        $aArbol[] = [
            'id' => 0,
            'parent' => '#',
            'text' => 'Menú',
            'icon' => 'fa fa-circle',
            'state' => [
                'opened' => true,
                'disabled' => true
            ],
            'tipo' => 'Carpeta',
            'modulo' => 0
        ];

        $aAccesos = explode(',', Auth::user()->Accesos);

        foreach ($oEntradasMenu as $oEntrada) {
            $aNodo = [
                'id' => $oEntrada->id,
                'parent' => $oEntrada->id_Padre,
                'text' => "{$oEntrada->id} - {$oEntrada->Nombre}",
                'icon' => $oEntrada->Icono,
                'state' => ['selected' => in_array($oEntrada->Permiso, $aAccesos)],
                'tipo' => ($oEntrada->Tipo == 1) ? 'Carpeta' : 'Módulo',
                'modulo' => $oEntrada->Permiso
            ];

            $aArbol[] = $aNodo;
        }

        return json_encode($aArbol);
    }
}
