<?php

namespace App\Models;

class EmploymentHistory extends Base {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'employment_histories';
    
    public $timestamps = false;
     
    /**
     * Get the user record associated with the employment.
     */
    public function user() {
        return $this->hasOne('App\Models\User');
    }
}
