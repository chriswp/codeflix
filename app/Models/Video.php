<?php

namespace App\Models;

use App\Models\Traits\GenerateUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes, GenerateUuid;

    const CLASSIFICACOES = ['L','10','12','14','16','18'];

    public $incrementing = false;
    protected $casts = [
        'id' => 'string',
        'ano_lancamento' => 'integer',
        'liberado' => 'boolean',
        'duracao' => 'integer'
    ];
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'titulo',
        'descricao',
        'ano_lancamento',
        'liberado',
        'classificacao',
        'duracao'
    ];

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class);
    }

    public function generos()
    {
        return $this->belongsToMany(Genero::class);
    }
}
