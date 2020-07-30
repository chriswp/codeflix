<?php

namespace App\Models;

use App\Models\Traits\GenerateUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genero extends Model
{
    use SoftDeletes, GenerateUuid;

    public $incrementing = false;
    protected $casts = ['id' => 'string'];
    protected $fillable = ['nome','ativo'];
    protected $dates = ['deleted_at'];

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class);
    }

}
