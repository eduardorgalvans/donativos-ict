<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

use Spatie\Activitylog\Traits\LogsActivity;

class Direccion extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $connection = 'DbInscripciones';

    protected $table = 'TblDireccion';

    protected $primaryKey = 'iddireccion';

    protected $fillable = ['idpersona', 'tipo', 'domcalle', 'domnum', 'domentrecalle1',
        'domentrecalle2', 'codigopostal', 'colonia', 'domciudad', 'domestado', 'dompais',
        'TblP_id', 'TblE_id', 'TblM_id', 'TblL_id'];

    protected static $logAttributes = ['idpersona', 'tipo', 'domcalle', 'domnum', 'domentrecalle1',
        'domentrecalle2', 'codigopostal', 'colonia', 'domciudad', 'domestado', 'dompais',
        'TblP_id', 'TblE_id', 'TblM_id', 'TblL_id', 'TblU_id'];

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
