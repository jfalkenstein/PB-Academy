<?php

/**
 * Description of lesson
 *
 * @author jfalkenstein
 */
class PbAcademyModelLesson extends BaseAdminModel{
    
    public $LessonId;
    private $allSeries;
    
    public function __construct($config = array()) {
        if(isset($config['id'])){
            $this->LessonId = $config['id'];
        }
        parent::__construct($config);
    }
    
    /**
     * 
     * @return Lesson
     */
    public function getLesson(){
        if(isset($this->LessonId)){
            $lesson = $this->pbAcademyManager->GetLesson($this->LessonId);
            if(!is_null($lesson)){
                $lesson->TruePosition = $lesson->TrueSeriesPosition(false);
            }
            return $lesson;
        }else{
            return null;
        }
    }
    
    /**
     * 
     * @return []LessonSeries
     */
    public function getAllSeries() {
        if(!isset($this->allSeries)){
            $allSeries = parent::getAllSeries();
            array_walk($allSeries, function(&$series){
                /* @var $item LessonSeries */
                array_walk($series->Lessons,function(&$lesson){
                    /* @var $lesson Lesson */
                    unset($lesson->Series);
                }); 
            });
            $this->allSeries = $allSeries;
        }
        return $this->allSeries;
    }
    
    public function getAllContentTypes(){
        return $this->pbAcademyManager->GetContentTypes();
    }
}
