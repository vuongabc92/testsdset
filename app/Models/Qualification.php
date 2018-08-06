<?php

namespace App\Models;

class Qualification extends Base {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'qualification';
    
    public $timestamps = false;
     
    /**
     * Get the user record associated with the employment.
     */
    public function educations() {
        return $this->hasMany('App\Models\Education');
    }
}
