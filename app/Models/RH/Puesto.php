<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

use Spatie\Activitylog\Traits\LogsActivity;

class Puesto extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $connection = 'DbIntranet';

    protected $table = 'TblPuestos';

    protected $fillable = ['IdPadre', 'Nombre', 'Perfiles', 'Permisos'];

    protected static $logAttributes = ['IdPadre', 'Nombre', 'Perfiles', 'Permisos', 'TblU_id'];

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
