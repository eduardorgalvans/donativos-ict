<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

use Spatie\Activitylog\Traits\LogsActivity;

use App\Models\DatosGeneralesPersona;

class Trabajador extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $connection = 'DbRecursosHumanos';

    protected $table = 'TblTrabajadores';

    protected $fillable = ['TblDGP_id', 'NumTrabajador', 'FechaIngreso', 'NSS', 'TblC_id', 'TblS_id',
        'TblTC_id', 'TblTJ_id', 'TblTPP_id', 'TblTS_id', 'CausaBajaInterna', 'CausaBajaSS', 'NumHijos',
        'TblTB_id', 'FechaBaja', 'Estatus'
    ];

    protected static $logAttributes = ['TblDGP_id', 'NumTrabajador', 'FechaIngreso', 'NSS', 'TblC_id',
        'TblS_id', 'TblTC_id', 'TblTJ_id', 'TblTPP_id', 'TblTS_id', 'CausaBajaInterna', 'CausaBajaSS',
        'NumHijos', 'TblTB_id', 'FechaBaja', 'Estatus', 'TblU_id'
    ];

    protected $casts = [
        'FechaIngreso' => 'datetime',
        'FechaBaja' => 'datetime'
    ];

    public function persona()
    {
        return $this->hasOne(DatosGeneralesPersona::class, 'IdPersona', 'TblDGP_id');
    }

    public function getFormFechaIngresoAttribute()
    {
        return $this->FechaIngreso ? $this->FechaIngreso->format('d/m/Y') : '';
    }

    public function getFormFechaBajaAttribute()
    {
        if (!$this->FechaBaja) {
            return '';
        } elseif ($this->FechaBaja->year == -1) {
            return '';
        } else {
            return $this->FechaBaja ? $this->FechaBaja->format('d/m/Y') : '';
        }
    }

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
