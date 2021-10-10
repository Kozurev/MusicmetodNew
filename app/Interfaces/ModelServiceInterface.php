<?php


namespace App\Interfaces;



use App\Abstracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Override\Models\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ModelServiceInterface
{
    /**
     * ModelServiceInterface constructor.
     * @param Model $model
     * @param Builder $query
     */
    public function __construct(Model $model, Builder $query);

    /**
     * @return Collection
     */
    public function get(): Collection;

    /**
     * @param null $perPage
     * @param array|string[] $columns
     * @param string $pageName
     * @param int|null $page
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = null, array $columns = ['*'], string $pageName = 'page', ?int $page = null): LengthAwarePaginator;

    /**
     * @param QueryFilter $filters
     * @return $this
     */
    public function applyFilters(QueryFilter $filters): self;

    /**
     * @param array $attributes
     * @return $this
     */
    public function create(array $attributes): self;

    /**
     * @param array $attributes
     * @return $this
     */
    public function update(array $attributes): self;

    /**
     *
     */
    public function delete(): void;

    /**
     * @return Model
     */
    public function getModel(): Model;

    /**
     * @return Model
     */
    public function getDefaultModel(): Model;
}
