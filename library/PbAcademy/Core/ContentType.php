<?php

class ContentType{
    public $Name;
    public $Id;
    
    public function __construct($name, $id){
        $this->Name = $name;
        $this->Id = $id;
    }
}