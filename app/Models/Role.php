<?php

namespace App\Models;

use App\Models\User;

class Role extends Base {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';
    
    public $timestamps = false;
    
    /**
     * Get the user skill record associated with the skill.
     */
    public function users() {
        return $this->hasMany(User::class);
    }
     
}
