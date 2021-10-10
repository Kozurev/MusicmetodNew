<?php

namespace App\Traits;

use App\Override\Models\Model;
use App\Scopes\SoftDeletableScope;

/**
 * @extends Model
 * @method static static|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder withTrashed(bool $withTrashed = true)
 * @method static mixed addGlobalScope($scope, \Closure $implementation = null)
 * @method string getDeletedColumn()
 * @method string|int getDeletedValue()
 */
trait SoftDeletes
{
    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new SoftDeletableScope);
    }

    /**
     * Perform the actual delete query on this model instance.
     *
     * @return void
     */
    protected function performDeleteOnModel()
    {
        $query = $this->setKeysForSaveQuery($this->newModelQuery());

        $columns = [$this->getDeletedColumn() => $this->getDeletedValue()];
        $this->{$this->getDeletedColumn()} = $this->getDeletedValue();

        if ($this->timestamps && ! is_null($this->getUpdatedAtColumn())) {
            $time = $this->freshTimestamp();
            $this->{$this->getUpdatedAtColumn()} = $time;
            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }

        $query->update($columns);

        $this->syncOriginalAttributes(array_keys($columns));

        $this->fireModelEvent('deleted', false);
    }
}
