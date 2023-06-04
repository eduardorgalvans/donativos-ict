<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Comunidad;
use Illuminate\Http\Request;

use Auth, DB, Str, Exception;

class ComunidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $comunidades = Comunidad::all(['id_comunidad', 'n_comunidad']);

        return view(
            'admin.comunidades.index', #admin/comunidad/index
            compact(
                'comunidades'
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
         # admin/comunidades/create
         return view('admin.comunidades.create');
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

            $datos['que']    = 'A'; 
            $datos['quien']  = Auth::id(); 
            $datos['cuando'] = date('Y-m-d hh:mm:ss'); 


            Comunidad::create($datos);

            DB::commit();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Datos guardados correctamente.'
            ]);

            return redirect()->route('admin.comunidades.index');
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
          $comunidad = Comunidad::find($id);

          # admin/comunidades/show
          return view('admin.comunidades.show')
              ->with(compact('comunidad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $int
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comunidad = Comunidad::find($id);

        # admin/comunidades/show
        return view('admin.comunidades.edit')
            ->with(compact('comunidad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $datos = $request->all();
           
            $datos['que']    = 'C'; 
            $datos['quien']  = Auth::id(); 
            $datos['cuando'] = date('Y-m-d hh:mm:ss'); 

            $comunidad = Comunidad::find($id);
            $comunidad->fill($datos);
            $comunidad->save();

            DB::commit();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Datos guardados correctamente.'
            ]);

            return redirect()->route('admin.comunidades.index');
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
         $comunidad = Comunidad::find($id);
         $comunidad->delete();

         return redirect()->route('admin.comunidades.index');
    }
}
