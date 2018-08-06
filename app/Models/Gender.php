<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Base
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'genders';
    
    public $timestamps = false;
}
