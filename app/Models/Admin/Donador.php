<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Donador extends Model
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
    protected $table = "dss_donadores";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'nombre', 'apellido', 'email', 'tel', 'importe'];

    public $timestamps = false;
}
