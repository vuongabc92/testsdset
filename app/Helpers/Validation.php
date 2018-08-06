<?php

namespace App\Helpers;

use Validator;

class Validation {
    
    public function __construct() {
        Validator::extend('alpha_spaces', function($attribute, $value, $parameters, $validator) {
            return preg_match('/^[\pL\s]+$/u', $value);
        });
    }
}

