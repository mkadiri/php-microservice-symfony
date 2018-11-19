<?php

namespace App\Tests\Service\Validator;

use App\Service\Validator\TitleValidator;
use Exception;
use PHPUnit\Framework\TestCase;

class TitleValidatorTest extends TestCase
{
    public function testInvalidLongTitle()
    {
        $this->expectException(Exception::class);
        (new TitleValidator())->validate(str_repeat('x', 351));
    }


    public function testValidTitle()
    {
        $this->assertNull((new TitleValidator())->validate('this is okay'));
    }
}