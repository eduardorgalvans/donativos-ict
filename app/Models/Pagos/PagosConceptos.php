<?php

namespace App\Models\Pagos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

use App\Model\conta\ContaPagosNotasDeCredito;

class PagosConceptos extends Model
{
    use SoftDeletes;
    use LogsActivity;

    #protected $primaryKey = 'idpagoordinario';

    /**
     * El nombre de la conexión para el modelo.
     *
     * @var string
     */
    protected $connection = 'DbConta';
    /**
     * La tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = "TblPagosConceptos";
    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [ 'Concepto', 'Nivel', 'Estatus', 'TblU_id', ];

    /**
     * The attributes .
     *
     * @var array
     */
    protected static $logAttributes = [ 'Concepto', 'Nivel', 'Estatus', 'TblU_id', ];

    /**
     * Los atributos que deben ser mutados a las fechas.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function notasDeCredito()
    {
        return $this->hasMany(ContaPagosNotasDeCredito::class, 'TblPO_id');
    }

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
