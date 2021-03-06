<?php

namespace App\Models;

use App\Models\Traits\GenerateUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes, GenerateUuid;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $casts = ['id' => 'string'];
    protected $fillable = ['nome', 'descricao', 'ativo'];
    protected $dates = ['deleted_at'];

    public function generos()
    {
        return $this->belongsToMany(Genero::class);
    }
}
