<?php

namespace App\Models;

class Skill extends Base {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'skills';
    
    public $timestamps = false;
    
    /**
     * Get the user skill record associated with the skill.
     */
    public function userSkills() {
        return $this->hasMany('App\Models\UserSkill');
    }
     
}
