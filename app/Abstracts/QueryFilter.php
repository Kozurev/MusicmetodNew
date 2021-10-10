<?php


namespace App\Abstracts;


use App\DefaultClasses\Pagination;
use App\Interfaces\PaginationInterface;
use App\Interfaces\QueryFilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class QueryFilter
 * @package App\Http\Filters
 */
abstract class QueryFilter implements QueryFilterInterface
{
    const PARAM_SELECTABLE_FIELDS = 'fields';
    const PARAM_FILTERS = 'filters';
    const PARAM_RELATIONS = 'relations';
    const PARAM_PAGINATION = 'paginate';
    const PARAM_PAGINATE_PAGE = 'page';
    const PARAM_PAGINATE_ON_PAGE = 'perpage';
    const PARAM_ORDER = 'order';

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var Builder|null
     */
    private ?Builder $builder;

    /**
     * @var Collection
     */
    private Collection $fields;

    /**
     * @var Collection
     */
    private Collection $filters;

    /**
     * @var PaginationInterface
     */
    private PaginationInterface $pagination;

    /**
     * @var Collection
     */
    private Collection $order;

    /**
     * @var Collection
     */
    private Collection $relations;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->setSelectableFields($request);
        $this->setFilters($request);
        $this->setRelations($request);
        $this->setPagination($request);
        $this->setOrder($request);
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Builder|null
     */
    protected function getBuilder(): ?Builder
    {
        return $this->builder;
    }

    /**
     * @param Request $request
     */
    protected function setFilters(Request $request): void
    {
        $this->filters = collect(
            array_map(function($filter) {
                if (!is_array($filter)) {
                    $filter = trim($filter);
                }
                return $filter;
            }, (array)$request->input(self::PARAM_FILTERS, []))
        );
    }

    /**
     * @return Collection
     */
    public function getFilters(): Collection
    {
        return $this->filters;
    }

    /**
     * @param Request $request
     */
    protected function setRelations(Request $request): void
    {
        $this->relations = collect(
            array_filter(
                array_map('trim', (array)$request->input(self::PARAM_RELATIONS, []))
            )
        );
    }

    /**
     * @param string $relation
     * @return $this
     */
    public function appendRelation(string $relation): self
    {
        $this->relations->add($relation);
        return $this;
    }

    /**
     * @param array $relations
     * @return $this
     */
    public function appendRelations(array $relations): self
    {
        foreach ($relations as $relation) {
            $this->appendRelation($relation);
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getRelations(): Collection
    {
        return $this->relations;
    }

    /**
     * @param Request $request
     */
    protected function setSelectableFields(Request $request)
    {
        $this->fields = collect(
            array_filter(
                array_map('trim', (array)$request->input(self::PARAM_SELECTABLE_FIELDS, []))
            )
        );
    }

    /**
     * @param string $field
     * @return $this
     */
    public function appendSelectableField(string $field): self
    {
        $this->fields->add($field);
        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function appendSelectableFields(array $fields): self
    {
        foreach ($fields as $field) {
            $this->appendSelectableField($field);
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getSelectableFields(): Collection
    {
        return $this->fields;
    }

    /**
     * @return Collection
     */
    public function getOrder(): Collection
    {
        return $this->order;
    }

    /**
     * @param Request $request
     */
    protected function setPagination(Request $request)
    {
        $paginationData = collect(
            array_filter(
                array_map('trim', (array)$request->input(self::PARAM_PAGINATION, []))
            )
        );
        $this->pagination = new Pagination(
            (int)$paginationData->get(self::PARAM_PAGINATE_PAGE, 1),
            (int)$paginationData->get(self::PARAM_PAGINATE_ON_PAGE, 10),
        );
    }

    /**
     * @param Request $request
     */
    protected function setOrder(Request $request)
    {
        $this->order = collect();
        if ($request->has(self::PARAM_ORDER)) {
            foreach ($request->input(self::PARAM_ORDER) as $column => $order) {
                $this->order->put($column, $order);
            }
        }
    }

    /**
     * @return PaginationInterface
     */
    public function getPagination(): PaginationInterface
    {
        return $this->pagination;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        if ($this->getSelectableFields()->isNotEmpty()) {
            $this->getBuilder()->select($this->getSelectableFields()->toArray());
        }

        foreach ($this->getRelations() as $relation) {
            $this->getBuilder()->with($relation);
        }

        if ($this->getOrder()->isNotEmpty()) {
            foreach ($this->getOrder() as $column => $order) {
                $this->getBuilder()->orderBy($column, $order);
            }
        }

        foreach ($this->getFilters() as $field => $value) {
            $method = self::toCamelCase($field);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
        return $this->getBuilder();
    }

    /**
     * @param string $convertingString
     * @param string $delimiter
     * @return string
     */
    protected static function toCamelCase(string $convertingString, string $delimiter = '_'): string
    {
        $return = '';
        $words = explode($delimiter, $convertingString);
        foreach ($words as $word) {
            $return .= ucfirst($word);
        }
        return lcfirst($return);
    }

    /**
     * @param string $date
     * @param string $outputFormat
     * @return string
     */
    public function parseDate(string $date, string $outputFormat = 'Y-m-d H:i:s'): string
    {
        return Carbon::parse($date)->format($outputFormat);
    }
}
