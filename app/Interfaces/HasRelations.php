<?php


namespace App\Interfaces;


/**
 * Методы интерфеса реализованы в трейте HasRelationsTrait
 * он необходим для лаконичной и удобной подгрузки связей в контроллерах
 *
 * Interface HasRelations
 * @package App\Interfaces
 */
interface HasRelations
{
    /**
     * @return array
     */
    public static function getRelationsList(): array;

    /**
     * @param array|null $relations
     * @return $this
     */
    public function loadRelations(array $relations = null): self;

    /**
     * @return void
     */
    public function removeRelations(): void;
}