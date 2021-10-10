<?php


namespace App\Traits;

use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

/**
 * Trait for ValidatableService interface
 *
 * Trait Validatable
 * @package App\Traits
 */
trait Validatable
{
    /**
     * @var Validator|null
     */
    protected ?Validator $validator = null;

    /**
     * @param array $values
     * @return array
     */
    public function createRules(array $values = []): array
    {
        return [];
    }

    /**
     * @param array $values
     * @return array
     */
    public function updateRules(array $values = []): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function validateMessages(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function needValidate(): bool
    {
        return true;
    }

    /**
     * @param array $attributes
     * @param array $rules
     * @param array|null $messages
     * @return bool
     */
    public function isValid(array $attributes, array $rules, ?array $messages = null): bool
    {
        if (is_null($messages)) {
            $messages = $this->validateMessages();
        }
        $this->validator = ValidatorFacade::make($attributes, $rules, $messages);
        return !$this->validator->fails();
    }

    /**
     * @param array $attributes
     * @param array $rules
     * @param array|null $messages
     * @throws ValidationException
     */
    public function validateOrFail(array $attributes, array $rules, ?array $messages = null): void
    {
        if (!$this->isValid($attributes, $rules, $messages)) {
            throw ValidationException::withMessages($this->validator->errors()->toArray());
        }
    }
}