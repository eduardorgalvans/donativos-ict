<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Causa;
use Illuminate\Http\Request;

use Auth, DB, Str, Exception;

class CausaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $causas = Causa::all(['id_causa', 'n_causa', 'minimo', 'maximo', 'activo']);

        return view(
            'admin.causas.index', #admin/causas/index
            compact(
                'causas'
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

         # admin/causas/create
         return view('admin.causas.create');
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

            if( floatval($datos['minimo']) < 0 || floatval($datos['maximo']) < 0 ){
                throw new Exception("Los valores mínimo y máximo no deben ser negativos");
            }

            if( floatval($datos['minimo']) > floatval($datos['maximo']) ){
                throw new Exception("El valor mínimo no debe ser mayor al máximo");
            }

            if( floatval($datos['minimo']) == floatval($datos['maximo']) ){
                throw new Exception("El valor mínimo no debe ser igual al máximo");
            }

            if( floatval($datos['minimo']) == floatval($datos['maximo']) ){
                throw new Exception("El valor mínimo no debe ser igual al máximo");
            }

            $datos['activo'] = isset($datos['activo']) ? 1 : 0; 
            $datos['que']    = 'A'; 
            $datos['quien']  = Auth::id(); 
            $datos['cuando'] = date('Y-m-d hh:mm:ss'); 


            Causa::create($datos);

            DB::commit();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Datos guardados correctamente.'
            ]);

            return redirect()->route('admin.causas.index');
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
        $causa = Causa::find($id);

        $causa->minimo = intval($causa->minimo);
        $causa->maximo = intval($causa->maximo);

        # admin/causas/show
        return view('admin.causas.show')
            ->with(compact('causa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $causa = Causa::find($id);

        $causa->minimo = intval($causa->minimo);
        $causa->maximo = intval($causa->maximo);

        # admin/causas/show
        return view('admin.causas.edit')
            ->with(compact('causa'));
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
            DB::beginTransaction();

            $datos = $request->all();
           
            if( floatval($datos['minimo']) < 0 || floatval($datos['maximo']) < 0 ){
                throw new Exception("Los valores mínimo y máximo no deben ser negativos");
            }

            if( floatval($datos['minimo']) > floatval($datos['maximo']) ){
                throw new Exception("El valor mínimo no debe ser mayor al máximo");
            }

            if( floatval($datos['minimo']) == floatval($datos['maximo']) ){
                throw new Exception("El valor mínimo no debe ser igual al máximo");
            }

            if( floatval($datos['minimo']) == floatval($datos['maximo']) ){
                throw new Exception("El valor mínimo no debe ser igual al máximo");
            }

            
            $datos['activo'] = isset($datos['activo']) ? 1 : 0; 
            $datos['que']    = 'C'; 
            $datos['quien']  = Auth::id(); 
            $datos['cuando'] = date('Y-m-d hh:mm:ss'); 

            $causa = Causa::find($id);
            $causa->fill($datos);
            $causa->save();

            DB::commit();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Datos guardados correctamente.'
            ]);

            return redirect()->route('admin.causas.index');
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
        $causa = Causa::find($id);
        $causa->delete();

        return redirect()->route('admin.causas.index');
    }

    /**
     * Obtiene las causas registradas.
     *
     *@param  \Illuminate\Http\Request  $request
     *
     */
    public function getCausasAPI(Request  $request)
    {
       try {
        $causas = Causa::all(['id_causa', 'n_causa', 'minimo', 'maximo', 'activo'])->where('activo', '=', '1');

        return response()->json($causas, 200);
        
    } catch (\Throwable $th) {
           return response()->json(['error' => $th->getMessage()], 500);
        
       }
    }
}
