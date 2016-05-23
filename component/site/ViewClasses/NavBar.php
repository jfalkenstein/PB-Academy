<?php

/**
 * Description of NavBar
 * @property Category $thisCategory
 * @property Lesson $thisLesson
 * @property LessonSeries $thisSeries
 * @property Lesson $nextLesson 
 * @property Lesson $prevLesson 
 * @author jfalkenstein
 */
class NavBar {
    private $navSectionsPath;
    public $thisCategory;
    public $thisLesson;
    public $thisSeries;
    public $nextLesson;
    public $prevLesson;
    
    private $navBarHtml;
    
    public function __construct(Lesson $thisLesson = null,
                                Category $thisCategory = null,
                                LessonSeries $thisSeries = null
                                ){
        $this->navSectionsPath = __DIR__ . '/../views/sharedViewResources/tmpl/NavBarSections/';
        if($thisLesson){
            $this->thisLesson = $thisLesson;
            $this->thisCategory = $thisLesson->Category;
            $this->thisSeries = $thisLesson->Series;
        }else{
            $this->thisCategory = $thisCategory;
            $this->thisSeries = $thisSeries;
        }
    }
    
    private function getLinkContent($sectionName){
        ob_start();
        include $this->navSectionsPath . $sectionName . '.php';
        return ob_get_clean();
    }
    
    public function MakeNavBar(){
        $this->navBarHtml = '';
        if($this->thisLesson){
            if($this->thisSeries){
                $this->prevLesson = $this->thisSeries->GetPrevLesson($this->thisLesson);
                $this->nextLesson = $this->thisSeries->GetNextLesson($this->thisLesson);
                if($this->prevLesson){
                    $this->addDiv('prevInSeries');
                }
                $this->addDiv('ser');
                if($this->nextLesson){
                    $this->addDiv('nextInSeries');
                }
            }
            $this->addDiv('cat');
        }else if ($this->thisCategory){
            $this->addDiv('allCats');
        }else if($this->thisSeries){
            $this->addDiv('allSeries');
        }
        $this->addDiv('home');
        return $this->navBarHtml;
    }
    
    private function wrapDiv($htmlString){
        return '<div class="navDiv">' . $htmlString . '</div>';
    }
    private function addDiv($sectionName){
        $this->navBarHtml.= $this->wrapDiv($this->getLinkContent($sectionName));
    }
}
