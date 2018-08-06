<?php

namespace App\Models;

class UserProfile extends Base {
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_profile';
    
    /**
     * Get the user profile record associated with the gender.
     */
    public function gender() {
        return $this->belongsTo('App\Models\Gender');
    }
    
    /**
     * Get the marital status record associated with the user.
     */
    public function maritalStatus() {
        return $this->belongsTo('App\Models\MaritalStatus');
    }
    
    /**
     * Get the country record associated with the user.
     */
    public function country() {
        return $this->belongsTo('App\Models\Country');
    }
    
    /**
     * Get the city record associated with the user.
     */
    public function city() {
        return $this->belongsTo('App\Models\City');
    }
    
    /**
     * Get the district record associated with the user.
     */
    public function district() {
        return $this->belongsTo('App\Models\District');
    }
    
    /**
     * Get the expertise record associated with the user.
     */
    public function expertise() {
        return $this->belongsTo('App\Models\Expertise');
    }
    
    /**
     * Get the ward record associated with the user.
     */
    public function ward() {
        return $this->belongsTo('App\Models\Ward');
    }
    
    /**
     * Get the theme record associated with the user.
     */
    public function theme() {
        return $this->belongsTo('App\Models\Theme');
    }
    
    public function avatar($size = 'small') {
        $avatars           = unserialize($this->avatar_image);
        $avatarDefault     = config('frontend.avatarDefault');
        $avatarStoragePath = config('frontend.avatarsFolder');
        $avatarSizes       = config('frontend.avatarSizes');
        
        return isset($avatars[$avatarSizes[$size]['w']]) ? $avatarStoragePath . '/' . $avatars[$avatarSizes[$size]['w']] : $avatarDefault;
    }
    
    public function cover($size = 'small') {
        $cover               = unserialize($this->cover_image);
        $coverDefault        = config('frontend.coverDefault');
        $coverStoragePath    = config('frontend.coversFolder');
        $coverSizes          = config('frontend.coverSizes');
        
        return isset($cover[$coverSizes[$size]['w']])   ? $coverStoragePath . '/' . $cover[$coverSizes[$size]['w']]    : $coverDefault;
    }
    
    public function cvUrl() {
        return route('front_cv', ['slug' => $this->slug]);
    }
}
