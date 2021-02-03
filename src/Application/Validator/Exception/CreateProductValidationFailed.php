<?php

namespace App\Application\Validator\Exception;

use App\Application\Validator\ValidationException;

class CreateProductValidationFailed extends \Exception implements ValidationException
{
    /**
     * @var array<string>
     */
    private $errors;

    /**
     * @param array<string> $errors
     */
    public function __construct(array $errors)
    {
        parent::__construct();

        $this->errors = $errors;
    }

    /**
     * @return array<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
