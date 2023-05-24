<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

use Spatie\Activitylog\Traits\LogsActivity;

use App\Models\DatosGeneralesPersona;

class Personal extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $connection = 'DbInscripciones';

    protected $table = 'TblPersonal';

    protected $fillable = ['idpersona', 'idempleado', 'status', 'TblRHC_id', 'TblCatP_id', 'TblDGP_id',
        'TblRHS_id', 'beneficiariosustituto', 'fechadeingreso', 'fechadebaja', 'fechavencecontrato',
        'salario', 'salariointegradofijo', 'salariointegradovariable', 'salariointegrado', 'imss',
        'fechaingresoimss', 'fechabajaimss', 'causabajainterna', 'causabajaimss', 'estatus',
        'fechacaptura', 'categoria', 'email', 'departamento', 'puesto', 'rfc', 'observaciones',
        'cuestionario', 'TblRHTC_id', 'TblRHTJ_id', 'TblRHTPP_id', 'TblRHTS_id', 'TblRHTB_id',
        'metascorto', 'metasmediano', 'metaslargo', 'numhijos'
    ];

    protected static $logAttributes = ['idpersona', 'idempleado', 'status', 'TblRHC_id', 'TblCatP_id',
        'TblDGP_id', 'TblRHS_id', 'beneficiariosustituto', 'fechadeingreso', 'fechadebaja',
        'fechavencecontrato', 'salario', 'salariointegradofijo', 'salariointegradovariable',
        'salariointegrado', 'imss', 'fechaingresoimss', 'fechabajaimss', 'causabajainterna',
        'causabajaimss', 'estatus', 'fechacaptura', 'categoria', 'email', 'departamento', 'puesto',
        'rfc', 'observaciones', 'cuestionario', 'TblRHTC_id', 'TblRHTJ_id', 'TblRHTPP_id', 'TblRHTS_id',
        'TblRHTB_id', 'metascorto', 'metasmediano', 'metaslargo', 'numhijos'
    ];

    protected $casts = [
        'fechadeingreso' => 'datetime',
        'fechadebaja' => 'datetime',
        'fechavencecontrato' => 'datetime',
        'fechaingresoimss' => 'datetime',
        'fechabajaimss' => 'datetime',
        'fechacaptura' => 'datetime'
    ];

    public function persona()
    {
        return $this->hasOne(DatosGeneralesPersona::class, 'IdPersona', 'idpersona');
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
