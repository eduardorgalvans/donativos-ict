<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\PerfilRequest;

use App\Models\Admin\{
    Perfil,
    Modulo
};

use DB, Exception, Str;

class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $oPerfiles = Perfil::all();

        return view(
            'admin.perfiles.index', # admin/perfiles/index
            compact(
                'oPerfiles'
            )
        )
        ->with( 'sSelecto', "2,5" );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        # admin/perfiles/create
        return view('admin.perfiles.create')
            ->with('aArbol', $this->obtenerArbol());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Admin\PerfilRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PerfilRequest $request)
    {
        return $this->guardarDatos($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Perfil $oPerfil)
    {
        # admin/perfiles/show
        return view('admin.perfiles.show')
            ->with(compact('oPerfil'))
            ->with('aArbol', $this->obtenerArbol(true));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Perfil $oPerfil)
    {
        # admin/perfiles/edit
        return view('admin.perfiles.edit')
            ->with(compact('oPerfil'))
            ->with('aArbol', $this->obtenerArbol());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Admin\PerfilRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PerfilRequest $request, $id)
    {
        return $this->guardarDatos($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Perfil $oPerfil)
    {
        try {
            $oPerfil->delete();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Perfil eliminado correctamente.'
            ]);

            return redirect()->route('admin.perfiles.index');
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

            Perfil::updateOrCreate(['id'=>$id], $datos);

            DB::commit();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Datos guardados correctamente.'
            ]);

            return redirect()->route('admin.perfiles.index');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([Str::limit($e, 250, '...')]);
        }
    }

    public function obtenerArbol($deshabilitarNodos = false)
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
                    'disabled' => $deshabilitarNodos
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
