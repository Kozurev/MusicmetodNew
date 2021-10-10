<?php


namespace App\Abstracts;


use App\Interfaces\ModelServiceInterface;
use App\Interfaces\ValidatableService;
use App\Traits\Validatable;
use Illuminate\Database\Eloquent\Builder;
use App\Override\Models\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class Service
 * @package App\Abstracts
 */
abstract class Service implements ModelServiceInterface, ValidatableService
{
    use Validatable;

    const DEFAULT_PER_PAGE = 25;

    /**
     * @var Model
     */
    private Model $model;

    /**
     * @var Builder
     */
    private Builder $query;

    /**
     * Service constructor.
     * @param Model|null $model
     * @param Builder|null $query
     */
    public function __construct(?Model $model = null, ?Builder $query = null)
    {
        if (is_null($model)) {
            $model = $this->getDefaultModel();
        }
        if (is_null($query)) {
            $query = $model->newQuery();
        }
        $this->setModel($model);
        $this->setQuery($query);
    }

    /**
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->query->get();
    }

    /**
     * @param int $perPage
     * @param string[] $columns
     * @param string $pageName
     * @param int|null $page
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = self::DEFAULT_PER_PAGE, array $columns = ['*'], string $pageName = 'page', ?int $page = null): LengthAwarePaginator
    {
        return $this->query->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * @param QueryFilter $filters
     * @return $this
     */
    public function applyFilters(QueryFilter $filters): self
    {
        $this->query = $filters->apply($this->query);
        return $this;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param Model $model
     * @return $this
     */
    public function setModel(Model $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @param Builder $query
     */
    public function setQuery(Builder $query): void
    {
        $this->query = $query;
    }

    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }

    /**
     * @return Builder
     */
    public function getClearQuery(): Builder
    {
        return $this->getModel()->newQuery();
    }

    /**
     * @param array $attributes
     * @return ModelServiceInterface
     * @throws \Throwable
     */
    public function create(array $attributes): ModelServiceInterface
    {
        try {
            DB::beginTransaction();
            $model = $this->getModel()->fill($attributes);
            $model->save();
            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollback();
            throw $throwable;
        }

        if ($model instanceof Model) {
            $this->setModel($model);
        } else {
            throw new \Exception('Can\'t create new instance');
        }
        return $this;
    }

    /**
     * @param array $attributes
     * @return ModelServiceInterface
     * @throws \Throwable
     */
    public function update(array $attributes): ModelServiceInterface
    {
        try {
            DB::beginTransaction();
            if ($this->needValidate()) {
                $this->validateOrFail($attributes, $this->updateRules());
            }
            $this->getModel()->fill($attributes);
            $this->getModel()->saveOrFail();
            DB::commit();
        } catch(\Throwable $throwable) {
            DB::rollback();
            throw $throwable;
        }

        return $this;
    }

    /**
     * @throws \Throwable
     */
    public function delete(): void
    {
        $this->getModel()->delete();
    }

}
