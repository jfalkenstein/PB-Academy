<?php

class Category{
    public $Id;
    public $Name;
    public $Description;
    public $ImagePath;
    private $lessons = [];
    
    public function __construct($name, 
                                $description = null,
                                $id = null,
                                $imagePath = null){
        $this->Name = $name;
        $this->Description = $description;
        $this->Id = $id;
        $this->ImagePath = $imagePath;
    }
    
    public function LessonCount($publishedOnly = true){
        return count($this->GetLessons($publishedOnly));
    }
    
    public function GetLessons($publishedOnly = true){
        if(is_null($this->lessons) || count($this->lessons) === 0){
            $this->loadLessonsFromDb();
        }
        if($publishedOnly){
            return array_filter($this->lessons, function($val){
                    return $val->Published;             
                }
            );
        }
        return $this->lessons;
    }
    
    public function GetRecentLessons($numToGet = 4){
        $mgr = PBAcademyManager::GetInstance();
        return $mgr->GetRepo()->Lessons->GetRecentLessons($numberToGet, $this->Id);
    }
    
    private function loadLessonsFromDb(){
        $mgr = PBAcademyManager::GetInstance();
        $this->lessons = $mgr->GetRepo()->Lessons->GetLessonsForCategory($this->Id, false);
    }
    
    public function GetLink(){
        return UrlMaker::Category($this->Id);
    }
}