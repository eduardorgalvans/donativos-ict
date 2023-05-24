<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

use Spatie\Activitylog\Traits\LogsActivity;

class HistorialPuestos extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $connection = 'DbRecursosHumanos';

    protected $table = 'TblHistorialPuestos';

    protected $fillable = ['TblDGP_id', 'TblD_id', 'TblP_id', 'TblPS_id', 'TblPP_id', 'TblPI_id',
        'TblPF_id'];

    protected static $logAttributes = ['TblDGP_id', 'TblD_id', 'TblP_id', 'TblPS_id', 'TblPP_id',
        'TblPI_id', 'TblPF_id', 'TblU_id'];

    public static function boot()
    {
        parent::boot();
        # primero le decimos al modelo qué hacer en un evento de creación
        static::creating(function($oRegistro) {
            $oRegistro->TblU_id = \Auth::id();
        });
        # luego le decimos al modelo qué hacer en un evento de actualización
        static::updating(function($oRegistro) {
            $oRegistro->TblU_id = \Auth::id();
        });
        # luego le decimos al modelo qué hacer en un evento de borrado
        static::deleting(function($oRegistro) {
            $oRegistro->TblU_id = \Auth::id();
        });
    }
}
