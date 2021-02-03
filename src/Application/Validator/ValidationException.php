<?php

namespace App\Application\Validator;

interface ValidationException extends \Throwable
{
    /**
     * @return array<string>
     */
    public function getErrors() : array;
}
