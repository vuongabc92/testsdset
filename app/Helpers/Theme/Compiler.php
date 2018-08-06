<?php

namespace App\Helpers\Theme;

use Illuminate\Filesystem\Filesystem;
use App\Helpers\Theme\Resume;

abstract class Compiler {
    
    /**
     * The Filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    
    /**
     * Get the theme name for the compiled views.
     *
     * @var string
     */
    protected $themeName;
    
    /**
     * Get the resume data for the compiled views.
     *
     * @var \App\Helpers\Theme\Resume
     */
    protected $resume;

    
    
    /**
     * Constructor
     *
     * @param Resume     $resume
     * @param string     $themeName
     */
    public function __construct(Resume $resume, $themeName) {
        $this->files     = new Filesystem();
        $this->themeName = $themeName;
        $this->resume    = $resume;
    }
    
    /**
     * Set the path currently being compiled.
     *
     * @param  string  $themeName
     * @return void
     */
    public function setThemeName($themeName) {
        $this->themeName = $themeName;
    }
    
    /**
     * Get the path currently being compiled.
     *
     * @return string
     */
    public function getThemeName() {
        return $this->themeName;
    }
}