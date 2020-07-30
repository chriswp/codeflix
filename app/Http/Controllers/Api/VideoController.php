<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoController extends BasicCrudController
{
    /** @var array */
    private $rules;

    public function __construct()
    {
        $this->rules = [
            'titulo' => 'required|max:255',
            'descricao' => 'required',
            'ano_lancamento' => 'required|integer|date_format:Y',
            'liberado' => 'boolean',
            'classificacao' => 'required|in:' . implode(',', Video::CLASSIFICACOES),
            'duracao' => 'required|integer',
            'categorias_id' => 'required|array|exists:categorias,id',
            'generos_id' => 'required|array|exists:generos,id',
        ];
    }

    protected function model()
    {
        return Video::class;
    }

    protected function rulesStore()
    {
        return $this->rules;
    }

    protected function rulesUpdate()
    {
        return $this->rules;
    }

    public function store(Request $request)
    {
        $self = $this;
        $validationData = $this->validate($request, $this->rulesStore());
        $video = DB::transaction(function () use ($validationData, $request, $self) {
            $video = $this->model()::create($validationData);
            $self->handleRelations($video, $request);
            return $video;
        });
        $video->refresh();

        return $video;
    }

    public function update(Request $request, $id)
    {
        $self = $this;
        $video = $this->firstOrFail($id);
        $validationData = $this->validate($request, $this->rulesUpdate());
        $video = DB::transaction(function () use ($validationData, $request, $self, $video) {
            $video->update($validationData);
            $self->handleRelations($video, $request);
            return $video;
        });

        return $video;
    }

    protected function handleRelations($video, Request $request)
    {
        $video->categorias()->sync($request->get('categorias_id'));
        $video->generos()->sync($request->get('generos_id'));
    }
}
