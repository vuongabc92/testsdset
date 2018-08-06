<?php

namespace App\Models;

class Country extends Base
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';

    public $timestamps = false;

    /**
     * Get districts
     *
     * @return App\Models\District
     */
    public function cities()
    {
        return $this->hasMany('App\Models\City');
    }

}
