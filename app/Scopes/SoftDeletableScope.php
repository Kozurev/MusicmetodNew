<?php

namespace App\Scopes;

use App\Interfaces\SoftDeletable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SoftDeletableScope implements Scope
{
    /**
     * All of the extensions to be added to the builder.
     *
     * @var array|string[]
     */
    protected array $extensions = ['WithTrashed'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  Builder  $builder
     * @param  Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        if ($model instanceof SoftDeletable) {
            $builder->where($model->getDeletedColumn(), '<>', $model->getDeletedValue());
        }
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  Builder  $builder
     * @return void
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }

        $builder->onDelete(function (Builder $builder) {
            return $builder->update([
                $this->getDeletedColumn($builder) => $this->getDeletedValue($builder)
            ]);
        });
    }

    /**
     * Add the with-trashed extension to the builder.
     *
     * @param  Builder  $builder
     * @return void
     */
    protected function addWithTrashed(Builder $builder)
    {
        $builder->macro('withTrashed', function (Builder $builder, $withTrashed = true) {
            if (! $withTrashed) {
                return $builder->withoutTrashed();
            }

            return $builder->withoutGlobalScope($this);
        });
    }

    /**
     * Get the "deleted at" column for the builder.
     *
     * @param  Builder  $builder
     * @return string|null
     */
    protected function getDeletedColumn(Builder $builder): ?string
    {
        $model = $builder->getModel();
        return $model instanceof SoftDeletable
            ?   $model->getDeletedColumn()
            :   null;
    }

    /**
     * @param Builder $builder
     * @return int|string|null
     */
    public function getDeletedValue(Builder $builder): int|string|null
    {
        $model = $builder->getModel();
        return $model instanceof SoftDeletable
            ?   $model->getDeletedValue()
            :   null;
    }

}
