<?php

namespace App\Http\Livewire\Configuraciones;

use Livewire\Component;
use \App\Models\Admin\{
    VariablesPermanentes
};
use PhpParser\Node\Expr\Cast\String_;

class Permanentes extends Component
{
    public $search = "";

    public function render()
    {
        # cargamos la vista
        return view(
            'livewire.configuraciones.permanentes'#, # livewire/configuraciones/permanentes
            /*
            compact(
                'oRegistros'
            )
            */
        );
    }

    public function emitTable()
    {
        $this->emit( 'updateTablePermanentes',  $this->search );
    }
}
