<?php

namespace App\Tests\Application\Validator\Exception;

use App\Application\Validator\Exception\CreateProductValidationFailed;
use PHPUnit\Framework\TestCase;

class CreateProductValidationFailedTest extends TestCase
{
    public  function testObject()
    {
        $errorMessages = ['name' => 'Name is required'];
        $exception = new CreateProductValidationFailed($errorMessages);
        $this->assertSame($errorMessages, $exception->getErrors());
    }
}
