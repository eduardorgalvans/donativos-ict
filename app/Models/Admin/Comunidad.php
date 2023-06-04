<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comunidad extends Model
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
    protected $table = "dss_cat_comunidades";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_comunidad', 'n_comunidad', 'que', 'quien', 'cuando'];

    protected $primaryKey = 'id_comunidad';
    public $timestamps = false;
}
