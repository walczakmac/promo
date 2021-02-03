<?php

namespace App\Application;

interface Validator
{
    /**
     * @param array<string> $data
     */
    public function validate(array $data) : void;

    public function isValid() : bool;

    /**
     * @return array<mixed>
     */
    public function getErrors() : array;
}
