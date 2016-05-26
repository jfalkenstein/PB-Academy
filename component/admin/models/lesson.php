<?php

/**
 * The model for the addEditLesson view.
 * @author jfalkenstein
 */
class PbAcademyModelLesson extends BaseAdminModel{
    
    public $LessonId;
    private $allSeries;
    
    /**
     * On construction, obtains the id for this lesson, if it exists, from the
     * config array (set in the controller);
     * @param type $config
     */
    public function __construct($config = array()) {
        if(isset($config['id'])){
            $this->LessonId = $config['id'];
        }
        parent::__construct($config);
    }
    
    /**
     * Obtains the lesson specified by the Id, if it exists. Otherwise, returns null;
     * The lesson, if it exists, will have a TruePosition property added, populated by the
     * TrueSeriesPosition method.
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
     * Obtains all LessonSeries. This overrides the base model. It avoids recursion
     * by unsetting the series field on each lesson associated with each series.
     * This is used to determine the series positions on the form. It will hold onto
     * this series list to avoid having to redo the array_walk unnecessarily.
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
    
    /**
     * Obtains all content types.
     * @return ContentType[]
     */
    public function getAllContentTypes(){
        return $this->pbAcademyManager->GetContentTypes();
    }
}
