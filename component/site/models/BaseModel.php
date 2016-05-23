<?php

require_once PB_ACADEMY_LIB . '/PBAcademyManager.php';
require_once __DIR__ . '/ModelInterfaces/IRecentLessonsModel.php';

/**
 * This is the base model for all other models. It provides a shared level of functionality
 * that all models make use of, especially as used by the base view and template.
 * 
 * @property PBAcademyManager $pbAcademyManager This is the single instance of PBAcademyManager
 * shared by all models.
 */
abstract class BaseModel extends JModelLegacy {
    /* @var $pbAcademyManager PBAcademyManager */
    public $pbAcademyManager;
    
    public function __construct($config = array()) {
        $this->pbAcademyManager = PBAcademyManager::GetInstance();
        parent::__construct($config);
    }
    
    /**
     * This will obtain a list of all categories from the db, filtering out any 
     * categories with no lessons. Then it will sort them FIRST by number of 
     * lessons (descending) and SECOND, by name.
     * @return []_category
     */
    public function getCategories(){
        $cats = array_filter(
                $this->pbAcademyManager->GetAllCategories(),
                function($cat){
            /* @var $cat Category */
                    return($cat->lessonCount() > 0);
                });
        uasort($cats, function($a, $b){
        /* @var $a Category */
        /* @var $b Category */
            if($a->LessonCount() == $b->LessonCount()){
                return strcmp($a->Name, $b->Name);
            }
            return ($a->LessonCount() < $b->LessonCount()) ? 1 : -1;
        });
        return $cats;
    }
    
    /**
     * This will obtain a list of all series from the db, filtering out any 
     * series with no lessons. Then it will sort them FIRST by number of lessons 
     * (descending), and SECOND, by name.
     * @return []LessonSeries
     */
    public function getSeries(){
        $allSeries = $this->pbAcademyManager->GetAllSeries();
        $series = array_filter(
                $this->pbAcademyManager->GetAllSeries(),
                function($ser){
                    /* @var $ser LessonSeries */
                    return ($ser->LessonCount() > 0);
                });
        uasort($series, function($a, $b){
            /* @var $a LessonSeries */
            /* @var $b LessonSeries */
            if($a->LessonCount() == $b->LessonCount()){
                return strcmp($a->SeriesName, $b->SeriesName);
            }
            return ($a->LessonCount() < $b->LessonCount()) ? 1 : -1;
        });
        return $series;
    }
}
