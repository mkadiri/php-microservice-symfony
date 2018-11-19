<?php

namespace App\Service\Validator;

use Exception;

interface ValidatorInterface
{
    /**
     * @param $value
     * @return mixed
     * @throws Exception
     */
    public function validate($value);
}