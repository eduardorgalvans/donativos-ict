<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\{
    UsuarioRequest,
    CambiarContrasenaRequest
};

use App\Models\User;
use App\Models\RH\Trabajador;

use Auth, DB, Str, Hash, Exception;

class UsuarioController extends Controller
{
    private $aPersonas;

    public function __construct()
    {
        $this->aPersonas = Trabajador::join(
                'DbInscripciones.TblDatosGeneralesPersonas AS TblDGP', 'TblTrabajadores.TblDGP_id', '=', 'TblDGP.IdPersona'
            )
            ->select(
                'TblDGP.IdPersona',
                DB::raw("CONCAT_WS(' ', TRIM(TblDGP.Nombres), TRIM(TblDGP.ApPaterno), TRIM(TblDGP.ApMaterno)) AS NombreFull")
            )
            ->where('TblTrabajadores.Estatus', 1)
            ->orderByRaw("CONCAT_WS(' ', TRIM(TblDGP.Nombres), TRIM(TblDGP.ApPaterno), TRIM(TblDGP.ApMaterno))")
            ->pluck('NombreFull', 'IdPersona')
            ->toArray();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $oUsuarios = User::with('persona')->get();

        return view(
            'admin.usuarios.index', # admin/usuarios/index
            compact(
                'oUsuarios'
            )
        )
        ->with( 'sSelecto', "2,3" );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        # admin/usuarios/create
        return view('admin.usuarios.create')
            ->with('aPersonas', $this->aPersonas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Admin\UsuarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsuarioRequest $request)
    {
        try {
            DB::beginTransaction();

            $datos = $request->all();
            $datos['password'] = Hash::make($datos['password']);

            User::create($datos);

            DB::commit();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Datos guardados correctamente.'
            ]);

            return redirect()->route('admin.usuarios.index');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([Str::limit($e, 250, '...')]);
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
        $oUsuario = User::find($id);

        # admin/usuarios/show
        return view('admin.usuarios.show')
            ->with(compact('oUsuario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $oUsuario = User::find($id);

        # admin/usuarios/edit
        return view('admin.usuarios.edit')
            ->with(compact('oUsuario'))
            ->with('aPersonas', $this->aPersonas);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Admin\UsuarioRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsuarioRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $datos = $request->all();
            $datos['password'] = Hash::make($datos['password']);

            $oUsuario = User::find($id);
            $oUsuario->fill($datos);
            $oUsuario->save();

            DB::commit();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Datos guardados correctamente.'
            ]);

            return redirect()->route('admin.usuarios.index');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([Str::limit($e, 250, '...')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $oUsuario = User::find($id);
        $oUsuario->delete();

        return redirect()->route('admin.usuarios.index');
    }

    public function cambiarContrasena($id)
    {
        $oUsuario = User::find($id);

        # admin/usuarios/cambiar-contrasena
        return view('admin.usuarios.cambiar-contrasena', compact('oUsuario'));
    }

    public function guardarCambioContrasena(CambiarContrasenaRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $oUsuario = User::find($id);
            $oUsuario->password = Hash::make($request->ContrasenaNueva);
            $oUsuario->save();

            DB::commit();

            Auth::logout();

            return redirect()->route('login');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([Str::limit($e, 250, '...')]);
        }
    }
}
