<?php

namespace App\Http\Livewire\Configuraciones;

use Livewire\Component;
use \App\Models\Admin\{
    VariablesPermanentes
};

class PermanentesTabla extends Component
{
    public $sort = 'id';
    public $search = '';
    public $direction = 'desc';

    protected $listeners = [ 'updateTablePermanentes', 'createTablePermanentes' ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function render()
    {
        #
        $oRegistros = VariablesPermanentes::select(
            'TblVariablesPermanentes.*'
        )
        ->where(function ( $oQuery ) {
            if ( $this->search != '' ) {
                $oQuery->where( 'id', 'LIKE', '%'.$this->search.'%' );
                $oQuery->orWhere( 'Variable', 'LIKE', '%'.$this->search.'%' );
                $oQuery->orWhere( 'Valor', 'LIKE', '%'.$this->search.'%' );
            }
        })
        ->orderBy( $this->sort, $this->direction )
        ->get();
        # cargamos la vista
        return view(
            'livewire.configuraciones.permanentes-tabla', # livewire/configuraciones/permanentes-tabla
            compact(
                'oRegistros'
            )
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function order( string $field = null )
    {
        if ( $this->sort == $field ) {
            $this->direction = ( $this->direction == 'desc' ) ? 'asc' : 'desc';
        }else{
            $this->sort = $field;
            $this->direction = 'desc';
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function updateTablePermanentes( String $search = '' )
    {
        $this->search = $search;
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function createTablePermanentes( String $search = '' )
    {
        $this->render();
    }
    
}
