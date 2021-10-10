<?php


namespace App\Interfaces;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Interface IFilterable
 * @package App\Http\Filters
 */
interface QueryFilterInterface
{
    public function __construct(Request $request);

    /**
     * @return Collection
     */
    public function getSelectableFields(): Collection;

    /**
     * @return Collection
     */
    public function getFilters(): Collection;

    /**
     * @return Collection
     */
    public function getRelations(): Collection;

    /**
     * @return PaginationInterface
     */
    public function getPagination(): PaginationInterface;

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder;
}
