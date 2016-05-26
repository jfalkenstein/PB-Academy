<?php

/**
 * A lesson may, or may not, exist within a series. Lessons in a series have a specific
 * position within the series. This position is determined by the Lessons's seriesOrder
 * property, but its position is ULTIMATELY decided by the series itself using its sorting
 * algorithms.
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
    
    /**
     * This obtains the lessons for this series. However, beyond simple retrieval,
     * this method also sorts them into their rightful position.
     * @param bool $publishedOnly Determines whether it includes unpublished lessons.
     * @return Lesson[]
     */
    public function GetLessons($publishedOnly = true){
        $mgr = PBAcademyManager::GetInstance();
        if(is_null($this->lessons)){ //If there is currently no store of lessons...
            //Get the lessons from the DB
            $lessons = $mgr->GetRepo()->Lessons->GetLessonsForSeries($this->Id, false);
            //Sort the lessons inot thier rightful order
            $this->sortLessions($lessons);
            //Store these lessons.
            $this->lessons = $lessons;
        }
        if($publishedOnly){//If $publishedOnly is true...
            //Filter out unpublished lessons and then return the filtered list.
            return array_filter($this->lessons, function($val){
                    return $val->Published;             
                }
            );
        }
        return $this->lessons;
    }
    
    /**
     * Sorts Lessons first by seriesOrder and then by datePublished.
     * @param array $array
     */
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
    
    /**
     * Creates an index for lessons. The index key is the Id of the lesson.
     * The value is the position of the lesson in the series.
     * @param bool $publishedOnly determines whether or not to include unpublished lessons.
     */
    private function setIndex($publishedOnly){
        $index = [];
        $this->indexIsPublishedOnly = $publishedOnly;
        foreach ($this->GetLessons($publishedOnly) as $k => $v){
            /* @var $v Lesson */
            $index[$v->Id] = $k;
        }
        $this->index = $index;
    }
    
    /**
     * Obtains the lesson index.
     * @param bool $publishedOnly Whether or not the index should be for all or only published lessons.
     * @return Lesson[]
     */
    private function getIndex($publishedOnly){
        if(is_null($this->index) || $publishedOnly !== $this->indexIsPublishedOnly){
            $this->setIndex($publishedOnly);
        }
        return $this->index;
    }
    
    /**
     * Gets the true position of a lesson in the series.
     * @param Lesson $lesson
     * @param value $default This is the value that should be returned if the lesson ISN'T in this series.
     * @param type $publishedOnly Whether or not to include unpublished lessons.
     * @return int
     */
    public function GetLessonPosition(Lesson $lesson, $default = null, $publishedOnly = true){
        if($lesson->Series->Id === $this->Id){
            return $this->getIndex($publishedOnly)[$lesson->Id];
        }else{
            return $default;
        }            
    }
    
    /**
     * Obtains the next lesson in the series from the lesson passed in.
     * @param Lesson $currentLesson
     * @param value $default The value to be returned if there is no next lesson.
     * @return type
     */
    public function GetNextLesson(Lesson $currentLesson, $default = null){
        $pos = $this->GetLessonPosition($currentLesson) + 1;
        if(!is_null($pos) && array_key_exists($pos, $this->lessons)){
            return $this->lessons[$pos];
        }
        return $default;
    }
    
    /**
     * Obtains the previous lesson in the series from the lesson passed in.
     * @param Lesson $currentLesson
     * @param value $default The value to be returned if there is no previous lesson.
     * @return type
     */
    public function GetPrevLesson(Lesson $currentLesson, $default = null){
        $pos = $this->GetLessonPosition($currentLesson) - 1;
        if(!is_null($pos) && array_key_exists($pos, $this->lessons)){
            return $this->lessons[$pos];
        }
        return $default;
    }
    
    /**
     * Obtains the link to this series.
     * @return string
     */
    public function GetLink(){
        return UrlMaker::Series($this->Id);
    }
}
