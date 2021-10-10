<?php


namespace App\Interfaces;


use Illuminate\Validation\ValidationException;

/**
 * Interface for validate models inside services
 *
 * Interface ValidatableService
 * @package App\Interfaces
 */
interface ValidatableService
{
    /**
     * @param array $values
     * @return array
     */
    public function createRules(array $values = []): array;

    /**
     * @param array $values
     * @return array
     */
    public function updateRules(array $values = []): array;

    /**
     * @return array
     */
    public function validateMessages(): array;

    /**
     * @return bool
     */
    public function needValidate(): bool;

    /**
     * @param array $attributes
     * @param array $rules
     * @param array|null $messages
     * @return bool
     */
    public function isValid(array $attributes, array $rules, ?array $messages = null): bool;

    /**
     * @param array $attributes
     * @param array $rules
     * @param array|null $messages
     * @throws ValidationException
     */
    public function validateOrFail(array $attributes, array $rules, ?array $messages = null): void;

}