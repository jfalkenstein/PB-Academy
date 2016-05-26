<?php

/**
 * The model for the lesson views.
 */
class PbAcademyModelLesson extends BaseModel
{    
    private $lessonId;
    private $ThisLesson;
    public function __construct($config = array()) {
        $this->lessonId = $config['lessonId'];
        parent::__construct($config);
    }
    
    /**
     * This will obtain the specified lesson, identified by the given Id.
     * @return Lesson
     */
    public function getLesson(){
        if(!isset($this->ThisLesson)){
            $this->ThisLesson = $this->pbAcademyManager->GetLesson($this->lessonId);
        }
        return $this->ThisLesson;
    }   
}
