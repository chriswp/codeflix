<?php

namespace App\Models;

use App\Models\Traits\GenerateUuid;
use App\Models\Traits\UploadFiles;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Video extends Model
{
    use SoftDeletes, GenerateUuid, UploadFiles;

    const CLASSIFICACOES = ['L', '10', '12', '14', '16', '18'];

    public $incrementing = false;
    protected $keyType = 'string';
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

    public static function create(array $attributes = [])
    {
        $files = self::extractFiles($attributes);
        try {
            DB::beginTransaction();
            /** @var Video $obj */
            $obj = static::query()->create($attributes);
            static::handleRelations($obj,$attributes);
            $obj->uploadFiles($files);
            DB::commit();
            return $obj;
        } catch (Exception $e) {
            if (isset($obj)) {
                //TODO excluir os arquivos de upload
            }
            DB::rollBack();
            throw $e;
        }
    }

    public function update(array $attributes = [], array $options = [])
    {
        try {
            DB::beginTransaction();
            $saved = parent::update($attributes, $options);
            static::handleRelations($this,$attributes);
            if ($saved) {
                //TODO realizar upload
                //TODO excluir os arquivos antigos
            }
            //TODO implementar upload
            DB::commit();
            return $saved;
        } catch (Exception $e) {
            //TODO excluir os arquivos de upload
            DB::rollBack();
            throw $e;
        }
    }

    public static function handleRelations(Video $video, array $attributes)
    {
        if (isset($attributes['categorias_id'])) {
            $video->categorias()->sync($attributes['categorias_id']);
        }

        if (isset($attributes['generos_id'])) {
            $video->generos()->sync($attributes['generos_id']);
        }
    }


    public function categorias()
    {
        return $this->belongsToMany(Categoria::class)->withTrashed();
    }

    public function generos()
    {
        return $this->belongsToMany(Genero::class)->withTrashed();
    }

    protected function uploadDir()
    {
        return $this->id;
    }


}
