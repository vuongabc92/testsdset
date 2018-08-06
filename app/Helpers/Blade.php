<?php

namespace App\Helpers;

use Form;

class Blade {

    public function __construct() {
        Form::component('kingSelect', 'frontend.components.form.select', ['name', 'value', 'default', 'attributes']);
    }
}