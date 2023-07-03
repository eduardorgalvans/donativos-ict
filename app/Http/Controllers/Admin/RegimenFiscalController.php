<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\RegimenFiscal;
use Illuminate\Http\Request;

use Auth, DB, Str, Exception;

class RegimenFiscalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $regimenes = RegimenFiscal::all(['id', 'n_regimen']);

        return view(
            'admin.regimenes-fiscales.index', #admin/regimenes-fiscales/index
            compact(
                'regimenes'
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
        # admin/regimenes-fiscales/create
        return view('admin.regimenes-fiscales.create');
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


            RegimenFiscal::create($datos);

            DB::commit();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Datos guardados correctamente.'
            ]);

            return redirect()->route('admin.regimenes-fiscales.index');
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
        $regimen = RegimenFiscal::find($id);

        # admin/regimenes-fiscales/show
        return view('admin.regimenes-fiscales.show')
            ->with(compact('regimen'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $regimen = RegimenFiscal::find($id);

        # admin/regimenes-fiscales/edit
        return view('admin.regimenes-fiscales.edit')
            ->with(compact('regimen'));
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

            $regimen = RegimenFiscal::find($id);
            $regimen->fill($datos);
            $regimen->save();

            DB::commit();

            session()->flash('mensaje-estatus', [
                'css' => 'green',
                'mensaje' => 'Datos guardados correctamente.'
            ]);

            return redirect()->route('admin.regimenes-fiscales.index');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $regimen = RegimenFiscal::find($id);
        $regimen->delete();

        return redirect()->route('admin.regimenes-fiscales.index');
    }

 
    /**
     * Obtiene los regimenes registradas.
     *
     *@param  \Illuminate\Http\Request  $request
     *
     */
    public function getRegimenesAPI(Request  $request)
    {
       try {
        $regimen = RegimenFiscal::all(['id', 'n_regimen']);

        return response()->json($regimen, 200);
        
    } catch (\Throwable $th) {
           return response()->json(['error' => $th->getMessage()], 500);
        
       }
    }

}
