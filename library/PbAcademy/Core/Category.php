<?php
/**
 * All lessons fall within a category. It was later decided to call categories "schools",
 * So in presentation, categories are called schools. The names are interchangeable in code.
 */
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
    
    /**
     * Will obtain all lessons that exist within this category.
     * @param type $publishedOnly Determines whether it returns ALL lessons, or only
     * those lessons that are published.
     * @return Lesson[]
     */
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
    
    /**
     * Obtains the recent lessons posted within this category. The number obtained
     * defaults to 4, but any number can be obtained.
     * @param int $numToGet
     * @return Lesson[]
     */
    public function GetRecentLessons($numToGet = 4){
        $mgr = PBAcademyManager::GetInstance();
        return $mgr->GetRepo()->Lessons->GetRecentLessons($numberToGet, $this->Id);
    }
    /**
     * Pulls and stores lessons from the db for this category.
     */
    private function loadLessonsFromDb(){
        $mgr = PBAcademyManager::GetInstance();
        $this->lessons = $mgr->GetRepo()->Lessons->GetLessonsForCategory($this->Id, false);
    }
    
    /**
     * Obtains the url to this category's page.
     * @return type
     */
    public function GetLink(){
        return UrlMaker::Category($this->Id);
    }
}