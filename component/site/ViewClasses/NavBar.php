<?php

/**
 * This class controls the navigation bar at the bottom of most pages.
 * This is used in conjunction with the INaveBarView interface (see
 * views/sharedViewResources/ViewInterfaces/INavBarView).
 * @property Category $thisCategory
 * @property Lesson $thisLesson
 * @property LessonSeries $thisSeries
 * @property Lesson $nextLesson 
 * @property Lesson $prevLesson 
 * @author jfalkenstein
 */
class NavBar {
    //The path of the directory where the various navbar section templates are stored.
    private $navSectionsPath = __DIR__ . '/../views/sharedViewResources/tmpl/NavBarSections/';
    
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
        if($thisLesson){//If a lesson is specified...
            $this->thisLesson = $thisLesson; 
            //Set the Category to the lesson's category, ignoring any specified category.
            $this->thisCategory = $thisLesson->Category;
            //Set the Series to the lesson's series (if it exists), ignoring any specified category.
            $this->thisSeries = $thisLesson->Series;
        }else{//If NO lesson is specified...
            //Set the category and series to whatever is passed in as a parameter.
            $this->thisCategory = $thisCategory;
            $this->thisSeries = $thisSeries;
        }
    }

    /**
     * This is the main method for this class. Based upon the parameters specfied
     * at instantiation, the navbar can create the relevant sections. It will return
     * the html for the whole nav bar, to be included wherever the caller might desire it.
     * @return string
     */
    public function MakeNavBar(){
        //Initialize the html as an empty string.
        $this->navBarHtml = '';
        if($this->thisLesson){//If there is a lesson specified...
            if($this->thisSeries){//If the lesson has a series...
                //Get the previous lesson in the series (if there is one).
                $this->prevLesson = $this->thisSeries->GetPrevLesson($this->thisLesson);
                //Get the next lesson in the series (if there is one).
                $this->nextLesson = $this->thisSeries->GetNextLesson($this->thisLesson);
                if($this->prevLesson){//If there is a previous lesson...
                    //Add the nav link for the previous lesson.
                    $this->addDiv('prevInSeries');
                }
                //Add the nav link to the series
                $this->addDiv('ser');
                if($this->nextLesson){//If there is a next lesson...
                    //add the nav link for the next lesson.
                    $this->addDiv('nextInSeries');
                }
            }
            //Add the nav link for the lesson's category
            $this->addDiv('cat');
        }else if ($this->thisCategory){ //If this is NOT a lesson, but a category is specified...
            //Add a link to the all categories page.
            $this->addDiv('allCats');
        }else if($this->thisSeries){//If this is NOT a lesson, but a series is specified...
            //add a link to the all series page.
            $this->addDiv('allSeries');
        }
        //Always add a link to go back to the PB Academy home page.
        $this->addDiv('home');
        //Return the compiled html string.
        return $this->navBarHtml;
    }
    
    /**
     * This will grab the NavBarSection html content from the NavBarSections directory
     * and concatenate it to the navBarHtml string. 
     * @param string $sectionName
     */
    private function addDiv($sectionName){
        $this->navBarHtml.= $this->wrapDiv($this->getLinkContent($sectionName));
    }
    
    /**
     * Wraps the passed in html string in a navDiv and returns it.
     * @param string $htmlString
     * @return string
     */
    private function wrapDiv($htmlString){
        return '<div class="navDiv">' . $htmlString . '</div>';
    }
    
    /**
     * Using output buffering, it includes the specified NavBarSection into a string
     * and then cleans the buffer so it isn't actually output. It then returns that html content.
     * @param string $sectionName
     * @return string
     */
    private function getLinkContent($sectionName){
        ob_start();
        include $this->navSectionsPath . $sectionName . '.php';
        return ob_get_clean();
    }
}
