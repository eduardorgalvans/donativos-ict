<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Donador;
use Illuminate\Http\Request;

use Auth, DB, Str, Exception;

class DonadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $donadores = Donador::all();

        return view(
            'admin.donadores.index', #admin/donadores/index
            compact(
                'donadores'
            )
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDonador(Request $request)
    {
        try {
            DB::beginTransaction();

            $datos = $request->all();

            Donador::create($datos);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'La escuela se pondrá en contacto contigo para revisar la donación que deseas realizar'
            ], 201);
        } catch (Exception $th) {
            DB::rollBack();

            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
            ], 500);
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
        $regimen = Donador::find($id);
        $regimen->delete();

        return redirect()->route('admin.donadores.index');
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
            $regimen = Donador::all(['id', 'n_regimen']);

            return response()->json($regimen, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
