<?php

require_once PB_ACADEMY_LIB . '/PBAcademyManager.php';

/**
 * 
 * @property PBAcademyManager $pbAcademyManager
 */
abstract class BaseAdminModel extends JModelLegacy
{
    public $pbAcademyManager;
    
    public function __construct($config = array()) {
        $this->pbAcademyManager = PBAcademyManager::GetInstance();
        parent::__construct($config);
    }
    
    public function getAllCategories(){
        return $this->pbAcademyManager->GetAllCategories();
    }
    
    public function getAllSeries(){
        $allSeries = $this->pbAcademyManager->GetAllSeries();
        array_walk($allSeries, function(&$item){
            /* @var $item LessonSeries */
            $item->Lessons = $item->GetLessons(false);
            $item->LessonsCount = $item->LessonCount(false);
        });
        return $allSeries;
    }
    
}
