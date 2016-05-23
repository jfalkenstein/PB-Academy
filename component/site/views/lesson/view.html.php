<?php

/**
 * @property Lesson $ThisLesson Description
 */
class PbAcademyViewLesson extends BaseViewMaster implements INavBarView
{    
    public $ThisLesson;
    public function display($tpl = null) {
        $problem = false;
        $this->ThisLesson = $this->get('Lesson','lesson');
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
