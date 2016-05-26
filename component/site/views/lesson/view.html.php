<?php

/**
 * This is the view for a single lesson.
 * @property Lesson $ThisLesson Description
 */
class PbAcademyViewLesson extends BaseViewMaster implements INavBarView
{    
    public $ThisLesson;
    public function display($tpl = null) {
        $problem = false;
        $this->ThisLesson = $this->get('Lesson','lesson');
        /* The view will report a problem with the lesson in the following circumstances:
         * 1. The lesson referenced by the ID in the url does not actually exist, or...
         * 2. The lesson is not published and the user is not a registered user.
         * 
         * Registered users CAN view unpublished lessons.
         */
        if(is_null($this->ThisLesson) || (!$this->ThisLesson->Published && JFactory::getUser()->guest == 1)){
            $problem = true;
        }
        parent::displayModel($problem);
    }
    
    public function setCategories() {
        $this->Categories = $this->get('Categories','lesson');
    }
    public function setPageTitle() {
        $this->PageTitle = $this->ThisLesson->Title;
    }
    public function setSeries() {
        $this->AllSeries = $this->get('Series','lesson');
    }
    public function SetNavBar() {
        $this->NavBar = new NavBar($this->ThisLesson);
    }
    public function PrintNavBar() {
        print $this->NavBar->MakeNavBar();
    }
}
