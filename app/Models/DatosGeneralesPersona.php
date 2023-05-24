<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

use Spatie\Activitylog\Traits\LogsActivity;

class DatosGeneralesPersona extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $connection = 'DbInscripciones';

    protected $table = 'TblDatosGeneralesPersonas';

    protected $primaryKey = 'IdPersona';

    protected $fillable = ['Titulo', 'Nombres', 'ApPaterno', 'ApMaterno', 'Sexo', 'Vive', 'tinyVive', 'TblEC_id', 'IdLugarNac',
        'LugarNacMunicipio', 'LugarNacEstado', 'TblL_id', 'TblM_id', 'TblE_id', 'TblP_id', 'FechaNac', 'Nacionalidad',
        'rfc', 'Curp', 'DomCalle', 'DomNum', 'DomEntreCalle1', 'DomEntreCalle2', 'CodigoPostal', 'Colonia', 'IdLocalidad',
        'Email1', 'Email2', 'EsExalumno', 'Generacion', 'BecaSepIct', 'Religion', 'Ocupacion', 'LugarOcupacion',
        'NivelEstudios', 'EstudiosPostgrado', 'Puesto', 'EstudiosLicenciatura', 'Ingreso', 'EstadoCivil', 'DomPais',
        'DomEstado', 'DomCiudad', 'Porcentaje', 'FiguraPersona', 'trabaja', 'TblCNE_id', 'numhijos', 'diaslaborales',
        'horariotrabajo', 'notas', 'Estatus', 'TblCO_id', 'mesultimoempleo', 'annoultimoempleo', 'empresaultimoempleo'];

    protected static $logAttributes = ['Titulo', 'Nombres', 'ApPaterno', 'ApMaterno', 'Sexo', 'Vive', 'tinyVive', 'TblEC_id', 'IdLugarNac',
        'LugarNacMunicipio', 'LugarNacEstado', 'TblL_id', 'TblM_id', 'TblE_id', 'TblP_id', 'FechaNac', 'Nacionalidad',
        'rfc', 'Curp', 'DomCalle', 'DomNum', 'DomEntreCalle1', 'DomEntreCalle2', 'CodigoPostal', 'Colonia', 'IdLocalidad',
        'Email1', 'Email2', 'EsExalumno', 'Generacion', 'BecaSepIct', 'Religion', 'Ocupacion', 'LugarOcupacion', 'NivelEstudios',
        'EstudiosPostgrado', 'Puesto', 'EstudiosLicenciatura', 'Ingreso', 'EstadoCivil', 'DomPais', 'DomEstado', 'DomCiudad',
        'Porcentaje', 'FiguraPersona', 'trabaja', 'TblCNE_id', 'numhijos', 'diaslaborales', 'horariotrabajo', 'notas', 'Estatus',
        'TblCO_id', 'mesultimoempleo', 'annoultimoempleo', 'empresaultimoempleo', 'TblU_id'];

    public function getNombreCompletoAttribute()
    {
        return sprintf('%s %s %s', trim($this->Nombres), trim($this->ApPaterno), trim($this->ApMaterno));
    }

    public function getApellidosNombresAttribute()
    {
        return sprintf('%s %s %s', trim($this->ApPaterno), trim($this->ApMaterno), trim($this->Nombres));
    }

    public function getFechaNacimientoAttribute()
    {
        return $this->FechaNac ? date('d/m/Y', strtotime($this->FechaNac)) : '';
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
