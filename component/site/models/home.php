<?php

/**
 * The model for the PB academy home.
 */
class PbAcademyModelHome extends BaseModel implements IRecentLessonsModel
{    
    private $recentLessonNum;
    public function __construct($config = array()) {
        //Check the config array for number of recent lessons to display.
        if(isset($config['lessons'])){
            $this->recentLessonNum = $config['lessons'];
        }else{ //If no number is specified, default to 4.
            $this->recentLessonNum = 4;
        }
        parent::__construct($config);
    }
    
    public function getRecentLessons(){
        return $this->pbAcademyManager->GetRecentLessons($this->recentLessonNum);
    }    
}
