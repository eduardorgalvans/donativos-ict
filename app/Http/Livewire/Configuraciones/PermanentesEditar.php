<?php

namespace App\Http\Livewire\Configuraciones;

use Livewire\Component;
use \App\Models\Admin\{
    VariablesPermanentes
};

use DB, Libreria, Session, Str;

class PermanentesEditar extends Component
{
    public $oRegistro;
    public $isVisible = TRUE;
    public $Variable, $Valor, $Tipo;
    public $aType = [
        'int' => 'Entero',
        'date' => 'Fecha',
        'json' => 'JSON',
        'str' => 'Cadena',
    ];
    protected $rules = [
        'oRegistro.Variable' => 'required',
        'oRegistro.Valor' => 'required',
        'oRegistro.Tipo' => 'required',
    ];

    public function mount( VariablesPermanentes $oRegistro )
    {
        \Log::info( $oRegistro );
        $this->oRegistro = $oRegistro;
    }
    
    public function render()
    {
        return view('livewire.configuraciones.permanentes-editar'); # livewire/configuraciones/permanentes-editar
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save()
    {
        #
        try {
            # Comienza la transacción
            DB::connection( 'mysql' )->beginTransaction();
            // Creamos el registro
            $this->oRegistro->save();
            # Compromete las consultas
            DB::connection( 'mysql' )->commit();
        } catch( \Exception $e ) {
            # Rollback y luego redirigir volver al formulario con errores
            DB::connection( 'mysql' )->rollBack();
            \Log::debug( 'PermanentesEditar@save', [ $e ] );
            # 
            Session::flash( 'danger', Str::limit( $e, 150, '...' ) );
        }
        # cerramos el modal
        $this->dispatchBrowserEvent( 'close-modal-permanentes-edit-'.$this->oRegistro->id );
        # solicitamos renderizar nuevamente el componente tabla y mostramos un alert
        $this->emit( 'createTablePermanentes' );
        $this->emit( 'alert-success', [ 'title'=>'Correcto.', 'message'=>'La variable se actualizo satisfactoriamente...', ] );
        # emitir notificacion
        Libreria::voidPutNotificacionLW( 'render', 'createTablePermanentes' );
    }

}
