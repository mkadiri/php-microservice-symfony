<?php

namespace App\Service\Validator;

use Exception;

class MoneyValidator implements ValidatorInterface
{
    /**
     * @param $value
     * @return false|int
     * @throws Exception
     */
    public function validate($value)
    {
        if (!preg_match("/^-?[0-9]{1,8}+(?:\.[0-9]{1,2})?$/", $value)) {
            throw new Exception("Invalid money value");
        }
    }
}