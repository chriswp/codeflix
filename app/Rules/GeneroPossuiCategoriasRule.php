<?php
declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GeneroPossuiCategoriasRule implements Rule
{
    /** @var array */
    private $categoriasId;

    /** @var array */
    private $generosId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $categoriasId)
    {
        $this->categoriasId = array_unique($categoriasId);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->generosId = array_unique($value);
        if (!count($this->generosId) || !count($this->categoriasId)) {
            return false;
        }

        $categoriasEncontradas = [];
        foreach ($this->generosId as $generoId) {
            $categoriasPorGenero = $this->getCategoriasPorGeneros($generoId);
            if (!$categoriasPorGenero->count()) {
                return false;
            }
            array_push($categoriasEncontradas, ...$categoriasPorGenero->pluck('categoria_id')->toArray());
        }

        if (count($categoriasEncontradas) !== count($this->categoriasId)) {
            return false;
        }

        return true;
    }

    protected function getCategoriasPorGeneros($generoId): Collection
    {
        return DB::table('categoria_genero')
            ->where('genero_id', $generoId)
            ->whereIn('categoria_id', $this->categoriasId)
            ->get();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O genero deve possuir ao menos uma categoria';
    }
}
