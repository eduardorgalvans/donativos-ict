<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

use Spatie\Activitylog\Traits\LogsActivity;

class CorreoConfiguracion extends Model
{
    use SoftDeletes, LogsActivity;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'DbIntranet';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "TblCorreoConfiguracion";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'Activo', 'Conteo', 'driver', 'host', 'port', 'encryption', 'user_name', 'password', 'sender_name', 'sender_email', 'Estatus', 'TblU_id', ];

    /**
     * The attributes .
     *
     * @var array
     */
    protected static $logAttributes = ['id', 'Activo', 'Conteo', 'driver', 'host', 'port', 'encryption', 'user_name', 'password', 'sender_name', 'sender_email', 'Estatus', 'TblU_id', ];

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
