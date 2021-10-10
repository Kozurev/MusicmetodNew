<?php


namespace App\Traits;


use App\Abstracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Filterable
 * @package App\Traits
 */
trait Filterable
{
    /**
     * @param Builder $builder
     * @param QueryFilter $filter
     */
    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }

}
