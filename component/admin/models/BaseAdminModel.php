<?php

require_once PB_ACADEMY_LIB . '/PBAcademyManager.php';

/**
 * This is the base model used by the various admin views. It provides a central
 * access to the PBAcademy manager, as well as access to all categories and all series.
 * @property PBAcademyManager $pbAcademyManager
 */
abstract class BaseAdminModel extends JModelLegacy
{
    public $pbAcademyManager;
    
    /**
     * Populates the PBAcademy manager upon construction.
     * @param type $config
     */
    public function __construct($config = array()) {
        $this->pbAcademyManager = PBAcademyManager::GetInstance();
        parent::__construct($config);
    }
    
    /**
     * Retuns all Categories
     * @return Category[]
     */
    public function getAllCategories(){
        return $this->pbAcademyManager->GetAllCategories();
    }
    
    /**
     * Returns all lesson series, each fully populated with lessons and with 
     * a lesson count property derriving from the method.
     * @return LessonSeries[]
     */
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
