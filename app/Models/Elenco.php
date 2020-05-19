<?php

namespace App\Models;

use App\Models\Traits\GenerateUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Elenco extends Model
{
    use SoftDeletes, GenerateUuid;

    const DIRETOR = 1;
    const ATOR = 2;

    public $incrementing = false;
    protected $casts = ['id' => 'string','tipo' => 'integer'];
    protected $fillable = ['nome','tipo'];
    protected $dates = ['deleted_at'];
}
