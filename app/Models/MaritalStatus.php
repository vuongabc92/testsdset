<?php

namespace App\Models;

class MaritalStatus extends Base {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'marital_statuses';
    
    public $timestamps = false;
     
    /**
     * Get the user record associated with the employment.
     */
    public function users() {
        return $this->hasMany('App\Models\Users');
    }
}
