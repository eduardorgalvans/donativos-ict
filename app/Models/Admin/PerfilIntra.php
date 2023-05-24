<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};

class PerfilIntra extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'appict';

    protected $table = 'TblAdmPerfil';
}
