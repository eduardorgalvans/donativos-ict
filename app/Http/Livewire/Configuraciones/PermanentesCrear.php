<?php

namespace App\Http\Livewire\Configuraciones;

use \App\Models\Admin\{
    VariablesPermanentes
};
use Livewire\Component;

use DB, Libreria, Session, Str;

class PermanentesCrear extends Component
{
    public $isVisible = TRUE;
    public $Variable, $Valor, $Tipo;
    public $aType = [
        'int' => 'Entero',
        'date' => 'Fecha',
        'json' => 'JSON',
        'str' => 'Cadena',
    ];
    protected $rules = [
        'Variable' => 'required',
        'Valor' => 'required',
        'Tipo' => 'required',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return view('livewire.configuraciones.permanentes-crear'); # livewire/configuraciones/permanentes-crear
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rules()
    {
        return $this->rules;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function updated( $fields )
    {
        $this->validateOnly( $fields );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save()
    {
        # recuperamos la data
        $bValidatedData = $this->validate();
        #
        try {
            # Comienza la transacción
            DB::connection( 'mysql' )->beginTransaction();
            if(! isset( $bValidatedData[ 'Estatus' ] ) ) $bValidatedData[ 'Estatus' ] = '0';
            // Creamos el registro
            $oRegistro = VariablesPermanentes::create( $bValidatedData );
            # Compromete las consultas
            DB::connection( 'mysql' )->commit();
        } catch( \Exception $e ) {
            # Rollback y luego redirigir volver al formulario con errores
            DB::connection( 'mysql' )->rollBack();
            \Log::debug( 'PermanentesCrear@save', [ $e ] );
            # 
            Session::flash( 'danger', Str::limit( $e, 150, '...' ) );
        }
        # limpiamos el contenido de los elementos
        $this->reset( [ 'Variable', 'Valor', 'Tipo' ] );
        # cerramos el modal
        $this->dispatchBrowserEvent( 'close-modal-permanentes' );
        # solicitamos renderizar nuevamente el componente tabla y mostramos un alert
        $this->emit( 'createTablePermanentes' );
        $this->emit( 'alert-success', [ 'title'=>'Correcto.', 'message'=>'La variable se creó satisfactoriamente...', ] );
        # emitir notificacion
        Libreria::voidPutNotificacionLW( 'render', 'createTablePermanentes' );
    }
}
