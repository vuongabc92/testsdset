<?php

namespace App\Models;

class Page extends Base {
    
    public $timestamps = false;
    
    public function getContent() {
        $content = unserialize($this->content);
        
        if ( ! is_array($content)) {
            return [];
        }
        
        return $content;
    }
    
    public function getBannerImage() {
        $storagePath = config('backend.page.upload');
        
        return $storagePath . '/' . $this->banner;
    }
 }
