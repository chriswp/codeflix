<?php

namespace App\Models;

use App\Models\Traits\GenerateUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genero extends Model
{
    use SoftDeletes,GenerateUuid;

    protected $fillable = ['nome','ativo'];
    protected $dates = ['deleted_at'];
    protected $casts = ['id' => 'string'];

}
