<?php

/**
 * ContentTypes determine the shape and content of a Lesson, particularly in how
 * it is embedded within a lesson page.
 */
class ContentType{
    public $Name;
    public $Id;
    
    public function __construct($name, $id){
        $this->Name = $name;
        $this->Id = $id;
    }
}