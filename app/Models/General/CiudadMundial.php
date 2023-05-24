<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CiudadMundial extends Model
{
    use HasFactory;

    protected $connection = 'DbInscripciones';

    protected $table = 'TblAdmCiudad';
}
