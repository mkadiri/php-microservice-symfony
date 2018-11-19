<?php

namespace App\Tests\Service\Validator;

use App\Service\Validator\MoneyValidator;
use Exception;
use PHPUnit\Framework\TestCase;

class MoneyValidatorTest extends TestCase
{
    public function testInvalidLongDecimalValue()
    {
        $this->expectException(Exception::class);
        (new MoneyValidator())->validate(10128378127387);
    }

    public function testInvalidStringValue()
    {
        $this->expectException(Exception::class);
        (new MoneyValidator())->validate('a-string');
    }

    public function testInvalidMoreThanTwoDecimals()
    {
        $this->expectException(Exception::class);
        (new MoneyValidator())->validate(10.999);
    }

    public function testValidMoney()
    {
        $this->assertNull((new MoneyValidator())->validate(10.50));
    }
}