<?php

namespace App\Models;

class Education extends Base {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'education';
    
    public $timestamps = false;
     
    /**
     * Get the user record associated with the education.
     */
    public function user() {
        return $this->hasOne('App\Models\User');
    }
    
    /**
     * Get the qualification record associated with the education.
     */
    public function qualification() {
        return $this->belongsTo('App\Models\Qualification');
    }
}
