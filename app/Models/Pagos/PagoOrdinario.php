<?php

namespace App\Models\Pagos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

use Spatie\Activitylog\Traits\LogsActivity;

class PagoOrdinario extends Model
{
    use HasFactory,SoftDeletes, LogsActivity;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'DbInscripciones';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "TblPagosOrdinarios";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'idpagoordinario','idpersona','clavetransferencia','nivel','numcontrol','fechadepago','folio',
    						 'numcuenta','TblC_id','mediodepago','TblTPSP_id','tj','formadepago','Cheque','importebruto',
    						 'nc' ,'importedescuentorecargo','importeneto' ,'referencia1' ,'referencia2' ,'referencia3','fechadevencimiento',
    						 'fechavigencia','clavesucursal','hora' ,'estatuspago','codigorechazo','tipomovimiento','periodoacademico',
    						 'claveconcepto','clavesubconcepto','TblPSI_id','estatus','controlbanco','folioficha','ahora',
    						 'fechadeprorroga','foliobancario','grado','grupo','tipodocumento','descuento','tipoderegistro','TblU_id',];
    /**
     * The attributes .
     *
     * @var array
     */
    protected static $logAttributes = [  'idpagoordinario','idpersona','clavetransferencia','nivel','numcontrol','fechadepago','folio',
    						 'numcuenta','TblC_id','mediodepago','TblTPSP_id','tj','formadepago','Cheque','importebruto',
    						 'nc' ,'importedescuentorecargo','importeneto' ,'referencia1' ,'referencia2' ,'referencia3','fechadevencimiento',
    						 'fechavigencia','clavesucursal','hora' ,'estatuspago','codigorechazo','tipomovimiento','periodoacademico',
    						 'claveconcepto','clavesubconcepto','TblPSI_id','estatus','controlbanco','folioficha','ahora',
    						 'fechadeprorroga','foliobancario','grado','grupo','tipodocumento','descuento','tipoderegistro','TblU_id',];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function boot() {
        parent::boot();
        // primero le decimos al modelo qué hacer en un evento de creación
        static::creating(function($post) {
            $post->TblU_id = \Auth::id();
        });
        // luego le decimos al modelo qué hacer en un evento de actualización
        static::updating(function($post) {
            $post->TblU_id = \Auth::id();
        });
        // luego le decimos al modelo qué hacer en un evento de borrado
        static::deleting(function($post) {
            #\Log::info($post);
            $post->TblU_id = \Auth::id();
        });
    }    
}
