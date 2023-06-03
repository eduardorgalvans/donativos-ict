<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Causa extends Model
{
    use  HasFactory;


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
    protected $table = "dss_cat_causas";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_causa', 'n_causa', 'minimo', 'maximo', 'activo', 'que', 'quien', 'cuando'];

    protected $primaryKey = 'id_causa';
    public $timestamps = false;
}
