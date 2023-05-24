<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'TblDGP_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
    * The accessors to append to the model's array form.
    *
    * @var array
    */
    protected $appends = [ 'acceso' ];

    /**
     * actualizamos el campo usuario cada que se actualiza o crea
     *
     * @var boolean
     */
    public function isOnline() {
        parent::boot();
        # regresmos si existe uan variable de cache
        return Cache::has('user-online-' . $this->id );
    }

    public function persona()
    {
        return $this->hasOne(DatosGeneralesPersona::class, 'IdPersona', 'TblDGP_id');
    }

    public function trabajador()
    {
        return $this->hasOne(RH\Trabajador::class, 'TblDGP_id', 'TblDGP_id');
    }

     /**
     * Get the administrator flag for the post.
     *
     * @return bool
     */
    public function getAccesoAttribute()
    {
        #
        $oPerfiles = \App\Models\RH\HistorialPuestos::select(
                'TblP.Perfiles',
            )
            ->join('DbIntranet.TblPuestos AS TblP', function( $oJoin ){
                $oJoin->on( 'TblHistorialPuestos.TblPP_id', '=', 'TblP.id' )
                    ->whereNull( 'TblP.deleted_at' );
            })
            ->where( 'TblHistorialPuestos.TblDGP_id', $this->attributes['TblDGP_id'] )
            ->where( 'TblHistorialPuestos.TblPF_id', 0 )
            ->get();
        #
        $aPermisos = [];
        foreach ($oPerfiles as $oPerfil) {
            $aPerfiles = explode(',', $oPerfil->Perfiles);

            $oPermisos = \App\Models\Admin\Perfil::select(
                    \DB::raw( 'GROUP_CONCAT(Permisos) AS Permisos' )
                )
                ->whereIn('id', $aPerfiles)
                ->first();

            $aPermisos[] = $oPermisos->Permisos;
        }
        #
        $aPermisos = array_unique(explode(',', implode(',', $aPermisos)));
        #
        $aPermisos = array_map(function($id){ return abs($id); }, $aPermisos);
        #
        return ','.implode(',', $aPermisos).',';
    }
}
