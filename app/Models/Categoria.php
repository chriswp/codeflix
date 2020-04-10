<?php

namespace App\Models;

use App\Models\Traits\GenerateUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes, GenerateUuid;

    protected $fillable = ['nome','descricao','ativo'];
    protected $dates = ['deleted_at'];
    protected $casts = ['id' => 'string'];
}
