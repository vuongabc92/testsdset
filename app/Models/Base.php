<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Base extends Model {
    
    public function createdAtFormat( $format ) {
        $createdAt = new \DateTime($this->created_at);
        
        return $createdAt->format($format);
    }
    
    public function updatedAtFormat( $format ) {
        $updatedAt = new \DateTime($this->updated_at);
        
        return $updatedAt->format($format);
    }
}
