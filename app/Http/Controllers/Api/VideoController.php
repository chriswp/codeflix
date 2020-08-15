<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use App\Rules\GeneroPossuiCategoriasRule;
use Illuminate\Http\Request;

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
            'categorias_id' => 'required|array|exists:categorias,id,deleted_at,NULL',
            'generos_id' => ['required', 'array', 'exists:generos,id,deleted_at,NULL'],
            'video_arquivo' => 'required'
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
        $this->addRuleSeGeneroPossuiCategorias($request);
        $validationData = $this->validate($request, $this->rulesStore());
        $video = $this->model()::create($validationData);
        $video->refresh();

        return $video;
    }

    public function update(Request $request, $id)
    {
        $video = $this->firstOrFail($id);
        $this->addRuleSeGeneroPossuiCategorias($request);
        $validationData = $this->validate($request, $this->rulesUpdate());
        $video->update($validationData);

        return $video;
    }

    protected function addRuleSeGeneroPossuiCategorias(Request $request)
    {
        $categoriasId = $request->get('categorias_id');
        $categoriasId = is_array($categoriasId) ? $categoriasId : [];
        $this->rules['generos_id'][] = new GeneroPossuiCategoriasRule($categoriasId);
    }

}
