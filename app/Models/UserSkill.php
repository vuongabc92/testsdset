<?php

namespace App\Models;

class UserSkill extends Base {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_skills';
    
    public $timestamps = false;
     
    /**
     * Get the user record associated with the user skill.
     */
    public function user() {
        return $this->hasOne('App\Models\User');
    }
    
    /**
     * Get the skill record associated with the user skill.
     */
    public function skill() {
        return $this->belongsTo('App\Models\Skill');
    }
}
