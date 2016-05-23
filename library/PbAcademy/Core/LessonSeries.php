<?php

/**
 * Description of LessonSeries
 *
 * @author jfalkenstein
 */
class LessonSeries {
    
    public $Id;
    public $SeriesName;
    public $ImagePath;
    public $Description;
    private $indexIsPublishedOnly;
    private $index;
    private $lessons;
    
    public function __construct($seriesName, $imagePath=null, $Id=null, $description = null) {
        $this->SeriesName = $seriesName;
        $this->ImagePath = $imagePath;
        $this->Id = $Id;
        $this->Description = $description;      
    }
    
    public function LessonCount($publishedOnly = true){
        return count($this->GetLessons($publishedOnly));
    }
    public function GetLessons($publishedOnly = true){
        $mgr = PBAcademyManager::GetInstance();
        if(is_null($this->lessons)){
            $lessons = $mgr->GetRepo()->Lessons->GetLessonsForSeries($this->Id, false);
            $this->sortLessions($lessons);
            $this->lessons = $lessons;
        }
        if($publishedOnly){
            return array_filter($this->lessons, function($val){
                    return $val->Published;             
                }
            );
        }
        return $this->lessons;
    }
    
    private function sortLessions(array &$array){
        usort($array, function($a, $b){
            /* @var $a Lesson */
            /* @var $b Lesson */
            if(isset($a->SeriesOrder) && isset($b->SeriesOrder)){ //If both elements have a series order...
                if($a->SeriesOrder === $b->SeriesOrder){ //If both elements have the SAME series order...
                    if(strtotime($a->DatePublished()) === strtotime($b->DatePublished())){//If both elements have the same date published
                        return 0; //They are equivalent.
                    }
                    return (strtotime($a->DatePublished()) > strtotime($b->DatePublished()))//Later date = later in the list.
                            ? 1 
                            : -1;
                }else{
                    return ($a->SeriesOrder > $b->SeriesOrder) //Greater series order = later in list
                            ? 1
                            : -1;
                }
            }elseif(isset($a->SeriesOrder)){//If a has a series order, but b does not
                return -1;//a should be earlier in the list
            }elseif(isset($b->SeriesOrder)){//If b has a series order, but a does not
                return 1;//a should be later in the liest
            }else{//Both a and b lack a series order
                if(strtotime($a->DatePublished()) === strtotime($b->DatePublished())){//If both elements have the same date published
                    return 0; //They are equivalent.
                }
                return (strtotime($a->DatePublished()) > strtotime($b->DatePublished()))//Later date = later in the list.
                        ? 1 
                        : -1;
            }
        });
    }
    
    private function setIndex($publishedOnly){
        $index = [];
        $this->indexIsPublishedOnly = $publishedOnly;
        foreach ($this->GetLessons($publishedOnly) as $k => $v){
            /* @var $v Lesson */
            $index[$v->Id] = $k;
        }
        $this->index = $index;
    }
    
    private function getIndex($publishedOnly){
        if(is_null($this->index) || $publishedOnly !== $this->indexIsPublishedOnly){
            $this->setIndex($publishedOnly);
        }
        return $this->index;
    }
    
    public function GetLessonPosition(Lesson $lesson, $default = null, $publishedOnly = true){
        if($lesson->Series->Id === $this->Id){
            return $this->getIndex($publishedOnly)[$lesson->Id];
        }else{
            return $default;
        }            
    }
    
    public function GetNextLesson(Lesson $currentLesson, $default = null){
        $pos = $this->GetLessonPosition($currentLesson) + 1;
        if(!is_null($pos) && array_key_exists($pos, $this->lessons)){
            return $this->lessons[$pos];
        }
        return $default;
    }
    public function GetPrevLesson(Lesson $currentLesson, $default = null){
        $pos = $this->GetLessonPosition($currentLesson) - 1;
        if(!is_null($pos) && array_key_exists($pos, $this->lessons)){
            return $this->lessons[$pos];
        }
        return $default;
    }
    
    public function GetLink(){
        return UrlMaker::Series($this->Id);
    }
}
