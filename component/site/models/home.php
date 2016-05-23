<?php

class PbAcademyModelHome extends BaseModel implements IRecentLessonsModel
{    
    private $recentLessonNum;
    public function __construct($config = array()) {
        if(isset($config['lessons'])){
            $this->recentLessonNum = $config['lessons'];
        }else{
            $this->recentLessonNum = 4;
        }
        parent::__construct($config);
    }
    
    public function getRecentLessons(){
        return $this->pbAcademyManager->GetRecentLessons($this->recentLessonNum);
    }    
}
