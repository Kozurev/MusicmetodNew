<?php


namespace App\Traits;


/**
 * Реализация интерфейса HasRelations
 *
 * @method load(string $relation)
 *
 * Trait HasRelationsTrait
 * @package App\Traits
 */
trait HasRelationsTrait
{
    /**
     * @return array
     */
    public static function getRelationsList(): array
    {
        return (new self)->relationList ?? [];
    }

    /**
     * @return $this
     */
    public function loadRelations(array $relations = null): self
    {
        $relations = !is_null($relations) ? $relations : self::getRelationsList();
        foreach ($relations as $relationName) {
            $this->load($relationName);
        }
        return $this;
    }

    /**
     *
     */
    public function removeRelations(): void
    {
        foreach (self::getRelationsList() as $relationName) {
            $this->$relationName()->delete();
        }
    }
}