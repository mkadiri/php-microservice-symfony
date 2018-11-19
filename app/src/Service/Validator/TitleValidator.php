<?php

namespace App\Service\Validator;

use Exception;

class TitleValidator implements ValidatorInterface
{
    public function validate($value)
    {
        if (strlen($value) > 255) {
            throw new Exception("Invalid Title");
        }
    }
}