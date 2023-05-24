<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

class Seccion extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'DbInscripciones';

    protected $table = 'TblRHSecciones';
}
