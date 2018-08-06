<?php

namespace App\Models;

class City extends Base
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cities';

    public $timestamps = false;

    /**
     * Get districts
     *
     * @return App\Models\District
     */
    public function districts()
    {
        return $this->hasMany('App\Models\District');
    }

    /**
     * Get stores
     *
     * @return App\Models\Store
     */
    public function stores()
    {
        return $this->hasMany('App\Models\Store');
    }
    
    /**
     * Get the user profile record associated with the user.
     */
    public function country() {
        return $this->hasOne('App\Models\Country');
    }
}
