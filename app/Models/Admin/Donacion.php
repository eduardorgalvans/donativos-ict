<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donacion extends Model
{
    use HasFactory;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'appict';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "dss_donaciones";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'referencia_banco', 'id_causa', 'importe', 'fecha', 'nombre', 'apellido', 'email', 'tel', 'id_comunidad', 'deducible', 'tipo_persona', 'rfc', 'razon_social', 'id_regimen', 'cp_fiscal', 'que', 'quien', 'cuando'];
    public $timestamps = false;
    public $incrementing = true;
}
